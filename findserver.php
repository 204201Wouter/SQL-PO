<?php
// (W3Schools, z.d.)
session_start();
?>
<html>
    <body  style="text-align:center;">
<?php
if ($_SESSION["loggedin"]  == true)
{
    // verbind met database
    $conn = new mysqli("localhost", "root", "", "zweeds pesten");
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // check of je al in server zit
    $sql = "SELECT * FROM servers WHERE id = '".$_SESSION['username']."'";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        header("Location: lobby.php?id=".$_SESSION['username']);
        exit();
    }

    // laat alle nog niet gestartte servers zien
    $sql = "SELECT * FROM servers WHERE started = 0";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo $row['id']." <a href="."joinserver.php?id=".$row['id'].">join</a><br>";
        }
    }
    else {
        echo "no servers found :(";
    }

    $conn->close();
}
else {
    header("Location: inlog.php");
    exit();
}
?>

<a href="home.php">home</a>
</body>
</html>