<?php
require_once __DIR__ . '/includes/functions.php';
$events = db()->query('SELECT * FROM events WHERE event_date >= CURDATE() ORDER BY event_date')->fetchAll();
$pageTitle = 'Events';
include __DIR__ . '/includes/header.php';
?>
<section class="page-hero"><div class="container"><p class="eyebrow">Campus calendar</p><h1>Meet, learn, take part.</h1><p>Upcoming events and activities from across the college.</p></div></section><section class="content-section container"><div class="event-grid"><?php foreach ($events as $event): ?><article class="event-card"><?php if ($event['image']): ?><img src="<?= e(url($event['image'])) ?>" alt="<?= e($event['title']) ?>" loading="lazy"><?php endif; ?><div><p class="eyebrow"><?= e(date('d M Y', strtotime($event['event_date']))) ?><?= $event['event_time'] ? ' · ' . e(substr($event['event_time'], 0, 5)) : '' ?></p><h3><?= e($event['title']) ?></h3><p><?= e($event['location']) ?></p><p><?= e($event['description']) ?></p></div></article><?php endforeach; ?><?php if (!$events): ?><p class="empty-state">No upcoming events are listed right now.</p><?php endif; ?></div></section>
<?php include __DIR__ . '/includes/footer.php'; ?>