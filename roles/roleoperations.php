<?php
require_once('../includes/application_top.php');
$_parent = basename(__FILE__);
	$users_query = tep_db_query("SELECT user_id,user_username,CONCAT(user_firstname,' ',user_lastname) As Name FROM " . TABLE_USERS." u ORDER BY u.user_id DESC");
	
	$users_array = array();
	while ($users = tep_db_fetch_array($users_query)) {
      	$users_array[$users['user_id']] = $users['Name'];
	}

	// get operations
	switch($_SESSION['P_LANG']){
				
case 'EN':

	$operations_description_lang ='operations_description_eng';

	break;

case 'FR':

	$operations_description_lang ='operations_description_fr';
	
	break;
	
case 'SWA':

	$operations_description_lang ='operations_description_sa';
	
	break;

case 'JA':
	
	$operations_description_lang ='operations_description_ja';
	
break;

case 'SP':
	
	$operations_description_lang ='operations_description_sp';
	
	break;

case 'LUG':

	$operations_description_lang ='operations_description_lug';
	
	break;

default:

	$operations_description_lang ='operations_description_eng';
	
	break;
}
	$operations_query = tep_db_query("SELECT operations_id,".$operations_description_lang." as operations_description FROM " . TABLE_OPERATIONS);
	$operations_array = array();	
	
	while ($operations = tep_db_fetch_array($operations_query)) {
      	$operationss_array[$operations['operations_id']] = $operations['operations_description'];
	}
	
	
	getlables("583,592");
		
?>

<?php require('../'.DIR_WS_INCLUDES . 'initmenu.php'); 

getlables("582,20,591,592,159,42");
?>
<form action="frmroleoperations.php?action=insert"  id='frmroleoperations' name='frmroleoperations' onReset="document.getElementById('action').value='add';">

<input name="roles_id" type="hidden"  id="roles_id" value="">
<input name="action" type="hidden"  id="action" value="add">
 <h2 id="roles"></h2>
 <table width="100%" border="0" cellspacing="0" cellpadding="0">
	<tr>
	<td colspan="2">
	<table width="100%" border="0" cellspacing="2" cellpadding="0">
					  
	 <tr height="36">
		<td colspan="3" align="center" >
		<a href="#demo2_tip" onMouseOver="tooltip.pop(this, '#demo2_tip')"> <img  src="../images/info.png" border="0" style="margin:0px;"></a>
			 <div style="display:none;float:right;width:900px;">
			<div id="demo2_tip">
				 <?php echo $lablearray['591'];?>          
			</div>
		</div>
		</td>
		
	  </tr>
	  
	   <tr height="26">
		<td id="roles_name" colspan="3"></td>
		
	  </tr>
	   <tr height="26">
	 
		<td >
		<p><?php echo $lablearray['159']?></p>
			<select id="user_id" name="user_id">
			<option id="" value=""><?php echo $lablearray['42']?></option>
			<?php foreach($users_array as $key=>$value){?>			
			
					<option id="<?php echo $key;?>" value="<?php echo $key;?>"><?php echo $value;?></option>		
			
			<?PHP } ?>
			</select>
		</td>
		<td id="modulescontent" valign="top"></td>
		<td id="operationscontent" valign="top"></td>
	  </tr>
	  
	
	 <tr height="30px">
		 <td align="right"></td>
		<td align="right"></td>
		<td  align="right">&nbsp;<input  id="save" type="button" value="  <?php echo $lablearray['20']?>  "  onClick="updateForm()" class="actbutton"></td>
	</tr>	
	<tr>
		<td colspan="3" id='txtHint' align="center"></td>
	</tr>				 
	</table>				
	</td>
	</tr>							
</table>

</form>					
      
<script language="JavaScript"  type="text/javascript">
     showResult('frmid=frmroleoperations','txtHint')
</script>

<script language="JavaScript"  type="text/javascript">
var url ='';
var iface = '';

url="../addedit.php";
 
function updateForm(){
	

	var selectedmodules ='';
	var selectedoperations ='';
	
	if(typeof(document.frmroleoperations.operations)!='undefined'){
			
		// get selected checkboxes
		for (i=0; i<document.frmroleoperations.operations.length; i++){
		
			if (document.frmroleoperations.operations[i].checked==true){
				selectedoperations = selectedoperations + '&p'+document.frmroleoperations.operations[i].value + '=' + document.frmroleoperations.operations[i].value;
			}
		}
	
	}else{
	
		alert('Operation(s) not selected. Edit role to set security');
		return;
	}
	
	if(typeof(document.frmroleoperations.modules)!='undefined'){
			
		// get selected checkboxes
		for (i=0; i<document.frmroleoperations.modules.length; i++){
			if (document.frmroleoperations.modules[i].checked==true)
			selectedmodules =selectedmodules + '&modules_id='+document.frmroleoperations.modules[i].value;
		}
	
	}else{
	
		alert('Module(s) not selected. Edit role to set security');
	}
	
	if(selectedmodules==""){
		alert('No modules selected. Please select a module to which you want to grant access(operation)');
	}
	
			
	getFormData('frmid=frmroleoperations&roles_id=' + document.getElementById('roles_id').value + selectedmodules+selectedoperations+'&user_id='+document.getElementById('user_id').value,document.getElementById('action').value,'frmroleoperations');
	//document.getElementById('roles_id').value ="";
	showResult('frmid=frmroleoperations&action=edit&id='+document.getElementById('roles_id').value,'')

} 

function getRoleOperations(theId){

	showResult('frmid=frmroleoperations&action=getmoduleoperations&modules_id='+theId+'&roles_id='+document.getElementById('roles_id').value,'')

}
</script>
	  
</BODY>
</HTML>
