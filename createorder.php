<?php
// Define constants for Binance Pay credentials
define('BINANCE_PAY_CERTIFICATE_SN', 'zp0ree8cq1lksnaohlg9bn57oshbbodfb62btkavzxel9qac36ctw9mmflsrjwrv');
define('BINANCE_PAY_SECRET', '9frnw7dgcs5zlwi8yf4yrz9snjzoakwufwymgdc3n3u24fefw70eajwuvoeipxmy');

// Generate nonce string
$chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
$nonce = '';
for ($i = 1; $i <= 32; $i++) {
    $pos = mt_rand(0, strlen($chars) - 1);
    $char = $chars[$pos];
    $nonce .= $char;
}

// Initialize cURL
$ch = curl_init();

// Set request headers
$headers = array();
$headers[] = 'Content-Type: application/json';
$headers[] = 'BinancePay-Timestamp: ' . round(microtime(true) * 1000);
$headers[] = 'BinancePay-Nonce: ' . $nonce;
$headers[] = 'BinancePay-Certificate-SN: ' . BINANCE_PAY_CERTIFICATE_SN;

// Build request body
$request = array(
    'env' => array('terminalType' => 'MINI_PROGRAM'),
    'merchantTradeNo' => '2224',
    'orderAmount' => 0.10,
    'currency' => 'USDT',
    'goods' => array(
        'goodsType' => '01',
        'goodsCategory' => '0000',
        'referenceGoodsId' => 'abc001',
        'goodsName' => 'apple',
        'goodsUnitAmount' => array('currency' => 'USDT', 'amount' => 1.00),
    ),
    'shipping' => array(
        'shippingName' => array('firstName' => 'Joe', 'lastName' => 'Don'),
        'shippingAddress' => array('region' => 'NZ'),
    ),
    'buyer' => array(
        'buyerName' => array('firstName' => 'cz', 'lastName' => 'zhao'),
    ),
);
$json_request = json_encode($request);

// Calculate signature
$payload = implode("\n", array(
    $headers[1], // timestamp
    $nonce,
    $json_request,
));
$signature = strtoupper(hash_hmac('SHA512', $payload, BINANCE_PAY_SECRET));

// Add signature header to request headers
$headers[] = 'BinancePay-Signature: ' . $signature;

// Set cURL options
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_URL, 'https://bpay.binanceapi.com/binancepay/openapi/v2/order');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $json_request);

// Execute cURL request
$result = curl_exec($ch);

// Check for errors
if (curl_errno($ch)) {
    echo 'Error: ' . curl_error($ch);
} else {
    // Output result
    echo $result;
}

// Close cURL
curl_close($ch);