<?php
include 'sql.php';
if(isset($_GET['class'])){
	//query for class
	$QRY = "SELECT * FROM class WHERE PID=" . $_GET['class'];
	$result = mysqli_query($con,$QRY);
	while($row = mysqli_fetch_array($result)){
		$questions = explode(",",$row['Questions']);
		foreach($questions as $question){
			$QRY1 = "SELECT * FROM questions WHERE PID=" . $question;
			$result1 = mysqli_query($con, $QRY1);
			while($row1 = mysqli_fetch_array($result1)){
				echo "<option value='";
				echo $row1['PID'];
				echo "'>";
				echo $row1['Question'];
				echo "</option>";
			}
		}
	}
	//query for question text
}else{
	$subjects = explode("," , $_GET['subjects']);
	$years = explode("," , $_GET['years']);
	foreach($subjects as  $subject){
		//first find a list of the classes who are of that subject (subject could be a list of subjects)
		//include a date perameter to limit the search to this year
		if($subject==0){
			//the case of all subjects
			$QRY = "SELECT * FROM main WHERE Annum=" . date('Y');
		}else{
			//the case of a pecific subject
			$QRY = "SELECT * FROM main WHERE SubjectID=" . $subject . " AND Annum=" . date('Y');
		}
		//echo $QRY;
		$result = mysqli_query($con,$QRY);
		//retrive request for a list of classes who are for that subject
		while($row = mysqli_fetch_array($result)){
			$QRY1 = "SELECT * FROM class WHERE PID = " . $row['ClassID'];
			//echo "<br />QRY1: " . $QRY1;
			$result1 = mysqli_query($con,$QRY1);
			//retrive each class from the class table of the database to check if it is the correct year group
			while($row1 = mysqli_fetch_array($result1)){
				//if the class code begins with a 1 then the year is given by the first two charactors of the code
				//otherwise only the first charactor is important
				if(substr($row1['Code'],0,1) == '1'){
					$year= substr($row1['Code'],0,2);
				}else{
					$year = substr($row1['Code'],0,1);
				}
				//echo "!" . $year . "|";
				//echo implode(",",$years) . "|";
				if(in_array($year,$years) || $years[0]==1){
					echo "<option value='";
					echo $row1['PID'];
					echo "'>";
					echo $row1['Code'];
					echo "</option>";
				}//otherwise that class is not in the bounds given by the specified years
			}
		//tell the user that the action has been updated
		}
	}
}
?>
