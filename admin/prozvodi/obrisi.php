<?php
session_start();
require_once __DIR__ . '/../includes/db_connection.php';
require_once __DIR__ . '/../includes/auth_check.php';

if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: /trgovina/admin/login.php");
    exit();
}

$id = (int)($_GET['id'] ?? 0);
if ($id === 0) {
    header("Location: /trgovina/admin/index.php?error=Nevažeći+ID");
    exit();
}

// Provjeri postoji li proizvod prije brisanja
$stmt = $conn->prepare("SELECT IDProizvod FROM proizvod WHERE IDProizvod = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    header("Location: /trgovina/admin/index.php?error=Proizvod+nije+pronađen");
    exit();
}

// Obriši proizvod
$delete_stmt = $conn->prepare("DELETE FROM proizvod WHERE IDProizvod = ?");
$delete_stmt->bind_param("i", $id);

if ($delete_stmt->execute()) {
    header("Location: /trgovina/admin/index.php?success=Proizvod+je+uspješno+obrisan");
} else {
    header("Location: /trgovina/admin/index.php?error=Greška+pri+brisanju+proizvoda");
}
exit();