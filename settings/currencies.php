<?php
require_once('../includes/application_top.php');
// $glaccounts = getAccountLevels(0,'coa');
?>
<?php
require('../' . DIR_WS_INCLUDES . 'pageheader.php');
getlables("1251,654,655,656,657,658,659,242,20,21,668,709");
?>
<form action="" method="POST" style="width:100%;height:auto;" id='frmcurrencies' name='frmcurrencies' onReset="document.getElementById('action').value = 'add';">
    <input name="action"  value="<?php if ($_GET['action'] != "") {
    echo $_GET['action'];
} else {
    echo 'add';
} ?>" id="action" type="hidden">
    <input name="currencies_id" id='currencies_id' value="" type="hidden" >

    <table width="100%" border="0" cellspacing="0" cellpadding="2">
        <tr>
            <td  colspan="2">
                <table width="100%" border="0" cellspacing="0" cellpadding="2">

                    <tr>
                        <td colspan="2" align="center">

                            <span id="status" style='color:#006600;'></span>
                            <span id="flag" style="float:right;padding:10px;"></span>
                        </td>
                    </tr>
                    <tr>
                        <td align="right" valign="middle"><?php echo $lablearray['1251']; ?></td>
                        <td><?php echo tep_draw_input_field('currencies_name', $currencies_name, '', false, 'text', $retainvalues, '32'); ?></td>
                    </tr>

                    <tr>
                        <td align="right" valign="middle"><?php echo $lablearray['654']; ?></td>
                        <td><?php echo tep_draw_input_field('currencies_code', $currencies_code, '', false, 'text', $retainvalues, '32'); ?></td>
                    </tr>

                    <tr>
                        <td align="right" valign="middle"><?php echo $lablearray['655']; ?></td>
                        <td><?php echo tep_draw_input_field('currencies_symbolleft', $currencies_symbolleft, '', false, 'text', $retainvalues, '32'); ?></td>
                    </tr>

                    <tr>
                        <td align="right" valign="middle"><?php echo $lablearray['656']; ?></td>
                        <td><?php echo tep_draw_input_field('currencies_symbolright', $currencies_symbolright, '', false, 'text', $retainvalues, '32'); ?></td>
                    </tr>

                    <tr>
                        <td align="right" valign="middle"><?php echo $lablearray['657']; ?></td>
                        <td><?php echo tep_draw_input_field('currencies_decimalpoint', $currencies_decimalpoint, '', false, 'text', $retainvalues, '32'); ?></td>
                    </tr>	

                    <tr>
                        <td align="right" valign="middle"><?php echo $lablearray['668']; ?></td>
                        <td><?php echo tep_draw_input_field('currencies_decimalplaces', $currencies_decimalplaces, '', false, 'text', $retainvalues, '32'); ?></td>
                    </tr>					  

                    <tr>
                        <td align="right"><?php echo $lablearray['709']; ?></td><td><?php echo DrawComboFromArray($glaccounts, 'chartofaccounts_accountcode', '') ?></td><td></td>

                    </tr>
                    <tr>
                        <td align="right" valign="middle" colspan="2"><input name="currencies_isbase" id="currencies_isbase" type="checkbox" value="currencies_isbase" value="" onClick="if (this.checked == true) {
                                    this.value = 'Y'
                                } else {
                                    this.value = 'N'
                                }"> <?php echo $lablearray['658']; ?></td>
                    </tr>
                    <tr>
                       
                        <td  colspan='2' align='right'>&nbsp;<button  type="reset"  id='reset' class="actbutton"><?php echo $lablearray['242']; ?></button><button  type="button"  onClick="updateForm()" class="actbutton"><?php echo $lablearray['20']; ?> </button></td>
                    </tr>				 
                </table>				
        
        <tr>
            <td colspan="2" id='txtHint' align="center"></td>
        </tr>							
    </table> 


</form>									
</TD>                        
</TR>       
</TBODY>
</TABLE>


<script language="JavaScript"  type="text/javascript">
    function getinfo(frm_id,theid,action,pagedata,urlpage,element){
        showValues('frmcurrencies',theid,action,'',urlpage,element);
    }
    showValues('frmcurrencies', 'txtHint', 'search', 'CURRENCIES', 'load.php');

</script>

</BODY>
</HTML>
