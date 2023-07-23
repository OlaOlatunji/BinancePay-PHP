<?php 
// Define constants
define('BINANCE_PAY', '168677762');
define('BINANCE_PAY_SECRET', 'oe9z3bwax0symufldlaf3gyba20qkkyccs8bujdkrmm8nvvy484pi9gt40a97sqy');

// Generate nonce string 
$chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'; 
$nonce = ''; 
for($i=1; $i <= 32; $i++) 
{ 
    $pos = mt_rand(0, strlen($chars) - 1); 
    $char = $chars[$pos]; 
    $nonce .= $char; 
} 

$ch = curl_init(); 
$timestamp = round(microtime(true) * 1000); 
// Request body 
$request = array( 
    "merchantTradeNo" => isset($_POST['merchantTradeNo']) ? $_POST['merchantTradeNo'] : null, // Get merchantTradeNo from POST data
); 

$json_request = json_encode($request); 
$payload = $timestamp."\n".$nonce."\n".$json_request."\n"; 
$signature = strtoupper(hash_hmac('SHA512',$payload,BINANCE_PAY_SECRET)); 

$headers = array(); 
$headers[] = "Content-Type: application/json"; 
$headers[] = "BinancePay-Timestamp: $timestamp"; 
$headers[] = "BinancePay-Nonce: $nonce"; 
$headers[] = "BinancePay-Certificate-SN: ".BINANCE_PAY; 
$headers[] = "BinancePay-Signature: $signature"; 

// Set curl options
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers); 
curl_setopt($ch, CURLOPT_URL, "https://bpay.binanceapi.com/binancepay/openapi/v2/order/query"); 
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
curl_setopt($ch, CURLOPT_POST, 1); 
curl_setopt($ch, CURLOPT_POSTFIELDS, $json_request); 

// Execute curl request
$result = curl_exec($ch); 

// Check for errors
if (curl_errno($ch)) {
    $response = array(
        'success' => false,
        'error' => 'Error: ' . curl_error($ch),
    );
} else {
    // Output result
    $response_data = json_decode($result, true);
    if ($response_data['code'] === '000000') {
        $response = array(
            'success' => true,
            'status' => $response_data['status'],
        );
    } else {
        $response = array(
            'success' => false,
            'error' => $response_data['msg'],
        );
    }
}

// Close curl
curl_close($ch); 

// Send the response as JSON
header('Content-Type: application/json');
echo json_encode($response);
