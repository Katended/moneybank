<?php
require_once('../includes/application_top.php');
$glaccounts = array();

// $glaccounts = getAccountLevels();

$query_result = tep_db_query("SELECT bankaccounts_accno,chartofaccounts_accountcode,bb.bankbranches_id,bankbranches_name,(SELECT banks_name FROM ".TABLE_BANKS." WHERE banks_id=bb.banks_id) as banks_name FROM ". TABLE_BANKACCOUNTS." as bc,".TABLE_BANKBRANCHES." as bb WHERE bc.bankbranches_id=bb.bankbranches_id"); 
$glaccounts = getAccountLevels(); 
$lablearray =getlables("35,317,387,247,358,388,187,389,388,403,317,291,414,415,422"); 
 
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3c.org/TR/1999/REC-html401-19991224/loose.dtd">
<HTML lang="<? echo $_SESSION['P_LANG'];?>"><HEAD>
<TITLE></TITLE>
<link rel="apple-touch-icon" href="../banks/images/logo.gif" >
<meta http-equiv="Content-Type" content="text/html; charset=utf8">
<META content="MSHTML 6.00.2900.3268" name=GENERATOR>
<script language="JavaScript" src="../includes/javascript/calendar_us.js"></script>
<script language="JavaScript" src="../includes/javascript/commonfunctions.js"></script>
<script type="text/javascript" src="../includes/javascript/gridsearch.js" language="javascript"></script>
<link  media="screen"  type="text/css" rel="stylesheet" href="../includes/javascript/de.css"></link>
<script type="text/javascript" src="../includes/javascript/jquery-1.6.2.min.js"></script>
<script type="text/javascript" src="../includes/javascript/jquery-ui-1.8.16.custom.min.js">
	
</script>

<script type="text/javascript" src="../includes/javascript/jquery.pnotify.js"></script>
<LINK href="../stylesheet.css" type=text/css rel=stylesheet>
<link rel="stylesheet" href="../styles/CALENDAR.CSS">
<script language="JavaScript"  type="text/javascript">
	var url ='';
	
	var iface = '';
	
	url = '../addedit.php';

	var breload = true;
	
	var action ="";
	var ctcode =""
	
// this function calculates values that are supposed to be in the textboxes
function recalculatevalues(elementid){


 	ctcode  = elementid;
	ctcode  = ctcode.substr(14,ctcode.length);
	action = document.getElementById(elementid).value

	var amount = parseFloat(action.substr(0,action.length-2));
	action = action.substr(action.length-2,action.length-1);
	 
	 
	
// deposits and other credits
	if(action=='D+' || action=='D-'){
		if(action=='D+'){	
				document.getElementById('txtDeposits').value = parseFloat(document.getElementById('txtDeposits').value) + amount;
				document.getElementById('txtbalancecleared').value = parseFloat(document.getElementById('txtbalancecleared').value) + amount;
				document.getElementById(elementid).value =amount+'D-';
				
		}else{		
			document.getElementById('txtDeposits').value = parseFloat(document.getElementById('txtDeposits').value) - amount;
			document.getElementById('txtbalancecleared').value = parseFloat(document.getElementById('txtbalancecleared').value) - amount;
			document.getElementById(elementid).value =amount+'D+';
		}
	}

	if(action=='P+' || action=='P-'){
		if(action=='P+'){		
				document.getElementById('txtPayments').value = parseFloat(document.getElementById('txtPayments').value) - amount;
				document.getElementById('txtbalancecleared').value = parseFloat(document.getElementById('txtbalancecleared').value) + amount;
				document.getElementById(elementid).value =amount+'P-'
		}else{		
			document.getElementById('txtPayments').value = parseFloat(document.getElementById('txtPayments').value) + amount;
			document.getElementById('txtbalancecleared').value = parseFloat(document.getElementById('txtbalancecleared').value) - amount;	
			document.getElementById(elementid).value =amount+'P+';
		}
	}
	
}
	
function saveReconcile(){

	showResult('frmid=frmreconciliation&action=save&txtStatementDate='+document.getElementById('txtStatementDate').value,'status');

}	
function UnReconcile(){

	if(confirm("<?php echo $lablearray['422']?>")){
		if(!IsNullEmptyField('bankaccounts_accno',"<?php echo $lablearray['187'];?>")|| !IsNullEmptyField('txtUnreconcileDate',"<?php echo $lablearray['388']?>")){
			showResult('frmid=frmreconciliation&action=unreconcileperiod&date='+document.getElementById('txtUnreconcileDate').value+'&bankaccounts_accno='+document.getElementById('bankaccounts_accno').value,'status');
		}
	}
}

