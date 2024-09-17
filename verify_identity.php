<?php
require_once 'session_control.php';
check_session_timeout();

require_once 'encryption_handler.php';
require_once 'connect_db.php';

$user_id = $_SESSION['user_id'];
$error_message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $entered_hkid = $_POST['hkid'];
    
    // Fetch the encrypted HKID from the database
    $check_id_sql = $conn->prepare("SELECT HKID FROM sensitive_data WHERE user_id = ?");
    $check_id_sql->bind_param("s", $user_id);
    $check_id_sql->execute();
    $IDresult = $check_id_sql->get_result();
    $IDrow = $IDresult->fetch_assoc();

    if ($IDrow && isset($IDrow['HKID'])) {
        $stored_HKID = $IDrow['HKID'];
        $decrypted_hkid = decryptPassword($stored_HKID);
        
        if ($entered_hkid === $decrypted_hkid) {
            // HKID verified, redirect to trading history
            header("Location: trading_history.php");
            exit();
        } else {
            $error_message = "Invalid HKID. Please try again.";
        }
    } else {
        $error_message = "User data not found.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify Identity</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            margin: 0;
            padding: 20px;
        }
        .error {
            color: red;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <h2>Verify Identity</h2>
    
    <?php
    if (!empty($error_message)) {
        echo "<p class='error'>" . htmlspecialchars($error_message) . "</p>";
    }
    ?>
    
    <form action="verify_identity.php" method="post">
        <label for="hkid">Enter your HKID to verify:</label>
        <input type="text" id="hkid" name="hkid" required>
        <input type="submit" value="Verify">
    </form>
    
    <br>
    <a href="index.php">Back to Trading Page</a>
</body>
</html>