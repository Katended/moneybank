<?php
  require('../includes/application_top.php');
  require_once('../includes/TCPDF.PHP');   

  $IsOpeningbal = $_POST['openingbal'];
  $benabled =true;
  $bpost = true;
  
 
  if($_POST['action']=="" || $_POST['action']=="add"){
	  $action = "add";
  }else{
	  $action = $_POST['action'];
  }
  
  if($_GET['students_sregno']!=""){
	  $students_sregno = replaces_underscores($_GET['students_sregno']);
  }else{
	  $students_sregno = replaces_underscores($_POST['students_sregno']);
  }
		
  // get student name
  $student_query = tep_db_query("SELECT CONCAT(students_firstname,' ',students_lastname) AS Name FROM " .TABLE_STUDENTS." WHERE students_sregno='".$students_sregno."'");	 
  $student = tep_db_fetch_array($student_query);	
	
	// check student fees categories
	$result = tep_db_query("SELECT studentfeecategories_id FROM ".TABLE_STUDENTFEECATEGORIES."   WHERE students_sregno='".$students_sregno."'");	

if(!tep_db_num_rows($result) && $_POST['students_sregno']!=""){
	 $_SESSION['msg'] = " Sorry, you can not add transaction(s)<br>  The Student doesnot belong to any Fees category.<br> Please click <a href='".DIR_WS_CATALOG."students/addpupil.php?action=edit&students_sregno=".replace_string($students_sregno)."'> here </a> to add the student to a Fees category." ;
	 $bpost =false;
}
	
// check dates
if(greaterDate($_POST['trandate'],date('m/d/Y'))==true && $bpost == true){			
	$_SESSION['msg'] ='Invalid transaction date. Transaction date is set in the future.';
	$bpost = false;
}
	
if($_POST['action']=='update' &&  $bpost ==true){	
	tep_db_query("START TRANSACTION");	
	tep_db_query("UPDATE " .TABLE_STUDENTSPAYMENTS." SET transactiontypes_code='".$_POST['transactiontypes_code']."',studentspayments_amount='".$_POST['amount']."',requirements_id='".$_POST['requirements_id_edit']."',studentspayments_dateupdated=NOW(),studentspayments_voucher='".$_POST['voucher']."' WHERE studentspayments_id = '".$_POST['tran_id']."'");		
	$bpost = UpdateTransactions($students_sregno,$_POST['requirements_id_edit'],$_POST['tcode'],$_POST['amount']);
	
	if($bpost ==true){
		tep_db_query("COMMIT");	
	}else{
		tep_db_query("ROLLBACK");
	}
	$benabled = true;
	$action ="add";
			
	$bpost ==false;
}
	
$amts  	=	$_POST['amounts'];

if(count($amts)==0 && $_POST['action']=='add'){	
	$_SESSION['msg'] ='Please add transaction(s)';
	$bpost ==false;
}
$name2 = $student['Name'];

$print_reciept = $_POST['print_reciept'];

if($bpost ==true && $_POST['action']=='add'){	
	$voucher =	$_POST['voucher'];
	$rids 	 =	$_POST['rids'];
	$paymode =	$_POST['paymode'];		
	$trandate   =  $_POST['trandate'];
	$bankaccounts   =  $_POST['bankbranch'];
	$cheqno   =  $_POST['cheqno'];
	$checktype   =  $_POST['checktype'];		
	$branchcode = 	$_POST['branchcode'];
	$cashAccount = $_POST['cashaccounts_code'];		


	$Obal 	= getBalancesBF($students_sregno,$rids);
	
	if(count($amts)>0 && $_POST['action']=="add"){
	
		
		tep_db_query("START TRANSACTION");	
	
	
		PostRepaymentransactions($students_sregno,$paymode,$rids,$_POST['voucher'],$amts,$_POST['trandate'],$_POST['studentspayments_recievedfrom'],$Obal,$bankaccounts,$cheqno,$checktype,$bankbranch,$branchcode,$cashAccount);				
		
		if($_SESSION['reciepts_code']!=""){
			tep_db_query("COMMIT");	
		}else{
			tep_db_query("ROLLBACK");
		}

	}
	
	if ($_SESSION['reciepts_code']!=""){
		//$_SESSION['msg'] = "Transaction(s) have been successfully posted";
		tep_redirect(tep_href_link('reciepts/makepayment.php?p=1&students_sregno='.replace_string($students_sregno)));						
	}
			
}

