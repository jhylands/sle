function SelectMoveRows(SS1,SS2)
{
    var SelID='';
    var SelText='';
    // Move rows from SS1 to SS2 from bottom to top
    for (i=SS1.options.length - 1; i>=0; i--)
    {
        if (SS1.options[i].selected == true)
        {
            SelID=SS1.options[i].value;
            SelText=SS1.options[i].text;
            var newRow = new Option(SelText,SelID);
            SS2.options[SS2.length]=newRow;
            SS1.options[i]=null;
        }
    }
    //SelectSort(SS2);
}
function moveUp(SS1)
{
    var SelID='';
    var SelText='';
    // Move rows from SS1 to SS2 from bottom to top
    for (i=0; i<=SS1.options.length - 1; i++)
    {
        if (SS1.options[i].selected == true)
        {
			//check that the move is valid i.e the item is not at the top of the list
			if(i!=0){
			// Swap down(moving up the list)
			ID=SS1[i].value;
			Text=SS1[i].text;
			SS1[i].value=SS1[i-1].value;
			SS1[i].text=SS1[i-1].text;
			SS1[i-1].value=ID;
			SS1[i-1].text=Text;
			SS1.options[i].selected = false;
			SS1.options[i-1].selected = true;
			}else{
			//tell the user there was an error
			alert('One of the items was already at the top of the list!');
			}
        }
    }
}
function moveDown(SS1)
{
    var SelID='';
    var SelText='';
    // Move rows from SS1 to SS2 from bottom to top
    for (i=SS1.options.length - 1; i>=0; i--)
    {
        if (SS1.options[i].selected == true)
        {
			//check if the move is valid i.e. if the item is not at the bottom of the list
			if(i!==SS1.options.length-1){
			// Swap down(moving up the list)
			ID=SS1[i].value;
			Text=SS1[i].text;
			SS1[i].value=SS1[i+1].value;
			SS1[i].text=SS1[i+1].text;
			SS1[i+1].value=ID;
			SS1[i+1].text=Text;
			SS1.options[i].selected = false;
			SS1.options[i+1].selected = true;
			}else{
			//tell the user there was an error
			alert('One of the items you have selected is already at the bottom of the list!');
			}
        }
    }
}
function SelectSort(SelList)
{
    var ID='';
    var Text='';
    for (x=0; x < SelList.length - 1; x++)
    {
        for (y=x + 1; y < SelList.length; y++)
        {
            if (SelList[x].text > SelList[y].text)
            {
                // Swap rows
                ID=SelList[x].value;
                Text=SelList[x].text;
                SelList[x].value=SelList[y].value;
                SelList[x].text=SelList[y].text;
                SelList[y].value=ID;
                SelList[y].text=Text;
            }
        }
    }
}
//function to add a question to the db
function addQuestion(SelList,question){
	console.debug('addQuestions entered');
	var xmlhttp;
	if (window.XMLHttpRequest){// code for IE7+, Firefox, Chrome, Opera, Safari
		xmlhttp=new XMLHttpRequest();
	}
	else{// code for IE6, IE5
		xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
	}
	xmlhttp.onreadystatechange=function(){
		if (xmlhttp.readyState==4 && xmlhttp.status==200){
			console.debug('Responce from adding question recived');
			var resp = xmlhttp.responseText;
			console.debug(resp);
			var newRow = new Option(question,resp);
            		SelList.options[SelList.length]=newRow;
		}
	}
	xmlhttp.open("GET","scripts/addquestion.php?question=" + question ,true);
	xmlhttp.send();
}
//function to validate the form before it is submited and the rule is added.
function submitIt(SS1){
/*
submit a list of question ID's
{
	submit a list of years
	submit a list of subjects
OR
	submit a class
}
get a list of quetions
*/
	//WARNING need to check if the box is empty
	strBuild=SS1.options[0].value;
	for (i=1;i<SS1.options.length; i++){
		strBuild=strBuild + "," + SS1.options[i].value;
	}
	//The list of questions 
	var listOfQuestions = strBuild;
	//put the questions in the form for submition
	document.Example.questions.value = listOfQuestions;
	/*if(document.Example.class.value!=""){
		switch(document.Example.year.value){
		case 1:
		year="7,8,9,10,11,12,13";
		break;
		case 2:
		year="7,8,9,10,11";
		break;
		case 3:
		year="7,8,9";
		break;
		case 4:
		year="10,11";
		break;
		case 5:
		year="12,13";
		break;
		default:
		yeardrop = document.Example.year;
		year = yeardrop.options
		console.log(year);
		}
		document.Example.years.value=year;
		//put the values into the form
	}//otherwise the server will look at class*/ 
//submit the data to the server
document.Example.submit();
}


