<?php
session_start();
require_once 'class.user.php';

$user = new USER();

if(!$user->is_logged_in())
	$user->redirect('index.php');


require_once 'updates.php';
try{
	$id = $_SESSION['userSession'];
	if($_SESSION['userType']=='S'){
		$stmt = $user->runQuery("SELECT q.quizId,name,sub,startTime from quizlist q,live_quiz".$id." l WHERE q.quizId=l.quizId");
		$stmt->execute();
		$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
	}
	else {
		$stmt = $user->runQuery("SELECT q.quizId,name,sub,duration,startTime,endTime,numQuizTakers,l.numSubmissions from quizlist q,live_quiz".$id." l WHERE q.quizId=l.quizId");
		$stmt->execute();
		$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
	}
	if($stmt->rowCount()==0){
		$msg = "No Live Quizzes At The Moment!";
        $mType = "info";
	}

}catch(PDOException $ex){
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
	<link rel="stylesheet" type="text/css" href="lib/dataTables/css/jquery.dataTables.min.css">
	<style>
	.tab{
		padding:10px;
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
	<script>
		$(document).ready(function() {
			$('#example').DataTable({
				"columnDefs": [
	   {"className": "dt-center", "targets": "_all"},
	   <?php
		if($_SESSION['userType']=='T'){
				echo '{ "targets": [7],"orderable": false, "visible":true}';
		}
		else {
		   echo '{ "targets": [4],"orderable": false, "visible":true}';
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
		<caption>LIVE QUIZZES</caption>
		<thead>
		<tr class="head">
			<?php
				if($_SESSION['userType']=='T'){
			?>
				<td>SL No</td>
				<td>Start Time</td>
				<td>End Time</td>
				<td>Quiz Name</td>
				<td>Subject</td>
				<td>Duration</td>
				<td>Submissions</td>
			<?php
		}else{
			 ?>
				<td>SL No</td>
	 			<td>Start Time</td>
	 			<td>Quiz Name</td>
	 			<td>Subject</td>
			<?php
				}
			 ?>
			 <td></td>
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
						<td><?=date('d-m-Y H:i:s',strtotime($row['startTime']))?></td>
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
						<td><?=date('d-m-Y H:i:s',strtotime($row['startTime']))?></td>
						<td><?=date('d-m-Y H:i:s',strtotime($row['endTime']))?></td>
						<td><?=$row['name']?></td>
						<td><?=$row['sub']?></td>
						<td><?=$row['duration']?></td>
						<td><?=$row['numSubmissions']?>&#47;<?=$row['numQuizTakers']?></td>
						<td><a href='editQuiz.php?id=<?=$row['quizId']?>'>Edit Quiz</td>
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
