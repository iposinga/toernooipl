<?php

//require_once('dbconfig.php');
require_once('query.class.php');

class Wedstrijd
{	
	private $conn;
	
	public function __construct()
	{
		$database = new Database();
		$db = $database->dbConnection();
		$this->conn = $db;
    }
	
	public function maakWedstrijd($wedstr_ar = array())
	{
		try
		{
			$stmt = $this->conn->prepare("INSERT INTO wedstrijden (toernid, poule, ronde, thuisploeg, uitploeg) VALUES (:toernid, :poule, :ronde, :thuisploeg, :uitploeg)");
			$stmt->bindparam(':toernid', $wedstr_ar[0]);
			$stmt->bindparam(':poule', $wedstr_ar[1]);
			$stmt->bindparam(':ronde', $wedstr_ar[2]);
			$stmt->bindparam(':thuisploeg', $wedstr_ar[3]);
			$stmt->bindparam(':uitploeg', $wedstr_ar[4]);	
			$stmt->execute();
		}
		catch(PDOException $e)
		{
			echo $e->getMessage();
		}
	}
	
	public function updateWedstrVeldTijd($wedstrid,$veld,$aanvang,$eind,$speelronde)
	{
		try
		{
			$stmt = $this->conn->prepare("UPDATE wedstrijden SET veld=:veld, aanvang=:aanvang, eind=:eind, speelronde=:speelronde WHERE wedstrid=:wedstrid");
			$stmt->bindparam(':wedstrid', $wedstrid);
			$stmt->bindparam(':veld', $veld);
			$stmt->bindparam(':aanvang', $aanvang);
			$stmt->bindparam(':eind', $eind);
			$stmt->bindparam(':speelronde', $speelronde);	
			$stmt->execute();
		}
		catch(PDOException $e)
		{
			echo $e->getMessage();
		}
	}
	
	public function maakWedstrformulier($wedstrid)
	{
		try
		{
			$stmt=new Query();
			$stmt->appendField('wedstrijden.toernid AS toernooi, veld, poule, speelronde, thuisploeg, uitploeg, naam');
			$stmt->appendTable('wedstrijden');
			$stmt->appendTable('toernooien');
			$stmt->appendRelatie('toernooien.toernid=wedstrijden.toernid');
			$stmt->appendBindvoorwaarde('wedstrid');
			$stmtprep=$stmt->prepSelectQuery();
			$stmtprep->execute([$wedstrid]);
			$recset=$stmtprep->fetch(PDO::FETCH_ASSOC);
			$returnstmt="<h3 align='center'>Wedstrijdformulier ".$recset['naam']."</h3>".PHP_EOL;
			$returnstmt.="<table><thead>".PHP_EOL;
			$returnstmt.="<tr><td class='noborder'>Veld</td><td class='noborder'>Poule</td><td class='noborder'>Ronde</td></tr>".PHP_EOL;
			$returnstmt.="<tr><td class='groot'>".$recset['veld']."</td><td class='groot'>".$recset['poule']."</td><td class='groot'>".$recset['speelronde']."</td></tr>".PHP_EOL;
			$returnstmt.="<tr><td class='noborder'>Thuis</td><td class='noborder'></td><td class='noborder'>Uit</td></tr>".PHP_EOL;
			$thuisploegnaam=$this->zoekNaamvanploeg($recset['toernooi'],$recset['thuisploeg']);
			$uitploegnaam=$this->zoekNaamvanploeg($recset['toernooi'],$recset['uitploeg']);
			$returnstmt.="<tr><td class='middelgroot'>".$recset['thuisploeg']."<br>".$thuisploegnaam."</td><td class='noborder'></td><td class='middelgroot'>".$recset['uitploeg']."<br>".$uitploegnaam."</td></tr>".PHP_EOL;
			$returnstmt.="<tr><td class='noborder'>Turfvak</td><td class='noborder'></td><td class='noborder'>Turfvak</td></tr>".PHP_EOL;
			$returnstmt.="<tr><td class='groot' align='top'></td><td class='noborder'></td><td class='groot' align='top'></td></tr>".PHP_EOL;
			$returnstmt.="<tr><td class='noborder'>Eindstand</td><td class='noborder'></td><td class='noborder'>Eindstand</td></tr>".PHP_EOL;
			$returnstmt.="<tr><td class='groot'></td><td class='noborder'></td><td class='groot'></td></tr>".PHP_EOL;
			$returnstmt.="<tr><td class='noborder'>Team ".$recset['thuisploeg']."</td><td class='noborder'></td><td class='noborder'>Team ".$recset['uitploeg']."</td></tr>".PHP_EOL;
			$returnstmt.="<tr><td class='groot' align='top'></td><td class='noborder'>Handtekening<br>aanvoerders<br>voor akkoord</td><td class='groot' align='top'></td></tr>".PHP_EOL;
			$returnstmt.="</table>".PHP_EOL;
			return $returnstmt;
		}
		catch(PDOException $e)
		{
			echo $e->getMessage();
		}
	}
	
	public function zoekNaamvanploeg($toernooiid,$ploegnr)
	{
		try
		{
			$stmt=new Query();
			$stmt->appendField('naam');
			$stmt->appendTable('ploegen');
			$stmt->appendBindvoorwaarde('toernid');
			$stmt->appendBindvoorwaarde('teamnr');
			$stmtprep=$stmt->prepSelectQuery();
			$stmtprep->execute([$toernooiid,$ploegnr]);
			$recset=$stmtprep->fetch(PDO::FETCH_NUM);
			return $recset[0];
		}
		catch(PDOException $e)
		{
			echo $e->getMessage();
		}
	}	
	
