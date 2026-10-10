<?php
require_once __DIR__ . '/includes/functions.php';
$pageTitle = 'Portals';
include __DIR__ . '/includes/header.php';
?>
<section class="page-hero"><div class="container"><p class="eyebrow">Online services</p><h1>TTVC portals.</h1><p>Quick access to applications, student services and college resources.</p></div></section>
<section class="content-section container"><div class="portal-grid"><article class="portal-card"><h3>Student portal</h3><p>View your application and student information.</p><a class="text-link" href="<?= e(url('login.php')) ?>">Login <span>→</span></a></article><article class="portal-card"><h3>Staff portal</h3><p>Access protected college management tools.</p><a class="text-link" href="<?= e(url('admin/login.php')) ?>">Login <span>→</span></a></article><article class="portal-card"><h3>E-learning</h3><p>Online learning services for enrolled students.</p><a class="text-link" href="<?= e(setting('elearning_url', 'https://moodle.org')) ?>" rel="noopener">Access <span>↗</span></a></article><article class="portal-card"><h3>Online application</h3><p>Start an application for your preferred course.</p><a class="text-link" href="<?= e(url('apply.php')) ?>">Apply now <span>→</span></a></article></div></section>
<?php include __DIR__ . '/includes/footer.php'; ?>