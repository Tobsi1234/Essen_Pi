<?php

	require('includes/includeDatabase.php');
	
	$name = htmlspecialchars($_GET["name"]);
	$essen1 = htmlspecialchars($_GET["essen1"]);
	$essen2 = htmlspecialchars($_GET["essen2"]);
	$datum = htmlspecialchars($_GET["datum"]);
	
	$stmt1 = $pdo->prepare("INSERT INTO tabperson (name) VALUES (:name)");
	$stmt1->execute(array('name' => $name));
	
	$stmt2 = $pdo->prepare("SELECT p_ID FROM tabperson WHERE name = :name");
	$stmt2->execute(array('name' => $name));
	$p_ID = $stmt2->fetch();
	
	$stmt3 = $pdo->prepare("INSERT INTO tabdatum (datum) VALUES (:datum)");
	$stmt3->execute(array('datum' => $datum));
	
	$stmt4 = $pdo->prepare("SELECT d_ID FROM tabdatum WHERE datum = :datum");
	$stmt4->execute(array('datum' => $datum));
	$d_ID = $stmt4->fetch();
	
	$stmt5 = $pdo->prepare("INSERT INTO tabessen (name) VALUES (:name)");
	$stmt5->execute(array('name' => $essen1));
	
	$stmt6 = $pdo->prepare("SELECT e_ID FROM tabessen WHERE name = :name");
	$stmt6->execute(array('name' => $essen1));
	$e_ID1 = $stmt6->fetch();
	
	if($essen2 != "") {
		$stmt7 = $pdo->prepare("INSERT INTO tabessen (name) VALUES (:name)");
		$stmt7->execute(array('name' => $essen2));
	
		$stmt8 = $pdo->prepare("SELECT e_ID FROM tabessen WHERE name = :name");
		$stmt8->execute(array('name' => $essen2));
		$e_ID2 = $stmt8->fetch();
	}

	if($essen2 == "") {	
		$stmt9 = $pdo->prepare("INSERT INTO tabbez (p_ID, d_ID, e_ID1) VALUES (:p_ID, :d_ID, :e_ID1)");
		$stmt9->execute(array('p_ID' => $p_ID[0], 'd_ID' => $d_ID[0], 'e_ID1' => $e_ID1[0]));
		
		$stmt10 = $pdo->prepare("UPDATE tabbez SET e_ID1 = :e_ID1, e_ID2 = null WHERE d_ID = :d_ID AND p_ID = :p_ID");
		$stmt10->execute(array('p_ID' => $p_ID[0], 'd_ID' => $d_ID[0], 'e_ID1' => $e_ID1[0]));
	}
	else {
		$stmt9 = $pdo->prepare("INSERT INTO tabbez (p_ID, d_ID, e_ID1, e_ID2) VALUES (:p_ID, :d_ID, :e_ID1, :e_ID2)");
		$stmt9->execute(array('p_ID' => $p_ID[0], 'd_ID' => $d_ID[0], 'e_ID1' => $e_ID1[0], 'e_ID2' => $e_ID2[0]));
		
		$stmt10 = $pdo->prepare("UPDATE tabbez SET e_ID1 = :e_ID1, e_ID2 = :e_ID2 WHERE d_ID = :d_ID AND p_ID = :p_ID");
		$stmt10->execute(array('p_ID' => $p_ID[0], 'd_ID' => $d_ID[0], 'e_ID1' => $e_ID1[0], 'e_ID2' => $e_ID2[0]));
	}

?>