function checkunckeck(elementid){

		
	if(elementid!=""){
	
		if(document.getElementById(elementid).checked){	
			action ='reconcile';
		}else{
			action ='unreconcile';
		}
		
		signedamt = document.getElementById(elementid).value
		
		showResult('frmid=frmreconciliation&signedamt='+signedamt+'&action='+action+'&tcode='+elementid+'&txtStatementDate='+document.getElementById('txtStatementDate').value+'&bankaccounts_accno='+document.getElementById('bankaccounts_accno').value+ '&elementid='+elementid+'&cal='+action+'&txtDeposits='+document.getElementById('txtDeposits').value+'&txtbalancecleared='+document.getElementById('txtbalancecleared').value+'&txtPayments='+document.getElementById('txtPayments').value+'&txtstatementbal='+document.getElementById('txtstatementbal').value,'status');
		sleep(1200);
		document.getElementById('txtbalancecleared').value ='0';
		document.getElementById('txtDeposits').value ='0';
		document.getElementById('txtPayments').value ='0';
		showResult('frmid=frmreconciliation&action='+document.getElementById('action').value+'&chartofaccounts_accountcode=' + document.getElementById('chartofaccounts_accountcode').value+'&bankaccounts_accno='+document.getElementById('bankaccounts_accno').value+'&txtStatementDate='+document.getElementById('txtStatementDate').value+'&txtstatementbal='+document.getElementById('txtstatementbal').value+'&reconciliationhistory_id='+document.getElementById('reconciliationhistory_id').value+'&txtOpeningBalance='+document.getElementById('txtOpeningBalance').value+'&load=text','status');
		
	}
	


}

function getGLAccount(cvalue){

	if(cvalue!=""){
		showResult('frmid=frmreconciliation&action=getgl&acc='+cvalue,'');
	}
	
	//document.getElementById('txtunpresentedcheques').value =0;
	//document.getElementById('txtAdjutedBal').value =0;
	document.getElementById('txtstatementbal').value =0;
}

function getBeginBalance(){

	if(document.getElementById('chartofaccounts_accountcode').value==""){
		alert('Please select account');
		return;
	}
		
	if(document.getElementById('txtStatementDate').value==""){
		alert("<?php echo $lablearray['358'];?>");//Please select statement date
		return;
	}
	
	document.getElementById('beginbal').innerHTML = "<img src='../images/loading.gif' border='0' style='margin:0px;'/>"
	showResult('frmid=frmreconciliation&action=getbeginbal&chartofaccounts_accountcode='+document.getElementById('chartofaccounts_accountcode').value+'&txtlastrecondate='+document.getElementById('txtlastrecondate').value+'&bankaccounts_accno='+document.getElementById('bankaccounts_accno').value,'');
	document.getElementById('beginbal').innerHTML = ""
}

// this function is used to search for discrepancies
function updateForm(){

		if(!IsNullEmptyField('bankaccounts_accno',"<?php echo $lablearray['187'];?>")|| !IsNullEmptyField('txtStatementDate',"<?php echo $lablearray['388']?>")  || !IsNullEmptyField('chartofaccounts_accountcode',"<?php echo $lablearray['187']?>") || !IsNullEmptyField('txtstatementbal',"<?php echo $lablearray['403']?>")){
			return;
		}else{
			
			document.getElementById('txtbalancecleared').value ='0';
			document.getElementById('txtDeposits').value ='0';
			document.getElementById('txtPayments').value ='0';
			
			showResult('frmid=frmreconciliation&action='+document.getElementById('action').value+'&chartofaccounts_accountcode=' + document.getElementById('chartofaccounts_accountcode').value+'&bankaccounts_accno='+document.getElementById('bankaccounts_accno').value+'&txtStatementDate='+document.getElementById('txtStatementDate').value+'&txtstatementbal='+document.getElementById('txtstatementbal').value+'&reconciliationhistory_id='+document.getElementById('reconciliationhistory_id').value+'&txtOpeningBalance='+document.getElementById('txtOpeningBalance').value+'&load=text','');
			sleep('800');
			showResult('frmid=frmreconciliation&action='+document.getElementById('action').value+'&chartofaccounts_accountcode=' + document.getElementById('chartofaccounts_accountcode').value+'&bankaccounts_accno='+document.getElementById('bankaccounts_accno').value+'&txtStatementDate='+document.getElementById('txtStatementDate').value+'&txtstatementbal='+document.getElementById('txtstatementbal').value+'&reconciliationhistory_id='+document.getElementById('reconciliationhistory_id').value+'&txtOpeningBalance='+document.getElementById('txtOpeningBalance').value,'txtHint');
			
						 
		}	

}

 

