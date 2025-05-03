<?php
// Replace with your actual key
// Build the POST data
$data = http_build_query([
    'success_url' => 'http://localhost/IP-Assignment/files/userIndex.php',
    'cancel_url' => 'http://localhost/IP-Assignment/files/views/admin_login_view.php',
    'mode' => 'payment',
    'line_items[0][price_data][currency]' => 'usd',
    'line_items[0][price_data][product_data][name]' => 'Test Product',
    'line_items[0][price_data][unit_amount]' => 2000, // Amount in cents (i.e., $20.00)
    'line_items[0][quantity]' => 1
        ]);

// Set HTTP headers
$headers = [
    "Authorization: Bearer $apiKey",
    "Content-Type: application/x-www-form-urlencoded"
];

// Create stream context for POST request
$context = stream_context_create([
    'http' => [
        'method' => 'POST',
        'header' => implode("\r\n", $headers),
        'content' => $data
    ]
        ]);

// Make the request to Stripe
$response = file_get_contents('https://api.stripe.com/v1/checkout/sessions', false, $context);

// Process the response
if ($response !== false) {
    $result = json_decode($response, true);

    if (isset($result['url'])) {
        // Redirect the user to Stripe Checkout
        header('Location: ' . $result['url']);
        exit();
    } else {
        echo 'Error: Stripe session created, but no redirect URL returned.';
        echo '<pre>' . print_r($result, true) . '</pre>';
    }
} else {
    echo 'Error: Failed to connect to Stripe.';
}
?>
