<?php
session_start();
require_once 'class.user.php';

$user = new USER();

if(!$user->is_logged_in())
	$user->redirect('index.php');

if(isset($_SESSION['valid']) && isset($_GET['id']) && $_GET['id']!="")
{
	$qid = preg_replace('/[^0-9]/', "", $_GET['id']);
	$id = $_SESSION['userSession'];
	try
	{
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
	}catch(PDOException $ex){
		echo $ex->getMessage();
		exit();
	}
}
else if(isset($_GET['id']) && $_GET['id']!=""){
	$qid = preg_replace('/[^0-9]/', "", $_GET['id']);
	$user->redirect("qValidate.php?id=$qid");
}
else {
	$user->redirect("qValidate.php?id=0");
}
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Quiz Page</title>
    <style>

		div.scroll { width:15%; overflow:auto; float:left; }
		div.mainContent	{ float:left; margin-left:20px; line-height:3; width:75%; }
		#left { width: 29%;}
		#center { width: 39%; }
		#right { width: 29%; }
            label {
                cursor: pointer;
            }
    </style>
    <link rel="stylesheet" href="css/bootstrap.min.css">
	<script src="js/jquery-1.12.1.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
	<script>
	var q = <?php echo json_encode($arr, JSON_PRETTY_PRINT) ?>;
	var prev, current;
	var aIds = {};
	var limit = <?php echo $_SESSION['num'] ?>;

	function startTimer(duration, display) {
		var start = Date.now(),
			diff,
			minutes,
			seconds, ob;

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

			if (diff == 0) {
				alert("Your time is up!");
				clearInterval(ob);
				sendData();
			}

			if (diff == 300)
				alert("5 mins remaining!");
		};
		// we don't want to wait a full second before the timer starts
		timer();
		ob = setInterval(timer, 1000);
	}

	function check() {
		var rads = document.getElementsByName("rads");
		if (rads.length == 0)
			return false;
		for (var i = 0; i < rads.length; i++) {
			if (rads[i].checked) {
				var val = rads[i].value;
				return val;
			}
		}
		return false;
	}

	function init() {
		prev=current=1;
		document.getElementById("main").style.visibility = "visible";
		document.getElementById("left").className = "btn btn-primary disabled";
		var nod = ("" + limit).length;
		if (Math.ceil(limit / 2) > 12)
			document.getElementById("scrollbar").style.height = 37 + "em";
		var pad = "";
		for (var i = 1; i <= nod; i++)
			pad += "0";
		for (var i = 1; i <= limit; i++) {
			if (i % 2 == 1)
				addButtonElement("panel1", pad.substring(0, pad.length - ("" + i).length) + i);
			else
				addButtonElement("panel2", pad.substring(0, pad.length - ("" + i).length) + i);

		}
		if (limit % 2 == 1) {
			var ni = document.getElementById("panel2");
			var newdiv = document.createElement('button');
			newdiv.setAttribute('id', "hidden");
			newdiv.setAttribute("type", "button");
			newdiv.setAttribute("class", "btn btn-default btn-lg");
			newdiv.setAttribute("style", "visibility:hidden");
			newdiv.innerHTML = "" + pad;
			ni.appendChild(newdiv);
		}
		current = 1;
		document.getElementById('button1').click();
		document.getElementById('button1').focus();
		startTimer(<?php echo ($_SESSION['duration']*60) ?>, document.getElementById("timer"));
	}

	function addButtonElement(panel, num) {
		var ni = document.getElementById(panel);
		var newdiv = document.createElement('button');
		var divIdName = 'button' + Number(num);
		newdiv.setAttribute('id', divIdName);
		newdiv.setAttribute("type", "button");
		newdiv.setAttribute("class", "btn btn-default btn-lg");
		newdiv.onclick = quesDiv;
		newdiv.innerHTML = num + " ";
		ni.appendChild(newdiv);
	}

	function addButtonElement(panel, num) {
		var ni = document.getElementById(panel);
		var newdiv = document.createElement('button');
		var divIdName = 'button' + Number(num);
		newdiv.setAttribute('id', divIdName);
		newdiv.setAttribute("type", "button");
		newdiv.setAttribute("class", "btn btn-default btn-lg");
		newdiv.onclick = quesDiv;
		newdiv.innerHTML = num + " ";
		ni.appendChild(newdiv);

	}

	function quesDiv() {
		var ni = document.getElementById("ques"),
			k, ans = "";
		var i = this.id.substring(this.id.search(/\d/));
		current = Number(i);

		if (current == 1) {
			document.getElementById("left").className = "btn btn-primary disabled";
			document.getElementById("right").innerHTML = "Next Question";
			document.getElementById("right").onclick=nextQues;
		} else if (current == limit) {
			document.getElementById("right").onclick = sendData;
			document.getElementById("right").innerHTML = "Submit";
			document.getElementById("left").className="btn btn-primary";
		} else {
			document.getElementById("left").className = "btn btn-primary";
			document.getElementById("right").onclick = nextQues;
			document.getElementById("right").innerHTML = "Next Question";
		}

		checked = check();
		if (checked != false)
			aIds[q[prev - 1]['qId']] = checked;

		if (aIds[q[current - 1]['qId']] != undefined)
			k = aIds[q[current - 1]['qId']];

		for (i = 0; i < q[current - 1]['answers'].length; i++) {
			x = q[current - 1]['answers'][i];
			for (y in x) {
				if (y == k)
					ans += '<label><input type="radio" name="rads" checked="checked" value="' + y + '">' + x[y] + '</label><br/><br/>';
				else
					ans += '<label><input type="radio" name="rads" value="' + y + '">' + x[y] + '</label><br/><br/>';
			}
		}

		ni.innerHTML = "Question Number " + current + "<br/>" + q[current - 1]['question'];
		document.getElementById('answers').innerHTML = ans;
		if (this.className.search("btn btn-default") == 0)
			document.getElementById("center").innerHTML = "Flag";
		else
			document.getElementById("center").innerHTML = "Unflag";

		prev = current;
	}

	function nextQues() {
		document.getElementById("left").className = "btn btn-primary";
		if (current == limit) {
			document.getElementById("right").onclick = sendData;
			document.getElementById("right").click();
		} else if ((current += 1) == limit) {
			document.getElementById("right").innerHTML = "Submit";
		} else {
			document.getElementById("right").onclick = nextQues;
			document.getElementById("right").innerHTML = "Next Question";
		}
		document.getElementById('button' + current).click();
		document.getElementById('button' + current).focus();
	}

	function prevQues() {
		if (current == limit) {
			document.getElementById("right").onclick = nextQues;
			document.getElementById("right").innerHTML = "Next Question";
		}
		if ((current -= 1) == 1) {
			document.getElementById("left").className = "btn btn-primary disabled";
		}
		document.getElementById('button' + current).click();
		document.getElementById('button' + current).focus();
	}


	function markUnmarkQues() {
		var b = document.getElementById("button" + current);
		if (b.className.search("btn btn-default") == 0) {
			b.className = "btn btn-warning btn-lg";
			document.getElementById("center").innerHTML = "Unflag";
		} else {
			b.className = "btn btn-default btn-lg";
			b.focus();
			document.getElementById("center").innerHTML = "Flag";
		}
	}

	function sendData() {
		document.getElementById('button' + current).click();
		document.getElementById('button' + current).focus();
		$.ajax({
			type: "POST",
			url: "userAnswers.php",
			data: aIds, //no need to call JSON.stringify etc... jQ does this for you
			cache: false,
			success: function(response) { //check response: it's always good to check server output when developing...
				alert(response);
				window.location = "past.php";
			}
		});
	}
    </script>

</head>

<body onload="init()">

    <div class="container">
        <h2><?php echo $_SESSION['qName']?></h2>
        <h2> Menu bar </h2>

        <div class="main" id="main">

            <div class="scroll" id="scrollbar">
                <div class="btn-group-vertical" id="panel1"> </div>
                <div class="btn-group-vertical" id="panel2"> </div>
            </div>

            <div class="mainContent">
				<div id="timer"></div>
				<div id="ques"></div>
				<div id="answers"></div>
                <button type="button" class="btn btn-primary" id="left" onclick="prevQues()">Previous question</button>
                <button type="button" class="btn btn-primary" id="center" onclick="markUnmarkQues()">Flag</button>
                <button type="button" class="btn btn-primary" id="right" onclick="nextQues()">Next question</button>
            </div>

        </div>
    </div>

</body>

</html>
<!--timer, question,answers,prev,next,finish
<div id="content">
	<div id="timer"></div>
	<div id="question"></div>
	<div id="answers"></div>
	<button id="prev" style="display:none" onclick="showQuestion(-1,true)">Prev</button>
	<button id="next" style="display:none" onclick="showQuestion(1,true)">Next</button>
	<button id="finish" style="display:none" onclick="sendData()">Finish</button>
</div>
-->
