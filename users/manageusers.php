<?php
require_once('../includes/application_top.php');
//require_once("../simple-php-captcha-master/simple-php-captcha.php");
//$_SESSION['captcha'] = simple_php_captcha(array(),'TRANSFER');

spl_autoload_register(function ($class_name) {
    include '../includes/classes/'.$class_name . '.php';
});
$_parent = basename(__FILE__); 
require('../'.DIR_WS_INCLUDES . 'pageheader.php'); 
getlables("887,291,971,1562,900,888,3,4,974,975,846,976,295,978,994,2,20,1566");
?><form action="" method="post"  id='frmmanageusers' name='frmmanageusers'>
    <span id='status' ></span>
    <fieldset> 
        <legend><?php echo $lablearray['971']; ?></legend>

        <input name="action" type="hidden" id="action" value="add">	
        <input name="usercode" type="hidden" id="usercode" >	
        <input name="user_id" type="hidden" id="user_id" >		
        <input name="theid" type="hidden" id="theid" value="">      
        
        
        <table width="100%" border="0" cellspacing="0" cellpadding="0">
            <tr>
                <td  colspan="2">

                    <table width="100%" border="0" cellspacing="2" cellpadding="0">



                        <tr>

                            <td  colspan="2">

                                <table width="100%" border="0" cellpadding="4">


                                    <tr>
                                        <td align="right"><?php echo $lablearray['887']; ?></td>
                                        <td><?php echo tep_draw_input_field('user_firstname', '', 'tabindex="1"', true, 'text', true, '20'); ?></td>
                                    </tr>
                                    <tr>
                                        <td align="right"><?php echo $lablearray['888']; ?></td>
                                        <td><?php echo tep_draw_input_field('user_middlename', '', 'tabindex="2"', false, 'text', true, '20'); ?></td>
                                    </tr>
                                    <tr>
                                        <td align="right"><?php echo $lablearray['900']; ?></td>
                                        <td><?php echo tep_draw_input_field('user_lastname', '', 'tabindex="3"', true, 'text', true, '20'); ?></td>
                                    </tr>
                                    <tr>
                                        <td align="right"><?php echo $lablearray['3']; ?></td>
                                        <td><?php echo tep_draw_input_field('user_username', '', 'tabindex="4"', true, 'text', true, '20'); ?></td>
                                    </tr>
                                    <tr>
                                        <td align="right"><?php echo $lablearray['4']; ?></td>
                                        <td><?php echo tep_draw_input_field('user_password', '', 'tabindex="5"', true, 'password', false, '20'); ?></td>
                                    </tr>
                                    <tr>
                                        <td align="right"><?php echo $lablearray['975']; ?></td>
                                        <td><?php echo tep_draw_input_field('user_password2', '', 'tabindex="6"', true, 'password', false, '20'); ?> </td>
                                    </tr>
                                    <tr>
                                        <td align="right"><?php echo $lablearray['1562']; ?></td>
                                        <td><?php echo tep_draw_input_field('user_accesscode', '', 'tabindex="7"', true, 'text', true, '20'); ?></td>
                                    </tr>
                                    <tr>
                                        <td align="right"><?php echo $lablearray['295']; ?></td>
                                        <td>

                                            <select  id="lang"  name="lang">
                                                
                                                <option value='EN' id="EN" selected >English(EN)</option>
                                                <option value='FR' id="FR" >français(FR)</option>
                                                <option value='JA' id="JA">日本語(JA)</option>
                                                <option value='SP' id="SP" >Español(SP)</option>
                                                <option value='LUG' id="LUG">Luganda(LUG)</option>


                                            </select>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td align="right" valign="middle"><?php echo $lablearray['994']; ?></td>
                                        <td><?php echo DrawComboFromArray('licence_build', 'licence_build', '', 'banks', ""); ?><?php echo TEXT_FIELD_REQUIRED; ?></td>
                                    </tr>
                                    <?php
                                    getlables("846,978,974");
                                    ?>
                                    <tr>
                                        <td align="right"><?php echo $lablearray['846']; ?></td>
                                        <td id="txtBranches">
                                            <select id="branch_code" name="branch_code" multiple="true" >
                                                <option id="" value=""></option>
                                            </select>
                                        </td>
                                    </tr>
                                     <tr>
                                        <td align="right" valign="middle"><?php echo $lablearray['1566']; ?></td>
                                        <td><?php echo DrawComboFromArray('pbranch_code','pbranch_code','','operatorbranches','','');?><?php echo TEXT_FIELD_REQUIRED; ?></td>
                                    </tr>

                                </table>
                                <p align="right">
                                    <?php echo $lablearray['978']; ?>
                                    <input name="pass_expdate" type="us-date"  id="pass_expdate" type="text" size="15" width="32" value=""  readonly/>
                                  
                                    </script><?php echo TEXT_FIELD_REQUIRED; ?>
                                    <input  name="user_isactive" type="checkbox" value="Y" id="user_isactive" checked onClick="if (this.checked) {
                                                this.value = 'Y'
                                            } else {
                                                this.value = 'N'
                                            }" > <?php echo $lablearray['974']; ?> </p>

                                <span style="float:right;margin:15px;"><button type="reset"  name="btnReset" class="btn" onClick="updateReset()"><?php echo $lablearray['2']; ?></button><button type="button" class="btn" name="btnSave" id="btnSave"><?php echo $lablearray['20']; ?></button></span>
                            </td>
                        </tr>
                    </table>

            <tr>
                <td colspan="2" id='txtHint' align="center"></td>
            </tr>
        </table>
    </fieldset>


