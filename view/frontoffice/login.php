<?php
session_start();
require_once '../../config.php';
require_once '../../controller/UserController.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    if (empty($email) || empty($password)) {
        header("Location: login.html?error=Veuillez remplir tous les champs.");
        exit;
    }

    try {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->execute(['email' => $email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            session_regenerate_id(true); // add this line
            $_SESSION['user'] = [
                'id' => $user['id'],
                'nom' => $user['nom'],
                'prenom' => $user['prenom'],
                'email' => $user['email'],
                'role' => $user['role']
            ];
            
            
            // Redirect based on role
            switch ($user['role']) {
                case 'admin':
                    header("Location: ../backoffice/dashboard.php");
                    break;
                case 'investisseur':
                    header("Location: ../frontoffice/index.php");
                    break;
                case 'entrepreneur':
                    header("Location: ../frontoffice/listmyspeeches.php");
                    break;
                default:
                    header("Location: login.html?error=Rôle non reconnu.");
            }
            exit;
        } else {
            header("Location: login.html?error=Email ou mot de passe incorrect.");
        }
    } catch (PDOException $e) {
        header("Location: login.html?error=Erreur lors de la connexion. Veuillez réessayer.");
    }
}


?>