<?
	$username = "u24647p26455_toernooiplan";
    $password = "osirules";
	$host = "localhost";
	$dbnaam = "u24647p26455_toernooiplan";
	$db = mysqli_connect($host, $username, $password) or die (mysqli_error());
	mysqli_select_db ($dbnaam, $db) or die (mysqli_error());
?>