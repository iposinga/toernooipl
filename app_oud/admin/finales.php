<?
include('password_protect_mysql.php');
include('../inc/functions.php');
$toernooiid = $_GET['toernid'];
//kijken of er een finaleronde is toegevoegd
$verstuurd = $_POST['submitted'];
if ($verstuurd == 1)
{
$aanv = $_POST['begin'];
$end = $_POST['eind'];
$round = $_POST['ronde'];
$i = 1;
while ($i <= $_POST['velden'])
{
	$eerste = "thuis".$i;
	$tweede = "uit".$i;
	$thuispl = $_POST[$eerste];
	$uitpl = $_POST[$tweede];
	//echo "thuispl = ".$thuispl;
	$finaleinsert = mysql_query ("INSERT INTO finalewedstrijden (toernooi_id, aanvang, eind, thuisploeg, uitploeg, finaleronde, veld) VALUES ('$toernooiid', '$aanv', '$end', '$thuispl', '$uitpl', '$round', '$i')", $db) or die(mysql_error());
	$i++;
	}

}
//zit al in password_protect_mysql.php
//include ('../includes/vars.php');
$toernvraag = mysql_query ("SELECT naam, teams, poules, velden, achtergrond FROM toernooien WHERE toernid='$toernooiid'", $db) or die(mysql_error());
$toerrow = mysql_fetch_array($toernvraag);
$toernooinaam = $toerrow['naam'];
$aantalpoules = $toerrow['poules'];
if ($toerrow['achtergrond'] <> '')
$achtergrond = $toerrow['achtergrond'];
else
$achtergrond = "background2.jpg";
// kijken of er een finaleronde toegevoegd moet worden:
?>
<!DOCTYPE html>
<html>
<head>
    <META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=UTF-8">
	<title>De Toernooiplanner</title>
	<link type="text/css" rel="stylesheet" href="../includes/toernooiplanner.css"/>
</head>
<body background="../backgrounds/<? echo $achtergrond ?>">
<div id="boxContainerContainer">
    <div id="boxContainer">
    <div id="box1">
    <h1>Finales <? echo $poule ?><br><? echo $toernooinaam ?></h1>
    <div id=menu>
    <ul>
		<li><a href="wedstrschema.php?toernooiid=<? echo $toernooiid ?>">TERUG</a></li>
		</ul>
    </div>
    </div>
<div id="box2">
<h1 align=left>Standen alle poules</h1>
<!-- hoofdtabel met 1 rij en zoveel kolommen als er poules zijn -->
<table border="0"><tr>

<!-- per poule een tabel in een cel van de hoofdtabel -->
<?
$pouleteller = 1;
while ($pouleteller <= $aantalpoules)
{
    $zoekpoule = chr($pouleteller+64);
   // echo $zoekpoule;
?>
<td class=zonder valign=top>
<?
poulestand($toernooiid,$zoekpoule);
?>

</td>
<?
$pouleteller++;
}
?>
</tr>
</table>
bepaal de beste nummers 2
<h1 align=left>Finales</h1>
<!-- kijken of er een finaleregel toegevoegd moet worden, zo ja: doen -->
<?
if ($_POST['begin'] <> '' AND $_POST['eind'] <> '')
{
	$ronde = $_POST['ronde'];
	$begin = $_POST['begin'];
	$eind = $_POST['eind'];
	$veldcounter = 1;
	while ($veldcounter <= $toerrow['velden'])
	{
	$home = "thuis".$veldcounter;
	$thuis = $_POST[$home];
	$out = "uit".$veldcounter;
	$uit = $_POST[$out];
	if ($_POST[$home] > 0 AND $_POST[$out] > 0)
	mysql_query ("INSERT INTO wedstrijden (toernid, poule, speelronde, thuisploeg, uitploeg, aanvang, eind, veld) VALUES ('$toernooiid', 'Z', '$ronde', '$thuis', '$uit', '$begin', '$eind', '$veldcounter')", $db) or die(mysql_error());
	$veldcounter++;
	}
}

?>
<table>
<!-- tabelkoppen -->
<tr><th>ronde</th><th>tijd</th>
<?
    $n = 1;
    while ($n <= $toerrow['velden'])
    {
        echo "<th>veld ".$n."</th>";
        $n++;
    }
?>
</tr>
<!-- kijken of er al finalerondes toegevoegd zijn -->
<?
$finalewedstrvraag = mysql_query ("SELECT * FROM finalewedstrijden WHERE toernooi_id='$toernooiid' ORDER BY finaleronde, veld", $db) or die(mysql_error());
$rondeteller = 0;
while ($rij = mysql_fetch_array($finalewedstrvraag))
{
    if ($rondeteller <> $rij['finaleronde'])
    {
        echo "</tr><tr><td align=right><b>".$rij['finaleronde'].".</b></td><td>".substr($rij['aanvang'],0,5)." - ".substr($rij['eind'],0,5)."</td>";
        $rondeteller++;
    }
	echo "<td class=wedstr align=center>";
	if ($rij['thuisploeg'] <> '')
	{
	echo "<a href=\"#\" title=\"".$rij['thuisscore']." - ".$rij['uitscore']."\" onclick=\"window.open('uitslinvoer.php?id=".$rij['wedstrid']."&backgr=".$achtergrond."', 'uitslag invoer', 'width=400,height=200,top=220,left=220'); return false\">".$rij['thuisploeg']." - ".$rij['uitploeg']."</a>";
	if (isset($rij['thuisscore']) AND isset($rij['uitscore']))
	echo "&nbsp;<font face=\"wingdings\" color=\"green\">&#x2713;</font>";
	//echo "<img style=\"float: rightt; margin: 0px 0px 0px 0px;\" src=\"Groen-vinkje.jpg\" width=5% >";
	}
	echo "</td>";
    //een waarde roep je op met: $rij['<veldnaam>']; de veldnamen zijn: poule, ronde (de ronde in de poule), thuisploeg, uitploeg, aanvang, veld, speelronde (in het toernooi)
}
?>
<!-- hier de mogelijkheid bieden om rijen wedstrijden toe te voegen -->
<form method="post" action="finales.php?toernid=<? echo $toernooiid ?>">
<tr>
<?
$rondeteller++;
$veldteller = 1;
echo "<td align=right><b>".$rondeteller.".</b></td><td><input type=time name=begin size=4> - <input type=time name=eind size=4></td>";
echo "<input type=hidden name=ronde value=".$rondeteller.">\n";
echo "<input type=hidden name=submitted value=1>\n";
echo "<input type=hidden name=velden value=".$toerrow['velden'].">\n";
while ($veldteller <= $toerrow['velden'])
{
    echo "<td><input type=text name=thuis".$veldteller." size=1> - <input type=text name=uit".$veldteller." size=1></td>\n";
    $veldteller++;
}
?>
<td><input type=submit value="voeg toe"></td>
</tr>
</table>

</form>
<!-- afsluiting box2 -->
        </div>
<!-- afsluiting boxContainer -->
    </div>
<!-- afsluiting boxContainerContainer -->
</div>

</body>
</html>
