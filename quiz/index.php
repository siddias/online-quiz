<?php

session_start(); //starts a new session, session is started so multiple pages on the server can share data
require_once 'class.user.php'; //if not found then fatal error. Stricter than include

$user = new USER(); //USER OBJECT created

if($user->is_logged_in()) //check if user is logged in. if yes redirect him to homepage
    $user->redirect('home.php');

if(isset($_POST['login']))
{
    $email = trim($_POST['email']); //remove spaces from either end
    $upass = trim($_POST['pwd']);
    if($user->login($email,$upass)); //checks valid user login. if yes then display homepage
        $user->redirect('home.php');
}

if(isset($_POST['signup'])) //when sign up form is submitted
{
	$id = strtoupper(trim($_POST['id']));
	$fname = strtoupper(trim($_POST['fname']));
	$lname = strtoupper(trim($_POST['lname']));
	$email = strtolower(trim($_POST['email']));
	$pass = trim($_POST['pwd']);
	$type = trim($_POST['type']);
	$code = md5(uniqid(rand())); //unique token code

	$stmt = $user->runQuery("SELECT * FROM members WHERE email=:email_id");
	$stmt->execute(array(":email_id"=>$email));
	$row = $stmt->fetch(PDO::FETCH_ASSOC);

	if($stmt->rowCount() > 0)
	{
		$msg = "
		      <div class='alert alert-warning'>
				<button class='close' data-dismiss='alert'>&times;</button>
					<strong>Sorry !</strong>  email allready exists , Please Try another one
			  </div>
			  ";
	}
	else
	{
		if($user->register($id,$fname,$lname,$email,$pass,$type,$code))
		{
			$id = $user->lasdID();
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

			$user->send_mail($email,$message,$subject);
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
if(isset($_GET['inactive'])) //redirected to index.php because account not activated
{
    $msg="<div class='alert alert-danger'>
        <button class='close' data-dismiss='alert'>&times;</button>
        <strong>Sorry!</strong> This Account is not Activated Go to your Inbox and Activate it.
    </div>";
}
if(isset($_GET['error'])) //redirected to index.php because of invalid sign in credentials
{
  $msg="<div class='alert alert-danger'>
      <button class='close' data-dismiss='alert' >&times;</button>
      <strong>Wrong Details!</strong>
      </div>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Login | Quiz-It</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/index.css">
</head>
<body>
  <div class="logo">
    <img alt="Quiz-It" src="images/Qi-logo.png">
  </div>
    <div class="bound"></div>
    <div class="container" style="width:40%;">
        <?php if(isset($msg)) echo $msg;  ?>
        <ul class="nav nav-tabs">
            <li class="active text-center" style="width:50%;"><a data-toggle="tab" href="#login" style="border-width:3px 0 0 3px;border-color:#5795db">LOGIN</a></li>
            <li class="text-center" style="width:50%;"><a data-toggle="tab" href="#signup" style="border-width:3px 3px 0 0;border-color:#5795db">SIGN UP</a></li>
        </ul>

        <div class="tab-content" style="position:relative;z-index:1;background-color:#FFFFFF;border-bottom-right-radius:5px;border-bottom-left-radius:5px;border:3px solid #5795db;border-top-style: hidden">
            <div id="login" class="tab-pane fade in active" >
                <form class="form-horizontal" role="form" method="post" >
                    <br/>
                    <div class="form-group"  >
                        <div class="col-sm-offset-2 col-sm-8">
                            <input type="email" class="form-control" name="email" placeholder="Enter email" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-8">
                            <input type="password" class="form-control" name="pwd" placeholder="Enter password" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-offset-6 col-sm-6">
                            <a href="fpass.php">Forgot Password?</a>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-8">
                            <input type="submit" name="login" value="Log In" class="btn btn-primary btn-block">
                        </div>
                    </div>
                    <br/>
                </form>
            </div>

            <div id="signup" class="tab-pane fade">
                <form class="form-horizontal" role="form" method="post">
                    <br/>
                    <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-8">
                            <input type="text" class="form-control" name="fname" placeholder="Enter First Name" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-8">
                            <input type="text" class="form-control" name="lname" placeholder="Enter Last Name" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-8">
                            <input type="email" class="form-control" name="email" placeholder="Enter email"required>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-8">
                            <input type="password" class="form-control" name="pwd" placeholder="Enter password"  required>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-offset-3 col-sm-4">
                            <label class="radio-inline text-center">
                                <input type="radio" name="type" value="T">Professor</label>
                        </div>
                        <div class="col-sm-4">
                            <label class="radio-inline">
                                <input type="radio" name="type" checked="checked" value="S">Student</label>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-8">
                            <input type="text" class="form-control" name="id" placeholder="Enter USN/EmployeeID">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-8">
                            <input type="submit" name="signup" value="Sign Up" class="btn btn-primary btn-block">
                        </div>
                    </div>
                    <br/>
                </form>
            </div>
        </div>
    </div>
    <script src="js/jquery-1.12.1.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script>
        if(typeof window.history.pushState == 'function') {
            window.history.pushState({}, "Hide", "index.php");
        }
        var num;
        var temp=0;
        var speed=3000; /* this is set for 5 seconds, edit value to suit requirements */
        var preloads=[];

     /* add any number of images here */

     preload(
             'images/bg1.jpg',
             'images/bg2.jpg',
             'images/bg3.jpg',
             'images/bg4.jpg',
             'images/bg5.jpg'
            );

     function preload(){

     for(var c=0;c<arguments.length;c++) {
        preloads[preloads.length]=new Image();
        preloads[preloads.length-1].src=arguments[c];
       }
      }

     function rotateImages() {
        num=Math.floor(Math.random()*preloads.length);
     if(num==temp){
        rotateImages();
      }
     else {
        document.body.style.backgroundImage='url('+preloads[num].src+')';
        temp=num;

     setTimeout(function(){rotateImages()},speed);
       }
      }

     if(window.addEventListener){
        window.addEventListener('load',rotateImages,false);
      }
     else {
     if(window.attachEvent){
        window.attachEvent('onload',rotateImages);
       }
      }
    </script>
</body>

</html>
