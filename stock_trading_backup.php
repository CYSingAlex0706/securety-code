<?php
session_start();


// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
$id = $_SESSION['user_id'];
$stock_symbol = "AAPL";
$stock_price = 210.00;
?>
<html>
<head>
    <title>Stock Trading</title>
</head>
<body>
    <div style="text-align: left;">
        <a href="profile.php">profile</a>
    </div>
    <div style="text-align: right;">
        <a href="trading_history.php">View Trading History</a>
    </div>
    
    <h2>Stock Trading</h2>
    
    <?php
    if (isset($_SESSION['message'])) {
        echo "<p>" . htmlspecialchars($_SESSION['message']) . "</p>";
        unset($_SESSION['message']);
    }
    ?>
    
    <h3>Current Stock:</h3>
    <p>
        Symbol: <?php echo $stock_symbol; ?><br>
        Price: $<?php echo number_format($stock_price, 2); ?>
    </p>
    
    <form action="buy_stock.php" method="post">
        <input type="hidden" name="stock_symbol" value="<?php echo $stock_symbol; ?>">
        <input type="hidden" name="stock_price" value="<?php echo $stock_price; ?>">
        <label for="quantity">Quantity:</label>
        <input type="number" id="quantity" name="quantity" min="1" value="1" required>
        <input type="submit" value="Buy">
    </form>
    
    <br>
</body>
</html>