<?php
session_start();

// Check if the user is logged in and has the "entrepreneur" role
if (!isset($_SESSION['user']) || !isset($_SESSION['user']['role']) || $_SESSION['user']['role'] !== 'entrepreneur') {
    header("Location: login.html");
    exit;
}

$user = $_SESSION['user'];
$userEmail = isset($user['email']) ? htmlspecialchars($user['email']) : 'entrepreneur@example.com';
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Entrepreneur - DealHub</title>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="profil.css">
</head>
<body>
    <!-- Header -->
    <header>
            <!-- Logo and Title -->
            <div class="container">
    <div class="logo-container">
        <img src="../../assets/logoweb2-transparent.png" alt="DealHub Logo" class="site-logo">
        <h1 class="logo">DealHub</h1>
    </div>
    <nav>
        <ul>
            <li><a href="accuil.php">Accueil</a></li>
            <li><a href="logout.php">Déconnexion</a></li>
            <li><a href="#" class="btn"><?= $userEmail ?> (Connecté)</a></li>
        </ul>
    </nav>
</div>
    </header>

    <!-- Main Profile Content -->
    <div class="profile-container">
        <div class="profile-header">
            <h1>Bienvenue, <?= htmlspecialchars($user['prenom']) ?> <?= htmlspecialchars($user['nom']) ?> 👋</h1>
            <p class="role">Entrepreneur</p>
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
        <div class="container">
            <p>© 2025 DealHub. Tous droits réservés.</p>
        </div>
    </footer>
    <!-- Modal for editing profile -->
<div id="editModal" class="modal">
  <div class="modal-content">
    <span class="close">&times;</span>
    <h2>Modifier votre profil</h2>
    <form id="editProfileForm" method="POST" action="modifier_profil.php">
    <div class="form-group">
        <label>Nom:</label>
        <input type="text" name="nom" id="nom" value="<?= htmlspecialchars($user['nom']) ?>">
        <div id="errorNom" style="color: red; font-size: 12px;"></div> <!-- Error message for Nom -->
    </div>
    <div class="form-group">
        <label>Prénom:</label>
        <input type="text" name="prenom" id="prenom" value="<?= htmlspecialchars($user['prenom']) ?>">
        <div id="errorPrenom" style="color: red; font-size: 12px;"></div> <!-- Error message for Prenom -->
    </div>
    <div class="form-group">
        <label>Email:</label>
        <input type="text" name="email" id="email" value="<?= htmlspecialchars($user['email']) ?>">
        <div id="errorEmail" style="color: red; font-size: 12px;"></div> <!-- Error message for Email -->
    </div>
    <button type="submit">Enregistrer</button>
</form>


  </div>
</div>

<script>
document.getElementById("editProfileForm").addEventListener("submit", function(e) {
    // Prevent form submission at the beginning
    e.preventDefault();

    // Get form values
    const nom = document.getElementById("nom").value.trim();
    const prenom = document.getElementById("prenom").value.trim();
    const email = document.getElementById("email").value.trim();

    // Clear previous error messages
    document.getElementById("errorNom").textContent = "";
    document.getElementById("errorPrenom").textContent = "";
    document.getElementById("errorEmail").textContent = "";

    let errorMessage = false;

    // Check for empty fields and invalid input
    if (nom === "") {
        document.getElementById("errorNom").textContent = "Le nom est obligatoire.";
        errorMessage = true;
    } else if (!/^[a-zA-ZÀ-ÿ\s\-']+$/.test(nom)) {
        document.getElementById("errorNom").textContent = "Le nom contient des caractères invalides.";
        errorMessage = true;
    }

    if (prenom === "") {
        document.getElementById("errorPrenom").textContent = "Le prénom est obligatoire.";
        errorMessage = true;
    } else if (!/^[a-zA-ZÀ-ÿ\s\-']+$/.test(prenom)) {
        document.getElementById("errorPrenom").textContent = "Le prénom contient des caractères invalides.";
        errorMessage = true;
    }

    if (email === "") {
        document.getElementById("errorEmail").textContent = "L'email est obligatoire.";
        errorMessage = true;
    } else if (!/^\S+@\S+\.\S+$/.test(email)) {
        document.getElementById("errorEmail").textContent = "L'adresse e-mail n'est pas valide.";
        errorMessage = true;
    }

    // If there are no errors, submit the form
    if (!errorMessage) {
        document.getElementById("editProfileForm").submit();
    }
});
</script>

<!-- Modal Script -->
<script>
document.querySelector(".update-btn").addEventListener("click", function(e) {
  e.preventDefault();
  document.getElementById("editModal").style.display = "block";
});

document.querySelector(".close").addEventListener("click", function() {
  document.getElementById("editModal").style.display = "none";
});

window.onclick = function(event) {
  const modal = document.getElementById("editModal");
  if (event.target == modal) {
    modal.style.display = "none";
  }
}
</script>
</body>
</html>