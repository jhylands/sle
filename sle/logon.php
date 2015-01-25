<?php
include 'scripts/sql.php';
//set a hold for if a valid user has been detected
$valid = false;
//echo '<script>alert("' . $_POST['user'] . '");</script>';
//check if the user is a valid student
$QRY = "SELECT * FROM `pupils` WHERE `Name` = '" . $_POST['user'] . "'";
$results = mysqli_query($con,$QRY);
while($row = mysqli_fetch_array($results)){
	if($row['Code']==$_POST['pass']){
		$valid=true;
		setcookie("SLEUser", $row['PID'], time()+3600);
		echo "<script>window.location.replace('main.php');</script>";
	}else{echo '<script>alert("1");</script>';}
}
//if the user is not a valid student check if they are a valid admin
if(!($valid)){
	echo "1";
	$QRY = "SELECT * FROM `admins` WHERE `User` = '" . $_POST['user'] . "'";
	$results = mysqli_query($con,$QRY);
	while($row = mysqli_fetch_array($results)){
		//echo "<br />" . $row['Password'] ;
		//echo "<br />" . hash("sha512",$_POST['pass']);
		if($row['Password']==hash("sha512",$_POST['pass'])){
			$valid=true;
			$time = date("H:i:s");
			setcookie("SLEUser", $row['PID'], time()+3600);
			setcookie("SLESession", $time, time()+3600);
			//echo "UPDATE admins SET Session='" . $time . "' WHERE User='" . $_POST['user'] . "'";
			mysqli_query($con,"UPDATE admins SET Session='" . $time . "' WHERE User='" . $_POST['user'] . "'");
			echo "<script>window.location.replace('admain.php');</script>";
		}
	}
}
if(!($valid)){
	$QRY = "SELECT * FROM `teachers` WHERE `User` = '" . $_POST['user'] . "'";
	$results = mysqli_query($con,$QRY);
	while($row = mysqli_fetch_array($results)){
		if($row['Password']==hash("sha512",$_POST['pass'])){
			$valid=true;
			setcookie("SLEUser", $row['PID'], time()+3600);
			setcookie("SLEteacher", 1, time()+3600);
			echo "<script>window.location.replace('tmain.php');</script>";
		}else{echo '<script>alert("1");</script>';}
	}
}
if(!($valid)){
//It's not a valid login
//echo "<script>window.location.replace('index.php?redirect=1&try=1');</script>";
}
mysqli_close($con);
?>
<p>If your browser does not automatically redirect <a href="index.php?redirect=1">click here</a></p>
