<?php
$collegeAddress = setting('address', 'Tindiret, Nandi County, Kenya');
$collegePhone = setting('phone', '');
$collegeEmail = setting('email', '');
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Offer of Admission - <?= e($application['application_number']) ?> | TTVC</title>
  <style>
    :root{font-family:Arial,sans-serif;color:#142d32;background:#edf1ed}
    *{box-sizing:border-box}
    body{margin:0;padding:32px 16px}
    .actions{max-width:800px;margin:0 auto 16px;display:flex;justify-content:flex-end}
    button{padding:12px 18px;border:0;background:#1b6755;color:#fff;font-size:14px;font-weight:700;cursor:pointer}
    .letter{max-width:800px;min-height:980px;margin:auto;padding:64px 72px;background:#fff;box-shadow:0 8px 32px #142d3220}
    .letter-header{display:flex;align-items:center;gap:18px;padding-bottom:22px;border-bottom:3px solid #d5a63c}
    .letter-header img{width:82px;height:82px;object-fit:contain}
    .letter-header h1{margin:0;font-size:22px;letter-spacing:.4px}
    .letter-header p{margin:5px 0 0;color:#667875;font-size:13px}
    .date{margin:28px 0 24px;color:#455c58}
    h2{margin:0 0 24px;text-align:center;font-size:21px;text-transform:uppercase;letter-spacing:1px}
    p{line-height:1.75}
    .details{margin:24px 0;padding:18px 22px;background:#f5f5ef}
    .details div{display:grid;grid-template-columns:175px 1fr;gap:12px;padding:7px 0}
    .details strong{font-size:14px}
    .details span{font-size:14px}
    .closing{margin-top:36px}
    .signature{margin-top:45px;font-weight:700}
    .signature span{display:block;margin-top:4px;font-weight:400;color:#667875}
    .contact{margin-top:46px;padding-top:14px;border-top:1px solid #dce3dc;color:#667875;font-size:12px}
    @media(max-width:600px){body{padding:12px 8px}.letter{padding:32px 24px;min-height:0}.letter-header h1{font-size:17px}.letter-header img{width:62px;height:62px}.details div{grid-template-columns:1fr;gap:0}}
    @media print{ :root{background:#fff}body{padding:0}.actions{display:none}.letter{max-width:none;min-height:0;margin:0;padding:24mm 22mm;box-shadow:none}@page{size:A4;margin:0}}
  </style>
</head>
<body>
  <div class="actions"><button type="button" onclick="window.print()">Print / Save as PDF</button></div>
  <main class="letter">
    <header class="letter-header">
      <img src="<?= e(url('assets/images/Tindiret-TVC-logo.png')) ?>" alt="TTVC logo">
      <div>
        <h1><?= e(APP_NAME) ?></h1>
        <p><?= e($collegeAddress) ?></p>
      </div>
    </header>
    <p class="date"><?= e(date('d F Y')) ?></p>
    <p>Dear <?= e($application['full_name']) ?>,</p>
    <h2>Offer of Admission</h2>
    <p>We are pleased to inform you that your application to <?= e(APP_NAME) ?> has been accepted. We are pleased to offer you admission to the programme below.</p>
    <section class="details" aria-label="Admission details">
      <div><strong>Applicant</strong><span><?= e($application['full_name']) ?></span></div>
      <div><strong>Application number</strong><span><?= e($application['application_number']) ?></span></div>
      <div><strong>Programme</strong><span><?= e($application['course']) ?></span></div>
      <div><strong>Department</strong><span><?= e($application['department']) ?></span></div>
      <div><strong>Level</strong><span><?= e($application['level']) ?></span></div>
      <div><strong>Duration</strong><span><?= e($application['duration']) ?></span></div>
      <div><strong>Intake</strong><span><?= e($application['intake']) ?></span></div>
    </section>
    <p>Please contact the Admissions Office if you need further information about this offer or the next steps for joining the College.</p>
    <p class="closing">We look forward to welcoming you to <?= e(APP_SHORT_NAME) ?>.</p>
    <p class="signature">Admissions Office<span><?= e(APP_NAME) ?></span></p>
    <footer class="contact">
      <?= e($collegeAddress) ?>
      <?php if ($collegePhone !== ''): ?> · <?= e($collegePhone) ?><?php endif; ?>
      <?php if ($collegeEmail !== ''): ?> · <?= e($collegeEmail) ?><?php endif; ?>
    </footer>
  </main>
</body>
</html>
