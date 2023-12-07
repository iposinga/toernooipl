<?
require_once('../session.php');
require_once('admin_inc/toernooi.class.php');
require_once('admin_inc/winstregel.class.php');
require_once('admin_inc/regel.class.php');

$toernooiid = $_GET['toernooiid'];

//kijken of er een mededeling over winnaar/gedrag is gesubmit
$sent = $_POST['sent'];
?>
<!DOCTYPE html>
<html>
<head>
    <META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=UTF-8">
	<title>De Toernooiplanner</title>
	<link type="text/css" rel="stylesheet" href="../css/toernooiplanner.css"/>
</head>
<?
if ($_POST['sent'] == 1)
{
	//gedrag is een string met gekozen cijfers gescheiden door komma's
	$gedrag=implode(',', $_POST['gedrag']);
	$toernooiupd=new Toernooi();
	$toernooiupd->updateWinstEnGedrag($toernooiid,$gedrag,$_POST['winst']);
?>
<body onLoad="window.close();">
<?
}
else
{
$toernooi=new Toernooi();
$winstengedrag = array();
$winstengedrag=$toernooi->getToernooiparameters($toernooiid);

?>

<body>
	<div class="printmededeling">
<h3><? echo $winstengedrag['naam']." op ".$winstengedrag['datum']; ?></h3>

	<b>Winnaar:</b><br>
		<form name="winstform" method="post" action="toerngedragsregels.php?toernooiid=<? echo $toernooiid ?>">
			<input type="hidden" name="sent" value="1">

<?
//vraag de winstbepalingen op:
$winstbepaling=new Winstregel();
$winstbepalingtabel=$winstbepaling->zoekWinstBepaling($winstengedrag['winnaar']);
echo $winstbepalingtabel;

//vraag de gedragsregels op:
echo "<br><b>Denk om:</b><br>";
$regelgeving=new Regel();
$regels=$regelgeving->zoekRegels($winstengedrag['gedrag']);
echo $regels;
?>
<p align="right"><input type=submit value="leg vast"></p>
</form>
</div>
<? } ?>
</body>
</html>
