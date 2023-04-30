<?php

// Define constants
define('BP_BASE_URL', 'https://bpay.binanceapi.com/binancepay');
define('BP_API_VERSION', 'v2');
define('BP_CERTIFICATE_SN', '***yourbinancepay***');
define('BP_SECRET_KEY', '***yourbinancepaysecret***');
define('BP_USDT_TO_NGN', 750);

// Check if required parameters are present
if (!isset($_POST['amount'], $_POST['currency'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid request parameters']);
    exit();
}

// Prepare request data
$requestBody = [
    'merchantTradeNo' => uniqid('BPCO_'),
    'amount' => $_POST['amount'],
    'currency' => $_POST['currency'],
    'payTypeEnum' => 'APP',
];

// Convert USDT to NGN
if ($requestBody['currency'] === 'USDT') {
    $requestBody['amount'] = $requestBody['amount'] * BP_USDT_TO_NGN;
    $requestBody['currency'] = 'NGN';
}

// Prepare request headers
$nonce = bin2hex(random_bytes(16));
$timestamp = round(microtime(true) * 1000);
$payload = $timestamp . "\n" . $nonce . "\n" . json_encode($requestBody) . "\n";
$signature = hash_hmac('sha512', $payload, BP_SECRET_KEY);

$headers = [
    'Content-Type: application/json',
    'BinancePay-Timestamp: ' . $timestamp,
    'BinancePay-Nonce: ' . $nonce,
    'BinancePay-Certificate-SN: ' . BP_CERTIFICATE_SN,
    'BinancePay-Signature: ' . strtoupper($signature),
];

// Send request to Binance Pay API
$ch = curl_init(BP_BASE_URL . '/' . BP_API_VERSION . '/order');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($requestBody));
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

$response = curl_exec($ch);
curl_close($ch);

// Check for errors in the response
if (!$response) {
    http_response_code(500);
    echo json_encode(['error' => 'Failed to communicate with Binance Pay API']);
    exit();
}

$responseData = json_decode($response, true);

if (isset($responseData['error'])) {
    http_response_code(500);
    echo json_encode(['error' => 'Failed to create order: ' . $responseData['error']['message']]);
    exit();
}

// Return response
header('Content-Type: application/json');
echo json_encode([
    'order_no' => $responseData['orderNo'],
    'amount' => $requestBody['amount'],
    'currency' => $requestBody['currency'],
    'payment_url' => $responseData['paymentUrl'],
]);
