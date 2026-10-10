<?php
require_once __DIR__ . '/includes/functions.php';
$pageTitle = 'Build your future with practical skills';
include __DIR__ . '/includes/header.php';
$courses = [];
$news = [];
try {
    $courses = db()->query('SELECT id, name, level, duration FROM courses WHERE status = "published" ORDER BY featured DESC, name LIMIT 3')->fetchAll();
    $news = db()->query('SELECT id, title, slug, excerpt, image, published_at FROM news WHERE status = "published" ORDER BY published_at DESC LIMIT 3')->fetchAll();
} catch (PDOException) {
}
?>
<section class="hero">
  <div class="hero-slider" aria-label="TTVC gallery slider">
    <div class="hero-slide active" style="background-image:linear-gradient(90deg, rgba(10,28,31,.72), rgba(10,28,31,.28)), url('<?= e(url('assets/images/image1.webp')) ?>');"></div>
    <div class="hero-slide" style="background-image:linear-gradient(90deg, rgba(10,28,31,.72), rgba(10,28,31,.28)), url('<?= e(url('assets/images/TINDIRET-TVC-GRADUATION-1058-1.webp')) ?>');"></div>
    <div class="hero-slide" style="background-image:linear-gradient(90deg, rgba(10,28,31,.72), rgba(10,28,31,.28)), url('<?= e(url('assets/images/TINDIRET-TVC-GRADUATION-1387-1-scaled.webp')) ?>');"></div>
    <div class="hero-slide" style="background-image:linear-gradient(90deg, rgba(10,28,31,.72), rgba(10,28,31,.28)), url('<?= e(url('assets/images/TINDIRET-TVC-GRADUATION-14-1-scaled.webp')) ?>');"></div>
  </div>

  <div class="container hero-content">
    <div class="hero-pill">Next intake · Applications open</div>
    <p class="eyebrow">Tindiret Technical & Vocational College</p>
    <h1>Build your future with practical skills.</h1>
    <p>Get career-focused training in engineering, ICT, business, hospitality and more—designed to prepare you for work and enterprise.</p>
    <div class="hero-actions">
      <a class="button" href="<?= e(url('apply.php')) ?>">Apply now <span aria-hidden="true">↗</span></a>
      <a class="button button-outline" href="<?= e(url('courses.php')) ?>">Explore courses</a>
    </div>
    <div class="hero-note"><span class="note-line"></span> Take your next step with Tindiret TVC</div>
  </div>

  <div class="hero-index" aria-live="polite">
    <span class="hero-index-current">01</span> <span>/</span> <span class="hero-index-total">04</span>
  </div>

  <div class="hero-dots" aria-label="Slider controls">
    <button type="button" class="hero-dot active" aria-label="Show slide 1"></button>
    <button type="button" class="hero-dot" aria-label="Show slide 2"></button>
    <button type="button" class="hero-dot" aria-label="Show slide 3"></button>
    <button type="button" class="hero-dot" aria-label="Show slide 4"></button>
  </div>
</section>
<section class="stats-band"><div class="container stats-grid"><div class="stat-card"><strong><?= e(setting('stat_courses', '20+')) ?></strong><span>Career-ready programmes</span></div><div class="stat-card"><strong><?= e(setting('stat_departments', '8')) ?></strong><span>Academic departments</span></div><div class="stat-card"><strong>4,000+</strong><span>Students shaped for work</span></div><div class="stat-card"><strong>85%</strong><span>Practical learning focus</span></div></div></section>
<section class="institutional-spotlight">
  <div class="container institutional-grid">
    <div class="institutional-copy">
      <p class="eyebrow">A college built for real careers</p>
      <h2>Practical training, trusted instruction, and a strong student experience.</h2>
      <p>Tindiret Technical and Vocational College prepares learners for work, enterprise, and lifelong progress through hands-on instruction, work-ready skills, and supportive academic guidance.</p>
      <div class="info-list">
        <div>
          <strong>Career-focused</strong>
          <span>Programmes designed around industry needs and local economic realities.</span>
        </div>
        <div>
          <strong>Community-rooted</strong>
          <span>Serving learners with skills, mentoring, and practical pathways to opportunity.</span>
        </div>
        <div>
          <strong>Skills for enterprise</strong>
          <span>Building confidence for employment, self-employment, and productive futures.</span>
        </div>
      </div>
    </div>
    <div class="institutional-panel">
      <div class="panel-header">
        <span>TTVC at a glance</span>
      </div>
      <ul>
        <li><span>Location</span><strong>Tindiret, Elgeyo-Marakwet County</strong></li>
        <li><span>Focus</span><strong>Technical, vocational and practical learning</strong></li>
        <li><span>Admissions</span><strong>Open for the next intake</strong></li>
        <li><span>Support</span><strong>Academic guidance and student services</strong></li>
      </ul>
      <a class="button button-small" href="<?= e(url('about.php')) ?>">Learn more about TTVC <span aria-hidden="true">↗</span></a>
    </div>
  </div>
