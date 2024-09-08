// buy_stock.php
<?php
session_start();
// Create connection
require_once 'connect_db.php';

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $_SESSION['user_id'];
    $stock_symbol = $_POST['stock_symbol'];
    $stock_price = $_POST['stock_price'];
    $quantity = $_POST['quantity'];
    $total_price = $stock_price * $quantity;
    $transaction_date = date('Y-m-d H:i:s');
    
    // 將交易記錄插入到資料庫
    $stmt = $conn->prepare("INSERT INTO trading_history (user_id, stock_symbol, stock_price, quantity, total_price, transaction_date) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssss", $user_id, $stock_symbol, $stock_price, $quantity, $total_price, $transaction_date);
    
    if ($stmt->execute()) {
        $_SESSION['message'] = "Stock purchased successfully!";
    } else {
        $_SESSION['message'] = "Error: " . $stmt->error;
    }
    
    $conn->close();
    
    // Redirect back to the stock trading page
    header("Location: stock_trading.php");
    exit();
} else {
    // If accessed directly without POST data, redirect to the stock trading page
    header("Location: stock_trading.php");
    exit();
}
?>