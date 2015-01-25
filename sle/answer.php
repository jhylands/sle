<?php
if(!isset($_GET['class'])){
//if a class isn't passed then go back
echo "<h1>Error:6 # you can not access this page</h1><p>Please <a href='index.php'>click here</a> to go back to where you should be.";
exit(404);

}else{
//create document
include 'head.php';
include 'scripts/sql.php';
//start building table to hold questions
//echo information on how to answer the questions
echo "<p class='information'>Please answer all of the questions. You can move through quickly by using the 'TAB' key along with the arrow keys.</p>";
//echo table of answers head
echo "<div id='questions'><form name='theForm' action='addresults.php' method='POST' >";
echo "<table border='1'><tr><td class='tabletop' colspan='4'>Please answer all questions</td></tr>";
//get the list of questions relevant to this class
$QRY = "SELECT * FROM class WHERE PID =" . $_GET['class'];
$results = mysqli_query($con,$QRY);
while($row = mysqli_fetch_array($results)){
	$questions = explode(',',$row['Questions']);
}
//if no questions are set then report an error
if($questions[0]!=null){
//get the comments from the database
$i=0;
$QRY = "SELECT * FROM comments ORDER BY pid";
$results = mysqli_query($con,$QRY);
while($row = mysqli_fetch_array($results)){
	$comments[$i] = $row;
	if($i==0){
	$javaScriptComments = $row['PID'];
	}else{
	$javaScriptComments = $javaScriptComments . "," .	$row['PID'];
	}
	$i++;
}
//loop through the question id's and get the question text
$i=1;
foreach($questions as &$question){
	//get the question from the db that are still in date(ie date='') and are used for this users year group
	$QRY = "SELECT * FROM questions WHERE PID =" . $question;
	$results = mysqli_query($con,$QRY);
	while($row = mysqli_fetch_array($results)){
		if($i==1){
			$javaScriptQuestions = $row['PID'];
			$oneQuestion = True;
		}else{
			$javaScriptQuestions = $javaScriptQuestions . "," .	$row['PID'];
			$oneQuestion = False;
		}
		echo "<tr><td class='question' colspan='4'>Q". $i . ". " . $row['Question'] . "</td></tr>";
		echo "<tr>";
		$oneComment = False;
		foreach($comments as &$comment){
			$name=$row['PID'] . "," . $comment['PID'];
			echo "<td>" . $comment['Comment'] . "<input type='radio' id='Q" . $name . "' name='Q" . $row['PID'] ."' value=" . $comment['PID'] . " /></td>";
		}
		echo "</tr>";
	}
	$i++;
}
echo "</table>";
//echo submit button to submit the form
?>
<input type='button' onclick='validateMyForm();' value='submit'/><input type='text' name='Class' style='visibility:hidden;' value='<?php echo $_GET['class']; ?>' /></form></div>
<script>
var <?php
if($oneComment){
	echo "comments[0] = " . $javaScriptComments . ";";
}else{
	echo "comments = new Array(";
	echo $javaScriptComments;
	echo ");";
}?>

<?
if($oneQuestion){
echo "questions[0] = " . $javaScriptQuestions . ";";
}else{
	echo "var questions = new Array(";
	echo $javaScriptQuestions;
	echo ");";
}?>
function validateMyForm(){
	var allAnswered = true;
	for(i=0;i<questions.length;i++){
		var thisChecked = false;
		for(n=0;n<comments.length;n++){
			//alert(questions);
			//alert("Q" + questions[i] + "," + comments[n]);
			var element = document.getElementById("Q" + questions[i] + "," + comments[n]);
			if(element.checked){
				thisChecked=true;
			}
		}
		if(!thisChecked){
			allAnswered=false;
		}
	}
	if(allAnswered){
		document.theForm.submit();
	}else{
		alert("Not all the questions have been filled in!");
	}
}
</script>
<?php
}else{
echo "<script>alert('Error:X #Your teacher has not assigned a set of questions to this class yet!');window.location.replace('main.php');</script>";
}
include 'foot.php';
}
?>
