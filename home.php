<?php
session_start();
?>

<html>
<body>
<?php
if ($_SESSION["loggedin"]  == true)
{
    echo "welkom ". $_SESSION["id"];
    

}
else {
      header("Location: inlog.php");
       exit();
}


?>
</body>
</html>