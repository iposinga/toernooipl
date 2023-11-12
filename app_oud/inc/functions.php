<?php
function poulewedstrijden($aantalteams, $startteamnr, $toernooiid, $poule, $heel)
{
include ('vars.php');
$teller = 1;
while ($teller <= $aantalteams)
{
  $teams[$teller] = $startteamnr;
  $startteamnr++;
  $teller++;
}

$aantalwedstrperronde = intval($aantalteams/2);
//echo "het aantal wedstrijden per ronde is ".$aantalwedstrperronde."<br>";

//als er een oneven aantal teams is, wordt er nog een team 0 toegevoegd; als een team tegen team 0 speelt, is dat team vrij
if ($aantalteams/2 <> intval($aantalteams/2))
{
//echo "(elke ronde is er 1 team vrij)<br>";
$teams[$teller] = 0;
$aantalteams++;
$aantalwedstrperronde++;
}
//echo '<pre>'.print_r($teams, true).'</pre>';
$ronde = 1;
//onderstaande rondeteller is nodig om de thuiswedstrijden van het eerste team om te zetten naar uitwedstrijden in poules met een oneven aantal teams; als team 1 vrij (tegen team 0 speelt) is, dan telt deze teller niet door
$ronde2 = 1;
$rondeteams = $teams;
while ($ronde <= $aantalteams - 1)
{
  //echo "ronde ".$ronde.":<br>";
  //echo '<pre>'.print_r($rondeteams, true).'</pre>';
  //de eerste wedstrijd is altijd positie 1 (positie 1 is altijd het laagste teamnr in de poule, daardoor zou dat team altijd thuis spelen.....) tegen positie 2
  if ($rondeteams[2] <> 0)
  {
  //echo $rondeteams[1]." - ".$rondeteams[2]."<br>";
    //de wedstrijd in de database zetten:
    //als de ronde even is, moet je thuis en uit omdraaien, anders......
    if ($ronde2 % 2 == 0)
        mysql_query ("INSERT INTO wedstrijden (toernid, poule, ronde, thuisploeg, uitploeg) VALUES ('$toernooiid', '$poule', '$ronde', '$rondeteams[2]', '$rondeteams[1]')", $db) or die(mysql_error());
    else
        mysql_query ("INSERT INTO wedstrijden (toernid, poule, ronde, thuisploeg, uitploeg) VALUES ('$toernooiid', '$poule', '$ronde', '$rondeteams[1]', '$rondeteams[2]')", $db) or die(mysql_error());
    $ronde2++;
  }
  else
    $vrij = 1;
  $rondewedstr = 2;
  $positie = 3;
  while ($rondewedstr <= $aantalwedstrperronde)
  {
    if ($rondeteams[$positie] > $rondeteams[$aantalteams-($positie-3)])
    {
    /* origineel die er voor zorgt dat het kleinste teamnr altijd de thuisploeg is
    $uit = $rondeteams[$positie];
  	$thuis = $rondeteams[$aantalteams-($positie-3)];
  	einde origineel, met wat er nu staat, doet de if niks */
  	$thuis = $rondeteams[$positie];
  	$uit = $rondeteams[$aantalteams-($positie-3)];
    }
    else
    {
    $thuis = $rondeteams[$positie];
  	$uit = $rondeteams[$aantalteams-($positie-3)];
    }
    if ($thuis <> 0 AND $uit <> 0)
    {
	//echo $thuis." - ".$uit."<br>";
      //de wedstrijd opslaan in de database:
      mysql_query ("INSERT INTO wedstrijden (toernid, poule, ronde, thuisploeg, uitploeg) VALUES ('$toernooiid', '$poule', '$ronde', '$thuis', '$uit')", $db) or die(mysql_error());
    }
    else
      $vrij = $uit;
	$positie++;
    $rondewedstr++;
  }
    //if ($vrij <> '')
  //echo $vrij." is vrij<br>";

$ronde++;
  //nu de array herschikken
  $teller = $aantalteams;
  $positiemax = $rondeteams[$aantalteams];
  while ($teller >= 2)
  {
    if ($teller == 2)
      $rondeteams[$teller] = $positiemax;
      else
    $rondeteams[$teller] = $rondeteams[$teller-1];
    $teller--;
  }
}
//nu evt. de terugwedstrijden invoeren als het een hele competitie is
//vraag alle wedstrijden in de poule op, sorteer ze op ronde, swap thuis- en uitploeg en tel bij de ronde het maximale aantal rondes op
if ($heel == 1)
	{
$wedstrvraag = mysql_query ("SELECT * FROM wedstrijden WHERE toernid = '$toernooiid' AND poule = '$poule' ORDER BY ronde", $db) or die(mysql_error());
while ($wedstrrij = mysql_fetch_array($wedstrvraag))
	{
	$thuis = $wedstrrij['uitploeg'];
	$uit = $wedstrrij['thuisploeg'];
	$nieuweronde = $wedstrrij['ronde'] + $ronde;
	mysql_query ("INSERT INTO wedstrijden (toernid, poule, ronde, thuisploeg, uitploeg) VALUES ('$toernooiid', '$poule', '$nieuweronde', '$thuis', '$uit')", $db) or die(mysql_error());
	}
	}
}

