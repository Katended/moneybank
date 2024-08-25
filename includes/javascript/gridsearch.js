
var UInter = ''; // the id of the elemtn you want data to be displayed in
var url="../addedit.php"; 
var cstr;
var bdataloaded =false
function makeRequest(str){
	
	var cstr = str;
		
	var objURL = {};
	//alert(Object.prototype.toString.call(str));
	
	
	if(Object.prototype.toString.call(str)=='[object String]'){
            // convert the url parameters to a javascrip object
            var pairs = cstr.split('&');
            for(i in pairs){
                var split = pairs[i].split('=');
                objURL[decodeURIComponent(split[0])] = decodeURIComponent(split[1]);
            }
	}else{
		
            objURL=str;
            JSON.stringify(str);
	}
	
	$.ajax({
            type: 'POST',
            url: url,
            data: objURL,
            beforeSend:function(){

            // this is where we append a loading image	
            if(cstr['action']=='add'){
                $('#'+UInter).html('<p style="text-align:center;font-size:10px;">loading...<br><img src="../images/loading.gif" border="0"></p>');
            }
		
	  },
	  
      success:function(data){
        // successful request; do something with the data

        if (IsJsonString(data)==true){
            displayNotification(data)
            return;
        }

        var dataresults = data;



        if(dataresults.search('pnotify')> 0 || cstr['action']=='load'> 0 ||cstr['action']=='update' || cstr['action']=='add'  && dataresults.search('<table')>0){			
            //eval(data);	
            displayNotification(data);
            return;
        }

        if(UInter!=''){
            $('#'+UInter).html(dataresults.trim());	
            return;
        }



        if((dataresults.search('Obj')>0 || dataresults.search('getElementById')>0) && dataresults.search('evallater')<=0){
            var formObj = document.forms[0];
            eval(dataresults.trim());	
            return;
        }else{
            $('#'+UInter).html(dataresults.trim());	
            bdataloaded = true;
        }



      },
      error:function(res){
        // failed request; give feedback to user
        $('#status').html('<p class="error">'+data+'</p>');
      }
    });
	
	
	
}

function IsJsonString(str) {
    try {
        JSON.parse(str);
    } catch (e) {
        return false;
    }
    return true;
}

function sleep(milliseconds) {
  var start = new Date().getTime();
  for (var i = 0; i < 1e7; i++) {
    if ((new Date().getTime() - start) > milliseconds){
      break;
    }
  }
}

function GetXmlHttpObject(){
	
	if (window.XMLHttpRequest)  {
	  // code for IE7+, Firefox, Chrome, Opera, Safari
	  return new XMLHttpRequest();
	 }
	
	if (window.ActiveXObject) {
	  // code for IE6, IE5
	  return new ActiveXObject("Microsoft.XMLHTTP");
	 }
	 
	return null;
}
	
// for live search 
function showResult(str,elementid){	
	
	if(elementid!=undefined){
		UInter = elementid;
	}else{
		UInter	='txtHint';
		
	}
	
	if (str.length!=0) {
					
		makeRequest(str);			
	}	
	

}

// This function is used to populate a data form
function getFormData(parameters,action,frmid){
	// this should be on the form
	//document.getElementById('status').innerHTML ="<p style='text-align:center;color:#B8CBDA;'>loading your request..<br><img src='../images/loading.gif'></p>";
	iface = 'form';	
	checkForSelectedCheckbox();	
	
	myData ="";
	
	
	if(action=='edit'){
		action = 'edit';	
	}else{
		if(id!="" && action!='add' && action!='delete'){
			action = 'update';	
		}		
	}
	
	makeRequest(parameters + '&id=' + id + '&action='+ action);
	
	// check see if we should reload grid
	if(action=='add' || action=='delete'){
		//iface ='';		
		//sleep(2000);
		//document.getElementById('txtHint').innerHTML = "<p style='text-align:center;color:#B8CBDA;'>loading your request..<br><img src='../images/loading.gif'></p>";
		makeRequest(parameters);
		
	}

	//document.getElementById(id).checked = true;
		
	if(action=='edit'){
		UInter ="";
		document.getElementById('action').value = 'update';
	}else{
		//commented on 6/01/2011
		//document.getElementById('action').value = 'add';
	}
}

// This function is used to populate a inner form data
function getInnerFormData(parameters,action,frmid,elementid){
	checkForSelectedCheckbox();
	
	if(Ischeckboxselected==false){
		alert('Please select item');
		return false;
	}
	// this should be on the form
	if(elementid!=undefined){
		//document.getElementById(elementid).innerHTML ="<p style='text-align:center;color:#B8CBDA;'>loading your request..<br><img src='../images/loading.gif'></p>";
		UInter = elementid;
	}else{
		UInter = '';	
	}
	iface = 'form';	
	
	document.getElementById('action').value = 'add';
	
	myData ="";
	
	makeRequest(parameters + '&id=' + id + '&action='+ action);
	
	// check see if we should reload grid
	if(action=='delete' || action=='add'){
		iface ='';
		
		//sleep(2000);
		//document.getElementById('txtHint2').innerHTML = "<p style='text-align:center;color:#B8CBDA;'>loading your request..<br><img src='../images/loading.gif'></p>";
		//makeRequest(parameters);
	}

}

// This function is used to check clicked checkbox and uncheck the rest of the checkboxes
function uncheck(eID,frmId){

	var field = document.forms[frmId];

    for (i=0;i<field.length;i++) {		
		if(field.elements[i].type == "checkbox"){
			if(field.elements[i].checked==true){
				field.elements[i].checked = false;						
				document.getElementById(eID).checked = true;
				document.getElementById("display").style.display = 'block';
				document.getElementById("display").innerHTML = document.getElementById(eID).value;				 
			}else{		
				document.getElementById("display").innerHTML ='';				
			}	
		}
	
	}
	
}


function displayNotification(myJasonStr){
	
	

	var Jason_array = JSON.parse(myJasonStr);


	
	$(function () {
		  $.notifyBar({
			html:Jason_array['msg'],
			delay: 902000,
			cls: "success",
            animationSpeed: "normal",		 
			onShow:function () {eval(Jason_array['callback']);}
			
		  });
		  return;  
	});
	
}

 //IsJsonString(str)

function clearTextFied(eID){
	document.getElementById(eID).value = "";	
}

function fillTextFied(eID,text){
	document.getElementById(eID).value = text;	
}

function getdata(paging,formid,action,searchterm) { 
	str= paging + '&frmid='+ formid+ '&n='+searchterm;
 	//document.getElementById('txtHint').innerHTML = "<p style='text-align:center;'>loading your request..<br><img src='images/loading.gif'></p>";
	makeRequest(str);	
 }