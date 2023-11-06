<?php
// Set CORS headers
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: POST");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// Function to create response message
function msg($status, $message, $extra = [])
{
    return json_encode(array_merge([
        'status' => $status,
        'message' => $message
    ], $extra));
}

// DATA FORM REQUEST
$data = json_decode(file_get_contents("php://input"));
$returnData = [];

if ($_SERVER["REQUEST_METHOD"] != "POST") {
    // Return a message for unsupported HTTP method
    $returnData = msg(0, 404, 'Page Not Found!');
} else {
    // Generate nonce and timestamp for Binance Pay request
    $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $nonce = '';
    for ($i = 1; $i <= 32; $i++) {
        $pos = mt_rand(0, strlen($chars) - 1);
        $char = $chars[$pos];
        $nonce .= $char;
    }

    $timestamp = round(microtime(true) * 1000);

    $orderAmount = isset($data->orderAmount) ? floatval($data->orderAmount) : null;
    $goodsType = isset($data->goodsType) ? trim($data->goodsType) : null;
    $goodsCategory = isset($data->goodsCategory) ? trim($data->goodsCategory) : null;
    $referenceGoodsId = isset($data->referenceGoodsId) ? trim($data->referenceGoodsId) : null;
    $goodsName = isset($data->goodsName) ? trim($data->goodsName) : null;
    $goodsDetail = isset($data->goodsDetail) ? trim($data->goodsDetail) : null;

    if (
        $orderAmount === null ||
        empty($goodsType) ||
        empty($goodsCategory) ||
        empty($referenceGoodsId) ||
        empty($goodsName) ||
        empty($goodsDetail)
    ) {
        // Return an error message for missing or invalid fields
        $fields = ['fields' => ['orderAmount', 'goodsType', 'goodsCategory', 'referenceGoodsId', 'goodsName', 'goodsDetail']];
        $returnData = msg(0, 422, 'Please provide all required fields!', $fields);
    } else {
        // Binance Pay Integration
        $binanceApiKey = "oopzdfrud9qzyb8w2dnh4gjzh2edtkjbiqjzv4yhhfyl41sfhyndtz260ixqzm7l";
        $binanceApiSecret = "11ormy7nagygzhfjbvw5o6tvnpkkzkidsrs4i5cwopfvnukxe3dlnpojfopivgug";
        $binanceApiEndpoint = "https://bpay.binanceapi.com/binancepay/openapi/v2/order";

        // Prepare payload for the request
        $payload = [
            "env" => ["terminalType" => "APP"],
            "merchantTradeNo" => mt_rand(982538, 9825382937292),
            "orderAmount" => $orderAmount,
            "currency" => "USDT",
            "goods" => [
                "goodsType" => $goodsType,
                "goodsCategory" => $goodsCategory,
                "referenceGoodsId" => $referenceGoodsId,
                "goodsName" => $goodsName,
                "goodsDetail" => $goodsDetail
            ]
        ];

        // Convert payload to JSON
        $json_payload = json_encode($payload);

        // Generate the signature
        $payloadWithTimestamp = $timestamp . "\n" . $nonce . "\n" . $json_payload . "\n";
        $signature = strtoupper(hash_hmac('SHA512', $payloadWithTimestamp, $binanceApiSecret));

        // Initialize cURL session for making the request
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $binanceApiEndpoint);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $json_payload);

        // Set headers for the request
        $headers = [
            "Content-Type: application/json",
            "BinancePay-Certificate-SN: $binanceApiKey",
            "BinancePay-Timestamp: $timestamp",
            "BinancePay-Nonce: $nonce",
            "BinancePay-Signature: $signature",
        ];
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        // Execute the request and get the response
        $result = curl_exec($ch);

        if (curl_errno($ch)) {
            // Return an error message if cURL encounters an issue
            $returnData = msg(500, 'Failed to create the order. Curl error: ' . curl_error($ch));
        } else {
            // Parse the response and handle success or failure
            $response = json_decode($result, true);

            if (isset($response['status']) && $response['status'] === 'SUCCESS') {
                // Return a success message with Binance Pay response
                $returnData = msg(201, 'Order Created', [$response][0]);
            } else {
                // Return an error message with the Binance Pay response
                $returnData = msg(500, 'Failed to create the order. Binance Pay response:' . $result);
            }
        }

        // Close the cURL session
        curl_close($ch);
    }
}

// Output the response as JSON
echo $returnData;

?>