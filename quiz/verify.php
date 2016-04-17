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

	$stmt = $user->runQuery("SELECT userId,userType,verified FROM members WHERE userId=:uID AND tokenCode=:code LIMIT 1");
	$stmt->execute(array(":uID"=>$id,":code"=>$code));
	$row=$stmt->fetch(PDO::FETCH_ASSOC);

	if($stmt->rowCount() == 1)
	{
		if($row['verified']==$statusN)
		{
			$stmt = $user->runQuery("UPDATE members SET verified=:status WHERE userId=:uID");
			$stmt->bindparam(":status",$statusY); //set status as Y
			$stmt->bindparam(":uID",$id);
			$stmt->execute();

			$msg = "Your Account is Now Activated!";
			$mType = "success";

			if($row['userType']=='S'){
				$stmt = $user->runQuery("CREATE TABLE past_quiz".$id."(id INT(10) NOT NULL AUTO_INCREMENT,quizId INT(10) NOT NULL,score INT(10) NOT NULL,submitDate DATE,PRIMARY KEY(id),FOREIGN KEY(quizId) REFERENCES quizlist(quizId) ON DELETE CASCADE)ENGINE = InnoDB");
				$stmt->execute();
				$stmt = $user->runQuery("CREATE TABLE live_quiz".$id."(id INT(10) NOT NULL AUTO_INCREMENT,quizId INT(10) NOT NULL,PRIMARY KEY(id),FOREIGN KEY(quizId) REFERENCES quizlist(quizId) ON DELETE CASCADE)ENGINE = InnoDB");
				$stmt->execute();
			}
			else {
				$stmt = $user->runQuery("CREATE TABLE past_quiz".$id."(id INT(10) NOT NULL AUTO_INCREMENT,quizId INT(10) NOT NULL,numSubmissions INT(10) NOT NULL DEFAULT 0,scoreAvg DECIMAL(4,2) NOT NULL DEFAULT 0,PRIMARY KEY(id),FOREIGN KEY(quizId) REFERENCES quizlist(quizId) ON DELETE CASCADE)ENGINE = InnoDB");
				$stmt->execute();
				$stmt = $user->runQuery("CREATE TABLE live_quiz".$id."(id INT(10) NOT NULL AUTO_INCREMENT,quizId INT(10) NOT NULL,numSubmissions INT(10) NOT NULL DEFAULT 0,PRIMARY KEY(id),FOREIGN KEY(quizId) REFERENCES quizlist(quizId) ON DELETE CASCADE)ENGINE = InnoDB");
				$stmt->execute();
			}

		}
		else
		{
			$msg = "Your Account is Already Activated";
			$mType = "info";
	   	}
	}
	else
	{
		$msg = "Invalid verification link!";
		$myType = "error";
	}
}

?>
<!DOCTYPE html>
<html>

<head>
	<meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Validate Quiz</title>

	<link rel="stylesheet" href="lib/lobibox/css/lobibox.min.css" />

	<script src="lib/jquery/jquery.min.js"></script>
	<script src="lib/lobibox/js/lobibox.min.js"></script>
	<script src="lib/lobibox/js/messageboxes.min.js"></script>
</head>

<body>
    <?php
		if(isset($msg))
		{
	?>
		<script>
			var t = "<?php echo $mType?>";
			var m = "<?php echo $msg?>";
			Lobibox.alert(t,
				{ msg: m,
				callback: function(lobibox){
					window.location="index.php";
   				}
			});
		</script>
	<?php
		}
 	?>
</body>

</html>
