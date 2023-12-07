<?php
include "inc/phpsettings.php";
//include('includes/dbconfig.class.php');
include "inc/toernooi.class.php";
?>
<!DOCTYPE html>
<html>
<head>
    <META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=UTF-8">
	<title>De Toernooiplanner</title>
	<link type="text/css" rel="stylesheet" href="css/toernooiplanner.css"/>
	<script>
	function popupwindow(url, title, w, h)
	{
  var left = (screen.width/2)-(w/2);
  var top = (screen.height/2)-(h/2);
  return window.open(url, title, 'toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=yes, resizable=yes, copyhistory=no, width='+w+', height='+h+', top='+top+', left='+left+'');
}
</script>
</head>
<body background="backgrounds/background2.jpg">
<div id="boxContainerContainer">
<div id="boxContainer">
	<div id="box1">
<!-- Het toernooiplanner-plaatje -->
<img src="images/detoernooiplanner.jpg" alt="Toernooiplanner">
<nav>
	<ul>
		<li><a href="index.php">OVERZICHT</a></li>
		<li><a href="login.php">LOG IN</a></li>
	</ul>
	</nav>
<!-- afsl box1 -->
</div>

<div id="box2">
<?php
	$toernooien=new Toernooi();
	$overzicht=$toernooien->toonToernooien();
	echo $overzicht;
	?>
</div> <!-- /box2 -->
</div> <!-- /boxContainer -->
</div> <!-- /boxContainerContainer -->
</body>
</html>
