<?php
include 'head.php';
//if(isset($logIn)){
if($logIn=="student"){
//if the user has logged in then navigate them to the subject selection page "main.php"
echo "<script>window.location.replace('main.php');</script>";
}elseif($logIn=="admin"){
//if the admin has logged in then navigate them to the main page "admain.php"
echo "<script>window.location.replace('admain.php');</script>";
}elseif($logIn=="teacher"){
//if the admin has logged in then navigate them to the main page "admain.php"
echo "<script>window.location.replace('tmain.php');</script>";
}
//}
?>
<div id="entry">

<form action="logon.php" method="post">
<?php
if(isset($_GET['try'])){
echo '<input type="text" style="visibility:hidden;" name="try" value="' . $_GET['try'] . '" />';
echo "<p id='incorrect'>Username or key incorrect!</p>";
}
?>
<table border="0">
<tr>
<td><b>User name: </b></td><td><input id="user" tabindex="1" name="user" type="text" /></td></tr>
<tr><td><b>Key: </b></td><td><input name="pass" tabindex="2" type="password" /></td></tr>
<tr><td></td><td><input tabindex="3" type="Submit" value="Enter" />
</table>
</form>
</div>
<?php
include 'foot.php';
?>
