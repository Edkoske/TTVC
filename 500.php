<?php
http_response_code(500);
require_once __DIR__ . '/includes/functions.php';
$pageTitle = 'Service unavailable';
include __DIR__ . '/includes/header.php';
?><section class="content-section container"><div class="content-panel"><p class="eyebrow">500 · Service unavailable</p><h1>We’re unable to complete that request.</h1><p>Please try again later or contact the college for assistance.</p><a class="button" href="<?= e(url('index.php')) ?>">Return home</a></div></section><?php include __DIR__ . '/includes/footer.php'; ?>