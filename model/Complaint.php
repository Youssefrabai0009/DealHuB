<?php
class Complaint {
    private $conn;
    private $table = 'complaints';

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getAllComplaints($userId)
    {
        // Récupérer toutes les réclamations
        $query = "SELECT * FROM complaints WHERE user_id = :user_id ORDER BY created_at DESC"; // Tri par date
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $userId);

        $stmt->execute();
        
        return $stmt->fetchAll(); // Retourne toutes les réclamations sous forme de tableau
    }


    // Ajouter une réclamation
    public function addComplaint($title, $description, $complaintTopic, $userId) {
    $query = "INSERT INTO " . $this->table . " (title, description, topic, user_id) 
              VALUES (:title, :description, :topic, :user_id)";
    $stmt = $this->conn->prepare($query);

    $stmt->bindParam(':title', $title);
    $stmt->bindParam(':description', $description);
    $stmt->bindParam(':topic', $complaintTopic);
    $stmt->bindParam(':user_id', $userId);

    return $stmt->execute();
}


    // Modifier une réclamation
    public function updateComplaint($id, $title, $description, $complaintTopic, $userId) {
        $query = "UPDATE " . $this->table . " SET title = :title, description = :description, topic = :topic WHERE id = :id AND user_id = :user_id";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':title', $title);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':topic', $complaintTopic);
        $stmt->bindParam(':user_id', $userId);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Supprimer une réclamation
    public function deleteComplaint($id, $userId) {
        $query = "DELETE FROM " . $this->table . " WHERE id = :id AND user_id = :user_id";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':user_id', $userId);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Obtenir toutes les réclamations d'un utilisateur
    public function getComplaintsByUser($user_id) {
        $query = "SELECT * FROM " . $this->table . " WHERE id = :user_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->execute();
        return $stmt;
    }

    // Obtenir une réclamation par ID
    public function getComplaintById($id) {
        $query = "SELECT * FROM " . $this->table . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt;
    }

    public function addResponse($complaint_id, $response_text) {
        try {
            // Démarrer une transaction
            $this->conn->beginTransaction();
    
            // 1. Ajouter la réponse
            $stmt = $this->conn->prepare("INSERT INTO responses (complaint_id, response_text) VALUES (?, ?)");
            $stmt->execute([$complaint_id, $response_text]);
    
            // 2. Mettre à jour le statut de la plainte
            $update = $this->conn->prepare("UPDATE complaints SET status = 'closed' WHERE id = ?");
            $update->execute([$complaint_id]);
    
            // Valider la transaction
            $this->conn->commit();
    
            return $stmt->rowCount() > 0;
        } catch (PDOException $e) {
            // En cas d'erreur, annuler la transaction
            $this->conn->rollBack();
            // Optionnel : journaliser ou afficher l'erreur
            return false;
        }
    }
        
    public function getResponsesByComplaintId($complaint_id) {
        $stmt = $this->conn->prepare("SELECT * FROM responses WHERE complaint_id = ?");
        $stmt->execute([$complaint_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function getResponseById($id) {
        $query = "SELECT * FROM responses WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt;
    }

    // Method to update a response
    public function updateResponse($id, $responseText) {
        $stmt = $this->conn->prepare("UPDATE responses SET response_text = :response_text WHERE id = :id");
        return $stmt->execute(['id' => $id, 'response_text' => $responseText]);
    }

    // Method to delete a response
    public function deleteResponse($id) {
        
        // 1. Récupérer l'ID de la réclamation associée à cette réponse
        $stmt = $this->conn->prepare("SELECT complaint_id FROM responses WHERE id = :id");
        $stmt->execute(['id' => $id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$result) {
            return false; // La réponse n'existe pas
        }

        $complaintId = $result['complaint_id'];

        // 2. Supprimer la réponse
        $stmt = $this->conn->prepare("DELETE FROM responses WHERE id = :id");
        $deleteSuccess = $stmt->execute(['id' => $id]);

        if (!$deleteSuccess) {
            return false; // Échec de la suppression
        }

        // 3. Vérifier s'il reste encore d'autres réponses pour cette réclamation
        $stmt = $this->conn->prepare("SELECT COUNT(*) FROM responses WHERE complaint_id = :complaint_id");
        $stmt->execute(['complaint_id' => $complaintId]);
        $responseCount = $stmt->fetchColumn();

        // 4. Si plus aucune réponse, mettre à jour le statut de la réclamation
        if ($responseCount == 0) {
            $stmt = $this->conn->prepare("UPDATE complaints SET status = 'open' WHERE id = :complaint_id");
            $stmt->execute(['complaint_id' => $complaintId]);
        }

        return true;
    }

    public function getFilteredComplaints($filters) {
        $query = "SELECT * FROM complaints WHERE 1=1";
        $params = [];
    
        // Filtrer par statut
        if (!empty($filters['status'])) {
            $query .= " AND status = :status";
            $params[':status'] = $filters['status'];
        }
    
        // Filtrer par titre
        if (!empty($filters['title'])) {
            $query .= " AND title LIKE :title";
            $params[':title'] = '%' . $filters['title'] . '%';
        }
    
        // Filtrer par date
        if (!empty($filters['date_filter'])) {
            if ($filters['date_filter'] == 'today') {
                $query .= " AND DATE(created_at) = CURDATE()";
            } elseif ($filters['date_filter'] == 'last_week') {
                $query .= " AND created_at >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)";
            } elseif ($filters['date_filter'] == 'last_month') {
                $query .= " AND created_at >= DATE_SUB(CURDATE(), INTERVAL 1 MONTH)";
            }
        }
    
        // Tri par date
        $sortOrder = strtoupper($filters['sort_order']) == 'ASC' ? 'ASC' : 'DESC';
        $query .= " ORDER BY created_at $sortOrder";
    
        $stmt = $this->conn->prepare($query);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getStatistics() {
        $query = "
            SELECT 
                COUNT(*) AS total_complaints,
                SUM(status = 'open') AS open_complaints,
                SUM(status = 'closed') AS closed_complaints,
                DATE(created_at) AS date,
                COUNT(*) AS complaints_per_day
            FROM complaints
            GROUP BY DATE(created_at)
            ORDER BY DATE(created_at) DESC
            LIMIT 7
        ";
    
        return $this->conn->query($query)->fetchAll(PDO::FETCH_ASSOC);
    }
    
    
    public function getComplaintsCountByTopic()
    {
        $stmt = $this->conn->query("SELECT topic, COUNT(*) as count FROM complaints GROUP BY topic");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getComplaintsCountByDate()
    {
        $stmt = $this->conn->query("SELECT DATE(created_at) as date, COUNT(*) as count FROM complaints GROUP BY DATE(created_at)");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    
}
?>
