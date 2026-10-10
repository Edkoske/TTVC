<?php
require_once __DIR__ . '/includes/functions.php';
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT) ?: 0;
$slug = trim((string)($_GET['slug'] ?? ''));
$stmt = $slug !== ''
	? db()->prepare('SELECT * FROM news WHERE slug = ? AND status = "published" LIMIT 1')
	: db()->prepare('SELECT * FROM news WHERE id = ? AND status = "published" LIMIT 1');
$stmt->execute([$slug !== '' ? $slug : $id]);
$article = $stmt->fetch();
if (!$article) { http_response_code(404); require __DIR__ . '/404.php'; exit; }
$pageTitle = $article['title'];
include __DIR__ . '/includes/header.php';
?>
<section class="page-hero"><div class="container"><p class="eyebrow"><?= e($article['category']) ?> · <?= e(date('d F Y', strtotime($article['published_at']))) ?></p><h1><?= e($article['title']) ?></h1></div></section><section class="content-section container"><article class="prose"><img src="<?= e(url($article['image'] ?: 'https://images.unsplash.com/photo-1581092334651-ddf26d9a09d0?auto=format&fit=crop&w=900&q=80')) ?>" alt="<?= e($article['title']) ?>" style="width:100%;max-height:440px;object-fit:cover"><p><?= nl2br(e($article['content'])) ?></p><p>By <?= e($article['author'] ?: 'TTVC Communications') ?></p></article></section>
<?php include __DIR__ . '/includes/footer.php'; ?>