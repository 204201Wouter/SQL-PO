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
  <input type="text" name="move" placeholder="move" id="input" value="[]" style="display:block;">
  <button type="submit" name="playMove" style='position:fixed;left:50%;bottom:10;transform:translateX(-50%);'>Play Move</button>
</form>

<script>

    function moveto(object, start,end)
    {
        object.style.transform = "translate(-50%,-50%)";
        object.style.top = "50%";
       // object.style.transform += ` translate(${end[0]-start[0]}px, ${end[1]-start[1]}px)`;
        console.log(object.style.left );

    }
    function insert(text)
    {
        const input = document.getElementById('input');
        const card = document.getElementById(text);

        console.log(card);
        console.log("e");
        function getkaartfromid(kaartid) {
            if (kaartid > 51) return 13;
            else return kaartid % 13;
        }


        

     
        try {
            let array = JSON.parse(input.value);
           // console.log(array);

            
            if (!array.includes(text) && (array.length == 0 || (array.length > 0 && getkaartfromid(array[0]) == getkaartfromid(text) ) )) {
                array.push(text);
               // console.log(array);
                input.value = JSON.stringify(array);
              //  card.style.transform += "translateY(-10px)";
            
                
            }
            else if (array.includes(text))  {
                
                array.splice(array.indexOf(text),1);
                input.value = JSON.stringify(array);
               // card.style.transform += "translateY(10px)";

            }
            
        } catch (e) {
            console.error(e);
        }

      //  moveto(card,[0,0],[0,-50])



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
    header("Location: home.php");
    ob_end_flush();
    exit();
}
$game = $game->fetch_assoc();


$you = $conn->query("SELECT hand, nummer, kaartenvooropen, kaartenvoorgesloten FROM players WHERE id = '".$_SESSION['id']."'")->fetch_assoc();

$kaarten = $you['hand'];
$kaartenvooropen = $you['hand'];
$kaartenvoorgesloten = $you['hand'];
$yournummer = $you['nummer'];

//echo "jouw kaarten: <br>".$kaarten."<br>";
$kaarten = json_decode($kaarten);

echo "<div id='hand'>";
$i = 1;
foreach ($kaarten as $kaart)
{
    if ($i % 2 == 0)
    {
        $b = -1;
    }
    else {$b = 1;}

    $a = floor(($i)/2) * 80 * $b;
    echo "<button class='card' onclick='insert($kaart)'><img id='$kaart' src='images/".$kaart.".svg' 
    style='width:75px; 
    position:fixed;
    bottom:50px;
    left:50%;
    transform:translateX(".$a."px) translateX(-50%);
    '></button>";
    $i++;
}




$sql = "SELECT hand FROM players WHERE serverid = '$gameid'";
$result = $conn->query($sql);
$i = 1;
$row = $result->fetch_assoc();
$enemykaarten = json_decode($row['hand']);
if ($enemykaarten == $kaarten)
{
    $row = $result->fetch_assoc();
    $enemykaarten = json_decode($row['hand']);  
}

foreach ($enemykaarten as $kaart)
{
    if ($i % 2 == 0)
    {
        $b = -1;
    }
    else {$b = 1;}

    $a = floor(($i)/2) * 80 * $b;
    echo "<img src='images/back.svg' 
    style='width:75px; 
    position:fixed;
    top:10px;
    left:50%;
    transform:translateX(".$a."px) translateX(-50%);
    '>";
    $i++;
}


$i = 1;
$row = $result->fetch_assoc();
$enemykaarten = json_decode($row['hand']);
if ($enemykaarten == $kaarten)
{
    $row = $result->fetch_assoc();
    $enemykaarten = json_decode($row['hand']);  
}

foreach ($enemykaarten as $kaart)
{
    if ($i % 2 == 0)
    {
        $b = -1;
    }
    else {$b = 1;}

    $a = floor(($i)/2) * 80 * $b;
    echo "<img src='images/back.svg' 
    style='width:75px; 
    position:fixed;
    top:100px;
    left:50%;
    transform:translateX(".$a."px) translateX(-50%);
    '>";
    $i++;
}


$i = 1;
$row = $result->fetch_assoc();
$enemykaarten = json_decode($row['hand']);
if ($enemykaarten == $kaarten)
{
    $row = $result->fetch_assoc();
    $enemykaarten = json_decode($row['hand']);  
}


foreach ($enemykaarten as $kaart)
{
    if ($i % 2 == 0)
    {
        $b = -1;
    }
    else {$b = 1;}

    $a = floor(($i)/2) * 80 * $b;
    echo "<img src='images/back.svg' 
    style='width:75px; 
    position:fixed;
    top:200px;
    left:50%;
    transform:translateX(".$a."px) translateX(-50%);
    '>";
    $i++;
}
echo "</div>";


$stapel = $game['stapel'];
$stapel = json_decode($stapel);

$pakstapel = json_decode($game['pakstapel']);

for ($i = 0; $i > -count($pakstapel); $i--) 
{   
    echo "<img src='images/back.svg'
        style='width:75px;
        position:fixed;
        bottom:50%;
        left:50%;
        transform:translate(50%, 50%) translateY(" . $i . "px);'>";
}


