<?php

$sqlhost = "localhost";
$sqluser = "root";
$sqlpass = "_1a2s3d_";
$connection = mysqli_connect($sqlhost, $sqluser, $sqlpass) or die ("DB-system nicht verfügbar");
mysqli_select_db($connection, "tobsi") or die ("Datenbank nicht verfügbar");

$pdo = new PDO('mysql:host=localhost;dbname=tobsi', 'root', '_1a2s3d_');


?>
