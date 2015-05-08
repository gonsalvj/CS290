<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include('clsdbaccess.php');
$obj = new clsdbaccess();
$filePath = explode('/', $_SERVER['PHP_SELF'], -1);
//return the array back to a single string
$filePath = implode('/', $filePath);
//combine filepath with header of the current request
$redirect = "http://".$_SERVER['HTTP_HOST'].$filePath;
if(isset($_POST['btnaddvideo'])) {
		$isValid = true;
		$name = $_POST['name'];
		$category = $_POST['category'];
		$length = $_POST['length'];
		$errorMsg = '';
		if (empty($name)) {
			$isValid = false;
			$errorMsg = 'Name cannot be empty.<br>';
		}
		if (!empty($length)) {
			if (!is_numeric($length)) || $length < 0) {
			$isValid = false;
			$errorMsg .= 'Length must be a positive number.<br>';
			}
		} 

		if ($isValid) {			
			$result = $obj->addvideo($name, $category, $length);	
			if($result =='success') {
				header("Location: {$redirect}/videoinventory.php", true);
			} else {
				header("Location: {$redirect}/videoinventory.php?errorMsg=$result", true);
			}
		} else {
			header("Location: {$redirect}/videoinventory.php?errorMsg=$errorMsg", true);				
		}
	}
?>