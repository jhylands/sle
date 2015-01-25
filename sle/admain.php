<?php
include 'head.php';
if(isset($logIn)){
if($logIn!="admin"){
echo "<h1>Error:7 # you can not access this page</h1><p>Please <a href='index.php'>click here</a> to go back to where you should be.";
exit(404);
}}
?>
<div id="logout">
<a href="logout.php">Logout</a>
</div>
<center>
<li><a href="upload.php">Uploads.php</a></li>
<li><a href="download1.php">Downloads</a></li>
<li><a href="graph1.php">Create a graph of the information</a></li>
<li><a href="studentlogins.php">View student logins</a></li>
<li><a href="setup.php">Select questions</a></li>
</center>
