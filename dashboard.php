<?php
require_once 'includes/auth.php';
require_once 'includes/config.php';

try {
    // Get counts for dashboard
    $productCount = $conn->query("SELECT COUNT(*) FROM proizvod")->fetchColumn();
    $supplierCount = $conn->query("SELECT COUNT(*) FROM dobavljac")->fetchColumn();
    $manufacturerCount = $conn->query("SELECT COUNT(*) FROM proizvodac")->fetchColumn();
    $invoiceCount = $conn->query("SELECT COUNT(*) FROM racun")->fetchColumn();
    
    // Get recent invoices
    $recentInvoices = $conn->query("SELECT * FROM racun ORDER BY Datum DESC LIMIT 5")->fetchAll();
    
    // Get low stock products
    $lowStockProducts = $conn->query("SELECT * FROM proizvod WHERE Kolicina < 20 ORDER BY Kolicina ASC LIMIT 5")->fetchAll();
} catch(PDOException $e) {
    $error = "Database error: " . $e->getMessage();
}

require_once 'includes/header.php';
?>

<h1 class="mb-4">Nadzorna ploča</h1>

<?php if (isset($error)): ?>
    <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
<?php endif; ?>

<div class="row mb-4">
    <div class="col-md-3">
        <div class="card text-white bg-primary mb-3">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="card-title">Proizvodi</h5>
                        <h2 class="mb-0"><?= $productCount ?></h2>
                    </div>
                    <i class="fas fa-box fa-3x"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-white bg-success mb-3">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="card-title">Dobavljači</h5>
                        <h2 class="mb-0"><?= $supplierCount ?></h2>
                    </div>
                    <i class="fas fa-truck fa-3x"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-white bg-info mb-3">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="card-title">Proizvođaći</h5>
                        <h2 class="mb-0"><?= $manufacturerCount ?></h2>
                    </div>
                    <i class="fas fa-industry fa-3x"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-white bg-warning mb-3">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="card-title">Računi</h5>
                        <h2 class="mb-0"><?= $invoiceCount ?></h2>
                    </div>
                    <i class="fas fa-file-invoice fa-3x"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="card shadow mb-4">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Nedavni računi</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Datum</th>
                                <th>Ukupno</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($recentInvoices as $invoice): ?>
                            <tr>
                                <td><?= $invoice['IDRacun'] ?></td>
                                <td><?= date('d.m.Y H:i', strtotime($invoice['Datum'])) ?></td>
                                <td><?= $invoice['UkupnaCijena'] ?> HRK</td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card shadow mb-4">
            <div class="card-header bg-danger text-white">
                <h5 class="mb-0">Proizvodi male količine</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Proizvod</th>
                                <th>Količina</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($lowStockProducts as $product): ?>
                            <tr>
                                <td><?= htmlspecialchars($product['Naziv']) ?></td>
                                <td class="<?= $product['Kolicina'] < 10 ? 'text-danger fw-bold' : 'text-warning' ?>">
                                    <?= $product['Kolicina'] ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>