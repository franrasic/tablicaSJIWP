<?php
require_once __DIR__ . '/../../includes/auth_check.php';
require_once __DIR__ . '/../../includes/db_connection.php';
check_admin_session();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $naziv = trim($conn->real_escape_string($_POST['naziv']));
    $cijena = (float)$_POST['cijena'];
    
    if (empty($naziv)) {
        header("Location: /trgovina/admin/proizvodi/dodaj.php?error=Naziv+proizvoda+je+obavezan");
        exit();
    }
    
    if ($cijena <= 0) {
        header("Location: /trgovina/admin/index.php?success=1");
        exit();
    }
    
    $stmt = $conn->prepare("INSERT INTO proizvod (Naziv, Cijena) VALUES (?, ?)");
    $stmt->bind_param("sd", $naziv, $cijena);
    
    if ($stmt->execute()) {
        header("Location: ../index.php?success=1");
    } else {
        header("Location: dodaj.php?error=" . urlencode($stmt->error));
    }
    exit();
}

header("Location: ../index.php");
exit();
?>