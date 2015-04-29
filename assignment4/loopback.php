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