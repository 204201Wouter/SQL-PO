<?php
// (W3Schools, z.d.)
session_start();
?>
<html>
    <body>
<?php
if ($_SESSION["loggedin"]  == true)
{
    // verbind met database
    $conn = new mysqli("localhost", "root", "", "zweeds pesten");
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // check of je al in een server zit
    $sql = "SELECT serverid FROM players WHERE id = '".$_SESSION['id']."'";
    $result = $conn->query($sql);
    if ($result->num_rows == 0) {
        // maak server
        $sql = "INSERT INTO servers (id, started)
        VALUES ('".$_SESSION['username']."', 0)";
        $result = $conn->query($sql);

        header("Location: joinserver.php?id=".$_SESSION['username']);
        exit();
    }

    header("Location: lobby.php?id=".$result->fetch_assoc()['serverid']);
    exit();

    $conn->close();
}
else {
    header("Location: inlog.php");
    exit();
}
?>
</body>
</html>