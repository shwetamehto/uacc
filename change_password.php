<?php
	/*Start a session*/
	session_start();
	
	if(!isset($_SESSION['member_id']))
	{
		header("location:index.php");
	}
	else
	{
		/*Retrieve the form values from the post*/
		$old_password = $_POST['oldpassword'];
		$new_password = $_POST['newpassword'];
		$new_password2 = $_POST['newpassword2'];
		
		$memID = $_SESSION['member_id'];
		if(strcmp($new_password, $new_password2)!= 0)
		{
			$_SESSION['front_message'] = "Error : The two new passwords must match";
			header("location: setpassword.php");
		}
		else if(strcmp($new_password, "") == 0 || strcmp($new_password2,"") == 0 || strcmp($old_password,"") == 0)
		{
			$_SESSION['front_message'] = "Error : All form fields must be filled";
			header("location: setpassword.php");
		}
		else
		{
			/*Connect to the database*/
			mysql_connect("localhost","prograph_dbUser","dbUser@uacc");
			mysql_select_db("prograph_uacc")or die (mysql_error());
			
			/*Aquire the md5 encrypted password to match the database*/
			$old_password = md5($old_password);
			/*Encrypt the new password for storage*/
			$new_password=md5($new_password);
			
			/*Set the password in the database*/
			mysql_query("UPDATE Member SET password='$new_password' WHERE member_id=$memID AND password='$old_password'") or die (mysql_error());
			
			/*If this count is 1, a member has been found with these credentials*/
			if(mysql_affected_rows() == 1)
			{
				/*Set the front message*/
				$_SESSION['front_message'] = "Password changed successfully";
				header("location: map.php");
			}
			else
			{
				$_SESSION['front_message'] = "Error : Incorrect old password given";
				header("location: setpassword.php");
			}
	}	
}
?>
