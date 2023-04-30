<?php 
// Define constants
define('BINANCE_PAY', 'zp0ree8cq1lksnaohlg9bn57oshbbodfb62btkavzxel9qac36ctw9mmflsrjwrv');
define('BINANCE_PAY_SECRET', '9frnw7dgcs5zlwi8yf4yrz9snjzoakwufwymgdc3n3u24fefw70eajwuvoeipxmy');

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
    "merchantTradeNo" => "2223", 
); 

$json_request = json_encode($request); 
$payload = $timestamp."\n".$nonce."\n".$json_request."\n"; 
$signature = strtoupper(hash_hmac('SHA512',$payload,BINANCE_PAY_SECRET)); 

echo $timestamp."<br/>"; 
echo $signature."<br/>"; 

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

// Handle errors
if (curl_errno($ch)) {
    echo 'Error:' . curl_error($ch); 
    // Log error or send alert to admin
} else {
    echo $result;
}

// Close curl
curl_close ($ch); 
