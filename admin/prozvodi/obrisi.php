<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/trgovina/includes/auth_check.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/trgovina/includes/db_connection.php';

header('Content-Type: application/json');

try {
    if (!isset($_GET['id'])) {
        throw new Exception('Nije pronađen ID proizvoda');
    }

    $id = (int)$_GET['id'];
    $stmt = $conn->prepare("DELETE FROM proizvod WHERE IDProizvod = ?"); 
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        throw new Exception($stmt->error);
    }
} catch (Exception $e) {
    http_response_code(400);
    echo json_encode(['error' => $e->getMessage()]);
}
exit();
?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Potvrdi brisanje
    document.querySelectorAll('a[onclick]').forEach(link => {
        link.addEventListener('click', function(e) {
            if (!confirm('Jeste li sigurni da želite obrisati ovaj proizvod?')) {
                e.preventDefault();
                return;
            }
            
            // AJAX brisanje
            e.preventDefault();
            const id = this.getAttribute('href').split('id=')[1];
            
            fetch('obrisi.php?id=' + id, {
                method: 'GET',
                headers: {
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    window.location.href = '../index.php?success=1';
                } else {
                    alert(data.error || 'Došlo je do greške prilikom brisanja');
                }
            });
        });
    });
});
</script>