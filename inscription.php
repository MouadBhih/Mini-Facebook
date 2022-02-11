<!DOCTYPE html>
<?php
    /*if (isset($_POST["login"] && isset($_POST["password"])) {

    }*/
?>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>inscription</title>
</head>
<body>
    <p>Welcome to inscription page</p>
    <form action="inscription.php" method="post">
        <table>
            <tr>
                <td>Identifiant:</td>
                <td><input type="text" name="login" size="32" maxlength="128"></td>
            </tr>
            <tr>
                <td>Mot de passe:</td>
                <td><input type="password" name="password" size="32" maxlength="32"></td>
            </tr>
            <tr><td colspan="2" align="center">
                <input type="submit" value="Se connecter">
                <input type="reset" value="Effacer">
            </td></tr>
        </table>
    </form>
</body>
</html>