<?php
require_once __DIR__ . '/../config/auth.php';require_student();
$stmt=db()->prepare('SELECT email FROM users WHERE id=?');$stmt->execute([$_SESSION['student_id']]);$email=$stmt->fetchColumn();$app=db()->prepare('SELECT application_number FROM applications WHERE email=? ORDER BY created_at DESC LIMIT 1');$app->execute([$email]);$number=$app->fetchColumn();if($number)redirect('application-status.php?number='.rawurlencode($number));redirect('apply.php');
