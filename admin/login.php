<?php
require_once __DIR__ . '/../includes/functions.php';
if (!empty($_SESSION['admin_id'])) redirect('admin/dashboard.php');
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    $lockoutUntil = (int)($_SESSION['admin_lockout_until'] ?? 0);
    if ($lockoutUntil > time()) {
        $minutes = (int)ceil(($lockoutUntil - time()) / 60);
        $error = 'Too many attempts. Please wait ' . $minutes . ' minute' . ($minutes === 1 ? '' : 's') . ' and try again.';
    } else {
        unset($_SESSION['admin_lockout_until']);
        if ((int)($_SESSION['admin_attempts'] ?? 0) > 8) {
            unset($_SESSION['admin_attempts']);
        }
        $stmt = db()->prepare('SELECT id, full_name, email, password_hash, role, force_password_change FROM admins WHERE email = ? AND status = "active" LIMIT 1');
        $stmt->execute([filter_var($_POST['email'] ?? '', FILTER_VALIDATE_EMAIL) ?: '']);
        $admin = $stmt->fetch();
        if ($admin && password_verify((string)($_POST['password'] ?? ''), $admin['password_hash'])) {
            session_regenerate_id(true);
            $_SESSION['admin_id'] = (int)$admin['id'];
            $_SESSION['admin_name'] = $admin['full_name'];
            $_SESSION['admin_role'] = $admin['role'];
            $_SESSION['last_activity'] = time();
            unset($_SESSION['admin_attempts']);
            db()->prepare('UPDATE admins SET last_login = NOW() WHERE id = ?')->execute([$admin['id']]);
            if ($admin['force_password_change']) redirect('admin/settings.php?password=1');
            redirect('admin/dashboard.php');
        }
        $_SESSION['admin_attempts'] = (int)($_SESSION['admin_attempts'] ?? 0) + 1;
        if ($_SESSION['admin_attempts'] >= 8) {
            $_SESSION['admin_lockout_until'] = time() + 900;
            $error = 'Too many attempts. Please wait 15 minutes and try again.';
        } else {
            $error = 'Email or password was not recognized.';
        }
    }
}
$pageTitle = 'Staff login';
include __DIR__ . '/../includes/header.php';
?><section class="content-section container"><div class="login-box"><p class="eyebrow">Staff administration</p><h1>Administrator sign in</h1><?php if ($error): ?><div class="alert error"><?= e($error) ?></div><?php endif; ?><form class="form-panel" method="post"><input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>"><div class="field"><label>Email address</label><input name="email" type="email" required autocomplete="username"></div><div class="field" style="margin-top:16px"><label>Password</label><input name="password" type="password" required autocomplete="current-password"></div><div class="form-actions"><button class="button">Sign in</button></div><p><a class="text-link" href="<?= e(url('admin/forgot-password.php')) ?>">Forgot your password?</a></p><p>Demo: admin@ttvc.ac.ke · ChangeMe-TTVC-2026! (change on first sign in)</p></form></div></section><?php include __DIR__ . '/../includes/footer.php'; ?>