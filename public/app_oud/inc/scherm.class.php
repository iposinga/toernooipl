<?php
require_once('poule.class.php');

class Scherm {		
	
	public function displayScherm($toernooiid,$poule)
	{
		echo "<table class='perscherm'>".PHP_EOL;
		//$i=0;
		//while ($i < count($poules_ar))
		//{
			echo '<tr>'.PHP_EOL;
			echo "<td class='zonder' valign='top'>".PHP_EOL;
			$poule = new Poule();       
			//$poulewdstr=$poule->displayPoulewedstr($toernooiid,$poule);
			//echo $poulewdstr;
			echo '</td>'.PHP_EOL;
			echo "<td class='zonder' valign='top'>".PHP_EOL;       
			$poulestand = $poule -> displayPoulestand($toernooiid,$poule);
			echo $poulestand;
			echo '</td>'.PHP_EOL;
			echo '</tr>'.PHP_EOL;
			//$i++;		
		//}
		echo '</table>'.PHP_EOL;		
	}
}