<!-- Ifeanyi Osuchukwu is responsible for MainMenu.php -->

<html>
<body>
<head>
<meta name="authors" content="Jintong Hou, Ifeanyi Osuchukwu, Samuel Yoon"> 
<title>Database Update </title>
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
	$u_var = array($u_fname,$u_lname,$u_npreg,$u_glucose,$u_BMI,$u_ped,$u_age,$u_Diabetes,$u_SurveyYear);
	
		
	if(isset($_POST['update']) ) // when click on Update button
	{
		$fname = $_POST['fname'];
		$lname = $_POST['lname'];
		$npreg = $_POST['npreg'];
		$glucose = $_POST['glucose'];
		$bmi = $_POST['bmi'];
		$ped = $_POST['ped'];
		$age = $_POST['age'];
		$diabetes = $_POST['diabetes'];
		$syear = $_POST['syear'];
		$n_new = array($fname,$lname,$npreg,$glucose,$bmi,$ped,$age,$diabetes,$syear);	
	}
	if (isset($_POST['update']) && $u_var != $n_new) {
		
	
	$update = $db->exec("UPDATE Dmdata SET FirstName='$fname',LastName ='$lname',npreg='$npreg',
		glucose = '$glucose', BMI='$bmi', ped = '$ped', age='$age', Diabetes = '$diabetes',
		SurveyYear = '$syear' WHERE id= '$id'");
		
		if($update)
		{
			header("refresh:3; url=search.php"); // redirects to all records page
			echo "Success this record was updated! You're being redirected to the search page.";
			
		}
			
			
		else
		{
			echo "Something is wrong";
		}    	

	}
	?>

	<h3>Update Data</h3>
	<table>
	<tr><td>First Name </td><td>Last Name </td><td>Number of Pregnancies</td><td>Glucose</td><td> BMI </td><td>Ped</td>
		<td> Age </td><td> Diabetes </td><td> Survery Year </td><td></td></tr>
	<form method="POST">
	  <tr><td><input type="text" name="fname" value="<?php echo (isset($fname)) ? $fname : $u_fname; ?>" size="10"></td>
	  <td><input type="text" name="lname" value="<?php echo (isset($lname)) ? $lname : $u_lname; ?> "size="10" Name" ></td>
	  <td><input type="text" name="npreg" value="<?php echo (isset($npreg)) ? $npreg : $u_npreg; ?>"  Required></td>
	  <td><input type="text" name="glucose" value="<?php echo (isset($glucose)) ? $glucose : $u_glucose; ?>" size="4" Required></td>
	  <td><input type="text" name="bmi" value="<?php echo (isset($bmi)) ? $bmi : $u_BMI; ?>" size="4" Required></td>
	  <td><input type="text" name="ped" value="<?php echo (isset($ped)) ? $ped : $u_ped; ?>" size="4" Required></td>
	  <td> <input type="text" name="age" value="<?php echo (isset($age)) ? $age : $u_age; ?>" size="4" Required></td>
	  <td><input type="text" name="diabetes" value="<?php echo (isset($diabetes)) ? $diabetes: $u_Diabetes; ?>"size="4" Required></td>
	  <td><input type="text" name="syear" value="<?php echo (isset($syear)) ? $syear : $u_SurveyYear; ?>"size="4" Required></td></tr>
	  <tr><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td><input type="submit" name="update" value="Update"></td></tr>
	</form>
	</table>
	</body>
</html>