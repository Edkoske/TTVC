<?php
require_once __DIR__ . '/includes/functions.php';
$facilities = db()->query('SELECT name, description, image FROM facilities WHERE is_published = 1 ORDER BY sort_order, name')->fetchAll();
$pageTitle = 'Facilities';
include __DIR__ . '/includes/header.php';
?>
<section class="page-hero"><div class="container"><p class="eyebrow">Our learning environment</p><h1>Spaces to explore<br>and make things.</h1><p>Practical learning needs the right tools, spaces and support.</p></div></section><section class="content-section container"><div class="facility-grid"><?php foreach ($facilities as $facility): ?><article class="facility-card"><img src="<?= e(url($facility['image'] ?: 'https://images.unsplash.com/photo-1581092335878-2d9ff86ca2bf?auto=format&fit=crop&w=900&q=80')) ?>" alt="<?= e($facility['name']) ?>" loading="lazy"><div><h3><?= e($facility['name']) ?></h3><p><?= e($facility['description']) ?></p></div></article><?php endforeach; ?></div></section>
<?php include __DIR__ . '/includes/footer.php'; ?>