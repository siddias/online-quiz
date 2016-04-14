<?php
session_start();
require_once 'class.user.php';

$user = new USER();

if(!$user->is_logged_in())
	$user->redirect('index.php');

try{
	$id = $_SESSION['userSession'];
	$stmt = $user->runQuery("SELECT q.quizId,name,sub,duration from quizlist q,live_quiz".$id." l WHERE q.quizId=l.quizId");
	$stmt->execute();
	$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
catch(PDOException $ex){
	echo $ex->getMessage();
	exit();
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Live-Quizes</title>

    <meta http-equiv="Content-type" content="text/html; charset=utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">


    <link charset="utf-8">
		<link rel="stylesheet" href="lib/bootstrap/css/bootstrap.min.css">
	<link rel="stylesheet" href="lib/datetimepicker/css/bootstrap-datetimepicker.min.css" />
	    <link rel="stylesheet" href="css/layout.css" />

			<script src="lib/jquery/jquery.min.js"></script>
		    <script src="lib/bootstrap/js/bootstrap.min.js"></script>


<body class="wide comments example">

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
                    <li class="active"><a href="#"><span class="glyphicon glyphicon-home"></span></a></li>
                    <li><a href="live.php">Live Quiz</a></li>
                    <li><a href="past.php">Dead Quiz</a></li>
                </ul>
                <ul class="nav navbar-nav navbar-right">
                    <li class="dropdown">
                        <a class="dropdown-toggle" data-toggle="dropdown" href="#"><span class="glyphicon glyphicon-user"></span>User <span class="caret"></span></a>
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
    <table id="example" border="2px" class="display" cellspacing="0" width="100%">
		<caption>LIVE QUIZZES</caption>
		<thead>
		<tr>
			<th>SL No</th>
			<th>Quiz Name</th>
			<th>Subject</th>
			<th>Duration</th>
		</tr>
		</thead>
		<tbody>
			<?php
				$i=1;
				foreach($result as $row){
			?>
					<tr onclick="window.location='qValidate.php?id=<?=$row['quizId']?>'">
						<td><?=$i?></td>
						<a><td><?=$row['name']?></td></a>
						<td><?=$row['sub']?></td>
						<td><?=$row['duration']?></td>
					</tr>
			<?php
				$i++;
				}
			?>
		</tbody>
    </table>
	</div>
    <div class="footer">
    </div>

</body>
</html>
