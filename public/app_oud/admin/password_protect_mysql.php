<?php

$includespath="../includes/";
include ($includespath."vars.php");
require_once('PasswordHash.php');
###############################################################
# Page Password Protect 2.13
###############################################################
# Visit http://www.zubrag.com/scripts/ for updates
############################################################### 
#
# Usage:
# Set usernames / passwords below between SETTINGS START and SETTINGS END.
# Open it in browser with "help" parameter to get the code
# to add to all files being protected. 
#    Example: password_protect.php?help
# Include protection string which it gave you into every file that needs to be protected
#
# Add following HTML code to your page where you want to have logout link
# <a href="http://www.example.com/path/to/protected/page.php?logout=1">Logout</a>
#
###############################################################

/*
-------------------------------------------------------------------
SAMPLE if you only want to request login and password on login form.
Each row represents different user.

$LOGIN_INFORMATION = array(
  'zubrag' => 'root',
  'test' => 'testpass',
  'admin' => 'passwd'
);

--------------------------------------------------------------------
SAMPLE if you only want to request only password on login form.
Note: only passwords are listed

$LOGIN_INFORMATION = array(
  'root',
  'testpass',
  'passwd'
);

--------------------------------------------------------------------
*/

##################################################################
#  SETTINGS START
##################################################################

// Add login/password pairs below, like described above
// NOTE: all rows except last must have comma "," at the end of line
$loginfill = mysql_query("SELECT * FROM users", $db) or die (mysql_error());
while ($row = mysql_fetch_array($loginfill))
{
$user = $row['user'];
$pass = $row['id'];
$LOGIN_INFORMATION[$user] = $pass;
}
/*
$LOGIN_INFORMATION = array(
  'zubrag' => 'root',
  'admin' => 'juffie'
*/
// request login? true - show login and password boxes, false - password box only
define('USE_USERNAME', true);

// User will be redirected to this page after logout
define('LOGOUT_URL', 'index.php');

// time out after NN minutes of inactivity. Set to 0 to not timeout
define('TIMEOUT_MINUTES', 0);

// This parameter is only useful when TIMEOUT_MINUTES is not zero
// true - timeout time from last activity, false - timeout time from login
define('TIMEOUT_CHECK_ACTIVITY', true);

##################################################################
#  SETTINGS END
##################################################################


///////////////////////////////////////////////////////
// do not change code below
///////////////////////////////////////////////////////

// show usage example
if(isset($_GET['help'])) {
  die('Include following code into every page you would like to protect, at the very beginning (first line):<br>&lt;?php include("' . str_replace('\\','\\\\',__FILE__) . '"); ?&gt;');
}

// timeout in seconds
$timeout = (TIMEOUT_MINUTES == 0 ? 0 : time() + TIMEOUT_MINUTES * 60);

// logout?
if(isset($_GET['logout'])) {
  setcookie("verify", '', $timeout, '/'); // clear password;
  //setcookie("stam", '', $timeout, '/'); // clear stamnummer;
  header('Location: ' . LOGOUT_URL);
  exit();
}

if(!function_exists('showLoginPasswordProtect')) {

// show login form
function showLoginPasswordProtect($error_msg) {
?>
<html>
<head>
  <title>Gebruikersnaam en wachtwoord</title>
  <link href="../includes/toernooiplanner.css" rel="stylesheet" type="text/css"> 
  <META HTTP-EQUIV="CACHE-CONTROL" CONTENT="NO-CACHE">
  <META HTTP-EQUIV="PRAGMA" CONTENT="NO-CACHE">
</head>
<body background="../backgrounds/background2.jpg">
<div id="boxContainerContainer">
    <div id="boxContainer">
    <div id="box1">
    <img src="../includes/LOGOE.png"alt="Toernooiplanner" style= "height: 150px" align= "middle" >
    <nav>
    <ul>
		<li><a href="http://toernooiplanner.dollardinformatica103.nl">OVERZICHT</a></li>
	</ul>
    </nav>
    </div>
<div id="box2">
  <form method="post">
    <b>Voer je gebruikersnaam en wachtwoord in om deze pagina te kunnen bezoeken<br>of vraag <a href="aanvraag.php">hier</a> je account aan!</b><br>
    <font color="red"><?php echo $error_msg; ?></font><br/>
<?php if (USE_USERNAME) echo 'Gebruikersnaam:<br /><input type="input" name="access_login" /><br />Wachtwoord:<br />'; ?>
    <input type="password" name="access_password" /><p></p><input type="submit" name="Submit" value="Verzend" />
  </form>
  </div>
  <?
include('footer.php');
?>

<?php
  // stop at this point
  die();
}
}
	
// user provided password
if (isset($_POST['access_password'])) {

  $login = isset($_POST['access_login']) ? $_POST['access_login'] : '';
  $pass = $_POST['access_password'];
  //nieuw
  $uservraag = mysql_query("SELECT pass, userid FROM users WHERE user='$login'", $db) or die (mysql_error());
  if (mysql_num_rows($uservraag) > 0)
  {     
      //echo "yes<br>";
      $row = mysql_fetch_array($uservraag);
      $passtocheck = $row['pass'];
      //echo $passtocheck."<br>";
      $result = validate_password($pass, $passtocheck);
      //echo $result."<br>";
      //geef $pass de waarde van id om de cookie mee te maken en te verifieren
      $pass = $row['id'];
  }
  else
  $result = 0;
  if (!$result)
  //eindnieuw
  /*if (!USE_USERNAME && !in_array($pass, $LOGIN_INFORMATION)
  || (USE_USERNAME && ( !array_key_exists($login, $LOGIN_INFORMATION) || $LOGIN_INFORMATION[$login] != $pass ) ) 
  ) */
  { 
    //echo "niet ingelogd";  
    showLoginPasswordProtect("Het wachtwoord is onjuist.");
  }
  else {
    //echo "ingelogd";  
    // set cookie if password was validated; de cookie wordt gefabriceerd uit de gebruikersnaam en de id (NIET de pass!)
    setcookie("verify", md5($login.'%'.$pass), $timeout, '/');
    
    // zelf toegevoegd:
    //echo $row['userid'];
    setcookie("userid", $row['userid']);
    
    // Some programs (like Form1 Bilder) check $_POST array to see if parameters passed
    // So need to clear password protector variables
    unset($_POST['access_login']);
    unset($_POST['access_password']);
    unset($_POST['Submit']);
    
    //volgende 3 regels zelf toegevoegd omdat de cookie met het stamnummer pas set is bij de volgende load
    $locstring = $_SERVER['PHP_SELF'];
    header("Location: $locstring");
    exit;
  }

}

else {

  // check if password cookie is set
  if (!isset($_COOKIE['verify'])) {
    showLoginPasswordProtect("");
  }

  // check if cookie is good
  $found = false;
  foreach($LOGIN_INFORMATION as $key=>$val) {
    $lp = (USE_USERNAME ? $key : '') .'%'.$val;
    if ($_COOKIE['verify'] == md5($lp)) {
      $found = true;
      // prolong timeout
      if (TIMEOUT_CHECK_ACTIVITY) {
        setcookie("verify", md5($lp), $timeout, '/');
      }
      break;
    }
  }
  //nieuw door OsI
  //$found = true;
  //einde nieuw door OsI
  if (!$found) {
    showLoginPasswordProtect("");
  }

}


?>
