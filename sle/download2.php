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
?>
<script>
//change the second select option based on first selection
var teacherList = new Array<?php echo $teacherList; ?>;
var subjectList = new Array<?php echo $subjectList; ?>;
var classList = new Array<?php echo $classList; ?>;
var yearList = new Array('All years','Lower school (years:7,8,9,10,11)','KS3 (years:7,8,9)','KS4 (years:10,11)','Sixth form (years:12,13)'<?php for($i=7;$i<=13;$i++){echo ",'Year " . $i . "'";}?>);
var annumList = new Array<?php echo $dateList; ?>;
function mainChange(){
var mainDrop = document.getElementById('mainDropDown');
switch(mainDrop.options[mainDrop.selectedIndex].value){
case "ClassID":
	classList = bubbleSort(classList);
	createOption(classList);
	break;
case "year":
	createOption(yearList);
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
//
function createOption(arrFilling){
var optionToChange = document.getElementById('secondOptionBlock');
var strBuild = "<select id='secondOption'>"
for(i=0;i<arrFilling.length;i++){
	strBuild = strBuild + "<option value='" + i + "'>" + arrFilling[i] + "</option>";
}
strBuild = strBuild + "</select>";
optionToChange.innerHTML=strBuild;
//optionToChange.addEventListener('change', function(){addFilter();}, false);
}
//add the selected filter to the list box
function addFilter(){
//find out whether the user requests that the filter must be or must not be
var orNot = whichRadioChecked();
//find which row of the main table the filter applies to
var mainDrop = document.getElementById('mainDropDown');
//find which option in that row we are filtering
var filter = document.getElementById('secondOption');
//generate text to be displayed to the user
if(orNot==1){
var SQL = "'" + mainDrop.options[mainDrop.selectedIndex].value + "' = " +  filter.options[filter.selectedIndex].value;
var textShown = "Must be(" + mainDrop.options[mainDrop.selectedIndex].text + "->" + filter.options[filter.selectedIndex].text + ")";
}else{
var SQL = "NOT '" + mainDrop.options[mainDrop.selectedIndex].value + "' = " +  filter.options[filter.selectedIndex].value;
var textShown = "Not(" + mainDrop.options[mainDrop.selectedIndex].text + "->" + filter.options[filter.selectedIndex].text + ")";
}
//add row
var newRow = new Option(textShown,SQL);
//create a new row in the listbox
var listBox = document.getElementById('listOfFilters'); 
listBox.options[listBox.length]=newRow;
}
function whichRadioChecked(){
var title;
for(i=0;i<=1;i++){
	if(title = document.getElementById('r' + i ).checked){
	return i;
	}
}
}
function removeSelected(){
	var SS1 = document.getElementById('listOfFilters');
    for (i=SS1.options.length - 1; i>=0; i--)
    {
        if (SS1.options[i].selected == true)
        {
            SS1.options[i]=null;
        }
    }
}
//function to turn the listbox into a hidden input in the form
function submitIt(){
var listBox = document.getElementById('listOfFilters');
var strBuild = "";
for(i=0;i<listBox.options.length;i++){
	if(i==0){
	strBuild = listBox.options[0].value;
	}else{
	strBuild = strBuild + " AND " + listBox.options[i].value;
	}
}
document.toBeSubmitted.submit();
}
</script>

<center>
<table>
<tr><td>
Add filter, by:<br />
<input name="orNot" type="radio" id="r1" value="Must be:">Must be:<br />
<input name="orNot" id="r0" type="radio" value="Must not be:" checked="true"/>must not be</form><br /> 
<select id="mainDropDown" onchange="mainChange()"><option>Select a type</option><option value="ClassID">class</option><option value="year">Year</option><option value="TeacherID">Teacher</option><option value="SubjectID">Subject</option><option value="Annum">Annum</option></select><br />
<div id="secondOptionBlock"></select></div><br /><input type='button' value="Add filter" onclick="addFilter();" /></td>
<td><select id="listOfFilters" name="list" style="width:500px;height:300px;" multiple="multiple"></select>
<form id="toBeSubmitted" name="toBeSubmitted" Method="POST" action="download3.php">
<input type="button" onclick="submitIt()" value="Next"/>
<input type="hidden" name="limitToGo" value="<?php echo $_POST['limitToGo'];?>" />
<input type="hidden" name="summary" value="<?php echo $_POST['summary'];?>" />
<input type="hidden" name="filter" value="" />
</form><br /><input type="button" value="remove" onclick="removeSelected()" /></td></tr></table>


</center>
