<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<title>CS 290 Assignment 4 - loopback</title>
	</head>
	<body>

		<table>
			<tbody>
				<form action="loopback.php" method="get" enctype="multipart/form-data">
				<tr><td>Enter min-multiplicand:</td><td><input name="min-multiplicand" type="text"></td></tr>
				<tr><td>Enter max-multiplicand:</td><td><input name="max-multiplicand" type="text"></td></tr>
				<tr><td>Enter min-multiplier:</td><td><input name="min-multiplier" type="text"></td></tr>
				<tr><td>Enter max-multiplier:</td><td><input name="max-multiplier" type="text"></td></tr>
				<tr><td> <input type="submit"  /></td></tr>
				</form>
			</tbody>
		</table>
	</body>

</html>

<?php 

$request = array();
$options = ["GET" => $_GET, "POST" => $_POST];
$method = $_SERVER["REQUEST_METHOD"];

 $request['Type'] = $method;
 
 
foreach ($options[$method] as $key => $value) {
	if(!isEmptyString($value)) {
		array_push($request['parameters'][$key] = $value);
	}
} 
if(count($request['parameters']) === 0) {
	$request['parameters'] = null;
}
	


 echo json_encode($request);


function isEmptyString($string) {
	if($string === '') {
		return true;
	}
	return false; 
}

?>