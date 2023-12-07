<?php
ini_set('display_errors',1);
error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING); //toon alle fouten behalve notices en warnings
setlocale(LC_MONETARY, 'nl_NL.UTF-8');
setlocale(LC_TIME, 'nl_NL.UTF-8');
date_default_timezone_set('Europe/Amsterdam');
header("Content-Type: text/html; charset=UTF-8");
?>
