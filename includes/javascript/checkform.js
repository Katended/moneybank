	function IsNullEmptyField(fieldname,message) {		
		if(document.getElementById(fieldname).value==""){
			alert(message);
			return false;
		}
		return true;
	}
	function checkPasswords(fieldname1,fieldname2,message) {		
		if(document.getElementById(fieldname1).value!=document.getElementById(fieldname2).value){
			alert(message);
			return false;
		}
		return true;
	}
	function checkMarks(fieldname,message,maxvalue) {		
			
		if(document.getElementById(fieldname).value!=""){
			if(parseInt(document.getElementById(fieldname).value)>parseInt(maxvalue)){
				alert(message);
				return false;
			}
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
			if(yearvalue.substring(pos,i)!='0' && yearvalue.substring(pos,i)!='1' && yearvalue.substring(pos,i)!='2' && yearvalue.substring(pos,i)!='3' && yearvalue.substring(pos,i)!='4' && yearvalue.substring(pos,i)!='5' && yearvalue.substring(pos,i)!='6' && yearvalue.substring(pos,i)!='7'&& yearvalue.substring(pos,i)!='8' && yearvalue.substring(pos,i)!='9' ){
				alert(message);
				return false;
			}			
		}
		return true;
	}
function IsValidYear(fieldname,message) {	
	 if(fieldname.length < 4){
		alert(message);
		return false;			
	}
	return true;
}

function checkEmail(field,message) {
	var email = document.getElementById(field);
	var filter = /^([a-zA-Z0-9_.-])+@(([a-zA-Z0-9-])+.)+([a-zA-Z0-9]{2,4})+$/;
	if (!filter.test(email.value)) {
		alert(message);
		email.focus
		return false;
	}
}
// set the form action where data is to be submitted
function submitForm() {
		//document.getElementById("tstatus").value = statusValue;
		document.forms[1].submit();
}
