<?php

session_start();
require_once 'class.user.php';

$reg_user = new USER();

if($reg_user->is_logged_in()) //if user is already logged in then redirect to homepage
	$reg_user->redirect('home.php');

if(isset($_POST['btn-signup'])) //when sign up form is submitted
{
	$id = strtoupper(trim($_POST['id']));
	$fname = strtoupper(trim($_POST['fname']));
	$lname = strtoupper(trim($_POST['lname']));
	$email = strtolower(trim($_POST['email']));
	$pass = trim($_POST['pass']);
	$type = trim($_POST['type']);
	$code = md5(uniqid(rand())); //unique token code

	$stmt = $reg_user->runQuery("SELECT * FROM members WHERE email=:email_id");
	$stmt->execute(array(":email_id"=>$email));
	$row = $stmt->fetch(PDO::FETCH_ASSOC);

	if($stmt->rowCount() > 0)
	{
		$msg = "
		      <div class='alert alert-error'>
				<button class='close' data-dismiss='alert'>&times;</button>
					<strong>Sorry !</strong>  email allready exists , Please Try another one
			  </div>
			  ";
	}
	else
	{
		if($reg_user->register($id,$fname,$lname,$email,$pass,$type,$code))
		{
			$id = $reg_user->lasdID();
			$key = base64_encode($id); //encode the userId
			$id = $key;

			//message for sign up
			$message = "
						Hello $fname,
						<br /><br />
						Welcome to Quiz-It!<br/>
						To complete your registration  please , just click following link<br/>
						<br />
						<a href='http://localhost/quiz/verify.php?id=$id&code=$code'>Click HERE to Activate :)</a>
						<br /><br />
						Thanks,";

			$subject = "Confirm Registration";

			$reg_user->send_mail($email,$message,$subject);
			$msg = "
					<div class='alert alert-success'>
						<button class='close' data-dismiss='alert'>&times;</button>
						<strong>Success!</strong>  We've sent an email to $email.
                    Please click on the confirmation link in the email to create your account.
			  		</div>
					"; //confirmation message to show user
		}
		else //if some error occurs while executing query
			echo "ERROR!";
	}
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<title>Signup | Quiz-It</title>
	<meta charset="utf-8">
	<!-- Bootstrap -->
	<link href="bootstrap/css/bootstrap.min.css" rel="stylesheet" media="screen">
	<link href="bootstrap/css/bootstrap-responsive.min.css" rel="stylesheet" media="screen">
	<link href="assets/styles.css" rel="stylesheet" media="screen">
	<script src="js/vendor/modernizr-2.6.2-respond-1.1.0.min.js"></script>
</head>
<body id="login">
	<div class="container">
		<?php if(isset($msg)) echo $msg;  ?>
		<form class="form-signin" method="post">
			<h2 class="form-signin-heading">Sign Up</h2><hr />
			<input type="text" class="input-block-level" placeholder="ID" name="id" required />
			<input type="text" class="input-block-level" placeholder="First Name" name="fname" required />
			<input type="text" class="input-block-level" placeholder="Last Name" name="lname" required />
			<input type="email" class="input-block-level" placeholder="Email address" name="email" required />
			<input type="password" class="input-block-level" placeholder="Password" name="pass" required />
			<label><input type="radio" name="type" value='T'/>Teacher</label>
			<label><input type="radio" name="type" checked="checked" value='S'/>Student</label>
			<hr />
			<button class="btn btn-large btn-primary" type="submit" name="btn-signup">Sign Up</button>
			<a href="index.php" style="float:right;" class="btn btn-large">Sign In</a>
		</form>
    </div> <!-- /container -->
    <script src="vendors/jquery-1.9.1.min.js"></script>
    <script src="bootstrap/js/bootstrap.min.js"></script>
  </body>
</html>
