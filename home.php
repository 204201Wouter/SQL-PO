<?php
session_start();
?>
<html>
<body>
<?php
if ($_SESSION["loggedin"] == true)
{
    // verbind met database
    $conn = new mysqli("localhost", "root", "", "zweeds pesten");
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $id = $_SESSION["id"];
    $result = $conn->query("SELECT * FROM stats WHERE id = $id")->fetch_assoc();

    // laat text en knoppen zien
    echo "<div style='text-align:center;'><h1>welkom ". $_SESSION['username']."!</h1> je elo is ".$result['elo']."<br><a href='createserver.php'>create server</a><br>
    <a href='findserver.php'>join server</a><br>
    <a href='search.php'>stats</a><br>
    <a href='uitleg.html'>uitleg</a><div>";

    $conn->close();

    echo '
    <form method="post">
        <input type="submit" name="logout" value="logout">
    </form>';

    // log uit als je op knop klikt
    if(isset($_POST['logout'])) {
        session_destroy(); 
        header("Location: inlog.php");
        exit();
    }
}
else {
    header("Location: inlog.php");
    exit();
}
?>

</body>
</html>