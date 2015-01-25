<?php
include 'sql.php';
include 'generalscripts.php';
echo  $_POST['type'] .":";
echo  $_POST['value'] . ":";
echo  $_POST['color'] . ":";
echo  $_POST['xaxis'] . ":";
echo  $_POST['yaxis'] .":";
echo  $_POST['ErrCol'];
$types = explode(",", $_POST['type']);
$value = explode(",", $_POST['value']);
$colors = explode(",", $_POST['color']);
$Xaxis = explode(",", $_POST['xaxis']);
$Yaxis = $_POST['yaxis'];
$ErrCol = $_POST['ErrCol'];
echo "!!";
for($i=0;$i<count($types);$i++){
	$summed[$i] = type($con,$types[$i],$value[$i],$Yaxis);
}

$feed = createPythonRequest($summed,$colors,$Yaxis,$Xaxis,$ErrCol);
echo "<br /><pre>" . $feed . "</pre><br />";
echo exec("whoami");
$mystring = system('python graph.py ' . $feed ,$retval);
echo $mystring;
echo $retval;

function createPythonRequest($summed,$colors,$Yaxis,$Xaxis,$ErrCol){
	$strBuild = implode("Y", $Xaxis);
	$strBuild = $strBuild . "Z";
	$strBuild = $strBuild . implode("Y",$colors);
	$strBuild = $strBuild . "Z";
	echo "Summed(size): " . count($summed);
	echo "Xaxis(size): " . count($Xaxis);
	for($n=0;$n<count($summed);$n++){
		for($i=0;$i<count($Xaxis);$i++){
			$position = binarySearch($Xaxis[$i],selectColumnOfATwoDimentionalArray($summed[$n],'QuestionsID'));
			if($position!=-1){
				$barValues[$n][$i] = $summed[$n][$position]['values'];
			}else{
				$barValues[$n][$i] = 0;
			}
		}
		$seriesValues[$n] = implode("X",$barValues[$n]);
	}
	$strBuild = $strBuild . implode("Y",$seriesValues);
	$strBuild = $strBuild . "Z";
	for($n=0;$n<count($summed);$n++){
		for($i=0;$i<count($Xaxis);$i++){
			$position = binarySearch($Xaxis[$i],selectColumnOfATwoDimentionalArray($summed[$n],'QuestionsID'));
			if($position!=-1){
				$barValuesE[$n][$i] = $summed[$n][$position]['error'];
			}else{
				$barValuesE[$n][$i] = 0;
			}
		}
		$seriesValuesE[$n] = implode("X",$barValuesE[$n]);
	}
	$strBuild = $strBuild . implode("Y",$seriesValuesE);
	$strBuild = $strBuild . "Z";
	$strBuild = $strBuild . $ErrCol;
	$strBuild = $strBuild . "Z";
	if($Yaxis==0){
		$strYlabel = "TotalValue";
	}else{
		$strYlabel = "AverageValue";
	}
	$strBuild = $strBuild . $strYlabel;
	return $strBuild;
}



//importing genral scripts is required
/*function type explained
This function is there to gather the data from the database and put it in a form that will allow the program to read off the information it needs to produce the user defined style of CSV.
The format that is used by the program to read of the data I have called the $new data fromat just to give it a name so that I can refure to it.
The format is a set of embeded arrays where the first dimention refures to a row in the final CSV. The next dimention is an object that is split into  'data' which include the response to the questions. The second dimention also contains the meta data of the row in a raw form, this is for example the class ID. The class ID is a number and so will be of no use to the user and so the function that generates the array to feed into the CSV generating function looks this 'ID' up in the SQL database to find the raw text, in this example a class code 'BP1/AS2'
The data element of the second dimention however contains an array where each element refurse to a question. We will call theis the third dimention of the main $new array for simplicity sake. Each element of the 3rd dimention is an arrayobject containing two elments 'QuestionsID' which is an inmterger refuring to a queston in the queston database. The other element is 'Response' this refurse to the summed response for this type. The summation prosses is chosen by the user from a list of: summing, taking the mean or finding the standard deviation. 
type as oposed to element takes an input of the limiting summary. This could be by subject or by class for example. */
function type($con, $type, $searchQuery, $sum){
	//Generate the SQL query to be submited to the database. The result must be ordered in the following way for the code later used in the while loop that retrives the request to work. The elements must first be sorted by the given $type this might be 'ClassID' for example. The elements within a given $type are then arraged in question order. For each question in each $type there will be many responses from many pupils. The responses (and individual elements of the result) are then arranged by 'PupilID'
	//the ordering is crutial for the next peice of code. However the code should still be stable so long as $type is fed in correctly and the connection with the database has been retived
	$QRY = "SELECT * FROM main WHERE " . $type . "='" . $searchQuery . "' ORDER BY " . $type . " ASC, QuestionsID ASC, PupilID ASC ";
	//send the request generated in the last line to the SQL server and retrive the response 
	$results= mysqli_query($con,$QRY);
	//initiate $z. The variable will count through the pupils in what can be thought of as the fourth dimention of the $data varibale which is almost in $new form
	$z = 0;
	//initiate $y. This variable will count through the questions. This is used rather then the questionsID so that the array is continues
	$y=-1;
	//QuestionsID like classID will hold the data of the current element so to detect a change throught the while.
	//it too starts at -1 so that it will never be the same as a value from the database
	$QuestionsID=-1;
	//the following while loop, loops through the data fetched from the SQL database a few line back
	while($row = mysqli_fetch_array($results)){
		//The following IF statement allows us to realise that a new set of questions are now comming from the SQL response this is one point where the ordering pointed out in the query is important
		if($QuestionsID!=$row['QuestionsID']){
			//we are now looking at a new question so increase counter pointing to  the elment we are looking for
			$y++;
			//Store the current question ID to the apropreate element of the $data array
			$data[$y]['QuestionsID'] = $row['QuestionsID'];
			//reset the pupil count as we are now looking at a new question (this makes the array continues)
			$z=0;
		}
		//store the response cell from the row just recived from the database 
		$data[$y]['Response'][$z] = $row['Response'];
		//increase the counter to indicate we are going to be looking at another pupil next pass of the while loop
		$z++;
	}
	$x=0;
	$y=0;
	foreach($data as $question){
		$summary[$x]['QuestionsID'] = $question['QuestionsID'];
		$summary[$x]['values'] = arraySummary($question['Response'],$sum);
		$summary[$x]['error'] = arraySummary($question['Response'],2);
		$x++;
	}
	//the $summary array is now in $new format and can be submitted for being read into the 2D array required to create the users CSV
	return $summary;
}
?>
