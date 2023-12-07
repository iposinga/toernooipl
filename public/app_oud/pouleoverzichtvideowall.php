<?
$toernooiid = $_GET['toernid'];
$poule = $_GET['poule'];
include('inc/vars.php');
include('inc/functions.php');
$toernvraag = mysql_query ("SELECT naam FROM toernooien WHERE toernid='$toernooiid'", $db) or die(mysql_error());
$row = mysql_fetch_array($toernvraag);
$toernooinaam = $row['naam'];

?>
<!DOCTYPE html>
<html>
<head>
    <META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=UTF-8">
	<title>De Toernooiplanner</title>
	<link type="text/css" rel="stylesheet" href="inc/toernooiplanner.css"/>
</head>
<body style="font-size: 20px;">
<div id="boxContainerContainer">
    <div id="boxContainer">
<!--    <div id="box1"> -->
<!--    <h1>Poule <? echo $poule ?></h1> -->
<!--    <h2>(<? echo $toernooinaam ?>)</h2> -->
<!--    </div> -->
<div id="box2">
<h1>Poule <? echo $poule ?></h1>
<table><tr><td class=zonder valign=top>

<p class=kop>Wedstrijdschema</p>
<table>
<tr><th>ronde</th><th>tijd</th><th colspan="3">wedstrijden (veld)</th></tr>
<?
$wedstrvraag = mysql_query ("SELECT * FROM wedstrijden WHERE toernid='$toernooiid' AND poule='$poule' ORDER BY speelronde, veld", $db) or die(mysql_error());
$rondeteller = 0;
while ($rij = mysql_fetch_array($wedstrvraag))
{
    if ($rondeteller <> $rij['speelronde'])
    {
        echo "</tr><tr><td align=right><b>".$rij['speelronde'].".</b></td><td>".substr($rij['aanvang'],0,5)." - ".substr($rij['eind'],0,5)."</td>";
        $rondeteller=$rij['speelronde'];
    }
	echo "<td class=wedstr align=center>";
	echo "".$rij['thuisploeg']." - ".$rij['uitploeg']."";

	if (isset($rij['thuisscore']) AND isset($rij['uitscore']))
	{
	//echo "<img src=\"groen-vinkje.jpg\" width=10%>";
	echo "<br><font color=darkblue>".$rij['thuisscore']." - ".$rij['uitscore']."</font>";
	}
	else
	echo " (".$rij['veld'].")";
	echo "</td>";
    //een waarde roep je op met: $rij['<veldnaam>']; de veldnamen zijn: poule, ronde (de ronde in de poule), thuisploeg, uitploeg, aanvang, veld, speelronde (in het toernooi)
}
?>
</tr>
</table>

</td><td class=zonder valign=top>

<p class=kop>Stand</p>
<?
poulestand($toernooiid,$poule);
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
