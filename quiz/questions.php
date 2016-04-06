<?php
session_start();
require_once 'class.user.php';

$user = new USER();

if(!$user->is_logged_in())
	$user->redirect('index.php');

if(isset($_GET['question'])){
	$question = preg_replace('/[^0-9]/', "", $_GET['question']);
	if(isset($_SESSION['answer_array']))
		$arrCount = count($_SESSION['answer_array']);
	else
		$arrCount = 0;

	if($arrCount >= $_SESSION['num']){
		echo 'finished|<p>There are no more questions. Please enter your first and last name and click next</p>
				<form action="userAnswers.php" method="post">
				<input type="hidden" name="complete" value="true">
				<input type="text" name="username">
				<input type="submit" value="Finish">
				</form>';
		exit();
	}
	try{
		$qid = $_SESSION['qNums'][$question];
		$stmt = $user->runQuery("SELECT * FROM quiz".$_SESSION['qId']."_questions WHERE qId=$qid");
		$stmt->execute();
		$row = $stmt->fetch(PDO::FETCH_ASSOC);
		$answers = "";
		$thisQuestion = $row['question'];
		$type = $row['type'];

		$p = '<p>Current Question'.($question+1)."/".$_SESSION['num']."</p>";
		$q = '<h2>'.$thisQuestion.'</h2>';

		$stmt = $user->runQuery("SELECT * FROM quiz".$_SESSION['qId']."_answers WHERE qId=$qid");
		$stmt->execute();

		while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
			$answer = $row['answer'];
			$aId = $row['aId'];
			$answers .= '<label style="cursor:pointer;"><input type="radio" name="rads" value="'.$aId.'"">'.$answer.'</label><br/><br/>';
		}
		$answers .='<input type="hidden" id="qid" value="'.$qid.'" name="qid"><br /><br />';
		$output = $p.''.$q.','.$answers.',<span id="btnSpan"><button onclick="post_answer()">Submit</button></span>';
		echo $output;
	}catch(PDOException $ex){
		echo $ex->getMessage();
		exit();
	}
}
?>
