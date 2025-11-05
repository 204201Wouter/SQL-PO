<html>
    <body>
        <form method="POST" action="inlogcheck.php">
            <label for="username">Username:</label><br>
            <input type="text" id="username" name="username"><br>
            <label for="password">Password:</label><br>
            <input type="text" id="password" name="password"><br><br>
            <input type="submit" value="Submit">
        </form>
        <a href="newaccount.php">new account</a>
        
        <?php
            if (isset($_GET["login"]) && $_GET["login"] == 'incorrect') {
                echo "incorrect username or password";
            }
        ?>
    </body>
</html>