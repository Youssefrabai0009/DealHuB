<?php
session_start();
require_once '../../config.php';

$error = '';
$nom = $prenom = $email = $password = $role = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
   //captcha
   $recaptcha_secret = "6LdwRTArAAAAALHH4nFFgpFIWdLQrKGbyNZNocPP";
   $recaptcha_response = $_POST['g-recaptcha-response'];

   // Make and decode POST request:
   $response = file_get_contents(
       "https://www.google.com/recaptcha/api/siteverify?secret=$recaptcha_secret&response=$recaptcha_response"
   );
   $response_keys = json_decode($response, true);

   // Check if the CAPTCHA was successful
   if (!$response_keys["success"]) {
    $error = "Échec de la vérification reCAPTCHA. Veuillez réessayer.";
}

   // fin de captcha
    // Get form data
    $nom = $_POST['nom'] ?? '';
    $prenom = $_POST['prenom'] ?? '';
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    $role = $_POST['role'] ?? '';

    // Basic validation
    if (empty($nom) || empty($prenom) || empty($email) || empty($password) || empty($role)) {
        $error = "Veuillez remplir tous les champs.";
    } else {
        // Check if email already exists
        $check = $pdo->prepare("SELECT id FROM users WHERE email = :email");
        $check->execute(['email' => $email]);
        if ($check->fetch()) {
            $error = "Cet email est déjà utilisé.";
        } else {
            // Hash password
            $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

            try {
                // Insert user into the database
                $sql = "INSERT INTO users (nom, prenom, email, password, role) 
                        VALUES (:nom, :prenom, :email, :password, :role)";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([
                    'nom' => $nom,
                    'prenom' => $prenom,
                    'email' => $email,
                    'password' => $hashedPassword,
                    'role' => $role
                ]);

                // Fetch the user
                $stmt = $pdo->prepare("SELECT * FROM users WHERE email = :email");
                $stmt->execute(['email' => $email]);
                $user = $stmt->fetch(PDO::FETCH_ASSOC);

                $_SESSION['user'] = $user;

                // Redirect based on role
                switch ($user['role']) {
                    case 'investisseur':
                        header("Location: ../frontoffice/investisseur.php");
                        exit;
                    case 'entrepreneur':
                        header("Location: ../frontoffice/entrepreneur.php");
                        exit;
                    default:
                        $error = "Erreur de rôle.";
                }
            } catch (PDOException $e) {
                $error = "Erreur lors de l'inscription : " . $e->getMessage();
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>DealHub - Inscription</title>
    <link rel="stylesheet" href="register.css">
    <style>
        .error-message {
            color: red;
            font-size: 0.9em;
            margin-bottom: 10px;
            text-align: center;
        }
    </style>
</head>
<body>
<video class="background-video" autoplay loop muted>
    <source src="../../assets/background.mp4" type="video/mp4" />
    Votre navigateur ne supporte pas la lecture de vidéos.
</video>

<div class="register-container">
    <!-- Left welcome panel -->
    <div class="register-welcome">
        <h1>Bienvenue sur DealHub</h1>
        <p>Rejoignez-nous pour connecter les esprits innovants avec les investisseurs du futur.</p>
    </div>

    <!-- Right form panel -->
    <div class="register-form">
        <h2>Créer un compte</h2>

        <?php if (!empty($error)) : ?>
            <div class="error-message"><?= htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <form id="registrationForm" method="POST" action="">
            <input type="text" name="nom" id="nom" placeholder="Nom" value="<?= htmlspecialchars($nom) ?>">
            <div id="nom-error" class="error-message"></div>

            <input type="text" name="prenom" id="prenom" placeholder="Prénom" value="<?= htmlspecialchars($prenom) ?>">
            <div id="prenom-error" class="error-message"></div>

            <input type="email" name="email" id="email" placeholder="Email" value="<?= htmlspecialchars($email) ?>">
            <div id="email-error" class="error-message"></div>

            <input type="password" name="password" id="password" placeholder="Mot de passe">
            <div id="password-error" class="error-message"></div>

            <select name="role" id="role">
                <option value="">Choisir un rôle</option>
                <option value="investisseur" <?= $role == 'investisseur' ? 'selected' : '' ?>>Investisseur</option>
                <option value="entrepreneur" <?= $role == 'entrepreneur' ? 'selected' : '' ?>>Entrepreneur</option>
            </select>
            <div id="role-error" class="error-message"></div>
                <!-- captcha -->
            <div class="g-recaptcha" data-sitekey="6LdwRTArAAAAAD-7w1mOM0JYYaqUPXZKpcIF6ZE8"></div>
            <button type="submit">S'inscrire</button>
        </form>
            <!-- captcha -->
        <script src="https://www.google.com/recaptcha/api.js" async defer></script>


        <a href="login.html" class="login-link">Déjà un compte ? Se connecter</a>
    </div>
</div>

<script>
    document.getElementById("registrationForm").addEventListener("submit", function (e) {
        let isValid = true;
        const fields = ["nom", "prenom", "email", "password", "role"];
        fields.forEach(field => {
            document.getElementById(field + "-error").textContent = "";
        });

        const nom = document.getElementById("nom").value.trim();
        const prenom = document.getElementById("prenom").value.trim();
        const email = document.getElementById("email").value.trim();
        const password = document.getElementById("password").value.trim();
        const role = document.getElementById("role").value;

        if (!nom) {
            document.getElementById("nom-error").textContent = "Veuillez saisir votre nom.";
            isValid = false;
        }
        if (!prenom) {
            document.getElementById("prenom-error").textContent = "Veuillez saisir votre prénom.";
            isValid = false;
        }
        if (!email) {
            document.getElementById("email-error").textContent = "Veuillez saisir votre email.";
            isValid = false;
        } else {
            const emailRegex = /^[^@\s]+@[^@\s]+\.[^@\s]+$/;
            if (!emailRegex.test(email)) {
                document.getElementById("email-error").textContent = "Adresse email invalide.";
                isValid = false;
            }
        }
        if (!password) {
            document.getElementById("password-error").textContent = "Veuillez saisir un mot de passe.";
            isValid = false;
        }
        if (!role) {
            document.getElementById("role-error").textContent = "Veuillez sélectionner un rôle.";
            isValid = false;
        }

        if (!isValid) {
            e.preventDefault();
        }
    });
</script>
</body>
</html>
