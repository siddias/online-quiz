<?php

session_start();
require_once 'class.user.php';

$user_home = new USER();

if(!$user_home->is_logged_in())
	$user_home->redirect('index.php'); //if not logged in then go to index page

$stmt = $user_home->runQuery("SELECT * FROM members WHERE userID=:uid LIMIT 1");
$stmt->execute(array(":uid"=>$_SESSION['userSession']));
$row = $stmt->fetch(PDO::FETCH_ASSOC); //contains all details of current user
$_SESSION['userId'] = $row['userId'];
$_SESSION['id'] = $row['id'];
$_SESSION['fname'] = $row['fname'];
$_SESSION['lname'] = $row['userId'];
$_SESSION['email'] = $row['email'];
$_SESSION['userType'] = $row['userType'];

?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <title><?php echo $_SESSION['fname'].'\'s Home'; ?></title>
		<meta charset="utf-8">
    </head>
    <body>
		<p>Welcome to HomePage</p>
		<a href="logout.php">Click here to logout</a>
		<?php
			if($row['userType']=='T')
			{
		?>
				<a href="setQuiz.php">Set Quiz</a>
		<?php
			}
		?>
		<a href="live.php">Live Quizes</a>
    </body>
</html>
