<?
require_once('../session.php');
require_once('admin_inc/toernooi.class.php');
require_once('admin_inc/wedstrijd.class.php');

$toernooiid = $_GET['toernooiid'];

$toernooiparameters=array();
$toernooi=new Toernooi();
$titelendatum=$toernooi->getTitelmetDatum($toernooiid);
$achtergrond=$toernooi->getBackground($toernooiid);

$message = "<b>Selecteer twee wedstrijden die omgewisseld moeten worden!</b><br>";
//kijken of er een swapsubmit is
if ($_POST['swapsubmit'] == 1)
{
	//nu de swap bewerkstelligen
	$toswap = $_POST['wedstrtoswap'];
	if (count($toswap) > 1)
	{
		//wissel de eerste 2 elementen om
		$wedstrijd=new Wedstrijd();

		$wedstr1 = $toswap[0];
		$rijswap1=array();
		$rijswap1=$wedstrijd->getSwapGeg($wedstr1);

		$wedstr2 = $toswap[1];
		$rijswap2=array();
		$rijswap2=$wedstrijd->getSwapGeg($wedstr2);

		$message.="<font color=red><i>De wedstrijden ".$rijswap1['thuisploeg']." - ".$rijswap1['uitploeg']." en ".$rijswap2['thuisploeg']." - ".$rijswap2['uitploeg']." zijn omgewisseld!</i></font>";

		$thuis1pl = $rijswap2['thuisploeg'];
		$thuis1sc = $rijswap2['thuisscore'];
		$thuis1pt = $rijswap2['thuispunten'];
		$uit1pl = $rijswap2['uitploeg'];
		$uit1sc = $rijswap2['uitscore'];
		$uit1pt = $rijswap2['uitpunten'];
		$poule1 = $rijswap2['poule'];

		$thuis2pl = $rijswap1['thuisploeg'];
		$thuis2sc = $rijswap1['thuisscore'];
		$thuis2pt = $rijswap1['thuispunten'];
		$uit2pl = $rijswap1['uitploeg'];
		$uit2sc = $rijswap1['uitscore'];
		$uit2pt = $rijswap1['uitpunten'];
		$poule2 = $rijswap1['poule'];

		$wedstrijd->updateWedstrijdschema($wedstr1,$thuis1pl,$thuis1sc,$thuis1pt,$uit1pl,$uit1sc,$uit1pt,$poule1);
		$wedstrijd->updateWedstrijdschema($wedstr2,$thuis2pl,$thuis2sc,$thuis2pt,$uit2pl,$uit2sc,$uit2pt,$poule2);
	}
	else
	$message = "<font color=red><i>Je hebt minder dan 2 wedstrijden aangeklikt, dat is te weinig!</i></font>";
}

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
<body background="../backgrounds/<? echo $achtergrond; ?>">
<div id="boxContainerContainer">
    <div id="boxContainer">
<div id="box2">
<h1><? echo $titelendatum ?></h1>
<? echo $message;

	$wedstrschemaswap=$toernooi->toonWedstrijdschemaSwap($toernooiid,1);
	echo $wedstrschemaswap;

?>
        </div> <!-- afsluiting box2 -->
    </div> <!-- afsluiting boxContainer -->
</div> <!-- afsluiting boxContainerContainer -->
</body>
</html>
