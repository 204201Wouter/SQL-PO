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

    $username = $_GET["username"];
    

    $sql = "SELECT * FROM stats JOIN users ON stats.user = users.id WHERE users.username = '$username'";
    $result = $conn->query($sql)->fetch_assoc();

    print_r($result);

    $sql = "SELECT * FROM gameslog";
    $result = $conn->query($sql)->fetch_assoc();

    print_r($result);



    $conn->close();

}
else {
    header("Location: inlog.php");
    exit();
}
?>

</body>
</html>