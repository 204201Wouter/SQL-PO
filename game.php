<?php 
session_start();
?>
<head>
    <style>
        .card {
            padding: 0;
            background-color: transparent;
            border: none;
            margin: 5px;
        }

        #hand {
            position: fixed;
            left: 50%;
            bottom: 10px;
        }

        #stapel {
            position: fixed;
            left: 50%;
            top: 50%; 
            transform: translate(-50%,-50%);
        }
    </style>
</head>

<body style="background-color:#008531;">
<form method="post">
    <input type="text" name="move" placeholder="move" id="input" value="[]" style="display:none;">
    <button type="submit" name="playMove" id="playMoveButton" style='position:fixed;left:50%;bottom:10;transform:translateX(-50%);'>Play Move</button>
</form>
<a href="home.php">home</a>

<form method="post">
    <button type="submit" name="ready" id='readyButton' style='position:fixed;left:10;bottom:10;'>Ready</button>
</form>

<script>
    function updatebuttonname()
    {
        const playmovebutton = document.getElementById('playMoveButton');

        if (wisselen) playmovebutton.innerHTML = "Switch Cards";
        else if (pakken) playmovebutton.innerHTML = "Take Card";
        else playmovebutton.innerHTML = "Play Move";
    }
    
    function moveto(object, start,end)
    {
        object.style.transform = "translate(-50%,-50%)";
        object.style.top = "50%";
       // object.style.transform += ` translate(${end[0]-start[0]}px, ${end[1]-start[1]}px)`;
        console.log(object.style.left );

    }
    function insert(text, kaartVoor)
    {
        const input = document.getElementById('input');
        const card = document.getElementById(text);

        function getkaartfromid(kaartid) {
            if (kaartid > 51) return 13;
            else return kaartid % 13;
        }

        try {
            let array = JSON.parse(input.value);
            
            if ((wisselen && !array.includes(text) && array.length < 2) || (pakken == kaartVoor && ((pakken && !array.includes(text)) || (!pakken && !array.includes(text) && (array.length == 0 || (array.length > 0 && getkaartfromid(array[0]) == getkaartfromid(text))))))) {
                array.push(text);
                input.value = JSON.stringify(array);
                card.style.transform += "translateY(-5px)";
            }
            else if (array.includes(text))  {
                array.splice(array.indexOf(text),1);
                input.value = JSON.stringify(array);
                card.style.transform += "translateY(5px)";
            }
        } catch (e) {
            console.error(e);
        }
    }
    
    function removereadybutton() {
        document.getElementById('readyButton').remove();
    }
</script>


<?php 
ob_start();
if ($_SESSION["loggedin"] != true)
{
    header("Location: inlog.php");
    ob_end_flush();
    exit();
}

$conn = new mysqli("localhost", "root", "", "zweeds pesten");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


$gameid = $_GET['id'];
$playerid = $_SESSION['id'];


$game = $conn->query("SELECT * FROM games WHERE id = '$gameid'");

if ($game->num_rows == 0) 
{
    header("Location: lobby.php?id=$gameid");
    ob_end_flush();
    exit();
}
$game = $game->fetch_assoc();


$you = $conn->query("SELECT hand, nummer, kaartenvooropen, kaartenvoorgesloten FROM players WHERE id = '$playerid'")->fetch_assoc();

$kaarten = $you['hand'];
$kaartenvooropen = json_decode($you['kaartenvooropen']);
$kaartenvoorgesloten = json_decode($you['kaartenvoorgesloten']);
$yournummer = $you['nummer'];

$cardsize = 65;

$started = true;
$playersready = $conn->query("SELECT ready FROM players WHERE serverid = '$gameid'");
while ($row = $playersready->fetch_assoc()) {
    if (!$row['ready']) {
        $started = false;
        echo "<script>wisselen=true;</script>";
        break;
    }
}

if ($started) echo "<script>wisselen=false;</script>";