	public function maakUitslaginvoer($wedstrid,$toernooiid)
	{
		try
		{
			$stmt=new Query();
			$stmt->appendField('thuisploeg, uitploeg, thuisscore, uitscore');
			$stmt->appendTable('wedstrijden');
			$stmt->appendBindVoorwaarde('wedstrid');
			$stmtprep=$stmt->prepSelectQuery();
			$stmtprep->execute([$wedstrid]);
			$recset=$stmtprep->fetch(PDO::FETCH_ASSOC);
			$thuisnaam=$this->zoekNaamvanploeg($toernooiid,$recset['thuisploeg']);
			$uitnaam=$this->zoekNaamvanploeg($toernooiid,$recset['uitploeg']);
				
			$returnform="<p>Voer de uitslag in voor de volgende wedstrijd:</p>";	
			$returnform.="<div class='row'>";
			$returnform.="<div class='col'></div>";
			$returnform.="<div class='col'>";
			$returnform.="<form method='post' id='uitslForm'>".PHP_EOL;
			$returnform.="<table class=''>".PHP_EOL;
			$returnform.="<tr><td class='text-center'>".$recset['thuisploeg']."</td><td class='text-center'>-</td><td class='text-center'>".$recset['uitploeg']."</td></tr>";
			if ($thuisnaam <> '')
				$returnform.="<tr><td class='text-center'>".$thuisnaam."</td><td class='text-center'>-</td><td class='text-center'>".$uitnaam."</td></tr>";
			$returnform.="<tr><td class='text-center'><input class='text-center' type='text' size='4' id='homescore' value='".$recset['thuisscore']."'></td><td class='text-center'>-</td><td class='text-center'><input class='text-center' type='text' size='4' id='outscore' value='".$recset['uitscore']."'></td></tr></table></form> ".PHP_EOL;
			$returnform.="</div><div class='col'></div>";
			return $returnform;
		}
		catch(PDOException $e)
		{
			echo $e->getMessage();
		}
	}
	
	public function vulinUitslag($wedstrid,$thuisscore,$uitscore)
	{
		try
		{
			if($thuisscore<>'' AND $uitscore<>'')
			{
				if ($thuisscore > $uitscore)
			    {
				    $thuisp = 3;
				    $uitp = 0;
			    }
			    elseif ($thuisscore < $uitscore)
			    {
				    $thuisp = 0;
				    $uitp = 3;
			    }   
			    else
			    {
				    $thuisp = 1;
				    $uitp = 1;
			    }
			}	
			$stmt = new Query();
			$stmt->appendTable('wedstrijden');
			$stmt->appendField('thuisscore');
			$stmt->appendField('thuispunten');
			$stmt->appendField('uitscore');
			$stmt->appendField('uitpunten');
			$stmt->appendField('meetellen');
			$stmt->appendBindvoorwaarde('wedstrid');
			$stmtprep=$stmt->prepUpdateBindQuery();
			$stmtprep->execute([$thuisscore,$thuisp,$uitscore,$uitp,1,$wedstrid]);
			$returnstmt=$this->getUitslURL($wedstrid,1);
			return $returnstmt;
		}
		catch(PDOException $e)
		{
			echo $e->getMessage();
		}
	}
	
	public function verwijderUitslag($wedstrid)
	{
		try
		{
			$stmt = new Query();
			$stmt->appendTable('wedstrijden');
			$stmt->appendField('thuisscore');
			$stmt->appendField('thuispunten');
			$stmt->appendField('uitscore');
			$stmt->appendField('uitpunten');
			$stmt->appendBindvoorwaarde('wedstrid');
			$stmtprep=$stmt->prepUpdateBindQuery();
			$stmtprep->execute([NULL,NULL,NULL,NULL,$wedstrid]);
			$returnstmt=$this->getUitslURL($wedstrid,0);
			return $returnstmt;
		}
		catch(PDOException $e)
		{
			echo $e->getMessage();
		}
	}	
	
	public function getUitslURL($wedstrid,$type)
	{
		try
		{
			$stmt = new Query();
			$stmt->appendTable('wedstrijden');
			$stmt->appendField('toernid');
			$stmt->appendField('thuisscore');
			$stmt->appendField('thuisploeg');
			$stmt->appendField('uitscore');
			$stmt->appendField('uitploeg');
			$stmt->appendBindvoorwaarde('wedstrid');
			$stmtprep=$stmt->prepSelectQuery();
			$stmtprep->execute([$wedstrid]);
			$recset=$stmtprep->fetch(PDO::FETCH_ASSOC);
			$returnstmt="<a href='#' title='".$recset['thuisploeg']." ".$recset['thuisscore']." - ".$recset['uitscore']." ".$recset['uitploeg']."' onclick=\"voerUitslagIn(".$wedstrid.",".$recset['toernid'].")\">".$recset['thuisploeg']." - ".$recset['uitploeg']."</a>";
			if ($type==1)
				$returnstmt.="&nbsp;<font color='green'>&#10003;</font>";
			return $returnstmt;
		}
		catch(PDOException $e)
		{
			echo $e->getMessage();
		}
	}
}


