<?php
require_once __DIR__ . '/includes/functions.php';
$application = null;
$error = '';
$number = trim((string)($_GET['number'] ?? $_POST['application_number'] ?? ''));
if ($_SERVER['REQUEST_METHOD'] === 'POST') verify_csrf();
if ($number !== '') {
    $stmt = db()->prepare('SELECT a.application_number, a.full_name, a.status, a.created_at, c.name AS course_name, d.name AS department FROM applications a JOIN courses c ON c.id = a.course_id JOIN departments d ON d.id = c.department_id WHERE a.application_number = ? LIMIT 1');
    $stmt->execute([$number]);
    $application = $stmt->fetch();
    if (!$application) $error = 'No application was found with that number.';
}
$pageTitle = 'Application status';
include __DIR__ . '/includes/header.php';
?>
<section class="page-hero"><div class="container"><p class="eyebrow">Admissions</p><h1>Track your application.</h1><p>Enter the application number provided after submission.</p></div></section><section class="content-section container"><form class="form-panel" method="post"><input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>"><div class="field"><label for="application_number">Application number</label><input id="application_number" name="application_number" placeholder="TTVC/2026/000123" required value="<?= e($number) ?>"></div><div class="form-actions"><button class="button">Check status <span>→</span></button></div></form><?php if ($error): ?><div class="alert error" style="margin-top:20px"><?= e($error) ?></div><?php endif; ?><?php if ($application): ?><div class="content-panel" style="margin-top:20px"><p class="eyebrow">Application record</p><h2><?= e($application['application_number']) ?></h2><p><strong>Course:</strong> <?= e($application['course_name']) ?><br><strong>Department:</strong> <?= e($application['department']) ?><br><strong>Status:</strong> <span class="badge"><?= e($application['status']) ?></span><br><strong>Submitted:</strong> <?= e(date('d F Y', strtotime($application['created_at']))) ?></p><?php if ($application['status'] === 'Accepted'): ?><p>Your application has been accepted. Sign in or create a student portal account using the email address on your application to view and print your admission letter.</p><div class="quick-actions"><a class="button" href="<?= e(url('login.php')) ?>">Student sign in</a><a class="button button-outline" href="<?= e(url('register.php')) ?>">Create student account</a></div><?php endif; ?></div><?php endif; ?></section>
<?php include __DIR__ . '/includes/footer.php'; ?>