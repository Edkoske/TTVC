<?php
declare(strict_types=1);

const APP_NAME = 'Tindiret Technical and Vocational College';
const APP_SHORT_NAME = 'TTVC';
define('APP_ENV', getenv('TTVC_ENV') ?: 'development');
$projectRoot = str_replace('\\', '/', realpath(__DIR__ . '/..') ?: dirname(__DIR__));
$documentRoot = str_replace('\\', '/', realpath($_SERVER['DOCUMENT_ROOT'] ?? '') ?: '');
$scriptName = str_replace('\\', '/', $_SERVER['SCRIPT_NAME'] ?? '');
$detectedBase = '';
if ($scriptName !== '') {
    $scriptDir = rtrim(dirname($scriptName), '/');
    if ($scriptDir !== '.' && $scriptDir !== '' && $scriptDir !== '/') {
        $detectedBase = $scriptDir;
    }
}
if ($documentRoot !== '' && $projectRoot !== '' && str_starts_with($projectRoot, rtrim($documentRoot, '/') . '/')) {
    $relativePath = substr($projectRoot, strlen(rtrim($documentRoot, '/')));
    if ($relativePath !== '') {
        $detectedBase = $relativePath;
    }
}
define('APP_BASE_URL', rtrim(getenv('TTVC_BASE_URL') ?: $detectedBase, '/'));
const UPLOAD_DIR = __DIR__ . '/../assets/uploads';
const MAX_UPLOAD_BYTES = 5_000_000;

if (APP_ENV === 'production') {
    ini_set('display_errors', '0');
    ini_set('log_errors', '1');
}

date_default_timezone_set('Africa/Nairobi');

if (session_status() !== PHP_SESSION_ACTIVE) {
    ini_set('session.use_strict_mode', '1');
    ini_set('session.cookie_httponly', '1');
    ini_set('session.cookie_samesite', 'Lax');
    if (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') {
        ini_set('session.cookie_secure', '1');
    }
    session_start();
}
