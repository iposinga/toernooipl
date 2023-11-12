<?php
require_once('dbconnection.class.php');

class Poule
{
    private $dbconn;

    public function __construct()
    {
        $this->dbconn = new Dbconnection();
    }


	public function maaknieuwePoule($aantalteams, $startteamnr, $toernooiid, $poule, $heel)
	{
		try {
			$teller = 1;
			while ($teller <= $aantalteams)
			{
				$teams[$teller] = $startteamnr;
				$startteamnr++;
				$teller++;
			}
			//we hebben nu een array 'teams' met de gewenste teamnummers
			$aantalwedstrperronde = intval($aantalteams/2);
			//als er een oneven aantal teams is, wordt er nog een team 0 toegevoegd; als een team tegen team 0 speelt, is dat team vrij
			if ($aantalteams/2 <> intval($aantalteams/2)):
				$teams[$teller] = 0;
				$aantalteams++;
				$aantalwedstrperronde++;
			endif;
			$aantalspeelrondes = count($teams) - 1;
			$ronde = 1;
			//onderstaande rondeteller is nodig om de thuiswedstrijden van het eerste team om te zetten naar uitwedstrijden in poules
			//met een oneven aantal teams; als team 1 vrij (tegen team 0 speelt) is, dan telt deze teller niet door
			$ronde2 = 1;
			//in elke iteratie in de volgende loop wordt op basis van de array 'rondeteams' rondewedstrijden gemaakt;
			//per iteratie schuift in deze array elk team een plekje op, waardoor er steeds weer nieuwe wedstrijden ontstaan
			$rondeteams = $teams;
			while ($ronde <= $aantalteams - 1):
				//de eerste wedstrijd is altijd positie 1 (positie 1 is altijd het laagste teamnr in de poule,
				//daardoor zou dat team altijd thuis spelen.....) tegen positie 2
				if ($rondeteams[2] <> 0)
				{
				//als de ronde even is, moet je thuis en uit omdraaien, anders......
    				if ($ronde2 % 2 == 0)
    					$insert_ar = array($toernooiid, $poule, $ronde, $rondeteams[2], $rondeteams[1]);
    				else
    					$insert_ar = array($toernooiid, $poule, $ronde, $rondeteams[1], $rondeteams[2]);
					$inseerstewedstr = new Wedstrijd();
					$inseerstewedstr->maakWedstrijd($insert_ar);
					//als het een hele competitie is, meteen de terugwedstrijd ook toevoegen
					if ($heel == 1)
					{
						$terugronde = $ronde + $aantalspeelrondes;
						if ($ronde2 % 2 == 0)
    						$insert_ar = array($toernooiid, $poule, $terugronde, $rondeteams[1], $rondeteams[2]);
						else
							$insert_ar = array($toernooiid, $poule, $terugronde, $rondeteams[2], $rondeteams[1]);
						$inseersteterugwedstr = new Wedstrijd();
						$inseersteterugwedstr->maakWedstrijd($insert_ar);
					}
					$ronde2++;
  				}
  				$rondewedstr = 2;
  				$positie = 3;
  				while ($rondewedstr <= $aantalwedstrperronde)
  				{
					$thuis = $rondeteams[$positie];
					$uit = $rondeteams[$aantalteams-($positie-3)];
					if ($thuis <> 0 AND $uit <> 0)
					{
						$insert_ar = array($toernooiid, $poule, $ronde, $thuis, $uit);
						$insnextwedstr = new Wedstrijd();
						$insnextwedstr->maakWedstrijd($insert_ar);
						//als het een hele competitie is, meteen de terugwedstrijd ook toevoegen
						if ($heel == 1)
						{
							$terugronde = $ronde + $aantalspeelrondes;
							$insert_ar = array($toernooiid, $poule, $terugronde, $uit, $thuis);
							$insnextterugwedstr = new Wedstrijd();
							$insnextterugwedstr->maakWedstrijd($insert_ar);
						}
    				}
					$positie++;
					$rondewedstr++;
  				}
  				$ronde++;
  				//nu de array herschikken
  				$teller = $aantalteams;
  				$positiemax = $rondeteams[$aantalteams];
  				while ($teller >= 2)
  				{
  					if ($teller == 2)
  						$rondeteams[$teller] = $positiemax;
  					else
  						$rondeteams[$teller] = $rondeteams[$teller-1];
  					$teller--;
  				}
			endwhile;
            /*als het een toernooi betreft met 1 veld en 1 poule van 5 of 6 teams (zoals bij het Lopsterzaalvoetbal),
                  dan het perfecte spelschema maken door de juiste wedstrijd in de juiste speelronde te plaatsen:
                  5 teams:
                    1. team 1 - team 2
                    2. team 3 - team 4
                    3. team 5 - team 1
                    4. team 2 - team 3
                    5. team 4 - team 5
                    6. team 1 - team 3
                    7. team 5 - team 2
                    8. team 4 - team 1
                    9. team 3 - team 5
                    10. team 2 - team 4
                  6 teams:
                    1. team 1 - team 6
                    2. team 2 - team 5
                    3. team 3 - team 4
                    4. team 5 - team 1
                    5. team 6 - team 3
                    6. team 4 - team 2
                    7. team 1 - team 3
                    8. team 4 - team 5
                    9. team 2 - team 6
                    10. team 1 - team 4
                    11. team 3 - team 2
                    12. team 5 - team 6
                    13. team 2 - team 1
                    14. team 6 - team 4
                    15. team 3 - team 5
                  */
            $this->maakPerfectSchema($toernooiid);
		} catch(PDOException $e) {
			echo $e->getMessage();
		}
	}
	public function toonWedstrijdschema($toernooiid,$poule)
	{
		try{
            $sql = "SELECT t1.naam AS thuisnaam, t2.naam AS uitnaam, thuisploeg, uitploeg, thuisscore, uitscore, speelronde, aanvang, eind, wedstrijden.poule, veld, wedstrid
            FROM wedstrijden
            LEFT JOIN ploegen AS t1 ON t1.teamnr = wedstrijden.thuisploeg AND t1.toernid=wedstrijden.toernid
            LEFT JOIN ploegen AS t2 ON t2.teamnr = wedstrijden.uitploeg AND t2.toernid=wedstrijden.toernid
            WHERE wedstrijden.toernid=? AND wedstrijden.poule=? ORDER BY speelronde, veld";
            $query = $this->dbconn->prepare($sql);
            $query->execute([$toernooiid, $poule]);
            $pouletabelbody="<tbody>";
			$rondeteller = 0;
			$colspan=3;
			$teller=0;
            $kopherhaling = 0;
            $kopherhalingmax = 0;
			while ($recset=$query->fetch(3)):
				if ($rondeteller <> $recset[6]){
                    $pouletabelbody.="<tr class='wedstr_$recset[2] wedstr_$recset[3]'><td class='text-right'><b>$recset[6].</b></td>";
                    $pouletabelbody.="<td class='text-right'>".substr($recset[7],0,5)."</td><td>&nbsp;-&nbsp;</td><td> ".substr($recset[8],0,5)."</td>";
					$rondeteller=$recset[6];
					$colspan=max($colspan,$teller);
					$teller=0;
                    $kopherhaling = 0;
    			}
                $pouletabelbody.="<td class='wedstr'>$recset[0]</td>";
                $pouletabelbody.="<td class='wedstr'>-</td>";
                $pouletabelbody.="<td class='wedstr'>$recset[1]</td>";
                $pouletabelbody.="<td class='text-center'>";
				if (isset($recset[4]) AND isset($recset[5]))
                    $pouletabelbody.=$recset[4]." - ".$recset[5];
                $pouletabelbody.="</td>";
				$teller++;
                $kopherhaling++;
                $kopherhalingmax = max($kopherhalingmax, $kopherhaling);
			endwhile;
            $pouletabelbody.="</tr></tbody>";
            $returnstmt="<h2>Wedstrijdschema</h2>";
			$returnstmt.="<table class='table table-sm table-hover'><thead><tr>";
			$returnstmt.="<th class='rondenrkoppoule'></th>";
			$returnstmt.="<th colspan='3'>tijd</th>";
            for($i =0; $i < $kopherhalingmax; $i++):
			    $returnstmt.="<th colspan='$colspan'>wedstrijd</th>";
			    $returnstmt.="<th>uitslag</th>";
            endfor;
			$returnstmt.="</tr></thead>";
			$returnstmt.=$pouletabelbody."</table>";
		} catch(PDOException $e) {
            $returnstmt = $e->getMessage();
		}
        return $returnstmt;
	}
	public function displayPoulewedstr($toernooiid,$poule)
	{
		try {
			$sql = "SELECT thuisploeg, uitploeg, thuisscore, uitscore, speelronde, veld
                FROM wedstrijden
                LEFT JOIN ploegen AS t1 ON t1.teamnr = wedstrijden.thuisploeg AND t1.toernid=wedstrijden.toernid
                LEFT JOIN ploegen AS t2 ON t2.teamnr = wedstrijden.uitploeg AND t2.toernid=wedstrijden.toernid
                WHERE wedstrijden.toernid=:toernooiid AND wedstrijden.poule=:poule ORDER BY speelronde, veld";
			$query = $this->dbconn->prepare($sql);
            $query->execute([$toernooiid, $poule]);
			$pouletabel="<table class='table table-sm'>";
			$pouletabel.="<thead><tr><th class='standleft'>ronde</th>";
			$pouletabel.="<th class='standcenter' colspan='3'>wedstrijden (veld)</th>";
			$pouletabel.="</tr></thead><tbody><tr>";
			$rondeteller = 0;
			while($recset = $query->fetch(3)):
				if ($rondeteller <> $recset[4])
                {
                    if ($rondeteller > 0)
                        $pouletabel.="</tr>".PHP_EOL."<tr>";
                    $pouletabel.="<td class='rijkop'>$recset[4].&nbsp;</td>".PHP_EOL;
                    $rondeteller=$recset[4];
                }
				$inhoudcel = $recset[0].' - '.$recset[1];
				if (isset($recset[2]) AND isset($recset[3]))
                    $inhoudcel.="<br><span style='color: red;  font-size: smaller'>$recset[2] - $recset[3]</span>";
                else
                    $inhoudcel.=" ($recset[5])";
				$pouletabel.="<td class='wedstr'>$inhoudcel</td>".PHP_EOL;
			endwhile;
			$pouletabel.="</tr></table>".PHP_EOL;
			$returnstmt="<h2>Poule $poule</h2>$pouletabel";
        } catch(PDOException $e) {
            $returnstmt = $e->getMessage();
		}
        return $returnstmt;
	}
	public function displayPoulestand($toernooiid,$poule): string
	{
		try {
            $sql = "SELECT * FROM ploegen WHERE toernid=? AND poule=? ORDER BY teamnr";
            $query = $this->dbconn->prepare($sql);
            $query->execute([$toernooiid, $poule]);
			$rondeteller = 0;
			while($recset = $query->fetch(PDO::FETCH_ASSOC)){
                $sql2 = "SELECT * FROM wedstrijden WHERE toernid=? AND (thuisploeg=? OR uitploeg=?) ORDER BY ronde";
                $query2 = $this->dbconn->prepare($sql2);
                $query2->execute([$toernooiid, $recset['teamnr'], $recset['teamnr']]);
				$punten = 0;
				$gespeeld = 0;
				$winst = 0;
				$gelijk = 0;
				$verlies = 0;
				$voor = 0;
				$tegen = 0;
				while ($recset2 = $query2->fetch(PDO::FETCH_ASSOC)){
					if ($recset2['thuisscore'] <> ''){
						if ($recset['teamnr'] == $recset2['thuisploeg']){
							$punten = $punten + $recset2['thuispunten'];
							$voor = $voor + $recset2['thuisscore'];
							$tegen = $tegen + $recset2['uitscore'];
							if ($recset2['thuispunten'] == 1)
							$gelijk++;
							elseif ($recset2['thuispunten'] == 0)
							$verlies++;
							else
							$winst++;
        				} else {
							$punten = $punten + $recset2['uitpunten'];
							$voor = $voor + $recset2['uitscore'];
							$tegen = $tegen + $recset2['thuisscore'];
							if ($recset2['uitpunten'] == 1)
							$gelijk++;
							elseif ($recset2['uitpunten'] == 0)
							$verlies++;
							else
							$winst++;
        				}
						$gespeeld++;
        			}
				}
				$naam = $recset['naam'];
				$saldo = $voor - $tegen;
				//zet de data in een array
				$data[] = array('team' => $recset['teamnr'],
				'naam' => $naam,
				'punten' => $punten,
				'gespeeld' => $gespeeld,
				'winst' => $winst,
				'gelijk' => $gelijk,
				'verlies' => $verlies,
				'voor' => $voor,
				'tegen' => $tegen,
				'saldo' => $saldo);
			}
			foreach ($data as $key => $row)
			{
				$points[$key]  = $row['punten'];
				$played[$key] = $row['gespeeld'];
				$doelscore[$key] = $row['saldo'];
			}
			//array_multisort($sort['punten'], SORT_DESC, $sort['gespeeld'], SORT_ASC, $sort['saldo'], SORT_DESC, $data);
			array_multisort($points, SORT_DESC, $played, SORT_ASC, $doelscore, SORT_DESC, $data);
			$returnstmt = "<table class='table table-sm'>";
			$returnstmt .= "<thead><tr>";
			$returnstmt .= "<th></th>";
			$returnstmt .= "<th class='standleft'>team</th>";
			$returnstmt .= "<th class='standcenter'>p</th>";
			$returnstmt .= "<th class='standcenter'>g</th>";
			$returnstmt .= "<th class='standcenter'>w</th>";
			$returnstmt .= "<th class='standcenter'>g</th>";
			$returnstmt .= "<th class='standcenter'>v</th>";
			$returnstmt .= "<th colspan='3'>doelsaldo</th></tr></thead><tbody>";
			$positie = 1;
			foreach ($data as $teamrij){
				$returnstmt .= "<tr class='team_{$teamrij['team']}'>";
				$returnstmt .= "<td class='standright'><b>".$positie.".</b></td>";
                $returnstmt .= "<td class='standleft'><a class='link' href='#' onclick='highLight({$teamrij['team']})'><i>{$teamrij['naam']}</i></a></td>";
				$returnstmt .= "<td class='standcenter' style='background: yellow;'>{$teamrij['punten']}</td>";
				$returnstmt .= "<td class='standcenter'>{$teamrij['gespeeld']}</td>";
				$returnstmt .= "<td class='standcenter'>{$teamrij['winst']}</td>";
				$returnstmt .= "<td class='standcenter'>{$teamrij['gelijk']}</td>";
				$returnstmt .= "<td class='standcenter'>{$teamrij['verlies']}</td>";
				$returnstmt .= "<td class='standright'>+{$teamrij['voor']}</td>";
				$returnstmt .= "<td class='standright'>-{$teamrij['tegen']}</td>";
				$returnstmt .= "<td class='standright' style='background: orange;'>{$teamrij['saldo']}</td>";
				$returnstmt .= "</tr>";
				$positie++;
			}
			$returnstmt .= "</tbody></table>";
			$returnstmt="<h2>Stand</h2>".$returnstmt;
		} catch(PDOException $e) {
            $returnstmt = $e->getMessage();
		}
        return $returnstmt;
	}
	public function getPouleploegen($toernooiid, $poule)
	{
		try {
            $sql = "SELECT teamnr, naam  FROM ploegen WHERE toernid=? AND poule=? ORDER BY teamnr";
			$query = $this->dbconn->prepare($sql);
            $query->execute([$toernooiid, $poule]);
			$returnstmt = "<table class='poule'>";
            $returnstmt .= "<tr><td class='poulekop' colspan='2'><a href='#' title='poule-overzicht' onclick=\"showPouleSchema($toernooiid, '$poule')\">poule $poule</a></td></tr>";
			while($recset = $query->fetch(3)):
                $returnstmt .= "<tr><td class='teamnr bg-primary'><a class='teamnr' href='#' title='teamoverzicht' onclick=\"showTeamSchema($toernooiid, $recset[0])\">$recset[0]</a></td>".PHP_EOL;
			    if($recset[1]<>'')
                    $returnstmt .= "<td class='teamnaam'>$recset[1]</td></tr>".PHP_EOL;
			    else
                    $returnstmt .= "<td class='teamnaam_leeg'></td></tr>".PHP_EOL;
			endwhile;
            $returnstmt .= "</table>";
		} catch(PDOException $e) {
            $returnstmt = $e->getMessage();
		}
        return $returnstmt;
	}
	public function printPoulecel($toernooiid, $poule)
	{
		try {
            $sql = "SELECT teamnr, naam FROM ploegen WHERE toernid=? AND poule=? ORDER BY teamnr";
            $query = $this->dbconn->prepare($sql);
            $query->execute([$toernooiid, $poule]);
			$returnstmt = "<td style='vertical-align: top' class='met'>";
            $returnstmt .= "<b><i>Poule ".$poule."</i></b><br>";
			while($recset = $query->fetch(3)):
                $returnstmt .= "$recset[0] = $recset[1]<br>";
			endwhile;
            $returnstmt .= "</td>";
		} catch(PDOException $e) {
            $returnstmt = $e->getMessage();
		}
        return $returnstmt;
	}
	public function zoekNaam($toernooiid,$teamnr)
	{
		try {
            $sql = "SELECT naam FROM ploegen WHERE toernid=? AND teamnr=?";
            $query = $this->dbconn->prepare($sql);
            $query->execute([$toernooiid, $teamnr]);
			$recset = $query->fetch(3);
            $returnstmt = $recset[0];
		} catch(PDOException $e) {
            $returnstmt = $e->getMessage();
		}
        return $returnstmt;
	}
    private function maakPerfectSchema($toernooiid){
        try {
            $sql = "SELECT teams, poules, velden FROM toernooien WHERE toernid=?";
            $query = $this->dbconn->prepare($sql);
            $query->execute([$toernooiid]);
            $recset = $query->fetch(3);
            if( ($recset[0] == 5 OR $recset[0] == 6) AND $recset[1] == 1 AND $recset[2] == 1 )
                $this->pasWedstrijdschemaAan($toernooiid, $recset[0]);
        } catch(PDOException $e) {
            echo $e->getMessage();
        }
    }
    private function pasWedstrijdschemaAan($toernid, $aantalinpoule){
        try {
            $sql = "SELECT wedstrid FROM wedstrijden WHERE toernid=? ORDER BY speelronde";
            $query = $this->dbconn->prepare($sql);
            $query->execute([$toernid]);
            $wedstrijden = array();
            if($aantalinpoule == 5){
                $wedstrijden[0] = array(1, 2);
                $wedstrijden[1] = array(3, 4);
                $wedstrijden[2] = array(5, 1);
                $wedstrijden[3] = array(2, 3);
                $wedstrijden[4] = array(4, 5);
                $wedstrijden[5] = array(1, 3);
                $wedstrijden[6] = array(5, 2);
                $wedstrijden[7] = array(4, 1);
                $wedstrijden[8] = array(3, 5);
                $wedstrijden[9] = array(2, 4);
            } elseif ($aantalinpoule == 6){
                $wedstrijden[0] = array(1, 6);
                $wedstrijden[1] = array(2, 5);
                $wedstrijden[2] = array(3, 4);
                $wedstrijden[3] = array(5, 1);
                $wedstrijden[4] = array(6, 3);
                $wedstrijden[5] = array(4, 2);
                $wedstrijden[6] = array(1, 3);
                $wedstrijden[7] = array(4, 5);
                $wedstrijden[8] = array(2, 6);
                $wedstrijden[9] = array(1, 4);
                $wedstrijden[10] = array(3, 2);
                $wedstrijden[11] = array(5, 6);
                $wedstrijden[12] = array(2, 1);
                $wedstrijden[13] = array(6, 4);
                $wedstrijden[14] = array(3, 5);
            }
            $i = 0;
            while ($recset = $query->fetch(PDO::FETCH_NUM)):
                $this->updateWedstrijd($recset[0], $wedstrijden[$i][0], $wedstrijden[$i][1]);
                $i++;
            endwhile;
        } catch(PDOException $e){
            echo $e->getMessage();
        }
    }
    private function updateWedstrijd($wedstr_id, $teamnr1, $teamnr2) {
        try {
            $sql = "UPDATE wedstrijden SET thuisploeg=?, uitploeg=? WHERE wedstrid=?";
            $query = $this->dbconn->prepare($sql);
            $query->execute([$teamnr1, $teamnr2, $wedstr_id]);
        } catch(PDOException $e){
            echo $e->getMessage();
        }
    }
}
