<?php
include 'head.php';
if(isset($logIn)){
if($logIn!="admin"){
echo "<h1>Error:7 # you can not access this page</h1><p>Please <a href='index.php'>click here</a> to go back to where you should be.";
//stops the rest of the page from being shown
exit(404);
}}
?>
<script>
//an array of all of the descriptions of the limiting factors
var arrDescriptions = new Array("This option will output a CSV where each row is from a specific pupil, about a specific class. You will be able to change the columns later in this form.","This option will output a CSV where each row is the summary from a specific class. The question values will be a summary of all of the students in that class who answered the questions.","This option will output a CSV where each row is a year group.","This option will output a CSV where each row is a subject.","This option will output a CSV where each row is from a teacher.","This option will summarise the question from the whole school for comparison against previous years.");
//declare a global variable allowing the correct descriptions to be shown
var foci=0;
//declare a global variable allowing the correct visuals of the limiting slection summary
var summaryNo=0;
//function to set the description to coraspond with what the user has selected as their limiting selection
function focus(){
//get the description box element and store it to a local variable so it can be edited more easiliy
var informationBox = document.getElementById('limitDescription');
//set the innerHTML (what is displayed inside the box) to a preset description of the limiting selection that is stored in the arrDescription array
informationBox.innerHTML = arrDescriptions[foci];
}
//function to change the limiting selection or set it in the first place
function changeFocus(newFoci){
//unformat the last limiting selection so that only one limiting selection is shown
//first get the object to unformat as a lcoal variable 
var visualChange = document.getElementById('limit' + foci);
//set the background back to white
visualChange.style.backgroundColor = "white";
//set the text colour back to black
visualChange.style.color = "black";
//reassign the global variable that holds the limiting selection with the new limiting selection value (the one that has just been clicked on)
foci = newFoci;
//get the object that refers to the element in the form that will be submitted sending data to the next stage of the download form.
//store this to a local variable and update its value with the value of the limiting selection just selected
var limitToSend = document.getElementById('limitToGo1');
limitToSend.value=foci;
//do the same with the other form element (the first refures to the form that sends data to another page, this one refurse to a form that downloads all of the data)
var limitToSend = document.getElementById('limitToGo2');
limitToSend.value=foci;
//change the visuals of the element just selected so that the use can see that the program has picked up their selection
//get the object corisponding to the element the user just clicked on 
visualChange = document.getElementById('limit' + foci);
//change the colour of the backgroud to blue
visualChange.style.backgroundColor = "blue";
//change the colour of the text to yellow
visualChange.style.color = "yellow";
//now that we  know the user has selected a limiting selection at least once we can allow the user to go onto another stage and either select more option or download all the data
//the following two lines allow the user to click on the corisponding buttons, they were not able to before as it would have caused an error on the next page as the limiting selection value would be null
document.getElementById('next').disabled = false;
document.getElementById('download').disabled = false;
//change the text in the description to corispond with the updated limiting selection allowing the user to see more information on what they have just selected
focus();
//if the limiting selection is not element then a limiting selection summary type will be required
var summaryTable = document.getElementById('limitingSummary');
if(foci!=0){
	summaryTable.style.visibility = 'visible';
}else{
	summaryTable.style.visibility = 'hidden';
}
}
//function to allow the user to change the summary type of their limiting selection 
function changeFoci(newFoci){
//get the object corresponding to the last summary that was selected store it to a local variable
var visualChange = document.getElementById('sum' + summaryNo);
//change the visuals of the old element
visualChange.style.backgroundColor = "white";
visualChange.style.color = "black";
//change the global variable corisponding to the summary
summaryNo = newFoci;
//get the element corrispnding to the variable in the form 
var limitToSend = document.getElementById('summary1');
//update its value
limitToSend.value=foci;
//do the same for the other form
var limitToSend = document.getElementById('summary2');
limitToSend.value=summaryNo;
//get the element corisponding to the summary method that has just been selected and store the object to a local variable
visualChange = document.getElementById('sum' + summaryNo);
//change the visuals of the element
visualChange.style.backgroundColor = "blue";
visualChange.style.color = "yellow";
}
</script>
<center><table width="500px">
<tr><td class="dow1tdl"><b>Limiting selection</b></td><td id="summaryType" rowspan="7">
<table id="limitingSummary" style="padding-bottom:90px;height:130px;">
<tr><td><b>Summation for limiting selection</b></td></tr>
<tr><td id="sum0" onclick="changeFoci(0)"><li>Summation</li></td></tr>
<tr><td id="sum1" onclick="changeFoci(1)"><li>Mean</li></td></tr>
<tr><td id="sum2" onclick="changeFoci(2)"><li>Standard deveation</li></td></tr></table></td>
<td id="limitDescription" style="vertical-align:text-top;" rowspan="7"><p> Please select an option from the left.</p></td></tr>
<tr>
<td id="limit0" class="dow1tdl"  onclick="changeFocus(0)"><li>Entry</li></td>
</tr><tr>
<td id="limit1" class="dow1tdl"  onclick="changeFocus(1)"><li>Class</li></td>
</tr><tr>
<td id="limit2" class="dow1tdl"  onclick="changeFocus(2)"><li>Year</li></td>
</tr><tr>
<td id="limit3" class="dow1tdl"  onclick="changeFocus(3)"><li>Subject</li></td>
</tr><tr>
<td id="limit4" class="dow1tdl"  onclick="changeFocus(4)"><li>Teacher</li></td>
</tr><tr>
<td id="limit5" class="dow1tdl"  onclick="changeFocus(5)"><li>Annum</li></td>
</tr><tr>
<td></td><td>
<table><tr>
<td><form action="download2.php" method="POST"><input type="hidden" name="summary" id="summary1" /><input type="hidden" name="limitToGo" id="limitToGo1" value="entry"/><input id="next" value="Next" name ="submit" type="submit" disabled="true" /></form></td>
<td><form action="download4.php" method="POST"><input type="hidden" name="summary" id="summary2" /><input type="hidden" name="dall" value="true" /><input type="hidden" name="limitToGo" id="limitToGo2" value="entry"/><input id="download" value="Download" name ="submit" type="submit" disabled="true" /></form></td>
</tr></table>
</td></tr>
</table></center>
<?php
include 'foot.php';
?>
