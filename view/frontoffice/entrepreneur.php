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
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Profil Entrepreneur - DealHub</title>

    <!-- Google Fonts -->
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;500;700&display=swap');

        /* Reset and base */
        * {
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', Arial, sans-serif;
            margin: 0;
            padding: 0;
            text-align: center;
            position: relative;
            overflow-x: hidden;
            background: linear-gradient(135deg, #1e1e2f, #2f1a4a);
            color: #e0d7f5;
            min-height: 100vh;
        }

        body::before {
            content: "";
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(47, 26, 74, 0.85);
            backdrop-filter: blur(6px);
            z-index: -1;
        }

        video.background-video {
            position: fixed;
            top: 0;
            left: 0;
            min-width: 100%;
            min-height: 100%;
            object-fit: cover;
            z-index: -2;
            filter: brightness(0.6) saturate(1.2);
            transition: filter 0.5s ease;
        }

        header {
            background: linear-gradient(90deg, #3a1a6a, #5a3a9e);
            color: #e0d7f5;
            padding: 20px 0;
            position: relative;
            z-index: 10;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.5);
            font-weight: 600;
            letter-spacing: 1px;
        }

        .header-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            width: 85%;
            margin: auto;
            max-width: 1200px;
        }

        .header-container span {
            font-size: 1.8rem;
            font-weight: 700;
            letter-spacing: 2px;
            color: #f0e9ff;
            text-shadow: 0 0 8px #a18aff;
        }

        .header-container nav a {
            color: #dcd6f7;
            text-decoration: none;
            margin: 0 18px;
            font-weight: 500;
            font-size: 1rem;
            padding: 6px 12px;
            border-radius: 6px;
            transition: background 0.3s ease, color 0.3s ease;
        }

        .header-container nav a:hover {
            background: #a18aff;
            color: #2f1a4a;
            box-shadow: 0 0 8px #a18aff;
        }

        .profile-container {
            width: 85%;
            max-width: 900px;
            margin: 40px auto 60px;
            background: rgba(132, 108, 160, 0.85);
            padding: 30px 40px;
            border-radius: 16px;
            box-shadow: 0 4px 15px rgba(132, 108, 160, 0.6);
            color: #f0e9ff;
            text-align: left;
        }

        .profile-header h1 {
            font-size: 2.8rem;
            font-weight: 700;
            margin-bottom: 10px;
            color: #f0e9ff;
            text-shadow: 0 0 10px #a18aff;
        }

        .profile-header .role {
            font-size: 1.2rem;
            font-weight: 500;
            color: #dcd6f7;
            margin-bottom: 25px;
        }

        .profile-details p {
            font-size: 1.1rem;
            margin: 8px 0;
        }

        .profile-actions {
            margin-top: 30px;
            display: flex;
            gap: 15px;
            flex-wrap: wrap;
        }

        .btn {
            padding: 12px 24px;
            border-radius: 12px;
            font-weight: 600;
            font-size: 1rem;
            cursor: pointer;
            text-decoration: none;
            user-select: none;
            transition: background 0.3s ease, color 0.3s ease, box-shadow 0.3s ease;
            display: inline-block;
            text-align: center;
        }

        .update-btn {
            background: linear-gradient(135deg, #6a4a9e, #a18aff);
            color: #2f1a4a;
            box-shadow: 0 4px 12px rgba(161, 138, 255, 0.6);
        }

        .update-btn:hover {
            background: linear-gradient(135deg, #8e6edb, #c3b7ff);
            color: #2f1a4a;
            box-shadow: 0 6px 20px rgba(195, 183, 255, 0.9);
        }

        .delete-btn {
            background: #e74c3c;
            color: #fff;
            box-shadow: 0 4px 12px rgba(231, 76, 60, 0.6);
        }

        .delete-btn:hover {
            background: #ff4c4c;
            box-shadow: 0 6px 20px rgba(255, 76, 76, 0.9);
        }

        /* Modal styles */
        .modal {
            display: none;
            position: fixed;
            z-index: 20;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(47, 26, 74, 0.9);
            backdrop-filter: blur(6px);
        }

        .modal-content {
            background-color: rgba(132, 108, 160, 0.95);
            margin: 10% auto;
            padding: 20px 30px;
            border-radius: 16px;
            width: 90%;
            max-width: 500px;
            color: #f0e9ff;
            box-shadow: 0 4px 15px rgba(132, 108, 160, 0.8);
            text-align: left;
        }

        .modal-content h2 {
            margin-top: 0;
            margin-bottom: 20px;
            font-weight: 700;
            font-size: 1.8rem;
            text-shadow: 0 0 8px #a18aff;
        }

        .modal-content label {
            display: block;
            margin-bottom: 6px;
            font-weight: 600;
        }

        .modal-content input[type="text"] {
            width: 100%;
            padding: 10px 12px;
            border-radius: 12px;
            border: none;
            font-size: 1rem;
            font-weight: 500;
            outline: none;
            color: #2f1a4a;
            margin-bottom: 10px;
            transition: box-shadow 0.3s ease;
        }

        .modal-content input[type="text"]:focus {
            box-shadow: 0 0 8px #a18aff;
        }

        .modal-content button {
            padding: 12px 24px;
            border-radius: 12px;
            background: linear-gradient(135deg, #6a4a9e, #a18aff);
            color: #2f1a4a;
            border: none;
            font-weight: 600;
            font-size: 1rem;
            cursor: pointer;
            transition: background 0.3s ease, box-shadow 0.3s ease;
            width: 100%;
            user-select: none;
        }

        .modal-content button:hover {
            background: linear-gradient(135deg, #8e6edb, #c3b7ff);
            box-shadow: 0 6px 20px rgba(195, 183, 255, 0.9);
        }
    </style>
</head>
<body>
    <video class="background-video" autoplay loop muted>
        <source src="../../assets/background.mp4" type="video/mp4" />
        Votre navigateur ne supporte pas la lecture de vidéos.
    </video>

    <header>
        <div class="header-container">
            <span>DealHub</span>
            <nav>
                <a href="/wissal/DealHuB/view/frontoffice/home.html">Home</a>
                <a href="/wissal/DealHuB/view/frontoffice/entrepreneur.php">Profile</a>
                <a href="/wissal/DealHuB/view/backoffice/dash.php">dashboard</a>
                <a href="/wissal/dealhub/view/frontoffice/login.html">sign out</a>
            </nav>
        </div>
    </header>

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
            <a href="listmyspeeches.php" class="btn update-btn">My Speeches</a>
            <a href="/wissal/DealHuB/controller/ComplaintsController.php?action=frontoffice" class="btn update-btn">My Complaints</a>
            <a href="supprimer_compte.php" class="btn delete-btn" onclick="return confirm('Êtes-vous sûr de vouloir supprimer votre compte ?');">Supprimer Compte</a>
        </div>
    </div>

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
