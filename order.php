<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: POST");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

function msg($success, $status, $message, $extra = [])
{
    return json_encode(array_merge([
        'success' => $success,
        'status' => $status,
        'message' => $message
    ], $extra));
}

// DATA FORM REQUEST
$data = json_decode(file_get_contents("php://input"));
$returnData = [];

if ($_SERVER["REQUEST_METHOD"] != "POST") {
    $returnData = msg(0, 404, 'Page Not Found!');
} else {
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

    if ($orderAmount === null || empty($goodsType) || empty($goodsCategory) || empty($referenceGoodsId) || empty($goodsName) || empty($goodsDetail)) {
        $fields = ['fields' => ['orderAmount', 'goodsType', 'goodsCategory', 'referenceGoodsId', 'goodsName', 'goodsDetail']];
        $returnData = msg(0, 422, 'Please provide all required fields!', $fields);
    } else {
        // Binance Pay Integration
        $binanceApiKey = "oopzdfrud9qzyb8w2dnh4gjzh2edtkjbiqjzv4yhhfyl41sfhyndtz260ixqzm7l";
        $binanceApiSecret = "11ormy7nagygzhfjbvw5o6tvnpkkzkidsrs4i5cwopfvnukxe3dlnpojfopivgug";
        $binanceApiEndpoint = "https://bpay.binanceapi.com/binancepay/openapi/v2/order";

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

        $json_payload = json_encode($payload);

        // Generate the signature
        $payloadWithTimestamp = $timestamp . "\n" . $nonce . "\n" . $json_payload . "\n";
        $signature = strtoupper(hash_hmac('SHA512', $payloadWithTimestamp, $binanceApiSecret));

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $binanceApiEndpoint);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $json_payload);

        $headers = [
            "Content-Type: application/json",
            "BinancePay-Certificate-SN: $binanceApiKey",
            "BinancePay-Timestamp: $timestamp",
            "BinancePay-Nonce: $nonce",
            "BinancePay-Signature: $signature",
        ];

        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $result = curl_exec($ch);

        if (curl_errno($ch)) {
            $returnData = msg(0, 500, 'Failed to create the order. Curl error: ' . curl_error($ch));
        } else {
            $response = json_decode($result, true);

            if (isset($response['status']) && $response['status'] === 'SUCCESS') {
                $returnData = msg(1, 201, 'Order Created', ['binanceResponse' => $response]);
            } else {
                $returnData = msg(0, 500, 'Failed to create the order. Binance Pay response: ' . $result);
            }
        }

        curl_close($ch);
    }
}

echo $returnData;
?>
