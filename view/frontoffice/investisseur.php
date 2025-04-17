<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'investisseur') {
    header("Location: login.html");
    exit;
}
$user = $_SESSION['user'];
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Profil Investisseur - DealHub</title>
    <link rel="stylesheet" href="styleentre.css"> <!-- Make sure the path is correct -->
</head>
<body>

    <!-- Header -->
    <header>
        <nav>
            <ul>
                <li><a href="home.html">Accueil</a></li>
                <li><a href="#">Mon Profil</a></li>
                <li><a href="home.html">Déconnexion</a></li>
            </ul>
        </nav>
    </header>

    <!-- Main Profile Content -->
    <div class="profile-container">
        <div class="profile-header">
            <h1>Bienvenue, <?= htmlspecialchars($user['prenom']) ?> <?= htmlspecialchars($user['nom']) ?> 👋</h1>
            <p class="role">Rôle : Investisseur</p>
        </div>

        <div class="profile-details">
            <p><strong>Nom :</strong> <?= htmlspecialchars($user['nom']) ?></p>
            <p><strong>Prénom :</strong> <?= htmlspecialchars($user['prenom']) ?></p>
            <p><strong>Email :</strong> <?= htmlspecialchars($user['email']) ?></p>
        </div>

        <div class="profile-actions">
            <a href="modifier_profil.php" class="btn update-btn">Modifier Profil</a>
            <a href="supprimer_compte.php" class="btn delete-btn" onclick="return confirm('Êtes-vous sûr de vouloir supprimer votre compte ?');">Supprimer Compte</a>
        </div>
    </div>

    <!-- Footer -->
    <footer>
        &copy; 2025 DealHub. Tous droits réservés.
    </footer>

</body>
</html>
