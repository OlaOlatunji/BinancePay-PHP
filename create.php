<!DOCTYPE html>
<html>

<head>
    <title>Order Form</title>
    <!-- Add Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
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
                <label for="goodsCategory">Goods Category:</label>
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

        <!-- Result section (initially hidden) -->
        <div id="resultSection" style="display: none;">
            <h2>Order Result</h2>
            <p><strong>Currency:</strong> <span id="resultCurrency"></span></p>
            <p><strong>Total Fee:</strong> <span id="resultTotalFee"></span></p>
            <!-- Add more result fields as needed -->
        </div>
    </div>

    <script>
        $(document).ready(function() {
            // Listen for form submission
            $("#orderForm").submit(function(event) {
                event.preventDefault(); // Prevent the default form submission

                // Get form data
                var formData = {
                    orderAmount: $("#orderAmount").val(),
                    goodsType: $("#goodsType").val(),
                    goodsCategory: $("#goodsCategory").val(),
                    referenceGoodsId: $("#referenceGoodsId").val(),
                    goodsName: $("#goodsName").val(),
                    goodsDetail: $("#goodsDetail").val()
                };

                // Send an AJAX POST request to order.php
                $.ajax({
                    type: "POST",
                    url: "order.php",
                    data: JSON.stringify(formData),
                    contentType: "application/json",
                    success: function(response) {
                        // Parse the JSON response
                        var responseData = JSON.parse(response);

                        if (responseData.status === "SUCCESS") {
                            // Display the result section and hide the form
                            $("#resultSection").show();
                            $("#orderForm").hide();

                            // Update the result section with the response data
                            $("#resultCurrency").text(responseData.data.currency);
                            $("#resultTotalFee").text(responseData.data.totalFee);
                            // Add more result fields as needed
                        } else {
                            // Handle error responses
                            alert("Error: " + responseData.message);
                        }
                    },
                    error: function(xhr, status, error) {
                        // Handle errors (e.g., show an error message)
                        console.error("Error: " + status + " - " + error);
                    }
                });
            });
        });
    </script>

</body>

</html>
