<?php 
session_start();
require('includes/includeDatabase.php');
include ("includes/includeHead.php");
?>
<!DOCTYPE html> 
<html> 
<head>
  <title>Registrierung</title>	
</head> 
<body>

<?php
include ("includes/includeBody.php");


	
$showFormular = true; //Variable ob das Registrierungsformular anezeigt werden soll
 
if(isset($_GET['register'])) {
	$error = false;
	$username = htmlspecialchars($_POST['name']);
	$email = $_POST['email'];
	$passwort = $_POST['passwort'];
	$passwort2 = $_POST['passwort2'];
  
	if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
		?>
		<div class="alert alert-danger">
			Bitte eine gültige E-Mail-Adresse eingeben<br>
		</div>
		<?php
		$error = true;
	} 	
	if($passwort != $passwort2) {
		?>
		<div class="alert alert-danger">
			Die Passwörter müssen übereinstimmen<br>
		</div>
		<?php
		$error = true;
	}
	
	//Überprüfe, dass die E-Mail-Adresse noch nicht registriert wurde
	if(!$error) { 
		$statement = $pdo->prepare("SELECT * FROM users WHERE email = :email");
		$result = $statement->execute(array('email' => $email));
		$user = $statement->fetch();
		
		if($user !== false) {
			?>
			<div class="alert alert-danger">
				Diese E-Mail-Adresse ist bereits vergeben<br>
			</div>
			<?php
			$error = true;
		}	
	}
	
	//Überprüfe, dass der Benutzername noch nicht registriert wurde
	if(!$error) { 
		$statement = $pdo->prepare("SELECT * FROM users WHERE username = :username");
		$result = $statement->execute(array('username' => $username));
		$user = $statement->fetch();
		
		if($user !== false) {
			?>
			<div class="alert alert-danger">
				Diesr Benutzername ist bereits vergeben<br>
			</div>
			<?php
			$error = true;
		}	
	}
	
	//Keine Fehler, wir können den Nutzer registrieren
	if(!$error) {	
		$passwort_hash = password_hash($passwort, PASSWORD_DEFAULT);
		
		$statement = $pdo->prepare("INSERT INTO users (username, email, passwort) VALUES (:username, :email, :passwort)");
		$result = $statement->execute(array('username' => $username, 'email' => $email, 'passwort' => $passwort_hash));
		
		if($result) {		
			//echo 'Du wurdest erfolgreich registriert. <a href="index.php">Zur Startseite</a>';
			$showFormular = false;

			$url = "mail.php?email=".$email."&username=".$username."&passwort=".$passwort_hash;
			?>
			<script>var url = "<?php echo $url ?>"</script>
			<?php
			echo "<script type='text/javascript'>window.location.href = url</script>";
			?>
			<div class="alert alert-success fade in">
  				Dir wurde gerade eine E-Mail mit einem Aktivierungslink geschickt. Bitte klicke auf diesen Link. <strong>registriert</strong>! <a href="index.php">Zur Startseite</a>
                <!-- <meta http-equiv="refresh" content="5; URL=index.php"> -->
			</div>
            <?php
		} else {
			?>
			<div class="alert alert-danger">
				Beim Abspeichern ist leider ein Fehler aufgetreten<br>
			</div>
			<?php
		}
	} 
}
 
if($showFormular) {
?>
<div class="userchange weiß" id="register">
    <form class="form-horizontal" action="?register=1" method="post">
	<div class="form-group">
		<label class="col-lg-4"> Benutzername: </label>
		<div class="col-lg-8">
			<input class="form-control" type="text" maxlength="30" name="name" required>
		</div>
	</div>
	<div class="form-group">
		<label class="col-lg-4"> E-Mail: </label>
		<div class="col-lg-8">
			<input class="form-control" type="email" maxlength="50" name="email" required>
		</div>
	</div>
    <div class="form-group">
		<label class="col-lg-4">Passwort: </label>
		<div class="col-lg-8">
			<input class="form-control" type="password"  maxlength="20" name="passwort" required>
		</div>
	</div>
    <div class="form-group">
		<label class="col-lg-4">Passwort wiederholen: </label>
		<div class="col-lg-8">
			<input class="form-control" type="password"  maxlength="20" name="passwort2" required>
		</div>
	</div>
    <input class="btn btn-default" type="submit" value="Abschicken">
    </form>
</div> 
<?php
} //Ende von if($showFormular)

	include ("includes/includeFooter.php");
	?>
 
</body>
</html>