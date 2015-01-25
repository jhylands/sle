<?php
/*The build CSV function takes the information from the summary variable which is in $new form and and the column header information which is in a comma seperated string. It then reads off the column header information taking the information it needs from $summary.
*/
function buildCSV($con,$summary,$strColumns,$strColumnNames){
	//split the columns string into its elements. That is that we are seperating it by comma as each of the headers is added as a comma
	$arrColumns = explode(",",$strColumns);
	//split the column names string into ints compenet headings so that we can read each header off in turn
	$arrColumnNames = explode("," , $strColumnNames);
	$n=0;
	//loop through the $columns array to write out the column headers
	foreach($arrColumnNames as $name){
		//write the name from the array posted by download3.php
		$data[0][$n] = $name;
		//increase the counter to continue onto the next column heading
		$n++;
	}
	//counter through the rows leave a gap for the headers at the top
	$i=1;
	//for each summary allows us to count through each of the rows of the to be CSV as the first dimention of $summary has been designed this way
	foreach($summary as $row){
		//counter through columns
		$n=0;
		//for each $arrColumns allows us to loop through each of the columns
		foreach($arrColumns as $header){
			//if this column is a question (denoted by 'Q') then get the question from $row
			if(intval($header)>50){
				//if the position of this question by searching though the questionID's
				$position = binarySearch((intval($header)-50),selectColumnOfATwoDimentionalArray($row['data'],'QuestionsID'));
				//if the string is not there then show a "N/A" instead of value
				if($position!=-1){
					//assign this column,row the correct responce corrisponding to the rows limiting selection and the columns question
					$data[$i][$n]=$row['data'][$position]['Response'];
				}else{
					//there is no value so show that by setting the value to "N/A"
					$data[$i][$n] = "N/A";
				}
			}elseif(substr($header,0,1)=="4"){
				//if the column is a summary of the all the questions then store that summary in the corrasponding element
				$data[$i][$n]=arraySummary(selectColumnOfATwoDimentionalArray($row['data'],'Response'),intval(substr($header,1)));
			}else{
				//if the column is meta data from the row for example the class ID then display that in the corrasponding cell
				switch($header){
				case 1:
					$data[$i][$n]=getSQLVal($con,$row['SubjectID'],'subjects','Subject');
					break;
				case 2:
					$data[$i][$n]=getSQLVal($con,$row['TeacherID'],'teachers','Name');
					break;
				case 0:
					$data[$i][$n]=$row['Annum'];
					break;
				case "ClassID":
					$data[$i][$n]=getSQLVal($con,$row['ClassID'],'class','Code');
					break;
				}
			}
		//next column
		$n++;
		}
	//next row
	$i++;
	}
	return $data;
}
//importing genral scripts is required
/*function type explained
This function is there to gather the data from the database and put it in a form that will allow the program to read off the information it needs to produce the user defined style of CSV.
The format that is used by the program to read of the data I have called the $new data fromat just to give it a name so that I can refure to it.
The format is a set of embeded arrays where the first dimention refures to a row in the final CSV. The next dimention is an object that is split into  'data' which include the response to the questions. The second dimention also contains the meta data of the row in a raw form, this is for example the class ID. The class ID is a number and so will be of no use to the user and so the function that generates the array to feed into the CSV generating function looks this 'ID' up in the SQL database to find the raw text, in this example a class code 'BP1/AS2'
The data element of the second dimention however contains an array where each element refurse to a question. We will call theis the third dimention of the main $new array for simplicity sake. Each element of the 3rd dimention is an arrayobject containing two elments 'QuestionsID' which is an inmterger refuring to a queston in the queston database. The other element is 'Response' this refurse to the summed response for this type. The summation prosses is chosen by the user from a list of: summing, taking the mean or finding the standard deviation. 
type as oposed to element takes an input of the limiting summary. This could be by subject or by class for example. */
function type($con,$type,$sum){
	//Generate the SQL query to be submited to the database. The result must be ordered in the following way for the code later used in the while loop that retrives the request to work. The elements must first be sorted by the given $type this might be 'ClassID' for example. The elements within a given $type are then arraged in question order. For each question in each $type there will be many responses from many pupils. The responses (and individual elements of the result) are then arranged by 'PupilID'
	//the ordering is crutial for the next peice of code. However the code should still be stable so long as $type is fed in correctly and the connection with the database has been retived
	$QRY = "SELECT * FROM main ORDER BY " . $type . " ASC, QuestionsID ASC, PupilID ASC ";
	//send the request generated in the last line to the SQL server and retrive the response 
	$results= mysqli_query($con,$QRY);
	//initiate $z. The variable will count through the pupils in what can be thought of as the fourth dimention of the $data varibale which is almost in $new form
	$z = 0;
	//initiate $y. This variable will count through the questions. This is used rather then the questionsID so that the array is continues
	$y = -1;
	//initiate $x. This variable will count throught the rows of the two be CSV
	//the variable starts at -1 so that when it is updated on the first pass through the while loop it becomes "0" the first index of the array
	$x = -1;
	//inishiate variables to change x and y as they only change when either the qustion read from the db changes or the class read from the db changes
	//initiate $ClassID this will hold the ID of the current $type. Class is only an example of the type of ID this varibale may hold but it still acts as a fitting name for the variable as it will often hold the class ID
	//it starts at -1 so that it will never start the same as a value of ClassID (or any other ID) from the database thus causing $x to update and $y to reset
	$ClassID=-1;
	//QuestionsID like classID will hold the data of the current element so to detect a change throught the while.
	//it too starts at -1 so that it will never be the same as a value from the database
	$QuestionsID=-1;
	//the following while loop, loops through the data fetched from the SQL database a few line back
	while($row = mysqli_fetch_array($results)){
		//The following IF statement checks for a change in the ClassID from the one we are currently looking at
		//this is required because the response from the SQL database will be many elements of the same class, followed by more elements of a different class, followed by futher elements of yet another class ect
		if($ClassID!=$row[$type]){
			//increasing X allows us to store the data in futher elements(not just this pass of the while loop) in the same element of the first dimention of $data which is almost in $new format
			$x++;
			//update the classID we are looking at to the one in the element that has just been retrived from the database
			$ClassID=$row[$type];
			//reset $y so that we count from the zelth element of question again
			$y=-1;
			//store the information for this class/subject ect from that of the current $row that has been retrived from the response from the SQL database
			$data[$x]['ClassID'] = $row['ClassID'];
			$data[$x]['SubjectID'] = $row['SubjectID'];
			$data[$x]['TeacherID'] = $row['TeacherID'];
			$data[$x]['Annum'] = $row['Annum'];
		}
		//The following IF statement allows us to realise that a new set of questions are now comming from the SQL response this is one point where the ordering pointed out in the query is important
		if($QuestionsID!=$row['QuestionsID']){
			//we are now looking at a new question so increase counter pointing to  the elment we are looking for
			$y++;
			//Store the current question ID to the apropreate element of the $data array
			$data[$x]['data'][$y]['QuestionsID'] = $row['QuestionsID'];
			//reset the pupil count as we are now looking at a new question (this makes the array continues)
			$z=0;
		}
		//store the response cell from the row just recived from the database 
		$data[$x]['data'][$y]['Response'][$z] = $row['Response'];
		//increase the counter to indicate we are going to be looking at another pupil next pass of the while loop
		$z++;
	}
	//we almost have the the data in the $new format but not quite $data[$x]['data'][$y]['data'] is still an array
	//we need to summarise this array
	//The following for each loop loops through each $z element of the $data array. This dimention of the array corrisponds to what will eventually be the rows of the CSV
	$x=0;
	$y=0;
	foreach($data as $element){
		$y=0;
		//the following for each loop loops through each question of the $data[]['data'] elements of the $data array
		foreach($element['data'] as $question){
			//this takes the information from the old data array and sums it to the new data array that is strucurally in the $new format but not wholly in the $new format as some of the meta data may be incorrect
			$summary[$x]['data'][$y]['Response'] = sumArray($question['Response'],$sum);
			//move the question ID information from the old array to the new one
			$summary[$x]['data'][$y]['QuestionsID'] = $question['QuestionsID'];
			$y++;
		}
		//if the $type is ClassID then it is meeningfull to talk about which subject the class was however as it is not meeningful to talk about which Teacher taught physics in the school for 2008-2009 (because their was not just one) the elemnts are filled with the string "MULTI"
		if($type=="ClassID"){
			$summary[$x]['ClassID'] = $element['ClassID'];
			$summary[$x]['SubjectID'] = $element['SubjectID'];
			$summary[$x]['TeacherID'] = $element['TeacherID'];
			$summary[$x]['Annum'] = $element['Annum'];
		}else{
			$summary[$x]['ClassID'] = "MULTI";
			$summary[$x]['SubjectID'] = "MULTI";
			$summary[$x]['TeacherID'] = "MULTI";
			$summary[$x]['Annum'] = "MULTI";
		}
		$x++;
	}
	//the $summary array is now in $new format and can be submitted for being read into the 2D array required to create the users CSV
	return $summary;
}
/*element function explained
The element function very simmilaly to the type function gathers data from the database and puts it into the $new data structure. This data structure has been explained above and so I will not explain it again here.
The main purpose of this function is to gather all the data where each row of the to be CSV is to corispond to a pecific pupil in a specific class .
*/
function elementDataGather($con){
	//Generate a request to be sent to the SQL database. The order is very important here.
	$QRY = "SELECT * FROM main ORDER BY PupilID ASC, ClassID ASC, QuestionsID ASC";
	//retrive the query
	$results= mysqli_query($con,$QRY);
	//feed it into an array of [pupil/class] -> {data,[other info],[questions]}
	//define variables to act as markers for PupilID and ClassID
	$ClassID = -1;
	$PupilID = -1;
	$QuestionsID = -1;
	//x is an element of of the array it represents the row that will be fed to the the $new  
	$x = -1;
	//y is the question so that all of the question can be put in the array continuesty even if the array PID is not continus
	$y = -1;
	while($row = mysqli_fetch_array($results)){
		//while the class stays the same $x stays the same 
		if($ClassID!=$row['ClassID']||$PupilID!=$row['PupilID']){
			$x++;
			$ClassID = $row['ClassID'];
			$PupilID = $row['PupilID'];
			//reset the question counter
			$y=-1;
			//update information about this class
			$data[$x]['ClassID'] = $row['ClassID'];
			$data[$x]['SubjectID'] = $row['SubjectID'];
			$data[$x]['TeacherID'] = $row['TeacherID'];
			$data[$x]['Annum'] = $row['Annum'];
			//need to add year group
		}
		if($QuestionsID!=$row['QuestionsID']){
			$y++;
			$QuestionID= $row['QuestionsID'];
		}
		//x=element][y=question->put for continuse reasons
		$data[$x]['data'][$y]['QuestionsID'] = $row['QuestionsID'];
		$data[$x]['data'][$y]['Response'] = $row['Response'];
	}//end while
	return $data;
}
function getSQLVal($con,$PID,$table,$column){
	$QRY = "SELECT * FROM " . $table . " WHERE PID=" . $PID;
	$results = mysqli_query($con,$QRY);
	while($row = mysqli_fetch_array($results)){
		return $row[$column];
	}
}
?>
