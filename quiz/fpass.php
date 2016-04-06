<?php

session_start();
require_once 'class.user.php';

$user = new USER();

if($user->is_logged_in())
	$user->redirect('home.php');

if(isset($_POST['btn-submit']))
{
	$email = $_POST['txtemail'];

	$stmt = $user->runQuery("SELECT * FROM members WHERE email=:email LIMIT 1");
	$stmt->execute(array(":email"=>$email));
	$row = $stmt->fetch(PDO::FETCH_ASSOC);

	if($stmt->rowCount() == 1) //if email id found in database
	{
		$id = base64_encode($row['userId']);
		$code = md5(uniqid(rand()));

		$stmt = $user->runQuery("UPDATE members SET tokenCode=:token WHERE email=:email"); //generate new token
		$stmt->execute(array(":token"=>$code,"email"=>$email));

		$u = $row['fname'];
		//password reset message
		$message= "
				   Hello $u,
				   <br /><br />
				   We got requested to reset your password, if you do this then just click the following link to reset your password, if not just ignore                   this email,
				   <br /><br />
				   Click Following Link To Reset Your Password
				   <br /><br />
				   <a href='http://localhost/quiz/resetpass.php?id=$id&code=$code'>Click here to reset your password</a>
				   <br /><br />
				   thank you :)
				   ";
		$subject = "Password Reset";

		$user->send_mail($email,$message,$subject);

		$msg = "<div class='alert alert-success'>
					<button class='close' data-dismiss='alert'>&times;</button>
					We've sent an email to $email.
                    Please click on the password reset link in the email to generate new password.
			  	</div>"; //message to be displayed to user
	}
	else
	{
		$msg = "<div class='alert alert-danger'>
					<button class='close' data-dismiss='alert'>&times;</button>
					<strong>Sorry!</strong>  this email not found.
			    </div>"; //email id not found
	}
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<title>Forgot Password</title>
	<meta charset="utf-8">
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/bootstrap-responsive.min.css" rel="stylesheet">
    <link href="css/styles.css" rel="stylesheet">
</head>
<body id="login">
	<div class="container">
      <form class="form-signin" method="post">
        <h2 class="form-signin-heading">Forgot Password</h2><hr />
    	<?php
			if(isset($msg))
				echo $msg;
			else
			{
		?>
          		<div class='alert alert-info'>
					Please enter your email address. You will receive a link to create a new password via email.!
				</div>
        <?php
			}
		?>
        <input type="email" class="input-block-level" placeholder="Email address" name="txtemail" required />
 		<hr />
        <button class="btn btn-danger btn-primary" type="submit" name="btn-submit">Generate new Password</button>
		<a href="index.php" style="float:right;"class="btn ">Sign In</a><hr />
      </form>
    </div>
    <script src="js/jquery-1.12.1.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
</body>
</html>