if($trandate==""){

	$trandate = date('m/d/Y');
}
?>
	
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3c.org/TR/1999/REC-html401-19991224/loose.dtd">
<HTML lang="<? echo $_SESSION['P_LANG'];?>"><HEAD><TITLE><?php echo NAME_OF_INSTITUTION;?></TITLE>
<script src="../includes/javascript/stickynote.js">

/***********************************************
* Sticky Note Script (c) Dynamic Drive (www.dynamicdrive.com)
* This notice MUST stay intact for legal use
* Visit http://www.dynamicdrive.com/ for this script and 100s more.
***********************************************/
</script>
<link rel="stylesheet" href="../styles/CALENDAR.CSS"> 
<script language="JavaScript" src="../includes/javascript/calendar_us.js"></script>
<script type="text/javascript" src="../includes/javascript/gridsearch.js" language="javascript"></script>
<link  media="screen"  type="text/css" rel="stylesheet" href="../includes/javascript/de.css"></link>
<script type="text/javascript" src="../includes/javascript/jquery-1.6.2.min.js"></script>
<script type="text/javascript" src="../includes/javascript/jquery-ui-1.8.16.custom.min.js"></script>
<script type="text/javascript" src="../includes/javascript/jquery.pnotify.js"></script>
<script type="text/javascript" src="../includes/javascript/commonfunctions.js"></script>

<META http-equiv=Content-Type content="text/html; charset=iso-8859-1">
<LINK href="../stylesheet.css" type=text/css rel=stylesheet>
<script language="JavaScript" type="text/javascript">	
	var url ='';
	var iface = '';	
	url="../addedit.php";
	
	function disableField(fieldname1,fieldname2,svalue) {					
		if(document.getElementById(fieldname1).value==svalue){			
			document.getElementById(fieldname2).className = 'disabled';
			document.getElementById(fieldname2).disabled=true;			
		}else{
			document.getElementById(fieldname2).className = '';
			document.getElementById(fieldname2).disabled=false;			
		}		
		return true;
	}
	
	
	function getTransactionNames(transactiontypes_id){
	
		switch (transactiontypes_id) {
			<?php
			$transaction_types_query = tep_db_query("select transactiontypes_code, transactiontypes_name from " . TABLE_TRANSACTIONTYPES." ORDER BY transactiontypes_name");
			 while($ttypes = tep_db_fetch_array($transaction_types_query)) {?>
			 
				case "<?php echo $ttypes['transactiontypes_code']?>":
				
				return "<?php echo $ttypes['transactiontypes_name']?>";
			
				break;
			<?php }	?>
		
		}
	}
</script>



<script language="JavaScript"  type="text/javascript">
	
	function getdata(paging,formid,action,searchterm) {
		if(paging=='undefined'){
			paging ='';
		}
		str= paging + '&transactiontypes_code='+ encodeURI(document.getElementById('transactiontypes_code2').value) + '&operator=' + encodeURI(document.getElementById('operator').value) + '&amount='+ encodeURI(document.getElementById('txtamount').value);
		//document.getElementById('txtHint').innerHTML = "<p style='text-align:center'>loading your request...<br><img src='images/loading.gif'></p>";
		makeRequest(str);	
	}
	
	
	function displayBanks(obj){
	
		switch(obj.value){
		
		case 'CA':
			document.getElementById('banks').style.display = 'none';
			document.getElementById('cheqtype_id').disabled = false;
			document.getElementById('cheques_no').disabled = false;
			break;

		case 'CQ':
			document.getElementById('banks').style.display = 'block';
			document.getElementById('cheqtype_id').disabled = false;
			document.getElementById('cheques_no').disabled = false;
			break;
		  
		case 'BT':
			document.getElementById('banks').style.display = 'block';
			document.getElementById('cheqtype_id').disabled = true;
			document.getElementById('cheques_no').disabled = true;
		 	break;
		  
		default:
		  document.getElementById('banks').style.display = 'none';		  
		}		
	}	
		
	function searchResults(str){
		  document.getElementById("livesearch").style.display="block";
		if (str.length==0){
				
			  document.getElementById("livesearch").innerHTML="";
			  document.getElementById("livesearch").style.border="0px";
			  document.getElementById("livesearch").style.padding="0px";
			  document.getElementById("livesearch").style.height="0px";
			  return;
		
		}else{
			str= 'frmid=frmpaysearch'+ '&searchterm='+document.getElementById("searchterm").value;
			document.getElementById("livesearch").style.height="200px";
			document.getElementById("livesearch").style.overflow="scroll";
			document.getElementById("livesearch").style.padding="0";
			document.getElementById("livesearch").innerHTML = "<p style='text-align:center;'>loading..<br><img src='../images/loading.gif'></p>";
			showResult(str,'livesearch');
			
	
		}
		  
		  
	
  }
  function searchSelect(students_sregno,name){
	 document.getElementById("livesearch").innerHTML="";
	 document.getElementById("livesearch").style.border="0px";
	 document.getElementById("livesearch").style.padding="0px";
	 document.getElementById("livesearch").style.height="0px";
	
	 document.getElementById("students_sregno").value=students_sregno;
	 document.getElementById("searchterm").value=students_sregno;
	 document.getElementById("name").innerHTML=name;
	 document.getElementById("name2").value=name;	  	
	  
	 str='frmid=frmpaysearch&students_sregno='+students_sregno+'&action=load';
	   
	 showResult(str,'txtBalances');
	  
  }
  <?php getlables("358"); ?>
   function getBalance(cvalue){   	 	
	  str='frmid=frmmakepayment&requirements_id='+cvalue+'&action=getbal&students_sregno='+  document.getElementById("students_sregno").value
	 if(!IsNullEmptyField('students_sregno',"<?php echo $lablearray['358'];?>")){
		return; 
	 }else{
	  	showResult('frmid=frmmakepayment&requirements_id='+cvalue+'&students_sregno='+  document.getElementById("students_sregno").value,'txtHint');
	  } 
	 
  }
  
 // document.getElementById("name2").innerHTML="<?php echo $name2;?>;
