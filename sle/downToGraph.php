<?php
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
//GENERATING ADD ROW HTML
//inishiate a string containing the html row of the table allowing users to add another row
$addRowScript = "<tr id='addrow'><td colspan='4'><form id='addRowExtraInfo'>" . $QuestionsDropDown . "</form><input type='button' value='+' onclick='addRow()'/></select></td></tr>";

//this is the end of the main PHP the rest of the PHP on the page is intertwined in the HTML and javascript
?>
<script>
//include array of the possible columns given that the limits are entry or class
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
	var strBuild="<tr><td>Bar name</td><td>Question</td><td>Remove siries</td></tr>";
	//cycle through each of the columns building its corraspondinf row of the table
	for(i=0;i<elements.length;i++){
		//the name box is an input box so that the text heading a column can be changed
		strBuild = strBuild + "<tr><td><input type='text' name='element" + i + "' id='element" + i + "' value='";
		strBuild = strBuild + elements[i].title;//the title column
		strBuild = strBuild + "' /><input type='hidden' name='typeOf" + i + "' value='";
		strBuild = strBuild + elements[i].type;//add the type for the server to see
		strBuild = strBuild + "' />";
		//include a description of what the column will be showing
		strBuild = strBuild + "</td><td><p>";
		strBuild = strBuild + elements[i].description;//the description column
		strBuild = strBuild + "</p></td><td>";
		//include the option to move this column,heading (row in this table) or deleat it
		strBuild = strBuild + "<input type='button' value='Delete series' onclick='deleteRow(" + i + ")' />";
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
	//remove the row in the elements array
	elements.splice(rowId,1);
	//redraw the table with the element removed from the 'elements' array
	drawTable();
}
//add row to the table
function addRow(){
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
	//get the users choice of question from the second dropdown menu
	var questionChoice = document.getElementById('questionNumber');
	elements[index].title = "Q" + questionChoice.options[questionChoice.selectedIndex].value;
	elements[index].type= questionChoice.options[questionChoice.selectedIndex].value;
	elements[index].description = questionChoice.options[questionChoice.selectedIndex].text;
	//redraw the table with the ammendments
	drawTable();
}
</script>
</head>
<body>
<table id="tableOfTitles" border="1"><tbody><tr><td>Bar name</td><td>Quesion</td><td>Remove row</td></tr><tr id="addrow"><td colspan="4"><form id="addRowExtraInfo"><?php echo $QuestionsDropDown; ?></form><input type="button" value="+" onclick="addRow()"></td></tr></tbody></table>

</body>
</html>
