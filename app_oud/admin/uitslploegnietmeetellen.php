<html>
	  <body>
<?
include('password_protect_mysql.php');
include('../inc/functions.php');
    $team = $_GET['team'];
    $tid = $_GET['tid'];
    $wedstrijdenquery = mysql_query ("SELECT * FROM wedstrijden WHERE toernid='$tid' AND (thuisploeg='$team' OR uitploeg='$team')", $db) or die(mysql_error());
    //echo "oke";

     while ($wedstrijdenrow = mysql_fetch_array($wedstrijdenquery))
     {
	  $poule = $wedstrijdenrow['poule'];
	  if($wedstrijdenrow['meetellen'] == 1)
	  telwedstrijdnietmee($wedstrijdenrow['wedstrid'],$tid);

     }

     //nieuwe stand in de poule opvragen
     //echo $poule;
     $poulequery = mysql_query ("SELECT * FROM ploegen WHERE toernid='$tid' AND poule='$poule' ORDER BY punten DESC, saldo DESC", $db) or die(mysql_error());
     ?>
     <table border="1"><tr><th></th><th>team</th><th>punten</th><th>gesp</th><th>saldo</th></tr>
     <?
	     $plaats=1;
     while ($poulequeryrow = mysql_fetch_array($poulequery))
     {
	  echo "<tr><td>".$plaats."</td><td>".$poulequeryrow['teamnr']."</td><td>".$poulequeryrow['punten']."</td><td>".$poulequeryrow['gespeeld']."</td><td>".$poulequeryrow['saldo']."</td></tr>\n";
	  $plaats++;
     }



?>
     </table>

  </body>
</html>
