<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/trgovina/includes/auth_check.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/trgovina/includes/db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = (int)$_POST['id'];
    $naziv = trim($conn->real_escape_string($_POST['naziv']));
    $cijena = (float)$_POST['cijena'];
    
    if (empty($naziv)) {
        header("Location: /trgovina/admin/proizvodi/uredi.php?id=$id&error=Naziv+je+obavezan");
        exit();
    }
    
    $stmt = $conn->prepare("UPDATE proizvod SET Naziv=?, Cijena=? WHERE IDProizvod=?");
    $stmt->bind_param("sdi", $naziv, $cijena, $id);
    
    if ($stmt->execute()) {
        header("Location: /trgovina/admin/index.php?updated=1");
    } else {
        header("Location: /trgovina/admin/proizvodi/uredi.php?id=$id&error=" . urlencode($stmt->error));
    }
    exit();
}

header("Location: /trgovina/admin/index.php");
exit();
?>