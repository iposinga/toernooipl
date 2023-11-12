<?
include_once('../session.php');
require_once('../inc/query.class.php');
require_once('admin_inc/toernooi.class.php');

$toernooiid = $_GET['toernid'];
//kijken of er een nieuwe achtergrond gekozen is
if ($_POST['verstuurd'] == 1)
{
    $stmt = new Query();
	$stmt->appendTable('toernooien');
	$stmt->appendField('achtergrond');
	$stmt->appendBindvoorwaarde('toernid');
	$stmtprep=$stmt->prepUpdateBindQuery();
	$stmtprep->execute([$_POST['backgr'],$toernooiid]);

    //mysql_query ("UPDATE toernooien SET achtergrond='$achtergrondnaam' WHERE toernid='$toernooiid'", $db) or die(mysql_error());
}
$toern = new Toernooi();
$toernparam = $toern -> getToernooiparameters($toernooiid);

//$toernooivraag = mysql_query ("SELECT * FROM toernooien WHERE toernid='$toernooiid'", $db) or die(mysql);
//$toernooi = mysql_fetch_array($toernooivraag);
$displdatum = date("d-m-Y",strtotime($toernparam['datum']));
if ($toernparam['achtergrond'] <> '')
	$achtergrond = $toernparam['achtergrond'];
else
	$achtergrond = "background2.jpg";
?>
<!DOCTYPE html>
<html>
<head>
    <META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=UTF-8">
	<title>De Toernooiplanner</title>
	<link type="text/css" rel="stylesheet" href="../css/toernooiplanner.css"/>
</head>
<body background="../backgrounds/<? echo $achtergrond; ?>"
<div id="boxContainerContainer">
    <div id="boxContainer">
<div id="box1">
<!-- Het toernooiplanner-plaatje -->
<h1>DE TOERNOOIPLANNER</h1>
<div id=menu>
	<ul>
		<li><a href="nieuw.php">NIEUW</a></li>
		<li><a href="index.php">OVERZICHT</a></li>
		<li><a href="index.php?logout=1">LOG UIT</a></li>

	</ul>
	</div>
	</div>
	<div id= 'box2'>
Upload een achtergrond:
<form action="upload.php?toernid=<? echo $toernooiid ?>" method="post" enctype="multipart/form-data">
    <input type="file" name="fileToUpload" id="fileToUpload">
    <input type="submit" value="Upload achtergrond" name="submit">
	</form>
<p class="klein">(de bestandsgrootte mag maximaal 500 kb zijn en de ideale afmetingen zijn 1920 x 1200 pixels)</p>
<br>
OF<br><br>Selecteer een achtergrond:
</br>
<?
if (is_dir('../backgrounds'))
{
    if ($handle = opendir('../backgrounds'))
    {
        foreach(glob('../backgrounds'.'/*.*') as $file)
        {
            $results_array[] = $file;
        }
        closedir($handle);
    }
}
?>
<form action="nieuwachtergrond.php?toernid=<? echo $toernooiid ?>" method="post" enctype="multipart/form-data">
<input type="hidden" name="verstuurd" value="1">
<table><tr>
<?
$teller= 0;
while ($teller < count($results_array))
{
	$a = getimagesize($results_array[$teller]);
	//echo "<pre>";
   //print_r($a);
//echo "</pre>";
	echo "<td class=zonder align=center valign=bottom><img src=\"".$results_array[$teller]."\" width= 100px><br><input valign=\"center\" type=\"radio\" name=\"backgr\" value=\"".substr ( $results_array[$teller] , 15 , strlen($results_array[$teller])-15)."\"></td>\n";
	$teller++;
}
?>
</tr>
<tr><td class="zonder" height="50" colspan="<? echo $teller ?>"><input type="submit" value="Selecteer"></td></tr>
</table>
</form>
</div>
</body>
</html>
