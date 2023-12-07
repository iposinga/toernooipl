<?php
require_once('../session.php');
require_once('admin_inc/veld.class.php');
if(!isset($_SESSION['user_session']))
{
	header("Location: http://detoernooiplanner.nl/app/");
}
else
{
//$achtergrond=$_GET['backgr'];
//$wid = $_GET['id'];
$tid = $_GET['toernooiid'];
?>
<!DOCTYPE html>
<html>
<head>
    <META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=UTF-8">
	<title>De Toernooiplanner</title>
	<link type="text/css" rel="stylesheet" href="../css/toernooiplanner.css"/>
    </META>
</head>
<body>
<div id="boxContainerContainer">
    <div id="boxContainer">
<div id="box2">
<?
	$veld=new Veld();
	$veldeninvoer=$veld->maakVeldeninvoer($tid);
	echo $veldeninvoer;
	?>
</div><!-- /box2 -->
</div><!-- /boxContainer -->
</div><!-- /boxContainerContainer -->
</body>
</html>
<? } ?>
