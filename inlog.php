<html>
    <body style='text-align:center;'>
        <!-- inlog invulvelden -->
        <form method="POST" action="inlogcheck.php" >
            <label for="username">Username:</label><br>
            <input type="text" id="username" name="username"><br>
            <label for="password">Password:</label><br>
            <input type="password" id="password" name="password"><br><br>
            <input type="submit" value="login">
        </form>
        <a href="newaccount.php">new account</a>
        
        <?php
            // als inlog niet goed is laat het zien
            if (isset($_GET["login"]) && $_GET["login"] == 'incorrect') {
                echo "<br>incorrect username or password";
            }
        ?>
    </body>
</html>