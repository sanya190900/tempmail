<?php
/**
 * 
 */
require_once 'apicall.php';
class Server
{

	function generate(){
		$parametrs['lang'] = "en";
		$mailArr = (array)(api_call("get_email_address", $parametrs));

		$_SESSION['mail'] = $mailArr['email_addr'];
		if (!isset($_SESSION['messages'])) {
			$_SESSION['messages'] = "<h3>You have no messages</h3>";
		}
	}

	function updateMessages(){
		if (!isset($_SESSION['mail']) || $_SESSION['mail'] == "") {
			$_SESSION['mail'] = "";
			$_SESSION['messages'] = "";
			return false;
		}
		$_SESSION['messages'] = "";
		$parametrs['offset'] = 0;
		$titleArr = (array)(api_call("get_email_list", $parametrs));
		if (sizeof((array)$titleArr['list']) > 1) {
			for ($i=0; $i < sizeof((array)$titleArr['list']) - 1; $i++) {
				$email_id = ((array)$titleArr['list'][$i])['mail_id'];
				$date = ((array)$titleArr['list'][$i])['mail_date'];
				$subject = ((array)$titleArr['list'][$i])['mail_subject'];
				$from = ((array)$titleArr['list'][$i])['mail_from'];
				$_SESSION['title'] = "- Time: ".date('H:i:s',(strtotime($date)+10800))." Subject: ".$subject." From: ".$from;
				$_SESSION['email_id'][$i] = $email_id;

				$parametrs['email_id'] = $email_id;
				$messageArr = (array)(api_call("fetch_email", $parametrs));

				$_SESSION['message'] = $messageArr['mail_body'];
				$_SESSION['messages'] =$_SESSION['messages'].' 
				<p>'.$_SESSION["title"].'</p>
				<p>'.$_SESSION["message"].'</p><br><br>';
			}
		} else $_SESSION['messages'] = "<h3>You have no messages</h3>";
	}

	function deleteMail(){
		$parametrs['email_addr'] = $_SESSION['mail'] = "";
		api_call("forget_me",$parametrs);
		$_SESSION['messages'] = "";
		$_SESSION['title'] = "";
		$_SESSION['mail'] = "";
	}

	function clearMessages(){
		if (isset($_SESSION['email_id'])) {
			$parametrs['email_ids']	= $_SESSION['email_id'];
			api_call("del_email", $parametrs);
		}
		$_SESSION['messages'] = "<h3>You have no messages</h3>";
	}
}
?>
