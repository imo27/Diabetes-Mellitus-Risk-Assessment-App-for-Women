<!-- Ifeanyi Osuchukwu is responsible for search.php -->

<html>
	<body>
	<head>
	<meta name="authors" content="Jintong Hou, Ifeanyi Osuchukwu, Samuel Yoon"> 
	<title>Database Deletion  </title>
	
	
		<style>
	html,body {
	  height:100%;
	  width:100%;
	  margin:0;
	}
	body {
	  font-family: "Open Sans", "Arial";
	  background:#E3F9F9;
	}


	</style>
	</head>
	<a href="index.html">Main Menu</a> > 
	<a href="search.php">Search Database</a>

	<?php

	include "dbtestconn.php"; 
	error_reporting(0);
	$id = $_GET['id']; // get id through query string

	$query = $db->query("SELECT * FROM DMdata WHERE id = '$id'");// select query
	$resquery = $db->query("SELECT * FROM DMdata WHERE id = '$id'");

	$search = $resquery->fetchAll(); // fetch data
	$u_fname = $search[0]['FirstName'];
	$u_lname = $search[0]['LastName'];
	$u_npreg = $search[0]['npreg'];
	$u_glucose = $search[0]['glucose'];
	$u_BMI = $search[0]['BMI'];
	$u_ped = $search[0]['ped'];
	$u_age = $search[0]['age'];
	$u_Diabetes = $search[0]['Diabetes'];
	$u_SurveyYear = $search[0]['SurveyYear'];
	


	if(isset($_POST['delete']) ){ // when click on Update button
	
		$fname = $_POST['fname'];
		$lname = $_POST['lname'];
		$npreg = $_POST['npreg'];
		$glucose = $_POST['glucose'];
		$bmi = $_POST['bmi'];
		$ped = $_POST['ped'];
		$age = $_POST['age'];
		$diabetes = $_POST['diabetes'];
		$syear = $_POST['syear'];
		
	
		$delete = $db->exec("Delete FROM DMdata WHERE id= '$id'");
		if($delete)
		{
			header("refresh:3; url=search.php"); // redirects to all records page
			echo "Success this record was deleted! You're being redirected the search page.";
			
		}
			
			
			
		else
		{
			echo "Something is Wrong!";
		}   	

	}
	?>

	<h3>Delete Data</h3>

	<table>
	<tr><td>First Name </td><td>Last Name </td><td>Number of Pregnancies</td><td> Glucose </td><td> BMI </td><td> Ped </td>
		<td> Age </td><td> Diabetes </td><td> Survery Year </td><td></td></tr>
	<form method="POST">
	  <tr><td><input type="text" name="fname" value="<?php echo $u_fname?>" size="10" readonly Required></td>
	  <td><input type="text" name="lname" value="<?php echo $u_lname ?>" size="10" readonly Required></td>
	  <td><input type="text" name="npreg" value="<?php echo $u_npreg?>" size="20" readonly Required></td>
	  <td><input type="text" name="glucose" value="<?php echo $u_glucose ?>"  size="4"readonly Required></td>
	  <td><input type="text" name="bmi" value="<?php echo $u_BMI?>"  size="4" readonly Required></td>
	  <td><input type="text" name="ped" value="<?php echo $u_ped ?>"  size="4" readonly Required></td>
	  <td> <input type="text" name="age" value="<?php echo $u_age ?>"  size="4" readonly Required></td>
	  <td><input type="text" name="diabetes" value="<?php echo $u_Diabetes?>"  size="4" readonly Required></td>
	  <td><input type="text" name="syear" value="<?php echo $u_SurveyYear ?>"  size="4"readonly Required></td></tr>
	  <tr><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td><input type="submit" name="delete" value="Delete"></td></tr>
	</form>
	</table>
	</body>
</html>