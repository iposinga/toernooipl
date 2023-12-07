<?php
include "../inc/dbconnection.class.php";
include "../inc/functions.php";
//include "../inc/query.class.php";
//require_once($_SERVER['DOCUMENT_ROOT'].'/app_oud/includes/query.class.php');
require_once "veld.class.php";
include "../inc/ploeg.class.php";
require_once "poule.class.php";
require_once "wedstrijd.class.php";

class Toernooi
{
    private $dbconn;
    public function __construct()
    {
        $this->dbconn = new Dbconnection();
    }
	public function zoekuserToernooien(): string
	{
		try {
            $sql = "SELECT toernid, naam, velden, poules FROM toernooien, toernooiusers WHERE toernooi=toernid AND user=?";
            $query = $this->dbconn->prepare($sql);
            $query->execute([$_SESSION['user_session']]);
		    $returnstmt="<table style='border: none'>";
		    $teller=1;
		    while($recset = $query->fetch(3)):
                $returnstmt.="<tr><td style='text-align: right;' class='zonder'>$teller</td>".PHP_EOL;
                $returnstmt.="<td style='text-align: left;' class='zonder'><a style='text-decoration: none;' href='wedstrschema.php?toernooiid=$recset[0]'>$recset[1]</a></td>".PHP_EOL;
                $returnstmt.="<td style='width: 20px;' class='zonder'><a href='verwijderpopup.php?toernooiid=$recset[0]' onclick=\"popupwindow('verwijderpopup.php?toernooiid=$recset[0]', 'verwijder', '640', '260'); return false\" title='verwijder'><img src='../images/Trash.png' alt=''></a></td>".PHP_EOL;
                $returnstmt.="<td style='width: 20px;' class='zonder'><a href='edittoernooi.php?toernooiid=$recset[0]' title='kopieer en pas aan'><img src='../images/Write.png' alt=''></a></td>".PHP_EOL;
                $returnstmt.="<td style='width: 20px;' class='zonder'><a href='koppelusers.php?toernooiid=$recset[0]' onclick=\"popupwindow('koppelusers.php?toernooiid=$recset[0]', 'koppel', '520', '770'); return false\" title='koppel gebruikers'><img src= '../images/User.png' alt=''></a></td>".PHP_EOL;
                $returnstmt.="<td style='width: 20px;' class='zonder'><a href='nieuwachtergrond.php?toernooiid=$recset[0]' onclick=\"window.open('nieuwachtergrond.php?toernid=$recset[0]', 'width=400,height=400,top=220,left=220'); return false\" title='achtergrond'><img src='../images/Picture.png' alt=''></a></td>".PHP_EOL;
                $returnstmt.="<td style='width: 20px;' class=zonder><a href='#' onclick=\"alert('Om te printen in het volgende scherm, kies \'ctrl + p\' en kies bij de voorkeursinstellingen: voor enkelzijdig A4, liggend (landscape)'); window.open('../wedstrformulier.php?toernooiid=$recset[0]&velden=$recset[2]', 'videowall', 'width=1020,height=680,top=0,left=0'); return false\" title='wedstrijdformulieren'><img src= '../images/Document.png' alt=''></a></td>".PHP_EOL;
                $videowallpage = "../videowall.php?toernooiid=$recset[0]&titel=$recset[1]&poules=$recset[3]";
                $returnstmt.="<td class=zonder><a href='$videowallpage' title='toon op videowall' onclick=\"popupwindow('$videowallpage', 'videowall', '3850','1970'); return false\"><img src='../images/Calendar.png' alt=''></a></td>".PHP_EOL;
                $returnstmt.="<td class=zonder><a href='#' title='print schema' onclick=\"popupwindow('../printwedstrschema.php?toernooiid=$recset[0]', 'print', '710', '980'); return false\"><img src='../images/Printer.png' alt=''></a></td>".PHP_EOL;
                $returnstmt.="</tr>".PHP_EOL;
                $teller++;
            endwhile;
            $returnstmt.="</table>";
        } catch(PDOException $e) {
            $returnstmt = $e->getMessage();
        }
        return $returnstmt;
	}
	public function getToernooiparameters($toernooiid): array
	{
		try {
            $sql = "SELECT naam, achtergrond, poules, velden, datum, gedrag, winnaar FROM toernooien WHERE toernid=?";
            $query = $this->dbconn->prepare($sql);
            $query ->execute([$toernooiid]);
			$recset = $query->fetch(3);
			$returnarray=array();
			$returnarray['naam']=$recset[0];
			$returnarray['aantalpoules']=$recset[2];
			$returnarray['aantalvelden']=$recset[3];
			$returnarray['winnaar']=$recset[6];
			$returnarray['gedrag']=$recset[5];
			//$displaydatum=strftime("%a %e %b '%y" , strtotime($recset['datum']));
            $displaydatum = datumFormatting(strtotime($recset[4]), 6);
			$returnarray['datum']=$displaydatum;
			if ($recset[2] <> '')
				$returnarray['achtergrond'] = $recset[1];
			else
				$returnarray['achtergrond'] = "background2.jpg";
		} catch(PDOException $e) {
            $returnarray[0] = $e->getMessage();
		}
        return $returnarray;
	}
	public function maakToernooi($naam,$datum,$aantteams,$aantpoules,$aantvelden,$aanvang,$duur,$typecomp)
	{
		try {
			//$displdate = strftime('%Y-%m-%d' , strtotime($datum));
            $displdate = datumFormatting(strtotime($datum), 7);
            $sql = "INSERT INTO toernooien (naam, datum, teams, poules, velden, aanvang, duur, comp) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
            $query = $this->dbconn->prepare($sql);
            $query->execute([$naam, $displdate, $aantteams, $aantpoules, $aantvelden, $aanvang, $duur, $typecomp]);
			$toernid = $this->dbconn->lastInsertId();
			//toernooi en user aan elkaar koppelen
			$this->insertToernooiMetUser($toernid);
			$teamsperpoule = intval($aantteams / $aantpoules);
			//$typecomp = $_POST['comp'];
			//maak velden aan
			$teller = 1;
			while ($teller <= $aantvelden):
				$makefield = new Veld();
				$makefield->maakVeld($toernid, $teller, 'F018');
				$teller++;
			endwhile;
			//maak ploegen aan en verdeel ze over poules
			$teamteller = 1;
			$pouleteller = 1;
			$restteller = $aantteams % $aantpoules;
			while ($pouleteller <= $aantpoules):
				$poule = chr($pouleteller+64);
  				$teamsperpouleteller = 1;
  				while ($teamsperpouleteller <= $teamsperpoule)
  				{
	  				$newploeg = new Ploeg();
	  				$newploeg->maakPloeg($toernid, $poule, $teamteller);
	  				$teamteller++;
	  				$teamsperpouleteller++;
				}
				if ($restteller > 0)
				{
					$newrestploeg = new Ploeg();
	  				$newrestploeg->maakPloeg($toernid, $poule, $teamteller);
	 			 	$teamteller++;
	 			 	$restteller--;
    			}
				$pouleteller++;
			endwhile;
			//nu de poulewedstrijden aanmaken
			$this->maakWedstrschemafase1($toernid,$typecomp);
			//nu de tijden en de velden toevoegen
			$this->maakWedstrschemafase2($toernid,$aanvang,$duur,$aantvelden);
			$returnstmt = $toernid;
		} catch(PDOException $e) {
            $returnstmt = $e->getMessage();
		}
        return $returnstmt;
	}
	public function maakWedstrschemafase1($toernooiid,$comp)
	{
		try {
            $sql = "SELECT COUNT(poule), MIN(teamnr), poule FROM ploegen WHERE toernid=? GROUP BY poule";
            $query = $this->dbconn->prepare($sql);
            $query->execute([$toernooiid]);
			//per poule wordt er een schema gegenereerd
			$newpoule = new Poule();
			while ($recset = $query->fetch(3)):
				$newpoule->maaknieuwePoule($recset[0], $recset[1], $toernooiid, $recset[2], $comp);
			endwhile;
		} catch(PDOException $e) {
			echo $e->getMessage();
		}
	}
    public function maakWedstrschemafase2($toernooiid,$aanvang,$duur,$aantalvelden)
	{
		try {
            $sql = "SELECT wedstrid FROM wedstrijden WHERE toernid=? ORDER BY ronde, poule";
            $query = $this->dbconn->prepare($sql);
            $query->execute([$toernooiid]);
			$veldteller = 1;
			//$tijdbijhouder = strftime('%H:%M', strtotime($aanvang));
            $tijdbijhouder = datumFormatting(strtotime($aanvang), 12);
			$speelronde = 1;
			while ($recset = $query->fetch(3)):
				if ($veldteller > $aantalvelden):
					$veldteller = 1;
					//$tijdbijhouder = strftime('%H:%M', strtotime($tijdbijhouder) + $duur * 60);
                    $tijdbijhouder = datumFormatting(strtotime($tijdbijhouder) + $duur * 60, 12);
					$speelronde++;
  				endif;
  				$invaanvtijd = $tijdbijhouder;
  				$inveindtijd = datumFormatting(strtotime($tijdbijhouder) + $duur * 60, 12);
  				$wedstrupdate = new Wedstrijd();
  				$wedstrupdate->updateWedstrVeldTijd($recset[0],$veldteller,$invaanvtijd,$inveindtijd,$speelronde);
  				$veldteller++;
			endwhile;
		} catch(PDOException $e) {
			echo $e->getMessage();
		}
	}
	public function aantalVelden($toernooiid)
	{
		try {
            $sql = "SELECT velden FROM toernooien WHERE toernid=?";
            $query = $this->dbconn->prepare($sql);
            $query->execute([$toernooiid]);
			$recset = $query->fetch(3);
			$returnstmt = $recset[0];
		} catch(PDOException $e) {
            $returnstmt = $e->getMessage();
		}
        return $returnstmt;
	}
	public function aantalRonden($toernooiid)
	{
		try {
            $sql = "SELECT MAX(speelronde) FROM wedstrijden WHERE toernid=?";
            $query = $this->dbconn->prepare($sql);
            $query->execute([$toernooiid]);
			$recset = $query->fetch(3);
			$returnstmt = $recset[0];
		} catch(PDOException $e) {
            $returnstmt = $e->getMessage();
		}
        return $returnstmt;
	}
	public function displayWedstrschema($toernooiid)
	{
		try {
            /*$sql = "SELECT thuisploeg, uitploeg, speelronde, aanvang, eind FROM wedstrijden
            LEFT JOIN ploegen AS t1 ON t1.teamnr = wedstrijden.thuisploeg
            LEFT JOIN ploegen AS t2 ON t2.teamnr = wedstrijden.uitploeg
            WHERE wedstrijden.toernid=? AND wedstrijden.poule<>'Z' AND wedstrijden.toernid=t1.toernid AND wedstrijden.toernid=t2.toernid
            ORDER BY speelronde, veld";*/
            $sql = "SELECT thuisploeg, uitploeg, speelronde, aanvang, eind FROM wedstrijden WHERE toernid=? AND poule<>'Z'ORDER BY speelronde, veld";
            $query = $this->dbconn->prepare($sql);
            $query->execute([$toernooiid]);
            $returnstmt = "<h2>Wedstrijdschema</h2><table>";
            $returnstmt .= "<tr><td class='kolomkop'>ronde</td>";
            $returnstmt .= "<td class='kolomkopmidden'>tijd</td>";
			$veldteller = 1;
			while ($veldteller <= $this->aantalVelden($toernooiid)):
                $returnstmt .= "<td class='kolomkopmidden'>veld $veldteller</td>";
				$veldteller++;
			endwhile;
            $returnstmt .= "</tr>";
			$rondeteller = 0;
			while ($recset = $query->fetch(3)):
				if ($rondeteller <> $recset[2])
				{
					if ($rondeteller > 0)
                        $returnstmt .= "</tr>";
                    $returnstmt .= "<tr>";
                    $returnstmt .= "<td class='rijkop'>$recset[2] </td>";
                    $returnstmt .= "<td class='wedstr'><b>".substr($recset[3],0,5)." - ".substr($recset[4],0,5)."</b></td>";
					$rondeteller++;
    			}
                $returnstmt .= "<td class='wedstr'>$recset[0] - $recset[1]</td>";
			endwhile;
			//laatste rij afsluiten
            $returnstmt .= "</tr>";
		} catch(PDOException $e) {
            $returnstmt = $e->getMessage();
		}
        return $returnstmt;
	}
	public function displayKlassen($toernooiid)
	{
		try {
            $sql = "SELECT stamklas FROM ploegen WHERE toernid=? GROUP BY stamklas ORDER BY stamklas";
            $query = $this->dbconn->prepare($sql);
            $query->execute([$toernooiid]);
			$returnstmt='<h2>Klassenstand</h2>';
			$returnstmt.="<table><tr><th></th>";
            $returnstmt.="<th>klas</th>";
            $returnstmt.="<th>gem</th>";
            $returnstmt.="<th>p</th>";
            $returnstmt.="<th>g</th>";
            $returnstmt.="<th>w</th>";
            $returnstmt.="<th>g</th>";
            $returnstmt.="<th>v</th>";
            $returnstmt.="<th colspan=3>doelsaldo</th></tr>";
			while ($recset = $query->fetch(3))
			{
				$klas = $recset[0];
                $sql2 = "SELECT t1.stamklas, t2.stamklas, thuisploeg, uitploeg, thuispunten, thuisscore, uitpunten, uitscore, speelronde, aanvang, eind, wedstrijden.poule, veld, wedstrid
                        FROM wedstrijden
                        LEFT JOIN ploegen AS t1 ON t1.teamnr = wedstrijden.thuisploeg
                        LEFT JOIN ploegen AS t2 ON t2.teamnr = wedstrijden.uitploeg
                        WHERE wedstrijden.toernid=:toernooiid AND (t1.stamklas=:klas OR t2.stamklas=:klas) AND wedstrijden.toernid=t1.toernid AND wedstrijden.toernid=t2.toernid";
				$query2 = $this->dbconn->prepare($sql2);
                $query2->execute([$toernooiid, $klas]);
    			$punten = 0;
				$gespeeld = 0;
				$winst = 0;
				$gelijk = 0;
				$verlies = 0;
				$voor = 0;
				$tegen = 0;
				while ($recset2 = $query2->fetch(3)):
					$teller = $gespeeld + 1;
					if ($recset2[5] <> '')
					{
						if ($klas == $recset2[0])
						{
							$punten = $punten + $recset2[4];
							$voor = $voor + $recset2[5];
							$tegen = $tegen + $recset2[7];
							if ($recset2[4] == 1)
								$gelijk++;
							elseif ($recset2[4] == 0)
								$verlies++;
							else
								$winst++;
        				} else {
							$punten = $punten + $recset2[6];
							$voor = $voor + $recset2[7];
							$tegen = $tegen + $recset2[5];
							if ($recset2[6] == 1)
								$gelijk++;
							elseif ($recset2[6] == 0)
								$verlies++;
							else
								$winst++;
        				}
						$gespeeld++;
        			}
    			endwhile;
				$saldo = $voor - $tegen;
				//zet de data in een array
				$gemiddelde = round($punten / $gespeeld, 3);
				$data[] = array('klas' => $klas, 'gem' => $gemiddelde, 'punten' => $punten, 'gespeeld' => $gespeeld, 'winst' => $winst, 'gelijk' => $gelijk, 'verlies' => $verlies, 'voor' => $voor, 'tegen' => $tegen, 'saldo' => $saldo);
			}
			// Obtain a list of columns
			foreach ($data as $key => $row)
			{
				$gem[$key] = $row['gem'];
			}
			// Sort the data with volume descending, edition ascending
			// Add $data as the last parameter, to sort by the common key
			array_multisort($gem, SORT_DESC, $data);
			//array_multisort($points, SORT_DESC, $played, SORT_ASC, $doelscore, SORT_DESC, $data);
			$positie = 1;
			foreach ($data as $v1)
			{
				$class="standright";
				$returnstmt.= "<tr><td class='$class'><b>$positie.</b></td>\n";
    			foreach ($v1 as $v2 => $value)
    			{
					if ($v2 == 'klas')
						$class="standleft";
					else
						$class="standright";
					$returnstmt.= "<td class='$class'>";
					if ($v2 == 'voor')
						$returnstmt.= "+";
					elseif ($v2 == 'tegen')
						$returnstmt.= "-";
					elseif ($v2 == 'gem')
						$returnstmt.= "<b>";
					$returnstmt.= $value;
					if ($v2 == 'punten')
						$returnstmt.= "</b>";
					$returnstmt.= "</td>\n";
    			}
				$returnstmt.= "</tr>";
				$positie++;
			}
			$returnstmt.= "</table>";
		} catch(PDOException $e) {
            $returnstmt = $e->getMessage();
		}
        return $returnstmt;
	}
	public function displayFinaleschema($toernooiid)
	{
		try {
			$returnstmt = '<h2>Finalewedstrijden</h2>';
            $returnstmt .= "<table><tr><th>ronde</th><th>tijd</th>";
			$veldteller = 1;
			$rondenenaantal = $this->aantalRonden($toernooiid);
			while ($veldteller <= $this->aantalVelden($toernooiid)):
                $returnstmt .= "<th>veld $veldteller</th>";
				$veldteller++;
			endwhile;
            $sql = "SELECT finaleronde, aanvang, eind, thuisploeg, uitploeg, fin_wedstrnaam, thuisploegnr, uitploegnr, fin_thuisscore, fin_uitscore FROM finalewedstrijden WHERE toernooi_id=? ORDER BY finaleronde, veld";
            $query = $this->dbconn->prepare($sql);
            $query->execute([$toernooiid]);
			$finalerondeteller = 0;
			while ($recset = $query->fetch(3)) {
		//als de eerste wedstrijd van een nieuwe speelronde zich aandient, moet er weer een nieuwe rij komen met ronde en tijden
				if ($finalerondeteller <> $recset[0]) {
					$speelrondedispl = $rondenenaantal + $recset[0];
                    $returnstmt .= "</tr>\n<tr><td style='text-align: right;'><b>$speelrondedispl.</b></td><td>".substr($recset[1],0,5)." - ".substr($recset[2],0,5)."</td>";
					$finalerondeteller++;
    			}
                $returnstmt .= "<td class='wedstr' style='text-align: center;'>";
				if ($recset[3] <> '')
                    $returnstmt .= "$recset[5]<br>$recset[3] - $recset[4]";
				if ($recset[6] > 0 OR $recset[7] > 0)
                    $returnstmt .= "<br>$recset[6] - $recset[7]";
				if (isset($recset[8]) AND isset($recset[9]))
                    $returnstmt .= "<br><span style='font-size: smaller; color: red;'>$recset[8] - $recset[9]</span>";
                $returnstmt .= "</td>\n";
			}
            $returnstmt .= "</tr></table>";
		} catch(PDOException $e) {
            $returnstmt = $e->getMessage();
		}
        return $returnstmt;
	}
	public function getBackground($toernooiid)
	{
		try {
            $sql = "SELECT achtergrond FROM toernooien WHERE toernid=?";
            $query = $this->dbconn->prepare($sql);
            $query->execute([$toernooiid]);
			$recset = $query->fetch(3);
			if ($recset[0] <> '')
				$returnstmt = $recset[0];
			else
                $returnstmt = "background2.jpg";
		} catch(PDOException $e) {
            $returnstmt = $e->getMessage();
		}
        return 	$returnstmt;
	}
	public function getTitelmetDatum($toernooiid)
	{
		try {
            $sql= "SELECT naam, datum FROM toernooien WHERE toernid=?";
            $query = $this->dbconn->prepare($sql);
            $query->execute([$toernooiid]);
			$recset = $query->fetch(3);
            $displdatum = datumFormatting($recset[1], 6);
            //$displdatum = strftime("%a %e %b '%y",strtotime($recset[1]));
			$returnstmt = "<h3>$recset[0] op ".$displdatum."</h3>";
		} catch(PDOException $e) {
            $returnstmt = $e->getMessage();
		}
        return $returnstmt;
	}
	public function getPoules($toernooiid)
	{
		try {
            $sql = "SELECT poules FROM toernooien WHERE toernid=?";
            $query = $this->dbconn->prepare($sql);
            $query->execute([$toernooiid]);
			$recset = $query->fetch(3);
			$returnstmt = PHP_EOL."<table style='border: none;'><tr>".PHP_EOL;
			$pouleteller=1;
			while ($pouleteller <= $recset[0]):
                $returnstmt .= "<td style='vertical-align: top;' class=zonder>";
			    $zoekpoule = chr($pouleteller+64);
			    // hier de tabel van een poule
			    $pouleploegen = new Poule();
			    $ploegen = $pouleploegen->getPouleploegen($toernooiid,$zoekpoule);
                $returnstmt .= $ploegen;
			    //einde tabel van een poule
                $returnstmt .= "</td>".PHP_EOL;
				$pouleteller++;
			endwhile;
            $returnstmt .= "</tr>".PHP_EOL."</table>".PHP_EOL;
		} catch(PDOException $e) {
            $returnstmt = $e->getMessage();
		}
        return $returnstmt;
	}
	public function toonWedstrijdschema($toernooiid,$achtergrond,$teamnamen)
	{
		try {
            $sql = "SELECT speelronde, aanvang, eind, thuisploeg, uitploeg, thuisscore, uitscore, wedstrid  FROM wedstrijden WHERE toernid=? AND poule <> 'Z' ORDER BY speelronde, veld";
            $query = $this->dbconn->prepare($sql);
            $query->execute([$toernooiid]);
			$rondeteller = 0;
			$returnstmt = PHP_EOL."<table style='border: none;' class='center'><tr>".PHP_EOL;
            $returnstmt .= "<tr><th>ronde</th>";
            $returnstmt .= "<th>tijd</th>";
			$aantalfields = $this->aantalVelden($toernooiid);
			$veldteller = 1;
			while ($veldteller <= $aantalfields){
                $returnstmt .= "<th>veld ".$veldteller."</th>";
				$veldteller++;
			}
            $returnstmt .= "</tr><tr>".PHP_EOL;
			while ($recset = $query->fetch(3)){
			    if ($rondeteller <> $recset[0]):
					$teamsinronde = array();
                    $returnstmt .= "</tr><tr>";
                    $returnstmt .= "<td style='text-align: right;'><b>$recset[0].</b></td>";
                    $returnstmt .= "<td>".substr($recset[1],0,5)." - ".substr($recset[2],0,5)."</td>";
					$rondeteller++;
    			endif;
				if(in_array($recset[3], $teamsinronde) OR in_array($recset[4], $teamsinronde))
                    $returnstmt .= "<td style='background-color: red; text-align: center;' class='wedstr'>";
				else
                    $returnstmt .= "<td id='wedstr_$recset[7]' class='wedstr' style='text-align: center'>";
				array_push($teamsinronde, $recset[3], $recset[4]);
				if($teamnamen==1){
					$ploeg=new Ploeg();
					$thuisteamnaam=$ploeg->getPloegnaam($toernooiid,$recset[3]);
					$uitteamnaam=$ploeg->getPloegnaam($toernooiid,$recset[4]);
                    $returnstmt .= "<a href='#' title='$recset[3] $recset[5] - $recset[6] $recset[4]' onclick=\"voerUitslagIn($recset[7], $toernooiid); return false\">$thuisteamnaam - $uitteamnaam</a>";
				} else
                    $returnstmt .= "<a href='#' title='$recset[3] $recset[5] - $recset[6] $recset[4]' onclick=\"voerUitslagIn($recset[7], $toernooiid); return false;\">$recset[3] - $recset[4]</a>";
				if (isset($recset[5]) AND isset($recset[6]))
                    $returnstmt .= "&nbsp;<span style='color: green;'>&#10003;</span>";
                $returnstmt .= "</td>";
			}
            $returnstmt .= "</tr>".PHP_EOL;
			$colsp = $aantalfields + 2;
			if($teamnamen==1)
                $returnstmt .= "<tr><td style='text-align: center' colspan='$colsp'><a href='wedstrschemaswap_teamnamen.php?toernooiid=$toernooiid'>wedstrijden verplaatsen</a></td></tr>";
			else
                $returnstmt .= "<tr><td style='text-align: center' colspan='$colsp'><a href='wedstrschemaswap.php?toernooiid=$toernooiid'>wedstrijden verplaatsen</a></td></tr>";
            $returnstmt .= "</table>".PHP_EOL;
		} catch(PDOException $e) {
            $returnstmt = $e->getMessage();
		}
        return 	$returnstmt;
	}
	public function toonWedstrijdschemaSwap($toernooiid,$teamnamen)
	{
		try {
            $sql = "SELECT speelronde, aanvang, eind, thuisploeg, uitploeg, wedstrid, thuisscore, uitscore FROM wedstrijden WHERE toernid=? AND poule <> 'Z' ORDER BY speelronde, veld";
            $query = $this->dbconn->prepare($sql);
            $query->execute([$toernooiid]);
			$rondeteller = 0;
			$returnstmt = "<form name='swapform' method='post' action='wedstrschemaswap.php?toernooiid=$toernooiid'>".PHP_EOL;
            $returnstmt .= "<table class='center'>".PHP_EOL;
            $returnstmt .= "<tr><th>ronde</th>";
            $returnstmt .= "<th>tijd</th>";
			$aantalfields = $this->aantalVelden($toernooiid);
			$veldteller = 1;
			while ($veldteller <= $aantalfields):
                $returnstmt .= "<th>veld $veldteller</th>";
				$veldteller++;
			endwhile;
            $returnstmt .= "</tr><tr>".PHP_EOL;
			while ($recset = $query->fetch(3)){
			    if ($rondeteller <> $recset[0]){
					$teamsinronde = array();
                    $returnstmt .= "</tr><tr><td style='text-align: right'><b>$recset[0].</b></td>";
                    $returnstmt .= "<td>".substr($recset[1],0,5)." - ".substr($recset[2],0,5)."</td>";
					$rondeteller++;
    			}
				if(in_array($recset[3], $teamsinronde) OR in_array($recset[4], $teamsinronde))
                    $returnstmt .= "<td class='wedstr' style='text-align: center; background-color: red;'>";
				else {
                    $returnstmt .= "<td class='wedstr' style='text-align: center'>";
				}
				array_push($teamsinronde, $recset[3], $recset[4]);
				if($teamnamen == 1){
					$ploeg=new Ploeg();
					$thuisteamnaam=$ploeg->getPloegnaam($toernooiid,$recset[3]);
					$uitteamnaam=$ploeg->getPloegnaam($toernooiid,$recset[4]);
                    $returnstmt .= $thuisteamnaam." - ".$uitteamnaam."&nbsp;<input type='checkbox' name='wedstrtoswap[]' value='$recset[5]'>";
				}
				else
                    $returnstmt .= "$recset[3] - $recset[4]&nbsp;<input type='checkbox' name='wedstrtoswap[]' value='$recset[5]'>";
				if (isset($recset[6]) AND isset($recset[7]))
                    $returnstmt .= "&nbsp;<span style='color: green;'>&#10003;</span>";
                $returnstmt .= "</td>";
			}
            $returnstmt .= "</tr>".PHP_EOL;
			$colsp = $aantalfields + 2;
            $returnstmt .= "<input type='hidden' name='swapsubmit' value='1'>".PHP_EOL;
			if($teamnamen==1)
                $returnstmt .= "<tr><td style='text-align: center' colspan='$colsp'><input type='submit' value='verplaats'> of <a href='wedstrschema_teamnamen.php?toernooiid=$toernooiid'>terug naar wedstrijdschema</a> of <a href='wedstrschemaswap.php?toernooiid=$toernooiid'>verplaatsschema met teamnummers</a></td></tr></table></form>";
			else
                $returnstmt .= "<tr><td style='text-align: center' colspan='$colsp'><input type='submit' value='verplaats'> of <a href='wedstrschema.php?toernooiid=$toernooiid'>terug naar wedstrijdschema</a> of <a href='wedstrschemaswap_teamnamen.php?toernooiid=$toernooiid'>verplaatsschema met teamnamen</a></td></tr></table></form>";
		} catch(PDOException $e) {
            $returnstmt = $e->getMessage();
		}
        return 	$returnstmt;
	}
	public function printPoules($toernooiid)
	{
		try {
            $sql = "SELECT poules FROM toernooien WHERE toernid=?";
            $query = $this->dbconn->prepare($sql);
            $query->execute([$toernooiid]);
			$recset = $query->fetch(3);
			$aantalpoules = $recset[0];
			$returnstmt = "<table class=tableprint><tr>";
			$pouleteller=1;
			if ($aantalpoules > 6)
			{
				if ($aantalpoules % 2 == 0)
					$poulesrij1 = $aantalpoules / 2;
				else
					$poulesrij1 = intval($aantalpoules / 2) + 1;
			}
			else
				$poulesrij1 = $aantalpoules;
			//echo $poulesrij1;
			while ($pouleteller <= $aantalpoules AND $pouleteller <= $poulesrij1)
			{
				$zoekpoule = chr($pouleteller+64);
				$pouletje = new Poule();
                $returnstmt .= $pouletje->printPoulecel($toernooiid, $zoekpoule);
			    $pouleteller++;
			}
			if ( $aantalpoules > 6)
			{
			    //nu een nieuwe regel in de hoofdtabel aanmaken
                $returnstmt .= "</tr>".PHP_EOL."<tr>";
			    $celteller = 1;
			    while ($pouleteller <= $aantalpoules)
			    {
					$zoekpoule = chr($pouleteller+64);
					$poultje = new Poule();
                    $returnstmt .= $poultje->printPoulecel($toernooiid, $zoekpoule);
					$pouleteller++;
					$celteller++;
			    }
			    	//nog nog lege cellen toevoegen indien nodig
			    while ($celteller <= $poulesrij1)
			    {
                    $returnstmt .= "<td>".PHP_EOL."</td>".PHP_EOL;
				    $celteller++;
			    }
			}
            $returnstmt .= "</tr>".PHP_EOL;
            $returnstmt .= "</table>";
		} catch(PDOException $e) {
            $returnstmt = $e->getMessage();
		}
        return $returnstmt;
	}
	public function zoekPlekVeld($toernooiid, $veldnr)
	{
		try {
            $sql = "SELECT plek FROM velden WHERE toernooi_id=? AND veld=?";
            $query = $this->dbconn->prepare($sql);
            $query->execute([$toernooiid, $veldnr]);
			$recset = $query->fetch(3);
			$returnstmt = $recset[0];
		} catch(PDOException $e) {
            $returnstmt = $e->getMessage();
		}
        return $returnstmt;
	}
	public function telVeldenPlekVeld($toernooiid, $veldnr)
	{
		try {
			$plekveld = $this->zoekPlekVeld($toernooiid, $veldnr);
            $sql = "SELECT COUNT(*) AS aantal FROM velden WHERE toernooi_id=? AND plek=?";
            $query = $this->dbconn->prepare($sql);
            $query->execute([$toernooiid, $plekveld]);
			$recset = $query->fetch(3);
            $returnstmt = $recset[0];
		} catch(PDOException $e) {
            $returnstmt = $e->getMessage();
		}
        return $returnstmt;
	}
	public function zoekHoogstespeelronde($toernooiid)
	{
		try {
            $sql = "SELECT MAX(speelronde) FROM wedstrijden WHERE toernid=? AND poule <> 'Z' GROUP BY speelronde";
            $query = $this->dbconn->prepare($sql);
            $query->execute([$toernooiid]);
			$recset = $query->fetch(3);
            $returnstmt = $recset[0];
		} catch(PDOException $e) {
            $returnstmt = $e->getMessage();
		}
        return $returnstmt;
	}
	public function printWedstrschema($toernooiid)
	{
		try {
			$returnstmt = "<table class='tableprint'>";
			$plekveld1 = $this->zoekPlekVeld($toernooiid, 1);
			$colspan1 = $this->telVeldenPlekVeld($toernooiid, 1);
			$veld2 = $colspan1 + 1;
			$aantal_velden = $this->aantalVelden($toernooiid);
			if ($veld2 <= $aantal_velden)
			{
				$plekveld2 = $this->zoekPlekVeld($toernooiid, $veld2);
				$colspan2 = $this->telVeldenPlekVeld($toernooiid, $veld2);
				$veld3 = $colspan1 + $colspan2 + 1;
				if ($veld2 <= $aantal_velden)
					$plekveld3 = $this->zoekPlekVeld($toernooiid, $veld3);
			}
			else
				$colspan2 = 0;
			if ($aantal_velden - $colspan1 - $colspan2 > 0)
				$colspan3 = $aantal_velden - $colspan1 - $colspan2;
			else
				$colspan3 = 0;
            $returnstmt .= "<tr>".PHP_EOL."<th class='lijnonder' colspan='2'></th>".PHP_EOL;
            $returnstmt .= "<th class='lijnonderenlinksvet' colspan='$colspan1'>$plekveld1</th>".PHP_EOL;
			if ($colspan2 > 0)
                $returnstmt .= "<th class='lijnonderenlinksvet' colspan='$colspan2'>$plekveld2</th>".PHP_EOL;
			if ($colspan3 > 0)
                $returnstmt .= "<th class='lijnonderenlinksvet' colspan='$colspan3'>$plekveld3</th>".PHP_EOL;
            $returnstmt .= "</tr>".PHP_EOL;
            $returnstmt .= "<tr><th class='lijnonder'>NR</th>".PHP_EOL;
            $returnstmt .= "<th class='lijnonderenlinks'>TIJD</th>".PHP_EOL;
			$veldteller = 1;
			while ($veldteller <= $aantal_velden):
				if ($veldteller == 1 OR $veldteller == $colspan1 + 1 OR $veldteller == $colspan1 + $colspan2 + 1 OR $veldteller == $colspan1 + $colspan2 + $colspan3 + 1)
					$class="lijnonderenlinksvet";
				else
					$class="lijnonderenlinks";
                $returnstmt .= "<th class='$class'>VELD $veldteller</th>".PHP_EOL;
				$veldteller++;
			endwhile;
            $sql = "SELECT speelronde, aanvang, eind, thuisploeg, uitploeg, veld FROM wedstrijden WHERE toernid=? AND poule <> 'Z' ORDER BY speelronde, veld";
            $query = $this->dbconn->prepare($sql);
            $query->execute([$toernooiid]);
			$max = $this->zoekHoogstespeelronde($toernooiid);
			$rondeteller = 0;
			//alle wedstrijden worden bij langs gegaan en alle wedstrijden van 1 speelronde komen op 1 rij
			while ($recset = $query->fetch(3)):
			    $class="wedstrprint";
			    $class2="lijnzonder";
			//als de eerste wedstrijd van een nieuwe speelronde zich aandient, moet er weer een nieuwe rij komen met ronde en tijden
			    if ($rondeteller <> $recset[0])
			    {
			        $counter = 1;
			        if (($rondeteller+1)%5 == 0)
			        {
			        	$class="lijnonderenlinks";
						$class2="lijnonder";
			        }
			        if ($rondeteller+1 == $max)
			        {
				        $class="lijnlinks";
				        $class2="lijnzonder";
			        }
                    $returnstmt .= "</tr>\n<tr><td style='text-align: right' class='$class2'><b>$recset[0].</b></td>";
                    $returnstmt .= "<td class='$class'>".substr($recset[1],0,5)." - ".substr($recset[2],0,5)."</td>".PHP_EOL;
			        $rondeteller++;
			    }
			//rij wordt aangevuld met alle wedstrijden in de speelronde
			    if (($rondeteller)%5 == 0)
			    {
				    if ($recset[5] == 1 OR $recset[5] == $colspan1 + 1 OR $recset[5] == $colspan1 + $colspan2 + 1 OR $recset[5] == $colspan1 + $colspan2 + $colspan3 + 1)
				    	$class="lijnonderenlinksvet";
				    else
				    	$class="lijnonderenlinks";
			    }
			    else
			    {
				    if ($recset[5] == 1 OR $recset[5] == $colspan1 + 1 OR $recset[5] == $colspan1 + $colspan2 + 1 OR $recset[5] == $colspan1 + $colspan2 + $colspan3 + 1)
				    	$class="lijnlinksvet";
				    else
				    	$class="lijnlinks";
			    }

			    if ($rondeteller == $max)
			    {
				    if ($recset[5] == 1 OR $recset[5] == $colspan1 + 1 OR $recset[5] == $colspan1 + $colspan2 + 1 OR $recset[5] == $colspan1 + $colspan2 + $colspan3 + 1)
				    	$class="lijnlinksvet";
				    else
			        	$class="lijnlinks";
			    }
                $returnstmt .= "<td class='".$class."' style='text-align: center'>$recset[3] - $recset[4]</td>".PHP_EOL;
				$counter++;
			endwhile;
			if ($counter > $recset[5])
				$counter--;
			//tabel aanvullen met lege cellen
			if ($counter < $aantal_velden)
			{
			    while ($counter < $aantal_velden)
			    {
			        if ($counter == 1 OR $recset[5] == $colspan1 + 1 OR $counter == $colspan1 + 1 OR $counter == $colspan1 + $colspan2 + 1 OR $counter == $colspan1 + $colspan2 + $colspan3 + 1)
			        	$class="lijnlinksvet";
			        else
			        	$class="lijnlinks ";
                    $returnstmt .= "<td class='$class'></td>".PHP_EOL;
			        $counter++;
			    }
			}
            $returnstmt .= "</tr></table>";
		} catch(PDOException $e) {
            $returnstmt = $e->getMessage();
		}
        return $returnstmt;
	}
	public function maakWedstrijdformulierenbijTweevelden($toernooiid,$veld1,$veld2)
	{
		try {
            $sql = "SELECT wedstrid FROM wedstrijden WHERE toernid=? AND (veld=? OR veld=?) ORDER BY speelronde, veld";
            $query = $this->dbconn->prepare($sql);
            $query->execute([$toernooiid, $veld1, $veld2]);
			$returnstmt="";
			$teller=0;
			while ($recset = $query->fetch(3)):
				if ($teller & 1)
					$class="a5rechts";
				//als $teller even is
				else {
					$class = "a5links";
					$returnstmt.="<div class='wrap'>\n";
				}
				$returnstmt.="<div class='$class'>";
				$wedstrijd = new Wedstrijd();
				$returnstmt.= $wedstrijd->maakWedstrformulier($recset[0]);
				$returnstmt.="</div>\n";
				$teller++;
				if ($class=='a5rechts')
					$returnstmt.= "</div>\n";
			endwhile;
			if($teller & 1)
				$returnstmt.="<div class='a5rechts'></div>";
			$returnstmt.="</div>";
		} catch(PDOException $e) {
            $returnstmt = $e->getMessage();
		}
        return $returnstmt;
	}
	public function insertToernooiMetUser($toernid): void
	{
		try {
            $sql = "INSERT INTO toernooiusers (toernooi, user) VALUES (?, ?)";
            $query = $this->dbconn->prepare($sql);
            $query->execute([$toernid, $_SESSION['user_session']]);
		} catch(PDOException $e) {
			echo $e->getMessage();
		}
	}
	public function deleteToernooi($toernid): void
	{
		try {
            $sql = "DELETE FROM toernooien WHERE toernid=?;
                    DELETE FROM wedstrijden WHERE toernid=?;
                    DELETE FROM ploegen WHERE toernid=?;
                    DELETE FROM toernooiusers WHERE toernooi=?";
            $query = $this->dbconn->prepare($sql);
            $query->execute([$toernid, $toernid, $toernid, $toernid]);
		} catch(PDOException $e) {
			echo $e->getMessage();
		}
	}
	public function toonEditformToernooi($toernid)
	{
		try {
            $sql = "SELECT naam, datum, teams, poules, velden, aanvang, duur FROM toernooien WHERE toernid=?";
            $query = $this->dbconn->prepare($sql);
            $query->execute([$toernid]);
			$recset = $query->fetch(3);
			$returnstmt="<form method='post' action='edittoernooi.php?toernid=$toernid'>".PHP_EOL;
			$returnstmt.="<table>".PHP_EOL;
			$returnstmt.="<tr>".PHP_EOL;
			$returnstmt.="<td height='40' style='text-align: right' class='zonder'>Naam Toernooi</td>".PHP_EOL;
			$returnstmt.="<td class='zonder'><input type='text' size='30' name='naam' value='$recset[0] kopie'></td>".PHP_EOL;
			$returnstmt.="</tr>".PHP_EOL;
			$returnstmt.="<tr>".PHP_EOL;
			$returnstmt.="<td height='40' style='text-align: right' class='zonder'>Datum</td>".PHP_EOL;
			$returnstmt.="<td class='zonder'><input type='date' size='6' name='datum' value='$recset[1]'></td>".PHP_EOL;
			$returnstmt.="</tr>".PHP_EOL;
			$returnstmt.="<tr>".PHP_EOL;
			$returnstmt.="<td height='40' style='text-align: right' class='zonder'>Aantal Teams</td>".PHP_EOL;
			$returnstmt.="<td class='zonder'><input type='number' size='1' name='teams' value='$recset[2]'></td>".PHP_EOL;
			$returnstmt.="</tr>".PHP_EOL;
			$returnstmt.="<tr>".PHP_EOL;
			$returnstmt.="<td height='40' style='text-align: right' class='zonder'>Aantal poules</td>".PHP_EOL;
			$returnstmt.="<td class='zonder'><input type='number' size='1' name='poules' value='$recset[3]'></td>".PHP_EOL;
			$returnstmt.="</tr>".PHP_EOL;
			$returnstmt.="<tr>".PHP_EOL;
			$returnstmt.="<td height='40' style='text-align: right' class='zonder'>Aantal velden</td>".PHP_EOL;
			$returnstmt.="<td class='zonder'><input type='number' size='1' name='velden' value='$recset[4]'></td>".PHP_EOL;
			$returnstmt.="</tr>".PHP_EOL;
			$returnstmt.="<tr>".PHP_EOL;
			$returnstmt.="<td height='40' style='text-align: right' class='zonder'>Aanvangstijd</td>".PHP_EOL;
			$returnstmt.="<td class='zonder'><input type='time' size='10' name='aanvang' value='$recset[5]'></td>".PHP_EOL;
			$returnstmt.="</tr>".PHP_EOL;
			$returnstmt.="<tr>".PHP_EOL;
			$returnstmt.="<td height='40' style='text-align: right' class='zonder'>Wedstrijdduur (minuten)</td>".PHP_EOL;
			$returnstmt.="<td class='zonder'><input type='number' size='2' name='duur' value='$recset[6]'></td>".PHP_EOL;
			$returnstmt.="</tr>".PHP_EOL;
			$returnstmt.="<tr>".PHP_EOL;
			$returnstmt.="<td height='40' style='text-align: right' class='zonder'>Hele of halve competitie</td>".PHP_EOL;
			$returnstmt.="<td class='zonder'><input type='radio' name='comp' value='1'> hele&nbsp;&nbsp;&nbsp;&nbsp;<input type='radio' name='comp' value='0' checked> halve</td>".PHP_EOL;
			$returnstmt.="</tr>".PHP_EOL;
			$returnstmt.="<tr>".PHP_EOL;
			$returnstmt.="<td height='40' class='zonder'></td>".PHP_EOL;
			$returnstmt.="<td class='zonder'><input name='edit' type='submit' value='Verzend'>".PHP_EOL;
			if (isset($_GET['error']))
				$returnstmt.="<span style='color: red'> je moet alle velden invullen!</span>";
			$returnstmt.="</td>".PHP_EOL;
			$returnstmt.="</tr></table></form>".PHP_EOL;
		} catch(PDOException $e) {
            $returnstmt = $e->getMessage();
		}
        return $returnstmt;
	}
	public function updateWinstEnGedrag($toernooiid,$gedrag,$winnaar): void
	{
		try {
            $sql = "UPDATE toernooien SET gedrag=?, winnaar=? WHERE toernid=?";
            $query = $this->dbconn->prepare($sql);
            $query->execute([$gedrag,$winnaar,$toernooiid]);
		} catch(PDOException $e) {
			echo $e->getMessage();
		}
	}
}
