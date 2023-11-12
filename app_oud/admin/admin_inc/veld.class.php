<?php
include "../../inc/dbconnection.class.php";
class Veld
{
    private $dbconn;
    public function __construct()
    {
        $this->dbconn = new Dbconnection();
    }
	public function maakVeld($toernooiid, $veld, $plek)
	{
		try {
            $sql = "INSERT INTO velden (toernooi_id, veld, plek) VALUES (?, ?, ?)";
            $query = $this->dbconn->prepare($sql);
			$query->execute([$toernooiid,$veld,$plek]);
		} catch(PDOException $e) {
			echo $e->getMessage();
		}
	}
	public function inserVeldEnUser($veld,$type)
	{
		try {
            $sql = "INSERT INTO usersenvelden (user, veld, soort) VALUES (?, ?, ?)";
            $query = $this->dbconn->prepare($sql);
            $query->execute([$_SESSION['user_session'], $veld, $type]);
		} catch(PDOException $e) {
			echo $e->getMessage();
		}
	}
	public function deleteVeldEnUser($veldid)
	{
		try {
            $sql = "DELETE FROM usersenvelden WHERE userveldid=?";
            $query = $this->dbconn->prepare($sql);
            $query->execute([$veldid]);
		} catch(PDOException $e) {
			echo $e->getMessage();
		}
	}
	public function selectVeldenBijUser($userid)
	{
		try {
            $sql = "SELECT veld, soort, userveldid FROM usersenvelden WHERE user=? ORDER BY soort";
            $query = $this->dbconn->prepare($sql);
            $query->execute([$userid]);
			$returnstmt="<form method='post' action='uservelden.php'>".PHP_EOL;
			$returnstmt.="<input type='hidden' name='verstuurd' value='1'>".PHP_EOL;
			$returnstmt.="<table style='border: none'><tr><th></th><th>locatie</th><th>soort</th><th>delete</th></tr>";
			$i=1;
			while($recset = $query->fetch(3)):
				$returnstmt.="<tr><td style='text-align: right; width: 20px;'>".$i.".&nbsp;</td>".PHP_EOL;
				$returnstmt.="<td>$recset[0]</td>".PHP_EOL;
				$returnstmt.="<td>$recset[1]</td>".PHP_EOL;
				$returnstmt.="<td style='text-align: center'><a href='uservelden.php?delveldid=$recset[2]'><img src='../images/Trash.png' alt=''><a></td></tr>";
				$i++;
			endwhile;
			$returnstmt.="<tr>";
            $returnstmt.="<td style='text-align: right'>$i.&nbsp;</td>";
            $returnstmt.="<td><input type='text' name='field'></td>".PHP_EOL;
			$returnstmt.="<td><input type='text' name='type'></td>";
            $returnstmt.="<td style='text-align: center'><input type='submit' value='voeg toe'></td>";
            $returnstmt.="</tr></table></form>";
		} catch(PDOException $e) {
            $returnstmt = $e->getMessage();
		}
        return $returnstmt;
    }
	/*public function maakVeldeninvoer($toernid)
	{
		try {
            $sql = "SELECT veld, plek FROM velden WHERE toernooi_id=? ORDER BY veld";
            $query = $this->dbconn->prepare($sql);
            $query->execute([$toernid]);
			$returnstmt="<form method='post' action='veldinvoer.php?toernooiid=$toernid'>".PHP_EOL;
			$i=0;
			while($recset = $query->fetch(PDO::FETCH_ASSOC)):
				$returnstmt.="VELD $recset[0]&nbsp;&nbsp;&nbsp;<select name='veldplek$recset[0]'>".PHP_EOL;
				$options=$this->getVeldOpties($_SESSION['user_session'],$recset[1]);
				$returnstmt.=$options;
				$returnstmt.="</optgroup>\n".PHP_EOL;
				$returnstmt.="</select><br>".PHP_EOL;
				$i++;
			endwhile;
			$returnstmt.="<input type='hidden' name='aantal' value='$i'>".PHP_EOL;
			$returnstmt.="<input type='submit' value='werk bij' size='50'>".PHP_EOL;
			$returnstmt.="</form>".PHP_EOL;
		} catch(PDOException $e) {
            $returnstmt = $e->getMessage();
		}
        return $returnstmt;
	}
	public function getVeldOpties($userid,$plek)
	{
		try {
            $sql = "SELECT * FROM usersenvelden WHERE user=? ORDER BY soort";
            $query = $this->dbconn->prepare($sql);
            $query->execute([$userid]);
			$i=0;
			$label="";
			$returnstmt="";
			while($recset = $query->fetch(3)):
				if ($label <> $field[$rinner][1])
				{
					if ($i <> 0)
						$returnstmt.="</optgroup>".PHP_EOL;
					$label = $recset['soort'];
					$afsl = 0;
					$returnstmt.="<optgroup label='".$recset['soort']."'>".PHP_EOL;
    			}
				else
					$afsl=1;
				$returnstmt.="<option value='".$recset['veld']."'".PHP_EOL;
				if ($plek == $recset['veld'])
					$returnstmt.="SELECTED";
				$returnstmt.=">".$recset['veld']."</option>\n".PHP_EOL;
				$i++;
			endwhile;
		} catch(PDOException $e) {
            $returnstmt = $e->getMessage();
		}
        return $returnstmt;
	}*/
	public function updateVeldplek($toernooiid,$veldnr,$plek)
	{
		try {
            $sql = "UPDATE velden SET plek=? WHERE toernooi_id=? AND veld=?";
            $query = $this->dbconn->prepare($sql);
            $query->execute([$plek,$toernooiid,$veldnr]);
		} catch(PDOException $e) {
			echo $e->getMessage();
		}
	}
	public function zoekVeldPlek($toernooiid,$veldnr)
	{
		try {
            $sql = "SELECT plek FROM velden WHERE toernooi_id=? AND veld=?";
            $query = $this->dbconn->prepare($sql);
            $query->execute([$toernooiid,$veldnr]);
			$recset = $query->fetch(3);
			$returnstmt = $recset[0];
		} catch(PDOException $e) {
            $returnstmt = $e->getMessage();
		}
        return $returnstmt;
	}
	public function getAantalVeldPlekken($toernooiid,$plek)
	{
		try {
            $sql = "SELECT COUNT(*) FROM velden WHERE toernooi_id=? AND plek=?";
            $query = $this->dbconn->prepare($sql);
            $query->execute([$toernooiid,$plek]);
			$recset = $query->fetch(3);
			$returnstmt = $recset[0];
		} catch(PDOException $e) {
            $returnstmt = $e->getMessage();
		}
        return $returnstmt;
	}
}
