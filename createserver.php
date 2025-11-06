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


        $sql = "INSERT INTO servers (id, stapel)
        VALUES ('".$_SESSION['username']."','".json_encode($cards)."')";
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