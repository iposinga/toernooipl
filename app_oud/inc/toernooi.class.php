<?php
require_once('dbconnection.class.php');
require_once('functions.php');
require_once('veld.class.php');
require_once('ploeg.class.php');
require_once('poule.class.php');
require_once('wedstrijd.class.php');
require_once('admin/admin_inc/finalewedstrijd.class.php');
class Toernooi
{
    private $dbconn;
    public function __construct()
    {
        $this->dbconn = new Dbconnection();
    }
	public function toonToernooien()
	{
		try {
            $sql = "SELECT toernid, naam, velden, poules FROM toernooien";
            $query = $this->dbconn->prepare($sql);
            $query->execute();
            $returnstmt="<table style='border: none'>";
            $teller = 1;
            while($recset = $query->fetch(PDO::FETCH_NUM)):
                $returnstmt.="<tr><td style='text-align: end' class='zonder'>".$teller++."</td>".PHP_EOL;
                $returnstmt.="<td style='text-align: left' class='zonder'><a style='text-decoration: none;' href='wedstrschema.php?toernooiid=$recset[0]'>$recset[1]</a></td>".PHP_EOL;
                $returnstmt.="<td style='width: 20px' class='zonder'><a href='#' onclick=\"alert('Om te printen in het volgende scherm, kies \'ctrl + p\' en kies bij de voorkeursinstellingen: voor enkelzijdig A4, liggend (landscape)'); window.open('wedstrformulier.php?toernooiid=$recset[0]&velden=$recset[2]', 'videowall', 'width=1020,height=680,top=0,left=0'); return false\" title='wedstrijdformulieren'><img src= 'images/Document.png' width='130%' alt=''></a></td>".PHP_EOL;
                $videowallpage = "videowall.php?toernooiid=$recset[0]&titel=$recset[1]&poules=$recset[3]";
                $returnstmt.="<td class='zonder'><a href='$videowallpage' title='toon op videowall' onclick=\"popupwindow('".$videowallpage."', 'videowall', '3850','1970'); return false\"><img src='images/Calendar.png' alt=''></a></td>".PHP_EOL;
                $returnstmt.="<td class='zonder'><a href='#' title='print schema' onclick=\"popupwindow('printwedstrschema.php?toernooiid=".$recset['toernid']."', 'print', '710', '980'); return false\"><img src='images/Printer.png' alt=''></a></td></tr>".PHP_EOL;
            endwhile;
		    $returnstmt.="</table>";
		}
		catch(PDOException $e) {
            $returnstmt = $e->getMessage();
		}
        return $returnstmt;
	}

