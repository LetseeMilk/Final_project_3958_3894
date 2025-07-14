<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>
<link href="../assets/bootstrap/css/bootstrap.min.css" rel="stylesheet">
<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
    <div class="container-fluid">
        <a class="navbar-brand fw-bold" href="/accueil.php">MonSite</a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#menuPrincipal">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="menuPrincipal">
            <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link" href="../pages/liste_obj.php">Liste des objets</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="../pages/profil.php">Profil</a>
                </li>
                <li class="nav-item">
                    <form action="../pages/logout.php" method="post" class="d-inline">
                        <button class="btn btn-danger btn-sm ms-2" type="submit">Déconnexion</button>
                    </form>
                </li>
            </ul>
        </div>
    </div>
</nav>
