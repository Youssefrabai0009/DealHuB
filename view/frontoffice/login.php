<?php
session_start();
require_once '../../config.php';
require_once '../../controller/UserController.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    if (empty($email) || empty($password)) {
        echo "Veuillez remplir tous les champs.";
        exit;
    }

    try {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->execute(['email' => $email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user'] = $user;

            // Redirect based on role
            switch ($user['role']) {
                case 'admin':
                    header("Location: ../backoffice/dashboard.html");
                    break;
                case 'investisseur':
                    header("Location: ../frontoffice/investisseur.php");
                    break;
                case 'entrepreneur':
                    header("Location: ../frontoffice/entrepreneur.php");
                    break;
                default:
                    echo "Rôle non reconnu.";
            }
            exit;
        } else {
            echo "Email ou mot de passe incorrect.";
        }
    } catch (PDOException $e) {
        echo "Erreur lors de la connexion : " . $e->getMessage();
    }
}
?>
