<?php
require_once __DIR__ . '/../config/config.php';
unset($_SESSION['student_id'],$_SESSION['student_name']);
session_regenerate_id(true);
header('Location: ../login.php');exit;
