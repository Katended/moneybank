<?php
  require_once('../includes/application_top.php');
	$_parent = basename(__FILE__);
 // if the user is not logged on, redirect them to the login page
  if(AuthenticateAccess('LOGIN')==0){
	//tep_redirect(tep_href_link(FILENAME_DEFAULT));
	tep_redirect(tep_href_link(FILENAME_LOGIN));
 }
$retainvalues =false;
//echo strtotime(date('Y-m-d H:i:s'));
$lablearray = getlables("944");

$doctypes_results =tep_db_query("SELECT documenttypes_id,IF('".$_SESSION['P_LANG']."'='FR',documenttypes_name_fr,documenttypes_name_en) as  documenttypes_name FROM ".TABLE_DOCUMENTTYPES);
					
while ($cats = tep_db_fetch_array($doctypes_results)) {
	$doctypes[$cats['documenttypes_id']] = $cats['documenttypes_name'];
}
?>
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
<script type="text/javascript" src="../includes/javascript/aes.js"></script>

<script type="text/javascript" >

$( document ).ready(function(){


	
	$("#status").click(function(){
	
		openPopupListWindow('../downloadlistpdf.php?rcode=RECEIVERECEIPT&TCODE='+$("form[name='frmvalidate'] input[id='transfers_code']").val(),'410','350');
		
	});
		
	
});
	

function retrieve (action){ 
		
	
	var transfers_code = $("form[name='frmvalidate'] input[id='transfers_code']").val();
	var transfers_qtn = $("form[name='frmvalidate'] input[id='transfers_qtn']").val();
	var transfers_ans = $("form[name='frmvalidate'] input[id='transfers_ans']").val();
	var transfers_ans = $("form[name='frmvalidate'] input[id='transfers_ans']").val();
	
	var receiver_exchangerate = $("form[name='frmvalidate'] input[id='receiver_exchangerate']").val();
	
	$('#status').load("../addedit.php",{'frmid':'frmreceivetransfer','action':action,'transfers_code':transfers_code,'transfers_qtn':transfers_qtn,'transfers_ans':transfers_ans,'receiver_exchangerate':receiver_exchangerate}			
	,function(result) {	
		
		if(result!=""){
			$('#status').empty();
			
			 eval(result);
			
					 
			 if($('#status').text()!=""){			 
			 
			 	$("#status").addClass("messageStackSuccess");				
				$("#status").effect( "shake", "slow" );	
				//$( "#status" ).fadeOut(5000);				
			 	return;
				
			 }
			
			
			return;
		
		}else{
			
			$("#status").addClass("messageStackSuccess");				
			$("#status").effect( "shake", "slow" );	
			$( "#status" ).fadeOut(50000);
		}
		 return;
		 

	});	


}



</script>

<?php require('../'.DIR_WS_INCLUDES . 'initmenu.php'); 
if(isset($msg)){
	echo $msg;
}


getlables("1,2,968,966,952,938,951,948,952,947,946,935,929,916,933,914,884,885,886,887,888,889,890,891,892,893,894,895,896,897,898,899,890,891,892,893,894,895,896,897,899,900,901,902,903,904,905,906,907,909,908,910,911,912");

?>
   
<style type="text/css">
<!--
body,td,th {
	font-size: 0.9em;
}
-->
</style><form  id="frmreceivetransfer" name="frmreceivetransfer">
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
							<td colspan="2" id='transfers_isclient' >	
							
								</td>
						</tr>
							<tr>
							<td ><?php echo $lablearray['887'];?><br>
								<span id="transfers_amount" class="info"></span>
							
							</td>
								<td>
								<?php echo $lablearray['888'];?><br>
								<span id="operatorcode" class="info"></span>
								
								</td>
							</tr>
							
							<tr>
							<td><?php echo $lablearray['895'];?><br>
								<span id="currencies_code" class="info"></span>								
							</td>
								<td>
								<?php echo $lablearray['890'];?><br>
								<span id="operatorbranches_code" class="info"></span>								
								
								</td>
							</tr>
							
							<tr>
							<td>
							</td>
								<td>
								<?php echo $lablearray['894'];?><br>
								<span id="transfers_amountoreceive" class="info"></span>
																
								</td>
							</tr>
							
							<td>
							</td>
								<td>
								<?php echo $lablearray['896'];?><br>
								<span id="transfers_total" class="info"></span>								
								</td>
							</tr>
							
							<tr>
							<td  colspan="2" align="right">
							
							</td>							
							
							</tr>
							
					</table>		
			</fieldset>
                          
						  
			<fieldset>			  
						  
			 <legend><?php echo $lablearray['897'];?></legend>
			<table width="100%" border="0" cellpadding="5" cellspacing="0" >
						
							<tr>
							<td><?php echo $lablearray['898'];?><br>
							<span id="transfers_firstname" class="info"></span>	
							
							</td>
								<td>
								<?php echo $lablearray['899'];?><br>
								<span id="transfers_middlename" class="info"></span>	
								
								</td>
							</tr>
							
							<tr>
							<td><?php echo $lablearray['900'];?><br>
							<span id="transfers_lastname" class="info"></span>	
							
							</td>
								<td>
								<?php echo $lablearray['966'];?><br>
									<span id="country_origin" class="info"></span>	
								
															
								</td>
							</tr>
							<tr>
							<td><?php echo $lablearray['901'];?><br>
							<span id="transfers_telephone" class="info"></span>	
							
							</td>
								<td>
								<?php echo $lablearray['902'];?><br>
								<span id="transfers_address" class="info"></span>
								
								</td>
							</tr>
							<tr>
							<td><?php echo $lablearray['903'];?><br>
							<span id="documenttypes_id" class="info"></span>
							
							</td>
								<td>
								<?php echo $lablearray['904'];?><br>
								<span id="transfers_docnum" class="info"></span>
								
								</td>
							</tr>
							
							<td><?php echo $lablearray['905'];?><br>
							<span id="transfers_docissuedate" class="info"></span>
							 	
							</td>
								<td>
								<?php echo $lablearray['906'];?><br>
								<span id="transfers_docexpdate" class="info"></span>
								
								</td>
							</tr>
							
							
							
							
					</table>		
			</fieldset>			  		  
				
																  
							
				</td> <TD width="20%"  valign="top" align="center">	
				
						
						
	 </td>
				</tr>			
							
