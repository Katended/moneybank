<?php
require_once('../includes/application_top.php');
$glaccounts = getAccountLevels();
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
<script type="text/javascript" src="../includes/javascript/commonfunctions.js"></script>
<script language="JavaScript"  type="text/javascript">

	var url ='';
	var iface = '';
	url = "../addedit.php";
	var cvat ="";
	var benabled ='';
	// selecct which option is gona be used to write off arrears
function selectOption(obj){
	var bdisabled = false;

	if(obj.checked==true){
		bdisabled = false;
		benabled ='N';
	}else{
		bdisabled = true;
		benabled ='Y';
	}

	var elem = document.getElementById('frmvat').elements;

	for(var i = 0; i < elem.length; i++){
		if(elem[i].type=='text'){
			if(elem[i].name=='txtvat'){
				elem[i].disabled = bdisabled;
			}
		}
	}


}

function updateVat(){
	cvat ="";
	var elem = document.getElementById('frmvat').elements;

	for(var i = 0; i < elem.length; i++){
		if(elem[i].type=='text'){
			if(elem[i].name=='txtvat'){
				cvat = cvat +'&R'+elem[i].id+'='+elem[i].value;
			}
		}
	}

	showResult('frmid=frmvat&action=update&benabled='+benabled+'&ACC_VAT_ON_PURCHASES='+document.getElementById('ACC_VAT_ON_PURCHASES').value+cvat,'')

}


</script>
<!-- Beginning of menu code below -->
<link href="../includes/menu/DROPDOWN/DROPDOWN.CSS" media="screen" rel="stylesheet" type="text/css" />
<link href="../includes/menu/DROPDOWN/THEMES/MTV.COM/default.ultimate.css" media="screen" rel="stylesheet" type="text/css" />

<!--[if lt IE 7]>
<script type="text/javascript" src="js/jquery/jquery.js"></script>
<script type="text/javascript" src="js/jquery/jquery.dropdown.js"></script>
<![endif]-->
<!-- / END -->
<style type="text/css">
<!--
body,td,th {
	font-size: 0.9em;
}
-->
</style></HEAD>
<BODY class="main" onLoad="doSomething(foo)" >
<table cellpadding="0" align="center" style="padding:0px;">
<tr>
	<td>
		<?php require('../'.DIR_WS_INCLUDES . 'userheader.php'); ?>
	</td>
</tr>
</table>
<TABLE cellSpacing=0 cellPadding=0 width="100%" align=center border=0>
  <TBODY>
  <TR>
    <TD>
      <TABLE cellSpacing=0 cellPadding=0 align=center border=0>
        <TBODY>
        <TR>
          <TD>
            <TABLE cellSpacing=0 cellPadding=0 align=left border=0>
              <TBODY>
                <TR>
                <TD class=mbdy_mid_left></TD>
                <TD class=mbdy_mid_center valign="top">
			<form action="" method="post" style="width:100%;height:auto;" id='frmvat' name='frmvat'>
			<h1>Value Added Tax</h1>

			 <table width="100%" border="0" cellspacing="0" cellpadding="0">
				<tr>
				<td  colspan="2">
               <table width="100%" border="0" cellspacing="2" cellpadding="0">

				  <tr>
					<td>
					 <h2>VAT on Purchases</h2>
					</td>
					<td>
                     <?php echo DrawComboFromArray($glaccounts,'ACC_VAT_ON_PURCHASES',ACC_VAT_ON_PURCHASES);?>
                    </td>
				  </tr>


				</table>
				<table width="100%" border="0" cellspacing="2" cellpadding="0">

				  <tr>
					<td>
					 <h2>On fees</h2>
					</td>
					<td id='section1'>

                    </td>
				  </tr>


				</table>

				<tr>
					<td colspan="2" id='txtHint' align="center" ></td>
				</tr>
				</table>


				</form>


				</TD>
                <TD class="mbdy_mid_right"></TD></TR>
              <TR>
                <TD class=mbdy_bot_left></TD>
                <TD class=mbdy_bot_center></TD>
                <TD
      class=mbdy_bot_right></TD></TR></TBODY></TABLE></TD></TR></TBODY></TABLE></TD></TR>
  <TR>

        <TD> </TD>
      </TR></TBODY></TABLE>
	  <script language="JavaScript"  type="text/javascript">
		showResult('frmid=frmvat','section1');

		benabled ='<?php echo VAT_ON_FEES_ENABLED;?>';

		if(benabled=='Y'){
			setTimeout("document.getElementById('enablevat').click()",1000);
		}


	 </script>

</BODY>
</HTML>
