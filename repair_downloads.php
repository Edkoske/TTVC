<?php
require __DIR__ . '/config/database.php';
$pdo = db();
$rows = [
    [1, 'Admissions information guide', 'Overview of applying and preparing your documents.', 'Admission', 'assets/uploads/resources/ttvc-admission-guide.pdf'],
    [2, 'Course directory', 'Summary of programme levels, departments and study pathways.', 'Brochures', 'assets/uploads/resources/ttvc-course-directory.pdf'],
    [3, 'Student application checklist', 'Checklist for preparing your documents and online application form.', 'Forms', 'assets/uploads/resources/ttvc-application-checklist.pdf'],
    [4, 'Fees and levies guide', 'Current fee guidance and important student financial information.', 'Fees', 'assets/uploads/resources/ttvc-fees-and-levies.pdf'],
    [5, 'Student handbook', 'A practical guide to student life, support services and expectations.', 'Student documents', 'assets/uploads/resources/ttvc-student-handbook.pdf'],
    [6, 'Student services guide', 'Guidance on student support, wellbeing and campus services.', 'Student documents', 'assets/uploads/resources/ttvc-student-services-guide.pdf'],
    [7, 'College information sheet', 'A short introduction to TTVC programmes and services.', 'Other', 'assets/uploads/resources/ttvc-information.txt'],
];
foreach ($rows as $r) {
    $stmt = $pdo->prepare('INSERT INTO downloads (id, title, description, category, file_path, is_public, download_count, created_at) VALUES (?, ?, ?, ?, ?, 1, 0, NOW()) ON DUPLICATE KEY UPDATE title = VALUES(title), description = VALUES(description), category = VALUES(category), file_path = VALUES(file_path), is_public = VALUES(is_public)');
    $stmt->execute([$r[0], $r[1], $r[2], $r[3], $r[4]]);
    echo "OK id={$r[0]} -> {$r[4]}\n";
}
