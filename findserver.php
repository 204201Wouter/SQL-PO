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

    // leave currents servers user
    /*
     $sql = "DELETE FROM players WHERE gameid=(SELECT id FROM servers WHERE player1='".$_SESSION['id']."' OR player2='".$_SESSION['id']."')";
       $result = $conn->query($sql);
  
     $sql = "DELETE FROM servers WHERE player1='".$_SESSION['id']."' OR player2='".$_SESSION['id']."'";
       $result = $conn->query($sql);

    */
    



    $sql = "SELECT * FROM servers WHERE started = 0";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
            echo $row['id']."<a href="."joinserver.php?id=".$row['id'].">join</a><br>"
            ;
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
</body>
</html>