<?php
require_once('../session.php');
if(!isset($_SESSION['user_session']))
{
	header("Location: http://detoernooiplanner.nl/app/");
}
else
{
require_once('admin_inc/toernooi.class.php');
require_once('admin_inc/poule.class.php');

$toernooiid = $_GET['toernid'];
$poule = $_GET['poule'];

$toernooiparameters=array();
$toernooi=new Toernooi();
$toernooiparameters=$toernooi->getToernooiparameters($toernooiid);

$actualpoule=new Poule();
$aantalinpoule=$actualpoule->zoekAantalInPoule($toernooiid,$poule);
$height=60+$aantalinpoule*40+100;
?>
<!DOCTYPE html>
<html>
<head>
    <META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=UTF-8">
	<title>De Toernooiplanner</title>
	<link type="text/css" rel="stylesheet" href="../css/toernooiplanner.css"/>
</head>
<body background="../backgrounds/<? echo $toernooiparameters['achtergrond'] ?>">
<div id="boxContainerContainer">
    <div id="boxContainer">
    <div id="box1">
    <h1>Poule <? echo $poule ?><br><? echo $toernooiparameters['naam'] ?></h1>
    <div id=menu>
    <ul>
		<li><a href="#" onclick="window.open('editteamnamen.php?toernid=<? echo $toernooiid ?>&poule=<? echo $poule ?>', 'teamnamen invoer', 'width=470,height=<? echo $height ?>,top=220,left=220'); return false">VOER TEAMNAMEN IN</a></li>
		<li><a href="wedstrschema.php?toernooiid=<? echo $toernooiid ?>">TERUG</a></li>
		</ul>
    </div>
    </div>
<div id="box2">

<table><tr><td class=zonder valign=top>
<?php
	//$actualpoule=new Poule();
	$poulewedstrijdschema=$actualpoule->toonWedstrijdschema($toernooiid,$poule,$toernooiparameters['achtergrond']);
	echo $poulewedstrijdschema;
?>

</td><td class=zonder valign=top>

<?
//$actualpoule = new Poule();
$poulestand=$actualpoule->displayPoulestand($toernooiid,$poule);
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
<? } ?>
