<form method="post">
  <button type="submit" name="runFunction">Delete servers</button>
</form>

<?php
session_start();

$conn = new mysqli("localhost", "root", "", "zweeds pesten");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

function test() {
    global $conn;
    $conn->query("DELETE FROM servers");
}

if (isset($_POST['runFunction'])) {
    test();
}
?>