$ready = $conn->query("SELECT ready FROM players WHERE id = '$playerid'")->fetch_assoc()['ready'];
if ($ready) echo "<script>removereadybutton();</script>";

//echo "jouw kaarten: <br>".$kaarten."<br>";
$kaarten = json_decode($kaarten);


if (count($kaarten) >= 3 || count($kaartenvoorgesloten) == 0)  {
    echo "<script>pakken=false;</script>";
}
else {
    echo "<script>pakken=true;</script>";
}

echo "<script>updatebuttonname();</script>";

function drawHand($kaarten, $hand, $height, $gesloten, $layer, $kaartvoor)
{
    global $cardsize;

    $margin = 70;
    if ($margin*count($kaarten) > 500) $margin = 500/count($kaarten);

    $i = 1;
    foreach ($kaarten as $kaart)
    {
        if ($i % 2 == 0) $b = -1;
        else $b = 1;

        if ($gesloten) $kaart = "back";


        $a = floor(($i)/2) * $margin  * $b;
        if ($hand == 0) {
            if (!$gesloten) {
                echo "<button class='card' onclick='insert($kaart, $kaartvoor)'><img id='$kaart' src='images/".$kaart.".svg' 
                style='width:".$cardsize."px; 
                position:fixed;
                bottom:".$height."px;
                left:50%;
                transform:translateX(".$a."px) translateX(-50%)  translateY(".-$layer."px);
                z-index:".round($a).";
                '></button>";
            }            
            else {
                echo "<img src='images/back.svg' 
                style='width:".$cardsize."px; 
                position:fixed;
                bottom:".$height."px;
                left:50%;
                transform:translateX(".$a."px) translateX(-50%)  translateY(".-$layer."px);
                z-index:".round($a).";
                '>";
            }
        }
        else if ($hand == 1) {
            echo "<img src='images/$kaart.svg' 
            style='width:".$cardsize."px; 
            position:fixed;
            top:".$height."px;
            left:50%;
            transform:translateX(".$a."px) translateX(-50%) rotate(180deg) translateY(".$layer."px);
            '>";
        }
        else if ($hand == 2) {
            echo "<img src='images/$kaart.svg' 
            style='width:".$cardsize."px; 
            position:fixed;
            top:50%;
            left:".$height."px;
            transform:translateY(".$a."px) translateY(-50%) rotate(90deg)  translateX(".-$layer."px);
            '>";
        }  
        else if ($hand == 3) {
            echo "<img src='images/$kaart.svg' 
            style='width:".$cardsize."px; 
            position:fixed;
            top:50%;
            right:".$height."px;
            transform:translateY(".$a."px) translateY(-50%) rotate(90deg) translateX(".-$layer."px);
            '>";
        }
        $i++;
    }
}


echo "<div id='hand'>";


// $margin*count($kaarten) = 1000
drawHand($kaarten, 0, 50, false, 0, "false");
drawHand($kaartenvoorgesloten, 0, 150, true, 0, "true");
drawHand($kaartenvooropen, 0, 150, false, 5, "true");



$sql = "SELECT hand, kaartenvooropen, kaartenvoorgesloten FROM players WHERE serverid = '$gameid'";
$result = $conn->query($sql);
$row = $result->fetch_assoc();


$enemykaarten = json_decode($row['hand']);
if ($enemykaarten == $kaarten)
{
    $row = $result->fetch_assoc();
    $enemykaarten = json_decode($row['hand']);  
}



drawHand($enemykaarten, 1, 50, true, 0, "false");
drawHand(json_decode($row['kaartenvoorgesloten']), 1, 160, true, 0, "false");
drawHand(json_decode($row['kaartenvooropen']), 1, 160, false, 5, "false");


$row = $result->fetch_assoc();
$enemykaarten = json_decode($row['hand']);
if ($enemykaarten == $kaarten)
{
    $row = $result->fetch_assoc();
    $enemykaarten = json_decode($row['hand']);  
}

