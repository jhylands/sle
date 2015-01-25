<?php
// Create connection to database
$con=mysqli_connect("localhost","root","space(11)");
// Check connection
if (mysqli_connect_errno($con))
  {
  echo "Failed to connect to MySQL: " . mysqli_connect_error();
  }
// Create database
$sql="CREATE DATABASE sle";
if (mysqli_query($con,$sql))
  {
  echo "Database my_db created successfully";
  }
else
  {
  echo "Error creating database: " . mysqli_error($con);
  }
mysqli_close($con);

