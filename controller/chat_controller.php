<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
header('Content-Type: application/json');
require_once __DIR__ . '/../config.php';



if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Fetch all messages with pitch title or ID
    $stmt = $pdo->prepare("
        SELECT sc.ID_message, sc.ID_speech, sc.message, sc.sent_at, s.Titre as title
        FROM speech_chat sc
        LEFT JOIN speeches s ON sc.ID_speech = s.ID_speech
        ORDER BY sc.sent_at ASC
    ");
    $stmt->execute();
    $messages = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($messages);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Insert new message
    $input = json_decode(file_get_contents('php://input'), true);
    $id_speech = $input['ID_speech'] ?? null;
    $message = $input['message'] ?? null;

    if (!$id_speech || !$message) {
        http_response_code(400);
        echo json_encode(['error' => 'Missing ID_speech or message']);
        exit;
    }

    $stmt = $pdo->prepare("INSERT INTO speech_chat (ID_speech, message) VALUES (:id_speech, :message)");
    $stmt->bindParam(':id_speech', $id_speech, PDO::PARAM_INT);
    $stmt->bindParam(':message', $message, PDO::PARAM_STR);

    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        http_response_code(500);
        echo json_encode(['error' => 'Failed to insert message']);
    }
    exit;
}

// If method not allowed
http_response_code(405);
echo json_encode(['error' => 'Method not allowed']);
exit;
?>
