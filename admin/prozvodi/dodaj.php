<?php
session_start();
require_once __DIR__ . '/../includes/db_connection.php';
require_once __DIR__ . '/../includes/auth_check.php';

if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: /trgovina/admin/login.php");
    exit();
}

// Obrada forme za dodavanje
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $naziv = $conn->real_escape_string($_POST['naziv']);
    $cijena = (float)$_POST['cijena'];
    $kolicina = (int)$_POST['kolicina'];
    $dobavljac_id = (int)$_POST['dobavljac_id'];
    
    $insert_stmt = $conn->prepare("INSERT INTO proizvod (Naziv, Cijena, Kolicina, DobavljacID) VALUES (?, ?, ?, ?)");
    $insert_stmt->bind_param("sdii", $naziv, $cijena, $kolicina, $dobavljac_id);
    
    if ($insert_stmt->execute()) {
        header("Location: /trgovina/admin/index.php?success=Proizvod+je+uspješno+dodan");
        exit();
    } else {
        $error = "Greška pri dodavanju proizvoda: " . $conn->error;
    }
}

// Dohvat dobavljača za dropdown
$dobavljaci = $conn->query("SELECT IDDobavljac, Ime FROM dobavljac");

include __DIR__ . '/../includes/header.php';
?>

<div class="container mt-5">
    <h2><i class="bi bi-plus-circle"></i> Dodaj novi proizvod</h2>
    
    <?php if (isset($error)): ?>
        <div class="alert alert-danger"><?= $error ?></div>
    <?php endif; ?>
    
    <form method="post">
        <div class="mb-3">
            <label for="naziv" class="form-label">Naziv proizvoda</label>
            <input type="text" class="form-control" id="naziv" name="naziv" required>
        </div>
        
        <div class="mb-3">
            <label for="cijena" class="form-label">Cijena (HRK)</label>
            <input type="number" step="0.01" class="form-control" id="cijena" name="cijena" required>
        </div>
        
        <div class="mb-3">
            <label for="kolicina" class="form-label">Količina</label>
            <input type="number" class="form-control" id="kolicina" name="kolicina" required>
        </div>
        
        <div class="mb-3">
            <label for="dobavljac_id" class="form-label">Dobavljač</label>
            <select class="form-select" id="dobavljac_id" name="dobavljac_id" required>
                <option value="">Odaberite dobavljača</option>
                <?php while($dobavljac = $dobavljaci->fetch_assoc()): ?>
                    <option value="<?= $dobavljac['IDDobavljac'] ?>"><?= htmlspecialchars($dobavljac['Ime']) ?></option>
                <?php endwhile; ?>
            </select>
        </div>
        
        <button type="submit" class="btn btn-primary">Dodaj proizvod</button>
        <a href="/trgovina/admin/index.php" class="btn btn-secondary">Odustani</a>
    </form>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>