<?
$toernooiid = $_GET['tid'];
if ($_POST['scherm1'] <> '')
{
$scherm1 = $_POST['scherm1'];
$scherm2 = $_POST['scherm2'];
$scherm3 = $_POST['scherm3'];
$scherm4 = $_POST['scherm4'];
$scherm5 = $_POST['scherm5'];
$scherm6 = $_POST['scherm6'];
$dubbel = $_POST['dubbel'];
}
else
{
$scherm1 = $_GET['scherm1'];
$scherm2 = $_GET['scherm2'];
$scherm3 = $_GET['scherm3'];
$scherm4 = $_GET['scherm4'];
$scherm5 = $_GET['scherm5'];
$scherm6 = $_GET['scherm6'];
$dubbel = $_GET['dubbel'];
}
//echo $scherm1."<br>";
//echo $scherm2."<br>";
if (strlen($scherm1) == 1 AND $dubbel == 1)
$link1 = "http://toernooiplanner.dollardinformatica103.nl/pouleoverzichtvideowalldubbel.php?toernid=".$toernooiid."&poule=".$scherm1;
elseif (strlen($scherm1) == 1)
$link1 = "http://toernooiplanner.dollardinformatica103.nl/pouleoverzichtvideowall.php?toernid=".$toernooiid."&poule=".$scherm1;
elseif ($scherm1 == 'poules')
$link1 = "http://toernooiplanner.dollardinformatica103.nl/poulesvideowall.php?toernooiid=".$toernooiid;
else
$link1 = "http://toernooiplanner.dollardinformatica103.nl/wedstrschemavideowall.php?toernooiid=".$toernooiid;

if (strlen($scherm2) == 1 AND $dubbel == 1)
$link2 = "http://toernooiplanner.dollardinformatica103.nl/pouleoverzichtvideowalldubbel.php?toernid=".$toernooiid."&poule=".$scherm2;
elseif (strlen($scherm2) == 1)
$link2 = "http://toernooiplanner.dollardinformatica103.nl/pouleoverzichtvideowall.php?toernid=".$toernooiid."&poule=".$scherm2;
elseif ($scherm2 == 'poules')
$link2 = "http://toernooiplanner.dollardinformatica103.nl/poulesvideowall.php?toernooiid=".$toernooiid;
else
$link2 = "http://toernooiplanner.dollardinformatica103.nl/wedstrschemavideowall.php?toernooiid=".$toernooiid;

if (strlen($scherm3) == 1 AND $dubbel == 1)
$link3 = "http://toernooiplanner.dollardinformatica103.nl/pouleoverzichtvideowalldubbel.php?toernid=".$toernooiid."&poule=".$scherm3;
elseif (strlen($scherm3) == 1)
$link3 = "http://toernooiplanner.dollardinformatica103.nl/pouleoverzichtvideowall.php?toernid=".$toernooiid."&poule=".$scherm3;
elseif ($scherm3 == 'poules')
$link3 = "http://toernooiplanner.dollardinformatica103.nl/poulesvideowall.php?toernooiid=".$toernooiid;
else
$link3 = "http://toernooiplanner.dollardinformatica103.nl/wedstrschemavideowall.php?toernooiid=".$toernooiid;

if (strlen($scherm4) == 1 AND $dubbel == 1)
$link4 = "http://toernooiplanner.dollardinformatica103.nl/pouleoverzichtvideowalldubbel.php?toernid=".$toernooiid."&poule=".$scherm4;
elseif (strlen($scherm4) == 1)
$link4 = "http://toernooiplanner.dollardinformatica103.nl/pouleoverzichtvideowall.php?toernid=".$toernooiid."&poule=".$scherm4;
elseif ($scherm4 == 'poules')
$link4 = "http://toernooiplanner.dollardinformatica103.nl/poulesvideowall.php?toernooiid=".$toernooiid;
else
$link4 = "http://toernooiplanner.dollardinformatica103.nl/wedstrschemavideowall.php?toernooiid=".$toernooiid;

if (strlen($scherm5) == 1 AND $dubbel == 1)
$link5 = "http://toernooiplanner.dollardinformatica103.nl/pouleoverzichtvideowalldubbel.php?toernid=".$toernooiid."&poule=".$scherm5;
elseif (strlen($scherm5) == 1)
$link5 = "http://toernooiplanner.dollardinformatica103.nl/pouleoverzichtvideowall.php?toernid=".$toernooiid."&poule=".$scherm5;
elseif ($scherm5 == 'poules')
$link5 = "http://toernooiplanner.dollardinformatica103.nl/poulesvideowall.php?toernooiid=".$toernooiid;
else
$link5 = "http://toernooiplanner.dollardinformatica103.nl/wedstrschemavideowall.php?toernooiid=".$toernooiid;

if (strlen($scherm6) == 1 AND $dubbel == 1)
$link6 = "http://toernooiplanner.dollardinformatica103.nl/pouleoverzichtvideowalldubbel.php?toernid=".$toernooiid."&poule=".$scherm6;
elseif (strlen($scherm6) == 1)
$link6 = "http://toernooiplanner.dollardinformatica103.nl/pouleoverzichtvideowall.php?toernid=".$toernooiid."&poule=".$scherm6;
elseif ($scherm6 == 'poules')
$link6 = "http://toernooiplanner.dollardinformatica103.nl/poulesvideowall.php?toernooiid=".$toernooiid;
else
$link6 = "http://toernooiplanner.dollardinformatica103.nl/wedstrschemavideowall.php?toernooiid=".$toernooiid;
?>
<html>
<head>
<!-- <meta http-equiv="refresh" content="10"> -->
<meta http-equiv="refresh" content="60; URL=3bij2scherm.php?tid=<? echo $toernooiid ?>&dubbel=<? echo $dubbel ?>&scherm1=<? echo $scherm1 ?>&scherm2=<? echo $scherm2 ?>&scherm3=<? echo $scherm3 ?>&scherm4=<? echo $scherm4 ?>&scherm5=<? echo $scherm5 ?>&scherm6=<? echo $scherm6 ?>">
<link type="text/css" rel="stylesheet" href="inc/toernooiplannerza.css"/>
</head>
<body>

 <iframe class="frame1" src="<? echo $link1 ?>"></iframe>
 <iframe class="frame2" src="<? echo $link2 ?>"></iframe>
 <iframe class="frame3" src="<? echo $link3 ?>"></iframe>
 <iframe class="frame4" src="<? echo $link4 ?>"></iframe>
 <iframe class="frame5" src="<? echo $link5 ?>"></iframe>
 <iframe class="frame6" src="<? echo $link6 ?>"></iframe>

</body>
</html>
