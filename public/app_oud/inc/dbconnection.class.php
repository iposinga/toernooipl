<?php
class Dbconnection extends PDO
{
    private $host = "localhost";
    private $db_name = "u24647p26455_toernooiplan";
    private $username = "u24647p26455_toernooiplan";
    private $password = "osirules";
    
    public function __construct()
    {
        parent::__construct("mysql:host=" . $this->host . ";dbname=" . $this->db_name . ";charset=utf8", $this->username, $this->password);
        $this->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    }

}