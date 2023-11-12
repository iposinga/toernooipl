<?php

//require_once('dbconfig.php');

class Veld
{	
	private $conn;
	
	public function __construct()
	{
		$database = new Database();
		$db = $database->dbConnection();
		$this->conn = $db;
    }
	
	public function maakVeld($toernooiid, $veld, $plek)
	{
		try
		{
			$stmt = $this->conn->prepare("INSERT INTO velden (toernooi_id, veld, plek) VALUES (:toernid, :veld, :plek)");
			$stmt->bindparam(':toernid', $toernooiid);
			$stmt->bindparam(':veld', $veld);
			$stmt->bindparam(':plek', $plek);	
			$stmt->execute();
		}
		catch(PDOException $e)
		{
			echo $e->getMessage();
		}
	}	
}