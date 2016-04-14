<?php
session_start();
require_once 'class.user.php';
$user = new USER();

if(!$user->is_logged_in())
	$user->redirect('index.php');
else
{
	$user->logout();
	$user->redirect('index.php');
}
?>
