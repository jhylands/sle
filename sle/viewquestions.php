<?php
include 'head.php';
if(isset($logIn)){
if($logIn!="admin"){
echo "<h1>Error:7 # you can not access this page</h1><p>Please <a href='index.php'>click here</a> to go back to where you should be.";
exit(404);
}}
?>
<script src="scripts/viewquestions.js"></script>
<center>
<p class="information">On this page you can view what questions are already in place. Go back to editing the rules <a href="setup.php">here</a>.</p>
<table><tr><td style="vertical-align:text-top;">
			<h2>Search</h2>
			<label for="year">Year:</label>
			<select id="year" onchange="updateClassList()">
				<option value="1">All years</option>
				<option value="2">Lower school (years:7,8,9,10,11)</option>
				<option value="3">KS3 (years:7,8,9)</option>
				<option value="4">KS4 (years:10,11)</option>
				<option value="5">Sixth form (years:12,13)</option>
				<?php for($i=7;$i<=13;$i++){echo "<option value='" . $i ."'>Year " . $i . "</option>";}?>
			</select>
			<br />
			<label for="subject">Subject:</label>
			<select id="subject" onchange="updateClassList()">
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
		</td><td>
			<h2>Class</h2>
			<select name="classList" onclick="viewQuestions()" id="class" size="10" style="width:150px;" MULTIPLE>
			<?php 
				$QRY1 = "SELECT * FROM class";
				$results1 = mysqli_query($con,$QRY1);
				while($row1 = mysqli_fetch_array($results1)){
					echo "<option value='";	
					echo $row1['PID'];
					echo "'>";
					echo $row1['Code'];
					echo "</option>";
				}
			?>
			</select>
</td>
<td>
<h2>Questions</h2>
<select id="questionList" size="10" style="width:500px;" MULTIPLE>
</select>
</td></tr>
</table></center>
<?php
include 'foot.php';
?>
