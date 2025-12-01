<html>
    <body style='text-align:center;'>
        <!-- new account invulvelden -->
        <form method="POST" action="newaccountcheck.php">
            <label for="username">Username:</label><br>
            <input type="text" id="username" name="username"><br>
            <label for="password">Password:</label><br>
            <input type="text" id="password" name="password"><br><br>
            <input type="submit" value="create">
        </form>
        <a href="inlog.php">login</a>

        <?php
            // als gegevens ongeldig zijn laat het zien
            if (isset($_GET["account"])) {
                switch ($_GET["account"]) {
                    case "taken":
                        echo "username already taken";
                        break;
                    case "short":
                        echo "username too short";
                        break;
                    case "long":
                        echo "username too long";
                        break;
                    default:
                        echo "invalid username or password";
                }
            }
        ?>
    </body>
</html>

