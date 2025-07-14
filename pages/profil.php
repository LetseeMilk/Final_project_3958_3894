<?php
session_start();
require("../inc/connexion.php");

if (!isset($_SESSION['id_membre'])) {
    header('Location: login.php');
    exit();
}

$id_membre = (int) $_SESSION['id_membre'];

$query = "SELECT * FROM e_membre WHERE id_membre = $id_membre";
$result = mysqli_query($dataBase, $query);
$membre = mysqli_fetch_assoc($result);

if (!$membre) {
    die("Membre non trouvé");
}

$dateNaissance = new DateTime($membre['date_de_naissance']);
$aujourdhui = new DateTime();
$age = $aujourdhui->diff($dateNaissance)->y;

$queryObjets = "SELECT o.*, c.nom_categorie 
                FROM e_objet o 
                JOIN e_categorie_objet c ON o.id_categorie = c.id_categorie 
                WHERE o.id_membre = $id_membre";
$resultObjets = mysqli_query($dataBase, $queryObjets);
$objets = [];
while ($row = mysqli_fetch_assoc($resultObjets)) {
    $objets[] = $row;
}

$queryEmprunts = "SELECT e.id_emprunt, e.date_emprunt, e.date_retour, e.etat_retour, o.nom_objet, m.nom AS nom_proprietaire
                  FROM e_emprunt e
                  JOIN e_objet o ON e.id_objet = o.id_objet
                  JOIN e_membre m ON o.id_membre = m.id_membre
                  WHERE e.id_membre = $id_membre";
$resultEmprunts = mysqli_query($dataBase, $queryEmprunts);
$emprunts = [];
while ($row = mysqli_fetch_assoc($resultEmprunts)) {
    $emprunts[] = $row;
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil de <?= $membre['nom'] ?></title>
    <link href="../assets/bootstrap/css/bootstrap.min.css" rel="stylesheet" />
    <style>
        .profile-img {
            width: 150px;
            height: 150px;
            object-fit: cover;
            border-radius: 50%;
        }
        .card {
            margin-bottom: 20px;
        }
        .centered-container {
            max-width: 800px;
            margin: 0 auto;
        }
    </style>
</head>
<body class="bg-light">

<?php include '../inc/header.php'; ?>

<div class="container py-5">
    <div class="centered-container">
        <div class="card shadow-sm mb-4">
            <div class="card-body text-center">
                <?php if ($membre['id_image_profil']): ?>
                    <img src="../uploads/<?= $membre['id_image_profil'] ?>" alt="Photo de profil" class="profile-img mb-3">
                <?php else: ?>
                    <div class="profile-img mb-3 bg-secondary d-flex align-items-center justify-content-center">
                        <span class="text-white">Pas de photo</span>
                    </div>
                <?php endif; ?>
                <h3><?= $membre['nom'] ?></h3>
                <p class="text-muted"><?= $membre['ville'] ?></p>
            </div>
        </div>

        <div class="card shadow-sm mb-4">
            <div class="card-header">
                <h4>Informations personnelles</h4>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <p><strong>Email :</strong> <?= $membre['email'] ?></p>
                        <p><strong>Ville :</strong> <?= $membre['ville'] ?></p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>Date de naissance :</strong> <?= date('d/m/Y', strtotime($membre['date_de_naissance'])) ?> (<?= $age ?> ans)</p>
                        <p><strong>Genre :</strong> <?= $membre['genre'] ?></p>
                    </div>
                </div>
            </div>
        </div>

        <div class="card shadow-sm mb-4">
            <div class="card-header">
                <h4>Mes objets (<?= count($objets) ?>)</h4>
            </div>
            <div class="card-body">
                <?php if (count($objets) > 0): ?>
                    <div class="row">
                        <?php foreach ($objets as $objet): ?>
                            <div class="col-md-4 mb-3">
                                <div class="card">
                                    <div class="card-body">
                                        <h5 class="card-title"><?= $objet['nom_objet'] ?></h5>
                                        <p class="card-text text-muted"><?= $objet['nom_categorie'] ?></p>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <p class="text-muted">Vous n'avez pas encore d'objets enregistrés.</p>
                <?php endif; ?>
            </div>
        </div>

        <div class="card shadow-sm">
    <div class="card-header">
        <h4>Mes emprunts (<?= count($emprunts) ?>)</h4>
    </div>
    <div class="card-body">
        <?php if (count($emprunts) > 0): ?>
            <div class="table-responsive">
    <table class="table">
        <thead>
            <tr>
                <th>Objet</th>
                <th>Propriétaire</th>
                <th>Date emprunt</th>
                <th>Date retour</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($emprunts as $emprunt): ?>
                <tr>
                    <td><?= $emprunt['nom_objet'] ?></td>
                    <td><?= $emprunt['nom_proprietaire'] ?></td>
                    <td><?= date('d/m/Y', strtotime($emprunt['date_emprunt'])) ?></td>
                    <td>
                        <?php if ($emprunt['date_retour']): ?>
                            <?php if ($emprunt['etat_retour']): ?>
                                <span class="badge bg-success">Retourné (<?= $emprunt['etat_retour'] ?>)</span>
                            <?php else: ?>
                                <span class="badge bg-secondary">Retour en attente</span>
                            <?php endif; ?>
                        <?php else: ?>
                            <span class="badge bg-warning">En cours</span>
                        <?php endif; ?>
                    </td>

                <td>
                    <?php if (!$emprunt['date_retour']): ?>
                        <form method="post" action="traiter_retour.php" class="row g-2">
                            <input type="hidden" name="id_emprunt" value="<?= $emprunt['id_emprunt'] ?>">
                            <div class="col-auto">
                                <select name="etat_retour" class="form-select form-select-sm" required>
                                    <option value="">État</option>
                                    <option value="bon">OK</option>
                                    <option value="abime">Abîmé</option>
                                </select>
                            </div>
                            <div class="col-auto">
                                <button type="submit" class="btn btn-sm btn-primary">Valider retour</button>
                            </div>
                        </form>
                    <?php else: ?>
                        <?php if ($emprunt['etat_retour']): ?>
                            <span class="badge bg-success">Retourné (<?= htmlspecialchars($emprunt['etat_retour']) ?>)</span>
                        <?php else: ?>
                            <span class="badge bg-secondary">Retour en attente</span>
                        <?php endif; ?>
                    <?php endif; ?>
                </td>

                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
        <?php else: ?>
            <p class="text-muted">Vous n'avez pas encore effectué d'emprunts.</p>
        <?php endif; ?>
    </div>
</div>
    </div>
</div>

<script src="../assets/bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>
