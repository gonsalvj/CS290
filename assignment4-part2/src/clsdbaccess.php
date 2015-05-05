<?php

//functions to access and interact with database
class clsdbaccess
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
 * add video
 *
 * Inserts new record to the video inventory table
 *
 * @param name, category, length
 * @return id (primary key) of newly created record
 */
	function addvideo($name, $category, $length)
	{
		$obj = new clsdbaccess();
		$mysqli = $obj->db_connect();
		$result ='';

		if(!($stmt = $mysqli->prepare("INSERT INTO videoinventory (name, category, length) VALUES (?,?,?)"))){
			echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
		}
		if(!($stmt->bind_param("ssi", $name, $category, $length))){
			echo "Bind failed: "  . $stmt->errno . " " . $stmt->error;
		}
		if(!$stmt->execute()) {
			$result = "Execute failed: " . $mysqli->error;
			
		}
		else {
			$result='success';
		}		
		$stmt->close();	
		return $result;
	}

	/**
	 * deletevideo
	 *
	 * Delete video
	 * @param id
	 * @return no return
	 */
	function deletevideo($id)
	{
		$obj = new clsdbaccess();
		$mysqli = $obj->db_connect();			
		if(!($stmt = $mysqli->prepare("DELETE FROM videoinventory WHERE id = ?"))){
			echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
		}		
		if(!($stmt->bind_param("i",$id))){
				echo "Bind failed: "  . $stmt->errno . " " . $stmt->error;
			}
		if(!$stmt->execute()){
			echo "Execute failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
		}				
		$stmt->fetch();				
	}

	/**
	 * deleteallvideos
	 *
	 * delete all videos
	 * @param none
	 * @return no return
	 */
	function deleteallvideos()
	{
		$obj = new clsdbaccess();
		$mysqli = $obj->db_connect();			
		if(!($stmt = $mysqli->prepare("DELETE FROM videoinventory"))){
			echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
		}		
		if(!$stmt->execute()){
			echo "Execute failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
		}				
		$stmt->fetch();		
	}


	/**
	 * filtervideos
	 *
	 * Gets all and print (link/images) of all photos related to a specified babyid
	 * @param babyid	 
	 */	
	function getfilteredvidoes($cat) {
		$obj = new clsdbaccess();
		$mysqli = $obj->db_connect();								
		if(!($stmt = $mysqli->prepare("SELECT v.id, v.name, v.category, v.length, v.rented FROM videoinventory v WHERE v.category = ?"))){
			echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
		}	
		if(!($stmt->bind_param("s",$cat))){
				echo "Bind failed: "  . $stmt->errno . " " . $stmt->error;
		}
		if(!$stmt->execute()){
			echo "Execute failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
		}
		if(!$stmt->bind_result($id, $name, $category, $length, $rented)){
			echo "Bind failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
		}		
		
		echo '<table border="1"><thead>';
		echo '<th>Name</th>';
		echo '<th>Category</th>';
		echo '<th>Length</th>';
		echo '<th>Rental Status</th>';
		echo '<th>Delete?</th>';
		echo '<th>Checkin/Checkout</th>';
		echo '</thead>';		
		echo '<tbody>';

		while($stmt->fetch()) {

			$status = $rented;
			if($rented === 1) {
				$rented = 'available';
			} else {
				$rented = 'checked out';
			}

			echo "<tr>";
			echo "<td>".$name."</td>";
			echo "<td>".$category."</td>";
			echo "<td>".$length."</td>";
			echo "<td>".$rented."</td>";
			echo "<td><form method='post' action='deletevideo.php'>";
			echo "<input type='hidden' name='id' value='".$id."'/>";
			echo "<input type='submit' name ='btndeletevideo' value='Delete'/>";
			echo "</form>";
			echo "<td><form method='post' action='updatestatus.php'>";
			echo "<input type='hidden' name='id' value='".$id."'>";
			echo "<input type='hidden' name='status' value='".$status."'>";
			echo "<input type='submit' name='btnupdatestatus' value='Checkin/Checkout'/>";
		 	echo "</form>";			
			echo "</tr>";
		}
		echo '</tbody></table>';
		echo '<br>';	
		echo "<form method='post' action='deleteallvideos.php'>";
		echo "<input type='submit' name='btndeleteallvideos' value='Delete All Videos'/>";
		echo "</form>";	
	
		$stmt->close();				
	}

	/**
	 * getallcateogories
	 *
	 * Gets all and print (link/images) of all photos related to a specified babyid
	 * @param babyid	 
	 */	
	function getallcateogories($cat) {
		$obj = new clsdbaccess();
		$mysqli = $obj->db_connect();								
		if(!($stmt = $mysqli->prepare("SELECT DISTINCT v.category FROM videoinventory v"))){
			echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
		}	
		if(!$stmt->execute()){
			echo "Execute failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
		}
		if(!$stmt->bind_result($category)){
			echo "Bind failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
		}		
		echo "<form action='videoinventory.php' method='get'>";
		echo '<select name="categories">';
		while($stmt->fetch()) {
			if ($cat == $category) {
				echo "<option value='".$category."' selected='selected'>".$category."</option>";
			} else {
				echo "<option value='".$category."'>".$category."</option>";
			}
		}
		if (is_null($cat)) {
			echo "<option value='ALL' selected='selected'>SHOW ALL</option>";
		} else {
			echo "<option value='ALL'>SHOW ALL</option>";
		}
		echo '</select>';
		echo '&nbsp;&nbsp;<input type = "submit" name = "btnfilter" value="Filter">';
		echo '</form><br>';	
		$stmt->close();				
	}


	/**
	 * getallvidos
	 *
	 * Gets all and print (link/images) of all photos related to a specified babyid
	 * @param babyid	 
	 */	
	function getallvideos() {
		$obj = new clsdbaccess();
		$mysqli = $obj->db_connect();								
		if(!($stmt = $mysqli->prepare("SELECT v.id, v.name, v.category, v.length, v.rented FROM videoinventory v"))){
			echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
		}	
		if(!$stmt->execute()){
			echo "Execute failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
		}
		if(!$stmt->bind_result($id, $name, $category, $length, $rented)){
			echo "Bind failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
		}		
		
		echo '<table border="1"><thead>';
		echo '<th>Name</th>';
		echo '<th>Category</th>';
		echo '<th>Length</th>';
		echo '<th>Rental Status</th>';
		echo '<th>Delete?</th>';
		echo '<th>Checkin/Checkout</th>';
		echo '</thead>';		
		echo '<tbody>';

		while($stmt->fetch()) {

			$status = $rented;
			if($rented === 1) {
				$rented = 'available';
			} else {
				$rented = 'checked out';
			}

			echo "<tr>";
			echo "<td>".$name."</td>";
			echo "<td>".$category."</td>";
			echo "<td>".$length."</td>";
			echo "<td>".$rented."</td>";
			echo "<td><form method='post' action='deletevideo.php'>";
			echo "<input type='hidden' name='id' value='".$id."'/>";
			echo "<input type='submit' name ='btndeletevideo' value='Delete'/>";
			echo "</form>";
			echo "<td><form method='post' action='updatestatus.php'>";
			echo "<input type='hidden' name='id' value='".$id."'>";
			echo "<input type='hidden' name='status' value='".$status."'>";
			echo "<input type='submit' name='btnupdatestatus' value='Checkin/Checkout'/>";
		 	echo "</form>";			
			echo "</tr>";
		}
		echo '</tbody></table>';
		echo '<br>';	
		echo "<form method='post' action='deleteallvideos.php'>";
		echo "<input type='submit' name='btndeleteallvideos' value='Delete All Videos'/>";
		echo "</form>";	
	
		$stmt->close();				
	}

	/**
 * updaterentedstatus
 *
 * Updates a baby record with photo_id
 *
 * @param babyid, photoid
 * @return no return
 */	
	function updaterentedstatus($id, $status)
	{
		$obj = new clsdbaccess();
			$mysqli = $obj->db_connect();
			$newid = NULL;			
		 
			if(!($stmt = $mysqli->prepare("UPDATE videoinventory SET rented=? WHERE id = ?"))){
				echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
			}
			if(!($stmt->bind_param("ii", $status,$id))){
				echo "Bind failed: "  . $stmt->errno . " " . $stmt->error;
			}
			if(!$stmt->execute()){			
				echo "Execute failed: "  . $stmt->errno . " " . $stmt->error;
			} 
			$stmt->close();				
	}
}

?>