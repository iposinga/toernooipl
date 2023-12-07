<?php
require_once('../session.php');
require_once('admin_inc/toernooi.class.php');
if(!isset($_SESSION['user_session']))
{
	header("Location: http://detoernooiplanner.nl/app/");
}
else
{
$toernooiid = $_GET['toernooiid'];
$toernooi = new Toernooi();
$achtergrond = $toernooi->getBackground($toernooiid);
?>
<!DOCTYPE html>
<html>
<head>
    <META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=UTF-8">
	<title>De Toernooiplanner</title>
	<link type="text/css" rel="stylesheet" href="../css/toernooiplanner.css"/>
	<script>
	function popupwindow(url, title, w, h)
	{
  var left = (screen.width/2)-(w/2);
  var top = (screen.height/2)-(h/2);
  return window.open(url, title, 'toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=yes, resizable=yes, copyhistory=no, width='+w+', height='+h+', top='+top+', left='+left+'');
}
</script>
</head>
<body background="../backgrounds/<?php echo $achtergrond ?>">
<div id="boxContainerContainer">
<div id="boxContainer">
<div id="box2">
<? // hier naam en datum
	$titelmetdatum = $toernooi->getTitelmetDatum($toernooiid);
	echo $titelmetdatum;
?>
 	<p><a href="wedstrschema_teamnamen.php?toernooiid=<? echo $toernooiid ?>">maximaal wedstrijdschema</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="wedstrschemainvul.php?toernooiid=<? echo $toernooiid ?>">wedstrijdschema met teamnummers</a></p>
<?
 //hier het wedstrijdschema
	$wedstrijdschema = $toernooi->toonWedstrijdschema($toernooiid,$achtergrond,1);
	echo $wedstrijdschema;
?>
</div> <!-- /box2 -->

    </div><!-- /boxContainer -->
</div><!-- /boxContainerContainer -->
</body>
</html>
<?
	}
	?>
