<?php
  require_once("config.php");
  require_once("chatClass.php");
	$session = strip_tags( $_GET['session'] );
	$jsonData = chatClass::getPastChat($session);
  print $jsonData;
?>
