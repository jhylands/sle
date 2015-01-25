	
	//query for class
	$QRY = "SELECT * FROM main WHERE ClassID=" . $_GET['class'] . " ORDER BY QuestionID ASC, PupilID";
	$result = mysqli_query($con,$QRY);
	echo "<table>";
	while($row = mysqli_fetch_array($result)){
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

