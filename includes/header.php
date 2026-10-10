<?php
$pageTitle = $pageTitle ?? APP_NAME;
$pageDescription = $pageDescription ?? 'Practical, career-focused technical and vocational education in Kenya.';
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= e($pageTitle) ?> | TTVC</title>
  <meta name="description" content="<?= e($pageDescription) ?>">
  <meta property="og:title" content="<?= e($pageTitle) ?> | TTVC">
  <meta property="og:description" content="<?= e($pageDescription) ?>">
  <meta property="og:type" content="website">
  <link rel="icon" type="image/svg+xml" href="<?= e(url('assets/images/ttvc-mark.svg')) ?>">
  <script type="application/ld+json"><?= json_encode(['@context'=>'https://schema.org','@type'=>'EducationalOrganization','name'=>APP_NAME,'url'=>APP_BASE_URL?:'https://www.ttvc.ac.ke','telephone'=>setting('phone','+254 700 000 000'),'email'=>setting('email','info@ttvc.ac.ke'),'address'=>['@type'=>'PostalAddress','streetAddress'=>setting('address','Tindiret, Nandi County'),'addressCountry'=>'KE']], JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT) ?></script>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Manrope:wght@500;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="<?= e(url('assets/css/site.css')) ?>">
  <link rel="stylesheet" href="<?= e(url('assets/css/custom.css')) ?>">
  <link rel="stylesheet" href="<?= e(url('assets/css/responsive.css')) ?>">
  <link rel="stylesheet" href="<?= e(url('assets/css/professional.css')) ?>">
  <script defer src="<?= e(url('assets/js/site.js')) ?>"></script>
</head>
<body>
<?php include __DIR__ . '/navbar.php'; ?>
<main>
