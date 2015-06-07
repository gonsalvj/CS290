<?php 
session_start();
error_reporting(-1);
ini_set('display_errors', 'On');



if(isset($_POST['action']) && !empty($_POST['action'])) {
	$action = $_POST['action'];
	switch($action) {
			case 'selectmilestone':
				selectmilestone($_POST['milestoneid']);
				break;
			
			case 'addmilestone':	
				addmilestone();
				break;
			case 'updatemilestone':
				updatemilestone();
				break;
	}


}

function addmilestone() {

	include('clsBabyJournal.php');
	$obj = new clsBabyJournal();
	if(!empty($_FILES['fileupload']['name'])) {
		$photoid=$obj->uploadPhoto($_SESSION['babyid'],$_FILES['fileupload']);
		if($photoid!==NULL){
			$milestoneid=$obj->submitMilestone($_SESSION['babyid'], $photoid,$_POST["mid"], $_POST['entryDate'], $_POST['emotion'], $_POST['detail1'],$_POST['detail2']);
			if($milestoneid!==NULL){
				if(isset($_POST['journals'])){
					$obj->insertRelatedJournals($milestoneid,json_decode($_POST['journals']));
				}	
				echo 'true';		
			} else {
				echo 'false';
			}	
		}
	} else {//insert new milestone without photo
		$milestoneid=$obj->submitMilestone($_SESSION['babyid'], NULL, $_POST["mid"], $_POST['entryDate'], $_POST['emotion'], $_POST['detail1'],$_POST['detail2']);				
		if($milestoneid!==NULL){
			if(isset($_POST['journals'])){
				$obj->insertRelatedJournals($milestoneid,json_decode($_POST['journals']));
			}	
			echo 'true';		
		} else {
			echo 'false';
		}	
	}	
}

function selectmilestone($milestoneid) {

	include('clsBabyJournal.php');
	$obj = new clsBabyJournal();
	$selectedmilestone = $milestoneid;
	$form = '<form name="addmilestone" method="post" action="entermilestone.php" enctype="multipart/form-data">';
	$form .= $obj->popMilestoneFields($selectedmilestone);
	$form .='<input type="hidden" name="mid" id="mid" value="'.$selectedmilestone.'" />
			<p>Date Achieved: <input id="entryDate" name="entryDate" type="date" size="20" /></p><br/>
				<p>Add Photograph: <input class="optional" id="fileupload" type="file" name="fileupload"></p><br/>
				<p>How are you feeling?
				<select id="emotion" name="emotion">
					<option>Happy!</option>
	  				<option>Nervous</option>
	  				<option>Sad</option>
	    			<option>Excited</option>
	    			<option>Funny</option>
				</select>
				</p>';
	$form .= $obj->popJournalsSelect(NULL, $_SESSION['babyid']);
	$form .= '<br/><input id="createmilestone" type="submit" name="createmilestone" value="Add Milestone"></form><br/>';
	echo $form;
}

function updatemilestone() {
include('clsBabyJournal.php');
	$obj = new clsBabyJournal();
if(!empty($_FILES['fileupload']['name']))
		{
			$photoid=$obj->uploadPhoto($_SESSION['babyid'],$_FILES['fileupload']);
			$obj->updateMilestoneWithPhoto($_POST['mid'], $photoid, $_POST['detail1'], $_POST['detail2'], $_POST['entryDate'], $_POST['emotion']);
			if(isset($_POST['journals'])){
				$obj->updateRelatedJournals($_POST['mid'],json_decode($_POST['journals']));
			}
			echo 'true';		
		}
		//If no new photo was provided
		else
		{
			$obj->updateMilestoneWithoutPhoto($_POST['mid'], $_POST['detail1'], $_POST['detail2'], $_POST['entryDate'], $_POST['emotion']);				
			if(isset($_POST['journals'])){
				$obj->updateRelatedJournals($_POST['mid'],json_decode($_POST['journals']));
			}
			echo 'true';					
		}


}


?>