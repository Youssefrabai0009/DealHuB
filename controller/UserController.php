<?php
require_once '../../config.php';  // Make sure the path is correct
require_once '../../model/User.php';  // Include the User model

class UserController {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    // Add a new user
    public function addUser(User $user) {
        try {
            $sql = "INSERT INTO users (nom, prenom, email, role, password) VALUES (:nom, :prenom, :email, :role, :password)"; 
            $stmt = $this->pdo->prepare($sql);
            return $stmt->execute([
                'nom' => $user->getNom(),
                'prenom' => $user->getPrenom(),
                'email' => $user->getEmail(),
                'role' => $user->getRole(),
                'password' => password_hash($user->getPassword(), PASSWORD_DEFAULT)
            ]);
        } catch (PDOException $e) {
            error_log("Error adding user: " . $e->getMessage());
            return false;
        }
    }

    /*public function addUser(User $user) {
        try {
            $sql = "INSERT INTO users (nom, prenom, email, role, password) 
                    VALUES (:nom, :prenom, :email, :role, :password)";
            $stmt = $this->pdo->prepare($sql);
            return $stmt->execute([
                'nom' => $user->getNom(),
                'prenom' => $user->getPrenom(),
                'email' => $user->getEmail(),
                'role' => $user->getRole(),
                'password' => password_hash($user->getPassword(), PASSWORD_DEFAULT)
            ]);
        } catch (PDOException $e) {
            error_log("Error adding user: " . $e->getMessage());
            return false;
        }
    }*/
    
    // Delete a user by ID
    public function deleteUser($id) {
        try {
            $sql = "DELETE FROM users WHERE id = :id"; 
            $stmt = $this->pdo->prepare($sql);
            return $stmt->execute(['id' => $id]);
        } catch (PDOException $e) {
            error_log("Error deleting user: " . $e->getMessage());
            return false;
        }
    }
    
   /* // Update user information
    public function updateUser(User $user, $id) {
        try {
            $sql = "UPDATE users SET nom = :nom, prenom = :prenom, email = :email, role = :role WHERE id = :id"; 
            $stmt = $this->pdo->prepare($sql);
            return $stmt->execute([
                'id' => $id,
                'nom' => $user->getNom(),
                'prenom' => $user->getPrenom(),
                'email' => $user->getEmail(),
                'role' => $user->getRole()
            ]);
        } catch (PDOException $e) {
            error_log("Error updating user: " . $e->getMessage());
            return false;
        }
    }*/

    public function updateUser($id, $data) {
        try {
            $sql = "UPDATE users SET nom = :nom, prenom = :prenom, email = :email, role = :role WHERE id = :id"; 
            $stmt = $this->pdo->prepare($sql);
            return $stmt->execute([
                'id' => $id,
                'nom' => $data['nom'],
                'prenom' => $data['prenom'],
                'email' => $data['email'],
                'role' => $data['role']
            ]);
        } catch (PDOException $e) {
            error_log("Error updating user: " . $e->getMessage());
            return false;
        }
    }


      
    public function updateUserFront(User $user, $id) {
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


    // Get a single user by ID
    public function getUserById($id) {
        try {
            $sql = "SELECT * FROM users WHERE id = :id";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute(['id' => $id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error fetching user: " . $e->getMessage());
            return false;
        }
    }

    // Get all users
    public function getAllUsers() {
        try {
            $stmt = $this->pdo->query("SELECT * FROM users");
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error fetching users: " . $e->getMessage());
            return false;
        }
    }


}
?>