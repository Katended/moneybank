<?php
  require_once('../includes/application_top.php');
 getlables("660,659,661,662,242,21,20,317,322,414,322,661,662");
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3c.org/TR/1999/REC-html401-19991224/loose.dtd">
<HTML lang="<? echo $_SESSION['P_LANG'];?>"><HEAD>
<TITLE>Manage Forex Rates</TITLE>
<meta http-equiv="Content-Type" content="text/html; charset=utf8">
<META content="MSHTML 6.00.2900.3268" name=GENERATOR>
<LINK href="../stylesheet.css" type=text/css rel=stylesheet>
<link rel="stylesheet" href="../styles/CALENDAR.CSS">
<script language="JavaScript" src="../includes/javascript/calendar_us.js"></script>
<script type="text/javascript" src="../includes/javascript/gridsearch.js" language="javascript"></script>
<link  media="screen"  type="text/css" rel="stylesheet" href="../includes/javascript/de.css"></link>
<script type="text/javascript" src="../includes/javascript/jquery-1.6.2.min.js"></script>
<script type="text/javascript" src="../includes/javascript/jquery-ui-1.8.16.custom.min.js"></script>
<script type="text/javascript" src="../includes/javascript/jquery.pnotify.js"></script>
<script type="text/javascript" src="../includes/javascript/commonfunctions.js"></script>
<script language="JavaScript"  type="text/javascript">

	
	var url ='';
	
	var iface = '';
	
	url="../addedit.php";
	
	function updateForm(){
	
		if(IsNullEmptyField('branchcode',"<?php echo $lablearray['414'];?> <?php echo $lablearray['322'];?>") && IsNullEmptyField('currencies_id',"<?php echo $lablearray['414'];?> <?php echo $lablearray['350'];?>")  && IsNullEmptyField('forexrates_date',"<?php echo $lablearray['414'];?> <?php echo $lablearray['317'];?>") && IsNullEmptyField('forexrates_buyrate',"<?php echo $lablearray['290'];?> <?php echo $lablearray['660'];?>") && IsNullEmptyField('forexrates_midrate',"<?php echo $lablearray['290'];?> <?php echo $lablearray['661'];?>") && IsNullEmptyField('forexrates_sellrate',"<?php echo $lablearray['290'];?> <?php echo $lablearray['662'];?>")){ 
			showResult('frmid=frmforexrates&currencies_id=' + document.getElementById('currencies_id').value +'&forexrates_buyrate='+ document.getElementById('forexrates_buyrate').value + '&forexrates_sellrate='+ document.getElementById('forexrates_sellrate').value+'&forexrates_midrate='+ document.getElementById('forexrates_midrate').value+'&forexrates_date='+ document.getElementById('forexrates_date').value +'&action='+document.getElementById('action').value+'&branchcode='+document.getElementById('branchcode').value+'&currencies_code_old='+document.getElementById('currencies_code_old').value,'');
			SelectItemInList("currencies_id","");	
			SelectItemInList("branchcode","");
			document.getElementById('forexrates_datecreated').value ="";			
			document.getElementById('forexrates_buyrate').value ="0";			
			document.getElementById('forexrates_sellrate').value ="0";
			document.getElementById('forexrates_midrate').value ="0";
 			document.getElementById('action').value ="add";	
		}
		
	} 
	
	
</script>

<?php require('../'.DIR_WS_INCLUDES . 'initmenu.php'); 
 getlables("660,659,661,662,242,21,20,317,322");
?>
			 
