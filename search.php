<?php
  require_once("config.php");
  require_once("chatClass.php");
	$info = strip_tags( $_GET['info'] );
	$jsonData = chatClass::getSearchOf($info);
  print $jsonData;
?>
