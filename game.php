<?php 
session_start();

$conn = new mysqli("localhost", "root", "", "zweeds pesten");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$gameid = $_GET["id"];

echo "dit is een prachtige game";
$turn = implode(', ', $conn->query("SELECT turn FROM servers WHERE id = '$gameid'")->fetch_assoc());
echo "<br>$turn"
?>