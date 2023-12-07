<?
require_once('../session.php');
require_once('admin_inc/regel.class.php');
//kijken of er een nieuw veld toegevoegd is
$posted = $_POST['verstuurd'];
if ($posted <> '')
{
	$insertregel=new Regel();
	$insertregel->inserRegelEnUser($_POST['rule']);
	//$rule = mysql_real_escape_string($_POST['rule']);
	//mysql_query ("INSERT INTO usersengedragsregels (user_id, regel) VALUES ('$userid', '$rule')", $db) or die(mysql_error());
}
//kijken of er een veld gedelete moet worden
//$delruleid = $_GET['delruleid'];
if ($_GET['delruleid'] <> '')
{
	$delregel=new Regel();
	$delregel->deleteRegelEnUser($_GET['delruleid']);
	//mysql_query ("DELETE FROM usersengedragsregels WHERE userengedrag_id='$delruleid'", $db) or die(mysql_error());
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
Hieronder kun je gedragsregels die tijdens toernooien gelden invoeren;<br>deze 'preset-regels' verschijnen in het keuzemenu bij 'aanvullingen' van een toernooi.
</div>
<div id="box2">
<?
	$userregel=new Regel();
	$toonregels=$userregel->selectRegelsBijUser($_SESSION['user_session']);
	echo $toonregels;
	?>
<!-- afsluiting box2 -->
</div>
<!-- afsluiting boxContainer -->
</div>
<!-- afsluiting boxContainerContainer -->
</div>
</body>
</html>
