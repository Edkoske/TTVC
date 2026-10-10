<?php
require_once __DIR__ . '/includes/functions.php';
$departments = db()->query('SELECT id, name FROM departments ORDER BY name')->fetchAll();
$levels = db()->query('SELECT DISTINCT level FROM courses WHERE status = "published" ORDER BY level')->fetchAll(PDO::FETCH_COLUMN);
$search = trim((string)($_GET['q'] ?? ''));
$department = (int)($_GET['department'] ?? 0);
$level = trim((string)($_GET['level'] ?? ''));
$sql = 'SELECT c.id, c.name, c.level, c.duration, c.description, d.name AS department FROM courses c JOIN departments d ON d.id = c.department_id WHERE c.status = "published"';
$params = [];
if ($search !== '') { $sql .= ' AND (c.name LIKE ? OR c.description LIKE ? OR d.name LIKE ? OR c.level LIKE ?)'; $term = '%' . $search . '%'; array_push($params, $term, $term, $term, $term); }
if ($department) { $sql .= ' AND c.department_id = ?'; $params[] = $department; }
if ($level !== '') { $sql .= ' AND c.level = ?'; $params[] = $level; }
$sql .= ' ORDER BY c.featured DESC, c.name';
$stmt = db()->prepare($sql);
$stmt->execute($params);
$courses = $stmt->fetchAll();
$pageTitle = 'Courses and programmes';
include __DIR__ . '/includes/header.php';
?>
<section class="page-hero"><div class="container"><p class="eyebrow">Find your path</p><h1>Courses built for<br>the work ahead.</h1><p>Explore practical, career-oriented programmes and discover the learning pathway that matches your ambition.</p></div></section>
<section class="content-section container">
  <div class="detail-layout">
    <div class="prose">
      <h2>Study with purpose</h2>
      <p>TTVC offers programmes designed for real-world employment, business creation and continued learning. Our courses combine classroom knowledge with workshop practice, industry exposure and guided project work.</p>
      <p>Whether you are looking for a certificate, artisan course, craft programme or diploma, you will find a pathway that supports your goals and strengthens your confidence for the workplace.</p>
    </div>
    <aside class="detail-aside">
      <p class="eyebrow">Quick guide</p>
      <h3>Programme areas</h3>
      <p>Computing · Engineering · Business · Agriculture · Hospitality · Fashion · Automotive · Construction</p>
      <a class="text-link" href="<?= e(url('admissions.php')) ?>">Learn about admissions <span>→</span></a>
    </aside>
  </div>
  <form class="search-row" method="get"><input class="search-field" name="q" placeholder="Search ICT, electrical, hospitality…" value="<?= e($search) ?>" aria-label="Search courses"><select class="search-field" name="department" aria-label="Filter by department"><option value="0">All departments</option><?php foreach ($departments as $item): ?><option value="<?= (int)$item['id'] ?>" <?= $department === (int)$item['id'] ? 'selected' : '' ?>><?= e($item['name']) ?></option><?php endforeach; ?></select><select class="search-field" name="level" aria-label="Filter by level"><option value="">All levels</option><?php foreach ($levels as $item): ?><option <?= $level === $item ? 'selected' : '' ?>><?= e($item) ?></option><?php endforeach; ?></select><button class="button">Search</button></form><p class="eyebrow"><?= count($courses) ?> course<?= count($courses) === 1 ? '' : 's' ?> found</p><div class="result-list"><?php foreach ($courses as $course): ?><article class="result-row"><div><h3><?= e($course['name']) ?></h3><p><?= e($course['department']) ?> · <?= e($course['level']) ?> · <?= e($course['duration']) ?></p></div><a class="text-link" href="<?= e(url('course-details.php?id=' . $course['id'])) ?>">View course <span>→</span></a></article><?php endforeach; ?><?php if (!$courses): ?><p class="empty-state">No courses match those filters.</p><?php endif; ?></div></section>
<?php include __DIR__ . '/includes/footer.php'; ?>