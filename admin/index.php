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

<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="bi bi-speedometer2"></i> Admin Dashboard</h2>
        <a href="/trgovina/admin/logout.php" class="btn btn-outline-danger">
            <i class="bi bi-box-arrow-right"></i> Odjava
        </a>
    </div>
    
    <?php if (isset($_GET['success'])): ?>
        <div class="alert alert-success"><?= htmlspecialchars($_GET['success']) ?></div>
    <?php endif; ?>
    
    <?php if (isset($_GET['error'])): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($_GET['error']) ?></div>
    <?php endif; ?>
    
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
                                    <a href="/trgovina/admin/proizvodi/uredi.php?id=<?= $row['IDProizvod'] ?>" 
                                       class="btn btn-sm btn-primary">
                                        <i class="bi bi-pencil"></i> Uredi
                                    </a>
                                    <a href="/trgovina/admin/proizvodi/obrisi.php?id=<?= $row['IDProizvod'] ?>" 
                                       class="btn btn-sm btn-danger" 
                                       onclick="return confirm('Jeste li sigurni da želite obrisati ovaj proizvod?');">
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