$(document).ready(
    function(){
       	 $('#btnclosepanel').click(
            function(event){
				$(".slide").hide('slide',{direction:'right'},1000);				
			
		
           });
			
				
			$(".slide").hide();
    });
		
			
	// upadete transactions
	function updateTransaction(){
		
	
	
		var cDebit = document.getElementById('txtDebitBalance').value ;
		
		document.getElementById('txtDebitBalance').value = cDebit.replace(",","");
		
		var cCredit = document.getElementById('txtDebitBalance').value 
		
		document.getElementById('txtCreditBalance').value = cCredit.replace(",","");
		
		
		if(parseFloat(document.getElementById('txtDebitBalance').value) != parseFloat(document.getElementById('txtCreditBalance').value) || (parseFloat(document.getElementById('txtDebitBalance').value)==0 || parseFloat(document.getElementById('txtCreditBalance').value)==0)){
			alert('The transaction is not balanced or amount is not specified.');
			return;
		}
		
		
		var elementname ="";
		
		var elem = document.getElementById('frmreconciliation').elements;
		
		var ulstring ="";
		
			
		for(var i = 0; i < elem.length; i++){
						
					
			if(elem[i].type=='select-one'){
			
				elementname = elem[i].name;				
															
				if(elementname.contains('cmbupdate')){
					ulstring = ulstring +'ACC'+ elementname.substr(9,elementname.length)+'_'+elem[i].value+'-D'+elem[i].value+'_'+document.getElementById('txtupdateD'+elementname.substr(9,elementname.length)).value + '-C'+ elem[i].value +'_'+ document.getElementById('txtupdateC'+elementname.substr(9,elementname.length)).value+'-';					
				}		
							
			}
		
		}
		
	
		showResult('frmid=frmreconciliation&action=update&thepost='+ulstring+'&tcode='+document.getElementById('txthiddentcode').value,'')
	//	$(".slide").hide('slide',{direction:'right'},1000);	
}

// this function is used to post charges and Interest
function PostChargesInterest(ctype){
	
	var urlstring = "";
	
	if(document.getElementById('bankaccounts_accno').value==""){
		alert("<?php echo $lablearray['414'];?> <?php echo $lablearray['35'];?>");
		return;
	}
	// check see if its a charge
	if(ctype=='C'){
	
		if(IsNullEmptyField('txtserviceCharge',"<?php echo $lablearray['401'];?> <?php echo $lablearray['291'];?>") && IsNullEmptyField('txtserviceChargeDate',"<?php echo $lablearray['401'];?> <?php echo $lablearray['317'];?> <?php echo $lablearray['291'];?>") && IsNullEmptyField('chargeACC',"<?php echo $lablearray['296'];?> <?php echo $lablearray['291'];?>")){
			urlstring='frmid=frmreconciliation&action=postcharge&type=C&amount='+document.getElementById('txtserviceCharge').value+'&date='+document.getElementById('txtserviceChargeDate').value+'&glaccount='+document.getElementById('chargeACC').value+'&bankaccounts_accno='+document.getElementById('bankaccounts_accno').value;
			document.getElementById('txtserviceCharge').value =""
		}else{
			return;
		}
		
	}
	// check see if its Interest
	if(ctype=='I'){
	
		if(IsNullEmptyField('txtInterest',"<?php echo $lablearray['412'];?> <?php echo $lablearray['291'];?>") && IsNullEmptyField('txtInterestDate',"<?php echo $lablearray['412'];?> <?php echo $lablearray['317'];?> <?php echo $lablearray['291'];?>")  && IsNullEmptyField('InterestACC',"<?php echo $lablearray['296'];?> <?php echo $lablearray['291'];?>")){
			urlstring='frmid=frmreconciliation&action=postinterest&type=I&amount='+document.getElementById('txtInterest').value+'&date='+document.getElementById('txtInterestDate').value+'&glaccount='+document.getElementById('InterestACC').value+'&bankaccounts_accno='+document.getElementById('bankaccounts_accno').value;
			document.getElementById('txtInterest').value =""
			
		}else{
			return;
		}
		
		
	}
	
	showResult(urlstring,'status');
	
	//updateForm();
	
}

