<?php
session_start();
require("includes/includeDatabase.php");
include("includes/includeHead.php");
?>
<!DOCTYPE html>
<html lang="de">
<head>
    <title>Benutzereinstellungen</title>
</head>
<body>

<?php
include("includes/includeBody.php");

if(isset($_GET['userdelete'])) {
    $error = false;
    $userid = $_SESSION['userid'];
    $email = $_SESSION['email'];
    $passwort = $_POST['passwort'];

    $statement = $pdo->prepare("SELECT * FROM users WHERE email = :email");
    $result = $statement->execute(array('email' => $email));
    $user = $statement->fetch();

    //Überprüfung des Passworts
    if ($user !== false && password_verify($passwort, $user['passwort'])) {
        //die('Konto löschen erfolgreich');
        $stmt1 = $pdo->prepare("DELETE FROM users WHERE email = :email");
        $stmt1->bindParam(':email', $_SESSION['email'], PDO::PARAM_INT);
        $stmt1->execute();
        session_destroy();
        ?>
        <div class="alert alert-success fade in">
            Das Konto wurde erfolgreich <strong>gelöscht</strong>!
            <meta http-equiv="refresh" content="0; URL=index.php">
            <a href="index.php">Zur Startseite</a>
        </div>
        <?php
    }
    else {
        $errorMessage = "Passwort war ungültig<br>";
    }
}

if(isset($_GET['pwchange'])) {
    $error = false;
    $userid = $_SESSION['userid'];
    $email = $_SESSION['email'];
    $passwort = $_POST['passwort'];
    $passwort2 = $_POST['passwort2'];

    $stmt3 = $pdo->prepare("SELECT * FROM users WHERE email = :email");
    $result3 = $stmt3->execute(array('email' => $email));
    $user = $stmt3->fetch();

    //Überprüfung des Passworts
    if ($user !== false && password_verify($passwort, $user['passwort'])) {


        $passwort_hash = password_hash($passwort2, PASSWORD_DEFAULT);

        $stmt2 = $pdo->prepare("UPDATE users SET passwort = :passwort WHERE u_ID = :u_ID");
        $result2 = $stmt2->execute(array('passwort' => $passwort_hash, 'u_ID' => $_SESSION['userid']));
        $_SESSION['passwort'] = $passwort2;
        ?>
        <div class="alert alert-success fade in">
            Das Passwort wurde erfolgreich <strong>geändert</strong>!
        </div>
        <?php
    }
    else {
        $errorMessage = "Passwort war ungültig<br>";
    }
}




if(isset($errorMessage)) {
    ?>
    <div class="alert alert-danger">
        <?php echo $errorMessage; ?>
    </div>
    <?php
}

?>
<div class="container weiß">
    <!-- Passwort ändern -->
    <div class="userchange">
        <form class="form-horizontal" action="?pwchange=1" method="post">
            <fieldset>
                <legend>Passwort ändern</legend>
                <table class="usertable">
                    <tbody>
                    <tr>
                        <td><label>Benutzername:</label></td>
                        <td><input class="inputuser" type="text" id="name" maxlength="30" value="<?php echo($_SESSION['username']) ?>" disabled="disabled"></td>
                    </tr>
                    <tr>
                        <td><label>E-Mail:</label></td>
                        <td><input class="inputuser" type="text" id="name" maxlength="30" value="<?php echo($_SESSION['email']) ?>" disabled="disabled"></td>
                    </tr>
                    <tr>
                        <td><label>altes Passwort: </label></td>
                        <td><input class="passwort" type="password"  maxlength="20" name="passwort" required></td>
                    </tr>
                    <tr>
                        <td><label>neues Passwort: </label></td>
                        <td><input class="passwort" type="password"  maxlength="20" name="passwort2" required></td>
                    </tr>
                    <tr>
                        <td></td>
                        <td style="text-align:right"><input class="btn btn-default" type="submit" value="speichern"></td>
                    </tr>
                    </tbody>
                </table>
            </fieldset>
        </form>
    </div>

    <!-- Benutzerkonto löschen -->
    <div class="userdelete">
        <form class="form-horizontal" action="?userdelete=1" method="post">
            <fieldset>
                <legend>Benutzerkonto löschen</legend>
                <table class="usertable">
                    <tbody>
                    <tr>
                        <td><label>Benutzername:</label></td>
                        <td><input class="inputuser" type="text" id="name" maxlength="30" value="<?php echo($_SESSION['username']) ?>" disabled="disabled"></td>
                    </tr>
                    <tr>
                        <td><label>E-Mail:</label></td>
                        <td><input class="inputuser" type="text" id="name" maxlength="30" value="<?php echo($_SESSION['email']) ?>" disabled="disabled"></td>
                    </tr>
                    <tr>
                        <td><label>Passwort zur Sicherheit eingeben:</label></td>
                        <td><input class="passwort" type="password"  maxlength="20" name="passwort" required></td>
                    </tr>
                    </tbody>
                </table>
                <p class="labeluser">Ich bin damit einverstanden, dass mein Benutzerkonto endgültig gelöscht wird.</p>
                <input class="btn btn-danger" type="submit" value="löschen" style="float:right; margin-right:8px">
            </fieldset>
        </form>
    </div>
</div>
<?php
include("includes/includeFooter.php");
?>
</body>
</html>
