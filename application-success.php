<?php
require_once __DIR__ . '/includes/functions.php';
$number = (string)($_SESSION['submitted_application'] ?? '');
if ($number === '') redirect('apply.php');
$stmt = db()->prepare('SELECT a.*, c.name AS course_name FROM applications a JOIN courses c ON c.id = a.course_id WHERE a.application_number = ?');
$stmt->execute([$number]);
$application = $stmt->fetch();
$pageTitle = 'Application submitted';
include __DIR__ . '/includes/header.php';
?>
<section class="content-section container"><div class="content-panel"><p class="eyebrow">Application received</p><h1>Application submitted successfully.</h1><p>Thank you, <?= e($application['full_name'] ?? '') ?>. Keep this application number to check your progress.</p><div class="message"><strong>Application number: <?= e($number) ?></strong><br>Course: <?= e($application['course_name'] ?? '') ?><br>Status: Submitted<br>Submitted: <?= e(date('d F Y', strtotime($application['created_at'] ?? 'now'))) ?></div><div class="form-actions"><a class="button" href="<?= e(url('application-status.php?number=' . rawurlencode($number))) ?>">Check status <span>→</span></a><button class="button button-outline" onclick="window.print()" type="button">Print acknowledgement</button></div></div></section>
<?php include __DIR__ . '/includes/footer.php'; ?>