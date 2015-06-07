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
	if(isset($_SESSION['babyid']))
	{
		$babyid=$_SESSION['babyid'];
		echo "<div style='padding-left: 20px;'>";
		echo "<br/><br/>";
		$obj->getRecentJournals($babyid);
		$obj->getRecentMilestones($babyid);
		$obj->getRecentPhotographs($babyid);
		echo "</div>";		
	}

?>



<?php

include("footer.php");
?>
