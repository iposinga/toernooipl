<?
include('password_protect_mysql.php');
//include('../includes/vars.php');
    $wid = $_GET['id'];
    //$tid = $_GET['tid'];
     //tabel ploegen bijwerken
     //wedstrijdgegevens opvragen
     /*
	 $wedstrquery = mysql_query ("SELECT * FROM wedstrijden WHERE wedstrid='$wid'", $db) or die(mysql_error());
     $wedstrrow = mysql_fetch_array($wedstrquery);
     $thuisscore = $wedstrrow['thuisscore'];
     $uitscore = $wedstrrow['uitscore'];
     $thuispunten = $wedstrrow['thuispunten'];
     $uitpunten = $wedstrrow['uitpunten'];
     $thuisploeg = $wedstrrow['thuisploeg'];
     $uitploeg = $wedstrrow['uitploeg'];
     //thuisploeg bijwerken
     $ploegquery = mysql_query ("SELECT * FROM ploegen WHERE toernid='$tid' AND teamnr='$thuisploeg'", $db) or die(mysql_error());
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
    */
    //tabel wedstrijden bijwerken
    mysql_query ("UPDATE finalewedstrijden SET fin_thuisscore=NULL, fin_uitscore=NULL WHERE finalewedstr_id='$wid'", $db) or die(mysql_error());
   
    

?>
<html>
  <body
    onLoad="opener.location.href=opener.location.href; window.close();">
  </body>
</html>