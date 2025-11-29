<?php
session_start();
?>

<html>
<body>
<?php


if ($_SESSION["loggedin"] == true)
{
    $conn = new mysqli("localhost", "root", "", "zweeds pesten");

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $username = $_GET["username"];

    // vaidate username

    $id = $conn->query("SELECT * FROM users WHERE username = '$username'")->fetch_assoc()["id"];
   // echo $id;

    $sql = "SELECT * FROM stats JOIN users ON stats.user = users.id WHERE users.username = '$username'";
    $result = $conn->query($sql)->fetch_assoc();

    echo $result["elo"];

    $sql = "SELECT * FROM gameslog WHERE player1 = $id OR player2 = $id OR player3 = $id OR player4 = $id ORDER BY id";


    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<br>".$row["winner"]." | ".$row["player1"]." | ".$row["player2"]." | ".$row["player3"]." | ".$row["player4"];
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