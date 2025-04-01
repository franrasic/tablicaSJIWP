<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once $_SERVER['DOCUMENT_ROOT'] . '/trgovina/includes/auth_check.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/trgovina/includes/db_connection.php';
check_admin_session();

if (isset($_GET['error'])) {
    $error = htmlspecialchars($_GET['error']);
}

include __DIR__ . '/../../includes/header.php';
?>

<div class="container mt-5">
    <h2><i class="bi bi-plus-circle"></i> Dodaj novi proizvod</h2>
    
    <?php if (isset($error)): ?>
        <div class="alert alert-danger"><?= $error ?></div>
    <?php endif; ?>
    
    <form action="/trgovina/admin/proizvodi/process_add.php" method="post">
        <div class="mb-3">
            <label for="naziv" class="form-label">Naziv proizvoda</label>
            <input type="text" class="form-control" id="naziv" name="naziv" required>
            <div class="invalid-feedback">Unesite naziv proizvoda</div>
        </div>
        
        <div class="mb-3">
            <label for="cijena" class="form-label">Cijena (HRK)</label>
            <input type="number" step="0.01" class="form-control" id="cijena" name="cijena" required>
            <div class="invalid-feedback">Unesite ispravnu cijenu</div>
        </div>
        
        <button type="submit" class="btn btn-primary">Spremi proizvod</button>
        <a href="../index.php" class="btn btn-secondary">Natrag</a>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('form');
    const feedback = document.createElement('div');
    
    form.addEventListener('submit', async function(e) {
        e.preventDefault();
        
        if (!this.checkValidity()) {
            e.stopPropagation();
            this.classList.add('was-validated');
            return;
        }

        const submitBtn = this.querySelector('button[type="submit"]');
        const originalText = submitBtn.innerHTML;
        submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Spremanje...';
        submitBtn.disabled = true;

        try {
            const formData = new FormData(this);
            const response = await fetch('process_add.php', {
                method: 'POST',
                body: formData
            });
            
            const data = await response.json();
            
            if (data.success) {
                window.location.href = '../index.php?success=1';
            } else {
                feedback.className = 'alert alert-danger mt-3';
                feedback.innerHTML = data.error || 'Došlo je do greške prilikom dodavanja';
                form.after(feedback);
            }
        } catch (error) {
            feedback.className = 'alert alert-danger mt-3';
            feedback.innerHTML = 'Network error: ' + error.message;
            form.after(feedback);
        } finally {
            submitBtn.innerHTML = originalText;
            submitBtn.disabled = false;
        }
    });

    // Live validacija
    form.querySelectorAll('input').forEach(input => {
        input.addEventListener('input', function() {
            if (this.checkValidity()) {
                this.classList.remove('is-invalid');
                this.classList.add('is-valid');
            } else {
                this.classList.remove('is-valid');
                this.classList.add('is-invalid');
            }
        });
    });
});
</script>

<?php include __DIR__ . '/../../includes/footer.php'; ?>