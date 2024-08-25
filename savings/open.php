<?php
require_once('../includes/application_top.php');
//require_once("../simple-php-captcha-master/simple-php-captcha.php");
//$_SESSION['captcha'] = simple_php_captcha(array(),'TRANSFER');

spl_autoload_register(function ($class_name) {
    include '../includes/classes/'.$class_name . '.php';
});
$_parent = basename(__FILE__);
//getlables("21,1097,300,1161,20");
?>
<script type="text/javascript">

    var searchtext  ='';
     var act  ='';
    $( "#radioedit" ).click(function() {


  
     //   if(document.getElementById('radioadd').checked){
         //  act ='ADD';
         //  $('#action').val('add');
       //     $("#PAYMODES, #txtvoucher, #txtamount").prop( "disabled", false );
       //   searchtext = $('input[name=radiosclient]:checked').val();		
        //}

       
           act ='EDIT';
            $('#action').val('update');

            $("#PAYMODES, #txtvoucher, #txtamount").prop( "disabled", true );
           searchtext = $('input[name=radiosclient]:checked').val()+'SAVACC';		
       // }          

          showValues('frmsavaccounts','gridata','search',searchtext,'load.php?act='+act);

    });

     $( "#radioadd" ).click(function() {  
       act ='ADD';
       $('#action').val('add');
       $("#PAYMODES, #txtvoucher, #txtamount").prop( "disabled", false );
       searchtext = $('input[name=radiosclient]:checked').val();		
       showValues('frmsavaccounts','gridata','search',searchtext,'load.php?act='+act);

    });


    function getinfo(frm_id,theid,action,pagedata,urlpage,element){		

        $( "#action" ).val(action);

            if(action==='add'){  
                action ='loadform';
                urlpage='load.php';
            }
            $( "#gridata" ).empty();
            
           // showValues(frm,theid,action,pageparams,urlpage,keyparam)
            showValues('frmsavaccounts',theid,action,pagedata,urlpage,element).done(

            function(){		
                $(function(){populateForm('frmsavaccounts',jsonObj['data']);});			
            });

    }
</script>
<?php 
require('../'.DIR_WS_INCLUDES . 'pageheader.php'); 
getlables("20,1515,1403,271,21,1516,24,300,1161,1024,373,299,317,1197,1096,373,9,654,316,317,1443,1723,1724");
?>
<form  id="frmsavaccounts" name="frmsavaccounts" action="#">
   
    <fieldset style='width:98%'>
        
	<table cellpadding="2" width="100%">
        <tr>
            <td colspan="3" align="center">
                 <input id="action" name="action" type="hidden" value="add">
                <input id="theid" name="theid" type="hidden" value="">
                <span id="Name" class='metroblock'></span>
                <span class="radiooptionsitem" >               
                    <a class='icons button-acount' id="radioedit" data-balloon="<?php echo $lablearray['1723']; ?>" data-balloon-pos="down">&nbsp;</a>
                    <a class='icons button-clients' id="radioadd" title="<?php echo $lablearray['1443']; ?>" data-balloon="<?php echo $lablearray['1724']; ?>" data-balloon-pos="left">&nbsp;</a>		   
		
                </span>
                            
                <span class="metroblock commentsblock left" > <span class="indicator" id='InfoBox'> </span></span>
                <?php  echo Common::clientOptions("S");?>
          </td>
	</tr>    
       
	<tr>
		<td><?php echo $lablearray['1197'];?><br><input type="text" id="txtsavaccount" name="txtsavaccount" value="" readonly=""></td>
		
                <td ><?php echo $lablearray['654'];?><br><input type="text" id="client_idno" name="client_idno" value=""  readonly=""></td>
	 	<td id='modes'></td>
	  </tr>
	<tr>
		<td><?php echo $lablearray['317'];?><br><input type="us-date" class='date' id="txtOpenDate" name="txtOpenDate" value=""><?php echo TEXT_FIELD_REQUIRED;?></td>
		<td><?php echo $lablearray['1096'];?><br><?php echo DrawComboFromArray(array(),'product_prodid','','SAVPROD','','SAVPROD');?><?php echo TEXT_FIELD_REQUIRED;?></td>
		<td ><?php echo $lablearray['24'];?><br><?php echo Common::DrawComboFromArray(array(),'PAYMODES','','PAYMODES','','PAYMODES');?></td>
	  </tr>
	 <tr>
		<td><?php echo $lablearray['316'];?><br><?php echo DrawComboFromArray('branch_code','branch_code','PP','operatorbranches','','');?><?php echo TEXT_FIELD_REQUIRED;?></td>
		<td><?php echo $lablearray['299'];?><br><input type="text" id="txtvoucher" name="txtvoucher" value=""></td>
		<td ><?php echo $lablearray['373'];?><br><input type="numeric" id="txtamount" name="txtamount" value='0'></td>
	  </tr>
	  
	  <tr>
            <td colspan="3"><fieldset style='padding:5px;'><legend><?php echo $lablearray['1516']; ?></legend><?php echo $lablearray['271'];?> <input type="numeric" id="txtrepaysavtamount" name="txtrepaysavtamount" value='0'> <?php echo $lablearray['1515'];?> <?php echo DrawComboFromArray(array(),'CMBFREQUENCY','',"FREQUENCY","","","frmsavaccounts");?> <?php echo $lablearray['1403'];?> <?php echo Common::DrawComboFromArray(array(), 'LOANPROD', 'LOANPROD','LOANPROD',"", "","");?></fieldset></td>           
	  </tr>
           <tr>
          
             
    
           <tr>
            <td></td>
            <td  align='right' valign="top" colspan="2"><button class="btn" name="Go"  type="button"  id="btnscancel"><?php echo $lablearray['300'];?></button><button class="btn" name="btnBack"  type="button"   id="btnBack"><?php echo $lablearray['1161'];?></button><button class="btn" name="btnSave"  type="button"   id="btnSave"><?php echo $lablearray['20'];?></button></td>
	  </tr>
        </table>
       </fieldset>  
    <div id="test" style="float:right">

    </div>
    <div id="gridata"></div> 
</form>	
<script type="text/javascript">

$( document ).ready(function() {
    
    $('input[type=us-date]').w2field('date',  { format: '<?php echo SETTING_DATE_FORMAT?>'});

    $( "#PAYMODES" ).trigger( "change" );
    // payment mode
    $( "#PAYMODES" ).change(function() {
        showValues('frmsavaccounts','modes','search','PAYMODES','load.php',$('#PAYMODES').val());
    });
   
    $( "#btnSave" ).click(function() {	
        
        
        $(this).prop('disabled',true); 
        
        var pageinfo =  JSON.stringify($("#frmsavaccounts").serializeArray());			
        var data1 = JSON.stringify(JSON.parse('{"pageinfo":'+pageinfo+"}"));

        showValues('frmsavaccounts','',$('#action').val(),data1,'addedit.php',$('#theid').val()).done(function(){

            

            //function(){		
                //$('#toppanel').hide('slide', {direction: 'left'}, 1000);
              // document.getElementById("frmsavaccounts").reset();

            });	
            
            $(this).prop('disabled',false); 
    });	

});	 
</script>
</BODY>
</HTML>