function swapwedstrijden($toernooiid)
{
include ('vars.php');
//array $speelrondeteams;
$rondeteller = 1;
//eerst het aantal rondes bepalen
$maxrondevraag = mysql_query ("SELECT MAX(speelronde) as maximum FROM wedstrijden WHERE toernid = '$toernooiid'", $db) or die(mysql_error());
$maxrow = mysql_fetch_array($maxrondevraag);
$aantalrondes = $maxrow['maximum'];
while ($rondeteller < $aantalrondes)
{
    $rondewedstrvraag = mysql_query ("SELECT * FROM wedstrijden WHERE toernid = '$toernooiid' AND speelronde = '$rondeteller' ORDER BY veld", $db) or die(mysql_error());
    unset($speelrondeteams);
    $i = 1;
    while ($row = mysql_fetch_array($rondewedstrvraag))
    {
        if (!in_array($row['thuisploeg'],$speelrondeteams) AND !in_array($row['uitploeg'],$speelrondeteams))
        {
           $speelrondeteams[$i] = $row['thuisploeg'];
           $i++;
           $speelrondeteams[$i] = $row['uitploeg'];
           $i++;
        }
        else
        {
            //swap met de eerstvolgende wedstrijd die kan in de volgende speelronde
            $zoekdoor = 1;
            $volgenderondeteller = $rondeteller + 1;
            $volgenderondewedstrvraag = mysql_query ("SELECT * FROM wedstrijden WHERE toernid = '$toernooiid' AND speelronde = '$volgenderondeteller' ORDER BY veld", $db) or die(mysql_error());
            while ($rij = mysql_fetch_array($volgenderondewedstrvraag) AND $zoekdoor)
            {
                 if (!in_array($rij['thuisploeg'],$speelrondeteams) AND !in_array($rij['uitploeg'],$speelrondeteams))
                 {
                     $zoekdoor = 0;
                     //swap de wedstrijd
                     $swap1thuis = $rij['thuisploeg'];
                     $swap1uit = $rij['uitploeg'];
                     $swap1wedstrid = $row['wedstrid'];
                     $swap2thuis = $row['thuisploeg'];
                     $swap2uit = $row['uitploeg'];
                     $swap2wedstrid = $rij['wedstrid'];
                     mysql_query ("UPDATE wedstrijden SET thuisploeg='$swap1thuis', uitploeg='$swap1uit' WHERE wedstrid='$swap1wedstrid'", $db) or die(mysql_error());
                     mysql_query ("UPDATE wedstrijden SET thuisploeg='$swap2thuis', uitploeg='$swap2uit' WHERE wedstrid='$swap2wedstrid'", $db) or die(mysql_error());
                    $speelrondeteams[$i] = $swap1thuis;
                    $i++;
                    $speelrondeteams[$i] = $swap1uit;
                    $i++;
                 }
            }

        }
    }
    $rondeteller++;
}
//nu de laatste rij inspecteren en swappen met de voorgaande rij
//$rondeteller is nu gelijk aan $aantalrondes
$laatsterondewedstrvraag = mysql_query ("SELECT * FROM wedstrijden WHERE toernid = '$toernooiid' AND speelronde = '$rondeteller' ORDER BY veld", $db) or die(mysql_error());
    unset($speelrondeteams);
    $i = 1;
    while ($row = mysql_fetch_array($laatsterondewedstrvraag))
    {
        if (!in_array($row['thuisploeg'],$speelrondeteams) AND !in_array($row['uitploeg'],$speelrondeteams))
        {
           $speelrondeteams[$i] = $row['thuisploeg'];
           $i++;
           $speelrondeteams[$i] = $row['uitploeg'];
           $i++;
        }
        else
        {
            //swap met de eerstvolgende wedstrijd die kan in de vorige speelronde
            $zoekdoor = 1;
            $vorigerondeteller = $rondeteller - 2;
            $vorigerondewedstrvraag = mysql_query ("SELECT * FROM wedstrijden WHERE toernid = '$toernooiid' AND speelronde = '$vorigerondeteller' ORDER BY veld", $db) or die(mysql_error());
            while ($rij = mysql_fetch_array($vorigerondewedstrvraag) AND $zoekdoor)
            {
                 if (!in_array($rij['thuisploeg'],$speelrondeteams) AND !in_array($rij['uitploeg'],$speelrondeteams))
                 {
                     $zoekdoor = 0;
                     //swap de wedstrijd
                     $swap1thuis = $rij['thuisploeg'];
                     $swap1uit = $rij['uitploeg'];
                     $swap1wedstrid = $row['wedstrid'];
                     $swap2thuis = $row['thuisploeg'];
                     $swap2uit = $row['uitploeg'];
                     $swap2wedstrid = $rij['wedstrid'];
                     mysql_query ("UPDATE wedstrijden SET thuisploeg='$swap1thuis', uitploeg='$swap1uit' WHERE wedstrid='$swap1wedstrid'", $db) or die(mysql_error());
                     mysql_query ("UPDATE wedstrijden SET thuisploeg='$swap2thuis', uitploeg='$swap2uit' WHERE wedstrid='$swap2wedstrid'", $db) or die(mysql_error());
                    $speelrondeteams[$i] = $swap1thuis;
                    $i++;
                    $speelrondeteams[$i] = $swap1uit;
                    $i++;
                 }
            }

        }
    }



}

