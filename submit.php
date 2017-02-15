<?php
	session_start();
  require_once("config.php");
	require_once("chatClass.php");
	$chattext = strip_tags( $_GET['chattext'] );
	$admin = $_SESSION[ 'admin' ];
	$color = $_SESSION['color'];
	$session = $_SESSION['session'];
	$me = $_SESSION[ 'username' ];
	chatClass::setChatLines($chattext,$me,$color,$admin,$session);
?>
