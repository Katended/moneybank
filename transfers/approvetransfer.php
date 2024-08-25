<?php
include('../includes/application_top.php');
$_parent = basename(__FILE__);
 // if the user is not logged on, redirect them to the login page
  if(AuthenticateAccess('LOGIN')==0){
	//tep_redirect(tep_href_link(FILENAME_DEFAULT));
	tep_redirect(tep_href_link(FILENAME_LOGIN));
 }
$retainvalues =false;
$results_query = tep_db_query("SELECT op.operatorbranches_code, operatorbranches_name FROM " .TABLE_USERBRANCHES." us,".TABLE_OPERATORBRANCHES." op WHERE op.operatorbranches_code=us.operatorbranches_code");
 
while ($cats = tep_db_fetch_array($results_query)) {
	$operatorbranches[$cats['operatorbranches_code']] = $cats['operatorbranches_name'];
}

$results_query = tep_db_query("SELECT operatorcode, licence_organisationname FROM " .TABLE_LICENCE);
 
while ($cats = tep_db_fetch_array($results_query)) {
	$operators[$cats['operatorcode']] = $cats['licence_organisationname'];
}

$currency_results =tep_db_query("SELECT currencies_code,name,currencies_id FROM ".TABLE_CURRENCIES." ORDER BY name");
					
while ($cats = tep_db_fetch_array($currency_results)) {
	$currencies[$cats['currencies_code']] = $cats['name'];
}					


$currency_results =tep_db_query("SELECT countries_name,countries_iso_code_3 FROM ".TABLE_COUNTRIES." ORDER BY countries_name");
					
while ($cats = tep_db_fetch_array($currency_results)) {
	$countries[$cats['countries_iso_code_3']] = $cats['countries_name'];
}	

$doctypes_results =tep_db_query("SELECT documenttypes_id,IF('".$_SESSION['P_LANG']."'='FR',documenttypes_name_fr,documenttypes_name_en) as  documenttypes_name FROM ".TABLE_DOCUMENTTYPES);
					
while ($cats = tep_db_fetch_array($doctypes_results)) {
	$doctypes[$cats['documenttypes_id']] = $cats['documenttypes_name'];
}

$doctypes_results =tep_db_query("SELECT documenttypes_id,IF('".$_SESSION['P_LANG']."'='FR',documenttypes_name_fr,documenttypes_name_en) as  documenttypes_name FROM ".TABLE_DOCUMENTTYPES);
					
while ($cats = tep_db_fetch_array($doctypes_results)) {
	$doctypes[$cats['documenttypes_id']] = $cats['documenttypes_name'];
}

	//session_start();
	// here you can perform all the checks you need on the user submited variables
        $_SESSION['security_number']=rand(10000,99999);?>
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
<style type="text/css" media="screen"></style>
<script type="text/javascript" >

$( document ).ready(function(){
	
		$("#btnsave").click(function(){
			var charges_array = [];
			$("input[name='charges[]']").each(function() {			
				charges_array.push(this.id+':'+$(this).val());
			});			
			
			///var myJsonString = jQuery.parseJSON(charges_array);
			
			var transfers_isclient = $("#transfers_isclient").val();
			var transfers_amount = $("#transfers_amount").val();
			var operatorcode = $("#operatorcode").val();
			var transfers_fee = $("#transfers_fee").val();
			var operatorbranches_code = $("#operatorbranches_code").val();			
			var transfers_smsfee = $("#transfers_smsfee").val();
			var transfers_chargerate = $("#transfers_chargerate").val();
			var transfers_vat = $("#transfers_vat").val();
			var transfers_amountoreceive = $("#transfers_amountoreceive").val();
			var currencies_code = $("#currencies_code").val();
			
			// to be computed on server side
			//var transfers_total = $("#transfers_total").val();
			var transfers_firstname = $("#transfers_firstname").val();
			var transfers_middlename = $("#transfers_middlename").val();
			var transfers_lastname = $("#transfers_lastname").val();
			var countries_iso_code_3 = $("#countries_iso_code_3").val();
			var transfers_telephone = $("#transfers_telephone").val();
			var transfers_address = $("#transfers_address").val();
			var documenttypes_id = $("#documenttypes_id").val();
			var transfers_docnum = $("#transfers_docnum").val();
			
			var transfers_docissuedate = $("#transfers_docissuedate").val();			
			var transfers_docexpdate = $("#transfers_docexpdate").val();				
						
			var transfers_firstname_rec = $("#transfers_firstname_rec").val();
			var transfers_middlename_rec = $("#transfers_middlename_rec").val();
			var transfers_lastname_rec = $("#transfers_lastname_rec").val();
			var transfers_telephone_rec = $("#transfers_telephone_rec").val();
						
			var transfers_qtn = $("#transfers_qtn").val();
			var transfers_ans = $("#transfers_ans").val();
			var transfers_code = $("#transfers_code").val();
			var captchcode = $("#captchcode").val();
			
			// validations		
			$("#status").load("../addedit.php",{frmid:'frmtransfer',action:'add',transfers_isclient:transfers_isclient,transfers_amount:transfers_amount,operatorcode:operatorcode,transfers_fee:transfers_fee,operatorbranches_code:operatorbranches_code,transfers_smsfee:transfers_smsfee,transfers_chargerate:transfers_chargerate,transfers_vat:transfers_vat,transfers_amountoreceive:transfers_amountoreceive,currencies_code:currencies_code,transfers_firstname:transfers_firstname,transfers_middlename:transfers_middlename,transfers_lastname:transfers_lastname,countries_iso_code_3:countries_iso_code_3,transfers_telephone:transfers_telephone,transfers_address:transfers_address,documenttypes_id:documenttypes_id,transfers_docnum:transfers_docnum,transfers_docissuedate:transfers_docissuedate,transfers_docexpdate:transfers_docexpdate,transfers_firstname_rec:transfers_firstname_rec,transfers_middlename_rec:transfers_middlename_rec,transfers_lastname_rec:transfers_lastname_rec,transfers_telephone_rec:transfers_telephone_rec,transfers_qtn:transfers_qtn,transfers_ans:transfers_ans,transfers_code:transfers_code,captchcode:captchcode,jason:charges_array}			
			,function() {
				
				$("#status").addClass("messageStackSuccess");				
				$("#status").effect( "shake", "slow" );	
				$("#status").fadeOut(5000);
				 return;
		
			});	
		
		});
	
	
		$("#transfers_amount").blur(function(){
			showResult("frmid=frmtransfer&action=evaluatecharge&transfers_amount="+$("#transfers_amount").val(),"")
		});
		

	
	});
	
	
	



