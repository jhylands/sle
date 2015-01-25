<?php
include 'head.php';
if(isset($logIn)){
if($logIn!="admin"){
echo "<h1>Error:7 # you can not access this page</h1><p>Please <a href='index.php'>click here</a> to go back to where you should be.";
exit(404);
}}
function getListForJavascript($table,$column){
//include a connection to the database
include 'scripts/sql.php';
//get each of what the user wants from the db (that should be fine
//put the summarised questions in an array [$questionID][$series]
//compress the first dimention of the array removing blank rows [$x][$series] = $y
//update the image using ajax request
//get list of teachers
$QRY = "SELECT * FROM " . $table;
$results = mysqli_query($con,$QRY);
$firstRound = true;
$accumulator = "('";
while($row = mysqli_fetch_array($results)){
	if($firstRound){
	$accumulator = $accumulator . $row[$column] . "'";
	$firstRound=false;
	}else{
	$accumulator = $accumulator . ",'" . $row[$column] . "'";
	}
}
mysqli_close($con);
return $accumulator . ")";
}
$teacherList = getListForJavascript("teachers","Name");
$subjectList = getListForJavascript("subjects","Subject");
$classList = getListForJavascript("class","Code");
//get list of years
include 'scripts/sql.php';
$QRY = "SELECT * FROM main ORDER BY 'annum' ASC LIMIT 1";
$results = mysqli_query($con,$QRY);
while($row = mysqli_fetch_array($results)){
	$firstYear = $row['Annum'];
	break;
}
$dateList = "('" . $firstYear . "'";
for($i=$firstYear;$i<=date("Y");$i++){
	$dateList = $dateList . ",'" . $i . "'";
}
$dateList = $dateList . ")";

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
$addRowScript = "<tr id='addrow'><td colspan='4'><form id='addRowExtraInfo'>" . $QuestionsDropDown . "</form><input type='button' value='Add Question' onclick='addRow()'/></select></td></tr>";

//this is the end of the main PHP the rest of the PHP on the page is intertwined in the HTML and javascript

