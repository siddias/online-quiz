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
    <link rel="stylesheet" type="text/css" href="lib/dataTables/css/jquery.dataTables.min.css">
    <link rel="stylesheet" type="text/css" href="lib/dataTables/css/select.dataTables.min.css">
    <link rel="stylesheet" href="lib/lobibox/css/lobibox.min.css" />

	<script type="text/javascript" language="javascript" src="lib/jquery/jquery.min.js"></script>
	<script src="lib/lobibox/js/lobibox.min.js"></script>
	<script src="lib/lobibox/js/messageboxes.min.js"></script>
	<script type="text/javascript" language="javascript" src="lib/dataTables/js/jquery.dataTables.min.js"></script>
    <script type="text/javascript" language="javascript" src="lib/dataTables/js/dataTables.select.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#example').DataTable({
				"columnDefs": [
	   {"className": "dt-center", "targets": "_all"}
	 ],
	 		"select": {
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
	</script>
</head>
<body class="wide comments example">
    <table id="example" class="display cell-border hover compact stripe" cellspacing="0" width="100%" style="border:1px solid rgb(200, 205, 199); border-top-style:none;">
		<caption>Select Students</caption>
		<thead>
		<tr style="background-color:rgb(87, 95, 101); ">
			<th style="border:1px solid rgb(200, 205, 199)">ID</th>
			<th style="border:1px solid rgb(200, 205, 199)">First Name</th>
			<th style="border:1px solid rgb(200, 205, 199)">Last Name</th>
			<th style="border:1px solid rgb(200, 205, 199)">Email Id</th>
		</tr>
		</thead>
		<tbody>
			<?php
				foreach($result as $row){
			?>
					<tr id=<?=$row['userId']?>>
						<td><?=$row['id']?></td>
						<td><?=$row['fname']?></td>
						<td><?=$row['lname']?></td>
						<td><?=$row['email']?></td>
					</tr>
			<?php
				}
			?>
		</tbody>
    </table>
	<button id="done" onclick="sendData()">Done</button>
</body>
</html>
