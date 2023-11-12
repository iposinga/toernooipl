<html>
<head>
<link type="text/css" rel="stylesheet" href="inc/toernooiplanner.css"/>
</head>
<body background="backgrounds/background2.jpg">
<div id="boxContainerContainer">
    <div id="boxContainer">
<div id="box1">
<?
$toernooiid = $_GET['toernooiid'];
include('inc/vars.php');
$toernvraag = mysql_query ("SELECT naam, teams, poules FROM toernooien WHERE toernid='$toernooiid'", $db) or die(mysql_error());
$row = mysql_fetch_array($toernvraag);
$toernooinaam = $row['naam'];
echo "<h1>Videowall ".$toernooinaam."</h1>";
?>
<form method="POST" action="3bij2scherm.php?tid=<? echo $toernooiid ?>">
<input type=checkbox name=dubbel value=1>twee poules op 1 scherm<br>
<table class="videowalltable">
<tr>
<td class="videowall">
<input type=radio name=scherm1 value=all>wedstrijdschema<br>
<input type=radio name=scherm1 value=poules>poule-indeling<br>
<?
$teller = 1;
//echo "aantal poules: ".$row['poules'];
while ($teller <= $row['poules'])
{
echo "<input type=radio name=scherm1 value=".chr(64+$teller).">poule ".chr(64+$teller)."<br>";
$teller++;
}
?>
</td>
<td class="videowall">
<input type=radio name=scherm2 value=all>wedstrijdschema<br>
<input type=radio name=scherm2 value=poules>poule-indeling<br>
<?
$teller = 1;
//echo "aantal poules: ".$row['poules'];
while ($teller <= $row['poules'])
{
echo "<input type=radio name=scherm2 value=".chr(64+$teller).">poule ".chr(64+$teller)."<br>";
$teller++;
}
?>
</td>
<td class="videowall">
<input type=radio name=scherm3 value=all>wedstrijdschema<br>
<input type=radio name=scherm3 value=poules>poule-indeling<br>
<?
$teller = 1;
//echo "aantal poules: ".$row['poules'];
while ($teller <= $row['poules'])
{
echo "<input type=radio name=scherm3 value=".chr(64+$teller).">poule ".chr(64+$teller)."<br>";
$teller++;
}
?>
</td>
</tr>
<tr>
<td class="videowall">
<input type=radio name=scherm4 value=all>wedstrijdschema<br>
<input type=radio name=scherm4 value=poules>poule-indeling<br>
<?
$teller = 1;
//echo "aantal poules: ".$row['poules'];
while ($teller <= $row['poules'])
{
echo "<input type=radio name=scherm4 value=".chr(64+$teller).">poule ".chr(64+$teller)."<br>";
$teller++;
}
?>
</td>
<td class="videowall">
<input type=radio name=scherm5 value=all>wedstrijdschema<br>
<input type=radio name=scherm5 value=poules>poule-indeling<br>
<?
$teller = 1;
//echo "aantal poules: ".$row['poules'];
while ($teller <= $row['poules'])
{
echo "<input type=radio name=scherm5 value=".chr(64+$teller).">poule ".chr(64+$teller)."<br>";
$teller++;
}
?>
</td>
<td class="videowall">
<input type=radio name=scherm6 value=all>wedstrijdschema<br>
<input type=radio name=scherm6 value=poules>poule-indeling<br>
<?
$teller = 1;
//echo "aantal poules: ".$row['poules'];
while ($teller <= $row['poules'])
{
echo "<input type=radio name=scherm6 value=".chr(64+$teller).">poule ".chr(64+$teller)."<br>";
$teller++;
}
?>
</td>
</tr>
</table>
<input type=submit value="Pas toe">
</form>
<!-- afsluiting box2 -->
        </div>
<!-- afsluiting boxContainer zit in menu.php-->
    </div>
<!-- afsluiting boxContainerContainer zit in menu.php-->
</div>
</body>
</html>
