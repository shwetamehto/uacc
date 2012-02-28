<?php
	/*Start the session*/
	session_start();
	
	/*Check if a member_id has been set for this session*/
	if(isset($_SESSION['member_id']))
	{
		/*Destroy the session*/
		session_destroy();
		/*Send to main page*/
		header("location:index.php");
	}
	/*If no member has been associated, send them to login screen*/
	else
	{
		header("location:index.php");
	}
?>
