<?
/*op de videowall is het vlak opgedeeld in 2 rijen van 3 vlakken (6 in totaal)
afhankelijk van het aantal poules kun je een indeling vaststellen/kiezen
*/
	error_reporting(E_ALL);
	require_once('inc/poule.class.php');
	require_once('inc/toernooi.class.php');
	//require_once('admin/admin_includes/toernooi.class.php');
	//$scherm = new Scherm();
	$poule = new Poule();
	$toernooi = new Toernooi();
	$toernooiid=$_GET['toernooiid'];
	$aantalpoules = 4;
	?>
<html>
<head>
    <META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=UTF-8">
    <meta HTTP-EQUIV="refresh" CONTENT="60; URL=videowall4poules.php?toernooiid=<?= $toernooiid ?>">
	<title>De Toernooiplanner</title>
	<link type="text/css" rel="stylesheet" href="css/toernooiplanner.css"/>
</head>
<body style="font-size: 20px;">
<?php
$teller = 1;
$charteller = 65;
while($teller <= $aantalpoules){
	echo "<div id='div$teller'>";
	echo "<table class='perscherm'>".PHP_EOL;
	echo '<tr>'.PHP_EOL;
	echo "<td class='zonder' valign='top'>".PHP_EOL;
	$poulewdstr=$poule->displayPoulewedstr($toernooiid,chr($charteller));
	echo $poulewdstr;
	echo '</td>'.PHP_EOL;
	echo "<td class='zonder' valign='top'>".PHP_EOL;
	$poulestand = $poule -> displayPoulestand($toernooiid,chr($charteller));
	echo $poulestand;
	echo '</td>'.PHP_EOL;
	echo '</tr>'.PHP_EOL;
	echo "</table>";
	echo "</div>";
	$teller++;
	$charteller++;
}
echo "<div id='div$teller'>";
$toernooi->displayWedstrschema($toernooiid);
echo "</div>";
?>
</body>
</html>
