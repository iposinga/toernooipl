<?
	error_reporting(E_ALL);
	require_once('inc/scherm.class.php');
	require_once('inc/toernooi.class.php');
	$toernooiid=$_GET['toernooiid'];
	$titel=$_GET['titel'];
?>
<html>
<head>
    <META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=UTF-8">
    <meta HTTP-EQUIV="refresh" CONTENT="60; URL=videowall6poules.php?toernooiid=<?= $toernooiid ?>&titel=<?= $titel ?>">
	<title>De Toernooiplanner</title>
	<link type="text/css" rel="stylesheet" href="css/toernooiplanner.css"/>
</head>
<body style="font-size: 20px;">

<div id="div1">
<h2>Welkom bij</h2><h1><?=$titel?></h1>
<h2>Veel plezier!</h2>
</div>

<div id="div2">
<? 	$scherm2 = new Toernooi();
	$scherm2->displayWedstrschema($toernooiid);
?>
</div>

<div id="div3">
<? 	$scherm3 = new Toernooi();
	$scherm3->displayFinaleschema($toernooiid);
?>
</div>

<div id="div4">
<? 	$scherm4 = new Scherm();
	$scherm4->displayScherm($toernooiid,array('A','B'));
?>
</div>

<div id="div5">
<? 	$scherm5 = new Scherm();
	$scherm5->displayScherm($toernooiid,array('C','D'));
?>
</div>

<div id="div6" align="center">
<? 	$scherm6 = new Scherm();
	$scherm6->displayScherm($toernooiid,array('E','F'));
?>
</div>

</body>
</html>
