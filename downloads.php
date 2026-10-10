<?php
require_once __DIR__ . '/includes/functions.php';
$category = trim((string)($_GET['category'] ?? ''));
$sql = 'SELECT id, title, description, category, file_path, download_count, created_at FROM downloads WHERE is_public = 1';
$params = [];
if ($category !== '') { $sql .= ' AND category = ?'; $params[] = $category; }
$sql .= ' ORDER BY created_at DESC';
$stmt = db()->prepare($sql); $stmt->execute($params); $items = $stmt->fetchAll();
$categories = db()->query('SELECT DISTINCT category FROM downloads WHERE is_public = 1 ORDER BY category')->fetchAll(PDO::FETCH_COLUMN);
$pageTitle = 'Downloads and resources';
include __DIR__ . '/includes/header.php';
?>
<section class="page-hero"><div class="container"><p class="eyebrow">Student resources</p><h1>Useful documents,<br>all in one place.</h1><p>Download admission information, forms and college resources.</p></div></section><section class="content-section container"><form class="search-row"><select class="search-field" name="category" aria-label="Filter by category"><option value="">All categories</option><?php foreach ($categories as $item): ?><option <?= $category === $item ? 'selected' : '' ?>><?= e($item) ?></option><?php endforeach; ?></select><button class="button">Filter</button></form><div class="result-list"><?php foreach ($items as $item): ?><article class="result-row"><div><h3><?= e($item['title']) ?></h3><p><?= e($item['category']) ?> · <?= e($item['description']) ?></p></div><a class="text-link" href="<?= e(url('download.php?id=' . $item['id'])) ?>">Download <span>↓</span></a></article><?php endforeach; ?><?php if (!$items): ?><p class="empty-state">No resources match this category.</p><?php endif; ?></div></section>
<?php include __DIR__ . '/includes/footer.php'; ?>