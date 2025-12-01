<?php
session_start();
?>
<meta http-equiv="refresh" content="2">
<html>
    <body  style="text-align:center;">
        <form method="post">
            <button type="submit" name="startServer">Start server</button>
        </form>
<?php
if ($_SESSION["loggedin"]  == true)
{
    // Create connection
    $conn = new mysqli("localhost", "root", "", "zweeds pesten");
    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $id = htmlspecialchars($_GET["id"]);

    $sql = "SELECT Users.username FROM players JOIN users ON players.user = users.id WHERE serverid = '".$id."'";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            if ($row['username'] != "bot") echo $row['username']."<br>";
        }
    }

    if ($conn->query("SELECT started FROM servers WHERE id = '".$id."'")->fetch_assoc()['started']) {
        header("Location: game.php?id=".$id);
        exit();
    }
}
else {
    header("Location: inlog.php");
    exit();
}

function startServer() {
    global $conn;
    global $id;

    if ($_SESSION['username'] == $id) {
        $hands = [[],[],[],[]];
        $kaartenvooropen = [[],[],[],[]];
        $kaartenvoorgesloten = [[],[],[],[]];

        $cards = [];
        for ($i = 0; $i < 36;$i++)
        {
            $cards[] = $i;
        }

        shuffle($cards);

        for ($i = 0; $i < 3; $i++) {
            for ($j = 0; $j < 4; $j++) {
                $hands[$j][] = array_shift($cards);
                $kaartenvooropen[$j][] = array_shift($cards);
                $kaartenvoorgesloten[$j][] = array_shift($cards);
            }
        }

        $stmt = $conn->prepare("UPDATE players SET hand = ?, kaartenvooropen = ?, kaartenvoorgesloten = ? WHERE id = ?");
        
        
        $sql = "SELECT * FROM players WHERE serverid = '".$id."'";
        $result = $conn->query($sql);
        $numrows = $result->num_rows;

        for ($i = 0; $i < $numrows; $i++) {
            $row = $result->fetch_assoc();
            if ($row['nummer'] != $i) $conn->query("UPDATE players SET nummer = $i WHERE serverid = '".$id."' AND id = '".$row['id']."'");
        }

        $i = 0;
        for ($j = $numrows; $j < 4; $j++) {
            $k = -$j;
            $conn->query("INSERT INTO players (id, user, serverid, nummer, ready)
            VALUES ($k, -1, '".$id."', $j, 1)");
        }


        $sql = "SELECT * FROM players WHERE serverid = '".$id."'";
        $result = $conn->query($sql);

        
        while ($row = $result->fetch_assoc()) {
            $stmt->bind_param('sssi', json_encode($hands[$i]), json_encode($kaartenvooropen[$i]), json_encode($kaartenvoorgesloten[$i]), $row['id']);
            $stmt->execute();

            $i++;
        }
    

        $turn = 0;


        $sql = "INSERT INTO games (id, turn, stapel, pakstapel)
        VALUES ('".$id."',".$turn.",'[]','".json_encode($cards)."')";
        $result = $conn->query($sql);

        $conn->query("UPDATE servers SET started = 1 WHERE id = '".$id."'");

        header("Location: game.php?id=".$id);
        exit();
    }
    else echo "<br>You can't start the server if you are not the host.";
}

if (isset($_POST['startServer'])) {
    startServer();
}
?>
<a href="home.php">home</a>
</body>
</html>