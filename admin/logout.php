<?php
require_once __DIR__ . '/../config/config.php';
unset($_SESSION['admin_id'],$_SESSION['admin_name'],$_SESSION['admin_role']);
session_regenerate_id(true);
header('Location: login.php');exit;