</table>

</fieldset>
<fieldset style="background-color:#FFFFCC"  >
				 <legend><?php echo $lablearray['947'];?></legend>
			<table width="100%" border="0" cellpadding="3" cellspacing="0" >
			
					<tr>
						<td>
					    <?php echo $lablearray['898'];?><br>
						<span id="transfers_firstname_rec" class='answer'></span>
						<?php // echo tep_draw_input_field('transfers_firstname_rec','','disabled=disabled',false,'password',$retainvalues,'32'); ?>
						</td>
							<td>
						<?php echo $lablearray['899'];?><br>
						<?php //echo tep_draw_input_field('transfers_middlename_rec','','disabled=disabled',false,'password',$retainvalues,'32'); ?> 
						<span id="transfers_middlename_rec" class='answer'></span>		
							</td>
						</tr>
						
					<tr>
					
					
					<tr>
						<td>
					  <?php echo $lablearray['900'];?><br>
						<?php //echo tep_draw_input_field('transfers_lastname_rec','','disabled=disabled',false,'text',$retainvalues,'32'); ?>  
						<span id="transfers_lastname_rec" class='answer'></span>		
						</td>
							<td>
								 <?php echo $lablearray['968'];?><br>
								<?php //echo tep_draw_input_field('transfers_lastname_rec','','disabled=disabled',false,'text',$retainvalues,'32'); ?>  
								<span id="destination" class='answer'></span>	
							</td>
						</tr>
				</table>
				</fieldset>	
				<fieldset style="background-color:#FFFFCC"  >
				 <legend><?php echo $lablearray['951'];?></legend>
				<table>		
					<tr>
					
					
					
					
							<td>
							 	<?php echo $lablearray['904'];?><br>
								
								<?php echo tep_draw_input_field('transfers_docnum','','',false,'text',$retainvalues,'32'); ?>  <?php echo TEXT_FIELD_REQUIRED;?>
							
                  
							
							</td>
								<td>
									 <?php echo $lablearray['948'];?><br>
	  								<input name="photo_file" type="file"  id="photo_file"  size="32" width="32">
								
								</td>
							</tr>
							
							<tr>
					
					
					
					
							<td>
							 	
                  	<?php echo $lablearray['903'];?><br>
								<?php echo DrawComboFromArray($doctypes,'documenttypes_id','')?> <?php echo TEXT_FIELD_REQUIRED;?>
								
							
							</td>
								<td>
								
								</td>
							</tr>
							
							
					</table>		
			</fieldset>		  
</form>
 
  <?php require('../'.DIR_WS_INCLUDES . 'userfooter.php'); ?>
  
 <script language="javascript">
 	
  	  // var action;
	 $( document ).ready(function(){		
		
		
			$(".txtHeader").html("<div class='infobox'><h3><?php echo $lablearray['952'];?></h3></div>");
		
			showResult("frmid=frmreceivetransfer","txtHint");
			
			$("form[id='frmvalidate'] input[id='btnwithdrawal']").hide();
			
			
			function waitForElementToDisplay(selector, time) {
					if(document.querySelector(selector)!=null) {
					   $("form[id='frmvalidate'] input[id='btnwithdrawal']").hide();
					    $("form[id='frmvalidate'] input[id='receiver_exchangerate']").hide();
						return;
					}
					else {
						setTimeout(function() {
							waitForElementToDisplay("form[id='frmvalidate'] input[id='btnwithdrawal']");
						},901000);
					}
				}
	
			waitForElementToDisplay();
	});
	

  </script>
 
</BODY>
</HTML>
