<?php
include "../../inc/dbconnection.class.php";
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
            $teams = array();
			$teller = 1;
			while ($teller <= $aantalteams):
				$teams[$teller] = $startteamnr;
				$startteamnr++;
				$teller++;
			endwhile;
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
			while ($ronde <= $aantalteams - 1)
			{
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
			}
		} catch(PDOException $e) {
			echo $e->getMessage();
		}
	}
	public function toonWedstrijdschema($toernooiid,$poule,$achtergrond)
    {
        try {
            $sql = "SELECT speelronde, aanvang, eind, wedstrid, thuisploeg, uitploeg, thuisscore, uitscore, veld FROM wedstrijden WHERE toernid=? AND poule=? ORDER BY speelronde, veld";
            $query = $this->dbconn->prepare($sql);
            $query->execute([$toernooiid, $poule]);
            $pouletabel = "<table><thead><tr>";
            $pouletabel .= "<th class='kolomkop'>ronde</th>";
            $pouletabel .= "<th class='kolomkopmidden'>tijd</th>";
            $pouletabel .= "<th class='kolomkop' colspan='3'>wedstrijden (veld)</th>";
            $pouletabel .= "</tr></thead><tbody><tr>";
            $rondeteller = 0;
            while ($recset = $query->fetch(3)):
                if ($rondeteller <> $recset['speelronde']):
                    $pouletabel .= "</tr><tr>";
                    $pouletabel .= "<td style='text-align: right'><b>$recset[0].</b></td>";
                    $pouletabel .= "<td>" . substr($recset[1], 0, 5) . " - " . substr($recset[2], 0, 5) . "</td>";
                    $rondeteller = $recset[0];
                endif;
                $pouletabel .= "<td class=wedstr style='text-align: center'>";
                $pouletabel .= "<a href='#' onclick=\"window.open('uitslinvoer.php?id=$recset[3]&backgr=" . $achtergrond . "&tid=" . $toernooiid . "', 'uitslag invoer', 'width=460,height=250,top=220,left=220'); return false\">$recset[4] - $recset[5]</a>";
                if (isset($recset['thuisscore']) and isset($recset['uitscore']))
                    $pouletabel .= "<br><span style='color: darkblue;'>$recset[6] - $recset[7]</span>";
                else
                    $pouletabel .= " ($recset[8])";
                $pouletabel .= "</td>";
            endwhile;
            $pouletabel .= "</tr></table>";
            $returnstmt = "<h3>Wedstrijdschema poule $poule</h3>";
            $returnstmt .= $pouletabel;
        } catch (PDOException $e) {
            $returnstmt = $e->getMessage();
        }
        return $returnstmt;
    }
	public function displayPoulewedstr($toernooiid,$poule)
	{
		try {
            $sql = "SELECT t1.naam AS thuisnaam, t2.naam AS uitnaam, thuisploeg, uitploeg, thuisscore, uitscore, speelronde, aanvang, eind, wedstrijden.poule, veld, wedstrid
                FROM wedstrijden
                LEFT JOIN ploegen AS t1 ON t1.teamnr = wedstrijden.thuisploeg
                LEFT JOIN ploegen AS t2 ON t2.teamnr = wedstrijden.uitploeg
                WHERE wedstrijden.toernid=? AND wedstrijden.poule=? AND wedstrijden.toernid=t1.toernid AND wedstrijden.toernid=t2.toernid
                ORDER BY speelronde, veld";
            $query = $this->dbconn->prepare($sql);
            $query->execute([$toernooiid, $poule]);
			$pouletabel="<table><thead><tr>";
            $pouletabel.="<th class='kolomkop'>ronde</th>";
            $pouletabel.="<th class='kolomkopmidden'>tijd</th>";
            $pouletabel.="<th class='kolomkopmidden'>wedstrijden (veld)</th>";
            $pouletabel.="</tr></thead><tbody><tr>";
			$rondeteller = 0;
			while($recset = $query->fetch(3)):
				if ($rondeteller <> $recset[6]):
                    if ($rondeteller > 0)
                        $pouletabel.="</tr>".PHP_EOL."<tr>";
                    $pouletabel.="<td class='rijkop'>$recset[6].&nbsp;</td>".PHP_EOL;
                    $pouletabel.="<td>".substr($recset[7],0,5).' - '.substr($recset[8],0,5).".&nbsp;</td>".PHP_EOL;
                    $rondeteller=$recset['speelronde'];
                endif;
				$inhoudcel = "$recset[2] - $recset[3]";
				if (isset($recset[4]) AND isset($recset[5]))
                    $inhoudcel.="<br><span style='color: red; font-size: smaller'>$recset[4] - $recset[5]</span>";
                else
                    $inhoudcel.=" ($recset[10])";
				$pouletabel.="<td class='wedstr'>$inhoudcel</td>".PHP_EOL;
			endwhile;
			//laatste rij afsluiten
			$pouletabel.="</tr>".PHP_EOL;
			$returnstmt="<h2>Poule $poule</h2>".$pouletabel;
        } catch(PDOException $e) {
            $returnstmt = $e->getMessage();
		}
        return $returnstmt;
	}


	public function displayPoulestand($toernooiid,$poule)
	{
		try {
            $sql = "SELECT teamnr, naam FROM ploegen WHERE toernid=? AND poule=? ORDER BY teamnr";
            $query = $this->dbconn->prepare($sql);
            $query->execute([$toernooiid, $poule]);
			$poulstand="<table><thead><tr>";
            $poulstand.="<th></th>";
            $poulstand.="<th class='kolomkop'>team</th>";
            $poulstand.="<th class='kolomkopmidden'>naam</th>";
            $poulstand.="<th class='kolomkopmidden'>p</th>";
            $poulstand.="<th class='kolomkopmidden'>g</th>";
            $poulstand.="<th class='kolomkopmidden'>w</th>";
            $poulstand.="<th class='kolomkopmidden'>g</th>";
            $poulstand.="<th class='kolomkopmidden'>v</th>";
            $poulstand.="<th class='kolomkop' colspan='3'>doelsaldo</th>";
            $poulstand.="</tr></thead><tbody>";
			$rondeteller = 0;
			while($recset = $query->fetch(3))
			{
				$sql2 = "SELECT * FROM wedstrijden WHERE toernid=? AND (thuisploeg=? OR uitploeg=?) ORDER BY ronde";
                $query2 = $this->dbconn->prepare($sql2);
                $query2->execute([$toernooiid, $recset[0], $recset[0]]);
				$punten = 0;
				$gespeeld = 0;
				$winst = 0;
				$gelijk = 0;
				$verlies = 0;
				$voor = 0;
				$tegen = 0;
				while ($standRige = $query2->fetch(PDO::FETCH_ASSOC))
				{
					if ($standRige['thuisscore'] <> '')
					{
						if ($recset[0] == $standRige['thuisploeg'])
						{
							$punten = $punten + $standRige['thuispunten'];
							$voor = $voor + $standRige['thuisscore'];
							$tegen = $tegen + $standRige['uitscore'];
							if ($standRige['thuispunten'] == 1)
							$gelijk++;
							elseif ($standRige['thuispunten'] == 0)
							$verlies++;
							else
							$winst++;
        				}
						else
						{
							$punten = $punten + $standRige['uitpunten'];
							$voor = $voor + $standRige['uitscore'];
							$tegen = $tegen + $standRige['thuisscore'];
							if ($standRige['uitpunten'] == 1)
							$gelijk++;
							elseif ($standRige['uitpunten'] == 0)
							$verlies++;
							else
							$winst++;
        				}
						$gespeeld++;
        			}
				}
				$naam = $recset[1];
				$saldo = $voor - $tegen;
				//zet de data in een array
				$data[] = array('team' => $recset[0],
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
			array_multisort($points, SORT_DESC, $played, SORT_ASC, $doelscore, SORT_DESC, $data);
			$positie = 1;

			//INGEVOEGD
			$k = 0;
			while ($k < count($data))
			{
				if ($data[$k]['punten'] == $data[$k+1]['punten'] AND $data[$k]['gespeeld'] == $data[$k+1]['gespeeld'])
				{
					if ($data[$k+2]['punten'] < $data[$k+1]['punten'])
					{
						//de volgende ploeg heeft niet hetzelfde aantal punten
						$ploeg1 = $data[$k]['team'];
						$ploeg2 = $data[$k+1]['team'];
                        $sql3 = "SELECT thuisploeg, thuisscore, uitscore FROM wedstrijden WHERE toernid=? AND ((thuisploeg=? AND uitploeg=?) OR (thuisploeg=? AND uitploeg=?))";
                        $query3 = $this->dbconn->prepare($sql3);
                        $query3->execute([$toernooiid, $ploeg1, $ploeg2, $ploeg2, $ploeg1]);
						$recset3 = $query3->fetch(3);
						if ($recset3[0] == $ploeg1)
						{
							if ($recset3[1] < $recset3[2])
							{
								//verwissel rangorde
								$dummyteam = $data[$k]['team'];
								$dummynaam = $data[$k]['naam'];
								$dummypunten = $data[$k]['punten'];
								$dummygespeeld = $data[$k]['gespeeld'];
								$dummywinst = $data[$k]['winst'];
								$dummygelijk = $data[$k]['gelijk'];
								$dummyverlies = $data[$k]['verlies'];
								$dummyvoor = $data[$k]['voor'];
								$dummytegen = $data[$k]['tegen'];
								$dummysaldo = $data[$k]['saldo'];
								$data[$k]['team'] = $data[$k+1]['team'];
								$data[$k]['naam'] = $data[$k+1]['naam'];
								$data[$k]['punten'] = $data[$k+1]['punten'];
								$data[$k]['gespeeld'] = $data[$k+1]['gespeeld'];
								$data[$k]['winst'] = $data[$k+1]['winst'];
								$data[$k]['gelijk'] = $data[$k+1]['gelijk'];
								$data[$k]['verlies'] = $data[$k+1]['verlies'];
								$data[$k]['voor'] = $data[$k+1]['voor'];
								$data[$k]['tegen'] = $data[$k+1]['tegen'];
								$data[$k]['saldo'] = $data[$k+1]['saldo'];
								$data[$k+1]['team'] = $dummyteam;
								$data[$k+1]['naam'] = $dummynaam;
								$data[$k+1]['punten'] = $dummypunten;
								$data[$k+1]['gespeeld'] = $dummygespeeld;
								$data[$k+1]['winst'] = $dummywinst;
								$data[$k+1]['gelijk'] = $dummygelijk;
								$data[$k+1]['verlies'] = $dummyverlies;
								$data[$k+1]['voor'] = $dummyvoor;
								$data[$k+1]['tegen'] = $dummytegen;
								$data[$k+1]['saldo'] = $dummysaldo;
							}
						}
						else
						{
							if ($recset3[2] < $recset3[1])
							{
								//verwissel rangorde
								$dummyteam = $data[$k]['team'];
								$dummynaam = $data[$k]['naam'];
								$dummypunten = $data[$k]['punten'];
								$dummygespeeld = $data[$k]['gespeeld'];
								$dummywinst = $data[$k]['winst'];
								$dummygelijk = $data[$k]['gelijk'];
								$dummyverlies = $data[$k]['verlies'];
								$dummyvoor = $data[$k]['voor'];
								$dummytegen = $data[$k]['tegen'];
								$dummysaldo = $data[$k]['saldo'];
								$data[$k]['team'] = $data[$k+1]['team'];
								$data[$k]['naam'] = $data[$k+1]['naam'];
								$data[$k]['punten'] = $data[$k+1]['punten'];
								$data[$k]['gespeeld'] = $data[$k+1]['gespeeld'];
								$data[$k]['winst'] = $data[$k+1]['winst'];
								$data[$k]['gelijk'] = $data[$k+1]['gelijk'];
								$data[$k]['verlies'] = $data[$k+1]['verlies'];
								$data[$k]['voor'] = $data[$k+1]['voor'];
								$data[$k]['tegen'] = $data[$k+1]['tegen'];
								$data[$k]['saldo'] = $data[$k+1]['saldo'];
								$data[$k+1]['team'] = $dummyteam;
								$data[$k+1]['naam'] = $dummynaam;
								$data[$k+1]['punten'] = $dummypunten;
								$data[$k+1]['gespeeld'] = $dummygespeeld;
								$data[$k+1]['winst'] = $dummywinst;
								$data[$k+1]['gelijk'] = $dummygelijk;
								$data[$k+1]['verlies'] = $dummyverlies;
								$data[$k+1]['voor'] = $dummyvoor;
								$data[$k+1]['tegen'] = $dummytegen;
								$data[$k+1]['saldo'] = $dummysaldo;
							}
						}
						$k = $k + 2;
					}
					else
					{
						//de volgende ploeg heeft wel hetzelfde aantal punten
						$k = $k + 3;
					}
				}
				else
				{
					$k++;
				}
			}
			foreach ($data as $v1)
			{
				$poulstand.="<tr>";
				$poulstand.="<td class='standright' style='width: 20px;'><b>$positie</b></td>";
				foreach ($v1 as $v2 => $value)
				{
					$celinh='';
					if ($v2 == 'naam')
						$class="standleft";
					else
						$class="standright";
					if ($v2 == 'voor')
						$celinh="+";
					elseif ($v2 == 'tegen')
						$celinh="-";
					elseif ($v2 == 'punten')
						$celinh="<b>";
					$celinh.=$value;
					if ($v2 == 'punten')
						$celinh.="</b>";
					$poulstand.="<td class='".$class."'>".$celinh."</td>";
    			}
    			$poulstand.="</tr>";
				$positie++;
			}
			$poulstand.="</table>";
			$kop="<h3>Stand poule $poule</h3>";
			$returnstmt=$kop.$poulstand;
		} catch(PDOException $e) {
            $returnstmt = $e->getMessage();
		}
        return $returnstmt;
	}
	public function getPouleploegen($toernooiid, $poule)
	{
		try {
            $sql = "SELECT teamnr, naam FROM ploegen WHERE toernid=? AND poule=? ORDER BY teamnr";
            $query = $this->dbconn->prepare($sql);
            $query->execute([$toernooiid, $poule]);
			$returnstmt = "<table style='border: none'>";
            $returnstmt .= "<tr><td class=zonder><a href=\"pouleoverzicht.php?toernid=$toernooiid&poule=$poule\" target=\"poule A\"><b>poule $poule</b></a></td></tr>";
			while($recset = $query->fetch(3)):
                $returnstmt.="<tr>";
                $returnstmt.="<td>&nbsp;<a href=\"#\" title=\"teamoverzicht\" onclick=\"window.open('../teamoverzicht.php?toernid=$toernooiid&team=$recset[0]', 'team', 'width=710,height=600,top=220,left=220'); return false\">$recset[0]</a>&nbsp;$recset[1]&nbsp;</td>";
                $returnstmt.="</tr>".PHP_EOL;
			endwhile;
            $returnstmt.="</table>";
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
			$returnstmt = "<td style='vertical-align: top;' class='met'>";
            $returnstmt .= "<b><i>Poule $poule</i></b><br>";
			while($recset = $query->fetch(PDO::FETCH_ASSOC))
			{
                $returnstmt .= "$recset[0] = $recset[1]<br>";
			}
            $returnstmt .= "</td>";
		} catch(PDOException $e) {
            $returnstmt = $e->getMessage();
		}
        return $returnstmt;
	}
	public function zoekAantalInPoule($toernooiid,$poule)
	{
		try {
            $sql = "SELECT COUNT(*) FROM ploegen WHERE toernid=? AND poule=?";
            $query = $this->dbconn->prepare($sql);
            $query->execute([$toernooiid,$poule]);
			$recset = $query->fetch(3);
			$returnstmt = $recset[0];
		} catch(PDOException $e) {
            $returnstmt = $e->getMessage();
		}
        return $returnstmt;
	}
	public function zoekMinimaalTeamnr($toernooiid,$poule)
	{
		try {
            $sql = "SELECT MIN(teamnr) FROM ploegen WHERE toernid=? AND poule=?";
            $query = $this->dbconn->prepare($sql);
            $query->execute([$toernooiid,$poule]);
			$recset = $query->fetch(3);
			$returnstmt = $recset[0];
		} catch(PDOException $e) {
            $returnstmt = $e->getMessage();
		}
        return $returnstmt;
	}
	public function toonTeamnamenForm($toernooiid,$poule,$aantal,$minimumnr)
	{
		try {
            $sql = "SELECT teamnr, naam, stamklas FROM ploegen WHERE toernid=? AND poule=?";
            $query = $this->dbconn->prepare($sql);
            $query->execute([$toernooiid,$poule]);
			$returnstmt="<form method='post' action='teamnvastleggen.php?tid=$toernooiid&aantal=$aantal&minimum=$minimumnr'>".PHP_EOL;
			$returnstmt.="<table><tr><th></th><th>naam</th><th>stamklas</th></tr>".PHP_EOL;
			$i=1;
			while($recset = $query->fetch(3)):
				$returnstmt.="<tr><td style='text-align: right; height: 40px;' class='zonder'>$recset[0]</td>".PHP_EOL;
				$returnstmt.="<td class='zonder'><input type='text' size='10' name='naam$i' value='$recset[1]'></td>".PHP_EOL;
				$returnstmt.="<td class='zonder'><input type='text' size='10' name='klas$i' value='$recset[2]'></td></tr>".PHP_EOL;
				$i++;
			endwhile;
			$returnstmt.="<tr><td colspan='3' style='text-align: center' class='zonder'><input type='submit' value='voer in'></td></tr></table></form>";
		} catch(PDOException $e) {
            $returnstmt = $e->getMessage();
		}
        return $returnstmt;
	}
	public function updateTeamnaam($teamnr,$toernooiid,$naam,$klas)
	{
		try {
            $sql = "UPDATE ploegen SET naam=?, stamklas=? WHERE toernid=? AND teamnr=?";
            $query = $this->dbconn->prepare($sql);
            $query->execute([$naam,$klas,$toernooiid,$teamnr]);
		} catch(PDOException $e) {
			echo $e->getMessage();
		}
	}
}
