<?php
//functions to access and interact with database
class clsBabyJournal
{	
	
/**
 * db_connect
 *
 * connects to database
 *
 * @return $mysqli object
 */	
	function db_connect()
	{	
		ini_set('display_errors', 'On');
		$hostname = 'oniddb.cws.oregonstate.edu';       
		$dbname   = 'gonsalvj-db';
		$username = 'gonsalvj-db';
		$password = 'PAKum3uAIPOc0WJY';
	
		$mysqli = new mysqli($hostname,$dbname ,$password,$username);
		if($mysqli->connect_errno){
			echo "Connection error " . $mysqli->connect_errno . " " . $mysqli->connect_error;
		}	
				return $mysqli;
	}	
/**
 * addBaby
 *
 * Inserts new record to the baby table
 *
 * @param userid, fname, lname, dob, haircolour, eyecolour, height, weight
 * @return id (primary key) of newly created record
 */
	function addBaby($userid, $fname, $lname, $dob, $haircolour, $eyecolour, $height, $weight)
	{
		$obj = new clsBabyJournal();
		$mysqli = $obj->db_connect();

		if(!($stmt = $mysqli->prepare("INSERT INTO baby (user_id, firstName, lastName, dateOfBirth, hairColour, eyeColour, height, weight) VALUES (?,?,?,?,?,?,?,?)"))){
			echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
		}
		if(!($stmt->bind_param("isssssii", $userid, $fname, $lname, $dob, $haircolour, $eyecolour, $height, $weight))){
			echo "Bind failed: "  . $stmt->errno . " " . $stmt->error;
		}
		if(!$stmt->execute()){
			echo "Execute failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
		}
		$babyid = $stmt->insert_id;
		$_SESSION['babyid'] = $babyid;		
		$stmt->close();			
		return $babyid;
				
	}
	
/**
 * updateBabyPhoto
 *
 * Updates a baby record with photo_id
 *
 * @param babyid, photoid
 * @return no return
 */
	
