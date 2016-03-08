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

	$stmt = $user->runQuery("SELECT userId,verified FROM members WHERE userId=:uID AND tokenCode=:code LIMIT 1");
	$stmt->execute(array(":uID"=>$id,":code"=>$code));
	$row=$stmt->fetch(PDO::FETCH_ASSOC);

	if($stmt->rowCount() > 0)
	{
		if($row['verified']==$statusN)
		{
			$stmt = $user->runQuery("UPDATE members SET verified=:status WHERE userId=:uID");
			$stmt->bindparam(":status",$statusY); //set status as Y
			$stmt->bindparam(":uID",$id);
			$stmt->execute();

			$msg = "
		           <div class='alert alert-success'>
				   <button data-dismiss='alert'>&times;</button>
					  <strong>WoW !</strong>  Your Account is Now Activated : <a href='index.php'>Login here</a>
			       </div>
			       ";
		}
		else
		{
			$msg = "
			   <div class='alert alert-success'>
			   <button class='close' data-dismiss='alert'>&times;</button>
				  <strong>WoW !</strong>  Your Account is Already Activated : <a href='index.php'>Login here</a>
			   </div>
			   ";
	   }
	}
	else
	{
		$msg = "
			   <div class='alert alert-'>
			   <button class='close' data-dismiss='alert'>&times;</button>
				  <strong>sorry !</strong>  Your Account is already Activated : <a href='index.php'>Login here</a>
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
	<link href="css/bootstrap.min.css" rel="stylesheet">
	<link href="css/styles.css" rel="stylesheet">
</head>
<body>
	<div class="container">
		<?php if(isset($msg)) { echo $msg; } ?>
	</div>
	<script src="js/jquery-1.12.1.min.js"></script>
	<script src="js/bootstrap.min.js"></script>
</body>
</html>
