<?
require_once('../session.php');
require_once('admin_inc/winstregel.class.php');
//kijken of er een nieuw veld toegevoegd is
$posted = $_POST['verstuurd'];
if ($posted <> '')
{
	$insertregel=new Winstregel();
	$insertregel->inserWinstregelEnUser(nl2br($_POST['bepaling']));
	//$bepaling = mysql_real_escape_string(nl2br($_POST['bepaling']));
	//mysql_query ("INSERT INTO usersenwinstbepalingen (user_id, winstbepaling) VALUES ('$userid', '$bepaling')", $db) or die(mysql_error());
}
//kijken of er een veld gedelete moet worden
//$delbepid = $_GET['delbepid'];
if ($_GET['delbepid'] <> '')
{
	$delregel=new Winstregel();
	$delregel->deleteWinstregelEnUser($_GET['delbepid']);
	//mysql_query ("DELETE FROM usersenwinstbepalingen WHERE userenwinstbep_id='$delbepid'", $db) or die(mysql_error());
}
?>
<!DOCTYPE html>
<html>
<head>
    <META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=UTF-8">
	<title>De Toernooiplanner</title>
	<link type="text/css" rel="stylesheet" href="../css/toernooiplanner.css"/>
</head>
<body background="../backgrounds/background2.jpg">
<div id="boxContainerContainer">
<div id="boxContainer">
<div id="box1">
Hieronder kun je bepalingen aan de hand waarvan een winnaar wordt bepaald invoeren;<br>deze 'preset-winstbepalingen' verschijnen in het keuzemenu bij 'aanvullingen' van een toernooi.
</div>
<div id="box2">
<?
	$userwinstregel=new Winstregel();
	$toonwinstregels=$userwinstregel->selectWinstregelsBijUser($_SESSION['user_session']);
	echo $toonwinstregels;
	?>
<!-- afsluiting box2 -->
</div>
<!-- afsluiting boxContainer -->
</div>
<!-- afsluiting boxContainerContainer -->
</div>
</body>
</html>
