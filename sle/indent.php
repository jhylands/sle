<pre><?php
//conect to database to gather the required data
include 'scripts/sql.php';
//get the data from the database
//select all from the main db ordering it by class
$QRY = "SELECT * FROM main ORDER BY ClassID ASC";
//echo $QRY;//for debugging
$results = mysqli_query($con,$QRY);
$csv = Array();
//Have an array to pass into the csv generator
$csv[0] = Array('Q1','Q2','Q3','Q4','Q5','Q6','Q7','Q8','Q9','Q10');
$classC = "";//The currently adding class
$n = 1;
while($row = mysqli_fetch_array($results)){
	//check for initial conditions
	if($classC == ""){
	$classC = $row['ClassID'];
	$csv[$n] = $Q = array(0,0,0,0,0,0,0,0,0,0);
	}
	//if the class hasn't changed then continue summing the answers
	if($classC == $row['ClassID']){
		for( $i=0;$i<10;$i++){
			$csv[$n][$i] = $csv[$n][$i] + $row['Q' . ($i+1)];
		}
	}else{
		//if the class has changed then output the last row
		//update the current class
		$n++;
		$classC = $row['ClassID'];
		$csv[$n] = Array(0,0,0,0,0,0,0,0,0,0);
		for( $i=0;$i<10;$i++){
			$csv[$n][$i] = $csv[$n][$i] + $row['Q' . ($i+1)];
		}
	}
}
//create the php file to be run
$file = fopen("graph.py","w");
fwrite($file,"
#!/usr/bin/env python
# a bar plot with errorbars
import numpy as np
import matplotlib.pyplot as plt

#number of bars
N = 10
#array of the scores
menMeans = (" . join("," , $csv[1]) . ")
#array of the error bars
menStd =   (0,0,0,0,0,0,0,0,0,0)

ind = np.arange(N)*1.2  # the x locations for the groups
width = 0.35       # the width of the bars

fig, ax = plt.subplots()
rects1 = ax.bar(ind, menMeans, width, color='r', yerr=menStd)

womenMeans = (" . join(",",$csv[2]) . ")
womenStd =   (0,0,0,0,0,0,0,0,0,0)
rects2 = ax.bar(ind+width, womenMeans, width, color='y', yerr=womenStd)

unspecifiedMeans = (1,2,3,4,5,6,7,8,9,10)
unspecifiedStd =   (0,0,0,0,0,0,0,0,0,0)
rects3 = ax.bar(ind+width*2, unspecifiedMeans, width, color='g', yerr=unspecifiedStd)

# add some lables
ax.set_ylabel(ind)
ax.set_title('Scores by group and gender')
ax.set_xticks(ind+width)
#array of X axis lables
ax.set_xticklabels( ('G1','G2') )
#legend lables
ax.legend( (rects1[0], rects2[0], rects3[0]), ('woMen', 'men', 'Unspecified') )
#save to tempery file
plt.savefig('temp.png')");
fclose($file);
//run the php file
$output = null;
$some = exec('python graph.py', $output);
echo $some;
echo var_export($output, TRUE);
?></pre>
<script>
//delete the image that we just created to keep the directory cleen
function cleanimg(){
//send request
var xmlhttp;
if (window.XMLHttpRequest){// code for IE7+, Firefox, Chrome, Opera, Safari
xmlhttp=new XMLHttpRequest();
}
else{// code for IE6, IE5
xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
}
xmlhttp.onreadystatechange=function(){
if (xmlhttp.readyState==4 && xmlhttp.status==200){
//get the response of the delete
var responce=xmlhttp.responseText;
//alert(responce);//for debugging
}
}
xmlhttp.open("GET","cleanimg.php",true);
xmlhttp.send();
}
/*
code to add the value of the bar to each bar
def autolabel(rects):
    # attach some text labels
    for rect in rects:
        height = rect.get_height()
        ax.text(rect.get_x()+rect.get_width()/2., 1.05*height, '%d'%int(height),
                ha='center', va='bottom')

#autolabel(rects1)
#autolabel(rects2)
*/
</script>
<img src="temp.png" onload="cleanimg();"/>
