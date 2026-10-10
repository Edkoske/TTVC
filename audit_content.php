<?php
require __DIR__ . '/config/database.php';
$pdo = db();
$tables = ['admins', 'departments', 'courses', 'news', 'events', 'faqs', 'gallery', 'facilities', 'downloads', 'settings'];
foreach ($tables as $table) {
    try {
        $count = $pdo->query('SELECT COUNT(*) FROM ' . $table)->fetchColumn();
        echo strtoupper($table) . ' => ' . $count . PHP_EOL;
    } catch (Throwable $e) {
        echo strtoupper($table) . ' => ERROR: ' . $e->getMessage() . PHP_EOL;
    }
}
