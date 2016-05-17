<?php

if (!isset($_SESSION)) {
	session_start();
}

require('includes/includeDatabase.php');

if (isset($_POST['callFunction'])) {
	switch ($_POST['callFunction']) {

		case 'insertLocation':
			insertLocation(htmlspecialchars($_POST['p1']), $_POST['p2'], $_POST['p3']);
			break;

		case 'abstimmen':
			if(isset($_POST['essen2']))	abstimmen($_POST['essen1'], $_POST['essen2'], $_POST['datum']);
			else abstimmen($_POST['essen1'], "", $_POST['datum']);
			break;

		case 'insertEssen':
			insertEssen(htmlspecialchars($_POST['p1']));
			break;

		case 'reloadEssen':
			reloadEssen();
			break;

		case 'emailPrüfen':
			emailPrüfen($_POST['email']);
			break;

		case 'gruppeErstellen':
			gruppeErstellen($_POST['name'], $_POST['u_ID'], $_POST['json']);
			break;

		case 'austreten':
			austreten($_POST['u_ID']);
			break;

		case 'getLocations':
			getLocations();
			break;

		case 'getEssen':
			getEssen();
			break;

		case 'mitgliedHinzufügen':
			mitgliedHinzufügen($_POST['mitglied']);
			break;

		case 'getDatesFromAbstimmung':
			getDatesFromAbstimmung();
			break;

		case 'getAbstimmungsErgebnisse':
			getAbstimmungsErgebnisse();
			break;

		case 'load_page':
			load_page($_POST['page']);
			break;

		case 'getAbstimmungenHeute':
			getAbstimmungenHeute();
			break;

		case 'calculateErgebnisHeute':
			calculateErgebnisHeute();
			break;

		case 'top3':
			top3();
			break;
		case 'verfuegbare_essen':
			verfuegbare_essen();
			break;
		case 'getAbstimmungen':
			getAbstimmungen($_POST['datum']);
			break;
		case 'chat_laden':
			chat_laden();
			break;
		case 'chat_speichern':
			chat_speichern(htmlspecialchars($_POST['nachricht']));
			break;
		case 'chat_delete':
			chat_delete();
			break;
		default:
			echo "Keine Funktion zum Aufrufen gefunden!";
			break;
	}
}

function insertLocation($locname, $locpage, $locessen) {

	global $pdo;
	$pdolocal = $pdo;

	require('includes/includeDatabase.php');

	$sqlInsLoc = $pdolocal->prepare("INSERT INTO location (name, link, u_ID) VALUES (:locname, :locpage, :userid)");
	$sqlInsLocRes = $sqlInsLoc->execute(array('locname' => $locname, 'locpage' => $locpage, 'userid' => $_SESSION['userid']));

	$sqlInsLocEssen = $pdolocal->prepare("INSERT INTO locessen (l_ID, e_ID) VALUES (:l_ID, :e_ID)");

	$sqlGetLocId = $pdolocal->prepare("SELECT l_ID FROM location WHERE name = :locname AND link = :locpage AND u_ID = :userid");
	$sqlGetLocId->execute(array('locname' => $locname, 'locpage' => $locpage, 'userid' => $_SESSION['userid']));
	$sqlGetLocIdRes = $sqlGetLocId->fetch();

	foreach ($locessen as $value) {

		$sqlGetEssenId = $pdolocal->prepare("SELECT e_ID FROM essen WHERE name = :essenName");
		$sqlGetEssenId->execute(array('essenName' => $value));
		$sqlGetEssenIdRes = $sqlGetEssenId->fetch();

		$sqlInsLocEssenRes = $sqlInsLocEssen->execute(array(':l_ID' => $sqlGetLocIdRes['l_ID'], ':e_ID' => $sqlGetEssenIdRes['e_ID']));
	}

	echo $locname;
}

function insertEssen($essenName) {

	global $pdo;
	$pdolocal = $pdo;

	require('includes/includeDatabase.php');

	$sqlInsEssen = $pdolocal->prepare("INSERT INTO essen (name, u_ID) VALUES (:essenName, :userid)");
	$sqlInsEssenRes = $sqlInsEssen->execute(array('essenName' => $essenName, 'userid' => $_SESSION['userid']));

	header("Location: locationverwaltung.php");
}

