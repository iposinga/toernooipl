<?
require_once('../session.php');
require_once('admin_inc/toernooi.class.php');
require_once('admin_inc/finalewedstrijd.class.php');
require_once('admin_inc/veld.class.php');

$toernooiid = $_GET['toernooiid'];

$toernooi=new Toernooi();
$toerngeg=array();
$toerngeg=$toernooi->getToernooiparameters($toernooiid);

$finalewedstr=new Finalewedstrijd();

//kijken of er een finaleronde verwijderd is
$delfinaleronde = $_GET['finaleronde'];
if ($delfinaleronde > 0)
	$finalewedstr->deleteFinaleRonde($toernooiid, $delfinaleronde);
	//mysql_query ("DELETE FROM finalewedstrijden WHERE toernooi_id='$toernooiid' AND finaleronde='$delfinaleronde'", $db) or die(mysql_error());
//kijken of er een finaleronde is gesubmit
if ($_POST['submitted'] == 1){
	$i = 1;
	while ($i <= $_POST['velden']){
		$eerste = "thuis".$i;
		$tweede = "uit".$i;
		$derde = "wedstrnaam".$i;
		$finalewedstr->insertFinalewedstr($toernooiid,$_POST['begin'],$_POST['eind'],$_POST[$eerste],$_POST[$tweede],$_POST['ronde'],$i,$_POST[$derde]);
		$i++;
	}
}
?>
<!DOCTYPE html>
<html>
<head>
<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=UTF-8">
<title>De Toernooiplanner</title>
<link type="text/css" rel="stylesheet" href="../css/toernooiplanner.css"/>
</head>
<div class="print" align="center">
<? //echo $toerngeg['aantalvelden'] ?>
<table class="tableprint">
<!-- kijken welke velden waar zitten en dat gebruiken bij de eerste rij van de wedstrijdtabel -->
<?
$veld=new Veld();
$plekveld1=$veld->zoekVeldPlek($toernooiid,1);
$colspan1=$veld->getAantalVeldPlekken($toernooiid,$plekveld1);

$veld2 = $colspan1 + 1;
if ($veld2 <= $toerngeg['aantalvelden'])
{
	$plekveld2=$veld->zoekVeldPlek($toernooiid,$veld2);
	$colspan2=$veld->getAantalVeldPlekken($toernooiid,$plekveld2);

	$veld3 = $colspan1 + $colspan2 + 1;
	if ($veld2 <= $toerngeg['aantalvelden'])
	{
		$plekveld3=$veld->zoekVeldPlek($toernooiid,$veld3);
	}
}
else
	$colspan2 = 0;

if ($toerngeg['aantalvelden'] - $colspan1 - $colspan2 > 0)
	$colspan3 = $toerngeg['aantalvelden'] - $colspan1 - $colspan2;
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
while ($veldteller <= $toerngeg['aantalvelden'])
{
	if ($veldteller == 1 OR $veldteller == $colspan1 + 1 OR $veldteller == $colspan1 + $colspan2 + 1 OR $veldteller == $colspan1 + $colspan2 + $colspan3 + 1)
		$class="lijnonderenlinksvet";
	else
		$class="lijnonderenlinks";
	echo "<th class=$class>VELD ".$veldteller."</th>";
	$veldteller++;
}
echo "</tr><tr>";

