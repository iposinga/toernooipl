<?php
//namespace app_oud\inc;
//require_once '../../inc/query.class.php';
//require_once($_SERVER['DOCUMENT_ROOT'].'/app/includes/query.class.php');
include '../../inc/query.class.php';

class Winstregel
{
	public function inserWinstregelEnUser($winstregel)
	{
		try
		{
			$stmt = new Query();
			$stmt->appendTable('usersenwinstbepalingen');
			$stmt->appendField('user_id');
			$stmt->appendField('winstbepaling');
			$stmtprep=$stmt->prepInsertBindQuery();
			$stmtprep->execute([$_SESSION['user_session'],$winstregel]);
		}
		catch(PDOException $e)
		{
			echo $e->getMessage();
		}
	}

	public function deleteWinstregelEnUser($winstregelid)
	{
		$stmt = new Query();
		$stmt->appendTable('usersenwinstbepalingen');
		$stmt->appendBindvoorwaarde('userenwinstbep_id');
		$stmtprep=$stmt->prepDeleteQuery();
		$stmtprep->execute([$winstregelid]);
	}

	public function selectWinstregelsBijUser($userid)
	{
		$stmt = new Query();
		$stmt->appendField('*');
		$stmt->appendTable('usersenwinstbepalingen');
		$stmt->appendBindVoorwaarde('user_id');
		$stmt->appendOrderBy('userenwinstbep_id');
		$stmtprep = $stmt->prepSelectQuery();
		$stmtprep->execute([$userid]);
		$returnstmt="<form method='post' action='userwinstbepalingen.php'>".PHP_EOL;
		$returnstmt.="<input type='hidden' name='verstuurd' value='1'>".PHP_EOL;
		$returnstmt.="<table border=1><tr><th></th><th>winstbepaling</th><th>delete</th></tr>";
		$i=1;
		while($recset=$stmtprep->fetch(PDO::FETCH_ASSOC))
		{
			$returnstmt.="<tr><td align=right width=20>".$i.".&nbsp;</td><td align='left'>".$recset['winstbepaling']."</td><td align='center'><a href='userwinstbepalingen.php?delbepid=".$recset['userenwinstbep_id']."'><img src='../images/Trash.png' valign='middle'><a></td></tr>";
	    $i++;
		}
		$returnstmt.="<tr><td align='right'>".$i.".&nbsp;</td><td align='left'><textarea rows='4' cols='120' name='bepaling'></textarea></td>".PHP_EOL;
		$returnstmt.="<td align='center'><input type='submit' value='voeg toe'></td></tr></table></form>";
		return $returnstmt;
	}

	public function zoekWinstBepaling($winstbepid)
	{
		$stmt = new Query();
		$stmt->appendField('*');
		$stmt->appendTable('usersenwinstbepalingen');
		$stmt->appendBindVoorwaarde('user_id');
		$stmtprep = $stmt->prepSelectQuery();
		$stmtprep->execute([$_SESSION['user_session']]);
		$returnstmt="<table>";
		$i=1;
		while($recset=$stmtprep->fetch(PDO::FETCH_ASSOC))
		{
			$returnstmt.="<tr><td valign=top class=zonder><input type=radio name=winst value=".$recset['userenwinstbep_id'];
			if ($winstbepid==$recset['userenwinstbep_id'])
				$returnstmt.=" CHECKED";
			$returnstmt.= "></td><td class=zonder>".$recset['winstbepaling']."</td></tr>";
			$i++;
		}
		$returnstmt.="</table>";
		return $returnstmt;
	}

	public function zoekToernooiWinstregel($twinnaar)
	{
		$stmt = new Query();
		$stmt->appendField('winstbepaling');
		$stmt->appendTable('usersenwinstbepalingen');
		$stmt->appendBindVoorwaarde('userenwinstbep_id');
		$stmtprep = $stmt->prepSelectQuery();
		$stmtprep->execute([$twinnaar]);
		$recset=$stmtprep->fetch(PDO::FETCH_NUM);
		return $recset[0];
	}

}