function telwedstrijdnietmee($wedstrid,$toernid){
	//tabel ploegen bijwerken
     //wedstrijdgegevens opvragen
     include ('vars.php');
     $wedstrquery = mysql_query ("SELECT * FROM wedstrijden WHERE wedstrid='$wedstrid'", $db) or die(mysql_error());
     $wedstrrow = mysql_fetch_array($wedstrquery);
     $thuisscore = $wedstrrow['thuisscore'];
     $uitscore = $wedstrrow['uitscore'];
     $thuispunten = $wedstrrow['thuispunten'];
     $uitpunten = $wedstrrow['uitpunten'];
     $thuisploeg = $wedstrrow['thuisploeg'];
     $uitploeg = $wedstrrow['uitploeg'];
     //thuisploeg bijwerken
     $ploegquery = mysql_query ("SELECT * FROM ploegen WHERE toernid='$toernid' AND teamnr='$thuisploeg'", $db) or die(mysql_error());
	 $ploegrow = mysql_fetch_array($ploegquery);
	 $gespeeld = $ploegrow['gespeeld'] - 1;
	 $punten = $ploegrow['punten'] - $thuispunten;
	 $voor = $ploegrow['voor'] - $thuisscore;
	 $tegen = $ploegrow['tegen'] - $uitscore;
	 $saldo = $voor - $tegen;
	 $teamid = $ploegrow['ploegid'];
	 //nu thuisploeg updaten
    mysql_query ("UPDATE ploegen SET gespeeld='$gespeeld', punten='$punten', voor='$voor', tegen='$tegen', saldo='$saldo' WHERE ploegid='$teamid'", $db) or die(mysql_error());
     //uitploeg bijwerken
     $uitploegquery = mysql_query ("SELECT * FROM ploegen WHERE toernid='$tid' AND teamnr='$uitploeg'", $db) or die(mysql_error());
	 $uitploegrow = mysql_fetch_array($uitploegquery);
	 $gespeeld = $uitploegrow['gespeeld'] - 1;
	 $punten = $uitploegrow['punten'] - $uitpunten;
	 $voor = $uitploegrow['voor'] - $uitscore;
	 $tegen = $uitploegrow['tegen'] - $thuisscore;
	 $saldo = $voor - $tegen;
	 $teamid = $uitploegrow['ploegid'];
	 //nu thuisploeg updaten
    mysql_query ("UPDATE ploegen SET gespeeld='$gespeeld', punten='$punten', voor='$voor', tegen='$tegen', saldo='$saldo' WHERE ploegid='$teamid'", $db) or die(mysql_error());
    //tabel wedstrijden bijwerken
    mysql_query ("UPDATE wedstrijden SET meetellen=0 WHERE wedstrid='$wedstrid'", $db) or die(mysql_error());

}