$turn = $game['turn'];
$sql = "SELECT users.username, players.nummer FROM players JOIN users ON players.user = users.id WHERE nummer = $turn";
$result = $conn->query($sql)->fetch_assoc();

$turnName = $result['username'];


if ($game['winner'] == null) {
    if (strcasecmp($turnName, $_SESSION['username']) == 0) echo "<br>Jij bent aan de beurt";
    else echo "<br>$turnName is aan de beurt";
   // else echo "<br>$turnName is aan de beurt<meta http-equiv='refresh' content='2'>";
}
else {
    if (strcasecmp($turnName, $_SESSION['username']) == 0) echo "Jij hebt gewonnen";
    else echo "$turnName heeft gewonnen";
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
foreach ($stapel as $kaart)
{
    echo "<br><img src='images/" . $kaart . ".svg' style='
        width:75px;
        position:fixed;
        bottom:50%;
        left:50%;
        transform: translate(-50%,50%) translateY(" . $i . "px);'>";
        $i-= 15;
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

function playmove(array $move) {
    global $conn;
    global $game;
    global $turn;
    global $gameid;
    global $kaarten;
    global $stapel;
    global $pakstapel;
    global $yournummer;

    
    $nextplayer = ($game["turn"]+1)%4;

 
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
            
            refillCards(true);

            if (count($kaarten) == 0) {
                $conn->query("UPDATE games SET winner = $turn WHERE id = '$gameid'");
            }
            else $conn->query("UPDATE games SET turn = $nextplayer WHERE id = '$gameid'");

            $stapel = json_encode($stapel);
            $kaarten = json_encode($kaarten);
            $pakstapel = json_encode($pakstapel);
            
            $conn->query("UPDATE games SET stapel = '$stapel' WHERE id = '$gameid'");
            $conn->query("UPDATE games SET pakstapel = '$pakstapel' WHERE id = '$gameid'");
            $conn->query("UPDATE players SET hand = '$kaarten' WHERE id = '".$_SESSION['id']."'");
            header("Refresh:0");
            ob_end_flush();
            exit();
        }
        else if (!possibleMove($kaarten))
        {
            $conn->query("UPDATE games SET turn = $nextplayer WHERE id = '$gameid'");
        
            
            foreach ($stapel as $kaart)
            {
                $kaarten[] = array_shift($stapel);
            }

            refillCards(true);

            $stapel = json_encode($stapel);
            $kaarten = json_encode($kaarten);
            $pakstapel = json_encode($pakstapel);
            
            $conn->query("UPDATE games SET stapel = '$stapel' WHERE id = '$gameid'");
            $conn->query("UPDATE games SET pakstapel = '$pakstapel' WHERE id = '$gameid'");
            $conn->query("UPDATE players SET hand = '$kaarten' WHERE id = '".$_SESSION['id']."'");
            header("Refresh:0");
            ob_end_flush();
            exit();
        }
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


    $sql = "SELECT hand FROM players WHERE nummer = $turn AND serverid = '$gameid'";
    $result = $conn->query($sql)->fetch_assoc();

    $botkaarten = json_decode($result['hand']);

    $nextplayer = ($game["turn"]+1)%4;
    
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

    if (count($move) == 0) {
        // pak stapel
        foreach ($stapel as $kaart)
        {
            $botkaarten[] = array_shift($stapel);
        }

        refillCards(false);

        $stapel = json_encode($stapel);
        $botkaarten = json_encode($botkaarten);
        $pakstapel = json_encode($pakstapel);
        
        $conn->query("UPDATE games SET stapel = '$stapel' WHERE id = '$gameid'");
        $conn->query("UPDATE games SET pakstapel = '$pakstapel' WHERE id = '$gameid'");
        $conn->query("UPDATE players SET hand = '$botkaarten' WHERE nummer = '$turn' AND serverid = '$gameid'");
        
        $conn->query("UPDATE games SET turn = $nextplayer WHERE id = '$gameid'");


    }
    else {
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
        
        refillCards(false);

        if (count($botkaarten) == 0) $conn->query("UPDATE games SET winner = $turn WHERE id = '$gameid'");
        else $conn->query("UPDATE games SET turn = $nextplayer WHERE id = '$gameid'");

        $stapel = json_encode($stapel);
        $botkaarten = json_encode($botkaarten);
        $pakstapel = json_encode($pakstapel);
        
        $conn->query("UPDATE games SET stapel = '$stapel' WHERE id = '$gameid'");
        $conn->query("UPDATE games SET pakstapel = '$pakstapel' WHERE id = '$gameid'");
        $conn->query("UPDATE players SET hand = '$botkaarten' WHERE nummer = '$turn' AND serverid = '$gameid'");
    }

    header("Refresh:0");
    ob_end_flush();
    exit();
}


$result = $conn->query("SELECT * FROM players WHERE serverid = '$gameid' AND user = -1");
$lowestbotnumber = 3;
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        if ($row["nummer"] < $lowestbotnumber) $lowestbotnumber = $row["nummer"];
    }
}
    

if ($turn >= $lowestbotnumber && $game['winner'] == null) {
    //usleep(1000000);
    botMove();
}



if (isset($_POST['playMove']) && $game['winner'] == null) {
    $value = $_POST['move'];
    playmove(json_decode($value));
}
?>
</body>