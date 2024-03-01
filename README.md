# BinancePay-PHP

An implementation of the Binancepay API for PHP

# Start-on-Ubuntu-or-Linux

php -S localhost:8080

![Binance Pay API](https://public.bnbstatic.com/image/cms/blog/20211207/273406c6-819c-4a48-a981-8c2dd8b0f907.png)

# Binance Pay API for PHP and Laravel using Curl
Binance Pay API for PHP and Laravel - This is a simple and quick repo on how to initiate crypto payments using the Official Binance API. You can use this to initiate ecommerce payments or any other payments of your choise from your website. 

## Binance Pay API: Authentication
The Binance Pay API uses API keys to authenticate requests. You can view and manage your API keys in the Binance Merchant Admin Portal.

Your API keys carry many privileges, so be sure to keep them secure! Do not share your secret API keys in publicly accessible areas such as GitHub, client-side code, and so forth.

All API requests must be made over HTTPS. Calls made over plain HTTP will fail. API requests without authentication will also fail.

## Apply API identity key and API secret key#
Register Merchant Account at Binance
Login merchant account and create new API identity key/API secret key [Binance Merchant Admin Portal](https://merchant.binance.com/).


## Create Order
Create order API Version 2 used for merchant/partner to initiate acquiring order.

### Base Url

```
https://bpay.binanceapi.com
```

### Endpoint
```
POST /binancepay/openapi/v2/order
```

### Request Parameters
Check here for all the request parameters
[-> Check here](https://developers.binance.com/docs/binance-pay/api-order-create-v2#request-parameters)

## Sample Data

```
{
  "orderAmount": 10.0,
  "goodsType": "01",
  "goodsCategory": "D000",
  "referenceGoodsId": "12345",
  "goodsName": "Product Name",
  "goodsDetail": "Product Description"
}
```

## Sample SUCCESS JSON Response

```
{
    "status": "SUCCESS",
    "message": "Order Created",
    "code": "000000",
    "data": {
        "currency": "USDT",
        "totalFee": "10",
        "prepayId": "260835475108052992",
        "terminalType": "APP",
        "expireTime": 1699301383637,
        "qrcodeLink": "https://public.bnbstatic.com/static/payment/20231106/505c33f7-c782-4a85-81f8-0b2beed04b34.jpg",
        "qrContent": "https://app.binance.com/qr/dplkacbb61f8a4334a24b0b2acd6dcae1ab2",
        "checkoutUrl": "https://pay.binance.com/en/checkout/3815e96ef371432c8809a543c6dc2311",
        "deeplink": "bnc://app.binance.com/payment/secpay?tempToken=Ezkxl7aEdsmSB3TPL096jWIRFK2kvnhe",
        "universalUrl": "https://app.binance.com/payment/secpay?linkToken=3815e96ef371432c8809a543c6dc2311&_dp=Ym5jOi8vYXBwLmJpbmFuY2UuY29tL3BheW1lbnQvc2VjcGF5P3RlbXBUb2tlbj1Femt4bDdhRWRzbVNCM1RQTDA5NmpXSVJGSzJrdm5oZQ"
    }
}
```