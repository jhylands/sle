<?php
include 'head.php';
if(isset($logIn)){
if($logIn!="admin"){
echo "<h1>Error:7 # you can not access this page</h1><p>Please <a href='index.php'>click here</a> to go back to where you should be.";
exit(404);
}}
//echo $_POST['listOfFilters'][0];
//the page should be posted with the limit choice, any filters
$limit=$_POST['limit'];
//$limit="entry";
//GENERTAING QUESTIONS ARRAY
//get a list of questions from the database
include 'scripts/sql.php';
//$Question = new Array();
$QRY = "SELECT * FROM questions";
$results = mysqli_query($con,$QRY);
$i=0;
$askedQuestionsArray = "";
$askedQuestionIDArray = "";
while($row = mysqli_fetch_array($results)){
/*'questions' table format
_________________________________
PID		|PRIMARY KEY, INTEGER	|
Question|STRING					|
---------------------------------
*/
//	$Questions[$i] = new Array();
	$Questions[$i] = $row;
	if($i==0){
	$askedQuestionIDArray = '"' . $row['PID'] . '"';
	$askedQuestionsArray = $askedQuestionsArray . '"' . $row['Question'] . '"';
	}else{
	$askedQuestionIDArray = $askedQuestionIDArray . ',"' . $row['PID'] . '"';
	$askedQuestionsArray = $askedQuestionsArray . ',"' . $row['Question'] . '"';
	}
	$i++;
}
//GENERATING ADD ROW HTML
$lossLess = Array("Year","Subject","Teacher","Annum");
$addRowScript = "<tr id='addrow'><td colspan='4'><input type='button' value='+' onclick='addRow()'/><select id='rowType' onchange='addRowDropDownChanged()'>";
//if the limiter is entry or class then the following are options for rows
if($limit=="0" || $limit=="1"){
//cycle through the lossLess array creating option in the drop down menu
	for($n=0;$n<(count($lossLess)-1);$n++){
		$addRowScript = $addRowScript . "<option value='" . $n . "' >" . $lossLess[$n] . "</option>";
	}
}
//add an option to have a summary of all of the questions
$addRowScript = $addRowScript . "<option value='sumall' id='sumall'>Summarise all of the questions into one value</option>";
//add an option to add an individual question
$addRowScript = $addRowScript . "<option value='qI' id='qI'>Add an individual question</option>";
//add an option to add all the questions
$addRowScript = $addRowScript . "<option value='qA' id='qA'>Add all questions</option>";
//add the end of the row
$addRowScript = $addRowScript . "</select><form id='addRowExtraInfo'></form></td></tr>";
//CREATE QUESTIONS DROPDOWN	
$QuestionsDropDown = "<select id='questionNumber'>";
//add the individual questions
if(isset($Questions)){
//if there are questions
for($n=0;$n<(count($Questions)-1);$n++){
	$QuestionsDropDown = $QuestionsDropDown . "<option value='" . $Questions[$n]['PID'] . "' >" . $Questions[$n]['Question'] . "</option>";
}
$QuestionsDropDown = $QuestionsDropDown . "</select>";
}
?>
<script>
//include array of the possible columns given that the limits are entry or class
var lossLess = Array("Year","Subject","Teacher","Annum");
//include an array of the questions that where asked
var questions = Array(<?php echo $askedQuestionsArray; ?>);
//include an array of those questions PID's for later reference
var questionID = Array(<?php echo $askedQuestionIDArray; ?>);
//elements array to contain all of the headers
var elements = new Array();
//variable to store the html code for adding a row script
var addRowHTML = "<?php echo $addRowScript; ?>";
//function to redraw the table when there is a change
function drawTable(){
var table = document.getElementById("tableOfTitles");
//a string used to build the innerHTML of the table from the information in the elements array
var strBuild="<tr><td>Order By</td><td>Column title</td><td>Description</td><td>Remove row</td></tr>";
for(i=0;i<elements.length;i++){
	strBuild = strBuild + "<tr><td><input name='which' type='radio' value='" + i + "' /></td><td><input type='text' name='element" + i + "' id='element" + i + "' value='";
	strBuild = strBuild + elements[i].title;//the title column
	strBuild = strBuild + "' /><input type='hidden' name='typeOf" + i + "' value='";
	strBuild = strBuild + elements[i].type;//add the type for the server to see
	strBuild = strBuild + "' />";
	strBuild = strBuild + "</td><td><p>";
	strBuild = strBuild + elements[i].description;//the description column
	strBuild = strBuild + "</p></td><td>";
	strBuild = strBuild + "<input type='button' value='-' onclick='moveUp(" + i + ")' />";
	strBuild = strBuild + "<input type='button' value='Delete row' onclick='deleteRow(" + i + ")' />";
	strBuild = strBuild + "<input type='button' value='+' onclick='moveDown(" + i + ")' />";
	strBuild = strBuild + "</td></tr>";
}
//add a row so that the user is able to add and additional row
strBuild = strBuild + addRowHTML;
//put the newly created table body in the body of the table
table.innerHTML = strBuild;
var eventListeners = new Array();
//create a set of listeners to update the name of the new column when it changes
for(i=0;i<elements.length;i++){
	eventListeners[i] = document.getElementById('element' + i);
	//not sure if the following line is allowed because the function in which they alert is in may be separate to the parent function which would mean that i was local outside of the alert function!!!!!!!!!
	//IT WORKS!!
	eventListeners[i].addEventListener('change', function(){changeColumnHeader(i-1);}, false);
}
//create an event listener so that when the row->type is changed the extra form data is changed if needed
var myImage = document.getElementById('rowType');
myImage.addEventListener('change', function(){addRowDropDownChanged();}, false);
}
//function to remove a column at the users request
function deleteRow(rowId){
//move all the rows above rowId in the array down one
if(elements.length==1){
	document.getElementById('submitButton').disabled=true;
}
elements.splice(rowId,1);
drawTable();
}
function moveUp(rowId){
	if(rowId==elements.length-1){
	//the operation cannot be done
		alert('The element is already at the bottom!');	
	}else{
		holder = elements[rowId];
		elements[rowId] = elements[rowId + 1];
		elements[rowId + 1] = holder;
		drawTable();
	}
}
function moveDown(rowId){
	if(rowId==0){
	//the operation cannot be done
		alert('The element is already at the top!');
	}else{
		holder = elements[rowId];
		elements[rowId] = elements[rowId - 1];
		elements[rowId - 1] = holder;
		drawTable();
	}
}
function changeColumnHeader(column){
var columnTitle = document.getElementById('element' + column);
elements[column].title = columnTitle.value;
}
//add row to the table
function addRow(){
//allow the user to now request to download the CSV as it now has more than zero rows.
document.getElementById('submitButton').disabled=false;
//count the number of elements already in the array so that we know what index to add the next one to
var index = elements.length;
//get the type of row
var dropDown = document.getElementById('rowType');
elements[index] = new Object();
/*
type codes
XY
X is the main type from the first drop down
Y is the next drop down if one exists
e.g
Add an individual question->Students who disrupt the learning are dealt with effectively
51
e.g
Summarise all of the questions into one value->Take the average
41
*/
switch(dropDown.options[dropDown.selectedIndex].value){
case "sumall":
	//use the which radiochecked function
	switch(whichRadioChecked()){
		case 1:
		elements[index].title = "Average answer to question";
		elements[index].type= 41;
		elements[index].description = "All the questions for this row summarised by taking an average.";
		break;
		case 2:
		elements[index].title = "Total of answers";
		elements[index].type= 42;
		elements[index].description = "All the questions for this row summarised by adding their respective value.";
		break;
		case 3:
		elements[index].title = "Standard deviation of questions";
		elements[index].type= 43;
		elements[index].description = "All the questions for this row summarised by calculating the average distance from the average responce.";
		break;	
		}
	break;
case "qI":
	var questionChoice = document.getElementById('questionNumber');
	elements[index].title = questionChoice.options[questionChoice.selectedIndex].text;
	elements[index].type= (50 + parseInt(questionChoice.options[questionChoice.selectedIndex].value));
	elements[index].description = "Responce to the question: " + questionChoice.options[questionChoice.selectedIndex].text;
	break;
case "qA":
	elements[index].title = questions[0];
	elements[index].type= (50 + parseInt(questionID[0]));
	elements[index].description = "Responce to the question: " + questions[0];
	for(i=1;i<questions.length;i++){
		index = elements.length;
		elements[index] = new Object();
		elements[index].title = questions[i];
		elements[index].type= (50 + parseInt(questionID[i]));
		elements[index].description = "Responce to the question: " + questions[i];
	}
	break;
default:
	elements[index].title = dropDown.options[dropDown.selectedIndex].text;
	elements[index].type = dropDown.options[dropDown.selectedIndex].value;
	elements[index].description = "The " + dropDown.options[dropDown.selectedIndex].text + " of the row in the CSV file";
}//end case
drawTable();
}
//function to find and return which of the radio buttons is checked on the summarise all of the questions option
function whichRadioChecked(){
var title;
for(i=1;i<=3;i++){
	if(title = document.getElementById('r' + i ).checked){
	return i;
	}
}
}
//if the add row drop down has changed then we might need to add or remove elements
function addRowDropDownChanged(){
var dropDown = document.getElementById('rowType');
var formToDrawIn = document.getElementById('addRowExtraInfo');
switch(dropDown.options[dropDown.selectedIndex].value){
case "sumall":
	//display the summation options
	formToDrawIn.innerHTML = "<input id='r1' type='radio' name='summarisation' value='mean'>Take the average of the questions<input id='r2' type='radio' name='summarisation' value='sum'>Add up all of the question numbers<input id='r3' type='radio' name='summarisation' value='sd'>Take the average distance the values are from the mean";
	break;
case "qI":
	//display the question options
	formToDrawIn.innerHTML = "<?php echo $QuestionsDropDown; ?>";
	break;
default:
	//otherwise no extra information is required
	formToDrawIn.innerHTML = "";
	break;
}
}
function submitForVerification(){
document.formSubmit.submit();
}
</script>
</head>
<body>
<p id="information">Information paragraph</p>
<table id="tableOfTitles" border="1"><tbody><tr><td>Order By</td><td>Column title</td><td>Description</td><td>Remove row</td></tr><tr id="addrow"><td colspan="4"><input type="button" value="+" onclick="addRow()"><select id="rowType" onchange="addRowDropDownChanged()"><option value="0">Year</option><option value="1">Subject</option><option value="2">Teacher</option><option value="sumall" id="sumall">Summarise all of the questions into one value</option><option value="qI" id="qI">Add an individual question</option><option value="qA" id="qA">Add all questions</option></select><form id="addRowExtraInfo"></form></td></tr></tbody></table>

<form id="formSubmit" name="formSubmit" method="POST" action="download4.php">
<input type="hidden" name="filter" value="<?php echo $_POST['filter']; ?>" />
<input type="hidden" name="columns" value="" />
<input type="hidden" name="orderBy" value="" />
<input type="hidden" name="limit" value="<?php echo $_POST['limit']; ?>"  />

<input id="submitButton" type="button" value="Download this" disabled="true" onclick="submitForVerification()" >
</form>
</body>
</html>
