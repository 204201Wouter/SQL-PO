<?php
session_start();
?>

<html>
<body>
<?php


if ($_SESSION["loggedin"] == true)
{
    $conn = new mysqli("localhost", "root", "", "zweeds pesten");

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $id = $_SESSION["id"];
    $username = $conn->query("SELECT * FROM users WHERE id = '$id'")->fetch_assoc()['username'];
    echo "welkom $username";
}
else {
    header("Location: inlog.php");
    exit();
}
?>
</body>
</html>