<?php
require_once('../includes/application_top.php');
 $_parent = basename(__FILE__);
 

?>
<script  type="text/javascript" language="javascript">

var url ='';
var iface = '';

url="../addedit.php";
 
function updateForm(){
	
	if(IsNullEmptyField('organisationname',"<?php echo $lablearray['9'];?> <?php echo $lablearray['291'];?>") && IsNullEmptyField('branch_code',"<?php echo $lablearray['996'];?> <?php echo $lablearray['291'];?>")){
	
		getFormData('frmid=frmbankbranches&bankbranches_id='+ document.getElementById('bankbranches_id').value + '&branch_code='+document.getElementById('branch_code').value+'&organisationname='+ document.getElementById('organisationname').value+'&licence_build='+ document.getElementById('licence_build').value,document.getElementById('action').value,'frmbankbranches');		
		showResult('frmid=frmbankbranches&licence_build='+document.getElementById('licence_build').value,'txtHint');
		document.getElementById('organisationname').value ="";
		document.getElementById('branch_code').value ="";
		document.getElementById('bankbranches_id').value ="";
		document.getElementById('action').value ="add";
	}
} 

</script>
<?php
	require('../'.DIR_WS_INCLUDES . 'pageheader.php');
	getlables("994,242,998,996,890");
?>
<fieldset>
<form action="managebankbranches.php?action=insert" method="post" style="width:100%;height:auto;" id='frmbankbranches' name='frmbankbranches' onReset="document.getElementById('action').value='add';">
		
			<input name="bankbranches_id" type="hidden"  id="bankbranches_id" value="">
			<input name="action" type="hidden"  id="action" value="add">
			 
			 <table width="100%" border="0" cellspacing="0" cellpadding="0">
				<tr>
				<td  colspan="2" align="center">
				<table width="100%" border="0" cellspacing="2" cellpadding="0">
					<tr>
					<td colspan="2" align="center"><span id="status" style='color:#006600;'></span></td>
				  </tr>
				   <tr>
					<td colspan="2" align="center"></td>
				  </tr>
				   <tr>
					<td align="right" valign="middle"><?php echo $lablearray['994'];?></td>
					<td ><?php echo DrawComboFromArray('licence_build','licence_build','','banks',"showResult(\"frmid=frmbankbranches&id=\" + this.value)");?><?php echo TEXT_FIELD_REQUIRED;?></td>
				  </tr>
				  <?php
				
					getlables("994,242,890,996");
				?>
				   <tr>
					<td align="right" valign="middle"><?php echo $lablearray['890'];?></td>
					<td ><?php echo tep_draw_input_field('organisationname','','',false,'text','','50'); ?><?php echo TEXT_FIELD_REQUIRED;?></td>
				  </tr>
                  <tr>
					<td align="right" valign="middle"><?php echo $lablearray['996'];?></td>
				<td ><?php echo tep_draw_input_field('branch_code','','',false,'text','','50'); ?><?php echo TEXT_FIELD_REQUIRED;?></td>
				  </tr>
				  <tr height="25">
					<td ></td>
					<td ></td>
				</tr>
				 <tr height="25">
					<td align="right"></td>
					<td  align="center">&nbsp;<input  type="reset" value="  <?php echo $lablearray['242'];?>  " id='reset' class="actbutton"><input  id="save" type="button" value="  Save  "  onClick="updateForm()" class="actbutton"></td>
				</tr>				 
				</table>				
				
                                    
				<tr>
					<td colspan="2" id='txtHint' align="center"></td>
				</tr>							
				</table> 


			</form>	
			 <script language="JavaScript"  type="text/javascript">
			  showValues('frmbankbranches', 'txtHint', 'search','BANKBRANCH', 'load.php');
		  </script>				
			
	</fieldset>			
		 	  
</BODY>
</HTML>
