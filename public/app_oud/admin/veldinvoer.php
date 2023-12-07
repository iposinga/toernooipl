<?php
require_once('../session.php');
require_once('admin_inc/veld.class.php');
if(!isset($_SESSION['user_session']))
{
	header("Location: http://detoernooiplanner.nl/app/");
}
else
{


    $veld=new Veld();
    $teller = 1;
	while ($teller <= $_POST['aantal'])
	{
	    $veldplek = "veldplek".$teller;
	    $plek = $_POST[$veldplek];
	    $veld->updateVeldplek($_GET['toernooiid'],$teller,$plek);
	    //mysql_query ("UPDATE velden SET plek='$plek' WHERE toernooi_id='$toernooiid' AND veld='$teller'", $db) or die(mysql_error());
		$teller++;
	}
?>
<html>
  <body
    onLoad="window.opener.location.reload(true); window.close();">
  </body>
</html>
<?
	} ?>
