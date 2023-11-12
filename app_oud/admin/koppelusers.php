<?
include_once('../session.php');
require_once('../inc/query.class.php');
require_once('admin_inc/toernooi.class.php');


$toernooiid=$_GET['toernooiid'];
$toern = new Toernooi();
$toernooiparam = $toern -> getToernooiparameters($toernooiid);

$stmt=new Query();
$stmt->appendField('userid, user_email');
$stmt->appendTable('users');
$stmtprep = $stmt->prepSelectQuery();
$stmtprep->execute();
?>
<!DOCTYPE html>
<html>
<head>
    <META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=UTF-8">
	<title>De Toernooiplanner</title>
	<link type="text/css" rel="stylesheet" href="../css/toernooiplanner.css"/>
</head>
<body>
<div id="boxContainerContainer">
    <div id="boxContainer">

<div id="box2">
Hieronder kun je andere gebruikers toegang geven<br>tot het admin-deel van het toernooi<br><b><? echo $toernooiparam['naam'] ?></b><br>De reeds gekoppelde gebruikers staan aangekruist,<br>deze kun je niet ontkopelen!
<!-- tabel met users die al toegevoegd zijn -->
</div>
<div id="box3">
<?
$echostmtkop="<form method='post' action='koppelbevestiging.php?toernooiid=".$toernooiid."&max=";
$echotable="<table border=0>";
//echo "user = ".$id;
$teller = 1;
while ($recset=$stmtprep->fetch(PDO::FETCH_ASSOC))
{
    $actuser = $recset['userid'];
    if ($actuser <> $_SESSION['user_session'])
    {
        //kijken of de user al toegevoegd is bij dit toernooi
        $stmt2=new Query();
		$stmt2->appendField('COUNT(*)');
		$stmt2->appendTable('toernooiusers');
		$stmt2->appendBindVoorwaarde('user');
		$stmt2->appendBindVoorwaarde('toernooi');
		$stmtprep2 = $stmt2->prepSelectQuery();
		$stmtprep2->execute([$actuser,$toernooiid]);
        $recset2=$stmtprep2->fetchColumn();
        //$userbijtoernooivraag = mysql_query ("SELECT * FROM toernooiusers WHERE user='$actuser' AND toernooi='$toernooiid'", $db) or die(mysql_error());
        if ($recset2 > 0)
        	$checked = "checked";
        else
        	$checked = "";
        $echotable .= "<tr><td align=right class=zonder>".$teller.".</td>";
		$echotable .= "<td align=left class=zonder><input type=\"checkbox\" name=\"gebruiker".$teller."\"  value=\"".$actuser."\"".$checked.">".$recset['user_email']."</td></tr>";
		$teller++;
    }
}
$echotable .= "<tr><td colspan='2' class='zonder'><input type='submit' value='koppel'></td></tr>";
$echotable .= "</table></form>";
$echostmtkop.=$teller."'>";
echo $echostmtkop.$echotable;
?>
<!-- afsluiting box2 -->
        </div>
        </div>
        </div>
</body>
</html>
