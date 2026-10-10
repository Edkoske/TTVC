<?php
http_response_code(404);
require_once __DIR__ . '/includes/functions.php';
$pageTitle = 'Page not found';
include __DIR__ . '/includes/header.php';
?><section class="content-section container"><div class="content-panel"><p class="eyebrow">404 · Not found</p><h1>We couldn’t find that page.</h1><p>It may have moved, or the link may be out of date.</p><a class="button" href="<?= e(url('index.php')) ?>">Return home <span>→</span></a></div></section><?php include __DIR__ . '/includes/footer.php'; ?>