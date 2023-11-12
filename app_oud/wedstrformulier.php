<?php
require_once('inc/wedstrijd.class.php');
require_once('inc/toernooi.class.php');

$toernooiid = $_GET['toernooiid'];
$velden = $_GET['velden'];
?>
<html>
<head>
	<link type="text/css" rel="stylesheet" href="css/toernooiplannerza.css"/>
</head>
<body>

<?php
$veld1 = 1;
while($veld1 <= $velden)
{
$veld2=$veld1+1;
$toern=new Toernooi();
$toernooiform=$toern->maakWedstrijdformulierenbijTweevelden($toernooiid,$veld1,$veld2);
echo $toernooiform;
$veld1 = $veld1 + 2;
}
//nu de finalewedstrijden nog
/*$finalewedstrvraag = mysql_query ("SELECT * FROM finalewedstrijden WHERE toernooi_id='$toernooiid' AND thuisploeg <> '' ORDER BY finaleronde, veld", $db) or die(mysql_error());
$teller = 0;
while ($finalerow = mysql_fetch_array($finalewedstrvraag))
{
	$speelronde = $aantalrondes + $finalerow['finaleronde'];
	if ($teller & 1)
		$class = "a5rechts";
	else
	{
		$class = "a5links";
		echo "<div class=\"wrap\">\n";
	}
?>
<div class="<? echo $class ?>">
<h3 align="center">Wedstrijdformulier <? echo $toernooinaam ?></h3>
<table>
<tr><td class="noborder">Veld</td><td class="noborder">Finalewedstrijd</td><td class="noborder">Ronde</td></tr>
<tr><td class="groot"><? echo $finalerow['veld'] ?></td><td class="groot"><? echo $finalerow['fin_wedstrnaam'] ?></td><td class="groot"><? echo $speelronde ?></td></tr>
<!--<tr><td colspan="3" class="groot"><? echo substr($row['begin'],0,5) ?> uur</td></tr>-->
<tr><td class="noborder">Thuis</td><td class="noborder"></td><td class="noborder">Uit</td></tr>
<tr><td class="middelgroot"><? echo $finalerow['thuisploeg'] ?><br><? echo $finalerow['thuisploegnr'] ?></td><td class="noborder"></td><td class="middelgroot"><? echo $finalerow['uitploeg'] ?><br><? echo $row['uitploegnr'] ?></td></tr>
<tr><td class="noborder">turfvak</td><td class="noborder"></td><td class="noborder">turfvak</td></tr>
<tr><td class="groot" align="top"></td><td class="noborder"></td><td class="groot" align="top"></td></tr>
<tr><td class="noborder">uitslag</td><td class="noborder"></td><td class="noborder">uitslag</td></tr>
<tr><td class="groot"></td><td class="noborder"></td><td class="groot"></td></tr>
<tr><td colspan="3" class="noborder">Opmerkingen</td></tr>
<tr><td colspan="3" class="groot"></td></tr>
</table>
</div>
<?
	$teller++;
	if ($class=='a5rechts')
		echo "</div>\n";
}*/
?>
</body>
</html>
