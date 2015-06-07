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
<div id="content">
		<div id="sidebar">
<div>
				<img src="images/login.jpg" alt="" width="233" height="41" /><br />
                <div class="categories">
                <h3>Login</h3><br/>
                <div class="add_error" id="add_error"></div>
                <form method="post" action="./">
                
                <p class="login_line">Email/Login:</p>
                <p class="login_line"><input id="username" name="username" type="text" size="20" /></p></br>
                <p class="login_line">Password:</p>
                <p class="login_line"><input id="password" name="password" type="password" size="20" /></p></br>
                <p id="login_btn"><input type="submit" id="login" name="login" value="Login" /></p>
                </form>
                </div>
               
			</div>
		</div>
	<div id="main">
<div id="inside">

				
