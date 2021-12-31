<!--Samuel Yoon -->


<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset = "UTF-8">
        <meta name = "viewport" content ="width=device-width, initial-scale=1.0">
        <title>Patient info</title>
        <style>
            label, input{ display:inline-block; }
            input{ padding:5px; }
            tr{background-color: #E3F9F9;}
            tr:nth-child(even) {background-color: #f2f2f2;} //hoverable table
            background-color: white;
        </style>
    
    <link rel="stylesheet" href="mainstyle.css">
    </head>
    <main>
    <body>
    <a href="index.html" style="color: white">Main Menu ></a>   
    <a href="table.php" style="color: white">Review Database</a><br>   
    <h2> Patients Datebase </h2>

    <?php
    include "dbtestconn.php";
	$pdo=$db;
    //$pdo = new PDO('sqlite:DMdata.db');
    $statement  = $pdo->query("SELECT * FROM DMdata");
    $DMdata = $statement->fetchAll(PDO::FETCH_ASSOC);

    echo"<table border=2 background-color:white >";
    
    echo"<tr>";
        echo"<td>id</td>";
        echo"<td>FirstName</td>";
        echo"<td>LastName</td>";
        echo"<td>No.Preg</td>";
        echo"<td>Glucose</td>";
        echo"<td>BMI</td>";
        echo"<td>Ped.Value</td>";
        echo"<td>Age</td>";
        echo"<td>Diabetes</td>";
        echo"<td>SurveyYear</td>";
       
    echo"</tr>";

    foreach($DMdata as $row => $DMdata){
        echo "<tr>";
            echo"<td>" . $DMdata['id'] . "</td>";
            echo"<td>" . $DMdata['FirstName'] . "</td>";
            echo"<td>" . $DMdata['LastName'] . "</td>";
            echo"<td>" . $DMdata['npreg'] . "</td>";
            echo"<td>" . $DMdata['glucose'] . "</td>";
            echo"<td>" . $DMdata['BMI'] . "</td>";
            echo"<td>" . $DMdata['ped'] . "</td>";
            echo"<td>" . $DMdata['age'] . "</td>";
            echo"<td>" . $DMdata['Diabetes'] . "</td>";
            echo"<td>" . $DMdata['SurveyYear'] . "</td>";
        echo "</tr>";
    }
    
    echo "</table>";
    ?>
    </main>
    </body>
    
</html>
    