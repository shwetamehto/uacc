<?php
/*Start the session*/
session_start();
/*Receive the email, password, and name entered from the POST*/
$email=$_POST["myemail"];
$password=$_POST["mypassword"];
$password2=$_POST["mypassword2"];
$firstname=$_POST["myfirstname"];
$lastname=$_POST["mylastname"];
/*Make sure the email address contains '@albany.edu'*/
$position = strpos($email,'@albany.edu');
if($position === false)
{
	$_SESSION['front_message'] = "Error: You must register using your UAlbany email address";
	header("location:register_member.php");
}
else if(strcmp($password, $password2)!= 0)
{
	$_SESSION['front_message'] = "Error : The two given passwords must match";
	header("location: register_member.php");
}
else if(strcmp($email, "") == 0 || strcmp($password,"") == 0 || strcmp($firstname,"") == 0 || strcmp($lastname,"") == 0)
{
		$_SESSION['front_message'] = "Error : All form fields must be filled";
		header("location: register_member.php");
}
/*If so, attempt to register the user*/
else{

/*Connect to the database*/
mysql_connect("localhost","prograph_dbUser","dbUser@uacc");
mysql_select_db("prograph_uacc")or die (mysql_error());

/*Check if email already exists*/
$result=mysql_query("SELECT * FROM Member WHERE email LIKE '$email' LIMIT 1") or die (mysql_error());
/*Retrieve the count of rows*/
$count=mysql_num_rows($result);

/*If this count is not zero, a member has been found with this email*/
if($count!=0)
{
	$_SESSION['front_message'] = "Error : User already exists with this email address";
	header("location:register.html");
}
else
{
	/*Aquire the md5 encryption of the password*/
	$password = md5($password);
	/*Create a verification code*/
	$code = rand (1 , 999999);
	/*Store the result of the query*/
	mysql_query("INSERT INTO Member (first_name, last_name, email, password,verified) VALUES ('$firstname', '$lastname', '$email','$password',$code)") or die (mysql_error());
	/*Define the user who will receive the mail*/
	$to = $email;
	/*Subject*/
	$subject = 'Welcome to UAlbany Campus Connect';
	/*The message to be sent*/
	$message = "Hello ".$firstname.",\nCSI 518 Team 4 would like to welcome you to UAlbany Campus Connect.\n\nPlease click the following link to authenticate your account :\n".
					"http://uacc.prographicstech.com/verify_account.php?verification_code=".$code."&email=".$email."\n\nSincerely,\nThe UAlbany Campus Connect Team";
	/*Send the mail*/
	$mail_sent = @mail( $to, $subject, $message);
	$_SESSION['front_message']= $mail_sent ? "Verification Email Sent" : "An Error has occured in Registration";
	header("location:index.php");
}
}
?>
