<?php
session_start();
require_once 'class.user.php';

$user = new USER();

if(!$user->is_logged_in())
	$user->redirect('index.php');

if(isset($_GET['id']) && $_GET['id']!="")//&& !isset($_SESSION['started']))
{
	$qid =preg_replace('/[^0-9]/', "", $_GET['id']);
	$id = $_SESSION['userSession'];
	try{
		$stmt = $user->runQuery("SELECT * FROM quizlist q ,live_quiz".$id." l WHERE q.quizId=l.quizId and q.quizId=$qid LIMIT 1"); //change
		$stmt->execute();
		$result = $stmt->fetch(PDO::FETCH_ASSOC);
		$currTime = time();
		if($stmt->rowCount()==0){
			header("location: quiz.php?msg=invalid link");
			exit();
		}
		else if($currTime<strtotime($result['startTime'])){
			header("location: quiz.php?msg=quiz not opened");
			exit();
		}
		else if($currTime>strtotime($result['endTime'])) {
			$stmt = $user->runQuery("SELECT quizId from past_quiz".$id." WHERE quizId=$qid");
			$stmt->execute();
			$r = $stmt->fetchAll(PDO::FETCH_ASSOC);
			if($stmt->rowCount()==0){
				if($_SESSION['userType']=='T')
					$stmt = $user->runQuery("INSERT INTO past_quiz".$id."(quizId) VALUES ($qid)");
				else
					$stmt = $user->runQuery("INSERT INTO past_quiz".$id."(quizId,score) VALUES ($qid,0)");
				$stmt->execute();
			}
			$stmt = $user->runQuery("DELETE from live_quiz".$id." WHERE quizId=$qid");
			$stmt->execute();
			header("location: quiz.php?msg=quiz ended");
			exit();
		}
		else {
			$_SESSION['qId']=$qid;
			$_SESSION['started']=true;
			$_SESSION['sTime']=time();
			$_SESSION['eTime']=$_SESSION['sTime']+$result['duration']*60;
			$_SESSION['num']=$result['numQuestions'];
			$_SESSION['qNums']=range(1,$_SESSION['num']);
			shuffle($_SESSION['qNums']);
			header("location: quiz.php?question=0");
			exit();
		}
	}catch(PDOException $ex){
		echo $ex->getMessage();
		exit();
	}
}
if(isset($_GET['question'])){
	$question = preg_replace('/[^0-9]/', "", $_GET['question']);
	$next = $question + 1;
	$prev = $question - 1;
}
?>
<!DOCTYPE html>
<html>

<head>
	<meta charset="utf-8">
	<title>Quiz Page</title>
	<script type="text/javascript">
		function countDown(secs, elem) {
			var element = document.getElementById(elem);
			element.innerHTML = "You have " + secs + " seconds remaining.";
			if (secs < 1) {
				var xhr = new XMLHttpRequest();
				var url = "userAnswers.php";
				var q = <?php echo $question; ?>;
				var vars = "radio=0" + "&qid=" + q;
				xhr.open("POST", url, true);
				xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
				xhr.onreadystatechange = function() {
					if (xhr.readyState == 4 && xhr.status == 200) {
						alert("You did not answer the question in the allotted time. It will be marked as incorrect.");
						clearTimeout(timer);
					}
				}
				xhr.send(vars);
				document.getElementById('counter_status').innerHTML = "";
				document.getElementById('btnSpan').innerHTML = '<h2>Times Up!</h2>';
				document.getElementById('btnSpan').innerHTML += '<a href="quiz.php?question=<?php echo $next; ?>">Click here now</a>';

			}
			secs--;
			var timer = setTimeout('countDown(' + secs + ',"' + elem + '")', 1000);
		}
	</script>
	<script>
		function getQuestion() {
			var hr = new XMLHttpRequest();
			hr.onreadystatechange = function() {
				if (hr.readyState == 4 && hr.status == 200) {
					var response = hr.responseText.split("|");
					if (response[0] == "finished") {
						document.getElementById('status').innerHTML = response[1];
					}
					var nums = hr.responseText.split(",");
					document.getElementById('question').innerHTML = nums[0];
					document.getElementById('answers').innerHTML = nums[1];
					document.getElementById('answers').innerHTML += nums[2];
				}
			}
			hr.open("GET", "questions.php?question=" + <?php echo $question; ?>, true);
			hr.send();
		}

		function x() {
			var rads = document.getElementsByName("rads");
			for (var i = 0; i < rads.length; i++) {
				if (rads[i].checked) {
					var val = rads[i].value;
					return val;
				}
			}
		}

		function post_answer() {
			var p = new XMLHttpRequest();
			var id = document.getElementById('qid').value;
			var url = "userAnswers.php";
			var vars = "qid=" + id + "&radio=" + x();
			p.open("POST", url, true);
			p.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
			p.onreadystatechange = function() {
				if (p.readyState == 4 && p.status == 200) {
					document.getElementById("status").innerHTML = '';
					alert("Thanks, Your answer was submitted" + p.responseText);
					var url = 'quiz.php?question=<?php echo $next; ?>';
					window.location = url;
				}
			}
			p.send(vars);
			document.getElementById("status").innerHTML = "processing...";

		}
	</script>
	<script>
		//window.oncontextmenu = function() { return false; }
	</script>
</head>

<body onLoad="getQuestion()">
	<?php
		if(isset($_GET['msg']))
		{
			$msg = $_GET['msg'];
			$msg = strip_tags($msg);
			$msg = addslashes($msg);
			echo $msg;
		}
	?>
	<div id="status">
		<div id="counter_status"></div>
		<div id="question"></div>
		<div id="answers"></div>
	</div>
	<script>
		countDown(40, "counter_status");
	</script>
</body>

</html>
