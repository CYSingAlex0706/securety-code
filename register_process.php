<?php
// register_process.php

// 确保只接受POST请求
if ($_SERVER["REQUEST_METHOD"] != "POST") {
    header("Location: register.html"); // 重定向到注册页面
    exit();
}

require_once 'connect_db.php';

// 获取表单数据
$id = $_POST["id"];
$pwd = $_POST["pwd"];
$name = $_POST["name"];
$email = $_POST["email"];
// 检查用户名是否已存在
$search_sql = $conn->prepare("SELECT * FROM user WHERE id = ?");
$search_sql->bind_param("s", $id);
$search_sql->execute();
$search_sql->store_result();

// If login name can be found in table "user", forbid user register process
if ($search_sql->num_rows > 0) {
	echo "<h2>The user name is registered by others. Please use other user name</h2>";
} else {
	$Salt_Hash_Password = password_hash($pwd, PASSWORD_DEFAULT);

	$insert_sql = $conn->prepare("INSERT INTO user (id, pwd, name, email) VALUES (?, ?, ?, ?)");
	$insert_sql->bind_param("ssss", $id, $Salt_Hash_Password, $name, $email);
	$insert_sql->execute();
	echo "<h2>Registration Success!! Now back to login</h2>";
	echo "</p><a href='login.php'>Go back to login page</a></p>";
	echo "<h2>" . $Salt_Hash_Password . "</h2>";
}

$conn->close();