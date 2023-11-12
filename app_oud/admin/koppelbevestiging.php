<?
include_once('../session.php');
require_once('../inc/query.class.php');
$tid = $_GET['toernooiid'];
$max = $_GET['max'];
$teller = 1;
$gebrid = "gebruiker".$teller;
while ($teller <= $max)
{
    $gebrid = "gebruiker".$teller;
    if ($_POST[$gebrid] <> '')
    {
    $insertgebrid = $_POST[$gebrid];

	$stmt=new Query();
	$stmt->appendField('COUNT(*)');
	$stmt->appendTable('toernooiusers');
	$stmt->appendBindVoorwaarde('user');
	$stmt->appendBindVoorwaarde('toernooi');
	$stmtprep = $stmt->prepSelectQuery();
	$stmtprep->execute([$insertgebrid,$tid]);
    $recset=$stmtprep->fetchColumn();
    if ($recset == 0)
    {
	    $stmt2 = new Query();
		$stmt2->appendTable('toernooiusers');
		$stmt2->appendField('toernooi');
		$stmt2->appendField('user');
		$stmtprep2=$stmt2->prepInsertBindQuery();
		$stmtprep2->execute([$tid,$insertgebrid]);
    }
    }
    $teller++;
}
?>
<html>
  <body
   onLoad="opener.location.href=opener.location.href; window.close();">
  </body>
</html>
