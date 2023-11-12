<?
include('password_protect_mysql.php');
$achtergrond=$_GET['backgr'];
?>
<!DOCTYPE html>
<html>
<head>
    <META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=UTF-8">
	<title>Uitslag invoer</title>
	<link type="text/css" rel="stylesheet" href="../includes/toernooiplanner.css"/>
</head>
<body background="../backgrounds/<? echo $achtergrond; ?>">
<div id="boxContainerContainer">
    <div id="boxContainer">
<div id="box2">
<?
//include('../includes/vars.php');
$wid = $_GET['id'];

$wedstrvraag = mysql_query ("SELECT * FROM finalewedstrijden WHERE finalewedstr_id='$wid'", $db) or die(mysql_error());




//$wedstrvraag = mysql_query ("SELECT A.naam, B.Naam FROM wedstrijden, ploegen as pl WHERE wedstrid='$wid' AND wedstrijden.toernid=ploegthuis.toernid AND wedstrijden.toernid=ploeguit.toernid AND ploeguit.uitploeg=teamnr AND ploeguit.uitploeg=teamnr", $db) or die(mysql_error());
$row = mysql_fetch_array($wedstrvraag)
?>

<form method="post" action="fin_uitslvastleggen.php?id=<? echo $wid ?>">
<!--<input type="hidden" name="toernooi" value="<? echo $_GET['tid'] ?>">-->	
<input type="hidden" name="thuis" value="<? echo $row['thuisploeg'] ?>">
<input type="hidden" name="uit" value="<? echo $row['uitploeg'] ?>">		
<table border="0">
<tr><td class="zonder" colspan=3>Voer de uitslag in voor de<br>wedstrijd: <? echo $row['fin_wedstrnaam']; ?></td><td class="zonder">
<?
if ($row['fin_thuisscore'] <> '' AND $row['fin_uitscore'] <> '')
echo "<button color=red type=\"submit\" formaction=\"fin_uitslweggooien.php?id=".$wid."\">verwijder</button>";
?>
</td></tr>
<tr>
<td class="zonder"><? echo $row['thuisploeg'] ?></td>
<td class="zonder">-</td>
<td class="zonder"><? echo $row['uitploeg'] ?></td>
<td class="zonder" align="right"><input type=reset value="  reset  "></td>
</tr>
<tr>
<td class="zonder"><input class="midden" type=text size="5" name="homesquadnr" value=<? echo $row['thuisploegnr'] ?>></td>
<td class="zonder"><font size="2">(evt. teamnrs)</font></td>
<td class="zonder"><input class="midden" type=text size="5" name="awaysquadnr" value=<? echo $row['uitploegnr'] ?>></td>
<td class="zonder" align="right"></td>
</tr>
<tr>
<td class="zonder"><input class="midden" type=text size="3" name="homescore" value=<? echo $row['fin_thuisscore'] ?>></td>
<td class="zonder"><font size="2">(eindstand)</td>
<td class="zonder"><input class="midden" type=text size="3" name="outscore" value=<? echo $row['fin_uitscore'] ?>></td>
<td class="zonder" align="right"><input type=submit value=verwerk></td></tr>
</table>
</form>
<!-- afsluiting box2 -->       
        </div>
<!-- afsluiting boxContainer zit in menu.php-->
    </div>
<!-- afsluiting boxContainerContainer zit in menu.php-->
</div>
</body>
</html>