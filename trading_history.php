<?php
require_once 'session_control.php';
check_session_timeout();

// Database connection
require_once 'connect_db.php';

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$user_id = $_SESSION['user_id'];
echo "<a href='profile.php'>" . $user_id . "</a>";
// Prepare and execute the SQL statement
$stmt = $conn->prepare("SELECT * FROM trading_history WHERE user_id = ? ORDER BY transaction_date DESC");
$stmt->bind_param("s", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<html>
<head>
    <title>Trading History</title>
</head>
<body>
    <h2>Trading History</h2>
    
    <?php
    if ($result->num_rows > 0) {
        echo "<table border='1'>";
        echo "<tr><th>Date</th><th>Symbol</th><th>Price</th><th>Quantity</th><th>Total</th></tr>";
        
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($row['transaction_date']) . "</td>";
            echo "<td>" . htmlspecialchars($row['stock_symbol']) . "</td>";
            echo "<td>$" . number_format($row['stock_price'], 2) . "</td>";
            echo "<td>" . htmlspecialchars($row['quantity']) . "</td>";
            echo "<td>$" . number_format($row['total_price'], 2) . "</td>";
            echo "</tr>";
        }
        
        echo "</table>";
    } else {
        echo "<p>No trading history available.</p>";
    }
    
    $stmt->close();
    $conn->close();
    ?>
    
    <br>
    <a href="stock_trading.php">Back to Stock Trading</a>
    <br>
    <a href="logout.php">Logout</a>
</body>
</html>