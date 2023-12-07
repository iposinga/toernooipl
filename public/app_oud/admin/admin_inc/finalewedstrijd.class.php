<?php
include "./app_oud/inc/dbconnection.class.php";

class Finalewedstrijd
{
    private $dbconn;
    public function __construct()
    {
        $this->dbconn = new Dbconnection();
    }
	public function aantalFinalewedstrijden($toernid)
	{
		try {
            $sql = "SELECT COUNT(*) FROM finalewedstrijden WHERE toernooi_id=?";
            $query = $this->dbconn->prepare($sql);
            $query->execute([$toernid]);
			$recset = $query->fetch(3);
			$returnstmt = $recset[0];
		} catch(PDOException $e) {
            $returnstmt = $e->getMessage();
		}
        return $returnstmt;
	}
	public function insertFinalewedstr($tid,$aanvang,$eind,$thuispl,$uitpl,$round,$i,$wnaam): void
	{
		try {
            $sql = "INSERT INTO finalewedstrijden (toernooi_id, aanvang, eind, thuisploeg, uitploeg, finaleronde, veld, fin_wedstrnaam) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
            $query = $this->dbconn->prepare($sql);
            $query->execute([$tid,$aanvang,$eind,$thuispl,$uitpl,$round,$i,$wnaam]);
		} catch(PDOException $e) {
			echo $e->getMessage();
		}
	}
	public function aantalFinaleRondes($toernid)
	{
		try {
            $sql = "SELECT MAX(finaleronde) FROM finalewedstrijden WHERE toernooi_id=?";
            $query = $this->dbconn->prepare($sql);
			$query->execute([$toernid]);
			$recset = $query->fetch(3);
			$returnstmt = $recset[0];
		} catch(PDOException $e) {
            $returnstmt = $e->getMessage();
		}
        return $returnstmt;
	}
	public function getFinaleSchema($toernooiid,$finmax,$colspan1,$colspan2,$colspan3,$startronde): string
	{
		try {
            $sql = "SELECT finaleronde, aanvang, eind, veld, thuisploeg, uitploeg, fin_wedstrnaam FROM finalewedstrijden WHERE toernooi_id=? ORDER BY finaleronde, veld";
            $query = $this->dbconn->prepare($sql);
			$query->execute([$toernooiid]);
			$returnstmt="";
			$i = 0;
           //$counter = 0;
			while($recset = $query->fetch(3)):
				$class="wedstrprint";
				$class2="lijnzonder";
		//als de eerste wedstrijd van een nieuwe speelronde zich aandient, moet er weer een nieuwe rij komen met ronde en tijden
			    if ($i <> $recset[0]):
			        //$counter = 1;
			        if (($i+1)%5 == 0) {
			        	$class="lijnonderenlinks";
						$class2="lijnonder";
			        }
			        if ($i+1 == $finmax) {
				        $class="lijnlinks";
				        $class2="lijnzonder";
			        }
			        $ronde = $startronde + $recset[0];
			        $returnstmt.="</tr>\n<tr>".PHP_EOL;
			        $returnstmt.="<td style='text-align: right;' class='$class2'><a href='finalerondes.php?toernooiid=$toernooiid&finaleronde=$recset[0]'><img src='../images/Trash.png' alt=''></a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>".$ronde.".</b></td>".PHP_EOL;
			        $returnstmt.="<td class='$class'>".substr($recset[1],0,5)." - ".substr($recset[2],0,5)."</td>";
			        $i++;
		    	endif;
		//rij wordt aangevuld met alle wedstrijden in de speelronde
			    if (($i)%5 == 0) {
				    if($recset[3]==1 OR $recset[3]==$colspan1 + 1 OR $recset[3]==$colspan1 + $colspan2 + 1 OR $recset[3]==$colspan1 + $colspan2 + $colspan3 + 1)
				    	$class="lijnonderenlinksvet";
				    else
				    	$class="lijnonderenlinks";
			    }
			    else
			    {
				    if ($recset[3]==1 OR $recset[3]==$colspan1 + 1 OR $recset[3]==$colspan1 + $colspan2 + 1 OR $recset[3]==$colspan1 + $colspan2 + $colspan3 + 1)
				    	$class="lijnlinksvet";
				    else
				    	$class="lijnlinks";
			    }
/*                if ($finalerondeteller == $finmax)
			    {
				    if ($recset[3]==1 OR $recset[3]==$colspan1 + 1 OR $recset[3]==$colspan1 + $colspan2 + 1 OR $recset[3]==$colspan1 + $colspan2 + $colspan3 + 1)
				    	$class="lijnlinksvet";
				    else
			        	$class="lijnlinks";
			    }*/
				$returnstmt.="<td class='$class' style='text-align: center;'>";
				if ($recset[4] <> '')
					$returnstmt.="$recset[6]<br>$recset[4] - $recset[5]";
				$returnstmt.="</td>\n";
				//$counter++;
			endwhile;
		} catch(PDOException $e) {
            $returnstmt = $e->getMessage();
		}
        return $returnstmt;
	}

	public function deleteFinaleRonde($toernooiid, $rondid): void
	{
		try {
            $sql = "DELETE FROM finalewedstrijden WHERE toernooi_id=? AND finaleronde=?";
            $query = $this->dbconn->prepare($sql);
            $query->execute([$toernooiid, $rondid]);
		} catch(PDOException $e) {
			echo $e->getMessage();
		}
	}

}
