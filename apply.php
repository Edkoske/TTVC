<?php
require_once __DIR__ . '/includes/functions.php';
$courses = db()->query('SELECT c.id, c.name, c.level, d.name AS department FROM courses c JOIN departments d ON d.id = c.department_id WHERE c.status = "published" ORDER BY d.name, c.name')->fetchAll();
$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    $required = ['full_name', 'gender', 'date_of_birth', 'id_number', 'phone', 'email', 'county', 'kcse_year', 'grade', 'course_id', 'intake'];
    foreach ($required as $field) {
        if (trim((string)($_POST[$field] ?? '')) === '') $errors[] = 'Complete all required fields before submitting.';
    }
    if (!filter_var($_POST['email'] ?? '', FILTER_VALIDATE_EMAIL)) $errors[] = 'Enter a valid email address.';
    if (!in_array($_POST['gender'] ?? '', ['Female', 'Male', 'Prefer not to say'], true)) $errors[] = 'Choose a valid gender option.';
    $birthDate = DateTime::createFromFormat('!Y-m-d', (string)($_POST['date_of_birth'] ?? ''));
    if (!$birthDate || $birthDate->format('Y-m-d') !== ($_POST['date_of_birth'] ?? '') || $birthDate > new DateTime('today')) $errors[] = 'Enter a valid date of birth.';
    $kcseYear = filter_var($_POST['kcse_year'] ?? null, FILTER_VALIDATE_INT);
    if (!$kcseYear || $kcseYear < 1980 || $kcseYear > (int)date('Y')) $errors[] = 'Enter a valid KCSE year.';
    foreach (['full_name' => 150, 'id_number' => 60, 'phone' => 35, 'county' => 100, 'sub_county' => 100] as $field => $limit) {
        if (strlen((string)($_POST[$field] ?? '')) > $limit) $errors[] = 'One or more fields exceed the allowed length.';
    }
    if (!ctype_digit((string)($_POST['course_id'] ?? ''))) $errors[] = 'Choose a valid course.';
    $courseCheck = db()->prepare('SELECT id FROM courses WHERE id = ? AND status = "published"');
    $courseCheck->execute([(int)($_POST['course_id'] ?? 0)]);
    if (!$courseCheck->fetchColumn()) $errors[] = 'Choose a currently available course.';

    $uploads = [];
    if (!$errors) {
        try {
            foreach (['identity' => 'identity', 'results' => 'results', 'photo' => 'photo', 'supporting' => 'supporting'] as $field => $type) {
                if (!empty($_FILES[$field]['name'])) {
                    $mimeList = $field === 'photo' ? ['image/jpeg', 'image/png', 'image/webp'] : ['application/pdf', 'image/jpeg', 'image/png'];
                    $path = upload_file($_FILES[$field], $mimeList, 'applications');
                    if ($path) $uploads[$type] = $path;
                }
            }
            $pdo = db();
            $pdo->beginTransaction();
            $stmt = $pdo->prepare('INSERT INTO applications (application_number, full_name, gender, date_of_birth, id_number, phone, email, county, sub_county, postal_address, kcse_index, kcse_year, grade, previous_institution, highest_qualification, course_id, intake, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, "Submitted")');
            $stmt->execute(['PENDING-' . bin2hex(random_bytes(12)), trim($_POST['full_name']), $_POST['gender'], $_POST['date_of_birth'], trim($_POST['id_number']), trim($_POST['phone']), strtolower(trim($_POST['email'])), trim($_POST['county']), trim($_POST['sub_county'] ?? ''), trim($_POST['postal_address'] ?? ''), trim($_POST['kcse_index'] ?? ''), (int)$_POST['kcse_year'], trim($_POST['grade']), trim($_POST['previous_institution'] ?? ''), trim($_POST['highest_qualification'] ?? ''), (int)$_POST['course_id'], trim($_POST['intake'])]);
            $id = (int)$pdo->lastInsertId();
            $number = 'TTVC/' . date('Y') . '/' . str_pad((string)$id, 6, '0', STR_PAD_LEFT);
            $pdo->prepare('UPDATE applications SET application_number = ? WHERE id = ?')->execute([$number, $id]);
            foreach ($uploads as $type => $path) {
                $pdo->prepare('INSERT INTO application_documents (application_id, document_type, file_path) VALUES (?, ?, ?)')->execute([$id, $type, $path]);
            }
            $pdo->commit();
            $_SESSION['submitted_application'] = $number;
            redirect('application-success.php');
        } catch (Throwable $error) {
            if (db()->inTransaction()) db()->rollBack();
            $errors[] = $error instanceof RuntimeException ? $error->getMessage() : 'We could not submit your application. Please check your details and try again.';
        }
    }
}
$pageTitle = 'Apply online';
include __DIR__ . '/includes/header.php';
?>
<section class="page-hero"><div class="container"><p class="eyebrow">Admissions · Online application</p><h1>Make your next move.</h1><p>Complete the steps below. Fields marked required must be provided to submit your application.</p></div></section>
<section class="content-section container"><?php foreach ($errors as $error): ?><div class="alert error"><?= e($error) ?></div><?php endforeach; ?><form class="form-panel" method="post" enctype="multipart/form-data" data-application-form data-start-step="<?= $errors ? '4' : '0' ?>"><input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>"><div class="steps"><span class="step active" data-step-marker>Personal</span><span class="step" data-step-marker>Education</span><span class="step" data-step-marker>Course</span><span class="step" data-step-marker>Documents</span><span class="step" data-step-marker>Review</span></div>
<section data-step-panel><h2>Personal details</h2><div class="form-grid"><div class="field"><label>Full name *</label><input name="full_name" required maxlength="120" value="<?= e($_POST['full_name'] ?? '') ?>"></div><div class="field"><label>Gender *</label><select name="gender" required><option value="">Select</option><?php foreach (['Female','Male','Prefer not to say'] as $option): ?><option <?= ($_POST['gender'] ?? '') === $option ? 'selected' : '' ?>><?= e($option) ?></option><?php endforeach; ?></select></div><div class="field"><label>Date of birth *</label><input name="date_of_birth" type="date" required max="<?= date('Y-m-d') ?>"></div><div class="field"><label>ID / Passport number *</label><input name="id_number" required maxlength="40"></div><div class="field"><label>Phone *</label><input name="phone" type="tel" required maxlength="30"></div><div class="field"><label>Email *</label><input name="email" type="email" required maxlength="190"></div><div class="field"><label>County *</label><input name="county" required></div><div class="field"><label>Sub-county</label><input name="sub_county"></div><div class="field field-full"><label>Postal address</label><input name="postal_address"></div></div></section>
<section class="hidden" data-step-panel><h2>Education</h2><div class="form-grid"><div class="field"><label>KCSE index number</label><input name="kcse_index"></div><div class="field"><label>KCSE year *</label><input name="kcse_year" type="number" min="1980" max="<?= date('Y') ?>" required></div><div class="field"><label>KCSE grade *</label><input name="grade" required></div><div class="field"><label>Previous institution</label><input name="previous_institution"></div><div class="field field-full"><label>Highest qualification</label><input name="highest_qualification"></div></div></section>
<section class="hidden" data-step-panel><h2>Choose a course</h2><div class="form-grid"><div class="field field-full"><label>Course *</label><select name="course_id" required><option value="">Select a course</option><?php foreach ($courses as $course): ?><option value="<?= (int)$course['id'] ?>"><?= e($course['name'] . ' · ' . $course['department'] . ' · ' . $course['level']) ?></option><?php endforeach; ?></select></div><div class="field field-full"><label>Preferred intake *</label><select name="intake" required><option value="">Select intake</option><option>January <?= date('Y') + 1 ?></option><option>May <?= date('Y') + 1 ?></option><option>September <?= date('Y') + 1 ?></option></select></div></div></section>
<section class="hidden" data-step-panel><h2>Supporting documents</h2><p class="body-copy">PDF, JPG or PNG only. Maximum 5 MB per file. Documents can also be submitted later through the admissions office.</p><div class="form-grid"><div class="field"><label>ID / Passport</label><input name="identity" type="file" accept=".pdf,image/jpeg,image/png"></div><div class="field"><label>KCSE certificate / result slip</label><input name="results" type="file" accept=".pdf,image/jpeg,image/png"></div><div class="field"><label>Passport photo</label><input name="photo" type="file" accept="image/jpeg,image/png,image/webp"></div><div class="field"><label>Other supporting document</label><input name="supporting" type="file" accept=".pdf,image/jpeg,image/png"></div></div></section>
<section class="hidden" data-step-panel><h2>Review and submit</h2><p>Please confirm your details before submission.</p><div class="result-list"><div class="result-row"><strong>Applicant</strong><span data-review="full_name"></span></div><div class="result-row"><strong>Email</strong><span data-review="email"></span></div><div class="result-row"><strong>County</strong><span data-review="county"></span></div><div class="result-row"><strong>Course ID</strong><span data-review="course_id"></span></div><div class="result-row"><strong>Intake</strong><span data-review="intake"></span></div></div><p class="body-copy">By submitting, you confirm the information provided is accurate.</p></section>
<div class="form-actions"><button type="button" class="button button-outline" data-prev>Back</button><button type="button" class="button" data-next>Continue <span>→</span></button><button type="submit" class="button hidden" data-submit>Submit application <span>↗</span></button></div></form><script>const submittedFields=<?= json_encode(array_filter($_POST ?? [], 'is_string'), JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT) ?>;document.querySelectorAll('[data-application-form]').forEach(form=>Object.entries(submittedFields).forEach(([name,value])=>{const field=form.elements.namedItem(name);if(field&&field.type!=='hidden'&&field.type!=='file')field.value=value;}));</script></section>
<?php include __DIR__ . '/includes/footer.php'; ?>