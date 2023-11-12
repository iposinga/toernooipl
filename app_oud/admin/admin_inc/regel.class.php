<?php
//namespace app_oud\inc;
//require_once '../../../inc/query.class.php';
include '../../inc/query.class.php';

class Regel
{
	public function inserRegelEnUser($regel)
	{
		try
		{
			$stmt = new Query();
			$stmt->appendTable('usersengedragsregels');
			$stmt->appendField('user_id');
			$stmt->appendField('regel');
			$stmtprep=$stmt->prepInsertBindQuery();
			$stmtprep->execute([$_SESSION['user_session'],$regel]);
		}
		catch(PDOException $e)
		{
			echo $e->getMessage();
		}
	}

	public function deleteRegelEnUser($regelid)
	{
		try
		{
		$stmt = new Query();
		$stmt->appendTable('usersengedragsregels');
		$stmt->appendBindvoorwaarde('userengedrag_id');
		$stmtprep=$stmt->prepDeleteQuery();
		$stmtprep->execute([$regelid]);
		}
		catch(PDOException $e)
		{
			echo $e->getMessage();
		}
	}

	public function selectRegelsBijUser($userid)
	{
		try
		{
		$stmt = new Query();
		$stmt->appendField('*');
		$stmt->appendTable('usersengedragsregels');
		$stmt->appendBindVoorwaarde('user_id');
		$stmt->appendOrderBy('userengedrag_id');
		$stmtprep = $stmt->prepSelectQuery();
		$stmtprep->execute([$userid]);
		$returnstmt="<form method='post' action='usergedragsregels.php'>".PHP_EOL;
		$returnstmt.="<input type='hidden' name='verstuurd' value='1'>".PHP_EOL;
		$returnstmt.="<table border=1><tr><th></th><th>locatie</th><th>delete</th></tr>";
		$i=1;
		while($recset=$stmtprep->fetch(PDO::FETCH_ASSOC))
		{
			$returnstmt.="<tr><td align=right width=20>".$i.".&nbsp;</td><td align='left'>".$recset['regel']."</td><td align='center'><a href='usergedragsregels.php?delruleid=".$recset['userengedrag_id']."'><img src='../images/Trash.png' valign='middle'><a></td></tr>";
	    $i++;
		}
		$returnstmt.="<tr><td align='right'>".$i.".&nbsp;</td><td align='left'><input type='text' name='rule' size='100'></td>".PHP_EOL;
		$returnstmt.="<td align='center'><input type='submit' value='voeg toe'></td></tr></table></form>";
		return $returnstmt;
		}
		catch(PDOException $e)
		{
			echo $e->getMessage();
		}
	}

	public function zoekRegels($regelid)
	{
		try
		{
		$stmt = new Query();
		$stmt->appendField('*');
		$stmt->appendTable('usersengedragsregels');
		$stmt->appendBindVoorwaarde('user_id');
		$stmtprep = $stmt->prepSelectQuery();
		$stmtprep->execute([$_SESSION['user_session']]);
		$returnstmt="";
		$i=1;
		while($recset=$stmtprep->fetch(PDO::FETCH_ASSOC))
		{
			$returnstmt.="<input type='checkbox' name='gedrag[]' value='".$recset['userengedrag_id']."'";
			if (substr_count($regelid,$recset['userengedrag_id']) > 0)
				$returnstmt.=" CHECKED";
			$returnstmt.= "> ".$recset['regel']."<br>";
			$i++;
		}
		return $returnstmt;
		}
		catch(PDOException $e)
		{
			echo $e->getMessage();
		}
	}

	public function zoekToernooiRegel($tgedrag)
	{
		try
		{
			$expl = explode(",", $tgedrag);
			$searchstring = "";
			$v = 0;
			while ($expl[$v] <> '')
			{
				if ($v > 0)
					$searchstring = $searchstring." OR ";
				$searchstring = $searchstring."userengedrag_id = ".$expl[$v];
				$v++;
			}
			if ($searchstring != '')
			{
				$stmt = new Query();
				$stmt->appendField('*');
				$stmt->appendTable('usersengedragsregels');
				$stmt->appendVoorwaardeString($searchstring);
				$stmtprep = $stmt->prepSelectQuery();
				$stmtprep->execute();
				$returnstmt="<br><b>Denk om:</b><br>";
				while($recset=$stmtprep->fetch(PDO::FETCH_ASSOC))
				{
					$returnstmt.="- ".$recset['regel']."<br>";
				}
				return $returnstmt;
			}
		}
		catch(PDOException $e)
		{
			echo $e->getMessage();
		}
	}
}
