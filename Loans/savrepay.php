<?php
require_once('../includes/application_top.php');
require_once('../includes/classes/common.php');
?>

<?php
getlables("21,1097,300,1161,20,186");
?>
<script type="text/javascript">

    P_LANG = "<?php echo P_LANG;?>";
    var searchtext  ='';
     var act  ='';
    $( "#radioadd, #radioedit" ).click(function() {


       // $("#toppanel").html(htmlString);		

 
//        if(document.getElementById('radioadd').checked){
//           act ='ADD';
//           $('#action').val('add');
//            $("#PAYMODES, #txtvoucher, #txtamount").prop( "disabled", false );
//           searchtext = $('input[name=radiosclient]:checked').val()+'REPAYLOANS';		
//        }
//
//        if(document.getElementById('radioedit').checked){
//           act ='EDIT';
//            $('#action').val('update');
//
//            $("#PAYMODES, #txtvoucher, #txtamount").prop( "disabled", true );
//           searchtext = $('input[name=radiosclient]:checked').val()+'REPAYLOANS';		
//        }          
        var tddate=$.trim($("#txtpayDate").val());

        if(tddate.length==0)
        {
            displaymessage('frmsavrepay',"<?php echo $lablearray['186']?>",'WARN');    
            return; 
        }

           
          showValues('frmsavrepay','gridata','search','COLLECTINTEREST','load.php?act='+act+'&product_prodid='+$( "#sproduct_prodid" ).val()+'&asatdate='+$( "#txtpayDate" ).val()+'&branch_code='+$( "#branch_code" ).val()+'&client_regstatus='+$( "#client_regstatus" ).val());
          $(this).prop('disabled',false); 
    });


    function getinfo(frm_id,theid,action,pagedata,urlpage,element){		

        $( "#action" ).val(action);

            if(action==='add'){                    
                urlpage='load.php';
            }
           
           
            
            $( "#gridata" ).empty();
            
            showValues('frmsavrepay',theid,pagedata,action,urlpage,element).done(

            function(){		
                $(function(){populateForm('frmsavrepay',jsonObj['data']);});
                 $(this).prop('disabled',false); 
            });
       
    }
</script>
<style>
.tab {
    width: auto;
    height:auto;
    border: 1px solid silver;
    display: none;
    padding: 1px;
    overflow: hidden;	
}
  #toppanel {
    width: 30%;
    height:auto;  

	float:right;
	position: relative;	
	z-index: 5000 ;
	padding:2px;
	position: relative;
	display:none;

  }
 
  .loader{
	margin:200px;
	padding:10px;
	}
h1{
	font-family: 'Actor', sans-serif;
	color:#CCCCCC;
	font-size:16px;
	letter-spacing:1px;
	font-weight:200;
	text-align:center;
	text-shadow: 0 1px 0 #FFFFFF;
}
.loader span{
	width:16px;
	height:16px;
	border-radius:50%;
	display:inline-block;
	position:absolute;
	left:50%;
	margin-left:-10px;

	
}


.loader span:nth-child(2){
	background:#E84C3D;

	
}
.loader span:nth-child(3){
	background:#F1C40F;
	z-index:100;
}
.loader span:nth-child(4){
	background:#2FCC71;

}
</style>
<?php 
getlables("20,21,24,300,1161,1024,317,1403,9,316,317,819,1405,68,1243,1244,1245,1582,1620,1257");
require('../'.DIR_WS_INCLUDES . 'pageheader.php'); 
?><form   id="frmsavrepay" name="frmsavrepay" action="#">   
    <div>     
	    <input id="action" name="action" type="hidden" value="add">
      <input id="theid" name="theid" type="hidden" value="">
                          
      <div class="clientcat-container">
        <span><input type="radio" id="INDSAVACC" name="radiosclient" value="IND" checked>
        <label for="INDSAVACC">Individuals</label></span>
        <span><input type="radio" id="GRPSAVACC" name="radiosclient" value="GRP" >
        <label for="GRPSAVACC">Groups</label></span>
        <span><input type="radio" id="BUSSAVACC" name="radiosclient" value="BUSS" >
        <label for="BUSSAVACC">Business</label></span>                    
      </div>       
      <div class="input-container">                 
          <span>
            <?php echo $lablearray['317'];?><?php echo TEXT_FIELD_REQUIRED;?><br><input type="us-date" class="date" id="txtpayDate" name="txtpayDate" value="">
          </span>
          <span>
            <?php echo $lablearray['24'];?><?php echo TEXT_FIELD_REQUIRED;?><br><?php echo DrawComboFromArray(array(),'PAYMODES','','PAYMODES','','PAYMODES');?>
          </span>                        
          <span>
            <?php echo $lablearray['819'];?><?php echo TEXT_FIELD_REQUIRED;?><br><input type="text" id="txtVoucher" name="txtVoucher" value="">
          </span>                                         
          <span>
            <?php echo $lablearray['1405'];?><br><?php echo DrawComboFromArray(array(),'sproduct_prodid','','SAVPROD','','SAVPROD');?></span>
          <span>
          <?php echo $lablearray['1257'];?><?php echo TEXT_FIELD_REQUIRED; ?> <br>
          <select  id="client_regstatus" name="client_regstatus">                
            <option value="ACT" selected><?php echo $lablearray['68'];?></option>
            <option value="INA"><?php echo $lablearray['1243'];?></option>									
            <option value="EXT"><?php echo $lablearray['1244'];?></option>
            <option value="CLO"><?php echo $lablearray['1245'];?></option>                    
        </select>                  
      </span>                                
      </div>
      <div id='modes' class="input-container"></div>
      <div class="clientcat-container" >
        <span id="Name"></span>
        <button  class="btn" type="button"  id="radioedit" name="radiosaction" value="Add">Search</button>             		
    </div>         
    </div>      
    <div id="gridata"></div>
    <div class="footer-content"><button class="btn" name="Go"  type="button" onClick="CloseDialog(vFloatingPane.id);"  id="btnscancel"><?php echo $lablearray['300'];?></button><button class="btn" name="btnBack"  type="button"   id="btnBack"><?php echo $lablearray['1161'];?></button><button class="btn" name="btnSave"  type="button"   id="btnSave"><?php echo $lablearray['20'];?></button></div>
