<?php
session_start();

$conn = new mysqli("localhost", "root", "", "zweeds pesten");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$result = $conn->query("SELECT * FROM users WHERE username = 'testaccount'");
$row = $result->fetch_assoc();
echo $row['password'];
?>