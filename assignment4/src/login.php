<?php
/*error_reporting(E_ALL);
ini_set('display_errors', 1);*/
session_start();
/*Destroy Session - Logout*/
if(isset($_GET['action']) && $_GET['action'] == 'end') {
	/*CITATION: CODE BELOW TAKEN FROM PHP SESSIONS LECTURE*/
	//detroy all session related to the site
	session_destroy();
	//get the filename of current script with relative path to root 
	//seperate into array using forward slash delimiter, not including last element (filename)
	$filePath = explode('/', $_SERVER['PHP_SELF'], -1);
	//return the array back to a single string
	$filePath = implode('/', $filePath);
	//combine filepath with header of the current request
	$redirect = "http://".$_SERVER['HTTP_HOST'].$filePath;
	//redirect to login page - once logged out
	header("Location: {$redirect}/login.php", true);
	die();
}
/*Check if user is logged in, if so print greeting, if not print login form*/
if(isset($_SESSION['visits']) && isset($_SESSION['username'])) {
	echo 'Hello '.$_SESSION['username'].' ,you are already logged in.<br>';
	echo 'Click <a href="login.php?action=end">here</a> to logout.<br>';
	echo 'Or visit: <br>  <a href="content1.php">content1.php</a> <br> <a href="content2.php">content2.php</a>';
} else {
	printForm();
}

/*print html form to the page*/
function printForm() {
	echo '<form action="content1.php" method="post" enctype="multipart/form-data">
		Username: <input name="username" type="text">
		<input type="submit" name="btnLogin" value="Login" />
		</form>';
}

?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<title>CS 290 Assignment 4 - Login</title>
	</head>
		<body>
			
	</body>
</html>
