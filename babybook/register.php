<?php
	include('headerOut.php');
?>
<div class="register_error" id="register_error"></div>
<h1>Register</h1>
<form method="post" action="./">
	<p class="register-ans">First Name:</p>
    <p><input type="text" id="fname" name="fname" size="20" /></p>
    <p class="register-ans">Last Name:</p>
    <p><input type="text" id="lname" name="lname" size="20" /></p>
    <p class="register-ans">Email/Login ID:</p>
    <p><input type="text" id="email" name="email" size="20" /></p>
    <p class="register-ans">Password:</p>
    <p><input type="password" id="psw" name="psw" size="20" /></p>
    <p class="register-ans">Repeat Password:</p>
    <p><input type="password" id="psw2" name="psw2" size="20" /></p>
    <p style="padding:0;margin:3px 0;"><input type="submit" id="register" name="register" value="Register"  /></p>
</form>




<?php
include("footer.php");
?>
