<?php
require_once __DIR__ . '/includes/functions.php';
$vacancies = db()->query('SELECT * FROM vacancies WHERE status = "published" AND deadline >= CURDATE() ORDER BY deadline')->fetchAll();
$pageTitle = 'Careers and vacancies';
include __DIR__ . '/includes/header.php';
?>
<section class="page-hero"><div class="container"><p class="eyebrow">Work with us</p><h1>Careers at TTVC.</h1><p>Help prepare the next generation of skilled professionals.</p></div></section><section class="content-section container"><div class="result-list"><?php foreach ($vacancies as $vacancy): ?><article class="result-row"><div><h3><?= e($vacancy['title']) ?></h3><p><?= e($vacancy['department']) ?> · Deadline <?= e(date('d M Y', strtotime($vacancy['deadline']))) ?></p><p><?= e($vacancy['description']) ?></p><p><strong>Requirements:</strong> <?= e($vacancy['requirements']) ?></p><p><?= e($vacancy['instructions']) ?></p></div><?php if ($vacancy['file_path']): ?><a class="text-link" href="<?= e(url($vacancy['file_path'])) ?>">Job details <span>↓</span></a><?php endif; ?></article><?php endforeach; ?><?php if (!$vacancies): ?><p class="empty-state">There are no open vacancies at this time.</p><?php endif; ?></div></section>
<?php include __DIR__ . '/includes/footer.php'; ?>