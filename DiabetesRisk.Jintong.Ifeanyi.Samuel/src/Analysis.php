<!-- Jintong Hou is responsible for Analysis.php -->
<html>
<head>
<meta name="authors" content="Jintong Hou, Ifeanyi Osuchukwu, Samuel Yoon"> 
<style>
.split {
  height: 100%;
  width: 50%;
  position: fixed;
  z-index: 1;
  top: 0;
  overflow-x: hidden;
  padding-top: 20px;
}
.left {
  left: 0;
}

.right {
  right: 0;
}
.centered {
  position: absolute;
  left: 5%;
  right: 50%
  top: 1%;
  text-align: left;
}

table {
  border-collapse: collapse;
}
.borders{
	border-collapse: collapse;
	border:1px solid black;
	width:100px;
}
.twoc {width:250px}
.mytable {border:1px solid black}
.fivec {width:80px}
.fourc {width:125px}
</style>
</head>
<body>


<div class="split left">
<div class="centered">
<a href="index.html"> Main Menu</a> >> 
<a href="Analysis.php">Analysis</a> <br><br>
<h1>Calculate risk of developing diabetes</h1><br>
<form method="post" enctype="multipart/form-data">
Number of pregnancies: <br>
<input type="text" name="preg"> <input type="submit" name="submitpreg" value="Show summary statistics "><br><br>
Glucose(mg/dL):<br>  
<input type="text" name="glucose"> <input type="submit" name="submitglu" value="Show summary statistics "><br><br>
BMI (kg/m<sup>2</sup>):<br> 
<input type="text" name="bmi"> <input type="submit" name="submitbmi" value="Show summary statistics "><br><br>
Diabetes pedigree function value:<br>
<input type="text" name="ped"> <input type="submit" name="submitped" value="Show summary statistics "><br><br>
Age: <br>
<input type="text" name="age"> <input type="submit" name="submitage" value="Show summary statistics "><br><br>
Select sample by Survey Year(All years by default. e.g. 2001):
<br><input type="text" name="year1"> - <input type="text" name="year2">
<input type="submit" name="submityear" value="Show summary statistics "><br><br>
<br><br>
<input type="submit" name="calculate" value="calculate">&nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp

</form>
</div>
</div>

<div class="split right">
<div class="centered">
<?php
echo "<h2>Outputs</h2>";
$PYEXE='python';
if(strtoupper(substr(PHP_OS, 0, 3)) === 'WIN'){
	if(file_exists($try='C:\ProgramData\Anaconda3\python.exe')) $PYEXE=$try;// professor's path
	elseif(file_exists($try='C:\Users\TIM\AppData\Local\Programs\Python\Python39\python.exe')) $PYEXE=$try;// Jintong's path
	elseif(file_exists($try='C:\Program Files\Python39\python.exe')) $PYEXE=$try; // Sam's path

	elseif($PYEXE=='python'){ die ("Add your python.exe path to Analysis.php file for PYEXE variable");}

}
else{
		
}
include "dbtestconn.php";//use this file instead of below code afterwards
//get temporary folder absolute path
//$tempdir=sys_get_temp_dir().'\bmestemp';
//if (!file_exists($tempdir)){mkdir($tempdir);}
//$filepath=$tempdir."\DMdata.db";
//if (!file_exists($filepath)){echo copy("datatemplate/DMdata.db",$filepath);};

function myFilter($var){return ($var !== NULL && $var !== FALSE && $var !== '');}//print_r($outs);


if (isset($_REQUEST['submitpreg'])){$sumvar=" npreg ";$label="Number of pregnancies";}
if (isset($_REQUEST['submitglu'])){$sumvar=" glucose ";$label="Glucose";}
if (isset($_REQUEST['submitbmi'])){$sumvar=" bmi ";$label="BMI";}
if (isset($_REQUEST['submitped'])){$sumvar=" ped ";$label="Diabetes pedigree function value";}
if (isset($_REQUEST['submitage'])){$sumvar=" age ";$label="Age";}
if (isset($_REQUEST['submityear'])){$sumvar=" SurveyYear ";$label="Survey Year";}

