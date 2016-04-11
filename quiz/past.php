<?php
session_start();
require_once 'class.user.php';

$user = new USER();

if(!$user->is_logged_in())
	$user->redirect('index.php');

try{
	$id = $_SESSION['userSession'];
	$stmt = $user->runQuery("SELECT q.quizId,name,sub,score from quizlist q,past_quiz".$id." p WHERE q.quizId=p.quizId");
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
    <meta http-equiv="Content-type" content="text/html; charset=utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Live-Quizes</title>
    <script type="text/javascript" language="javascript" src="//code.jquery.com/jquery-1.12.0.min.js"></script>
    <script>
    </script>
<body class="wide comments example">
    <table id="example" border="2px" class="display" cellspacing="0" width="100%">
		<caption>PAST QUIZZES</caption>
		<thead>
		<tr>
			<th>SL No</th>
			<th>Quiz Name</th>
			<th>Subject</th>
			<th>Score</th>
		</tr>
		</thead>
		<tbody>
			<?php
				$i=1;
				foreach($result as $row){
			?>
					<tr>
						<td><?=$i?></td>
						<a><td><?=$row['name']?></td></a>
						<td><?=$row['sub']?></td>
						<td><?=$row['score']?></td>
					</tr>
			<?php
				$i++;
				}
			?>
		</tbody>
    </table>
</body>
</html>
