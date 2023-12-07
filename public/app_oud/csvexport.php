<?
echo "oke";
$toernooiid = $_GET['toernooiid'];
$toernooiid = 256;
include('inc/vars.php');
//include('menu.php');

mysql_query ("
SELECT poule
FROM wedstrijden
WHERE toernid='$toernooiid' AND poule <> 'Z'
ORDER BY speelronde, veld
INTO OUTFILE 'C:/temp/wedstrexport.csv'
FIELDS ENCLOSED BY '"' TERMINATED BY ';'
ESCAPED BY '"'
LINES TERMINATED BY '\r\n'
", $db) or die(mysql_error());

	?>
