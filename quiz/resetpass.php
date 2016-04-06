<?php

require_once 'class.user.php';
$user = new USER();

if(empty($_GET['id']) && empty($_GET['code'])) //if id and code are empty
	$user->redirect('index.php');

if(isset($_GET['id']) && isset($_GET['code'])) //if both are set
{
	$id = base64_decode($_GET['id']); //decode id
	$code = $_GET['code'];

	$stmt = $user->runQuery("SELECT * FROM members WHERE userId=:uid AND tokenCode=:token");
	$stmt->execute(array(":uid"=>$id,":token"=>$code));
	$rows = $stmt->fetch(PDO::FETCH_ASSOC);

	if($stmt->rowCount() == 1) //if userId found
	{
		if(isset($_POST['btn-reset-pass'])) //reset form submited
		{
			$pass = $_POST['pass'];
			$cpass = $_POST['confirm-pass'];

			if($cpass!==$pass) //if mismatch
			{
				$msg = "<div class='alert alert-block'>
						<button class='close' data-dismiss='alert'>&times;</button>
						<strong>Sorry!</strong>  Password Doesn't match.
						</div>";
			}
			else
			{
				$password = md5($cpass);
				$stmt = $user->runQuery("UPDATE members SET pass=:upass WHERE userId=:uid");
				$stmt->execute(array(":upass"=>$password,":uid"=>$rows['userId'])); //update password in database

				$msg = "<div class='alert alert-success'>
						<button class='close' data-dismiss='alert'>&times;</button>
						Password Changed.
						</div>"; //user confirmation message
				header("refresh:5;index.php"); //redirect user to login page
			}
		}
	}
	else
	{
		$msg = "<div class='alert alert-success'>
				<button class='close' data-dismiss='alert'>&times;</button>
				No Account Found, Try again
				</div>"; //invalid reset link

	}


}

?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <title>Password Reset</title>
	<meta charset="utf-8">
    <!-- Bootstrap -->
	<link href="css/bootstrap.min.css" rel="stylesheet">
	<link href="css/bootstrap-responsive.min.css" rel="stylesheet">
	<link href="css/styles.css" rel="stylesheet">
  </head>
  <body id="login">
    <div class="container">
    	<div class='alert alert-success'>
			<strong>Hello !</strong>  <?php echo $rows['fname'] ?> You are here to reset your forgotten password.
		</div>
        <form class="form-signin" method="post">
        <h3 class="form-signin-heading">Password Reset.</h3><hr />
        <?php
			if(isset($msg))
				echo $msg;
		?>
        <input type="password" class="input-block-level" placeholder="New Password" name="pass" required />
        <input type="password" class="input-block-level" placeholder="Confirm New Password" name="confirm-pass" required />
     	<hr />
        <button class="btn btn-large btn-primary" type="submit" name="btn-reset-pass">Reset Your Password</button>

      </form>

    </div>
    <script src="js/jquery-1.12.1.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
  </body>
</html>
