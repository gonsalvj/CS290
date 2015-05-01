<?php
/*error_reporting(E_ALL);
ini_set('display_errors', 1);*/
session_start();

/*Print greeting to screen*/
function printGreeting() {
	echo 'Hello '.$_SESSION['username'].'<br>';
	echo 'Click <a href="login.php?action=end">here</a> to logout.<br>';
	echo 'Go to <a href="content1.php">content1.php</a>';
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
	//redirect to login page - once logged out
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
