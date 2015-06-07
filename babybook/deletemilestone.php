<?php

if(isset($_GET['id']))
{
	include('headerIn.php');
	$obj = new clsBabyJournal();
	$mysqli = $obj->db_connect();	
	$id = $_GET['id'];
	
	if(!($stmt = $mysqli->prepare("DELETE FROM actualMilestones WHERE id = ?"))){
			echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
		}		
		if(!($stmt->bind_param("i",$id))){
				echo "Bind failed: "  . $stmt->errno . " " . $stmt->error;
			}
		if(!$stmt->execute()){
			echo "Execute failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
		}
				
		$stmt->fetch();
		
		if(($stmt->affected_rows)==1)
		{
			echo "The milestone was successfully deleted.";			
		}
		else
		{	
			echo "There was an error deleting your milestone.  Please contact your system administrator.";			
		}
		$stmt->close();
	include('footer.php');
}
?>