drawHand($enemykaarten, 2, 50, true, 0, "false");
drawHand(json_decode($row['kaartenvoorgesloten']), 2, 160, true, 0, "false");
drawHand(json_decode($row['kaartenvooropen']), 2, 160, false, 5, "false");


$row = $result->fetch_assoc();
$enemykaarten = json_decode($row['hand']);
if ($enemykaarten == $kaarten)
{
    $row = $result->fetch_assoc();
    $enemykaarten = json_decode($row['hand']);  
}

drawHand($enemykaarten, 3, 50, true, 0, "false");
drawHand(json_decode($row['kaartenvoorgesloten']), 3, 160, true, 0, "false");
drawHand(json_decode($row['kaartenvooropen']), 3, 160, false, 5, "false");



echo "</div>";


$stapel = $game['stapel'];
$stapel = json_decode($stapel);

$pakstapel = json_decode($game['pakstapel']);

for ($i = 0; $i > -count($pakstapel); $i--) 
{   
    echo "<img src='images/back.svg'
        style='width:".$cardsize."px;
        position:fixed;
        bottom:50%;
        left:50%;
        transform:translate(50%, 50%) translateY(" . $i . "px);'>";
}


$turn = $game['turn'];
$sql = "SELECT users.username, players.nummer FROM players JOIN users ON players.user = users.id WHERE nummer = $turn";
$result = $conn->query($sql)->fetch_assoc();

$turnName = $result['username'];

if (!$started) {
    if ($ready) echo "<meta http-equiv='refresh' content='2'>";
    echo "<br>Wachten tot iedereen klaar is...<br>Wissel je kaarten";
}
else if ($game['winner'] == null) {
    if (strcasecmp($turnName, $_SESSION['username']) == 0) {
        echo "<br>Jij bent aan de beurt";
        if (count($kaarten) >= 3 || count($kaartenvoorgesloten) == 0) {echo "<br>Leg kaart neer";}
        else {echo "<br>Pak kaart(en)";}
    }
    else echo "<br>$turnName is aan de beurt<meta http-equiv='refresh' content='2'>";
}
else {
    if (strcasecmp($turnName, $_SESSION['username']) == 0) echo "Jij hebt gewonnen";
    else echo "$turnName heeft gewonnen";
    echo "<form method='post'><button type='submit' name='toLobby' id='lobbyButton' style='position:fixed;right:10;top:10;'>Return to lobby</button></form>";
}

// ------ IDs KAARTEN -------
// 0-12: HARTEN, 13-25: RUITEN, 26-38: SCHOPPEN, 39-51: KLAVERS
// volgorde: 2 t/m 10, B, V, K, A
// 52 en 53 zijn jokers

function getkaartfromid($kaartid) {
    if ($kaartid > 51) return 13;
    else return $kaartid % 13;
}


if (count($stapel) > 0) {
    echo "<div id='stapel'>";
    $bovenstekaartid = end($stapel);
    $bovenstekaart = getkaartfromid($bovenstekaartid);


    $i = 2;
    while ($bovenstekaart == 1 || $bovenstekaart == 13) {
        if ($i > count($stapel)) {
            $bovenstekaart = -1;
            break;
        }
        $bovenstekaart = getkaartfromid($stapel[count($stapel) - $i]);
        $a = ($i-1)*15;
        $i++;
    }

    echo "</div>";
}
else {
    $bovenstekaartid = -1; // lege stapel dus geen kaart
    $bovenstekaart = -1;
}


$i = 0;
$a = 0;
foreach ($stapel as $kaart)
{   
    if (getkaartfromid($kaart) == 1 || getkaartfromid($kaart) == 13) {$a  = 10;}
    else {$a = 0;}
    echo "<br><img src='images/" . $kaart . ".svg' style='
        width:".$cardsize."px;
        position:fixed;
        bottom:50%;
        left:50%;
        transform: translate(-50%,50%) translateY(" . $i-$a . "px);'>";
        $i-= 3;
}

