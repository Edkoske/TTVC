<?php
require_once __DIR__ . '/../config/auth.php';
require_admin(['Super Admin', 'Admissions Officer']);

$statuses = ['Submitted', 'Under Review', 'Shortlisted', 'Accepted', 'Rejected', 'Completed'];
$perPage = 50;
$q = trim((string)($_GET['q'] ?? ''));
$statusFilter = trim((string)($_GET['status'] ?? ''));
$statusFilter = in_array($statusFilter, $statuses, true) ? $statusFilter : '';
$page = max(1, filter_input(INPUT_GET, 'page', FILTER_VALIDATE_INT) ?: 1);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT) ?: 0;
    $status = (string)($_POST['status'] ?? '');
    $returnQuery = [
        'q' => trim((string)($_POST['q'] ?? '')),
        'status' => (string)($_POST['status_filter'] ?? ''),
        'page' => max(1, (int)($_POST['page'] ?? 1)),
    ];
    $returnQuery = array_filter($returnQuery, static fn($value): bool => $value !== '' && $value !== 1);
    if ($id < 1 || !in_array($status, $statuses, true)) {
        flash('error', 'Choose a valid application and status.');
        redirect('admin/applications.php' . ($returnQuery ? '?' . http_build_query($returnQuery) : ''));
    }

    $pdo = db();
    $pdo->beginTransaction();
    try {
        $lookup = $pdo->prepare('SELECT status,application_number,user_id,email FROM applications WHERE id=? FOR UPDATE');
        $lookup->execute([$id]);
        $application = $lookup->fetch();
        if (!$application) {
            $pdo->rollBack();
            flash('error', 'That application could not be found.');
            redirect('admin/applications.php' . ($returnQuery ? '?' . http_build_query($returnQuery) : ''));
        }

        $notes = trim((string)($_POST['admin_notes'] ?? ''));
        $pdo->prepare('UPDATE applications SET status=?,admin_notes=? WHERE id=?')->execute([$status, $notes, $id]);
        if ($application['status'] !== $status) {
            $userId = $application['user_id'];
            if (!$userId) {
                $user = $pdo->prepare('SELECT id FROM users WHERE email=? AND role="student" LIMIT 1');
                $user->execute([$application['email']]);
                $userId = $user->fetchColumn() ?: null;
            }
            if ($userId) {
                $message = 'Your application ' . $application['application_number'] . ' is now ' . $status . '.';
                if ($status === 'Accepted') {
                    $message .= ' Sign in to your student portal to view and print your admission letter.';
                }
                $pdo->prepare('INSERT INTO notifications (user_id,application_id,title,message) VALUES (?,?,?,?)')
                    ->execute([$userId, $id, 'Application status updated', $message]);
            }
        }
        $pdo->commit();
        flash('success', 'Application review saved.');
    } catch (Throwable $exception) {
        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }
        throw $exception;
    }
    redirect('admin/applications.php' . ($returnQuery ? '?' . http_build_query($returnQuery) : ''));
}

$where = [];
$params = [];
if ($q !== '') {
    $where[] = '(a.application_number LIKE ? OR a.full_name LIKE ? OR a.email LIKE ? OR a.phone LIKE ? OR a.id_number LIKE ?)';
    $term = '%' . $q . '%';
    array_push($params, $term, $term, $term, $term, $term);
}
if ($statusFilter !== '') {
    $where[] = 'a.status=?';
    $params[] = $statusFilter;
}
$whereSql = $where ? ' WHERE ' . implode(' AND ', $where) : '';
$countStmt = db()->prepare('SELECT COUNT(*) FROM applications a' . $whereSql);
$countStmt->execute($params);
$totalApplications = (int)$countStmt->fetchColumn();
$totalPages = max(1, (int)ceil($totalApplications / $perPage));
$page = min($page, $totalPages);
$offset = ($page - 1) * $perPage;
$stmt = db()->prepare(
    'SELECT a.*,c.name AS course,c.level,c.duration,d.name AS department
     FROM applications a
     JOIN courses c ON c.id=a.course_id
     JOIN departments d ON d.id=c.department_id' . $whereSql . '
     ORDER BY a.created_at DESC,a.id DESC
     LIMIT ' . $perPage . ' OFFSET ' . $offset
);
$stmt->execute($params);
$applications = $stmt->fetchAll();

