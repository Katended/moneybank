<?php
require_once('../includes/application_top.php');
$_parent = basename(__FILE__);
$glaccounts = getAccountLevels();

?>

<script language="JavaScript"  type="text/javascript">

var url ='';
var iface = '';

url="../addedit.php";

function updateForm(){

	if(!IsNullEmptyField('taxes_name','Tax name missing.')){
		return false;
	}

	if(!IsNullEmptyField('chartofaccounts_accountcode','General ledger account missing!')){
		return false;
	}
		
	showResult('frmid=frmtaxes&taxes_id=' + document.getElementById('taxes_id').value + '&taxes_name='+ document.getElementById('taxes_name').value+'&chartofaccounts_accountcode='+document.getElementById('chartofaccounts_accountcode').value+'&action='+document.getElementById('action').value,'');
		document.getElementById('taxes_id').value ="";
		document.getElementById('taxes_name').value ="";
		document.getElementById('action').value ="add";
	
}

</script>
<?php require('../'.DIR_WS_INCLUDES . 'pageheader.php'); ?>
<form action="#" method="post" style="width:100%;height:auto;" id='frmtaxes' name='frmtaxes' onReset="document.getElementById('action').value='add';">
		
			<input name="taxes_id" type="hidden"  id="taxes_id" value="">
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
					<td align="right" valign="bottom"></td>
					<td >Tax name<br><?php echo tep_draw_input_field('taxes_name','','',false,'text','','100'); ?><?php echo TEXT_FIELD_REQUIRED;?></td>
				  </tr>
				  <tr height="25">
					<td >GL Account</td>
					<td ><?php echo DrawComboFromArray($glaccounts,'chartofaccounts_accountcode','','combo',"");?></td>
				</tr>

				 <tr height="25">
					<td align="right"></td>
					<td  align="center">&nbsp;<input  type="reset" value="  Clear  " id='reset' class="actbutton"><input  id="save" type="button" value="  Save  "  onClick="updateForm()" class="actbutton"></td>
				</tr>
				</table>
		
				<tr>
					<td colspan="2" id='txtHint' align="center"></td>
				</tr>
				</table>


			</form>

		  <script language="JavaScript"  type="text/javascript">
                    showValues('frmtaxes', 'txtHint', 'search','TAXES', 'load.php');
			
		  </script>

</BODY>
</HTML>
