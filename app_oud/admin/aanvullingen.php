<?
include('password_protect_mysql.php');
$userid = $_COOKIE['userid'];
setlocale(LC_ALL, 'nl_NL'); //NL_nl werkt niet met strftime
//echo strftime('%l'); // mei
$toernooiid = $_GET['toernooiid'];
include('../inc/vars.php');
include('../inc/functions.php');
//kijken of de toernooinaam gewijzigd moet worden
$wijzignaam = $_POST['nammeferstj'];
if ($wijzignaam > 0)
{
	$newname = $_POST['namme'];
mysql_query ("UPDATE toernooien SET naam='$newname' WHERE toernid='$toernooiid'", $db) or die(mysql_error());
}
//kijken of er een finaleronde verwijderd is
$delfinaleronde = $_GET['finaleronde'];
if ($delfinaleronde > 0)
mysql_query ("DELETE FROM finalewedstrijden WHERE toernooi_id='$toernooiid' AND finaleronde='$delfinaleronde'", $db) or die(mysql_error());
//kijken of er een mededeling verwijderd is
$delmedid = $_GET['meddelid'];
if ($delmedid > 0)
mysql_query ("DELETE FROM mededelingen WHERE mededelingid='$delmedid'", $db) or die(mysql_error());
//echo $toernooiid;
//kijken of er een mededeling over winnaar/gedrag is gesubmit
$sent = $_POST['sent'];
if ($sent == 1)
{
	//gedrag is een string met gekozen cijfers gescheiden door komma's
	$gedrag=implode(',', $_POST['gedrag']);
	//$gedrag = $_POST['gedrag'];
	//echo $gedrag;
	$winst = $_POST['winst'];
	mysql_query ("UPDATE toernooien SET gedrag='$gedrag', winnaar='$winst' WHERE toernid='$toernooiid'", $db) or die(mysql_error());

}
//kijken of er een finaleronde is gesubmit
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
	$derde = "wedstrnaam".$i;
	$thuispl = $_POST[$eerste];
	$uitpl = $_POST[$tweede];
	$wnaam = $_POST[$derde];
	//echo "thuispl = ".$thuispl;
	$finaleinsert = mysql_query ("INSERT INTO finalewedstrijden (toernooi_id, aanvang, eind, thuisploeg, uitploeg, finaleronde, veld, fin_wedstrnaam) VALUES ('$toernooiid', '$aanv', '$end', '$thuispl', '$uitpl', '$round', '$i', '$wnaam')", $db) or die(mysql_error());
	$i++;
	}

}
//even kijken of er een mededeling is gesubmit
$medregel = $_POST['ferstjoerd'];
if ($medregel == 1)
{
$medregel1 = $_POST['cell1'];
$medregel2 = $_POST['cell2'];
$medregel3 = $_POST['cell3'];
if ($medregel1 <> '' OR $medregel2 <> '' OR $medregel3 <> '')
mysql_query ("INSERT INTO mededelingen (toernooi_id, cel1, cel2, cel3) VALUES ('$toernooiid', '$medregel1', '$medregel2', '$medregel3')", $db) or die(mysql);
}

$med = $_POST['submitted'];
if ($med == 1)
{
$mededeling = nl2br($_POST['comment']);
mysql_query ("UPDATE toernooien SET mededeling = '$mededeling' WHERE toernid='$toernooiid' ", $db) or die(mysql);
}

$toernooivraag = mysql_query ("SELECT * FROM toernooien WHERE toernid='$toernooiid'", $db) or die(mysql);
$toernooi = mysql_fetch_array($toernooivraag);
//$displdatum = date("l d F Y",strtotime($toernooi['datum'])); //werkt niet in het Nederlands

?>
<!DOCTYPE html>
<html>
<head>
    <META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=UTF-8">
	<title>De Toernooiplanner</title>
	<link type="text/css" rel="stylesheet" href="../includes/toernooiplanner.css"/>
</head>
<body>
<form name="titel" method="post" action="aanvullingen.php?toernooiid=<? echo $toernooiid ?>">
	<input type="hidden" name="nammeferstj" value="1">
