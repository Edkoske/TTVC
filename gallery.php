<?php
require_once __DIR__ . '/includes/functions.php';
$images = db()->query('SELECT title, category, image_path FROM gallery WHERE is_published = 1 ORDER BY created_at DESC')->fetchAll();
$pageTitle = 'Campus gallery';
include __DIR__ . '/includes/header.php';
?>
<section class="page-hero"><div class="container"><p class="eyebrow">Life at TTVC</p><h1>Learning in action.</h1><p>A look at the people, spaces and experiences that make our community.</p></div></section><section class="content-section container"><div class="news-grid"><?php foreach ($images as $image): ?><article class="news-card"><a href="<?= e(url($image['image_path'])) ?>" data-lightbox><img src="<?= e(url($image['image_path'])) ?>" alt="<?= e($image['title']) ?>" loading="lazy" style="width:100%;height:220px;object-fit:cover"><p class="eyebrow"><?= e($image['category']) ?></p><h3><?= e($image['title']) ?></h3></a></article><?php endforeach; ?><?php if (!$images): ?><p class="empty-state">Gallery images will appear here.</p><?php endif; ?></div></section>
<?php include __DIR__ . '/includes/footer.php'; ?>