<?php

session_start();
require_once 'class.user.php';

$user = new USER();
if(!$user->is_logged_in() || $_SESSION['userType']!='T')
	$user->redirect('index.php');

if(isset($_POST['submit']) && $_POST['submit'] != "")
{
    if(!isset($_POST['quizName']) || $_POST['quizName'] == "" ||
    !isset($_POST['duration']) || $_POST['duration'] == "" || !isset($_POST['sub']) || $_POST['sub'] == "" ||
    !isset($_POST['startTime']) || $_POST['startTime'] == "" || !isset($_POST['endTime']) || $_POST['endTime'] == ""
	|| !isset($_POST['num']) || $_POST['num'] == "")
    {
        echo "Sorry, important data to submit your question is missing. Please press back in your browser and try again and make sure you select a correct answer for the question.";
        exit();
    }
    try
    {

        $stmt = $user->runQuery("INSERT INTO quizlist(userId,name,sub,duration,startTime,endTime,numQuestions)
                                     VALUES(:uid,:name,:sub,:duration,:sTime,:eTime,:num)");
        $stmt->bindparam(":uid",$_SESSION['userSession']);
        $stmt->bindparam(":name",$_POST['quizName']);
        $stmt->bindparam(":sub",$_POST['sub']);
        $stmt->bindparam(":duration",$_POST['duration']);
        $stmt->bindparam(":sTime",$_POST['startTime']);
        $stmt->bindparam(":eTime",$_POST['endTime']);
		$stmt->bindparam(":num",$_POST['num']);
        $stmt->execute();

        $id = $user->lasdID();
        $stmt = $user->runQuery("CREATE TABLE quiz".$id."_questions(qId INT(10) NOT NULL AUTO_INCREMENT,question VARCHAR(100) NOT NULL, type ENUM('TF','MC','FB') NOT NULL, PRIMARY KEY(qId))ENGINE = InnoDB");
        $stmt->execute();

        $stmt = $user->runQuery("CREATE TABLE quiz".$id."_answers(aId INT(10) NOT NULL AUTO_INCREMENT,qId INT(10) NOT NULL,answer VARCHAR(100) NOT NULL,correct BOOLEAN, PRIMARY KEY(aId),FOREIGN KEY(qId) REFERENCES quiz".$id."_questions(qId) ON DELETE CASCADE)ENGINE = InnoDB");
        $stmt->execute();

        $stmt = $user->runQuery("CREATE TABLE quiz".$id."_takers(id INT(10) NOT NULL AUTO_INCREMENT,userId INT(10) NOT NULL,taken BOOLEAN, score INT(10) NOT NULL,submitTime DATETIME NOT NULL,PRIMARY KEY(id),FOREIGN KEY(userId) REFERENCES members(userId) ON DELETE CASCADE)ENGINE = InnoDB");
        $stmt->execute();
    }
    catch(PDOException $ex)
    {
        echo $ex->getMessage();
		exit();
    }
    $_SESSION['quizId']=$id;
	$_SESSION['qno']=1;
	$_SESSION['num']=$_POST['num'];
    header('location: addQuestions.php');
}
?>





<!DOCTYPE html>
<html lang="en">

<head>
    <title>Set-Quiz</title>
    <link charset="utf-8">

    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/bootstrap-datetimepicker.min.css" />

    <style>
        .classForm{
            margin-top: 48px;
            margin-left: auto;
            margin-right: auto;
            width: 780px;
            border:5px solid;
            padding: 10px;
            width:50%;
        }
	#footer {
	position:relative;
	bottom:0;
	margin-top:2%;
	width:100%;
	padding: 15px 10px;
	background: #272f35;
	min-height: 60px;
	padding-top: 10px;
	z-index: 1;
	}
	.navbar {
	padding: 0;
	transition: background 0.5s ease-in-out 0s, padding 5.5s ease-in-out 0s;
	line-height: 2px;
	padding: 4px;
	font-size:20px;
	min-height: 60px;
	}
    </style>
</head>

