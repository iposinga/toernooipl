<?
require_once('../session.php');
require_once('admin_inc/finalewedstrijd.class.php');
$toernooiid = $_GET['toernid'];
$finale=new Finalewedstrijd();
$aantalfinwedstr=$finale->aantalFinalewedstrijden($toernooiid);
if ($aantalfinwedstr > 0)
{
	header('Location: standen_poulesoverzicht.php?toernooiid='.$toernooiid);
}
else
{
	header('Location: standen_klassescore.php?toernooiid='.$toernooiid);
}
?>