//get summary statistics	
if (isset($sumvar) ){
	$year1in=preg_replace("/[^0-9.]/", "", escapeshellarg($_REQUEST['year1']));//remove all invalid characters
$cperiods=substr_count($year1in,".");if ($cperiods>0 | $year1in=="."){$year1in="";}

$year2in=preg_replace("/[^0-9.]/", "", escapeshellarg($_REQUEST['year2']));//remove all invalid characters
$cperiods=substr_count($year2in,".");if ($cperiods>0 | $year2in=="."){$year2in="";}

if ($year2in !="" && ($year1in > $year2in)){die("Invalid year input. Example: 2000 2001");}

	$cmd="\"$PYEXE\"  Summary_Statistic.py ".escapeshellarg($tempdir).$sumvar.escapeshellarg($year1in)." ".escapeshellarg($year2in)."2>&1";
	exec($cmd,$outs); //print_r($outs);
	if ($outs[0]=="importissue"){die("Python is unable to import all required modules, check README.md for required modules.");}
    elseif ($outs[0]=="dbissue"){die("Python is unable to connect to database, calculation is not performed.");}
	elseif ($outs[0]=="inputissue"){die("Python is unable to load user input, calculation is not performed.");}
    elseif ($outs[0]=="calissue"){die("Python failed to perform analysis.");}

	
	echo "<h2 align='center'>Summary statistics for $label</h2>";
    if ($sumvar !=" SurveyYear "){
        $mean=round($outs[0],3);//print_r($outs);
        $std=round($outs[1],3);$median=round($outs[2],3);$min=round($outs[3],3);$max=round($outs[4],3);$nobs=$outs[5];
//////////////////////////table for summary statistics mean median//////////////////////////////////////////
echo '<table align="center" class="mytable">';
echo "<tr><th align='center' class='borders'>Mean</th><th align='center' class='borders'>Standard Deviation</th>
 <th align='center' class='borders'>Median</th><th align='center' class='borders'>Minimum</th>
 <th align='center' class='borders'>Maximum</th><tr>";
 
echo "<tr><td align='center' class='borders'>$mean</td><td align='center' class='borders'>$std</td>
 <td align='center' class='borders'>$median</td><td align='center' class='borders'>$min</td>
 <td align='center' class='borders'>$max</td><tr>";
 
echo "</tbody></table><br>";
	
$histdir=end($outs);//histogram
if(file_exists($histdir)){
$histimg=base64_encode(file_get_contents($histdir));//want
echo "<img class='center' width=500 src='data:image/png;base64,$histimg'>";//want
}

	if ($year1in=="" && $year2in==""){echo "<br><br>Sample year range: All years <br><br>";}
	if ($year1in=="" && $year2in!=""){echo "<br><br>Sample year range: No later than year $year2in <br><br>";}
	if ($year1in!="" && $year2in==""){echo "<br><br>Sample year range: No earlier than year $year1in <br><br>";}
	else if ($year1in!="" && ($year1in <= $year2in)){echo "<br><br>Sample year range: $year1in - $year2in <br><br>";}
	echo "Number of observations used: $nobs <br><br>";
	echo "*By default, negative values are disgarded, and zeros are disgarded for Glucose, BMI,Diabetes pedigree function value, and age.
	<br><br>";
	
unlink($histdir);
unset($sumvar);
}//if ($sumvar !=" SurveyYear ")
	
else if ($sumvar ==" SurveyYear "){
	/////////////////////////table for Survey year frequency table////////////////////////////////

//filter empty but not 0	

	$yearpieses=preg_replace("/[^0-9]/", " ", escapeshellarg($outs['0'])); ////
	$yearpieses=explode(" ",$yearpieses);//$cc=array_filter($yearpieses,'myFilter');print_r($cc);
$yearpieses=array_values(array_filter($yearpieses,'myFilter'));//print_r($yearpieses);
$counts=count($yearpieses);//how many unique values for survey year
	$freqpieses=preg_replace("/[^0-9]/", " ", escapeshellarg($outs['1']));
	$freqpieses=explode(" ",$freqpieses);
$freqpieses=array_values(array_filter($freqpieses,'myFilter'));  //
$nobs=$outs['2'];//print_r($outs);
	echo '<table align="center" class="mytable">';
	echo "<tr><th align='center' class='borders'>Year</th><th align='center' class='borders'>Number of Records</th>
	<th align='center' class='borders'>Percent</th><tr>";
	for ($i=0;$i<$counts;$i++){
		$percent=round($freqpieses[$i]/$nobs * 100,3);
		if ($yearpieses[$i]=='9999'){$yearpieses[$i]="Unknown";}
		//$check=($yearpieses[$i]=='9999');print_r($check);
		//print_r($i);print_r($yearpieses[$i]);
	echo "<tr><td align='center' class='borders'>$yearpieses[$i]</td><td align='center' class='borders'>$freqpieses[$i]</td>
	<td align='center' class='borders'>$percent %</td><tr>";
	}
	echo "<tr><td align='center' class='borders'>Total </td><td align='center' class='borders'>$nobs</td>
	<td align='center' class='borders'>100%</td><tr>";
	echo "</tbody></table><br><br>";  //print_r($outs);
//////////////////////////////////delete useless files//////////////////////
$histdir=end($outs);
if (file_exists($histdir)){unlink($histdir);};
unset($sumvar);
	}

	//just to hide outputs 
?>
<form method="POST">
<input type="submit" name="reset" value="Clear All">
</form>
<?php
}//if (isset($sumvar) )

