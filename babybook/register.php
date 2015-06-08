<!DOCTYPE html>
<html>
    <head>
        <title>Baby Journal</title>
        <meta charset="UTF-8">
<script src="http://code.jquery.com/jquery-1.10.2.min.js"></script>
<script src="js/validation.js"></script>
<script src="js/login.js"></script>
<script src="js/register.js"></script>
<link rel="stylesheet" type="text/css" href="style.css" />
</head>

<body>
<div>
        <a href="#"><img src="images/logo.gif" width="237" height="123" class="float" alt="setalpm" /></a>                                                                                                                                                                                                                                                                  
      <div class="topnav">
            <p align="right"><strong><a href="register.php">Register</a></strong></p> 
        </div>
        <ul id="menu">
            <li><a href="aboutbaby.php"><img src="images/btn1.gif" alt="" width="110" height="32" /></a></li>
            <li><a href="journals.php"><img src="images/btn2.gif" alt="" width="110" height="32" /></a></li>
            <li><a href="milestones.php"><img src="images/btn3.gif" alt="" width="110" height="32" /></a></li>
            <li><a href="enterjournal.php"><img src="images/btn4.gif" alt="" width="110" height="32" /></a></li>
            <li><a href="entermilestone.php"><img src="images/btn5.gif" alt="" width="110" height="32" /></a></li>
            <li><a href="photogallery.php"><img src="images/btn6.gif" alt="" width="110" height="32" /></a></li>
        </ul>
    </div>
    <div id="main">
<div id="inside">
<div id="content">
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
