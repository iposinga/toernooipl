<?
$toernooiid = $_GET['toernid'];
$poule = $_GET['poule'];
$poule2 = chr(ord($poule)+1);
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
<div id="box2">

<table><tr><td class=zonder valign=top>
<h2 align="left">Poule <? echo $poule ?></h2>
<table>
<tr><th>ronde</th><th>tijd</th><th colspan="3">wedstrijden (veld)</th></tr>
<?

$wedstrvraag = mysql_query ("SELECT
t1.naam AS thuisnaam,
t2.naam AS uitnaam, thuisploeg, uitploeg, thuisscore, uitscore, speelronde, aanvang, eind, wedstrijden.poule, veld, wedstrid
FROM wedstrijden
LEFT JOIN ploegen AS t1
ON t1.teamnr = wedstrijden.thuisploeg
LEFT JOIN ploegen AS t2
ON t2.teamnr = wedstrijden.uitploeg
WHERE wedstrijden.toernid='$toernooiid' AND wedstrijden.poule='$poule' AND wedstrijden.toernid=t1.toernid AND wedstrijden.toernid=t2.toernid
ORDER BY speelronde, veld", $db) or die(mysql_error());

//$wedstrvraag = mysql_query ("SELECT * FROM wedstrijden WHERE toernid='$toernooiid' AND poule='$poule' ORDER BY speelronde, veld", $db) or die(mysql_error());
$rondeteller = 0;
while ($rij = mysql_fetch_array($wedstrvraag))
{
    if ($rondeteller <> $rij['speelronde'])
    {
        echo "</tr>\n<tr>\n<td align=right><b>".$rij['speelronde'].".</b></td><td>".substr($rij['aanvang'],0,5)." - ".substr($rij['eind'],0,5)."</td>";
        $rondeteller=$rij['speelronde'];
    }
	echo "<td class=wedstr align=center>";
	echo $rij['thuisploeg']." - ".$rij['uitploeg'];

	if (isset($rij['thuisscore']) AND isset($rij['uitscore']))
	{
	echo "&nbsp;|&nbsp;<font size=-0.5 color=red>".$rij['thuisscore']." - ".$rij['uitscore']."</font>";
	}
	else
	echo " (".$rij['veld'].")";
	echo "</td>\n";
    //een waarde roep je op met: $rij['<veldnaam>']; de veldnamen zijn: poule, ronde (de ronde in de poule), thuisploeg, uitploeg, aanvang, veld, speelronde (in het toernooi)
}
?>
</tr>
</table>

</td><td class=zonder valign=top>


<h2 align=left>Stand</h2>
<?
poulestand($toernooiid,$poule);
?>

</td></tr>

<!-- tweede poule -->
<tr><td class=zonder valign=top>
<h2 align="left">Poule <? echo $poule2 ?></h2>
<table>
<tr><th>ronde</th><th>tijd</th><th colspan="3">wedstrijden (veld)</th></tr>
<?

$wedstrvraag = mysql_query ("SELECT
t1.naam AS thuisnaam,
t2.naam AS uitnaam, thuisploeg, uitploeg, thuisscore, uitscore, speelronde, aanvang, eind, wedstrijden.poule, veld, wedstrid
FROM wedstrijden
LEFT JOIN ploegen AS t1
ON t1.teamnr = wedstrijden.thuisploeg
LEFT JOIN ploegen AS t2
ON t2.teamnr = wedstrijden.uitploeg
WHERE wedstrijden.toernid='$toernooiid' AND wedstrijden.poule='$poule2' AND wedstrijden.toernid=t1.toernid AND wedstrijden.toernid=t2.toernid
ORDER BY speelronde, veld", $db) or die(mysql_error());

$rondeteller = 0;
while ($rij = mysql_fetch_array($wedstrvraag))
{
    if ($rondeteller <> $rij['speelronde'])
    {
        echo "</tr>\n<tr>\n<td align=right><b>".$rij['speelronde'].".</b></td><td>".substr($rij['aanvang'],0,5)." - ".substr($rij['eind'],0,5)."</td>";
        $rondeteller=$rij['speelronde'];
    }
	echo "<td class=wedstr align=center>";
	echo $rij['thuisploeg']." - ".$rij['uitploeg'];

	if (isset($rij['thuisscore']) AND isset($rij['uitscore']))
	{
	echo "&nbsp;|&nbsp;<font size=-0.5 color=red>".$rij['thuisscore']." - ".$rij['uitscore']."</font>";
	}
	else
	echo " (".$rij['veld'].")";
	echo "</td>\n";
    //een waarde roep je op met: $rij['<veldnaam>']; de veldnamen zijn: poule, ronde (de ronde in de poule), thuisploeg, uitploeg, aanvang, veld, speelronde (in het toernooi)
}
?>
</tr>
</table>

</td><td class=zonder valign=top>

<h2 align=left>Stand</h2>
<?
poulestand($toernooiid,$poule2);
?>


</td></tr>
<!-- einde tweede poule-->


</table>

<!-- afsluiting box2 -->
        </div>
<!-- afsluiting boxContainer -->
    </div>
<!-- afsluiting boxContainerContainer -->
</div>

</body>
</html>
