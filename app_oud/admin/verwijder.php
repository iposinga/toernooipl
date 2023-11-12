<?
require_once('../session.php');
require_once('admin_inc/toernooi.class.php');

$deltoernooi=new Toernooi();
$deltoernooi->deleteToernooi($_POST['toernooi']);

?>
<html>
  <body
   onLoad="opener.location.href=opener.location.href; window.close();">
  </body>
</html>
