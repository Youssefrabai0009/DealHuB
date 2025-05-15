<?php
require_once __DIR__ . '/../config.php';  // Make sure the path is correct
require_once __DIR__ . '/../model/Complaint.php';  // Include the User model

$controller = new ComplaintsController($pdo);
$action = $_GET['action'] ?? 'index';
$id = $_GET['id'] ?? null;  // Récupérer l'ID si présent

if (method_exists($controller, $action)) {
    // Passer l'ID à la méthode si l'action est 'edit'
    if ($action === 'edit' && $id !== null) {
        $controller->$action($id);
    } else if ($action === 'delete' && $id !== null) {
        $controller->$action($id);
    } else if ($action === 'reply' && $id !== null) {
        $controller->$action($id);
    } else if ($action === 'modify_reply' && $id !== null) {
        $controller->$action($id);
    } else if ($action === 'delete_reply' && $id !== null) {
        $controller->$action($id);
    } else if ($action === 'view_complaint_replies' && $id !== null) {
        $controller->$action($id);
    } else {
        $controller->$action();
    }
}



class ComplaintsController {

    public $complaintModel;

    public function __construct($pdo) {
        $this->complaintModel = new Complaint($pdo);
    }

    protected function view($view, $data = []) {
        extract($data);
        // require_once __DIR__ . '/../views/layout/header.php';
        require_once __DIR__ . '/../view/' . $view . '.php';
    }


    // Afficher toutes les réclamations
    public function frontoffice() {
        //get connected user
        session_start();
        $userId = $_SESSION['user']['id'];

        // Récupérer toutes les réclamations depuis la base de données
        $complaints = $this->complaintModel->getAllComplaints($userId);
        // Passer les réclamations à la vue
        $this->view('frontoffice/complaints_list', ['complaints' => $complaints]);
    }
    
    public function backoffice() {
        $filters = [
            'status' => $_GET['status'] ?? '',
            'title' => $_GET['title'] ?? '',
            'date_filter' => $_GET['date_filter'] ?? '',
            'sort_order' => $_GET['sort_order'] ?? 'desc'
        ];
    
        $complaints = $this->complaintModel->getFilteredComplaints($filters);

        $this->view('backoffice/complaints_list_back', [
            'complaints' => $complaints,
            'filters' => $filters
        ]);
    }
    
    


    public function add(): void {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            session_start();
            $userId = $_SESSION['user']['id'];

            $title = htmlspecialchars($_POST['title']);
            $description = htmlspecialchars($_POST['description']);
    
            // Validation côté serveur : max 7 mots dans le titre
            $titleWords = explode(' ', $title);
            if (count($titleWords) > 7) {
                $error = "Le titre ne doit pas dépasser 7 mots.";
                $this->view('frontoffice/add_complaint', ['error' => $error]);
                return;
            }
    
            // Vérification des propos avec Gemini
            $fullText = $title . "\n" . $description;
            $hasBadWords = $this->checkWithGemini($fullText);
    
            if ($hasBadWords) {
                $error = "Votre réclamation contient des propos inappropriés.";
                $this->view('frontoffice/add_complaint', ['error' => $error]);
                return;
            }

            $complaintTopic = $this->generateTopicWithGemini($fullText);
            
    
            // Ajout dans la base si tout est ok
            if ($this->complaintModel->addComplaint($title, $description, $complaintTopic, $userId)) {
                header('Location: /wissal/DealHuB/controller/ComplaintsController.php?action=frontoffice');
            } else {
                echo "Erreur lors de l'ajout de la réclamation.";
            }
        }

