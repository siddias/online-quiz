<?php
session_start();
require_once 'class.user.php';

$user = new USER();

if(!$user->is_logged_in())
	$user->redirect('index.php');

if(isset($_GET['id']) && $_GET['id']!="" && !isset($_SESSION['started']))
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
			$_SESSION['num']=$result['numQuestions'];
			$_SESSION['duration']=$result['duration'];
			$stmt = $user->runQuery("SELECT * FROM quiz".$qid."_questions ORDER BY rand()");
			$stmt->execute();
			$row = $stmt->fetchAll(PDO::FETCH_ASSOC);
			$arr=[];
			foreach($row as $r){
				$qNum = $r['qId'];

				$stmt = $user->runQuery("SELECT * FROM quiz".$qid."_answers WHERE qId=$qNum ORDER BY rand()");
				$stmt->execute();

				$answers = [];
				while($y = $stmt->fetch(PDO::FETCH_ASSOC)){
					array_push($answers,array($y['aId']=>$y['answer']));
				}
				array_push($arr,array('qId'=>$qNum,'question'=>$r['question'],'type'=>$r['type'],'answers'=>$answers));
			}
		}
	}catch(PDOException $ex){
		echo $ex->getMessage();
		exit();
	}
}
?>
<!DOCTYPE html>
<html>

<head>
	<meta charset="utf-8">
	<title>Quiz Page</title>
	<style>
        #content {
            margin-top: 50px;
            margin-left: auto;
            margin-right: auto;
            width: 800px;
            border: #333 1px solid;
            border-radius: 12px;
            -moz-border-radius: 12px;
            padding: 12px;
			display:none;
        }

        label {
            cursor: pointer;
        }
    </style>
	<script src="http://code.jquery.com/jquery-1.11.0.min.js"></script>
    <script>
        var q = <?php echo json_encode($arr, JSON_PRETTY_PRINT) ?>;
		var current=-1;
		var aIds={};
        function startTimer(duration, display) {
            var start = Date.now(),
                diff,
                minutes,
                seconds,ob;

            function timer() {
                // get the number of seconds that have elapsed since
                // startTimer() was called
                diff = duration - (((Date.now() - start) / 1000) | 0);

                // does the same job as parseInt truncates the float
                minutes = (diff / 60) | 0;
                seconds = (diff % 60) | 0;

                minutes = minutes < 10 ? "0" + minutes : minutes;
                seconds = seconds < 10 ? "0" + seconds : seconds;

                display.textContent = minutes + ":" + seconds;

				if(diff==0){
					alert("Your time is up!");
					clearInterval(ob);
					sendData();
				}

				if(diff==300)
					alert("5 mins remaining!");
            };
            // we don't want to wait a full second before the timer starts
            timer();
            ob = setInterval(timer, 1000);
        }

		function check() {
			var rads = document.getElementsByName("rads");
			if(rads.length==0)
				return false;
			for ( var i = 0; i < rads.length; i++ ) {
				if ( rads[i].checked ){
					var val = rads[i].value;
					return val;
				}
			}
			return false;
		}
		function showQuestion(number,ch){
			var p,n,ans="",i,x,y,checked,k;
			p=document.getElementById("prev");
			n=document.getElementById("next");
			if(ch==true)
				number+=current;

			checked = check();
			if(checked!=false)
				aIds[q[current]['qId']]=checked;

			if(number<0 || number>=q.length)
				return false;

			if(number==0){
				p.style.display = 'block';
				p.disabled = true;
			}
			else{
				p.style.display = 'block';
				p.disabled = false;
			}
			if(number==q.length-1)
			{
				n.style.display = 'none';
				document.getElementById('finish').style.display = 'block';
			}
			else
			{
					n.style.display = 'block';
					document.getElementById('finish').style.display = 'none';
			}

			if(aIds[q[number]['qId']]!=undefined)
				k=aIds[q[number]['qId']];

			document.getElementById('question').innerHTML = "Current Question "+(number+1)+"/"+q.length+"<br/><br/>"+q[number]['question'];
			for(i=0;i<q[number]['answers'].length;i++){
				x=q[number]['answers'][i];
				for(y in x){
					if(y==k)
						ans+= '<label><input type="radio" name="rads" checked="checked" value="'+y+'">'+x[y]+'</label><br/><br/>';
					else
						ans+= '<label><input type="radio" name="rads" value="'+y+'">'+x[y]+'</label><br/><br/>';
				}
			}

			document.getElementById('answers').innerHTML = ans;
			current=number;
		}

        function start() {
			document.getElementById("start").style.display = 'none';
			document.getElementById("content").style.display = 'block';
			document.getElementById("next").style.display = 'block';
	        showQuestion(0,false);
			startTimer(<?php echo ($_SESSION['duration']*60) ?>, document.getElementById("timer"));
        }
	</script>
	<script>
	function sendData(){
		showQuestion(1,true);
		$.ajax({ type: "POST",
			url: "userAnswers.php",
			data: aIds,//no need to call JSON.stringify etc... jQ does this for you
			cache: false,
			success: function(response)
			{//check response: it's always good to check server output when developing...
				alert(response);
				window.location="past.php";
			}
   });
	}
    </script>
</head>

<body>
    <?php
	    if(isset($_GET['msg']))
	    {
	        $msg = strip_tags($_GET['msg']);
	        $msg = addslashes($msg);
	        echo $msg;
	    }
		else
		{
	?>
	        <button id="start" onclick="start()">Start Quiz</button>
	        <div id="content">
	            <div id="timer"></div>
	            <div id="question"></div>
	            <div id="answers"></div>
				<button id="prev" style="display:none" onclick = "showQuestion(-1,true)">Prev</button>
				<button id="next" style="display:none" onclick = "showQuestion(1,true)">Next</button>
				<button id="finish" style="display:none" onclick="sendData()">Finish</button>
	        </div>
		<?php
		}
		?>

</body>

</html>