function printpoule($toernooiid,$poule){
	include ('vars.php');
	echo "<td valign=top class=met>";
    echo "<b><i>Poule ".$poule."</i></b><br>";
    $ploegenvraag = mysql_query ("SELECT * FROM ploegen WHERE toernid='$toernooiid' AND poule='$poule' ORDER BY poule", $db) or die(mysql);
    while($row = mysql_fetch_array($ploegenvraag))
    {
        echo $row['teamnr']." = ".$row['naam']."<br>";
    }
    echo "</td>";
}

function poulestand($toernooiid,$poule) {
    //include ('vars.php');
    echo "<table><tr><th></th><th>team</th><th>naam</th><th>p</th><th>g</th><th>w</th><th>g</th><th>v</th><th colspan=3>doelsaldo</th></tr>";
$ploegensql = "SELECT * FROM ploegen WHERE toernid='$toernooiid' AND poule='$poule' ORDER BY teamnr";
$ploegenquery = mysqli_query($link, $ploegensql);
while ($row = mysqli_fetch_array($ploegenquery))
{
    $team = $row['teamnr'];
    $ploegsql = "SELECT * FROM wedstrijden WHERE toernid='$toernooiid' AND (thuisploeg='$team' OR uitploeg='$team') ORDER BY ronde";
    $ploegquery = mysqli_query($link, $ploegsql);
    $punten = 0;
    $gespeeld = 0;
    $winst = 0;
    $gelijk = 0;
    $verlies = 0;
    $voor = 0;
    $tegen = 0;
    while ($rige = mysqli_fetch_array($ploegquery))
    {
        if ($rige['thuisscore'] <> '')
        {
        if ($team == $rige['thuisploeg'])
        {
        $punten = $punten + $rige['thuispunten'];
        $voor = $voor + $rige['thuisscore'];
        $tegen = $tegen + $rige['uitscore'];
        if ($rige['thuispunten'] == 1)
        $gelijk++;
        elseif ($rige['thuispunten'] == 0)
        $verlies++;
        else
        $winst++;
        }
        else
        {
        $punten = $punten + $rige['uitpunten'];
        $voor = $voor + $rige['uitscore'];
        $tegen = $tegen + $rige['thuisscore'];
        if ($rige['uitpunten'] == 1)
        $gelijk++;
        elseif ($rige['uitpunten'] == 0)
        $verlies++;
        else
        $winst++;
        }
        $gespeeld++;
        }
    }
    $naam = $row['naam'];
    $saldo = $voor - $tegen;
    //zet de data in een array
    $data[] = array('team' => $team, 'naam' => $naam, 'punten' => $punten, 'gespeeld' => $gespeeld, 'winst' => $winst, 'gelijk' => $gelijk, 'verlies' => $verlies, 'voor' => $voor, 'tegen' => $tegen, 'saldo' => $saldo);
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
/*echo "<pre>";
print_r($data);
echo "</pre>";*/

//INGEVOEGD
$k = 0;
while ($k < count($data))
{
	if ($data[$k]['punten'] == $data[$k+1]['punten'] AND $data[$k]['gespeeld'] == $data[$k+1]['gespeeld'])
	{
		if ($data[$k+2]['punten'] < $data[$k+1]['punten'])
		{
			//de volgende ploeg heeft niet hetzelfde aantal punten
			$ploeg1 = $data[$k]['team'];
			$ploeg2 = $data[$k+1]['team'];
			$gelijkeploegensql = "SELECT * FROM wedstrijden WHERE toernid='$toernooiid' AND ((thuisploeg = '$ploeg1' AND uitploeg = '$ploeg2') OR (thuisploeg = '$ploeg2' AND uitploeg = '$ploeg1'))";
			$gelijkeploegenquery = mysqli_query($link, $gelijkeploegensql);
			$bestenrij = mysqli_fetch_array($gelijkeploegenquery);
			if ($bestenrij['thuisploeg'] == $ploeg1)
			{
				if ($bestenrij['thuisscore'] < $bestenrij['uitscore'])
				{
					//verwissel rangorde
					$dummyteam = $data[$k]['team'];
					$dummynaam = $data[$k]['naam'];
					$dummypunten = $data[$k]['punten'];
					$dummygespeeld = $data[$k]['gespeeld'];
					$dummywinst = $data[$k]['winst'];
					$dummygelijk = $data[$k]['gelijk'];
					$dummyverlies = $data[$k]['verlies'];
					$dummyvoor = $data[$k]['voor'];
					$dummytegen = $data[$k]['tegen'];
					$dummysaldo = $data[$k]['saldo'];
					$data[$k]['team'] = $data[$k+1]['team'];
					$data[$k]['naam'] = $data[$k+1]['naam'];
					$data[$k]['punten'] = $data[$k+1]['punten'];
					$data[$k]['gespeeld'] = $data[$k+1]['gespeeld'];
					$data[$k]['winst'] = $data[$k+1]['winst'];
					$data[$k]['gelijk'] = $data[$k+1]['gelijk'];
					$data[$k]['verlies'] = $data[$k+1]['verlies'];
					$data[$k]['voor'] = $data[$k+1]['voor'];
					$data[$k]['tegen'] = $data[$k+1]['tegen'];
					$data[$k]['saldo'] = $data[$k+1]['saldo'];
					$data[$k+1]['team'] = $dummyteam;
					$data[$k+1]['naam'] = $dummynaam;
					$data[$k+1]['punten'] = $dummypunten;
					$data[$k+1]['gespeeld'] = $dummygespeeld;
					$data[$k+1]['winst'] = $dummywinst;
					$data[$k+1]['gelijk'] = $dummygelijk;
					$data[$k+1]['verlies'] = $dummyverlies;
					$data[$k+1]['voor'] = $dummyvoor;
					$data[$k+1]['tegen'] = $dummytegen;
					$data[$k+1]['saldo'] = $dummysaldo;
				}
			}
			else
			{
				if ($bestenrij['uitscore'] < $bestenrij['thuisscore'])
				{
					//verwissel rangorde
					$dummyteam = $data[$k]['team'];
					$dummynaam = $data[$k]['naam'];
					$dummypunten = $data[$k]['punten'];
					$dummygespeeld = $data[$k]['gespeeld'];
					$dummywinst = $data[$k]['winst'];
					$dummygelijk = $data[$k]['gelijk'];
					$dummyverlies = $data[$k]['verlies'];
					$dummyvoor = $data[$k]['voor'];
					$dummytegen = $data[$k]['tegen'];
					$dummysaldo = $data[$k]['saldo'];
					$data[$k]['team'] = $data[$k+1]['team'];
					$data[$k]['naam'] = $data[$k+1]['naam'];
					$data[$k]['punten'] = $data[$k+1]['punten'];
					$data[$k]['gespeeld'] = $data[$k+1]['gespeeld'];
					$data[$k]['winst'] = $data[$k+1]['winst'];
					$data[$k]['gelijk'] = $data[$k+1]['gelijk'];
					$data[$k]['verlies'] = $data[$k+1]['verlies'];
					$data[$k]['voor'] = $data[$k+1]['voor'];
					$data[$k]['tegen'] = $data[$k+1]['tegen'];
					$data[$k]['saldo'] = $data[$k+1]['saldo'];
					$data[$k+1]['team'] = $dummyteam;
					$data[$k+1]['naam'] = $dummynaam;
					$data[$k+1]['punten'] = $dummypunten;
					$data[$k+1]['gespeeld'] = $dummygespeeld;
					$data[$k+1]['winst'] = $dummywinst;
					$data[$k+1]['gelijk'] = $dummygelijk;
					$data[$k+1]['verlies'] = $dummyverlies;
					$data[$k+1]['voor'] = $dummyvoor;
					$data[$k+1]['tegen'] = $dummytegen;
					$data[$k+1]['saldo'] = $dummysaldo;
				}
			}
			$k = $k + 2;
		}
		else
		{
			//de volgende ploeg heeft wel hetzelfde aantal punten
			$k = $k + 3;
		}
	}
	else
	{
		$k++;
	}
}

//EINDE INGEVOEGD

foreach ($data as $v1) {
    $class="standright";
    echo "<tr><td class=".$class."><b>".$positie.".</b></td>\n";
     /*echo "<pre>";
print_r($v1);
echo "</pre>"*/;
    foreach ($v1 as $v2 => $value) {
	    //echo $v1."-".$v2."-".$value."<br>";
        if ($v2 == 'naam')
        $class="standleft";
        else
        $class="standright";
        echo "<td class=".$class.">";
        if ($v2 == 'voor')
        echo "+";
        elseif ($v2 == 'tegen')
        echo "-";
        elseif ($v2 == 'punten')
        echo "<b>";
        echo $value;
        if ($v2 == 'punten')
        echo "</b>";
        echo "</td>\n";
    }
    echo "</tr>";
    $positie++;
}
echo "</table>";
}

function poulestandklein($toernooiid,$poule) {
include ('vars.php');
echo "<table><tr><th>team</th><th>p</th><th>ds</th><th>gem</th></tr>";
$ploegenvraag = mysql_query ("SELECT * FROM ploegen WHERE toernid='$toernooiid' AND poule='$poule' ORDER BY teamnr", $db) or die(mysql_error());
while ($row = mysql_fetch_array($ploegenvraag))
{
    $team = $row['teamnr'];
    $ploegwedstrvraag = mysql_query ("SELECT * FROM wedstrijden WHERE toernid='$toernooiid' AND (thuisploeg='$team' OR uitploeg='$team') ORDER BY ronde", $db) or die(mysql_error());
    $punten = 0;
    $gespeeld = 0;
    $winst = 0;
    $gelijk = 0;
    $verlies = 0;
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
        if ($rige['thuispunten'] == 1)
        $gelijk++;
        elseif ($rige['thuispunten'] == 0)
        $verlies++;
        else
        $winst++;
        }
        else
        {
        $punten = $punten + $rige['uitpunten'];
        $voor = $voor + $rige['uitscore'];
        $tegen = $tegen + $rige['thuisscore'];
        if ($rige['uitpunten'] == 1)
        $gelijk++;
        elseif ($rige['uitpunten'] == 0)
        $verlies++;
        else
        $winst++;
        }
        $gespeeld++;
        }
    }
    $naam = $row['naam'];
    $saldo = $voor - $tegen;
    $gemiddelde = round($punten/$gespeeld, 3);
    //zet de data in een array
    //$data[] = array('team' => $team, 'naam' => $naam, 'punten' => $punten, 'saldo' => $saldo);
    $data[] = array('team' => $team, 'punten' => $punten, 'saldo' => $saldo, 'gemiddeld' => $gemiddelde);
}

