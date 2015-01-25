<?php
error_reporting(E_ALL);
ini_set("display_errors",1);
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
include 'scripts/sql.php';
// Create main table
$sql="CREATE TABLE main(EntryID INT AUTO_INCREMENT,ClassID INT,SubjectID INT, TeacherID INT, QuestionsID INT, Response INT ,PupilID INT,Annum INT,PRIMARY KEY (EntryID))";
// Execute query
if (mysqli_query($con,$sql))
  {
  echo "Table persons created successfully<br/>";
  }
else
  {
  echo "Error creating table: " . mysqli_error($con);
  }
// Create class table
$sql="CREATE TABLE class(PID INT AUTO_INCREMENT,Code TEXT,Questions TEXT COMMENT 'question id in csv',PRIMARY KEY (PID))";
// Execute query
if (mysqli_query($con,$sql))
  {
  echo "Table persons created successfully<br/>";
  }
else
  {
  echo "Error creating table: " . mysqli_error($con);
  }
// Create subjects table
$sql="CREATE TABLE subjects(PID INT AUTO_INCREMENT,Subject TEXT,PRIMARY KEY (PID))";
// Execute query
if (mysqli_query($con,$sql))
  {
  echo "Table persons created successfully<br/>";
  }
else
  {
  echo "Error creating table: " . mysqli_error($con);
  }
// Create teachers table
$sql="CREATE TABLE teachers(PID INT AUTO_INCREMENT,Name TEXT,User TEXT, Password CHAR(128), PRIMARY KEY (PID))";
// Execute query
if (mysqli_query($con,$sql))
  {
  echo "Table persons created successfully<br/>";
  }
else
  {
  echo "Error creating table: " . mysqli_error($con);
  }
// Create questions table
$sql="CREATE TABLE questions(PID INT AUTO_INCREMENT,Question TEXT,PRIMARY KEY (PID))";
// Execute query
if (mysqli_query($con,$sql))
  {
  echo "Table persons created successfully<br/>";
  mysqli_query($con,"INSERT INTO questions (Question)
	VALUES ('The teacher has a good relationship with the class?')");
	mysqli_query($con,"INSERT INTO questions (Question)
	VALUES ('Students who disrupt the learning are dealt with effectivly?')");
	mysqli_query($con,"INSERT INTO questions (Question)
	VALUES ('Lessons begin promptly?')");
	mysqli_query($con,"INSERT INTO questions (Question)
	VALUES ('There is enough varaity in lessons?')");
	mysqli_query($con,"INSERT INTO questions (Question)
	VALUES ('Homework tasks improve understanding?')");
	mysqli_query($con,"INSERT INTO questions (Question)
	VALUES ('Work is marked regularly?')");
	mysqli_query($con,"INSERT INTO questions (Question)
	VALUES ('I often recive comments on how to improve my work orally or in writting?')");
	mysqli_query($con,"INSERT INTO questions (Question)
	VALUES ('The teacher cators well for students of my ability?')");
	mysqli_query($con,"INSERT INTO questions (Question)
	VALUES ('The lessons are interesting?')");
	mysqli_query($con,"INSERT INTO questions (Question)
	VALUES ('My thinking is often streched and challanged in lessons?')");
  }
else
  {
  echo "Error creating table: " . mysqli_error($con);
  }
// Create comments table
$sql="CREATE TABLE comments(PID INT AUTO_INCREMENT,Comment TEXT,PRIMARY KEY (PID))";
// Execute query
if (mysqli_query($con,$sql))
  {
	echo "Table persons created successfully<br/>";
	mysqli_query($con,"INSERT INTO comments (Comment)
	VALUES ('Strongly Disagree')");
	mysqli_query($con,"INSERT INTO comments (Comment)
	VALUES ('Disagree')");
	mysqli_query($con,"INSERT INTO comments (Comment)
	VALUES ('Agree')");
	mysqli_query($con,"INSERT INTO comments (Comment)
	VALUES ('Strongly Agree')");
  }
else
  {
  echo "Error creating table: " . mysqli_error($con);
  }
// Create pupils table
$sql="CREATE TABLE pupils(PID INT AUTO_INCREMENT, Code CHAR(8),Name TEXT,PRIMARY KEY (PID))";
// Execute query
if (mysqli_query($con,$sql))
  {
  echo "Table persons created successfully<br/>";
  }
else
  {
  echo "Error creating table pupils: " . mysqli_error($con);
  }
// Create admins table
$sql="CREATE TABLE admins(PID INT AUTO_INCREMENT,User TEXT, Password CHAR(128), Session TIME,PRIMARY KEY (PID))";
// Execute query
if (mysqli_query($con,$sql))
  {
  echo "Table persons created successfully<br/>";
  mysqli_query($con,"INSERT INTO admins (User,Password)
	VALUES ('root','" . hash("sha512" , "password") . "')");
  }
else
  {
  echo "Error creating table: " . mysqli_error($con);
  }

?>
<h1>Something</h1>