function reloadEssen() {

	global $pdo;
	$pdolocal = $pdo;

	$sqlSelEssen = $pdolocal->prepare("SELECT name FROM essen");
	$sqlSelEssen->execute();
	$sqlSelEssenRes = $sqlSelEssen->fetchAll();

	echo json_encode($sqlSelEssenRes);
}

function abstimmen($essen1, $essen2, $datum) {
	require('includes/includeDatabase.php');

	/*	$stmt1 = $pdo->prepare("INSERT INTO person (name) VALUES (:name)");
	$stmt1->execute(array('name' => $name));

	$stmt2 = $pdo->prepare("SELECT p_ID FROM person WHERE name = :name");
	$stmt2->execute(array('name' => $name));
	$p_ID = $stmt2->fetch();

	$stmt5 = $pdo->prepare("INSERT INTO essen (name) VALUES (:name)");
	$stmt5->execute(array('name' => $essen1)); */

	$stmt6 = $pdo->prepare("SELECT e_ID FROM essen WHERE name = :name");
	$stmt6->execute(array('name' => $essen1));
	$e_ID1 = $stmt6->fetch();

	if($essen2 != "") {
		$stmt7 = $pdo->prepare("INSERT INTO essen (name) VALUES (:name)");
		$stmt7->execute(array('name' => $essen2));

		$stmt8 = $pdo->prepare("SELECT e_ID FROM essen WHERE name = :name");
		$stmt8->execute(array('name' => $essen2));
		$e_ID2 = $stmt8->fetch();
	}

	if($essen2 == "") {
		$stmt9 = $pdo->prepare("INSERT INTO abstimmen (u_ID, datum, e_ID1, g_ID) VALUES (:u_ID, :datum, :e_ID1, :g_ID)");
		$stmt9->execute(array('u_ID' => $_SESSION['userid'], 'datum' => $datum, 'e_ID1' => $e_ID1[0], 'g_ID' => $_SESSION['g_ID']));

		$stmt10 = $pdo->prepare("UPDATE abstimmen SET e_ID1 = :e_ID1, e_ID2 = null, g_ID = :g_ID WHERE datum = :datum AND u_ID = :u_ID");
		$stmt10->execute(array('u_ID' => $_SESSION['userid'], 'datum' => $datum, 'e_ID1' => $e_ID1[0], 'g_ID' => $_SESSION['g_ID']));
	}
	else {
		$stmt9 = $pdo->prepare("INSERT INTO abstimmen (u_ID, datum, e_ID1, e_ID2, g_ID) VALUES (:u_ID, :datum, :e_ID1, :e_ID2, :g_ID)");
		$stmt9->execute(array('u_ID' => $_SESSION['userid'], 'datum' => $datum, 'e_ID1' => $e_ID1[0], 'e_ID2' => $e_ID2[0], 'g_ID' => $_SESSION['g_ID']));

		$stmt10 = $pdo->prepare("UPDATE abstimmen SET e_ID1 = :e_ID1, e_ID2 = :e_ID2, g_ID = :g_ID  WHERE datum = :datum AND u_ID = :u_ID");
		$stmt10->execute(array('u_ID' => $_SESSION['userid'], 'datum' => $datum, 'e_ID1' => $e_ID1[0], 'e_ID2' => $e_ID2[0], 'g_ID' => $_SESSION['g_ID']));
	}
}

function emailPrüfen($email) {

	require('includes/includeDatabase.php');

	$stmt1 = $pdo->prepare("SELECT username FROM users WHERE email = :email AND g_ID IS NULL"); //gibt es benutzer und ist er noch frei?
	$stmt1->execute(array('email' => $email));
	$email = $stmt1->fetch();
	//if(!isset($email[0])) echo "null";
	echo $email[0];

}

function gruppeErstellen($name, $u_ID, $json) {

	require('includes/includeDatabase.php');
	$mitglieder = json_decode($json, TRUE);

	$stmt1 = $pdo->prepare("INSERT INTO gruppe (name, u_ID) VALUES (:name, :u_ID)"); //Gruppenname + Admin
	$stmt1->execute(array('name' => $name, 'u_ID' => $u_ID));

	$stmt2 = $pdo->prepare("SELECT g_ID FROM gruppe WHERE u_ID = :u_ID");
	$stmt2->execute(array('u_ID' => $u_ID));
	$g_ID = $stmt2->fetch();

	$stmt3 = $pdo->prepare("UPDATE users SET g_ID = :g_ID WHERE u_ID = :u_ID"); //User Gruppe zuweisen
	$stmt3->execute(array('g_ID' => $g_ID[0], 'u_ID' => $u_ID));

	for($i=0; $i<count($mitglieder); $i++) {
		$stmt1 = $pdo->prepare("UPDATE users SET g_ID = :g_ID WHERE username = :username"); //anderen Usern Gruppe zuweisen
		$stmt1->execute(array('g_ID' => $g_ID[0], 'username' => $mitglieder[$i]));
	}
}