$documentsByApplication = [];
if ($applications) {
    $applicationIds = array_map(static fn(array $application): int => (int)$application['id'], $applications);
    $placeholders = implode(',', array_fill(0, count($applicationIds), '?'));
    $documents = db()->prepare('SELECT id,application_id,document_type,uploaded_at FROM application_documents WHERE application_id IN (' . $placeholders . ') ORDER BY uploaded_at');
    $documents->execute($applicationIds);
    foreach ($documents->fetchAll() as $document) {
        $documentsByApplication[(int)$document['application_id']][] = $document;
    }
}

$pageTitle = 'Manage applications';
include __DIR__ . '/../includes/header.php';
?>
<section class="admin-shell">
  <aside class="admin-sidebar">
    <a href="<?= e(url('admin/dashboard.php')) ?>">← Dashboard</a>
    <a href="<?= e(url('admin/logout.php')) ?>">Log out</a>
  </aside>
  <div class="admin-content">
    <p class="eyebrow">Admissions</p>
    <h1>Applications</h1>
    <?php include __DIR__ . '/../includes/alerts.php'; ?>

    <form class="search-row" method="get">
      <input class="search-field" name="q" placeholder="Search name, email, phone, ID or application number" value="<?= e($q) ?>">
      <select class="search-field" name="status" aria-label="Filter by status">
        <option value="">All statuses</option>
        <?php foreach ($statuses as $status): ?>
          <option value="<?= e($status) ?>" <?= $statusFilter === $status ? 'selected' : '' ?>><?= e($status) ?></option>
        <?php endforeach; ?>
      </select>
      <button class="button">Filter</button>
    </form>

    <p class="body-copy">
      <?= $totalApplications ?> application<?= $totalApplications === 1 ? '' : 's' ?>
      <?php if ($totalApplications > 0): ?>
        · Showing <?= $offset + 1 ?>–<?= min($offset + $perPage, $totalApplications) ?>
      <?php endif; ?>
    </p>

    <div class="table-wrap">
      <table>
        <thead>
          <tr>
            <th>Application</th>
            <th>Applicant / course</th>
            <th>Review decision and internal notes</th>
            <th>Documents / admission</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($applications as $application): ?>
            <?php $applicationId = (int)$application['id']; ?>
            <tr>
              <td>
                <?= e($application['application_number']) ?><br>
                <?= e(date('d M Y', strtotime($application['created_at']))) ?>
                <details>
                  <summary>Applicant details</summary>
                  <div class="application-review-details">
                    <p><strong>Gender:</strong> <?= e($application['gender']) ?> · <strong>Date of birth:</strong> <?= e(date('d M Y', strtotime($application['date_of_birth']))) ?></p>
                    <p><strong>ID / passport:</strong> <?= e($application['id_number']) ?></p>
                    <p><strong>Phone:</strong> <?= e($application['phone']) ?> · <strong>Email:</strong> <?= e($application['email']) ?></p>
                    <p><strong>Location:</strong> <?= e($application['county']) ?><?= $application['sub_county'] ? ', ' . e($application['sub_county']) : '' ?><?= $application['postal_address'] ? ' · ' . e($application['postal_address']) : '' ?></p>
                    <p><strong>KCSE index:</strong> <?= e($application['kcse_index'] ?: 'Not provided') ?> · <strong>Year:</strong> <?= (int)$application['kcse_year'] ?> · <strong>Grade:</strong> <?= e($application['grade']) ?></p>
                    <p><strong>Previous institution:</strong> <?= e($application['previous_institution'] ?: 'Not provided') ?></p>
                    <p><strong>Highest qualification:</strong> <?= e($application['highest_qualification'] ?: 'Not provided') ?></p>
                    <p><strong>Programme:</strong> <?= e($application['course']) ?> (<?= e($application['level']) ?>, <?= e($application['duration']) ?>)</p>
                    <p><strong>Department:</strong> <?= e($application['department']) ?> · <strong>Intake:</strong> <?= e($application['intake']) ?></p>
                    <p><strong>Last updated:</strong> <?= e(date('d M Y H:i', strtotime($application['updated_at']))) ?></p>
                  </div>
                </details>
              </td>
              <td>
                <?= e($application['full_name']) ?><br>
                <?= e($application['email']) ?><br>
                <?= e($application['course']) ?><br>
                <span class="badge"><?= e($application['status']) ?></span>
              </td>
              <td>
                <form method="post" action="<?= e(url('admin/applications.php')) ?>">
                  <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">
                  <input type="hidden" name="id" value="<?= $applicationId ?>">
                  <input type="hidden" name="q" value="<?= e($q) ?>">
                  <input type="hidden" name="status_filter" value="<?= e($statusFilter) ?>">
                  <input type="hidden" name="page" value="<?= $page ?>">
                  <label for="status-<?= $applicationId ?>">Review status</label>
                  <select id="status-<?= $applicationId ?>" name="status" required>
                    <?php foreach ($statuses as $status): ?>
                      <option value="<?= e($status) ?>" <?= $application['status'] === $status ? 'selected' : '' ?>><?= e($status) ?></option>
                    <?php endforeach; ?>
                  </select>
                  <label for="notes-<?= $applicationId ?>">Internal review notes</label>
                  <textarea id="notes-<?= $applicationId ?>" name="admin_notes" placeholder="Record the review outcome or follow-up needed. These notes are staff-only." rows="3"><?= e($application['admin_notes']) ?></textarea>
                  <button class="button button-small">Save review</button>
                </form>
              </td>
              <td>
                <?php $applicationDocuments = $documentsByApplication[$applicationId] ?? []; ?>
                <?php if ($applicationDocuments): ?>
                  <?php foreach ($applicationDocuments as $document): ?>
                    <a class="text-link" href="<?= e(url('admin/document.php?id=' . $applicationId . '&type=' . rawurlencode($document['document_type']))) ?>">
                      <?= e(ucfirst($document['document_type'])) ?> ↓
                    </a><br>
                  <?php endforeach; ?>
                <?php else: ?>
                  <p>No documents submitted.</p>
                <?php endif; ?>
                <?php if ($application['status'] === 'Accepted'): ?>
                  <a class="text-link" href="<?= e(url('admin/admission.php?id=' . $applicationId)) ?>">Generate admission letter ↓</a>
                <?php endif; ?>
              </td>
            </tr>
          <?php endforeach; ?>
          <?php if (!$applications): ?>
            <tr><td colspan="4">No applications match these filters.</td></tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>

    <?php if ($totalPages > 1): ?>
      <nav aria-label="Application pages" class="quick-actions" style="margin-top:20px">
        <?php if ($page > 1): ?>
          <a class="button button-outline" href="<?= e(url('admin/applications.php?' . http_build_query(array_filter(['q' => $q, 'status' => $statusFilter, 'page' => $page - 1], static fn($value): bool => $value !== '' && $value !== 1)))) ?>">Previous</a>
        <?php endif; ?>
        <span>Page <?= $page ?> of <?= $totalPages ?></span>
        <?php if ($page < $totalPages): ?>
          <a class="button button-outline" href="<?= e(url('admin/applications.php?' . http_build_query(array_filter(['q' => $q, 'status' => $statusFilter, 'page' => $page + 1], static fn($value): bool => $value !== '' && $value !== 1)))) ?>">Next</a>
        <?php endif; ?>
      </nav>
    <?php endif; ?>
  </div>
</section>
<?php include __DIR__ . '/../includes/footer.php'; ?>
