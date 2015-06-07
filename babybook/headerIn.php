<?php 
session_start();
include('clsBabyJournal.php');
$obj = new clsBabyJournal();

	 ?>

<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<title>Baby Journal</title>
		<script src="http://code.jquery.com/jquery-1.10.2.min.js"></script>
		<script src="js/validation.js"></script>
		<script src="js/journal.js"></script>
		<script src="js/milestone.js"></script>

		<link rel="stylesheet" type="text/css" href="style.css" />
	</head>

<body>
 <div>
		<a href="#"><img src="images/logo.gif" width="237" height="123" class="float" alt="setalpm" /></a>																																																																	
      <div class="topnav">
			<span><strong><?php echo "Welcome ".$_SESSION['username']."!" ?></strong></span> <br/>
            <p align="right"><strong><a href="logout.php">Logout</a></strong></p> 
	<!--		<select>
				<option>Query Babies</option>
				<option>Baby A</option>
				<option>Baby B</option>			
			</select>
        <span><a href="#">Add New Baby</a></span>		 -->  
		</div>
		<ul id="menu">
			<li><a href="aboutbaby.php"><img src="images/btn1.gif" alt="" width="110" height="32" /></a></li>
			<li><a href="journals.php"><img src="images/btn2.gif" alt="" width="110" height="32" /></a></li>
			<li><a href="milestones.php"><img src="images/btn3.gif" alt="" width="110" height="32" /></a></li>
			<li><a href="enterjournal.php"><img src="images/btn4.gif" alt="" width="110" height="32" /></a></li>
			<li><a href="entermilestone.php"><img src="images/btn5.gif" alt="" width="110" height="32" /></a></li>
			<li><a href="photogallery.php"><img src="images/btn6.gif" alt="" width="110" height="32" /></a></li>
		</ul>
	</div>
<div id="content">
		<div id="sidebar">
<div>
<?php 
$obj->popBabySidebar($_SESSION['babyid']);
	 ?>
			</div>
		</div>
	<div id="main">
<div id="inside">