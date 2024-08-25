<?php

  require_once('../includes/application_top.php');
  require_once("../simple-php-captcha-master/simple-php-captcha.php");
  $_SESSION['captcha'] = simple_php_captcha(array(),'TRANSFER');
  $_parent = basename(__FILE__);
 $code_array = unserialize($_SESSION['_CAPTCHA']['config']);//['code'];
 //echo  $code_array['code']; 
  tep_db_query("INSERT INTO ".TABLE_TRANSFERCODES." (transfercodes_datecreated,transfers_code,operatorbranches_code) VALUES (NOW(),'".$code_array['code']."','".$_SESSION['operatorbranches_code']."')");
	$retainvalues =false;
 // fnEncrypt(trim($password),'PASSWORD');
	/*$printerList = printer_list(PRINTER_ENUM_LOCAL);
      //  var_dump($printerList);
        $printerName = $printerList[3]['NAME'];
  
		
		$fhandle = fopen("D:\CCS Documents\Artciles_Nov_2014.docx","rb");
		$content= fread($fhandle,filesize("D:\CCS Documents\Artciles_Nov_2014.docx"));
        if($ph = printer_open($printer)) {
           //$content = "hello";
		//	printer_start_doc($ph, "Start Doc");	
           // Set print mode to RAW and send PDF to printer
           printer_set_option($ph, PRINTER_MODE, "RAW");
           printer_write($ph,$content);
           printer_close($ph);
		 //  printer_end_doc($ph);
        }
		
*/
 // if the user is not logged on, redirect them to the login page
  if(AuthenticateAccess('LOGIN')==0){
	//tep_redirect(tep_href_link(FILENAME_DEFAULT));
	tep_redirect(tep_href_link(FILENAME_LOGIN));
 }

$results_query = tep_db_query("SELECT op.operatorbranches_code, operatorbranches_name FROM " .TABLE_USERBRANCHES." us,".TABLE_OPERATORBRANCHES." op WHERE op.operatorbranches_code=us.operatorbranches_code");
 
while ($cats = tep_db_fetch_array($results_query)) {
	$operatorbranches[$cats['operatorbranches_code']] = $cats['operatorbranches_name'];
}

