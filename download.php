<?php
require_once __DIR__ . '/includes/functions.php';
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT) ?: 0;
$stmt = db()->prepare('SELECT file_path FROM downloads WHERE id = ? AND is_public = 1');
$stmt->execute([$id]);
$path = $stmt->fetchColumn();
$fullPath = $path ? realpath(__DIR__ . '/' . $path) : false;
$uploadRoot = realpath(UPLOAD_DIR);
if (!$fullPath || !$uploadRoot || !str_starts_with($fullPath, $uploadRoot . DIRECTORY_SEPARATOR) || !is_file($fullPath)) { http_response_code(404); exit('Resource not found.'); }
db()->prepare('UPDATE downloads SET download_count = download_count + 1 WHERE id = ?')->execute([$id]);
header('X-Content-Type-Options: nosniff');
header('Content-Type: application/octet-stream');
header('Content-Disposition: attachment; filename="' . basename($fullPath) . '"');
header('Content-Length: ' . filesize($fullPath));
readfile($fullPath);
exit;
