<?php
// public/boost_speech.php
// This script handles Stripe payment for boosting a speech without using Stripe PHP SDK.

// Load dependencies
require_once __DIR__ . '/../../config.php';

if (!isset($_GET['id'])) {
    http_response_code(400);
    echo "Speech ID is required.";
    exit;
}

$speechId = intval($_GET['id']);

// Prepare POST fields
$postFields = http_build_query([
    'payment_method_types[]' => 'card',
    'line_items[0][price_data][currency]' => 'usd',
    'line_items[0][price_data][product_data][name]' => 'Boost Speech #' . $speechId,
    'line_items[0][price_data][unit_amount]' => 476600, // $4766 in cents
    'line_items[0][quantity]' => 1,
    'mode' => 'payment',
'success_url' => 'http://localhost/wissal/dealhub/controller/listmyspeeches_controller.php?boost_success=1&id=' . $speechId,
'cancel_url' => 'http://localhost/wissal/dealhub/controller/listmyspeeches_controller.php?boost_cancel=1&id=' . $speechId,
]);

// Setup HTTP context with basic auth
$context = stream_context_create([
    'http' => [
        'method' => 'POST',
        'header' => "Authorization: Bearer $stripeSecretKey\r\n" .
                    "Content-Type: application/x-www-form-urlencoded\r\n",
        'content' => $postFields,
    ],
]);

// Send request to Stripe API
$response = file_get_contents($url, false, $context);
if ($response === FALSE) {
    http_response_code(500);
    echo "Error creating Stripe session.";
    exit;
}

$data = json_decode($response, true);
if (!isset($data['url'])) {
    http_response_code(500);
    echo "Invalid response from Stripe API.";
    exit;
}

// Redirect to Stripe Checkout
header("Location: " . $data['url']);
exit;
?>
