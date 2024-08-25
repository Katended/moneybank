<?php
require_once('../includes/application_top.php');
// require_once('../includes/classes/common.php');
?>
 <?php getlables("1633,20,1634,2"); ?> 
<?php require('../'.DIR_WS_INCLUDES . 'pageheader.php'); ?>
 <form   id="frmproducts" name="frmProducts">
      <p id='messages' align="center" style="color:#FF0000;margin:0px;"></p>
     <input id="action" name="action" type="hidden" value="add">
     <input id="theid" name="theid" type="hidden" value="add">
    <table  border="0" cellpadding="0" width="100%">

        <tr>
            <td colspan="2" align="center"><?php echo $lablearray['1634']; ?><br><input  type="text" id="txtproduct" name="txtproduct" value=''><?php echo TEXT_FIELD_REQUIRED;?></td>						

        </tr>      

         <tr>
            <td colspan="2" align="center"><?php echo $lablearray['1633']; ?><br><input  type="text" id="txtproductcode" name="txtproductcode" value=''><?php echo TEXT_FIELD_REQUIRED;?></td>						
        </tr>
    </table>

       
    <div id="txtHint" class="txtHint">			
   </div>
    <?php getlables("300,20"); ?>        
    <p align="right"><button class="btn" name="Go"  type="button" onClick="CloseDialog(vFloatingPane.id);"  id="btnscancel"><?php echo $lablearray['300']; ?></button><button class="btn" name="btnReset"  type="reset"   id="btnReset" OnClick="$('#action').val('add')"><?php echo $lablearray['2']; ?></button><button class="btn" name="btnSave"  type="button"   id="btnSave"><?php echo $lablearray['20']; ?></button></p>
 
    </form>
    <script type="text/javascript">

        $(document).ready(function () {

             $("#btnSave").click(function () {

                var pageinfo = JSON.stringify($("#frmproducts").serializeArray());
                //var data1 = JSON.parse('{"pageinfo":'+pageinfo+"}");	
                var data1 = JSON.stringify(JSON.parse('{"pageinfo":' + pageinfo + "}"));
                //frm,theid,action,pageparams,urlpage,keyparam
                showValues('frmproducts','', 'add', data1, 'addedit.php').done(     
                 function(){
                     
                    showValues('frmproducts', 'txtHint', 'search','PRODUCTS', 'load.php','');  
                });
             

            });
        //  showValues('frmloanproductsettings3', 'glaccounts', 'search', 'LOANPRODGLACC', 'load.php', $('#product_prodid').val());  
           showValues('frmproducts', 'txtHint', 'search','PRODUCTS', 'load.php','');  
        });
        
        
        function getinfo(frm_id,theid,action,pagedata,urlpage,element){		
	
           
                var pageinfo =  JSON.stringify($("#frmproducts").serializeArray());

                var data1 = JSON.stringify(JSON.parse('{"pageinfo":'+pageinfo+"}"));

                showValues('frmproducts',theid,action,data1,urlpage,element).done(     
                 function(){
                     
                    showValues('frmproducts', 'txtHint', 'search','PRODUCTS', 'load.php','');  
                });

         }
    </script>
</BODY>
</HTML>