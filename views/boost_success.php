<?php
// public/boost_success.php
require_once __DIR__ . '/../config/data_base.php';

if (!isset($_GET['id'])) {
    http_response_code(400);
    echo "Speech ID is required.";
    exit;
}

$speechId = intval($_GET['id']);

// Update the boosted field in the speeches table
try {
    $stmt = $pdo->prepare("UPDATE speeches SET boosted = 1 WHERE ID_speech = ?");
    $stmt->execute([$speechId]);
} catch (Exception $e) {
    // Log error or handle accordingly
    error_log("Error updating boosted field: " . $e->getMessage());
    // Redirect anyway
}

// Redirect back to listmyspeeches.php with success message
header("Location: /entrepreneurship/views/listmyspeeches.php?boost_success=1&id=" . $speechId);
exit;
