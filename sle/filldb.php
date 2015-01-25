<?php
include 'scripts/sql.php';
//get the list of students
$PupilsQuery = "SELECT * FROM pupils";
$PupilsQueryResults = mysqli_query($con,$PupilsQuery);
while($PupilsQueryRow = mysqli_fetch_array($PupilsQueryResults)){
	//select that pupil from the main db
	$pupil = $PupilsQueryRow['PID'];
	echo "002";
	$MainQuery = "SELECT * FROM main WHERE PupilID=" . $pupil;
	echo $MainQuery;
	$MainResults = mysqli_query($con,$MainQuery);
	while($MainRow = mysqli_fetch_array($MainResults)){
		echo "003";
		//select the class from the class table as given from the main table in the last line
		$ClassQuery = "SELECT * FROM class WHERE PID=" . $MainRow['ClassID'];
		$ClassResults = mysqli_query($con,$ClassQuery);
		while($ClassRow = mysqli_fetch_array($ClassResults)){
			//for a specific class
			$questions = explode(",",$ClassRow['Questions']);
			//select the first element in the main table for editing
			$MainClassQuery = "SELECT * FROM main WHERE PupilID=" . $pupil . " AND ClassID=" . $MainRow['ClassID'] . " AND Annum=" . date("Y");
			$MainClassResults = mysqli_query($con,$MainClassQuery);
			while($MainClassRow = mysqli_fetch_array($MainClassResults)){
				$ged = $MainClassRow;//ged short for General entry data
			}
			echo "004";
			//create input for db
			//the flip is there so that the  first entry which already exists can be updated before more are created
			$flip = true;
			foreach($questions as &$question){
				if($flip){
					//modify the first
					$QRY = "UPDATE main SET QuestionsID='" . $question . "' , Response=" . rand(1,4) . " WHERE EntryID=" . $ged['EntryID'];
					$flip=false;
				}else{
					//add the rest
					$QRY = "INSERT INTO  main (`ClassID` ,`SubjectID` ,`TeacherID` ,`QuestionsID` ,`Response` ,`PupilID` ,`Annum`)
					VALUES (" . $ged['ClassID'] . "," . $ged['SubjectID'] . "," . $ged['TeacherID'] . ",'" . $question . "'," . rand(1,4) . "," . $ged['PupilID'] . "," . date("Y") . ")";
				}
				echo $QRY . "<br />";
				echo mysqli_query($con,$QRY) . "<br /><hr />";
			}
		}
	echo "005";
	}
}//end of for each pupil
?>