function austreten($u_ID) {
	require('includes/includeDatabase.php');

	$stmt1 = $pdo->prepare("SELECT g_ID FROM gruppe WHERE u_ID = :u_ID"); //ist User admin?
	$stmt1->execute(array('u_ID' => $u_ID));
	$g_ID = $stmt1->fetch();

	if($g_ID[0] != "") {
		$stmt2 = $pdo->prepare("DELETE FROM gruppe WHERE g_ID = :g_ID"); //wenn ja, lösch die ganze Gruppe
		$stmt2->execute(array('g_ID' => $g_ID[0]));
	}

	$stmt3 = $pdo->prepare("UPDATE users SET g_ID = NULL WHERE u_ID = :u_ID"); //lösch die verlinkung des users auf die Gruppe
	$stmt3->execute(array('u_ID' => $u_ID));

}

function getLocations(){
	require('includes/includeDatabase.php');

	$stmt1 = $pdo->prepare("SELECT * FROM location ORDER BY name ASC");
	$stmt1->execute();
	foreach ($stmt1->fetchAll(PDO::FETCH_ASSOC) as $row1) {
		$stmt2 = $pdo->prepare("SELECT name FROM locessen, essen WHERE l_ID = :l_ID AND locessen.e_ID = essen.e_ID ORDER BY name ASC");
		$stmt2->execute(array('l_ID' => $row1['l_ID']));
		$essen = null;
		foreach ($stmt2->fetchAll(PDO::FETCH_ASSOC) as $row2) {
			$essen[] = $row2['name'];
		}
		if ($row1['link']) {
			if (strpos($row1['link'], 'http') !== false) $link = $row1['link'];
			else $link = "http://" . $row1['link'];
		}
		else $link = "";
		$location = array(
			"name" => $row1['name'],
			"link" => $link,
			"essen" => $essen
		);
		$arr[] = json_encode($location);
	}

	print json_encode($arr);
}

function getEssen(){
	require('includes/includeDatabase.php');

	$stmt1 = $pdo->prepare("SELECT name FROM essen ORDER BY name ASC");
	$stmt1->execute();
	foreach ($stmt1->fetchAll(PDO::FETCH_ASSOC) as $row){
		$arr[] = $row['name'];
	}

	print json_encode($arr);
}

function mitgliedHinzufügen($mitglied) {
	require('includes/includeDatabase.php');

	$g_ID = $_SESSION['g_ID'];

	$stmt2 = $pdo->prepare("UPDATE users SET g_ID = :g_ID WHERE username = :username"); //Usern Gruppe zuweisen
	$stmt2->execute(array('g_ID' => $g_ID[0], 'username' => $mitglied));
	echo $mitglied;
}

function getDatesFromAbstimmung() {
	global $pdo;
	$pdolocal = $pdo;

	$sqlSelDates = $pdolocal->prepare("SELECT DISTINCT datum FROM abstimmung_ergebnis WHERE g_ID = :g_ID ORDER BY datum DESC");
	$sqlSelDates->execute(array('g_ID' => $_SESSION['g_ID']));
	$sqlSelDatesRes = $sqlSelDates->fetchAll();

	echo json_encode($sqlSelDatesRes);
}

