<?php
include __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/db_connection.php';

// Dohvat proizvoda iz baze
$sql = "SELECT p.*, d.Ime AS DobavljacIme, pr.Ime AS ProizvodacIme 
        FROM proizvod p
        JOIN dobavljac d ON p.DobavljacID = d.IDDobavljac
        JOIN proizvodac pr ON p.ProizvodacID = pr.IDProizvodac";
$result = $conn->query($sql);
?>

<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="bi bi-list-ul"></i> Naši proizvodi</h2>
        <a href="admin/" class="btn btn-outline-primary">
            <i class="bi bi-person-lock"></i> Admin pristup
        </a>
    </div>

    <div class="table-responsive">
        <table class="table table-striped table-hover align-middle">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Naziv</th>
                    <th>Dobavljač</th>
                    <th>Proizvođač</th>
                    <th class="text-end">Cijena (HRK)</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result->num_rows > 0): ?>
                    <?php while($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['IDProizvod']) ?></td>
                        <td class="fw-bold"><?= htmlspecialchars($row['Naziv']) ?></td>
                        <td>
                            <a href="pages/dobavljaci/single.php?id=<?= $row['DobavljacID'] ?>" 
                               class="text-decoration-none">
                                <?= htmlspecialchars($row['DobavljacIme']) ?>
                            </a>
                        </td>
                        <td>
                            <a href="pages/proizvodaci/single.php?id=<?= $row['ProizvodacID'] ?>" 
                               class="text-decoration-none">
                                <?= htmlspecialchars($row['ProizvodacIme']) ?>
                            </a>
                        </td>
                        <td class="text-end text-success fw-bold">
                            <?= number_format($row['Cijena'], 2) ?>
                        </td>
                        <td>
                            <span class="badge bg-<?= $row['Kolicina'] > 0 ? 'success' : 'danger' ?>">
                                <?= $row['Kolicina'] > 0 ? 'Na zalihi' : 'Nedostupno' ?>
                            </span>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" class="text-center py-4">
                            <div class="alert alert-warning">
                                <i class="bi bi-exclamation-triangle"></i> Trenutno nema proizvoda
                            </div>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include __DIR__ . '/includes/footer.php'; ?>