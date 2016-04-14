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
    <style>
        .content {
            margin-top: 48px;
            margin-left: auto;
            margin-right: auto;
            width: 780px;
            border: #333 1px solid;
            border-radius: 12px;
            -moz-border-radius: 12px;
            padding: 12px;
            display: none;
        }

        .qdiv {
            width: 700px;
            margin-left: auto;
            margin-right: auto;
            text-align: center;
        }

        .tarea {
            width: 400px;
            height: 95px;
        }

        label {
            cursor: pointer;
            color: #06F;
        }
    </style>
</head>

<body>
    <div class="qdiv">
        <p style="color:#86b2e5;">
            <?php echo $msg; ?>
        </p>
        <h2>What type of question would you like to create?</h2>
        <button onClick="showDiv('TF', 'MC')">True/False</button>&nbsp;&nbsp;
        <button onClick="showDiv('MC', 'TF')">Multiple Choice</button>&nbsp;&nbsp;
        <span id="resetBtn">
                <button id="done">Done</button>
        </span>
    </div>
    <div class="content" id="TF">
        <h3>True or false</h3>
        <form action="addQuestions.php" name="addTFQuestion" method="post">
			<p>Question number <?=$_SESSION['qno']?></p>
            <strong>Please type your new question here</strong>
            <br/>
            <textarea id="tfDesc" name="desc" class="tarea" required></textarea>
            <br/>
            <br/>
            <strong>Please select whether true or false is the correct answer</strong>
            <br/>
            <input type="text" id="a1" name="a1" value="True" readonly>&nbsp;
            <label>
                <input type="radio" name="iscorrect" value="a1">Correct Answer?
            </label>
            <br/>
            <br/>
            <input type="text" id="a2" name="a2" value="False" readonly>&nbsp;
            <label>
                <input type="radio" name="iscorrect" value="a2">Correct Answer?
            </label>
            <br/>
            <br/>
            <input type="hidden" value='TF' name="type">
            <input type="submit" value="Add To Quiz">
        </form>
    </div>
    <div class="content" id="MC">
        <h3>Multiple Choice</h3>
        <form action="addQuestions.php" name="addMCQuestion" method="post">
			<p>Question number <?=$_SESSION['qno']?></p>
            <strong>Please type your new question here</strong>
            <br/>
            <textarea id="mcdesc" name="desc" class="tarea"></textarea>
            <br/>
            <br/>
            <strong>Answer 1</strong>
            <input type="text" id="mca1" name="a1">&nbsp;
            <label>
                <input type="radio" name="iscorrect" value="a1">Correct Answer?
            </label>
            <br/>
            <br/>
            <strong>Answer 2</strong>
            <input type="text" id="mca2" name="a2">&nbsp;
            <label>
                <input type="radio" name="iscorrect" value="a2">Correct Answer?
            </label>
            <br/>
            <br/>
            <strong>Answer 3</strong>
            <input type="text" id="mca3" name="a3">&nbsp;
            <label>
                <input type="radio" name="iscorrect" value="a3">Correct Answer?
            </label>
            <br/>
            <br/>
            <strong>Answer 4</strong>
            <input type="text" id="mca4" name="a4">&nbsp;
            <label>
                <input type="radio" name="iscorrect" value="a4">Correct Answer?
            </label>
            <br/>
            <br/>
            <input type="hidden" value='MC' name="type">
            <input type="submit" value="Add To Quiz">
        </form>
    </div>
	<script>
        function showDiv(el1, el2) {
            document.getElementById(el1).style.display = 'block';
            document.getElementById(el2).style.display = 'none';
        }
    	//document.getElementById("quit").onclick = function () {
        //window.location = "done.php?quit=yes";
    //}
	</script>
</body>

</html>
