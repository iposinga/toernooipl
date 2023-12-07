<?php
//require_once($_SERVER['DOCUMENT_ROOT'].'/session.php'); //bevat: variabele '$path', alle locale-instellingen, foutmeld-instellingen, en de classes user.class -> dbconfig.class

require_once('../admin_inc/wedstrijd.class.php');
$wedstrijd = new Wedstrijd();
//require_once('../../inc/ploeg.class.php');
//require_once('../admin_inc/poule.class.php');

$action = $_POST['action'];

if ($action=='uitslaginvoer') {
    $data=$wedstrijd->maakUitslaginvoer($_POST['wedstr'],$_POST['toern']);
    echo json_encode($data);
}
elseif($action=='uitslagvastleggen')
{
    $wedstrijd->vulinUitslag($_POST['wedstr'],$_POST['thuissc'],$_POST['uitsc']);
    $returndata = $wedstrijd->getWedstrGeg($_POST['wedstr'], $_POST['toern']);
    echo json_encode($returndata);
}
elseif($action=='uitslagverwijderen')
{
    $wedstrijd->verwijderUitslag($_POST['wedstr']);
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
