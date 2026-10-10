<?php
require __DIR__ . '/config/database.php';
$pdo = db();
$rows = $pdo->query("SELECT id, title, file_path, category FROM downloads ORDER BY id")->fetchAll(PDO::FETCH_ASSOC);
foreach ($rows as $r) {
    $path = __DIR__ . '/' . $r['file_path'];
    $exists = file_exists($path);
    echo $r['id'] . ' | ' . $r['title'] . ' | ' . $r['category'] . ' | ' . ($exists ? 'OK' : 'MISSING') . ' | ' . $r['file_path'] . PHP_EOL;
}
