<!-- Ifeanyi Osuchukwu is responsible for search.php -->

<html>
<body>
<head>
<meta name="authors" content="Jintong Hou, Ifeanyi Osuchukwu, Samuel Yoon"> 
<title>Database Search  </title>
<style>
html,body {
  height:100%;
  width:100%;
  margin:0;
}
body {
  display:flex;
  font-family: "Open Sans", "Arial";
  background:#E3F9F9;
}
table,div,h1{
  margin:auto;/* nice thing of auto margin if display:flex; it center both horizontal and vertical :) */

  
}
table  {
	border-collapse: collapse
	width:600px
	
}

</style>


</head>
<a href="index.html">Main Menu</a> > 
<a href="search.php">Search Database</a>
<div><h1>Database Search</h1></d>
<?php
include "dbtestconn.php";
#error_reporting(0);
$conditions=[];
$params=[];
$keys = [];

if(isset($_POST['search'])AND empty($_POST['ID']) AND empty($_POST['Fname'] ) AND empty($_POST['Lname']) AND empty($_POST['Syear'])){//Forces user to input data to be searched
	echo "<div class =below><p style=font-size:125%;color:red;>Please enter data for atleast one field!</p></d>";
}
if(isset($_POST['search']) AND !empty($_POST['ID'])){
	$id = $_POST['ID'];
	$conditions['id']=$id;
	$p_id = "id="."'".$conditions['id']."'";
	array_push($params,$p_id);
	
}if(isset($_POST['search']) AND !empty($_POST['Fname'])){//lines 53-69 test to see which $_POST variables are not empty if they are not empty they will be added to search query.
	$fname = $_POST['Fname'];
	$conditions['Fname']=$fname;
	$p_fname = "FirstName="."'".$conditions['Fname']."'";
	array_push($params,$p_fname);
	
}if(isset($_POST['search'] )AND !empty($_POST['Lname'])){
	$lname = $_POST['Lname'];
	$conditions['Lname']=$lname;
	$p_lname = "LastName="."'".$conditions['Lname']."'";
	array_push($params,$p_lname);
	
}if(isset($_POST['search'] )AND !empty($_POST['Syear'])){
	$syear = $_POST['Syear'];
	$conditions['Syear']=$syear;
	$p_syear = "SurveyYear="."'".$conditions['Syear']."'";
	array_push($params,$p_syear);

}
	
if(isset($_POST['search']) && !empty($conditions)){ 
	$where = "WHERE ".implode(' AND ',$params);
	$query ='SELECT * FROM DMdata '.$where;
	$stmt = $db->prepare($query);
	$stmt->execute();
	$result=$stmt->fetchAll();
	$searchid = $result;
	if(empty($result)){//if the result query is empty a warning is provided.
		echo "<div class =below><p style=font-size:125%;color:red;>There is no record of patient! Please try again.</p></d>";
	}
	
	if(!empty($result)){
	
    foreach ($searchid as $key => $value){
		array_push($keys,$value[0]);
	}
		print "<div>";
		print "<p><table border=1>";
		print "<tr><td> id </td><td> FirstName </td><td> LastName </td><td> nPreg </td><td> glucose </td><td> BMI </td><td> ped </td>
	<td> age </td><td> Diabetes </td><td> SurveyYear </td><td> Update </td><td> Delete </td></tr>";
	$iterator = 0 ;
	foreach($result as $row){
		print "<tr><td>".$row['id']."</td>";
		print "<td>".$row['FirstName']."</td>";
		print "<td>".$row['LastName']."</td>";
		print "<td>".$row['npreg']."</td>";
		print "<td>".$row['glucose']."</td>";
		print "<td>".$row['BMI']."</td>";
		print "<td>".$row['ped']."</td>";
		print "<td>".$row['age']."</td>";
		print "<td>".$row['Diabetes']."</td>";
		print "<td>".$row['SurveyYear']."</td>";
		print "<td><a href='update.php?id=$keys[$iterator]'>Update</a></td>";
		print "<td><a href='delete.php?id=$keys[$iterator]'>Delete</a></td></tr>";
		$iterator = $iterator +1;
		
	}
	print "</table></p></d>";
		

}
	
}	
?>
<table id = "table1">
<form method = "POST" action = "<?php echo htmlentities($_SERVER['PHP_SELF']); ?>">
 <tr><td> <label for="Fname">First Name: </label></td>
  <td><input type="text" id="Fname" name="Fname"></td></tr>
  <tr><td><label for="Lname">Last Name: </label></td>
  <td><input type="text" id="Lname" name="Lname"></td></tr>
  <tr><td><label for="ID">ID:</label></td>
  <td><input type="text" id="ID" name="ID"></td></tr>
  <tr><td><label for="SurveyYear">Survey Year:</label></td>
  <td><input type="text" id="SurveyYear" name="Syear"></td></tr>
  <tr><td></td><td></td><td><input type = "submit" name="search" value= "Search"/></td></tr>
</form>
</table>



</body>

</html>
