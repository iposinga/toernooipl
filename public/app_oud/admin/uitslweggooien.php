<?php
require_once('../session.php');
require_once('admin_inc/wedstrijd.class.php');
if(!isset($_SESSION['user_session']))
{
	header("Location: http://detoernooiplanner.nl/app/");
}
else
{
    //tabel wedstrijden bijwerken
    $wedstrijd=new Wedstrijd();
    $uitslagweg=$wedstrijd->verwijderUitslag($_GET['id']);
?>
<html>
  <body
    onLoad="window.opener.location.reload(true); window.close();">
  </body>
</html>
<?
	} ?>
