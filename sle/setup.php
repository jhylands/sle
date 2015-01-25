<?php
include 'head.php';
if(isset($logIn)){
if($logIn!="admin"){
echo "<h1>Error:7 # you can not access this page</h1><p>Please <a href='index.php'>click here</a> to go back to where you should be.";
exit(404);
}}
//if the page has been accesed from having just added a rule then tell the user this
include 'scripts/info.php';
?>
<script type="text/javascript" src="scripts/setup.js"></script>
<center><p class="information">On this page you can ajust which questions apply to which classes. To make this easier you can select more than one class at once. To do this select a year group on the left followed by a subject. If you would like to see rules already in place then <a href="viewquestions.php">here</a> is a page where you can view such.</p>
<div style="position:relative;top:5px;">
<form name="Example" action="scripts/changequestionrule.php" method="post">
<center>
<table border="0" cellpadding="3" cellspacing="0">
	<tr>
		<td>
			<h2>What group does this rule apply to?</h2>
			<label for="year">Year:</label>
			<select name="year" id="year">
				<option value="1">All years</option>
				<option value="2">Lower school (years:7,8,9,10,11)</option>
				<option value="3">KS3 (years:7,8,9)</option>
				<option value="4">KS4 (years:10,11)</option>
				<option value="5">Sixth form (years:12,13)</option>
				<?php for($i=7;$i<=13;$i++){echo "<option value='" . $i ."'>Year " . $i . "</option>";}?>
			</select>
			<br />
			<label for="subject">Subject:</label>
			<select name="subjects" id="subject">
				<option value="0">All subjects</option>
				<?php $QRY1 = "SELECT * FROM subjects";
				$results1 = mysqli_query($con,$QRY1);
				while($row1 = mysqli_fetch_array($results1)){
					echo "<option value='";	
					echo $row1['PID'];
					echo "'>";
					echo $row1['Subject'];
					echo "</option>";
				}?>  
			<select>
			<hr />
			<label for="class">OR a specific class:</label>
			<input type="text" name="class" id="class" /><br/>
		</td>
		<td></td>
		<td width="510px">
			<p class="information">Select the questions from the past that you want to use with each subject. Once you are done click select questions and the questions in the 'to be used' box will be assigned.</p>
			<input type="button" onclick="submitIt(document.Example.selectedQuestions)" value="Select questions" />
		</td>
		
    </tr>
    <tr>
        <td>
            <select name="allQuestions" size="10" style="width:500px;" MULTIPLE>
			<?php
				include 'scripts/sql.php';
				$QRY0 = "SELECT * FROM questions ORDER BY 'PID' ASC";
				$results = mysqli_query($con,$QRY0);
				while($row = mysqli_fetch_array($results)){
					echo "<option value='" . $row['PID'] . "'>". $row['Question'] . "</option>";
				}
			?>
            </select><br />
			<input name="newQuestion" type="text" > <input type="button" value="Add Question" onclick="addQuestion(document.Example.allQuestions,document.Example.newQuestion.value)" />
        </td>
        <td align="center" valign="middle">
            <input type="Button" value="Add >>" style="width:100px" onClick="SelectMoveRows(document.Example.allQuestions,document.Example.selectedQuestions)"><br>
            <br>
            <input type="Button" value="<< Remove" style="width:100px" onClick="SelectMoveRows(document.Example.selectedQuestions,document.Example.allQuestions)">
        </td>
        <td>
            <select name="selectedQuestions" size="10" style="width:500px;" MULTIPLE>
            </select>
			<br /><input type="button" value="Move question up" onclick="moveUp(document.Example.selectedQuestions)" /><input type="button" value="Move question down" onclick="moveDown(document.Example.selectedQuestions)"/>
        </td>
	</tr>

</table>
</center>

<input type="hidden" name="years" value="" />
<input type="hidden" name="questions" value="" />
</form>
</div>
</body>
</html>
