<?php
include('inc/phpsettings.php');
include('inc/dbconfig.class.php');
include('inc/toernooi.class.php');
//$database = new Database();
$toernooiid = $_GET['toernooiid'];
$toernooi = new Toernooi();
$achtergrond = $toernooi->getBackground($toernooiid);
//echo $achtergrond;
?>
<!DOCTYPE html>
<html>
<head>
    <META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=UTF-8">
	<title>De Toernooiplanner</title>
	<link type="text/css" rel="stylesheet" href="css/toernooiplanner.css"/>
    <link type="text/css" rel="stylesheet" href="css/modal.css"/>
</head>
<body style="background-image: url('backgrounds/<?=$achtergrond;?>')">

<!-- The Modal -->
<div id="myModal" class="modal">
    <!-- Modal content -->
    <div class="modal-content">
        <div class="modal-header">
            <span class="close">&times;</span>
            <h2 class="modal-title">Modal Header</h2>
        </div>
        <div class="modal-body">
            <p>Some text in the Modal Body</p>
            <p>Some other text...</p>
        </div>
        <div class="modal-footer">
            <h3>Modal Footer</h3>
        </div>
    </div>
</div>

<div id="boxContainerContainer">
<div id="boxContainer">
<div id="box1">
<!-- Het toernooiplanner-plaatje -->
<img src="images/detoernooiplanner_zondertekst.jpg"alt="Toernooiplanner">
<nav>
	<ul>
		<li><a href="index.php">OVERZICHT</a></li>
		<li><a href="login.php">LOG IN</a></li>
		<li> <?php echo "<a href=\"#\" title=\"print schema\" onclick=\"window.open('printwedstrschema.php?toernooiid=".$toernooiid."', 'print', 'width=710,height=980,top=10,left=10'); return false\">" ?>PRINT</a></li>
	</ul>
	</nav>
<!-- afsl box1 -->
</div>

<div id="box2">
<?php // hier naam en datum
	$titelmetdatum = $toernooi->getTitelmetDatum($toernooiid);
	echo $titelmetdatum;
 	$poule_overzicht = $toernooi->getPoules($toernooiid);
 	echo $poule_overzicht;
 ?>
    <div style="clear:both;">
    <p><a href="wedstrschema_teamnamen.php?toernooiid=<?php echo $toernooiid; ?>">wedstrijdschema met teamnamen</a></p>
    </div>
	<?php
		//hier het wedstrijdschema
		$wedstrijdschema = $toernooi->toonWedstrijdschema($toernooiid,0,0);
		echo $wedstrijdschema;
?>
<!-- afsluiting box2 -->
        </div>
<!-- afsluiting boxContainer zit in menu.php-->
    </div>
<!-- afsluiting boxContainerContainer zit in menu.php-->
</div>
<script src='https://code.jquery.com/jquery-3.6.0.min.js' integrity='sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=' crossorigin='anonymous'></script>
<script src="js/wedstrijdschemajs.js"></script>
</body>
</html>
