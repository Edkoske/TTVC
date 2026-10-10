<?php
require_once __DIR__ . '/includes/functions.php';
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT) ?: 0;
$stmt = db()->prepare('SELECT c.*, d.name AS department FROM courses c JOIN departments d ON d.id = c.department_id WHERE c.id = ? AND c.status = "published" LIMIT 1');
$stmt->execute([$id]);
$course = $stmt->fetch();
if (!$course) { http_response_code(404); require __DIR__ . '/404.php'; exit; }
$pageTitle = $course['name'];
include __DIR__ . '/includes/header.php';
?>
<section class="page-hero"><div class="container"><p class="eyebrow"><?= e($course['department']) ?> · <?= e($course['level']) ?></p><h1><?= e($course['name']) ?>.</h1><p><?= e($course['duration']) ?> · Practical, competency-based learning at TTVC.</p></div></section><section class="content-section container"><div class="detail-layout"><div class="detail-copy"><h2>About this course</h2><p><?= nl2br(e($course['description'])) ?></p><h2>Entry requirements</h2><p><?= nl2br(e($course['entry_requirements'])) ?></p><h2>Modules</h2><p><?= nl2br(e($course['modules'] ?: 'Programme modules are available from the admissions office.')) ?></p><h2>Career opportunities</h2><p><?= nl2br(e($course['careers'] ?: 'Graduates can pursue careers across the relevant industry.')) ?></p></div><aside class="detail-aside"><p class="eyebrow">Programme details</p><h3><?= e($course['level']) ?></h3><p><strong>Department</strong><br><?= e($course['department']) ?></p><p><strong>Duration</strong><br><?= e($course['duration']) ?></p><p><strong>Fees</strong><br><?= e($course['fees'] ?: 'Contact admissions for current fee guidance.') ?></p><p><strong>Funding</strong><br><?= e($course['funding'] ?: 'Funding information available from the admissions office.') ?></p><a class="button" href="<?= e(url('apply.php')) ?>">Apply for this course <span>↗</span></a></aside></div></section>
<?php include __DIR__ . '/includes/footer.php'; ?>