	function updateBabyPhoto($babyid, $photoid)
	{
		$obj = new clsBabyJournal();
			$mysqli = $obj->db_connect();
			$newid = NULL;			
		 
			if(!($stmt = $mysqli->prepare("UPDATE baby SET photo_id=? WHERE id = ?"))){
				echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
			}
			if(!($stmt->bind_param("ii", $photoid,$babyid))){
				echo "Bind failed: "  . $stmt->errno . " " . $stmt->error;
			}
			if(!$stmt->execute()){			
				echo "Execute failed: "  . $stmt->errno . " " . $stmt->error;
			} 
			$stmt->close();		
		
	}
	/**
	 * deleteRelatedMilestones
	 *
	 * Deletes all milestones from the many to many relationship with a specified journal
	 * Does NOT delete the milestones, only it's relation to a specified journal
	 * @param journalid
	 * @return no return
	 */
	function deleteRelatedMilestones($journalid)
	{
		$obj = new clsBabyJournal();
		$mysqli = $obj->db_connect();			
		if(!($stmt = $mysqli->prepare("DELETE FROM journalMilestones WHERE journal_id = ?"))){
			echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
		}		
		if(!($stmt->bind_param("i",$journalid))){
				echo "Bind failed: "  . $stmt->errno . " " . $stmt->error;
			}
		if(!$stmt->execute()){
			echo "Execute failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
		}				
		$stmt->fetch();		
	}	
	/**
	 * deleteRelatedJournals
	 *
	 * Deletes all journals from the many to many relationship with a specified milestone
	 * Does NOT delete the journals, only it's relation to a specified milestone
	 * @param milestoneid
	 * @return no return
	 */
	function deleteRelatedJournals($milestoneid)
	{
		$obj = new clsBabyJournal();
		$mysqli = $obj->db_connect();			
		if(!($stmt = $mysqli->prepare("DELETE FROM journalMilestones WHERE milestone_id = ?"))){
			echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
		}		
		if(!($stmt->bind_param("i",$milestoneid))){
				echo "Bind failed: "  . $stmt->errno . " " . $stmt->error;
			}
		if(!$stmt->execute()){
			echo "Execute failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
		}				
		$stmt->fetch();				
	}
	/**
	 * getAllJournals
	 *
	 * Gets all journals related to a specified baby_id
	 * @param baby_id
	 * @return no return, but prints formatted journals
	 */
	function getAllJournals($babyid)
	{			
		$obj = new clsBabyJournal();
		$mysqli = $obj->db_connect();								
		if(!($stmt = $mysqli->prepare("SELECT j.id, j.title, j.dateOfEntry, j.entry, p.pathLocation FROM journal j LEFT JOIN photos p ON j.photo_id=p.id WHERE j.baby_id = ? ORDER BY j.dateOfEntry DESC"))){
			echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
		}	
		if(!($stmt->bind_param("i",$babyid))){
				echo "Bind failed: "  . $stmt->errno . " " . $stmt->error;
		}	
		if(!$stmt->execute()){
			echo "Execute failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
		}
		if(!$stmt->bind_result($id, $title, $date, $entry,$photopath)){
			echo "Bind failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
		}		
		while($stmt->fetch()){
			echo "<br/><p align='right'><strong><a href='updatejournal.php?id=".$id."'>Update</a> | <a href='deletejournal.php?id=".$id."'>Delete</a></strong></p>";
			echo "<h3>".$title."</h3>";
			echo "<p>Date: ".$date."</p>";
			if($photopath!=NULL)
			{
				echo "<p><img src='".$photopath."' alt='' style='width:304px;height:228px'></p><br/>";		
			}
			echo "<p>".$entry."</p>";
			echo "<br/><HR width='650px'><br/>";
			$obj->relatedMilestoneLinks($id);
			echo "<br/><HR width='650px'><br/>";
		}					
		$stmt->close();		
	}
	/**
	 * getAllMilestones
	 *
	 * Gets all milestones related to a specified baby_id
	 * @param baby_id
	 * @return no return, but prints formatted milestones
	 */
	function getAllMilestones($babyid)
	{
		$obj = new clsBabyJournal();
		$mysqli = $obj->db_connect();								
		if(!($stmt = $mysqli->prepare("SELECT am.id, am.milestone_name, am.emotion, am.detail_1, am.detail_2, am.dateOccured, p.pathLocation, pm.detail_1, pm.detail_2 FROM actualMilestones am LEFT JOIN photos p ON am.photo_id = p.id INNER JOIN possibleMilestones pm ON pm.name = am.milestone_name WHERE am.baby_id = ? ORDER BY am.dateOccured DESC"))){
			echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
		}		
		
		if(!($stmt->bind_param("i",$babyid))){
				echo "Bind failed: "  . $stmt->errno . " " . $stmt->error;
		}
		if(!$stmt->execute()){
			echo "Execute failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
		}
		if(!$stmt->bind_result($id, $name, $emotion, $amdetail1, $amdetail2, $date, $photopath, $pmdetail1, $pmdetail2)){
			echo "Bind failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
		}		
		while($stmt->fetch()){
			echo "<p align='right'><strong><a href='updatemilestone.php?id=".$id."'>Update</a> | <a href='deletemilestone.php?id=".$id."'>Delete</a></strong></p>";
			echo "<h3>".$name."</h3>";
			echo "<p>Date: ".$date."</p>";
			if($photopath!=NULL)
			{
				echo "<p><img src='".$photopath."' alt='' style='width:304px;height:228px'></p>";		
			}
			
			echo "</br><p>".$pmdetail1.": <br/> ".$amdetail1."</p>";
			echo "</br><p>".$pmdetail2.": <br/>".$amdetail2."</p>";
			echo "</br><p>How did you feel? ".$emotion."</p>";
			echo "<br/><HR width='650px'><br/>";
			$obj->relatedJournalLinks($id);
			echo "<br/><HR width='650px'><br/>";
		}					
		$stmt->close();				
	}	
	/**
	 * getJournal
	 *
	 * Gets and prints a specified journal to the page
	 * @param id (journal id)
	 */
	function getJournal($id)
	{	
		$obj = new clsBabyJournal();
		$mysqli = $obj->db_connect();	
		if(!($stmt = $mysqli->prepare("SELECT j.id, j.title, j.dateOfEntry, j.entry, p.pathLocation FROM journal j LEFT JOIN photos p ON j.photo_id=p.id WHERE j.id= ?"))){
			echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
		}	
		if(!($stmt->bind_param("i",$id))){
				echo "Bind failed: "  . $stmt->errno . " " . $stmt->error;
		}		
		if(!$stmt->execute()){
			echo "Execute failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
		}
		if(!$stmt->bind_result($id, $title, $date, $entry,$photopath)){
			echo "Bind failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
		}		
		$stmt->fetch();
	echo "<br/><p align='right'><strong><a href='updatejournal.php?id=".$id."'>Update</a> | <a href='deletejournal.php?id=".$id."'>Delete</a></strong></p>";
		echo "<h3>".$title."</h3>";
		echo "<p>Date: ".$date."</p>";
		if($photopath!=NULL)
		{
			echo "<p><img src='".$photopath."' alt='".$id."' style='width:304px;height:228px'></p>";	
		}
			
		echo "<p>".$entry."</p>";
		echo "<br/><HR width='650px'><br/>";
		$obj->relatedMilestoneLinks($id);
		echo "<br/><HR width='650px'><br/>";				
		$stmt->close();	
	}
	/**
	 * getMilestone
	 *
	 * Gets and prints a specified milestone to the page
	 * @param mlestoneid
	 */
	function getMilestone($milestoneid)
	{
		$obj = new clsBabyJournal();
		$mysqli = $obj->db_connect();								
		if(!($stmt = $mysqli->prepare("SELECT am.id, am.milestone_name, am.emotion, am.detail_1, am.detail_2, am.dateOccured, p.pathLocation, pm.detail_1, pm.detail_2 FROM actualMilestones am LEFT JOIN photos p ON am.photo_id = p.id INNER JOIN possibleMilestones pm ON pm.name = am.milestone_name WHERE am.id = ?"))){
			echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
		}			
		if(!($stmt->bind_param("i",$milestoneid))){
				echo "Bind failed: "  . $stmt->errno . " " . $stmt->error;
		}
		if(!$stmt->execute()){
			echo "Execute failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
		}
		if(!$stmt->bind_result($id, $name, $emotion, $amdetail1, $amdetail2, $date, $photopath, $pmdetail1, $pmdetail2)){
			echo "Bind failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
		}	
		$stmt->fetch();	
		echo "<p align='right'><strong><a href='updatemilestone.php?id=".$id."'>Update</a> | <a href='deletemilestone.php?id=".$id."'>Delete</a></strong></p>";
		echo "<h3>".$name."</h3>";
		echo "<p>Date: ".$date."</p>";
		if($photopath!=NULL)
		{
			echo "<p><img src='".$photopath."' alt='' style='width:304px;height:228px'></p>";		
		}		
		echo "</br><p>".$pmdetail1.": <br/> ".$amdetail1."</p>";
		echo "</br><p>".$pmdetail2.": <br/>".$amdetail2."</p>";
		echo "</br><p>How did you feel? <br/>".$emotion."</p>";
		echo "<br/><HR width='650px'><br/>";
		$obj->relatedJournalLinks($id);
		echo "<br/><HR width='650px'><br/>";						
		$stmt->close();			
	}	
	/**
	 * getRecentJournals
	 *
	 * Gets and prints upto the five most recent journal entries according to date of entry of a specified babyid
	 * @param babyid
	 */
	function getRecentJournals($babyid)
	{
		$obj = new clsBabyJournal();
		$mysqli = $obj->db_connect();								
		if(!($stmt = $mysqli->prepare("SELECT j.id, j.title FROM journal j WHERE j.baby_id = ? ORDER BY j.id DESC limit 5"))){
			echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
		}	
		if(!($stmt->bind_param("i",$babyid))){
				echo "Bind failed: "  . $stmt->errno . " " . $stmt->error;
		}	
		if(!$stmt->execute()){
			echo "Execute failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
		}
		if(!$stmt->bind_result($id, $title)){
			echo "Bind failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
		}		
		
		echo "Most recent journals: <br/><ul>";
		while($stmt->fetch()){
			echo "<li><a href='journals.php?id=".$id."'>".$title."</a></li>";			
		}					
		echo "</ul>";
		$stmt->close();		
	}
	/**
	 * getRecentMilestones
	 *
	 * Gets and prints upto the five most recent milestone entries according to date occured of a specified babyid
	 * @param babyid
	 */
	function getRecentMilestones($babyid)
	{
		$obj = new clsBabyJournal();
		$mysqli = $obj->db_connect();								
		if(!($stmt = $mysqli->prepare("SELECT am.id, am.milestone_name, am.dateOccured FROM actualMilestones am WHERE am.baby_id = ? ORDER BY am.id DESC limit 5"))){
			echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
		}		
		
		if(!($stmt->bind_param("i",$babyid))){
				echo "Bind failed: "  . $stmt->errno . " " . $stmt->error;
		}
		if(!$stmt->execute()){
			echo "Execute failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
		}
		if(!$stmt->bind_result($id, $name, $date)){
			echo "Bind failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
		}		
		echo "<br/>Most recent milestones: <br/><ul>";
		while($stmt->fetch()){
			echo "<li><a href='milestones.php?id=".$id."'>".$name.' - '.$date."</a></li>";			
		}					
		echo "</ul>";		$stmt->close();			
	}
	/**
	 * getRelatedJournals
	 *
	 * Gets all journals related to a specified milestoneid
	 * @param milestoneid
	 * @return array of journalids
	 */	
	function getRelatedJournals($milestoneid)
	{
		$obj = new clsBabyJournal();
		$mysqli = $obj->db_connect();								
		if(!($stmt = $mysqli->prepare("SELECT j.id FROM journalMilestones jm INNER JOIN journal j ON jm.journal_id = j.id WHERE jm.milestone_id =?"))){
			echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
		}	
		if(!($stmt->bind_param("i",$milestoneid))){
				echo "Bind failed: "  . $stmt->errno . " " . $stmt->error;
			}	
		if(!$stmt->execute()){
			echo "Execute failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
		}
		if(!$stmt->bind_result($journalids)){
			echo "Bind failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
		}	
		$rows = array();	
		while($stmt->fetch()){
			
			$rows[] = $journalids;
		}					
		$stmt->close();		
		return $rows;	
	}
	
