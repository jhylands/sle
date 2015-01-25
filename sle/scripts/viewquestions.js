function updateClassList(){
	var objYear = document.getElementById('year');
	var valYear = objYear.options[objYear.selectedIndex].value;
	var objClass = document.getElementById('subject');
	var valClass = objClass.options[objClass.selectedIndex].value;
	var xmlhttp;
	if (window.XMLHttpRequest){// code for IE7+, Firefox, Chrome, Opera, Safari
		xmlhttp=new XMLHttpRequest();
	}else{// code for IE6, IE5
		xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
	}
	xmlhttp.onreadystatechange=function(){
		if (xmlhttp.readyState==4 && xmlhttp.status==200){
			var resp = xmlhttp.responseText;
			//take the responce and put it in the class box
			document.getElementById("class").innerHTML=resp;
		}
	}
	xmlhttp.open("GET","scripts/viewassignedquestions.php?years=" + valYear + "&subjects=" + valClass ,true);
	xmlhttp.send();
}
function viewQuestions(){
	var SS1 = document.getElementById('class');
	for (i=SS1.options.length - 1; i>=0; i--){
	        if (SS1.options[i].selected == true){
	            getQuestions(SS1.options[i].value);
	        }
	}
}
function getQuestions(ClassID){
	var xmlhttp;
	if (window.XMLHttpRequest){// code for IE7+, Firefox, Chrome, Opera, Safari
		xmlhttp=new XMLHttpRequest();
	}else{// code for IE6, IE5
		xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
	}
	xmlhttp.onreadystatechange=function(){
		if (xmlhttp.readyState==4 && xmlhttp.status==200){
			var resp = xmlhttp.responseText;
			//update the innerHTMl of the questions list
			document.getElementById('questionList').innerHTML= resp;
		}
	}
	xmlhttp.open("GET","scripts/viewassignedquestions.php?class=" + ClassID ,true);
	xmlhttp.send();
}
