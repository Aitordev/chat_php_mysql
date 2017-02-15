<?php
	session_start();
  require_once("config.php");
	require_once("chatClass.php");
	$me = $_SESSION[ 'username' ];
	$session = $_SESSION['session'];
	$id = intval( $_GET['lastTimeID'] );
	$jsonData = chatClass::getRestChatLines($id,$me,$session);
	print $jsonData;
?>
