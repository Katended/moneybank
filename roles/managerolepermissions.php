<?php
 require_once('../includes/application_top.php');

$_parent = basename(__FILE__);
$code_array = unserialize($_SESSION['_CAPTCHA']['config']);//['code'];
 
 
$roles_array = array();
		// set language for roles
switch($_SESSION['P_LANG']){
				
case 'EN':
	$roles_name = 'roles_name_eng';
	$charges_name_fieldname='charges_name_en';
	$operations_description_lang ='operations_description_eng';
	$Language = 'translations_eng';
	break;

case 'FR':
	$roles_name = 'roles_name_fr';
	$charges_name_fieldname='charges_name_fr';
	$operations_description_lang ='operations_description_fr';
	$Language = 'translations_fr';
	break;
	
case 'SWA':
	$roles_name = 'roles_name_sa';
	$charges_name_fieldname='charges_name_sa';
	$operations_description_lang ='operations_description_sa';
	$Language = 'translations_sa';
	break;

case 'JA':
	$roles_name = 'roles_name_ja';
	$charges_name_fieldname='charges_name_ja';
	$operations_description_lang ='operations_description_ja';
	$Language = 'translations_ja';
break;

case 'SP':
	$roles_name = 'roles_name_sp';
	$operations_description_lang ='operations_description_sp';
	$charges_name_fieldname='charges_name_sp';
	break;

case 'LUG':
	$roles_name = 'roles_name_lug';
	$charges_name_fieldname='charges_name_lug';
	$operations_description_lang ='operations_description_lug';
	$Language = 'translations_lug';
	break;

default:
	$roles_name = 'roles_name_eng';
	$charges_name_fieldname='charges_name_eng';
	$operations_description_lang ='operations_description_eng';
	$Language = 'translations_eng';
	break;
}

	$roles_query = tep_db_query("SELECT modules_id,modules_description FROM ". TABLE_MODULES." ORDER BY modules_description ASC");
	
	while ($roles = tep_db_fetch_array($roles_query)) {
      	$roles_array[$roles['modules_id']] = $roles['modules_description'];
	}
	
	// get persmissions
	$cashaccounts_query = tep_db_query("SELECT chartofaccounts_accountcode,cashaccounts_name FROM " . TABLE_CASHACCOUNTS." ORDER BY chartofaccounts_accountcode ASC");
	$cashaccounts_array = array();	
	
	while ($cashaccounts = tep_db_fetch_array($cashaccounts_query)) {
      	$cashaccounts_array[$cashaccounts['chartofaccounts_accountcode']] = $cashaccounts['chartofaccounts_accountcode']."".$cashaccounts['cashaccounts_name'];
	}
getlables("345,79,344,574,26,575,20,641");

	  $_parent = basename(__FILE__);

getlables("344,641");
?>
<script language="JavaScript"  type="text/javascript">

var url ='';
var iface = '';

url = "../addedit.php";
 

</script>
<?php require('../'.DIR_WS_INCLUDES . 'pageheader.php'); 
getlables("345,79,344,574,26,575,20,590,592,641,344");
?>
<form  id='frmrolemodules' name='frmrolemodules' onReset="document.getElementById('action').value='add';">
<input name="theid" type="hidden"  id="theid" value="">			
<input name="roles_id" type="hidden"  id="roles_id" value="">
<input name="action" type="hidden"  id="action" value="add">

 <table width="100%" border="0" cellspacing="0" cellpadding="0">
	<tr>
	<td  colspan="2"><fieldset>
	<table width="100%" border="0" cellspacing="2" cellpadding="0">
					  
	
	  
	  <tr>
	  
		<TD colspan="2" >	
		<p align="center"><h2 id="roles_name"></h2></p>									
		<table cellspacing="0" width="100%" >
			<TR>
		
			<TD align="left"valign="top" >
			<fieldset><legend><?php echo $lablearray['592']?></legend><?php echo generateCheckBoxList($roles_array,array(),'Modules','','permissions',true);?></fieldset></TD>
			
			<TD lign="right" colspan="2" valign="top">
			
			<fieldset><legend><?php echo $lablearray['26']?></legend><?php echo generateCheckBoxList($cashaccounts_array,array(),'cashaccounts','','cashaccounts',true);?></fieldset></TD>
			
					
			</TD>
			</tr>
			 
		</table>
		</td>
		
	  </tr>  				         
	 
	 <tr height="30px">
		<td align="right"></td>
		<td  align="right"><button class="btn" name="btnSave" id="btnSave"  type="button"> <?php echo $lablearray['20'];?> </button></td>
	</tr>				 
	</table>				
	</fieldset>
	</td>
	</tr>
	<tr>
		<td colspan="2" align="center" valign="top" id='txtHint'></td>
	</tr>							
	</table> 


</form>					
 <script language="JavaScript"  type="text/javascript">
 
$( document ).ready(function() {
    
    // showValues('frmrolemodules', 'txtHint', 'search','ROLEPERSMISSIONS', 'load.php','');
    
 	showValues('frmrolemodules','txtHint','search','ROLEPERSMISSIONS','load.php','');

	 url = "../addedit.php";

        var selectedpermissions =[];
	var selectedcahsaccounts =[];
	  
	$( "#btnSave" ).click(function() {	
	

		
                
		var permissions  =  document.getElementsByName("permissions[]");
		var cashaccounts  =  document.getElementsByName("cashaccounts[]");

                selectedpermissions.length = 0;
                
                if(typeof(permissions.length)!='undefined'){
                   for (i=0; i<permissions.length; i++){
                       if (permissions[i].checked==true){
                            selectedpermissions.push(permissions[i].value);	
                        }
                   }	
               }
                  
                  selectedcahsaccounts.length = 0;
                 if(typeof(cashaccounts.length)!='undefined'){
                   for (i=0; i<cashaccounts.length; i++){
                       if (cashaccounts[i].checked==true){
                            selectedcahsaccounts.push(cashaccounts[i].value);	
                        }
                   }	
               }
               
                var perm = JSON.stringify(selectedpermissions);
                var cash= JSON.stringify(selectedcahsaccounts);
                var data1 = JSON.parse('{"permissions":' + perm + "}");
                var data2 = JSON.parse('{"cashaccounts":' + cash + "}");
                var pageinfo = JSON.stringify($("#frmrolemodules").serializeArray());
             
                var data3 = JSON.parse('{"pageinfo":'+pageinfo+"}");
        
		var object = $.extend({}, data1, data2,data3);
                var pagedata = JSON.stringify(object);  
            
                showValues('frmrolemodules', '', 'add', pagedata, 'addedit.php').done(function () {                                
                   showValues('frmrolemodules','txtHint','search','ROLEPERSMISSIONS','load.php','');
                });
                                
                
		
	});	

});


function getinfo(frm_id,theid,action,pagedata,urlpage,element){	
     $('#modules').find('input[type=checkbox]:checked').removeAttr('checked');
      showValues('frmrolemodules',theid,'edit','','load.php',element);
 }
</script>
	  
</BODY>
</HTML>
