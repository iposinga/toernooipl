<?
require_once('inc/toernooi.class.php');
require_once('inc/ploeg.class.php');

$toernooiid = $_GET['toernid'];
$team = $_GET['team'];

$toernooiparameters=array();
$toernooi=new Toernooi();
$toernooiparameters=$toernooi->getToernooiparameters($toernooiid);

$ploegparameters=array();
$ploeg=new Ploeg();
$ploegparameters=$ploeg->getPloegparameters($toernooiid,$team);

?>
<!DOCTYPE html>
<html>
<head>
	<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=UTF-8">
	<title>De Toernooiplanner</title>
	<link type="text/css" rel="stylesheet" href="css/toernooiplanner.css"/>
	</head>
	<body background="backgrounds/<? echo $toernooiparameters['achtergrond']; ?>">

<div id="boxContainerContainer">
    <div id="boxContainer">
    <div id="box1">
	    <?
		    if ($ploegparameters['naam'] <> '')
		    $teamecho = $team." (".$ploegparameters['naam'].")";
		    else
		    $teamecho = $team;
		    ?>
     <h1>Team <? echo $teamecho."<br>".$toernooiparameters['naam']; ?></h1>
    <div id=menu>
    <!--<ul>
		<li><a href="wedstrschema.php?toernooiid=<? echo $toernooiid ?>">TERUG</a>&nbsp;&nbsp;&nbsp;&nbsp;</li>
		</ul> -->
    </div>
    </div>
    <div id="box2">
<h1 align="left">Team <? echo $ploegparameters['teamnr']." speelt in poule ".$ploegparameters['poule']; ?></h1>
<?
	$wedstrtabel=$ploeg->getPloegwedstr($toernooiid,$team);
	echo $wedstrtabel;
?>
<!-- afsluiting box2 -->
        </div>
<!-- afsluiting boxContainer -->
    </div>
<!-- afsluiting boxContainerContainer -->
</div>


</body>
</html>

