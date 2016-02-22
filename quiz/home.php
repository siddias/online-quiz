<?php

session_start();
require_once 'class.user.php';

$user_home = new USER();

if(!$user_home->is_logged_in())
	$user_home->redirect('index.php'); //if not logged in then go to index page

$stmt = $user_home->runQuery("SELECT * FROM tbl_users WHERE userID=:uid");
$stmt->execute(array(":uid"=>$_SESSION['userSession']));
$row = $stmt->fetch(PDO::FETCH_ASSOC); //contains all details of current user

?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <title><?php echo $row['userName'].'\'s Home'; ?></title>
		<meta charset="utf-8">
    </head>
    <body>
		<p>Welcome to HomePage</p>
    </body>
</html>
