<?php
class SpeechesModel {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function showMySpeeches($entrepreneur_id) {
        $stmt = $this->pdo->prepare("SELECT * FROM speeches WHERE entrepreneur_id = :entrepreneur_id");
        $stmt->execute(['entrepreneur_id' => $entrepreneur_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getSpeechById($speech_id) {
        $stmt = $this->pdo->prepare("SELECT * FROM speeches WHERE ID_speech = :speech_id");
        $stmt->execute(['speech_id' => $speech_id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getPdo() {
        return $this->pdo;
    }
}
?>
