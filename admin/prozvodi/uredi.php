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

// Dohvat podataka iz baze
$stmt = $conn->prepare("SELECT * FROM proizvod WHERE IDProizvod = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$proizvod = $stmt->get_result()->fetch_assoc();

if (!$proizvod) {
    header("Location: /trgovina/admin/index.php?error=Proizvod+nije+pronađen");
    exit();
}

// Obrada forme za ažuriranje
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $naziv = $conn->real_escape_string($_POST['naziv']);
    $cijena = (float)$_POST['cijena'];
    
    $update_stmt = $conn->prepare("UPDATE proizvod SET Naziv = ?, Cijena = ? WHERE IDProizvod = ?");
    $update_stmt->bind_param("sdi", $naziv, $cijena, $id);
    
    if ($update_stmt->execute()) {
        header("Location: /trgovina/admin/index.php?success=Proizvod+je+uspješno+ažuriran");
        exit();
    } else {
        $error = "Greška pri ažuriranju proizvoda: " . $conn->error;
    }
}

include __DIR__ . '/../includes/header.php';
?>

<div class="container mt-5">
    <h2><i class="bi bi-pencil-square"></i> Uređivanje proizvoda</h2>
    
    <?php if (isset($error)): ?>
        <div class="alert alert-danger"><?= $error ?></div>
    <?php endif; ?>
    
    <form method="post">
        <input type="hidden" name="id" value="<?= $proizvod['IDProizvod'] ?>">
        
        <div class="mb-3">
            <label for="naziv" class="form-label">Naziv proizvoda</label>
            <input type="text" class="form-control" id="naziv" name="naziv" 
                   value="<?= htmlspecialchars($proizvod['Naziv']) ?>" required>
        </div>
        
        <div class="mb-3">
            <label for="cijena" class="form-label">Cijena (HRK)</label>
            <input type="number" step="0.01" class="form-control" id="cijena" 
                   name="cijena" value="<?= $proizvod['Cijena'] ?>" required>
        </div>
        
        <button type="submit" class="btn btn-primary">Spremi promjene</button>
        <a href="/trgovina/admin/index.php" class="btn btn-secondary">Odustani</a>
    </form>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>