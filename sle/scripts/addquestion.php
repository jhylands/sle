<?php

//$_GET['question']
//include connection to the database
include 'sql.php';
//insert the question into the database
echo "INSERT INTO questions (Question) VALUES (" . $_GET['question'] . ")";
echo mysqli_query($con,"INSERT INTO questions (Question) VALUES ('" . $_GET['question'] . "')");
//querying the database to the the question ID
//$QRY = "select * from questions WHERE Question='" . $_GET['question'] . "'";
$results = mysqli_query($con,$QRY);
while ($row = mysqli_fetch_array($results)){
	echo $row['PID'];
}
?>
