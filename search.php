<?php
session_start();
?>

<html>
<body style="text-align:center;">

<table style="margin:auto; border: 1;">

<form method="GET" action="search.php">
    <label for="username">Username:</label><br>
    <input type="text" id="username" name="username"><br>
    <input type="submit" value="search"> <br><br>

    <tr>
        <th><input type="submit" name="sortbyusername" value="username"></th>
        <th><input type="submit" name="sortbyelo" value="elo"></th>
    </tr>
</form>

<?php
if ($_SESSION["loggedin"] == true)
{
    $conn = new mysqli("localhost", "root", "", "zweeds pesten");

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    if (isset($_GET["username"]) && $_GET["username"] != null){
    $id = htmlspecialchars($_GET["username"]);
    $sql = "SELECT * FROM stats JOIN users ON stats.user = users.id WHERE users.username = '$id'";
    }
    else 
    
    {
    if (isset($_GET["sortbyusername"]) )
    $sort = htmlspecialchars("username");
    else if (isset($_GET["sortbyelo"]) )
    $sort = htmlspecialchars("elo");

    else $sort = "username";

    $sql = "SELECT * FROM stats JOIN users ON stats.user = users.id ORDER BY $sort";

    }


    $result = $conn->query("$sql");

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($row['username']) . "</td>";
            echo "<td>" . htmlspecialchars($row['elo']) . "</td>";
            echo "<td><a href='profile.php?username=" . urlencode($row['username']) . "'>Visit Profile</a></td>";
            echo "</tr>";
        }
    }

    $conn->close();
}
else {
    header("Location: inlog.php");
    exit();
}
?>

</table>
</body>
</html>