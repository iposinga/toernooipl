<?
require_once('../session.php');
//require_once('admin_includes/wedstrijd.class.php');
require_once('admin_inc/poule.class.php');
require_once('admin_inc/toernooi.class.php');
if(!isset($_SESSION['user_session']))
{
	header("Location: http://detoernooiplanner.nl/app/");
}
else
{
$toernooiid = $_GET['toernid'];
$poule = $_GET['poule'];

$toernooi=new Toernooi();
$toernooiparam=$toernooi->getToernooiparameters($toernooiid);
?>
<!DOCTYPE html>
<html>
<head>
    <META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=UTF-8">
	<title>De Toernooiplanner</title>
	<link type="text/css" rel="stylesheet" href="../css/toernooiplanner.css"/>
</head>
<body background="../backgrounds/<? echo $toernooiparam['achtergrond']; ?>">
<div id="boxContainerContainer">
<div id="boxContainer">
<div id="box2">
<?
$actualpoule=new Poule();
$aantalinpoule=$actualpoule->zoekAantalInPoule($toernooiid,$poule);
$teamnrminimuminpoule=$actualpoule->zoekMinimaalTeamnr($toernooiid,$poule);
//echo $aantalinpoule."<br>";
$teamnamenform=$actualpoule->toonTeamnamenForm($toernooiid,$poule,$aantalinpoule,$teamnrminimuminpoule);
echo $teamnamenform;
?>
</div><!-- afsluiting box2 -->
</div> <!-- afsluiting boxContainer -->
</div> <!-- afsluiting boxContainerContainer -->
</body>
</html>
<?
}	?>
