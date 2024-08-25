<?php
require_once('../includes/application_top.php');
$glaccounts = getAccountLevels();
 
$_parent = basename(__FILE__);
$results_query = tep_db_query("SELECT op.bankbranches_code, bankbranches_name FROM " .TABLE_USERBRANCHES." us,".TABLE_OPERATORBRANCHES." op WHERE op.bankbranches_code=us.bankbranches_code");
 
while ($cats = tep_db_fetch_array($results_query)) {
	$operatorbranches[$cats['bankbranches_code']] = $cats['bankbranches_name'];
}
$lablearray = getlables("15,291,38,931,969,889,291,994,907,952"); 
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3c.org/TR/1999/REC-html401-19991224/loose.dtd">
<HTML lang="<? echo $_SESSION['P_LANG'];?>"><HEAD>
<TITLE></TITLE>
<meta http-equiv="Content-Type" content="text/html; charset=utf8">
<META content="MSHTML 6.00.2900.3268" name=GENERATOR>
<LINK href="../stylesheet.css" type=text/css rel=stylesheet>
<script type="text/javascript" src="../includes/javascript/gridsearch.js" language="javascript"></script>
<link  media="screen"  type="text/css" rel="stylesheet" href="../includes/javascript/de.css"></link>
<script type="text/javascript" src="../includes/javascript/jquery-1.6.2.min.js"></script>
<script type="text/javascript" src="../includes/javascript/jquery-ui-1.8.16.custom.min.js"></script>
<script type="text/javascript" src="../includes/javascript/jquery.pnotify.js"></script>
<script type="text/javascript" src="../includes/javascript/commonfunctions.js"></script>
<script language="JavaScript"  type="text/javascript">
	
	var url ='';
	var iface = '';
	url = "../addedit.php";
	var aBranches =[];
	
	$( document).ready(function(){
	
			

		$('#chargesrates_fixed').click(function() {
	
			if($('#chargesrates_fixed').is(':checked')){
			
				$("#chargesrates_from").prop('disabled', false);	
				$("#chargesrates_to").prop('disabled', false);	
				$("#chargesrates_per").prop('disabled', true);
				$("#chargesrates_amount").prop('disabled', false);
				$('#chargesrates_per').val('0.00'); 	
				$('#chargesrates_from').val('0.00'); 
				$('#chargesrates_to').val('0.00'); 
				$('#chargesrates_amount').val('0.00');
											
				$('#chargesrates_activated').attr('checked', false); 
				$('#chargesrates_fixed').val('N'); 			
				
			}else{
			
				 $("#chargesrates_from").prop('disabled', true);	
				$("#chargesrates_to").prop('disabled', true);	
				$("#chargesrates_per").prop('disabled', true);
				$("#chargesrates_amount").prop('disabled', true);
				$("#chargesrates_per").prop('disabled', false);
				$('#chargesrates_fixed').val('Y'); 	
			
			}
		
		
		});
	
		$("#btnSave").click(function(){
				
				if(!IsNullEmptyField('charges_id',"<?php echo $lablearray['889'];?> <?php echo $lablearray['291'];?>")|| !IsNullEmptyField('bankbranches_id',"<?php echo $lablearray['969'];?>")){
					return;		
				}
				
				//var src = document.getElementById("bankbranches_code");
				if(!IsNullEmptyField('licence_build',"<?php echo $lablearray['889'];?> <?php echo $lablearray['291'];?>")){
					alert("<?php echo $lablearray['907'];?>");
					return;
				}
				
				if(!IsNullEmptyField('bankbranches_id',"<?php echo $lablearray['889'];?> <?php echo $lablearray['291'];?>")){
					alert("<?php echo $lablearray['907'];?>");
					return;
				}	
			
				$( "#status" ).load("../addedit.php",{frmid:'frmconfigurecharges',action: $("#action").val(),chargesrates_from:$("#chargesrates_from").val(),chargesrates_to:$("#chargesrates_to").val(),chargesrates_amount:$("#chargesrates_amount").val(),chargesrates_per:$("#chargesrates_per").val(),chargesrates_activated:$("#chargesrates_activated").val(),'bankbranches_code':$("#bankbranches_id").val(),'licence_build':$("#licence_build").val(),'chargesrates_vat':$("#chargesrates_vat").val(),chargesrates_fixed:$('#chargesrates_fixed').val(),'chargesrates_id':$('#chargesrates_id').val(),charges_id:$('#charges_id').val(),chargesrates_stage:$('#chargesrates_stage').val()}			
				,function() {
				
					$("#status").addClass("messageStackSuccess");				
					$("#status").effect( "shake", "slow" );	
					$( "#status" ).fadeOut(10200);
					showResult('frmid=frmconfigurecharges','txtHint');
					return;
			
			});	
	
		});
		
		//$("#chargesrates_from,#chargesrates_to,#chargesrates_amount" ).keyup(function(){
		//	IsNumeric(fieldname,message)
		//});
		
		$("#chargesrates_from,#chargesrates_to,#chargesrates_amount" ).keydown(function(e){
				
			
				
				// Allow only backspace and delete
				if ( e.keyCode == 46 || e.keyCode == 8  || e.keyCode == 190 || e.keyCode == 188) {
					// let it happen, don't do anything
					//alert(e.keyCode);
					$("#chargesrates_per").prop('disabled', false);	
				}
				else {
					// Ensure that it is a number and stop the keypress
					if ((e.keyCode < 48 || e.keyCode > 57) &&(e.keyCode!=9)  ) {		
						
						e.preventDefault();	
					}else{
						$("#chargesrates_per").prop('disabled',true);	
						$("#chargesrates_per").prop('disabled',true);	
					}	
				}
		
				//if($("#chargesrates_from").val()!='' || $("#chargesrates_to").val()!='' || $("#chargesrates_amount").val()!=''){
					//$("#chargesrates_per").prop('disabled', true);
				//}else{
				//	$("#chargesrates_per").prop('disabled', false);				
				//}
				
							
			
		});
	
	});




