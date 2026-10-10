<?php
declare(strict_types=1);

require_once __DIR__ . '/../includes/functions.php';

function require_student(): void
{
    if (isset($_SESSION['last_activity']) && time() - (int)$_SESSION['last_activity'] > 1800) {
        $_SESSION = [];
        session_regenerate_id(true);
    }
    if (empty($_SESSION['student_id'])) {
        flash('warning', 'Please sign in to access the student portal.');
        redirect('login.php');
    }
    $stmt = db()->prepare('SELECT status FROM users WHERE id = ?');
    $stmt->execute([$_SESSION['student_id']]);
    if ($stmt->fetchColumn() !== 'active') {
        unset($_SESSION['student_id'], $_SESSION['student_name']);
        redirect('login.php');
    }
    $_SESSION['last_activity'] = time();
}

function require_admin(?array $roles = null): void
{
    if (isset($_SESSION['last_activity']) && time() - (int)$_SESSION['last_activity'] > 1800) {
        $_SESSION = [];
        session_regenerate_id(true);
    }
    if (empty($_SESSION['admin_id'])) {
        redirect('admin/login.php');
    }
    $_SESSION['last_activity'] = time();
    $stmt = db()->prepare('SELECT status, force_password_change FROM admins WHERE id = ?');
    $stmt->execute([$_SESSION['admin_id']]);
    $admin = $stmt->fetch();
    if (!$admin || $admin['status'] !== 'active') {
        unset($_SESSION['admin_id'], $_SESSION['admin_name'], $_SESSION['admin_role']);
        redirect('admin/login.php');
    }
    if (basename($_SERVER['SCRIPT_NAME'] ?? '') !== 'settings.php') {
        if ((int)$admin['force_password_change'] === 1) {
            redirect('admin/settings.php?password=1');
        }
    }
    if ($roles !== null && !in_array($_SESSION['admin_role'] ?? '', $roles, true)) {
        http_response_code(403);
        require __DIR__ . '/../403.php';
        exit;
    }
}
