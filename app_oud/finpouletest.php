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
   <!-- <meta HTTP-EQUIV="refresh" CONTENT="60; URL=videowall8poules.php?toernooiid=<?=$toernooiid?>&titel=<?=$titel?>">-->
	<title>De Toernooiplanner</title>
	<link type="text/css" rel="stylesheet" href="inc/toernooiplanner.css"/>
</head>
<body style="font-size: 20px;">
<div id="div1" align="center">
<br>Welkom bij<br><br><b><?=$titel?></b><br><br>
Veel plezier!
</div>
<div id="div2">
<? 	$scherm2 = new Toernooi();
	$scherm2->displayFinaleschema($toernooiid);
	//$scherm2->displayScherm($toernooiid,array('A','B'));
?>
</div>
<div id="div3">
<? 	//$scherm3 = new Scherm();
	//$scherm3->displayScherm($toernooiid,array('C','D'));
?>
</div>
<div id="div4">
<? 	//$scherm4 = new Toernooi();
	//$scherm4->displayWedstrschema($toernooiid);
	//$scherm4->displayScherm($toernooiid,array('E','F'));
?>
</div>
<div id="div5">
<? 	//$scherm5 = new Scherm();
	//$scherm5->displayScherm($toernooiid,array('G','H'));
?>
</div>
<div id="div6">
<? 	//$scherm6 = new Scherm();
	//$scherm6->displayScherm($toernooiid,array('G','H'));
	//$scherm6->displayFinaleschema($toernooiid);
?>
</div>

</body>
</html>