$results_query = tep_db_query("SELECT operatorcode, licence_organisationname FROM " .TABLE_LICENCE." WHERE licence_build='".$_SESSION['licence_build']."'");
 
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


	//session_start();
	// here you can perform all the checks you need on the user submited variables
        $_SESSION['security_number']=rand(10000,99999);


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
<script type="text/javascript" src="../includes/javascript/gibberish-aes.js"></script>
<script type="text/javascript" >
$( document ).ready(function(){
		/*
		// was for testing
		$( "#currencies_code" ).change(function() {
		  alert( "Handler for .change() called." );
		});*/
		 
		$("#btnsave").click(function(){
		
			
			var charges_array = [];
			$("input[name='charges[]']").each(function() {			
				charges_array.push(this.id+':'+$(this).val());
			});	
					
			var transfers_qtn = $("#transfers_qtn").val()
			//var transfers_qtn2 = CryptoJS.AES.encrypt(transfers_qtn,transfers_qtn);
			
			var transfers_ans = $("#transfers_ans").val();
			//var transfers_ans2 = CryptoJS.AES.encrypt(transfers_ans,transfers_ans);
			
			
			
			//var pass = 'my secret long pass-phrase (????? ??????) qiYV3xmL5uW1bUeGe6gZH1aaaA4HFgwkwux2uKSKcSmCW6XprmNmkEKdma76Zr1';
			//var secret_string = 'my secret message (????? ?????????)';
			GibberishAES.size(256);    // Also 192, 128
			var transfers_qtn2 = GibberishAES.enc(transfers_qtn, transfers_qtn);
			
			var transfers_ans2 = GibberishAES.enc(transfers_ans, transfers_ans);
			
			//var decrypted_secret_string = GibberishAES.dec(encrypted_secret_string, pass);
			GibberishAES.size(256);

			
			
			
			
			// $("#transfers_code").val();
			
		//	var transfers_code2 = CryptoJS.AES.encrypt(transfers_code,transfers_code);
			
		
	//		 var encrypted = CryptoJS.AES.encrypt("Message", "Secret Passphrase");
//   			 var decrypted = CryptoJS.AES.decrypt(encrypted, "Secret Passphrase");
			 
			//alert(decrypted.toString(CryptoJS.enc.Utf8));
			var transfers_isclient =''; 
			var transfers_isclient_c = $("#transfers_isclient").val();
			if(transfers_isclient_c=='C'){			
					transfers_isclient ='C'
			}else{			
				transfers_isclient ='C'
			}
			var transfers_code = $("#transfers_code").val();
			var transfers_amount = $("#transfers_amount").val();
			var operatorcode = $("#operatorcode").val();
			var transfers_fee = $("#transfers_fee").val();
			var operatorbranches_code = $("#operatorbranches_code").val();			
			var transfers_smsfee = $("#transfers_smsfee").val();
			var transfers_chargerate = $("#transfers_chargerate").val();
			var transfers_vat = $("#transfers_vat").val();
			var transfers_amountoreceive = $("#transfers_amountoreceive").val();
			var currencies_code = $("#currencies_code").val();
			var transfers_address_rec  = $("#transfers_address_rec").val();
			var country_origin = $("#country_origin").val();
			// to be computed on server side
			//var transfers_total = $("#transfers_total").val();
			var transfers_firstname = $("#transfers_firstname").val();
			var transfers_middlename = $("#transfers_middlename").val();
			var transfers_lastname = $("#transfers_lastname").val();
			var countries_iso_code_3 = $("#countries_iso_code_3").val();
			var countries_iso_code_3_rec = $("#countries_iso_code_3_rec").val();
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
			
			
		
		
		
		//var decrypted = CryptoJS.AES.decrypt("U2FsdGVkX1/Z8idkV8lj3E4NVMTusFZ5mSjVnYeoL/w=", "376237285935596");
		//alert(decrypted.toString(CryptoJS.enc.Utf8));
		//return;
			// validations
			
			var transfers_datecreated = new Date();
					
			$("#status").load("../addedit.php",{frmid:'frmtransfer',action:'add',transfers_isclient:transfers_isclient,transfers_amount:transfers_amount,operatorcode:operatorcode,transfers_fee:transfers_fee,operatorbranches_code:operatorbranches_code,transfers_smsfee:transfers_smsfee,transfers_chargerate:transfers_chargerate,transfers_vat:transfers_vat,transfers_amountoreceive:transfers_amountoreceive,currencies_code:currencies_code,transfers_firstname:transfers_firstname,transfers_middlename:transfers_middlename,transfers_lastname:transfers_lastname,countries_iso_code_3:countries_iso_code_3,countries_iso_code_3_rec:countries_iso_code_3_rec,transfers_telephone:transfers_telephone,transfers_address:transfers_address,documenttypes_id:documenttypes_id,transfers_docnum:transfers_docnum,transfers_docissuedate:transfers_docissuedate,transfers_docexpdate:transfers_docexpdate,transfers_firstname_rec:transfers_firstname_rec,transfers_middlename_rec:transfers_middlename_rec,transfers_lastname_rec:transfers_lastname_rec,transfers_telephone_rec:transfers_telephone_rec,country_origin:country_origin,transfers_address_rec:transfers_address_rec,transfers_qtn:transfers_qtn2.toString(),transfers_ans:transfers_ans2.toString(),'transfers_code':transfers_code,'captchcode':transfers_code,jason:charges_array,transfers_datecreated:transfers_datecreated}			
			,function() {
						
				$("#myDiv").html("View / Print Reciept "+transfers_code);
				
				$("#status").addClass("messageStackSuccess");				
				$("#status").effect( "shake", "slow" );	
				$( "#status" ).fadeOut(5000);
				$( "#status" ).stop(false,fase);
				openPopupListWindow('../downloadlistpdf.php?rcode=SENDERRECEIPT&TCODE='+$("#transfers_code").val(),'410','350');
				
				document.getElementById("frmtransfer").reset();
				
				 return;
		
			});	
		
		});
		
		$("#myDiv").click(function(){
					
			openPopupListWindow('../downloadlistpdf.php?rcode=SENDERRECEIPT&TCODE='+$("#transfers_code").val(),'410','350');
		
		});
		
		$("#transfers_amount, #transfers_amount").blur(function(){
			showResult("frmid=frmtransfer&action=evaluatecharge&transfers_amount="+$("#transfers_amount").val(),"")
		});
		
		
		
		
 
	
	});

