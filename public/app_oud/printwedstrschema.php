<?php
include('inc/phpsettings.php');
include 'inc/toernooi.class.php';
include 'admin/admin_inc/winstregel.class.php';
include 'admin/admin_inc/regel.class.php';
$toernooiid = $_GET['toernooiid'];
$toernooiprint = new Toernooi();
$toernooidata=array();
$toernooidata = $toernooiprint->gettoernooiparameters($toernooiid);
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=UTF-8">
	<title>De Toernooiplanner</title>
	<link type="text/css" rel="stylesheet" href="css/toernooiplanner.css"/>
</head>
<body>
<div class="print" align=center>
<?php
echo "<h3>".$toernooidata['naam']." op ".$toernooidata['datum']."</h3>";
$poule_print = 	$toernooiprint->printPoules($toernooiid);
echo $poule_print;
?>
</div>

<div class=print align=center>
<?php
$schema_print = $toernooiprint->printWedstrschema($toernooiid);
//echo "test";
echo $schema_print;

?>
</div>
<div class=printmededeling>
<?php
$winst=new Winstregel();
$winstbepaling=$winst->zoekToernooiWinstregel($toernooidata['winnaar']);
echo $winstbepaling;
//gedragsregels ophalen
$gedrag=new Regel();
$gedragbepalingen=$gedrag->zoekToernooiRegel($toernooidata['gedrag']);
echo $gedragbepalingen;
?>
</div>

</body>
</html>
