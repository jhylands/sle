<?php
include 'sql.php';
if(isset($_GET['class'])){
	echo "<a href='scripts/viewassignedstudentlogins.php?class=" . $_GET['class'] . "'>View printable version</a>";	
	//query for class
	$QRY = "SELECT * FROM main WHERE ClassID=" . $_GET['class'] . " ORDER BY QuestionsID ASC";
	$result = mysqli_query($con,$QRY);
	echo "<table border='1'>";
	echo "<td><b>UserName</b></td><td><b>Password</b></td>";
	$flipflop = True;
	$name = -1;
	while($row = mysqli_fetch_array($result)){
		if($name==$row['PupilID']){
			break;
		}
		if($flipflop){
			$name=$row['PupilID'];
			$flipflop = False;
		}
		$QRY1 = "SELECT * FROM pupils WHERE PID=" . $row['PupilID'];
		$result1 = mysqli_query($con,$QRY1);
		echo "<tr>";
		while($row1 = mysqli_fetch_array($result1)){
			echo "<td>";
			echo $row1['Name'];
			echo "</td><td>";
			echo $row1['Code'];
			echo "</td>";
		}
		echo "</tr>";
	}
	echo "</table>";


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
