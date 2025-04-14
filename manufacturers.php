<?php
require_once 'includes/auth.php';
require_once 'includes/config.php';

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add_manufacturer'])) {
        try {
            $stmt = $conn->prepare("INSERT INTO proizvodac (Ime, Kontakt, Adresa) 
                                   VALUES (:ime, :kontakt, :adresa)");
            
            $stmt->bindParam(':ime', $_POST['ime']);
            $stmt->bindParam(':kontakt', $_POST['kontakt'], PDO::PARAM_INT);
            $stmt->bindParam(':adresa', $_POST['adresa']);
            
            $stmt->execute();
            $success = "Manufacturer added successfully!";
        } catch(PDOException $e) {
            $error = "Error adding manufacturer: " . $e->getMessage();
        }
    }
}

// Get all manufacturers
try {
    $manufacturers = $conn->query("SELECT * FROM proizvodac ORDER BY Ime")->fetchAll();
} catch(PDOException $e) {
    $error = "Database error: " . $e->getMessage();
}

require_once 'includes/header.php';
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>Manufacturers Management</h1>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addManufacturerModal">
        <i class="fas fa-plus"></i> Add Manufacturer
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
                        <th>Name</th>
                        <th>Contact</th>
                        <th>Address</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($manufacturers as $manufacturer): ?>
                    <tr>
                        <td><?= htmlspecialchars($manufacturer['IDProizvodac']) ?></td>
                        <td><?= htmlspecialchars($manufacturer['Ime']) ?></td>
                        <td><?= htmlspecialchars($manufacturer['Kontakt']) ?></td>
                        <td><?= htmlspecialchars($manufacturer['Adresa']) ?></td>
                        <td>
                            <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#editManufacturerModal<?= $manufacturer['IDProizvodac'] ?>">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#deleteManufacturerModal<?= $manufacturer['IDProizvodac'] ?>">
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

<!-- Add Manufacturer Modal -->
<div class="modal fade" id="addManufacturerModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add New Manufacturer</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="ime" class="form-label">Manufacturer Name</label>
                        <input type="text" class="form-control" id="ime" name="ime" required>
                    </div>
                    <div class="mb-3">
                        <label for="kontakt" class="form-label">Contact Number</label>
                        <input type="number" class="form-control" id="kontakt" name="kontakt" required>
                    </div>
                    <div class="mb-3">
                        <label for="adresa" class="form-label">Address</label>
                        <textarea class="form-control" id="adresa" name="adresa" rows="3" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" name="add_manufacturer" class="btn btn-primary">Add Manufacturer</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>