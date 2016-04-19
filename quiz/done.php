<?php
session_start();
require_once 'class.user.php';

$user = new USER();

if(!$user->is_logged_in())
	$user->redirect('index.php');

if(isset($_POST["msg"])){
	$qId = $_SESSION['quizId'];
	try {
			$n = count($_POST);
			for($i=1;$i<$n;$i++){
					$id=$_POST['id'.$i];
					$stmt = $user->runQuery("INSERT INTO live_quiz".$id."(quizId) VALUES($qId)");
					$stmt->execute();
					$stmt = $user->runQuery("INSERT INTO quiz".$qId."_takers(userId) VALUES ($id)");
					$stmt->execute();
			}
			$n--;
			$id=$_SESSION["userSession"];
			$stmt = $user->runQuery("INSERT INTO live_quiz".$id."(quizId) VALUES ($qId)");
			$stmt->execute();
			$stmt = $user->runQuery("UPDATE quizlist SET numQuizTakers=$n WHERE quizId=$qId");
			$stmt->execute();
			unset($_SESSION['qno']);
			unset($_SESSION['quizId']);
	}
	catch(PDOException $ex)
	{
		echo $ex->getMessage();
		exit();
	}
	echo "success";
	exit();
}
else {
	try{
		unset($_SESSION['qno']);
		$stmt = $user->runQuery("SELECT userId,id,fname,lname,email from members WHERE userType='S' and verified='Y' ORDER BY id ASC");
		$stmt->execute();
		$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
	}catch(PDOException $ex){
		echo $ex->getMessage();
		exit();
	}
}
?>

<!DOCTYPE html>
<html>

<head>
    <meta http-equiv="Content-type" content="text/html; charset=utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Done</title>
	<link rel="stylesheet" href="lib/bootstrap/css/bootstrap.min.css">
	<link rel="stylesheet" href="css/layout.css" />
	<link rel="stylesheet" type="text/css" href="lib/dataTables/css/jquery.dataTables.min.css">
    <link rel="stylesheet" type="text/css" href="lib/dataTables/css/select.dataTables.min.css">
    <link rel="stylesheet" href="lib/lobibox/css/lobibox.min.css" />
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
		td{
			cursor: pointer;
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
	   {"className": "dt-center", "targets": "_all"}
	 ],"select": {
                    style: 'multi'
                },
				"pageLength": 50
            });
        });

    </script>
	<script>
	function sendData(){
		var ob={msg:"add"};
		var s = document.getElementsByClassName("selected");
		if(s.length==0){
		Lobibox.alert("error",
			{ msg: "No Students selected!",
	});
}
else{
		for (var i = 0; i <s.length; i++) {
			ob["id"+(i+1)]=s[i]["id"];
		}
		$.ajax({ type: "POST",
			url: "done.php",
			data: ob,//no need to call JSON.stringify etc... jQ does this for you
			cache: false,
			success: function(response)
			{//check response: it's always good to check server output when developing...
			Lobibox.alert("success",
				{ msg: "Quiz Created Successfully!",
				callback: function(lobibox){
					window.location="home.php";
   				}
			});
			}
   });
   }
}
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
                            <li><a href="logout.php">Sign Out</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
	<div class="container stuff">
	<div class="tab">
		<table id="example" class="display cell-border hover compact stripe" cellspacing="0" width="100%" style="border:1px solid rgb(200, 205, 199); border-top-style:none;">
		<caption>SELECT STUDENTS</caption>
		<thead>
		<tr class="head">
			<td>SL NO</td>
			<td>ID</td>
			<td>First Name</td>
			<td>Last Name</td>
			<td>Email Id</td>
		</tr>
		</thead>
		<tbody>
			<?php
				$i=1;
				foreach($result as $row){
			?>
						<tr id=<?=$row['userId']?>>
							<td><?=$i?></td>
							<td><?=$row['id']?></td>
							<td><?=$row['fname']?></td>
							<td><?=$row['lname']?></td>
							<td><?=$row['email']?></td>
						</tr>
			<?php
				$i++;
				}
			?>
		</tbody>
    </table>
</div>
<div class="form-group">
	<div class="col-sm-offset-4 col-sm-4">
		<button type="button" class="btn btn-primary btn-lg" id="done" onclick="sendData()">Done</button>
	</div>
</div>
//<button type="button" class="btn btn-primary btn-md" id="done" onclick="sendData()">Done</button>
</div>
<div class="footer">
</div>

</body>
</html>
