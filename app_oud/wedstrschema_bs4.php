<?
include('inc/phpsettings.php');
include('inc/dbconfig.class.php');
include('inc/toernooi.class.php');
//$database = new Database();
$toernooiid = $_GET['toernooiid'];

$displdatum = date("d-m-Y",strtotime($toernooi['datum']));
$toernooi = new Toernooi();
$achtergrond = $toernooi->getBackground($toernooiid);
//echo $achtergrond;
?>
<!DOCTYPE html>
<html>
<head>
    <META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=UTF-8">
	<title>De Toernooiplanner</title>
	    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
	  <link href="css/custom.css" rel="stylesheet">
	  <script src="js/wedstrijdschemajs.js" type="text/javascript"></script>
</head>
<body background="backgrounds/<? echo $achtergrond; ?>">

<!-- Modal -->
<div class="modal fade" id="exampleModalLong" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header alert-primary">
        <h5 class="modal-title" id="exampleModalLongTitle"></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
      </div>
      <div class="modal-footer bg-secondary">
      </div>
    </div>
  </div>
</div>
<!-- /Modal -->

<div class="mai-wrapper">
<div class="main-content container">



<div class="card" style="margin-top: 30px; margin-bottom: 30px;">
		<div class="card-body text-center" style="align-content: center">
<div class="row">
	<div class="col-md-3">
		<img class="img-fluid" src="images/detoernooiplanner_zondertekst.jpg" alt="Toernooiplanner">
	</div>
	<div class="col-md-3" style="padding-top: 2%; padding-bottom: 2%;">
		 <a class="btn btn-primary btn-block" href="index.php">Overzicht</a>
	</div>
	<div class="col-md-3" style="padding-top: 2%; padding-bottom: 2%;">
		<a class="btn btn-primary btn-block" href="index.php">Log in</a>
	</div>
	<div class="col-md-3" style="padding-top: 2%; padding-bottom: 2%;">
		<a class="btn btn-primary btn-block" href="index.php">Print</a>
	</div>
</div>
	</div> <!-- /card-body -->
</div> <!-- /card -->

<div class="card">
	<div class="card-body">
<? // hier naam en datum
	$titelmetdatum = $toernooi->getTitelmetDatum($toernooiid);
	echo $titelmetdatum;
 //hier het poule-overzicht
 	//$poule_overzicht = $toernooi->getPoules($toernooiid);
 	//echo $poule_overzicht;
 ?>
  <div class="row">
  <?php
 	$poule_overzicht = $toernooi->getPoules($toernooiid);
 	echo $poule_overzicht;
 ?>
 </div>
 <div class="row">
	 <div class="col">
	 </div>
	 <div class="col"><a href="wedstrschema_teamnamen.php?toernooiid=<? echo $toernooiid; ?>">wedstrijdschema met teamnamen</a>
	 </div>
	 <div class="col">
	 </div>
 </div>
 <div class="row">
	 <div class="col">
	<?php
		//hier het wedstrijdschema
		$wedstrijdschema = $toernooi->toonWedstrijdschema($toernooiid,0,0);
		echo $wedstrijdschema;
?>
	 </div>
 </div>
        </div> <!-- /card-body -->
</div> <!-- /card -->
    </div><!-- /main-content container -->
</div><!-- /mai-wrapper -->
<script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
</body>
</html>