</section>
<section class="section container"><div class="section-heading"><div><p class="eyebrow">Explore learning</p><h2>Skills that move<br>you forward.</h2></div><a class="text-link" href="<?= e(url('courses.php')) ?>">Browse all courses <span>→</span></a></div><div class="course-grid"><?php foreach ($courses as $course): ?><a class="course-card" href="<?= e(url('course-details.php?id=' . $course['id'])) ?>"><span class="course-number">TTVC / <?= e(strtoupper(substr($course['level'], 0, 3))) ?></span><h3><?= e($course['name']) ?></h3><div class="course-meta"><span><?= e($course['level']) ?></span><span><?= e($course['duration']) ?></span></div><span class="card-arrow">↗</span></a><?php endforeach; ?><?php if (!$courses): ?><p class="empty-state">Course listings are being updated. Please check back soon.</p><?php endif; ?></div></section>
<section class="section department-showcase"><div class="container"><div class="section-heading"><div><p class="eyebrow">Departments</p><h2>Where practical learning<br>meets real careers.</h2></div><a class="text-link" href="<?= e(url('departments.php')) ?>">See all departments <span>→</span></a></div><div class="department-grid"><a class="department-card" href="<?= e(url('departments.php')) ?>"><span class="department-tag">Technology</span><h3>Computing &amp; Informatics</h3><p>ICT support, software skills, computer applications and digital literacy for modern work.</p></a><a class="department-card" href="<?= e(url('departments.php')) ?>"><span class="department-tag">Engineering</span><h3>Electrical &amp; Electronics</h3><p>Practical installation, electrical systems, maintenance and renewable energy skills.</p></a><a class="department-card" href="<?= e(url('departments.php')) ?>"><span class="department-tag">Construction</span><h3>Building &amp; Civil</h3><p>Skills in construction, materials, site practice and technical planning.</p></a><a class="department-card" href="<?= e(url('departments.php')) ?>"><span class="department-tag">Business</span><h3>Business Studies</h3><p>Management, entrepreneurship, accounts, procurement and enterprise readiness.</p></a><a class="department-card" href="<?= e(url('departments.php')) ?>"><span class="department-tag">Agriculture</span><h3>Agriculture</h3><p>Farm production, agribusiness and sustainable crop and livestock practice.</p></a><a class="department-card" href="<?= e(url('departments.php')) ?>"><span class="department-tag">Hospitality</span><h3>Hospitality</h3><p>Food preparation, guest service, food safety and operational hospitality skills.</p></a><a class="department-card" href="<?= e(url('departments.php')) ?>"><span class="department-tag">Creative</span><h3>Fashion Design</h3><p>Garment making, design thinking, textiles and visual creativity for enterprise.</p></a><a class="department-card" href="<?= e(url('departments.php')) ?>"><span class="department-tag">Mobility</span><h3>Automotive &amp; Mechanical</h3><p>Workshop skills, vehicle systems, diagnostics and industrial maintenance practice.</p></a></div></div></section>
<section class="section container">
  <div class="cta-grid">
    <div class="cta-panel cta-panel-primary">
      <p class="eyebrow">Admissions open</p>
      <h3>Apply for the next intake</h3>
      <p>Choose the course that matches your ambition and start your application today.</p>
      <a class="button" href="<?= e(url('apply.php')) ?>">Apply now <span>↗</span></a>
    </div>
    <div class="cta-panel">
      <p class="eyebrow">Need guidance?</p>
      <h3>Talk to the admissions office</h3>
      <p>We can help with programme selection, entry requirements and next steps.</p>
      <a class="text-link" href="<?= e(url('contact.php')) ?>">Get support <span>→</span></a>
    </div>
    <div class="cta-panel">
      <p class="eyebrow">Student support</p>
      <h3>Resources for every learner</h3>
      <p>Access downloads, programme information and practical guidance for your study journey.</p>
      <a class="text-link" href="<?= e(url('downloads.php')) ?>">View resources <span>→</span></a>
    </div>
  </div>
