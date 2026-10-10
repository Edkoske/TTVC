<?php
require_once __DIR__ . '/../config/auth.php';
require_student();

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT) ?: 0;
$stmt = db()->prepare('SELECT a.application_number,a.full_name,a.email,a.intake,a.status,c.name AS course,c.level,c.duration,d.name AS department FROM applications a JOIN courses c ON c.id=a.course_id JOIN departments d ON d.id=c.department_id JOIN users u ON u.id=? WHERE a.id=? AND a.status="Accepted" AND a.email=u.email LIMIT 1');
$stmt->execute([$_SESSION['student_id'], $id]);
$application = $stmt->fetch();
if (!$application) {
    http_response_code(404);
    exit('Accepted application not found.');
}

include __DIR__ . '/../includes/admission-letter.php';
