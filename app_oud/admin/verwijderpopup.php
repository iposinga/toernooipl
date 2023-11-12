<?php
require_once('../session.php');
require_once('admin_inc/toernooi.class.php');

$toernooi_id=$_GET['toernooiid'];

$toernooigeg=array();
$toernooi = new Toernooi();
$toernooigeg = $toernooi->getToernooiparameters($toernooi_id);

?>
<!DOCTYPE html>
<html>
<head>
    <META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=UTF-8">
	<title>De Toernooiplanner</title>
	<link type="text/css" rel="stylesheet" href="../css/toernooiplanner.css"/>
</head>
<body background="../backgrounds/<? echo $toernooigeg['achtergrond']; ?>">
<div id="boxContainerContainer">
    <div id="boxContainer">
<div id="box1">
<form method="post" action="verwijder.php">
Weet je zeker dat je het toernooi<br><b><? echo $toernooigeg['naam'] ?></b><br>wilt verwijderen?<br>Alle gegevens (ook uitslagen, standen etc.) worden vernietigd!<br><br>
<input type="hidden" name="toernooi" value="<? echo $toernooi_id ?>">
<input type="submit" name="ok" value="ik weet het zeker">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<button type="button" name="annuleer" onclick="window.close()">annuleer</button>
	</form>
	</div>
	</div>
	</div>
	</body>
	</html>