</script>

<?php



getlables("1,2,945,935,929,916,933,914,884,885,886,887,888,889,890,891,892,893,894,895,896,897,898,899,890,891,892,893,894,895,896,897,899,900,901,902,903,904,905,906,907,909,908,910,911,912");





?>  
<style type="text/css">
<!--
body,td,th {
	font-size: 0.9em;
}
-->
</style>
<form action="transfer.php"  id="frmtransfer" name="frmtransfer">
<span id='status'></span>
<div id="myDiv"></div>	

<span style='color:#009900;text-shadow:none;padding:4px;'><a></a></span>  

	
	<div style='float:center;  text-align:center;color:#FFFFFF;text-shadow:none;padding:10px;' id='myDiv'></div>  
			
	
			<fieldset >
				 <legend><?php echo $lablearray['884'];?></legend>
			<table width="100%" border="0" cellpadding="5" cellspacing="0" >
						<tr>
							<td colspan="2">	
							<?php echo tep_draw_radio_field('transfers_isclient_c','C', true,'')."&nbsp;&nbsp;".$lablearray['885'];
							 	echo tep_draw_radio_field('transfers_isclient_n','N', false,'')."&nbsp;&nbsp;".$lablearray['886'];?><?php echo TEXT_FIELD_REQUIRED;?>
								</td>
						</tr>
							<tr>
							<td><?php echo $lablearray['887'];?><br>
							<?php echo tep_draw_input_field('transfers_amount','','',false,'text',$retainvalues,'32'); ?><?php echo TEXT_FIELD_REQUIRED;?>
							</td>
								<td>
								<?php echo $lablearray['888'];?><br>
								<?php echo DrawComboFromArray($operators,'operatorcode','')?> <?php echo TEXT_FIELD_REQUIRED;?>
								</td>
							</tr>
							
							<tr>
							<td ><?php echo $lablearray['895'];?><br>
								<select id="currencies_code" name="currencies_code" OnChange="showResult('id='+this.value+'&frmid=frmforexrates','txtHint')">
								<option id="" name=""> </option>
								<?php 
								
								$currency_results =tep_db_query("SELECT currencies_code,name,currencies_id FROM ".TABLE_CURRENCIES." ORDER BY name");
								
								while($currency = tep_db_fetch_array($currency_results)){
									echo "<option id='".$currency['currencies_code']."' value='".$currency['currencies_code']."'>".$currency['name'].":  ".$currency['currencies_code']."</option>";
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
								<?php echo $lablearray['945'];?><br>
								<?php echo DrawComboFromArray($countries,'country_origin','')?> <?php echo TEXT_FIELD_REQUIRED;?>
						
								
							</td>
								<td>
								<?php echo $lablearray['894'];?><br>
								<?php echo tep_draw_input_field('transfers_amountoreceive','','disabled=\'disabled\'','','',false,'text',$retainvalues,'32'); ?>
								</td>
							</tr>
							
							<td>
							</td>
								<td>
								<?php echo $lablearray['896'];?><br>
								<?php echo tep_draw_input_field('transfers_total','','disabled=\'disabled\'','','',false,'text',$retainvalues,'32'); ?>
								</td>
							</tr>
							
							<tr>
							<td  colspan="2" align="center">
						
							
							
								
								<div style="height: 105px;">
								<div data-dojo-type="dijit/layout/TabContainer" style="width: 95%;height: 100%"  tabPosition="right-h" >
									<div data-dojo-type="dijit/layout/ContentPane" title="My first tab" data-dojo-props="selected:true">
										Lorem ipsum and all around...
									</div>
									<div data-dojo-type="dijit/layout/ContentPane" title="My second tab" data-dojo-props="closable:true">
										Lorem ipsum and all around - second...<br />
										Hmmm expanding tabs......
									</div>
									<div data-dojo-type="dijit/layout/ContentPane" title="My last tab">
										Lorem ipsum and all around - last...<br />
										<br />
										<br />
										Hmmm even more expanding tabs......
									</div>
								</div>
							</div>
							</fieldset>
							</td>
							</tr>
						  </table>
			


 </form>

</BODY>
</HTML>
