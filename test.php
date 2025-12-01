<form method="post">
  <button type="submit" name="runFunction">Delete servers</button>
</form>

<?php
// (W3Schools, z.d.)
session_start();

// verbind met database
$conn = new mysqli("localhost", "root", "", "zweeds pesten");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


// tijdelijk bestand voor deleten servers
function test() {
    global $conn;

    $conn->query("DELETE FROM players");
    $conn->query("DELETE FROM servers");
    $conn->query("DELETE FROM games");
    header("Location: home.php");
    exit();
}

if (isset($_POST['runFunction'])) {
    test();
}
?>