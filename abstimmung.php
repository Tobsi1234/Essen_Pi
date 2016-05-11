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
	var name, refEssen, refDatum, refNeu, refMenu1, refVerfügbar, selectedEssen, essen, heute, tag, monat, jahr, datum_heute;
	var nichtTop3Essen = [];
	function name_ausgeben() {
		name = "<?php echo $_SESSION['username'] ?>";
		u_ID = "<?php echo $_SESSION['userid'] ?>";

		//alert("Hallo " + name);
	}
	
	function form_name() {
		document.getElementById('name').value = name;
	}
	
	function form_essen() {
		
		var essenArr = [];
		refEssen = document.forms['form1'].essen;
		essenArr = checkboxWert(refEssen);
		var datum = jahr + "-" + monat + "-" + tag;
		if(essenArr.length > 0 && essenArr.length < 3) {
			if(essenArr.length == 1) {
				$.ajax({
					type: "POST",
					url: "procedures.php",
					data: {callFunction: 'abstimmen', u_ID: u_ID, essen1: essenArr[0], datum: datum},
					dataType: 'text',
					success:function(data) {
						window.location.reload();
					}
				});
			}
			else {
			$.ajax({
					type: "POST",
					url: "procedures.php",
					data: {callFunction: 'abstimmen', u_ID: u_ID, essen1: essenArr[0], essen2: essenArr[1], datum: datum},
					dataType: 'text',
					success:function(data) {
						window.location.reload();
					}
				});
			}

			//alert(name + " hat " + essenArr[0] + " und " + essenArr[1] + " gewählt. Danke!");
		}

		return false; 
	}
	
	function checkboxWert(rObj) {
		//gibt die ausgewählten Checkboxen zurück
		var arr = []
		for (var i=0; i<rObj.length; i++) {
			if (rObj[i].checked) {
				if(rObj[i].value == "Sonstiges1") {
					arr[arr.length] = $('#verfuegbare_essen option:selected').text();
				}
				else if(rObj[i].value == "Sonstiges2") {
					arr[arr.length] = $('#verfuegbare_essen2 option:selected').text();
				}
				else {
					arr[arr.length] = rObj[i].value;
				}
			}
		}
		if ($('#doppelt').is(':checked')) {
			arr[1] = arr[0];
		}
		return arr;
	}
	
	// zeigt zweiten Selector nur an, wenn erster bereits ausgewählt wurde
	function validate(){

		if ($('#sonstiges1').is(':checked') && !$('#top1').is(':checked') && !$('#top2').is(':checked') && !$('#top3').is(':checked') && !$('#doppelt').is(':checked')){
			$('#sonstiges2').css('display', 'inline-block');
			$('#verfuegbare_essen2').css('display','inline-block');

		}else{
			$('#sonstiges2').css('display', 'none');
			$('#sonstiges2').prop('checked', false);
			$('#verfuegbare_essen2').css('display','none');
		}
	}

	function coordinateSelects(selectBox) {

		if (selectBox === 'verfuegbare_essen' || selectBox === 'start') {
			var tmp = $('#verfuegbare_essen2 option:selected').val();
			document.getElementById('verfuegbare_essen2').length = 0;
			for (var i = 0; i < nichtTop3Essen.length; i++) {
				if (nichtTop3Essen[i] !== $('#verfuegbare_essen option:selected').text()) {
					//alert("Vergleiche "+nichtTop3Essen[i].replace(/ /g,'')+" mit "+($('#verfuegbare_essen option:selected').val()).replace(/ /g,''));
					$('#verfuegbare_essen2').append("<option value=" + nichtTop3Essen[i] + ">" + nichtTop3Essen[i] + "</option>");
				}
			}
			if (selectBox === 'verfuegbare_essen')
				$("#verfuegbare_essen2").val(tmp);
		}


		if ((selectBox === 'verfuegbare_essen2') || (selectBox === 'start' && !$('#sonstiges1').is(':checked')) ) {
			tmp = $('#verfuegbare_essen option:selected').val();
			document.getElementById('verfuegbare_essen').length = 0;
			for (var i = 0; i < nichtTop3Essen.length; i++) {
				if (nichtTop3Essen[i] !== $('#verfuegbare_essen2 option:selected').text())
					$('#verfuegbare_essen').append("<option value=" + nichtTop3Essen[i] + ">" + nichtTop3Essen[i] + "</option>");
			}
			$('#verfuegbare_essen').val(tmp);
		}

	}

	function countCheckboxes() {
		var counter = 0;
		if ($('#top1').is(':checked')) counter += 1;
		if ($('#top2').is(':checked')) counter += 1;
		if ($('#top3').is(':checked')) counter += 1;
		if ($('#sonstiges1').is(':checked')) counter += 1;
		if ($('#sonstiges2').is(':checked')) counter += 1;
		if ($('#doppelt').is(':checked')) counter += 1;

		// Button disablen, wenn zu wenig bzw. zu viel Auswahl da ist oder wenn man zweimal dasselbe Essen wählt
		if(counter > 0 && counter < 3 && !(counter === 1 && $('#doppelt').is(':checked')))  {
			$('#auswahl_speichern').prop('disabled', false);
		}
		else $('#auswahl_speichern').prop('disabled', true);

		if (counter === 1 || $('#doppelt').is(':checked'))  $('#doppelt').prop('disabled', false);
		else $('#doppelt').prop('disabled', true);

	}

	function top3() {

		$.ajax({
			type: "POST",
			url: "procedures.php",
			data: {callFunction: 'top3'},
			dataType: 'text',
			success:function(data) {
				var top3Array = JSON.parse(data);
				if(top3Array.length > 0) {
					var top1 = top3Array[0];
					$('#top1').val(top1);
					$('#label_top1').html(top1);
					$('#top1').css('display', 'inline-block');
					$('#label_top1').css('display', 'inline-block');
				}
				if(top3Array.length > 1) {
					var top2 = top3Array[1];
					$('#top2').val(top2);
					$('#label_top2').html(top2);
					$('#top2').css('display', 'inline-block');
					$('#label_top2').css('display', 'inline-block');
				}
				if(top3Array.length > 2) {
					var top3 = top3Array[2];
					$('#top3').val(top3);
					$('#label_top3').html(top3);
					$('#top3').css('display', 'inline-block');
					$('#label_top3').css('display', 'inline-block');
				}
				verfuegbare_essen();
			}
		});
	}

	function verfuegbare_essen() {
		$.ajax({
			type: "POST",
			url: "procedures.php",
			data: {callFunction: 'verfuegbare_essen'},
			dataType: 'text',
			success: function (data) {
				var verfuegbare_essen = JSON.parse(data);
				for(var i=0; i<verfuegbare_essen.length; i++) {
					$('#verfuegbare_essen').append("<option value=" + verfuegbare_essen[i] + ">" + verfuegbare_essen[i] + "</option>");
					$('#verfuegbare_essen2').append("<option value=" + verfuegbare_essen[i] + ">" + verfuegbare_essen[i] + "</option>");
					nichtTop3Essen[i] = verfuegbare_essen[i];
				}
			}
		});
	}
	
	function f_datum_heute() {
		heute = new Date();
		tag = heute.getDate();
		monat = heute.getMonth() + 1;
		jahr = heute.getFullYear();
		datum_heute = tag + "." + monat + "." + jahr;
		//refDatum = document.getElementById('datum');
		//refDatum.innerHTML = datum_heute;
		refMenu1 = document.getElementById('menu1');
		refMenu1.innerHTML = datum_heute;
		return false;
	}
	function f_datum_morgen() {
		heute = new Date();
		heute.setDate(heute.getDate() + 1);
		tag = heute.getDate();
		monat = heute.getMonth() + 1;
		jahr = heute.getFullYear();
		datum_morgen = tag + "." + monat + "." + jahr;
		//refDatum = document.getElementById('datum');
		//refDatum.innerHTML = datum_heute;
		refMenu1 = document.getElementById('menu1');
		refMenu1.innerHTML = datum_morgen;
		return false;
	}
	function f_datum_uebermorgen() {
		heute = new Date();
		heute.setDate(heute.getDate() + 2);
		tag = heute.getDate();
		monat = heute.getMonth() + 1;
		jahr = heute.getFullYear();
		datum_uebermorgen = tag + "." + monat + "." + jahr;
		//refDatum = document.getElementById('datum');
		//refDatum.innerHTML = datum_heute;
		refMenu1 = document.getElementById('menu1');
		refMenu1.innerHTML = datum_uebermorgen;
		return false;
	}
	
	function f_datum() {
		heute = new Date();
		tag = heute.getDate();
		monat = heute.getMonth() + 1;
		jahr = heute.getFullYear();
		datum_heute = tag + "." + monat + "." + jahr;
		refDatum = document.getElementById('datum');
		//refDatum.innerHTML = datum_heute;
		return false;
	}
	