        $this->view('frontoffice/add_complaint');
    }

    private function generateTopicWithGemini($text) {    
        $prompt = "Je vais vous donner une réclamation d'un utilisateur de mon site et je veux que tu extrais le sujet de cette reclamation en un mot ou maximum 2 mots. Reponds par le sujet extracté seulement. Texte du reclamation : " . $text;
    
        $textResponse = $this->sendWithGemini($prompt);
    
        return $textResponse;
    }


    private function checkWithGemini($text) {
        $prompt = "Est-ce que ce texte contient des insultes ou des propos offensants je veux que tu bloque juste ces mots \"blabla, flafla, plapla \" ? Réponds simplement par 'oui' ou 'non'. Texte : " . $text;

        $textResponse = $this->sendWithGemini($prompt);
    
        return stripos($textResponse, 'oui') !== false;
    }

    private function sendWithGemini($prompt){
        $apiKey = 'AIzaSyB_YOqTXipIBAogpStwHhNCTaE7Geq-TCg';  // Remplace par ta vraie clé API Gemini
        $url = 'https://generativelanguage.googleapis.com/v1beta/models/gemini-2.0-flash:generateContent?key=' . $apiKey;
    
    
        $data = [
            'contents' => [
                ['parts' => [['text' => $prompt]]]
            ]
        ];
    
        $options = [
            'http' => [
                'header'  => "Content-type: application/json",
                'method'  => 'POST',
                'content' => json_encode($data),
            ]
        ];
    
        $context = stream_context_create($options);
        $result = file_get_contents($url, false, $context);
    
        if ($result === FALSE) {
            return false; // En cas d'erreur, laisser passer
        }
    
        $response = json_decode($result, true);
        $textResponse = $response['candidates'][0]['content']['parts'][0]['text'] ?? '';
        return $textResponse;
    }
    
    
    // Modifier une réclamation
    public function edit($id) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            session_start();
            $userId = $_SESSION['user']['id'];

            $title = htmlspecialchars($_POST['title']);
            $description = htmlspecialchars($_POST['description']);

            // Server-side validation to check if title exceeds 7 words
            $titleWords = explode(' ', $title);
            if (count($titleWords) > 7) {
                // If title exceeds 7 words, show an error message
                $error = "Le titre ne doit pas dépasser 7 mots.";
                // Pass the error to the view
                $this->view('frontoffice/add_complaint', ['error' => $error]);
                return; // Stop further processing
            }
            
            // Vérification des propos avec Gemini
            $fullText = $title . "\n" . $description;
            $hasBadWords = $this->checkWithGemini($fullText);
    
            if ($hasBadWords) {
                $error = "Votre réclamation contient des propos inappropriés.";
                $this->view('frontoffice/add_complaint', ['error' => $error]);
                return;
            }

            $complaintTopic = $this->generateTopicWithGemini($fullText);
            
            
            if ($this->complaintModel->updateComplaint($id, $title, $description, $complaintTopic, $userId)) {
                header('Location: /wissal/DealHuB/controller/ComplaintsController.php?action=frontoffice');
            } else {
                echo "Erreur lors de la modification de la réclamation.";
            }
        }

        // Récupérer la réclamation par ID pour la pré-remplir dans le formulaire
        $complaint = $this->complaintModel->getComplaintById($id)->fetch();
        $this->view('frontoffice/edit_complaint', ['complaint' => $complaint]);
    }

    // Supprimer une réclamation
    public function delete($id) {
        session_start();
        $userId = $_SESSION['user']['id'];
        if ($this->complaintModel->deleteComplaint($id, $userId)) {
            header('Location: /wissal/DealHuB/controller/ComplaintsController.php?action=frontoffice');
        } else {
            echo "Erreur lors de la suppression de la réclamation.";
        }
    }

    // Ajouter une réponse à une réclamation
    public function reply($id) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $response_text = htmlspecialchars($_POST['response_text']);


            // Split the response into words
            $responseWords = explode(' ', $response_text);
            $wordCount = count($responseWords);

            // Initialize an empty error array
            $errors = [];

            // Check if response has more than 3 words
            if ($wordCount <= 3) {
                $errors[] = "La réponse doit comporter plus de 3 mots.";
            }

            // Check if response starts with uppercase letter
            if (!preg_match('/^[A-Z]/', $response_text)) {
                $errors[] = "La réponse doit commencer par une majuscule.";
            }

            // Check if response ends with period
            if (!preg_match('/\.$/', $response_text)) {
                $errors[] = "La réponse doit se terminer par un point.";
            }

            // If there are errors, display them and return to the form
            if (!empty($errors)) {
                $this->view('backoffice/reply_complaint', ['errors' => $errors, 'complaint_id' => $id]);
                return;
            }


            // Ajouter la réponse à la réclamation
            if ($this->complaintModel->addResponse($id, $response_text)) {
                header('Location: /wissal/DealHuB/view/backoffice/complaints_list_back.php');
            } else {
                echo "Erreur lors de l'ajout de la réponse.";
            }
        }

        // Récupérer la réclamation et la réponse pour la pré-remplir
        $complaint = $this->complaintModel->getComplaintById($id)->fetch();
        $this->view('backoffice/reply_complaint', ['complaint' => $complaint]);
    }

    
    // Modify a response
    public function modify_reply($id) {
        // Fetch the current reply by ID
        $response = $this->complaintModel->getResponseById($id)->fetch();

        // Check if the form is submitted
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $responseText = htmlspecialchars($_POST['response_text']);

            // Split the response into words
            $responseWords = explode(' ', $responseText);
            $wordCount = count($responseWords);

            // Initialize an empty error array
            $errors = [];

            // Check if response has more than 3 words
            if ($wordCount <= 3) {
                $errors[] = "La réponse doit comporter plus de 3 mots.";
            }

            // Check if response starts with uppercase letter
            if (!preg_match('/^[A-Z]/', $responseText)) {
                $errors[] = "La réponse doit commencer par une majuscule.";
            }

            // Check if response ends with period
            if (!preg_match('/\.$/', $responseText)) {
                $errors[] = "La réponse doit se terminer par un point.";
            }

            // If there are errors, display them and return to the form
            if (!empty($errors)) {
                $this->view('backoffice/reply_complaint', ['errors' => $errors, 'reply_id' => $id, 'reply' => $response]);
                return;
            }

            if ($this->complaintModel->updateResponse($id, $responseText)) {
                // Redirect to the complaint page after successful modification
            header('Location: /wissal/DealHuB/view/backoffice/complaints_list_back.php');
            } else {
                echo "Error updating response.";
            }
        }

        $this->view('backoffice/reply_complaint', ['reply' => $response]);
    }

    // Delete a response
    public function delete_reply($id) {
        // Fetch the response to get the complaint ID
        $response = $this->complaintModel->getResponseById($id)->fetch();
        
        if ($this->complaintModel->deleteResponse($id)) {
            // Redirect to the complaint page after successful deletion
            header('Location: /wissal/DealHuB/view/backoffice/complaints_list_back.php');
        } else {
            echo "Error deleting response.";
        }
    }

    public function view_complaint_replies($id) {

        //get complaint details
        $complaint = $this->complaintModel->getComplaintById($id)->fetch();

        // Fetch the responses for the relevant complaint ID
        $response = $this->complaintModel->getResponsesByComplaintId($id);

        if ( $response !== null) {
            // Redirect to the complaint page after successful deletion
            $this->view('frontoffice/replies_list', ['replies' => $response, 'complaint' => $complaint]);
        } else {
            echo "There are no replies yet.";
        }
    }

    public function statistics()
    {
        $statsByTopic = $this->complaintModel->getComplaintsCountByTopic();
        $statsByDate = $this->complaintModel->getComplaintsCountByDate();
        $stats = $this->complaintModel->getStatistics();

        $this->view('backoffice/complaints_statistics', [
            'statsByTopic' => $statsByTopic,
            'statsByDate' => $statsByDate,
            'stats' => $stats
        ]);
    }
    
    


}
?>
