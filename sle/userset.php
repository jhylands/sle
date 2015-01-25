<?php
//get the username and password from the post data
$password = password_hash($_POST['pass'],PASSWORD_BCRYPT);
$User=$_POST['user'];
//create a connection to the database
include 'scripts/sql.php';
mysqli_query($con,"INSERT INTO admins (User, Password) VALUES ('" . $User . "', '" . $password ."')");
mysqli_close($con);
?>