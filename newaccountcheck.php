<?php
session_start();

$username = htmlspecialchars($_POST["username"]);
$password = htmlspecialchars($_POST["password"]);


if (strlen($username) < 3) {
    header("Location: newaccount.php?account=short");
    exit();}
if (strlen($username) > 10) {
    header("Location: newaccount.php?account=long");
    exit();}





$conn = new mysqli("localhost", "root", "", "zweeds pesten");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$usersWithUsername = $conn->query("SELECT * FROM users WHERE username = '$username'");

if ($usersWithUsername->num_rows > 0) {
    header("Location: newaccount.php?account=taken");
    exit();

}
else {
    $id = rand();
    $usersWithId = $conn->query("SELECT * FROM users WHERE id = '$id'");
    while ($usersWithId->num_rows > 0) {
        $id = rand();
        $usersWithId = $conn->query("SELECT * FROM users WHERE id = '$id'");
    }
    $conn->query("INSERT INTO users (id, username, password) VALUES ('$id', '$username', '$password')");
    $conn->query("INSERT INTO stats (id, user, wins, gamesplayed, elo) VALUES ('$id', '$id', 0, 0, 1000)");
    echo "account created";



    $_SESSION['loggedin'] = true;
    $_SESSION['id'] = $id;
    $_SESSION['username'] = $username;
    header("Location: home.php");
    exit();
}
?>