</script>
<?php 
require('../'.DIR_WS_INCLUDES . 'initmenu.php'); 
 getlables("915,918,919,916,15,42,752,38,39,753,890,889,893,934,956,952,1000,1002,1001");
 
 ?>
<form action="" method="post" style="width:100%;height:auto;" id='frmconfigurecharges' name='frmconfigurecharges'>
<span id='status' ></span>
<fieldset> 
		
			<input name="action" type="hidden" id="action" value="add">	
			<input name="chargesrates_id" type="hidden" id="chargesrates_id" value="">	
				
			<table width="100%" border="0" cellspacing="0" cellpadding="0">
				<tr>
				<td  colspan="2">
              
				<table width="100%" border="0" cellspacing="2" cellpadding="0">

				

				 <tr>
					
					<td  colspan="2">
						<fieldset>
						<legend><?php echo $lablearray['916'];?></legend>
					<table width="100%" border="0" cellpadding="4">
					 
					  <tr>
						<td valign="top" >
						
						
					
					
						<?php echo $lablearray['890'];?><?php echo TEXT_FIELD_REQUIRED;?><br>
						
						
						<?php				
						
						 echo DrawComboFromArray($operatorbranches,'bankbranches_id','')?> <td>
						<?php echo $lablearray['889'];?><?php echo TEXT_FIELD_REQUIRED;?><br>
						<select name="charges_id" id="charges_id" tabindex="1">
							<option id='' value=''><?php echo $lablearray['42']?></option>
							<?PHP 
							switch($_SESSION['P_LANG']){							
							case 'EN':
								$charges_name_fieldname='charges_name_en';
							break;
							
							case 'FR':
									$charges_name_fieldname='charges_name_fr';
							break;
							
							case 'SWA':
								$charges_name_fieldname='charges_name_sa';
							break;
							
							case 'SP':
								$charges_name_fieldname='charges_name_sp';
							break;
							
							default:
								$charges_name_fieldname='charges_name_en';
							break;
							}
							$taxes_types_query = tep_db_query("select charges_id, ".$charges_name_fieldname." as charges_name from " . TABLE_CHARGES." ORDER BY ".$charges_name_fieldname." ASC");
							
							 while ($tax = tep_db_fetch_array($taxes_types_query)){
									
									
								echo "<option id='".$tax['charges_id']."' value='".$tax['charges_id']."'>".$tax['charges_name']."</option>";
								
							}	
							?>
							
							</select> 
							<td>
							<td>
							<?php echo $lablearray['38'];?><?php echo TEXT_FIELD_REQUIRED;?><br> <?php echo tep_draw_input_field('chargesrates_from','','tabindex="2"',false,'text',true,'15'); ?>
							</td>
							<td>
							 <?php echo $lablearray['39'];?><?php echo TEXT_FIELD_REQUIRED;?><br> <?php echo tep_draw_input_field('chargesrates_to','','tabindex="3"',false,'text',true,'15'); ?> 
							 </td>
							 <td>
							 <?php echo $lablearray['919'];?><?php echo TEXT_FIELD_REQUIRED;?><br><?php echo tep_draw_input_field('chargesrates_amount','','tabindex="4"',false,'text',true,'15'); ?><input name="chargesrates_fixed" type="checkbox" value="Y" id="chargesrates_fixed"  onClick="if(this.checked){this.value='Y'}else{this.value='N'}" ><?php echo $lablearray['1000'];?>
								<?php echo tep_draw_input_field('chargesrates_per','','tabindex="6"',false,'text',true,'5'); ?>(%)						
							
							</td>
						  </tr>
						<tr>
						
					  </tr>
					
					</table>
					<p align="right"><?php echo $lablearray['1002'];?>
					
					<select id="chargesrates_stage" name="chargesrates_stage">
						<option id="" value=""></option>
						<option id="TR" value="TR"><?php echo $lablearray['1001'];?></option>
						<option id="WI" value="WI"><?php echo $lablearray['952'];?></option>
					</select>
					<?php echo DrawComboFromArray('licence_build','licence_build','','banks',"");?></p>
					
					<?php getlables('934,753,915,918,994,1000')?>
					
					<p align="right" id='txtBranches'></p>
						
					
					<fieldset>
							<legend><?php echo $lablearray['915'];?></legend><?php echo $lablearray['753'];?><?php echo tep_draw_input_field('chargesrates_vat','','tabindex="5"',false,'text',true,'5'); ?>(%)  
							
							
							</fieldset>
							
							
							<p align="right"><input name="chargesrates_activated" type="checkbox" value="Y" id="chargesrates_activated" checked onClick="if(this.checked){this.value='Y'}else{this.value='N'}" > <?php echo $lablearray['918'];?> </p>
						
					<span style="float:right;margin:15px;"><input  type="button" value="  Save  " id="btnSave" class="actbutton" tabindex="6" ><input  type="reset"  value="  Reset  " id="Cancel" class="actbutton" onClick="updateReset()" tabindex="7"></span>
                  
				</table>

				<tr>
					<td colspan="2" id='txtHint' align="center"></td>
				</tr>
				</table>
				</fieldset>
				
				
				</form>
	 <?php require('../'.DIR_WS_INCLUDES . 'userfooter.php'); ?>
	  <script language="JavaScript"  type="text/javascript">
		showResult('frmid=frmconfigurecharges','txtHint');
		$("#chargesrates_fixed").prop('checked', true);
		$("#chargesrates_per").prop('disabled', true);
	 </script>

</BODY>
</HTML>
