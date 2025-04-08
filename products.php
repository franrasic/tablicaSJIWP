<?php
require_once 'includes/auth.php';
require_once 'includes/config.php';

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add_product'])) {
        try {
            $stmt = $conn->prepare("INSERT INTO proizvod (Naziv, DobavljacID, ProizvodacID, Cijena, Kolicina) 
                                    VALUES (:naziv, :dobavljac_id, :proizvodac_id, :cijena, :kolicina)");
            
            $stmt->bindParam(':naziv', $_POST['naziv']);
            $stmt->bindParam(':dobavljac_id', $_POST['dobavljac_id'], PDO::PARAM_INT);
            $stmt->bindParam(':proizvodac_id', $_POST['proizvodac_id'], PDO::PARAM_INT);
            $stmt->bindParam(':cijena', $_POST['cijena'], PDO::PARAM_INT);
            $stmt->bindParam(':kolicina', $_POST['kolicina'], PDO::PARAM_INT);
            
            $stmt->execute();
            $success = "Product added successfully!";
        } catch(PDOException $e) {
            $error = "Error adding product: " . $e->getMessage();
        }
    }
}

// Get all products with related data
try {
    $stmt = $conn->prepare("
        SELECT p.*, d.Ime AS DobavljacIme, pr.Ime AS ProizvodacIme 
        FROM proizvod p
        JOIN dobavljac d ON p.DobavljacID = d.IDDobavljac
        JOIN proizvodac pr ON p.ProizvodacID = pr.IDProizvodac
        ORDER BY p.Naziv
    ");
    $stmt->execute();
    $products = $stmt->fetchAll();
    
    // Get suppliers and manufacturers for dropdowns
    $suppliers = $conn->query("SELECT IDDobavljac, Ime FROM dobavljac ORDER BY Ime")->fetchAll();
    $manufacturers = $conn->query("SELECT IDProizvodac, Ime FROM proizvodac ORDER BY Ime")->fetchAll();
} catch(PDOException $e) {
    $error = "Database error: " . $e->getMessage();
}

require_once 'includes/header.php';
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>Upravljanje proizvodima</h1>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addProductModal">
        <i class="fas fa-plus"></i> Dodaj Proizvod
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
                        <th>Naziv</th>
                        <th>Dobavljac</th>
                        <th>Proizvođać</th>
                        <th>Cijena</th>
                        <th>Količina</th>
                        <th>Akcije</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($products as $product): ?>
                    <tr>
                        <td><?= htmlspecialchars($product['IDProizvod']) ?></td>
                        <td><?= htmlspecialchars($product['Naziv']) ?></td>
                        <td><?= htmlspecialchars($product['DobavljacIme']) ?></td>
                        <td><?= htmlspecialchars($product['ProizvodacIme']) ?></td>
                        <td><?= htmlspecialchars($product['Cijena']) ?> HRK</td>
                        <td><?= htmlspecialchars($product['Kolicina']) ?></td>
                        <td>
                            <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#editProductModal<?= $product['IDProizvod'] ?>">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#deleteProductModal<?= $product['IDProizvod'] ?>">
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

<!-- Add Product Modal -->
<div class="modal fade" id="addProductModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Dodaj novi proizvod</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="naziv" class="form-label">Ime proizvoda</label>
                        <input type="text" class="form-control" id="naziv" name="naziv" required>
                    </div>
                    <div class="mb-3">
                        <label for="dobavljac_id" class="form-label">Dobavljač</label>
                        <select class="form-select" id="dobavljac_id" name="dobavljac_id" required>
                            <option value="">Select Supplier</option>
                            <?php foreach ($suppliers as $supplier): ?>
                                <option value="<?= $supplier['IDDobavljac'] ?>"><?= htmlspecialchars($supplier['Ime']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="proizvodac_id" class="form-label">Proizvođać</label>
                        <select class="form-select" id="proizvodac_id" name="proizvodac_id" required>
                            <option value="">Select Manufacturer</option>
                            <?php foreach ($manufacturers as $manufacturer): ?>
                                <option value="<?= $manufacturer['IDProizvodac'] ?>"><?= htmlspecialchars($manufacturer['Ime']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="cijena" class="form-label">Cijena (HRK)</label>
                        <input type="number" class="form-control" id="cijena" name="cijena" min="0" required>
                    </div>
                    <div class="mb-3">
                        <label for="kolicina" class="form-label">Količina</label>
                        <input type="number" class="form-control" id="kolicina" name="kolicina" min="0" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Zatvori</button>
                    <button type="submit" name="add_product" class="btn btn-primary">Dodaj proizvod</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>