function possibleMove(array $move)  
{
    foreach ($move as $movekaart) {
        if (validCard($movekaart)) return true;
    }

    return false;
}

function samecards(array $move)
{
    global $kaarten;
    $kaart1 = getkaartfromid($move[0]);
    foreach ($move as $movekaart) {
        if (!in_array($movekaart, $kaarten) || getkaartfromid($movekaart) != $kaart1) return false;
    }
    return true;
}


function validCard(int $move) {
    global $bovenstekaart;

    $kaart = getkaartfromid($move);

    // als kaart 2, 3 of joker is
    if ($kaart == 1 || $kaart == 13 || $kaart == 0) return true;
    
    // als bovenste kaart 7 is
    if ($bovenstekaart == 5) return $kaart < $bovenstekaart;

    return $kaart > $bovenstekaart;
}

function refillCards(bool $forPlayer) {
    global $pakstapel;
    if ($forPlayer) {
        global $kaarten;
        $localkaarten = $kaarten;
    }
    else {
        global $botkaarten;
        $localkaarten = $botkaarten;
    }

    for ($i = 0; $i < 13; $i++) {
        $currentCards = [$i, $i + 13, $i + 26, $i + 39];

        if (count(array_intersect($localkaarten, $currentCards)) == 4) {
            $localkaarten = array_values(array_diff($localkaarten, [$i, $i + 13, $i + 26, $i + 39]));
        }
    }

    while (count($localkaarten) < 3 && count($pakstapel) > 0)
    {
        $localkaarten[] = array_shift($pakstapel);
    }

    if (!$forPlayer) $botkaarten = $localkaarten;
    else $kaarten = $localkaarten;
}

function updatestats(array $player)
{
    global $turn;
    global $conn;

    $sql = "SELECT * FROM stats WHERE id = " . $player[0];
    $result = $conn->query($sql)->fetch_assoc();


    $wins = $result["wins"];
    $played = $result["gamesplayed"];

    $played += 1;
    if ($player[0] == $turn) $wins += 1;

    $elo = $player[1]+$player[3];

    $result = $conn->query("UPDATE stats SET elo = $elo WHERE id = ".$player[0]);
    $result = $conn->query("UPDATE stats SET wins = $wins WHERE id = ".$player[0]);
    $result = $conn->query("UPDATE stats SET gamesplayed = $played WHERE id = ".$player[0]);

    /*
    E=1+10(Ropp​−Rplayer​)/4001​
    S=points earned
    R′=R+K(S−Etotal​)

    RA′​=RA​+Kall opponents B∑​(SA,B​−EA,B​)

    k = 20

    E = 1/ (1+pow(10,((elo opp - elo player) /400))  for all players

    s = 1 0.5 or 0 

    delta = s-E all players

    Elo += 20*delta
    */
}

