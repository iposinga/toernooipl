<?
require_once('../session.php');
require_once('admin_inc/poule.class.php');

$tid = $_GET['tid'];
$teamnr = $_GET['minimum'];
$aantal = $_GET['aantal'];
$teller = 1;
/*
echo "oke<br>";
echo "minimum teamnr = ".$teamnr."<br>";
echo "aantal = ".$aantal."<br>";
*/
$poule=new Poule();

while ($teller <= $aantal)
{

    $naam = "naam".$teller;
    $klas = "klas".$teller;
    $insertnaam = $_POST[$naam];
    $insertklas = $_POST[$klas];
    //echo "insertnaam = ".$insertnaam."<br>";
    //tabel wedstrijden bijwerken
    $poule->updateTeamnaam($teamnr,$tid,$insertnaam,$insertklas);
    //mysql_query ("UPDATE ploegen SET naam='$insertnaam', stamklas='$insertklas' WHERE toernid='$tid' AND teamnr='$teamnr'", $db) or die(mysql_error());
    $teller++;
    $teamnr++;
}
?>
<html>
  <body
    onLoad="opener.location.href=opener.location.href; window.close();">
  </body>
</html>
