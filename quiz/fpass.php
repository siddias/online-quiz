<?php

session_start();
require_once 'class.user.php';

$user = new USER();

if($user->is_logged_in())
	$user->redirect('home.php');

if(isset($_POST['reset']))
{
	$email = $_POST['email'];

	$stmt = $user->runQuery("SELECT * FROM members WHERE email=:email LIMIT 1");
	$stmt->execute(array(":email"=>$email));
	$row = $stmt->fetch(PDO::FETCH_ASSOC);

	if($stmt->rowCount() == 1) //if email id found in database
	{
		if($row['verified']=='Y'){
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

			try{
				$user->send_mail($email,$message,$subject);
				$msg = "We've sent an email to $email.Please click on the password reset link in the email to generate new password."; //message to be displayed to user
				$mType = "success";
			}catch(phpmailerException $e){
				$msg = "There was an error sending email. Please try again";
				$mType = "error";
			}
		}
		else {
			$msg = "Your Account has not been verified! Cannot reset password"; //message to be displayed to user
			$mType = "error";
		}
	}
	else{
		$msg = "Email id is not registered on Quiz-It!";
		$mType = "error";
	}
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Forgot Password</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="lib/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/index.css">
    <link rel="stylesheet" href="lib/lobibox/css/lobibox.min.css" />
     <script src="lib/jquery/jquery.min.js"></script>
     <script src="lib/lobibox/js/lobibox.min.js"></script>
     <script src="lib/lobibox/js/messageboxes.min.js"></script>
    <script src="lib/bootstrap/js/bootstrap.min.js"></script>
</head>
<body>
    <?php
        if(isset($msg))
        {
    ?>
        <script>
            var t = "<?php echo $mType?>";
            var m = "<?php echo $msg?>";
            Lobibox.alert(t, { msg: m});
        </script>
    <?php
    }
    ?>
    <div class="logo">
        <img alt="Quiz-It" src="images/Qi-logo.png">
    </div>
    <div class="bound"></div>
    <div class="container" style="width:40%;">
        <div style="position:relative;z-index:1;background-color:#FFFFFF;border-bottom-right-radius:5px;border-bottom-left-radius:5px;border:3px solid #5795db;">
			<div id="signup">
				<div style="text-align: center; font-size: 20px" class='alert alert-info'>
							Please enter your email address. You will receive a link to create a new password via email.
				</div>
				<br />
                <form class="form-horizontal" role="form" method="post">
                    <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-8">
                            <input type="email" class="form-control" name="email" placeholder="Enter email"required>
                        </div>
                    </div>
				<br/>
                    <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-8">
                            <input type="submit" name="reset" value="Reset Password" class="btn btn-primary btn-block">
                        </div>
                    </div>
                    <br/>
                </form>
            </div>
        </div>
    </div>
    <script>
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
