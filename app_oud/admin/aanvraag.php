<?php
$includespath="includes/";
include ($includespath."vars.php");
include ($includespath."functions.php");
?>
<HTML>
<HEAD>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link href="../includes/toernooiplanner.css" rel="stylesheet" type="text/css">
<title>Account aanvragen</title>
<!-- <script src="../includes/functionsjava.js"></script> -->
</HEAD>
<BODY>
<div id="boxContainerContainer">
    <div id="boxContainer">
<div id="box1">
<h1>Account aanvragen</h1>
<?
require_once('PasswordHash.php');
include('../inc/vars.php');
$message = "";
if (isset($_POST['aanvraag']) AND ($_POST['llnr']=='' OR $_POST['wachtw']=='' OR $_POST['wachtwherh']=='' OR $_POST['mailadres']==''))
$message="U moet alle velden correct invoeren!";
elseif (isset($_POST['aanvraag']) AND $_POST['wachtw'] == $_POST['wachtwherh'] )
{
    $user = $_POST['llnr'];
    //kijken of de username uniek is
    $usernamecheck = mysql_query("SELECT * FROM users WHERE user='$user'", $db) or die (mysql_error());
    if ( mysql_num_rows($usernamecheck) > 0)
    $message="De gebruikersnaam bestaat al, kies een andere gebruikersnaam!";
}
elseif (isset($_POST['aanvraag']) AND $_POST['wachtw'] <> $_POST['wachtwherh'])
$message="U hebt twee verschillende wachtwoorden ingevoerd!";



if (isset($_POST['aanvraag']) AND $message == '')
    {
        $pass = $_POST['wachtw'];
        $mailadres = $_POST['mailadres'];
        $hash = create_hash($pass);
        $userinsert = mysql_query("INSERT INTO users (user, pass, email) VALUES ('$user', '$hash', '$mailadres')", $db) or die (mysql_error());
        echo "account is succesvol aangemaakt!<br>";
        echo "klik <a href=\"index.php\">hier</a> om in te loggen op het admin-deel!";
    }
else
{
?>
Bij problemen en/of vragen, mail de webmaster: <a href="mailto: i.osinga@dollardcollege.nl">i.osinga@dollardcollege.nl</a><br><br>
<table>
<form method="post" action="aanvraag.php">
<!-- <input type="hidden" name="stam" value="<? echo $stamnummer ?>">-->
<tr><td class=zonder>Gebruikersnaam:</td><td class=zonder><input type="text" name="llnr" size="10"></td></tr>
<tr><td class=zonder>Wachtwoord:</td><td class=zonder><input type="password" name="wachtw" size="10"></td></tr>
<tr><td class=zonder>Herhaal wachtwoord:</td><td class=zonder><input type="password" name="wachtwherh" size="10"></td></tr>
<tr><td class=zonder>E-mailadres dat bij school bekend is:</td><td class=zonder><input type="email" name="mailadres" size="25"></td></tr>
<tr><td class=zonder colspan=2 align=center><input type="submit" name="aanvraag" value="Vraag aan" ></td></tr>
</form>
<tr><td class=zonder align=center colspan=2><font color=red><? echo $message ?></font></td></tr>
</table>
<?
}
?>
</div>
</div>
</div>
</BODY></HTML>
