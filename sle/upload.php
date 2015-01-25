<?php 
include 'head.php';
?>
<script>
function checkSubmit(){
	if(document.getElementById('file').value!=""){
		document.getElementById('infobox').innerHTML='<h1>Loading...</h1><p>This may take some time.</p>';
		document.getElementById('submitButton').disabled=true;
		document.formSubmit.submit();
	}else{
		alert('No file selected!');
	}
}
</script>
<center><form id="formSubmit" name="formSubmit" action="upload2.php" method="post" enctype="multipart/form-data">
<table>
	<tr>
	<td>
		<label for="file">Filename:</label>
		<input type="file" name="file" id="file" accept="text/comma-separated-values" />
	</td>
	</tr><tr>	
	<td>
		<label for="year">What year did the classes start?</label>	
		<input type="test" maxlength="4" id="year" name="year" value="<?php echo date('Y'); ?>" style="width:35px;"/>
	</td>
	<td style="align:right;">
		<input id="submitButton" type="Button" value="Submit" name="submitButton" onclick="checkSubmit()" />
	</td>
	</tr>
</form>
<x id="infobox"></x>
</center>
