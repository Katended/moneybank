<?php
 require_once('../includes/application_top.php');
// require_once("../simple-php-captcha-master/simple-php-captcha.php");
// require_once('../includes/classes/common.php');
// $_SESSION['captcha'] = simple_php_captcha(array(),'TRANSFER');
 
spl_autoload_register(function ($class_name) {
    include '../includes/classes/'.$class_name . '.php';
});


// get roles
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

	$roles_query = tep_db_query("SELECT roles_id,".$roles_name." as roles_name FROM " . TABLE_ROLES);
	$roles_array = array();	
	
	while ($roles = tep_db_fetch_array($roles_query)) {
      	$roles_array[$roles['roles_id']] = $roles['roles_name'];
	}
	getlables("579,580");
	
getlables("3,2,37,20,21,267,582,587");
$_parent = basename(__FILE__); 
require('../'.DIR_WS_INCLUDES . 'pageheader.php'); 
?>

<script language="JavaScript"  type="text/javascript">


</script>
<form action="" method="post" id='frmuserroles' name='frmuserroles' onReset="document.getElementById('action').value='add';">
		
			<input name="user_id" type="hidden"  id="user_id" value="">
			<input name="action" type="hidden"  id="action" value="add">
			
        <input name="theid" type="hidden" id="theid" value="">  
			 <table width="100%" border="0" cellspacing="0" cellpadding="0">
				<tr>
				<td  colspan="2">
				<table width="100%" border="0" cellspacing="2" cellpadding="0">
				  				  
				  
				  </tr>
				  
				  <tr>
				  	
				  	<TD >															
					<?php echo $lablearray['37']?><?php echo generateCheckBoxList($roles_array,array(),'Roles','','roles',true);?>					
					</td>
					<TD>
                                            <span id="Name"></span>
                                            <span id="user_username"></span>
                                            <span id="user_email_address"></span>
                                            <span id="last_login"></span>                                        
                                        </TD>
				  </tr>  				         
				 
							 
				</table>				
				<tr>
					<td colspan="2" align="center">
						<table width="100%" border="0" cellspacing="1" cellpadding="0">						 
						  <tr>
							<td  colspan="2" align="right"><button type="reset"  name="btnReset" class="btn"><?php echo $lablearray['2']; ?></button><button type="button" class="btn" name="btnSave" id="btnSave"><?php echo $lablearray['20']; ?></button></td>
							<td></td>
						  </tr>
						</table>					
					 </td>
				</tr>
				<tr>
					<td colspan="2" id='txtHint' align="center"></td>
				</tr>							
				</table> 


			</form>					
		
			
		  <script language="JavaScript"  type="text/javascript">
                       $( document).ready(function(){
                               var aselectedroles =[];
                               url = "../addedit.php";
                           $("#btnSave").click(function(){
                                   
                                   var roles  =  document.getElementsByName("roles[]");

                                    aselectedroles.length = 0;

                                   if(typeof(roles.length)!='undefined'){
                                       for (i=0; i<roles.length; i++){
                                           if (roles[i].checked==true){
                                                aselectedroles.push(roles[i].value);	
                                            }
                                       }	
                                   }

                                  // var pageinfo = JSON.stringify($("#user_id").serializeArray());
                                  // var data1 = JSON.stringify(JSON.parse('{"pageinfo":' + pageinfo + "}"));
                                   var selectedroles = JSON.stringify(aselectedroles);
                            
                                   var data2 = JSON.parse('{"roles":' + selectedroles + "}");
                                  // var object = $.extend({}, data1, data2);
                                   var pagedata = JSON.stringify(data2);     
                                   
                                   showValues('frmuserroles', $("#user_id").val(), 'add', pagedata, 'addedit.php');

                           }); 
                           
                           showValues('frmuserroles','txtHint','search','USERROLES','load.php','');
                        });
			function getinfo(frm_id,theid,action,pagedata,urlpage,element){	
                            showValues('frmuserroles',theid,'edit','',urlpage,element);
                        }
		  </script>
	  
</BODY>
</HTML>
