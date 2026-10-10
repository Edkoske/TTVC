<?php
require_once __DIR__ . '/includes/functions.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    $stmt = db()->prepare('SELECT id, full_name, password_hash FROM users WHERE email = ? AND role = "student" AND status = "active" LIMIT 1');
    $stmt->execute([filter_var($_POST['email'] ?? '', FILTER_VALIDATE_EMAIL) ?: '']);
    $user = $stmt->fetch();
    if ($user && password_verify((string)($_POST['password'] ?? ''), $user['password_hash'])) {
        session_regenerate_id(true);
        $_SESSION['student_id'] = (int)$user['id'];
        $_SESSION['student_name'] = $user['full_name'];
        $_SESSION['last_activity'] = time();
        redirect('student/dashboard.php');
    }
    flash('error', 'Email or password was not recognized.');
}
$pageTitle = 'Student login';
include __DIR__ . '/includes/header.php';
?>
<section class="content-section container"><div class="login-box"><p class="eyebrow">Student services</p><h1>Sign in</h1><?php include __DIR__ . '/includes/alerts.php'; ?><form class="form-panel" method="post"><input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>"><div class="field"><label for="email">Email address</label><input id="email" name="email" type="email" required autocomplete="username"></div><div class="field" style="margin-top:16px"><label for="password">Password</label><input id="password" name="password" type="password" required autocomplete="current-password"></div><div class="form-actions"><button class="button">Sign in</button></div><p>New applicant? <a class="text-link" href="<?= e(url('register.php')) ?>">Create an account →</a></p></form></div></section>
<?php include __DIR__ . '/includes/footer.php'; ?>