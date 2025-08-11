<?php
// create-checkout-session.php

header('Content-Type: application/json');

// Load .env file
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

// Get the secret key from .env
$stripe = new \Stripe\StripeClient($_ENV['STRIPE_SECRET_KEY']);

// reccommends useage of .env on local. Since this is a simple demo, the variables are directly assigned with values
// Do NOT expose the secret key onto the site.
$stripeSecretKey = ($_ENV['STRIPE_SECRET_KEY']); // <-- TODO : Enter Scret key

require __DIR__ . '/vendor/autoload.php'; // For when composer is used to install stripe.

\Stripe\Stripe::setApiKey($stripeSecretKey);

// Reads JSON body received via POST
$input = @file_get_contents("php://input");
$data = json_decode($input, true);

if (!$data) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid request']);
    exit;
}

try {
    // Can be sent from client or be fixed on server side.
    $line_items = $data['line_items'] ?? [
        [
            'price_data' => [
                'currency' => 'usd',
                'product_data' => ['name' => 'Electric Bike Model 1'],
                'unit_amount' => 129900
            ],
            'quantity' => 1
        ]
    ];

    $session = \Stripe\Checkout\Session::create([
        'payment_method_types' => ['card'],
        'line_items' => $line_items,
        'mode' => 'payment',
        'success_url' => $data['success_url'] ?? 'http://localhost:4242/stripeSuccess.html',
        'cancel_url' => $data['cancel_url'] ?? 'http://localhost:4242/stripeCancel.html',
    ]);

    echo json_encode(['id' => $session->id]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
