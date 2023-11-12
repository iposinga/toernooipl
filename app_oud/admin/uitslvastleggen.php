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
    $uitslaginvoer=$wedstrijd->vulinUitslag($_GET['id'],$_POST['homescore'],$_POST['outscore']);
?>
<html>
  <body
    onLoad="window.opener.location.reload(true); window.close();">
  </body>
</html>
<?
	} ?>
