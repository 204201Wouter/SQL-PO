<?php

session_start();
?>
<html>
    <body>
<?php
if ($_SESSION["loggedin"]  == true)
{



    



    echo "waiting for more players";





    

}
else {
      header("Location: inlog.php");
       exit();
}






?>
</body>
</html>