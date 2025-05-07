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
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DealHub - Connect Entrepreneurs & Investors</title>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <link rel="stylesheet" href="home.css">
</head>
<body>

<header class="bg-white shadow p-4">
    <div class="container flex items-center justify-between">
        <!-- Logo and Title -->
        <div class="flex items-center flex-col text-center">
    <h1 class="text-xl font-bold text-gray-800 mb-2">DealHub</h1>
    <img src="../../assets/logoweb2-transparent.png" alt="DealHub Logo" class="site-logo">
</div>

        <!-- Navigation -->
        <nav>
            <ul class="flex items-center space-x-4">
                <li>Bonjour, <?php echo htmlspecialchars($_SESSION['user']['prenom']); ?></li>

                <li class="relative dropdown">
                    <i class="fas fa-user-circle profile-icon text-2xl cursor-pointer" onclick="toggleDropdown()"></i>
                    <ul class="dropdown-content absolute right-0 mt-2 bg-white shadow-md rounded hidden" id="dropdownMenu">
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
                </li>
            </ul>
        </nav>
    </div>
</header>


<section class="hero">
    <div class="container">
        <h2>Connectez les Entrepreneurs et les Investisseurs</h2>
        <p>Publiez votre pitch, obtenez des offres, et faites avancer vos projets avec l'aide des investisseurs. Trouvez des opportunités et investissez dans l'avenir.</p>
    </div>
</section>

<section id="features" class="features">
    <div class="container">
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
    </div>
</section>

<section id="about" class="about">
    <div class="container">
        <h2>À propos de DealHub</h2>
        <p>DealHub est une plateforme qui met en relation des entrepreneurs et des investisseurs, facilitant ainsi le financement des projets innovants. Nous offrons des outils puissants pour les deux types d’utilisateurs pour maximiser leurs opportunités de croissance.</p>
    </div>
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
    
<style>
    /*profil icon */
.profile-icon {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    cursor: pointer;
}

.dropdown {
    position: relative;
}

.dropdown-content {
    display: none;
    position: absolute;
    right: 0;
    background-color: #fff;
    min-width: 160px;
    box-shadow: 0 8px 16px rgba(0,0,0,0.2);
    z-index: 1001;
    border-radius: 5px;
    margin-top: 10px;
}

.dropdown-content li {
    padding: 10px;
    text-align: left;
}

.dropdown-content li a {
    color: #2F1A4A;
    text-decoration: none;
    display: block;
    font-weight: 600;
}

.dropdown-content li a:hover {
    background-color: #eee;
}

.dropdown:hover .dropdown-content {
    display: block;
}

.profile-icon {
    font-size: 32px;
    color: white;
    cursor: pointer;
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

</style>

</body>
</html>
