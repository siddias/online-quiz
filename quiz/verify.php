<?php

require_once 'class.user.php';
$user = new USER();

if(empty($_GET['id']) && empty($_GET['code'])) //invalid verification page
	$user->redirect('index.php');

if(isset($_GET['id']) && isset($_GET['code']))
{
	$id = base64_decode($_GET['id']); //decode userID
	$code = $_GET['code'];

	$statusY = "Y";
	$statusN = "N";

	$stmt = $user->runQuery("SELECT userID,userStatus FROM tbl_users WHERE userID=:uID AND tokenCode=:code LIMIT 1");
	$stmt->execute(array(":uID"=>$id,":code"=>$code));
	$row=$stmt->fetch(PDO::FETCH_ASSOC);

	if($stmt->rowCount() > 0)
	{
		if($row['userStatus']==$statusN)
		{
			$stmt = $user->runQuery("UPDATE tbl_users SET userStatus=:status WHERE userID=:uID");
			$stmt->bindparam(":status",$statusY); //set status as Y
			$stmt->bindparam(":uID",$id);
			$stmt->execute();

			$msg = "
		           <div>
				   <button data-dismiss='alert'>&times;</button>
					  <strong>WoW !</strong>  Your Account is Now Activated : <a href='index.php'>Login here</a>
			       </div>
			       ";
		}
		$msg = "
			   <div class='alert alert-success'>
			   <button class='close' data-dismiss='alert'>&times;</button>
				  <strong>WoW !</strong>  Your Account is Now Activated : <a href='index.php'>Login here</a>
			   </div>
			   ";
	}
	else
	{
		$msg = "
			   <div class='alert alert-error'>
			   <button class='close' data-dismiss='alert'>&times;</button>
				  <strong>sorry !</strong>  Your Account is allready Activated : <a href='index.php'>Login here</a>
			   </div>
			   ";
	}
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
	<title>Confirm Registration</title>
	<meta charset="utf-8">
	<!-- Bootstrap -->
	<link href="bootstrap/css/bootstrap.min.css" rel="stylesheet" media="screen">
	<link href="bootstrap/css/bootstrap-responsive.min.css" rel="stylesheet" media="screen">
	<link href="assets/styles.css" rel="stylesheet" media="screen">
	<script src="js/vendor/modernizr-2.6.2-respond-1.1.0.min.js"></script>
</head>
<body id="login">
	<div class="container">
		<?php if(isset($msg)) { echo $msg; } ?>
	</div> <!-- /container -->
	<script src="vendors/jquery-1.9.1.min.js"></script>
	<script src="bootstrap/js/bootstrap.min.js"></script>
</body>
</html>
