<?php
require_once __DIR__ . '/../includes/functions.php';

$sent = false;
$deliveryFailed = false;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    $sent = true;
    $email = filter_var($_POST['email'] ?? '', FILTER_VALIDATE_EMAIL);
    if ($email) {
        $stmt = db()->prepare('SELECT id, full_name, email FROM admins WHERE email=? AND status="active" LIMIT 1');
        $stmt->execute([$email]);
        $admin = $stmt->fetch();
        if ($admin) {
            $recent = db()->prepare('SELECT created_at FROM admin_password_resets WHERE admin_id=? LIMIT 1');
            $recent->execute([$admin['id']]);
            $createdAt = $recent->fetchColumn();
            if (!$createdAt || strtotime((string)$createdAt) < time() - 60) {
                $token = bin2hex(random_bytes(32));
                $save = db()->prepare('INSERT INTO admin_password_resets (admin_id,token_hash,expires_at) VALUES (?,?,DATE_ADD(NOW(), INTERVAL 1 HOUR)) ON DUPLICATE KEY UPDATE token_hash=VALUES(token_hash),expires_at=VALUES(expires_at),created_at=CURRENT_TIMESTAMP');
                $save->execute([$admin['id'], hash('sha256', $token)]);

                $configuredBase = trim((string)(getenv('TTVC_BASE_URL') ?: ''));
                if (preg_match('~^https?://~i', $configuredBase)) {
                    $baseUrl = rtrim($configuredBase, '/');
                } else {
                    $host = (string)($_SERVER['HTTP_HOST'] ?? '');
                    if (!preg_match('/\A(?:[A-Za-z0-9.-]+|\[[0-9A-Fa-f:]+\])(?::\d{1,5})?\z/', $host)) {
                        error_log('Admin password reset email not sent: invalid or missing HTTP_HOST; configure TTVC_BASE_URL.');
                        db()->prepare('DELETE FROM admin_password_resets WHERE admin_id=?')->execute([$admin['id']]);
                        $host = '';
                    }
                    if ($host !== '') {
                        $scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
                        $baseUrl = $scheme . '://' . $host . APP_BASE_URL;
                    }
                }

                if (isset($baseUrl)) {
                    $resetUrl = $baseUrl . '/admin/reset-password.php?token=' . rawurlencode($token);
                    $mailSent = false;
                    try {
                        $autoload = __DIR__ . '/../vendor/autoload.php';
                        if (!is_file($autoload)) {
                            throw new RuntimeException('PHPMailer is not installed. Run composer install.');
                        }
                        require_once $autoload;

                        $smtpHost = (string)(getenv('TTVC_SMTP_HOST') ?: setting('smtp_host'));
                        $smtpUsername = (string)(getenv('TTVC_SMTP_USERNAME') ?: setting('smtp_username'));
                        $smtpPassword = (string)(getenv('TTVC_SMTP_PASS') ?: '');
                        $smtpPort = (int)(getenv('TTVC_SMTP_PORT') ?: setting('smtp_port', '587'));
                        $encryption = strtolower((string)(getenv('TTVC_SMTP_ENCRYPTION') ?: setting('smtp_encryption', 'tls')));
                        if ($smtpHost === '' || $smtpUsername === '' || $smtpPassword === '' || $smtpPort < 1 || $smtpPort > 65535) {
                            throw new RuntimeException('SMTP host, username, password, or port is not configured.');
                        }

                        $mailer = new PHPMailer\PHPMailer\PHPMailer(true);
                        $mailer->isSMTP();
                        $mailer->Host = $smtpHost;
                        $mailer->SMTPAuth = true;
                        $mailer->Username = $smtpUsername;
                        $mailer->Password = $smtpPassword;
                        $mailer->Port = $smtpPort;
                        if (in_array($encryption, ['tls', 'starttls'], true)) {
                            $mailer->SMTPSecure = PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_STARTTLS;
                        } elseif (in_array($encryption, ['ssl', 'smtps'], true)) {
                            $mailer->SMTPSecure = PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_SMTPS;
                        } elseif (in_array($encryption, ['', 'none'], true)) {
                            $mailer->SMTPSecure = '';
                            $mailer->SMTPAutoTLS = false;
                        } else {
                            throw new RuntimeException('Unsupported SMTP encryption setting.');
                        }

                        $fromEmail = (string)(getenv('TTVC_SMTP_FROM_EMAIL') ?: setting('smtp_from_email', $smtpUsername));
                        if (!filter_var($fromEmail, FILTER_VALIDATE_EMAIL)) {
                            throw new RuntimeException('A valid SMTP sender email address is required.');
                        }
                        $fromName = (string)(getenv('TTVC_SMTP_FROM_NAME') ?: setting('smtp_from_name', APP_SHORT_NAME));
                        $mailer->setFrom($fromEmail, $fromName);
                        $mailer->addAddress($admin['email'], $admin['full_name']);
                        $mailer->isHTML(false);
                        $mailer->Subject = APP_SHORT_NAME . ' administrator password reset';
                        $mailer->Body = "Hello {$admin['full_name']},\n\nUse the link below to reset your administrator password. This link expires in one hour and can only be used once.\n\n{$resetUrl}\n\nIf you did not request this reset, you can ignore this email.\n";
                        $mailSent = $mailer->send();
                    } catch (PHPMailer\PHPMailer\Exception | RuntimeException $exception) {
                        error_log('Admin password reset email could not be sent: ' . $exception->getMessage());
                    }
                    if (!$mailSent) {
                        $deliveryFailed = true;
                        db()->prepare('DELETE FROM admin_password_resets WHERE admin_id=?')->execute([$admin['id']]);
                    }
                }
            }
        }
    }
}

$pageTitle = 'Reset administrator password';
include __DIR__ . '/../includes/header.php';
?>
<section class="content-section container">
  <div class="login-box">
    <p class="eyebrow">Staff administration</p>
    <h1>Forgot your password?</h1>
    <?php if ($sent): ?>
      <?php if ($deliveryFailed): ?>
        <div class="alert error" role="alert">The server could not send the reset email, so no reset link was delivered. Configure email delivery on the server or contact a Super Admin for help.</div>
      <?php else: ?>
        <div class="alert" role="status">If that email belongs to an active administrator, a reset link will be sent. Check your inbox and spam folder. If no email arrives, contact a Super Admin.</div>
      <?php endif; ?>
    <?php endif; ?>
    <form class="form-panel" method="post">
      <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">
      <div class="field">
        <label for="email">Administrator email address</label>
        <input id="email" name="email" type="email" required autocomplete="username">
      </div>
      <div class="form-actions"><button class="button">Send reset link</button></div>
      <p><a class="text-link" href="<?= e(url('admin/login.php')) ?>">Back to sign in</a></p>
    </form>
  </div>
</section>
<?php include __DIR__ . '/../includes/footer.php'; ?>
