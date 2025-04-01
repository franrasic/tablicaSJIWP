<?php if (isset($_SESSION['admin_logged_in'])): ?>
    <li class="nav-item">
        <a class="nav-link" href="../admin/">
            <i class="bi bi-speedometer2"></i> Admin Panel
        </a>
    </li>
<?php endif; ?>


<nav class="navbar navbar-expand-lg navbar-dark bg-primary fixed-top">
    <div class="container">
        <a class="navbar-brand" href="../">
            <i class="bi bi-shop"></i> Trgovina
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" href="../pages/proizvodi/">
                        <i class="bi bi-list-ul"></i> Proizvodi
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="../pages/dobavljaci/">
                        <i class="bi bi-truck"></i> Dobavljači
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>