<form action="" method="POST" style="width:100%;height:auto;" id='frmforexrates' name='frmforexrates' onReset="document.getElementById('action').value='add';">
 <input name="action"  value="<?php if($_GET['action']!=""){ echo $_GET['action'];}else{echo 'add';}?>" id="action" type="hidden">
 <input name="currencies_code_old"  value="" id="currencies_code_old" type="hidden">
		              
			<table width="100%" border="0" cellspacing="0" cellpadding="0">
				<tr>
				<td  colspan="2">
				<table width="100%" border="0" cellspacing="0" cellpadding="0">
				
				   <tr>
					<td colspan="2" align="center"><span id="status" style='color:#006600;'></span></td>
				  </tr>
					  <tr>
						<td  align="right">
									
						</td>
						
						<td align="left">
						<?php echo $lablearray['322'];?><br>						
						 <?php echo generateBranchCombo();?> <?php echo TEXT_FIELD_REQUIRED;?>
						</td>
					</tr>
				   <tr>
					<td align="right" valign="middle"></td>
					<td>
					<?php echo $lablearray['659'];?><br>
					<select id="currencies_id" name="currencies_id" OnChange="showResult('id='+this.value+'&frmid=frmforexrates','txtHint')">
					<option id="" name=""> </option>
					<?php 
					
					$currency_results =tep_db_query("SELECT currencies_code,name,currencies_id FROM ".TABLE_CURRENCIES." ORDER BY name");
					
					while($currency = tep_db_fetch_array($currency_results)){
						echo "<option id='".$currency['currencies_code']."' value='".$currency['currencies_id']."'>".$currency['name'].":  ".$currency['currencies_code']."</option>";
					}
					?>
					</select><?php echo TEXT_FIELD_REQUIRED;?>
					</td>
				  </tr>
				   <tr>
						<td align="right" colspan="2">
						
							<table width="20%" border="0" cellpadding="5">	
							<tr><td><?php echo $lablearray['317'];?><?php echo TEXT_FIELD_REQUIRED;?><BR>
							<input name="forexrates_date" class="yellowfield"  id="forexrates_date" type="text" size="15" width="32" value=""  readonly/>
							<script language="JavaScript" type='text/javascript'>
								new tcal ({
									// form name
									'formname': 'frmforexrates',
									// input name
									'controlname': 'forexrates_date'
								});
							</script> 
							
							
							</td>						  
							  <tr>
								<td><?php echo $lablearray['660'];?><?php echo TEXT_FIELD_REQUIRED;?>&nbsp;<?php echo tep_draw_input_field('forexrates_buyrate',$forexrates_buyrate,'',false,'text',$retainvalues,'32'); ?></td></tr>
								 <tr><td><?php echo $lablearray['661'];?><?php echo TEXT_FIELD_REQUIRED;?>&nbsp;<?php echo tep_draw_input_field('forexrates_midrate',$forexrates_buyrate,'',false,'text',$retainvalues,'32'); ?></td></tr>
								 <tr><td><?php echo $lablearray['662'];?><?php echo TEXT_FIELD_REQUIRED;?>&nbsp;<?php echo tep_draw_input_field('forexrates_sellrate',$forexrates_sellrate,'',false,'text',$retainvalues,'32'); ?></td>
							  </tr>
							</table>

						
						</td>
						
				</tr>
				 
				  <tr>
						<td align="center"></td>
						<td align="center" >&nbsp;<input  type="reset" value="  <?php echo $lablearray['242'];?>  " id='reset' class="actbutton"><input  type="button" value="  <?php echo $lablearray['20'];?>  "  onClick="updateForm()" class="actbutton"></td>
				</tr>				 
				</table>				
				<tr>
					<td colspan="2"  align="right">&nbsp;&nbsp;<?php echo $lablearray['21'];?><input name="search"  value="" type="text" size="50" id='search' onKeyUp="showResult('searchterm='+this.value+'&frmid=frmcurrencies&action=search','txtHint')"/></td>
				</tr>
				<tr>
					<td colspan="2" id='txtHint' align="center"></td>
				</tr>							
				</table> 
				
				
			</form>									
			</TD>                        
		 </TR>       
     </TBODY>
  </TABLE>
  <?php require('../'.DIR_WS_INCLUDES . 'userfooter.php'); ?>
		  <script language="JavaScript"  type="text/javascript">
			 showResult('frmid=frmforexrates','txtHint');
		  </script>
  
</BODY>
 </HTML>