function goNextTurn(bool $win) {
    global $turn;
    global $gameid;
    global $conn;


    $nextplayer = ($turn+1)%4;

    if ($win)  {
        $conn->query("UPDATE games SET winner = $turn WHERE id = '$gameid'"); 
        $conn->query("UPDATE servers SET started = 0 WHERE id = '$gameid'"); 

        $sql = "SELECT user, hand, kaartenvooropen, kaartenvoorgesloten FROM players WHERE serverid = '$gameid'";
        $result = $conn->query($sql);

        $players = [];

        while ($row = $result->fetch_assoc()) {
            $player = $row['user'];
            if ($player != -1) {
                $hand = count(json_decode($row["hand"]));
                $open = count(json_decode($row["kaartenvooropen"]));
                $gesloten = count(json_decode($row["kaartenvoorgesloten"]));
                $playercards = $hand + $open + $gesloten;  

                $elo = $conn->query("SELECT elo FROM stats WHERE id = '$player'")->fetch_assoc()['elo'];

                $players[] = [$player, $elo, $playercards, 0];
            }
            
        }

        foreach ($players as &$player)
        {
            foreach ($players as $opp)
            {
                if ($player !== $opp)
                {
                    $E = 1 / (1 + pow(10, ($opp[1] - $player[1])/400));

                    if ($player[2] < $opp[2]) $S = 1;
                    else if ($player[2] > $opp[2]) $S = 0;
                    else $S = 0.5;

                    $player[3] += 20*($S-$E);
                }
            }   
        }

        foreach ($players as &$player)
        {
     

            updatestats($player);
        }

        $sql = "SELECT user FROM players WHERE serverid = '$gameid' AND nummer = $turn";
        $result = $conn->query($sql)->fetch_assoc();

        while (count($players) < 4) $players[] = [-1, 1000, 0, 0];

        
        

        $conn->query("INSERT INTO gameslog (
        player1, player1elo, player1elodiff, 
        player2, player2elo, player2elodiff, 
        player3, player3elo, player3elodiff, 
        player4, player4elo, player4elodiff, date)
        VALUES ('".$players[0][0]."', '".$players[0][1]."', '".$players[0][3]."',
         '".$players[1][0]."', '".$players[1][1]."', '".$players[1][3]."',
          '".$players[2][0]."', '".$players[2][1]."', '".$players[2][3]."',
           '".$players[3][0]."', '".$players[3][1]."', '".$players[3][3]."',
           NOW()
           )");        
    }

    else $conn->query("UPDATE games SET turn = $nextplayer WHERE id = '$gameid'");

    header("Refresh:0");
    ob_end_flush();
    exit();
}

function playmove(array $move) {
    global $conn;
    global $game;
    global $turn;
    global $gameid;
    global $kaarten;
    global $stapel;
    global $pakstapel;
    global $yournummer;
    global $kaartenvooropen;
    global $kaartenvoorgesloten;
    global $playerid;

 
    if ($yournummer == $turn) {
        if (count($move) > 0 && validCard($move[0]) && samecards($move)) {
            foreach ($move as $movekaart) {
                $stapel[] = $movekaart;
                array_splice($kaarten, array_search($movekaart, $kaarten), 1); 
            }
            
            if (getkaartfromid($move[0]) == 8) {
                $stapel = [];
            }

            for ($i = 0; $i < 13; $i++) {
                if (in_array($i, $stapel) && in_array($i + 13, $stapel) && in_array($i + 26, $stapel) && in_array($i + 39, $stapel)) {
                    $stapel = [];
                    break;
                }
            }
        }
        else if (!possibleMove($kaarten))
        {
            foreach ($stapel as $kaart)
            {
                $kaarten[] = array_shift($stapel);
            }
        }
        else return null;

        refillCards(true);
        
        $conn->query("UPDATE games SET stapel = '" . json_encode($stapel) . "' WHERE id = '$gameid'");
        $conn->query("UPDATE games SET pakstapel = '" . json_encode($pakstapel) . "' WHERE id = '$gameid'");
        $conn->query("UPDATE players SET hand = '" . json_encode($kaarten) . "' WHERE id = '$playerid'");

        if (count($kaarten) < 3){
            if (count($kaartenvooropen) > 0){
                header("Refresh:0");
                ob_end_flush();
                exit();
            }
            else if (count($kaartenvoorgesloten) > 0) {
                for ($i = 0; $i < 3 - count($kaarten); $i++) {
                    if (count($kaartenvoorgesloten) > 0) $kaarten[] = array_shift($kaartenvoorgesloten);
                }

                $conn->query("UPDATE players SET hand = '" . json_encode($kaarten) . "' WHERE id = '$playerid'");
                $conn->query("UPDATE players SET kaartenvoorgesloten = '" . json_encode($kaartenvoorgesloten) . "' WHERE id = '$playerid'");

                goNextTurn((count($kaarten)+count($kaartenvoorgesloten)) == 0);
            }
            else goNextTurn((count($kaarten)+count($kaartenvoorgesloten)) == 0);
        }
        else goNextTurn((count($kaarten)+count($kaartenvoorgesloten)) == 0);
    }
}

