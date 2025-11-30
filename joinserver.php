<?php
session_start();
?>
<html>
    <body>
<?php
if ($_SESSION["loggedin"]  == true)
{
    $conn = new mysqli("localhost", "root", "", "zweeds pesten");
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $gameid = $_GET['id'];
    $playerid = $_SESSION['id'];

    $game = $conn->query("SELECT * FROM servers WHERE id = '$gameid'");

    if ($game->num_rows == 0) 
    {
        header("Location: home.php");
        exit();
    }
    
    $sql = "SELECT COUNT(*) FROM players WHERE serverid = '$gameid'";
    $playersjoined = $conn->query($sql)->fetch_assoc()["COUNT(*)"];
    
    $sql = "INSERT INTO players (id, user, serverid, nummer, ready)
    VALUES ('".$_SESSION['id']."', '".$_SESSION['id']."', '$gameid', $playersjoined, 0)";
    $result = $conn->query($sql);
    header("Location: lobby.php?id=".$_GET['id']);
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