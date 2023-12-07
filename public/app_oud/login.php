<?php
//date_default_timezone_set('Europe/Amsterdam');
//session_start();
require_once('session.php');
require_once("inc/user.class.php");
$login = new User();
if($login->is_loggedin())
{
	//echo "ben ingelogd";
	$login->redirect('admin/index.php');
}

if(isset($_POST['btn-login']))
{
	//echo "submitted";
	$umail = strip_tags($_POST['inputEmail']);
	$upass = strip_tags($_POST['inputPassword']);
	//echo $umail;
	//echo $upass;
	if($login->doLogin($umail,$upass))
	{
		$login->redirect('admin/index.php');
	}
	else
	{
		$error = "De inlogggegevens klopten niet!";
	}
}
?>
<html lang="nl">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="voetbal in Loppersum eo">
    <meta name="author" content="Ids Osinga">

    <title>De Toernooiplanner WebApp: Login</title>

    <!-- Bootstrap core CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">

    <!-- Custom styles for this template -->
    <link href="css/signin.css" rel="stylesheet">
  </head>

  <body class="text-center">

    <form class="form-signin" method="post" id="login-form">
      <img class="mb-4" src="images/detoernooiplanner.jpg" alt="" width="100%">
      <h1 class="h3 mb-3 font-weight-normal">Log in</h1>
       <?php
			if(isset($error))
			{
				?>
                <div class="alert alert-danger">
                <?php echo $error ?>
                </div>
                <?php
			}
		?>
      <label for="inputEmail" class="sr-only">E-mailadres</label>
      <input type="text" id="inputEmail" name="inputEmail" class="form-control" placeholder="Email address" required autofocus>
      <label for="inputPassword" class="sr-only">Wachtwoord</label>
      <input type="password" id="inputPassword" name="inputPassword" class="form-control" placeholder="Password" required>
      <!--<div class="checkbox mb-3">
        <label>
          <input type="checkbox" value="remember-me"> Onthoud mij
        </label>
      </div>-->
      <button name="btn-login" class="btn btn-lg btn-primary btn-block" type="submit">Log in</button>
      <div style="padding-top: 10px">Ik heb nog geen account!&nbsp;&nbsp;&nbsp;<a href="signup.php">Meld je aan</a></div>
      <div style="padding-top: 10px"><a href="index.php">Ga terug naar detoernooiplanner.nl</a></div>
      <p class="mt-5 mb-3 text-muted">&copy; 2018</p>
    </form>
    <!--<label>Ik heb nog geen account!&nbsp;&nbsp;&nbsp;</label><label>&nbsp;&nbsp;&nbsp;<a href="signup.php">Meld je aan</a></label>-->
  </body>
</html>
