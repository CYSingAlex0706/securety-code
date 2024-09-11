<?php
require_once 'session_control.php';
check_session_timeout();
require_once 'connect_db.php';
require_once 'encryption_handler.php';


if ($conn->connect_error) {
    die("连接失败: " . $conn->connect_error);
}

// 从数据库获取用户的存款金额
$user_id = $_SESSION['user_id'];
echo "<p>" . $user_id . "</p>";
$check_deposits_sql = $conn->prepare("SELECT Deposits, name FROM user WHERE id = ?");
$check_deposits_sql->bind_param("s", $user_id);
$check_deposits_sql->execute();
$result = $check_deposits_sql->get_result();
$row = $result->fetch_assoc();
$deposits = $row['Deposits'];

$check_ID_sql = $conn->prepare("SELECT HKID FROM sensitive_data WHERE user_id = ?");
if ($check_ID_sql === false) {
    die("準備查詢失敗（敏感數據）: " . $conn->error);
}
$check_ID_sql->bind_param("s", $user_id);
$check_ID_sql->execute();
$IDresult = $check_ID_sql->get_result();
$IDrow = $IDresult->fetch_assoc();

if ($IDrow && isset($IDrow['HKID'])) {
    $HKID = decryptPassword($IDrow['HKID']);
} else {
    $HKID = "No HKID found";
}

$conn->close();

?>
<html>
<head>
    <title>User Profile</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <style>
        .money-container {
            display: flex;
            align-items: center;
        }
        .eye-icon {
            margin-left: 10px;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <h2>Welcome, <?php echo htmlspecialchars($row['name']); ?>!</h2><a href="edit_profile.php">Edit profile</a>
 
    <div class="money-container">
        <h2>Your Deposits: <span id="moneyAmount">$<?php echo htmlspecialchars(number_format($deposits, 2)); ?></span></h2>
        <i id="eyeIcon" class="fas fa-eye eye-icon" onclick="toggleMoneyVisibility()"></i>
    </div>
        <h2>Your HKID: <?php echo $HKID; ?></span></h2>
    <a href="stock_trading.php">Go to Stock Trading</a>
    <br><br>
    <a href="logout.php">Logout</a>

    <script>
        let originalAmount = '$<?php echo htmlspecialchars(number_format($deposits, 2)); ?>';
        function toggleMoneyVisibility() {
            const moneyAmount = document.getElementById('moneyAmount');
            const eyeIcon = document.getElementById('eyeIcon');
            
            if (moneyAmount.textContent === '*****') {
                moneyAmount.textContent = originalAmount;
                eyeIcon.className = 'fas fa-eye eye-icon';
            } else {
                moneyAmount.textContent = '*****';
                eyeIcon.className = 'fas fa-eye-slash eye-icon';
            }
        }
    </script>
</body>
</html>