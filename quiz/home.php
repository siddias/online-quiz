<?php

session_start();
require_once 'class.user.php';

$user_home = new USER();

if(!$user_home->is_logged_in())
	$user_home->redirect('index.php'); //if not logged in then go to index page

if(!isset($_SESSION['userId'])){
	$stmt = $user_home->runQuery("SELECT * FROM members WHERE userID=:uid LIMIT 1");
	$stmt->execute(array(":uid"=>$_SESSION['userSession']));
	$row = $stmt->fetch(PDO::FETCH_ASSOC); //contains all details of current user
	$_SESSION['userId'] = $row['userId'];
	$_SESSION['id'] = $row['id'];
	$_SESSION['fname'] = $row['fname'];
	$_SESSION['lname'] = $row['userId'];
	$_SESSION['email'] = $row['email'];
	$_SESSION['userType'] = $row['userType'];
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title><?php echo $_SESSION['fname']?>'s Home</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="lib/bootstrap/css/bootstrap.min.css" />
    <link rel="stylesheet" href="css/layout.css" />
    <script src="lib/jquery/jquery.min.js"></script>
    <script src="lib/bootstrap/js/bootstrap.min.js"></script>
</head>

<body>
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
                    <li class="active"><a href="home.php"><span class="glyphicon glyphicon-home"></span></a></li>
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
        <h3>Collapsible Navbar</h3>
        <p>In this example, the navigation bar is hidden on small screens and replaced by a button in the top right corner (try to re-size this window).</p>
        <p>Only when the button is clicked, the navigation bar will be displayed.</p>
        <p>In this example, the navigation bar is hidden on small screens and replaced by a button in the top right corner (try to re-size this window).</p>
        <p>Only when the button is clicked, the navigation bar will be displayed.</p>
    </div>
    <div class="footer">
    </div>

</body>

</html>
