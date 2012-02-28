<?php
	session_start();
?>

<html>
<head>
<style type="text/css">
body
{
background-color:#E0E8E0;
margin:0;
padding:0;
}

</style>
</head>

<div id="container" style="width:100%;">

<div id="header" style="width:100%;height:20%;background-color:#C3C5CA">
</br>
<img src="UAlbany_logo.png" width ="40%" padding-left="100px"></img>
</div>

<div id="menu" style="width:50%;float:left;">
<br></br>
<br></br>


<img src="Screen_Shot2.png" width ="90%"></img></br>
</br>
</br>
</br>
</br>
</br>
</div>

<div id="content" style="width:50%;float:left;">
</div>

</div>
</br>
</br>
</br>


<?php
if(isset($_SESSION['front_message']))
{
		echo "<p><font color=\"red\">". $_SESSION['front_message'] . "</font></p>";
		unset($_SESSION['front_message']);
}
?>
</br>
<h1 >Create a new UACC Account</h1>
<form action ='register.php' method = 'POST' >
      <table>
            <tr>
               <td>
               Firstname:
               </td>
               <td>
               <input type = 'text' name = 'myfirstname' id = 'myfirstname'>
               </td>
            </tr>
            <tr>
               <td>
               Lastname:
               </td>
               <td>
               <input type = 'text' name = 'mylastname' id = 'mylastname'>
               </td>
            </tr>
             <tr>
               <td>
               Email:
               </td>
               <td>
               <input type = 'text' name = 'myemail' id='myemail'>
               </td>
              </tr>
             <tr>
               <td>
               Password:
               </td>
               <td>
               <input type = 'password' name = 'mypassword' id = 'mypassword'>
               </td>
              </tr>
             <tr>
               <td>
               Confirm password:
               </td>
               <td>
               <input type = 'password' name = 'mypassword2' id = 'mypassword2'>
               </td>
              </tr>
           </table>
          <p>
         <input type ='submit' name ='submit' value = 'Register'>
       </form>
      </html>