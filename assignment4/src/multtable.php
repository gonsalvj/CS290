<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
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
				<tr><td>Enter min-multiplicand:</td><td><input name="min-multiplicand" type="text"></td></tr>
				<tr><td>Enter max-multiplicand:</td><td><input name="max-multiplicand" type="text"></td></tr>
				<tr><td>Enter min-multiplier:</td><td><input name="min-multiplier" type="text"></td></tr>
				<tr><td>Enter max-multiplier:</td><td><input name="max-multiplier" type="text"></td></tr>
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
   	$params = array(
            'min-multiplicand' => $_GET['min-multiplicand'],
	        'max-multiplicand' => $_GET['max-multiplicand'],
	    	'min-multiplier' => $_GET['min-multiplier'],
            'max-multiplier' => $_GET['max-multiplier'],
   		);

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

	if ($params['min-multiplicand'] >= $params['max-multiplicand']) {
		$isValid = false;
	    $errorMsg .= "Minimum multicand must be larger than maximum multiplicand.";
	}

	if ($params['min-multiplier'] >= $params['max-multiplier']) {
		$isValid = false;
	    $errorMsg .= "Minimum multiplier must be larger than maximum multiplier.";
	}      

	if ($isValid) {			
		createTable($params);
	}	
	else
	{
		echo "<p>Errors: <br>";
		echo $errorMsg."</p>";
	}

}

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
