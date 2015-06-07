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
	$obj->getAllJournals($_SESSION['babyid']);
}
else
{
	$id = $_GET['id'];	
	$obj->getJournal($id);
}



include("footer.php");
?>
