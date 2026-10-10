<?php
require_once __DIR__ . '/../includes/functions.php';

$token = trim((string)($_POST['token'] ?? $_GET['token'] ?? ''));
header('Cache-Control: no-store, private');
header('Referrer-Policy: no-referrer');
$error = '';
$changed = false;
$validToken = preg_match('/\A[a-f0-9]{64}\z/', $token) === 1;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    $password = (string)($_POST['password'] ?? '');
    $confirmation = (string)($_POST['confirm_password'] ?? '');
    if (strlen($password) < 12 || $password !== $confirmation) {
        $error = 'Enter matching passwords that are at least 12 characters long.';
    } elseif ($validToken) {
        $pdo = db();
        $pdo->beginTransaction();
        try {
            $stmt = $pdo->prepare('SELECT r.admin_id FROM admin_password_resets r JOIN admins a ON a.id=r.admin_id WHERE r.token_hash=? AND r.expires_at>NOW() AND a.status="active" LIMIT 1 FOR UPDATE');
            $stmt->execute([hash('sha256', $token)]);
            $adminId = $stmt->fetchColumn();
            if ($adminId) {
                $pdo->prepare('UPDATE admins SET password_hash=?,force_password_change=0 WHERE id=?')->execute([password_hash($password, PASSWORD_DEFAULT), $adminId]);
                $pdo->prepare('DELETE FROM admin_password_resets WHERE admin_id=?')->execute([$adminId]);
                $pdo->commit();
                $changed = true;
            } else {
                $pdo->rollBack();
                $validToken = false;
                $error = 'This reset link is invalid or has expired. Request a new one.';
            }
        } catch (Throwable $exception) {
            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }
            throw $exception;
        }
    } else {
        $error = 'This reset link is invalid or has expired. Request a new one.';
    }
} elseif ($validToken) {
    $stmt = db()->prepare('SELECT r.admin_id FROM admin_password_resets r JOIN admins a ON a.id=r.admin_id WHERE r.token_hash=? AND r.expires_at>NOW() AND a.status="active" LIMIT 1');
    $stmt->execute([hash('sha256', $token)]);
    if (!$stmt->fetchColumn()) {
        $validToken = false;
        $error = 'This reset link is invalid or has expired. Request a new one.';
    }
} else {
    $error = 'This reset link is invalid or has expired. Request a new one.';
}

$pageTitle = 'Choose a new administrator password';
include __DIR__ . '/../includes/header.php';
?>
<section class="content-section container">
  <div class="login-box">
    <p class="eyebrow">Staff administration</p>
    <h1>Choose a new password</h1>
    <?php if ($changed): ?>
      <div class="alert" role="status">Your password has been reset. You can now sign in with your new password.</div>
      <p><a class="button" href="<?= e(url('admin/login.php')) ?>">Return to sign in</a></p>
    <?php else: ?>
      <?php if ($error !== ''): ?><div class="alert error" role="alert"><?= e($error) ?></div><?php endif; ?>
      <?php if ($validToken): ?>
        <form class="form-panel" method="post" action="<?= e(url('admin/reset-password.php')) ?>">
          <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">
          <input type="hidden" name="token" value="<?= e($token) ?>">
          <div class="field">
            <label for="password">New password (12+ characters)</label>
            <input id="password" name="password" type="password" required minlength="12" autocomplete="new-password">
          </div>
          <div class="field" style="margin-top:16px">
            <label for="confirm_password">Confirm new password</label>
            <input id="confirm_password" name="confirm_password" type="password" required minlength="12" autocomplete="new-password">
          </div>
          <div class="form-actions"><button class="button">Reset password</button></div>
        </form>
      <?php else: ?>
        <p><a class="text-link" href="<?= e(url('admin/forgot-password.php')) ?>">Request another reset link</a></p>
      <?php endif; ?>
    <?php endif; ?>
  </div>
</section>
<?php include __DIR__ . '/../includes/footer.php'; ?>
