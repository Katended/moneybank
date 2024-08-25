<?php
require_once('../includes/application_top.php');


 $glaccounts = getAccountLevels();
?>

<script language="JavaScript"  type="text/javascript">

var url ='';
var iface = '';

url="../addedit.php";
 
function updateForm(){
	if(IsNullEmptyField('banks_id','Bank name missing. Please select the Brank.') && IsNullEmptyField('bankbranches_id','Branch name missing, Please select the Bank branch.') && IsNullEmptyField('chartofaccounts_accountcode','GL Account missing. Please select the GL Account.') && IsNullEmptyField('bankaccounts_accno','Bank Account No missing. Please enter the bank account no.')){
		UInter ='';
		getFormData('frmid=frmbankaccounts&bankbranches_id=' + document.getElementById('bankbranches_id').value + '&banks_id='+ document.getElementById('banks_id').value+'&chartofaccounts_accountcode='+document.getElementById('chartofaccounts_accountcode').value+'&bankaccounts_accno='+ document.getElementById('bankaccounts_accno').value,document.getElementById('action').value,'frmbankaccounts');		
		document.getElementById('bankaccounts_id').value ="";

	}
} 

</script>

<?php require('../'.DIR_WS_INCLUDES . 'pageheader.php');?>
<form action="managefrmbankaccounts.php?action=insert" method="post" style="width:100%;height:auto;" id='frmbankaccounts' name='frmbankaccounts' onReset="document.getElementById('action').value='add';">
		
			<input name="bankaccounts_id" type="hidden"  id="bankaccounts_id" value="">
			<input name="action" type="hidden"  id="action" value="add">
			 
			 <table width="100%" border="0" cellspacing="0" cellpadding="0">
				<tr>
				<td  colspan="2">
				<table width="100%" border="0" cellspacing="2" cellpadding="0">
					<tr>
					<td colspan="2" align="center"><span id="status" style='color:#006600;'></span></td>
				  </tr>
				   <tr>
					<td colspan="2" align="center"><?php echo TEXT_FIELD_REQUIRED;?>Required</td>
				  </tr>
				   <tr>
					<td align="right" valign="bottom">Bank</td>
					<td ><?php echo DrawComboFromArray('','banks_id','','banks',"LoadInfoToCombo()");?><?php echo TEXT_FIELD_REQUIRED;?></td>
				  </tr>
				   <tr>
					<td align="right" valign="bottom">Branch Name</td>
					<td id="branchname"><select id="bankbranches_id" name="bankbranches_id"><option id="" name="" disabled="disabled">Select Bank Branch...</option></select><?php echo TEXT_FIELD_REQUIRED;?></td>
				  </tr>
				   <tr>
					<td align="right" valign="bottom">Account No</td>
					<td ><?php echo tep_draw_input_field('bankaccounts_accno',$bankaccounts_accno,'',false,'text',$retainvalues,'46'); ?><?php echo TEXT_FIELD_REQUIRED;?></td>
				  </tr>
				   <tr>
					<td align="right" valign="bottom">GL Account</td>
					<td ><?php echo DrawComboFromArray($glaccounts,'chartofaccounts_accountcode','')?><?php echo TEXT_FIELD_REQUIRED;?></td>
				  </tr>				  
				   <tr>
					<td align="right" valign="bottom">Branch</td>
					<td ></td>
				  </tr>
                 
				  <tr height="25">
					<td ></td>
					<td ></td>
				</tr>
				 <tr height="25">
					<td align="right"></td>
					<td  align="center">&nbsp;<input  type="reset" value="  Clear  " id='reset' class="actbutton"><input  type="reset" value="  Add New " id='reset' class="actbutton"  onclick="document.getElementById('action').value='add'"><input  id="save" type="button" value="  Save  "  onClick="updateForm()" class="actbutton"></td>
				</tr>				 
				</table>				
				
				<tr>
					<td colspan="2" id='txtHint' align="center"></td>
				</tr>							
				</table> 


			</form>					
<?php require('../'.DIR_WS_INCLUDES . 'userfooter.php'); ?>			
	   <script language="JavaScript"  type="text/javascript">
		//	 showResult('frmid=frmcoa','txtHint')
			
			 
			 function LoadInfoToCombo(){
				  UInter ='branchname';
				if(document.getElementById('banks_id').value!=""){					
					makeRequest('frmid=frmbankaccounts&id='+document.getElementById('banks_id').value+'&action=load')
				}
				
			}
			showValues('frmbankaccounts', 'txtHint', 'search','BANKACCCOUNTS', 'load.php');
			 //showResult('frmid=frmbankaccounts','txtHint')
		  </script>	 	  
</BODY>
</HTML>