</script>

<?php require('../'.DIR_WS_INCLUDES . 'initmenu.php'); ?>
<?php $lablearray = getlables("20,71,26,24,42,189,9,67,35,64,351,271,299,352,300,353,317,155,354,353");

?>
<fieldset>
<h1 style='float:right;clear:both;margin:0px;'><span id="name"><?php echo $name2;?></span></h1>
<div id='txtBalances' style="padding:3px;height:100px;margin:0px;width:500px;overflow:auto;border: 1px solid #6699CC;background-color:lightyellow;padding:0px;float:left;margin:0px;">
</div>

<table cellpadding="0" align="center" style="padding:0px;margin:0px;" width="100%"> 

<tr>
<td valign="top">

	<table cellpadding="0"  style="padding:0px;margin:0px;" width="100%"> 
	<tr>
	
		<td valign="top">		
	  <form id='frmpaysearch' style="margin:0px;padding:0px;width:auto;float:right;border:0;">  
		<span><?php echo $lablearray['71'];?> <input id="searchterm"  name="searchterm" type="text" size="26" value="<?php echo $lablearray['189']?>/<?php echo $lablearray['9']?>" onKeyUp="searchResults(this.value)" style='margin-bottom:0px;'  onClick="this.value=''"/></span>
		<div id="livesearch" style="display:none;color:#004AAE;padding:1px;margin:0px;background:#FFFFFF;overflow:auto;width:300px;z-index:6000;position: absolute;" ><?php echo $name2;?></div>
	   </form>
	   </td>
    <tr>
	
	</table> 
  </td>
</tr>
</table> 
<TABLE cellSpacing=0 cellPadding=0 width="100%" align=center border=0>
<TBODY>
<TR>
<TD>
	
<form action="makepayment.php?action=process" method="POST"   onSubmit="return clearField(elementid,fieldtype);" id="frmmakepayment" name="frmmakepayment">				
	<input name="datefield"  id="datefield" type="hidden" value="" <?php if($benabled==false){echo "disabled='disabled'";}?>>
<?php echo $previoustcode;
?>
	<?php echo tep_draw_hidden_field('requirements_id_edit',''); ?>	
	<?php echo tep_draw_hidden_field('tran_id',''); ?>	
	<?php echo tep_draw_hidden_field('action',$action); ?>
	<?php echo tep_draw_hidden_field('name2',$name2); ?>
	<?php echo tep_draw_hidden_field('previoustcode',$_SESSION['reciepts_code']); ?>
	<?php echo tep_draw_hidden_field('students_sregno',replace_string($students_sregno)); ?>
	<?php echo tep_draw_hidden_field('tcode',''); ?>
	<?php echo tep_draw_hidden_field('tran_balance',$transaction['tran_balance']); ?>
	
	<?php 
	$classes_id=$students_details['classes_id'];	
	echo tep_draw_hidden_field('classes_id',$classes_id); ?>
