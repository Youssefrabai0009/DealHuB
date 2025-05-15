<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: login.html");
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>DealHub - Connect Entrepreneurs & Investors</title>

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

        header,
        footer {
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

        .container {
            width: 85%;
            margin: 30px auto 50px;
            max-width: 1200px;
            text-align: left;
        }

        h1, h2, h3 {
            color: #f0e9ff;
            text-shadow: 0 0 10px #a18aff;
        }

        p {
            color: #e0d7f5;
            font-weight: 400;
            line-height: 1.5;
        }

        /* Profile icon and dropdown */
        .profile-icon {
            font-size: 32px;
            color: #e0d7f5;
            cursor: pointer;
        }

        .dropdown {
            position: relative;
        }

        .dropdown-content {
            display: none;
            position: absolute;
            right: 0;
            background-color: #3a1a6a;
            min-width: 160px;
            box-shadow: 0 8px 16px rgba(0,0,0,0.5);
            z-index: 1001;
            border-radius: 5px;
            margin-top: 10px;
        }

        .dropdown-content li {
            padding: 10px;
            text-align: left;
        }

        .dropdown-content li a {
            color: #dcd6f7;
            text-decoration: none;
            display: block;
            font-weight: 600;
        }

        .dropdown-content li a:hover {
            background-color: #5a3a9e;
        }

        .dropdown:hover .dropdown-content {
            display: block;
        }

        img {
            background: transparent;
            border-radius: 8px;
            object-fit: contain;
        }

        .site-logo {
            height: 40px;
            filter: brightness(0) invert(1); /* This turns a black logo white */
        }

        /* Sections styling */
        .hero {
            margin-bottom: 40px;
        }

        .features {
            margin-bottom: 40px;
        }

        .feature-box {
            display: flex;
            gap: 20px;
            justify-content: space-between;
        }

        .feature {
            background: rgba(132, 108, 160, 0.85);
            padding: 20px;
            border-radius: 16px;
            box-shadow: 0 4px 15px rgba(132, 108, 160, 0.6);
            flex: 1;
            color: #f0e9ff;
        }

        footer p {
            margin: 0;
            font-weight: 500;
        }
    </style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>

<header>
    <div class="header-container">
        <span>DealHub</span>
        <nav>
            <a href="accuil.php">Accueil</a>
            <a href="entrepreneur.php">Profil</a>
            <a href="investisseur.php">Investisseur</a>
            <a href="logout.php">Déconnexion</a>
        </nav>
        <div class="dropdown">
            <i class="fas fa-user-circle profile-icon" onclick="toggleDropdown()"></i>
            <ul class="dropdown-content" id="dropdownMenu">
                <?php if (isset($_SESSION['user'])): ?>
                    <?php if ($_SESSION['user']['role'] === 'entrepreneur'): ?>
                        <li><a href="entrepreneur.php" class="block px-4 py-2 hover:bg-gray-100">Mon Profil</a></li>
                    <?php elseif ($_SESSION['user']['role'] === 'investisseur'): ?>
                        <li><a href="investisseur.php" class="block px-4 py-2 hover:bg-gray-100">Mon Profil</a></li>
                    <?php endif; ?>
                    <li><a href="logout.php" class="block px-4 py-2 hover:bg-gray-100">Déconnexion</a></li>
                <?php else: ?>
                    <li><a href="login.html" class="block px-4 py-2 hover:bg-gray-100">Se connecter</a></li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</header>

<section class="hero container">
    <h2>Connectez les Entrepreneurs et les Investisseurs</h2>
    <p>Publiez votre pitch, obtenez des offres, et faites avancer vos projets avec l'aide des investisseurs. Trouvez des opportunités et investissez dans l'avenir.</p>
</section>

<section id="features" class="features container">
    <h2>Fonctionnalités</h2>
    <div class="feature-box">
        <div class="feature">
            <h3>Pitchs Vidéos</h3>
            <p>Les entrepreneurs publient leurs pitchs détaillant leur projet, le montant et l’equity demandée.</p>
        </div>
        <div class="feature">
            <h3>Offres des Investisseurs</h3>
            <p>Les investisseurs peuvent envoyer des offres aux entrepreneurs pour soutenir leur projet.</p>
        </div>
        <div class="feature">
            <h3>Accès Exclusif</h3>
            <p>Les investisseurs ont un accès exclusif aux pitchs et peuvent les consulter à tout moment.</p>
        </div>
    </div>
</section>

<section id="about" class="about container">
    <h2>À propos de DealHub</h2>
    <p>DealHub est une plateforme qui met en relation des entrepreneurs et des investisseurs, facilitant ainsi le financement des projets innovants. Nous offrons des outils puissants pour les deux types d’utilisateurs pour maximiser leurs opportunités de croissance.</p>
</section>

<footer>
    <div class="container">
        <p>&copy; 2025 DealHub. Tous droits réservés.</p>
    </div>
</footer>

<!-- profil icon -->
<script>
    function toggleDropdown() {
        const menu = document.getElementById("dropdownMenu");
        menu.style.display = (menu.style.display === "block") ? "none" : "block";
    }
    
    // Hide dropdown if clicked outside
    window.addEventListener("click", function(e) {
        const icon = document.querySelector(".profile-icon");
        const menu = document.getElementById("dropdownMenu");
    
        if (!icon.contains(e.target) && !menu.contains(e.target)) {
            menu.style.display = "none";
        }
    });
</script>

</body>
</html>
