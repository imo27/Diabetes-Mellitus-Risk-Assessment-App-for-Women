<!--Samuel Yoon -->


<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset = "UTF-8">
        <meta name = "viewport" content ="width=device-width, initial-scale=1.0">
        <title> Add Patient info </title>
        <style>
            label, input{ display:inline-block; }
            input{ padding:5px; }
        </style>
        
    <link rel="stylesheet" href="mainstyle.css"> 
    </head>
    <main>
    <body>
    <a href="index.html" style="color: white">Main Menu ></a>   
    <a href="New_records.php" style="color: white">New Record</a><br> 
    
    <h2>Add Patient info to database</h2>

    <?php
    if (!isset($_POST['submit'])) {
    ?>
    
    <form action="<?php echo htmlentities($_SERVER['PHP_SELF']); ?>" method="post">
    <p style="color:white">    
    <label for="FirstName">Given Name:</label><br>
    <input type="text" name="FirstName" require><br><br>
    <label for="LastName">Family Name:</label><br>
    <input type="text" name="LastName" require><br><br>
    <label for="npreg">Number of pregnancies(0~10):</label><br>
    <input type="number" name="npreg" require><br><br>
    <label for="glucose">Glucose(mg/dL):</label><br>
    <input type="text" name="glucose" require><br><br>
    <label for="BMI">BMI (kg/m<sup>2</sup>):</label><br>
    <input type="text" name="BMI" require><br><br>
    <label for="ped">Diabetes pedigree function value:</label><br>
    <input type="text" name="ped" require><br><br>
    <label for="age">Age:</label><br>
    <input type="text" name="age" require><br><br>
    <lable for="Diabetes">Diabetes, Please select one of options:</label><br>
    <input type="radio" name="Diabetes" value="NULL" require>
    <label for="Diabetes"> Don't know</label><br>
    <input type="radio" name="Diabetes" value="1" require>
    <label for="Diabetes">Yes</label><br> 
    <input type="radio" name="Diabetes" value="0" require>
    <label for="Diabetes">No</label><br><br>  
    <label for="SurveyYear">Survey Year</label><br>
    <input type="number" name="SurveyYear" require><br><br>
    <input type="submit" name="submit" value="Submit & Review">
    <input type="reset" value="Reset">
    </p>
    </form>

    <?php
    } else  {
    try  {
        //$db = new PDO('sqlite:DMdata.db');
		include "dbtestconn.php";
        $sql = "INSERT INTO DMdata (FirstName, LastName, npreg, glucose, BMI, ped, age, Diabetes, SurveyYear) VALUES
        (:FirstName, :LastName, :npreg, :glucose, :BMI, :ped, :age, :Diabetes, :SurveyYear)";
        $stmt = $db->prepare($sql);
        //named params

        $FirstName = filter_input(INPUT_POST, 'FirstName');
        $stmt->bindValue(':FirstName', $FirstName, PDO::PARAM_STR);

        $LastName = filter_input(INPUT_POST, 'LastName');
        $stmt->bindValue(':LastName', $LastName, PDO::PARAM_STR);

        $npreg = filter_input(INPUT_POST, 'npreg');
        $stmt->bindValue(':npreg', $npreg, PDO::PARAM_STR);

        $glucose = filter_input(INPUT_POST, 'glucose');
        $stmt->bindValue(':glucose', $glucose, PDO::PARAM_STR);

        $BMI = filter_input(INPUT_POST, 'BMI');
        $stmt->bindValue(':BMI', $BMI, PDO::PARAM_STR);

        $ped = filter_input(INPUT_POST, 'ped');
        $stmt->bindValue(':ped', $ped, PDO::PARAM_STR);

        $age = filter_input(INPUT_POST, 'age');
        $stmt->bindValue(':age', $age, PDO::PARAM_STR);

        $Diabetes = filter_input(INPUT_POST, 'Diabetes');
		if ($Diabetes=='NULL'){
			$stmt->bindValue(':Diabetes', $Diabetes, PDO::PARAM_NULL);
		}
		else {$stmt->bindValue(':Diabetes', $Diabetes, PDO::PARAM_STR);}
          
        
        $SurveyYear = filter_input(INPUT_POST, 'SurveyYear');
        $stmt->bindValue(':SurveyYear', $SurveyYear, PDO::PARAM_STR);


            $success = $stmt->execute();
            if($success){
                //echo "Patient data has been added to the database";
                          
                echo("<script>location.replace('table.php');</script>"); 
                
            } else{
                echo "Sorry try again";
            }
        $db = null;
            
        } catch (PDOException $e) {
            // for development
            print "We had an error: " . $e->getMessage() . "<br/>";
            die();
        }   
    }
    ?>
    



    </main>
    </body>
    </html>