<fieldset>
	<table width="100%" border="0" cellspacing="8" cellpadding="0">
	  <tr>
		<td >		
		<?php echo $lablearray['24']; // Transaction type?>:<br>
	<select name="transactiontypes_code" id="transactiontypes_code" <?php if($benabled==false){echo "disabled='disabled'";}?>  onChange="displayBanks(this)">
	<option id='' value=''><?php echo $lablearray['42']?></option>
	<?PHP 
	
	$transaction_types_query = tep_db_query("select transactiontypes_code, transactiontypes_name from " . TABLE_TRANSACTIONTYPES." ORDER BY transactiontypes_id ASC");
	
	 while ($transactiontypes = tep_db_fetch_array($transaction_types_query)){
			
		if($transactiontypes['transactiontypes_code']==$transactiontypes_code){	
			echo "<option id='".$transactiontypes['transactiontypes_code']."' value='".$transactiontypes['transactiontypes_code']."' selected>".$transactiontypes['transactiontypes_name']."</option>";	
		}else{		
			echo "<option id='".$transactiontypes['transactiontypes_code']."' value='".$transactiontypes['transactiontypes_code']."'>".$transactiontypes['transactiontypes_name']."</option>";
		}
	}	
	?>
	
	</select><?php echo TEXT_FIELD_REQUIRED;?>
		</td>
		<td >
			<?php echo $lablearray['351'];//Set off against:?><br>
			<select name="requirements_id" id="requirements_id" <?php if($benabled==false){echo "disabled='disabled'";}?> onChange="getBalance(this.value);">
			<option id='' value=''><?php echo $lablearray['42']?></option>
			<?PHP 
			
			$requirements_query = tep_db_query("SELECT fca.requirements_id,r.requirements_name FROM ".TABLE_FEECATEGORIESAMOUNT." fca,".TABLE_STUDENTFEECATEGORIES." AS sfc, ".TABLE_REQUIREMENTS." r , ".TABLE_SCHOOLSESSIONFEECATEGORIES." ssfc  WHERE fca.feecategories_id = ssfc.feecategories_id AND fca.feecategories_id = sfc.feecategories_id AND r.requirements_id = fca.requirements_id AND ssfc.schoolsessionfeecategories_currentflag = 'Y' GROUP BY fca.requirements_id");

		
			 while ($requirements = tep_db_fetch_array($requirements_query)){
					
				if($requirements['requirements_id']==$requirements_id){	
					echo "<option id='".$requirements['requirements_id']."' value='".$requirements['requirements_id']."'>".$requirements['requirements_name']."</option>";	
				}else{		
					echo "<option id='".$requirements['requirements_name']."' value='".$requirements['requirements_id']."'>".$requirements['requirements_name']."</option>";
				}
			}
			
			?>		
			</select><?php echo TEXT_FIELD_REQUIRED;?>
		
		</td>
		<td >
		
			<?php echo $lablearray['271'];//Amount?><br> <input name="amount" id="amount" type="text" onKeyPress="return EnterNumericOnly(event,'amount')"><?php echo TEXT_FIELD_REQUIRED;?>			
		</td>
	 <tr>
		<td><?php echo $lablearray['299'];//Voucher?> <br><?php echo tep_draw_input_field('voucher','',$voucher,false,'text',true,'25'); ?></td>
		<td ><?php echo $lablearray['317'];//Date?> <br>
		<input name="trandate" class="yellowfield"  id="trandate" type="text" size="15" width="32" value="<?php echo $trandate;?>"  readonly/>
			<script language="JavaScript">
				new tcal ({
					// form name
					'formname': 'frmmakepayment',
					// input name
					'controlname': 'trandate'
				});
			</script><?php echo TEXT_FIELD_REQUIRED;?></td>
		<td ><?php echo $lablearray['352'];//Recieved From?><br><input name="studentspayments_recievedfrom" type="text" size="20" id="studentspayments_recievedfrom"> 	</td>
	 </tr>	
	
	  <tr>
	  	<td align="left" colspan="3"><?php echo $lablearray['26'];?>/<?php echo $lablearray['155'];?><br><?php echo DrawCheqBanks();
		
		
		?><?php echo DrawCashAccounts($_SESSION['user_id'])?><?php echo TEXT_FIELD_REQUIRED;?>
		<?php  getlables("20,71,26,24,42,189,9,67,35,64,351,271,299,352,300,353,317,155,354,353");

