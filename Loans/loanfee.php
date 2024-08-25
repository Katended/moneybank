<?php
require_once('../includes/application_top.php');
//require_once("../simple-php-captcha-master/simple-php-captcha.php");
//$_SESSION['captcha'] = simple_php_captcha(array(),'TRANSFER');

spl_autoload_register(function ($class_name) {
    include '../includes/classes/'.$class_name . '.php';
});

$_parent = basename(__FILE__);

getlables("21,1097,300,1161,20");
?>
<script type="text/javascript">    
 function newPage(){
          
    showValues('frmfees','toppanel','search','LOANFEES','load.php').done(function(){
         w2utils.date(new Date());		
        $('input[type=us-date]').w2field('date',  { format: '<?php echo SETTING_DATE_FORMAT?>'})
    });  
}

$( "#radioadd, #radioedit" ).click(function() {
        newPage();
});
function getinfo(frm_id,theid,action,pagedata,urlpage,element){	
    
    alert(theid);
}

</script>
<?php  require('../'.DIR_WS_INCLUDES . 'pageheader.php'); 
getlables("20,1096,21,24,300,1161,271,1024,299,317,1097,9,654,316,317");
?>
<form   id="frmfees" name="frmfees" action="#">
   
    <fieldset>
          <input id="theid" name="theid" type="hidden" value="">
	<table cellpadding="2" width="100%">
        <tr>
            <td colspan="3" align="center">
                 <input id="action" name="action" type="hidden" value="add">
                <input id="theid" name="theid" type="hidden" value="">
                <span id="Name"></span>
                <span class="radiooptionsitem" >
                    <input  type="radio" id="radioadd" name="radiosaction" value="Add" >
                    <label for="radioadd">Search</label>
                    <input type="radio" id="radioedit" name="radiosaction" value="Edit"  >
                    <label for="radioedit">Edit</label>				   
		
                </span>
                 <span class="radiooptions">

                    <input type="radio" id="INDSAVACC" name="radiosclient" value="IND" checked>
                    <label for="INDSAVACC">Individuals</label>
                    <input type="radio" id="GRPSAVACC" name="radiosclient" value="GRP" >
                    <label for="GRPSAVACC">Groups</label>
                    <input type="radio" id="BUSSAVACC" name="radiosclient" value="BUSS" >
                    <label for="BUSSAVACC">Business</label> 	
                    
                </span>
          </td>
	</tr> 
         <tr>
		 <td colspan="3" align="center"> <?php echo $lablearray['316'];?><br></td>
		
	  </tr>
        
        
	<tr>
		<td><?php echo $lablearray['1097'];?><br><input type="text" id="txtlnr" name="txtlnr" value="" readonly=""><?php echo TEXT_FIELD_REQUIRED;?></td>
		
                <td ><?php echo $lablearray['654'];?><br><input type="text" id="client_idno" name="client_idno" value=""  readonly=""><?php echo TEXT_FIELD_REQUIRED;?></td>
	 	<td><?php echo $lablearray['1096'];?><br><?php echo DrawComboFromArray(array(),'SAVPROD','','SAVPROD','','SAVPROD');?></td>
                
	  </tr>
	<tr>
		<td><?php echo $lablearray['317'];?><br><input type="us-date" class='date' id="txtDate" name="txtDate" value=""><?php echo TEXT_FIELD_REQUIRED;?></td>
		<td><?php echo $lablearray['24'];?><br><?php echo DrawComboFromArray(array(),'PAYMODES','','PAYMODES','','PAYMODES');?><span id='modes'></span><?php echo TEXT_FIELD_REQUIRED;?> </td>
		<td ><?php echo $lablearray['299'];?><br><input type="text" id="txtvoucher" name="txtvoucher" value=""></td>
	  </tr>
	<tr>
        </table>
          <p align="center"><?php echo $lablearray['271'];?><br> <input type="numeric" id="txtAmount" name="txtAmount" value="0"><?php echo TEXT_FIELD_REQUIRED;?></p> 
        </fieldset>
       <table cellpadding="2" width="100%">
        <td colspan="3" align="center" >  
            
           <fieldset id="toppanel" align="center" style="width:99%"></fieldset>   
        </td>
	</tr>
	  <tr>
            <td colspan="2"></td>
            <td  align='right' valign="top" ><button class="btn" name="Go"  type="button" onClick="CloseDialog(vFloatingPane.id);"  id="btnscancel"><?php echo $lablearray['300'];?></button><button class="btn" name="btnBack"  type="button"   id="btnBack"><?php echo $lablearray['1161'];?></button><button class="btn" name="btnSave"  type="button"   id="btnSave"><?php echo $lablearray['20'];?></button></td>
	  </tr>
        </table>
       </fieldset>  
</form>	
<script type="text/javascript">

$( document ).ready(function() {
    
    $('input[type=us-date]').w2field('date');

    // payment mode
    $( "#PAYMODES" ).change(function() {
        showValues('frmfees','modes','search','PAYMODES','load.php',$('#PAYMODES').val());
    });
   
    $( "#btnSave" ).click(function() {	
        
        
        $(this).prop('disabled',true); 
        
        var pageinfo =  JSON.stringify($("#frmfees").serializeArray());			
        var data1 = JSON.stringify(JSON.parse('{"pageinfo":'+pageinfo+"}"));

        showValues('frmfees','','add',data1,'addedit.php',$('#theid').val()).done(function(){

           
               document.getElementById("frmfees").reset();

            });	
            
            $(this).prop('disabled',false); 
    });	

});	 
</script>
</BODY>
</HTML>