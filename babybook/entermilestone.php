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
<form id="selectmilestone" name="selectmilestone" method="post" action="./">
<p>Please select a milestone:</p><br/>
<?php 
$selectid =NULL;
if(isset($_POST['milestones'])) {
		$selectid = $_POST['milestones'];
}


$obj->getPossibleMilestonesDropdown($selectid);?>
&nbsp;&nbsp;<input id="select" type="submit" name="select" value="Select"> 
</form> 
<br/>
<div id="milestoneform"></div>
<?php
/*
if(isset($_POST['btnSelectMilestone']))
{
	//populate fields according to specified milestone
		$selectedmilestone = $_POST["milestones"];
		echo '<form name="createmilestone" method="post" action="entermilestone.php" enctype="multipart/form-data">';
		$obj->popMilestoneFields($selectedmilestone);
		echo '<input type="hidden" name="mid" id="mid" value="'.$selectedmilestone.'" />
		<p>Date Achieved: <input name="entryDate" type="date" size="20" /></p><br/>
			<p>Add Photograph: <input id="fileupload" type="file" name="fileupload"></p><br/>
			<p>How are you feeling?
			<select name="emotion">
				<option>Happy!</option>
  				<option>Nervous</option>
  				<option>Sad</option>
    			<option>Excited</option>
    			<option>Funny</option>
			</select>
			</p>';
			
			$obj->popJournalsSelect(NULL, $_SESSION['babyid']);
		echo '<br/><input id="btnEnterMilestone" type="submit" name="btnEnterMilestone" value="Submit"></form><br/>';
}

if(isset($_POST['btnEnterMilestone']))
{
	//insert new milestone with photo
	if(!empty($_FILES['fileupload']['name']))
	{
		$photoid=$obj->uploadPhoto($_SESSION['babyid'],$_FILES['fileupload']);
		if($photoid!==NULL){
			$milestoneid=$obj->submitMilestone($_SESSION['babyid'], $photoid,$_POST["mid"], $_POST['entryDate'], $_POST['emotion'], $_POST['detail1'],$_POST['detail2']);
			if($milestoneid!==NULL){
				if(isset($_POST['journals'])){
					$obj->insertRelatedJournals($milestoneid,$_POST['journals']);
				}	
				echo "Success! Your milestone has been saved!";		
			}				
		}
	}
	else
	{//insert new milestone without photo
		$milestoneid=$obj->submitMilestone($_SESSION['babyid'], NULL, $_POST["mid"], $_POST['entryDate'], $_POST['emotion'], $_POST['detail1'],$_POST['detail2']);				
		if($milestoneid!==NULL){
			if(isset($_POST['journals'])){
				$obj->insertRelatedJournals($milestoneid,$_POST['journals']);
			}	
			echo "Success! Your milestone has been saved!";		
		}				
	}	
}*/
include("footer.php");
?>
