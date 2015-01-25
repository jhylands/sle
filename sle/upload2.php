<?php
//include general scripts
include 'head.php';
include 'scripts/generalscripts.php';
include 'scripts/sql.php';
$errorMessage = "<p id='information'>Invalid file. If you where looking for the table of codes with the student logins, that is available from the main page.<a href='index.php'>Back to menu</a><br/></p>";

class classData{
public $connection;
//requires sql.php
public function addPupilClassData($dataRow , $pupilID, $annum, $con){
	$classID=$this->classExist($dataRow[1],$con);
	if(!$classID){
		//add class
		$classID = $this->addClass($dataRow[1],$con);
		//Get ID for main entry
		$classID = $this->classExist($dataRow[1],$con);
	}
	$subjectID=$this->subjectExist($dataRow[2],$con);
	if(!$subjectID){
		//add subject
		$subjectID = $this->addSubject($dataRow[2],$con);
		//Get ID for main entry
		$subjectID = $this->subjectExist($dataRow[2],$con);
	}
	$teacherID=$this->teacherExist($dataRow[3],$con);
	if(!$teacherID){
		//add teacher
		$teacherID = $this->addTeacher($dataRow[3],$con);
		//Get ID for main entry
		$teacherID = $this->teacherExist($dataRow[3],$con);
	}
	//add 1 entry for questions with response and question id blank
	$QRY0 = "INSERT INTO main (ClassID,SubjectID,TeacherID,Response,PupilID,Annum) VALUES (" . $classID . "," . $subjectID . "," . $teacherID . ",0," . $pupilID . "," . $annum . ")";
	return mysqli_query($con,$QRY0);
}
//check whether the class exists or not
public function classExist($class,$con){
	//by default it doesn't
	$exist = false;
	$QRY0 = "SELECT * FROM class WHERE Code='" . $class . "'";
	$results = mysqli_query($con,$QRY0);
	while($row = mysqli_fetch_array($results)){
		//if the selection returns blank then this while won't be entered
		//therefore $exist stays false
		$exist= $row['PID'];
	}
	return $exist;
}
//check whether the subject exists or not
public function subjectExist($subject,$con){
	//by default it doesn't
	$exist = false;
	$QRY0 = "SELECT * FROM subjects WHERE Subject='" . $subject . "'";
	$results = mysqli_query($con,$QRY0);
	while($row = mysqli_fetch_array($results)){
		//if the selection returns blank then this while won't be entered
		//therefore $exist stays false
		$exist= $row['PID'];
	}
	return $exist;
}
//check whether the teacher exists or not
public function teacherExist($teacher,$con){
	//by default it doesn't
	$exist = false;
	$QRY0 = "SELECT * FROM teachers WHERE Name='" . $teacher . "'";
	$results = mysqli_query($con,$QRY0);
	while($row = mysqli_fetch_array($results)){
		//if the selection returns blank then this while won't be entered
		//therefore $exist stays false
		$exist=$row['PID'];
	}
	return $exist;
}
//add the class to the class table
public function addClass($class,$con){
	$QRY0 = "INSERT INTO class (Code) VALUES ('" . $class . "')";
	mysqli_query($con,$QRY0);
}
//add the teacher to the teacher table
public function addTeacher($teacher,$con){
	$names = explode(" ",$teacher);
	$QRY0 = "INSERT INTO teachers (Name,User,Password) VALUES ('" . $teacher . "','" . $names[count($names)-1] . "','" . hash("sha512" , "password") . "')";
	mysqli_query($con,$QRY0);
}
//add the teacher to the teacher table
public function addSubject($subject,$con){
	$QRY0 = "INSERT INTO subjects (Subject) VALUES ('" . $subject . "')";
	mysqli_query($con,$QRY0);
}
//add pupil
public function addPupil($dataRow, $con){
	//check if the pupil exists
	//MORE DATA VALIDATION NEEDED HERE
	//split the full name into names
	$names = explode(chr(160), $dataRow[0]);
	//take the first letter of the first name and the sir name and concatinate them
	$userName = strtolower(substr($names[0],0,1) . $names[1]);
	$pupilID = $this->pupilExist($userName,$con);
	if(!$pupilID){
		echo "<tr><td>" . $dataRow[0] ."</td>";
		$key = hash("crc32",$userName);
		echo "<td>" . $userName . "</td>";
		echo "<td>" . $key . "</td>";
		echo "</tr>";
		$QRY0 = "INSERT INTO pupils (Name,Code) VALUES ('" . $userName . "','" . $key . "')";
		//echo $QRY0;
		mysqli_query($con,$QRY0);
		$pupilID = $this->pupilExist($userName,$con);
	}
	//echo "<br />" . $dataRow[0] . ": Pupil ID:" . $pupilID;
	return $pupilID;
}
//check whether the pupil already exists
private function pupilExist($pupil,$con){
	//by default it doesn't
	$exist = false;
	$QRY0 = "SELECT * FROM pupils WHERE Name='" . $pupil . "'";
	$results = mysqli_query($con,$QRY0);
	while($row = mysqli_fetch_array($results)){
		//if the selection returns blank then this while won't be entered
		//therefore $exist stays false
		$exist=$row['PID'];
	}
	return $exist;
}
}
//select allowed
$allowedExts = array("csv","CSV");
if(isset($_FILES["file"]["name"])){
$temp = explode(".", $_FILES["file"]["name"]);
$extension = end($temp);
if (($_FILES["file"]["size"] < 30000)
&& in_array($extension, $allowedExts))
  {
  if ($_FILES["file"]["error"] > 0)
    {
	
    echo "Return Code: " . $_FILES["file"]["error"] . "<br>";
    }
  else
    {
    echo "<a href='index.php'>Back to menu</a><br/>";
    echo "Upload: " . $_FILES["file"]["name"] . "<br>";
    echo "Type: " . $_FILES["file"]["type"] . "<br>";
    echo "Size: " . ($_FILES["file"]["size"]) . " Bytes<br>";
    echo "Temp file: " . $_FILES["file"]["tmp_name"] . "<br>";
    if (file_exists("upload/" . $_FILES["file"]["name"]))
      {
      echo $_FILES["file"]["name"] . " already exists. ";
      }
    else
      {
	  //Convert the file to 2D array
	  $data = csvToArray($_FILES["file"]["tmp_name"]);
      	  //add lines to db ----------------
	  //create a variable to hold the 'classData' class
	  $classDataObject  = new classData();
	  $sizeOfInput = sizeof($data);
	  echo "Size of input:" . $sizeOfInput;
	  $pupilID = 0;
	echo "<table border='1' id='tableOfTitles'><tr><td><b>Name</b></td><td><b>Username</b></td><td><b>Password</b></td>";
	  for($i=0;$i<$sizeOfInput;$i++){
		  //check is house list
		  if($data[$i][2]!==""){
		  	//check if the name is listed Not null but blank
		  	if($data[$i][0]!==""){
		  		//add pupil 
		  		$pupilID = $classDataObject->addPupil($data[$i], $con);
		  	}
		  	//call add data
		  	$classDataObject->addPupilClassData($data[$i] , $pupilID, $_POST['year'], $con);
      		  }
  	  }
	echo "</table>";
  	  mysqli_close($con);
  	}
  }
  }
else
  {
  echo $_FILES["file"]["type"] . "<br />" .$_FILES["file"]["type"];
   echo $errorMessage;
  }
}else{
	echo $errorMessage;
}
?>
