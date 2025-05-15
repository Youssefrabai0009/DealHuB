<?php
session_start();
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../model/Speechesmodel.php';

class SpeechController {
    private $model;

    public function __construct($pdo) {
        $this->model = new SpeechesModel($pdo);
    }

    public function showForm() {
        global $pdo; // Make $pdo available in the scope
        $entrepreneur_id = $_SESSION['user']['id']; // Use logged-in user ID from session // temporaryspeech
        $speeches = $this->model->showMySpeeches($entrepreneur_id);

        // Fetch offers data for all speeches
        $stmt = $pdo->query("SELECT speechnumber, amount, equity, investor_name FROM offer");
        $offers = $stmt->fetchAll(PDO::FETCH_ASSOC);

include __DIR__ . '/../view/frontoffice/listmyspeeches.php'; // Updated path
    }

    public function addPitch() {//dhhrli mataamel chy 
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $title = $_POST['title'];
            $video = $_POST['video'];
            $amount = $_POST['amount'];
            $equity = $_POST['equity'];
            $entrepreneur_id = $_SESSION['user']['id']; // temporaryspeech

            try {
                $stmt = $this->model->getPdo()->prepare("INSERT INTO speeches (Titre, video, amount, equity, entrepreneur_id) VALUES (:title, :video, :amount, :equity, :entrepreneur_id)");
                $stmt->execute([
                    'title' => $title,
                    'video' => $video,
                    'amount' => $amount,
                    'equity' => $equity,
                    'entrepreneur_id' => $entrepreneur_id
                ]);
                header("Location: ../public/indexpublic.php"); // Redirect after adding
            } catch (Exception $e) {
                echo "Error: " . $e->getMessage();
            }
        }
    }
}
?>
</create_file>
