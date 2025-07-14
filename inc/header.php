<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<link rel="stylesheet" href="../assets/css/header.css">
<link rel="stylesheet" href="../assets/bootstrap-icons/bootstrap-icons.css">

<nav class="navbar navbar-expand-lg navbar-custom shadow-sm">
    <div class="container">
        <a class="navbar-brand text-white fw-bold" href="../pages/liste_obj.php">
            <i class="bi bi-house-door-fill me-1"></i> Emprunt-site
        </a>

        <button class="navbar-toggler text-white" type="button" data-bs-toggle="collapse" data-bs-target="#menuPrincipal">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse justify-content-center" id="menuPrincipal">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" href="../pages/liste_obj.php">
                        <i class="bi bi-box-seam"></i> Objets
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="../pages/profil.php">
                        <i class="bi bi-person-circle"></i> Profil
                    </a>
                </li>
                
            </ul>
        </div>

        <form action="/logout.php" method="post" class="d-flex ms-auto">
            <button type="submit" class="btn-deconnexion">
                <i class="bi bi-box-arrow-right"></i> Déconnexion
            </button>
        </form>
    </div>
</nav>
