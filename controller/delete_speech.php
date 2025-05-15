<?php
require_once '../config.php';
require_once '../model/Speechesmodel.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $speech_id = $_POST['id'];

    try {
        $stmt = $pdo->prepare("DELETE FROM speeches WHERE ID_speech = :id");
        $stmt->execute(['id' => $speech_id]);
        echo json_encode(['success' => true]);
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'error' => $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'Invalid request method']);
}
?>
