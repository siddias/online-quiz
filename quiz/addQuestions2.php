<?php
session_start();
require_once 'class.user.php';

$user = new USER();

if(!$user->is_logged_in() || $_SESSION['userType']!='T')
	$user->redirect('index.php');

if(isset($_SESSION['qno']) && isset($_SESSION['quizId']) && isset($_POST['desc']))
{
	if(!isset($_POST['iscorrect']) || $_POST['iscorrect'] == "" || !isset($_POST['type']) || $_POST['type'] == "")
	{
		echo "Sorry, important data to submit your question is missing. Please press back in your browser and try again and make sure you select a correct answer for the question.";
		exit();
	}

	$type = preg_replace('/[^A-Z]/', "", $_POST['type']);
	$isCorrect = preg_replace('/[^0-9a-z]/', "", $_POST['iscorrect']);
	$a1 = strip_tags( $_POST['a1']);
	$a2 = strip_tags($_POST['a2']);
	$a3 = strip_tags($_POST['a3']);
	$a4 = strip_tags($_POST['a4']);
	$question = strip_tags($_POST['desc']);

	if($type == 'TF' && (!$question || !$a1 || !$a2 || !$isCorrect)){
			echo "Sorry, All fields must be filled in to add a new question to the quiz. Please press back in your browser and try again.";
			exit();
	}

	if($type == 'MC' && (!$question || !$a1 || !$a2 || !$a3 || !$a4 || !$isCorrect)){
			echo "Sorry, All fields must be filled in to add a new question to the quiz. Please press back in your browser and try again.";
			exit();
	}
	try
	{
			$id = $_SESSION['quizId'];
			$stmt = $user->runQuery("INSERT INTO quiz".$id."_questions(question,type) VALUES('$question', '$type')");
			$stmt->execute();
			$lastId = $user->lasdID();

			$stmt = $user->runQuery("INSERT INTO quiz".$id."_answers(qId, answer) VALUES ('$lastId',:a)");
			$stmt->execute(array(":a"=>$a1));
			$aId = $user->lasdID();

			$stmt->execute(array(":a"=>$a2));

			if($type == 'MC'){
				$stmt->execute(array(":a"=>$a3));
				$stmt->execute(array(":a"=>$a4));
			}

			$aId +=((int)$isCorrect[1])-1;
			$stmt = $user->runQuery("UPDATE quiz".$id."_answers SET correct=1 WHERE aId='$aId'");
			$stmt->execute();
	}
	catch(PDOException $ex)
    {
        echo $ex->getMessage();
		exit();
    }
	if($_SESSION['qno']==$_SESSION['num'])
		header('location: done.php');
	else
		header('location: addQuestions.php?msg=success');
	$_SESSION['qno']++;
}
?>
<?php
$msg = "";
if(isset($_GET['msg'])){
	$msg = 'Question has been added';
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Create Quiz</title>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link rel="stylesheet" href="lib/bootstrap/css/bootstrap.min.css" />
		<link rel="stylesheet" href="css/layout.css" />
		<!--<link rel="stylesheet" href="css/addQuestions.css" />-->
		<script src="lib/jquery/jquery.min.js"></script>
		<script src="lib/bootstrap/js/bootstrap.min.js"></script>

</head>

<body>
	<nav class="navbar navbar-inverse">
			<div class="container-fluid">
					<div class="navbar-header">
							<button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
									<span class="icon-bar"></span>
									<span class="icon-bar"></span>
									<span class="icon-bar"></span>
							</button>
							<a class="navbar-brand" href="#"><img alt="Quiz-It!" src="images/Qi-logo.png"></a>
					</div>
					<div class="collapse navbar-collapse" id="myNavbar">
							<ul class="nav navbar-nav">
									<li class="active"><a href="home.php"><span class="glyphicon glyphicon-home"></span></a></li>
									<li><a href="live.php">Live Quiz</a></li>
									<li><a href="past.php">Past Quiz</a></li>
				<?php
					if($_SESSION['userType']=='T')
					{
				?>
						<li><a href="setQuiz.php">Set Quiz</a></li>
				<?php
					}
				?>
				</ul>
							</ul>
							<ul class="nav navbar-nav navbar-right">
									<li class="dropdown">
											<a class="dropdown-toggle" data-toggle="dropdown" href="#"><span class="glyphicon glyphicon-user"></span>&nbsp;<?php echo $_SESSION['fname']?><span class="caret"></span></a>
											<ul class="dropdown-menu">
													<li><a href="#">Edit Profile</a></li>
													<li><a href="logout.php">Sign Out</a></li>
											</ul>
									</li>
							</ul>
					</div>
			</div>
	</nav>

	<div class="container stuff">
    <div class="qdiv" id="qdiv">
        <p style="color:#86b2e5;">
            <?php echo $msg; ?>
        </p>
        <h3>What type of question would you like to create?</h3>
        <button id="TFB" type="button" class="btn btn-primary" onclick="addTF()">True/False</button>&nbsp;&nbsp;
        <button id="MCB" type="button" class="btn btn-primary" onclick="addMCQ()">Multiple Choice</button>&nbsp;&nbsp;
    </div>
    <div class="content" id="content">
    </div>
	</div>
	<div class="footer">
	</div>
	<script>
	var num=1,prev=1,limit=<?php echo $_SESSION['num']?>;

				function addTF()
				{
					if(num==1)
					{
						var olddiv = document.getElementById("TFB");
						var d = document.getElementById('qdiv');
						d.removeChild(olddiv);
						olddiv = document.getElementById("MCB");
						d.removeChild(olddiv);

						var ni = document.getElementById("content");
						var newdiv = document.createElement('form');
						newdiv.setAttribute('id','quesForm');
						newdiv.setAttribute('action', 'addQuestions.php');
						newdiv.setAttribute('name', 'addQuestion');
						newdiv.setAttribute('method', 'post');
						ni.appendChild(newdiv);

						ni = document.getElementById("quesForm");
						newdiv = document.createElement('div');
						var divIdName = 'qcontent' + Number(num);
						var quesId="TFq"+Number(num);
						var ansId1="q"+Number(num)+"ans1";
						var ansId2="q"+Number(num)+"ans2";
						newdiv.setAttribute('id', divIdName);
						newdiv.setAttribute("style", "margin:5px; border-bottom:2px dashed #4e3232;");
						ni.appendChild(newdiv);

						ni=document.getElementById(divIdName);
						ni.innerHTML='<p>Question Number '+num+'</p><strong>Please type your new question here</strong><br/><textarea id=\"'+quesId+'\" name=\"desc\" class=\"tarea\" required></textarea><br/><br/><strong>Please select whether true or false is the correct answer</strong><br/><input type=\"text\" id=\"'+ansId1+'\" name=\"'+ansId1+'\" value="True" readonly>&nbsp;<label><input type=\"radio\" name=\"iscorrect\" value=\"'+ansId1+'\">Correct Answer?</label><br/><br/><input type=\"text\" id=\"'+ansId2+'\" name=\"'+ansId2+'\" value=\"False\" readonly>&nbsp;<label><input type=\"radio\" name=\"iscorrect\" value=\"'+ansId2+'\">Correct Answer?</label><br/><br/>';
						prev=num;
						num++;

						newdiv = document.createElement('button');
						newdiv.setAttribute("id","TFB");
						newdiv.setAttribute("type", "button");
						newdiv.setAttribute("class", "btn btn-primary");
						newdiv.setAttribute("style", "margin:5px;");
						ni.appendChild(newdiv);
						newdiv.innerHTML="Next Question T/F";
						newdiv.onclick=addTF;

						newdiv = document.createElement('button');
						newdiv.setAttribute("id","MCB");
						newdiv.setAttribute("type", "button");
						newdiv.setAttribute("class", "btn btn-primary");
						newdiv.setAttribute("style", "margin:5px;");
						ni.appendChild(newdiv);
						newdiv.innerHTML="Next Question MCQ";
						newdiv.onclick=addMCQ;


					}
					else if(num==limit)
					{
						var elem = document.getElementById('TFB');
					    elem.parentNode.removeChild(elem);
						elem = document.getElementById('MCB');
    					elem.parentNode.removeChild(elem);
/*
var olddiv = document.getElementById("TFB");
var id='qcontent'+Number(num);
var d = document.getElementById(id);
d.removeChild(olddiv);
olddiv = document.getElementById("MCB");
d.removeChild(olddiv);
*/
						var ni = document.getElementById("quesForm");
						var newdiv = document.createElement('div');
						var divIdName = 'qcontent' + Number(num);
						var quesId="TFq"+Number(num);
						var ansId1="q"+Number(num)+"ans1";
						var ansId2="q"+Number(num)+"ans2";
						newdiv.setAttribute('id', divIdName);
						newdiv.setAttribute("style", "margin:5px; border-bottom:2px dashed #4e3232;");
						ni.appendChild(newdiv);

						ni=document.getElementById(divIdName);
						ni.innerHTML='<p>Question Number '+num+'</p><strong>Please type your new question here</strong><br/><textarea id=\"'+quesId+'\" name=\"desc\" class=\"tarea\" required></textarea><br/><br/><strong>Please select whether true or false is the correct answer</strong><br/><input type=\"text\" id=\"'+ansId1+'\" name=\"'+ansId1+'\" value="True" readonly>&nbsp;<label><input type=\"radio\" name=\"iscorrect\" value=\"'+ansId1+'\">Correct Answer?</label><br/><br/><input type=\"text\" id=\"'+ansId2+'\" name=\"'+ansId2+'\" value=\"False\" readonly>&nbsp;<label><input type=\"radio\" name=\"iscorrect\" value=\"'+ansId2+'\">Correct Answer?</label><br/><br/>';

						newdiv = document.createElement('button');
						newdiv.setAttribute("type", "button");
						newdiv.setAttribute("class", "btn btn-primary");
						newdiv.setAttribute("style", "margin:5px;");
						ni.appendChild(newdiv);
						newdiv.innerHTML="Submit Quiz";
						newdiv.onclick=abc;
					}
					else
					{
						var elem = document.getElementById('TFB');
							elem.parentNode.removeChild(elem);
						elem = document.getElementById('MCB');
							elem.parentNode.removeChild(elem);

						var ni = document.getElementById("quesForm");
						var newdiv = document.createElement('div');
						var divIdName = 'qcontent' + Number(num);
						var quesId="TFq"+Number(num);
						var ansId1="q"+Number(num)+"ans1";
						var ansId2="q"+Number(num)+"ans2";
						newdiv.setAttribute('id', divIdName);
						newdiv.setAttribute("style", "margin:10px; border-bottom:2px dashed #4e3232;");
						ni.appendChild(newdiv);

						ni=document.getElementById(divIdName);
						ni.innerHTML='<p>Question Number '+num+'</p><strong>Please type your new question here</strong><br/><textarea id=\"'+quesId+'\" name=\"desc\" class=\"tarea\" required></textarea><br/><br/><strong>Please select whether true or false is the correct answer</strong><br/><input type=\"text\" id=\"'+ansId1+'\" name=\"'+ansId1+'\" value="True" readonly>&nbsp;<label><input type=\"radio\" name=\"iscorrect\" value=\"'+ansId1+'\">Correct Answer?</label><br/><br/><input type=\"text\" id=\"'+ansId2+'\" name=\"'+ansId2+'\" value=\"False\" readonly>&nbsp;<label><input type=\"radio\" name=\"iscorrect\" value=\"'+ansId2+'\">Correct Answer?</label><br/><br/>';
						prev=num;
						num++;

						newdiv = document.createElement('button');
						newdiv.setAttribute("type", "button");
						newdiv.setAttribute("class", "btn btn-primary");
						newdiv.setAttribute("style", "margin:5px;");
						newdiv.setAttribute("id","TFB");
						ni.appendChild(newdiv);
						newdiv.innerHTML="Next Question T/F";
						newdiv.onclick=addTF;

						newdiv = document.createElement('button');
						newdiv.setAttribute("type", "button");
						newdiv.setAttribute("class", "btn btn-primary");
						newdiv.setAttribute("style", "margin:5px;");
						newdiv.setAttribute("id","MCB");
						ni.appendChild(newdiv);
						newdiv.innerHTML="Next Question MCQ";
						newdiv.onclick=addMCQ;

					}
				}
			//document.getElementById("quit").onclick = function () {
				//window.location = "done.php?quit=yes";
		//}

		function addMCQ()
		{
			if(num==1)
			{
				var olddiv = document.getElementById("TFB");
				var d = document.getElementById('qdiv');
				d.removeChild(olddiv);
				olddiv = document.getElementById("MCB");
				d.removeChild(olddiv);

				var ni = document.getElementById("content");
				var newdiv = document.createElement('form');
				newdiv.setAttribute('id','quesForm');
				newdiv.setAttribute('action', 'addQuestions.php');
				newdiv.setAttribute('name', 'addQuestion');
				newdiv.setAttribute('method', 'post');
				ni.appendChild(newdiv);

				ni = document.getElementById("quesForm");
				newdiv = document.createElement('div');
				var divIdName = 'qcontent' + Number(num);
				var quesId="MCQq"+Number(num);
				var ansId1="q"+Number(num)+"ans1";
				var ansId2="q"+Number(num)+"ans2";
				var ansId3="q"+Number(num)+"ans3";
				var ansId4="q"+Number(num)+"ans4";
				newdiv.setAttribute('id', divIdName);
				newdiv.setAttribute("style", "margin:5px; border-bottom:2px dashed #4e3232;");
				ni.appendChild(newdiv);

				ni=document.getElementById(divIdName);
				ni.innerHTML='<p>Question Number '+num+'</p><strong>Please type your new question here</strong><br/><textarea id=\"'+quesId+'\" name=\"desc\" class=\"tarea\" required></textarea><br/><br/><strong>Answer 1</strong><br/><input type=\"text\" id=\"'+ansId1+'\" name=\"'+ansId1+'\" >&nbsp;<label><input type=\"radio\" name=\"iscorrect\" value=\"'+ansId1+'\">Correct Answer?</label><br/><br/><strong>Answer 2</strong><br/><input type=\"text\" id=\"'+ansId2+'\" name=\"'+ansId2+'\" >&nbsp;<label><input type=\"radio\" name=\"iscorrect\" value=\"'+ansId2+'\">Correct Answer?</label><br/><br/><strong>Answer 3</strong><br/><input type=\"text\" id=\"'+ansId3+'\" name=\"'+ansId3+'\" >&nbsp;<label><input type=\"radio\" name=\"iscorrect\" value=\"'+ansId3+'\">Correct Answer?</label><br/><br/><strong>Answer 4</strong><br/><input type=\"text\" id=\"'+ansId4+'\" name=\"'+ansId4+'\" >&nbsp;<label><input type=\"radio\" name=\"iscorrect\" value=\"'+ansId4+'\">Correct Answer?</label><br/>';
				prev=num;
				num++;

				newdiv = document.createElement('button');
				newdiv.setAttribute("type", "button");
				newdiv.setAttribute("class", "btn btn-primary");
				newdiv.setAttribute("style", "margin:5px;");
				newdiv.setAttribute("id","TFB");
				ni.appendChild(newdiv);
				newdiv.innerHTML="Next Question T/F";
				newdiv.onclick=addTF;

				newdiv = document.createElement('button');
				newdiv.setAttribute("type", "button");
				newdiv.setAttribute("class", "btn btn-primary");
				newdiv.setAttribute("style", "margin:5px;");
				newdiv.setAttribute("id","MCB");
				ni.appendChild(newdiv);
				newdiv.innerHTML="Next Question MCQ";
				newdiv.onclick=addMCQ;


			}
			else if(num==limit)
			{
				var elem = document.getElementById('TFB');
					elem.parentNode.removeChild(elem);
				elem = document.getElementById('MCB');
					elem.parentNode.removeChild(elem);

				var ni = document.getElementById("quesForm");
				var newdiv = document.createElement('div');
				var divIdName = 'qcontent' + Number(num);
				var quesId="MCQq"+Number(num);
				var ansId1="q"+Number(num)+"ans1";
				var ansId2="q"+Number(num)+"ans2";
				newdiv.setAttribute('id', divIdName);
				newdiv.setAttribute("style", "margin:5px; border-bottom:2px dashed #4e3232;");
				ni.appendChild(newdiv);

				ni=document.getElementById(divIdName);
				ni.innerHTML='<p>Question Number '+num+'</p><strong>Please type your new question here</strong><br/><textarea id=\"'+quesId+'\" name=\"desc\" class=\"tarea\" required></textarea><br/><br/><strong>Answer 1</strong><br/><input type=\"text\" id=\"'+ansId1+'\" name=\"'+ansId1+'\" >&nbsp;<label><input type=\"radio\" name=\"iscorrect\" value=\"'+ansId1+'\">Correct Answer?</label><br/><br/><strong>Answer 2</strong><br/><input type=\"text\" id=\"'+ansId2+'\" name=\"'+ansId2+'\" >&nbsp;<label><input type=\"radio\" name=\"iscorrect\" value=\"'+ansId2+'\">Correct Answer?</label><br/><br/><strong>Answer 3</strong><br/><input type=\"text\" id=\"'+ansId3+'\" name=\"'+ansId3+'\" >&nbsp;<label><input type=\"radio\" name=\"iscorrect\" value=\"'+ansId3+'\">Correct Answer?</label><br/><br/><strong>Answer 4</strong><br/><input type=\"text\" id=\"'+ansId4+'\" name=\"'+ansId4+'\" >&nbsp;<label><input type=\"radio\" name=\"iscorrect\" value=\"'+ansId4+'\">Correct Answer?</label><br/>';

				newdiv = document.createElement('button');
				newdiv.setAttribute("type", "button");
				newdiv.setAttribute("class", "btn btn-primary");
				newdiv.setAttribute("style", "margin:5px;");
				ni.appendChild(newdiv);
				newdiv.innerHTML="Submit Quiz";
				newdiv.onclick=abc;
			}
			else
			{
				var elem = document.getElementById('TFB');
					elem.parentNode.removeChild(elem);
				elem = document.getElementById('MCB');
					elem.parentNode.removeChild(elem);

				var ni = document.getElementById("quesForm");
				var newdiv = document.createElement('div');
				var divIdName = 'qcontent' + Number(num);
				var quesId="MCQq"+Number(num);
				var ansId1="q"+Number(num)+"ans1";
				var ansId2="q"+Number(num)+"ans2";
				newdiv.setAttribute('id', divIdName);
				newdiv.setAttribute("style", "margin:10px; border-bottom:2px dashed #4e3232;");
				ni.appendChild(newdiv);

				ni=document.getElementById(divIdName);
				ni.innerHTML='<p>Question Number '+num+'</p><strong>Please type your new question here</strong><br/><textarea id=\"'+quesId+'\" name=\"desc\" class=\"tarea\" required></textarea><br/><br/><strong>Answer 1</strong><br/><input type=\"text\" id=\"'+ansId1+'\" name=\"'+ansId1+'\" >&nbsp;<label><input type=\"radio\" name=\"iscorrect\" value=\"'+ansId1+'\">Correct Answer?</label><br/><br/><strong>Answer 2</strong><br/><input type=\"text\" id=\"'+ansId2+'\" name=\"'+ansId2+'\" >&nbsp;<label><input type=\"radio\" name=\"iscorrect\" value=\"'+ansId2+'\">Correct Answer?</label><br/><br/><strong>Answer 3</strong><br/><input type=\"text\" id=\"'+ansId3+'\" name=\"'+ansId3+'\" >&nbsp;<label><input type=\"radio\" name=\"iscorrect\" value=\"'+ansId3+'\">Correct Answer?</label><br/><br/><strong>Answer 4</strong><br/><input type=\"text\" id=\"'+ansId4+'\" name=\"'+ansId4+'\" >&nbsp;<label><input type=\"radio\" name=\"iscorrect\" value=\"'+ansId4+'\">Correct Answer?</label><br/>';
				prev=num;
				num++;

				newdiv = document.createElement('button');
				newdiv.setAttribute("type", "button");
				newdiv.setAttribute("class", "btn btn-primary");
				newdiv.setAttribute("style", "margin:5px;");
				newdiv.setAttribute("id","TFB");
				ni.appendChild(newdiv);
				newdiv.innerHTML="Next Question T/F";
				newdiv.onclick=addTF;

				newdiv = document.createElement('button');
				newdiv.setAttribute("type", "button");
				newdiv.setAttribute("class", "btn btn-primary");
				newdiv.setAttribute("style", "margin:5px;");
				newdiv.setAttribute("id","MCB");
				ni.appendChild(newdiv);
				newdiv.innerHTML="Next Question MCQ";
				newdiv.onclick=addMCQ;

			}

		}

		function abc()
		{
			alert("abc");
	}
	</script>


</body>

</html>
