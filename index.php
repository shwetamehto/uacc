<?php
	/*Start a session*/
	session_start();
	/*Check if a member_id has been associated with this set*/
	if(isset($_SESSION['member_id']))
	{
		/*If so, take them right to the page*/
		header("location:map.php");
	}
?>

<html>
<head>
<style type="text/css">
a:hover
{
background-color:yellow;
}
body
{
background-color:#E0E8E0;
}
</style>
</head>
</br>

<center><H1 ALIGN="CENTER">Welcome to UAlbany Campus Connect</H1></center>
</br>
<center><img src="pic-9.jpg"></img></center>
</br>
<?php
	if(isset($_SESSION['front_message']))
	{
			echo "<center><p><font color=\"red\">". $_SESSION['front_message'] . "</font></p></center>";
			unset($_SESSION['front_message']);
	}
?>
</br>
<table width="300" border="0" align="center" cellpadding="0" cellspacing="1" bgcolor="#CCCCCC">
<tr>
<form name="form1" method="post" action='check_login.php'>
<td>
<table width="100%" border="0" cellpadding="3" cellspacing="1" bgcolor="#E0E8E0">
<tr>
<td colspan="3"><strong>Member Login</strong></td>
</tr>
<tr>
<td width="78">Email</td>
<td width="6">:</td>
<td width="294"><input name="myemail" type="text" id="myemail"></td>
</tr>
<tr>
<td>Password</td>
<td>:</td>
<td><input name="mypassword" type="password" id="mypassword"></td>
</tr>
<tr>
<td>Building</td>
<td>:</td>
<td><select name="mylocation" id="mylocation">
<option>University Library</option>
<option>Science Library</option>
</select>
</td>
</tr>
<tr>
<td>&nbsp;</td>
<td>&nbsp;</td>
<td><input type="submit" name="Submit" value="Login">
<a href="password.html">Forgot Password ?</a>
</td>
</tr>
<a href='register_member.php'>Click here to register</a>
</table>
</td>

</form>
</tr>
</table>
</html>