if (isset($_REQUEST['calculate']) ){
///////////////////////////////////////////////sanitize user input////////////////////////////////////////////////
$pregin=preg_replace("/[^0-9.]/", "", escapeshellarg($_REQUEST['preg']));//remove all invalid characters
$cperiods=substr_count($pregin,".");if ($cperiods>0){$pregin="";} 

$glucosein=preg_replace("/[^0-9.]/", "", escapeshellarg($_REQUEST['glucose']));//remove all invalid characters
$cperiods=substr_count($glucosein,".");if ($cperiods>1|$glucosein=="."){$glucosein="";} 

$bmiin=preg_replace("/[^0-9.]/", "", escapeshellarg($_REQUEST['bmi']));//remove all invalid characters
$cperiods=substr_count($bmiin,".");if ($cperiods>1|$bmiin=="."){$bmiin="";}

$pedin=preg_replace("/[^0-9.]/", "", escapeshellarg($_REQUEST['ped']));//remove all invalid characters
$cperiods=substr_count($pedin,".");if ($cperiods>1|$pedin=="."){$pedin="";}

$agein=preg_replace("/[^0-9.]/", "", escapeshellarg($_REQUEST['age']));//remove all invalid characters
$cperiods=substr_count($agein,".");if ($cperiods>1|$agein=="."){$agein="";}

$year1in=preg_replace("/[^0-9]/", "", escapeshellarg($_REQUEST['year1']));//remove all invalid characters
$cperiods=substr_count($year1in,".");if ($cperiods>0 | $year1in=="."){$year1in="";}

$year2in=preg_replace("/[^0-9]/", "", escapeshellarg($_REQUEST['year2']));//remove all invalid characters
$cperiods=substr_count($year2in,".");if ($cperiods>0 | $year2in=="."){$year2in="";}

if ($year2in !="" && ($year1in > $year2in)){die("Invalid year input. Example: 2000 2001");}

/////////////////////////////////////////if no input predictors///////////////////////////////////////////
else if (strlen($pregin)==0 && strlen($glucosein)==0 && strlen($bmiin)==0 && strlen($pedin)==0 && strlen($agein)==0){
	$cmd="\"$PYEXE\"  NpInputforRisk.py ".escapeshellarg($tempdir)." ".escapeshellarg($year1in)." ".escapeshellarg($year2in)." 2>&1";
	exec($cmd,$outno);//print_r($outno);
	if ($outno[0]=="importissue"){die("Python is unable to import all required modules, check README.md for required modules.");}
    elseif ($outno[0]=="dbissue"){die("Python is unable to connect to database, calculation is not performed.");}
	elseif ($outno[0]=="inputissue"){die("Python is unable to load user input, calculation is not performed.");}
    elseif ($outno[0]=="calissue"){die("Python failed to perform analysis.");}


	$percentout=$outno[0];
	if ($percentout>=0){
		$percent=round($percentout*100,3);
	    echo "No predictors entered. Proportion of diabetes in selected sample is $percent %.<br><br>";
		}
	else {echo "No records with known diabetes status. Risk cannot be calculated. <br><br>";}
	
	if ($year1in=="" && $year2in==""){echo "Sample year range: All years ";}
	if ($year1in=="" && $year2in!=""){echo "Sample year range: No later than year $year2in";}
	if ($year1in!="" && $year2in==""){echo "Sample year range: No earlier than year $year1in";}
	else if ($year1in!="" && ($year1in <= $year2in)){echo "Sample year range: $year1in - $year2in";}
	echo "<br><br>Number of valid records in selected sample: $outno[1] <br><br>"; 
	
?>
<form method="POST">
<input type="submit" name="reset" value="Clear All">
</form>
<?php
}

else {// if at least one predictor
$cmd="\"$PYEXE\"  Calculate_Risk.py ".escapeshellarg($tempdir)." ".escapeshellarg($pregin)." ".
escapeshellarg($glucosein)." ".escapeshellarg($bmiin)." ".escapeshellarg($pedin)." ".escapeshellarg($agein)." ".
escapeshellarg($year1in)." ".escapeshellarg($year2in)." 2>&1";
exec($cmd,$out); //print_r($out);

	if ($out[0]=="importissue"){die("Python is unable to import all required modules, check README.md for required modules.");}
    elseif ($out[0]=="dbissue"){die("Python is unable to connect to database, calculation is not performed.");}
	elseif ($out[0]=="inputissue"){die("Python is unable to load user input, calculation is not performed.");}
    elseif ($out[0]=="calissue"){die("Python failed to perform analysis.");}


if ($out[0]=="nobs"){
	echo "Sample size: $out[1]. Estimation is not performed when sample size < 5.";
}
else if (!strpos($out[1],"Number of Observations")==0){die("Failure to perform analysis.cperiods>0");}


else {//when sample size >=5
echo "Risk to get diabetes: $out[0] <br><br>";
echo "Based on user inputs, risk is estimated using individual values: <br>";

//1 for user input, 0 for no user input
if (strlen($pregin)>0){$lenpreg=1;echo "Number of pregnancies: $pregin <br>";}else {$lenpreg=0;};
if (strlen($glucosein)>0){$lenglu=1;echo "Glucose: $glucosein mg/dL<br>";}else {$lenglu=0;};//print_r($lenglu);
if (strlen($bmiin)>0){$lenbmi=1;echo "BMI: $bmiin kg/m<sup>2</sup><br>";}else {$lenbmi=0;};
if (strlen($pedin)>0){$lenped=1;echo "Diabetes pedigree function value: $pedin <br>";}else {$lenped=0;};
if (strlen($agein)>0){$lenage=1;echo "Age: $agein years <br>";}else {$lenage=0;};
	if ($year1in=="" && $year2in==""){echo "Sample year range: All years ";}
	if ($year1in=="" && $year2in!=""){echo "Sample year range: No later than year $year2in";}
	if ($year1in!="" && $year2in==""){echo "Sample year range: No earlier than year $year1in";}
	else if ($year1in!="" && ($year1in <= $year2in)){echo "Sample year range: $year1in - $year2in <br>";}
$cloop=$lenpreg + $lenglu + $lenbmi + $lenped + $lenage + 1; //print_r($cloop);//total number of predictors + intercept
echo "<br><br>";

////////////////////////table for model information////////////////////////////////////
echo '<table class="mytable">';
echo "<tr><th align='center' colspan='2'>Model information (Logistic regression)</th></tr>";
echo "<tbody><tr><td class='twoc'>$out[1]</td><td class='twoc'>$out[2]</td></tr>";//1 for nobs,2 for df residual
echo "<tr><td class='twoc'>$out[3]</td><td class='twoc'>Method: MLE</td></tr>";//3 for DF model
echo "<tr><td class='twoc'>$out[4]</td><td class='twoc'>$out[5]</td></tr>";//4=aic 5=bic
echo "<tr><td class='twoc'>$out[6]</td><td class='twoc'>$out[7]</td></tr>";
echo "<tr><td class='twoc'>$out[8]</td><td class='twoc'>$out[9]</td></tr>";
echo "</tbody></table><br><br>";
 
 /////////////////////////////////////table for parameter estimates//////////////////

echo '<table class="mytable">';
echo "<tr><th align='center' colspan='6' >Model parameters</th></tr>";

echo "<tr class='mytable'><td class='fivec' align='center'>Parameter</td><td align='center' class='fivec'>coef</td>";
echo "<td colspan='2' align='center'>95% Confidence Limits</td>
   <td align='center' class='fivec'>z</td><td align='center' class='fivec'>P>|z|</td></tr>";

//Intercept  $cloop 
//order: estimates, CI, z value, p value
$extraest=2;//two useless index for pieces  [17] => dtype: float64 [18] => 0 1;
$extraz=1; //one useless index for pz  [31] => dtype: float64 
//print_r($out);
$iparameter=11;//parameter initial index  
$pieses=explode(" ",$out[$iparameter]);//parameter estimate.mark=10
$pieses=array_values(array_filter($pieses,'myFilter'));//remove empty elements

$ciindex=$iparameter + $cloop +$extraest;// 
$pci=explode(" ",$out[$ciindex]);//19 for all
$pci=array_values(array_filter($pci,'myFilter'));

$zindex=$ciindex+ $cloop ; //
$pz=explode(" ",$out[$zindex]);//25 for aa
$pz=array_values(array_filter($pz,'myFilter'));

$pindex=$zindex + $cloop + $extraz;//
$pvalue=explode(" ",$out[$pindex]);//32 for all
$pvalue=array_values(array_filter($pvalue,'myFilter'));
$pieses[1]=round($pieses[1],3);$pci[1]=round($pci[1],3);$pci[2]=round($pci[2],3);
$pz[1]=round($pz[1],3);$pvalue[1]=round($pvalue[1],3);

if ($pieses[0]!="Intercept"){die("Failure to perform analysis.");}
echo "<tr><td class='fivec'>$pieses[0]</td><td align='center' class='fivec'>$pieses[1]</td>
   <td align='center' class='fivec'>$pci[1]</td><td align='center' class='fivec'>$pci[2]</td>
   <td align='center' class='fivec'>$pz[1]</td><td align='center' class='fivec'>$pvalue[1]</td></tr>";

//npreg
if (strlen($pregin)>0){
	$incre=$lenpreg;
$pieses=explode(" ",$out[$iparameter + $incre]);//12 for all
$pieses=array_values(array_filter($pieses,'myFilter'));//remove empty elements
$pci=explode(" ",$out[$ciindex + $incre]);//20 for all
$pci=array_values(array_filter($pci,'myFilter'));
$pz=explode(" ",$out[$zindex + $incre]);//26 for all
$pz=array_values(array_filter($pz,'myFilter'));
$pvalue=explode(" ",$out[$pindex + $incre]);//33 for all
$pvalue=array_values(array_filter($pvalue,'myFilter'));
$ornpreg=round(exp($pieses[1]),3);$ornpregL=round(exp($pci[1]),3);$ornpregU=round(exp($pci[2]),3);
$pieses[1]=round($pieses[1],3);$pci[1]=round($pci[1],3);$pci[2]=round($pci[2],3);
$pz[1]=round($pz[1],3);$pvalue[1]=round($pvalue[1],3);
echo "<tr><td class='fourc'>NO. of pregnancies</td><td align='center' class='fivec'>$pieses[1]</td>
   <td align='center' class='fivec'>$pci[1]</td><td align='center' class='fivec'>$pci[2]</td>
   <td align='center' class='fivec'>$pz[1]</td><td align='center' class='fivec'>$pvalue[1]</td></tr>";
}

//glucose
if (strlen($glucosein)>0){
	$incre=$lenpreg +$lenglu; //index increment from intercept
$pieses=explode(" ",$out[$iparameter + $incre]);//13 for all
$pieses=array_values(array_filter($pieses,'myFilter'));//remove empty elements
$pci=explode(" ",$out[$ciindex + $incre]);//21 for all
$pci=array_values(array_filter($pci,'myFilter'));
$pz=explode(" ",$out[$zindex + $incre]); //27 for all
$pz=array_values(array_filter($pz,'myFilter'));
$pvalue=explode(" ",$out[$pindex + $incre]); // 34 for all
$pvalue=array_values(array_filter($pvalue,'myFilter'));
$orglucose=round(exp($pieses[1]),3);$orglucoseL=round(exp($pci[1]),3);$orglucoseU=round(exp($pci[2]),3);
$pieses[1]=round($pieses[1],3);$pci[1]=round($pci[1],3);$pci[2]=round($pci[2],3);
$pz[1]=round($pz[1],3);$pvalue[1]=round($pvalue[1],3);
echo "<tr><td class='fourc'>Glucose</td><td align='center' class='fivec'>$pieses[1]</td>
   <td align='center' class='fivec'>$pci[1]</td><td align='center' class='fivec'>$pci[2]</td>
   <td align='center' class='fivec'>$pz[1]</td><td align='center' class='fivec'>$pvalue[1]</td></tr>";
}
//BMI
if (strlen($bmiin)>0){
	$incre=$lenpreg +$lenglu + $lenbmi; //index increment
$pieses=explode(" ",$out[$iparameter + $incre]);//14 for all
$pieses=array_values(array_filter($pieses,'myFilter'));//remove empty elements
$pci=explode(" ",$out[$ciindex + $incre]);//22 for all
$pci=array_values(array_filter($pci,'myFilter'));
$pz=explode(" ",$out[$zindex + $incre]);//28 for all
$pz=array_values(array_filter($pz,'myFilter'));
$pvalue=explode(" ",$out[$pindex + $incre]);//35 for all
$pvalue=array_values(array_filter($pvalue,'myFilter'));
$orBMI=round(exp($pieses[1]),3);$orBMIL=round(exp($pci[1]),3);$orBMIU=round(exp($pci[2]),3);
$pieses[1]=round($pieses[1],3);$pci[1]=round($pci[1],3);$pci[2]=round($pci[2],3);
$pz[1]=round($pz[1],3);$pvalue[1]=round($pvalue[1],3);
echo "<tr><td class='fourc'>BMI</td><td align='center' class='fivec'>$pieses[1]</td>
   <td align='center' class='fivec'>$pci[1]</td><td align='center' class='fivec'>$pci[2]</td>
   <td align='center' class='fivec'>$pz[1]</td><td align='center' class='fivec'>$pvalue[1]</td></tr>";
}

//ped
if (strlen($pedin)>0){
	$incre=$lenpreg +$lenglu + $lenbmi +$lenped; //index increment
$pieses=explode(" ",$out[$iparameter + $incre]);//15 for all
$pieses=array_values(array_filter($pieses,'myFilter'));//remove empty elements
$pci=explode(" ",$out[$ciindex + $incre]);//23 for all
$pci=array_values(array_filter($pci,'myFilter'));
$pz=explode(" ",$out[$zindex + $incre]);//29 for all
$pz=array_values(array_filter($pz,'myFilter'));
$pvalue=explode(" ",$out[$pindex + $incre]);//36 for all
$pvalue=array_values(array_filter($pvalue,'myFilter'));
$orped=exp($pieses[1]);$orpedL=exp($pci[1]);$orpedU=exp($pci[2]);
$orped=round(exp($pieses[1]),3);$orpedL=round(exp($pci[1]),3);$orpedU=round(exp($pci[2]),3);
$pieses[1]=round($pieses[1],3);$pci[1]=round($pci[1],3);$pci[2]=round($pci[2],3);
$pz[1]=round($pz[1],3);$pvalue[1]=round($pvalue[1],3);
echo "<tr><td class='fourc'>Diabetes pedigree</td><td align='center' class='fivec'>$pieses[1]</td>
   <td align='center' class='fivec'>$pci[1]</td><td align='center' class='fivec'>$pci[2]</td>
   <td align='center' class='fivec'>$pz[1]</td><td align='center' class='fivec'>$pvalue[1]</td></tr>";
}

//age
if (strlen($agein)>0){
	$incre=$lenpreg +$lenglu + $lenbmi +$lenped + $lenage; 
$pieses=explode(" ",$out[$iparameter + $incre]);//16 for all
$pieses=array_values(array_filter($pieses,'myFilter'));//remove empty elements
$pci=explode(" ",$out[$ciindex + $incre]);//24 for all
$pci=array_values(array_filter($pci,'myFilter'));
$pz=explode(" ",$out[$zindex + $incre]);//30 for all
$pz=array_values(array_filter($pz,'myFilter'));
$pvalue=explode(" ",$out[$pindex + $incre]);//37 for all
$pvalue=array_values(array_filter($pvalue,'myFilter'));
$orage=round(exp($pieses[1]),3);$orageL=round(exp($pci[1]),3);$orageU=round(exp($pci[2]),3);
$pieses[1]=round($pieses[1],3);$pci[1]=round($pci[1],3);$pci[2]=round($pci[2],3);
$pz[1]=round($pz[1],3);$pvalue[1]=round($pvalue[1],3);
echo "<tr><td class='fourc'>Age</td><td align='center' class='fivec'>$pieses[1]</td>
   <td align='center' class='fivec'>$pci[1]</td><td align='center' class='fivec'>$pci[2]</td>
   <td align='center' class='fivec'>$pz[1]</td><td align='center' class='fivec'>$pvalue[1]</td></tr>";
}   
echo "</tbody></table><br><br>";

/////////////////////////////////table for odds ratio/////////////////
echo '<table class="mytable">';
echo "<tr class='mytable'><th align='center' colspan='4' >Odds Ratio Estimates</th></tr>";
echo "<tbody><tr class='mytable'><td align='center'>Effect</td><td align='center'>Point Estimate</td>
<td colspan='2' align='center'>95% Confidence Limits</td><tr>";
if (strlen($pregin)>0){
echo "<tr><td class='fourc'>NO. of pregnancies</td><td class='fourc' align='center'>$ornpreg</td>
 <td align='center' class='fourc'>$ornpregL</td><td align='center' class='fourc'>$ornpregU</td></tr>";
}

if (strlen($glucosein)>0){
echo "<tr><td class='fourc'>Glucose</td><td class='fourc' align='center'>$orglucose</td>
 <td align='center' class='fourc'>$orglucoseL</td><td align='center' class='fourc'>$orglucoseU</td></tr>";
}

if (strlen($bmiin)>0){
echo "<tr><td class='fourc'>BMI</td><td class='fourc' align='center'>$orBMI</td>
 <td align='center' class='fourc'>$orBMIL</td><td align='center' class='fourc'>$orBMIU</td></tr>";
}
if (strlen($pedin)>0){
echo "<tr><td class='fourc'>Diabetes pedigree</td><td class='fourc' align='center'>$orped</td>
 <td align='center' class='fourc'>$orpedL</td><td align='center' class='fourc'>$orpedU</td></tr>";
}
if (strlen($agein)>0){
echo "<tr><td class='fourc'>Age</td><td class='fourc' align='center'>$orage</td>
 <td align='center' class='fourc'>$orageL</td><td align='center' class='fourc'>$orageU</td></tr>";
}
echo "</tbody></table><br><br>";

$auc=round($out[10],3);
echo "Area under curve: $auc <br>";




$rocdir=end($out);//suppose the last elemennt returns roc curve image,want
if(file_exists($rocdir)){
	$rocimg=base64_encode(file_get_contents($rocdir));//want
    echo "<img width=300 src='data:image/png;base64,$rocimg'>";//want
    unlink($rocdir);
}



?>
<form method="POST">
<input type="submit" name="reset" value="Clear All">
</form>
<?php
}//if sample size >=5
}//else if there is at least one input
}//if (isset($_REQUEST['calculate']) )
?>

</div>
</div>

</body>

</html>