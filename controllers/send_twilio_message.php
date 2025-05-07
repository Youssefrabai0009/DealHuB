<?php
// Simple Twilio SMS sender without SDK using curl and REST API

// Twilio credentials
//houni twilio

header('Content-Type: application/json');

$input = json_decode(file_get_contents('php://input'), true);

if (!isset($input['investor_name']) || !isset($input['message'])) {
    echo json_encode(['success' => false, 'error' => 'Missing parameters']);
    exit;
}

$message_body = $input['message'];

// Since all investors have the same number, use it as recipient
$to_number = $investor_number;

$url = "https://api.twilio.com/2010-04-01/Accounts/$account_sid/Messages.json";

$data = http_build_query([
    'From' => $twilio_number,
    'To' => $to_number,
    'Body' => $message_body,
]);

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_USERPWD, "$account_sid:$auth_token");

$response = curl_exec($ch);
$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($http_code >= 200 && $http_code < 300) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'error' => 'Failed to send message']);
}
?>
