<?
include('inc/phpsettings.php');
include('inc/toernooi.class.php');
$toernooiid = $_GET['toernid'];
$poule = $_GET['poule'];


$toernooi=new Toernooi();
$toernooiparam=$toernooi->getToernooiparameters($toernooiid);

?>
<!DOCTYPE html>
<html>
<head>
    <META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=UTF-8">
     <meta http-equiv="refresh" content="60">
	<title>De Toernooiplanner</title>
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
	<script src="/app/js/wedstrijdschemajs.js" type="text/javascript" ></script>
</head>
<body>
<div class="container">
<div class="row">
<div class="col-1"><form name="clockForm"><input class="btn btn-outline-secondary" type="button" name="clockButton" value="Loading..." style="margin-top: 25px"/></form></div>
<div class="col-10 text-center">
	<h1 style="margin-top: 20px; margin-bottom: 40px;"><?php echo "{$toernooiparam['naam']} op {$toernooiparam['datum']}"; ?></h1>
  </div>
  <div class="col-1 text-right"><span class="btn btn-outline-secondary" id="timer" style="margin-top: 25px"></span></div>
  </div>
	<div class="row">
		<div class="col-lg">
<?php
	$toernooipoule=new Poule();
	$poulewedstrijdschema=$toernooipoule->toonWedstrijdschema($toernooiid,$poule);
	echo $poulewedstrijdschema;
?>
		</div>
		<div class="col-lg">
<?php
$poulestand=$toernooipoule->displayPoulestand($toernooiid,$poule);
echo $poulestand;
?><p>Klik op een teamnaam om de bijbehorende wedstrijden te markeren.</p>

<p>Deze pagina kun je bekijken via internet: kijk op sclopppersum.nl</p>
		</div>
	</div>
</div>
<script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
<script type="text/javascript">
function highLight(teamnr){
    $("tr").removeClass("alert alert-primary");
    $(".wedstr_" + teamnr).addClass("alert alert-primary");
    $(".team_" + teamnr).addClass("alert alert-primary");
}

function clock(){
    let time = new Date()
    let hr = time.getHours()
    let min = time.getMinutes()
    let sec = time.getSeconds()
    if (hr < 10){
        hr = " " + hr
    }
    if (min < 10){
        min = "0" + min
    }
    if (sec < 10){
        sec = "0" + sec
    }
    document.clockForm.clockButton.value = hr + ":" + min
    setTimeout("clock()", 1000)
}

window.onload = clock;

function checklength(i) {
    'use strict';
    if (i < 10) {
        i = "0" + i;
    }
    return i;
}
let minutes, seconds, count, counter;
count = 61; //seconds
counter = setInterval(timer, 1000);

function timer() {
    'use strict';
    count = count - 1;
    minutes = checklength(Math.floor(count / 60));
    seconds = checklength(count - minutes * 60);
    if (count < 0) {
        clearInterval(counter);
        return;
    }
    document.getElementById("timer").innerHTML = ' ' + minutes + ':' + seconds + ' ';
    if (count === 0) {
        location.reload();
    }
}

function pageloadEvery(t) {
    setTimeout('location.reload()', t);
}
</script>
</body>
</html>
