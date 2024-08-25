<?php
require_once('../includes/application_top.php');

$cashaccounts_query = tep_db_query("SELECT ca.cashaccounts_name,ca.chartofaccounts_accountcode,(SELECT CONCAT(currencies_name,' ',currencies_code) FROM " . TABLE_CURRENCIES . " c WHERE coa.currencies_id=c.currencies_id) currency from " . TABLE_CASHACCOUNTS . " ca LEFT JOIN " . TABLE_CHARTOFACCOUNTS . " coa ON coa.chartofaccounts_accountcode=ca.chartofaccounts_accountcode");
while ($cashaccounts_array = tep_db_fetch_array($cashaccounts_query)) {
    $cashaccounts[$cashaccounts_array['chartofaccounts_accountcode']] = $cashaccounts_array['cashaccounts_name'] . " " . $cashaccounts_array['chartofaccounts_accountcode'] . " " . $cashaccounts_array['currency'];
}

$cashitems_query = tep_db_query("select ci.chartofaccounts_accountcode,ci.cashitems_name ,(SELECT CONCAT(currencies_name,' ',currencies_code) FROM " . TABLE_CURRENCIES . " c WHERE coa.currencies_id=c.currencies_id) currency FROM " . TABLE_CASHITEMS . " ci LEFT JOIN " . TABLE_CHARTOFACCOUNTS . " coa ON coa.chartofaccounts_accountcode=ci.chartofaccounts_accountcode");
while ($cashitems_array = tep_db_fetch_array($cashitems_query)) {
    $cashitems[$cashitems_array['chartofaccounts_accountcode']] = $cashitems_array['cashitems_name'] . " " . $cashitems_array['currency'];
}


$glaccounts = getAccountLevels();
 Common::getlables("317,291,316,26,271,318,264,321,350,299", "", "",$Conn);

?>
<script language="JavaScript"  type="text/javascript">

    function updateForm() {
        var ctype = "";

        if (document.getElementById('chkpay').checked == false && document.getElementById('chkreceive').checked == false) {
            displaymessage("frmcashentries","<?php echo Common::$lablearray['321']; ?>","WAR.") 
           // alert("<?php echo Common::$lablearray['321']; ?>");
            return;
        }

        if (document.getElementById('chkpay').checked) {
            ctype = 'P';
        }

        if (document.getElementById('chkreceive').checked) {
            ctype = 'R';
        }

        var pageinfo = JSON.stringify($("#frmcashentries").serializeArray());
        var data1 = JSON.stringify(JSON.parse('{"pageinfo":' + pageinfo + "}"));

        showValues('frmcashentries', '', $('#action').val(), data1, 'addedit.php', $('#cashitems_id').val()).done(function () {

            
        // showResult('frmid=frmcashentries&cashaccounts_id=' + document.getElementById('cashaccounts_id').value+'&txtAmount='+document.getElementById('txtAmount').value +'&txtDescription='+document.getElementById('txtDescription').value+ '&txtdate='+ document.getElementById('txtdate').value+'&branchcode='+document.getElementById('branchcode').value+'&txtBalance='+document.getElementById('txtBalance').value+'&type='+ctype+'&cashitems='+document.getElementById('cashitems').value+'&action='+document.getElementById('action').value+'&currencies_id='+document.getElementById('currencies_id').value,'');

            showValues('frmcashentries', '', 'select', data1, 'load.php', 'txtHint');
        });
    }

    $("#cashaccounts_id").change(function () {
      //  var pageinfo = JSON.stringify($("#cashaccounts_id").serializeArray());
      //  var data1 = JSON.stringify(JSON.parse('{"pageinfo":' + pageinfo + "}"));
      
    
        showValues('frmcashentries',$("#cashaccounts_id").val(), "loadform", '', 'load.php','');
     
     });
</script>
<?php
require('../' . DIR_WS_INCLUDES . 'pageheader.php');
Common::getlables("311,316,306,1310,317,271,249,318,314,319,21,267,242,307,20,320,264,42,350,1251", "", "",$Conn);
?>

