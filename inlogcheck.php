<?php 


session_start();



$username = htmlspecialchars($_POST["username"]);
$password = htmlspecialchars($_POST["password"]);



// Create connection
$conn = new mysqli("localhost", "root", "", "zweeds pesten");
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT * FROM users WHERE username = '$username'";
$result = $conn->query($sql);

if ($result->num_rows > 0 && $username != 'bot') {
    $row = $result->fetch_assoc();
    if ($row["password"] == $password)
    {
        echo "succes login";

        $id = rand();
        $usersWithId = $conn->query("SELECT * FROM users WHERE id = '$id'");
        while ($usersWithId->num_rows > 0) {
            $id = rand();
            $usersWithId = $conn->query("SELECT * FROM users WHERE id = '$id'");
        }

        $conn->query("UPDATE users SET id = '$id' WHERE username = '$username'");

        $_SESSION['loggedin'] = true;
        $_SESSION['id'] = $id;
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
