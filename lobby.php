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

    if ($_SESSION['id'] == $player1['id']) {
        $conn->query("UPDATE servers SET turn = ".$player1['id']." WHERE id = '".$_GET["id"]."'");

        if (!$player2joined) $conn->query("UPDATE servers SET player2 = -1 WHERE id = '".$_GET["id"]."'");
        if (!$player3joined) $conn->query("UPDATE servers SET player3 = -1 WHERE id = '".$_GET["id"]."'");
        if (!$player4joined) $conn->query("UPDATE servers SET player4 = -1 WHERE id = '".$_GET["id"]."'");

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