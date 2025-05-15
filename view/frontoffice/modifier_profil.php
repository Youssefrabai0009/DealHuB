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

