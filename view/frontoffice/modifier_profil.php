<?php
session_start();
if (!isset($_SESSION['user'])) {
  header("Location: ../frontoffice/login.html");
  exit;
}
require_once '../../config.php';
require_once '../../controller/UserController.php';
require_once '../../model/User.php';


$userController = new UserController($pdo);
$currentUser = $_SESSION['user'];
$userId = $currentUser['id'];

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nom = $_POST['nom'] ?? '';
    $prenom = $_POST['prenom'] ?? '';
    $email = $_POST['email'] ?? '';

    if (empty($nom) || empty($prenom) || empty($email)) {
        echo "Veuillez remplir tous les champs.";
        exit;
    }

    $updatedUser = new User($nom, $prenom, $email, $currentUser['role']);
    $userController->updateUserFront($updatedUser, $userId);

    // Update session
    $_SESSION['user']['nom'] = $nom;
    $_SESSION['user']['prenom'] = $prenom;
    $_SESSION['user']['email'] = $email;

    // Redirect based on role
    $redirectPage = ($currentUser['role'] === 'investisseur') 
        ? '/template/view/frontoffice/investisseur.php' 
        : '/template/view/frontoffice/entrepreneur.php';

    header("Location: $redirectPage");
    exit();
}

// Get current user info (in case of page refresh)
$nom = htmlspecialchars($currentUser['nom']);
$prenom = htmlspecialchars($currentUser['prenom']);
$email = htmlspecialchars($currentUser['email']);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Modifier Profil</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="modifier_profil.css">
</head>
<body>
    <div class="profile-container">
        <h2>Modifier votre profil</h2>
        <form method="POST">
            <div class="form-group">
                <label>Nom:</label>
                <input type="text" name="nom" value="<?= $nom ?>" required>
            </div>

            <div class="form-group">
                <label>Prénom:</label>
                <input type="text" name="prenom" value="<?= $prenom ?>" required>
            </div>

            <div class="form-group">
                <label>Email:</label>
                <input type="email" name="email" value="<?= $email ?>" required>
            </div>

            <button type="submit">Enregistrer les modifications</button>
            <a href="<?= ($currentUser['role'] === 'investisseur') 
                        ? '/template/view/frontoffice/investisseur.php' 
                        : '/template/view/frontoffice/entrepreneur.php' ?>" class="back-link">Retour au tableau de bord</a>
        </form>
    </div>
</body>
</html>