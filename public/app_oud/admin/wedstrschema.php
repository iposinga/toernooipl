<?php
require_once('../session.php');
require_once('admin_inc/toernooi.class.php');
if(!isset($_SESSION['user_session']))
{
	header("Location: http://detoernooiplanner.nl/app_oud/");
}
else {
    $toernooiid = $_GET['toernooiid'];
    $teamnamen = 0;
    if(isset($_GET['teamnamen']))
        $teamnamen = $_GET['teamnamen'];
    $toernooi = new Toernooi();
    $achtergrond = $toernooi->getBackground($toernooiid);
}
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=UTF-8">
	<title>De Toernooiplanner</title>
	<link type="text/css" rel="stylesheet" href="../css/toernooiplanner.css"/>
    <link type="text/css" rel="stylesheet" href="../css/modal.css"/>
</head>
<body style='background-image: url("../backgrounds/<?= $achtergrond ?>")'>

<!-- The Modal -->
<div id="myModal" class="modal" style="width: 30%;">
    <!-- Modal content -->
    <div class="modal-content">
        <div class="modal-header">
            <span class="close">&times;</span>
            <h2 class="modal-title">Modal Header</h2>
        </div>
        <div class="modal-body" style="align-items: center;">
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
<img src="../images/detoernooiplanner_zondertekst.jpg" alt="Toernooiplanner" style= "height: 120px">

	<nav>
	<ul style="margin-top: 0px;">
		<li><a href="nieuw.php">NIEUW</a></li>
		<li><a href="index.php">OVERZICHT</a></li>
		<li><a href="#">OPTIES</a>
		<ul>
				<li><a href="#" onclick="popupwindow('uservelden.php', 'velden van user', '620', '670'); return false">VELDLOCATIES</a></li>
				<li><a href="#" onclick="popupwindow('userwinstbepalingen.php', 'winstbepalingen van user', '1180', '670'); return false">WINSTREGELS</a></li>
				<li><a href="#" onclick="popupwindow('usergedragsregels.php', 'gedragsregels van user', '1020', '670'); return false">GEDRAGSREGELS</a></li>
		</ul>
		</li>
		<li><a href="logout.php?logout=true">LOG UIT</a></li>

	</ul>
	</nav>
</div><!-- /box1 -->
<div id="box2">
<?php // hier naam en datum
	$titelmetdatum = $toernooi->getTitelmetDatum($toernooiid);
	echo $titelmetdatum;
?>
<nav>
	<ul style="margin-top: 0px;">
		<li><a href="#" title="velden" onclick="window.open('velden.php?toernooiid=<?= $toernooiid ?>', 'velden invoer', 'width=360,height=360,top=220,left=220, resizable=yes'); return false">VELDEN</a></li>
		<li> <a href="#" title="standen" onclick="window.open('standen.php?toernid=<?= $toernooiid ?>', 'klasssenstand', 'width=750,height=680,top=120,left=220, resizable=yes'); return false">STANDEN</a></li>
		<li> <a href="#">AANVULLINGEN</a>
		<ul>
				<li><a href="#" onclick="popupwindow('finalerondes.php?toernooiid=<?= $toernooiid ?>', 'finalerondes bij toernooi', '620', '670'); return false">FINALERONDES</a></li>
				<!--<li><a href="#" onclick="popupwindow('mededelingen.php?toernooiid=<?= $toernooiid ?>', 'mededelingen bij toernooi', '620', '670'); return false">MEDEDELINGEN</a></li>
			-->	<li><a href="#" onclick="popupwindow('toerngedragsregels.php?toernooiid=<?= $toernooiid ?>', 'gedragsregels bij toernooi', '1020', '670'); return false">WINST- EN GEDRAGSREGELS</a></li>
				<!--<li><a href="#" onclick="popupwindow('toernwinstbepalingen.php', 'winstbepalingen bij toernooi', '1180', '670'); return false">WINNAARBEPALING</a></li>-->
		</ul>


		</li>
		<li> <a href="#" title="print schema" onclick="popupwindow('../printwedstrschema.php?toernooiid=<?= $toernooiid ?>', 'print', '710', '980'); return false">PRINT</a></li>
	</ul>
</nav>
<?php
 //hier het poule-overzicht
 	$poule_overzicht = $toernooi->getPoules($toernooiid);
 	echo $poule_overzicht;
 ?>
 	<p><a href="wedstrschemainvul.php?toernooiid=<?= $toernooiid; ?>">alleen wedstrijdschema</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        <?php if($teamnamen == 0) { ?>
            <a href="wedstrschema.php?toernooiid=<?= $toernooiid; ?>&teamnamen=1">wedstrijdschema met teamnamen</a>
    <?php } else { ?>
            <a href="wedstrschema.php?toernooiid=<?= $toernooiid; ?>&teamnamen=0">wedstrijdschema met teamnummers</a>
    <?php } ?>
    </p>
<?php
 //hier het wedstrijdschema
	$wedstrijdschema = $toernooi->toonWedstrijdschema($toernooiid, $achtergrond, $teamnamen);
	echo $wedstrijdschema;
?>
</div> <!-- /box2 -->

    </div><!-- /boxContainer -->
</div><!-- /boxContainerContainer -->
<script src='https://code.jquery.com/jquery-3.6.0.min.js' integrity='sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=' crossorigin='anonymous'></script>
<script src="js/wedstrschema.js"></script>
</body>
</html>
