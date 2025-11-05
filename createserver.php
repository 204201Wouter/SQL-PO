<?php

session_start();
?>
<html>
    <body>
<?php
if ($_SESSION["loggedin"]  == true)
{
    echo "welkom ". $_SESSION["id"];


    


    // Create connection
    $conn = new mysqli("localhost", "root", "", "zweeds pesten");
    // Check connection
    if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
    }



    $sql = "SELECT * FROM servers WHERE id = ".$_SESSION['id'];
    $result = $conn->query($sql);
    if ($result->num_rows < 1) {



    $sql = "INSERT INTO servers (id, player1)
    VALUES (".$_SESSION['id'].",". $_SESSION['id'].")";
    $result = $conn->query($sql);
    }

    echo "waiting for more players";




    $conn->close();
    

}
else {
      header("Location: inlog.php");
       exit();
}






?>
</body>
</html>