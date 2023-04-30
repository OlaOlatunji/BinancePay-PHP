<?php 
// Set constants
define('BINANCE_PAY_CERTIFICATE_SN', '***yourbinancepay***');
define('BINANCE_PAY_SECRET', '***yourbinancepaysecret***');

// Generate nonce string 
$chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'; 
$nonce = ''; 
for ($i = 1; $i <= 32; $i++) { 
    $pos = mt_rand(0, strlen($chars) - 1); 
    $char = $chars[$pos]; 
    $nonce .= $char; 
} 

// Create request payload
$request = array(
    'merchantId' => '123456',
    'currency' => 'USDT',
);

// Convert payload to JSON and generate payload signature
$json_request = json_encode($request); 
$timestamp = round(microtime(true) * 1000);
$payload = $timestamp."\n".$nonce."\n".$json_request."\n";
$signature = strtoupper(hash_hmac('SHA512', $payload, BINANCE_PAY_SECRET));

// Set request headers
$headers = array(
    'Content-Type: application/json',
    "BinancePay-Timestamp: $timestamp",
    "BinancePay-Nonce: $nonce",
    "BinancePay-Certificate-SN: " . BINANCE_PAY_CERTIFICATE_SN,
    "BinancePay-Signature: $signature",
);

// Set cURL options and send request
$ch = curl_init();
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_URL, 'https://bpay.binanceapi.com/binancepay/openapi/v2/balance');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $json_request);
$result = curl_exec($ch);

// Check for cURL errors and handle response
if (curl_errno($ch)) { 
    echo 'Error: ' . curl_error($ch); 
} else {
    $response = json_decode($result, true);
    if (isset($response['resultCode']) && $response['resultCode'] !== 'SUCCESS') {
        echo 'Error: ' . $response['resultMsg'];
    } else {
        echo $result;
    }
}

// Close cURL session
curl_close($ch);
