<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<title>CS 290 Assignment 4 - Login</title>
	</head>
	<body>
		<body>
		
				<form action="login.php" method="post" enctype="multipart/form-data">
				Username: <input name="username" type="text">
				<input type="submit" name="btnLogin" value="Login" />
				</form>

		
	</body>
</html>
<?php 
if(isset($_POST['btnLogin']))
{
	//session_start();
	//$_SESSION['username'] = $_POST['username'];
	echo "works";
}
?>