<?
require_once('../session.php');
require_once('admin_inc/toernooi.class.php');
require_once('admin_inc/poule.class.php');

$toernooiid = $_GET['toernooiid'];

$toernooi=new Toernooi();
$toernooigeg=array();
$toernooigeg=$toernooi->getToernooiparameters($toernooiid);
?>
<!DOCTYPE html>
<html>
<head>
    <META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=UTF-8">
	<title>De Toernooiplanner</title>
	<link type="text/css" rel="stylesheet" href="../css/toernooiplanner.css"/>
</head>
<body background="../backgrounds/<? echo $toernooigeg['achtergrond']; ?>">
<div id="boxContainerContainer">
    <div id="boxContainer">
	    <div id="box1">
<?
$pouleteller=1;
$poule=new Poule();
while ($pouleteller <= $toernooigeg['aantalpoules'])
{
    $poulestand=$poule->displayPoulestand($toernooiid,chr($pouleteller+64));
    echo $poulestand;
    $pouleteller++;
}

?>
<!--</tr>
</table>-->
</div><!-- box1-->

</div><!-- afsluiting boxContainer-->

</div><!-- afsluiting boxContainerContainer zit in menu.php-->
</body>
</html>
