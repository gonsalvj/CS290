<?php		
		session_start();		
		unset($_SESSION['username']);
		unset($_SESSION['userid']);
		unset($_SESSION['babyid']);
		header('Location: index.php');	
	?>