</form>
<script language="JavaScript"  type="text/javascript">
	
	var url ='';
	var iface = '';
	url = "../addedit.php";
	
	var aBranches =[];
		
	$( document).ready(function(){
	
                 w2utils.date(new Date());
                 $('input[type=us-date]').w2field('date',  { format: '<?php echo SETTING_DATE_FORMAT?>'})
                showValues('frmmanageusers', 'txtHint', 'search','USERS', 'load.php');
                
		$("#licence_build").change(function(){		
		
                          var pageinfo =  JSON.stringify($("#licence_build").serializeArray());
      		
                          var data1 = JSON.stringify(JSON.parse('{"pageinfo":'+pageinfo+"}"));

                          showValues('frmmanageusers','txtBranches','',data1,'load.php','');
              		
		});
                
                $("#btnReset").click(function(){
                    $("#action").val('add');
                });
			
		$("#btnSave").click(function(){
				
				var src = document.getElementById("branch_code");
//				
//				// reset array
				aBranches.length = 0;
				for(var count=0; count < src.options.length; count++) {
	 
					if(src.options[count].selected == true) {	
						 var option = src.options[count];			 
						aBranches.push(option.value);							
						
					}
				}
                               
                               
                                var pageinfo = JSON.stringify($("#frmmanageusers").serializeArray());
                                var data1 = JSON.stringify(JSON.parse('{"pageinfo":' + pageinfo + "}"));
                                var branches = JSON.stringify(aBranches);
                                var data1 = JSON.parse('{"pageinfo":' + pageinfo + "}");
                                var data2 = JSON.parse('{"branchinfo":' + branches + "}");
                                var object = $.extend({}, data1, data2);
                                var pagedata = JSON.stringify(object);                  
                                
                                showValues('frmmanageusers', '', 'add', pagedata, 'addedit.php').done(function () {                                
                                   showValues('frmmanageusers', 'txtHint', 'search','USERS', 'load.php','');
                                });
                          
				
                                
	
		});
	
	
	});

        
        function getinfo(frm_id,theid,action,pagedata,urlpage,element){	
            showValues('frmmanageusers',theid,action,'',urlpage,element);
	
        }


</script>

</BODY>
</HTML>