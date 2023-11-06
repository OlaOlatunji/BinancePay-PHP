<!DOCTYPE html>
<html>

<head>
    <title>Order Form</title>
    <!-- Add Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>

<body>
    <div class="container mt-5">
        <h2>Create an Order</h2>
        <form id="orderForm">
            <div class="form-group">
                <label for="orderAmount">Order Amount:</label>
                <input type="text" class="form-control" id="orderAmount" name="orderAmount" placeholder="Enter Order Amount">
            </div>
            <div class="form-group">
                <label for="goodsType">Goods Type:</label>
                <input type="text" class="form-control" id="goodsType" name="goodsType" placeholder="Enter Goods Type">
            </div>
            <div class="form-group">
                <label for "goodsCategory">Goods Category:</label>
                <input type="text" class="form-control" id="goodsCategory" name="goodsCategory" placeholder="Enter Goods Category">
            </div>
            <div class="form-group">
                <label for="referenceGoodsId">Reference Goods ID:</label>
                <input type="text" class="form-control" id="referenceGoodsId" name="referenceGoodsId" placeholder="Enter Reference Goods ID">
            </div>
            <div class="form-group">
                <label for="goodsName">Goods Name:</label>
                <input type="text" class="form-control" id="goodsName" name="goodsName" placeholder="Enter Goods Name">
            </div>
            <div class="form-group">
                <label for="goodsDetail">Goods Detail:</label>
                <input type="text" class="form-control" id="goodsDetail" name="goodsDetail" placeholder="Enter Goods Detail">
            </div>
            <button type="submit" class="btn btn-primary">Create Order</button>
        </form>
    </div>

    <!-- Response Modal -->
    <div class="modal fade" id="responseModal" tabindex="-1" role="dialog" aria-labelledby="responseModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="responseModalLabel">Order Response</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <table class="table table-bordered">
                        <tbody>
                            <tr>
                                <th>Currency</th>
                                <td id="currency"></td>
                            </tr>
                            <tr>
                                <th>Total Fee</th>
                                <td id="totalFee"></td>
                            </tr>
                            <tr>
                                <th>Prepay ID</th>
                                <td id="prepayId"></td>
                            </tr>
                            <tr>
                                <th>Terminal Type</th>
                                <td id="terminalType"></td>
                            </tr>
                            <tr>
                                <th>Expire Time</th>
                                <td id="expireTime"></td>
                            </tr>
                            <tr>
                                <th>QR Code Link</th>
                                <td id="qrcodeLink"></td>
                            </tr>
                            <tr>
                                <th>QR Content</th>
                                <td id="qrContent"></td>
                            </tr>
                            <tr>
                                <th>Checkout URL</th>
                                <td id="checkoutUrl"></td>
                            </tr>
                            <tr>
                                <th>Deep Link</th>
                                <td id="deeplink"></td>
                            </tr>
                            <tr>
                                <th>Universal URL</th>
                                <td id="universalUrl"></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Add jQuery and Bootstrap JS from CDNs -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <!-- Your custom JavaScript -->
    <script>
        $(document).ready(function() {
            $('#orderForm').submit(function(e) {
                e.preventDefault();
                $.ajax({
                    type: 'POST',
                    url: 'order.php',
                    data: $('#orderForm').serialize(),
                    success: function(response) {
                        $('#currency').text(response.binanceResponse.currency);
                        $('#totalFee').text(response.binanceResponse.totalFee);
                        $('#prepayId').text(response.binanceResponse.prepayId);
                        $('#terminalType').text(response.binanceResponse.terminalType);
                        $('#expireTime').text(response.binanceResponse.expireTime);
                        $('#qrcodeLink').text(response.binanceResponse.qrcodeLink);
                        $('#qrContent').text(response.binanceResponse.qrContent);
                        $('#checkoutUrl').text(response.binanceResponse.checkoutUrl);
                        $('#deeplink').text(response.binanceResponse.deeplink);
                        $('#universalUrl').text(response.binanceResponse.universalUrl);
                        $('#responseModal').modal('show');
                    },
                    error: function(xhr, status, error) {
                        console.error('Request failed: ' + error);
                    }
                });
            });
        });
    </script>
</body>

</html>