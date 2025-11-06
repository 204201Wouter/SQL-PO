<form method="post">
  <input type="number" name="move" placeholder="move (invullen doet nog niks)">
  <button type="submit" name="playMove">Play Move</button>
</form>


<?php 
session_start();

if ($_SESSION["loggedin"]  == true)
{


 
    


$conn = new mysqli("localhost", "root", "", "zweeds pesten");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}



$gameid = $_GET['id'];
$playerid = $_SESSION['id'];

$game = $conn->query("SELECT * FROM servers WHERE id = '$gameid'");

if ($game->num_rows == 0) 
{
  
    header("Location: home.php");
    exit();
}
$game = $game->fetch_assoc();


$kaarten = $conn->query("SELECT hand FROM players WHERE id = '".$_SESSION['id']."'")->fetch_assoc()['hand'];

echo "jouw kaarten: <br>".$kaarten;




$turn = $game['turn'];
$turnName = $conn->query("SELECT username FROM users WHERE id = '$turn'")->fetch_assoc()['username'];
if ($turnName == $_SESSION['username'])
    echo "<br>Jij bent aan de beurt";
else
echo "<br>$turnName is aan de beurt<meta http-equiv='refresh' content='2'>";


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
}
else {
    header("Location: inlog.php");
    exit();
}

?>