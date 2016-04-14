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
    <title>Reset Password</title>
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
            Lobibox.alert(t, {
                msg: m
            });
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
                        <div style="text-align: center; font-size:25px" class='alert alert-info'>
                            Reset Password
                        </div>
                        <br />
                        <form class="form-horizontal" role="form" method="post">
                            <div class="form-group">
                                <div class="col-sm-offset-2 col-sm-8">
                                    <input type="password" class="form-control" name="pass" id="pass" placeholder="Enter Password" required>
                                </div>
                            </div>
                            <br/>
                            <div class="form-group">
                                <div class="col-sm-offset-2 col-sm-8">
                                    <input type="password" class="form-control" name="confirm-pass" id="confirm-pass" placeholder="Confirm Password" required>
                                </div>
                            </div>
                            <br />
                            <div class="form-group">
                                <div class="col-sm-offset-2 col-sm-8">
                                    <input type="submit" name="btn-reset-pass" value="Reset Password" class="btn btn-primary btn-block">
                                </div>
                            </div>
                            <br/>
                        </form>
                    </div>
                </div>
            </div>
            <script>
                var num;
                var temp = 0;
                var speed = 3000; /* this is set for 5 seconds, edit value to suit requirements */
                var preloads = [];

                /* add any number of images here */

                preload(
                    'images/bg1.jpg',
                    'images/bg2.jpg',
                    'images/bg3.jpg',
                    'images/bg4.jpg',
                    'images/bg5.jpg'
                );

                function preload() {

                    for (var c = 0; c < arguments.length; c++) {
                        preloads[preloads.length] = new Image();
                        preloads[preloads.length - 1].src = arguments[c];
                    }
                }

                function rotateImages() {
                    num = Math.floor(Math.random() * preloads.length);
                    if (num == temp) {
                        rotateImages();
                    } else {
                        document.body.style.backgroundImage = 'url(' + preloads[num].src + ')';
                        temp = num;

                        setTimeout(function() {
                            rotateImages()
                        }, speed);
                    }
                }

                if (window.addEventListener) {
                    window.addEventListener('load', rotateImages, false);
                } else {
                    if (window.attachEvent) {
                        window.attachEvent('onload', rotateImages);
                    }
                }
            </script>
</body>

</html>
