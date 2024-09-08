<?php
session_start();
?>
<html>
<head>
    <title>Login</title>
</head>
<body>
<?php
// Create connection
require_once 'connect_db.php';

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$error_message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Login process
    $id = $_POST["id"];
    $pwd = $_POST["pwd"];

    // Check if account is locked
    $check_lock_sql = $conn->prepare("SELECT account_locked, pwd, name FROM user WHERE id = ?");
    $check_lock_sql->bind_param("s", $id);
    $check_lock_sql->execute();
    $lock_result = $check_lock_sql->get_result();

    if ($lock_result->num_rows > 0) {
        $user_data = $lock_result->fetch_assoc();
        if ($user_data['account_locked']) {
            $error_message = "This account has been locked. Please contact support.";
        } else {

            if (password_verify($pwd, $user_data['pwd'])) {
                // Successful login
                $_SESSION['user_id'] = $id;
                
                // Reset login attempts
                $reset_attempts_sql = $conn->prepare("UPDATE user SET login_attempts = 0 WHERE id = ?");
                $reset_attempts_sql->bind_param("s", $id);
                $reset_attempts_sql->execute();

                header("Location: profile.php");
                exit();
            } else {
                // Failed login
                $update_attempts_sql = $conn->prepare("UPDATE user SET login_attempts = login_attempts + 1 WHERE id = ?");
                $update_attempts_sql->bind_param("s", $id);
                $update_attempts_sql->execute();

                // Check if attempts exceed limit
                $check_attempts_sql = $conn->prepare("SELECT login_attempts FROM user WHERE id = ?");
                $check_attempts_sql->bind_param("s", $id);
                $check_attempts_sql->execute();
                $attempts_result = $check_attempts_sql->get_result();
                $attempts = $attempts_result->fetch_assoc();

                if ($attempts['login_attempts'] >= 3) {
                    // Lock the account
                    $lock_account_sql = $conn->prepare("UPDATE user SET account_locked = TRUE WHERE id = ?");
                    $lock_account_sql->bind_param("s", $id);
                    $lock_account_sql->execute();
                    $error_message = "Too many failed attempts. Your account has been locked.";
                } else {
                    $error_message = "Incorrect login credentials. Please try again. Attempts left: " . (3 - $attempts['login_attempts']);
                }
            }
        }
    } else {
        $error_message = "User not found.";
    }
}
?>

<h2>Login</h2>
<?php
if (!empty($error_message)) {
    echo "<p style='color: red;'>" . htmlspecialchars($error_message) . "</p>";
}
?>
<form method="post" action="">
    <label for="id">ID:</label>
    <input type="text" id="id" name="id" required><br><br>
    <label for="pwd">Password:</label>
    <input type="password" id="pwd" name="pwd" required><br><br>
    <input type="submit" value="Login">
</form>
<a href="register_form.php">Register</a>

</body>
</html>