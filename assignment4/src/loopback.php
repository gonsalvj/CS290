<?php 
$request = array();
$options = ["GET" => $_GET, "POST" => $_POST];
//get method
$method = $_SERVER["REQUEST_METHOD"];
$request['Type'] = $method; 
 
 //get parameters from request and add to request array
foreach ($options[$method] as $key => $value) {
	if(!empty($value)) {
		array_push($request['parameters'][$key] = $value);
	}
} 
//if no parameters are present - set to null
if(count($request['parameters']) === 0) {
	$request['parameters'] = null;
}
//echo json object of request
echo json_encode($request);
?>