	/**
	 * getRecentPhotographs
	 *
	 * Gets and prints upto the five most recent photos according to photo id of a specified babyid
	 * @param babyid
	 */
	function getRecentPhotographs($babyid)
	{
		$obj = new clsBabyJournal();
		$mysqli = $obj->db_connect();								
		if(!($stmt = $mysqli->prepare("SELECT p.id, p.pathLocation FROM photos p WHERE p.baby_id = ? ORDER BY p.id DESC limit 5"))){
			echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
		}	
		if(!($stmt->bind_param("i",$babyid))){
				echo "Bind failed: "  . $stmt->errno . " " . $stmt->error;
		}	
		if(!$stmt->execute()){
			echo "Execute failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
		}
		if(!$stmt->bind_result($id, $photopath)){
			echo "Bind failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
		}			
		echo "<br/>Most recent photos: <br/>";
		while($stmt->fetch()){
			echo "<a href='photogallery.php?id=".$id."'><img src='".$photopath."' alt=''style='padding: 5px 5px 5px 0px;width:100px;height:100px'></a>";			
		}				
		$stmt->close();		
	}
	/**
	 * getRelatedMilestones
	 *
	 * Gets all milestones related to a specified journalid
	 * @param journalid
	 * @return array of milestoneids
	 */	
	function getRelatedMilestones($journalid)
	{
		$obj = new clsBabyJournal();
		$mysqli = $obj->db_connect();								
		if(!($stmt = $mysqli->prepare("SELECT am.id FROM journalMilestones jm INNER JOIN actualMilestones am ON jm.milestone_id = am.id WHERE jm.journal_id =?"))){
			echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
		}	
		if(!($stmt->bind_param("i",$journalid))){
				echo "Bind failed: "  . $stmt->errno . " " . $stmt->error;
			}	
		if(!$stmt->execute()){
			echo "Execute failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
		}
		if(!$stmt->bind_result($milestoneids)){
			echo "Bind failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
		}	
		$rows = array();	
		while($stmt->fetch()){
			
			$rows[] = $milestoneids;
		}					
		$stmt->close();		
		return $rows;	
	}	
	/**
	 * getPossibleMilestonesDropdown
	 *
	 * Gets all POSSIBLE milestones and populate and print a dropdown of all POSSIBLE milestones
	 * @param $selectid (if a specific possible milestone should be selected)	 
	 */	
	function getPossibleMilestonesDropdown($selectid)
	{
		$obj = new clsBabyJournal();
		$mysqli = $obj->db_connect();	
		if(!($stmt = $mysqli->prepare("SELECT id, name FROM possibleMilestones"))){
			echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
		}		
		if(!$stmt->execute()){
			echo "Execute failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
		}
		
		if(!$stmt->bind_result($id, $name)){
			echo "Bind failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
		}	
				
		echo '<select id="possmilestones" name="possmilestones">';
		while($stmt->fetch()){			
			
			if($selectid!=$id)
			{
				echo '<option value="'.$id.'"> '. $name.'</option>';	
			}
			else
			{
				echo '<option value="'.$id.'" selected > '.$name.'</option>';	
			}
		
		}
		echo '</select>';								
		$stmt->close();				
	}
	/**
	 * getAllPhotos
	 *
	 * Gets all and print (link/images) of all photos related to a specified babyid
	 * @param babyid	 
	 */	
	function getAllPhotos($babyid)
	{
		$obj = new clsBabyJournal();
		$mysqli = $obj->db_connect();								
		if(!($stmt = $mysqli->prepare("SELECT p.id, p.pathLocation FROM photos p WHERE p.baby_id = ? ORDER BY p.id DESC"))){
			echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
		}	
		if(!($stmt->bind_param("i",$babyid))){
				echo "Bind failed: "  . $stmt->errno . " " . $stmt->error;
		}	
		if(!$stmt->execute()){
			echo "Execute failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
		}
		if(!$stmt->bind_result($id, $photopath)){
			echo "Bind failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
		}			
		while($stmt->fetch()){
			echo "<a href='photogallery.php?id=".$id."'><img src='".$photopath."' alt=''style='padding: 5px 5px 5px 0px;width:100px;height:100px'></a>";			
		}				
		$stmt->close();				
	}
	/**
	 * getPhoto
	 *
	 * Gets all and print (link/images) of a specified photo
	 * @param photoid	 
	 */	
	function getPhoto($photoid)
	{
		$obj = new clsBabyJournal();
		$mysqli = $obj->db_connect();								
		if(!($stmt = $mysqli->prepare("SELECT p.pathLocation FROM photos p WHERE p.id =?"))){
			echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
		}	
		if(!($stmt->bind_param("i",$photoid))){
				echo "Bind failed: "  . $stmt->errno . " " . $stmt->error;
		}	
		if(!$stmt->execute()){
			echo "Execute failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
		}
		if(!$stmt->bind_result($photopath)){
			echo "Bind failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
		}			
		$stmt->fetch();
		echo "<img src='".$photopath."' alt=''style='padding: 5px 5px 5px 0px;width:90%;height:90%'>";			
		$stmt->close();	
	}
	/**
	 * insertRelatedJournals
	 *
	 * Inserts a relation into the journalMilestones table of the specified milestone with each of the specfied journalids in 					     * the array.  The row is only inserted if the combination of milestone and journal id does not already exist
	 * @param milestoneid, 	journalids 
	 */	
	function insertRelatedJournals($milestoneid, $journalids)
	{
		$obj = new clsBabyJournal();
		$mysqli = $obj->db_connect();
		$prequery = "INSERT IGNORE INTO journalMilestones(journal_id, milestone_id) VALUES";
		foreach($journalids as $selected)
		{
			$prequery .= "(".$mysqli->real_escape_string($selected).",".$milestoneid."),";				
		}	
		$sqlquery = rtrim($prequery,',');
		$sqlquery.=";";
		$newid=NULL;
		
	
		if(!($stmt = $mysqli->prepare($sqlquery))){
			echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
		}
		if(!$stmt->execute()){
			echo "Execute failed: "  . $stmt->errno . " " . $stmt->error;
		}
		else{
			$newid = $stmt->insert_id;			
		}		
		$stmt->close();
		return $newid;	
	}
		
