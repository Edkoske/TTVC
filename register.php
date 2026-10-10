<?php
require_once __DIR__ . '/includes/functions.php';
$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    $name = trim((string)($_POST['full_name'] ?? ''));
    $email = filter_var($_POST['email'] ?? '', FILTER_VALIDATE_EMAIL);
    $password = (string)($_POST['password'] ?? '');
    if (mb_strlen($name) < 3) $errors[] = 'Enter your full name.';
    if (!$email) $errors[] = 'Enter a valid email address.';
    if (strlen($password) < 10) $errors[] = 'Password must be at least 10 characters.';
    if (!$errors) {
        try {
            $stmt = db()->prepare('INSERT INTO users (full_name, email, password_hash, role, status) VALUES (?, ?, ?, "student", "active")');
            $stmt->execute([$name, $email, password_hash($password, PASSWORD_DEFAULT)]);
            session_regenerate_id(true);
            $_SESSION['student_id'] = (int)db()->lastInsertId();
            db()->prepare('INSERT INTO students (user_id) VALUES (?)')->execute([$_SESSION['student_id']]);
            $_SESSION['student_name'] = $name;
            $_SESSION['last_activity'] = time();
            redirect('student/dashboard.php');
        } catch (PDOException) {
            $errors[] = 'That email may already have an account.';
        }
    }
}
$pageTitle = 'Create student account';
include __DIR__ . '/includes/header.php';
?>
<section class="content-section container"><div class="login-box"><p class="eyebrow">Student services</p><h1>Create account</h1><?php foreach ($errors as $error): ?><div class="alert error"><?= e($error) ?></div><?php endforeach; ?><form class="form-panel" method="post"><input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>"><div class="field"><label for="full_name">Full name</label><input id="full_name" name="full_name" required maxlength="120" value="<?= e($_POST['full_name'] ?? '') ?>"></div><div class="field" style="margin-top:16px"><label for="email">Email address</label><input id="email" name="email" type="email" required autocomplete="email" value="<?= e($_POST['email'] ?? '') ?>"></div><div class="field" style="margin-top:16px"><label for="password">Password (10 characters minimum)</label><input id="password" name="password" type="password" required minlength="10" autocomplete="new-password"></div><div class="form-actions"><button class="button">Create account</button></div></form></div></section>
<?php include __DIR__ . '/includes/footer.php'; ?>