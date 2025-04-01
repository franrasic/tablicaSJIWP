<?php
session_start();
echo "<pre>";
echo "PHP Version: " . phpversion() . "\n";
echo "Password hash info:\n";
print_r(password_get_info('$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'));
echo "</pre>";
require_once __DIR__ . '/../includes/db_connection.php';

error_reporting(E_ALL);
ini_set('display_errors', 1);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    // Debug ispis
    echo "<pre>Uneseni podaci:\n";
    echo "Username: " . htmlspecialchars($username) . "\n";
    echo "Password: " . htmlspecialchars($password) . "</pre>";

    $stmt = $conn->prepare("SELECT * FROM administratori WHERE username = ? LIMIT 1");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $admin = $result->fetch_assoc();
        
        // Debug ispis
        echo "<pre>Podaci iz baze:\n";
        print_r($admin);
        echo "Password verify: " . (password_verify($password, $admin['password']) ? 'true' : 'false') . "</pre>";

        if (password_verify($password, $admin['password'])) {
            $_SESSION['admin_logged_in'] = true;
            $_SESSION['admin_id'] = $admin['id'];
            $_SESSION['admin_username'] = $admin['username'];
            header("Location: index.php");
            exit();
        }
    }

    $_SESSION['login_error'] = "Neispravno korisničko ime ili lozinka";
    header("Location: login.php");
    exit();
}
?>