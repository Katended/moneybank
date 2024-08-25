<?php
 require_once('../includes/application_top.php');
 //require_once("../simple-php-captcha-master/simple-php-captcha.php");
//require_once('../includes/classes/common.php');
// $_SESSION['captcha'] = simple_php_captcha(array(),'TRANSFER');

//$_parent = basename(__FILE__);
//$code_array = unserialize($_SESSION['_CAPTCHA']['config']);//['code'];
 

//session_start();
// here you can perform all the checks you need on the user submited variables
//$_SESSION['security_number']=rand(10000,99999);

    spl_autoload_register(function ($class_name) {
        include '../includes/classes/'.$class_name . '.php';
    });
   require('../'.DIR_WS_INCLUDES . 'pageheader.php'); 

   getlables("20,300,42,1194,522,684,1584,686,687,688,689,690,691,692,693,683,694,755,756,757,758,758,759,758,760,761,111,762,763,764,765,771,1695");
 ?>
 <form style="padding:10px;" id="frmgeneralsettings" name="frmgeneralsettings">            


		<p id="status" style='color:#006600;margin:10px;' align="center">
		  <?php if(STARTFINYEAR==""){?>
				<?php echo $lablearray['755'];?>
		<?php }?> 			
		</p>  		
        <table cellpadding="2" cellspacing="2" border="0" width="100%">            
            
            <tr>
                <td ><?php echo $lablearray['683'];?></td><td ><input type="text" id="NAME_OF_INSTITUTION" name="NAME_OF_INSTITUTION" value='<?php echo NAME_OF_INSTITUTION;?>' maxlength="100" size="70"></td>
            </tr>
			 <tr>
                <td ><?php echo $lablearray['522'];?></td><td ><input type="text" id="TELEPHONE" name="TELEPHONE" value='<?php echo TELEPHONE;?>' maxlength="100" size="70"></td>
            </tr>
            <tr>
                <td nowrap="nowrap"><?php echo $lablearray['684'];?></td><td ><?php echo tep_draw_file_field('SETTING_STUDENT_PHOTO_DIR_PATH',false);?></td>
            </tr>
          
            <tr>
                <td nowrap="nowrap"><?php echo $lablearray['686'];?></td><td >
			
					<?php 
					
					echo  DrawComboFromArray(array(SETTTING_CURRENCY_ID=>SETTTING_CURRENCY_ID),'SETTTING_CURRENCY_ID',SETTTING_CURRENCY_ID,'CURRENCIES','','CURRENCIES');
					
					?>
				<?php echo TEXT_FIELD_REQUIRED;?>			
							
				
				</td>
           
            <tr>
                <td nowrap="nowrap"><?php echo $lablearray['688'];?></td><td >
                <select name="SETTING_DATE_FORMAT" id="SETTING_DATE_FORMAT">
                    <option value=''><?php 				
					echo $lablearray['42'];?></option>
                    <option value='m/d/Y' <?php if(SETTING_DATE_FORMAT=='m/d/Y'){ echo 'SELECTED';}?>>American: MM/DD/YYYY</option>
                    <option value='d/m/Y' <?php if(SETTING_DATE_FORMAT=='d/m/Y'){ echo 'SELECTED';}?>>British/French: DD/MM/YYYY</option>
					 <option value='d/m/Y' <?php if(SETTING_DATE_FORMAT=='d/m/Y'){ echo 'SELECTED';}?>>British/French: d/m/Y</option>
                    <option value='m/d/Y' <?php if(SETTING_DATE_FORMAT=='m/d/Y'){ echo 'SELECTED';}?>>MM/DD/YYYY</option>
                    <option value='d/m/Y' <?php if(SETTING_DATE_FORMAT=='d/m/Y'){ echo 'SELECTED';}?>>DD/MM/YYYY</option>
                    <option value='Y/m/d' <?php if(SETTING_DATE_FORMAT=='Y/m/d'){ echo 'SELECTED';}?>>YYYY/MM/DD</option>
                </select><?php echo TEXT_FIELD_REQUIRED;?>	
            </td>
            <tr>
                <td nowrap="nowrap"><?php echo $lablearray['689'];?></td><td ><?php echo tep_draw_input_field('setting_round_to',SETTTING_ROUND_TO,'',false,'text',true,'3');?> Decimal Places</td>					
            </tr>					
            <tr>
                <td nowrap="nowrap"><?php echo $lablearray['690'];?></td><td >
                <select name="DEFAULT_LANGUAGE" id="DEFAULT_LANGUAGE">
                    <option value=''>Select date format</option>
                    <option value='en' <?php if(DEFAULT_LANGUAGE=='en'){ echo 'SELECTED';}?>>English</option>
                    <option value='fr' <?php if(DEFAULT_LANGUAGE=='fr'){ echo 'SELECTED';}?>>French</option>
                    <option value='sw' <?php if(DEFAULT_LANGUAGE=='sw'){ echo 'SELECTED';}?>>Swahili</option>
                    <option value='ar' <?php if(DEFAULT_LANGUAGE=='ar'){ echo 'SELECTED';}?>>Arabic</option>							
                </select><?php echo TEXT_FIELD_REQUIRED;?>	
                
                </td>					
            </tr>
            
                            
             <tr>
                <td><?php echo $lablearray['756'];?></td><td >
				<input type="us-date" id="STARTFINYEAR" name="STARTFINYEAR" value="<?php echo Common::changeMySQLDateToPageFormat(STARTFINYEAR);?>">
			</td>
            </tr>
			<tr>
                <td><?php echo $lablearray['757'];?></td><td ><?php echo DrawComboFromArray(array(SETTING_PROFIT_LOSS_ACC=>SETTING_PROFIT_LOSS_ACC),'SETTING_PROFIT_LOSS_ACC','','COACOMBO','','COACOMBO');?></td>
            </tr>
			
            <tr>
                <td><?php echo $lablearray['1194'];?></td><td ><?php echo DrawComboFromArray(array(SETTING_INTERBRANCH_ACC=>SETTING_INTERBRANCH_ACC),'SETTING_INTERBRANCH_ACC','','COACOMBO','','COACOMBO');?></td>
            </tr>				
                 
            <tr>
                <td><?php echo $lablearray['759'];?></td><td ><input name="SETTING_POSTING_CLOSED_PERIOD_SL" id="SETTING_POSTING_CLOSED_PERIOD_SL" type="checkbox" value="checked" <?php echo SETTING_POSTING_CLOSED_PERIOD_SL; ?> /></td>
            </tr>
            <tr>
                <td><?php echo $lablearray['760'];?></td><td ><input name="SETTING_POSTING_CLOSED_PERIOD_GL" id="SETTING_POSTING_CLOSED_PERIOD_GL"  type="checkbox" value="checked" <?php echo SETTING_POSTING_CLOSED_PERIOD_GL; ?>/></td>
            </tr>
            
             <tr>
                <td><?php echo $lablearray['1695'];?></td><td ><input name="SETTING_CURRENCY_DENO" id="SETTING_CURRENCY_DENO"  type="checkbox" value="checked" <?php echo SETTING_CURRENCY_DENO; ?>/></td>
            </tr>
            <tr>
                <td><?php echo $lablearray['1584'];?></td><td ><?php echo Common::DrawComboFromArray(array(),'SETTING_PAYMODE','','PAYMODES','','PAYMODES','frmgeneralsettings');?></td>
             </tr>
      </table>
	 <button class="btn" name="btn300"  type="button" style="float:right;margin:4px;" id="btn300" onClick="CloseDialog(vFloatingPane.id);"><?php echo $lablearray['300'];?></button><button class="btn" name="btn20"  type="button" style="float:right;margin:4px;" id="btn20"><?php echo $lablearray['20'];?></button>	    
	</form>
	
</BODY>
<script language="JavaScript"  type="text/javascript">
    $('input[type=us-date]').w2field('date');
	$( document ).ready(function() {
		$( "#btn20" ).click(function() {			
				var pageinfo =  JSON.stringify($("#frmgeneralsettings").serializeArray());	
				var data1 = JSON.stringify(JSON.parse('{"pageinfo":'+pageinfo+"}"));			
				showValues('frmgeneralsettings','','update',data1,'addedit.php','');	  
		});
	});
</script>
</HTML>
