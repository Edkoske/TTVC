<?php
require_once __DIR__ . '/includes/functions.php';
$articles = db()->query('SELECT id, title, slug, excerpt, image, category, published_at FROM news WHERE status = "published" ORDER BY published_at DESC')->fetchAll();
$pageTitle = 'News and stories';
include __DIR__ . '/includes/header.php';
?>
<section class="page-hero"><div class="container"><p class="eyebrow">College updates</p><h1>News and stories.</h1><p>News, ideas and moments from the TTVC community.</p></div></section><section class="content-section container"><div class="news-grid"><?php foreach ($articles as $item): ?><article class="news-card"><a href="<?= e(url('news-details.php?slug=' . rawurlencode($item['slug']))) ?>"><div class="news-image" style="background-image:url('<?= e(url($item['image'] ?: 'https://images.unsplash.com/photo-1581092334651-ddf26d9a09d0?auto=format&fit=crop&w=900&q=80')) ?>')"></div><p class="eyebrow"><?= e($item['category']) ?> · <?= e(date('d M Y', strtotime($item['published_at']))) ?></p><h3><?= e($item['title']) ?></h3><p><?= e($item['excerpt']) ?></p><span class="text-link">Read story <span>→</span></span></a></article><?php endforeach; ?><?php if (!$articles): ?><p class="empty-state">No published news at the moment.</p><?php endif; ?></div></section>
<?php include __DIR__ . '/includes/footer.php'; ?>