?>		
		
		<td align="right" colspan="2"></td><td align="right"><input name="print_reciept" id ='print_reciept' type="checkbox" value="1" checked="checked"> Print/View Reciept</td>
		</tr>
       
		 <tr>
		 	<td colspan="3" align="center">						
			<input name="submit"  type="button" value="<?php echo $lablearray['354'];?>" onClick="addRow('dataTable','frmmakepayment','amount','voucher','trandate','transactiontypes_code','requirements_id','studentspayments_recievedfrom','cheques_no','bankbranches_id','cheqtype_id');" <?php if($benabled==false){echo "disabled='disabled'";}?> id="add" class="actbutton"><input name="submit"  type="button" value="<?php echo $lablearray['353'];?>" onClick="deleteRow('dataTable');" <?php if($benabled==false){echo "disabled='disabled'";}?> id="remove" class="actbutton"><input name="cancel"  type="button" value="<?php echo $lablearray['300'];?>" onClick="" class="actbutton">
			</td>
		</tr>
        <tr>
		 	<td colspan="3" align="center" height="15">						
			
			</td>
		</tr>	  
	</table>
	</fieldset>
    <?php
		$promise_watchlist_query = tep_db_query("SELECT w.debtorswatchlist_id,tt.transactiontypes_name,w.debtorswatchlist_datecreated,w.debtorswatchlist_notes,w.debtorswatchlist_date,w.debtorswatchlist_amount FROM " . TABLE_DEBTORSWATCHLIST." AS w LEFT JOIN ".TABLE_TRANSACTIONTYPES." AS tt ON w.transactiontypes_id=tt.transactiontypes_id WHERE w.students_sregno='".$students_sregno."' AND w.debtorswatchlist_status='Y' ORDER BY w.debtorswatchlist_datecreated ASC");		
	?>
	<table border='0' width='100%' celpadding='0' cellspacing='0' id="dataTable">
		
			<tr class='bgimage'>
				<td>Transactions</td><td></td><td align="left"></td><td align="right"></td><td ></td><td align="right"></td><td></td><td></td>
			</tr>			
		
	
	</TABLE>

	<table border='0' width='100%' celpadding='0' cellspacing='0'>
	<?php
	if($rowcolor =='#D5E7FF'){				 
			$rowcolor ='#FFFFFF';
	 }else{				 
			$rowcolor ='#D5E7FF';				 
	}
		
	if($bpost==false && count($amts)>0){
		foreach($amts as $k=>$v){
	?>
		<tr bgcolor="<?php echo $rowcolor; ?>">
			<td><input name="" type="checkbox" value=""></td><td></td><td><input name="voc[]" value="<?php echo $voc[$k];?>" type="hidden"><input name="rids[]" value="<?php echo $rids[$k];?>" type="hidden"><input name="amounts[]" value="<?php echo $v;?>" type="hidden"><?php echo $v;?></td><td><input name="tdate[]" value="<?php echo $tdate[$k];?>" type="hidden"><?php echo $tdate[$k];?></td><td></td><td><input name="paymode[]" value="<?php echo $paymode[$k];?>" type="hidden"><?php echo $paymode[$k];?></td><td></td>
		</tr>
	<?php 
		}
	
	}?>
	    				
	</TABLE>
	
	<table border='0' width='100%' celpadding='0' cellspacing='0'>
		<thead>
			<tr class='bgimage'>
				<th></th><th></th><th></th><th></th><th></th><th></th><th></th>
			</tr>			
		</thead>			
		
	</TABLE>
		
	<table width="100%" border="0" cellspacing="0" cellpadding="0">
		 <tr>
			<td  align="right"><input name="submit"  type="submit" value="<?php echo $lablearray['20'];?>" <?php if($benabled==false){echo "disabled='disabled'";}?> class="actbutton"></td>		
		</tr>
		
		 <tr>
			<td id='txtHint' align="center"></td>		
		</tr>
	</table>	

  </form>
	</TD>

      </TR></TBODY></TABLE></fieldset>
   
  <?php require('../'.DIR_WS_INCLUDES . 'userfooter.php'); ?>    
     <script language="JavaScript" type="text/javascript">	  
		if(document.getElementById("students_sregno").value!=""){
		// str='frmid=frmpaysearch&students_sregno='+document.getElementById("students_sregno").value+'&action=load'  
		//showResult(str,'txtBalances');	
		}
	//showResult('students_sregno=<?php echo replace_string($students_sregno);?>&frmid=frmmakepayment','txtHint');
	
	<?php 
		
	if($_SESSION['msg']!="" ){?>
	
		eval(<?php echo informationUpdate('success',$_SESSION['msg'])?>);
	
	<?php } ?>

	
	<?php 
	
	
	if($_GET['p']=='1' && $_SESSION['reciepts_code']!="" ){
	
	?>
			openPopupListWindow('../downloadlistpdf.php?rcode=RCT&recid=<?php echo $_SESSION['reciepts_code'];?>','410','350');
	<?php 
		$_SESSION['reciepts_code']="";
		unset($_SESSION['msg']);
		unset($_POST);
	} ?>
</script>  
</BODY>
	 
</HTML>
