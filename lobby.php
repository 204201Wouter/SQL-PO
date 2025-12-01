<?php
session_start();
?>
<meta http-equiv="refresh" content="2">
<html>
    <body  style="text-align:center;">
        <form method="post">
            <button type="submit" name="startServer">Start server</button>
        </form>
        <form method="post">
            <button type="submit" name="leaveServer">Leave server</button>
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

    $gameid = htmlspecialchars($_GET["id"]);

    $result = $conn->query("SELECT * FROM servers WHERE id = '$gameid'");
    if ($result->num_rows == 0) {
        header("Location: home.php");
        exit();
    }

    $sql = "SELECT Users.username FROM players JOIN users ON players.user = users.id WHERE serverid = '".$gameid."'";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            if ($row['username'] != "bot") echo $row['username']."<br>";
        }
    }

    if ($conn->query("SELECT started FROM servers WHERE id = '$gameid'")->fetch_assoc()['started']) {
        header("Location: game.php?id=".$gameid);
        exit();
    }
}
else {
    header("Location: inlog.php");
    exit();
}

function startServer() {
    global $conn;
    global $gameid;

    if ($_SESSION['username'] == $gameid) {
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
        
        
        $sql = "SELECT * FROM players WHERE serverid = '".$gameid."'";
        $result = $conn->query($sql);
        $numrows = $result->num_rows;

        for ($i = 0; $i < $numrows; $i++) {
            $row = $result->fetch_assoc();
            if ($row['nummer'] != $i) $conn->query("UPDATE players SET nummer = $i, ready = 0 WHERE serverid = '".$gameid."' AND id = '".$row['id']."'");
        }

        $i = 0;
        for ($j = $numrows; $j < 4; $j++) {
            $k = -$j;
            $conn->query("INSERT INTO players (user, serverid, nummer, ready)
            VALUES (-1, '".$gameid."', $j, 1)");
        }


        $sql = "SELECT * FROM players WHERE serverid = '".$gameid."'";
        $result = $conn->query($sql);

        
        while ($row = $result->fetch_assoc()) {
            $stmt->bind_param('sssi', json_encode($hands[$i]), json_encode($kaartenvooropen[$i]), json_encode($kaartenvoorgesloten[$i]), $row['id']);
            $stmt->execute();

            $i++;
        }

        $turn = 0;


        $sql = "INSERT INTO games (id, turn, stapel, pakstapel)
        VALUES ('".$gameid."',".$turn.",'[]','".json_encode($cards)."')";
        $result = $conn->query($sql);

        $conn->query("UPDATE servers SET started = 1 WHERE id = '$gameid'");

        header("Location: game.php?id=".$gameid);
        exit();
    }
    else echo "<br>You can't start the server if you are not the host.";
}

function leaveServer() {
    global $conn;
    global $gameid;

    if ($_SESSION['username'] == $gameid) {
        $conn->query("DELETE FROM players WHERE serverid = '$gameid'");
        $conn->query("DELETE FROM servers WHERE id = '$gameid'");
    }
    else {
        $conn->query("DELETE FROM players WHERE id = ".$_SESSION['id']);
    }

    header("Location: home.php?id=".$gameid);
    exit();
}

if (isset($_POST['startServer'])) {
    startServer();
}

if (isset($_POST['leaveServer'])) {
    leaveServer();
}
?>
<br>
<a href="home.php">home</a>
</body>
</html>