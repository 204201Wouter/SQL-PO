<?php 
session_start();

$username = htmlspecialchars($_POST["username"]);
$password = htmlspecialchars($_POST["password"]);

// verbind met database
$conn = new mysqli("localhost", "root", "", "zweeds pesten");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// inlog logica
$sql = "SELECT * FROM users WHERE username = '$username'";
$result = $conn->query($sql);

if ($result->num_rows > 0 && $username != 'bot') {
    $row = $result->fetch_assoc();
    if ($row["password"] == $password) {
        echo "succes login";
        
        $_SESSION['loggedin'] = true;
        $_SESSION['id'] = $row['id'];
        $_SESSION['username'] = $username;
        header("Location: home.php");
        exit();
    }
    else {
        echo "incorrect username or password";
        header("Location: inlog.php?login=incorrect");
        exit();
    } 
}
else {
    echo "incorrect username or password";
    header("Location: inlog.php?login=incorrect");
    exit();
}
  
$conn->close();
?>