<form action="#"  id='frmcashentries' name='frmcashentries' onReset="document.getElementById('action').value = 'add';">			
    <input name="cashitems_id" type="hidden"  id="cashitems_id" value="">
    <input name="action" type="hidden"  id="action" value="add"> 
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
            <td  colspan="2">
                <table width="100%" border="0" cellspacing="2" cellpadding="3">
                    <tr>
                        <td colspan="2" align="center"><span id="status" style='color:#006600;'></span></td>
                    </tr>

                    <tr>
                        <td valign="bottom"><?php echo Common::$lablearray['317']; ?><br>
                            <input type="us-date" id="txtdate" name="txtdate"><?php echo TEXT_FIELD_REQUIRED; ?>                         

                        </td>
                        <td ><?php echo Common::$lablearray['316']; ?><br><?php echo generateBranchCombo(); ?><?php echo TEXT_FIELD_REQUIRED; ?></td>
                    </tr>
                    <tr>
                        <td  valign="bottom"><?php echo Common::$lablearray['311']; ?><br>
                            <select id='cashaccounts_id' name="cashaccounts_id">
                                <option value=""><?php echo Common::$lablearray['42']; ?></option>
                                <?php
                                foreach ($cashaccounts as $key => $value) {
                                    echo "<option value='" . $key . "' id='" . $key . "'>" . $value . "</option>";
                                }
                                ?>
                            </select><?php echo TEXT_FIELD_REQUIRED; ?> <?php echo Common::$lablearray['249']; ?> <input id="txtBalance" name="txtBalance" type="text" value="" maxlength="10" disabled="disabled">
                            <span id="flag1" style='color:#006600;'></span>
                        </td>
                        <td ></td>
                    </tr>
                    <tr>
                        <td ><?php echo Common::$lablearray['271']; ?><br><input id="txtAmount" name="txtAmount" type="text" value="" ><?php echo TEXT_FIELD_REQUIRED; ?> </td>
                        <td ><?php echo Common::$lablearray['318']; ?><br>
                            <select id='cashitems' name='cashitems'>
                                <option value=""><?php echo Common::$lablearray['42']; ?></option>
                            <?php
                            foreach ($cashitems as $key => $value) {
                                echo "<option value='" . $key . "' id='" . $key . "'>" . $value . ":" . $key . "</option>";
                            }
                            ?>
                            </select><?php echo TEXT_FIELD_REQUIRED; ?> 
                        </td>
                    </tr>
                    <tr>
                        <td valign="top"><?php echo Common::$lablearray['264']; ?><br><textarea   name="txtDescription" cols="50" rows="10" id='txtDescription'></textarea></td>
			<td valign="top"><?php echo Common::$lablearray['1251']; ?><br><?php echo Common::DrawComboFromArray(array(), 'currencies_id', '', 'CURRENCIES', '', 'currencies_id'); ?><?php echo TEXT_FIELD_REQUIRED; ?> <p></p><?php echo Common::$lablearray['1310']; ?><br><?php echo Common::generateReportControls('COSTCENTERS', Common::$connObj, 'costcenters_code'); ?><p></p><?php echo Common::$lablearray['299']; ?><br><input id="txtVoucher" name="txtVoucher" type="text" value="" ><p></p><fieldset><p align='right'><?php echo TEXT_FIELD_REQUIRED; ?></p><input id='chkpay' name="type" type="radio" value="P" onClick="document.getElementById('chkreceive').checked = false;"> <?php echo Common::$lablearray['319']; ?> <br><input id='chkreceive' name="type" type="radio" value="R" onClick="document.getElementById('chkpay').checked = false;"> <?php echo Common::$lablearray['320']; ?></fieldset></td>
		  </tr>
					 
		  <tr height="25"  >
			<td ></td>
			<td ></td>
		</tr>
		 <tr height="25">
			<td align="right"></td>
			<td  align="center">
                <button class="btn" name="reset"  type="reset" id="reset"><?php echo Common::$lablearray['242']; ?></button>  
<button  class="btn" name="reset"  type="button" id="save" onClick="updateForm()" ><?php echo Common::$lablearray['20']; ?></button>     
</td>
		</tr>				 
		</table>				
								
		</table> 


</form>					
		
<script language="JavaScript"  type="text/javascript">
    // showResult('frmid=frmcashentries','txtHint')
   $('input[type=us-date]').w2field('date',  { format: '<?php echo SETTING_DATE_FORMAT?>'})
</script>	  
</BODY>
</HTML>