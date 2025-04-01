<?php
session_start();
require_once __DIR__ . '/../includes/db_connection.php';
require_once __DIR__ . '/../includes/auth_check.php';

// Provjera admin sesije
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: /trgovina/admin/login.php");
    exit();
}

// Dohvat proizvoda iz baze
$sql = "SELECT p.*, d.Ime AS DobavljacIme 
        FROM proizvod p
        JOIN dobavljac d ON p.DobavljacID = d.IDDobavljac";
$result = $conn->query($sql);

include __DIR__ . '/../includes/header.php';
?>

<?php
// Debug ispis
error_log("Pristup uredi.php sa ID: " . ($_GET['id'] ?? 'N/A'));

require_once $_SERVER['DOCUMENT_ROOT'] . '/trgovina/includes/auth_check.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/trgovina/includes/db_connection.php';


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


?>


<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="bi bi-speedometer2"></i> Admin Dashboard</h2>
        <a href="/trgovina/admin/logout.php" class="btn btn-outline-danger">
            <i class="bi bi-box-arrow-right"></i> Odjava
        </a>
    </div>
    
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h4 class="m-0 font-weight-bold text-primary">
                <i class="bi bi-box-seam"></i> Upravljanje proizvodima
            </h4>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead class="thead-light">
                        <tr>
                            <th>ID</th>
                            <th>Naziv</th>
                            <th>Dobavljač</th>
                            <th>Cijena</th>
                            <th>Količina</th>
                            <th>Akcije</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($result->num_rows > 0): ?>
                            <?php while($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td><?= $row['IDProizvod'] ?></td>
                                <td><?= htmlspecialchars($row['Naziv']) ?></td>
                                <td><?= htmlspecialchars($row['DobavljacIme']) ?></td>
                                <td><?= number_format($row['Cijena'], 2) ?> HRK</td>
                                <td>
                                    <span class="badge bg-<?= $row['Kolicina'] > 0 ? 'success' : 'danger' ?>">
                                        <?= $row['Kolicina'] ?>
                                    </span>
                                </td>
                                <td>
                                <!-- Button trigger modal -->
<button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">
  Launch demo modal
</button>
                                       
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
                                    
                                    <a href="/trgovina/admin/proizvodi/obrisi.php?id=<?= $row['IDProizvod'] ?>" class="btn btn-sm btn-danger">
                                      
                                        <i class="bi bi-trash"></i> Obriši
                                    </a>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" class="text-center">Nema proizvoda u bazi</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            <a href="/trgovina/admin/proizvodi/dodaj.php" class="btn btn-success">
                <i class="bi bi-plus-circle"></i> Dodaj novi proizvod
            </a>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>