function getAbstimmungsErgebnisse() {
	global $pdo;
	$pdolocal = $pdo;

	$sqlSelAbst = $pdolocal->prepare("SELECT * FROM abstimmung_ergebnis WHERE g_ID = :g_ID ORDER BY datum DESC");
	$sqlSelAbst->execute(array('g_ID' => $_SESSION['g_ID']));
	$sqlSelAbstRes = $sqlSelAbst->fetchAll();
	$i = 0;
	foreach ($sqlSelAbstRes as $value) {
		$sqlSelLocname = $pdolocal->prepare("SELECT name FROM location WHERE l_ID = :l_ID");
		$sqlSelLocname->execute(array('l_ID' => $value['l_ID']));
		$sqlSelLocnameRes = $sqlSelLocname->fetch();
		$sqlSelAbstRes[$i]['locname'] = $sqlSelLocnameRes['name'];

		$sqlSelGruppe = $pdolocal->prepare("SELECT name FROM gruppe WHERE g_ID = :g_ID");
		$sqlSelGruppe->execute(array('g_ID' => $value['g_ID']));
		$sqlSelGruppeRes = $sqlSelGruppe->fetch();
		$sqlSelAbstRes[$i]['gruppe'] = $sqlSelGruppeRes['name'];

		// tobi:
		$datum = $value['datum'];
		$stmt1 = $pdolocal->prepare("SELECT * FROM abstimmen WHERE g_ID = :g_ID AND datum = :datum");
		$stmt1->execute(array('g_ID' => $_SESSION['g_ID'], 'datum' => $datum));
		$arr = null;
		foreach ($stmt1->fetchAll(PDO::FETCH_ASSOC) as $row) {
			$stmt2 = $pdolocal->prepare("SELECT name FROM essen WHERE e_ID = :e_ID1");
			$stmt2->execute(array('e_ID1' => $row['e_ID1']));
			$essen1 = $stmt2->fetch();
			$abstimmungen['essen1'] = $essen1[0];
			if($row['e_ID2']) {
				$stmt3 = $pdolocal->prepare("SELECT name FROM essen WHERE e_ID = :e_ID2");
				$stmt3->execute(array('e_ID2' => $row['e_ID2']));
				$essen2 = $stmt3->fetch();
				$abstimmungen['essen2'] = $essen2[0];
			}
			$stmt4 = $pdolocal->prepare("SELECT username FROM users WHERE u_ID = :u_ID");
			$stmt4->execute(array('u_ID' => $row['u_ID']));
			$username = $stmt4->fetch();
			$abstimmungen['name'] = $username[0];

			$json = json_encode($abstimmungen);
			$arr[] = $json;
		}
		$sqlSelAbstRes[$i]['abstimmungen'] =  json_encode($arr);
		// tobi ende
		$i++;
	}

	echo json_encode($sqlSelAbstRes);
}

function load_page($page) {

	if(file_exists('pages/'.$page.'.php'))
		echo file_get_contents('pages/'.$page.'.php');

	else echo 'There is no such page!'.$page;

}

function getAbstimmungenHeute() {
	global $pdo;
	$pdolocal = $pdo;

	$abstHeute = selectAbstimmungenHeute();

	$sqlSelAbstHeuteRes = $abstHeute;
	$i = 0;

	foreach ($sqlSelAbstHeuteRes as $value) {
		// Hole Usernamen der heutigen Abstimmer
		$sqlSelHilfsUsers = $pdolocal->prepare('SELECT username FROM users WHERE u_ID = :u_ID');
		$sqlSelHilfsUsers->execute(array('u_ID' => $value['u_ID']));
		$sqlSelHilfsUsersRes = $sqlSelHilfsUsers->fetch();
		$sqlSelAbstHeuteRes[$i]['username'] = $sqlSelHilfsUsersRes['username'];

		// Hole Gruppennamen der zugehörigen Gruppe
		$sqlSelHilfsGruppe = $pdolocal->prepare('SELECT gruppe.name FROM gruppe WHERE g_ID = :g_ID');
		$sqlSelHilfsGruppe->execute(array('g_ID' => $value['g_ID']));
		$sqlSelHilfsGruppeRes = $sqlSelHilfsGruppe->fetch();
		$sqlSelAbstHeuteRes[$i]['gruppe'] = $sqlSelHilfsGruppeRes['name'];

		// Hole die je zwei Essensbezeichnungen, für die der User abgestimmt hat. Bei doppelten Nennungen sorgt die if-Abfrage auch für eine doppelte Speicherung
		$sqlSelHilfsUsers1 = $pdolocal->prepare('SELECT name FROM essen WHERE e_ID = :e_ID1');
		$sqlSelHilfsUsers1->execute(array('e_ID1' => $value['e_ID1']));
		$sqlSelHilfsUsersRes1 = $sqlSelHilfsUsers1->fetch();
		$sqlSelHilfsUsers2 = $pdolocal->prepare('SELECT name FROM essen WHERE e_ID = :e_ID2');
		$sqlSelHilfsUsers2->execute(array('e_ID2' => $value['e_ID2']));
		$sqlSelHilfsUsersRes2 = $sqlSelHilfsUsers2->fetch();

		if (isset($sqlSelHilfsUsersRes1['name'])){$sqlSelAbstHeuteRes[$i]['essen1'] = $sqlSelHilfsUsersRes1['name'];}
		if (isset($sqlSelHilfsUsersRes2['name'])) {$sqlSelAbstHeuteRes[$i]['essen2'] = $sqlSelHilfsUsersRes2['name'];}
		$i++;
	}

	echo json_encode($sqlSelAbstHeuteRes);
}

