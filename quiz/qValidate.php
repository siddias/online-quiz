<?php

session_start();
require_once 'class.user.php';

$user = new USER();

if(!$user->is_logged_in())
	$user->redirect('index.php');

if(isset($_GET['id']) && $_GET['id']!="")
{
	$qid =preg_replace('/[^0-9]/', "", $_GET['id']);
	$id = $_SESSION['userSession'];
	try{
		$stmt = $user->runQuery("SELECT * FROM quizlist q ,live_quiz".$id." l WHERE q.quizId=l.quizId and q.quizId=$qid LIMIT 1"); //change
		$stmt->execute();
		$result = $stmt->fetch(PDO::FETCH_ASSOC);
		$currTime = time();
		if($stmt->rowCount()==0){
			$msg = "Invalid link";
			$mType = "error";
		}
		else if($currTime<strtotime($result['startTime'])){
			$msg = "Quiz Not Yet Opened";
			$mType = "error";
		}
		else if($currTime>strtotime($result['endTime'])) {
			$stmt = $user->runQuery("SELECT quizId from past_quiz".$id." WHERE quizId=$qid");
			$stmt->execute();
			$r = $stmt->fetchAll(PDO::FETCH_ASSOC);
			if($stmt->rowCount()==0){
				$stmt = $user->runQuery("INSERT INTO past_quiz".$id."(quizId,score) VALUES ($qid,0)");
				$stmt->execute();
			}
			$stmt = $user->runQuery("DELETE from live_quiz".$id." WHERE quizId=$qid");
			$stmt->execute();
			$msg = "Quiz has Ended!";
			$mType = "error";
		}
		else {
			$stmt = $user->runQuery("SELECT taken from quiz".$qid."_takers WHERE userId=$id and taken=1	 LIMIT 1");
			$stmt->execute();

			if($stmt->rowCount()==1){
				$msg = "You have already taken the quiz!";
				$mType = "error";
			}
			else{
				$_SESSION['qId']=$qid;
				$_SESSION['started']=true;
				$_SESSION['num']=$result['numQuestions'];
				$_SESSION['duration']=$result['duration'];
				$_SESSION['qName']=$result['name'];
				$_SESSION['valid']=true;
			}
		}
	}catch(PDOException $ex){
		echo $ex->getMessage();
		exit();
	}
}
else {
	$msg = "Invalid link";
	$mType = "error";
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
					window.location="home.php";
   				}
			});
		</script>
	<?php
	}
	else{
	?>
		<script>
			Lobibox.confirm({
		    msg: 'Are you sure you want to start the quiz?',
		    callback: function(lobibox, type){
		        if (type === 'no')
		            window.location = "home.php";
		        else
					window.location = "quiz.php?id=<?php echo $qid?>";
				}
		});
		</script>

	<?php
	}
	?>
</body>

</html>
