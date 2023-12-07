<?
require_once('../session.php');
require_once('admin_inc/toernooi.class.php');

$toernooiid = $_GET['toernooiid'];

//check of er een submit heeft plaatsgevonden en of alle velden ingevuld zijn
if (isset($_POST['edit']) AND !(empty($_POST['naam']) OR empty($_POST['datum']) OR empty($_POST['teams']) OR empty($_POST['poules']) OR empty($_POST['velden']) OR empty($_POST['aanvang']) OR empty($_POST['duur'])))
{
	$toernooi = new Toernooi();
	$toernooi_id=$toernooi->maakToernooi($_POST['naam'],$_POST['datum'],$_POST['teams'],$_POST['poules'],$_POST['velden'],$_POST['aanvang'],$_POST['duur'],$_POST['comp']);
	header("Location: wedstrschema.php?toernooiid=".$toernooi_id);
}
else
{
	include('menu.php');
	echo "<div id='box2'>";
	$edittoernooi=new Toernooi();
	$toonedittoernooi=$edittoernooi->toonEditformToernooi($toernooiid);
	echo $toonedittoernooi;
	?>
	</div><!-- afsluiting box2 -->
	</div><!-- afsluiting boxContainer zit in menu.php-->
	</div><!-- afsluiting boxContainerContainer zit in menu.php-->
	</body>
	</html>
<?
}
?>

