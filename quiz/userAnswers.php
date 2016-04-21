<?php
session_start();
require_once 'class.user.php';

$user = new USER();

if(!$user->is_logged_in())
	$user->redirect('index.php');

if(isset($_POST)){
	$count = $_SESSION['num'];
	try{
		$id = $_SESSION['userSession'];
		$quizId = $_SESSION['qId'];

		$stmt = $user->runQuery("SELECT * FROM quiz".$quizId."_answers WHERE correct=1 ORDER BY qId");
		$stmt->execute();
		$row = $stmt->fetchAll(PDO::FETCH_ASSOC);

		$stmt = $user->runQuery("CREATE TABLE u".$id."q".$quizId."_answers(id INT(10) NOT NULL AUTO_INCREMENT,
		qId INT(10) UNIQUE NOT NULL,aId INT(10) UNIQUE NOT NULL,correct BOOLEAN NOT NULL,PRIMARY KEY(id),
		FOREIGN KEY(qId) REFERENCES quiz".$quizId."_questions(qId) ON DELETE CASCADE,
		FOREIGN KEY(aId) REFERENCES quiz".$quizId."_answers(aId) ON DELETE CASCADE)ENGINE = InnoDB");
        $stmt->execute();

		ksort($_POST);
		$score = 0;
		$c = 0;
		foreach(array_keys($_POST) as $i){
			foreach($row as $x)
				if($i==$x['qId']){
					if($_POST[$i]==$x['aId']){
						$score++;
						$c=1;
					}
					else
						$c=0;
					$aId = $x['aId'];
					$stmt = $user->runQuery("INSERT INTO u".$id."q".$quizId."_answers(aId,qId,correct) VALUES($aId,$i,$c)");
					$stmt->execute();
					break;
				}
		}
		echo "Your answers have been submitted\nYou scored ".$score."/".$_SESSION['num'];
		$currTime = date("Y-m-d H:i:s");
		$dt = date("Y-m-d");
		$stmt = $user->runQuery("INSERT INTO past_quiz".$id."(quizId,score,submitDate) VALUES ($quizId,$score,'$dt')");
		$stmt->execute();

		$stmt = $user->runQuery("DELETE FROM live_quiz".$id." WHERE quizId=$quizId");
		$stmt->execute();

		$stmt = $user->runQuery("UPDATE quiz".$quizId."_takers SET taken=1, score=$score, submitTime='$currTime' WHERE userId=$id");
		$stmt->execute();

		$stmt = $user->runQuery("SELECT userId from quizlist WHERE quizId=$quizId");
		$stmt->execute();
		$r =  $stmt->fetch(PDO::FETCH_ASSOC);

		$stmt = $user->runQuery("UPDATE live_quiz".$r['userId']." SET numSubmissions=numSubmissions+1 WHERE quizId=$quizId");
		$stmt->execute();

		unset($_SESSION['qId']);
		unset($_SESSION['started']);
		unset($_SESSION['num']);
		unset($_SESSION['valid']);
		unset($_SESSION['start']);
	}catch(PDOException $ex){
		echo $ex->getMessage();
		exit();
	}
}
?>
