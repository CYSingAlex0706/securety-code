<?php
session_start();
require_once 'connect_db.php';
require_once 'encryption_handler.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!isset($_SESSION['user_id'])) {
        die("User not logged in");
    }

    $user_id = $_SESSION['user_id'];
    $hkid = $_POST["hkid"];

    $encryptPassword = encryptPassword($hkid);

    $insert_sql = $conn->prepare("INSERT INTO sensitive_data (user_id, HKID) VALUES (?, ?)");
    $insert_sql->bind_param("ss", $user_id, $encryptPassword);
    
    if ($insert_sql->execute()) {
        echo "<h2>Update HKID Successfully!</h2>";
        echo "<p><a href='profile.php'>Go back to profile page</a></p>";
    } else {
        echo "<h2>Error updating HKID: " . $conn->error . "</h2>";
    }

    $insert_sql->close();
    $conn->close();
}
?>