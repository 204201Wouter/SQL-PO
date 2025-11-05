<?php
session_start();

$username = htmlspecialchars($_POST["username"]);
$password = htmlspecialchars($_POST["password"]);

$conn = new mysqli("localhost", "root", "", "zweeds pesten");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$result = $conn->query("SELECT * FROM users WHERE username = '$username'");

if ($result->num_rows > 0) {
    echo "username already taken";
}
else {
    $conn->query("INSERT INTO users (id, username, password, admin) VALUES (101, $username, $password, false)");
}
?>
