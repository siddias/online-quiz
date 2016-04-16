<?php
session_start();
require_once 'class.user.php';

$user = new USER();

if(!$user->is_logged_in())
	$user->redirect('index.php');

try{
	$id = $_SESSION['userSession'];
	$stmt = $user->runQuery("SELECT q.quizId,name,sub,duration,startTime,endTime from quizlist q,live_quiz".$id." l WHERE q.quizId=l.quizId");
	$stmt->execute();
	$result = $stmt->fetchAll(PDO::FETCH_ASSOC);

	if($stmt->rowCount()==0){
		$msg = "No live quizzes at the moment!";
        $mType = "info";
	}
	/*if($_SESSION['userType']=='T'){
		$stmt = $user->runQuery("SELECT  from quizlist q,live_quiz".$id." l WHERE q.quizId=l.quizId");
		$stmt->execute();
	}*/
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
	    <link rel="stylesheet" href="css/layout.css" />
		<link rel="stylesheet" href="lib/lobibox/css/lobibox.min.css" />

		<script src="lib/jquery/jquery.min.js"></script>
		<script src="lib/bootstrap/js/bootstrap.min.js"></script>
		<script src="lib/lobibox/js/lobibox.min.js"></script>
		<script src="lib/lobibox/js/messageboxes.min.js"></script>
</head>
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
                    <li><a href="past.php">Past Quiz</a></li>
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
	<?php
        if(isset($msg))
        {
    ?>
        <script>
            var t = "<?php echo $mType?>";
            var m = "<?php echo $msg?>";
            Lobibox.alert(t, { msg: m,callback: function(lobibox){
				window.location="home.php";
			}
		});
        </script>
    <?php
		exit();
    }
    ?>
    <div class="container stuff">
    <table id="example" border="2px" class="display" cellspacing="0" width="100%">
		<caption>LIVE QUIZZES</caption>
		<thead>
		<tr>

			<?php
				if($_SESSION['userType']=='T'){
			?>
				<th>SL No</th>
				<th>Start Time</th>
				<th>End Time</th>
				<th>Quiz Name</th>
				<th>Subject</th>
				<th>Duration</th>
				<th>Submissions</th>
			<?php
		}else{
			 ?>
				<th>SL No</th>
	 			<th>Start Time</th>
	 			<th>Quiz Name</th>
	 			<th>Subject</th>
			<?php
				}
			 ?>
			<th></th>
		</tr>
		</thead>
		<tbody>
			<?php
				if($_SESSION['userType']=='S'){
				$i=1;
				foreach($result as $row){
			?>
					<tr>
						<td><?=$i?></td>
						<td><?=$row['startTime']?></td>
						<td><?=$row['name']?></td>
						<td><?=$row['sub']?></td>
						<td><a href='qValidate.php?id=<?=$row['quizId']?>'>Take Quiz</td>
					</tr>
			<?php
				$i++;
				}
			}else{
				$i=1;
				foreach($result as $row){
			?>
					<tr>
						<td><?=$i?></td>
						<td><?=$row['startTime']?></td>
						<td><?=$row['endTime']?></td>
						<td><?=$row['name']?></td>
						<td><?=$row['sub']?></td>
						<td><?=$row['duration']?></td>
						<td><a href='qValidate.php?id=<?=$row['quizId']?>'>Take Quiz</td>
					</tr>
			<?php
				$i++;
				}
			}
			?>
		</tbody>
    </table>
	</div>
    <div class="footer">
    </div>

</body>
</html>
