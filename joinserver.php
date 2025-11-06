<?php

session_start();
?>
<html>
    <body>
<?php
if ($_SESSION["loggedin"]  == true)
{
 
    


    // Create connection
    $conn = new mysqli("localhost", "root", "", "zweeds pesten");
    // Check connection
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
        

    $sql = "UPDATE players SET id =". $_SESSION['id']." WHERE gameid = '".$_GET['id']."' AND id = 0 LIMIT 1";

    $result = $conn->query($sql);
    

    $player1 = $conn->query("SELECT * FROM users WHERE id IN (SELECT player1 FROM servers WHERE id = '".$_GET["id"]."')")->fetch_assoc();
    if ($player1 == null) {

        $sql = "UPDATE servers SET player1 = '". $_SESSION['id']."' WHERE id = '".$_GET['id']."'";

        $result = $conn->query($sql);
  
        header("Location: lobby.php?id=".$_GET['id']);
        exit();

    }
    else {

        $player2 = $conn->query("SELECT * FROM users WHERE id IN (SELECT player2 FROM servers WHERE id = '".$_GET["id"]."')")->fetch_assoc();
        if ($player2 == null) {

            $sql = "UPDATE servers SET player2 = '". $_SESSION['id']."' WHERE id = '".$_GET['id']."'";
           
            $result = $conn->query($sql);

        
            header("Location: lobby.php?id=".$_GET['id']);
            exit();

        }
        
    }




    




    
    $conn->close();
    






}
else {
    header("Location: inlog.php");
    exit();
}


?>
</body>
</html>