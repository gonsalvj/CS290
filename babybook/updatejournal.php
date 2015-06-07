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
			$journalid = NULL;
			$title = NULL;
			$date = NULL;
			$entry=NULL;
			$photopath=NULL;
			//check if id of milestone record is provided
			if(isset($_GET['id']))
			{
				
				$id = $_GET['id'];
			
				$mysqli=$obj->db_connect();
				
				
				if(!($stmt = $mysqli->prepare("SELECT j.id, j.title, j.dateOfEntry, j.entry, p.pathLocation FROM journal j LEFT JOIN photos p ON j.photo_id=p.id WHERE j.id= ?"))){
					echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
				}	
				if(!($stmt->bind_param("i",$id))){
					echo "Bind failed: "  . $stmt->errno . " " . $stmt->error;
				}		
				if(!$stmt->execute()){
					echo "Execute failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
				}
				if(!$stmt->bind_result($journalid, $title, $date, $entry,$photopath)){
					echo "Bind failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
				}				
				$stmt->fetch();	
			}		
						
		}		
	}
?>
<div id="updatejournalform">
<div id="add_error"></div>
<form id="updatejournalform" method="post" action="./" enctype="multipart/form-data">
<p>Title</p>
<p><input id="jid" type="hidden" name="jid" id="jid" value="<?php echo $journalid; ?>">
<input id="title" name="title" type="text" size="20" value="<?php echo $title; ?>"/></p>
<p>Date of Entry</p>
<p><input id="date" name="date" type="date" size="20" value="<?php echo $date; ?>"/></p>
<br/>
<p>Add Photograph:  <input class="optional" type="file" id="fileupload" name="fileupload" /> 
<?php if(!empty($photopath)) echo "<br/><br/>Current image file: ".$photopath."<br/><br/>";?>
<p><textarea id="entry" name="entry" rows="20" cols="80"><?php echo $entry; ?></textarea></p>
     <br/>
<?php

 	echo $obj->popMilestoneSelect($journalid, $_SESSION['babyid']);
?> 

  <input id="updatejournal" type="submit" name="update" value="Update"></p>
  <br/>
</form> 
</div>

<?php
//Update record basesd on new values
/*if(isset($_POST['update']))
{
	$journalid = $_POST['jid'];
	//If new photo was provided 
		if(!empty($_FILES['fileupload']['name']))
		{
			$photoid=$obj->uploadPhoto($_SESSION['babyid'],$_FILES['fileupload']);
			$obj->updateJournalWithPhoto($journalid, $photoid, $_POST['title'], $_POST['entryDate'], $_POST['entry']);
			if(isset($_POST['milestones'])){
				$obj->updateRelatedMilestones($journalid,$_POST['milestones']);
			}
			echo "Success! Your updated journal has been saved!";			
		}
		//If no new photo was provided
		else
		{
			$obj->updateJournalWithoutPhoto($journalid, $_POST['title'], $_POST['entryDate'], $_POST['entry']);				
			if(isset($_POST['milestones'])){
				$obj->updateRelatedMilestones($journalid,$_POST['milestones']);
			}
			echo "Success! Your updated journal has been saved!";						
		}
}*/
include("footer.php");
?>
