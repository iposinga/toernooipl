<?
$toernooiid = $_GET['toernooiid'];
include('inc/vars.php');
$toernooivraag = mysql_query ("SELECT * FROM toernooien WHERE toernid='$toernooiid'", $db) or die(mysql);
$toernooi = mysql_fetch_array($toernooivraag);
$displdatum = date("d-m-Y",strtotime($toernooi['datum']));
?>
<!DOCTYPE html>
<html>
<head>
    <META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=UTF-8">
	<title>De Toernooiplanner</title>
	<link type="text/css" rel="stylesheet" href="inc/toernooiplanner.css"/>
</head>
<body>
<div id="boxContainerContainer">
    <div id="boxContainer">
<div id="box1">
<h1>Wedstrijdschema <? echo $toernooi['naam'] ?></h1>
<table>
<tr><th>ronde</th><th>tijd</th>
<?
$veldteller = 1;
while ($veldteller <= $toernooi['velden'])
{
echo "<th>veld ".$veldteller."</th>";
$veldteller++;
}
echo "</tr><tr>";
$wedstrvraag = mysql_query ("SELECT * FROM wedstrijden WHERE toernid='$toernooiid' ORDER BY speelronde, veld", $db) or die(mysql_error());
$rondeteller = 0;
while ($rij = mysql_fetch_array($wedstrvraag))
{
    if ($rondeteller <> $rij['speelronde'])
    {
        echo "</tr><tr><td align=right><b>".$rij['speelronde'].".</b></td><td>".substr($rij['aanvang'],0,5)." - ".substr($rij['eind'],0,5)."</td>";
        $rondeteller++;
    }
	echo "<td class=wedstr align=center>";
	echo $rij['thuisploeg']." - ".$rij['uitploeg'];
	//if (isset($rij['thuisscore']) AND isset($rij['uitscore']))
	//echo "&nbsp;<font face=\"wingdings\" color=\"green\">&#252</font>";
	//echo "<img style=\"float: rightt; margin: 0px 0px 0px 0px;\" src=\"Groen-vinkje.jpg\" width=5% >";
	echo "</td>";
    //een waarde roep je op met: $rij['<veldnaam>']; de veldnamen zijn: poule, ronde (de ronde in de poule), thuisploeg, uitploeg, aanvang, veld, speelronde (in het toernooi)
}
?>
</tr>
</table>
<!-- afsluiting box1 -->
        </div>
<!-- afsluiting boxContainer -->
    </div>
<!-- afsluiting boxContainerContainer -->
</div>
</body>
</html>
