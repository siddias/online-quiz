<?php
require_once 'class.user.php';
$user = new USER();

try{
		$id = $_SESSION['userSession'];
		if($_SESSION['userType']=='T'){
			$stmt = $user->runQuery("SELECT q.quizId,l.numSubmissions,q.endTime,q.numQuestions from quizlist q,live_quiz".$id." l WHERE q.quizId=l.quizId");
			$stmt->execute();
			$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
		}
		else {
			$stmt = $user->runQuery("SELECT q.quizId,q.endTime,q.numQuestions from quizlist q,live_quiz".$id." l WHERE q.quizId=l.quizId");
			$stmt->execute();
			$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
		}
		foreach($result as $row){
			$qid = $row['quizId'];
			$currTime = time();
			if($currTime>strtotime($row['endTime'])){
					if($_SESSION['userType']=='S'){
		            	$stmt = $user->runQuery("INSERT INTO past_quiz".$id."(quizId,score) VALUES ($qid,0)");
		            	$stmt->execute();
					}
					else {
						$stmt = $user->runQuery("SELECT AVG(score) FROM quiz".$qid."_takers");
		            	$stmt->execute();
						$r= $stmt->fetch(PDO::FETCH_ASSOC);
						$submissions =$row['numSubmissions'];
						$average = $r['AVG(score)']/$row['numQuestions'];
						$stmt = $user->runQuery("INSERT INTO past_quiz".$id."(quizId,numSubmissions,scoreAvg) VALUES ($qid,$submissions,'$average')");
		            	$stmt->execute();
					}
				$stmt = $user->runQuery("DELETE from live_quiz".$id." WHERE quizId=$qid");
				$stmt->execute();
			}
		}
    }catch(PDOException $ex)
	{
		echo $ex->getMessage();
		exit();
	}
	?>
