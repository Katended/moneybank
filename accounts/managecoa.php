<?php
require_once('../includes/application_top.php');
$_parent = basename(__FILE__);
$glaccounts = getAccountLevels('0','coa');
 $lablesarray = getlables("665,1251,438,439,440,441,442,443,444,445,446,447,448,449,450,451,452,453,20,264,108,247,247,666");
?>
<script language="JavaScript"  type="text/javascript">

var url ='';
var iface = '';


 function updateForm(action){
 
        var pageinfo =  JSON.stringify($("#frmcoa").serializeArray());
      		
        var data1 = JSON.stringify(JSON.parse('{"pageinfo":'+pageinfo+"}"));
   
        showValues('frmcoa',document.getElementById('ccurrent_selected_acc').value,action,data1,'addedit.php','').done(     
             function(){
                  //  showValues('frmcoa','coa','', '','addedit.php');
                  // showValues('frmcoa', 'coa', '','', 'addedit.php','');
			
                
            });
showValues('frmcoa','coa','', '','addedit.php');
 }
 
 function removeRow(form) {
    theTableBody.deleteRow(form.deleteIndex.value) 
}

function ChangeColor(tableRow, highLight,elementid)
{
	
	if (highLight)
	{
				

		if(document.getElementById('ccurrent_selected_acc').value!="")
			document.getElementById('icon'+document.getElementById('ccurrent_selected_acc').value).innerHTML ="";
         }
         showValues('frmcoa','','edit','','load.php',elementid);
       // showValues('frmcoa', 'txtHint', 'search','', 'load.php',elementid);
	
        document.getElementById('ccurrent_selected_acc').value = elementid;
       // document.getElementById('icon' + elementid).innerHTML = "<img src='images/EDITICON.PNG' border='0' style='margin-right:0px;'/>";
        document.getElementById('action').value = 'update';
    }

     function mouseIn(tableRow) {
         tableRow.style.backgroundColor = '#dcfac9';
     }

     function mouseOut(tableRow) {
         tableRow.style.backgroundColor = 'white';
     }
   

