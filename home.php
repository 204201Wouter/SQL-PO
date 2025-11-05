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

    echo "welkom". $_SESSION['username']."<a href='createserver.php'>create server</a><br><a href='joinserver.php'>join server</a>";

    $conn->close();
}
else {
    header("Location: inlog.php");
    exit();
}
?>
<br>
<a href="createserver.php">create server</a>
</body>
</html>