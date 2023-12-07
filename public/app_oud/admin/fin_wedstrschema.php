<?
include('password_protect_mysql.php');
$toernooiid = $_GET['toernooiid'];
include('../inc/functions.php');
//zit al in password_protect_mysql.php in menu.php
//include ('../includes/vars.php');

$toernooivraag = mysql_query ("SELECT * FROM toernooien WHERE toernid='$toernooiid'", $db) or die(mysql);
$toernooi = mysql_fetch_array($toernooivraag);
$displdatum = date("d-m-Y",strtotime($toernooi['datum']));
if ($toernooi['achtergrond'] <> '')
$achtergrond = $toernooi['achtergrond'];
else
$achtergrond = "background2.jpg";
?>
<!DOCTYPE html>
<html>
<head>
    <META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=UTF-8">
	<title>De Toernooiplanner</title>
	<link type="text/css" rel="stylesheet" href="../inc/toernooiplanner.css"/>
	<script>
	function popupwindow(url, title, w, h)
	{
  var left = (screen.width/2)-(w/2);
  var top = (screen.height/2)-(h/2);
  return window.open(url, title, 'toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=yes, resizable=yes, copyhistory=no, width='+w+', height='+h+', top='+top+', left='+left+'');
}
</script>
</head>
<body background="../backgrounds/<? echo $achtergrond; ?>">
<div id="boxContainerContainer">
    <div id="boxContainer">
<div id="box1">
<!-- Het toernooiplanner-plaatje -->
<!-- <table align="center"><tr><td class=zonder><img src="../includes/LOGO TP.png"alt="Toernooiplanner" style= "height: 100px"></td><td class=zonder valign=middle><h1>DE TOERNOOIPLANNER</h1></td></tr></table> -->
<nav><img src="../inc/LOGO TP.png" alt="Toernooiplanner" style= "height: 50px">
	<ul>
		<li><a href="nieuw.php">NIEUW</a></li>
		<li><a href="index.php">OVERZICHT</a></li>
		<li><a href="#">OPTIES</a>
		<ul>
				<li><a href="#" onclick="popupwindow('uservelden.php', 'velden van user', '620', '670'); return false">VELDLOCATIES</a></li>
				<li><a href="#" onclick="popupwindow('usergedragsregels.php', 'gedragsregels van user', '1220', '670'); return false">GEDRAGSREGELS</a></li>
				<li><a href="#" onclick="popupwindow('userwinstbepalingen.php', 'winstbepalingen van user', '1220', '670'); return false">WINNAARBEPALING</a></li>
		</ul>
		</li>
		<li><a href="index.php?logout=1">LOG UIT</a></li>

	</ul>
	<img src="../inc/LOGO TP.png" alt="Toernooiplanner" style= "height: 50px">
	</nav>
	</div>
<div id="box2">
<h1><? echo $toernooi['naam']." op ".$displdatum ?></h1>
<!-- <div id=menu> -->
<nav>
	<ul>
		<!-- <li><a href="index.php">TEAMNAMEN</a></li> -->
		<li><a href="#" title="velden" onclick="window.open('velden.php?toernooiid=<?= $toernooiid ?>', 'velden invoer', 'width=250,height=270,top=220,left=220, resizable=yes'); return false">VELDEN</a></li>

<!--		<li> <? echo "<a href=\"#\" title=\"finales\" onclick=\"window.open('finales.php?toernid=".$toernooiid."', 'finales invoer', 'width=710,height=1028,top=10,left=10'); return false\">" ?>STAND EN FINALES</a></li>
-->
		<li> <a href="#" title="standen" onclick="window.open('standen.php?toernid=<?= $toernooiid ?>', 'klasssenstand', 'width=710,height=580,top=220,left=220, resizable=yes'); return false">STANDEN</a></li>
		<li> <a href="#" title="voeg finales en opmerkingen toe" onclick="window.open('aanvullingen.php?toernooiid=<?= $toernooiid ?>', 'print', 'width=960,height=980,top=10,left=10, resizable=yes'); return false">AANVULLINGEN</a></li>
		<li> <a href="#" title="print schema" onclick="popupwindow('../printwedstrschema.php?toernooiid=<?= $toernooiid ?>', 'print', '710', '980'); return false">PRINT</a></li>

	</ul>
</nav>
<!--	</div> -->
<table border=0>
<tr>
<?
$pouleteller=1;
while ($pouleteller <= $toernooi['poules'])
{
    echo "<td valign=top align=left class=zonder>";
    echo "Poule ".chr($pouleteller+64);
    poulestandklein($toernooiid,chr($pouleteller+64));
    echo "</td>";
    $pouleteller++;
}

?>
</table>
<p><a href="wedstrschema.php?toernooiid=<? echo $toernooiid ?>">ga terug naar wedstrijdschema</a></p>
<table>
<tr><th>ronde</th><th>tijd</th>
<?
$veldteller = 1;
while ($veldteller <= $toernooi['velden'])
{
echo "<th>veld $veldteller</th>";
$veldteller++;
}
?>
<!--Hier moeten de finale ronden komen, als ze er zijn................-->
<?

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
		//als de eerste wedstrijd van een nieuwe speelronde zich aandient, moet er weer een nieuwe rij komen met ronde en tijden
		if ($finalerondeteller <> $finrij['finaleronde'])
		{
        	echo "</tr>\n<tr><td align=right><b>".$finrij['finaleronde'].".</b></td><td>".substr($finrij['aanvang'],0,5)." - ".substr($finrij['eind'],0,5)."</td>";
			$finalerondeteller++;
    	}
		echo "<td class=wedstr align=center>";
		if ($finrij['thuisploeg'] <> '')
		{
			echo $finrij['fin_wedstrnaam']."<br><a href=\"#\" title=\"".$finrij['thuisploeg']." ".$finrij['fin_thuisscore']." - ".$finrij['fin_uitscore']." ".$finrij['uitploeg']."\" onclick=\"window.open('fin_uitslinvoer.php?id=".$finrij['finalewedstr_id']."&backgr=".$achtergrond."&tid=".$toernooiid."', 'uitslag invoer', 'width=460,height=250,top=220,left=220'); return false\">".$finrij['thuisploeg']." - ".$finrij['uitploeg']."</a>";
		}
		if (isset($finrij['thuisploegnr']) AND isset($finrij['uitploegnr']))
		{
			echo "<br>".$finrij['thuisploegnr']." - ".$finrij['uitploegnr'];
		}
		if (isset($finrij['fin_thuisscore']) AND isset($finrij['fin_uitscore']))
		{
			echo "&nbsp;<font face=\"wingdings\" color=\"green\">&#x2713;</font>";
		}
		echo "</td>\n";
		$counter++;
	}
}
?>
</tr>
<!--Einde finale rondes-->
</table>

<!-- afsluiting box2 -->
        </div>
<!-- afsluiting boxContainer zit in menu.php-->
    </div>
<!-- afsluiting boxContainerContainer zit in menu.php-->
</div>
</body>
</html>
