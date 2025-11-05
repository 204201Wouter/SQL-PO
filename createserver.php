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



    $sql = "SELECT * FROM servers WHERE id = "."'".$_SESSION['username']."'";
    $result = $conn->query($sql);
    if ($result->num_rows == 0) {



        $sql = "INSERT INTO servers (id, player1)
        VALUES ("."'".$_SESSION['username']."'".",". $_SESSION['id'].")";
        $result = $conn->query($sql);
    }

    echo "waiting for more players<br>";
    $player1 = $conn->query("SELECT * FROM users WHERE id IN (SELECT player1 FROM servers WHERE id = '$username')")->fetch_assoc();
    echo "player 1: " . $player1['username'];
    echo "<br>";

    $player2 = $conn->query("SELECT * FROM users WHERE id IN (SELECT player2 FROM servers WHERE id = '$username')")->fetch_assoc();
    if ($player2 <> null) {
        echo "player 2: " . $player2['username'];
    }
    else echo "player 2: not joined";



    $conn->close();
    
    header("Location: lobby.php?id=".$_SESSION['username']);
    exit();


}
else {
    header("Location: inlog.php");
    exit();
}
?>
</body>
</html>