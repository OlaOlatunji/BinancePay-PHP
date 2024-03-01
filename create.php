<!DOCTYPE html>
<html>

<head>
    <title>Order Form</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>

<body>
    <div class="container mt-5">
        <h2>Create an Order</h2>
        <form id="orderForm" action="order.php" method="POST">
            <div class="form-group">
                <label for="orderAmount">Order Amount:</label>
                <input type="text" class="form-control" id="orderAmount" name="orderAmount" placeholder="Enter Order Amount">
            </div>
            <div class="form-group">
                <label for="goodsType">Goods Type:</label>
                <input type="text" class="form-control" id="goodsType" name "goodsType" placeholder="Enter Goods Type">
            </div>
            <div class="form-group">
                <label for="goodsCategory">Goods Category:</label>
                <input type="text" class="form-control" id="goodsCategory" name="goodsCategory" placeholder="Enter Goods Category">
            </div>
            <div class="form-group">
                <label for="referenceGoodsId">Reference Goods ID:</label>
                <input type="text" class="form-control" id="referenceGoodsId" name="referenceGoodsId" placeholder="Enter Reference Goods ID">
            </div>
            <div class "form-group">
                <label for="goodsName">Goods Name:</label>
                <input type="text" class="form-control" id="goodsName" name="goodsName" placeholder="Enter Goods Name">
            </div>
            <div class="form-group">
                <label for="goodsDetail">Goods Detail:</label>
                <input type="text" class="form-control" id="goodsDetail" name="goodsDetail" placeholder="Enter Goods Detail">
            </div>
            <button type="submit" class="btn btn-primary">Create Order</button>
        </form>
        <div id="resultSection" style="display: none;">
            <h2>Order Result</h2>
            <p><strong>Currency:</strong> <span id="resultCurrency"></span></p>
            <p><strong>Total Fee:</strong> <span id="resultTotalFee"></span></p>
        </div>
    </div>
</body>

</html>