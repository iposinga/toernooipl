<?php
include "../../inc/dbconnection.class.php";
class Wedstrijd
{
    private $dbconn;
    public function __construct()
    {
        $this->dbconn = new Dbconnection();
    }
	public function maakWedstrijd($wedstr_ar = array())
	{
		try {
            $sql = "INSERT INTO wedstrijden (toernid, poule, ronde, thuisploeg, uitploeg) VALUES (?, ?, ?, ?, ?)";
            $query = $this->dbconn->prepare($sql);
            $query->execute($wedstr_ar);
		} catch(PDOException $e) {
			echo $e->getMessage();
		}
	}
	public function updateWedstrVeldTijd($wedstrid,$veld,$aanvang,$eind,$speelronde)
	{
		try {
            $sql = "UPDATE wedstrijden SET veld=?, aanvang=?, eind=?, speelronde=? WHERE wedstrid=?";
            $query = $this->dbconn->prepare($sql);
            $query->execute([$veld, $aanvang, $eind, $speelronde, $wedstrid]);
		} catch(PDOException $e) {
			echo $e->getMessage();
		}
	}
	public function maakWedstrformulier($wedstrid)
	{
		try {
            $sql = "SELECT wedstrijden.toernid, veld, poule, speelronde, thuisploeg, uitploeg, naam FROM wedstrijden, toernooien WHERE toernooien.toernid=wedstrijden.toernid AND wedstrid=?";
            $query = $this->dbconn->prepare($sql);
            $query->execute([$wedstrid]);
			$recset = $query->fetch(3);
			$returnstmt="<h3 style='text-align: center'>Wedstrijdformulier $recset[6]</h3>".PHP_EOL;
			$returnstmt.="<table><thead>".PHP_EOL;
			$returnstmt.="<tr><td class='noborder'>Veld</td><td class='noborder'>Poule</td><td class='noborder'>Ronde</td></tr>".PHP_EOL;
			$returnstmt.="<tr><td class='groot'>$recset[1]</td><td class='groot'>$recset[2]</td><td class='groot'>$recset[3]</td></tr>".PHP_EOL;
			$returnstmt.="<tr><td class='noborder'>Thuis</td><td class='noborder'></td><td class='noborder'>Uit</td></tr>".PHP_EOL;
			$thuisploegnaam=$this->zoekNaamvanploeg($recset[0],$recset[4]);
			$uitploegnaam=$this->zoekNaamvanploeg($recset[0],$recset[5]);
			$returnstmt.="<tr><td class='middelgroot'>$recset[4]<br>$thuisploegnaam</td><td class='noborder'></td><td class='middelgroot'>$recset[5]<br>$uitploegnaam</td></tr>".PHP_EOL;
			$returnstmt.="<tr><td class='noborder'>Turfvak</td><td class='noborder'></td><td class='noborder'>Turfvak</td></tr>".PHP_EOL;
			$returnstmt.="<tr><td class='groot' style='vertical-align: top;'></td><td class='noborder'></td><td class='groot' style='vertical-align: top;'></td></tr>".PHP_EOL;
			$returnstmt.="<tr><td class='noborder'>Eindstand</td><td class='noborder'></td><td class='noborder'>Eindstand</td></tr>".PHP_EOL;
			$returnstmt.="<tr><td class='groot'></td><td class='noborder'></td><td class='groot'></td></tr>".PHP_EOL;
			$returnstmt.="<tr><td class='noborder'>Team $recset[4]</td><td class='noborder'></td><td class='noborder'>Team $recset[5]</td></tr>".PHP_EOL;
			$returnstmt.="<tr><td class='groot' style='vertical-align: top;'></td><td class='noborder'>Handtekening<br>aanvoerders<br>voor akkoord</td><td class='groot' style='vertical-align: top;'></td></tr>".PHP_EOL;
			$returnstmt.="</table>".PHP_EOL;
		} catch(PDOException $e) {
            $returnstmt = $e->getMessage();
		}
        return $returnstmt;
	}
	public function zoekNaamvanploeg($toernooiid,$ploegnr)
	{
		try {
            $sql = "SELECT naam FROM ploegen WHERE toernid=? AND teamnr=?";
            $query = $this->dbconn->prepare($sql);
            $query->execute([$toernooiid,$ploegnr]);
			$recset = $query->fetch(3);
			$returnstmt = $recset[0];
		} catch(PDOException $e) {
            $returnstmt = $e->getMessage();
		}
        return $returnstmt;
	}
	public function maakUitslaginvoer($wedstrid,$toernooiid)
	{
		try {
            $sql = "SELECT thuisploeg, uitploeg, thuisscore, uitscore, speelronde FROM wedstrijden WHERE wedstrid=?";
            $query = $this->dbconn->prepare($sql);
            $query->execute([$wedstrid]);
			$recset = $query->fetch(3);
			$thuisnaam=$this->zoekNaamvanploeg($toernooiid,$recset[0]);
			$uitnaam=$this->zoekNaamvanploeg($toernooiid,$recset[1]);
			$returnstmt="<form id='uitslaginvoerForm' method='post' onsubmit=\"bewaarUitsl($wedstrid); return false;\">".PHP_EOL;
            //$returnstmt.="<input type='hidden' name='toernooi' value='$toernooiid'>".PHP_EOL;
            //$returnstmt.="<input type='hidden' name='thuis' value='$recset[0]'>".PHP_EOL;
            //$returnstmt.="<input type='hidden' name='uit' value='$recset[1]'>".PHP_EOL;
            //$returnstmt.="<input type='hidden' name='ronde' value='$recset[4]'>";
            $returnstmt.="<table style='border: none'>".PHP_EOL;
            $returnstmt.="<tr><td class='zonder' colspan='3'>voor de wedstrijd:</td>".PHP_EOL;
            $returnstmt.="</tr>".PHP_EOL;
            $returnstmt.="<tr><td style='text-align: center' class='zonder'>$recset[0]</td>";
            $returnstmt.="<td style='text-align: center' class='zonder'>-</td>";
            $returnstmt.="<td style='text-align: center' class='zonder'>$recset[1]</td>";
            $returnstmt.="</tr>";
			if ($thuisnaam <> '') {
                $returnstmt .= "<tr><td style='text-align: center' class='zonder'>$thuisnaam</td>";
                $returnstmt .= "<td style='text-align: center' class='zonder'>-</td>";
                $returnstmt .= "<td style='text-align: center' class='zonder'>$uitnaam</td>";
                $returnstmt.="</tr>";
            }
            $returnstmt.="<tr><td style='text-align: center' class='zonder'><input style='font-size: large;' class='midden' type='text' size='4' id='homescore' value='$recset[2]'></td>";
            $returnstmt.="<td style='text-align: center' class='zonder'>-</td>";
            $returnstmt.="<td style='text-align: center' class='zonder'><input style='font-size: large;' class='midden' type='text' size='4' id='outscore' value='$recset[3]'></td>";
            $returnstmt.="</tr></table></form>".PHP_EOL;
		} catch(PDOException $e) {
            $returnstmt = $e->getMessage();
		}
        return $returnstmt;
	}
	public function verwijderUitslag($wedstrid)
	{
		try {
            $sql = "UPDATE wedstrijden SET thuisscore=?, thuispunten=?, uitscore=?, uitpunten=?, meetellen=? WHERE wedstrid=?";
            $query = $this->dbconn->prepare($sql);
            $query->execute([NULL,NULL,NULL,NULL,0,$wedstrid]);
		} catch(PDOException $e) {
			echo $e->getMessage();
		}
	}
	public function vulinUitslag($wedstrid,$thuisscore,$uitscore)
	{
		try {
			if($thuisscore<>'' AND $uitscore<>'')
			{
				if ($thuisscore > $uitscore) {
				    $thuisp = 3;
				    $uitp = 0;
			    }
			    elseif ($thuisscore < $uitscore) {
				    $thuisp = 0;
				    $uitp = 3;
			    }
			    else {
				    $thuisp = 1;
				    $uitp = 1;
			    }
			}
            $sql = "UPDATE wedstrijden SET thuisscore=?, thuispunten=?, uitscore=?, uitpunten=?, meetellen=? WHERE wedstrid=?";
            $query = $this->dbconn->prepare($sql);
            $query->execute([$thuisscore,$thuisp,$uitscore,$uitp,1,$wedstrid]);
		} catch(PDOException $e) {
			echo $e->getMessage();
		}
	}
	public function getSwapGeg($wedstrid)
	{
		try {
            $sql = "SELECT thuisploeg, thuisscore, thuispunten, uitploeg, uitscore, uitpunten, poule FROM wedstrijden WHERE wedstrid=?";
            $query = $this->dbconn->prepare($sql);
            $query->execute([$wedstrid]);
			$recset = $query->fetch(PDO::FETCH_ASSOC);
		} catch(PDOException $e) {
            $recset[0] = $e->getMessage();
		}
        return $recset;
	}
	public function updateWedstrijdschema($wedstr,$thuispl,$thuissc,$thuispt,$uitpl,$uitsc,$uitpt,$poule)
	{
		try {
            $sql = "UPDATE wedstrijden SET thuisploeg=?, thuisscore=?, thuispunten=?, uitploeg=?, uitscore=?, uitpunten=?, poule=? WHERE wedstrid=?";
            $query = $this->dbconn->prepare($sql);
            $query->execute([$thuispl,$thuissc,$thuispt,$uitpl,$uitsc,$uitpt,$poule,$wedstr]);
		} catch(PDOException $e) {
			echo $e->getMessage();
		}
	}
    public function getWedstrGeg($wedstrid, $toernooiid)
    {
        try {
            $sql = "SELECT p1.naam AS thuisnaam, p2.naam AS uitnaam, thuisploeg, uitploeg, wedstrijden.toernid AS toernooiid
                FROM wedstrijden
                LEFT JOIN ploegen AS p1 ON p1.teamnr = wedstrijden.thuisploeg AND p1.toernid = wedstrijden.toernid
                LEFT JOIN ploegen AS p2 ON p2.teamnr = wedstrijden.uitploeg AND p2.toernid = wedstrijden.toernid
                WHERE wedstrid=? AND p1.toernid=?";
            $query = $this->dbconn->prepare($sql);
            $query->execute([$wedstrid, $toernooiid]);
            $recset = $query->fetch(4);
        } catch(PDOException $e) {
            $recset[0] = $e->getMessage();
        }
        return $recset;
    }
}
