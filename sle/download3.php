<?php
//include the header for the site
include 'head.php';
//check if the user has logged in and has the priverleges to view this page
if(isset($logIn)){
if($logIn!="admin"){
//if not don't send the rest of the page 
echo "<h1>Error:7 # you can not access this page</h1><p>Please <a href='index.php'>click here</a> to go back to where you should be.";
exit(404);
}}
//check that errors wont occure because the user has incorrectly navigated to this page without sending the correct post data
if(!isset($_POST['limitToGo'])){
echo "<h1>Error:#Incorrect navigation</h1><p>This is probably our fault but try the same thing again, if this message occurse again then tell us what you did and we will try to correct the problem.</p>";
exit(501);
}
//the page should be posted with the limiting selection, the limiting summary and any filters the user has selected
$limit=$_POST['limitToGo'];
//GENERTAING QUESTIONS ARRAY
//include a connection to the database
include 'scripts/sql.php';
//request all of the questions
$QRY = "SELECT * FROM questions";
//retrives the results of the SQL query
$results = mysqli_query($con,$QRY);
//inishiate a counter to count through the response from the database
$i=0;
//define a string that will become an array in the javascript later on in the page
//the first string contains a CSV of the questions in plain text
$askedQuestionsArray = "";
//the second string contains a CSV of the questionsID's for reference in the next stage of the form
$askedQuestionIDArray = "";
//retrive the rows of the response from the SQL query
while($row = mysqli_fetch_array($results)){
/*'questions' table format
_________________________________
PID		|PRIMARY KEY, INTEGER	|
Question|STRING					|
---------------------------------
*/
	//store each row of the reponse in an array for use later in producing a drop down of all the questions
	$Questions[$i] = $row;
	//if the first row then don't put a comma before the response
	if($i==0){
	//add the response to the strings
	$askedQuestionIDArray = '"' . $row['PID'] . '"';
	$askedQuestionsArray = $askedQuestionsArray . '"' . $row['Question'] . '"';
	}else{
	//otherwise add the response to the string with a comma preceeding the response
	$askedQuestionIDArray = $askedQuestionIDArray . ',"' . $row['PID'] . '"';
	$askedQuestionsArray = $askedQuestionsArray . ',"' . $row['Question'] . '"';
	}
	//increase the counter
	$i++;
}
//GENERATING ADD ROW HTML
//inishiate an array of the option available in the limiting selection is by class or by element
$lossLess = Array("Year","Subject","Teacher","Annum");
//inishiate a string containing the html row of the table allowing users to add another row
$addRowScript = "<tr id='addrow'><td colspan='4'><input type='button' value='+' onclick='addRow()'/><select id='rowType' onchange='addRowDropDownChanged()'>";
//if the limitng selection is entry or class then the following are options for rows
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
//inishiate the string to hold the questions drop down html
$QuestionsDropDown = "<select id='questionNumber'>";
//add the individual questions
if(isset($Questions)){
	//if there are questions
	for($n=0;$n<(count($Questions)-1);$n++){
		$QuestionsDropDown = $QuestionsDropDown . "<option value='" . $Questions[$n]['PID'] . "' >" . $Questions[$n]['Question'] . "</option>";
	}
	$QuestionsDropDown = $QuestionsDropDown . "</select>";
}
//this is the end of the main PHP the rest of the PHP on the page is intertwined in the HTML and javascript
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
	//geth the table object as a local varibale to make it easy to make ajustmants to
	var table = document.getElementById("tableOfTitles");
	//inishiate a string used to build the innerHTML of the table from the information in the elements array
	var strBuild="<tr><td>Order By</td><td>Column title</td><td>Description</td><td>Remove row</td></tr>";
	//cycle through each of the columns building its corraspondinf row of the table
	for(i=0;i<elements.length;i++){
		//the name box is an input box so that the text heading a column can be changed
		strBuild = strBuild + "<tr><td><input name='which' type='radio' value='" + i + "' /></td><td><input type='text' name='element" + i + "' id='element" + i + "' value='";
		strBuild = strBuild + elements[i].title;//the title column
		strBuild = strBuild + "' /><input type='hidden' name='typeOf" + i + "' value='";
		strBuild = strBuild + elements[i].type;//add the type for the server to see
		strBuild = strBuild + "' />";
		//include a description of what the column will be showing
		strBuild = strBuild + "</td><td><p>";
		strBuild = strBuild + elements[i].description;//the description column
		strBuild = strBuild + "</p></td><td>";
		//include the option to move this column,heading (row in this table) or deleat it
		strBuild = strBuild + "<input type='button' value='-' onclick='moveUp(" + i + ")' />";
		strBuild = strBuild + "<input type='button' value='Delete row' onclick='deleteRow(" + i + ")' />";
		strBuild = strBuild + "<input type='button' value='+' onclick='moveDown(" + i + ")' />";
		strBuild = strBuild + "</td></tr>";
	}
	//add a row so that the user is able to add and additional row
	strBuild = strBuild + addRowHTML;
	//put the newly created table body in the body of the table
	table.innerHTML = strBuild;
	//create an array to hold an array of event listeners
	var eventListeners = new Array();
	//create a set of listeners to update the name of the new column when it changes
	for(i=0;i<elements.length;i++){
		//take the objects and assign it to the local array 
		eventListeners[i] = document.getElementById('element' + i);
		//add a listener for when the text in the text boxes is changed
		eventListeners[i].addEventListener('change', function(){changeColumnHeader(i-1);}, false);
	}
	//create an event listener so that when the row->type is changed the extra form data is changed if needed. For example a list of questions needs to be diplayed if the user has asked to add a specific question
	var myImage = document.getElementById('rowType');
	//add the event listener to the local object
	myImage.addEventListener('change', function(){addRowDropDownChanged();}, false);
}
//function to remove a column at the users request
function deleteRow(rowId){
	//if the user has removed all of the collumns disable the ability to download the file (to be re-enabled when they add a column)
	if(elements.length==1){
		document.getElementById('submitButton').disabled=true;
	}
	//remove the row in the elements array
	elements.splice(rowId,1);
	//redraw the table with the element removed from the 'elements' array
	drawTable();
}
//move a row of the table up
function moveUp(rowId){
	//check if the element is already at the top
	if(rowId==elements.length-1){
		//the operation cannot be done
		alert('The element is already at the bottom!');	
	}else{
		//store the element in a temperary variable
		holder = elements[rowId];
		//more the element above to the element to be moved's spot
		elements[rowId] = elements[rowId + 1];
		//assign the element whom was to be moved to their new spot
		elements[rowId + 1] = holder;
		//redraw the table with the alterations
		drawTable();
	}
}
//move a row of the table down
function moveDown(rowId){
	//if the element is already at the bottom
	if(rowId==0){
		//the operation cannot be done
		alert('The element is already at the top!');
	}else{
		//stoe the element in a temperay variable
		holder = elements[rowId];
		//move the element bellow up
		elements[rowId] = elements[rowId - 1];
		//allow the current element to go down
		elements[rowId - 1] = holder;
		//redraw table with emendments
		drawTable();
	}
}
//function to change the text at the top of a column of the file to be outputted
function changeColumnHeader(column){
	//get the object that the new text has been written in and assign it to a local variable
	var columnTitle = document.getElementById('element' + column);
	//update the cell of the array to the new value
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
	//select the first dropbdown box as the first case 
	switch(dropDown.options[dropDown.selectedIndex].value){
	case "sumall":
		//use the which radiochecked function to find out which of the options (sum,average,SD) the user has selected
		//use this as the case for the next case statement
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
		//get the users choice of question from the second dropdown menu
		var questionChoice = document.getElementById('questionNumber');
		elements[index].title = questionChoice.options[questionChoice.selectedIndex].text;
		elements[index].type= (50 + parseInt(questionChoice.options[questionChoice.selectedIndex].value));
		elements[index].description = "Responce to the question: " + questionChoice.options[questionChoice.selectedIndex].text;
		break;
	case "qA":
		//add all the quetions
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
		//otherwise the choice is a selection from the lossless array
		elements[index].title = dropDown.options[dropDown.selectedIndex].text;
		elements[index].type = dropDown.options[dropDown.selectedIndex].value;
		elements[index].description = "The " + dropDown.options[dropDown.selectedIndex].text + " of the row in the CSV file";
	}//end case
	//redraw the table with the ammendments
	drawTable();
}
//function to find and return which of the radio buttons is checked on the summarise all of the questions option
function whichRadioChecked(){
	var title;
	//loop through each of the radio boxes looking for the users selection
	for(i=1;i<=3;i++){
		if(title = document.getElementById('r' + i ).checked){
		return i;
		}
	}
}
//function to be called when the 'add a new row' dropdown has changed
function addRowDropDownChanged(){
	//get the drop down object as a local variable for ease of editing
	var dropDown = document.getElementById('rowType');
	//get the objet of the element that we can use to draw HTML in. This element is bellow the drop down and we use it for adding a drop down of question and a set of radios for a summary type
	var formToDrawIn = document.getElementById('addRowExtraInfo');
	//use the dropdown's value as the case
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
//function used to check all information is in a for that can be sent to the server so that no errors are returned. Data validation is also carryed out on the server
function submitForVerification(){
	strBuild="";
	//put the elements array into a form that can be sent to the server
	for(i=0;i<elements.length;i++){
		//don't put a comma at the beggining but do put one in between each element
		if(i==0){
			strBuild = strBuild + elements[i].title;
		}else{
			strBuild = strBuild + "," + elements[i].title;
		}
	}
	var formToBeWrittenTo = document.getElementById('headings')
	formToBeWrittenTo.value = strBuild;
	strBuild="";
	for(i=0;i<elements.length;i++){
		//don't put a comma at the beggining but do put one in between each element
		if(i==0){
			strBuild = strBuild + elements[i].type;
		}else{
			strBuild = strBuild + "," + elements[i].type;
		}
	}
	formToBeWrittenTo = document.getElementById('columnsToGo')
	formToBeWrittenTo.value = strBuild;
	document.formSubmit.submit();
}
</script>
</head>
<body>
<p id="information">Information paragraph</p>
<table id="tableOfTitles" border="1"><tbody><tr><td>Order By</td><td>Column title</td><td>Description</td><td>Remove row</td></tr><tr id="addrow"><td colspan="4"><input type="button" value="+" onclick="addRow()"><select id="rowType" onchange="addRowDropDownChanged()"><option value="0">Year</option><option value="1">Subject</option><option value="2">Teacher</option><option value="sumall" id="sumall">Summarise all of the questions into one value</option><option value="qI" id="qI">Add an individual question</option><option value="qA" id="qA">Add all questions</option></select><form id="addRowExtraInfo"></form></td></tr></tbody></table>

<form id="formSubmit" name="formSubmit" method="POST" action="download4.php">
<input type="hidden" name="filter" value="<?php echo $_POST['filter']; ?>" />
<input type="hidden" id="columnsToGo" name="columns" value="" />
<input type="hidden" id="headings" name="headers" value="" />
<input type="hidden" name="orderBy" value="" />
<input type="hidden" name="limitToGo" value="<?php echo $_POST['limitToGo']; ?>"  />
<input type="hidden" name="summary" value="<?php echo $_POST['summary'];?>" />

<input id="submitButton" type="button" value="Download this" disabled="true" onclick="submitForVerification()" >
</form>
</body>
</html>