<h2 align=center><input class="inp" type="text" size="26" name="namme" value="<? echo $toernooi['naam'] ?>"><? echo " - ".strftime("%A %e %B %Y",strtotime($toernooi['datum'])) ?></h2>
<p align=center><input type=submit value="wijzig naam"></p>
</form>

<div class=print align=center>
<table class=tableprint>
<!-- kijken welke velden waar zitten en dat gebruiken bij de eerste rij van de wedstrijdtabel -->
<?
$veld1vraag = mysql_query ("SELECT * FROM velden WHERE toernooi_id='$toernooiid' AND veld='1'", $db) or die(mysql);
$row1 = mysql_fetch_array($veld1vraag);
$plekveld1 = $row1['plek'];
$plekveld1vraag =  mysql_query ("SELECT * FROM velden WHERE toernooi_id='$toernooiid' AND plek='$plekveld1'", $db) or die(mysql);
//mysql_num_rows ($plekveld1vraag geeft het aantal velden dat op de plek wordt gespeeld van veld 1
$colspan1 = mysql_num_rows($plekveld1vraag);

$veld2 = $colspan1 + 1;
//echo "tweede veld = ".$veld2;
if ($veld2 <= $toernooi['velden'])
{
//echo "yep";
$veldvraag2 = mysql_query ("SELECT * FROM velden WHERE toernooi_id='$toernooiid' AND veld='$veld2'", $db) or die(mysql);
$row2 = mysql_fetch_array($veldvraag2);
$plekveld2 = $row2['plek'];
//echo $plekveld2;
$plekveld2vraag =  mysql_query ("SELECT * FROM velden WHERE toernooi_id='$toernooiid' AND plek='$plekveld2'", $db) or die(mysql);
//mysql_num_rows ($plekveld1vraag geeft het aantal velden dat op de plek wordt gespeeld van het eerste veld dat op een andere plek dan veld 1 wordt gespeeld
$colspan2 = mysql_num_rows($plekveld2vraag);
$veld3 = $colspan1 + $colspan2 + 1;
	if ($veld2 <= $toernooi['velden'])
	{
	$veldvraag3 = mysql_query ("SELECT * FROM velden WHERE toernooi_id='$toernooiid' AND veld='$veld3'", $db) or die(mysql);
	$row3 = mysql_fetch_array($veldvraag3);
	$plekveld3 = $row3['plek'];
	}
}
else
$colspan2 = 0;

if ($toernooi['velden'] - $colspan1 - $colspan2 > 0)
$colspan3 = $toernooi['velden'] - $colspan1 - $colspan2;
else
$colspan3 = 0
?>
<tr>
<th class=lijnonder colspan=2></th><th class=lijnonderenlinksvet colspan=<? echo $colspan1 ?>><? echo $plekveld1 ?></th>
<? if ($colspan2 > 0)
	{
		?>
<th class=lijnonderenlinksvet colspan=<? echo $colspan2 ?>><? echo $plekveld2 ?></th>
<?
	}
	?>
<? if ($colspan3 > 0)
	{
		?>
<th class=lijnonderenlinksvet colspan=<? echo $colspan3 ?>><? echo $plekveld3 ?></th>
<?
	}
	?>
</tr>
<tr><th class=lijnonder>NR</th><th class=lijnonderenlinks>TIJD</th>
<?
$veldteller = 1;
while ($veldteller <= $toernooi['velden'])
{
if ($veldteller == 1 OR $veldteller == $colspan1 + 1 OR $veldteller == $colspan1 + $colspan2 + 1 OR $veldteller == $colspan1 + $colspan2 + $colspan3 + 1)
$class="lijnonderenlinksvet";
else
$class="lijnonderenlinks";
echo "<th class=$class>VELD ".$veldteller."</th>";
$veldteller++;
}
echo "</tr><tr>";
$maxvraag = mysql_query ("SELECT speelronde FROM wedstrijden WHERE toernid='$toernooiid' AND poule <> 'Z' GROUP BY speelronde", $db) or die(mysql_error());
//$rijmax = mysql_fetch_array($maxvraag);
$max = mysql_num_rows($maxvraag);

