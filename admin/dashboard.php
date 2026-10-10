<?php
require_once __DIR__ . '/../config/auth.php';
require_admin();
$canViewAdmissions = in_array($_SESSION['admin_role'] ?? '', ['Super Admin', 'Admissions Officer'], true);
$stats = [
 'Courses' => (int)db()->query('SELECT COUNT(*) FROM courses')->fetchColumn(),
 'News articles' => (int)db()->query('SELECT COUNT(*) FROM news')->fetchColumn(),
];
$recent = [];
$statusCounts = [];
if ($canViewAdmissions) {
 $stats = ['Applications' => (int)db()->query('SELECT COUNT(*) FROM applications')->fetchColumn(),
  'Pending review' => (int)db()->query('SELECT COUNT(*) FROM applications WHERE status IN ("Submitted","Under Review")')->fetchColumn(),
  'Accepted' => (int)db()->query('SELECT COUNT(*) FROM applications WHERE status = "Accepted"')->fetchColumn(),
  'Rejected' => (int)db()->query('SELECT COUNT(*) FROM applications WHERE status = "Rejected"')->fetchColumn(),
  'Courses' => $stats['Courses'],
  'Students' => (int)db()->query('SELECT COUNT(*) FROM users WHERE role = "student"')->fetchColumn(),
  'News articles' => $stats['News articles'],
  'Enquiries' => (int)db()->query('SELECT COUNT(*) FROM enquiries WHERE status = "new"')->fetchColumn()];
 $recent = db()->query('SELECT a.application_number,a.full_name,a.status,a.created_at,c.name AS course FROM applications a JOIN courses c ON c.id=a.course_id ORDER BY a.created_at DESC LIMIT 8')->fetchAll();
 $statusCounts = db()->query('SELECT status, COUNT(*) AS total FROM applications GROUP BY status ORDER BY total DESC')->fetchAll();
}
$statusMax = max(array_column($statusCounts, 'total') ?: [1]);
$pageTitle = 'Admin dashboard';
include __DIR__ . '/../includes/header.php';
$nav = ['Dashboard'=>'dashboard.php','Applications'=>'applications.php','Students'=>'students.php','Courses'=>'courses.php','Departments'=>'departments.php','News'=>'news.php','Events'=>'events.php','Gallery'=>'gallery.php','Downloads'=>'downloads.php','Staff'=>'staff.php','Enquiries'=>'enquiries.php','FAQs'=>'faqs.php','Notifications'=>'notifications.php','Vacancies'=>'vacancies.php','Facilities'=>'facilities.php','Users'=>'users.php','Settings'=>'settings.php'];
?><section class="admin-shell"><aside class="admin-sidebar"><p class="eyebrow" style="color:#e4bd5b">TTVC ADMIN</p><?php foreach ($nav as $label=>$path): ?><a href="<?= e(url('admin/' . $path)) ?>"><?= e($label) ?></a><?php endforeach; ?><a href="<?= e(url('admin/logout.php')) ?>">Log out</a></aside><div class="admin-content"><div class="admin-header"><div><p class="eyebrow">Administration</p><h1>Welcome, <?= e($_SESSION['admin_name']) ?></h1></div><div class="quick-actions"><?php if ($canViewAdmissions): ?><a class="button button-small" href="<?= e(url('admin/applications.php')) ?>">Review applications</a><?php endif; ?><a class="button button-small button-outline" href="<?= e(url('admin/settings.php')) ?>">Manage settings</a></div></div><p class="admin-role"><?= e($_SESSION['admin_role']) ?></p><div class="metric-grid"><?php foreach ($stats as $label=>$count): ?><div class="metric"><strong><?= $count ?></strong><span><?= e($label) ?></span></div><?php endforeach; ?></div><?php if ($canViewAdmissions): ?><div class="dashboard-panels"><div class="summary-card"><h2>Application status</h2><div class="chart-list"><?php foreach ($statusCounts as $status): ?><div class="chart-row"><strong><?= e($status['status']) ?></strong><div class="chart-track"><div class="chart-fill" style="width:<?= (int)round(((int)$status['total'] / max(1, (int)$statusMax)) * 100) ?>%"></div></div><span><?= (int)$status['total'] ?></span></div><?php endforeach; ?></div></div><div class="summary-card"><h2>Operational snapshot</h2><ul class="status-list"><li><span>Admissions queue</span><strong><?= (int)db()->query('SELECT COUNT(*) FROM applications WHERE status IN ("Submitted","Under Review")')->fetchColumn(); ?></strong></li><li><span>New enquiries</span><strong><?= (int)db()->query('SELECT COUNT(*) FROM enquiries WHERE status = "new"')->fetchColumn(); ?></strong></li><li><span>Active students</span><strong><?= (int)db()->query('SELECT COUNT(*) FROM users WHERE role = "student"')->fetchColumn(); ?></strong></li><li><span>Published courses</span><strong><?= (int)db()->query('SELECT COUNT(*) FROM courses WHERE status = "published"')->fetchColumn(); ?></strong></li></ul></div></div><h2>Recent applications</h2><div class="table-wrap"><table><thead><tr><th>Number</th><th>Applicant</th><th>Course</th><th>Status</th><th>Submitted</th></tr></thead><tbody><?php foreach ($recent as $row): ?><tr><td><?= e($row['application_number']) ?></td><td><?= e($row['full_name']) ?></td><td><?= e($row['course']) ?></td><td><span class="badge"><?= e($row['status']) ?></span></td><td><?= e(date('d M Y',strtotime($row['created_at']))) ?></td></tr><?php endforeach; ?></tbody></table></div><?php endif; ?></div></section><?php include __DIR__ . '/../includes/footer.php'; ?>