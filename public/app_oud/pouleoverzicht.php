<?
include('inc/phpsettings.php');
//include('includes/dbconfig.class.php');
include('inc/toernooi.class.php');
//include('includes/poule.class.php');
//$database = new Database();
//$link = $database->getLink();

$toernooiid = $_GET['toernid'];
$poule = $_GET['poule'];

$toernooiparameters=array();
$toernooi=new Toernooi();
$toernooiparameters=$toernooi->getToernooiparameters($toernooiid);

//echo $aantalinvoervelden;
//$height = $aantalinvoervelden * 38 + 90;
?>
<!DOCTYPE html>
<html>
<head>
    <META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=UTF-8">
	<title>De Toernooiplanner</title>
	<link type="text/css" rel="stylesheet" href="css/toernooiplanner.css"/>
</head>
<body background="backgrounds/<? echo $toernooiparameters['achtergrond'] ?>">
<div id="boxContainerContainer">
    <div id="boxContainer">
    <div id="box1">
    <h1>Poule <? echo $poule ?><br><? echo $toernooiparameters['naam'] ?></h1>
    <div id=menu>
    <ul>
		<li><a href="wedstrschema.php?toernooiid=<? echo $toernooiid ?>">TERUG</a></li>
		</ul>
    </div>
    </div>
<div id="box2">

<table><tr><td class=zonder valign=top>
<?php
	$pouleschema=new Poule();
	$poulewedstrijdschema=$pouleschema->toonWedstrijdschema($toernooiid,$poule);
	echo $poulewedstrijdschema;
?>

</td><td class=zonder valign=top>

<?
$toernooipoule = new Poule();
$poulestand=$toernooipoule->displayPoulestand($toernooiid,$poule);
echo $poulestand;
//poulestand($toernooiid,$poule);
?>

</td></tr>
</table>

<!-- afsluiting box2 -->
        </div>
<!-- afsluiting boxContainer -->
    </div>
<!-- afsluiting boxContainerContainer -->
</div>

</body>
</html>
