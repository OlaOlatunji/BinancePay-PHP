<?php
// Define constants for Binance Pay credentials
define('BINANCE_PAY_CERTIFICATE_SN', 'oopzdfrud9qzyb8w2dnh4gjzh2edtkjbiqjzv4yhhfyl41sfhyndtz260ixqzm7l');
define('BINANCE_PAY_SECRET', '11ormy7nagygzhfjbvw5o6tvnpkkzkidsrs4i5cwopfvnukxe3dlnpojfopivgug');
define('YOUR_MERCHANT_ID', '168677762');

// Get the amount from the POST data
$amount = isset($_POST['amount']) ? floatval($_POST['amount']) : null;

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
$headers[] = 'merchantId: ' . YOUR_MERCHANT_ID;
$headers[] = 'X-Forwarded-For: ' . $_SERVER['REMOTE_ADDR'];

// Build request body
$request = array(
    'env' => array('terminalType' => 'MINI_PROGRAM'),
    'merchantTradeNo' => '2224',
    'orderAmount' => $amount,
    'currency' => 'USDT',
    'goods' => array(
        'goodsType' => '01',
        'goodsCategory' => '0000',
        'referenceGoodsId' => 'balablu',
        'goodsName' => 'banana',
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

// Build payload
$payload = implode("\n", array(
    $headers[1],
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