	public function zoekuserToernooien()
	{
		try {
            $sql = "SELECT toernid, naam, velden, poules FROM toernooien, toernooiusers WHERE toernooi = toernid AND user=?";
            $query = $this->dbconn->prepare($sql);
            $query->execute([$_SESSION['user_session']]);
            $returnstmt="<table style='border: none'>";
            $teller = 1;
            while($recset=$query->fetch(3)):
                $returnstmt.="<tr><td style='text-align: end' class='zonder'>".$teller."</td>".PHP_EOL;
                $returnstmt.="<td style='text-align: left' class='zonder'><a style='text-decoration: none;' href='wedstrschema.php?toernooiid=$recset[0]'>$recset[1]</a></td>".PHP_EOL;
                $returnstmt.="<td style='width: 20px' class='zonder'><a href='verwijderpopup.php?toernooiid=$recset[0]' onclick=\"popupwindow('verwijderpopup.php?toernooiid=$recset[0]', 'verwijder', '640', '260'); return false\" title='verwijder'><img src='../images/Trash.png' alt=''></a></td>".PHP_EOL;
                $returnstmt.="<td style='width: 20px' class='zonder'><a href='edittoernooi.php?toernooiid=$recset[0]' title='kopieer en pas aan'><img src='../images/Write.png' alt=''></a></td>".PHP_EOL;
                $returnstmt.="<td style='width: 20px' class='zonder'><a href='koppelusers.php?toernooiid=$recset[0]' onclick=\"popupwindow('koppelusers.php?toernooiid=$recset[0]', 'koppel', '520', '770'); return false\" title='koppel gebruikers'><img src= '../images/User.png' alt=''></a></td>".PHP_EOL;
                $returnstmt.="<td style='width: 20px' class='zonder'><a href='nieuwachtergrond.php?toernooiid=$recset[0]' onclick=\"window.open('nieuwachtergrond.php?toernid=$recset[0]', 'width=400,height=400,top=220,left=220'); return false\" title='achtergrond'><img src='../images/Picture.png' alt=''></a></td>".PHP_EOL;
                $returnstmt.="<td style='width: 20px' class=zonder><a href='#' onclick=\"alert('Om te printen in het volgende scherm, kies \'ctrl + p\' en kies bij de voorkeursinstellingen: voor enkelzijdig A4, liggend (landscape)'); window.open('wedstrformulier.php?toernooiid=".$recset['toernid']."&velden=".$recset['velden']."', 'videowall', 'width=1020,height=680,top=0,left=0'); return false\" title='wedstrijdformulieren'><img src= '../images/Document.png' alt=''></a></td>".PHP_EOL;
                $videowallpage = "../videowall$recset[3]poules.php?toernooiid=$recset[0]&titel=$recset[1]";
                $returnstmt.="<td class='zonder'><a href='$videowallpage' title='toon op videowall' onclick=\"popupwindow('".$videowallpage."', 'videowall', '3850','1970'); return false\"><img src='../images/Calendar.png' alt=''></a></td>".PHP_EOL;
                $returnstmt.="<td class='zonder'><a href='#' title='print schema' onclick=\"popupwindow('printwedstrschema.php?toernooiid=$recset[0]', 'print', '710', '980'); return false\"><img src='../images/Printer.png' alt=''></a></td>".PHP_EOL;
                $returnstmt.="</tr>".PHP_EOL;
            endwhile;
		    $returnstmt.="</table>";
		} catch(PDOException $e) {
            $returnstmt = $e->getMessage();
		}
        return $returnstmt;
	}
	public function getToernooiparameters($toernooiid)
	{
		try {
            $sql = "SELECT naam, teams, achtergrond, poules, datum, winnaar, gedrag FROM toernooien WHERE toernid = ?";
            $query = $this->dbconn->prepare($sql);
            $query->execute([$toernooiid]);
			$recset=$query->fetch(3);
			$returnarray=array();
			$returnarray['naam']=$recset[0];
            $displaydatum = datumFormatting($recset[4], 6);
			//$displaydatum=strftime("%a %e %b '%y" , strtotime($recset['datum']));
			$returnarray['datum']=$displaydatum;
			if ($recset[2] <> '')
				$returnarray['achtergrond'] = $recset[2];
			else
				$returnarray['achtergrond'] = "background2.jpg";
			/*if ($recset['teams'] / $recset['poules'] == round($recset['teams'] / $recset['poules'],0))
				$returnarray['aantalvelden'] = $recset['teams'] / $recset['poules'] + 2;
			else
				$returnarray['aantalvelden'] = round($recset['teams'] / $recset['poules'] + 1/2,0) + 2;	*/
			$returnarray['winnaar']=$recset[5];
			$returnarray['gedrag']=$recset[6];
		} catch(PDOException $e) {
            $returnarray[0] = $e->getMessage();
		}
        return $returnarray;
	}
	public function maakToernooi()
	{
		try {
            $displdate = datumFormatting($_POST['datum'], 7);
            $sql = "INSERT INTO toernooien (naam, datum, teams, poules, velden, aanvang, duur) VALUES (?, ?, ?, ?, ?, ?, ?)";
            $query = $this->dbconn->prepare($sql);
            $query->execute([$_POST['naam'],$displdate,$_POST['teams'],$_POST['poules'],$_POST['velden'],$_POST['aanvang'],$_POST['duur']]);
            $toernid = $this->dbconn->lastInsertId();
			$teamsperpoule = intval($_POST['teams'] / $_POST['poules']);
			$typecomp = $_POST['comp'];
			//maak velden aan
			$teller = 1;
			while ($teller <= $_POST['velden']):
				$makefield = new Veld();
				$makefield->maakVeld($toernid, $teller, 'F018');
				$teller++;
			endwhile;
			//maak ploegen aan en verdeel ze over poules
			$teamteller = 1;
			$pouleteller = 1;
			$restteller = $_POST['teams'] % $_POST['poules'];
			while ($pouleteller <= $_POST['poules']):
				$poule = chr($pouleteller+64);
  				$teamsperpouleteller = 1;
  				while ($teamsperpouleteller <= $teamsperpoule):
	  				$newploeg = new Ploeg();
	  				$newploeg->maakPloeg($toernid, $poule, $teamteller);
	  				$teamteller++;
	  				$teamsperpouleteller++;
				endwhile;
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
			$this->maakWedstrschemafase2($toernid,$_POST['aanvang'],$_POST['duur'],$_POST['velden']);
		} catch(PDOException $e) {
			echo $e->getMessage();
		}
	}
	public function maakWedstrschemafase1($toernooiid,$comp)
	{
		try {
            $sql = "SELECT MIN(teamnr), COUNT(poule), poule FROM ploegen WHERE toernid=?";
            $query = $this->dbconn->prepare($sql);
            $query->execute([$toernooiid]);
			while ($recset=$query->fetch(3)):
				$newpoule = new Poule();
				$newpoule->maaknieuwePoule($recset[1], $recset[0], $toernooiid, $recset[2], $comp);
			endwhile;
		} catch(PDOException $e) {
			echo $e->getMessage();
		}
	}
	public function maakWedstrschemafase2($toernooiid,$aanvang,$duur,$aantalvelden)
	{
		try {
            $sql = "SELECT wedstrid FROM wedstrijden WHERE toernid = ? ORDER BY ronde, poule";
            $query = $this->dbconn->prepare($sql);
            $query->execute([$toernooiid]);
			$veldteller = 1;
			//$tijdbijhouder = strftime('%H:%M', strtotime($aanvang));
            $tijdbijhouder = datumFormatting(strtotime($aanvang), 12);
			$speelronde = 1;
			while ($recset=$query->fetch(3)):
				if ($veldteller > $aantalvelden):
					$veldteller = 1;
					//$tijdbijhouder = strftime('%H:%M', strtotime($tijdbijhouder) + $duur * 60);
                    $tijdbijhouder = datumFormatting(strtotime($aanvang) + $duur * 60, 12);
					$speelronde++;
  				endif;
  				$invaanvtijd = $tijdbijhouder;
  				//$inveindtijd = strftime('%H:%M', strtotime($tijdbijhouder) + $duur * 60);
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
            $sql = "SELECT velden FROM toernooien WHERE toernid = ?";
            $query = $this->dbconn->prepare($sql);
            $query->execute([$toernooiid]);
			$recset = $query->fetch(3);
			$returntstmt = $recset[0];
		} catch(PDOException $e) {
            $returntstmt = $e->getMessage();
		}
        return $returntstmt;
	}
	public function aantalRonden($toernooiid)
	{
		try {
            $sql = "SELECT MAX(speelronde) FROM wedstrijden WHERE toernid = ?";
            $query = $this->dbconn->prepare($sql);
            $query->execute([$toernooiid]);
            $recset = $query->fetch(3);
            $returntstmt = $recset[0];
		} catch(PDOException $e) {
            $returntstmt = $e->getMessage();
		}
        return $returntstmt;
	}
	public function displayWedstrschema($toernooiid)
	{
		try {
			$sql= "SELECT t1.naam AS thuisnaam, t2.naam AS uitnaam, thuisploeg, uitploeg, thuisscore, uitscore, speelronde, aanvang, eind, wedstrijden.poule, veld, wedstrid FROM wedstrijden
			LEFT JOIN ploegen AS t1 ON t1.teamnr = wedstrijden.thuisploeg AND t1.toernid = wedstrijden.toernid
			LEFT JOIN ploegen AS t2 ON t2.teamnr = wedstrijden.uitploeg AND t2.toernid = wedstrijden.toernid
			WHERE wedstrijden.toernid=? AND wedstrijden.poule<>'Z' ORDER BY speelronde, veld";
			$query = $this->dbconn->prepare($sql);
			$query->execute([$toernooiid]);
			$wedstrtabel="<table class='table table-sm'><thead>";
			$wedstrtabel.="<tr>";
			$wedstrtabel.="<th class='standleft'>ronde</th>";
			$wedstrtabel.="<th class='standcenter'>tijd</th>";
			$veldteller = 1;
			while ($veldteller <= $this->aantalVelden($toernooiid)):
				$wedstrtabel.="<th class='standcenter'>veld $veldteller</th>";
				$veldteller++;
			endwhile;
			$wedstrtabel.="</tr><tr></thead><tbody>";
			$rondeteller = 0;
			while ($recset=$query->fetch(PDO::FETCH_ASSOC)):
				if ($rondeteller <> $recset['speelronde']):
					if ($rondeteller > 0):
						$wedstrtabel.="</tr><tr>";
					endif;
					$wedstrtabel.="<td class='rijkop'>{$recset['speelronde']}.&nbsp;</td>";
					$wedstrtabel.="<td class='wedstr'><b>".substr($recset['aanvang'],0,5)." - ".substr($recset['eind'],0,5)."</b></td>";
					$rondeteller++;
    			endif;
    			$wedstrtabel.="<td class='wedstr'>{$recset['thuisploeg']} - {$recset['uitploeg']}</b></td>";
			endwhile;
			$wedstrtabel.="</tr></tbody></table>";
			$returnstmt= "<h2>Wedstrijdschema</h2>".$wedstrtabel;
		} catch(PDOException $e) {
            $returnstmt = $e->getMessage();
		}
        return $returnstmt;
	}
	public function displayKlassen($toernooiid)
	{
		try {
			echo '<h2>Klassenstand</h2>';
			echo "<table><tr><th></th><th>klas</th><th>gem</th><th>p</th><th>g</th><th>w</th><th>g</th><th>v</th><th colspan=3>doelsaldo</th></tr>";
            $sql = "SELECT * FROM ploegen WHERE toernid=? GROUP BY stamklas ORDER BY stamklas";
			$query = $this->dbconn->prepare($sql);
            $query->execute([$toernooiid]);
			while ($row=$query->fetch(PDO::FETCH_ASSOC)):
				$klas = $row['stamklas'];
                $sql2 ="SELECT t1.stamklas AS thuisklas, t2.stamklas AS uitklas, thuisploeg, uitploeg, thuispunten, thuisscore, uitpunten, uitscore, speelronde, aanvang, eind, wedstrijden.poule, veld, wedstrid FROM wedstrijden
                        LEFT JOIN ploegen AS t1 ON t1.teamnr = wedstrijden.thuisploeg
                        LEFT JOIN ploegen AS t2 ON t2.teamnr = wedstrijden.uitploeg
                        WHERE wedstrijden.toernid=? AND (t1.stamklas=? OR t2.stamklas=?) AND wedstrijden.toernid=t1.toernid AND wedstrijden.toernid=t2.toernid";
				$query2 = $this->dbconn->prepare($sql2);
                $query2->execute([$toernooiid, $klas, $klas]);
    			$punten = 0;
				$gespeeld = 0;
				$winst = 0;
				$gelijk = 0;
				$verlies = 0;
				$voor = 0;
				$tegen = 0;
				while ($rige=$query2->fetch(PDO::FETCH_ASSOC)):
					$teller = $gespeeld + 1;
					if ($rige['thuisscore'] <> '')
					{
						if ($klas == $rige['thuisklas'])
						{
							//echo $teller.") ".$rige['thuisklas']."(".$rige['thuisploeg'].") - ".$rige['uitklas']."(".$rige['uitploeg'].") geeft ".$rige['thuispunten']." p<br>";
							$punten = $punten + $rige['thuispunten'];
							$voor = $voor + $rige['thuisscore'];
							$tegen = $tegen + $rige['uitscore'];
							if ($rige['thuispunten'] == 1)
								$gelijk++;
							elseif ($rige['thuispunten'] == 0)
								$verlies++;
							else
								$winst++;
        				}
						else
						{
							//echo $teller.") ".$rige['thuisklas']."(".$rige['thuisploeg'].") - ".$rige['uitklas']."(".$rige['uitploeg'].") geeft ".$rige['uitpunten']." p<br>";
							$punten = $punten + $rige['uitpunten'];
							$voor = $voor + $rige['uitscore'];
							$tegen = $tegen + $rige['thuisscore'];
							if ($rige['uitpunten'] == 1)
								$gelijk++;
							elseif ($rige['uitpunten'] == 0)
								$verlies++;
							else
								$winst++;
        				}
						$gespeeld++;
        			}
    			endwhile;
				//$naam = $row['stamklas'];
				$saldo = $voor - $tegen;
				//echo "<hr>";
				//zet de data in een array
				$gemiddelde = round($punten / $gespeeld, 3);
				$data[] = array('klas' => $klas, 'gem' => $gemiddelde, 'punten' => $punten, 'gespeeld' => $gespeeld, 'winst' => $winst, 'gelijk' => $gelijk, 'verlies' => $verlies, 'voor' => $voor, 'tegen' => $tegen, 'saldo' => $saldo);
			endwhile;

			// Obtain a list of columns
			foreach ($data as $key => $row)
			{
				$gem[$key] = $row['gem'];
    //$points[$key]  = $row['punten'];
    //$played[$key] = $row['gespeeld'];
    //$doelscore[$key] = $row['saldo'];
			}

			// Sort the data with volume descending, edition ascending
			// Add $data as the last parameter, to sort by the common key
			array_multisort($gem, SORT_DESC, $data);
			//array_multisort($points, SORT_DESC, $played, SORT_ASC, $doelscore, SORT_DESC, $data);
			$positie = 1;
/*echo "<pre>";
print_r($data);
echo "</pre>";*/
			foreach ($data as $v1)
			{
				$class="standright";
				echo "<tr><td class=".$class."><b>".$positie.".</b></td>\n";
     /*echo "<pre>";
print_r($v1);
echo "</pre>"*/;
    			foreach ($v1 as $v2 => $value)
    			{
					//echo $v1."-".$v2."-".$value."<br>";
					if ($v2 == 'klas')
						$class="standleft";
					else
						$class="standright";
					echo "<td class=".$class.">";
					if ($v2 == 'voor')
						echo "+";
					elseif ($v2 == 'tegen')
						echo "-";
					elseif ($v2 == 'gem')
						echo "<b>";
					echo $value;
					if ($v2 == 'punten')
						echo "</b>";
					echo "</td>\n";
    			}
				echo "</tr>";
				$positie++;
			}
			echo "</table>";
		}
		catch(PDOException $e)
		{
			echo $e->getMessage();
		}
	}
	public function displayFinaleschema($toernooiid)
	{
		try {
			echo '<h2>Finalewedstrijden</h2>';
			echo "<table><tr><th>ronde</th><th>tijd</th>";
			$veldteller = 1;
			//$veldenaantal = $this->aantalVelden($toernooiid);
			$rondenenaantal = $this->aantalRonden($toernooiid);
			while ($veldteller <= $this->aantalVelden($toernooiid)):
				echo "<th>veld $veldteller</th>";
				$veldteller++;
			endwhile;
            $sql = "SELECT * FROM finalewedstrijden WHERE toernooi_id=? ORDER BY finaleronde, veld";
			$query = $this->dbconn->prepare($sql);
            $query->execute([$toernooiid]);
			$finalerondeteller = 0;
			while ($recset = $query->fetch(PDO::FETCH_ASSOC)):
		    //als de eerste wedstrijd van een nieuwe speelronde zich aandient, moet er weer een nieuwe rij komen met ronde en tijden
				if ($finalerondeteller <> $recset['finaleronde']):
					$speelrondedispl = $rondenenaantal + $recset['finaleronde'];
					echo "</tr>\n<tr><td style='text-align: right'>><b>$speelrondedispl.</b></td><td>".substr($recset['aanvang'],0,5)." - ".substr($recset['eind'],0,5)."</td>";
					$finalerondeteller++;
    			endif;
				echo "<td class='wedstr' style='text-align: center'>";
				if ($recset['thuisploeg'] <> '')
					echo $recset['fin_wedstrnaam']."<br>{$recset['thuisploeg']} - ".$recset['uitploeg'];
				if ($recset['thuisploegnr'] > 0 OR $recset['uitploegnr'] > 0)
					echo "<br>{$recset['thuisploegnr']} - ".$recset['uitploegnr'];
				if (isset($recset['fin_thuisscore']) AND isset($recset['fin_uitscore']))
					echo "<br><span style='font-size: smaller; color: red;'>{$recset['fin_thuisscore']} - {$recset['fin_uitscore']}</span>";
				echo "</td>\n";
			endwhile;
			echo "</tr></table>";
		} catch(PDOException $e) {
			echo $e->getMessage();
		}
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
            $sql = "SELECT naam, datum FROM toernooien WHERE toernid=?";
			$query = $this->dbconn->prepare($sql);
            $query->execute([$toernooiid]);
			$recset = $query->fetch(3);
			$displdatum = datumFormatting($recset[1], 6);
			//$displdatum = strftime("%a %d %b '%y",strtotime($titelrij['datum']));
			//strftime('%a %d-%m-%Y' , strtotime($recset['hvoblogb_timestamp']));
			$returnstmt = "<h3 style='text-align: center'>$recset[0] op $displdatum</h3>";
		} catch(PDOException $e) {
            $returnstmt = $e->getMessage();
		}
        return 	$returnstmt;
	}
	public function getPoules($toernooiid)
	{
		try {
            $sql = "SELECT poules FROM toernooien WHERE toernid=?";
			$query = $this->dbconn->prepare($sql);
            $query->execute([$toernooiid]);
			$recset = $query->fetch(3);
			$returnstmt = "";
			$pouleteller=1;
            $widtpercentage = intval(100 / $recset[0]) - 2;
            if($widtpercentage < 18)
                $widtpercentage = 18;
			while ($pouleteller <= $recset[0]):
                $returnstmt .= "<div style='float: left; width: $widtpercentage%; padding: 5px;'>";
			    $zoekpoule = chr($pouleteller+64);
			    // hier de tabel van een poule
			    $pouleploegen = new Poule();
			    $ploegen = $pouleploegen->getPouleploegen($toernooiid,$zoekpoule);
                $returnstmt .= $ploegen;
			    //einde tabel van een poule
                $returnstmt .= "</div>".PHP_EOL;
				$pouleteller++;
			endwhile;
		} catch(PDOException $e) {
            $returnstmt = $e->getMessage();
		}
        return 	$returnstmt;
	}
	public function toonWedstrijdschema($toernooiid,$teamnamen,$admin)
	{
		try {
            $sql = "SELECT * FROM wedstrijden WHERE toernid=? AND poule <> 'Z' ORDER BY speelronde, veld";
            $query = $this->dbconn->prepare($sql);
            $query->execute([$toernooiid]);
            $rondeteller = 0;
            $returnstmt = PHP_EOL . "<table class='table-hover'><tr>" . PHP_EOL;
            $returnstmt .= "<tr><th>ronde</th><th class='veldkop'>tijd</th>";
            $aantalfields = $this->aantalVelden($toernooiid);
            $veldteller = 1;
            while ($veldteller <= $aantalfields):
                $returnstmt .= "<th class='veldkop'>veld " . $veldteller . "</th>";
                $veldteller++;
            endwhile;
            $returnstmt .= "</tr><tr>" . PHP_EOL;
            while ($recset = $query->fetch(PDO::FETCH_ASSOC)):
                if ($rondeteller <> $recset['speelronde']):
                    $returnstmt .= "</tr><tr><td class='rondenr'><b>{$recset['speelronde']}.</b></td><td class='tijden'>" . substr($recset['aanvang'], 0, 5) . " - " . substr($recset['eind'], 0, 5) . "</td>";
                    $rondeteller++;
                endif;
                $returnstmt .= "<td class='wedstr' id='wedstr{$recset['wedstrid']}'>";
                if ($teamnamen == 0):
                    if ($admin == 0) {
                        $returnstmt .= $recset['thuisploeg'] . " - " . $recset['uitploeg'];
                        if (isset($recset['thuisscore']) and isset($recset['uitscore']))
                            $returnstmt .= "<br><span style='color: darkblue'>{$recset['thuisscore']} - {$recset['uitscore']}</span>";
                    } else {
                        $returnstmt .= "<a href='#' title='{$recset['thuisploeg']} {$recset['thuisscore']} - {$recset['uitscore']} {$recset['uitploeg']}' onclick=\"voerUitslagIn({$recset['wedstrid']}, $toernooiid); return false;\">{$recset['thuisploeg']} - {$recset['uitploeg']}</a>";
                        if (isset($recset['thuisscore']) and isset($recset['uitscore']))
                            $returnstmt .= "&nbsp;<span style='color: green;'>&#10003;</span>";
                    }
                else:
                    $ploeg = new Ploeg();
                    $thuisteamnaam = $ploeg->getPloegnaam($toernooiid, $recset['thuisploeg']);
                    $uitteamnaam = $ploeg->getPloegnaam($toernooiid, $recset['uitploeg']);
                    if ($admin == 0) {
                        $returnstmt .= $thuisteamnaam . " - " . $uitteamnaam;
                        if (isset($recset['thuisscore']) and isset($recset['uitscore']))
                            $returnstmt .= "<br><span style='color: darkblue'>{$recset['thuisscore']} - {$recset['uitscore']}</span>";
                    } else {
                        $returnstmt .= "<a href='#' title='{$recset['thuisploeg']} {$recset['thuisscore']} - {$recset['uitscore']} {$recset['uitploeg']}' onclick=\"voerUitslagIn({$recset['wedstrid']}, $toernooiid); return false;\">$thuisteamnaam - $uitteamnaam</a>";
                        if (isset($recset['thuisscore']) and isset($recset['uitscore']))
                            $returnstmt .= "&nbsp;<span style='color: green'>&#10003;</span>";
                    }
                endif;
                $returnstmt .= "</td>";
			endwhile;
            $returnstmt .= "</tr>".PHP_EOL."</table>".PHP_EOL;
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
			while ($pouleteller <= $aantalpoules AND $pouleteller <= $poulesrij1):
				$zoekpoule = chr($pouleteller+64);
				$pouletje = new Poule();
			    $print_poule = $pouletje->printPoulecel($toernooiid, $zoekpoule);
                $returnstmt .= $print_poule;
			    $pouleteller++;
			endwhile;
			if ( $aantalpoules > 6):
			    //nu een nieuwe regel in de hoofdtabel aanmaken
                $returnstmt .= "</tr>".PHP_EOL."<tr>";
			    $celteller = 1;
			    while ($pouleteller <= $aantalpoules)
			    {
					$zoekpoule = chr($pouleteller+64);
					$poultje = new Poule();
					$print_poule = $poultje->printPoulecel($toernooiid, $zoekpoule);
                    $returnstmt .= $print_poule;
					$pouleteller++;
					$celteller++;
			    }
                //nog nog lege cellen toevoegen indien nodig
			    while ($celteller <= $poulesrij1)
			    {
                    $returnstmt .= "<td>".PHP_EOL."</td>".PHP_EOL;
				    $celteller++;
			    }
			endif;
            $returnstmt .= "</tr>".PHP_EOL;
            $returnstmt .= "</table>";
		} catch(PDOException $e) {
            $returnstmt = $e->getMessage();
		}
        return 	$returnstmt;
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
			if ($veld2 <= $aantal_velden):
				$plekveld2 = $this->zoekPlekVeld($toernooiid, $veld2);
				$colspan2 = $this->telVeldenPlekVeld($toernooiid, $veld2);
				$veld3 = $colspan1 + $colspan2 + 1;
				if ($veld2 <= $aantal_velden):
					$plekveld3 = $this->zoekPlekVeld($toernooiid, $veld3);
                endif;
			else:
				$colspan2 = 0;
            endif;
			if ($aantal_velden - $colspan1 - $colspan2 > 0)
				$colspan3 = $aantal_velden - $colspan1 - $colspan2;
			else
				$colspan3 = 0;
            $returnstmt .= "<tr>".PHP_EOL."<th class='lijnonder' colspan='2'></th>".PHP_EOL."<th class='lijnonderenlinksvet' colspan='$colspan1'>$plekveld1</th>".PHP_EOL;
			if ($colspan2 > 0)
                $returnstmt .= "<th class='lijnonderenlinksvet' colspan='$colspan2'>$plekveld2</th>".PHP_EOL;
			if ($colspan3 > 0)
                $returnstmt .= "<th class='lijnonderenlinksvet' colspan='$colspan3'>$plekveld3</th>".PHP_EOL;
            $returnstmt .= "</tr>".PHP_EOL;
            $returnstmt .= "<tr><th class='lijnonder'>NR</th>".PHP_EOL."<th class='lijnonderenlinks'>TIJD</th>".PHP_EOL;
			$veldteller = 1;
			while ($veldteller <= $aantal_velden):
				if ($veldteller == 1 OR $veldteller == $colspan1 + 1 OR $veldteller == $colspan1 + $colspan2 + 1 OR $veldteller == $colspan1 + $colspan2 + $colspan3 + 1)
					$class="lijnonderenlinksvet";
				else
					$class="lijnonderenlinks";
                $returnstmt .= "<th class=$class>VELD $veldteller</th>".PHP_EOL;
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
                    $returnstmt .= "</tr>\n<tr><td style='text-align: end' class='$class2'><b>$recset[0].</b></td>";
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
                $returnstmt .= "<td class='$class' style='text-align: center'>$recset[3] - $recset[4]</td>".PHP_EOL;
				$counter++;
				$volgendespeelronde = $recset[0] + 1;
			endwhile;
			if ($counter > $aantal_velden)
				$counter--;
			//tabel aanvullen met lege cellen
			if ($counter < $aantal_velden)
			{
			    while ($counter <= $aantal_velden)
			    {
			        if ($counter == 1 OR $recset[5] == $colspan1 + 1 OR $counter == $colspan1 + 1 OR $counter == $colspan1 + $colspan2 + 1 OR $counter == $colspan1 + $colspan2 + $colspan3 + 1)
			        	$class="lijnlinksvet";
			        else
			        	$class="lijnlinks ";
                    $returnstmt .= "<td class='$class'></td>".PHP_EOL;
			        $counter++;
			    }
			}
            $returnstmt .= "</tr>".PHP_EOL;
			//$eerstefinalespeelronde=$max + 1;
			//nu de regels met de evt. finalewedstrijden
            $sql2 = "SELECT finaleronde, aanvang, eind, thuisploeg, uitploeg, veld FROM finalewedstrijden WHERE toernooi_id=? ORDER BY finaleronde, veld";
			$query2 = $this->dbconn->prepare($sql2);
            $query2->execute([$toernooiid]);
			$finalewedstrijd=new Finalewedstrijd();
			$max = $finalewedstrijd->aantalFinaleRondes($toernooiid);
			$rondeteller = 0;
			//alle wedstrijden worden bij langs gegaan en alle wedstrijden van 1 speelronde komen op 1 rij
			while ($recset = $query2->fetch(PDO::FETCH_ASSOC))
			{
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
                    $returnstmt .= "</tr>\n<tr><td style='text-align: end' class='$class2'><b>$volgendespeelronde.</b></td>";
                    $returnstmt .= "<td class='$class'>".substr($recset[1],0,5)." - ".substr($recset[2],0,5)."</td>".PHP_EOL;
			        $volgendespeelronde++;
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
                $returnstmt .= "<td class='$class' style='text-align: center'>$recset[3] - $recset[4]</td>".PHP_EOL;
				$counter++;
			}
            $returnstmt .= "</tr>".PHP_EOL;
			//einde finales
            $returnstmt .= "</table>";
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
            $query->execute([$toernooiid,$veld1,$veld2]);
			$returnstmt="";
			$teller=0;
			while ($recset=$query->fetch(3)):
				if ($teller & 1)
					$class="a5rechts";
				//als $teller even is
				else
				{
					$class = "a5links";
					$returnstmt.="<div class='wrap'>\n";
				}
				$returnstmt.="<div class='".$class."'>";
				$wedstrijd=new Wedstrijd();
				$wedstrijdform=$wedstrijd->maakWedstrformulier($recset[0]);
				$returnstmt.= $wedstrijdform;
				$returnstmt.="</div>\n";
				$teller++;
				//$aantalrondes = $recset['speelronde'];
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
}