<body style="background-color: #d6d6c2;">

<nav class="navbar navbar-inverse">
  <div class="container-fluid">
    <div class="navbar-header">
      <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>                        
      </button>
   
    </div>
    <div class="collapse navbar-collapse" id="myNavbar">
	<div class="wo">
      <ul class="nav navbar-nav">
        <li class="active"><a href="#">  Home   </a></li>
         <li ><a href="#"> Live Quiz</a></li>
        <li><a href="#"> Past Quiz</a></li>
        
      </ul>
      <ul class="nav navbar-nav navbar-right">
        <li class="dropdown">
		  <a class="dropdown-toggle" data-toggle="dropdown" href="#"><span class="glyphicon glyphicon-user"></span><span class="caret"></span> </a>
		   <ul class="dropdown-menu"style="background-color:#d6d6c2;">
            <li style="text-align:center;min-height:25px;padding-bottom: 5px;"><a  href="#">Edit profile</a></li>
            <li style="text-align:center;min-height:25px;padding-bottom: 5px;"><a href="#">Register</a></li>
            <li style="text-align:center;min-height:25px;padding-bottom: 5px;"><a href="logout.php">signout</a></li>
          </ul>
        </li>
        
      </ul>
	  </div>
    </div>
  </div>
</nav>

    <div class="classForm">
        <form action="setQuiz.php" method="POST">
            <label>Name of Quiz:&nbsp;<input type="text" name="quizName" required maxlength="50" /></label><br/>
            <label>Duration in minutes:&nbsp;<input type="number" name="duration" value="20" required /></label><br/>
            <label>Subject:&nbsp;<input type="text" name="sub" required maxlength="50"/></label><br/>
			<label>Number of Questions&nbsp;<input type="number" name="num" value="20" required /></label><br/>
            <p>Start:<div class="container">

                <div class='col-md-5' >
                    <div class="form-group">
                        <div class='input-group date' id='datetimepicker6'>
                            <input type='text' class="form-control readonly" name="startTime" required  />
                            <span class="input-group-addon">
                    <span class="glyphicon glyphicon-calendar"></span>
                            </span>
                        </div>
                    </div>
                </div>
            </p>
            <p style="clear:both" />
            <p>End:
                <div class='col-md-5'>
                    <div class="form-group">
                        <div class='input-group date' id='datetimepicker7'>
                            <input type='text' class="form-control" name="endTime" required />
                            <span class="input-group-addon">
                    <span class="glyphicon glyphicon-calendar"></span>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            </p>
            <input type="submit" name="submit" value="Create Quiz" />
        </form>
    </div>

  <div id="footer">
      <span class="a" style="padding:40px;">   <a href="#"> HOME</a></span> 
	   <span class="a" style="padding:40px;">   <a href="#"> CONTACT</a></span> 
	   <span class="a" style="padding:40px;">   <a href="#"> ABOUT</a></span> 
	   <span class="a" style="padding:40px;">   <a href="#"> REGISTER</a></span> 
            </div>
            <!-- End Footer -->
        </div>

    <script src="js/moment.min.js"></script>
    <script src="js/jquery-2.2.1.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/bootstrap-datetimepicker.min.js"></script>
    <script type="text/javascript">
        $(function() {
            $('#datetimepicker6').datetimepicker({
                format: "YYYY-MM-DD HH:mm",
                ignoreReadonly: true,
                useCurrent: false,
                minDate: new Date()
            });
            $('#datetimepicker7').datetimepicker({
				format: "YYYY-MM-DD HH:mm",
                ignoreReadonly: true,
                useCurrent: false
            });
            $("#datetimepicker6").on("dp.change", function(e) {
                $('#datetimepicker7').data("DateTimePicker").minDate(e.date);
            });
            $("#datetimepicker7").on("dp.change", function(e) {
                $('#datetimepicker6').data("DateTimePicker").maxDate(e.date);
            });
        });
    </script>
    <script>
    $(".readonly").keydown(function(e){
        e.preventDefault();
    });
</script>

</body>

</html>