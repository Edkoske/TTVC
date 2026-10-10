<?php
require_once __DIR__ . '/../config/auth.php';
require_admin(['Super Admin','Admissions Officer']);
$id=filter_input(INPUT_GET,'id',FILTER_VALIDATE_INT)?:0;$type=trim((string)($_GET['type']??''));
$stmt=db()->prepare('SELECT file_path FROM application_documents WHERE application_id=? AND document_type=?');$stmt->execute([$id,$type]);$path=$stmt->fetchColumn();$full=$path?realpath(__DIR__.'/../'.$path):false;$root=realpath(UPLOAD_DIR);
if(!$full||!$root||!str_starts_with($full,$root.DIRECTORY_SEPARATOR)||!is_file($full)){http_response_code(404);exit('Document not found.');}
header('X-Content-Type-Options: nosniff');header('Content-Type: application/octet-stream');header('Content-Disposition: attachment; filename="'.basename($full).'"');header('Content-Length: '.filesize($full));readfile($full);exit;
