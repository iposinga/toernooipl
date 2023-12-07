<?
	error_reporting(E_ALL);
	require_once('inc/scherm.class.php');
	$toernooiid=$_GET['toernooiid'];
	?>
<html>
<head>
    <META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=UTF-8">
    <meta HTTP-EQUIV="refresh" CONTENT="60; URL=videowalltest1.php?toernooiid=<?= $toernooiid ?>">
	<title>De Toernooiplanner</title>
	<link type="text/css" rel="stylesheet" href="css/toernooiplanner.css"/>
</head>
<body style="font-size: 20px;">

<div id="div1">
<? 	$scherm1 = new Scherm();
	$scherm1->displayScherm($toernooiid,array('A','B'));
?>
</div>

<div id="div2">
<? 	$scherm2 = new Scherm();
	$scherm2->displayScherm($toernooiid,array('C','D'));
?>
</div>

<div id="div3">
<? 	$scherm3 = new Scherm();
	$scherm3->displayScherm($toernooiid,array('E','F'));
?>
</div>

<div id="div4">
<? 	$scherm4 = new Scherm();
	$scherm4->displayScherm($toernooiid,array('G','H'));
?>
</div>

<div id="div5">
<? 	$scherm5 = new Scherm();
	$scherm5->displayScherm($toernooiid,array('I','J'));
?>
</div>

<div id="div6">
<? 	$scherm6 = new Scherm();
	$scherm6->displayScherm($toernooiid,array('K'));
?>
</div>

</body>
</html>
