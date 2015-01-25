<?php 
include 'head.php';
if(isset($logIn)){
if($logIn!="teacher"){
echo $logIn;
exit(404);
}
}
//if an update is incomming
if(isset($_POST['newpass1'])){
	include 'scripts/sql.php';
	//check the validity of the request
	$valid=False;
	$QRY = "SELECT * FROM teachers WHERE PID=" . $_COOKIE['SLEUser'];
	$result = mysqli_query($con, $QRY);
	while($row = mysqli_fetch_array($result)){
		if($row['User']==$_POST['user'] && hash("sha512",$_POST['oldpass']) == $row['Password']){
			$valid=True;
		}
	}
	if($valid){
		//check that the new passwords match
		if($_POST['newpass1']==$_POST['newpass2']){
			$QRY = "UPDATE teachers SET Password='" . hash("sha512",$_POST['newpass1']) . "' WHERE PID=" . $_COOKIE['SLEUser'];
			//echo $QRY;
			mysqli_query($con,$QRY);
			echo "<h1>Password changed</h1>";
		}else{
			echo "<h2>Passwords did not match</h2>";
		}
	}else{
		echo "<h1>Password could not be changed</h1><p>Their was an error in you login details. This may be because you are trying to change a password of a user other than the one logged in. This could also be because your old password was incorrect.</p>";
	}
}
?>
<center>
<form method="POST" action="changepass.php">
<label for="user">Username:</label>
<input type="text" id="user" name="user" /><br/>
<label for="oldpass">Old password:</label>
<input type="password" id="oldpass" name="oldpass" /><br />
<label for="newpass1">New password</label>
<input type="password" id="newpass1" name="newpass1" /><br />
<label for="newpass2">Retype new password</label>
<input type="password" id="newpass2" name="newpass2" /><br />
<input type="submit" value="Submit" />
</form>
</center>