	/**
	 * insertRelatedMilestones
	 *
	 * Inserts a relation into the journalMilestones table of the specified journalid with each of the specfied milestoneids in 	 
	 * the array.  The row is only inserted if the combination of milestone and journal id does not already exist
	 * @param journalid, milestoneids 
	 */	
	function insertRelatedMilestones($journalid, $milestoneids)
	{
		$obj = new clsBabyJournal();
		$mysqli = $obj->db_connect();
		
		$prequery = "INSERT IGNORE INTO journalMilestones(journal_id, milestone_id) VALUES";
		foreach($milestoneids as $selected)
		{
			$prequery .= "(".$journalid.",".$mysqli->real_escape_string($selected)."),";				
		}	
		$sqlquery = rtrim($prequery,',');
		$sqlquery.=";";
		$newid=NULL;
		
		if(!($stmt = $mysqli->prepare($sqlquery))){
			echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
		}
		if(!$stmt->execute()){
			echo "Execute failed: "  . $stmt->errno . " " . $stmt->error;
		}
		else{
			$newid = $stmt->insert_id;			
		}		
		$stmt->close();
		return $newid;	
	}
	/**
	 * popBabySidebar
	 *
	 * Get and populate formatted row from baby table
	 * @param babyid
	 */	
	function popBabySidebar($babyid)
	{
		$obj = new clsBabyJournal();
		$mysqli = $obj->db_connect();								
		if(!($stmt = $mysqli->prepare("SELECT firstName, lastName, dateOfBirth, hairColour,eyeColour, height, weight, p.pathLocation FROM baby b INNER JOIN photos p ON b.photo_id=p.id WHERE b.id = ?"))){
			echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
		}		
		if(!($stmt->bind_param("i",$babyid))){
				echo "Bind failed: "  . $stmt->errno . " " . $stmt->error;
		}
		if(!$stmt->execute()){
			echo "Execute failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
		}
		if(!$stmt->bind_result($fname, $lname, $dob, $hair, $eyes, $height, $weight, $imgpath)){
			echo "Bind failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
		}	
	
		$stmt->store_result();
		if($stmt->num_rows>0)
		{
			$stmt->fetch();
		
			echo '<img src="images/about.jpg" alt="" width="233" height="41" /><br />
				<div class="categories">
					<img src="'.$imgpath.'" alt="" width="181" height="161" /><br /><br/>
					<p><h3>'.$fname.' '.$lname.'</h3></p><br />
					<p>Little '.$fname.' '.$lname.' was born on '.$dob.'. '.$fname.' was born weighing at '.$weight.' grams and measuring '.$height.' cm long. '
					.$fname.' has '.$eyes. ' eyes and was born with '.$hair.' hair.</p></div>';		
		}	
		else
		{
			echo '<img src="images/about.jpg" alt="" width="233" height="41" /><br />
				<div class="categories"><p>
					Please Complete Babies Profile!					
				</p></div>';		
			
		}		
		$stmt->close();		
	}
	/**
	 * popJournalsSelect
	 *
	 * Get and populate multiple select of all journals related to a specified baby id, any journalids related to the specified     * milestone id should be selected
	 * @param $milestoneid, $babyid
	 */	
	function popJournalsSelect($milestoneid, $babyid)
	{
		$obj = new clsBabyJournal();
		$mysqli = $obj->db_connect();	
		$msg =''; 							
		if(!($stmt = $mysqli->prepare("SELECT id, title, dateOfEntry FROM journal WHERE baby_id = ?"))){
			$msg = "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
		}		
		if(!($stmt->bind_param("i",$babyid))){
				$$msg = "Bind failed: "  . $stmt->errno . " " . $stmt->error;
		}	
		if(!$stmt->execute()){
			$msg = "Execute failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
		}
		if(!$stmt->bind_result($id, $title, $date)){
			$msg = "Bind failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
		}	
	
		$stmt->store_result();
		if($stmt->num_rows>0)
		{
			$msg = "<p><br/>Is this milestone related to any of your journals?</p><br/>";
			$msg .= '<select id="journals" class="optional" name="journals[]" size=5 multiple>';
			if(!empty($milestoneid))
			{
				$journalids = $obj->getRelatedJournals($milestoneid);		
			}	

			if(!empty($journalids))
			{
				while($stmt->fetch()){						
					if(in_array($id,$journalids))
					{
						$msg .= '<option value=" '. $id .'" selected> '.$title.' '.$date . '</option>';								
					}				
					else
					{
						$msg .= '<option value="'. $id .'">' . $title.' '.$date . '</option>';
					}													
				}	
			} else {
				while($stmt->fetch()){					
					$msg .= '<option value=" '. $id . ' "> ' . $title.' '.$date . '</option>';		
				}				
			}	
				$msg .= '</select><br/>';					
		}						
		$stmt->close();		
		return $msg;		
	}
	/**
	 * popMilestoneFields
	 *
	 * Get and populate milestone headings according to specified milestone id
	 * @param $id
	 */	
	function popMilestoneFields($id)
	{
		$obj = new clsBabyJournal();
		$mysqli = $obj->db_connect();	
		$msg = '';

		if(!($stmt = $mysqli->prepare("SELECT id, name, description, detail_1, detail_2 FROM possibleMilestones WHERE id =?"))){
			$msg = "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
		}	
		if(!($stmt->bind_param("i",$id))){
				$msg = "Bind failed: "  . $stmt->errno . " " . $stmt->error;
			}	
		if(!$stmt->execute()){
			$msg = "Execute failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
		}
		
		if(!$stmt->bind_result($id, $name, $description, $detail_1, $detail_2)){
			$msg = "Bind failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
		}	
		if($stmt->fetch())
		{
			$msg = '<p><h3>'.$description.'</h3></p><br/>';
			$msg .= '<p>'.$detail_1.'</p><br/>';
			$msg .= '<p><input id="detail1" name="detail1" type="text" size="20" /></p><br/>';
			$msg .= '<p>'.$detail_2.'</p><br/>';
			$msg .= '<p><input id="detail2" name="detail2" type="text" size="20" /></p><br/>';			
		}									
		$stmt->close();	
		return $msg;		
	}
	/**
	 * popMilestoneSelect
	 *
	 * Get and populate multiple select of all milestones related to a specified baby id, any milestoneids related to the 					     * specified journal id should be selected
	 * @param $journalid, $babyid
	 */	
	function popMilestoneSelect($journalid, $babyid)
	{
		$obj = new clsBabyJournal();
		$mysqli = $obj->db_connect();	
		if(!($stmt = $mysqli->prepare("SELECT id, dateOccured, milestone_name FROM actualMilestones WHERE baby_id = ?"))){
			echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
		}			
		if(!($stmt->bind_param("i",$babyid))){
				echo "Bind failed: "  . $stmt->errno . " " . $stmt->error;
		}	
		if(!$stmt->execute()){
			echo "Execute failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
		}
		if(!$stmt->bind_result($id, $date, $name)){
			echo "Bind failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
		}
		$stmt->store_result();
		if($stmt->num_rows>0)
		{
			echo "<p>Are there milesteones related to this journal?</p>";
			echo '<select class="optional" id="milestones" name="milestones[]" size=5 multiple>';
			if(!empty($journalid))
			{
				$milestoneids = $obj->getRelatedMilestones($journalid);	

			}				
			if(!empty($milestoneids))
			{
				while($stmt->fetch()){					
					if(in_array($id,$milestoneids))
					{
						echo '<option value="'.$id.'" selected>'.$name.' '.$date.'</option>';
					}
					else
					{
						echo '<option value="'.$id.'"> '.$name.' '.$date.'</option>';	
					}		
				}
			} else 	{
				while($stmt->fetch()){					
					echo '<option value="'.$id.'"> '.$name.' '.$date.'</option>';	
				}
			}	
			echo '</select><br/>';			
		}						
		$stmt->close();		
	}
	/**
	 * updateRelatedJournals
	 *
	 * Delete all rows from journalMilestones table according to specific milestoneid
	 * Insert a row for each milestoneid/journalid specified
	 * @param $milestoneid, $journalids
	 */	
	function updateRelatedJournals($milestoneid, $journalids)
	{
		$obj = new clsBabyJournal();		
		$obj->deleteRelatedJournals($milestoneid);		
		$obj->insertRelatedJournals($milestoneid, $journalids);		
	}
	/**
	 * updateRelatedMilestones
	 *
	 * Delete all rows from journalMilestones table according to specific journalid
	 * Insert a row for each milestoneid/journalid specified
	 * @param $journalid, $milestoneids
	 */		
	function updateRelatedMilestones($journalid, $milestoneids)
	{ 
		$obj = new clsBabyJournal();		
		$obj->deleteRelatedMilestones($journalid);		
		$obj->insertRelatedMilestones($journalid, $milestoneids);			
	}
	/**
	 * updateJournalWithPhoto
	 *
	 * Update journal 
	 * @param $journalid, $photoid, $title, $date, $entry
	 */		
	function updateJournalWithPhoto($journalid, $photoid, $title, $date, $entry)
	{
			$obj = new clsBabyJournal();
			$mysqli = $obj->db_connect();
			$newid = NULL;			
		 
			if(!($stmt = $mysqli->prepare("UPDATE journal SET photo_id=?, title = ?, dateOfEntry = ?, entry = ? WHERE id = ?"))){
				echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
			}
			if(!($stmt->bind_param("isssi", $photoid,$title,$date,$entry, $journalid))){
				echo "Bind failed: "  . $stmt->errno . " " . $stmt->error;
			}
			if(!$stmt->execute()){			
				echo "Execute failed: "  . $stmt->errno . " " . $stmt->error;
			} 
			$stmt->close();			
	}	  
	/**
	 * updateJournalWithoutPhoto
	 *
	 * Update journal (no new photo id)
	 * @param $journalid, $title, $date, $entry
	 */	
	function updateJournalWithoutPhoto($journalid, $title, $date, $entry)
	{
			$obj = new clsBabyJournal();
			$mysqli = $obj->db_connect();
			$numrows = NULL;					 

			if(!($stmt = $mysqli->prepare("UPDATE journal SET title = ?, dateOfEntry = ?, entry = ? WHERE id = ?"))){
				echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
			}
			if(!($stmt->bind_param("sssi", $title,$date,$entry, $journalid))){
				echo "Bind failed: "  . $stmt->errno . " " . $stmt->error;
			}
			if(!$stmt->execute()){			
				echo "Execute failed: "  . $stmt->errno . " " . $stmt->error;
			} 
			else{
				$numrows = $stmt->affected_rows;				
			}
			$stmt->close();	
			
	}
	/**
	 * updateMilestoneWithPhoto
	 *
	 * Update actualMilestones 
	 * @param $milestoneid, $photoid, $detail1, $detail2, $dateOccured, $emotion
	 */	
	function updateMilestoneWithPhoto($milestoneid, $photoid, $detail1, $detail2, $dateOccured, $emotion)
	{
		$obj = new clsBabyJournal();
			$mysqli = $obj->db_connect();
			$newid = NULL;			
		 
			if(!($stmt = $mysqli->prepare("UPDATE actualMilestones SET photo_id=?, detail_1 = ?, detail_2 = ?, dateOccured = ?, emotion = ? WHERE id = ?"))){
				echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
			}
			if(!($stmt->bind_param("isssi", $photoid,$detail1,$detail2,$dateOccured, $emotion,$milestoneid))){
				echo "Bind failed: "  . $stmt->errno . " " . $stmt->error;
			}
			if(!$stmt->execute()){			
				echo "Execute failed: "  . $stmt->errno . " " . $stmt->error;
			} 
			$stmt->close();		
		
	}
	/**
	 * updateMilestoneWithoutPhoto
	 *
	 * Update actualMilestones (without new photo id)
	 * @param $$milestoneid, $detail1, $detail2, $dateOccured, $emotion
	 */	
	function updateMilestoneWithoutPhoto($milestoneid, $detail1, $detail2, $dateOccured, $emotion)
	{
		$obj = new clsBabyJournal();
			$mysqli = $obj->db_connect();
			$newid = NULL;			
		 
			if(!($stmt = $mysqli->prepare("UPDATE actualMilestones SET detail_1 = ?, detail_2 = ?, dateOccured = ?, emotion = ? WHERE id = ?"))){
				echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
			}
			if(!($stmt->bind_param("ssssi", $detail1,$detail2,$dateOccured, $emotion,$milestoneid))){
				echo "Bind failed: "  . $stmt->errno . " " . $stmt->error;
			}
			if(!$stmt->execute()){			
				echo "Execute failed: "  . $stmt->errno . " " . $stmt->error;
			} 
			$stmt->close();
		
	}
	/**
	 * uploadPhoto
	 *
	 * Upload new photo to server and add row to photo table
	 * @param $babyid, $img
	 * @return new photoid
	 */	
	function uploadPhoto($babyid, $img)
	{		
		$obj = new clsBabyJournal();
		$mysqli = $obj->db_connect();
		$random_digit=rand(0000,9999);
		$target_dir = "photos/";
		$target_file = $target_dir .$random_digit. basename($img["name"]);
		$photoid=NULL;
		$uploadOk = 1;
		$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
	
		// Check if image file is a actual image or fake image		
		$check = getimagesize($img["tmp_name"]);
		if($check === false) {
			echo "File is not an image.<br/>";
			$uploadOk = 0;
		} 
		// Check if file already exists
		if (file_exists($target_file)) {
			echo "Sorry, file already exists.<br/>";
			$uploadOk = 0;
		}
		// Check file size
		if ($img["size"] > 500000) {
			echo "Sorry, your file is too large.<br/>";
			$uploadOk = 0;
		}
		// Allow certain file formats
		if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif" ) {
			echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.<br/>";
			$uploadOk = 0;
		}
		// Check if $uploadOk is set to 0 by an error
		if ($uploadOk == 0) {
			echo "Sorry, your file was not uploaded.<br/>";
			return $photoid;
		} 
		else
		{			
			if (move_uploaded_file($img["tmp_name"], $target_file)) {
				if(!($stmt = $mysqli->prepare("INSERT INTO photos(baby_id, pathLocation) VALUES (?,?)"))){
					echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
				}
				if(!($stmt->bind_param("is", $babyid, $target_file))){
					echo "Bind failed: "  . $stmt->errno . " " . $stmt->error;
				}
				if(!$stmt->execute()){
					echo "Execute failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
				}
				$photoid = $stmt->insert_id;	
				$stmt->close();							
			}		
			return $photoid;	
		}		
	}
	
