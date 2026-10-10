<?php
require_once __DIR__ . '/includes/functions.php';
$pageTitle = 'Admissions';
include __DIR__ . '/includes/header.php';
?>
<section class="page-hero"><div class="container"><p class="eyebrow">Admissions</p><h1>Your next chapter<br>starts with a skill.</h1><p>Choose your programme, prepare your documents and apply online. Our admissions team is ready to guide you through every step.</p></div></section>
<section class="content-section container"><div class="detail-layout"><div class="prose"><h2>How to apply</h2><p>Applying to TTVC is simple and student-focused. First, browse the available courses and choose the programme that matches your interest and qualification. Then complete the online application form, upload the required documents and submit your details.</p><p>After submission, you will receive an application number that you can use to track your status online. Our admissions team reviews applications as they come in and may contact you for additional information if needed.</p><h2>What you will need</h2><p>You should prepare a valid national ID or passport, your KCSE result slip or certificate where applicable, a passport-size photo, and your contact details. Some courses may also require additional supporting information or references.</p><h2>Selection and fees</h2><p>Entry requirements vary by programme, course level and department. Review each course listing carefully before you apply. Fee and funding information is shared during the admission process and can also be confirmed through the admissions office.</p>
          <div style="margin-top:28px; display:grid; gap:14px;">
            <div style="background:#f5f7f3; border:1px solid rgba(20,45,50,.08); border-radius:16px; padding:18px 20px;">
              <strong>1. Explore programmes</strong>
              <p style="margin:8px 0 0;">Review available courses, levels and department options.</p>
            </div>
            <div style="background:#f5f7f3; border:1px solid rgba(20,45,50,.08); border-radius:16px; padding:18px 20px;">
              <strong>2. Prepare your documents</strong>
              <p style="margin:8px 0 0;">Collect your academic records, ID and any supporting material.</p>
            </div>
            <div style="background:#f5f7f3; border:1px solid rgba(20,45,50,.08); border-radius:16px; padding:18px 20px;">
              <strong>3. Submit your application</strong>
              <p style="margin:8px 0 0;">Complete the form online and keep your application number for follow-up.</p>
            </div>
          </div>
          <div style="margin-top:28px;"><a class="button" href="<?= e(url('apply.php')) ?>">Start application <span>↗</span></a></div></div><aside class="detail-aside"><p class="eyebrow">Application help</p><h3>Need assistance?</h3><p>Our admissions team is available to help with programme selection, entry requirements, fee guidance and application follow-up.</p><p><?= e(setting('phone', '+254 700 000 000')) ?><br><?= e(setting('email', 'info@ttvc.ac.ke')) ?><br>Monday to Friday: 8:00am - 5:00pm</p><a class="text-link" href="<?= e(url('contact.php')) ?>">Contact the college <span>→</span></a></aside></div></section>
<?php include __DIR__ . '/includes/footer.php'; ?>