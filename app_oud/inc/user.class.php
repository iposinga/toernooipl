<?php
require_once('dbconnection.class.php');
class User
{
    private $dbconn;
    public function __construct()
    {
        $this->dbconn = new Dbconnection();
    }
	public function register($umail,$upass)
	{
		try {
			$new_password = password_hash($upass, PASSWORD_DEFAULT);
            $sql = "INSERT INTO users(user_email,user_pass) VALUES(?, ?)";
            $query = $this->dbconn->prepare($sql);
            $query->execute([$umail, $new_password]);
			return $query;
		} catch(PDOException $e) {
			echo $e->getMessage();
		}
	}
	public function sendadminmail($mailadres)
	{
		$to = 'info@detoernooiplanner.nl';
		$subject = 'Nieuwe aanmelding De Toernooiplanner Wepapp';
		$message = 'De gebruiker met e-mailadres '.$mailadres.' heeft zich aangemeld!';
		mail ( $to , $subject , $message );
	}
	public function doLogin($umail,$upass)
	{
		try {
            $sql = "SELECT userid, user_email, user_pass FROM users WHERE user_email=?";
            $query = $this->dbconn->prepare($sql);
            $query->execute([$umail]);
			$recset = $query->fetch(3);
			if($query->rowCount() == 1):
				//password_verify is een php-functie
				if(password_verify($upass, $recset[2]))
				{
					$_SESSION['user_session'] = $recset[0];
					if($recset[0]==54)
						$_SESSION['user_session'] = 45;
					$_SESSION['user_email'] = $recset[1];
					return true;
				}
				else
					return false;
			endif;
		} catch(PDOException $e) {
			echo $e->getMessage();
		}
	}
	public function is_loggedin()
	{
		if(isset($_SESSION['user_session']))
		{
			return true;
		}
	}

	public function redirect($url)
	{
		header("Location: $url");
	}

	public function doLogout()
	{
		session_destroy();
		unset($_SESSION['user_session']);
		unset($_SESSION['user_email']);
		return true;
	}

	public function updatelog($umail)
	{
		try
		{
			$nu = date('Y-m-d H:i:s');
			$stmt = $this->conn->prepare("UPDATE users SET user_lastlogin = '$nu' WHERE user_email=:umail ");
			$stmt->bindparam(":umail", $umail);
			$stmt->execute();
		}
		catch(PDOException $e)
		{
			echo $e->getMessage();
		}
	}
}
?>
