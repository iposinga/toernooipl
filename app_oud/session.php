<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING); //toon alle fouten behalve notices en warnings	
setlocale(LC_MONETARY, 'nl_NL.UTF-8');	
setlocale(LC_TIME, 'nl_NL.UTF-8');
date_default_timezone_set('Europe/Amsterdam');	

session_start();

/*require_once ('includes/user.class.php');
$session = new User();

// if user session is not active(not loggedin) this page will help 'home.php and profile.php' to redirect to login page
// put this file within secured pages that users (users can't access without login)
if(!$session->is_loggedin())
{
	// session no set redirects to login page
	$session->redirect('index.php');
}*/

?>