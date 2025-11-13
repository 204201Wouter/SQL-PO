<head>
    <style>
        .card {
            padding: 0;
            background-color: transparent;
        
            border: none;
            margin: 5px;
         

        }
        .card:active {
        transform: translateY(4px);
        }
        #hand {
          
            position: fixed;
            left: calc(50% - 125px);
            bottom: 10;

        }
        #stapel {
            position: fixed;
            left: calc(50% - 125px);
            top: calc(50% - 125px);; 
        }
    </style>
</head>

<body style="background-color:#008531;">
<form method="post">
  <input type="text" name="move" placeholder="move" id="input" value="[]">
  <button type="submit" name="playMove">Play Move</button>
</form>

<script>
    function insert(text)
    {
        const input = document.getElementById('input');
        const card = document.getElementById(text);

        function getkaartfromid(kaartid) {
            if (kaartid > 51) return 13;
            else return kaartid % 13;
        }
     
        try {
            let array = JSON.parse(input.value);
            console.log(array);

            
            if (!array.includes(text) && (array.length == 0 || (array.length > 0 && getkaartfromid(array[0]) == getkaartfromid(text) ) )) {
                array.push(text);
                console.log(array);
                input.value = JSON.stringify(array);
            
                
            }
            else if (array.includes(text))  {
                
                array.splice(array.indexOf(text),1);
                input.value = JSON.stringify(array);

            }
            
        } catch (e) {
            console.error(e);
        }



    }
</script>

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

echo "<div id='hand'>";
foreach ($kaarten as $kaart)
{
echo "<button class='card' onclick='insert($kaart)'><img src='images/".$kaart.".svg' style='width:75px;'></button>";
}
echo "</div>";




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
    echo "<br>$bovenstekaartid <br><img src='images/".$bovenstekaartid.".svg' style=width:75px;> <br>";


    $i = 2;
    while ($bovenstekaart == 1 || $bovenstekaart == 13) {
        $bovenstekaart = getkaartfromid($stapel[count($stapel) - $i]);
        echo "<img src='images/".$stapel[count($stapel) - $i].".svg' style=width:75px;> <br>";
        $i++;
        if ($i > count($stapel)) $bovenstekaart = -1;
    }
}
else {
    $bovenstekaartid = -1; // lege stapel dus geen kaart
    $bovenstekaart = -1;
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
    global $kaarten;
    global $bovenstekaart;



    $kaart = getkaartfromid($move);

    // als je kaart niet hebt
  //  foreach ($move as $movekaart) {
      //  if (!in_array($movekaart, $kaarten) || getkaartfromid($movekaart) != $kaart) return false;
   // }
   // if (!in_array($move, $kaarten)) return false;

    // als kaart 2, 3 of joker is
    if ($kaart == 1 || $kaart == 13 || $kaart == 0) return true;
    
    // als bovenste kaart 7 is
    if ($bovenstekaart == 5) return $kaart < $bovenstekaart;

    return $kaart > $bovenstekaart;
}

function refillCards() {
    global $kaarten;
    global $pakstapel;

    for ($i = 0; $i < 13; $i++) {
        $currentCards = [$i, $i + 13, $i + 26, $i + 39];

        if (count(array_intersect($kaarten, $currentCards)) == 4) {
            $kaarten = array_values(array_diff($kaarten, [$i, $i + 13, $i + 26, $i + 39]));
        }
    }

    while (count($kaarten) < 3)
    {
        $kaarten[] = array_shift($pakstapel);
    }
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


    if ($playerid == $turn) {
        if (validCard($move[0]) && samecards($move)){
            $conn->query("UPDATE servers SET turn = ".$nextplayerid." WHERE id = '$gameid'");
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
            
            refillCards();

            $stapel = json_encode($stapel);
            $kaarten = json_encode($kaarten);
            $pakstapel = json_encode($pakstapel);
            
            $conn->query("UPDATE servers SET stapel = '$stapel' WHERE id = '$gameid'");
            $conn->query("UPDATE servers SET pakstapel = '$pakstapel' WHERE id = '$gameid'");
            $conn->query("UPDATE players SET hand = '$kaarten' WHERE id = '".$_SESSION['id']."'");
            header("Refresh:0");
        }
        else {
            if (!possibleMove($kaarten))
            {
                $conn->query("UPDATE servers SET turn = ".$nextplayerid." WHERE id = '$gameid'");

                foreach ($stapel as $kaart)
                {
                    $kaarten[] = array_shift($stapel);
                }

                refillCards();

                $stapel = json_encode($stapel);
                $kaarten = json_encode($kaarten);
                $pakstapel = json_encode($pakstapel);
                
                $conn->query("UPDATE servers SET stapel = '$stapel' WHERE id = '$gameid'");
                $conn->query("UPDATE servers SET pakstapel = '$pakstapel' WHERE id = '$gameid'");
                $conn->query("UPDATE players SET hand = '$kaarten' WHERE id = '".$_SESSION['id']."'");
                header("Refresh:0");
            }
        }
    }
}

function botMove() {
    // bot logic hier
    
}

if (isset($_POST['playMove'])) {
    $value = $_POST['move'];
    $array = json_decode($value);
    if (is_array($array) || true)
    {
        playmove(json_decode($value));
    }
}
}
else {
    header("Location: inlog.php");
    exit();
}

?>
</body>