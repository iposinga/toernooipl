<?
$toernooiid = $_GET['toernooiid'];
include('inc/vars.php');
$toernooivraag = mysql_query ("SELECT * FROM toernooien WHERE toernid='$toernooiid'", $db) or die(mysql);
$toernooi = mysql_fetch_array($toernooivraag);
$displdatum = date("d-m-Y",strtotime($toernooi['datum']));
?>
<!DOCTYPE html>
<html>
<head>
    <META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=UTF-8">
	<title>De Toernooiplanner</title>
	<link type="text/css" rel="stylesheet" href="inc/toernooiplanner.css"/>
</head>
<body>
<div id="boxContainerContainer">
    <div id="boxContainer">
<div id="box1">
<h1>Poule-indeling <? echo $toernooi['naam'] ?></h1>
<table border=0>
<tr>
<?
$pouleteller=1;
while ($pouleteller <= $toernooi['poules'])
{
    echo "<td valign=top class=zonder>";
    // hier de tabel van een poule
    echo "<table border=0>";
    $zoekpoule = chr($pouleteller+64);
    echo "<tr><td class=zonder colspan=2 align=left><b>poule ".$zoekpoule."</b></td></tr>";
    $ploegenvraag = mysql_query ("SELECT * FROM ploegen WHERE toernid='$toernooiid' AND poule='$zoekpoule' ORDER BY poule", $db) or die(mysql);
    while($row = mysql_fetch_array($ploegenvraag))
    {
        echo "<tr><td class=rightnoborder>".$row['teamnr'].".</td><td class=leftnoborder>".$row['naam']."</td></tr>";
    }
    echo "</table>";
    echo "</td>";
    $pouleteller++;
}

?>
</tr>
</table>
<!-- afsluiting box1 -->
        </div>
<!-- afsluiting boxContainer -->
    </div>
<!-- afsluiting boxContainerContainer -->
</div>
</body>
</html>