$wedstrvraag = mysql_query ("SELECT speelronde, aanvang, eind, thuisploeg, uitploeg, veld FROM wedstrijden WHERE toernid='$toernooiid' AND (speelronde=1 OR speelronde='$max') ORDER BY speelronde, veld", $db) or die(mysql_error());


//echo $max;

$rondeteller = 0;
//alle wedstrijden worden bij langs gegaan en alle wedstrijden van 1 speelronde komen op 1 rij
while ($rij = mysql_fetch_array($wedstrvraag))
{

    $class="wedstrprint";
    $class2="lijnzonder";
//als de eerste wedstrijd van een nieuwe speelronde zich aandient, moet er weer een nieuwe rij komen met ronde en tijden
    if ($rondeteller <> $rij['speelronde'])
    {
        $counter = 1;
        /*if (($rondeteller+1)%5 == 0)
        {
        $class="lijnonderenlinks";
        $class2="lijnonder";
        }
        if ($rondeteller+1 == $max)
        {
	        $class="lijnlinks";
	        $class2="lijnzonder";
        }*/
        echo "</tr>";
       // if ($rondeteller == 1 OR $rondeteller == $max)
        echo "<tr><td align=right class=$class2><b>".$rij['speelronde'].".</b></td><td class=$class>".substr($rij['aanvang'],0,5)." - ".substr($rij['eind'],0,5)."</td>";

        $rondeteller=$rij['speelronde'];
        //if ($rij['speelronde']==28)
        //$rondeteller=28;
    }
//rij wordt aangevuld met alle wedstrijden in de speelronde
    if (($rondeteller)%5 == 0)
    {
	    if ($rij['veld'] == 1 OR $rij['veld'] == $colspan1 + 1 OR $rij['veld'] == $colspan1 + $colspan2 + 1 OR $rij['veld'] == $colspan1 + $colspan2 + $colspan3 + 1)
	    $class="lijnonderenlinksvet";
	    else
	    $class="lijnonderenlinks";
    }
    else
    {
	    if ($rij['veld'] == 1 OR $rij['veld'] == $colspan1 + 1 OR $rij['veld'] == $colspan1 + $colspan2 + 1 OR $rij['veld'] == $colspan1 + $colspan2 + $colspan3 + 1)
	    $class="lijnlinksvet";
	    else
	    $class="lijnlinks";
    }

    if ($rondeteller == $max)
    {
	    if ($rij['veld'] == 1 OR $rij['veld'] == $colspan1 + 1 OR $rij['veld'] == $colspan1 + $colspan2 + 1 OR $rij['veld'] == $colspan1 + $colspan2 + $colspan3 + 1)
	    $class="lijnlinksvet";
	    else
        $class="lijnlinks";
    }
    //if ($rondeteller == 1 OR $rondeteller == $max)
    //{
	echo "<td class=$class align=center>";
	echo "".$rij['thuisploeg']." - ".$rij['uitploeg'];
	echo "</td>";
	//}
	$counter++;
}
//tabel aanvullen met lege cellen
if ($counter < $toernooi['velden'])
{
    while ($counter <= $toernooi['velden'])
    {
        if ($counter == 1 OR $rij['veld'] == $colspan1 + 1 OR $counter == $colspan1 + 1 OR $counter == $colspan1 + $colspan2 + 1 OR $counter == $colspan1 + $colspan2 + $colspan3 + 1)
        $class="lijnlinksvet";
        	else
        $class="lijnlinks";
        	echo "<td class=$class></td>";
        $counter++;
    }
}
?>
</tr>
<!-- tabelregels met finalerondes toevoegen -->
<?
$colsp = $toernooi['velden']+2;
echo "<tr><td class=lijnbovenenondervet colspan=".$colsp."><b>FINALES</b></td><tr>";
$finalevraag = mysql_query ("SELECT * FROM finalewedstrijden WHERE toernooi_id='$toernooiid' ORDER BY finaleronde, veld", $db) or die(mysql);
if (mysql_num_rows($finalevraag) > 0)
{



$finmaxvraag = mysql_query ("SELECT finaleronde FROM finalewedstrijden WHERE toernooi_id='$toernooiid' GROUP BY finaleronde", $db) or die(mysql_error());

$finmax = mysql_num_rows($finmaxvraag);
//echo $finmax;

//alle wedstrijden worden bij langs gegaan en alle wedstrijden van 1 speelronde komen op 1 rij
$finalerondeteller = 0;
while ($finrij = mysql_fetch_array($finalevraag))
{

    $class="wedstrprint";
    $class2="lijnzonder";
//als de eerste wedstrijd van een nieuwe speelronde zich aandient, moet er weer een nieuwe rij komen met ronde en tijden
    if ($finalerondeteller <> $finrij['finaleronde'])
    {
        $counter = 1;
        if (($finalerondeteller+1)%5 == 0)
        {
        $class="lijnonderenlinks";
        $class2="lijnonder";
        }
        if ($finalerondeteller+1 == $finmax)
        {
	        $class="lijnlinks";
	        $class2="lijnzonder";
        }
        $ronde = $rondeteller + $finrij['finaleronde'];
        echo "</tr>\n<tr><td align=right class=$class2><a href=\"aanvullingen.php?toernooiid=".$toernooiid."&finaleronde=".$finrij['finaleronde']. ".$ronde.".</b></td><td class=$class>".substr($finrij['aanvang'],0,5)." - ".substr($finrij['eind'],0,5)."</td>";

        $finalerondeteller++;
    }
//rij wordt aangevuld met alle wedstrijden in de speelronde
    if (($finalerondeteller)%5 == 0)
    {
	    if ($finrij['veld'] == 1 OR $finrij['veld'] == $colspan1 + 1 OR $finrij['veld'] == $colspan1 + $colspan2 + 1 OR $finrij['veld'] == $colspan1 + $colspan2 + $colspan3 + 1)
	    $class="lijnonderenlinksvet";
	    else
	    $class="lijnonderenlinks";
    }
    else
    {
	    if ($finrij['veld'] == 1 OR $finrij['veld'] == $colspan1 + 1 OR $finrij['veld'] == $colspan1 + $colspan2 + 1 OR $finrij['veld'] == $colspan1 + $colspan2 + $colspan3 + 1)
	    $class="lijnlinksvet";
	    else
	    $class="lijnlinks";
    }

    if ($finalerondeteller == $finmax)
    {
	    if ($finrij['veld'] == 1 OR $finrij['veld'] == $colspan1 + 1 OR $finrij['veld'] == $colspan1 + $colspan2 + 1 OR $finrij['veld'] == $colspan1 + $colspan2 + $colspan3 + 1)
	    $class="lijnlinksvet";
	    else
        $class="lijnlinks";
    }
	echo "<td class=$class align=center>";
	if ($finrij['thuisploeg'] <> '')
		echo $finrij['fin_wedstrnaam']."<br>".$finrij['thuisploeg']." - ".$finrij['uitploeg'];
	echo "</td>\n";
	$counter++;
}
}
?>
</tr>
<!-- hier de mogelijkheid bieden om rijen wedstrijden toe te voegen -->
<form method="post" action="aanvullingen.php?toernooiid=<? echo $toernooiid ?>">
<tr>
<?
$ronde++;
$finalerondeteller++;
$veldteller = 1;
echo "<td class=lijnzonder align=right><b>".$ronde.".</b></td><td class=lijnlinks><input type=time name=begin size=4> - <input type=time name=eind size=4></td>";
echo "<input type=hidden name=ronde value=".$finalerondeteller.">\n";
echo "<input type=hidden name=submitted value=1>\n";
echo "<input type=hidden name=velden value=".$toernooi['velden'].">\n";
while ($veldteller <= $toernooi['velden'])
{
    if ($veldteller == 1 OR $veldteller == $colspan1 + 1 OR $veldteller == $colspan1 + $colspan2 + 1 OR $veldteller == $colspan1 + $colspan2 + $colspan3 + 1)
	    $class="lijnlinksvet";
	    else
        $class="lijnlinks";
    echo "<td class=".$class.">naam: <input type=text name=wedstrnaam".$veldteller." size=10><br><input type=text name=thuis".$veldteller." size=4> - <input type=text name=uit".$veldteller." size=4></td>\n";
    $veldteller++;
}
?>
<tr><td class=lijnbovenvet colspan=<? echo $colsp; ?> align=right><input type=submit value="voeg toe"></td>
</tr>
</form>
<!-- tabelregels met mededelingen toevoegen -->
<?
	$mededelingenvraag = mysql_query ("SELECT * FROM mededelingen WHERE toernooi_id='$toernooiid'", $db) or die(mysql);
	$clmsp = $toernooi['velden'];
	while ($medrij = mysql_fetch_array($mededelingenvraag))
	{
		echo "<tr><td class=lijnbovenvet><a href=\"aanvullingen.php?toernooiid=".$toernooiid."&meddelid=".$medrij['mededelingid']."\"><img src=\"../includes/Trash.png\" valign=middle></a>".$medrij['cel1']."</td><td class=lijnlinksenbovenvet>".$medrij['cel2']."</td><td class=lijnbovenenlinksvet colspan=".$clmsp.">".$medrij['cel3']."</td></tr>\n";
	}

