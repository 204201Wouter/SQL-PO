<?php
session_start();

$conn = new mysqli("localhost", "root", "", "zweeds pesten");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$reset = true;
if ($reset) {
    $conn->query("DELETE FROM servers");
}
?>