<?php
	session_start();
	// a registered user
	if (isset($_SESSION['userid']))
	{
		$logged=1;
		//registered user with no baby record
		if(isset($_SESSION['babyid']))
		{
			$logged=2;		
		}
	} 
	else{
		//not logged in
		$logged = 0;
	}
	
	?>
<?php
	switch($logged)
	{
		case(0): 		
		include("headerOut.php");
		if($_GET['error']==="login") echo "Error logging in. Please check username and password.<br/>";
		?>
        <img src="images/title3.gif" alt="" width="159" height="15" /><br />
				<div class="info">
					<img src="images/pic2.jpg" alt="" width="159" height="132" /><br/>
					<p>BABY BOOK is fictional online baby journal application.  This website was built for CS290 at Oregon State University.  The
                    concept behind this website is to enable parents to easily maintain a baby book for their newborn throughout the course of their first year.  Since
                    the first year can be quite exhausting for parents, this application endeavours parents to easily document baby's milestones by leveraging pre-defined templates of many of baby's firsts, including first time trying solid foods, first hair cut, and first steps.  The user can also include journal entries and upload accompanying photographs.
                    </p>
							
				</div>
				<img src="images/title4.gif" alt="" width="159" height="17" /><br />
				<div id="items">
					<div class="item">
						<a href="#"><img src="images/item1.jpg" width="213" height="192" /></a><br />
						
					</div>
					<div class="item center">
						<a href="#"><img src="images/item2.jpg" width="213" height="192" /></a><br />
						
					</div>
					<div class="item">
						<a href="#"><img src="images/item3.jpg" width="213" height="192" /></a><br />
						
					</div>
					<div class="item">
						<a href="#"><img src="images/item4.jpg" width="213" height="192" /></a><br />
						
					</div>
					<div class="item center">
						<a href="#"><img src="images/item5.jpg" width="213" height="192" /></a><br />
						
					</div>
					<div class="item">
						<a href="#"><img src="images/item6.jpg" width="213" height="192" /></a><br />
						
					</div>
				</div>
			</div>
		</div>
        <?php
		
		break;
		case(1): 
		header('Location: newbaby.php');
		break;	
		case(2): 
		header('Location: aboutbaby.php');
		break;	
	} // end switch
	
	include("footer.php");
?>