</form>	
<script type="text/javascript">

$( document ).ready(function() {
    
    $('input[type=us-date]').w2field('date',  { format: '<?php echo SETTING_DATE_FORMAT?>'})
    
    // payment mode
    $( "#PAYMODES" ).change(function() {
     
        if($('#PAYMODES').val()=='SA'){
          $("#sproduct_prodid").prop('disabled',false);   
        }else{
            $("#sproduct_prodid").prop('disabled',true); 
        }
        
        showValues('frmsavrepay','modes','search','PAYMODES','load.php',$('#PAYMODES').val());
    });
    
   //  $("#sproduct_prodid").prop('disabled',true); 
    
     $( "#sproduct_prodid" ).change(function() {    
        // alert($('#PAYMODES').val());
        
      //   $(".savprod").parent().find('select').value('S000');
         
       $(".savprod>select").val($('#sproduct_prodid').val());
       
    });
   
   
    $( "#btnSave" ).click(function() {	
        
        
        if($('#txtpayDate').val()=="") {            
          displaymessage('frmsavrepay',"<?php echo $lablearray['621']?>",'WARN');    
          return;  
        }

        var paydetails = {
          'paymodes': $('#PAYMODES').val(),
          'voucher': $('#txtVoucher').val(),
          'txtpayDate': $('#txtpayDate').val(),
          'status': $('#client_regstatus').val(),
          'savprod': $('#sproduct_prodid').val()??'',
          'cashaccounts': $('#cashaccounts_code').val()??'',         
          'cheqno': $('#cheques_no').val()??'',       
          'bankid':$('#bankbranches_id').val()??''
        };

        var loans = [];

        $('.chkgrd:checked').each(function() {
          alert();
          var value = $(this).val().replace('/', '_');
          var princ = $('#txt_princ_' + value).val();
          var int = $('#txt_int_' + value).val();
          var com = $('#txt_com_' + value).val();
          var pen = $('#txt_pen_' + value).val();
          var vat = $('#txt_vat_' + value).val();
          var fund = $('#txt_fund_' + value).val();
          var prodid = $('#txt_prod_' + value).val();
          var donor = $('#txt_donor_' + value).val();
          var clientcode = $('#txt_clientcode_' + value).val();
          var lnrno = $('#txt_lnrno_' + value).val();
          var member =$('#txt_member_' + value).val();
          
          loans.push({
            'princ': princ,
            'int': int,
            'com': com,
            'pen': pen,
            'vat': vat,
            'fund': fund,
            'prodid': prodid,
            'donor': donor,
            'clientcode': clientcode,
            'lnrno': lnrno,
            'member':member
          });
        });
    
        if(loans.length > 0) {
          var pageinfo = JSON.stringify({ paydetails, loans});
          showValues('frmsavrepay','','add',pageinfo,'addedit.php',$('#theid').val()).done(function(){
              
            // $('#gridata').empty();         
            // $(this).prop('disabled',false);       
            // $("#radioadd, #radioedit").trigger('click');  
              
          });
        }
        
    });	
    
    $("#PAYMODES").val("SA");

});	 
</script>
</BODY>
</HTML>