<?php
require_once('../includes/application_top.php');

 $glaccounts = getAccountLevels();
 $query_result = tep_db_query("SELECT bankaccounts_accno,chartofaccounts_accountcode,bb.bankbranches_id,bankbranches_name,(SELECT banks_name FROM ".TABLE_BANKS." WHERE banks_id=bb.banks_id) as banks_name FROM ". TABLE_BANKACCOUNTS." as bc,".TABLE_BANKBRANCHES." as bb WHERE bc.bankbranches_id=bb.bankbranches_id"); 
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3c.org/TR/1999/REC-html401-19991224/loose.dtd">
<HTML lang="<? echo $_SESSION['P_LANG'];?>"><HEAD>
<TITLE></TITLE>
<meta http-equiv="Content-Type" content="text/html; charset=utf8">
<META content="MSHTML 6.00.2900.3268" name=GENERATOR>
<LINK href="../stylesheet.css" type=text/css rel=stylesheet>
<link rel="stylesheet" href="../styles/CALENDAR.CSS"> 
<script language="JavaScript" src="../includes/javascript/calendar_us.js"></script>
<script type="text/javascript" src="../includes/javascript/commonfunctions.js"></script>
<script language="JavaScript"  type="text/javascript">

var url ='';
var iface = '';
url="../addedit.php";

var from ='<?php echo $_POST['txtFrom'];?>';
var to = '<?php echo $_POST['txtto'];?>';

if(from!="" && to!=""){
	window.open('downloadlistpdf.php?rcode=TB', 'open_window', 'menubar, toolbar, location, directories, status, scrollbars, resizable, dependent, width=640, height=480, left=0, top=0')
}

function printOptions(){
	if(!IsNullEmptyField('txtprintoptions','Please select a print option') || !IsNullEmptyField('txtFrom','Please select date') ||  !IsNullEmptyField('txtTo','Please select date')){
		return;
	}else{
		url=document.getElementById('txtprintoptions').value+'rcode=RECON&txtTo='+document.getElementById('txtTo').value+'&txtFrom='+document.getElementById('txtFrom').value;
		openPopupListWindow(url);
	}
}
</script>
<?php require('../'.DIR_WS_INCLUDES . 'initmenu.php'); ?>
<fieldset>
<form action="trialbal.php?action=insert" method="post" style="width:100%;height:auto;" id='frmtrialbalance' name='frmtrialbalance' onReset="document.getElementById('action').value='add';">
	<input name="bankbranches_id" type="hidden"  id="bankbranches_id" value="">
	<input name="action" type="hidden"  id="action" value="add">
	<input name="txtprintoptions" type="hidden"  id="txtprintoptions" value="">
			 
			 <table border="0" cellspacing="0" cellpadding="0">
				<tr>
				<td  colspan="2">
				<table  border="0" cellspacing="2" cellpadding="0">
					<tr>
					<td colspan="2" align="center"><span id="status" style='color:#006600;'></span></td>
				  </tr>
				   <tr>
					<td colspan="2" align="center"><?php echo TEXT_FIELD_REQUIRED;?>Required</td>
				  </tr>
				  
				  <tr>
					<td colspan="2" align="center">
					
					
					<table  border="0" cellspacing="0" cellpadding="0">
					  <tr>
						<td>From&nbsp;</td><td><input name="txtFrom" class="yellowfield"  id="txtFrom" type="text" size="15" width="32" value="<?php echo changeMySQLDateToPageFormat($students_dateenrolled);?>"  readonly/>
							<script language="JavaScript">
								new tcal ({
									// form name
									'formname': 'frmtrialbalance',
									// input name
									'controlname': 'txtFrom'
								});
							</script><?php echo TEXT_FIELD_REQUIRED;?>&nbsp;</td><td>To &nbsp;</td><td>
							 <input name="txtTo" class="yellowfield"  id="txtTo" type="text" size="15" width="32" value="<?php echo changeMySQLDateToPageFormat($students_dateenrolled);?>"  readonly/>
							<script language="JavaScript">
								new tcal ({
									// form name
									'formname': 'frmtrialbalance',
									// input name
									'controlname': 'txtTo'
								});
							</script>                          
                            <?php echo TEXT_FIELD_REQUIRED;?> 
							</td>
					  </tr>
					   <tr>
						<td>From Account:&nbsp;</td><td>
						<?php echo $lablearray['35'];?> &nbsp;<br><select id='bankaccounts_accnofrom' name='bankaccounts_accnofrom' onChange="getGLAccount(this.value)">
							<option id="" value="">Select Account...</option>
							<?php while ($bankaccounts  = tep_db_fetch_array($query_result)){
								echo "<option id=".$bankaccounts['chartofaccounts_accountcode']." value=".$bankaccounts['chartofaccounts_accountcode'].">".$bankaccounts['chartofaccounts_accountcode']."------".$bankaccounts['bankaccounts_accno'].":".$bankaccounts['bankbranches_name']."</option>";
						
							}
							?>
							</select><?php echo TEXT_FIELD_REQUIRED;			
							
							?> 
						</td><td>To Account:&nbsp;</td>
						<td>
							<?php 
							
							$query_result = tep_db_query("SELECT bankaccounts_accno,chartofaccounts_accountcode,bb.bankbranches_id,bankbranches_name,(SELECT banks_name FROM ".TABLE_BANKS." WHERE banks_id=bb.banks_id) as banks_name FROM ". TABLE_BANKACCOUNTS." as bc,".TABLE_BANKBRANCHES." as bb WHERE bc.bankbranches_id=bb.bankbranches_id"); 
							echo $lablearray['35'];?> &nbsp;<br><select id='bankaccounts_accnoto' name='bankaccounts_accnoto' onChange="getGLAccount(this.value)">
							<option id="" value="">Select Account...</option>
							<?php while ($bankaccounts  = tep_db_fetch_array($query_result)){
								echo "<option id=".$bankaccounts['chartofaccounts_accountcode']." value=".$bankaccounts['chartofaccounts_accountcode'].">".$bankaccounts['chartofaccounts_accountcode']."------".$bankaccounts['bankaccounts_accno'].":".$bankaccounts['bankbranches_name']."</option>";
							
							}
							?>
							</select><?php echo TEXT_FIELD_REQUIRED;?> 
						                      
						</td>
					  </tr>
					</table>
					</td>
				  </tr>
				
				 
				 <tr height="25">
					<td align="right"></td>
					<td  align="right">
				
					
					&nbsp;<img src="../images/PDF.GIF" title="Download to PDF" border="0" class="imgclass" onClick="if(document.getElementById('txtFrom').value=='' || document.getElementById('txtTo').value==''){alert('Invalid date specified. Please check the date range.')}else{openPopupListWindow('../downloadlistpdf.php?rcode=RECON&txtTo='+document.getElementById('txtTo').value+'&txtFrom='+document.getElementById('txtFrom').value+'&accFrom='+document.getElementById('bankaccounts_accnofrom').value+'&accTo='+document.getElementById('bankaccounts_accnoto').value)}"><img src="../images/EXCL.GIF" title="Download to Excel"  onClick="parent.document.location='../downloadlist.php?list=RECON&columncheck=7&filename=Report&timestamp=<?php echo strtotime("now"); ?>'" class="imgclass"></td>
				</tr>				 
				</table>				
				
				 <tr height="25">
					<td align="center" colspan="2" ><?php //echo drawPrintOptions();?></td>
				
				</tr>							
				</table> 


			</form>					
		</fieldset>	
			
	<?php require('../'.DIR_WS_INCLUDES . 'userfooter.php'); ?>	 	  
</BODY>
</HTML>