function botMove() {
    global $conn;
    global $turn;
    global $game;
    global $gameid;
    global $stapel;
    global $pakstapel;
    global $botkaarten;


    $sql = "SELECT hand, kaartenvooropen, kaartenvoorgesloten FROM players WHERE nummer = $turn AND serverid = '$gameid'";
    $result = $conn->query($sql)->fetch_assoc();

    $botkaarten = json_decode($result['hand']);
    $botkaartenvooropen = json_decode($result['kaartenvooropen']);
    $botkaartenvoorgesloten = json_decode($result['kaartenvoorgesloten']);
    
    $laagstekaart = 13;
    $bestekaartid = -1;
    foreach ($botkaarten as $kaart) {
        $kaartid = getkaartfromid($kaart);
        if (validCard($kaart) && $kaartid < $laagstekaart && $kaartid != 1 && $kaartid != 0) {
            $laagstekaart = $kaartid;
            $bestekaartid = $kaartid;
        }
    }

    $move = [];
    if ($bestekaartid != -1) {
        foreach ($botkaarten as $kaart) {
            if (getkaartfromid($kaart) == $bestekaartid) {
                $move[] = $kaart;
            }
        }
    }
    else {
        foreach ($botkaarten as $kaart) {
            if (getkaartfromid($kaart) == 0) {
                $move = [$kaart];
                break;
            }
            if (count($move) == 0 && (getkaartfromid($kaart) == 1 || getkaartfromid($kaart) == 13)) {
                $move = [$kaart];
            }
        }
    }

    if (count($move) > 0) {
        foreach ($move as $movekaart) {
            $stapel[] = $movekaart;
            array_splice($botkaarten, array_search($movekaart, $botkaarten), 1); 
        }

        if (getkaartfromid($move[0]) == 8) {
            $stapel = [];
        }

        for ($i = 0; $i < 13; $i++) {
            if (in_array($i, $stapel) && in_array($i + 13, $stapel) && in_array($i + 26, $stapel) && in_array($i + 39, $stapel)) {
                $stapel = [];
                break;
            }
        }
    }
    else {
        foreach ($stapel as $kaart)
        {
            $botkaarten[] = array_shift($stapel);
        }
    }
        
    refillCards(false);

    if (count($botkaarten) < 3) {
        for ($i = 0; $i < 3 - count($botkaarten); $i++) {
            if (count($botkaartenvooropen) > 0) $botkaarten[] = array_shift($botkaartenvooropen);
            else if (count($botkaartenvoorgesloten) > 0) $botkaarten[] = array_shift($botkaartenvoorgesloten);
        }
    }

    $conn->query("UPDATE games SET stapel = '" . json_encode($stapel) . "' WHERE id = '$gameid'");
    $conn->query("UPDATE games SET pakstapel = '" . json_encode($pakstapel) . "' WHERE id = '$gameid'");
    $conn->query("UPDATE players SET hand = '" . json_encode($botkaarten) . "' WHERE nummer = '$turn' AND serverid = '$gameid'");
    $conn->query("UPDATE players SET kaartenvooropen = '" . json_encode($botkaartenvooropen) . "' WHERE nummer = '$turn' AND serverid = '$gameid'");
    $conn->query("UPDATE players SET kaartenvoorgesloten = '" . json_encode($botkaartenvoorgesloten) . "' WHERE nummer = '$turn' AND serverid = '$gameid'");

    goNextTurn((count($botkaarten)+count($botkaartenvoorgesloten)) == 0);
}

