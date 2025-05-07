<?php
require_once '../config/data_base.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);
    $id = $input['id'] ?? null;
    $message = $input['message'] ?? null;

    if ($id === null || $message === null) {
        http_response_code(400);
        echo json_encode(['success' => false, 'error' => 'Missing message ID or message content']);
        exit;
    }

    try {
        $stmt = $pdo->prepare("UPDATE speech_chat SET message = :message WHERE ID_message = :id");
        $stmt->execute(['message' => $message, 'id' => $id]);

        if ($stmt->rowCount() > 0) {
            echo json_encode(['success' => true]);
        } else {
            http_response_code(404);
            echo json_encode(['success' => false, 'error' => 'Message not found or no change']);
        }
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['success' => false, 'error' => $e->getMessage()]);
    }
} else {
    http_response_code(405);
    echo json_encode(['success' => false, 'error' => 'Method not allowed']);
}
?>
