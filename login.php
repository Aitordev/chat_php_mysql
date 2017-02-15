<?php
	session_start();
	require_once("config.php");
	require_once("chatClass.php");
	$location = "chat.php?session=" . $_SESSION['session'];
	if(isset($_POST['g-recaptcha-response'])){
	  $captcha=$_POST['g-recaptcha-response'];
	}
	echo '<script language="javascript">';
	if(!$captcha){
	  echo 'alert("Verify you are a human"); ';
	}
	else{
	  $response=file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=6Le4gRQUAAAmTp7cqjeyTQ&response=".$captcha."&remoteip=".$_SERVER['REMOTE_ADDR']); // Fill
	  $obj = json_decode($response);
	  if($obj->success == true)
	  {
			//login DB
			$user = strip_tags($_POST['username']);
			$pass = md5(strip_tags($_POST['pass']));
			$factory = strip_tags($_POST['factory']);
			$data = chatClass::login($user,$pass,$factory);
			if (isset($data->username) && $user == $data->username){
				$_SESSION['username'] = $data->username;
				$_SESSION['admin'] = $data->admin;
				$_SESSION['color'] = rand_color();
			}
			else{
				echo 'alert("Wrong User or Password"); ';
			}
	  }
	  else
	  {
	    echo 'alert("Verify you are a human"); ';
	  }
	}
	//	header("Location: chat.php?session=" . $_SESSION['session']);
	echo "location.href='$location'; ";
	echo '</script>';

	function rand_color() {
		  $colors = [
		  	'#F44336','#C62828', // red
		  	'#9C27B0','#BA68C8','#6A1B9A','#E040FB','#AA00FF', // purple
		  	'#2196F3','#1976D2','#0D47A1','#82B1FF', // blue
		  	'#03A9F4','#01579B','#80D8FF','#40C4FF','#00B0FF','#0091EA', // lightblue
		  	'#00BCD4','#00E5FF','#00B8D4', // cyan
		  	'#009688','#00695C','#00BFA5', // teal
		  	'#4CAF50','#2196F3','#4CAF50','#00E676', // green
		  	'#FF9800','#F57C00','#FF6D00', // orange
		  	'#FF5722','#BF360C','#DD2C00', // deep orange
		  	'#000', // black
		  ];
		  return $colors[array_rand($colors)];
			}
?>
