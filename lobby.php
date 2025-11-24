<?php

session_start();
?>
<meta http-equiv="refresh" content="2">
<html>
    <body>
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

    $player1joined = false;
    $player2joined = false;
    $player3joined = false;
    $player4joined = false;


    $sql = "SELECT Users.username FROM players JOIN users ON players.user = users.id WHERE serverid = '".$_GET["id"]."'";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
  
            echo $row['username']."<br> ";
            ;
        }
    }
        

    /*
    echo "waiting for more players<br>";
    $player1 = $conn->query("SELECT * FROM users WHERE id IN (SELECT player1 FROM servers WHERE id = '".$_GET["id"]."')")->fetch_assoc();
    if ($player1 <> null) {
        echo "player 1: " . $player1['username'];
        $player1joined = true;
    }
    else echo "player 1: not joined";
    echo "<br>";

    $player2 = $conn->query("SELECT * FROM users WHERE id IN (SELECT player2 FROM servers WHERE id = '".$_GET["id"]."')")->fetch_assoc();
    if ($player2 <> null) {
        echo "player 2: " . $player2['username'];
        $player2joined = true;
    }
    else echo "player 2: not joined";
    echo "<br>";

    $player3 = $conn->query("SELECT * FROM users WHERE id IN (SELECT player3 FROM servers WHERE id = '".$_GET["id"]."')")->fetch_assoc();
    if ($player3 <> null) {
        echo "player 3: " . $player3['username'];
        $player3joined = true;
    }
    else echo "player 3: not joined";
    echo "<br>";

    $player4 = $conn->query("SELECT * FROM users WHERE id IN (SELECT player4 FROM servers WHERE id = '".$_GET["id"]."')")->fetch_assoc();
    if ($player4 <> null) {
        echo "player 4: " . $player4['username'];
        $player4joined = true;
    }
    else echo "player 4: not joined";

    */

    if ($conn->query("SELECT started FROM servers WHERE id = '".$_GET["id"]."'")->fetch_assoc()['started']) {
        header("Location: game.php?id=".$_GET["id"]);
        exit();
    }
}
else {
    header("Location: inlog.php");
    exit();
}

function startServer() {
    global $conn;
    global $player1;
    global $player2joined;
    global $player3joined;
    global $player4joined;

    if ($_SESSION['username'] == $_GET['id']) {
      //  $conn->query("UPDATE servers SET turn = ".$_SESSION['username'] ." WHERE id = '".$_SESSION['username'] ."'");

        $hands = [[],[],[],[]];
        $kaartenvooropen = [[],[],[],[]];
        $kaartenvoorgesloten = [[],[],[],[]];

        $cards = [];
        for ($i = 0; $i < 54;$i++)
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
        
        
        $sql = "SELECT * FROM players WHERE serverid = '".$_GET["id"]."'";
        $result = $conn->query($sql);

        

        $i = 0;
        for ($j = $result->num_rows; $j < 4; $j++) {
            $k = -$j;
            $sql = $conn->query("INSERT INTO players (id, user, serverid, nummer)
            VALUES ($k, -1, '".$_GET['id']."', $j )");
        }


        $sql = "SELECT * FROM players WHERE serverid = '".$_GET["id"]."'";
        $result = $conn->query($sql);

        
    
        while ($row = $result->fetch_assoc()) {
            $stmt->bind_param('sssi', json_encode($hands[$i]), json_encode($kaartenvooropen[$i]), json_encode($kaartenvoorgesloten[$i]), $row['id']);
            $stmt->execute();

            $i++;
        }
    

        $turn = 0;


        $sql = "INSERT INTO games (id, turn, stapel, pakstapel)
        VALUES ('".$_GET['id']."',".$turn.",'[]','".json_encode($cards)."')";
        $result = $conn->query($sql);

        $conn->query("UPDATE servers SET started = 1 WHERE id = '".$_GET["id"]."'");

        header("Location: game.php?id=".$_GET["id"]);
        exit();
    }
    else echo "<br>You can't start the server if you are not the host.";
}

if (isset($_POST['startServer'])) {
    startServer();
}
?>
</body>
</html>