function pakKaartenVoor(array $input) {
    global $kaartenvooropen;
    global $kaartenvoorgesloten;
    global $kaarten;
    global $conn;
    global $playerid;
    
    foreach ($input as $kaart) {
        if (in_array($kaart, $kaartenvooropen) && count($kaarten) < 3) {
            $kaarten[] = $kaart;
            array_splice($kaartenvooropen, array_search($kaart, $kaartenvooropen), 1);
        }
        else {
            header("Refresh:0");
            ob_end_flush();
            exit();
        }
    }

    $conn->query("UPDATE players SET hand = '" . json_encode($kaarten) . "' WHERE id = '$playerid'");
    $conn->query("UPDATE players SET kaartenvooropen = '" . json_encode($kaartenvooropen) . "' WHERE id = '$playerid'");

    if (count($kaarten) >= 3) goNextTurn((count($kaarten)+count($kaartenvoorgesloten)) == 0);
    else if (count($kaartenvooropen) == 0) {
        for ($i = 0; $i < 3 - count($kaarten); $i++) {
            if (count($kaartenvoorgesloten) > 0) $kaarten[] = array_shift($kaartenvoorgesloten);
        }

        $conn->query("UPDATE players SET hand = '" . json_encode($kaarten) . "' WHERE id = '$playerid'");
        $conn->query("UPDATE players SET kaartenvoorgesloten = '" . json_encode($kaartenvoorgesloten) . "' WHERE id = '$playerid'");

        goNextTurn((count($kaarten)+count($kaartenvoorgesloten)) == 0);
    }
}

$result = $conn->query("SELECT * FROM players WHERE serverid = '$gameid' AND user = -1");
$lowestbotnumber = 3;
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        if ($row["nummer"] < $lowestbotnumber) $lowestbotnumber = $row["nummer"];
    }
}
    

if ($turn >= $lowestbotnumber && $game['winner'] == null && $started) {
    //usleep(1000000);
    botMove();
}

if (isset($_POST['playMove']) && $game['winner'] == null) {
    $value = json_decode($_POST['move']);
    if ((count($kaarten) >= 3 || count($kaartenvoorgesloten) == 0) && $started)  {
        playmove($value);
    }
    else if (!$started) {
        if (count($value) == 2) {
            if (in_array($value[0], $kaarten) && in_array($value[1], $kaartenvooropen)){
                $kaarthand = $value[0];
                $kaartvoor = $value[1];
                $validinput = true;
            }
            else if (in_array($value[1], $kaarten) && in_array($value[0], $kaartenvooropen)){
                $kaarthand = $value[1];
                $kaartvoor = $value[0];
                $validinput = true;
            }
            else $validinput = false;

            if ($validinput) {
                array_splice($kaarten, array_search($kaarthand, $kaarten), 1);
                array_splice($kaartenvooropen, array_search($kaartvoor, $kaartenvooropen), 1);

                $kaarten[] = $kaartvoor;
                $kaartenvooropen[] = $kaarthand;

                $conn->query("UPDATE players SET hand = '" . json_encode($kaarten) . "' WHERE id = '$playerid'");
                $conn->query("UPDATE players SET kaartenvooropen = '" . json_encode($kaartenvooropen) . "' WHERE id = '$playerid'");

                header("Refresh:0");
                ob_end_flush();
                exit();
            }
            else echo "<br>Die kaarten kan je niet wisselen";
        }
    }
    else {
        pakKaartenVoor($value);
    }
}

if (isset($_POST['ready'])){
    $conn->query("UPDATE players SET ready = 1 WHERE id = '$playerid'");
    header("Refresh:0");
    ob_end_flush();
    exit();
}

if (isset($_POST['toLobby'])) {
    if ($_SESSION['username'] == $gameid) {
        $conn->query("DELETE FROM players WHERE serverid = '$gameid' AND user = -1");
        $conn->query("DELETE FROM games WHERE id = '$gameid'");
    }
    header("Location: lobby.php?id=$gameid");
    ob_end_flush();
    exit();
}
?>
</body>