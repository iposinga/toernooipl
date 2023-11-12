<?
include('password_protect_mysql.php');
//include('../includes/vars.php');
if (($_POST['homescore'] <> '' AND $_POST['outscore'] <> '') OR $_POST['homesquadnr'] <> '' OR $_POST['awaysquadnr'])
{
    $thuis = $_POST['homescore'];
    $uit = $_POST['outscore'];
    $thuisnr = $_POST['homesquadnr'];
    $uitnr = $_POST['awaysquadnr'];
    $wid = $_GET['id'];
    if ($_POST['homescore'] <> '' AND $_POST['outscore'] <> '')
    	mysql_query ("UPDATE finalewedstrijden SET fin_thuisscore='$thuis', fin_uitscore='$uit', thuisploegnr='$thuisnr', uitploegnr='$uitnr' WHERE finalewedstr_id='$wid'", $db) or die(mysql_error());
    else
    	mysql_query ("UPDATE finalewedstrijden SET thuisploegnr='$thuisnr', uitploegnr='$uitnr' WHERE finalewedstr_id='$wid'", $db) or die(mysql_error());	
}
?>
<html>
  <body onLoad="opener.location.href=opener.location.href; window.close();">
  </body>
</html>