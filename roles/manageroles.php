<?php
require_once('../includes/application_top.php');
getlables("572");
$_parent = basename(__FILE__);
?>
<script language="JavaScript"  type="text/javascript">

    var url = '';
    var iface = '';

    url = "../addedit.php";

     function getinfo(frm_id,theid,action,pagedata,urlpage,element){	
        showValues('frmroles',theid,'edit','',urlpage,element);	
    }
    $("#btnSave").click(function(){
        var pageinfo = JSON.stringify($("#frmroles").serializeArray());
        var data1 = JSON.stringify(JSON.parse('{"pageinfo":'+pageinfo+"}"));
    
        showValues('frmroles', '', 'add', data1, 'addedit.php').done(function () {                                
           showValues('frmroles', 'txtHint', 'search','ROLES', 'load.php','');
        });
    });  

</script>
<?php
require('../' . DIR_WS_INCLUDES . 'pageheader.php');
getlables("571,2,242,307,20,21,267,574");
?>
<form  method="post" id='frmroles' name='frmroles' onReset="document.getElementById('action').value = 'add';">

    <input name="roles_id" type="hidden"  id="roles_id" value="">
    <input name="action" type="hidden"  id="action" value="add">

    <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
            <td  colspan="2">
                <table width="100%" border="0" cellspacing="2" cellpadding="0">
                    
                    <tr>
                        <td colspan="2" align="center"><span id="status" style='color:#006600;'></span></td>
                    </tr>
                   
                    <tr>
                        <td align="right"></td>
                        <td><?php echo $lablearray['574']; ?><br><?php echo tep_draw_input_field('roles_name', '', '', false, 'text', '', '50'); ?><?php echo TEXT_FIELD_REQUIRED; ?></td>
                    </tr>

                    <tr height="25">
                        <td align="right"></td>
                        <td  align="right"><button type="reset"  name="btnReset" class="btn" onClick="updateReset()"><?php echo $lablearray['2']; ?></button><button type="button" class="btn" name="btnSave" id="btnSave"><?php echo $lablearray['20']; ?></button></td>
                    </tr>
                </table>
        
        <tr>
            <td colspan="2" id='txtHint' align="center"></td>
        </tr>
    </table>
</form>
<script language="JavaScript"  type="text/javascript">
    showValues('frmroles', 'txtHint', 'search','ROLES', 'load.php','');
    
</script>
</BODY>
</HTML>
