<?
	error_reporting(E_ALL);
	require_once('inc/poule.class.php');
	require_once('inc/toernooi.class.php');
	//require_once('admin/admin_includes/toernooi.class.php');
	//$scherm = new Scherm();
	$poule = new Poule();
	$toernooi = new Toernooi();
	$toernooiid = $_GET['toernooiid'];
	$aantalpoules = $_GET['poules'];
	$title = $_GET['titel'];

	if($aantalpoules <= 3)
	//twee kolommen
		$kolomverdeling = "50% 50%";
	elseif($aantalpoules > 5)
	//vier kolommen
		$kolomverdeling = "25% 25% 25% 25%";
	else
	//drie kolommen
		$kolomverdeling = "33% 33% 33%";
	?>
<html>
<head>
    <META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=UTF-8">
    <meta HTTP-EQUIV="refresh" CONTENT="60; URL=videowall.php?toernooiid=<?= $toernooiid ?>&titel=<?= $title ?>&poules=<?= $aantalpoules ?>">
	<title>De Toernooiplanner</title>
	<link type="text/css" rel="stylesheet" href="css/toernooiplanner.css"/>
	<style>
	body {
		font-size: 20px;
	  margin: 40px;
	}

	.wrapper {
	  display: grid;
	  grid-template-columns: <?= $kolomverdeling ?>;
	  grid-gap: 10px;
	  background-color: #fff;
	  color: #444;
	}

	.box {
	  background-color: #eff;
	  color: #fff;
	  border-radius: 5px;
	  padding: 20px;
	  /*font-size: 150%;*/
	}
	</style>
</head>
<body style="font-size: 20px;">
	<?php
	echo "<h1>$title</h1>";
	?>
	<div class="wrapper">
<?php

$teller = 1;
$charteller = 65;
while($teller <= $aantalpoules){
	echo "<div class='box'>";
	echo "<table>".PHP_EOL;
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
echo "<div class='box'>";
	echo "<table>".PHP_EOL;
echo '<tr>'.PHP_EOL;
echo "<td class='zonder' valign='top'>".PHP_EOL;
$wedstrschema = $toernooi->displayWedstrschema($toernooiid);
echo  $wedstrschema;
echo '</td>'.PHP_EOL;
echo '</tr>'.PHP_EOL;
echo "</table>";
echo "</div>";
?>
	</div>
</body>
</html>
