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
			$milestoneid = NULL;
			$amdetail1 = NULL;
			$amdetail2=NULL;
			$pmdetail1=NULL;
			$pmdetail2=NULL;
			$name=NULL;
			$date=NULL;
			$emotion=NULL;
			$photopath=NULL;
			
			//check if id of milestone record is provided
			if(isset($_GET['id']))
			{
				$id = $_GET['id'];			
				$mysqli=$obj->db_connect();				
				if(!($stmt = $mysqli->prepare("SELECT am.id, am.milestone_name, am.emotion, am.detail_1, am.detail_2, am.dateOccured, p.pathLocation, pm.detail_1, pm.detail_2 FROM actualMilestones am LEFT JOIN photos p ON am.photo_id = p.id INNER JOIN possibleMilestones pm ON pm.name = am.milestone_name WHERE am.id = ?"))){
					echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
				}	
				if(!($stmt->bind_param("i",$id))){
					echo "Bind failed: "  . $stmt->errno . " " . $stmt->error;
				}		
				if(!$stmt->execute()){
					echo "Execute failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
				}
				if(!$stmt->bind_result($milestoneid, $name, $emotion, $amdetail1, $amdetail2, $date, $photopath, $pmdetail1, $pmdetail2)){
			echo "Bind failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
				}				
				$stmt->fetch();	
			}		
						
		}		
	}
?>
<div id="milestoneform">
<div id="add_error"></div>
<form method="post" action="./" enctype="multipart/form-data">
<input type="hidden" name="mid" id="mid" value="<?php echo $milestoneid; ?>">
<p><?php echo '<h3>'.$name.'</h3>'; ?></p>
<p><?php echo $pmdetail1; ?></p>
<p><input id= "detail1" name="detail1" type="text" size="20" value="<?php echo $amdetail1; ?>"/></p>
<p><?php echo $pmdetail2; ?></p>
<p><input id = "detail2" name="detail2" type="text" size="20" value="<?php echo $amdetail2; ?>"/></p>
<p>Date Achieved</p>
<p><input id = "dateoccured" name="dateoccured" type="date" size="20" value="<?php echo $date; ?>"/></p>
<p>Add Photograph:  <input type="file" class="optional" id="fileupload" name="fileupload" /> 
<?php if(!empty($photopath)) echo "<br/><br/>Current image file: ".$photopath."<br/><br/>";?>
<p>How are you feeling?
<select id="emotion" name="emotion">
    <option>Happy!</option>
    <option>Nervous</option>
    <option>Sad</option>
    <option>Excited</option>
    <option>Funny</option>
</select></p>
<?php
	$msg = $obj->popJournalsSelect($milestoneid, $_SESSION['babyid']);
	echo $msg;
?> 

  <input id="updatemilestone" type="submit" name="updatemilestone" value="Update"></p>
  <br/>
</form> 
</div>

<?php
//Update record basesd on new values
/*if(isset($_POST['update']))
{
	$milestoneid = $_POST['mid'];
	//If new photo was provided 
		if(!empty($_FILES['fileupload']['name']))
		{
			$photoid=$obj->uploadPhoto($_SESSION['babyid'],$_FILES['fileupload']);
			$obj->updateMilestoneWithPhoto($milestoneid, $photoid, $_POST['detail1'], $_POST['detail2'], $_POST['dateoccured'], $_POST['emotion']);
			if(isset($_POST['journals'])){
				$obj->updateRelatedJournals($milestoneid,$_POST['journals']);
			}
			echo "Success! Your updated milestone has been saved!";			
		}
		//If no new photo was provided
		else
		{
			$obj->updateMilestoneWithoutPhoto($milestoneid, $_POST['detail1'], $_POST['detail2'], $_POST['dateoccured'], $_POST['emotion']);				
			if(isset($_POST['journals'])){
				$obj->updateRelatedJournals($milestoneid,$_POST['journals']);
			}
			echo "Success! Your updated milestone has been saved!";						
		}
}*/
include("footer.php");
?>