//tabelregels met finalerondes toevoegen
$colsp = $toerngeg['aantalvelden']+2;
//echo "<tr><td class='lijnbovenenondervet' colspan='".$colsp."'><b>FINALES</b></td></tr>";
$colsp1=$colsp-2;
echo "<tr><td align='center' class='lijnonder'>VO</td>";
echo "<td align='center' class='lijnonderenlinks'>12:00 - 12:15</td>";
echo "<td align='center' class='lijnonderenlinksvet'>kwartfinale KF1<br>A1 - B2</td>";
echo "<td align='center' class='lijnonderenlinks'>kwartfinale KF2<br>A2 - B1</td>";
echo "<td align='center' class='lijnonderenlinks'>kwartfinale KF3<br>C1 - D2</td>";
echo "<td align='center' class='lijnonderenlinks'>kwartfinale KF4<br>C2 - D1</td>";
echo "<td align='center' class='lijnlinks'></td>";
echo "<td align='center' class='lijnlinks'></td></tr>";
echo "<tr><td class='lijnonder'>OR</td>";
echo "<td align='center' class='lijnonderenlinks'>12:15 - 12:30</td>";
echo "<td align='center' class='lijnonderenlinksvet'>halve finale HF1<br>winn. KF1 - winn. KF2</td>";
echo "<td align='center' class='lijnonderenlinks'>halve finale HF2<br>winn. KF3 - winn. KF4</td>";
echo "<td align='center' class='lijnlinks'></td>";
echo "<td align='center' class='lijnlinks'></td>";
echo "<td align='center' class='lijnlinks'></td>";
echo "<td align='center' class='lijnlinks'></td></tr>";
echo "<tr><td class='lijnonder'>B.</td>";
echo "<td align='center' class='lijnonderenlinks'>12:30 - 12:45</td>";
echo "<td align='center' class='lijnonderenlinksvet'>finale<br>winn. HF1 - winn. HF2</td>";
echo "<td align='center' class='lijnonderenlinks'></td>";
echo "<td align='center' class='lijnonderenlinks'></td>";
echo "<td align='center' class='lijnonderenlinks'></td>";
echo "<td align='center' class='lijnonderenlinks'></td>";
echo "<td align='center' class='lijnonderenlinks'></td></tr>";
$finalewedstr=new Finalewedstrijd();
$aantalfinalewedstr=$finalewedstr->aantalFinalewedstrijden($toernooiid);
//echo "aantal finalewedstrijden = ".$aantalfinalewedstr;

if ($aantalfinalewedstr > 0)
{
	$finmax=$finalewedstr->aantalFinaleRondes($toernooiid);

	//$finmaxvraag = mysql_query ("SELECT finaleronde FROM finalewedstrijden WHERE toernooi_id='$toernooiid' GROUP BY finaleronde", $db) or die(mysql_error());
	//$finmax = mysql_num_rows($finmaxvraag);
	//echo "finmax = ".$finmax;

	//alle wedstrijden worden bij langs gegaan en alle wedstrijden van 1 speelronde komen op 1 rij

	$aantaltoernooirondes=$toernooi->aantalRonden($toernooiid);
	//$startronde=$aantaltoernooirondes+1;
	//echo "aantal toernooirondes = ".$aantaltoernooirondes;

	$finaleschema=$finalewedstr->getFinaleSchema($toernooiid,$finmax,$colspan1,$colspan2,$colspan3,$aantaltoernooirondes);
	echo $finaleschema;
}
?>
</tr>
<!-- hier de mogelijkheid bieden om rijen wedstrijden toe te voegen -->
<form method="post" action="finalerondes.php?toernooiid=<? echo $toernooiid ?>">
<tr>
<?
$ronde=$aantaltoernooirondes+$finmax+1;
$finalerondeteller=$finmax+1;
$veldteller = 1;
echo "<td class='lijnzonder' align='right'><b>".$ronde.".</b></td><td class='lijnlinks'><input type='time' name='begin' size='4'> - <input type='time' name='eind' size='4'></td>";
echo "<input type='hidden' name='ronde' value='".$finalerondeteller."'>\n";
echo "<input type='hidden' name='submitted' value='1'>\n";
echo "<input type='hidden' name='velden' value='".$toerngeg['aantalvelden']."'>\n";
while ($veldteller <= $toerngeg['aantalvelden'])
{
    if ($veldteller == 1 OR $veldteller == $colspan1 + 1 OR $veldteller == $colspan1 + $colspan2 + 1 OR $veldteller == $colspan1 + $colspan2 + $colspan3 + 1)
	    $class="lijnlinksvet";
	else
        $class="lijnlinks";
    echo "<td class='".$class."'><input type='text' name='wedstrnaam".$veldteller."' size='16'><br><input type='text' name='thuis".$veldteller."' size='6'> - <input type='text' name='uit".$veldteller."' size='6'></td>\n";
    $veldteller++;
}
?>
<tr><td class="lijnbovenvet" colspan="<? echo $colsp; ?>" align="right"><input type="submit" value="voeg toe"></td>
</tr>
</form>
</table>
</div>
</body>
</html>


