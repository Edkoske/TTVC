<?php
http_response_code(403);
require_once __DIR__ . '/includes/functions.php';
$pageTitle = 'Access denied';
include __DIR__ . '/includes/header.php';
?><section class="content-section container"><div class="content-panel"><p class="eyebrow">403 · Access denied</p><h1>You don’t have access to this page.</h1><p>Please sign in with an authorized account or return to the public site.</p><a class="button" href="<?= e(url('index.php')) ?>">Return home</a></div></section><?php include __DIR__ . '/includes/footer.php'; ?>