// This function is used to check whether debit and credit are balancing
function balanceTransaction(obj){

	var nDebit =0;
	var nCredit =0;
	var cvalue ="";
	var elementname ="";
	var bInum= true;
	$('.pop_panel').each(function() {
			
		elementname = $(this).attr('name');
		
		cvalue 	= $(this).val();
				
		if(elementname.substr(0,10)=='txtupdateD'){	
			if(cvalue.replace(",","")!=""){				
				nDebit =  nDebit + parseFloat(cvalue.replace(",",""));						
			}
		
		}
		
		if(elementname.substr(0,10)=="txtupdateC"){	
			if(cvalue.replace(",","")!=""){
			
				nCredit =  nCredit + parseFloat(cvalue.replace(",",""));		
			}
			
		
		}
		
		
		
	});

	if(nDebit!=nCredit)	{	
	
		$("#txtDebitBalance").attr('class', 'imbalance');
		$("#txtCreditBalance").attr('class', 'imbalance');	
	
	}else{	
	
		$("#txtDebitBalance").attr('class', 'balanced');
		$("#txtCreditBalance").attr('class', 'balanced');
		
	}
	 
	document.getElementById('txtDebitBalance').value= nDebit;
	document.getElementById('txtCreditBalance').value= nCredit;
			
}


function ChangingOpeningBals(){
	
	if(document.getElementById('txtOpeningBalance').value==""){
		//document.getElementById('txtOpeningBalance').value ='0'
	}
	if(document.getElementById('txtstatementbal').value==""){
		//document.getElementById('txtstatementbal').value ='0'
	}
	
		
	EndingBal = parseFloat(document.getElementById('txtOpeningBalance').value) + parseFloat(document.getElementById('txtDeposits').value) - parseFloat(document.getElementById('txtPayments').value);
	
	document.getElementById('txtdifference').value = parseFloat(document.getElementById('txtstatementbal').value) - EndingBal;
}
</script>
<style>

	.slide{	
		
		z-index:2000;
		position:absolute; 
		width:auto;
		height:auto;
		background:#F0F0F0;
		border:1px solid #999999;
		left:10%;
		top:50%;
		padding: 10px;
		-moz-border-radius: 10px;
		-webkit-border-radius: 10px;
		padding:0px;
				
	}	
	
	.dataclass {	
		padding: 10px;
		-moz-border-radius: 5px;
		-webkit-border-radius: 5px;		
	}
	
</style>

<?php 
  require('../'.DIR_WS_INCLUDES . 'initmenu.php'); 
 $lablearray =getlables("2,35,387,389,390,391,392,393,394,395,396,397,398,399,400,401,402,412,416,416,417,418,419,420,421,423"); 
 $glaccounts = getAccountLevels();
 ?>
<fieldset>
 
<form action="../finalaccounts/trialbal.php?action=insert" method="post" style="height:auto;" id='frmreconciliation' name='frmreconciliation'>
 
 
 
   <span id="slidebottom" class="slide">		  
			 
	  <table border="0" cellpadding="2" cellspacing="0">
	 <tr>
		<td colspan="3" align="left" ></td>				
	  </tr>	
	  		  
	   <tr>
		<td colspan="3" align="left" id='txtHint2'></td>				
	  </tr>			 
	 
	   <tr>
		<td  align="right">
		</td>
		<td  align="right">
			
		</td>
		<td  align="right">
			<input  type="button" value="Save"  class="actbutton" onClick="updateTransaction()" ><input  type="button" value="Close"  class="actbutton"  id="btnclosepanel">
	  </td>
	 
		
	  </tr>
	</table>
		
