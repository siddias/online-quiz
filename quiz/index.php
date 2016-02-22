<?php

session_start(); //starts a new session, session is started so multiple pages on the server can share data
require_once 'class.user.php'; //if not found then fatal error. Stricter than include

$user_login = new USER(); //USER OBJECT created

if($user_login->is_logged_in()) //check if user is logged in. if yes redirect him to homepage
    $user_login->redirect('home.php');

if(isset($_POST['btn-login']))
{
    $email = trim($_POST['txtemail']); //remove spaces from either end
    $upass = trim($_POST['txtupass']);

    if($user_login->login($email,$upass)) //checks valid user login. if yes then display homepage
        $user_login->redirect('home.php');
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Login | Quiz-It</title>
    <meta charset="utf-8">
    <!-- Bootstrap -->
    <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet" media="screen">
    <link href="bootstrap/css/bootstrap-responsive.min.css" rel="stylesheet" media="screen">
    <link href="assets/styles.css" rel="stylesheet" media="screen">
    <script src="js/vendor/modernizr-2.6.2-respond-1.1.0.min.js"></script>
</head>
<body id="login">
  <div class="container">
      <?php
      if(isset($_GET['inactive'])) //redirected to index.php because account not activated
      {
          ?>
          <div class='alert alert-error'>
              <button class='close' data-dismiss='alert'>&times;</button>
              <strong>Sorry!</strong> This Account is not Activated Go to your Inbox and Activate it.
          </div>
          <?php
      }
      ?>
      <form class="form-signin" method="post">
      <?php
      if(isset($_GET['error'])) //redirected to index.php because of invalid sign in credentials
      {
          ?>
          <div class='alert alert-success'>
              <button class='close' data-dismiss='alert'>&times;</button>
              <strong>Wrong Details!</strong>
          </div>
          <?php
      }
      ?>
      <h2 class="form-signin-heading">Sign In.</h2><hr />
      <input type="email" class="input-block-level" placeholder="Email address" name="txtemail" required />
      <input type="password" class="input-block-level" placeholder="Password" name="txtupass" required />
      <hr />
      <button class="btn btn-large btn-primary" type="submit" name="btn-login">Sign in</button>
      <a href="signup.php" style="float:right;" class="btn btn-large">Sign Up</a><hr />
      <a href="fpass.php">Lost your Password ? </a>
    </form>
  </div> <!-- /container -->
  <script src="bootstrap/js/jquery-1.9.1.min.js"></script>
  <script src="bootstrap/js/bootstrap.min.js"></script>
</body>
</html>
