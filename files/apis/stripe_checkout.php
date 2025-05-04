<?php
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    die('Invalid request method.');
}

$apiKey = '';
$currency = $_POST['currency'] ?? 'usd';
$grandTotal = (float) $_POST['grand_total']; // Already discounted

if ($grandTotal <= 0) {
    die('Invalid total amount.');
}

$unitAmount = intval($grandTotal * 100); // Convert to cents

// Prepare flat POST data
$postData = [
    'success_url' => 'http://localhost/IP-Assignment/files/views/view_success.php',
    'cancel_url' => 'http://localhost/IP-Assignment/files/views/view_cancel.php',
    'mode' => 'payment',
    'line_items[0][price_data][currency]' => $currency,
    'line_items[0][price_data][product_data][name]' => 'Cart Purchase',
    'line_items[0][price_data][unit_amount]' => $unitAmount,
    'line_items[0][quantity]' => 1,
];

$data = http_build_query($postData);

$headers = [
    "Authorization: Bearer $apiKey",
    "Content-Type: application/x-www-form-urlencoded"
];

$context = stream_context_create([
    'http' => [
        'method' => 'POST',
        'header' => implode("\r\n", $headers),
        'content' => $data
    ]
]);

$response = file_get_contents('https://api.stripe.com/v1/checkout/sessions', false, $context);

if ($response !== false) {
    $result = json_decode($response, true);
    if (isset($result['url'])) {
        header('Location: ' . $result['url']);
        exit();
    } else {
        echo 'Stripe session created, but no URL returned.';
        echo '<pre>' . print_r($result, true) . '</pre>';
    }
} else {
    echo 'Failed to connect to Stripe.';
}
