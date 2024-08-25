<?php
require_once('../includes/application_top.php');
//require_once("../simple-php-captcha-master/simple-php-captcha.php");
//$_SESSION['captcha'] = simple_php_captcha(array(),'TRANSFER');

spl_autoload_register(function ($class_name) {
    include '../includes/classes/'.$class_name . '.php';
});
$_parent = basename(__FILE__);
?>
<script type="text/javascript">

    var searchtext  ='';
     var act  ='';
    // $( "#radioadd, #radioedit" ).click(function() {
//
//
//       // $("#toppanel").html(htmlString);		
//
//
//        if(document.getElementById('radioadd').checked){
//           act ='ADD';
//           $('#action').val('add');
//            $("#PAYMODES, #txtvoucher, #txtamount").prop( "disabled", false );
//           searchtext = $('input[name=radiosclient]:checked').val();		
//        }
//
//        if(document.getElementById('radioedit').checked){
//           act ='EDIT';
//            $('#action').val('update');
//
//            $("#PAYMODES, #txtvoucher, #txtamount").prop( "disabled", true );
//           searchtext = $('input[name=radiosclient]:checked').val()+'SAVACC';		
//        }          
//
//          showValues('frmsavaccounts','gridata','search',searchtext,'load.php?act='+act);
//
//    });
//
//
//    function getinfo(frm_id,theid,action,pagedata,urlpage,element){		
//
//        $( "#action" ).val(action);
//
//            if(action==='add'){  
//                action ='loadform';
//                urlpage='load.php';
//            }
//            $( "#gridata" ).empty();
//            
//           // showValues(frm,theid,action,pageparams,urlpage,keyparam)
//            showValues('frmsavaccounts',theid,action,pagedata,urlpage,element).done(
//
//            function(){		
//                $(function(){populateForm('frmsavaccounts',jsonObj['data']);});			
//            });
//
//    }

       
               
</script>
<?php 
require('../'.DIR_WS_INCLUDES . 'pageheader.php'); 
Common::getlables("1568,300,21,317,1197,1096,1619,1246,316,960,1294,1612,1603,1613,1611,1614", "", "", $Conn); 
?><form   id="frmsms" name="frmsms" action="#">
    <input id="action" name="action" type="hidden" value="add">
    <input id="theid" name="theid" type="hidden" value="">     

    <fieldset>
       <p align="center"> <?php echo Common::$lablearray['1603'];?><br>
        <?php echo Common::generateReportControls('MODEMS', $Conn);?><?php echo TEXT_FIELD_REQUIRED;?></p>
	<table cellpadding="2" width="100%">
        <tr>
            <td colspan="3" align="center">
               <fieldset>                
                   <table cellpadding="2" width="100%">
                    <tr><td valign='top'><?php echo Common::$lablearray['317'];?><br><input type="us-date"  id="txtDateCreated" name="txtDateCreated"  intermediateChanges=true></td>
                     <td valign='top'><?php echo Common::$lablearray['1613'];?><br><input id="txtNumber" name="txtNumber" value=""><?php echo TEXT_FIELD_REQUIRED;?></td>
                     <td valign='top'><?php echo Common::$lablearray['1611'];?><br><Textarea  id="txtMessage" name="txtMessage" value="" ></textarea></td><td valign='top'><button class="btn" name="btnSend"  type="button"   id="btnSend"><?php echo Common::$lablearray['1612'];?></button></td></tr>                        
                   </table>
                   
               </fieldset>
                
          </td>
	</tr>    
       <tr>
        <td colspan="3" align="center"> 
        <fieldset>
        <table cellpadding="2" width="100%">
        <tr>
             <td><?php echo Common::$lablearray['317'];?><br><input type="us-date"  id="txtDate" name="txtDate"  intermediateChanges=true><?php echo TEXT_FIELD_REQUIRED;?></td>
             <td><?php echo Common::$lablearray['1096'];?><br><?php echo DrawComboFromArray(array(),'product_prodid','','LOANPROD','','LOANPROD');?><?php echo TEXT_FIELD_REQUIRED;?></td>
             <td><?php echo DrawComboFromArray('branch_code','branch_code','','operatorbranches','','');?><?php echo TEXT_FIELD_REQUIRED;?></td>
         <tr> 
          <td>            
        <?php echo Common::$lablearray['1246'];?><br>
        <?php echo Common::generateReportControls('AREACODES', $Conn);?></td>
         <td ><?php echo Common::$lablearray['1294'];?><br><input id="txtnDays" name="txtnDays" value="0"></td>
         <td><button class="btn" name="btnFind"  type="button"   id="btnFind"><?php echo Common::$lablearray['1614'];?></button></td>
       </tr>
       </table>
        </fieldset>
	 </td>
	</tr>
	
        <tr>
        <td colspan="3" align="center" id="gridata">    
           
        </td>
	</tr>
        <tr>
        <td colspan="3" align="center">    
           Q: Queued 
           S: Sent 
           P: Pending
        </td>
	</tr>
           <tr>
            <td><?php echo Common::$lablearray['1619'];?><input type="text"  id="txtNumSMS" name="txtNumSMS" value="0"></td>
            <td  align='right' valign="top" colspan="2"><button class="btn" name="Go"  type="button" onClick="CloseDialog(vFloatingPane.id);"  id="btnscancel"><?php echo Common::$lablearray['300'];?></button><button class="btn" name="btnSendAll"  type="button"   id="btnSendAll"><?php echo Common::$lablearray['960'];?></button></td>
	  </tr>
        </table>
       </fieldset>  
