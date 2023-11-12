<?php
include('password_protect_mysql.php');
$oudetoernooiid = $_GET['toernid'];
$userid = $_COOKIE['userid'];
//eerst kijken of alle velden zijn ingevuld
if (empty($_POST['naam']) OR empty($_POST['datum']) OR empty($_POST['teams']) OR empty($_POST['poules']) OR empty($_POST['velden']) OR empty($_POST['aanvang']) OR empty($_POST['duur']))
{
//$naam = urlencode($_POST['naam']);
$locatie = "edittoernooi.php?toernooiid=".$oudetoernooiid."&error=1";
//echo "hoera";
}
else
{

    //kijken of de huidige datum voor de datum van het toernooi is
//kijken of er al uitslagen van wedstrijden zijn ingevuld
//include ('../includes/vars.php');
include('../inc/functions.php');
$naam = $_POST['naam'];
$insertdate = date('Y-m-d', strtotime($_POST['datum']));
$aantalteams = $_POST['teams'];
$aantalpoules = $_POST['poules'];
$aantalvelden = $_POST['velden'];
$aanvang = $_POST['aanvang'];
$duur = $_POST['duur'];
//toernooi aanmaken in de tabel 'toernooien'
mysql_query ("INSERT INTO toernooien (naam, datum, teams, poules, velden, aanvang, duur) VALUES ('$naam', '$insertdate', '$aantalteams', '$aantalpoules', '$aantalvelden', '$aanvang', '$duur')", $db) or die(mysql_error());
$toernooiid = mysql_insert_id();
//de velden tabel bijwerken; alle velden worden in de sporthal (F018) geplaatst
$teller = 1;
while ($teller <= $aantalvelden)
{
    $plek = "F018";
    mysql_query ("INSERT INTO velden (toernooi_id, veld, plek) VALUES ('$toernooiid', '$teller', '$plek')", $db) or die(mysql_error());
    $teller++;
}
//toernooi koppelen aan de user
mysql_query ("INSERT INTO toernooiusers (toernooi, user) VALUES ('$toernooiid', '$userid')", $db) or die(mysql_error());
//de teams over de poules verdelen
$teamsperpoule = intval($aantalteams / $aantalpoules);
//echo $teamsperpoule." poules met rest = ".$aantalteams % $aantalpoules."<br>";
$teamteller = 1;
$pouleteller = 1;
$restteller = $aantalteams % $aantalpoules;

while ($pouleteller <= $aantalpoules)
{
  	$poule = chr($pouleteller+64);
  	//echo "poule ".chr($pouleteller+64)." bestaat uit de teams:<br>";
  	$teamsperpouleteller = 1;
  	while ($teamsperpouleteller <= $teamsperpoule)
	{
  		//echo $teamteller."<br>";
  		//zoek in de ploegentabel of bij de ploeg ook een naam en/of klas ingevuld was
  		 $naamvraag = mysql_query ("SELECT naam, stamklas FROM ploegen WHERE toernid = '$oudetoernooiid' AND teamnr='$teamteller'", $db) or die(mysql_error());
  		 if (mysql_num_rows($naamvraag) > 0)
  		 {
	  		$namerow = mysql_fetch_array($naamvraag);
	  		$ploegnaam = $namerow['naam'];
	  		$stamklas = $namerow['stamklas'];
  		 	mysql_query ("INSERT INTO ploegen (toernid, poule, teamnr, naam, stamklas) VALUES ('$toernooiid', '$poule', '$teamteller', '$ploegnaam', '$stamklas')", $db) or die(mysql_error());
  		 }
  		 else
      	//het teamnr met de poule in de tabel 'ploegen' zetten:
    mysql_query ("INSERT INTO ploegen (toernid, poule, teamnr) VALUES ('$toernooiid', '$poule', '$teamteller')", $db) or die(mysql_error());

 	 	$teamteller++;
      	$teamsperpouleteller++;
	}
  	if ($restteller > 0)
    {
      //echo $teamteller."<br>";
      $naamquest = mysql_query ("SELECT naam, stamklas FROM ploegen WHERE toernid = '$oudetoernooiid' AND teamnr='$teamteller'", $db) or die(mysql_error());
  		 if (mysql_num_rows($naamquest) > 0)
  		 {
	  		$naamrow = mysql_fetch_array($naamquest);
	  		$ploegnaam = $naamrow['naam'];
	  		$stamklas = $naamrow['stamklas'];
  		 	mysql_query ("INSERT INTO ploegen (toernid, poule, teamnr, naam, stamklas) VALUES ('$toernooiid', '$poule', '$teamteller', '$ploegnaam', '$stamklas')", $db) or die(mysql_error());
  		 }
  		 else
      mysql_query ("INSERT INTO ploegen (toernid, poule, teamnr) VALUES ('$toernooiid', '$poule', '$teamteller')", $db) or die(mysql_error());
      $teamteller++;
      $restteller--;
    }
  $pouleteller++;
}

//nu de wedstrijden tabel vullen met alle wedstrijden uit de poules:
//je zoekt in de tabel 'ploegen' op toernooiid;
//vervolgens per poule de wedstrijden genereren mbv de functie 'poulewedstrijden':
//per poule heb je nodig: de poule-aanduiding, het laagste teamnr, het aantal teams;
//deze parameters geef je mee aan de functie 'poulewedstrijden' (samen met het toernooiid)
$poulevraag = mysql_query ("SELECT MIN(teamnr), COUNT(poule), poule FROM ploegen WHERE toernid='$toernooiid' GROUP BY poule", $db) or die(mysql_error());
while ($row = mysql_fetch_array($poulevraag))
{
  //echo "Poule ".$row['poule']." heeft ".$row['COUNT(poule)']. " ploegen en het minimum teamnr is: ".$row['MIN(teamnr)']."<br>";
  poulewedstrijden($row['COUNT(poule)'],$row['MIN(teamnr)'],$toernooiid,$row['poule']);
}
//nu de wedstrijden tabel vullen met de aanvangstijden en de velden; algoritme (stel er zijn 5 velden):
//vraag alle wedstrijden in het toernooi op en sorteer ze op ronde en poule;
//laat de eerste 5 wedstrijden beginnen op het aanvangstijdstip van het toernooi en plaats ze op veld 1 t/m 5
//laat de volgende 5 wedstrijden beginnen op het aanvangstijdstip + wedstrijdduur en plaats ze op veld 1 t/m 5
//herhaal dit zo vaak als nodig; je hebt (aantal wedstrijden) / (aantal velden) keer de wedstrijdduur nodig
$veldteller = 1;
$tijdbijhouder = date('H:i', strtotime($aanvang));
$speelronde = 1;
$wedstrvraag = mysql_query ("SELECT * FROM wedstrijden WHERE toernid='$toernooiid' ORDER BY ronde, poule", $db) or die(mysql_error());
while ($rij = mysql_fetch_array($wedstrvraag))
{
  $id = $rij['wedstrid'];
  if ($veldteller > $aantalvelden)
  {
      $veldteller = 1;
      $tijdbijhouder = date('H:i', strtotime($tijdbijhouder) + $duur * 60);
      $speelronde++;
  }
  $invaanvtijd = $tijdbijhouder;
  $inveindtijd = date('H:i', strtotime($tijdbijhouder) + $duur * 60);
  $wedstrupdate = mysql_query ("UPDATE wedstrijden SET veld='$veldteller', aanvang='$invaanvtijd', eind='$inveindtijd', speelronde='$speelronde' WHERE wedstrid='$id'", $db) or die(mysql_error());
  //echo "Speelronde is ".$speelronde.", poule is ".$rij['poule']." wedstr ".$rij['thuisploeg']. " tegen ".$rij['uitploeg']." op veld ".$veldteller." om ".date('H:i', $tijdbijhouder)."<br>";
  $veldteller++;
}
$locatie = "wedstrschema.php?toernooiid=".$toernooiid;
//echo $locatie;
}
header('Location: '.$locatie);
?>
