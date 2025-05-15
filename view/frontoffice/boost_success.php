<?php
// public/boost_success.php
require_once __DIR__ . '/..config.php';

if (!isset($_GET['id'])) {
    http_response_code(400);
    echo "Speech ID is required.";
    exit;
}

$speechId = intval($_GET['id']);

// Update the boosted field in the speeches table
try {
    $stmt = $pdo->prepare("UPDATE speeches SET boosted = 1 WHERE ID_speech = ?");
    if (!$stmt) {
        error_log("Prepare failed: " . implode(":", $pdo->errorInfo()));
        echo "Database prepare failed.";
        exit;
    }
    $result = $stmt->execute([$speechId]);
    if (!$result) {
        error_log("Execute failed: " . implode(":", $stmt->errorInfo()));
        echo "Database execute failed.";
        exit;
    }
} catch (Exception $e) {
    // Log error or handle accordingly
    error_log("Error updating boosted field: " . $e->getMessage());
    echo "Exception: " . $e->getMessage();
    exit;
}

// Redirect back to listmyspeeches.php with success message
header("Location: /controller/listmyspeeches_controller.php?boost_success=1&id=" . $speechId);
exit;
?>
