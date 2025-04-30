<?php
require_once '../../config.php';
// Adjust path if needed

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $nom = $_POST['nom'] ?? '';
    $prenom = $_POST['prenom'] ?? '';
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    $role = $_POST['role'] ?? '';

    // Basic validation
    if (empty($nom) || empty($prenom) || empty($email) || empty($password) || empty($role)) {
        echo "Veuillez remplir tous les champs.";
        exit;
    }

     // Check if email already exists
     $check = $pdo->prepare("SELECT id FROM users WHERE email = :email");
     $check->execute(['email' => $email]);
     if ($check->fetch()) {
         echo "Cet email est déjà utilisé.";
         exit;
     }

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

        // After successful registration, fetch the user details
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->execute(['email' => $email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // Start session and set the user session
        session_start();
        $_SESSION['user'] = $user;

        // Redirect based on user role
        switch ($user['role']) {
            
            case 'investisseur':
                header("Location: ../frontoffice/investisseur.php");
                break;
            case 'entrepreneur':
                header("Location: ../frontoffice/entrepreneur.php");
                break;
                /*case 'admin':
                    header("Location: ../backoffice/dashboard.php");
                    break;*/
            default:
                echo "Erreur de rôle.";
                exit;
        }

        exit;
    } catch (PDOException $e) {
        echo "Erreur lors de l'inscription : " . $e->getMessage();
        exit;
    }
}
?>
