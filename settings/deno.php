<?php
require_once('../includes/application_top.php');
require('../' . DIR_WS_INCLUDES . 'pageheader.php');
getlables("1251,242,20,1693");
?>
<form action="" method="POST" style="width:100%;height:auto;" id='frmcurrencydeno' name='frmcurrencydeno' onReset="document.getElementById('action').value = 'add';">
    <input name="action"  value="<?php
    if ($_GET['action'] != "") {
        echo $_GET['action'];
    } else {
        echo 'add';
    }
    ?>" id="action" type="hidden">
    <input name="currencydeno_id" id='currencydeno_id' value="" type="hidden" >

    <table width="100%" border="0" cellspacing="0" cellpadding="2">
        <tr>
            <td  colspan="2">
                <table width="100%" border="0" cellspacing="0" cellpadding="2">

                    <tr>
                        <td align="right" valign="middle"><?php echo $lablearray['1693']; ?></td>
                        <td><?php echo DrawComboFromArray(array(), 'CURRENCIES_ID', '', 'CURRENCIES', '', 'CURRENCIES'); ?></td>
                    </tr>

                    <tr>
                        <td align="right" valign="middle"><?php echo $lablearray['1693']; ?></td>
                        <td><?php echo tep_draw_input_field('currency_deno', '', '', false, 'text', $retainvalues, '32'); ?></td>
                    </tr>

                    <tr>

                        <td  colspan='2' align='right'>&nbsp;<button  type="reset"  id='reset' class="btn" ><?php echo $lablearray['242']; ?></button><button  type="button" name='btnSave' id='btnSave' class="btn" ><?php echo $lablearray['20']; ?> </button></td>
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

    $("#btnSave").click(function () {

        var pageinfo = JSON.stringify($("#frmcurrencydeno").serializeArray());
        var data1 = JSON.stringify(JSON.parse('{"pageinfo":' + pageinfo + "}"));

        showValues('frmcurrencydeno', '', $('#action').val(), data1, 'addedit.php', $('#theid').val()).done(function(){            
            $( "#CURRENCIES_ID" ).trigger( "change");
            
           // $( "#frmcurrencydeno" ).reset();
        });

    });

    $("#CURRENCIES_ID").change(function () {
        showValues('frmcurrencydeno', 'txtHint', 'search', 'CURRENCYDENO', 'load.php', $('#CURRENCIES_ID').val());
    });

    function getinfo(frm_id, theid, action, pagedata, urlpage, element) {
        showValues('frmcurrencydeno', theid, action, '', urlpage, element);
    }


</script>

</BODY>
</HTML>
