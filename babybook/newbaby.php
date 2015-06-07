<?php

	session_start();
	if (!isset($_SESSION['userid'])) {
			header('Location: index.php');
	}
	else if(isset($_SESSION['babyid']))
	{
		header('Location: aboutbaby.php');
	}
	else
	{
		include("headerIn.php");		
	}
	

?>

<form method="post" action="newbaby.php" enctype="multipart/form-data">

<p>Date of Birth <input name="dob" type="date" size="20" /></p> <br/>
<p>First Name: <input type="text" name="fname" /></p> <br/>
<p>Last Name: <input type="text" name="lname" /></p> <br/>
<p>Eye Colour: <select name="eyecolour">
<option value="blue">Blue</option>
<option value="green">Green</option>
<option value="brown">Brown</option>
<option value="hazel">Hazel</option>
<option value="grey">Grey</option>
</select></p> <br/>
<p>
Hair Colour: <select name="haircolour">
<option value="black">Black</option>
<option value="brown">Brown</option>
<option value="blonde">Blonde</option>
<option value="red">Red</option>
<option value="no">Bald</option>
</select> </p> <br/>

<p>Height at Birth: <select name="height">
<option value="25">25</option>
<option value="26">26</option>
<option value="27">27</option>
<option value="28">28</option>
<option value="29">29</option>
<option value="30">30</option>
<option value="31">31</option>
<option value="32">32</option>
<option value="33">33</option>
<option value="34">34</option>
<option value="35">35</option>
<option value="36">36</option>
<option value="37">37</option>
<option value="38">38</option>
<option value="39">39</option>
<option value="40">40</option>
<option value="41">41</option>
<option value="42">42</option>
<option value="43">43</option>
<option value="44">44</option>
<option value="45">45</option>
<option value="46">46</option>
<option value="47">47</option>
<option value="48">48</option>
<option value="49">49</option>
<option value="50">50</option>
<option value="51">51</option>
<option value="52">52</option>
<option value="53">53</option>
<option value="54">54</option>
</select> cm </p> <br/> <p>Weight (in grams): <input type="text" name="weight" /></p> <br/>

<p>Baby Photo:  <input type="file" id="fileupload" name="fileupload"></p> <br/>
<p> <input id="submit" type="submit" name="submit" value="Submit"></p> <br/>
  <br/>
</form> 



<?php
if(isset($_POST['submit']))
{
	//add baby with photo
		if(!empty($_FILES['fileupload']['name']))
		{
			$babyid = $obj->addBaby($_SESSION['userid'], $_POST['fname'], $_POST['lname'], $_POST['dob'], $_POST['haircolour'], $_POST['eyecolour'], $_POST['height'], $_POST['weight']);
			$photoid=$obj->uploadPhoto($_SESSION['babyid'],$_FILES['fileupload']);
			$obj->updateBabyPhoto($babyid, $photoid);
		}
		//add baby with no photo
		else
		{
			$babyid = $obj->addBaby($_SESSION['userid'], $_POST['fname'], $_POST['lname'], $_POST['dob'], $_POST['haircolour'], $_POST['eyecolour'], $_POST['height'], $_POST['weight']);
			
		}
			echo "Thank you for filling out your baby's profile.  You are ready to get started!.";	
				
		
}
include("footer.php");
?>
