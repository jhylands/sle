<?php
include 'head.php';
if(isset($logIn)){
if($logIn!="student"){
echo "<h1>Error:5 # you can not access this page</h1><p>Please <a href='index.php'>click here</a> to go back to where you should be.";
exit(404);
}}
?>
<div id="logout">
<a href="logout.php">Logout</a>
</div>
<p class="information" >Information on how to use this page:asdfsa;sd;sad;sad;sa;d;sad';</p>
<?php
/*TABLE OF SUBJECTS
*/
//start building table?>
<center>
<table border="1" id="dashbody"><tr class="tabletop"><td>Subject</td><td>Class</td><td>Teacher</td><td>Complete</td></tr>
<?php
include "scripts/sql.php";
$comp=-1;
//get other information
$QRY1 = "SELECT * FROM main WHERE PupilID=" . $_COOKIE['SLEUser'] . " AND Annum=" . date("Y") . " ORDER BY ClassID";
echo $QRY1;
$results1 = mysqli_query($con,$QRY1);
while($row1 = mysqli_fetch_array($results1)){
	//If statement to stop multiple instances of the same subject
	if($row1['ClassID']!=$comp){
		//build this row of the table
		echo "<tr>";
		//build this cell
		echo "<td>";
		//find the subject name from the subject ID
		$QRY2 = "SELECT * FROM subjects WHERE PID=" . $row1['SubjectID'];
		$results2 = mysqli_query($con,$QRY2);
		while($row2 = mysqli_fetch_array($results2)){
			echo $row2['Subject'];
		}
		echo "</td><td>";
		//find the class name from the class ID
		$QRY2 = "SELECT * FROM class WHERE PID=" . $row1['ClassID'];
		$results2 = mysqli_query($con,$QRY2);
		while($row2 = mysqli_fetch_array($results2)){
			echo $row2['Code'];
		}
		echo "</td><td>";
		//find the teacher name from the teacher ID
		$QRY2 = "SELECT * FROM teachers WHERE PID=" . $row1['TeacherID'];
		$results2 = mysqli_query($con,$QRY2);
		while($row2 = mysqli_fetch_array($results2)){
			echo $row2['Name'];
		}
		echo "</td>";
		$QRY2 = "SELECT * FROM main WHERE ClassID=" . $row1['ClassID'] . " AND Annum=" . date("Y");
		$results2 = mysqli_query($con,$QRY2);
		while($row2 = mysqli_fetch_array($results2)){
			if($row2['Response']==0){
				echo "<td><a href='answer.php?class=" . $row1['ClassID'] . "'>Not yet complete</a></td></tr>";
			}else{
				echo "<td>Complete</td></tr>";
			}	
			break;
		}
		$comp=$row1['ClassID'];
	}
}
?>
</table>
</center>
<?php
include 'foot.php';
?>