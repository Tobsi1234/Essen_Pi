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

		// Methode aus dem Internet, wo man ein Datum reingibt, um die aktuelle Kalenderwoche als Zahl zu bekommen
		function getWeekNumber(date) {
			// date = new Date(+date);
			date.setHours(0,0,0);
			date.setDate(date.getDate() + 4 - (date.getDay()||7));
			var yearStart = new Date(date.getFullYear(),0,1);
			var weekNo = Math.ceil(( ( (date - yearStart) / 86400000) + 1)/7);
			date.setMilliseconds(0);
			return [weekNo, date.getFullYear()];
		}

		// Methode aus dem Internet, wo man ein Datum reingibt, um die aktuelle Kalenderwoche in Tagen (erster und letzter Tag der Woche) zu bekommen
		function getDaysOfWeek(date) {

			date.setDate(date.getDate()-1);
			var first = (date.getDate() - date.getDay())+1;

			var firstday = new Date(date.setDate(first));
			var lastday = new Date(date.setDate(firstday.getDate()+6));

			return [firstday, lastday];
		}

		// Eigene Funktion, die für das Datum den String so formatiert, dass er der Dropdown-Liste hinzugefügt werden kann
		function getFormattedDropdownString(date) {

			var week = getDaysOfWeek(date);
			var weeknumber = getWeekNumber(date);

			if (weeknumber[0] < 10) weeknumber[0] = '0'+weeknumber[0].toString();
			var weeknumberString = weeknumber[0]+", "+weeknumber[1];


			var firstDay, lastDay, firstMonth, lastMonth;

			// Dieser if-else-Block hängt bei Tagen und Monaten eine Null vornean, wenn sie nicht zweistellig sind (wichtig für das Auslesen in Funktion "datum_refreshen")!
			if (week[0].getDate() < 10) firstDay = "0"+week[0].getDate(); else firstDay = week[0].getDate();
			if (week[1].getDate() < 10) lastDay = "0"+week[1].getDate(); else lastDay = week[1].getDate();
			if ((week[0].getMonth()+1) < 10) firstMonth = "0"+(week[0].getMonth()+1); else firstMonth = (week[0].getMonth()+1);
			if ((week[1].getMonth()+1) < 10) lastMonth = "0"+(week[1].getMonth()+1); else lastMonth = (week[1].getMonth()+1);

			var firstdate = firstDay+"."+firstMonth+".";
			var lastdate = lastDay+"."+lastMonth+".";

			// var dropdownInhalt = "KW "+weeknumberString+" ("+firstdate+" - "+lastdate+")";
			// var dropdownInhalt = firstdate+week[0].getFullYear()+" - "+lastdate+week[1].getFullYear()+" (KW "+weeknumber[0]+")";
			var dropdownInhalt = firstdate+" - "+lastdate+" (KW "+weeknumberString+")";

			return dropdownInhalt;


		}

		// Hilfsfunktion zum Füllen der Dropdown-Liste
		function fülle_dropdown(data) {

			var allDates = JSON.parse(data);

			for (var i = 0; i<allDates.length; i++) {
				var date = new Date(allDates[i]['datum']);
				// alert(date);
				var dropdownInhalt = getFormattedDropdownString(date);
				var exists = false;

				$('#woche > option').each(function() {
					if ($(this).val() === dropdownInhalt) {
						exists = true;
					}
				});

				// Überprüfung, ob Woche bereits in Dropdown-Liste
				if (exists == false) {
					$('#woche').append($('<option>', {
						text: dropdownInhalt
					}));
				}
			}
		}

		// Hilfsfunktion zum Auslesen der Dropdown-Liste und Ausgeben der Abstimmungsergebnisse
		function schreibe_abstimmungsergebnisse(data) {
			var ergebnisse = JSON.parse(data);
			var selectedText = $('#woche :selected').text();

			var year = selectedText.substring(24,28);

			var firstDay = selectedText.substring(0,2);
			var firstMonth = selectedText.substring(3,5);
			var firstDate = new Date(year+"-"+firstMonth+"-"+firstDay);

			var lastDay = selectedText.substring(9,11);
			var lastMonth = selectedText.substring(12,14);
			var lastDate = new Date(year+"-"+lastMonth+"-"+lastDay);

			$('#abstimmungen').html("");

			for (var i = 0; i<ergebnisse.length; i++) {
				var current = ergebnisse[i];
				if (new Date(current['datum']) >= firstDate && new Date(current['datum']) <= lastDate) {
					var cd = new Date(current['datum']);
					var abstimmungen = JSON.parse(current['abstimmungen']);
					var content="";
					if(abstimmungen) {
						abstimmungen.forEach(function (s, i, a) {
							var username = JSON.parse(s)['name'];
							var essen1 = JSON.parse(s)['essen1'];
							var essen2 = JSON.parse(s)['essen2'];
							if (!essen2) content = content.concat(username + " hat " + essen1 + " gewählt. <br>");
							else content = content.concat(username + " hat " + essen1 + " und " + essen2 + " gewählt. <br>");
						});
					}
					//alert(content + cd);

					$('#abstimmungen').append("<br><br>");
					//$('#abstimmungen').append("Ergebnis am "+cd.getDate()+"."+(cd.getMonth()+1)+"."+" von "+current['gruppe']+": <b>"+current['locname']+"</b><br>");
					$('#abstimmungen').append("<a href=\"#\" data-trigger=\"focus\" data-toggle=\"popover\" title=\"Abstimmungen\" data-content=\"" + content +"\" data-html=\"true\">Ergebnis am "+
						cd.getDate()+"."+(cd.getMonth()+1)+"."+" von "+current['gruppe']+": <b>"+current['locname']+"</a>");
					$('[data-toggle="popover"]').popover();
				}
			}
		}

		// schaue nach, was für eine Woche in der Dropdown-Liste steht, hole die entsprechenden Einträge aus der DB	und gib diese formatiert aus
		function datum_refreshen() {

			$.ajax({
				type    : "POST",
				url     : "procedures.php",
				data    : {callFunction: 'getAbstimmungsErgebnisse'},
				dataType: 'text',
				success : function (data) {
					schreibe_abstimmungsergebnisse(data);
				}
			});
		}

		// hole alle Datumswerte, für die es Einträge in der Tabelle "abstimmung_ergebnis" gibt und schreibe diese in die Dropdown-Liste
		function woche_abholen() {

			$.ajax({
				type    : "POST",
				url     : "procedures.php",
				data    : {callFunction: 'getDatesFromAbstimmung'},
				dataType: 'text',
				success : function (data) {
					fülle_dropdown(data);
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
<div class="container weiß" id="container" style="display: none">

	<!-- First Featurette -->
	<div class="featurette" id="about" style="font-size: 17px">
		<br>
		<?php
		if(!isset($_SESSION['g_ID'])) { //noch keine Gruppe?
			?>
			<h1>Herzlich Willkommen auf wir-haben-hunger.ddns.net!</h1>
			<br><br>
			<h2>Um richtig loszulegen, gründe eine Gruppe oder lass dich von Freunden einladen.</h2>

			<?php
		}
		else { //bereits eine Gruppe
			?>
			<div>
				<div class="info">
					<legend>Verlauf</legend>
					Auf dieser Seite werden alle Ergebnisse der Gruppe sowie die einzelnen Abstimmungen der Gruppenteilnehmer angezeigt.
				</div>
				<br>
				<form id="verlauf" name="verlauf" action="" method="post" onsubmit="">
					<label for="woche">Woche auswählen:</label>
					<select id="woche" class="fancyform" name="woche">
					</select>
					<button type="button" class="btn btn-primary" onclick="datum_refreshen();">Anwenden</button>
					<div></div>
					<div class="abstimmungen" id="abstimmungen">
					</div>
					<br><br><br><br>

				</form>

			</div>
			<?php
		} //ende php abfrage
		?>
	</div>
</div>

<script>woche_abholen()</script>
<?php
include ("includes/includeFooter.php");
?>
</body>
</html>
