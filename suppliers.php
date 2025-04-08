<?php
require_once 'includes/auth.php';
require_once 'includes/config.php';

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add_supplier'])) {
        try {
            $stmt = $conn->prepare("INSERT INTO dobavljac (Ime, Kontakt, Kolicina, Adresa) 
                                   VALUES (:ime, :kontakt, :kolicina, :adresa)");
            
            $stmt->bindParam(':ime', $_POST['ime']);
            $stmt->bindParam(':kontakt', $_POST['kontakt'], PDO::PARAM_INT);
            $stmt->bindParam(':kolicina', $_POST['kolicina'], PDO::PARAM_INT);
            $stmt->bindParam(':adresa', $_POST['adresa']);
            
            $stmt->execute();
            $success = "Supplier added successfully!";
        } catch(PDOException $e) {
            $error = "Error adding supplier: " . $e->getMessage();
        }
    }
}

// Get all suppliers
try {
    $suppliers = $conn->query("SELECT * FROM dobavljac ORDER BY Ime")->fetchAll();
} catch(PDOException $e) {
    $error = "Database error: " . $e->getMessage();
}

require_once 'includes/header.php';
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>Upravitelj Dobavljača</h1>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addSupplierModal">
        <i class="fas fa-plus"></i> Dodaj Dobavljača
    </button>
</div>

<?php if (isset($success)): ?>
    <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
<?php endif; ?>

<?php if (isset($error)): ?>
    <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
<?php endif; ?>

<div class="card shadow">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Ime</th>
                        <th>Kontakt</th>
                        <th>Količina</th>
                        <th>Adresa</th>
                        <th>Akcije</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($suppliers as $supplier): ?>
                    <tr>
                        <td><?= htmlspecialchars($supplier['IDDobavljac']) ?></td>
                        <td><?= htmlspecialchars($supplier['Ime']) ?></td>
                        <td><?= htmlspecialchars($supplier['Kontakt']) ?></td>
                        <td><?= htmlspecialchars($supplier['Kolicina']) ?></td>
                        <td><?= htmlspecialchars($supplier['Adresa']) ?></td>
                        <td>
                            <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#editSupplierModal<?= $supplier['IDDobavljac'] ?>">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#deleteSupplierModal<?= $supplier['IDDobavljac'] ?>">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Add Supplier Modal -->
<div class="modal fade" id="addSupplierModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Dodaj dobavljača</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="ime" class="form-label">Ime Dobavljača</label>
                        <input type="text" class="form-control" id="ime" name="ime" required>
                    </div>
                    <div class="mb-3">
                        <label for="kontakt" class="form-label">Kontakt</label>
                        <input type="number" class="form-control" id="kontakt" name="kontakt" required>
                    </div>
                    <div class="mb-3">
                        <label for="kolicina" class="form-label">Količina</label>
                        <input type="number" class="form-control" id="kolicina" name="kolicina" min="0" required>
                    </div>
                    <div class="mb-3">
                        <label for="adresa" class="form-label">Adresa</label>
                        <textarea class="form-control" id="adresa" name="adresa" rows="3" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Zatvori</button>
                    <button type="submit" name="add_supplier" class="btn btn-primary">Dodaj dobavaljača</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>s