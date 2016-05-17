<?php
session_start();
require("includes/includeDatabase.php");
?>
<!DOCTYPE html>
<html lang="de">
<head>

	<?php
	include ("includes/includeHead.php");
	?>

	<script language="javascript">
		<!--
		function emailPrüfen() {
			var email = $('#mitglied').val();
			$.ajax({
				type: "POST",
				url: "procedures.php",
				data: {callFunction: 'emailPrüfen', email: email},
				dataType: 'text',
				success:function(data) {
					mitgliedHinzufügen(data);
				}
			});
		}

		var mitglieder = new Array();
		function mitgliedHinzufügen(name) {
			if(name) {
				$('#bisherHinzugefügt').append(name + " ");
				mitglieder[mitglieder.length] = name;
				$('#fehlermeldung').hide();
				$('#mitglied').val("");
				$('#freunde_einladen').prop('disabled', false);
			}
			else {
				$('#fehlermeldung').html("Person nicht vorhanden oder bereits in einer Gruppe vertreten");
				$('#fehlermeldung').show();
			}
		}

		function gruppeErstellen() {
			var u_ID = "<?php echo $_SESSION['userid'] ?>";
			var name = $('#gruppenname').val();
			var jsonMitglieder = JSON.stringify(mitglieder);
			$.ajax({
				type: "POST",
				url: "procedures.php",
				data: {callFunction: 'gruppeErstellen', name: name, u_ID: u_ID, json: jsonMitglieder},
				dataType: 'text',
				success:function(data) {
					window.location.reload();
				}
			});
		}

		//ohne weiterleitung (wenn man schon in einer gruppe ist)
		function emailPrüfen2() {
			var mitglied;
			var email = $('#mitglied').val();
			$.ajax({
				type: "POST",
				url: "procedures.php",
				data: {callFunction: 'emailPrüfen', email: email},
				dataType: 'text',
				success:function(data) {
					mitglied = data;
					mitgliedHinzufügen2(mitglied);
				}
			});
		}

		function mitgliedHinzufügen2(mitglied) {
			if(mitglied) {
				$('#fehlermeldung').hide();
				$.ajax({
					type: "POST",
					url: "procedures.php",
					data: {callFunction: 'mitgliedHinzufügen', mitglied: mitglied},
					dataType: 'text',
					success:function(data) {
						window.location.reload();
					}
				});
			}
			else {
				$('#fehlermeldung').html("Person nicht vorhanden oder bereits in einer Gruppe vertreten");
				$('#fehlermeldung').show();
			}

		}

		function austreten() {
			var u_ID = "<?php echo $_SESSION['userid'] ?>";
			$.ajax({
				type: "POST",
				url: "procedures.php",
				data: {callFunction: 'austreten', u_ID: u_ID},
				dataType: 'text',
				success:function(data) {
					window.location.reload();
				}
			});
		}
		-->
	</script>
</head>
<body>

<?php
include ("includes/includeBody.php");
?>
<?php
$stmt1 = $pdo->prepare("SELECT g_ID FROM users WHERE u_ID = :u_ID");
$stmt1->execute(array('u_ID' => $_SESSION['userid']));
$g_ID = $stmt1->fetch();
if(!isset($g_ID[0])) echo "";
else {
	$stmt2 = $pdo->prepare("SELECT name FROM gruppe WHERE g_ID = :g_ID");
	$stmt2->execute(array('g_ID' => $g_ID[0]));
	$gruppenname = $stmt2->fetch();
	//echo "Deine Gruppe: " . $gruppenname[0];
}
?>

<!-- Page Content -->
<div class="container weiß">
	<div class="alert alert-danger" id="fehlermeldung" style="display:none">
	</div>
	<!-- First Featurette -->
	<div class="featurette" id="about" style="font-size: 17px">
		<br>
		<?php
		if(!isset($g_ID[0])) { //noch keine Gruppe?
			?>
			<div class="info">
				<legend>Gruppeneinstellungen</legend>
				Auf dieser Seite kann eine Gruppe angelegt und Mitglieder hinzugefügt werden.
			</div>
			<br>
			<div class="form-horizontal">
				<form class="form-inline" id="formAnlegen" name="formAnlegen" action="" onsubmit="" method="post">
					<label for="gruppenname"> Gruppenname: </label>
					<input class="form-control" type="text" id="gruppenname" maxlength="30" placeholder="Gruppenname" style="margin-left:20px" required><br><br>
					<label>Freunde einladen: </label>
					<input class="form-control" type="text" id="mitglied" maxlength="30" placeholder="E-Mail" style="margin-left:20px">
					<button type="button" class="btn btn-default" onclick="emailPrüfen();">Hinzufügen</button><br><br>
					<div id="bisherHinzugefügt">
						<label>Bisher hinzugefügt: </label>
					</div><br>
					<button type="button" class="btn btn-primary" onclick="gruppeErstellen();">Gruppe erstellen</button>
				</form>
				<br><br>
			</div>

			<?php
		}
		else { //bereits eine Gruppe
			?>
			<div class="info">
				<legend>Gruppeneinstellungen</legend>
				Füge Gruppenmitglieder zu deiner Gruppe hinzu oder trete aus deiner aktuellen Gruppe aus. Verlässt der Gruppenadmin eine Gruppe, wird die Gruppe gelöscht.
			</div>
			<br>
			<label>Deine Gruppe:</label> <?php echo $gruppenname[0];?><br>
			<label>Gruppenmitglieder: </label>
			<div id="gruppenmitglieder">
				<?php
				$stmt1 = $pdo->prepare("SELECT username, u_ID FROM users WHERE g_ID = :g_ID");
				$stmt1->execute(array('g_ID' => $g_ID[0]));
				$stmt2 = $pdo->prepare("SELECT u_ID FROM gruppe WHERE g_ID = :g_ID");
				$stmt2->execute(array('g_ID' => $g_ID[0]));
				$admin = $stmt2->fetch();
				foreach ($stmt1->fetchAll(PDO::FETCH_ASSOC) as $row1){
					if($row1['u_ID'] == $admin[0]) echo $row1['username'] . " (admin) <br>";
					else echo $row1['username'] . "<br>";
				}

				?>
			</div>
			<br>
			<div class="form-horizontal">
				<form class="form-inline" id="" name="" action="" onsubmit="" method="">
					<label>Freunde einladen: </label>
					<input class="form-control" type="text" id="mitglied" maxlength="30" placeholder="E-Mail" style="margin-left:20px">
					<button type="button" class="btn btn-default" onclick="emailPrüfen2();">Hinzufügen</button><br><br>
					<button type="button" class="btn btn-danger" onclick="austreten();">Austreten</button>
				</form>
				<br><br>
			</div>

			<?php
		} //ende php abfrage
		?>

	</div>

</div>

<?php
include ("includes/includeFooter.php");
?>
</body>
</html>
