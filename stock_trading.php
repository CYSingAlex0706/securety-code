<?php

require_once 'session_control.php';
check_session_timeout();

require_once 'polygon_api.php';

$user_id = $_SESSION['user_id'];
echo "<a href='profile.php'>" . $user_id . "</a>";
// You can make this dynamic based on user input or database
$stock_symbol = "AAPL";

// Fetch the stock price
$API_data = getStockData($stock_symbol);
$stock_price = $API_data['open'];

if ($stock_price === null) {
    // Handle error - set a default price and an error message
    $stock_price = 0.00;
    $error_message = "Unable to fetch current stock price. Please try again later.";
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stock Trading</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            margin: 0;
            padding: 20px;
        }
        .header {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }
        .error {
            color: red;
            font-weight: bold;
        }
        form {
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="header">

        <div>
            <a href="trading_history.php">View Trading History</a>
        </div>
    </div>
    
    <h2>Stock Trading</h2>
    
    <?php
    if (isset($_SESSION['message'])) {
        echo "<p>" . htmlspecialchars($_SESSION['message']) . "</p>";
        unset($_SESSION['message']);
    }

    if (isset($error_message)) {
        echo "<p class='error'>" . htmlspecialchars($error_message) . "</p>";
    }
    ?>
    
    <h3>Current Stock:</h3>
    <p>
        Symbol: <?php echo htmlspecialchars($stock_symbol); ?><br>
        Price: $<?php echo $stock_price; ?>
    </p>
    
    <form action="buy_stock.php" method="post">
        <input type="hidden" name="stock_symbol" value="<?php echo htmlspecialchars($stock_symbol); ?>">
        <input type="hidden" name="stock_price" value="<?php echo $stock_price; ?>">
        <label for="quantity">Quantity:</label>
        <input type="number" id="quantity" name="quantity" min="1" value="1" required>
        <input type="submit" value="Buy">
    </form>
    
    <br>
    <a href="logout.php">Logout</a>

</body>
</html>