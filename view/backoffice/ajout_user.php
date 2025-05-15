<?php
session_start();
require_once '../../config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nom = htmlspecialchars(trim($_POST['nom']));
    $prenom = htmlspecialchars(trim($_POST['prenom']));
    $email = htmlspecialchars(trim($_POST['email']));
    $password = htmlspecialchars(trim($_POST['password']));
    $role = htmlspecialchars(trim($_POST['role']));

    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    $sql = "INSERT INTO users (nom, prenom, email, password, role) 
            VALUES (:nom, :prenom, :email, :password, :role)";

    try {
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':nom' => $nom,
            ':prenom' => $prenom,
            ':email' => $email,
            ':password' => $hashedPassword,
            ':role' => $role
        ]);

        // ✅ Set notification AFTER insertion
        $_SESSION['notifications'][] = "Nouvel utilisateur ajouté : $prenom $nom";

        $_SESSION['success'] = "Utilisateur ajouté avec succès !";

    } catch (PDOException $e) {
        $_SESSION['error'] = "Erreur lors de l'ajout de l'utilisateur : " . $e->getMessage();
    }

    header("Location: gestionusers.php");
    exit();
} else {
    header("Location: gestionusers.php");
    exit();
}
