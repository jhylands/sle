<?php
if(isset($_POST['Class']) && isset($_COOKIE['SLEUser'])){
include 'scripts/sql.php';
//gather data from database
$Class = $_POST['Class'];
$QRY1 = "SELECT * FROM class WHERE PID=" . $_POST['Class'];
$results1 = mysqli_query($con,$QRY1);
while($row1 = mysqli_fetch_array($results1)){
	$questions = explode(",",$row1['Questions']);
}
$QRY1 = "SELECT * FROM main WHERE PupilID=" . $_COOKIE['SLEUser'] . " AND ClassID=" . $_POST['Class'] . " AND Annum=" . date("Y") . " ORDER BY EntryID ASC LIMIT 1";
echo $QRY1;
$results1 = mysqli_query($con,$QRY1);
while($row1 = mysqli_fetch_array($results1)){
	$ged = $row1;//ged short for General entry data
}
//create input for db
$flip = True;
foreach($questions as &$question){
	if($flip){
	//modify the first
		$QRY = "UPDATE main SET QuestionsID=" . $questions[0] . " , Response=" . $_POST['Q' . $questions[0]] . " WHERE EntryID=" . $ged['EntryID'];
		$flip=false;
	}else{
	//add the rest
		$QRY = "INSERT INTO  main (`ClassID` ,`SubjectID` ,`TeacherID` ,`QuestionsID` ,`Response` ,`PupilID` ,`Annum`)
		VALUES (" . $ged['ClassID'] . "," . $ged['SubjectID'] . "," . $ged['TeacherID'] . "," . $question . "," . $_POST['Q' . $question] . "," . $ged['PupilID'] . "," . date("Y") . ")";
	}
	//echo $QRY . "<br />";
	//!WARNING! DO NOT COMMENT OUT THE FOLLOWING LINE!
	echo $QRY;
	echo mysqli_query($con,$QRY) . "<br /><hr />";
}
echo "<script>window.location.replace('main.php');</script>";
}else{
echo "<h1>Error:6 # you can not access this page</h1><p>Please <a href='index.php'>click here</a> to go back to where you should be.";
}
?>
