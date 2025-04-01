<?php
// Debug ispis
error_log("Pristup uredi.php sa ID: " . ($_GET['id'] ?? 'N/A'));

require_once $_SERVER['DOCUMENT_ROOT'] . '/trgovina/includes/auth_check.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/trgovina/includes/db_connection.php';
check_admin_session();

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

include $_SERVER['DOCUMENT_ROOT'] . '/trgovina/includes/header.php';
?>
<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
      <div class="container mt-5">
    <h2><i class="bi bi-pencil-square"></i> Uređivanje proizvoda</h2>
    
    <form action="/trgovina/admin/proizvodi/process_update.php" method="post">
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
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary">Save changes</button>
      </div>
    </div>
  </div>
</div>



<?php include $_SERVER['DOCUMENT_ROOT'] . '/trgovina/includes/footer.php'; ?> 