<?php
class Response {
    private $conn;
    private $table = 'responses';

    public function __construct($db) {
        $this->conn = $db;
    }

    // Ajouter une réponse
    public function addResponse($complaint_id, $user_id, $response_text) {
        $query = "INSERT INTO " . $this->table . " (complaint_id, user_id, response_text) VALUES (:complaint_id, :user_id, :response_text)";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':complaint_id', $complaint_id);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->bindParam(':response_text', $response_text);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Modifier une réponse
    public function updateResponse($response_id, $response_text) {
        $query = "UPDATE " . $this->table . " SET response_text = :response_text WHERE id = :id";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':response_text', $response_text);
        $stmt->bindParam(':id', $response_id);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Supprimer une réponse
    public function deleteResponse($response_id) {
        $query = "DELETE FROM " . $this->table . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':id', $response_id);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Obtenir toutes les réponses d'une réclamation
    public function getResponsesByComplaint($complaint_id) {
        $query = "SELECT * FROM " . $this->table . " WHERE complaint_id = :complaint_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':complaint_id', $complaint_id);
        $stmt->execute();
        return $stmt;
    }
}
?>
