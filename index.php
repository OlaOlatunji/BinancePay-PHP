<!DOCTYPE html>
<html>

<head>
    <title>Create Binance Pay Order</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>

<body>
    <div class="container">
        <h1 class="mt-5">Create Binance Pay Order</h1>
        <form id="orderForm" class="mt-3">
            <div class="form-group">
                <label for="orderAmount">Order Amount:</label>
                <input type="text" class="form-control" id="orderAmount" name="orderAmount" required>
            </div>
            <button type="submit" class="btn btn-primary">Create Order</button>
        </form>
        <div id="responseContainer" class="mt-4"></div>
    </div>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#orderForm').submit(function(event) {
                event.preventDefault();
                $.ajax({
                    type: 'POST',
                    url: 'createorder.php',
                    data: $('#orderForm').serialize(),
                    success: function(response) {
                        $('#responseContainer').html(response);
                    },
                    error: function(xhr, status, error) {
                        $('#responseContainer').html('Error: ' + error);
                    }
                });
            });
        });
    </script>
</body>

</html>