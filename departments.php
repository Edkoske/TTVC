<?php
require_once __DIR__ . '/includes/functions.php';
$departments = db()->query('SELECT d.id, d.name, d.description, COUNT(c.id) AS course_count FROM departments d LEFT JOIN courses c ON c.department_id = d.id AND c.status = "published" WHERE d.status = "active" GROUP BY d.id ORDER BY d.name')->fetchAll();
$pageTitle = 'Departments';
include __DIR__ . '/includes/header.php';
?>
<section class="page-hero"><div class="container"><p class="eyebrow">Areas of study</p><h1>Departments that<br>build capability.</h1><p>Explore practical learning across our academic and technical departments.</p></div></section><section class="content-section container"><div class="event-grid"><?php foreach ($departments as $item): ?><article class="portal-card"><p class="eyebrow"><?= (int)$item['course_count'] ?> courses</p><h3><?= e($item['name']) ?></h3><p><?= e($item['description']) ?></p><a class="text-link" href="<?= e(url('courses.php?department=' . $item['id'])) ?>">View courses <span>→</span></a></article><?php endforeach; ?></div></section>
<?php include __DIR__ . '/includes/footer.php'; ?>