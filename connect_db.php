<?php

// Create connection
$conn = mysqli_connect("localhost", "test", "P@ssw0rd#123", "stock");

// Check connection
if ($conn->connect_error) 
{
    die("Connection failed: ". $conn->connect_error);
}
?>