</form>	
<script type="text/javascript">
    
     showValues('frmsms', 'gridata', 'search','SMS', 'load.php');

    $('input[type=us-date]').w2field('date',  { format: '<?php echo SETTING_DATE_FORMAT?>'})

  
    $( "#btnFind" ).click(function() {	
        
    
        var pageinfo =  JSON.stringify($("#frmsms").serializeArray());	
        
        var data1 = JSON.stringify(JSON.parse('{"pageinfo":'+pageinfo+"}"));
        
        // showValues('frmsms', 'gridata', 'search','SMS', 'load.php');
            $("#frmsms").append("<div>Please wait. This may take some time..</div>");
            showValues('frmsms','','',data1,'load.php','').done(function(){
                showValues('frmsms', 'gridata', 'search','SMS', 'load.php'); 
            });	
            
           
            
          //  $(this).prop('disabled',false); 
    });	
    
     $( "#btnSend" ).click(function() {			
        var pageinfo =  JSON.stringify($("#frmsms").serializeArray());	
       
        var data1 = JSON.stringify(JSON.parse('{"pageinfo":'+pageinfo+"}"));			
       
        showValues('frmsms','',$("#action").val(),data1,'addedit.php','').done(function(){
            showValues('frmsms', 'gridata', 'search','SMS', 'load.php');
         });	
    });
    
     $( "#btnSendAll" ).click(function() {			
         var pageinfo =  JSON.stringify($("#modems, #txtNumSMS").serializeArray());	
       
         var data1 = JSON.stringify(JSON.parse('{"pageinfo":'+pageinfo+"}"));			
       
        showValues('frmsms','','sendall',data1,'addedit.php','').done(function(){
            showValues('frmsms', 'gridata', 'search','SMS', 'load.php');
         });	
    });
    

//     $( "#btnSend" ).click(function() {	
//        
//        
//        $(this).prop('disabled',true); 
//        
//        var pageinfo =  JSON.stringify($("#frmsms").serializeArray());	
//        
//        var data1 = JSON.stringify(JSON.parse('{"pageinfo":'+pageinfo+"}"));
//  
//        showValues('frmsms','gridata','add',data1,'addedit.php','');
//            
//       $(this).prop('disabled',false); 
//    });	
    
    
     $('input[type="checkbox"]').click(function(){
                   
        if ($(this).attr('checked')){
          alert();
               $('.chkgrd').attr('checked','checked');                           
        }   else {

               $('.chkgrd').removeAttr('checked');

        }
    });
    
    
  function getinfo(frm_id,theid,action,pagedata,urlpage,element){	
      showValues('frmsms',theid,'edit','',urlpage,element);	
  }
  
</script>
</BODY>
</HTML>