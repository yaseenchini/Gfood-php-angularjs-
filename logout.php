<?php
	include('common/functions.php');
	$data_access=new Data_access();
	
	session_start(); 
	unset($_SESSION['user_id']);
  unset($_SESSION['user_name']);
  unset($_SESSION['user_role']);
  session_destroy();
  
  session_start();
  $msg="Logout Successfully!";
  $msg_type="success";
  $url="login.php";
  $data_access->Redirect($msg, $msg_type, $url);
  exit();
?>