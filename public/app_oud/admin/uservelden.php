<?
require_once('../session.php');
require_once('admin_inc/veld.class.php');
//kijken of er een nieuw veld toegevoegd is
$posted = $_POST['verstuurd'];
if ($posted <> '')
{
	$insertveld=new Veld();
	$insertveld->inserVeldEnUser($_POST['field'],$_POST['type']);
}
//kijken of er een veld gedelete moet worden
if ($_GET['delveldid'] <> '')
{
	$deleteveld=new Veld();
	$deleteveld->deleteVeldEnUser($_GET['delveldid']);
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
Hieronder kun je locaties waarop wedstrijden gespeeld worden invoeren; deze 'preset-locaties' verschijnen in het keuzemenu bij velden van een toernooi.<br>De <i>soort</i> zorgt voor de groeperingen in het keuzemenu!
</div>
<div id="box2">
<?
	$userveld=new Veld();
	$toonvelden=$userveld->selectVeldenBijUser($_SESSION['user_session']);
	echo $toonvelden;
	?>
</div><!-- afsluiting box2 -->
</div><!-- afsluiting boxContainer -->
</div><!-- afsluiting boxContainerContainer -->
</body>
</html>