function calculateErgebnisHeute() {
	global $pdo;
	$pdolocal = $pdo;

	$abstimmungen = array();
	$locations = array();

	$sqlSelAbstHeuteRes = selectAbstimmungenHeute();

	// Fülle Array abstimmungen mit allen e_IDs, für die heute abgestimmt wurde
	for($i = 0; $i < count($sqlSelAbstHeuteRes); $i++) {
		if (isset($sqlSelAbstHeuteRes[$i]['e_ID1'])) array_push($abstimmungen, $sqlSelAbstHeuteRes[$i]['e_ID1']);
		if (isset($sqlSelAbstHeuteRes[$i]['e_ID2'])) array_push($abstimmungen, $sqlSelAbstHeuteRes[$i]['e_ID2']);
	}

	foreach ($abstimmungen as $abst) {
		error_log("Abstimmung ".$abst);
		// Hole alle zu den Essen, für die abgestimmt wurde, passenden Locations
		$sqlSelLoc = $pdolocal->prepare("SELECT l_ID FROM locessen WHERE e_ID = :e_ID");
		$sqlSelLoc->execute(array('e_ID' => $abst));
		$sqlSelLocRes = $sqlSelLoc->fetchAll();
		// Diese Locations im Array speichern
		foreach($sqlSelLocRes as $value) {
			$locations[]['l_ID'] = $value['l_ID'];
		}
	}

	foreach($locations as $value) {
		error_log("Hier spricht Location ".$value['l_ID']);
	}
	// Entferne mehrfach vorhandene Locations
	$locations = array_multi_unique($locations);

	// Berechne, wie oft für jedes Essen abgestimmt wurde
	$häufigkeiten = array_count_values($abstimmungen);

	foreach($häufigkeiten as $value) {
		// error_log("Essen ".key($häufigkeiten)." hat ".$value. "Stimmen.");
		next($häufigkeiten);
	}

	for($i = 0; $i<count($locations); $i++) {
		error_log("Ich komme jetzt zu Location ".$locations[$i]['l_ID']);
		$masterzahl = 0;
		//error_log($locations[$i]['l_ID']);

		$sqlSelEssen = $pdolocal->prepare("SELECT e_ID FROM locessen WHERE l_ID = :l_ID");
		$sqlSelEssen->execute(array('l_ID' => $locations[$i]['l_ID']));
		$sqlSelEssenRes = $sqlSelEssen->fetchAll();

		foreach($sqlSelEssenRes as $value) {
			if (in_array($value['e_ID'], $abstimmungen)) { $masterzahl = $masterzahl + $häufigkeiten[$value['e_ID']];}
		}
		$locations[$i]['masterzahl'] = $masterzahl;
		error_log("Die Masterzahl hier ist".$locations[$i]['masterzahl']);
	}

	foreach($locations as $value) {
		error_log("Location ".$value['l_ID']." hat ".$value['masterzahl']." Abstimmungen.");
	}

	// Gibt dem Array, was am Ende zurückgegeben wird, die Information mit, ob es überhaupt Abstimmungen gegeben hat
	$result = array();
	if (count($sqlSelAbstHeuteRes) === 0) {
		$result[0]['abstimmungen'] = false;
		$result[0]['locname'] = false;
	}
	else $result[0]['abstimmungen'] = true;

	// Zufallszahl, um eine zufällige der bestimmten Locations auszuwählen, die das ermittelte Essen anbietet
	// $zufallszahl = mt_rand(0, count($locations) - 1);

	for ($i = 0; $i<count($locations); $i++) {

		// Ermittle, wie oft die Location für diese Gruppe bisher besucht wurde
		$sqlSelVerlauf = $pdolocal->prepare("SELECT COUNT(*) FROM abstimmung_ergebnis WHERE l_ID = :l_ID AND g_ID = :g_ID AND datum < :datum ");
		$sqlSelVerlauf->execute(array('l_ID' => $locations[$i]['l_ID'], 'g_ID' => $_SESSION['g_ID'], 'datum' => date("Y-m-d",time())));
		$sqlSelVerlaufRes = $sqlSelVerlauf->fetchColumn();
		error_log("Die Location ".$locations[$i]['l_ID']." mit ".$locations[$i]['masterzahl']." Abstimmungen kam für die Gruppe ".$_SESSION['g_ID']." bisher ".$sqlSelVerlaufRes." Mal vor.");

		$locations[$i]['verlaufzahl'] = $sqlSelVerlaufRes;
	}

	// Schleife notwendig, um Daten in richtige Form für multisort zu bringen
	$l_ID = array();
	$masterzahl = array();
	$verlaufzahl = array();
	foreach($locations as $key => $row) {
		$l_ID[$key] = $row['l_ID'];
		$verlaufzahl[$key] = $row['verlaufzahl'];
		$masterzahl[$key] = $row['masterzahl'];
	}

	// Sortiere die Locations:
	// 1. nach häufigster Abstimmung, 2. nach geringstem Besuchen der Gruppe, 3. nach Location-ID
	array_multisort($masterzahl, SORT_DESC, $verlaufzahl, SORT_ASC, $l_ID, SORT_ASC, $locations);

	foreach($locations as $value) {
		error_log("Location ".$value['l_ID']." hat ".$value['masterzahl']." Abstimmungen.");
	}


	for ($i = 0; $i<3 && $i < count($locations); $i++) {
		$result[$i]['l_ID'] = $locations[$i]['l_ID'];
		$result[$i]['masterzahl'] = $locations[$i]['masterzahl'];

		// Den Namen der Location ermitteln (für die Ausgabe)
		$sqlSelLocname = $pdolocal->prepare("SELECT name FROM location WHERE l_ID = :l_ID");
		$sqlSelLocname->execute(array('l_ID' => $locations[$i]['l_ID']));
		$sqlSelLocnameRes = $sqlSelLocname->fetch();

		$result[$i]['locname'] = $sqlSelLocnameRes['name'];
	}
	// Füge die Location als Abstimmungsergebnis in die Tabelle ein. Wenn es schon ein Ergebnis gibt, überschreibe es.
	if (count($locations) > 0) {
		$sqlInsErg = $pdolocal->prepare("INSERT INTO abstimmung_ergebnis (l_ID, datum, g_ID) VALUES (:l_ID, :datum, :g_ID) ON DUPLICATE KEY UPDATE l_ID = :l_ID;");
		$sqlInsErg->execute(array('l_ID' => $locations[0]['l_ID'], 'datum' => date("Y-m-d",time()),'g_ID' => $_SESSION['g_ID']));
	}
	else {
		$result[0]['locname'] = false;
	}

	error_log("Debug-Ausgabe: ".$result[0]['abstimmungen']." und ".$result[0]['locname']);
	echo json_encode($result);

}

