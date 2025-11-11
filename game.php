<form method="post">
  <input type="text" name="move" placeholder="move">
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

echo "jouw kaarten: <br>".$kaarten."<br>";
$kaarten = json_decode($kaarten);

$stapel = $game['stapel'];
echo "stapel: <br>".$stapel."<br>";
$stapel = json_decode($stapel);


$pakstapel = json_decode($game['pakstapel']);

$turn = $game['turn'];
$turnName = $conn->query("SELECT username FROM users WHERE id = '$turn'")->fetch_assoc()['username'];
if ($turnName == $_SESSION['username'])
    echo "<br>Jij bent aan de beurt";
else
echo "<br>$turnName is aan de beurt<meta http-equiv='refresh' content='2'>";

// ------ IDs KAARTEN -------
// 0-12: HARTEN, 13-25: RUITEN, 26-38: SCHOPPEN, 39-51: KLAVERS
// volgorde: 2 t/m 10, B, V, K, A
// 52 en 53 zijn jokers

function getkaartfromid($kaartid) {
    if ($kaartid > 51) return 13;
    else return $kaartid % 13;
}

if (count($stapel) > 0) {
    $bovenstekaartid = end($stapel);
    $bovenstekaart = getkaartfromid($bovenstekaartid);
    $i = 2;
    while ($bovenstekaart == 1 || $bovenstekaart == 13) {
        $bovenstekaart = getkaartfromid($stapel[count($stapel) - $i]);
        $i++;
    }
}
else {
    $bovenstekaartid = -1; // lege stapel dus geen kaart
    $bovenstekaart = -1;
}

function valid(array $move) {
    global $kaarten;
    global $bovenstekaart;

    $kaart = getkaartfromid($move[0]);

    // als je kaart niet hebt
    foreach ($move as $movekaart) {
        if (!in_array($movekaart, $kaarten) || getkaartfromid($movekaart) != $kaart) return false;
    }

    // als kaart 2, 3 of joker is
    if ($kaart == 1 || $kaart == 13 || $kaart == 0) return true;
    
    // als bovenste kaart 7 is
    if ($bovenstekaart == 5) return $kaart < $bovenstekaart;

    return $kaart > $bovenstekaart;
}


function playmove(array $move) {
    global $conn;
    global $game;
    global $turn;
    global $gameid;
    global $playerid;
    global $kaarten;
    global $stapel;
    global $pakstapel;
    global $bovenstekaart;

    if ($game['player1'] == $playerid) $nextplayerid = $game['player2'];
    else $nextplayerid = $game['player1'];


    if ($playerid == $turn && valid($move)) {

        $conn->query("UPDATE servers SET turn = ".$nextplayerid." WHERE id = '$gameid'");
        foreach ($move as $movekaart) {
            $stapel[] = $movekaart;
            array_splice($kaarten, array_search($movekaart, $kaarten),1); 
        }
        
        if (count($kaarten) < 3)
        {
            $kaarten[] = array_shift($pakstapel);
        }

        $stapel = json_encode($stapel);
        $kaarten = json_encode($kaarten);
        $pakstapel = json_encode($pakstapel);
        
        $conn->query("UPDATE servers SET stapel = '$stapel' WHERE id = '$gameid'");
        $conn->query("UPDATE servers SET pakstapel = '$pakstapel' WHERE id = '$gameid'");
        $conn->query("UPDATE players SET hand = '$kaarten' WHERE id = '".$_SESSION['id']."'");
        header("Refresh:0");
    }
}

if (isset($_POST['playMove'])) {
    $value = $_POST['move'];
    playmove(json_decode($value));
}
}
else {
    header("Location: inlog.php");
    exit();
}

?>