<?php
require_once('../includes/application_top.php');
$_parent = basename(__FILE__);
$glaccounts = getAccountLevels();

$users_query = tep_db_query("SELECT u.user_usercode,user_id,user_username,CONCAT(user_firstname,' ',user_lastname) As Name FROM " . TABLE_USERS." u, ".TABLE_USERBRANCHES." ub WHERE ub.user_usercode=u.user_usercode AND ub.operatorbranches_code='".$_SESSION['operatorbranches_code']."' ORDER BY u.user_id DESC");
	
$users_array = array();
while ($users = tep_db_fetch_array($users_query)) {
   	$users_array[$users['user_usercode']] = $users['Name'];
}?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3c.org/TR/1999/REC-html401-19991224/loose.dtd">
<HTML lang="<? echo $_SESSION['P_LANG'];?>"><HEAD>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<META http-equiv=Content-Type content="text/html; charset=utf8">
<TITLE></TITLE>
<LINK href="../stylesheet.css" type=text/css rel=stylesheet>
<link rel="stylesheet" href="../styles/CALENDAR.CSS">
<script language="JavaScript" src="../includes/javascript/calendar_us.js"></script>
<script language="JavaScript" src="../includes/javascript/commonfunctions.js"></script>
<script type="text/javascript" src="../includes/javascript/gridsearch.js" language="javascript"></script>
<link  media="screen"  type="text/css" rel="stylesheet" href="../includes/javascript/de.css"></link>
<script type="text/javascript" src="../includes/javascript/jquery-1.6.2.min.js"></script>
<script type="text/javascript" src="../includes/javascript/jquery-ui-1.8.16.custom.min.js"></script>
<script type="text/javascript" src="../includes/javascript/jquery.pnotify.js">
</script>
<script language="JavaScript"  type="text/javascript">

function viewreport(){
	
	//if(!IsNullEmptyField('txtFrom','Please select date') || !IsNullEmptyField('txtTo','Please select date')){
		//return;
	//}	
		
	window.open('../downloadlistpdf.php?rcode=TRANSFERREPORT&txtFrom='+document.getElementById('txtFrom').value+'&txtTo='+document.getElementById('txtTo').value+'&transfer_status='+document.getElementById('transfer_status').value+'&ucode='+document.getElementById('user_id').value+'&currencies_code='+document.getElementById('currencies_code').value)
	
}

</script>

<?php require('../'.DIR_WS_INCLUDES . 'initmenu.php'); 

	getlables("9,42,70,79,38,39,194,246,247,248,242,189,199,191,249,980,981,982,962,895,987,981,982,962,988");
?>
<style type="text/css">
<!--
body,td,th {
	font-size: 0.9em;
}
-->
</style><fieldset>
			<form action="#" method="post" style="width:100%;height:auto;" id='frmStudentreport' name='frmStudentreport' onReset="document.getElementById('action').value='add';">

			<input name="action" type="hidden"  id="action" value="add">
			 
		<span id="status" style='color:#006600;'></span>
				
					
					
					<table width="100%" border="0" cellspacing="0" cellpadding="0">
					  <tr>
						<td nowrap>
						<?php echo $lablearray['38'];?></td><td valign="middle"><input name="txtFrom" class="yellowfield"  id="txtFrom" type="text" size="15" width="32" value="<?php echo date('m/d/Y');?>"  readonly/>
							<script language="JavaScript">
								new tcal ({
									// form name
									'formname': 'frmStudentreport',
									// input name
									'controlname': 'txtFrom'
								});
							</script>&nbsp;<?php echo $lablearray['39'];?>
							
							 <input name="txtTo" class="yellowfield"  id="txtTo" type="text" size="15" width="32" value="<?php echo date('m/d/Y');?>"  readonly/>
							<script language="JavaScript">
								new tcal ({
									// form name
									'formname': 'frmStudentreport',
									// input name
									'controlname': 'txtTo'
								});
							</script>                          
                            <?php echo TEXT_FIELD_REQUIRED;?>
							
							</td>
					  </tr>
					  <tr>
					  <td><?php echo $lablearray['895'];?></td>
					  <td>
					  
					  
								<select id="currencies_code" name="currencies_code" >
								<option id="" name=""> </option>
								<?php 
								
								$currency_results =tep_db_query("SELECT currencies_code,name,currencies_id FROM ".TABLE_CURRENCIES." ORDER BY name");
								
								while($currency = tep_db_fetch_array($currency_results)){
									echo "<option id='".$currency['currencies_code']."' value='".$currency['currencies_code']."'>".$currency['name'].":  ".$currency['currencies_code']."</option>";
								}
								?>
								</select>
					  
					  </td>
					  </tr>
					  <tr>
                        <td ><?php echo $lablearray['980'];?></td><td>
                           <select name="transfer_status" id="transfer_status" >   
						   		<option id="" value=""></option>                         	                          	
                          		<option id="A" value="A"><?php echo $lablearray['981'];?></option> 
							 	<option id="C" value="C"><?php echo $lablearray['982'];?></option>
								<option id="S" value="S"><?php echo $lablearray['962'];?></option>	
								<option id="P" value="P"><?php echo $lablearray['988'];?></option>														
						    </select>
                        </td>
                      </tr>
                        <tr>
                        <td ><?php echo $lablearray['987'];?></td><td>
                          <select id="user_id" name="user_id">
							<option id="" value=""><?php echo $lablearray['42']?></option>
							<?php foreach($users_array as $key=>$value){?>			
							
									<option id="<?php echo $key;?>" value="<?php echo $key;?>"><?php echo $value;?></option>		
							
							<?PHP } ?>
							</select>
                        </td>
                      </tr>
					
					   <tr height="25">
					<td colspan="2" align="center" ><input  type="button" value="    <?php echo $lablearray['248'];?>    " id='reset' class="actbutton" onClick="viewreport()"><input  type="reset" value="  <?php echo $lablearray['242'];?>   " id='reset' class="actbutton"></td>
				
				</tr>
					</table>
								
		
		
</fieldset>
 </form>
 <?php require('../'.DIR_WS_INCLUDES . 'userfooter.php'); ?>

 
</BODY>
</HTML>