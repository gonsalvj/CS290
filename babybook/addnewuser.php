<?php
 
ini_set('display_errors', 'On');
		
	include('clsBabyJournal.php');
	$obj = new clsBabyJournal();
	$mysqli = $obj->db_connect();

	//get values from text fields:
	$fname = $_POST['fname'];
	$lname = $_POST['lname'];
	$psw = $_POST['psw'];
	$psw2 = $_POST['psw2'];
	$email = $_POST['email'];
	
	//set flags:
	$fname_ok = true;
	$lname_ok = true;
	$psw_ok = true;
	$email_ok = true;
	
	$msg = 'true';
	
	// first name check:
	if (empty($fname)) { $msg .= "<br />First Name is Required."; $fname_ok = false; }
	
	// last name check:
	if (empty($lname)) { $msg .= "<br />Last Name is Required."; $lname_ok = false; }
	
	// email check:
	if (empty($email)) { $msg .= "<br />Email is Required."; $email_ok = false; }
	
	// password check:
	if (empty($psw)) { $msg .= "<br />Password is Required."; $psw_ok = false; }
	else
	{
		if ($psw!=$psw2) { $msg .= "<br />The passwords you entered do not match."; $psw_ok = false; }
	}
	
	//check flags:
	if ($fname_ok and $lname_ok and $psw_ok and $email_ok)
	{
		if(!($stmt = $mysqli->prepare("INSERT INTO users (firstName, lastName, email, password) VALUES  (?,?,?,?)"))){
				echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
		}
		
		$pw =md5($psw);
		if(!($stmt->bind_param("ssss",$fname, $lname, $email, $pw))){
			echo "Bind failed: "  . $stmt->errno . " " . $stmt->error;
		}
		if(!$stmt->execute()){
			echo "Execute failed: "  . $stmt->errno . " " . $stmt->error;
		} /*else {
			$msg .= "Added " . $stmt->affected_rows . " rows to users.";
		}	*/
		
	}
		echo $msg;
	
	

?>
