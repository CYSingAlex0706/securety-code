<?php
require_once 'session_control.php';
check_session_timeout();

require_once 'connect_db.php';
require_once 'encryption_handler.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_SESSION['user_id'];
    $hkid = $_POST["hkid"];

    $encryptHKID = encryptPassword($hkid);

    // First, check if a record already exists for this user
    $check_sql = $conn->prepare("SELECT * FROM sensitive_data WHERE user_id = ?");
    $check_sql->bind_param("s", $user_id);
    $check_sql->execute();
    $result = $check_sql->get_result();

    if ($result->num_rows > 0) {
        // Record exists, so update
        $update_sql = $conn->prepare("UPDATE sensitive_data SET HKID = ? WHERE user_id = ?");
        $update_sql->bind_param("ss", $encryptHKID, $user_id);
        
        if ($update_sql->execute()) {
            echo "<h2>HKID Updated Successfully!</h2>";
        } else {
            echo "<h2>Error updating HKID: " . $conn->error . "</h2>";
        }
        $update_sql->close();
    } else {
        // No record exists, so insert
        $insert_sql = $conn->prepare("INSERT INTO sensitive_data (user_id, HKID) VALUES (?, ?)");
        $insert_sql->bind_param("ss", $user_id, $encryptHKID);
        
        if ($insert_sql->execute()) {
            echo "<h2>HKID Added Successfully!</h2>";
        } else {
            echo "<h2>Error adding HKID: " . $conn->error . "</h2>";
        }
        $insert_sql->close();
    }

    echo "<p><a href='profile.php'>Go back to profile page</a></p>";

    $check_sql->close();
    $conn->close();
}
?>
