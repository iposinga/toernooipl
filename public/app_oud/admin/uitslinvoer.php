<?php
require_once('../session.php');
require_once('admin_inc/wedstrijd.class.php');
if(!isset($_SESSION['user_session']))
{
	header("Location: http://detoernooiplanner.nl/app/");
}
else
{
$achtergrond=$_GET['backgr'];
$wid = $_GET['id'];
$tid = $_GET['tid'];
?>
<!DOCTYPE html>
<html>
<head>
    <META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=UTF-8">
	<title>Uitslag invoer</title>
	<link type="text/css" rel="stylesheet" href="../css/toernooiplanner.css"/>
</head>
<body background="../backgrounds/<? echo $achtergrond; ?>">
<div id="boxContainerContainer">
    <div id="boxContainer">
<div id="box2">
<?
	$wedstrijd=new Wedstrijd();
	$uitslinvoer=$wedstrijd->maakUitslaginvoer($wid,$tid);
	echo $uitslinvoer;
	?>
        </div><!-- /box2 -->
    </div><!-- /boxContainer -->
</div><!-- /boxContainerContainer -->
</body>
</html>
<? } ?>
