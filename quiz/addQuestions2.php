<?php
session_start();
require_once 'class.user.php';

$user = new USER();

if(!$user->is_logged_in() || $_SESSION['userType']!='T')
	$user->redirect('index.php');
if(isset($_POST['sub']) && $_POST['sub']!="" && isset($_SESSION['quizId']))
{
	$id = $_SESSION['quizId'];
	for($i=1;$i<=$_SESSION['num'];$i++){
		try
		{
			$a1 = strip_tags( $_POST['q'.$i.'ans1']);
			$a2 = strip_tags( $_POST['q'.$i.'ans2']);
			if(isset($_POST['TFq'.$i])){
				$q = strip_tags($_POST['TFq'.$i]);
				$stmt = $user->runQuery("INSERT INTO quiz".$id."_questions(question,type) VALUES('$q', 'TF')");
				$stmt->execute();
				$type="TF";
			}
			else {
				$q = strip_tags($_POST['MCQq'.$i]);
				$a3 = strip_tags( $_POST['q'.$i.'ans3']);
				$a4 = strip_tags( $_POST['q'.$i.'ans4']);
				$stmt = $user->runQuery("INSERT INTO quiz".$id."_questions(question,type) VALUES('$q', 'MC')");
				$stmt->execute();
				$type="MC";
			}

			$lastId = $user->lasdID();
			$stmt = $user->runQuery("INSERT INTO quiz".$id."_answers(qId, answer) VALUES ('$lastId',:a)");
			$stmt->execute(array(":a"=>$a1));
			$aId = $user->lasdID();

			$stmt->execute(array(":a"=>$a2));

			if($type == 'MC'){
				$stmt->execute(array(":a"=>$a3));
				$stmt->execute(array(":a"=>$a4));
			}

			$isCorrect=$_POST['iscorrect'.$i];
			$aId +=((int)$isCorrect[strlen($isCorrect)-1])-1;
			$stmt = $user->runQuery("UPDATE quiz".$id."_answers SET correct=1 WHERE aId='$aId'");
			$stmt->execute();
		}
		catch(PDOException $ex)
	    {
	        echo $ex->getMessage();
			exit();
	    }
	}
	header('location: done.php');
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
		<link rel="stylesheet" href="css/addQuestions.css" />
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
													<li><a href="logout.php">Sign Out</a></li>
											</ul>
									</li>
							</ul>
					</div>
			</div>
	</nav>

	<div class="container stuff">
    <div class="qdiv text-center" id="qdiv">
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
						newdiv.setAttribute('action', 'addQuestions2.php');
						newdiv.setAttribute('name', 'addQuestion');
						newdiv.setAttribute('method', 'post');
						newdiv.setAttribute('class','form-horizontal');
						ni.appendChild(newdiv);

						ni = document.getElementById("quesForm");
						newdiv = document.createElement('div');
						var divIdName = 'qcontent' + Number(num);
						var quesId="TFq"+Number(num);
						var ansId1="q"+Number(num)+"ans1";
						var ansId2="q"+Number(num)+"ans2";
						var radioID="iscorrect"+Number(num);
						newdiv.setAttribute('id', divIdName);
						newdiv.setAttribute("class", "tfqs");
						ni.appendChild(newdiv);

						ni=document.getElementById(divIdName);
						ni.innerHTML='<h2><small>Question Number '+num+'</small></h2><div class=\"form-group\"><label class=\"control-label col-sm-4\">Please type your new question here:</label></div><div class=\"form-group\"><div class=\"col-sm-offset-1 col-sm-9\"><textarea id=\"'+quesId+'\" name=\"'+quesId+'\" class=\"tarea form-control\" rows=\"5\" required></textarea></div></div><div class=\"form-group\"><label  class=\"control-label col-sm-4\">Please select the correct answer:</label></div><div class=\"form-group\"><div class=\" col-sm-offset-1 col-sm-2\"><input type=\"text\" class=\"form-control readonly\" id=\"'+ansId1+'\" name=\"'+ansId1+'\" value=\"True\" readonly></div><div class=\"col-sm-2\"><div class=\"radio\"><label><input type=\"radio\" name=\"'+radioID+'\" value=\"'+ansId1+'\" checked>Correct Answer?</label></div></div><div class=\" col-sm-offset-1 col-sm-2\"><input type=\"text\" class=\"form-control readonly\" id=\"'+ansId2+'\" name=\"'+ansId2+'\" value=\"False\" readonly></div><div class=\"col-sm-2\"><div class=\"radio\"><label><input type=\"radio\" name=\"'+radioID+'\" value=\"'+ansId2+'\">Correct Answer?</label></div></div></div>';
						prev=num;
						num++;

						newdiv = document.createElement('button');
						newdiv.setAttribute("id","TFB");
						newdiv.setAttribute("type", "button");
						newdiv.setAttribute("class", "btn btn-primary");
						newdiv.setAttribute("style", "margin-left:34%;");
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
						var radioID="iscorrect"+Number(num);
						newdiv.setAttribute('id', divIdName);
						newdiv.setAttribute("class", "tfqs");
						ni.appendChild(newdiv);

						ni=document.getElementById(divIdName);
						ni.innerHTML='<h2><small>Question Number '+num+'</small></h2><div class=\"form-group\"><label class=\"control-label col-sm-4\">Please type your new question here:</label></div><div class=\"form-group\"><div class=\"col-sm-offset-1 col-sm-9\"><textarea id=\"'+quesId+'\" name=\"'+quesId+'\" class=\"tarea form-control\" rows=\"5\" required></textarea></div></div><div class=\"form-group\"><label  class=\"control-label col-sm-4\">Please select the correct answer:</label></div><div class=\"form-group\"><div class=\" col-sm-offset-1 col-sm-2\"><input type=\"text\" class=\"form-control readonly\" id=\"'+ansId1+'\" name=\"'+ansId1+'\" value=\"True\" readonly></div><div class=\"col-sm-2\"><div class=\"radio\"><label><input type=\"radio\" name=\"'+radioID+'\" value=\"'+ansId1+'\" checked>Correct Answer?</label></div></div><div class=\" col-sm-offset-1 col-sm-2\"><input type=\"text\" class=\"form-control readonly\" id=\"'+ansId2+'\" name=\"'+ansId2+'\" value=\"False\" readonly></div><div class=\"col-sm-2\"><div class=\"radio\"><label><input type=\"radio\" name=\"'+radioID+'\" value=\"'+ansId2+'\">Correct Answer?</label></div></div></div>';

						newdiv = document.createElement('button');
						newdiv.setAttribute("name","sub");
						newdiv.setAttribute("value","submitQues");
						newdiv.setAttribute("type", "submit");
						newdiv.setAttribute("class", "btn btn-primary");
						newdiv.setAttribute("style", "margin-left:45%;");
						ni.appendChild(newdiv);
						newdiv.innerHTML="Submit Quiz";
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
						var radioID="iscorrect"+Number(num);
						newdiv.setAttribute('id', divIdName);
						newdiv.setAttribute("class", "tfqs");
						ni.appendChild(newdiv);

						ni=document.getElementById(divIdName);
						ni.innerHTML='<h2><small>Question Number '+num+'</small></h2><div class=\"form-group\"><label class=\"control-label col-sm-4\">Please type your new question here:</label></div><div class=\"form-group\"><div class=\"col-sm-offset-1 col-sm-9\"><textarea id=\"'+quesId+'\" name=\"'+quesId+'\" class=\"tarea form-control\" rows=\"5\" required></textarea></div></div><div class=\"form-group\"><label  class=\"control-label col-sm-4\">Please select the correct answer:</label></div><div class=\"form-group\"><div class=\" col-sm-offset-1 col-sm-2\"><input type=\"text\" class=\"form-control readonly\" id=\"'+ansId1+'\" name=\"'+ansId1+'\" value=\"True\" readonly></div><div class=\"col-sm-2\"><div class=\"radio\"><label><input type=\"radio\" name=\"'+radioID+'\" value=\"'+ansId1+'\" checked>Correct Answer?</label></div></div><div class=\" col-sm-offset-1 col-sm-2\"><input type=\"text\" class=\"form-control readonly\" id=\"'+ansId2+'\" name=\"'+ansId2+'\" value=\"False\" readonly></div><div class=\"col-sm-2\"><div class=\"radio\"><label><input type=\"radio\" name=\"'+radioID+'\" value=\"'+ansId2+'\">Correct Answer?</label></div></div></div>';
						prev=num;
						num++;

						newdiv = document.createElement('button');
						newdiv.setAttribute("type", "button");
						newdiv.setAttribute("class", "btn btn-primary");
						newdiv.setAttribute("style", "margin-left:34%;");
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
					window.scrollBy(0, 350);
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
				newdiv.setAttribute('action', 'addQuestions2.php');
				newdiv.setAttribute('name', 'addQuestion');
				newdiv.setAttribute('method', 'post');
				newdiv.setAttribute('class','form-horizontal');
				ni.appendChild(newdiv);

				ni = document.getElementById("quesForm");
				newdiv = document.createElement('div');
				var divIdName = 'qcontent' + Number(num);
				var quesId="MCQq"+Number(num);
				var ansId1="q"+Number(num)+"ans1";
				var ansId2="q"+Number(num)+"ans2";
				var ansId3="q"+Number(num)+"ans3";
				var ansId4="q"+Number(num)+"ans4";
				var radioID="iscorrect"+Number(num);
				newdiv.setAttribute('id', divIdName);
				newdiv.setAttribute("class","mcqs");
				ni.appendChild(newdiv);

				ni=document.getElementById(divIdName);
				ni.innerHTML='<h2><small>Question Number '+num+'</small></h2><div class=\"form-group\"><label class=\"control-label col-sm-4\">Please type your new question here:</label></div><div class=\"form-group\"><div class=\"col-sm-offset-1 col-sm-9\"><textarea id=\"'+quesId+'\" name=\"'+quesId+'\" class=\"tarea form-control\" rows=\"5\" required></textarea></div></div><div class=\"form-group\"><label  class=\"control-label col-sm-4\">Please select the correct answer:</label></div><div class=\"form-group\"><div class=\" col-sm-offset-1 col-sm-2\"><input type=\"text\" id=\"'+ansId1+'\" class=\"form-control\" name=\"'+ansId1+'\" placeholder=\"Answer 1\"></div><div class=\"col-sm-2\"><div class=\"radio\"><label><input type=\"radio\" name=\"'+radioID+'\" value=\"'+ansId1+'\" checked>Correct Answer?</label></div></div><div class=\" col-sm-offset-1 col-sm-2\"><input type=\"text\" class=\"form-control \" id=\"'+ansId2+'\" name=\"'+ansId2+'\" placeholder=\"Answer 2\"></div><div class=\"col-sm-2\"><div class=\"radio\"><label><input type=\"radio\" name=\"'+radioID+'\" value=\"'+ansId2+'\">Correct Answer?</label></div></div></div><div class=\"form-group\"><div class=\" col-sm-offset-1 col-sm-2\"><input type=\"text\" class=\"form-control\" id=\"'+ansId3+'\" name=\"'+ansId3+'\" placeholder=\"Answer 3\"></div><div class=\"col-sm-2\"><div class=\"radio\"><label><input type=\"radio\" name=\"'+radioID+'\" value=\"'+ansId3+'\">Correct Answer?</label></div></div><div class=\" col-sm-offset-1 col-sm-2\"><input type=\"text\" class=\"form-control\" id=\"'+ansId4+'\" name=\"'+ansId4+'\" placeholder=\"Answer 4\"></div><div class=\"col-sm-2\"><div class=\"radio\"><label><input type=\"radio\" name=\"'+radioID+'\" value=\"'+ansId4+'\">Correct Answer?</label></div></div></div>';
				prev=num;
				num++;

				newdiv = document.createElement('button');
				newdiv.setAttribute("type", "button");
				newdiv.setAttribute("class", "btn btn-primary");
				newdiv.setAttribute("style", "margin-left:34%;");
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
				var ansId3="q"+Number(num)+"ans3";
				var ansId4="q"+Number(num)+"ans4";
				var radioID="iscorrect"+Number(num);
				newdiv.setAttribute('id', divIdName);
				newdiv.setAttribute("class","mcqs");
				ni.appendChild(newdiv);

				ni=document.getElementById(divIdName);
				ni.innerHTML='<h2><small>Question Number '+num+'</small></h2><div class=\"form-group\"><label class=\"control-label col-sm-4\">Please type your new question here:</label></div><div class=\"form-group\"><div class=\"col-sm-offset-1 col-sm-9\"><textarea id=\"'+quesId+'\" name=\"'+quesId+'\" class=\"tarea form-control\" rows=\"5\" required></textarea></div></div><div class=\"form-group\"><label  class=\"control-label col-sm-4\">Please select the correct answer:</label></div><div class=\"form-group\"><div class=\" col-sm-offset-1 col-sm-2\"><input type=\"text\" id=\"'+ansId1+'\" class=\"form-control\" name=\"'+ansId1+'\" placeholder=\"Answer 1\"></div><div class=\"col-sm-2\"><div class=\"radio\"><label><input type=\"radio\" name=\"'+radioID+'\" value=\"'+ansId1+'\" checked>Correct Answer?</label></div></div><div class=\" col-sm-offset-1 col-sm-2\"><input type=\"text\" class=\"form-control \" id=\"'+ansId2+'\" name=\"'+ansId2+'\" placeholder=\"Answer 2\"></div><div class=\"col-sm-2\"><div class=\"radio\"><label><input type=\"radio\" name=\"'+radioID+'\" value=\"'+ansId2+'\">Correct Answer?</label></div></div></div><div class=\"form-group\"><div class=\" col-sm-offset-1 col-sm-2\"><input type=\"text\" class=\"form-control\" id=\"'+ansId3+'\" name=\"'+ansId3+'\" placeholder=\"Answer 3\"></div><div class=\"col-sm-2\"><div class=\"radio\"><label><input type=\"radio\" name=\"'+radioID+'\" value=\"'+ansId3+'\">Correct Answer?</label></div></div><div class=\" col-sm-offset-1 col-sm-2\"><input type=\"text\" class=\"form-control\" id=\"'+ansId4+'\" name=\"'+ansId4+'\" placeholder=\"Answer 4\"></div><div class=\"col-sm-2\"><div class=\"radio\"><label><input type=\"radio\" name=\"'+radioID+'\" value=\"'+ansId4+'\">Correct Answer?</label></div></div></div>';

				newdiv = document.createElement('button');
				newdiv.setAttribute("name","sub");
				newdiv.setAttribute("value","submitQues");
				newdiv.setAttribute("type", "submit");
				newdiv.setAttribute("class", "btn btn-primary");
				newdiv.setAttribute("style", "margin-left:45%;");
				ni.appendChild(newdiv);
				newdiv.innerHTML="Submit Quiz";
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
				var ansId3="q"+Number(num)+"ans3";
				var ansId4="q"+Number(num)+"ans4";
				var radioID="iscorrect"+Number(num);
				newdiv.setAttribute('id', divIdName);
				newdiv.setAttribute("class","mcqs");
				ni.appendChild(newdiv);

				ni=document.getElementById(divIdName);
				ni.innerHTML='<h2><small>Question Number '+num+'</small></h2><div class=\"form-group\"><label class=\"control-label col-sm-4\">Please type your new question here:</label></div><div class=\"form-group\"><div class=\"col-sm-offset-1 col-sm-9\"><textarea id=\"'+quesId+'\" name=\"'+quesId+'\" class=\"tarea form-control\" rows=\"5\" required></textarea></div></div><div class=\"form-group\"><label  class=\"control-label col-sm-4\">Please select the correct answer:</label></div><div class=\"form-group\"><div class=\" col-sm-offset-1 col-sm-2\"><input type=\"text\" id=\"'+ansId1+'\" class=\"form-control\" name=\"'+ansId1+'\" placeholder=\"Answer 1\"></div><div class=\"col-sm-2\"><div class=\"radio\"><label><input type=\"radio\" name=\"'+radioID+'\" value=\"'+ansId1+'\" checked>Correct Answer?</label></div></div><div class=\" col-sm-offset-1 col-sm-2\"><input type=\"text\" class=\"form-control \" id=\"'+ansId2+'\" name=\"'+ansId2+'\" placeholder=\"Answer 2\"></div><div class=\"col-sm-2\"><div class=\"radio\"><label><input type=\"radio\" name=\"'+radioID+'\" value=\"'+ansId2+'\">Correct Answer?</label></div></div></div><div class=\"form-group\"><div class=\" col-sm-offset-1 col-sm-2\"><input type=\"text\" class=\"form-control\" id=\"'+ansId3+'\" name=\"'+ansId3+'\" placeholder=\"Answer 3\"></div><div class=\"col-sm-2\"><div class=\"radio\"><label><input type=\"radio\" name=\"'+radioID+'\" value=\"'+ansId3+'\">Correct Answer?</label></div></div><div class=\" col-sm-offset-1 col-sm-2\"><input type=\"text\" class=\"form-control\" id=\"'+ansId4+'\" name=\"'+ansId4+'\" placeholder=\"Answer 4\"></div><div class=\"col-sm-2\"><div class=\"radio\"><label><input type=\"radio\" name=\"'+radioID+'\" value=\"'+ansId4+'\">Correct Answer?</label></div></div></div>';
				prev=num;
				num++;

				newdiv = document.createElement('button');
				newdiv.setAttribute("type", "button");
				newdiv.setAttribute("class", "btn btn-primary");
				newdiv.setAttribute("style", "margin-left:34%;");
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
			window.scrollBy(0, 400);
		}
	</script>


</body>

</html>
