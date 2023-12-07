<?php
include('inc/phpsettings.php');
include('inc/dbconfig.class.php');
include('inc/toernooi.class.php');
//$database = new Database();
$toernooiid = $_GET['toernooiid'];
$toernooi = new Toernooi();
$achtergrond = $toernooi->getBackground($toernooiid);
//echo $achtergrond;
?>
<!DOCTYPE html>
<html>
<head>
    <META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=UTF-8">
	<title>De Toernooiplanner</title>
	<link type="text/css" rel="stylesheet" href="css/toernooiplanner.css"/>
</head>
<body background="backgrounds/<?php echo $achtergrond; ?>">
<div id="boxContainerContainer">
<div id="boxContainer">
<div id="box1">
<!-- Het toernooiplanner-plaatje -->
<img src="images/detoernooiplanner_zondertekst.jpg"alt="Toernooiplanner">
<nav>
	<ul>
		<li><a href="index.php">OVERZICHT</a></li>
		<li><a href="login.php">LOG IN</a></li>
        <li><a href='printwedstrschema.php?toernooiid=<?php echo $toernooiid; ?>' target='_blank'>PRINT</a></li>
<!--        <li> <?php /*echo "<a href='#' title='print schema' onclick=\"window.open('printwedstrschema.php?toernooiid=$toernooiid', 'print', 'width=710,height=980,top=10,left=10'); return false\">" */?>PRINT</a></li>
-->	</ul>
	</nav>
<!-- afsl box1 -->
</div>

<div id="box2">
<?php // hier naam en datum
	$titelmetdatum = $toernooi->getTitelmetDatum($toernooiid);
	echo $titelmetdatum;
 //hier het poule-overzicht
 	$poule_overzicht = $toernooi->getPoules($toernooiid);
 	echo $poule_overzicht;
 ?>
  <p><a href="wedstrschema.php?toernooiid=<?php echo $toernooiid; ?>">wedstrijdschema met teamnummers</a></p>
 <?php
 //hier het wedstrijdschema
	$wedstrijdschema = $toernooi->toonWedstrijdschema($toernooiid,1,0);
	echo $wedstrijdschema;
?>
<!-- afsluiting box2 -->
        </div>
<!-- afsluiting boxContainer zit in menu.php-->
    </div>
<!-- afsluiting boxContainerContainer zit in menu.php-->
</div>
</body>
</html>
