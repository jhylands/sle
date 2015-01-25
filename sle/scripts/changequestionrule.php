<?php
//include a connection to the database
include 'sql.php';
/*inputs from post data:
-Years
-Subjects
||||||||||||||
-Class 
&&
-array of questions
*/
//create flip
echo "year as posted: " . $_POST['year'] . "!<br />";
$flip = true;
//if the class has been posted then assume the user wants to carry out the task by that class and not by the other filter
if(isset($_POST['class'])){
if($_POST['class']!=""){
	//the post should the the classID
	$QRY = "UPDATE class SET Questions='" . $_POST['questions'] . "' WHERE Code='" . $_POST['class'] . "'";
	echo $QRY;
	if(mysqli_query($con,$QRY)){
		echo "<script>var sendData = 'Class " . $_POST['class'] . " Updated';";
	}else{
		echo "<script>var sendData = 'Error class: " . $_POST['class'] . " not found';";
	}
	//flip the flipper so that the code skips the new if statement
	$flip=false;
}
}
if($flip){
	//get a list of the classes
	echo "subjects as posted:" . $_POST['subjects'] . "<br />";
	$subjects = explode("," , $_POST['subjects']);
	$years = explode("," , $_POST['year']);
	echo "years as posted: " . $_POST['years'] . "<br />";
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
		echo $QRY;
		$result = mysqli_query($con,$QRY);
		//retrive request for a list of classes who are for that subject
		while($row = mysqli_fetch_array($result)){
			$QRY1 = "SELECT * FROM class WHERE PID = " . $row['ClassID'];
			echo "<br />QRY1: " . $QRY1;
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
				echo "!" . $year . "|";
				echo implode(",",$years) . "|";
				if(in_array($year,$years) || $years[0]==1){
					$QRY2 = "UPDATE class SET Questions='" . $_POST['questions'] . "' WHERE PID=" . $row['ClassID'];
					echo $QRY2;
					echo mysqli_query($con,$QRY2);
				}//otherwise that class is not in the bounds given by the specified years
			}
		//tell the user that the action has been updated
		}
	}
	echo "<script>var  sendData = 'The questions for those classes updated.';";
}
?>
window.location.replace('../setup.php?just=' + sendData);</script>
