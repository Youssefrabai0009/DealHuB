<?php
// Assuming you have database connection $conn
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve user data from form submission
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $role = $_POST['role'];

    // SQL to insert the user
    $sql = "INSERT INTO users (nom, prenom, email, password, role) VALUES ('$nom', '$prenom', '$email', '$password', '$role')";
    
    if (mysqli_query($conn, $sql)) {
        // Add notification after successfully adding the user
        $_SESSION['notifications'][] = "Un nouvel utilisateur a été ajouté avec succès.";
    
        // Redirect to another page after success (e.g., back to the user management page)
        header("Location: gestionuser.php");
        exit;  // Always exit after redirecting
    } else {
        // Handle the case when the query fails
        $_SESSION['notifications'][] = "Erreur lors de l'ajout de l'utilisateur.";
    }
}    
?>
