<?php
require_once('../includes/application_top.php');
$_parent = basename(__FILE__);
$glaccounts = getAccountLevels(0,'');
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3c.org/TR/1999/REC-html401-19991224/loose.dtd">
<HTML lang="<? echo $_SESSION['P_LANG'];?>"><HEAD>
<TITLE></TITLE>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<META content="MSHTML 6.00.2900.3268" name=GENERATOR>
<LINK href="../stylesheet.css" type=text/css rel=stylesheet>
<script type="text/javascript" src="../includes/javascript/gridsearch.js" language="javascript"></script>
<link  media="screen"  type="text/css" rel="stylesheet" href="../includes/javascript/de.css"></link>
<script type="text/javascript" src="../includes/javascript/jquery-1.6.2.min.js"></script>
<script type="text/javascript" src="../includes/javascript/jquery-ui-1.8.16.custom.min.js"></script>
<script type="text/javascript" src="../includes/javascript/jquery.pnotify.js"></script>
<script type="text/javascript" src="../includes/javascript/collapsiblepanel.js"></script>
<script type="text/javascript" src="../includes/javascript/commonfunctions.js"></script>
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
<?php require('../'.DIR_WS_INCLUDES . 'initmenu.php'); ?>
<style type="text/css">
<!--
body,td,th {
	font-size: 0.9em;
}
-->
</style><form action="#" method="post" style="width:100%;height:auto;" id='frmtaxes' name='frmtaxes' onReset="document.getElementById('action').value='add';">
			<h1>Configure  Tax Rates</h1>
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
					<td >Tax<br><?php echo tep_draw_input_field('taxes_name','','',false,'text',$retainvalues,'100'); ?><?php echo TEXT_FIELD_REQUIRED;?></td>
				  </tr>
				  <tr height="25">
					<td >GL Account</td>
					<td ><?php 
				
					echo DrawComboFromArray($glaccounts,'chartofaccounts_accountcode','');?></td>
				</tr>

				 <tr height="25">
					<td align="right"></td>
					<td  align="center">&nbsp;<input  type="reset" value="  Clear  " id='reset' class="actbutton"><input  id="save" type="button" value="  Save  "  onClick="updateForm()" class="actbutton"></td>
				</tr>
				</table>
				<tr>
					<td colspan="2" align="center">
						<table width="100%" border="0" cellspacing="1" cellpadding="0">
						  <tr>
							<td  colspan="2" align="right">Search <input name="search"  value="" type="text" size="50" id='search' onKeyUp="showResult('searchterm='+this.value+'&frmid=frmtaxes&action=search','txtHint')"/><input type="button" value="Search" onClick="showResult('frmid=frmtaxes&action=search&searchterm='+document.getElementById('search').value,'txtHint');" class="actbutton"><input name="download" type="button" id="download" onClick="parent.document.location='downloadlist.php?columncheck=7&Fee_Categories&timestamp=<?php echo strtotime("now"); ?>'" value="Download" class="actbutton"></td>
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
<?php require('../'.DIR_WS_INCLUDES . 'userfooter.php'); ?>
		  <script language="JavaScript"  type="text/javascript">
			 showResult('frmid=frmtaxes','txtHint')
		  </script>

</BODY>
</HTML>
