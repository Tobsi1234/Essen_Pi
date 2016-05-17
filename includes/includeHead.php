<!DOCTYPE html>
<html lang="de">
<head>

	<meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Essen</title>

    <link href="css/bootstrap.css" rel="stylesheet">
    <!-- Bootstrap Core CSS -->

	<link rel="icon" href="includes/essenMaennchen.jpg" type="image/vnd.microsoft.icon">

    <!-- Custom CSS -->
    <link href="css/one-page-wonder.css" rel="stylesheet">
    
	<!-- CSS für Login -->
    <link href="css/login.css" rel="stylesheet">
	
	<!-- jQuery -->
    <script src="js/jquery.js"></script>
	<!--<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script> -->

    <!-- Bootstrap Core JavaScript -->
    <script src="js/bootstrap.js"></script>
	
	<!-- Page Swapper Ajax -->
	<script type="text/javascript" src="js/pages.js"></script>

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

	<?php
	require('includes/includeDatabase.php');

	if(isset($_GET['login'])/* && isset($_POST['email']) && isset($_POST['passwort'])*/) {
		$email = $_POST['email'];
		$passwort = $_POST['passwort'];

		$statement = $pdo->prepare("SELECT * FROM users WHERE email = :email");
		$result = $statement->execute(array('email' => $email));
		$user = $statement->fetch();

		//Überprüfung des Passworts
		if ($user !== false && password_verify($passwort, $user['passwort']) && $user['verified'] != 0) {
			$_SESSION['userid'] = $user['u_ID'];
			$_SESSION['username'] = $user['username'];
			$_SESSION['email'] = $user['email'];

		} else {
			$errorMessage = "E-Mail oder Passwort war ungültig<br>";
		}
	}

	if (isset($_SESSION['userid'])) {
		//g_ID auch noch als Session speichern, sofern gerade ein User angemeldet ist
		$sqlSelG_ID = $pdo->prepare("SELECT g_ID FROM users WHERE u_ID = :u_ID");
		$sqlSelG_ID->execute(array('u_ID' => $_SESSION['userid']));
		$sqlSelG_IDRes = $sqlSelG_ID->fetch();
		// Wenn eine G_ID existiert, speichere sie in der Session. Ansonsten mache die Session leer.
		if(isset($sqlSelG_IDRes[0])) $_SESSION['g_ID'] = $sqlSelG_IDRes[0];
		else unset($_SESSION['g_ID']);
	}
	?>
	
<script language="javascript">
	$(document).ready(function(){
		$('#login-trigger').click(function() {
			$(this).next('#login-content').slideToggle();
			$(this).toggleClass('active');                    
			
			if ($(this).hasClass('active')) $(this).find('span').html('&#x25B2;')
				else $(this).find('span').html('&#x25BC;')
			})
	});

	function chat_speichern() {
		refChatEingabe = document.forms['formChat'].nachricht;
		nachricht = refChatEingabe.value;
		if (nachricht) {

			$.ajax({
				type: "POST",
				url: "procedures.php",
				data: {callFunction: 'chat_speichern', nachricht: nachricht},
				dataType: 'text',
				success: function (data) {
					chat_laden();
					refChatEingabe.value = "";
				}
			});
		}
		return false;
	}

	function chat_laden() {
		refChatAusgabe = document.getElementById('chat_ausgabe');

		$.ajax({
			type: "POST",
			url: "procedures.php",
			data: {callFunction: 'chat_laden'},
			dataType: 'text',
			success:function(data) {
				json1 = JSON.parse(data);
				refChatAusgabe.innerHTML = "";
				if(Object.keys(json1).length > 40) deleteOldMsgs();
				for(var i=0; i<Object.keys(json1).length; i++) {
					json2 = JSON.parse(json1[i]);
					refChatAusgabe.innerHTML += "<b>" + json2.name + "</b>: " + json2.nachricht + "<br>" + "<p style=\"font-size: 10px\" > am: " + json2.ts + "</p>";
				}
				json2 = JSON.parse(json1[Object.keys(json1).length - 1]);
				scrollen();
			}
		});
	}

	function deleteOldMsgs() {
		$.ajax({
			type: "POST",
			url: "procedures.php",
			data: {callFunction: 'chat_delete'},
			dataType: 'text',
			success: function (data) {

			}
		});
	}

	function chat_nachladen() {
		refChatAusgabe = document.getElementById('chat_ausgabe');

		$.ajax({
			type: "POST",
			url: "procedures.php",
			data: {callFunction: 'chat_laden'},
			dataType: 'text',
			success:function(data) {
				jsonNeu1 = JSON.parse(data);
				var jsonTest = json2.nachricht;
				jsonNeu2 = JSON.parse(jsonNeu1[Object.keys(jsonNeu1).length - 1]);
				if (jsonTest != jsonNeu2.nachricht) {
					refChatAusgabe.innerHTML = "";
					chat_laden();
				}
				window.setTimeout(chat_nachladen, 500);
			}
		});
	}

	function chat_verspätet() {
		window.setTimeout(chat_nachladen, 1000);
	}

	function scrollen() {
		if(name) {
			refChatAusgabe = document.getElementById('chat_ausgabe');
			refChatAusgabe.scrollTop = refChatAusgabe.scrollHeight;
		}
	}

</script>

</head>
</html>