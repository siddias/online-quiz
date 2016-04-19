<?php
session_start();
require_once 'class.user.php';

$user = new USER();

if(!$user->is_logged_in())
	$user->redirect('index.php');

if(isset($_GET['id']) && $_GET['id']!="")
{
	$qid =preg_replace('/[^0-9]/', "", $_GET['id']);
	$id = $_SESSION['userSession'];
	try{
		$stmt = $user->runQuery("SELECT * FROM quizlist q, past_quiz".$id." p WHERE q.quizId=p.quizId and q.quizId=$qid LIMIT 1");
		$stmt->execute();
		$result = $stmt->fetch(PDO::FETCH_ASSOC);

		if($stmt->rowCount()==0){
			$msg = "Invalid link";
			$mType = "error";
		}
		else {
			$stmt = $user->runQuery("SELECT numQuestions,numQuizTakers from quizlist WHERE quizId=$qid LIMIT 1");
			$stmt->execute();
			$result = $stmt->fetch(PDO::FETCH_ASSOC);
			$numQues = $result['numQuestions'];
			$numTakers = $result['numQuizTakers'];

			$stmt = $user->runQuery("SELECT * from quiz".$qid."_takers q, members m WHERE q.userId=m.userId");
			$stmt->execute();
			$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
			}
		}catch(PDOException $ex){
		echo $ex->getMessage();
		exit();
	}
}
else {
	$msg = "Invalid link";
	$mType = "error";
}
?>
<!DOCTYPE html>
<html>

<head>
	<meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Quiz Results</title>

	<link rel="stylesheet" href="lib/bootstrap/css/bootstrap.min.css">
	<link rel="stylesheet" href="css/layout.css" />
	<link rel="stylesheet" href="lib/lobibox/css/lobibox.min.css" />
	<link rel="stylesheet" type="text/css" href="lib/dataTables/css/jquery.dataTables.min.css">
	<style>
		.tab{
			padding:30px;
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
	<script src="lib/lobibox/js/lobibox.min.js"></script>
	<script src="lib/lobibox/js/messageboxes.min.js"></script>
	<script type="text/javascript" language="javascript" src="lib/dataTables/js/jquery.dataTables.min.js"></script>
    <script type="text/javascript" language="javascript" src="lib/dataTables/js/dataTables.select.min.js"></script>
	<script type="text/javascript" src="lib/tableExport/tableExport.js"></script>
	<script type="text/javascript" src="lib/tableExport/jquery.base64.js"></script>

	<script>
        $(document).ready(function() {
            $('#example').DataTable({
				"columnDefs": [
	   {"className": "dt-center", "targets": "_all"}
	 ],
				"pageLength": 50
            });
        });

    </script>
</head>

<body class="wide comments example">
    <?php
		if(isset($msg))
		{
	?>
		<script>
			var t = "<?php echo $mType?>";
			var m = "<?php echo $msg?>";
			Lobibox.alert(t,
				{ msg: m,
				callback: function(lobibox){
					window.location="home.php";
   				}
			});
		</script>
	<?php
	}
	?>
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
					<li><a href="#"><span class="glyphicon glyphicon-home"></span></a></li>
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
			<caption>RESULTS</caption>
			<thead>
			<tr class="head" >
					<th>SL No</th>
					<th>USN</th>
					<th>Name</th>
					<th>Score</th>
					<th>Percentage</th>
			</tr>
			</thead>
			<tbody>
				<?php
					$i=1;
					foreach($result as $row){
				?>
						<tr>
							<td><?=$i?></td>
							<td><?=$row['id']?></td>
							<td><?php echo $row['fname'].' '.$row['lname']?></td>
							<td><?=$row['score']?></td>
							<td><?php echo number_format($row['score']*100/$numQues,2)?>&#37;</td>
						</tr>
				<?php
					$i++;
					}
				?>
			</tbody>
		</table>
		</div>
		<button type="button" class="btn btn-primary btn-md" id="done" onclick="$('#example').tableExport({type:'excel',escape:'false'});">Get in Excel Sheet</button>
	</div>
	<div class="footer">
	</div>

	</body>
	</html>
