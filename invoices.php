<?php
require_once 'includes/auth.php';
require_once 'includes/config.php';

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add_invoice'])) {
        try {
            // Start transaction
            $conn->beginTransaction();
            
            // Insert invoice
            $stmt = $conn->prepare("INSERT INTO racun (UkupnaCijena, Datum) 
                                   VALUES (:ukupna_cijena, NOW())");
            $stmt->bindParam(':ukupna_cijena', $_POST['ukupna_cijena'], PDO::PARAM_INT);
            $stmt->execute();
            $invoiceId = $conn->lastInsertId();
            
            // Insert invoice items
            foreach ($_POST['items'] as $item) {
                $stmt = $conn->prepare("INSERT INTO stavka_racuna (RacunID, ProizvodID, Cijena, Kolicina) 
                                       VALUES (:racun_id, :proizvod_id, :cijena, :kolicina)");
                $stmt->bindParam(':racun_id', $invoiceId, PDO::PARAM_INT);
                $stmt->bindParam(':proizvod_id', $item['proizvod_id'], PDO::PARAM_INT);
                $stmt->bindParam(':cijena', $item['cijena'], PDO::PARAM_INT);
                $stmt->bindParam(':kolicina', $item['kolicina'], PDO::PARAM_INT);
                $stmt->execute();
            }
            
            $conn->commit();
            $success = "Invoice added successfully!";
        } catch(PDOException $e) {
            $conn->rollBack();
            $error = "Error adding invoice: " . $e->getMessage();
        }
    }
}

// Get all invoices with their items
try {
    // Get invoices
    $invoices = $conn->query("SELECT * FROM racun ORDER BY Datum DESC")->fetchAll();
    
    // Get products for dropdown
    $products = $conn->query("SELECT IDProizvod, Naziv, Cijena FROM proizvod ORDER BY Naziv")->fetchAll();
} catch(PDOException $e) {
    $error = "Database error: " . $e->getMessage();
}

require_once 'includes/header.php';
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>Invoices Management</h1>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addInvoiceModal">
        <i class="fas fa-plus"></i> Dodaj Račun
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
                        <th>Datum</th>
                        <th>Ukupno</th>
                        <th>Detalji</th>
                        <th>Akcije</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($invoices as $invoice): ?>
                    <tr>
                        <td><?= htmlspecialchars($invoice['IDRacun']) ?></td>
                        <td><?= date('d.m.Y H:i', strtotime($invoice['Datum'])) ?></td>
                        <td><?= htmlspecialchars($invoice['UkupnaCijena']) ?> HRK</td>
                        <td>
                            <button class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#invoiceDetailsModal<?= $invoice['IDRacun'] ?>">
                                <i class="fas fa-eye"></i>Pogledaj stavke
                            </button>
                        </td>
                        <td>
                            <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#editInvoiceModal<?= $invoice['IDRacun'] ?>">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#deleteInvoiceModal<?= $invoice['IDRacun'] ?>">
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

<!-- Add Invoice Modal -->
<div class="modal fade" id="addInvoiceModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Dodaj račun</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" id="invoiceForm">
                <div class="modal-body">
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label class="form-label">Datum Računa</label>
                            <input type="text" class="form-control" value="<?= date('d.m.Y H:i') ?>" readonly>
                        </div>
                        <div class="col-md-6">
                            <label for="ukupna_cijena" class="form-label">Ukupno (HRK)</label>
                            <input type="number" class="form-control" id="ukupna_cijena" name="ukupna_cijena" readonly>
                        </div>
                    </div>
                    
                    <h5 class="mb-3">Stavke računa</h5>
                    <div id="invoiceItems">
                        <div class="row item-row mb-3">
                            <div class="col-md-5">
                                <select class="form-select product-select" name="items[0][proizvod_id]" required>
                                    <option value="">Odaberi Proizvod</option>
                                    <?php foreach ($products as $product): ?>
                                        <option value="<?= $product['IDProizvod'] ?>" data-price="<?= $product['Cijena'] ?>">
                                            <?= htmlspecialchars($product['Naziv']) ?> (<?= $product['Cijena'] ?> HRK)
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <input type="number" class="form-control price-input" name="items[0][cijena]" placeholder="Price" readonly>
                            </div>
                            <div class="col-md-2">
                                <input type="number" class="form-control quantity-input" name="items[0][kolicina]" placeholder="Qty" min="1" required>
                            </div>
                            <div class="col-md-2">
                                <input type="text" class="form-control total-input" placeholder="Total" readonly>
                            </div>
                            <div class="col-md-1">
                                <button type="button" class="btn btn-danger remove-item"><i class="fas fa-times"></i></button>
                            </div>
                        </div>
                    </div>
                    
                    <button type="button" id="addItemBtn" class="btn btn-sm btn-secondary mt-2">
                        <i class="fas fa-plus"></i> Dodaj Stavku
                    </button>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" name="add_invoice" class="btn btn-primary">Create Invoice</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Add new item row
    let itemCount = 1;
    document.getElementById('addItemBtn').addEventListener('click', function() {
        const newRow = document.querySelector('.item-row').cloneNode(true);
        const newIndex = itemCount++;
        
        // Update all names and IDs
        newRow.querySelectorAll('[name]').forEach(el => {
            const name = el.getAttribute('name').replace(/\[\d+\]/, `[${newIndex}]`);
            el.setAttribute('name', name);
            el.value = '';
        });
        
        // Clear values
        newRow.querySelector('.product-select').selectedIndex = 0;
        newRow.querySelector('.price-input').value = '';
        newRow.querySelector('.quantity-input').value = '';
        newRow.querySelector('.total-input').value = '';
        
        document.getElementById('invoiceItems').appendChild(newRow);
    });
    
    // Remove item row
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('remove-item')) {
            if (document.querySelectorAll('.item-row').length > 1) {
                e.target.closest('.item-row').remove();
                calculateTotal();
            }
        }
    });
    
    // Product selection change
    document.addEventListener('change', function(e) {
        if (e.target.classList.contains('product-select')) {
            const selectedOption = e.target.options[e.target.selectedIndex];
            const price = selectedOption.getAttribute('data-price') || 0;
            const priceInput = e.target.closest('.item-row').querySelector('.price-input');
            priceInput.value = price;
            calculateRowTotal(e.target.closest('.item-row'));
        }
    });
    
    // Quantity change
    document.addEventListener('input', function(e) {
        if (e.target.classList.contains('quantity-input')) {
            calculateRowTotal(e.target.closest('.item-row'));
        }
    });
    
    // Calculate row total
    function calculateRowTotal(row) {
        const price = parseFloat(row.querySelector('.price-input').value) || 0;
        const quantity = parseFloat(row.querySelector('.quantity-input').value) || 0;
        const total = price * quantity;
        row.querySelector('.total-input').value = total.toFixed(2);
        calculateTotal();
    }
    
    // Calculate invoice total
    function calculateTotal() {
        let total = 0;
        document.querySelectorAll('.item-row').forEach(row => {
            const rowTotal = parseFloat(row.querySelector('.total-input').value) || 0;
            total += rowTotal;
        });
        document.getElementById('ukupna_cijena').value = total.toFixed(2);
    }
});
</script>

<?php require_once 'includes/footer.php'; ?>