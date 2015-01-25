<?php
//export data as csv of class totals
include 'sql.php';
//include the code to generate the csv
include'makecsv.php';
//select all from the main db ordering it by class
$QRY = "SELECT * FROM main ORDER BY Class ASC";
//echo $QRY;//for debugging
$results = mysqli_query($con,$QRY);
$csv = Array();
//Have an array to pass into the csv generator
$csv[0] = Array('Class','Q1','Q2','Q3','Q4','Q5','Q6','Q7','Q8','Q9','Q10');
$classC = "";//The currently adding class
$n = 1;
while($row = mysqli_fetch_array($results)){
	//check for initial conditions
	if($classC == ""){
	$classC = $row['Class'];
	$csv[$n] = $Q = array($classC,0,0,0,0,0,0,0,0,0,0);
	}
	//if the class hasn't changed then continue summing the answers
	if($classC == $row['Class']){
		for( $i=1;$i<11;$i++){
			$csv[$n][$i] = $csv[$n][$i] + $row['Q' . $i];
		}
	}else{
		//if the class has changed then output the last row
		//update the current class
		$n++;
		$classC = $row['Class'];
		$csv[$n] = Array($classC,0,0,0,0,0,0,0,0,0,0);
		for( $i=1;$i<11;$i++){
			$csv[$n][$i] = $csv[$n][$i] + $row['Q' . $i];
		}
	}
}
//send the CSV to the browser
outputCSV("Class totals.csv", $csv);
?>