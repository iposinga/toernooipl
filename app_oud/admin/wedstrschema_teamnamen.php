<?php
require_once('../session.php');
require_once('admin_inc/toernooi.class.php');
if(!isset($_SESSION['user_session']))
{
	header("Location: http://detoernooiplanner.nl/app/");
}
else
{
$toernooiid = $_GET['toernooiid'];
$toernooi = new Toernooi();
$achtergrond = $toernooi->getBackground($toernooiid);
?>
<!DOCTYPE html>
<html>
<head>
    <META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=UTF-8">
	<title>De Toernooiplanner</title>
	<link type="text/css" rel="stylesheet" href="../css/toernooiplanner.css"/>
	<script>
	function popupwindow(url, title, w, h)
	{
  var left = (screen.width/2)-(w/2);
  var top = (screen.height/2)-(h/2);
  return window.open(url, title, 'toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=yes, resizable=yes, copyhistory=no, width='+w+', height='+h+', top='+top+', left='+left+'');
}
</script>
</head>
<body background="../backgrounds/<?php echo $achtergrond ?>">
<div id="boxContainerContainer">
<div id="boxContainer">
<div id="box1">
<!-- Het toernooiplanner-plaatje -->
<img src="../images/detoernooiplanner_zondertekst.jpg" alt="Toernooiplanner" style= "height: 120px">

	<nav>
	<ul style="margin-top: 0px;">
		<li><a href="nieuw.php">NIEUW</a></li>
		<li><a href="index.php">OVERZICHT</a></li>
		<li><a href="#">OPTIES</a>
		<ul>
				<li><a href="#" onclick="popupwindow('uservelden.php', 'velden van user', '620', '670'); return false">VELDLOCATIES</a></li>
				<li><a href="#" onclick="popupwindow('userwinstbepalingen.php', 'winstbepalingen van user', '1180', '670'); return false">WINSTREGELS</a></li>
				<li><a href="#" onclick="popupwindow('usergedragsregels.php', 'gedragsregels van user', '1020', '670'); return false">GEDRAGSREGELS</a></li>
		</ul>
		</li>
		<li><a href="logout.php?logout=true">LOG UIT</a></li>

	</ul>
	</nav>
</div><!-- /box1 -->
<div id="box2">
<? // hier naam en datum
	$titelmetdatum = $toernooi->getTitelmetDatum($toernooiid);
	echo $titelmetdatum;
?>
<nav>
	<ul style="margin-top: 0px;">
		<li><a href="#" title="velden" onclick="window.open('velden.php?toernooiid=<? echo $toernooiid; ?>', 'velden invoer', 'width=360,height=360,top=220,left=220, resizable=yes'); return false">VELDEN</a></li>
		<li> <a href="#" title="standen" onclick="window.open('standen.php?toernid=<? echo $toernooiid; ?>', 'klasssenstand', 'width=750,height=680,top=120,left=220, resizable=yes'); return false">STANDEN</a></li>
		<li> <a href="#">AANVULLINGEN</a>
		<ul>
				<li><a href="#" onclick="popupwindow('finalerondes.php?toernooiid=<? echo $toernooiid ?>', 'finalerondes bij toernooi', '620', '670'); return false">FINALERONDES</a></li>
				<!-- <li><a href="#" onclick="popupwindow('mededelingen.php?toernooiid=<? echo $toernooiid ?>', 'mededelingen bij toernooi', '620', '670'); return false">MEDEDELINGEN</a></li> -->
				<li><a href="#" onclick="popupwindow('toerngedragsregels.php?toernooiid=<? echo $toernooiid ?>', 'gedragsregels bij toernooi', '1020', '670'); return false">WINST- EN GEDRAGSREGELS</a></li>
				<!--<li><a href="#" onclick="popupwindow('toernwinstbepalingen.php', 'winstbepalingen bij toernooi', '1180', '670'); return false">WINNAARBEPALING</a></li>-->
		</ul>


		</li>
		<li> <a href="#" title="print schema" onclick="popupwindow('../printwedstrschema.php?toernooiid=<? echo $toernooiid; ?>', 'print', '710', '980'); return false">PRINT</a></li>
	</ul>
</nav>
<?
 //hier het poule-overzicht
 	$poule_overzicht = $toernooi->getPoules($toernooiid);
 	echo $poule_overzicht;?>
 	<p><a href="wedstrschemainvul.php?toernooiid=<? echo $toernooiid; ?>">alleen wedstrijdschema</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="wedstrschema.php?toernooiid=<? echo $toernooiid; ?>">wedstrijdschema met teamnummers</a></p>
<?
 //hier het wedstrijdschema
	$wedstrijdschema = $toernooi->toonWedstrijdschema($toernooiid,$achtergrond,1);
	echo $wedstrijdschema;
?>
</div> <!-- /box2 -->

    </div><!-- /boxContainer -->
</div><!-- /boxContainerContainer -->
</body>
</html>
<?
	}
	?>
