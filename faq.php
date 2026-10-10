<?php
require_once __DIR__ . '/includes/functions.php';
$faqs = db()->query('SELECT question, answer FROM faqs WHERE is_published = 1 ORDER BY sort_order, id')->fetchAll();
$pageTitle = 'Frequently asked questions';
include __DIR__ . '/includes/header.php';
?>
<section class="page-hero"><div class="container"><p class="eyebrow">Need a hand?</p><h1>Frequently asked<br>questions.</h1><p>Quick answers to common questions about courses and admissions.</p></div></section><section class="content-section container"><div class="content-panel"><?php foreach ($faqs as $item): ?><article class="faq-item"><h3><?= e($item['question']) ?></h3><p><?= e($item['answer']) ?></p></article><?php endforeach; ?><?php if (!$faqs): ?><p class="empty-state">FAQs will be published here soon.</p><?php endif; ?></div></section>
<?php include __DIR__ . '/includes/footer.php'; ?>