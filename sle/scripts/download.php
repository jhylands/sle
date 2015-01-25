<?php
include 'sql.php';
$_POST['type']
switch($_POST['type']){
case "all":
	//download all the data from all the years
	break;
case "year":
	//download data from a specific year
	break;
case "teacher":
	//download the data for a specific teacher
	$QRY0 = "SELECT * FROM main WHERE TeacherID=" . $_POST['value'] ." ORDER BY 'PupilID' 'QuestionID' ASC|ASC ";
	$results = mysqli_query($con,$QRY0);
	while($row = mysqli_fetch_array($results)){
	
	//get the teachewr name
	$QRY0 = "SELECT * FROM teachers WHERE TeacherID=" . $_POST['value'];
	$results = mysqli_query($con,$QRY0);
	while($row = mysqli_fetch_array($results)){
		$teacher = $row['Name'];
	}
	outputCSV($teacher . ".csv", $data);
	break;
	}