<?php
require_once('../includes/application_top.php');
 $glaccounts = getAccountLevels();
 $query_result = tep_db_query("SELECT bankaccounts_accno,bb.chartofaccounts_accountcode,bb.bankbranches_id,bankbranches_name,(SELECT banks_name FROM ".TABLE_BANKS." WHERE banks_id=bb.banks_id) as banks_name FROM ". TABLE_BANKACCOUNTS." as bc,".TABLE_BANKBRANCHES." as bb WHERE bc.bankbranches_id=bb.bankbranches_id"); 
?>
<?php 
require('../'.DIR_WS_INCLUDES . 'pageheader.php'); 
Common::getlables("1532,429,260,38,39,260,20,427,2,431,1533,1534", "", "",$Conn);?>
<fieldset>
<form action="" method="post" style="width:100%;height:auto;" id='frmcloseperiod' name='frmcloseperiod'>
	<span id='status'></span>
	<table  border="0" cellspacing="0" cellpadding="10" width="100%" >
		  <tr>
			<td>
						
					</td>
						<td align="right">
					
						
						<table width="100%" border="0" cellpadding="0">
						 <tr>
                                                     <td>
                                                         <?php echo Common::$lablearray['260'];?><br>
                                                        <select name="selectperiod" id="selectperiod">
							<option id="" value="">-----</option>
							<option id="open" value="D"><?php echo Common::$lablearray['427'];?></option>
							<option id="close" value="Y"><?php echo Common::$lablearray['1532'];?></option>						
						</select>
                                                     </td>
							<td>&nbsp;<?php echo Common::$lablearray['431'];?><br>	
                                                <select name="selectaction" id="selectaction">
							<option id="" value="">-----</option>
							<option id="open" value="O"><?php echo Common::$lablearray['429'];?></option>
							<option id="close" value="C"><?php echo Common::$lablearray['260'];?></option>						
						</select></td>
						  </tr>
						  <tr>
							<td align="left"><?php echo Common::$lablearray['38'];?>&nbsp;<br><input name="txtFrom" id="txtFrom" type="us-date" size="15" width="32" value="<?php echo Common::changeMySQLDateToPageFormat(STARTFINYEAR,SETTING_DATE_FORMAT);?>"/>
							
							</td>
							<td align="left"><?php echo Common::$lablearray['39'];?>&nbsp;<br><input name="txtTo"   id="txtTo" type="us-date" size="15" width="32" value=""/>
							</td>
						  </tr>
						  						  
						   <tr>
							<td align="right"></td>
							<td >
							
							
							</td>
						  </tr>
						</table>
                                                 <button class="btn" name="reset"  type="reset" id="btnreset"> <?php echo Common::$lablearray['2'];?></button> <button class="btn" name="save"  type="reset" id="btnSave"><?php echo Common::$lablearray['20'];?></button>   
							
							</td>
					  </tr>
					  
					</table>				


			</form>					
		</fieldset>	
			
	
</BODY>
<script language="JavaScript"  type="text/javascript"> 
$('input[type=us-date]').w2field('date',  { format: '<?php echo SETTING_DATE_FORMAT?>'})
    
//    $( "#selectaction" ).change(function() {
//        
//        if($(this).val()=='O'){           
//            $('#txtFrom').prop('disabled',false);
//        }else{
//            $('#txtFrom').prop('disabled',true);
//        }
//        
//        
//    });
     $( "#btnSave" ).click(function() {
       
       if(document.getElementById('selectperiod').value=="" || document.getElementById('selectaction').value==""){
           displaymessage("frmcloseperiod","<?php echo Common::$lablearray['1533']; ?>","WAR.") 
       }
       
       if(document.getElementById('txtFrom').value=="" || document.getElementById('txtTo').value==""){
           displaymessage("frmcloseperiod","<?php echo Common::$lablearray['1534']; ?>","WAR.") 
       }
        
        $(this).prop('disabled',true); // didable save button
        var pageinfo =  JSON.stringify($("#frmcloseperiod").serializeArray());			
        var data1 = JSON.stringify(JSON.parse('{"pageinfo":'+pageinfo+"}"));
        
        $(this).prop('disabled',true);
        
        showValues('frmcloseperiod','','add',data1,'addedit.php','').done(function(){
            
             $("#btnSave").prop('disabled',false);
        });			
              
        // document.getElementById("#frmSave").reset();
         $(this).prop('disabled',false);   
    });
</script>
</HTML>
