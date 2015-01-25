<?php
//include a list of useful scripts
include 'generalscripts.php';
//include a connection to the database
include 'sql.php'
/*
Building the request language
comma seperated series 
Annum:2014;ClassID:3;,Annum:2013;*/
$requests = explode(",",$_GET['request']);
foreach($requests as $request){
	$attributes = explode(";" , $request);
	//each attribute is now in the form "something:someting"
	foreach($attributes as /*an*/$atteribute){
		//check before colon
		//check after colon
	}
	$QRY = "SELECT * FROM main WHERE stuff thats been calculated before";
	$result = mysqli_query($con, $QRY);
	while($row=mysqli(resultything){
		//build a 2D array of Questions and elements
	}
	$a[$i][$n] = summaryarray($arr,0);
	
}
pythonGraph($a);
?>

