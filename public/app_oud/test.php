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


<h1 align=left>Stand</h1>
<table>
<tr><th></th><th>team</th><th>naam</th><th>p</th><th>g</th><th>v</th><th>t</th><th>s</th></tr>
<?
$ploegenvraag = mysql_query ("SELECT * FROM ploegen WHERE toernid='$toernooiid' AND poule='$poule' ORDER BY teamnr", $db) or die(mysql_error());
unset ($data);
while ($row = mysql_fetch_array($ploegenvraag))
{
    $team = $row['teamnr'];
    $ploegwedstrvraag = mysql_query ("SELECT * FROM wedstrijden WHERE toernid='$toernooiid' AND (thuisploeg='$team' OR uitploeg='$team') ORDER BY ronde", $db) or die(mysql_error());
    $punten = 0;
    $gespeeld = 0;
    $voor = 0;
    $tegen = 0;
    while ($rige = mysql_fetch_array($ploegwedstrvraag))
    {
        if ($rige['thuisscore'] <> '')
        {
        if ($team == $rige['thuisploeg'])
        {
        $punten = $punten + $rige['thuispunten'];
        $voor = $voor + $rige['thuisscore'];
        $tegen = $tegen + $rige['uitscore'];
        }
        else
        {
        $punten = $punten + $rige['uitpunten'];
        $voor = $voor + $rige['uitscore'];
        $tegen = $tegen + $rige['thuisscore'];
        }
        $gespeeld++;
        }
    }
    $naam = $row['naam'];
    $saldo = $voor - $tegen;
    //zet de data in een array
    $data[] = array('team' => $team, 'naam' => $naam, 'punten' => $punten, 'gespeeld' => $gespeeld, 'voor' => $voor, 'tegen' => $tegen, 'saldo' => $saldo);
}

// Obtain a list of columns
foreach ($data as $key => $row) {
    $points[$key]  = $row['punten'];
    $played[$key] = $row['gespeeld'];
    $doelscore[$key] = $row['saldo'];
}

// Sort the data with volume descending, edition ascending
// Add $data as the last parameter, to sort by the common key
array_multisort($points, SORT_DESC, $played, SORT_ASC, $doelscore, SORT_DESC, $data);
$positie = 1;
foreach ($data as $v1) {
    $class="standright";
    echo "<tr><td class=".$class."><b>".$positie.".</b></td>\n";
    foreach ($v1 as $v2) {
        if ($data[$positie-1]['naam'] === $v2)
        $class="standleft";
        else
        $class="standright";
        echo "<td class=".$class.">".$v2."</td>\n";
    }
    echo "</tr>";
    $positie++;
}
?>
</table>

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

<h1 align=left>Stand</h1>
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
