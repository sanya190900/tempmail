<?php 
require_once 'server.php';

if (!isset($_SESSION['mail'])) {
	$_SESSION['mail'] = "";
	$_SESSION['messages'] = "";
}
$server = new Server();
$params['ip'] = $_SERVER['REMOTE_ADDR'];
$params['agent'] = substr($_SERVER['HTTP_USER_AGENT'], 0, 160);

if (isset($_POST['generate'])) {
	session_start();
	$server->generate();
}
if (isset($_POST['updateMessages'])) {
	session_start();
	$server->updateMessages();
}
if (isset($_POST['deleteMail'])) {
	session_start();
	$server->deleteMail();
	session_destroy();
}
if (isset($_POST['clearMessages'])) {
	session_start();
	$server->clearMessages();
}
if (isset($_POST['updateMail'])) {
	session_start();
	if (!isset($_SESSION['mail']) || $_SESSION['mail'] == "") {
		$_SESSION['mail'] = "";
		$_SESSION['messages'] = "";
	} else $server->generate();
}

?>

<!DOCTYPE html>
<html>
<head>
	<title>Welcome to Tempmail</title>
	<link rel="stylesheet" type="text/css" href="css/index.css">
	<link rel="stylesheet" type="text/css" href="css/w3.css">
	<link rel="shotcut icon" type="image/x-icon" href="img/logo.png">
</head>
<body>
	<div class="block">
		<form class="w3-container"  method="POST">
		<div class="header"><img src="img/logo.png" height="140px" style="margin-top: 10px;margin-left: 12%; float: left;"><h1 class="w3-text-white" align="center" style="margin-right: 18%; "><br>Welcome to Tempmail!

		</h1></div>
		<div class="text">
			<p style="text-align: justify;">Nowadays an email address is a popular communication method, the practicality and other advantages of which are difficult to overestimate. Today many network resources, websites, programs allow you to activate your account or license solely by sending a control letter to an e-mail address. However, no one will ever guarantee that the email address entered anywhere will not be used by anyone to send unsolicited emails, which are called "spam". But how to be in this situation? The answer to this question is simple - your assistant will be a temporary mail, currently open to you.</p>

			<p style="text-align: justify;">How to use this service: </p>
			<ul style="text-align: justify;">
				<li>To <b>get an individual e-mail address</b> you should click on the red button "Generate". Then service generates for you temporary e-mail address for 1 hour. If you exceed your time for your temporary e-mail address, it will be deleted. </li>
				<li>To <b>extend your e-mail address</b> for an another hour you should click on the green button "Update" which located on the right side of the web-page. If you exceed your time for your temporary e-mail address and then click to this button - you will see start web-page without your individual e-mail address. </li>
				<li>To <b>check your messages</b> you should click on the red button "Update Messages" which located on the right side of the web-page. This service can provide to you only 20 new messages. If you exceed the limit of received messages, you can see only 20 recent incoming messages. If you have not messages yet, you will see message "You have no messages".</li>
				<li>To <b>clear the list of your messages</b> you should click on the blue button "Clear" which located on the right side of the web-page. Then you will see message "You have no messages".</li>
				<li>To <b>delete your individual e-mail address</b> you should click on the yellow button "Delete Mail" which located on the right side of the web-page. Then your e-mail address and messages will be deleted.</li>
			</ul>
				<table border="0" width="100%">
					<tr>
						<td width="75%"><input value="<?php echo $_SESSION['mail'] ?>" type="text" name="mail" class="w3-input w3-border"></td>
						<td style="padding-left: 20px;"><button class="w3-btn w3-red w3-round-large" name="generate" style="width: 200px;">Generate</button></td>
					</tr>
				</table>
				<h2>Your messages:</h2>
				<!-- <p><?php echo $_SESSION['title']; ?> </p>
				<p><?php echo $_SESSION['message']; ?></p> -->
				<p><?php echo $_SESSION['messages'];?></p>
		</div>
		<div class="btn">
				<table border="0" width="85%" align="center">
					<tr>
						<td><button class="w3-btn w3-red w3-round-large" name="updateMessages" style="width: 160px;">Update Messages</button></td>
						<td><button class="w3-btn w3-green w3-round-large" name="updateMail" style="width: 80px;margin-right: 14px;" >Update</button></td>
					</tr>
				</table>
				<table border="0" width="85%" align="center">
					<tr>
						<td><button class="w3-btn w3-blue w3-round-large" name="clearMessages" style="width: 80px;">Clear</button></td>
						<td><button class="w3-btn w3-yellow w3-round-large" name="deleteMail" style="width: 160px; margin-left: 10px;" >Delete Mail</button></td>
					</tr>
				</table>
		</div>
		<div class="footer"><p class="w3-text-white">Created by group IV-71<br>Copyright &copy; IV-71 (tempmail.com) 2019 All rights reserved</p></div>
		</form>
	</div>
</body>
</html>

