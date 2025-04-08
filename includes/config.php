<?php
// Database configuration
define('DB_HOST', '127.0.0.1');
define('DB_USER', 'root'); // Change to your database username
define('DB_PASS', '');     // Change to your database password
define('DB_NAME', 'trgovina');

try {
    // Create PDO connection
    $conn = new PDO("mysql:host=".DB_HOST.";dbname=".DB_NAME, DB_USER, DB_PASS);
    
    // Set PDO to throw exceptions on error
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Set default fetch mode to associative array
    $conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    
} catch(PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
?>