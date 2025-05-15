<?php
session_start();
require_once '../../config.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $reset_code = $_POST['reset_code'] ?? '';
    $new_password = $_POST['new_password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    $email = $_SESSION['reset_email'] ?? '';
    $stored_code = $_SESSION['reset_code'] ?? '';
    $expires = $_SESSION['reset_code_expires'] ?? 0;

    if (empty($reset_code) || empty($new_password) || empty($confirm_password) || empty($email)) {
        header("Location: reset_password.html?error=Veuillez remplir tous les champs.");
        exit;
    }

    if ($new_password !== $confirm_password) {
        header("Location: reset_password.html?error=Les mots de passe ne correspondent pas.");
        exit;
    }

    if (strlen($new_password) < 8) {
        header("Location: reset_password.html?error=Le mot de passe doit contenir au moins 8 caractères.");
        exit;
    }

    if ($reset_code !== $stored_code || time() > $expires) {
        unset($_SESSION['reset_email'], $_SESSION['reset_code'], $_SESSION['reset_code_expires']);
        header("Location: reset_password.html?error=Code invalide ou expiré.");
        exit;
    }

    try {
        // Update the user's password
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("UPDATE users SET password = :password WHERE email = :email");
        $stmt->execute(['password' => $hashed_password, 'email' => $email]);

        // Clear session
        unset($_SESSION['reset_email'], $_SESSION['reset_code'], $_SESSION['reset_code_expires']);

        // Redirect to login with success message
        header("Location: login.html?success=Votre mot de passe a été réinitialisé avec succès.");
    } catch (PDOException $e) {
        header("Location: reset_password.html?error=Erreur lors du traitement. Veuillez réessayer.");
    }
}
?>