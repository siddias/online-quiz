<?php
session_start();
require_once 'class.user.php';

$user = new USER();

if(!$user->is_logged_in())
	$user->redirect('index.php');

require_once 'updates.php';

try{
	$id = $_SESSION['userId'];
	if($_SESSION['userType']=='S'){
		$stmt = $user->runQuery("SELECT q.quizId,name,sub,score,p.submitDate,q.numQuestions from quizlist q,past_quiz".$id." p WHERE q.quizId=p.quizId");
		$stmt->execute();
		$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
	}
	else {
		$stmt = $user->runQuery("SELECT q.quizId,name,sub,duration,startTime,endTime,numQuizTakers,p.numSubmissions,p.scoreAvg from quizlist q,past_quiz".$id." p WHERE q.quizId=p.quizId");
		$stmt->execute();
		$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
	}
	if($stmt->rowCount()==0){
		$msg = "No Record of Past Quizzes!";
        $mType = "info";
	}
}
catch(PDOException $ex){
	echo $ex->getMessage();
	exit();
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Past-Quizes</title>

    <meta http-equiv="Content-type" content="text/html; charset=utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">


    <link charset="utf-8">
		<link rel="stylesheet" href="lib/bootstrap/css/bootstrap.min.css">
	    <link rel="stylesheet" href="css/layout.css" />
		<link rel="stylesheet" href="lib/lobibox/css/lobibox.min.css" />
		<link rel="stylesheet" type="text/css" href="lib/dataTables/css/jquery.dataTables.min.css">
		<style>
			.tab{
				padding:20px;
			}
			.head{
				background-color:rgb(87, 95, 101);
				color:white;
			}
			.head>td{
				border:1px solid rgb(200, 205, 199);
			}
		</style>
		<script src="lib/jquery/jquery.min.js"></script>
		<script src="lib/bootstrap/js/bootstrap.min.js"></script>
		<script src="lib/lobibox/js/lobibox.min.js"></script>
		<script src="lib/lobibox/js/messageboxes.min.js"></script>
		<script type="text/javascript" language="javascript" src="lib/dataTables/js/jquery.dataTables.min.js"></script>
	    <script type="text/javascript" language="javascript" src="lib/dataTables/js/dataTables.select.min.js"></script>
		<script>
	        $(document).ready(function() {
	            $('#example').DataTable({
					"columnDefs": [
		   {"className": "dt-center", "targets": "_all"},
		    <?php
				if($_SESSION['userType']=='T'){
						echo '{ "targets": [6],"orderable": false, "visible":true}';
				}
			?>
		 ],
					"pageLength": 50
	            });
	        });

	    </script>
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
                    <li><a href="home.php"><span class="glyphicon glyphicon-home"></span></a></li>
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
	<div class="tab">
		<table id="example" class="display cell-border hover compact stripe" cellspacing="0" width="100%" style="border:1px solid rgb(200, 205, 199); border-top-style:none;">
		<caption>PAST QUIZZES</caption>
		<thead>
		<tr class="head">
			<?php
				if($_SESSION['userType']=='S'){
			?>
				<td>SL No</td>
				<td>Date</td>
				<td>Quiz Name</td>
				<td>Subject</td>
				<td>Score</td>
			<?php
		}else{
			?>
			<td>SL No</td>
			<td>Date</td>
			<td>Quiz Name</td>
			<td>Subject</td>
			<td>Submissions</td>
			<td>Average Score</td>
			<td></td>
		<?php
			}
			?>
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
						<td>
							<?php if(is_null($row['submitDate']))
										echo "NA";
								else
									echo $row['submitDate'];
							?></td>
						<a><td><?=$row['name']?></td></a>
						<td><?=$row['sub']?></td>
						<td><?=$row['score']?>&#47;<?=$row['numQuestions']?></td>
					</tr>
			<?php
				$i++;
				}
			}else{
				$i=1;
				foreach($result as $row){
						$d = explode(" ", $row['endTime']);
				?>
						<tr>
							<td><?=$i?></td>
							<td><?=$d[0]?></td>
							<td><?=$row['name']?></td>
							<td><?=$row['sub']?></td>
							<td><?=$row['numSubmissions']?>&#47;<?=$row['numQuizTakers']?></td>
							<td><?=$row['scoreAvg']?></td>
							<td><a href='viewResults.php?id=<?=$row['quizId']?>'>View Results</td>
						</tr>
			<?php
				$i++;
				}
			}
			?>
		</tbody>
    </table>
</div>
</div>
<div class="footer">
</div>

</body>
</html>