--> 
</script>
</head>
<body>
<?php
	include ("includes/includeBody.php");
?>

	<script> f_datum();</script>

    <!-- Page Content -->
    <div class="container weiß">
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
        <!-- First Featurette -->
        <div class="featurette" id="about">
		 	<br><br>
			<script> name_ausgeben(); </script>
			<?php
			if(!isset($g_ID[0])) { //noch keine Gruppe?
				?>
				<h1>Bitte gründe eine Gruppe!</h1>

				<?php
			}
			else { //bereits eine Gruppe
			?>
			<div class="col-md-7">
				<div id="headline">
				<h1>Abstimmung </h1><br>
				</div>
				<div style="float:left; width:30px;"><label>Datum: </label>
				</div>
				<div class="dropdown" style="margin-left:68px;">
					<button class="btn btn-default dropdown-toggle" id="menu1" type="button" data-toggle="dropdown">Datum
					<span class="caret"></span></button>
					<ul class="dropdown-menu" role="menu" aria-labelledby="menu1">
						<li role="presentation"><a role="menuitem" tabindex="-1" href="javascript:void(0)" onclick="f_datum_heute();">Heute</a></li>
						<li role="presentation"><a role="menuitem" tabindex="-1" href="javascript:void(0)" onclick="f_datum_morgen();">Morgen</a></li>
						<li role="presentation"><a role="menuitem" tabindex="-1" href="javascript:void(0)" onclick="f_datum_uebermorgen();">Übermorgen</a></li>
					</ul>
				</div>
				<br>
				<script> f_datum_heute();</script>
				<form class="form-inline" id="form1" name="form1" action="" method="post" onsubmit="form_essen(); return false;">
					<div class="form-group">
						<label for="name"> Name: </label>
						<input class="form-control" type="text" id="name" maxlength="30" value="Name" style="margin-left:20px;" disabled>
					</div>
					<script> form_name(); </script> <br><br>
					<br>
					<div>
						<h3><b>---------------Wähle bis zu zwei Essen---------------</b></h3>
					</div>
					<br>
					<label for=""> Top 3 Essen: </label>
					<input class="form-control" onclick="validate(); countCheckboxes();" type="checkbox" id="top1" name="essen" value="top1" style="margin-left:15px; display:none"> <label id="label_top1" for="top1" style="display:none">Top1 </label>
					<input class="form-control" onclick="validate(); countCheckboxes();" type="checkbox" id="top2" name="essen" value="top2" style="margin-left:15px; display:none"> <label id="label_top2" for="top2" style="display:none">Top2 </label>
					<input class="form-control" onclick="validate(); countCheckboxes();" type="checkbox" id="top3" name="essen" value="top3" style="margin-left:15px; display:none"> <label id="label_top3" for="top3" style="display:none">Top3 </label>
					<br><label for=""> Weitere Essen: </label>
					<input class="form-control" onclick="validate(); countCheckboxes(); coordinateSelects('start');" type="checkbox" id="sonstiges1" name="essen" value="Sonstiges1" style="margin-left:15px"> <label for=""></label>

					<select class="form-control" id="verfuegbare_essen" onchange="validate(); coordinateSelects($(this).attr('id')); countCheckboxes();">
					</select>

					<input class="form-control" onclick="validate(); countCheckboxes();" type="checkbox" id="sonstiges2" name="essen" value="Sonstiges2" style="margin-left:15px; display:none;" >

					<select class="form-control" id="verfuegbare_essen2" onchange="validate(); coordinateSelects($(this).attr('id')); countCheckboxes();" style="display:none">
					</select>
					<br>
					<input class="form-control" onclick="validate(); countCheckboxes();" type="checkbox" id="doppelt" name="" value="doppelt" style="" title="Doppelt abstimmen (nur mit einem Essen kombinierbar)"> <label id="label_doppelt" for="doppelt" style=""  title="Doppelt abstimmen (nur mit einem Essen kombinierbar)">Essen doppelt gewichten</label>
					<br><br>
					<button type="submit" id="auswahl_speichern" class="btn btn-primary" disabled>Auswahl speichern</button>
				</form>
				<script>top3();</script>

				<br><br>
			</div>
			<div class="col-md-4 col-md-offset-1">
				<div class = "panel panel-primary" id="chat_border">
					<div class="panel-heading">Chat</div>
					<div class="panel-body">
						<div id="chat_ausgabe"></div>
						<div id="chat_eingabe" style="margin-left: 10px">
							<form class="form-inline" role="form" id="formChat" name="formChat" action="" method="post" onsubmit="chat_speichern(); return false;">
								<input class="form-control" id="nachricht" type="text" placeholder="schreiben..."/>
								<button class="btn btn-dafualt" type="submit">Senden</button>
							</form>
						</div>
					</div>
				</div>
			</div>
			<br><br>
		</div>
		<script>
			chat_laden(); // läd chat jede sekunde neu.
			chat_verspätet();
		</script>
		<?php
		} //ende php abfrage
		?>
	<br><br>

</div>
<div id="loggedOutSeite" style="display: none">
	<h1> Willkommen </h1>
	<h2> Bitte logge dich ein :) </h2>
</div>

<?php
	include ("includes/includeFooter.php");
?>
</body>
</html>
