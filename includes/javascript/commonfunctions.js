



function isInteger(s){
	var i;
    for (i = 0; i < s.length; i++){   
        // Check that current character is number.
        var c = s.charAt(i);
        if (((c < "0") || (c > "9"))) return false;
    }
    // All characters are numbers.
    return true;
}

function daysInFebruary (year){
	// February has 29 days in any year evenly divisible by four,
    // EXCEPT for centurial years which are not also divisible by 400.
    return (((year % 4 == 0) && ( (!(year % 100 == 0)) || (year % 400 == 0))) ? 29 : 28 );
}
function DaysArray(n) {
	for (var i = 1; i <= n; i++) {
		this[i] = 31
		if (i==4 || i==6 || i==9 || i==11) {this[i] = 30}
		if (i==2) {this[i] = 29}
   } 
   return this
}

function isDate(dtStr,message){
	var daysInMonth = DaysArray(12);
	var pos1=dtStr.indexOf(dtCh);
	var pos2=dtStr.indexOf(dtCh,pos1+1);
	var strDay=dtStr.substring(0,pos1);
	var strMonth=dtStr.substring(pos1+1,pos2);
	var strYear=dtStr.substring(pos2+1);
	strYr=strYear;
	if (strDay.charAt(0)=="0" && strDay.length>1) strDay=strDay.substring(1)
	if (strMonth.charAt(0)=="0" && strMonth.length>1) strMonth=strMonth.substring(1)
	for (var i = 1; i <= 3; i++) {
		if (strYr.charAt(0)=="0" && strYr.length>1) strYr=strYr.substring(1)
	}
	month=parseInt(strMonth);
	
	alert('The month is'+ strMonth);
	alert('The day is is'+ strDay);
	day=parseInt(strDay);
	year=parseInt(strYr);
	if (pos1==-1 || pos2==-1){
		alert("The date format should be : mm-dd-yyyy for " + message);
		return false;
	}
	if (strMonth.length<1 || month<1 || month>12){
		alert("Please enter a valid month for " + message);
		return false;
	}
	if (strDay.length<1 || day<1 || day>31 || (month==2 && day>daysInFebruary(year)) || day > daysInMonth[month]){
		alert("Please enter a valid day for " + message);
		return false;
	}
	
	if (strYear.length != 4 || year==0 || year<minYear || year>maxYear){
		alert("Please enter a valid 4 digit year between "+minYear+" and "+maxYear +'\n for '+ message);
		return false;
	}
	if (dtStr.indexOf(dtCh,pos2+1)!=-1 || isInteger(stripCharsInBag(dtStr, dtCh))==false){
		alert("Please enter a valid date for " + message);
		return false;
	}
	
return true;
}

// general purpose function to see if a suspected numeric input
// is a positive integer
function isPosInteger(fieldName,message) {
	inputVal = document.getElementByName(fieldName).value;
	inputStr = inputVal.toString();
	Isvalid = false;
	for (var i = 0; i < inputStr.length; i++) {
		var oneChar = inputStr.charAt(i);
		if (oneChar < "0" || oneChar > "9") {
			Isvalid = true;
		}
	}
	if(Isvalid){
		alert(message);
		return false;
	}else{
		return true;
	}
}

function clearField(elementid,fieldtype){
	document.getElementById(elementid).value = "";		
	//switch(fieldtype){	
	//	case 'textbox':								
		//	document.getElementById(elementid).value = "";						
		//	break;
				
	//}	
	return true;	
}