</script>

<?php require('../'.DIR_WS_INCLUDES . 'initmenu.php'); 
if(isset($msg)){
	echo $msg;
}
getlables("1,2,960,959,940,938,935,929,916,933,914,884,885,886,887,888,889,890,891,892,893,894,895,896,897,898,899,890,891,892,893,894,895,896,897,899,900,901,902,903,904,905,906,907,909,908,910,911,912");

?>
   
<style type="text/css">
<!--
body,td,th {
	font-size: 0.9em;
}
-->
</style><form  id="frmapprovetransfer" name="frmapprovetransfer">
<span id='status'></span>
<input id="action" name="action" type="hidden" value="">

<fieldset id="approvetransfer"> 
  <TABLE cellSpacing="0"  width="100%" >
    <TBODY>
	
      <TR>
	 
        <TD width="80%"> 			
	
	
			<fieldset >
				 <legend><?php echo $lablearray['884'];?></legend>
			<table width="100%" border="0" cellpadding="5" cellspacing="0" >
							<tr>
							<td colspan="2">	
							<?php
								echo $lablearray['938'];
							?>
								<span id="localtime"></span>
								</td>
						</tr>
						<tr>
							<td colspan="2">	
							<?php echo tep_draw_radio_field('transfers_isclient_c','C', true,'')."&nbsp;&nbsp;".$lablearray['885'];
							 	echo tep_draw_radio_field('transfers_isclient_n','N', false,'')."&nbsp;&nbsp;".$lablearray['886'];?><?php echo TEXT_FIELD_REQUIRED;?>
								</td>
						</tr>
							<tr>
							<td ><?php echo $lablearray['887'];?><br>
							<?php echo tep_draw_input_field('transfers_amount','','',false,'text',$retainvalues,'32'); ?><?php echo TEXT_FIELD_REQUIRED;?>
							</td>
								<td>
								<?php echo $lablearray['888'];?><br>
								<?php echo DrawComboFromArray($operators,'operatorcode','')?> <?php echo TEXT_FIELD_REQUIRED;?>
								</td>
							</tr>
							
							<tr>
							<td><?php echo $lablearray['895'];?><br>
								<select id="currencies_code" name="currencies_code" OnChange="showResult('id='+this.value+'&frmid=frmforexrates','txtHint')">
								<option id="" name=""> </option>
								<?php 
								
								$currency_results =tep_db_query("SELECT currencies_code,name,currencies_id FROM ".TABLE_CURRENCIES." ORDER BY name");
								
								while($currency = tep_db_fetch_array($currency_results)){
									echo "<option id='".$currency['currencies_code']."' value='".$currency['currencies_id']."'>".$currency['name'].":  ".$currency['currencies_code']."</option>";
								}
								?>
								</select><?php echo TEXT_FIELD_REQUIRED;?>
							</td>
								<td>
								<?php echo $lablearray['890'];?><br>
								
								<?php echo DrawComboFromArray($operatorbranches,'operatorbranches_code','','operatorbranches')?> <?php echo TEXT_FIELD_REQUIRED;?>
								</td>
							</tr>
							
							<tr>
							<td>
							</td>
								<td>
								<?php echo $lablearray['894'];?><br>
								<?php echo tep_draw_input_field('transfers_amountoreceive','','',false,'text',$retainvalues,'32'); ?><?php echo TEXT_FIELD_REQUIRED;?>
								
								</td>
							</tr>
							
							<td>
							</td>
								<td>
								<?php echo $lablearray['896'];?><br>
								<?php echo tep_draw_input_field('transfers_total','','',false,'text',$retainvalues,'32'); ?><?php echo TEXT_FIELD_REQUIRED;?>
								
								
								</td>
							</tr>
							
							<tr>
							<td  colspan="2" align="right">
							<fieldset>
							<legend><?php echo $lablearray['916'];?></legend>
								<table cellpadding="2" >
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
									
								// only pick the first branch
								// we assume user will only transaction with one branch at a time
								$charges_query = tep_db_query("SELECT ".$charges_name_fieldname." as name, c.charges_code  FROM ".TABLE_CHARGES." c,".TABLE_BRANCHCHARGES." br WHERE br.operatorbranches_code='".$_SESSION['branches'][0]."' AND br.charges_code=c.charges_code");
								
								
								while($results_array = tep_db_fetch_array($charges_query)){
								?>
								<tr>
								<td><?php echo $results_array['name'];?><br>
								<input name="charges[]" class="yellowfield"  id="<?php echo $results_array["charges_code"];?>" type="text" size="15" width="32" value=""  disabled='disabled'/>							
									<?php // echo tep_draw_input_field('charges[]','','  disabled=\'disabled\'',$transfers_smsfee,'',false,'text',$retainvalues,'32'); ?>
								</td>
								<td>
									<?php echo $lablearray['893'];?><br>
									<input name="VAT<?php echo $results_array["charges_code"];?>" class="yellowfield"  id="VAT<?php echo $results_array["charges_code"];?>" type="text" size="15" width="32" value=""  disabled='disabled'/>														
									<?php //echo tep_draw_input_field('charges[]','','  disabled=\'disabled\'',$transfers_smsfee,'',false,'text',$retainvalues,'32'); ?>
								</td>
								</tr>
								<?php }?>
								
								</table>
							</fieldset>
							</td>							
							
							</tr>
							
					</table>		
			</fieldset>
                          
						  
			<fieldset>			  
						  
			 <legend><?php echo $lablearray['897'];?></legend>
			<table width="100%" border="0" cellpadding="5" cellspacing="0" >
						
							<tr>
							<td><?php echo $lablearray['898'];?><br>
							<?php echo tep_draw_input_field('transfers_firstname','','',false,'text',$retainvalues,'32'); ?><?php echo TEXT_FIELD_REQUIRED;?>
							</td>
								<td>
								<?php echo $lablearray['899'];?><br>
								<?php echo tep_draw_input_field('transfers_middlename','','',false,'text',$retainvalues,'32'); ?>
								</td>
							</tr>
							
							<tr>
							<td><?php echo $lablearray['900'];?><br>
							<?php echo tep_draw_input_field('transfers_lastname','','',false,'text',$retainvalues,'32'); ?><?php echo TEXT_FIELD_REQUIRED;?>
							</td>
								<td>
								<?php echo $lablearray['912'];?><br>
								<?php echo DrawComboFromArray($countries,'countries_iso_code_3','')?> <?php echo TEXT_FIELD_REQUIRED;?>
															
								</td>
							</tr>
							<tr>
							<td><?php echo $lablearray['901'];?><br>
							<?php echo tep_draw_input_field('transfers_telephone','','',false,'text',$retainvalues,'32'); ?><?php echo TEXT_FIELD_REQUIRED;?>
							</td>
								<td>
								<?php echo $lablearray['902'];?><br>
								<?php echo tep_draw_input_field('transfers_address','','',false,'text',$retainvalues,'32'); ?><?php echo TEXT_FIELD_REQUIRED;?>
								</td>
							</tr>
							<tr>
							<td><?php echo $lablearray['903'];?><br>
							<?php echo DrawComboFromArray($doctypes,'documenttypes_id','')?> <?php echo TEXT_FIELD_REQUIRED;?>
							</td>
								<td>
								<?php echo $lablearray['904'];?><br>
								<?php echo tep_draw_input_field('transfers_docnum','','',false,'text',$retainvalues,'32'); ?>  <?php echo TEXT_FIELD_REQUIRED;?>
								</td>
							</tr>
							
							<td><?php echo $lablearray['905'];?><br>
							 	<input name="transfers_docissuedate" class="yellowfield"  id="transfers_docissuedate" type="text" size="15" width="32" value=""  readonly/>
								<script language="JavaScript">
									new tcal ({
										// form name
										'formname': 'frmtransfer',
										// input name
										'controlname': 'transfers_docissuedate'
									});
								</script>
                            <?php echo TEXT_FIELD_REQUIRED;?>
							</td>
								<td>
								<?php echo $lablearray['906'];?><br>
								<input name="students_dateenrolled" class="yellowfield"  id="transfers_docexpdate" type="text" size="15" width="32" value=""  readonly/>
								<script language="JavaScript">
									new tcal ({
										// form name
										'formname': 'frmtransfer',
										// input name
										'controlname': 'transfers_docexpdate'
									});
								</script><?php echo TEXT_FIELD_REQUIRED;?>
								</td>
							</tr>
							
							
							
							
					</table>		
			</fieldset>			  
						  
						  
			<fieldset >
				 <legend><?php echo $lablearray['908'];?></legend>
			<table width="100%" border="0" cellpadding="3" cellspacing="0" >
						<tr>
							<td><?php echo $lablearray['898'];?><br>
							<?php echo tep_draw_input_field('transfers_firstname_rec','','',false,'text',$retainvalues,'32'); ?><?php echo TEXT_FIELD_REQUIRED;?>
							</td>
								<td>
								<?php echo $lablearray['899'];?><br>
								<?php echo tep_draw_input_field('transfers_middlename_rec','','',false,'text',$retainvalues,'32'); ?>
								</td>
							</tr>
							
							<tr>
							<td><?php echo $lablearray['900'];?><br>
							<?php echo tep_draw_input_field('transfers_lastname_rec','','',false,'text',$retainvalues,'32'); ?><?php echo TEXT_FIELD_REQUIRED;?>
							</td>
								<td>
								<?php echo $lablearray['901'];?><br>
								<?php echo tep_draw_input_field('transfers_telephone_rec','','',false,'text',$retainvalues,'32'); ?>
								</td>
							</tr>
							
							
					</table>		
			</fieldset>		  
						  
				<table width="100%" border="0" cellpadding="8" cellspacing="0" >
						<tr>
							<td><?php echo $lablearray['909'];?><br>
							<?php echo tep_draw_input_field('transfers_qtn','','',false,'password',$retainvalues,'60'); ?><?php echo TEXT_FIELD_REQUIRED;?>
							</td>
								<td>
								<?php echo $lablearray['910'];?><br>
								<?php echo tep_draw_input_field('transfers_ans','','',false,'password',$retainvalues,'50'); ?><?php echo TEXT_FIELD_REQUIRED;?>
								</td>
							</tr>
							
							<tr>
							<td><?php echo $lablearray['911'];?><br>
							<?php echo tep_draw_input_field('transfers_code','','',false,'password',$retainvalues,'32'); ?><?php echo TEXT_FIELD_REQUIRED;?>
							</td>
								<td>
								
								</td>
							</tr>
							
							
					</table>				  
												  
							
				</td> <TD width="20%"  valign="top" align="center" nowrap="nowrap">	
				
						<button id="btnsave" class="actbuttonhot" type="button"><?php echo $lablearray['960'];?> </button>
						<button id="btnreset"  type="reset" class="actbutton"><?php echo $lablearray['2'];?></button>
						
	 </td>
				</tr>			
							