//    function savedata(){
//
//            if(IsNullEmptyField('chartofaccounts_name',"<?php echo $lablesarray['439'] ?>") && IsNullEmptyField('chartofaccounts_accountcode',"<?php echo $lablesarray['440'] ?>") && IsNullEmptyField('chartofaccounts_parent',"<?php echo $lablesarray['441'] ?>") && IsNullEmptyField('currencies_id',"<?php echo $lablesarray['666'] ?>")){
//                 
//                var data1 =  JSON.stringify($("#frmcoa").serializeArray());	
//                
//                showValues('frmcoa', '' ,'add', data1,'addedit.php').done(function(){			
//                    showValues('frmcoa', 'txtHint','','','addedit.php');	
//	
//                });
//                
//              //  showValues('frmid=frmcoa&ccurrent_selected_acc='+document.getElementById('ccurrent_selected_acc').value + '&chartofaccounts_name=' + document.getElementById('chartofaccounts_name').value + '&chartofaccounts_accountcode=' + document.getElementById('chartofaccounts_accountcode').value + '&chartofaccounts_parent=' + document.getElementById('chartofaccounts_parent').value + '&chartofaccounts_tgroup=' + document.getElementById('chartofaccounts_tgroup').value + '&chartofaccounts_header=' + document.getElementById('chartofaccounts_header').value + '&chartofaccounts_description=' + document.getElementById('chartofaccounts_description').value + '&action=' + document.getElementById('action').value + '&chartofaccounts_oldaccountcode=' + document.getElementById('chartofaccounts_oldaccountcode').value + '&chartofaccounts_revalue=' + document.getElementById('chartofaccounts_revalue').value + '&currencies_id=' + document.getElementById('currencies_id').value);
//               // showValues('frmcoa', 'txtHint')
//            }
//
//    }			
</script><form  method="post" style="width:100%;height:auto;" id='frmcoa' name='frmcoa'>
    <input name="action"  value="add" id="action" type="hidden">
    <input name="ccurrent_selected_acc"  value="" id="ccurrent_selected_acc" type="hidden">
    <input name="chartofaccounts_oldaccountcode"  value="" id="chartofaccounts_oldaccountcode" type="hidden">
    <input name="chartofaccounts_groupcode"  value="" id="chartofaccounts_groupcode" type="hidden">
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
            <td >
                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                        <td  colspan="2"  align="center"><span id="status" style='color:#006600;'></span></td>					
                    </tr>
                    <tr>
                        <td colspan="2">
                            <table width="98%" border="0" cellspacing="0" cellpadding="0" bgcolor="#FFFFFF" >
                                <tr><td >						
                                        <div  style="border: 1px solid #999;overflow:auto;height:300px;text-align:left;font-size:12px; color:#0000FF;">							
                                        <div id="coa">	
                                        </div>
                                        </div>														
                                    </td>
                                </tr>
                            </table>

                        </td>
                    </tr>
                    <tr>
                        <td  valign="bottom">

                            <table cellpadding="2" cellspacing="0" border="0">
                               
                                <tr>
                                    <td><?php echo $lablesarray['442'] ?></td><td><input type="text" size='50' id="chartofaccounts_name" name="chartofaccounts_name">
                                        <?php echo TEXT_FIELD_REQUIRED; ?></td>
                                </tr>
                                <tr>
                                    <td><?php echo $lablesarray['443'] ?></td><td><input type="text" size='50'id="chartofaccounts_accountcode" name="chartofaccounts_accountcode">
                                        <?php echo TEXT_FIELD_REQUIRED; ?></td>
                                </tr>
                                <tr>
                                    <td><?php echo $lablesarray['444'] ?></td><td><?php echo DrawComboFromArray($glaccounts, 'chartofaccounts_parent', '', 'combo') ?><?php echo TEXT_FIELD_REQUIRED; ?></td>
                                </tr>
                                <tr>
                                    <td><?php echo $lablesarray['445'] ?></td>
                                    <td>
                                        <select id="chartofaccounts_tgroup" name="chartofaccounts_tgroup">
                                            <option id="" name=""><?php echo $lablesarray['446'] ?></option>
                                            <option id="1" name="1" value="1"><?php echo $lablesarray['108'] ?></option>
                                            <option id="2" name="2" value="3"><?php echo $lablesarray['447'] ?></option>
                                            <option id="3" name="4" value="4"><?php echo $lablesarray['448'] ?></option>
                                            <option id="3" name="9" value="9"><?php echo $lablesarray['449'] ?></option>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td><?php echo $lablesarray['1251']; ?></td>
                                    <td>
                                      <?php
                                            echo Common::DrawComboFromArray(array(),'currencies_id','','CURRENCIES');  
                                     ?>                                    
                                    </td>
                                </tr>
                                <tr>
                                    <td><?php echo $lablesarray['264'] ?></td><td><input type='text' cols="50" rows="2"  id="chartofaccounts_description" name="chartofaccounts_description"></td>
                                </tr>


                            </table>

                        </td>
                        <td valign="top">				
                            <table cellpadding=0" cellspacing="0" border="0" width="100 ">
                                <tr>
                                    <td nowrap="nowrap" valign="bottom"><input type="checkbox" value="N" id="chartofaccounts_header" name="chartofaccounts_header" style="margin:0px" onClick="if(document.getElementById('chartofaccounts_header').value=='N'){document.getElementById('chartofaccounts_header').value='Y'}else{document.getElementById('chartofaccounts_header').value='N'}"/></td><td nowrap="nowrap"><?php echo $lablesarray['450'] ?></td>
                                </tr>

                                <tr>
                                    <td nowrap="nowrap" valign="bottom"><input type="checkbox" value="N" id="chartofaccounts_revalue" name="chartofaccounts_revalue" style="margin:0px" onClick="if(document.getElementById('chartofaccounts_revalue').value=='N'){document.getElementById('chartofaccounts_revalue').value='Y'}else{document.getElementById('chartofaccounts_revalue').value='N'}"/></td><td nowrap="nowrap"><?php echo $lablesarray['665'] ?></td>
                                </tr>

                            </table>		
                        </td>
                    </tr>						  
                    	  			 			
                   
                    <tr>

                        <td  colspan="2" align="center">
                            <button class="btn" name="removeRowBtn" value="" onClick="updateForm('delete')" type="button"><?php echo $lablesarray['452'] ?></button>
                                <button class="btn" type="reset" value="" id='reset' class="actbutton" onClick="docucument.getElementById('action').value = 'add'"><?php echo $lablesarray['453'] ?></button>
                            <button  class="btn" type="button" value=""  onClick="updateForm('add');" class="actbutton"><?php echo $lablesarray['20'] ?></button></td>
                    </tr>				 
                </table>


            </td>
        </tr>				


    </table> 

</form>					

<script language="JavaScript"  type="text/javascript">
    showValues('frmcoa','coa','', '','addedit.php');			
</script>
	  
</BODY>
</HTML>
   