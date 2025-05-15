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
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>DealHub - Inscription</title>

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

        .register-container {
            display: flex;
            width: 85%;
            margin: 30px auto 50px;
            justify-content: center;
            align-items: center;
            max-width: 1200px;
            gap: 30px;
            text-align: left;
        }

        .left-section {
            width: 50%;
        }

        .left-section h1 {
            font-size: 2.8rem;
            font-weight: 700;
            margin-bottom: 25px;
            color: #f0e9ff;
            text-shadow: 0 0 10px #a18aff;
        }

        .left-section span {
            color: #a18aff;
        }

        .left-section p {
            font-size: 1.1rem;
            color: #dcd6f7;
            line-height: 1.5;
        }

        .right-section {
            width: 50%;
            background: rgba(132, 108, 160, 0.85);
            padding: 30px 40px;
            border-radius: 16px;
            box-shadow: 0 4px 15px rgba(132, 108, 160, 0.6);
            color: #f0e9ff;
        }

        .right-section h2 {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 25px;
            text-align: center;
            color: #f0e9ff;
            text-shadow: 0 0 8px #a18aff;
        }

        form {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .input-group {
            display: flex;
            flex-direction: column;
        }

        input[type="text"],
        input[type="email"],
        input[type="password"],
        select {
            padding: 12px 15px;
            border-radius: 12px;
            border: none;
            font-size: 1rem;
            font-weight: 500;
            outline: none;
            transition: box-shadow 0.3s ease;
            color: #2f1a4a;
        }

        input[type="text"]:focus,
        input[type="email"]:focus,
        input[type="password"]:focus,
        select:focus {
            box-shadow: 0 0 8px #a18aff;
        }

        .input-error {
            border: 2px solid #e74c3c !important;
        }

        .error-message {
            color: #e74c3c;
            font-size: 12px;
            margin-top: 5px;
            text-align: left;
            padding-left: 5px;
            display: none;
        }

        button.register-btn {
            padding: 14px 28px;
            background: linear-gradient(135deg, #6a4a9e, #a18aff);
            color: #2f1a4a;
            border: none;
            cursor: pointer;
            border-radius: 12px;
            font-size: 1.1rem;
            font-weight: 600;
            transition: background 0.4s ease, color 0.4s ease, box-shadow 0.4s ease;
            width: 100%;
            box-shadow: 0 4px 12px rgba(161, 138, 255, 0.6);
            user-select: none;
        }

        button.register-btn:hover {
            background: linear-gradient(135deg, #8e6edb, #c3b7ff);
            color: #2f1a4a;
            box-shadow: 0 6px 20px rgba(195, 183, 255, 0.9);
        }

        .login-link {
            margin-top: 15px;
            text-align: center;
            font-size: 0.9rem;
            color: #dcd6f7;
        }

        .login-link a {
            color: #a18aff;
            text-decoration: none;
            font-weight: 600;
        }

        .login-link a:hover {
            text-decoration: underline;
        }

        /* Success message */
        .success-message {
            color: #2ecc71;
            font-size: 12px;
            margin-bottom: 15px;
            text-align: center;
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
                
            </nav>
        </div>
    </header>

    <div class="register-container">
        <div class="left-section">
            <h1>Bienvenue sur <span>DealHub</span></h1>
            <p>Rejoignez-nous pour connecter les esprits innovants avec les investisseurs du futur.</p>
        </div>

        <div class="right-section">
            <h2>Créer un compte</h2>

            <?php if (!empty($error)) : ?>
                <p class="error-message" style="display:block; text-align:center; margin-bottom:15px;"><?= htmlspecialchars($error); ?></p>
            <?php endif; ?>

            <form id="registrationForm" method="POST" action="" novalidate>
                <div class="input-group">
                    <input type="text" name="nom" id="nom" placeholder="Nom" value="<?= htmlspecialchars($nom) ?>" />
                    <p id="nom-error" class="error-message"></p>
                </div>
                <div class="input-group">
                    <input type="text" name="prenom" id="prenom" placeholder="Prénom" value="<?= htmlspecialchars($prenom) ?>" />
                    <p id="prenom-error" class="error-message"></p>
                </div>
                <div class="input-group">
                    <input type="email" name="email" id="email" placeholder="Email" value="<?= htmlspecialchars($email) ?>" />
                    <p id="email-error" class="error-message"></p>
                </div>
                <div class="input-group">
                    <input type="password" name="password" id="password" placeholder="Mot de passe" />
                    <p id="password-error" class="error-message"></p>
                </div>
                <div class="input-group">
                    <select name="role" id="role">
                        <option value="">Choisir un rôle</option>
                        <option value="investisseur" <?= $role == 'investisseur' ? 'selected' : '' ?>>Investisseur</option>
                        <option value="entrepreneur" <?= $role == 'entrepreneur' ? 'selected' : '' ?>>Entrepreneur</option>
                    </select>
                    <p id="role-error" class="error-message"></p>
                </div>
                <div class="input-group" style="margin-top: 10px;">
                    <div class="g-recaptcha" data-sitekey="6LdwRTArAAAAAD-7w1mOM0JYYaqUPXZKpcIF6ZE8"></div>
                </div>
                <button type="submit" class="register-btn">S'inscrire</button>
            </form>

            <div class="login-link">
                <p>Déjà un compte ? <a href="login.html">Se connecter</a></p>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const registrationForm = document.getElementById("registrationForm");
            const nomInput = document.getElementById("nom");
            const prenomInput = document.getElementById("prenom");
            const emailInput = document.getElementById("email");
            const passwordInput = document.getElementById("password");
            const roleInput = document.getElementById("role");

            const nomError = document.getElementById("nom-error");
            const prenomError = document.getElementById("prenom-error");
            const emailError = document.getElementById("email-error");
            const passwordError = document.getElementById("password-error");
            const roleError = document.getElementById("role-error");

            function showError(inputElement, errorElement, message) {
                inputElement.classList.add('input-error');
                errorElement.textContent = message;
                errorElement.style.display = 'block';
            }

            function hideError(inputElement, errorElement) {
                inputElement.classList.remove('input-error');
                errorElement.style.display = 'none';
            }

            function validateNom() {
                const nom = nomInput.value.trim();
                if (!nom) {
                    showError(nomInput, nomError, "Veuillez saisir votre nom.");
                    return false;
                } else {
                    hideError(nomInput, nomError);
                    return true;
                }
            }

            function validatePrenom() {
                const prenom = prenomInput.value.trim();
                if (!prenom) {
                    showError(prenomInput, prenomError, "Veuillez saisir votre prénom.");
                    return false;
                } else {
                    hideError(prenomInput, prenomError);
                    return true;
                }
            }

            function validateEmail() {
                const email = emailInput.value.trim();
                const emailRegex = /^[^@\s]+@[^@\s]+\.[^@\s]+$/;
                if (!email) {
                    showError(emailInput, emailError, "Veuillez saisir votre email.");
                    return false;
                } else if (!emailRegex.test(email)) {
                    showError(emailInput, emailError, "Adresse email invalide.");
                    return false;
                } else {
                    hideError(emailInput, emailError);
                    return true;
                }
            }

            function validatePassword() {
                const password = passwordInput.value.trim();
                if (!password) {
                    showError(passwordInput, passwordError, "Veuillez saisir un mot de passe.");
                    return false;
                } else {
                    hideError(passwordInput, passwordError);
                    return true;
                }
            }

            function validateRole() {
                const role = roleInput.value;
                if (!role) {
                    showError(roleInput, roleError, "Veuillez sélectionner un rôle.");
                    return false;
                } else {
                    hideError(roleInput, roleError);
                    return true;
                }
            }

            nomInput.addEventListener('blur', validateNom);
            prenomInput.addEventListener('blur', validatePrenom);
            emailInput.addEventListener('blur', validateEmail);
            passwordInput.addEventListener('blur', validatePassword);
            roleInput.addEventListener('blur', validateRole);

            nomInput.addEventListener('input', function() {
                if (nomInput.classList.contains('input-error')) {
                    validateNom();
                }
            });
            prenomInput.addEventListener('input', function() {
                if (prenomInput.classList.contains('input-error')) {
                    validatePrenom();
                }
            });
            emailInput.addEventListener('input', function() {
                if (emailInput.classList.contains('input-error')) {
                    validateEmail();
                }
            });
            passwordInput.addEventListener('input', function() {
                if (passwordInput.classList.contains('input-error')) {
                    validatePassword();
                }
            });
            roleInput.addEventListener('input', function() {
                if (roleInput.classList.contains('input-error')) {
                    validateRole();
                }
            });

            registrationForm.addEventListener("submit", function (e) {
                const isNomValid = validateNom();
                const isPrenomValid = validatePrenom();
                const isEmailValid = validateEmail();
                const isPasswordValid = validatePassword();
                const isRoleValid = validateRole();

                if (!isNomValid || !isPrenomValid || !isEmailValid || !isPasswordValid || !isRoleValid) {
                    e.preventDefault();
                }
            });
        });
    </script>

    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
</body>
</html>
