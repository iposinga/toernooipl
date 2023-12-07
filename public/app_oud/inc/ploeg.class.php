<?php
require_once('dbconnection.class.php');
class Ploeg
{
    private $dbconn;

    public function __construct()
    {
        $this->dbconn = new Dbconnection();
    }

	public function maakPloeg($toernooiid, $poule, $team)
	{
		try {
            $sql = "INSERT INTO ploegen (toernid, poule, teamnr) VALUES (?, ?, ?)";
            $query = $this->dbconn->prepare($sql);
            $query->execute([$toernooiid, $poule, $team]);
		} catch(PDOException $e) {
			echo $e->getMessage();
		}
	}
	public function getPloegparameters($toernooiid, $team)
	{
		try {
            $sql = "SELECT * FROM ploegen WHERE toernid=? AND teamnr=?";
            $query = $this->dbconn->prepare($sql);
            $query->execute([$toernooiid, $team]);
			$recset = $query->fetch(PDO::FETCH_ASSOC);
		} catch(PDOException $e) {
            $recset[0] = $e->getMessage();
		}
        return $recset;
	}
	public function getPloegwedstr($toernooiid,$team)
	{
		try {
            $sql = "SELECT speelronde, aanvang, eind, thuisploeg, uitploeg, thuisscore, uitscore, veld FROM wedstrijden WHERE toernid=? AND (thuisploeg=? OR uitploeg=?)";
            $query = $this->dbconn->prepare($sql);
            $query->execute([$toernooiid, $team, $team]);
			$returnstmt="<table><tr><th>ronde</th><th class='veldkop'>tijd</th><th class='veldkop'>wedstrijd</th><th class='veldkop'>veld</th></tr>";
			while($recset = $query->fetch(3)):
				$returnstmt.="<tr><td class='rondenr'><b>$recset[0].</b></td>".PHP_EOL;
				$returnstmt.="<td class='tijden'>".substr($recset[1],0,5)." - ".substr($recset[2],0,5)."</td>".PHP_EOL;
				$returnstmt.="<td class='wedstr'>$recset[3] - $recset[4]";
				if (isset($recset[5]) AND isset($recset[6]))
					$returnstmt.="<br><span style='color: darkblue'>$recset[5] - $recset[6]</span>";
				$returnstmt.="</td>".PHP_EOL;
				$returnstmt.="<td class='wedstr'> $recset[7]</td></tr>";
			endwhile;
			$returnstmt.="</table>";
		} catch(PDOException $e) {
            $returnstmt = $e->getMessage();
		}
        return $returnstmt;
	}
	public function getPloegnaam($toernooiid,$team)
	{
		try {
            $sql = "SELECT naam FROM ploegen WHERE toernid=? AND teamnr=?";
            $query = $this->dbconn->prepare($sql);
            $query->execute([$toernooiid, $team]);
			$recset = $query->fetch(3);
			$returnstmt = $recset[0];
		} catch(PDOException $e) {
            $returnstmt = $e->getMessage();
		}
        return $returnstmt;
	}
}