//function to validate two date fields and ensures that the date in the firstfield is before the date in the second field
function validateDateFields(firstField, secondField, message){
	var firstdatearray = new Array();
	var seconddatearray = new Array();
	
	//var firstdatestring = document.getElementById(firstField).value;
	
	if(firstField != 'birthdate'){
		// this date already has a / separator for the month, day and year
		firstdatestring = document.getElementById(firstField).value;
	} else {
		// birth date is got from 3 fields, birthdate_m, birthdate_d, birthdate_y so we 
		// concatenate them and separate them with a / which we shall strip off later
		firstdatestring  = document.getElementById("birthdate_m").value + "/" + document.getElementById("birthdate_d").value + "/" + document.getElementById("birthdate_y").value;
	}
	
	var seconddatestring = document.getElementById(secondField).value;
	
	if(firstdatestring != "" && seconddatestring != ""){
		// if the date strings are the same then return true
		//alert('the first date string is '+firstField+' with value '+firstdatestring+' and the second date string is '+secondField +' with value '+seconddatestring);
		//extract the values from the date strings and place them in arrays
		firstdatearray = firstdatestring.split("/");
		seconddatearray = seconddatestring.split("/");
		
		//declare new dates using the values in the arrays
		// For some reason, subtract 1 from the month, I think it has to do with
		// months being calculated from 0 (for Jan) instead of 1 or something like that
		var firstdate = new Date(firstdatearray[2], firstdatearray[0] - 1, firstdatearray[1]);
		var seconddate = new Date(seconddatearray[2], seconddatearray[0] - 1, seconddatearray[1]);
	
		//subtract the time between the two dates
		var difference = seconddate.getTime() - firstdate.getTime();
		//if the time difference is negative, empty the fields, alert a message and return false
		if(difference < 0 ){
			alert(message);
			//document.getElementById(secondField).value = "";
			return false;
			
		}
	}
	return true;
}

var id='';	
var theid='';

// check to see if there is a checkbox selected in the grid
function checkForSelectedCheckbox(element_prefix){
		var elementname ='';
		
		if (element_prefix == 'undefined'){
		 	element_prefix ='';
		}else{
			elementname = element_prefix 
		}
		
		var field = document.forms[0];	
		
		for (i=0; i<field.length; i++) {
			
		
			if(field.elements[i].type =="checkbox"){
				
				if(element_prefix!=""){
					
					elementname = field.elements[i].id;
					
					if(field.elements[i].checked==true && elementname.substr(0,5) =="grid_"){
						id = field.elements[i].value
						Ischeckboxselected = true;
						return true;
					}	
				}else{
					if(field.elements[i].checked==true ){
						id = field.elements[i].value
						Ischeckboxselected = true;
						return true;
					}
				}
				
				
			}
		
		}
	
}



function checkPasswords(fieldname1,fieldname2,message) {		
	if(document.getElementById(fieldname1).value!=document.getElementById(fieldname2).value){
		alert(message);
		return false;
	}
	return true;
}

function IsNumeric(fieldname,message) {
	yearvalue = document.getElementById(fieldname).value;			
	for (i=1;i<=yearvalue.length;i++) {
		if(i!=1){
			pos=i-1;
		}else{
			pos=0;
		}
		
		if(yearvalue.substring(pos,i)!='0' && yearvalue.substring(pos,i)!='1' && yearvalue.substring(pos,i)!='2' && yearvalue.substring(pos,i)!='3' && yearvalue.substring(pos,i)!='4' && yearvalue.substring(pos,i)!='5' && yearvalue.substring(pos,i)!='6' && yearvalue.substring(pos,i)!='7'&& yearvalue.substring(pos,i)!='8' && yearvalue.substring(pos,i)!='9' && yearvalue.substring(pos,i)!=','  && yearvalue.substring(pos,i)!='.'){
			alert(message);
			return false;
		}			
	}
	return true;
}

function formReset(){
    var x=document.forms.myForm;
    x.reset();
}
 
 function EnterNumericOnly(e,elementid) {
	var keynum;
	var keychar;
	var numcheck;
	var haystack;
	var needle =".";
	
	
	
	if(window.event) // IE
	{
		keynum = e.keyCode
	}
	else if(e.which) // Netscape/Firefox/Opera
	{
		keynum = e.which
	}
	
	if(keynum==8 || typeof(keynum)=='undefined'){return;}
	//alert(keynum);
	keychar = String.fromCharCode(keynum)
	numcheck = /\d/
	haystack = document.getElementById(elementid).value;
	
	// see user does not input more then i full stop
	if(haystack.split(needle).length >1){		
		//return numcheck.test(keychar);	
	}
	
	if (keynum==46){ return;}

	if(!numcheck.test(keychar)&& keynum!=44 ){
		alert('Please enter only numeric charaters!');
		return numcheck.test(keychar);
	}
	
	if(keynum==44){
		return keychar
	}else{
		return numcheck.test(keychar)	
	}
	
	
}
