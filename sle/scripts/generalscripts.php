<?php
function outputCSV($filename,$data) {
header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=\"" . $filename . "\"");
    $outstream = fopen("php://output", 'w');
    function __outputCSV(&$vals, $key, $filehandler) {
        fputcsv($filehandler, $vals, ',', '"');
    }
    array_walk($data, '__outputCSV', $outstream);
    fclose($outstream);
}

function csvToArray($filename=''){
	//check the files exists
    if(file_exists($filename) && is_readable($filename)){
		//create an array to store the lines
		$data = array();
		//if the file is opened without error
		if (($handle = fopen($filename, 'r')) !== FALSE)
		{
			//go through the file line by line storing the line as an array to an element in the array $data
			//WARNING parameter 1000 may be too small (suspectedly the number of lines to read~)
			while (($row = fgetcsv($handle)) !== FALSE)
			{
				//add the data
				$data[] = $row;
				//echo "row";
			}
			fclose($handle);
		}
		return $data;
	}
}

//a function to quicksort the data
function quickSort($column,$data) {
    if(!count($data)) return $data;
    $pivot= $data[0][$column];
	$pivotX = $data[0]['QuestionsID'];
    $low = $high = array();
    $length = count($data);
    for($i=1; $i < $length; $i++) {
        if($data[$i][$column] < $pivot) {
            $low [] = $data[$i];
        } elseif($data[$i][$column] < $pivot)  {
            $high[] = $data[$i];
        }else{
		if($data[$i][$column] < $pivot) {
            $low [] = $data[$i];
        } elseif($data[$i]['QuestionsID'] < $pivot)  {
            $high[] = $data[$i];
		  }
		}
    }
    return array_merge(quickSort($column,$low), array($pivot), quickSort($column,$high));
}
function binary($needle,$haystack){
	$pivot = (count($haystack))/2;
	if(count($haystack)==1){
		if($haystack==$needle){
			return 0;
		}else{
			return -1;
		}
	}else{
		if($pivot == intval(count($haystack)/2)){
			$pivot = intval(count($haystack)/2) + 1;
		}
		if($haystack[$pivot]==$needle){
			return $pivot;
		}elseif($haystack[$pivot]<$needle){
			$position = binarySearch($needle,array_slice($haystack,$pivot));
			if($position!=-1){
				return $pivot + $position;
			}else{
				return -1;
			}
		}else{
			return binarySearch($needle,array_slice($haystack,0,$pivot));
		}
	}
}
function binarySearch($needle,$haystack){
	$first = 0;
	$last = count($haystack)-1;
	$found = False;
	while(($first <= $last) AND (!$found)){
		$middle = ($first+$last)/2;
		if($haystack[$middle]==$needle){
			$found = True;
		}elseif($needle<$haystack[$middle]){
			$last = $middle-1;
		}else{
			$first = $middle+1;
		}
	}
	if($found){
		return $middle;
	}else{
		return -1;
	}
}


function selectColumnOfATwoDimentionalArray($arr,$column){
	$i=0;
	foreach($arr as $element){
		$oneDimentionalArray[$i] = $element[$column];
		$i++;
	}
	return $oneDimentionalArray;
}
//function to select summary method
function arraySummary($arr,$type){
	switch($type){
	case 0:
		//sum
		$result = sumArray($arr);
		break;
	case 1:
		//mean
		$result = avgArray($arr);
		break;
	case 2:
		//standard deviation of the array
		$result = SDArray($arr);
	break;
	}
	return $result;
}
//function to sum an array
function sumArray($arrArray){
	$total = 0;
	foreach($arrArray as $element){
		$total+=$element;
	}
	return $total;
}
//function to calculate average value in array
function avgArray($arrArray){
	$average = sumArray($arrArray)/count($arrArray);
	return $average;
}
//function to find the standard deveation of an array
function SDArray($arrArray){
	//mean of the squares minus the square of the means
	$Variance = pow(avgArray($arrArray),2)-avgArray(sqrArray($arrArray));
	$SD = pow($Variance,0.5);
	return $SD;
}
//function to find an array where each element is the square of the input array
function sqrArray($arr){
	foreach($arr as $element){
		$element = $element*$element;
	}
	return $arr;
}

?>
