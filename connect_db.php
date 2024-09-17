<?php

// Include config file
$config = require 'config.php';

// Create connection
$conn = mysqli_connect($config['DB_SERVER'], $config['DB_USERNAME'], $config['DB_PASSWORD'], $config['DB_NAME']);

// Check connection
if ($conn->connect_error) 
{
    die("Connection failed: ". $conn->connect_error);
}
?>
