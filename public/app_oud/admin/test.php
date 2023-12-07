<?
	require_once('../session.php');
	require_once('admin_inc/toernooi.class.php');
	$test=new Toernooi();
	$testarray=$test->maakWedstrschemafase1TEST(425,1);
	echo '<pre>';
	print_r ($testarray);
	echo '</pre>';
?>
