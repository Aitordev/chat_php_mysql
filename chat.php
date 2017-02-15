<?php
session_start();
if (!isset($_GET['session']) || $_GET['session'] === null  || $_GET['session'] === ""){
	header("Location: index.php");
}
$_SESSION['session'] = strip_tags( $_GET['session'] );

if(!isset($_SESSION[ 'username' ]) || ($_SESSION["username"] == "")){
		?>
<html>
	<head>
		<title>Chat</title>
		<link rel="stylesheet" href="css/chat.css" />
		<script src='https://www.google.com/recaptcha/api.js'></script>
	</head>
	<body>
		<div id="form">
			<form action="login.php" method="post">
				<label>Username</label>
		    <input name="username" placeholder=""  type="text" required autofocus>

				<label>Password</label>
		    <input name="pass" type="password" placeholder="" required autofocus>

				<label>Factory Floor</label>
		    <input name="factory" type="text" placeholder="" required autofocus>

				<div class="g-recaptcha" data-sitekey="6Le4gRQUJC0DUJPdq6SEYsu"></div> <!-- Use your own key-->
				<input id="submit" name="submit" type="submit" value="Login" />
			</form>
		</div>
	</body>
</html>
		<?php
	}else{
		?>
<html>
	<head>
		<title>Chat</title>
		<script src="js/jquery.min.js"></script>
		<script src="js/chat.js"></script>
		<script>
		<?php if(1 == $_SESSION['admin']){?>
			$(document).ready(function() {
				var ref = "" + window.location.href;
				$.post( "contact.php", { type: "adminAdvise", link: ref, user: "<?php echo $_SESSION[ 'username' ] ?>" } );
				//setTimeout(function(){
					//var html = '<p class="notification wtime"><?php echo $_SESSION[ 'username' ] ?> joined <time> <?php echo date('H:i'); ?></time></p>';
				//	$('#view_ajax').append(html);
				//},3000);
			});
		<?php } ?>
		</script>
		<link rel="stylesheet" href="css/chat.css" />
		<link href="http://fonts.googleapis.com/css?family=Lato:100,300,400,700" rel="stylesheet">
	</head>
	<body>
		<div id = "windowchat" <?php
		if(0 == $_SESSION['admin']){?> style="width: 100%;" <?php } ?>>
			<div class="menu">
				<div class="tittle">Chat</div>
				<a id="modal-launcher" href="#" class="circle-button">+</a>
				<div class="members"><b><?php echo $_SESSION[ 'username' ] ?></b>, Admin</div>
			</div>
			<ol class="chat"  id="view_ajax"></ol>
			<div class="send">
				<input id = "chatInput" class="textarea" type="text" placeholder="Type here!"/>
				<input class="sendButton" type="image" src="img/send.png" alt="Send" id="btnSend" />
			</div>
			<div id="modal-background"></div>
			<div id="share" class ="modal-content">
					<h3>Add person to group sharing the link </h3>
					<form method="post" action="contact.php" novalidate>
						<input id="urlInput" class="inputUrl" name="link" type="text" value="url" readonly/>
			    	<label>Invite with Email</label>
			    	<input name="email" type="email" placeholder="example1@mail.com, example2@mail.com,..." required autofocus>
			    	<input id="submitEmail" name="submit" type="submit" value="Send">
					</form>
			</div>
		</div>
		<?php
		if(1 == $_SESSION['admin']){
				?>
		<div id="search">
			<input id="searchbox" name="search" type="text" placeholder="Search.." />
			<div id = "searchResults">
				<p class="notification">Double click to open a view of the conversation</p>
				<ol class="searches"  id="view_searches"></ol>
			</div>
		</div>
		<?php } ?>
	</body>
</html>
<?php } ?>
