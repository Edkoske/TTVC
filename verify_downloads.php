<?php
require __DIR__ . '/config/database.php';
$rows = db()->query('SELECT id, title, file_path FROM downloads ORDER BY id')->fetchAll(PDO::FETCH_ASSOC);
foreach ($rows as $r) {
    $full = __DIR__ . '/' . $r['file_path'];
    echo $r['id'] . ' | ' . $r['title'] . ' | ' . (file_exists($full) ? 'OK' : 'MISSING') . ' | ' . $r['file_path'] . PHP_EOL;
}
