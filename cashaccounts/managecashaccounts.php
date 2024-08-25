<?php
require_once('../includes/application_top.php');
Common::getlables("439,1575", "", "", $Conn);
require('../' . DIR_WS_INCLUDES . 'pageheader.php');
$glaccounts = getAccountNoHeaders();
Common::getlables("442,306,300,307,20,316,1251", "", "", $Conn);
?>
<form id='frmcashaccounts' name='frmcashaccounts'>
    <input name="cashaccounts_id" type="hidden"  id="cashaccounts_id" value="">
    <input name="action" type="hidden"  id="action" value="add">
    <span id="status" style='color:#006600;'></span>
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
            <td align="center" >
                <table width="50%" border="0" cellspacing="0" cellpadding="5">
                   
                     <tr>
                       
                        <td ><?php echo Common::$lablearray['316']; ?><br><?php echo DrawComboFromArray('branch_code', 'branch_code', '', 'operatorbranches', '', '');?><?php echo TEXT_FIELD_REQUIRED; ?></td>
                    </tr>

                    <tr>
                      
                        <td ><?php echo Common::$lablearray['442'] ?><br><?php echo tep_draw_input_field('cashaccounts_name', '', '', false, 'text', $retainvalues, '60'); ?><?php echo TEXT_FIELD_REQUIRED; ?></td>
                    </tr>
                    <tr>
                       
                        <td ><?php echo Common::$lablearray['306'] ?><br><?php echo DrawComboFromArray(array(), 'chartofaccounts_accountcode', '', 'COACOMBO', '', 'COACOMBO'); ?><?php echo TEXT_FIELD_REQUIRED; ?>        
                        </td>
                    </tr>
                      <tr>
                       
                        <td ><?php echo Common::$lablearray['1251'] ?><br><?php echo DrawComboFromArray(array(), 'currencies_id', '', 'CURRENCIES', '', 'CURRENCIES'); ?> <?php echo TEXT_FIELD_REQUIRED; ?>         
                        </td>
                    </tr>
                    <tr >                     
                        <td  align="center" >&nbsp;<button class="btn" name="Go"  type="button" onClick="CloseDialog(vFloatingPane.id);"  id="btnscancel"><?php echo Common::$lablearray['300']; ?></button><button class="btn" id="btnSave" type="button" name="btnSave"><?php echo Common::$lablearray['20']; ?></button></td>
                    </tr>
                            			 
                </table>				
                </td>
                </tr>
              <tr>
                <td id='txtHint' align="center"></td>
             </tr>
    </table> 


</form>							
<script language="JavaScript"  type="text/javascript">
    showValues('frmcashaccounts', 'txtHint', 'search', 'CASHACCOUNTS', 'load.php', '');
    
     function getinfo(frm_id,theid,action,pagedata,urlpage,element){	
            showValues(frm_id,theid,action,'','load.php',element);
	
    }
    
    $(document).ready(function () {
        $("#btnSave").click(function(){

                var pageinfo =  JSON.stringify($("#frmcashaccounts").serializeArray());			
                var data1 = JSON.stringify(JSON.parse('{"pageinfo":'+pageinfo+"}"));    
                showValues('frmcashaccounts', '',$('#action').val(), data1, 'addedit.php','').done(function () {
                    $('#action').val('add');
                    showValues('frmcashaccounts', 'txtHint', 'search', 'CASHACCOUNTS', 'load.php', '');
                });


           });      

     });

</script>	  
</BODY>
</HTML>
