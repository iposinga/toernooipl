<?php
class Database
{   
    private $host = "localhost";
    //host = web0120.zxcs.nl
    //admin-gebruiker
    //private $username = "u24647p18883_admin";
    //private $password = "6aY-u7n-EGs-YVY";
    private $db_name = "u24647p26455_toernooiplan";
    private $username = "u24647p26455_toernooiplan";
    private $password = "osirules";
    public $conn;
    public $link;
     
    public function dbConnection()
	{
     
	    $this->conn = null;    
        try
		{
            $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name . ";charset=utf8", $this->username, $this->password);
			$this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);	
        }
		catch(PDOException $exception)
		{
            echo "Connection error: " . $exception->getMessage();
        }
         
        return $this->conn;
    }
    
    /*public function getLink()
	{
		$this->link = mysqli_connect($this->host, $this->username, $this->password, $this->db_name) or die ('There was a problem connecting to the database');
		mysqli_set_charset($this->link, "utf8");
		return $this->link;
	}*/
}
?>