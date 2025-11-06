<?php

session_start();
?>
<meta http-equiv="refresh" content="2">
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







    echo "waiting for more players<br>";
    $player1 = $conn->query("SELECT * FROM users WHERE id IN (SELECT player1 FROM servers WHERE id = '".$_GET["id"]."')")->fetch_assoc();
    if ($player1 <> null) {
        echo "player 1: " . $player1['username'];
    }
    else echo "player 1: not joined";
    echo "<br>";

    $player2 = $conn->query("SELECT * FROM users WHERE id IN (SELECT player2 FROM servers WHERE id = '".$_GET["id"]."')")->fetch_assoc();
    if ($player2 <> null) {
        echo "player 2: " . $player2['username'];
    }
    else echo "player 2: not joined";






    

}
else {
      header("Location: inlog.php");
       exit();
}






?>
</body>
</html>