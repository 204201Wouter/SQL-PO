<?php
session_start();
?>

<html>
<body>
<?php


if ($_SESSION["loggedin"] == true)
{
    $conn = new mysqli("localhost", "root", "", "zweeds pesten");

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $id = $_SESSION["id"];
    $result = $conn->query("SELECT * FROM stats WHERE id = $id")->fetch_assoc();


    echo "welkom". $_SESSION['username']." je elo is ".$result['elo']."<a href='createserver.php'>create server</a><br>
    <a href='findserver.php'>join server</a><br>
    <a href='search.php'>stats</a>";

    $conn->close();

    echo '
    <form method="post">
        <input type="submit" name="logout"
                 value="logout">
        

    </form>';

    if(array_key_exists('logout', $_POST)) {
        session_destroy(); 
    }
}
else {
    header("Location: inlog.php");
    exit();
}
?>

</body>
</html>