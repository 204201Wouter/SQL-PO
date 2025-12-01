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

    $gameid = htmlspecialchars($_GET['id']);
    $playerid = htmlspecialchars($_SESSION['id']);

    $game = $conn->query("SELECT * FROM servers WHERE id = '$gameid'");

    // als game niet bestaat ga terug naar home
    if ($game->num_rows == 0) 
    {
        header("Location: home.php");
        exit();
    }
    
    // voeg jezelf toe aan database
    $sql = "SELECT COUNT(*) FROM players WHERE serverid = '$gameid'";
    $playersjoined = $conn->query($sql)->fetch_assoc()["COUNT(*)"];
    
    $sql = "INSERT INTO players (user, serverid, nummer, ready)
    VALUES ('".$_SESSION['id']."', '$gameid', $playersjoined, 0)";
    $conn->query($sql);
    
    header("Location: lobby.php?id=".$gameid);
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