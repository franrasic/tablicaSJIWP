<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

error_reporting(E_ALL);
ini_set('display_errors', 1);

file_put_contents('auth_debug.log', print_r($_SESSION, true), FILE_APPEND);

if (!isset($_SESSION['admin_logged_in'])) {
    $login_url = '/trgovina/admin/login.php';
    header("Location: $login_url");
    exit();
}
?>