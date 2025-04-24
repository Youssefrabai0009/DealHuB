<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'entrepreneur') {
    header("Location: login.html");
    exit;
}

$user = $_SESSION['user'];

$userEmail = $_SESSION['user']['email'] ?? 'entrepreneur@example.com';

?>


<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Profil Entrepreneur - DealHub</title>
    <link rel="stylesheet" href="styleentre.css">
</head>
<body>

    <!-- Header -->
    <header>
        <nav>
            <ul>
                <li><a href="home.html">Accueil</a></li>
                <li><a href="#">Mon Profil</a></li>
                <li><a href="logout.php">Déconnexion</a></li>

                <div>
          <p class="font-medium"><?= htmlspecialchars($userEmail) ?></p>
          <p class="text-xs text-gray-500">Connecté</p>

          </div>
            </ul>
        </nav>
    </header>

    <!-- Main Profile Content -->
    <div class="profile-container">
        <div class="profile-header">
            <h1>Bienvenue, <?php echo htmlspecialchars($user['prenom'] . ' ' . $user['nom']); ?></h1>
            <p class="role">Rôle : Entrepreneur</p>
        </div>

        <div class="profile-details">
            <p><strong>Nom :</strong> <?php echo htmlspecialchars($user['nom']); ?></p>
            <p><strong>Prénom :</strong> <?php echo htmlspecialchars($user['prenom']); ?></p>
            <p><strong>Email :</strong> <?php echo htmlspecialchars($user['email']); ?></p>
        </div>

        <!-- Buttons: Modifier / Supprimer -->
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
