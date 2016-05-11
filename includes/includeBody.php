<script language="javascript">

	function hideUnterseiten() {
		$('.unterSeiten').hide();
		$('#container').hide();
		$('#loggedOutSeite').show();
	}
	function showUnterseiten() {
		$('.unterSeiten').show();
		$('#container').show();
		$('#loggedOutSeite').hide();
	}

	function logoutchange() {
		var username = "<?php if(isset($_SESSION['userid'])) echo($_SESSION['username']) ?>";
		$('#login-trigger').html(username + ' <span>&#x25BC;</span>');
		$('#login-content').html('<a href="benutzereinstellungen.php">Benutzereinstellungen</a></br></br><a href="logout.php">Logout</a>');
		$('#login-content').css('width', '175px'); 
	}
	function loginalert() {
		alert ("Bitte zuerst einloggen");
		window.location = "index.php";	
	}
</script>
<!DOCTYPE html>
<html lang="de">
<body>
<?php 
if(isset($errorMessage)) {
	?>
	<div class="alert alert-danger">
		<?php echo $errorMessage; ?>
	</div>
<?php
}

?>
<!-- Navigation -->
    <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
        <div class="container">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header pull-left">
				<a class="navbar-brand" href="index.php">Startseite</a>
            </div>
			<div class="navbar-header pull-right">
				<ul class="nav navbar-nav pull-left">
					<li id="login" style="margin-right:15px">
						<a id="login-trigger" href="javascript:;">
							Einloggen <span>&#x25BC;</span>
						</a>
						<div id="login-content">
							<form action="?login=1" method="post">
								<fieldset id="inputs">
									<input id="username" type="email" name="email" placeholder="E-Mail Adresse" required>
									<input id="password" type="password" name="passwort" placeholder="Passwort" required>
								</fieldset>
								<fieldset id="actions">
									<input type="submit" id="submit" value="Einloggen">
									<label><input type="checkbox" checked="checked">Eingeloggt bleiben?</label>
									<label><a href="registrieren.php">Noch nicht registriert? </a></label>
								</fieldset>
							</form>
						</div>
					</li>
				</ul>
				<button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
					<span class="sr-only">Toggle navigation</span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>
			</div>
            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="navbar-collapse collapse pull-left" id="bs-example-navbar-collapse-1">
                <ul class="nav navbar-nav">
                    <li class="unterSeiten" style="display: none">
                        <a href="abstimmung.php">Abstimmung</a>
                    </li>
					<li class="unterSeiten" style="display: none">
                        <a href="locationverwaltung.php">Verwaltung</a>
                    </li>
					<li class="unterSeiten" style="display: none">
                        <a href="verlauf.php">Verlauf</a>
                    </li>
					<li class="unterSeiten" style="display: none">
                        <a href="gruppeneinstellungen.php">Meine Gruppe</a>
                    </li>
				</ul>
				<!-- /.navbar-collapse -->
			</div>

        </div>
        <!-- /.container -->
    </nav>  
	<?php
			
		//Prüfen ob eingeloggt um Statuswechsel beim Login Feld zu machen.
		if(isset($_SESSION['userid'])) {
			echo '<script language="javascript">logoutchange();</script>';
		}	
			
		$url = $_SERVER['REQUEST_URI'];
		$checkLogin = false;

		$pagesToCheck = array('0' => "abstimmung.php", '1' => "locationverwaltung.php", '2' => "gruppeneinstellungen.php", '3' =>"benutzereinstellungen.php", '4' => "verlauf.php");

		// Das Array und die Abfrage sorgen dafür, dass der Login-Check nur bei den obigen Seiten ausgeführt wird
		// Das bedeutet: Jede vom Benutzer aufrufbare Seite, bei dem er angemeldet sein muss, gehört in das Array rein!
		$checkLogin = false;
		foreach($pagesToCheck as $value) {
			if (strpos($url, $value) !== false) {
				$checkLogin = true;
			}
		}

		if ($checkLogin) {
			if(!isset($_SESSION['userid'])) {
				die('<script language="javascript">loginalert();</script>');
			}

		}
	?>
</body>
</html>

