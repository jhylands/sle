<?php
include 'sql.php';
$QRY = "SELECT * FROM pupils WHERE Name='" . $_GET['Pupil'] . "'";
//echo $QRY;
$result = mysqli_query($con,$QRY);
if($result){
	while($row = mysqli_fetch_array($result)){
		$PupilID = $row['PID'];
	}
echo "1";
}
else(
echo "0";
}
$QRY = "SELECT DISTINCT ClassID FROM main WHERE PupilID = " . $PupilID;
//echo $QRY;
$result = mysqli_query($con,$QRY);
while($row = mysqli_fetch_array($result)){
	//echo "<br /><hr>UPDATE main SET QuestionsID=NULL, Response=0 WHERE PupilID=" . $PupilID . " AND ClassID=" . $row['ClassID'] . " LIMIT 1";
	mysqli_query($con,"UPDATE main SET QuestionsID=NULL, Response=0 WHERE PupilID=" . $PupilID . " AND ClassID=" . $row['ClassID'] . " LIMIT 1");
}
$QRY = "SELECT * FROM main WHERE PupilID = " . $PupilID;
/*echo $QRY;
$result = mysqli_query($con,$QRY);
echo "<table border=1>";
while($row = mysqli_fetch_array($result)){
	echo "<tr><td>"  . $row['ClassID'] . "</td><td>" . $row['QuestionsID'] . "</td><td>" . $row['Response'] . "</td></tr>";
}*/
mysqli_query($con,"DELETE FROM main WHERE PupilID=" . $PupilID . " AND QuestionsID IS NOT NULL");
//echo "DELETE FROM main WHERE PupilID=" . $PupilID . " AND QuestionsID IS NOT NULL";
?>
