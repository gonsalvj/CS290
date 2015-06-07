<?php
		
	ini_set('display_errors', 'On');
	include('clsBabyJournal.php');
	$obj = new clsBabyJournal();
	$mysqli = $obj->db_connect();		
	session_start();

	$username = $_POST['username'];
	$enc_psw = md5($_POST['password']);

	if(!($stmt = $mysqli->prepare("SELECT users.id, users.firstName, COUNT( users.id ), baby.id
									FROM users LEFT JOIN baby ON users.id = baby.user_id 
									WHERE (email = ?)AND (PASSWORD = ?)
									ORDER BY baby.firstName ASC LIMIT 1")))
	{
		echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
	}

	if(!($stmt->bind_param("ss",$username,$enc_psw))){
	echo "Bind failed: "  . $stmt->errno . " " . $stmt->error;
	}
	if(!$stmt->execute()){
		echo "Execute failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
	}
		
	if(!$stmt->bind_result($id, $firstName, $numUsers, $babyid)){
		echo "Bind failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
	}
	
	
		$stmt->fetch();
		$stmt->close();
		
		
	if ($numUsers==1) {
		echo 'true';
		$_SESSION['username'] = $firstName;
		$_SESSION['userid'] = $id;	
		if($babyid!=NULL)
		{
			$_SESSION['babyid']	= $babyid;			
		}
		
	}	
	else {
		echo 'false';
	}
	
	
	
	
?>