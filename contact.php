<?php
require_once __DIR__ . '/includes/functions.php';
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    $name = trim((string)($_POST['name'] ?? ''));
    $email = filter_var($_POST['email'] ?? '', FILTER_VALIDATE_EMAIL);
    $subject = trim((string)($_POST['subject'] ?? ''));
    $message = trim((string)($_POST['message'] ?? ''));
    if (mb_strlen($name) < 2 || !$email || mb_strlen($subject) < 3 || mb_strlen($message) < 10) {
        $error = 'Please provide valid contact details and a message of at least 10 characters.';
    } else {
        $stmt = db()->prepare('INSERT INTO enquiries (name, email, phone, subject, message) VALUES (?, ?, ?, ?, ?)');
        $stmt->execute([$name, $email, trim((string)($_POST['phone'] ?? '')), $subject, $message]);
        flash('success', 'Thank you. Your message has been sent to the college.');
        redirect('contact.php');
    }
}
$pageTitle = 'Contact TTVC';
include __DIR__ . '/includes/header.php';
?>
<section class="page-hero"><div class="container"><p class="eyebrow">Get in touch</p><h1>We’re here to help.</h1><p>Talk to our team about admissions, programmes, student services or general college enquiries.</p></div></section><section class="content-section container"><div class="detail-layout"><div><?php include __DIR__ . '/includes/alerts.php'; ?><?php if ($error): ?><div class="alert error"><?= e($error) ?></div><?php endif; ?><form class="form-panel" method="post"><input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>"><div class="form-grid"><div class="field"><label>Name</label><input name="name" required maxlength="120"></div><div class="field"><label>Email</label><input name="email" type="email" required></div><div class="field"><label>Phone</label><input name="phone" type="tel"></div><div class="field"><label>Subject</label><input name="subject" required maxlength="180"></div><div class="field field-full"><label>Message</label><textarea name="message" required minlength="10"></textarea></div></div><div class="form-actions"><button class="button">Send message <span>↗</span></button></div></form></div><aside class="detail-aside"><p class="eyebrow">Visit or call</p><h3>Tindiret Technical and Vocational College</h3><p><?= e(setting('address', 'Tindiret, Nandi County, Kenya')) ?></p><p><?= e(setting('phone', '+254 700 000 000')) ?><br><?= e(setting('email', 'info@ttvc.ac.ke')) ?><br>Mon - Fri: 8:00am to 5:00pm</p><div style="margin-top:20px; padding-top:16px; border-top:1px solid var(--line);">
              <p><strong>Support areas</strong></p>
              <ul>
                <li>Admissions and application help</li>
                <li>Course and programme guidance</li>
                <li>Student support and services</li>
                <li>General college enquiries</li>
              </ul>
            </div><iframe title="TTVC location map" src="https://www.google.com/maps?q=Tindiret%2C%20Kenya&output=embed" width="100%" height="210" style="border:0" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe></aside></div></section>
<?php include __DIR__ . '/includes/footer.php'; ?>