</table>

</fieldset>
</form>
 
  <?php require('../'.DIR_WS_INCLUDES . 'userfooter.php'); ?>
  
 <script language="javascript">
 	
  	  // var action;
	 $( document ).ready(function(){
  	  	$("#approvetransfer :input").attr("disabled", true);
		$("#btnsave").attr("disabled", false);
		$("#btnreset").attr("disabled", false);	 
		
		
		//$(".txtHeader").html("<div class='infobox'><h3><?php echo $lablearray['940'];?></h3></div>");
		$(".txtSearch").html("<div class='searchterm'><?php echo $lablearray['959'];?> <input id='searchterm'  name='searchterm' type='text' size='26'   style='margin-bottom:0px;'/><button id='btnSearch' class='actbutton' type='button'><?php echo $lablearray['959'];?></button></div>");
		var myInterval = setInterval("showResult({frmid:'frmapprovetransfer'},'txtHint')",90000);
		clearInterval(myInterval);
		
		
		(function poll(){
		   setTimeout(function(){
		   			showResult({frmid:'frmapprovetransfer'},'txtHint');
				//Setup the next poll recursively
				poll();
			  }
		  ,30000);
		})();	
	
		$("form[id='frmvalidate'] button[id='btnSearch']").click(function(){			
			clearInterval(myInterval);	
			showResult({'frmid':'frmapprovetransfer','action':'search','searchterm':$("form[id='frmvalidate'] input[id='searchterm']").val()},'txtHint');
		});
		
	});
	
  </script>
 
</BODY>
</HTML>