</section>
<section class="value-section"><div class="container value-layout"><div><p class="eyebrow">Why TTVC</p><h2>Learn with purpose.<br>Graduate with confidence.</h2><p class="body-copy">Practical learning, skilled trainers and a strong connection to the world of work make every step count.</p><a class="text-link" href="<?= e(url('about.php')) ?>">Discover TTVC <span>→</span></a></div><div class="value-list"><article><span>01</span><div><h3>Training that is hands-on</h3><p>Build real confidence through practical workshops, labs and projects.</p></div></article><article><span>02</span><div><h3>Skills employers need</h3><p>Learn competencies shaped around evolving industry requirements.</p></div></article><article><span>03</span><div><h3>Support at every step</h3><p>Get guidance from application through to career development.</p></div></article></div></div></section>
<section class="section container"><div class="section-heading"><div><p class="eyebrow">Campus life</p><h2>What’s happening<br>at Tindiret.</h2></div><a class="text-link" href="<?= e(url('news.php')) ?>">All news <span>→</span></a></div><div class="news-grid"><?php foreach ($news as $article): ?><article class="news-card"><a href="<?= e(url('news-details.php?slug=' . rawurlencode($article['slug']))) ?>"><div class="news-image" style="background-image:url('<?= e(url($article['image'] ?: 'https://images.unsplash.com/photo-1581092334651-ddf26d9a09d0?auto=format&fit=crop&w=900&q=80')) ?>')"></div><p class="eyebrow"><?= e(date('d M Y', strtotime($article['published_at'] ?? 'now'))) ?></p><h3><?= e($article['title']) ?></h3><p><?= e($article['excerpt'] ?? '') ?></p><span class="text-link">Read story <span>→</span></span></a></article><?php endforeach; ?><?php if (!$news): ?><article class="news-card"><div class="news-image" style="background-image:url('https://images.unsplash.com/photo-1581092334651-ddf26d9a09d0?auto=format&fit=crop&w=900&q=80')"></div><p class="eyebrow">TTVC updates</p><h3>News and campus stories</h3><p>Updates from the college will appear here.</p></article><?php endif; ?></div></section>
<section class="section container"><div class="section-heading"><div><p class="eyebrow">Resource centre</p><h2>Download what you need<br>before you start.</h2></div><a class="text-link" href="<?= e(url('downloads.php')) ?>">View all downloads <span>→</span></a></div><div class="news-grid"><article class="news-card"><a href="<?= e(url('download.php?id=1')) ?>" aria-label="Download admissions information guide"><p class="eyebrow">Admissions</p><h3>Admissions information guide</h3><p>Understand how to apply, required documents, and the next steps for joining TTVC.</p><span class="text-link">Download PDF <span>↓</span></span></a></article><article class="news-card"><a href="<?= e(url('download.php?id=2')) ?>" aria-label="Download course directory"><p class="eyebrow">Programmes</p><h3>Course directory</h3><p>Browse programmes by department, duration, and level before you apply.</p><span class="text-link">Download PDF <span>↓</span></span></a></article><article class="news-card"><a href="<?= e(url('download.php?id=3')) ?>" aria-label="Download application checklist"><p class="eyebrow">Forms</p><h3>Application checklist</h3><p>Prepare key records, certificates, and personal details before submitting your application.</p><span class="text-link">Download PDF <span>↓</span></span></a></article></div></section>
<section class="apply-strip"><div class="container apply-strip-inner"><div><p class="eyebrow">Your next step starts here</p><h2>Ready to put your future in motion?</h2></div><a class="button button-light" href="<?= e(url('apply.php')) ?>">Start your application <span>↗</span></a></div></section>
<?php include __DIR__ . '/includes/footer.php'; ?>
