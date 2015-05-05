<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include('clsdbaccess.php');
$obj = new clsdbaccess();

?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<title>CS290 - Assignment 4</title>
	</head>
	<body>
		
		<table border="1">
			<caption>Add new video</caption>
			<tbody>
				<form action="insertvideo.php" method="post" enctype="multipart/form-data">
				<tr><td>Name:</td><td><input name="name" type="text">
				</td>
				<td>Category:</td><td><input name="category" type="text"></td>
				<td>Length:</td><td><input name="length" type="number" maxlength="3"></td>
				<td> <input type="submit" name="btnaddvideo" value="Add" /></td></tr>
				</form>
			</tbody>
		</table>		
		<?php
			if (isset($_GET['errorMsg'])) {
				$errorMsg = "<p>Errors: <br>";
				$errorMsg .= $_GET['errorMsg'];
				$errorMsg .= "</p>";
				echo $errorMsg;
			}
		?>
		<br>
		<div>
		<?php 
		
		if(!isset($_GET['categories']) || $_GET['categories'] == 'ALL')  {
			$obj->getallcateogories(null);		
			$obj->getallvideos();	
		} else {
			$filter = $_GET['categories'];
			$obj->getallcateogories($filter);
			$obj->getfilteredvidoes($filter);
		}
		
		?>
		</div>
		<div></div>
	</body>
</html>


