<?php 
  include('common/functions.php');
  $data_access=new Data_access();
  $connect=$data_access->Connect_db();

  $data_access->Decrease_attempts($_SESSION['user_id']);
  if(!isset($_SESSION['user_name'])){
    $msg="Login First!";
    $msg_type="error";
    $url="login.php";
    $data_access->Redirect($msg, $msg_type, $url);
    exit();
  }
  $first_role=substr($_SESSION['user_role'], 0, 1);
  $second_role=substr($_SESSION['user_role'], 1, 1);
  $third_role=substr($_SESSION['user_role'], 2, 3);
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="Mosaddek">
    <meta name="keyword" content="FlatLab, Dashboard, Bootstrap, Admin, Template, Theme, Responsive, Fluid, Retina">
    <link rel="shortcut icon" href="img/favicon.png">

    <title>GFood Inventory</title>

    <!-- Bootstrap core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/bootstrap-reset.css" rel="stylesheet">
    <!--external css-->
    <link href="assets/font-awesome/css/font-awesome.css" rel="stylesheet" />
    <link href="assets/jquery-easy-pie-chart/jquery.easy-pie-chart.css" rel="stylesheet" type="text/css" media="screen"/>
    <link rel="stylesheet" href="css/owl.carousel.css" type="text/css">
    <link href="assets/advanced-datatable/media/css/demo_page.css" rel="stylesheet" />
    <link href="assets/advanced-datatable/media/css/demo_table.css" rel="stylesheet" />
    <link rel="stylesheet" href="assets/data-tables/DT_bootstrap.css" />
    <!-- Custom styles for this template -->
    <link href="css/invoice-print.css" rel="stylesheet" media="print" />
    <link href="css/style.css" rel="stylesheet">
    <link href="css/style-responsive.css" rel="stylesheet" />
    <link href="css/custom_style.css" rel="stylesheet" />

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 tooltipss and media queries -->
    <!--[if lt IE 9]>
      <script src="js/html5shiv.js"></script>
      <script src="js/respond.min.js"></script>
    <![endif]-->
    <script src="js/angular.1.4.8.js"></script>
    <script  src="js/angular-ui.js"></script>
  </head>

  <body>

  <section id="container" >