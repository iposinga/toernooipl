<?php
require_once('../session.php');
require_once('admin_inc/toernooi.class.php');
if(!isset($_SESSION['user_session']))
{
	header("Location: http://detoernooiplanner.nl/app/");
}
else
{
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
<body background="../backgrounds/background2.jpg">
<div id="boxContainerContainer">
<div id="boxContainer">
<div id="box1">
<!-- Het toernooiplanner-plaatje -->
<img src="../images/detoernooiplanner_zondertekst.jpg" alt="Toernooiplanner" align= "middle" >

	<nav>
	<ul style="margin-top: 0px;">
		<li><a href="nieuw.php">NIEUW</a></li>
		<li><a href="index.php">OVERZICHT</a></li>
		<li><a href="#">OPTIES</a>
		<ul>
				<li><a href="#" onclick="popupwindow('uservelden.php', 'velden van user', '620', '670'); return false">VELDLOCATIES</a></li>
				<li><a href="#" onclick="popupwindow('usergedragsregels.php', 'gedragsregels van user', '1020', '670'); return false">GEDRAGSREGELS</a></li>
				<li><a href="#" onclick="popupwindow('userwinstbepalingen.php', 'winstbepalingen van user', '1120', '670'); return false">WINNAARBEPALING</a></li>
		</ul>
		</li>
		<li><a href="logout.php?logout=true">LOG UIT</a></li>

	</ul>
	</nav>
</div><!-- /box1 -->
<div id="box2">

<?php

$toernooien=new Toernooi();
$usertoernooien=$toernooien->zoekuserToernooien();
echo $usertoernooien;
?>
</div><!-- /box2 -->
</div><!-- /boxContainer -->
</div><!-- /boxContainerContainer -->
</body>
</html>
<?php
	} ?>