?>
<!-- tabelregels met invoervelden toevoegen -->
<form name"medregelform" method="post" action="aanvullingen.php?toernooiid=<? echo $toernooiid ?>">
	<input type="hidden" name="ferstjoerd" value="1">
	<tr><td class=lijnbovenvet><input name="cell1" type="text" value="" size="1"></td><td class=lijnlinksenbovenvet><input name="cell2" type="text" value="" size="10"></td><td class=lijnbovenenlinksvet colspan="<? echo $toernooi['velden'] ?>"><input name="cell3" type="text" value="" size="<? echo $toernooi['velden']*12 ?>"></td></tr>
	<tr><td class=lijnboven align=right colspan="<? echo $toernooi['velden']+2 ?>"><input type="submit" value="voeg toe"></td></tr>
</form>
</table>

</div>
<div class=printmededeling>
	<b>Winnaar:</b><br>
		<form name="winstform" method="post" action="aanvullingen.php?toernooiid=<? echo $toernooiid ?>">
			<input type=hidden name=sent value=1>

<?
//vraag de winstbepalingen op:
$winstbepvraag = mysql_query ("SELECT * FROM usersenwinstbepalingen WHERE user_id='$userid' ORDER BY userenwinstbep_id", $db) or die(mysql_error());
$u = 1;
echo "<table>";
while ($winstrij = mysql_fetch_array($winstbepvraag))
{
	echo "<tr><td valign=top class=zonder><input type=radio name=winst value=".$winstrij['userenwinstbep_id'];
	if ($toernooi['winnaar']== $winstrij['userenwinstbep_id'])
		echo " CHECKED";
	echo "></td><td class=zonder>".$winstrij['winstbepaling']."</td></tr>";
	$u++;
}
echo "</table>";
//vraag de gedragsregels op:
$gedragsvraag = mysql_query ("SELECT * FROM usersengedragsregels WHERE user_id='$userid' ORDER BY userengedrag_id", $db) or die(mysql_error());
$u = 1;
echo "<br><b>Denk om:</b><br>";
while ($gedragrij = mysql_fetch_array($gedragsvraag))
{
	echo "<input type=checkbox name=gedrag[] value=".$gedragrij['userengedrag_id'];
	if (substr_count($toernooi['gedrag'],$gedragrij['userengedrag_id']) > 0) echo " CHECKED";
	echo "> ".$gedragrij['regel']."<br>";
	$u++;
}
?>
			<p align="right"><input type=submit value="voeg toe"></p>
			</form>
</div>


</body>
</html>