/*	function recentJournals()
	{
		$obj = new clsBabyJournal();
		$mysqli = $obj->db_connect();								
		if(!($stmt = $mysqli->prepare("SELECT j.id, j.title FROM journal j LEFT JOIN photos p ON j.photo_id=p.id ORDER BY j.dateOfEntry DESC"))){
			echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
		}		
		if(!$stmt->execute()){
			echo "Execute failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
		}
		if(!$stmt->bind_result($id, $title, $date, $entry,$photopath)){
			echo "Bind failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
		}		
		while($stmt->fetch()){
			echo "<br/><p align='right'><strong><a href='updatejournal.php?id=".$id."'>Update</a> | <a href='deletejournal.php?id=".$id."'>Delete</a></strong></p>";
			echo "<h3>".$title."</h3>";
			echo "<p>Date: ".$date."</p>";
			if($photopath!=NULL)
			{
				echo "<p><img src='".$photopath."' alt='' style='width:304px;height:228px'></p><br/>";		
			}
			echo "<p>".$entry."</p>";
			echo "<br/><HR width='650px'><br/>";
			$obj->relatedMilestoneLinks($id);
			echo "<br/><HR width='650px'><br/>";
		}					
		$stmt->close();		
	}	*/
	/**
	 * relatedJournalLinks
	 *
	 * Get and print (links) of all Journals related to a specific Milestone
	 * @param $milestoneid
	 */	
	function relatedJournalLinks($milestoneid)
	{
		$obj = new clsBabyJournal();
		$mysqli = $obj->db_connect();	
		if(!($stmt = $mysqli->prepare("SELECT j.id, j.title FROM journalMilestones jm INNER JOIN journal j ON jm.journal_id = j.id WHERE jm.milestone_id = ?"))){
			echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
		}
			
	if(!($stmt->bind_param("i",$milestoneid))){
				echo "Bind failed: "  . $stmt->errno . " " . $stmt->error;
		}	
			if(!$stmt->execute()){
			echo "Execute failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
		}
		if(!$stmt->bind_result($id, $name)){
			echo "Bind failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
		}		
		echo "<p>Related Journals: ";
		while($stmt->fetch()){					
			echo " <a href='journals.php?id=".$id."'>".$name."</a> |";								
		}
		echo "</p>";			
		$stmt->close();			
	}
	/**
	 * relatedMilestoneLinks
	 *
	 * Get and print (links) of all milestones related to a specific journalid
	 * @param $journalid
	 */	
	function relatedMilestoneLinks($journalid)
	{
		$obj = new clsBabyJournal();
		$mysqli = $obj->db_connect();	
		if(!($stmt = $mysqli->prepare("SELECT am.id, am.milestone_name FROM journalMilestones jm INNER JOIN actualMilestones am ON jm.milestone_id = am.id WHERE jm.journal_id = ?"))){
			echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
		}
			
	if(!($stmt->bind_param("i",$journalid))){
				echo "Bind failed: "  . $stmt->errno . " " . $stmt->error;
		}	
			if(!$stmt->execute()){
			echo "Execute failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
		}
		if(!$stmt->bind_result($id, $name)){
			echo "Bind failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
		}		
		echo "<p>Related Milestones: ";
		while($stmt->fetch()){					
			echo " <a href='milestones.php?id=".$id."'>".$name."</a> |";								
		}
		echo "</p>";			
		$stmt->close();				
	}
	/**
	 * submitJournal
	 *
	 * Insert new journal
	 * @param $babyid, $photoid, $title, $date, $entry
	 * @return new journalid
	 */	
	function submitJournal($babyid, $photoid, $title, $date, $entry)
	{
			$obj = new clsBabyJournal();
			$mysqli = $obj->db_connect();
			$newid = NULL;
			if(!($stmt = $mysqli->prepare("INSERT INTO journal(baby_id, photo_id, title, dateOfEntry, entry) VALUES (?,?,?,?,?)"))){
				echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
			}
			if(!($stmt->bind_param("iisss",$babyid, $photoid,$title,$date,$entry))){
				echo "Bind failed: "  . $stmt->errno . " " . $stmt->error;
			}
			if(!$stmt->execute()){			
				echo "Execute failed: "  . $stmt->errno . " " . $stmt->error;
			} 
			else{
				$newid = $stmt->insert_id;				
			}
			$stmt->close();
				return $newid;
	}
	/**
	 * submitMilestone
	 *
	 * Insert new actualMilestone
	 * @param $babyid, $photoid,$milestone_id,$date,$emotion,$detail1,$detail2
	 * @return new milestoneid
	 */	
	function submitMilestone($babyid, $photoid,$milestone_id,$date,$emotion,$detail1,$detail2)
	{
		$obj = new clsBabyJournal();
		$mysqli = $obj->db_connect();
		$newid = NULL; 
		if(!($stmt = $mysqli->prepare("INSERT INTO actualMilestones (baby_id, photo_id, milestone_name, dateOccured, emotion, detail_1, detail_2) VALUES (?,?,(SELECT name FROM possibleMilestones WHERE id = ?),?,?,?,?)"))){
			echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
		}
		if(!($stmt->bind_param("iiissss",$babyid, $photoid,$milestone_id,$date,$emotion, $detail1, $detail2))){
			echo "Bind failed: "  . $stmt->errno . " " . $stmt->error;
		}		
		if(!$stmt->execute()){			
			echo "Execute failed: "  . $stmt->errno . " " . $stmt->error;
		} 
		else{
			$newid = $stmt->insert_id;				
		}
		$stmt->close();
			return $newid;		
	}	
}

?>
