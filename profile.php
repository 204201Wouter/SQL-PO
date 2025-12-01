<?php
session_start();
?>

<html>
<body style="text-align:center;">
<?php
if ($_SESSION["loggedin"] == true)
{
    // verbind met database
    $conn = new mysqli("localhost", "root", "", "zweeds pesten");
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $username = htmlspecialchars($_GET["username"]);

    // laat alles zien van ingevulde username
    $id = $conn->query("SELECT * FROM users WHERE username = '$username'")->fetch_assoc()["id"];

    $sql = "SELECT * FROM stats JOIN users ON stats.user = users.id WHERE users.username = '$username'";
    $result = $conn->query($sql)->fetch_assoc();

    echo "<h1>".$result['username']."</h1><br><br>";

    echo "elo: ".$result["elo"]."<br>";
    echo "wins: ".$result["wins"]."<br>";
    echo "losses: ".$result["gamesplayed"]+$result["wins"]."<br>";
    echo "games played: ".$result["gamesplayed"]."<br><br>";

    // laat alle gespeelde games van deze speler zien in een tabel
    $sql = "SELECT gameid FROM playerlog WHERE playerid = $id";
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
            $players = $conn->query("SELECT * FROM playerlog WHERE gameid = " . $row['gameid']);
            $bots = 4;
            while ($player = $players->fetch_assoc()) {
                player($player["playerid"], $player["elo"], $player["elodiff"]);
                $bots--;
            }

            // laat alle bots in spel ook zien
            for ($i = 0; $i < $bots; $i++) {
                player(-1, 1000, 0);
            }

            $date = $conn->query("SELECT date FROM gameslog WHERE id = " . $row['gameid'])->fetch_assoc()['date'];
            echo "<td>$date</td>";
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