<html>
<head>
<title>Student lesson evaluations</title>
<link rel="stylesheet" type="text/css" href="styles/main.css" />
<?php
include 'scripts/sql.php';
$valid=false;
if(isset($_COOKIE['SLEUser'])){
if(isset($_COOKIE['SLESession'])){
	$QRY = "SELECT * FROM admins WHERE PID='" . $_COOKIE['SLEUser'] . "'";
	$results = mysqli_query($con,$QRY);
	while($row = mysqli_fetch_array($results)){
		if($row['Session']==$_COOKIE['SLESession']){
			$valid=true;
		}
	}
	if($valid){
		$logIn="admin";
	}else{
		echo "<script>alert('Login expired Error:" . $row['Session'] . "," . $_COOKIE['SLESession'] . "');</script>";
	}
}elseif(isset($_COOKIE['SLEteacher'])){
		//teacher?
		$QRY = "SELECT * FROM teachers WHERE PID=" . $_COOKIE['SLEUser'];
		$results = mysqli_query($con,$QRY);
		while($row = mysqli_fetch_array($results)){
			$valid=true;
		}
		if($valid){
			$logIn = "teacher";
		}
}else{
//student?
$QRY = "SELECT * FROM pupils WHERE PID=" . $_COOKIE['SLEUser'];
	$results = mysqli_query($con,$QRY);
	while($row = mysqli_fetch_array($results)){
		$valid=true;
	}
	if($valid){
		$logIn = "student";
	}else{
		echo '<script>prompt("Login expired Error:", "' . $QRY  . '");</script>';
	}
}}
if(!(isset($_GET['redirect']))&& $valid == false){
//check that redirect=1 so that we don't get caught in an infinite loop
echo "<script>window.location.replace('index.php?redirect=1');</script>";
$logIn="false";
}else if($valid == false){
$logIn="false";
}
?>
</head>
<body>
<center><p id="titlename">Student lesson evaluations</p></center>
<div style="position:absolute;left:0px;top:0px;">
<a href="index.php"><img src="images/lion.png"width="250px" /></a>
</div>
<div style="position:absolute;right:0px;top:0px;">
<a href="index.php">
<img src="images/lion.png"width="250px" /></a>
</div>
