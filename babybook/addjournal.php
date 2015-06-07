<?php
session_start();
ini_set('display_errors', 'On');	

if(isset($_POST['action']) && !empty($_POST['action'])) {
	$action = $_POST['action'];
	switch($action) {
			case 'addjournal':
				addjournal();
				break;
			
			case 'updatejournal':	
				updatejournal();
				break;			
	}


}


function addjournal() {
	include('clsBabyJournal.php');
	$obj = new clsBabyJournal();
	$mysqli = $obj->db_connect();

	$fileupload = $_FILES['fileupload'];
    $title = $_POST['title'];
    $date= $_POST['date'];
    $entry = $_POST['entry'];
    $milestones = json_decode($_POST['milestones']);	
    $babyid = $_SESSION['babyid'];
//insert journal with photo
		if(!empty($_FILES['fileupload']['name']))
		{
			$photoid=$obj->uploadPhoto($babyid, $_FILES['fileupload']);
			if($photoid!==NULL){
				$journalid=$obj->submitJournal($babyid, $photoid, $title, $date, $entry);
				if($journalid!==NULL){
					if(!empty($milestones)){
						$obj->insertRelatedMilestones($journalid,$milestones);
					}							
				} else {
					echo 'false';
				}

			}
		}
		else
		{
			//insert journal without photo
			$journalid=$obj->submitJournal($babyid, NULL, $title, $date, $entry);				
			if($journalid!==NULL){
				if(!empty($milestones)){
					$obj->insertRelatedMilestones($journalid,$milestones);
				}	
			} else {
				echo 'false';
			}				
		}
		echo 'true';
}

function updatejournal() {
	
include('clsBabyJournal.php');
	$obj = new clsBabyJournal();
	$mysqli = $obj->db_connect();
	$journalid = $_POST['jid'];

	if(!empty($_FILES['fileupload']['name']))
		{
			$photoid=$obj->uploadPhoto($_SESSION['babyid'],$_FILES['fileupload']);
			$obj->updateJournalWithPhoto($journalid, $photoid, $_POST['title'], $_POST['date'], $_POST['entry']);
			if(isset($_POST['milestones'])){
				$obj->updateRelatedMilestones($journalid, json_decode($_POST['milestones']));
			}
			echo 'true';			
		}
		//If no new photo was provided
		else
		{
			$obj->updateJournalWithoutPhoto($journalid, $_POST['title'], $_POST['date'], $_POST['entry']);				
			if(isset($_POST['milestones'])){
				$obj->updateRelatedMilestones($journalid,json_decode($_POST['milestones']));
			}
			echo 'true';						
		}


}
?>