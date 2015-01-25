<?php
include 'head.php';
if(isset($logIn)){
if($logIn!="teacher"){
echo "<h1>Error: 7 #Acces dennied</h1><p>You do not have acces to this page.</p>";
exit(404);
}
}else{
echo "<h1>no</h1>";
}
?>
<div id="logout">
<a href="logout.php">Log out</a>
</div>
<center>
<li>Graph</li>
<li>Tables</li>
<li><a href="changepass.php">Change password</a></li>
<center>
<?php
include 'foot.php';
?>

