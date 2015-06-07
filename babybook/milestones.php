<?php
	session_start();
	if (!isset($_SESSION['username'])) {
	header('Location: index.php');}
	else
	{
		if (!isset($_SESSION['babyid'])) {
			header('Location: newbaby.php');		
		}
		else{
			
			include('headerIn.php');
							
		}		
	}
?>

<?php

if(!isset($_GET['id']))
{
	$obj->getAllMilestones($_SESSION['babyid']);
}
else
{
	$obj->getMilestone($_GET['id']);	
}



include("footer.php");
?>
