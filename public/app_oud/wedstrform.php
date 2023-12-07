<html>
<head>
	<link type="text/css" rel="stylesheet" href="inc/toernooiplannerza.css"/>
</head>
<body>

<?
$toernooiid = $_GET['toernooiid'];
include('inc/vars.php');
$toernooivraag = mysql_query ("SELECT velden, naam FROM toernooien WHERE toernid='$toernooiid'", $db) or die(mysql_error());
$row = mysql_fetch_array($toernooivraag);
echo "het aantal velden is: ".$row['velden'];
//$aantalloops = intdiv(2,2);
//echo "aantal loops = ".$aantalloops;

$wedstrvraag1 = mysql_query ("SELECT tm1.naam AS thuis, tm2.naam AS uit, veld, wst.poule, speelronde, thuisploeg, uitploeg, wst.aanvang AS begin FROM toernooien AS toer, wedstrijden AS wst, ploegen AS tm1, ploegen AS tm2 WHERE veld='1' AND toer.toernid='$toernooiid' AND wst.toernid='$toernooiid' AND tm1.toernid=wst.toernid AND tm2.toernid=wst.toernid AND wst.thuisploeg=tm1.teamnr AND wst.uitploeg=tm2.teamnr ORDER BY speelronde, veld", $db) or die(mysql_error());
$wedstrvraag2 = mysql_query ("SELECT tm1.naam AS thuis, tm2.naam AS uit, veld, wst.poule, speelronde, thuisploeg, uitploeg, wst.aanvang AS begin FROM toernooien AS toer, wedstrijden AS wst, ploegen AS tm1, ploegen AS tm2 WHERE veld='2' AND toer.toernid='$toernooiid' AND wst.toernid='$toernooiid' AND tm1.toernid=wst.toernid AND tm2.toernid=wst.toernid AND wst.thuisploeg=tm1.teamnr AND wst.uitploeg=tm2.teamnr ORDER BY speelronde, veld", $db) or die(mysql_error());
//$teller = 0;
//afhankelijk van het aantal velden moeten ze 2 aan 2 worden afgedrukt: links de oneven-velden, rechts de even velden
while ($row1 = mysql_fetch_array($wedstrvraag1) AND $row2 = mysql_fetch_array($wedstrvraag2))
{
?>
<div class="wrap">
<div class="a5links">
<h2 align="center">Wedstrijdformulier <? echo $row['naam'] ?></h1>
<table>
<tr><td class="noborder">Veld</td><td class="noborder">Poule</td><td class="noborder">Speelronde</td></tr>
<tr><td class="groot"><? echo $row1['veld'] ?></td><td class="groot"><? echo $row1['poule'] ?></td><td class="groot"><? echo $row1['speelronde'] ?></td></tr>
<tr><td colspan="3" class="groot"><? echo substr($row1['begin'],0,5) ?> uur</td></tr>
<tr><td class="noborder">Thuis</td><td class="noborder"></td><td class="noborder">Uit</td></tr>
<tr><td class="middelgroot"><? echo $row1['thuisploeg'] ?><br><? echo $row1['thuis'] ?></td><td class="noborder"></td><td class="middelgroot"><? echo $row1['uitploeg'] ?><br><? echo $row1['uit'] ?></td></tr>
<tr><td class="noborder">turfvak</td><td class="noborder"></td><td class="noborder">turfvak</td></tr>
<tr><td class="groot" align="top"></td><td class="noborder"></td><td class="groot" align="top"></td></tr>
<tr><td class="noborder">uitslag</td><td class="noborder"></td><td class="noborder">uitslag</td></tr>
<tr><td class="groot"></td><td class="noborder"></td><td class="groot"></td></tr>
</table>
</div>
<div class="a5rechts">
<h2 align="center">Wedstrijdformulier <? echo $row['naam'] ?></h1>
<table>
<tr><td class="noborder">Veld</td><td class="noborder">Poule</td><td class="noborder">Speelronde</td></tr>
<tr><td class="groot"><? echo $row2['veld'] ?></td><td class="groot"><? echo $row2['poule'] ?></td><td class="groot"><? echo $row2['speelronde'] ?></td></tr>
<tr><td colspan="3" class="groot"><? echo substr($row2['begin'],0,5) ?> uur</td></tr>
<tr><td class="noborder">Thuis</td><td class="noborder"></td><td class="noborder">Uit</td></tr>
<tr><td class="middelgroot"><? echo $row2['thuisploeg'] ?><br><? echo $row2['thuis'] ?></td><td class="noborder"></td><td class="middelgroot"><? echo $row2['uitploeg'] ?><br><? echo $row2['uit'] ?></td></tr>
<tr><td class="noborder">turfvak</td><td class="noborder"></td><td class="noborder">turfvak</td></tr>
<tr><td class="groot" align="top"></td><td class="noborder"></td><td class="groot" align="top"></td></tr>
<tr><td class="noborder">uitslag</td><td class="noborder"></td><td class="noborder">uitslag</td></tr>
<tr><td class="groot"></td><td class="noborder"></td><td class="groot"></td></tr>
</table>
</div>
</div>
<?
}
?>
</body>
</html>