?>
<script type="text/javascript" src="scripts/jscolor.js"></script>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
<script>
var teacherList = new Array<?php echo $teacherList; ?>;
var subjectList = new Array<?php echo $subjectList; ?>;
var classList = new Array<?php echo $classList; ?>;
var yearList = new Array('All years','Lower school (years:7,8,9,10,11)','KS3 (years:7,8,9)','KS4 (years:10,11)','Sixth form (years:12,13)'<?php for($i=7;$i<=13;$i++){echo ",'Year " . $i . "'";}?>);
var annumList = new Array<?php echo $dateList; ?>;
var elementsX = new Array();
var elementsS = new Array();
var addRowHTML = "<?php echo $addRowScript; ?>";
//add row to the table
function addRow(){
	//count the number of elements already in the array so that we know what index to add the next one to
	var index = elementsX.length;
	elementsX[index] = new Object();
	var questionChoice = document.getElementById('questionNumber');
	elementsX[index].value = questionChoice.options[questionChoice.selectedIndex].value;
	elementsX[index].text = questionChoice.options[questionChoice.selectedIndex].text;
	//redraw the table with the ammendments
	drawTableX();
}
function drawTableX(){
        //geth the table object as a local varibale to make it easy to make ajustmants to
        var table = document.getElementById("tableOfX");
        //inishiate a string used to build the innerHTML of the table from the information in the elements array
        var strBuild="<tr><td>Bar name</td><td>Question</td><td>Remove bar</td></tr>";
        //cycle through each of the columns building its corraspondinf row of the table
        for(i=0;i<elementsX.length;i++){
                //the name box is an input box so that the text heading a column can be changed
                strBuild = strBuild + "<tr><td><p name='element" + i + "' id='element" + i + "'>Q";
                strBuild = strBuild + elementsX[i].value;
                strBuild = strBuild + "</p>";
                strBuild = strBuild + "</td><td><p>";
                strBuild = strBuild + elementsX[i].text;
                strBuild = strBuild + "</p></td><td>";
                //include the option to move this column,heading (row in this table) or deleat it
                strBuild = strBuild + "<input type='button' value='Remove bar' onclick='deleteRowX(" + i + ")' />";
                strBuild = strBuild + "</td></tr>";
        }
        strBuild = strBuild + addRowHTML;
        table.innerHTML = strBuild;
}
function mainChange(){
        var mainDrop = document.getElementById('mainDropDown');
        switch(mainDrop.options[mainDrop.selectedIndex].value){
        case "ClassID":
                classList = bubbleSort(classList);
                createOption(classList);
                break;
        case "TeacherID":
                createOption(teacherList);
                break;
        case "SubjectID":
                createOption(subjectList);
                break;
        case "Annum":
                createOption(annumList);
                break;
        }
}
function createOption(arrFilling){
var optionToChange = document.getElementById('secondOptionBlock');
var strBuild = "<select id='secondOption'>"
for(i=0;i<arrFilling.length;i++){
	strBuild = strBuild + "<option value='" + (i+1) + "'>" + arrFilling[i] + "</option>";
}
strBuild = strBuild + "</select>";
optionToChange.innerHTML=strBuild;
//optionToChange.addEventListener('change', function(){addFilter();}, false);
}
function bubbleSort(selList)
{
    var Text='';
    for (x=0; x < selList.length - 1; x++)
    {
        for (y=x + 1; y < selList.length; y++)
        {
            if (selList[x] > selList[y])
            {
                // Swap rows
                Text=selList[x];
                selList[x]=selList[y];
                selList[y]=Text;
            }
        }
    }
	return selList;
}
function addSeries(){
	//count the number of elements already in the array so that we know what index to add the next one to
	var index = elementsS.length;
	elementsS[index] = new Object();
	var type = document.getElementById('mainDropDown');
	elementsS[index].type = type.options[type.selectedIndex].value;
	elementsS[index].typetext = type.options[type.selectedIndex].text;
	var value = document.getElementById('secondOption');
	elementsS[index].value = value.options[value.selectedIndex].value;
	elementsS[index].valuetext = value.options[value.selectedIndex].text;
	elementsS[index].color = document.getElementById('seriesColor').value
	drawTableS();
	//redraw the table with the ammendments
}
function drawTableS(){
        //geth the table object as a local varibale to make it easy to make ajustmants to
        var table = document.getElementById("tableOfS");
        //inishiate a string used to build the innerHTML of the table from the information in the elements array
        var strBuild="<tr><td></td><td>Series</td><td>Remove bar</td></tr>";
        //cycle through each of the columns building its corraspondinf row of the table
        for(i=0;i<elementsS.length;i++){
                //the name box is an input box so that the text heading a column can be changed
                strBuild = strBuild + "<tr><td style='width:100px;background-color:" + elementsS[i].color + "'></td><td><p name='elementS" + i + "' id='elementS" + i + "'>";
                strBuild = strBuild + elementsS[i].typetext;
                strBuild = strBuild +  "->";
                strBuild = strBuild + elementsS[i].valuetext;
                strBuild = strBuild + "</p></td><td>";
                //include the option to move this column,heading (row in this table) or deleat it
                strBuild = strBuild + "<input type='button' value='Remove bar' onclick='deleteRowS(" + i + ")' />";
                strBuild = strBuild + "</td></tr>";
        }
        table.innerHTML = strBuild;
}
function deleteRowX(rowId){
        //remove the row in the elements array
        elementsX.splice(rowId,1);
        //redraw the table with the element removed from the 'elements' array
        drawTableX();
}
function deleteRowS(rowId){
        //remove the row in the elements array
        elementsS.splice(rowId,1);
        //redraw the table with the element removed from the 'elements' array
        drawTableS();
}
function postMan(){
	var strType = joinObj(elementsS,'type');
	var strValue = joinObj(elementsS,'value');
	var strColor = joinObj(elementsS,'color');
	var strXaxis = joinObj(elementsX,'value');
	var errCol = document.getElementById('ErrCol').value;
	var strYaxis = document.getElementById('summary').value;
	alert(strValue);
	$.post("scripts/graphcreator.php",{type:strType,value:strValue,xaxis:strXaxis,yaxis:strYaxis,color:strColor,ErrCol:errCol},function (data,status){alert("data: " + data + "\nStatus: " + status);document.getElementById('graph').src="temp.png?nothing=" + Math.random();});
	//$.post("test.php",{ErrCol:errCol},function(data,status){alert("data: " + data + "\nStatus: " + status);});
	//$.post("test.php",{name:strYaxis},function(data,status){alert("Data: " + data + "\nStatus: " + status);});
	alert('fin');
}
function joinObj(a, attr){
	var out = [];
	for(var i=0;i<a.length;i++){
		out.push(a[i][attr]);
	}
	return out.join(",");
}
</script>
<input type="button" value="show" onclick="postMan()" />
<table width="100%">
<tr>
	<td rowspan="4" style="vertical-align:text-top;" width="50%"><h2>Preview</h2><img id="graph" src="temp.png" style="width:100%;" /></td>
	<td id="seriesBox" rowspan="2" style="vertical-align:text-top;" width="25%"><h1>Series:</h1>
<table id="tableOfS" width="100%" border="1"><tr><td>Colour</td><td>Series</td><td>Remove series</td></tr></table>
	</td>
	<td><h2>Error bar colours</h2>
	<label for="ErrCol">Select a color for the error bars:</label><input id="ErrCol" name="ErrCol" val="FFFFFF" class="color" /></td>
</tr>
<tr>
	<td><p>Y axis label:<select id="summary"><option value="0">Values added up</option><option value="1">Average answer</option></select></td>
</tr><tr>
	<td id="questionBox"  style="vertical-align:text-top;"><h1>X-axis</h1><p>Please select the questions you wish to display on the x-axis</p><br />
	<table id="tableOfX" border="1"><tbody><tr><td>Bar name</td><td>Quesion</td><td>Remove row</td></tr><tr id="addrow"><td colspan="4"><form id="addRowExtraInfoX"><?php echo $QuestionsDropDown; ?></form><input type="button" value="Add Question" onclick="addRow()"></td></tr></tbody></table>
	</td>
	<td style="vertical-align:text-top;"><h2>Add series</h2> 
	<select id="mainDropDown" onchange="mainChange()"><option>Select a type</option><option value="ClassID">class</option><option value="TeacherID">Teacher</option><option value="SubjectID">Subject</option><option value="Annum">Annum</option></select><br />
	<div id="secondOptionBlock"></select></div><br />
	<label for="seriesColor">Select the bar color for this series:</label><input id="seriesColor" name="seriesColor" value="9999FF" class="color" /><br />
	<input type='button' value="Add series" onclick="addSeries();" /></td>
	</td>
</tr>
</table>
<?php
include 'foot.php';
?>
