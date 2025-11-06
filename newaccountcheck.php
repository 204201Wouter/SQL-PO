<?php
session_start();

$username = htmlspecialchars($_POST["username"]);
$password = htmlspecialchars($_POST["password"]);

$conn = new mysqli("localhost", "root", "", "zweeds pesten");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$usersWithUsername = $conn->query("SELECT * FROM users WHERE username = '$username'");

if ($usersWithUsername->num_rows > 0) {
    echo "username already taken";
}
else {
    $id = rand();
    $usersWithId = $conn->query("SELECT * FROM users WHERE id = '$id'");
    while ($usersWithId->num_rows > 0) {
        $id = rand();
        $usersWithId = $conn->query("SELECT * FROM users WHERE id = '$id'");
    }
    $conn->query("INSERT INTO users (id, username, password, admin) VALUES ('$id', '$username', '$password', 0)");
    echo "account created";

    $_SESSION['loggedin'] = true;
    $_SESSION['id'] = $id;
    $_SESSION['username'] = $username;
    header("Location: home.php");
    exit();
}
?>
