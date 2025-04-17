<?php
require_once '../../config.php';  // Make sure the path is correct

class UserController {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function addUser(User $user) {
        try {
            $sql = "INSERT INTO users (nom, prenom, email, role) VALUES (:nom, :prenom, :email, :role)"; // Change to 'users'
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([
                'nom' => $user->getNom(),
                'prenom' => $user->getPrenom(),
                'email' => $user->getEmail(),
                'role' => $user->getRole(),
            ]);
            return true;
        } catch (PDOException $e) {
            error_log("Error adding user: " . $e->getMessage());
            return false;
        }
    }

    public function deleteUser($id) {
        try {
            $sql = "DELETE FROM users WHERE id = :id"; // changed from iduser to id
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindValue(':id', $id);
            $stmt->execute();
            return true;
        } catch (PDOException $e) {
            error_log("Error deleting user: " . $e->getMessage());
            return false;
        }
    }
    
    public function updateUser(User $user, $id) {
        try {
            $sql = "UPDATE users SET nom = :nom, prenom = :prenom, email = :email, role = :role WHERE id = :id"; // changed from iduser to id
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([
                'id' => $id,
                'nom' => $user->getNom(),
                'prenom' => $user->getPrenom(),
                'email' => $user->getEmail(),
                'role' => $user->getRole()
            ]);
            return true;
        } catch (PDOException $e) {
            error_log("Error updating user: " . $e->getMessage());
            return false;
        }
    }
    
}
?>