</span>
 <span id='status'></span>
			<?php echo TEXT_FIELD_REQUIRED;?><?php echo $lablearray['247'];?>			
			<input name="action" type="hidden"  id="action" value="search">
			<input name="reconciliationhistory_id" id="reconciliationhistory_id" type="hidden" value="">
			
			
		 <table width="100%" border="0" cellspacing="0" cellpadding="0">
				<tr>
				<td  colspan="2" >
				
				  
				<table width="100%" border="0" cellspacing="2" cellpadding="0">
					<tr>
					<td colspan="2" align="center"><span id="status" style='color:#006600;'></span></td>
				  </tr>
			
				  
				  <tr>
					<td colspan="2" align="center">
					
					
					<table width="100%" border="0" cellspacing="0" cellpadding="5">
					<tr>
						<td><?php echo $lablearray['35'];?> &nbsp;<br><select id='bankaccounts_accno' name='bankaccounts_accno' onChange="getGLAccount(this.value)">
							<option id="" value="">Select Account...</option>
							<?php while ($bankaccounts  = tep_db_fetch_array($query_result)){
								echo "<option id=".$bankaccounts['bankaccounts_accno']." value=".$bankaccounts['bankaccounts_accno'].">".$bankaccounts['banks_name']." ".$bankaccounts['bankaccounts_accno'].":".$bankaccounts['bankbranches_name']."</option>";
							
							}
							?>
							</select><?php echo TEXT_FIELD_REQUIRED;?>  </td>
						<td nowrap="nowrap">							
							 <?php echo $lablearray['391'];?><br> <input name="chartofaccounts_accountcode" type="text" id="chartofaccounts_accountcode" value=""  onClick="" disabled="disabled">                        
                            <?php echo TEXT_FIELD_REQUIRED;?> 
							
							</td>
					  </tr>
					<tr>
						<td><?php echo $lablearray['392'];?> &nbsp; <br>
						<input  name="txtStatementDate" class="yellowfield"  id="txtStatementDate" type="text" size="15" width="32" value=""  onClick=""  readonly/>
							<script language="JavaScript">
								new tcal ({
									// form name
									'formname': 'frmreconciliation',
									// input name
									'controlname': 'txtStatementDate'
								});
							</script> <?php echo TEXT_FIELD_REQUIRED;?>    
						 <input  type="button" value="  <?php echo $lablearray['402'];?>  " id='reset' class="actbutton" onClick="getBeginBalance()"> 
						</td>
						<td><?php echo $lablearray['393'];?><br>
							<input name="txtlastrecondate"  id="txtlastrecondate" type="text" size="15" width="32" value=""  readonly/>
							 <script language="JavaScript">
								new tcal ({
									// form name
									'formname': 'frmreconciliation',
									// input name
									'controlname': 'txtlastrecondate'
								});
							</script>   <?php echo TEXT_FIELD_REQUIRED;?>                   
                           
							</td>
					  </tr>
					   <tr>
						<td><?php echo $lablearray['394'];?><br><input name="txtstatementbal" type="text" id="txtstatementbal"  value='0.000' onKeyUp="ChangingOpeningBals()">&nbsp; </td>
						<td><?php echo $lablearray['395'];?><br><input name="txtOpeningBalance" type="text" id="txtOpeningBalance"   value='0.000' onKeyUp="ChangingOpeningBals()"><span id='beginbal'></span></td>
					  </tr>
					 	  
					  					  
					   <tr>
						<td colspan="2" >
						<span  onClick="if(document.getElementById('lblsign').innerHTML=='+'){eval(&quot document.getElementById('lblsign').innerHTML='-';\n toggle('postings')&quot)}else{  eval(&quot document.getElementById('lblsign').innerHTML='+';\n toggle('postings')&quot)}" style=" color:#006600;cursor:pointer;"><span id="lblsign">+</span><?php echo $lablearray['398'];?></span>
						<fieldset style="display:none;" id="postings">
							<table width="100%" border="0" cellspacing="2" cellpadding="5">
						  <tr>
							<td><?php echo $lablearray['400'];?><br><input name="txtserviceCharge" type="text" id="txtserviceCharge" value='0.000' onKeyPress="return EnterNumericOnly(event,'txtserviceCharge')"></td>
							<td><?php echo $lablearray['317'];?> <br><input name="txtserviceChargeDate"   id="txtserviceChargeDate" type="text" size="15" width="32" value=""  readonly/>
							<script language="JavaScript">
								new tcal ({
									// form name
									'formname': 'frmreconciliation',
									// input name
									'controlname': 'txtserviceChargeDate'
								});
							</script>                       </td>
							<td><?php echo $lablearray['423'];?><br><?php echo DrawComboFromArray($glaccounts,'chargeACC','');?><input  type="button" value="Post" id='reset' class="actbutton" onClick="PostChargesInterest('C')"></td>
						  </tr>
						   <tr>
							<td><?php echo $lablearray['401'];?><br><input name="txtInterest" type="text" id="txtInterest"  value='0.000' onKeyPress="return EnterNumericOnly(event,'txtInterest')"></td>
							<td><?php echo $lablearray['317'];?><br><input name="txtInterestDate" class="yellowfield"  id="txtInterestDate" type="text" size="15" width="32" value="" readonly/>
							<script language="JavaScript">
								new tcal ({
									// form name
									'formname': 'frmreconciliation',
									// input name
									'controlname': 'txtInterestDate'
								});
							</script>                       </td>
							<td><?php echo $lablearray['401'];?><br><?php echo DrawComboFromArray($glaccounts,'InterestACC','');?><input  type="button" value="Post" id='reset' class="actbutton" onClick="PostChargesInterest('I')"></td>
						  </tr>
						 
						</table>
						</fieldset>
						
						</td>
					
					  </tr>
					
										 
					</table>
					</td>
				  </tr>
				
				
								 
				</table>				
				
				<tr>
					<td id='txtHint' colspan="2"  bgcolor="#FFFFFF" align="center"  valign="top"></td>
				</tr>
				
				  <tr height="25">
					<td align="right" colspan="2" ><input  type="button" value="<?php echo $lablearray['387'];?>" id='go' class="actbutton" style="width:150px;" onClick="updateForm()"><input  type="button" value="View Report"  class="actbutton" style="width:150px;" onClick="openPopupListWindow('../downloadlistpdf.php?rcode=RECON&txtTo='+document.getElementById('txtTo').value+'&txtFrom='+document.getElementById('txtFrom').value+'&bankaccounts_accno='+document.getElementById('bankaccounts_accno').value+'&chartofaccounts_accountcode='+document.getElementById('chartofaccounts_accountcode').value+'&statementbal='+document.getElementById('txtstatementbal').value+'&OpBal='+document.getElementById('txtOpeningBalance').value)"></td>
				
				</tr>
					<tr>
					<td  valign="top">
					<table width="100%" border="0" padding="2"> 
						<tr>
						<td><?php echo $lablearray['418'];?></td>
						<td><input name="txtDeposits" type="text" id="txtDeposits"  value='0.000' disabled="disabled"></td>
					  </tr>
					  <tr>
						<td><?php echo $lablearray['419'];?></td>
						<td><input name="txtPayments" type="text" id="txtPayments"  value='0.000' disabled="disabled"></td>
					  </tr>
					 
					</table>

					</td>
					
					<td  bgcolor="#FFFFFF">
					  <table width="100%" border="0" padding="2"> 
						
					  <tr>
						<td><?php echo $lablearray['420'];?>:&nbsp;</td>
						<td align="right"><input name="txtbalancecleared" type="text" id="txtbalancecleared"  value='0.000' disabled="disabled"></td>
					  </tr>
					    <tr>
						<td><?php echo $lablearray['421'];?></td>
						<td align="right"><input name="txtdifference" type="text" id="txtdifference"  value='0.000' disabled="disabled"></td>
					  </tr>
					 
					</table>
					</td>
				</tr>
				
				
				  <tr height="25">
					<td align="right" colspan="2" ><input  type="button" value="<?php echo $lablearray['417'];?>"  class="actbutton" style="width:150px;" onClick="saveReconcile()"><input  type="reset" value="  <?php echo $lablearray['2'];?>  " id='reset' class="actbutton"></td>
				
				</tr>						
				</table> 


	</form>					
	<?php $footercontrols = "	
	<form action='../finalaccounts/trialbal.php?action=insert' id='frmreconciliation' name='frmunreconciliation'>
	
		".$lablearray['416']."<br>
		<input  name='txtUnreconcileDate' class='yellowfield'  id='txtUnreconcileDate' type='text' size='15' width='32' value=''  onClick=''  readonly/>
				<script language='JavaScript'>
					new tcal ({
						// form name
						'formname': 'frmunreconciliation',
						// input name
						'controlname': 'txtUnreconcileDate'
					});
				</script> 
				
		
		<input  type='button' value='Unreconcile'  class='actbutton' style='width:150px;' onClick='UnReconcile()'>
		</form>
		";
		
 require('../'.DIR_WS_INCLUDES . 'userfooter.php'); ?>		
</BODY>
</HTML>
