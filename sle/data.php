<?php
include "head.php";
?>
<script>
//inishiate variables
var informations = new Array("To compare information, simple look to the row which represents the information ou wish to compare, select any aditional filtering and click on compare data in the relevent row.");
//run starting scripts
//function to change the information at the top of the page to reflect that of what the use is hovering their mouse over
function changeInfo(info){
	//get the element in which to put the text
	var tex = document.getElementById('information');
	//put the relevant text from the array into the element
	tex.innerHTML = informations[info];
}
//function to navigate to the download of all of the data for a given year
function gotoYear(){
	alert('No year selected');
}
</script>
<center>
<p class="information" id="information"></p>
<table border="1">
<tr onmouseover="changeInfo(0);"><td>Type</td><td>Selection</td><td>Compare Data</td><td>Download csv</td></tr>
<tr><td>All</td><td><hr /></td><td><input type="button" value="Compare all" /></td><td><a href="scripts/dall.php?valid=1"><input type="button" value="Download" /></a></td></tr>
<tr><td>Year group</td><td><select name="year"><option value="0">Compare all years</option><option value="1">Compare KS3</option><option value="2">Compare KS4</option><option vlaue="3">Compare Sixth form</option><?php for($i=7;$i<=13;$i++){ echo "<option value='" . ($i - 3) . "'>Compare Year ". $i . "</option>"; }?></select></td><td><input type="button" value="Compare" /></td><td><input type="button" value="Download" onclick="gotoYear();"/></td></tr>
</table>
</center>
<?php
include 'foot.php';
?>