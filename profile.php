<?php
session_start();
?>

<html>
<body style="text-align:center; ">
<?php


if ($_SESSION["loggedin"] == true)
{
    $conn = new mysqli("localhost", "root", "", "zweeds pesten");

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $username = htmlspecialchars($_GET["username"]);

    // vaidate username

    $id = $conn->query("SELECT * FROM users WHERE username = '$username'")->fetch_assoc()["id"];
   // echo $id;

    $sql = "SELECT * FROM stats JOIN users ON stats.user = users.id WHERE users.username = '$username'";
    $result = $conn->query($sql)->fetch_assoc();

    echo "<h1>".$result['username']."</h1><br><br>";

    echo "elo: ".$result["elo"]."<br>";
    echo "wins: ".$result["wins"]."<br>";
    echo "losses: ".$result["gamesplayed"]+$result["wins"]."<br>";
    echo "games played: ".$result["gamesplayed"]."<br><br>";

    $sql = "SELECT * FROM gameslog WHERE player1 = $id OR player2 = $id OR player3 = $id OR player4 = $id ORDER BY id DESC";


    $result = $conn->query($sql);

    echo "<table border=1; style='margin: auto;'>";
    echo "<tr><td>Player 1</td><td>ELO</td><td>+/-</td><td>Player 2</td><td>ELO</td><td>+/-</td><td>Player 3</td><td>ELO</td><td>+/-</td><td>Player 4</td><td>ELO</td><td>+/-</td><td>Date</td></tr>";


    function player($player, $elo, $elodiff) {
        global $conn;
        $name = "bot";
        if ($player != -1) {
            $name = $conn->query("SELECT * FROM users where id = '$player'")->fetch_assoc()["username"];
        }

         echo "<td>".$name."</td><td>".$elo."</td><td>".$elodiff."</td>";


    }
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            player($row["player1"],$row["player1elo"],$row["player1elodiff"]);
            player($row["player2"],$row["player2elo"],$row["player2elodiff"]);
            player($row["player3"],$row["player3elo"],$row["player3elodiff"]);
            player($row["player4"],$row["player4elo"],$row["player4elodiff"]);
            echo "<td>".$row["date"]."</td>";
            echo "</tr>";
         

        }
    }
    echo "</table>";

    



    $conn->close();

}
else {
    header("Location: inlog.php");
    exit();
}
?>

<a href="home.php">home</a>

</body>
</html>