<?php
/*error_reporting(E_ALL);
ini_set('display_errors', 1);*/
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<title>CS290 - Assignment 4</title>
	</head>
	<body>
		<table>
			<tbody>
				<form action="multtable.php" method="get" enctype="multipart/form-data">
				<tr><td>Enter min-multiplicand:</td><td><input name="min-multiplicand" type="number"></td></tr>
				<tr><td>Enter max-multiplicand:</td><td><input name="max-multiplicand" type="number"></td></tr>
				<tr><td>Enter min-multiplier:</td><td><input name="min-multiplier" type="number"></td></tr>
				<tr><td>Enter max-multiplier:</td><td><input name="max-multiplier" type="number"></td></tr>
				<tr><td> <input type="submit" name="submit" value="submit" /></td></tr>
				</form>
			</tbody>
		</table>
	</body>
</html>
<?php 
$errorMsg = '';
if(isset($_GET['submit'])) {
    $isValid = true;
    //Create array of user inputs
   	$params = array(
            'min-multiplicand' => $_GET['min-multiplicand'],
	        'max-multiplicand' => $_GET['max-multiplicand'],
	    	'min-multiplier' => $_GET['min-multiplier'],
            'max-multiplier' => $_GET['max-multiplier'],
   		);
   	//Ensure all values have been supplied by users - are numeric
   	//and are greater than equal to zero
	foreach ($params as $key => $val) {
		        
        if (!empty($val)) {
			if (!is_numeric($val)) {
				$isValid = false;
    		    $errorMsg .= $key.": ".$val." must be a valid integer.<br>"; 
    	    }
		} else {
			$isValid = false;
			$errorMsg .= "Missing parameter ".$key."<br>";
		}
	}
	//Ensure min-multiplicand is less than max-multiplicand
	if ($params['min-multiplicand'] >= $params['max-multiplicand']) {
		$isValid = false;
	    $errorMsg .= "Minimum multicand must be larger than maximum multiplicand.";
	}
	//Ensure min-multiplier is less than max-multiplier
	if ($params['min-multiplier'] >= $params['max-multiplier']) {
		$isValid = false;
	    $errorMsg .= "Minimum multiplier must be larger than maximum multiplier.";
	}      

	//If inputs are valid, create HTML table, if not print errors
	if ($isValid) {			
		createTable($params);
	}	
	else
	{
		echo "<p>Errors: <br>";
		echo $errorMsg."</p>";
	}

}
//Create HTML table based on validated inputs
	function createTable($params) {

		$width = $params['max-multiplier'] - $params['min-multiplier'] + 2;
		$height = $params['max-multiplicand'] - $params['min-multiplicand'] + 2;

	    echo "<table border='1'><thead><tr><th></th>";
		for ( $i = 0; $i < $width -1; $i++)
		{   $sum = $i + $params['min-multiplier'];
			echo    "<th>".$sum."</th>";
		}
		echo "</tr></thead><tbody>";
		for ( $j = 0; $j < $height -1; $j++) {
			$sum = $j + $params['min-multiplicand'];
			echo "<tr>";
			echo  "<th>".$sum."</th>";
			for ( $k = 0; $k < $width -1; $k++) {
				$product = $sum * ($k +$params['min-multiplier']);
				echo "<td>".$product."</td>";
			}
			echo "</tr>";
		}
		echo "</tbody></table>";
    }

?>
