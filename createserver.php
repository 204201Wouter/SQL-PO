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


        $cards = [];
        for ($i = 0; $i<54;$i++)
        {
        $cards[] = $i;
        }

        shuffle($cards);

        for ($i = 0; $i < 3; $i++) {
            $player1hand[] = array_shift($cards);
            $player2hand[] = array_shift($cards);
        }



        $sql = "INSERT INTO servers (id, stapel, pakstapel)
        VALUES ('".$_SESSION['username']."', '".json_encode([])."', '".json_encode($cards)."')";
        $result = $conn->query($sql);

        

        $sql = "INSERT INTO players (hand, gameid)
        VALUES ('".json_encode($player1hand)."','".$_SESSION['username']."')";
        $result = $conn->query($sql);
        $sql = "INSERT INTO players (hand, gameid)
        VALUES ('".json_encode($player2hand)."','".$_SESSION['username']."')";
        $result = $conn->query($sql);


        header("Location: joinserver.php?id=".$_SESSION['username']);
        exit();


    }

    header("Location: lobby.php?id=".$_SESSION['username']);
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