// Obtain a list of columns
foreach ($data as $key => $row) {
    $points[$key]  = $row['punten'];
    //$played[$key] = $row['gespeeld'];
    $doelscore[$key] = $row['saldo'];
}

// Sort the data with volume descending, edition ascending
// Add $data as the last parameter, to sort by the common key
array_multisort($points, SORT_DESC, $doelscore, SORT_DESC, $data);
$positie = 1;
/*echo "<pre>";
print_r($data);
echo "</pre>";*/
$k = 0;
while ($k < count($data))
{
	if ($data[$k]['punten'] == $data[$k+1]['punten'])
	{
		if ($data[$k+2]['punten'] < $data[$k+1]['punten'])
		{
			//de volgende ploeg heeft niet hetzelfde aantal punten
			$ploeg1 = $data[$k]['team'];
			$ploeg2 = $data[$k+1]['team'];
			$gelijkeploegenvraag = mysql_query ("SELECT * FROM wedstrijden WHERE toernid='$toernooiid' AND ((thuisploeg = '$ploeg1' AND uitploeg = '$ploeg2') OR (thuisploeg = '$ploeg2' AND uitploeg = '$ploeg1'))", $db) or die(mysql_error());
			$bestenrij = mysql_fetch_array($gelijkeploegenvraag);
			if ($bestenrij['thuisploeg'] == $ploeg1)
			{
				if ($bestenrij['thuisscore'] < $bestenrij['uitscore'])
				{
					//verwissel rangorde
					$dummyteam = $data[$k]['team'];
					$dummypunten = $data[$k]['punten'];
					$dummysaldo = $data[$k]['saldo'];
					$dummygemiddeld = $data[$k]['gemiddeld'];
					$data[$k]['team'] = $data[$k+1]['team'];
					$data[$k]['punten'] = $data[$k+1]['punten'];
					$data[$k]['saldo'] = $data[$k+1]['saldo'];
					$data[$k]['gemiddeld'] = $data[$k+1]['gemiddeld'];
					$data[$k+1]['team'] = $dummyteam;
					$data[$k+1]['punten'] = $dummypunten;
					$data[$k+1]['saldo'] = $dummysaldo;
					$data[$k+1]['gemiddeld'] = $dummygemiddeld;
				}
			}
			else
			{
				if ($bestenrij['uitscore'] < $bestenrij['thuisscore'])
				{
					//verwissel rangorde
					$dummyteam = $data[$k]['team'];
					$dummypunten = $data[$k]['punten'];
					$dummysaldo = $data[$k]['saldo'];
					$dummygemiddeld = $data[$k]['gemiddeld'];
					$data[$k]['team'] = $data[$k+1]['team'];
					$data[$k]['punten'] = $data[$k+1]['punten'];
					$data[$k]['saldo'] = $data[$k+1]['saldo'];
					$data[$k]['gemiddeld'] = $data[$k+1]['gemiddeld'];
					$data[$k+1]['team'] = $dummyteam;
					$data[$k+1]['punten'] = $dummypunten;
					$data[$k+1]['saldo'] = $dummysaldo;
					$data[$k+1]['gemiddeld'] = $dummygemiddeld;
				}
			}
			$k = $k + 2;
		}
		else
		{
			//de volgende ploeg heeft wel hetzelfde aantal punten
			$k = $k + 3;
		}
	}
	else
	{
		$k++;
	}
}
foreach ($data as $v1) {
    $class="standright";
    echo "<tr>";
    //echo "<td class=".$class."><b>".$positie.".</b></td>\n";
     /*echo "<pre>";
print_r($v1);
echo "</pre>"*/;
    foreach ($v1 as $v2 => $value) {
	    //echo $v1."-".$v2."-".$value."<br>";
        if ($v2 == 'naam')
        $class="standleft";
        else
        $class="standright";
        echo "<td class=".$class.">";
        if ($v2 == 'voor')
        echo "+";
        elseif ($v2 == 'tegen')
        echo "-";
        elseif ($v2 == 'punten')
        echo "<b>";
        echo $value;
        if ($v2 == 'punten')
        echo "</b>";
        echo "</td>\n";
    }
    echo "</tr>";
    $positie++;
}
echo "</table>";
}

	function voegToeAlsNietAanwezig($string,$toetevoegen,$seperator)
	{
    if ($toetevoegen<>'') {
      if ($string=='')
        $string.=$toetevoegen;
      else {
        if (substr_count($string, $toetevoegen)==0)
          $string.=$seperator.$toetevoegen;
      }
    }
		return $string;
  }

	function datumFormatting($datum, $type)
    {
      //0: za 30 jan.
        //1: 20:37 uur
        //2: 2022-01-30T20:37 --> voor value in datetime-inputveld
        //3: za 30 jan. 20:37 uur
        //4: 2022
        //5: 02-09-2022
        //6: di 2 sept. '22
        //7: 2022-09-02
        //8: 2 september 2022
        //9: 2 (de dag)
        //10: 9 (de maand)
        //11: september 2022
		try {
      $displdatum = new DateTime($datum);
      $datefmt = new IntlDateFormatter('nl_NL', IntlDateFormatter::SHORT, IntlDateFormatter::SHORT);
      switch ($type) {
        case 0:
          $datefmt->setPattern("E d MMM");
          break;
        case 1:
            $datefmt->setPattern("HH:mm 'uur'");
            break;
        case 2:
            $datefmt->setPattern("yyyy-MM-dd'T'HH:mm");
            break;
        case 3:
            $datefmt->setPattern("E d MMM HH:mm 'uur'");
            break;
        case 4:
            $datefmt->setPattern("yyyy");
            break;
        case 5:
            $datefmt->setPattern("dd-MM-yyyy");
            break;
        case 6:
            $datefmt->setPattern("E d MMM ''yy");
            break;
        case 7:
            $datefmt->setPattern("yyyy-MM-dd");
            break;
        case 8:
            $datefmt->setPattern("d MMMM yyyy");
            break;
        case 9:
            $datefmt->setPattern("d");
            break;
        case 10:
            $datefmt->setPattern("M");
            break;
        case 11:
            $datefmt->setPattern("MMMM yyyy");
            break;
        case 12:
            $datefmt->setPattern("HH:mm");
            break;
        default:
            $datefmt->setPattern("dd-MM-''yy");
      }
            $returnstmt = $datefmt->format($displdatum);
    } catch (Exception $e) {
      $returnstmt = $e->getMessage();
    }
		return $returnstmt;
    }

    function maakRoepenAchternaam($roepnaam, $tussenv, $achternaam): string
    {
      $returnstmt = $roepnaam." ";
      if($tussenv <> "" AND !is_null($tussenv))
        $returnstmt.=$tussenv." ";
      $returnstmt.=$achternaam;
      return $returnstmt;
    }

    function maakAchterenRoepnaam($roepnaam, $tussenv, $achternaam): string
    {
      $returnstmt = $achternaam.", ".$roepnaam;
      if($tussenv <> "" AND !is_null($tussenv))
        $returnstmt.=" ".$tussenv;
      return $returnstmt;
    }

    function leeftijd($gebdatum): bool|int|string
    {
      $dag = datumFormatting($gebdatum, 9);
      $maand = datumFormatting($gebdatum, 10);
      $jaar = datumFormatting($gebdatum, 4);
      $leeftijd = date('Y') - $jaar;
      $maand2 = date('m') - $maand;
      if ($maand2 < 0) {
        $leeftijd = $leeftijd - 1;
      } elseif ($maand2 == 0) {
        if (date('d') < $dag)
          $leeftijd = $leeftijd - 1;
      }
        return $leeftijd;
    }


?>
