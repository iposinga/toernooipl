<?php
//require_once($_SERVER['DOCUMENT_ROOT'].'/session.php'); //bevat: variabele '$path', alle locale-instellingen, foutmeld-instellingen, en de classes user.class -> dbconfig.class

require_once('../inc/wedstrijd.class.php');
require_once('../inc/ploeg.class.php');
require_once('../inc/poule.class.php');

$action = $_POST['action'];

if ($action=='uitslaginvoer')
{

	$wedstrijd=new Wedstrijd();
	$data=$wedstrijd->maakUitslaginvoer($_POST['wedstr'],$_POST['toern']);
	echo json_encode($data);
}
elseif($action=='uitslagvastleggen')
{
	$wedstrijd=new Wedstrijd();
	$data=$wedstrijd->vulinUitslag($_POST['wedstr'],$_POST['thuissc'],$_POST['uitsc']);
	echo json_encode($data);
}
elseif($action=='uitslagverwijderen')
{
	$data=$wedstrijd->verwijderUitslag($_POST['wedstr']);
	//echo json_encode($data);
}
elseif($action=='teamschema')
{
	$ploeg=new Ploeg();
	$data=$ploeg->getPloegwedstr($_POST['toern'],$_POST['team']);;
	echo json_encode($data);
}
elseif($action=='pouleschema')
{
	$pouleschema=new Poule();
	$data=$pouleschema->toonWedstrijdschema($_POST['toern'],$_POST['poule_id']);
	echo json_encode($data);
}
