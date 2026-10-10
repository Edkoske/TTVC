<?php
require_once __DIR__ . '/../config/auth.php';
require_admin(['Super Admin', 'Admissions Officer']);

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT) ?: 0;
$stmt = db()->prepare('SELECT a.application_number,a.full_name,a.email,a.intake,a.status,c.name AS course,c.level,c.duration,d.name AS department FROM applications a JOIN courses c ON c.id=a.course_id JOIN departments d ON d.id=c.department_id WHERE a.id=? AND a.status="Accepted" LIMIT 1');
$stmt->execute([$id]);
$application = $stmt->fetch();
if (!$application) {
    http_response_code(404);
    exit('Accepted application not found.');
}

include __DIR__ . '/../includes/admission-letter.php';
