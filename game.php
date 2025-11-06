<form method="post">
  <input type="number" name="move" placeholder="move (invullen doet nog niks)">
  <button type="submit" name="playMove">Play Move</button>
</form>
<meta http-equiv="refresh" content="2">

<?php 
session_start();

$conn = new mysqli("localhost", "root", "", "zweeds pesten");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$gameid = $_GET['id'];
$playerid = $_SESSION['id'];

echo "dit is een prachtige game";
$game = $conn->query("SELECT * FROM servers WHERE id = '$gameid'")->fetch_assoc();
$turn = $game['turn'];
$turnName = $conn->query("SELECT username FROM users WHERE id = '$turn'")->fetch_assoc()['username'];
echo "<br>$turnName is aan de beurt";


function playmove(int $move) {
    global $conn;
    global $game;
    global $turn;
    global $gameid;
    global $playerid;

    if ($game['player1'] == $playerid) $nextplayerid = $game['player2'];
    else $nextplayerid = $game['player1'];

    if ($playerid == $turn) {
        // move spul
        $conn->query("UPDATE servers SET turn = ".$nextplayerid." WHERE id = '$gameid'");
        header("Refresh:0");
    }
}

if (isset($_POST['playMove'])) {
    $value = intval($_POST['move']);
    playmove($value);
}
?>