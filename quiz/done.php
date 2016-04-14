<?php
session_start();
require_once 'class.user.php';

$user = new USER();

if(!$user->is_logged_in())
	$user->redirect('index.php');

if(isset($_POST["msg"])){
	$qId = $_SESSION['quizId'];
	try {
			for($i=1;$i<count($_POST);$i++){
					$id=$_POST['id'.$i];
					$stmt = $user->runQuery("INSERT INTO live_quiz".$id."(quizId) VALUES($qId)");
					$stmt->execute();
					$stmt = $user->runQuery("INSERT INTO quiz".$qId."_takers(userId,score) VALUES ($id,0)");
					$stmt->execute();
			}
			$id=$_SESSION["userSession"];
			$stmt = $user->runQuery("INSERT INTO live_quiz".$id."(quizId) VALUES ($qId)");
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
    <script type="text/javascript" language="javascript" src="lib/jquery/jquery.min.js"></script>
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
				alert("Quiz Created Successfully!");
				window.location="home.php";
			}
   });
}
	</script>
</head>
<body class="wide comments example">
    <table id="example" class="display cell-border" cellspacing="0" width="100%">
		<caption>Select Students</caption>
		<thead>
		<tr>
			<th>ID</th>
			<th>First Name</th>
			<th>Last Name</th>
			<th>Email Id</th>
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
