<?php
if(isset($_GET['valid'])){
//download all
include 'sql.php';
include 'generalscripts.php';
//select all from the main db ordering it by class
$QRY = "SELECT * FROM main ORDER BY Class ASC";
//echo $QRY;//for debugging
$results = mysqli_query($con,$QRY);
$csv = Array();
//Have an array to pass into the csv generator
$csv[0] = Array('Class','Subject','Teacher','Q1','Q2','Q3','Q4','Q5','Q6','Q7','Q8','Q9','Q10','Total');
$classC = "";//The currently adding class
$n = 1;
while($row = mysqli_fetch_array($results)){
	$csv[$n] = Array($row['Class'],$row['Subject'],$row['Teacher'],0,0,0,0,0,0,0,0,0,0,$row['Total']);
	for($i=1;$i<11;$i++){
		$csv[$n][$i + 3] = $row['Q' . $i];
	}
	$n++;
}
//send the CSV to the browser
outputCSV("Raw.csv", $csv);
}else{
//tell them the page does not exist
echo "<h1>Not Found</h1><p>The requested URL /dall.php was not found on this server.</p>";
}
?>