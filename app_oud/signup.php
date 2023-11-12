<?php
session_start();
require_once('inc/user.class.php');
$user = new USER();

if($user->is_loggedin()!="")
{
	$user->redirect('index.php');
}

if(isset($_POST['btn-signup']))
{
	$umail = strip_tags($_POST['txt_umail']);
	$upass = strip_tags($_POST['txt_upass']);

	if($umail=="")	{
		$error = "Voer uw e-mailadres in !";
	}
	else if(!filter_var($umail, FILTER_VALIDATE_EMAIL))	{
	    $error = 'Voer een geldig e-mailadres in !';
	}
	else if($upass=="")	{
		$error = "Voer een wachtwoord in !";
	}
	else if(strlen($upass) < 6){
		$error = "Het wachtwoord moet uit minstens 6 karakters bestaan !";
	}
	else
	{
		try
		{
			$stmt = $user->runQuery("SELECT user_email FROM users WHERE user_email=:umail");
			$stmt->execute(array(':umail'=>$umail));
			$row=$stmt->fetch(PDO::FETCH_ASSOC);
			if($row['user_email']==$umail) {
				$error = "Sorry, dit e-mailadres is al in gebruik !";
			}
			else
			{
				if($user->register($umail,$upass))
				{
					$user->redirect('signup.php?joined');
					$user->sendadminmail($umail);
				}
				else
					$error = "Sorry, je bent niet gerechtigd je hierbij aan te melden! Als je vindt dat jij je wel zou moeten kunnen aanmelden, wend je tot de <a href='mailto:webmaster@scloppersum.nl'>webmaster</a>.";
			}
		}
		catch(PDOException $e)
		{
			echo $e->getMessage();
		}
	}
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>De Toernooiplanner WebApp: Aanmelden</title>
<!-- Bootstrap core CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
<!-- Custom styles for this template -->
<link href="css/signin.css" rel="stylesheet">
</head>
<body class="text-center">


    	<form class="form-signin" method="post">
      <img class="mb-4" src="images/detoernooiplanner.png" alt="">
      <h1 class="h3 mb-3 font-weight-normal">Aanmelden</h1>
       <?php
			if(isset($error))
			{
				?>
                <div class="alert alert-danger">
                <?php echo $error ?>
                </div>
                <?php
			}
			else if(isset($_GET['joined']))
			{
				 ?>
                 <div class="alert alert-info">
                      U bent met succes geregistreerd!
                 </div>
                 <label><a href="index.php">Log in</a></label>
                 <?php
			}
			if (!isset($_GET['joined']))
			{
		?>
      <label for="inputEmail" class="sr-only">E-mailadres</label>
      <input type="email" id="inputEmail" name="txt_umail" class="form-control" placeholder="E-mailadres" required autofocus>
      <label for="inputPassword" class="sr-only">Wachtwoord</label>
      <input type="password" id="inputPassword" name="txt_upass" class="form-control" placeholder="Wachtwoord van minimaal 6 karakters" required>
      <!--<div class="checkbox mb-3">
        <label>
          <input type="checkbox" value="remember-me"> Onthoud mij
        </label>
      </div>-->
      <button name="btn-signup" class="btn btn-lg btn-primary btn-block" type="submit">Meld aan</button>
      <div style="padding-top: 10px">Ik heb al een account!&nbsp;&nbsp;&nbsp;<a href="login.php">Log in</a></div>
      <?php } ?>
      <p class="mt-5 mb-3 text-muted">&copy; 2018</p>
    </form>


</body>
</html>
