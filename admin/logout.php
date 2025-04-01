<?php
session_start();

// Uništi sve sesijske varijable
$_SESSION = array();

// Uništi sesiju
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], 
        $params["domain"],
        $params["secure"], 
        $params["httponly"]
    );
}

session_destroy();

// Preusmjeri na login stranicu
header("Location: login.php");
exit();
?>