<?php

$tempdir=sys_get_temp_dir().'\bmestemp';
if (!file_exists($tempdir)){mkdir($tempdir);}
$filepath=$tempdir."\DMdata.db";//print_r($filepath);
if (!file_exists($filepath)){echo copy("datatemplate/DMdata.db",$filepath);}   

try{	
	$db = new PDO("sqlite:$filepath");
	//print_r($db);
}catch(PDOException $e){
	//echo $e ->getMessage();
	

	
	
}





?>
	