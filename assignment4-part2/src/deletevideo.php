<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include('clsdbaccess.php');
$obj = new clsdbaccess();	

if(isset($_POST['btndeletevideo'])) {

		$id = $_POST['id'];
		$obj->deletevideo($id);		
		$filePath = explode('/', $_SERVER['PHP_SELF'], -1);
		//return the array back to a single string
		$filePath = implode('/', $filePath);
		//combine filepath with header of the current request
		$redirect = "http://".$_SERVER['HTTP_HOST'].$filePath;
		header("Location: {$redirect}/videoinventory.php", true);	
}

?>