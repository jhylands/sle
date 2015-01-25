<?php
//include a connection to the sql database
include 'scripts/sql.php';
//include general scripts that are usefull on many of the pages throught the site such as summing the values of an array of floading point numbers
include 'scripts/generalscripts.php';
//include scripts specialy written to download files
include 'scripts/downloadscripts.php';
if($_POST['limitToGo']=="0"){
	//this is when the limiting selection is by each entry from the database
	$summary = elementDataGather($con);
	$data = buildCSV($con,$summary,$_POST['columns'],$_POST['headers']);
	outputCSV("SLE.csv",$data);
}elseif($_POST['limitToGo']=="1"){
	$summed = type($con,"ClassID",$_POST['summary']);
	$data = buildCSV($con,$summed,$_POST['columns'],$_POST['headers']);
	outputCSV("SLE.csv",$data);
}elseif($_POST['limitToGo']=="2"){
	$summed = type($con,"Year",$_POST['summary']);
	$data = buildCSV($con,$summed,$_POST['columns'],$_POST['headers']);
	outputCSV("SLE.csv",$data);
}elseif($_POST['limitToGo']=="3"){
	$summed = type($con,"SubjectID",$_POST['summary']);
	$data = buildCSV($con,$summed,$_POST['columns'],$_POST['headers']);
	outputCSV("SLE.csv",$data);
}elseif($_POST['limitToGo']=="4"){
	$summed = type($con,"TeacherID",$_POST['summary']);
	$data = buildCSV($con,$summed,$_POST['columns'],$_POST['headers']);
	outputCSV("SLE.csv",$data);
}elseif($_POST['limitToGo']=="5"){
	$summed = type($con,"Annum",$_POST['summary']);
	$data = buildCSV($con,$summed,$_POST['columns'],$_POST['headers']);
	outputCSV("SLE.csv",$data);
}
?>
