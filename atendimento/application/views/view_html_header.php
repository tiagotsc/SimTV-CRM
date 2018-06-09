<?php 
# Configurações do sistema
#include_once('configSistema.php');
header('Content-Type: text/html; charset=utf-8');
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta http-equiv="Content-Type" content="text/html;charset=utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="">
  <meta name="author" content="">
  <title>Sim TV - Atendimento</title>
  
<?php

# Bootstrap core CSS
echo link_tag(array('href' => 'assets/css/bootstrap.min.css','rel' => 'stylesheet','type' => 'text/css'));
echo link_tag(array('href' => 'assets/css/font-awesome.min.css','rel' => 'stylesheet','type' => 'text/css'));
echo link_tag(array('href' => 'assets/css/prettyPhoto.css','rel' => 'stylesheet','type' => 'text/css'));
echo link_tag(array('href' => 'assets/css/animate.css','rel' => 'stylesheet','type' => 'text/css'));
echo link_tag(array('href' => 'assets/css/main.css','rel' => 'stylesheet','type' => 'text/css'));

echo link_tag(array('href' => 'assets/css/personalizado.css','rel' => 'stylesheet','type' => 'text/css'));

# JavaScript
echo "<script type='text/javascript' src='".base_url('assets/js/jquery.js')."'></script>";
echo "<script type='text/javascript' src='".base_url('assets/js/bootstrap.min.js')."'></script>";
echo "<script type='text/javascript' src='".base_url('assets/js/jquery.prettyPhoto.js')."'></script>";
echo "<script type='text/javascript' src='".base_url('assets/js/main.js')."'></script>";

echo link_tag(array('href' => 'assets/js/jquery-ui/jquery-ui.css','rel' => 'stylesheet','type' => 'text/css'));
echo "<script type='text/javascript' src='".base_url("assets/js/jquery-ui/jquery-ui.js")."'></script>";

echo "<script type='text/javascript' src='".base_url("assets/js/simple-expand.js")."'></script>";

echo "<script type='text/javascript' src='".base_url("assets/js/jquery.validate.min.js")."'></script>";
?>
  
  <!--[if lt IE 9]>
  <script src="js/html5shiv.js"></script>
  <script src="js/respond.min.js"></script>
  <![endif]       
  <link rel="shortcut icon" href="<?php echo base_url('assets/images/ico/favicon.ico'); ?>">-->
  <link rel="apple-touch-icon-precomposed" sizes="144x144" href="<?php echo base_url('assets/images/ico/apple-touch-icon-144-precomposed.png'); ?>">
  <link rel="apple-touch-icon-precomposed" sizes="114x114" href="<?php echo base_url('assets/images/ico/apple-touch-icon-114-precomposed.png');?>">
  <link rel="apple-touch-icon-precomposed" sizes="72x72" href="<?php echo base_url('assets/images/ico/apple-touch-icon-72-precomposed.png');?>">
  <link rel="apple-touch-icon-precomposed" href="<?php echo base_url('images/ico/apple-touch-icon-57-precomposed.png'); ?>">
</head><!--/head-->
<body>