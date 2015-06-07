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
<div id="add_error"></div>
<form id="journalform" method="post" action="./" enctype="multipart/form-data">
<p>Title</p>
<p><input name="title" id="title" type="text" size="20"/></p>
<p>Date of Entry</p>
<p><input name="date" id="date" type="date" size="20" /></p>
<br/>
<p>Add Photograph:  <input type="file" class="optional" id="fileupload" name="fileupload" ></p>
<p>Journal Entry</p>
<p><textarea id="entry" name="entry" rows="20" cols="80"></textarea></p>
     <br/>
<?php

 	$obj->popMilestoneSelect(NULL,$_SESSION['babyid']);
?> 

  <input type="submit" name="journal" id="journal" value="Add Journal"></p>
  <br/>
</form> 


<?php
include("footer.php");
?>