function top3() {
	require('includes/includeDatabase.php');
	$g_ID = $_SESSION['g_ID'];
	//gibt die drei am meisten gewählten Essen (id) zurück:
	$stmt1 = $pdo->prepare("SELECT e_ID, COUNT(e_ID) AS ids FROM (SELECT e_ID1 AS e_ID FROM abstimmen WHERE g_ID = :g_ID UNION ALL SELECT e_ID2 AS e_ID FROM abstimmen WHERE g_ID = :g_ID)x GROUP BY e_ID ORDER BY ids DESC");
	$stmt1->execute(array('g_ID' => $g_ID));
	$rows = $stmt1->fetchAll(PDO::FETCH_ASSOC);
	$arr = null;
	if(isset($rows[0]['e_ID'])) {
		for ($i = 0; $i < 3; $i++) {
			if(isset($rows[$i]['e_ID'])) {
				$stmt2 = $pdo->prepare("SELECT name FROM essen WHERE e_ID = :e_ID");
				$stmt2->execute(array('e_ID' => $rows[$i]['e_ID']));
				$essen = $stmt2->fetch();
				$j = $i + 1;
				if (isset($essen[0])) {
					$arr[] = $essen[0];
					$_SESSION['top' . $j] = $essen[0];
				}
			}
		}
	}
	else {
		$arr[] = "Gebäck"; $_SESSION['top1'] = "Gebäck";
	}
	print json_encode($arr);
}

