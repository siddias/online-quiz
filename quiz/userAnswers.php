<?php
session_start();
require_once 'class.user.php';

$user = new USER();

if(!$user->is_logged_in())
	$user->redirect('index.php');

if(isset($_POST['radio']) && $_POST['radio'] != ""){
	$answer = preg_replace('/[^0-9]/', "", $_POST['radio']);
	if(!isset($_SESSION['answer_array']) || count($_SESSION['answer_array']) < 1){
		$_SESSION['answer_array'] = array($answer);
	}else{
		array_push($_SESSION['answer_array'], $answer);
	}
}

if(isset($_POST['qid']) && $_POST['qid'] != ""){
	$qid = preg_replace('/[^0-9]/', "", $_POST['qid']);
	if(!isset($_SESSION['qid_array']) || count($_SESSION['qid_array']) < 1){
		$_SESSION['qid_array'] = array($qid);
	}else{
		array_push($_SESSION['qid_array'], $qid);
	}
}
?>
<?php
if(isset($_POST['complete']) && $_POST['complete'] == "true"){
	$count = $_SESSION['num'];
	$stmt = $user->runQuery("SELECT * FROM quiz".$_SESSION['qId']."_answers WHERE correct=1 ORDER BY qId");
	$stmt->execute();
	$row = $stmt->fetchAll(PDO::FETCH_ASSOC);
	array_multisort($_SESSION['qid_array'],$_SESSION['answer_array']);
	$score = 0;
	for($i=0;$i<$_SESSION['num'];$i++){
		if($_SESSION['answer_array'][$i] ==$row[$i]['aId']){
			$score++;
		}
	}

	$id = $_SESSION['userSession'];
	$quizId = $_SESSION['qId'];
	try{
		$stmt = $user->runQuery("INSERT INTO past_quiz".$id."(quizId,score) VALUES ($quizId,$score)");
		$stmt->execute();

		$stmt = $user->runQuery("DELETE FROM live_quiz".$id." WHERE quizId=$quizId");
		$stmt->execute();

		$stmt = $user->runQuery("UPDATE quiz".$quizId."_takers SET taken=1, score=$score WHERE userId=$id");
		$stmt->execute();
		echo "Thanks for taking the quiz! You scored ".$score;
		echo '<a href="home.php">Go to Home</a>';
		unset($_SESSION['answer_array']);
		unset($_SESSION['qid_array']);
		unset($_SESSION['qId']);
		unset($_SESSION['started']);
		unset($_SESSION['sTime']);
		unset($_SESSION['eTime']);
		unset($_SESSION['num']);
		unset($_SESSION['qNums']);
	}catch(PDOException $ex){
		echo $ex->getMessage();
		exit();
	}
}
?>
