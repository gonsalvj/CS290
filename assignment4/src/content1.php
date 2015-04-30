<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

/*Print greeting to screen*/
function printGreeting() {
	echo 'Hello '.$_SESSION['username'].' , you have visited this page '.$_SESSION['visits'].' times before.<br>';
	echo 'Click <a href="login.php?action=end">here</a> to logout.<br>';
	$_SESSION['visits']++;
	echo 'Go to <a href="content2.php">content2.php</a>';
}
/*On login submit check if username is provided, if so create session if not print error*/
if(isset($_POST['btnLogin']))
{
	if(session_status() == PHP_SESSION_ACTIVE) {
		$username = $_POST['username'];
		if(empty($username)) {
			echo 'A username must be entered. Click <a href="login.php">here</a> to return to the login screen.';
		}
		else {
			$_SESSION = array();
			$_SESSION['username'] = $_POST['username'];
			$_SESSION['visits'] = 0;		
		}
	}	
}

/*On visits to page (other than from login), check for session and print greeting or redirect to login.php*/
if(isset($_SESSION['visits']) && isset($_SESSION['username'])) {
	printGreeting();
} else {
	/*CITATION: CODE BELOW TAKEN FROM PHP SESSIONS LECTURE*/
	//get the filename of current script with relative path to root 
	//seperate into array using forward slash delimiter, not including last element (filename)
	$filePath = explode('/', $_SERVER['PHP_SELF'], -1);
	//return the array back to a single string
	$filePath = implode('/', $filePath);
	//combine filepath with header of the current request
	$redirect = "http://".$_SERVER['HTTP_HOST'].$filePath;
	header("Location: {$redirect}/login.php", true);
}
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<title>CS 290 Assignment 4 - Content1</title>
	</head>
	<body>

	</body>
</html>