function verfuegbare_essen() {
	require('includes/includeDatabase.php');

	$arr = null;
	$stmt1 = $pdo->prepare("SELECT name FROM essen ORDER BY name ASC");
	$stmt1->execute();
	foreach ($stmt1->fetchAll(PDO::FETCH_ASSOC) as $row)
	{
		if(isset($_SESSION['top3'])) {
			if(($row['name'] != $_SESSION['top1']) && ($row['name'] != $_SESSION['top2']) && ($row['name'] != $_SESSION['top3'])) $arr[] = $row['name'];
		}
		else if(isset($_SESSION['top2'])) {
			if(($row['name'] != $_SESSION['top1']) && ($row['name'] != $_SESSION['top2'])) $arr[] = $row['name'];
		}
		else if(isset($_SESSION['top1'])) {
			if($row['name'] != $_SESSION['top1']) $arr[] = $row['name'];
		}
		//if( ($row['name'] != $_SESSION['top1']) && ($row['name'] != $_SESSION['top2']) && ($row['name'] != $_SESSION['top3'])) $arr[] = $row['name'];
	}
	print json_encode($arr);
}


/*
 * HILFSFUNKTIONEN!!!
 *
 * Die Hilfsfunktionen werden ausschließlich serverseitig verwendet und benötigen deshalb KEINEN Eintrag im Switch-Statement!!
 */



// Funktion, die doppelte Einträge auch aus mehrdimensionalen Arrays entfernen kann
function array_multi_unique($multiArray){

	$uniqueArray = array();
	foreach($multiArray as $subArray){
		if(!in_array($subArray, $uniqueArray)){
			$uniqueArray[] = $subArray;
		}
	}
	return $uniqueArray;
}


// Holt alle Abstimmungen von heute (per Join :P )
function selectAbstimmungenHeute() {
	global $pdo;
	$pdolocal = $pdo;
	date_default_timezone_set("Europe/Berlin");

	// Hole alle heutigen Abstimmungen von allen Usern der Gruppe  (,der der aktuelle User angehört)
	$sqlSelAbstHeute = $pdolocal->prepare("SELECT abstimmen.u_ID, abstimmen.g_ID, e_ID1, e_ID2 FROM abstimmen WHERE datum = :datum AND abstimmen.g_ID = :g_ID");
	$sqlSelAbstHeute->execute(array('datum' => date("Y-m-d",time()),'g_ID' => $_SESSION['g_ID']));
	$sqlSelAbstHeuteRes = $sqlSelAbstHeute->fetchAll();

	return $sqlSelAbstHeuteRes;
}

function chat_laden() {
	require('includes/includeDatabase.php');
	$arr = array();
	$stmt1 = $pdo->prepare("SELECT * FROM chat WHERE g_ID = :g_ID");
	$stmt1->execute(array('g_ID' => $_SESSION['g_ID']));
	foreach ($stmt1->fetchAll(PDO::FETCH_ASSOC) as $row1){
		$nachricht = $row1['nachricht'];
		$name = $row1['name'];
		$ts = $row1['ts'];
		$message = array(
			"name" => $name,
			"nachricht" => $nachricht,
			"ts" => $ts
		);
		//echo("<b>" . $name . ":</b> " . $nachricht . "<br>");
		$arr[] = json_encode($message);
	}
	print json_encode($arr);
}

function chat_speichern($nachricht) {
	require('includes/includeDatabase.php');

	$nachricht = $nachricht;
	$stmt1 = $pdo->prepare("INSERT INTO chat (name, nachricht, g_ID) VALUES (:name, :nachricht, :g_ID)");
	$result1 = $stmt1->execute(array('name' => $_SESSION['username'], 'nachricht' => $nachricht, 'g_ID' => $_SESSION['g_ID']));
}

function chat_delete() {
	require('includes/includeDatabase.php');

	$stmt1 = $pdo->prepare("DELETE FROM chat WHERE g_ID = :g_ID LIMIT 20");
	$stmt1->execute(array('g_ID' => $_SESSION['g_ID']));
}

?>
