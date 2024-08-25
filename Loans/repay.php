<?php
require_once('../includes/application_top.php');
// require_once("../simple-php-captcha-master/simple-php-captcha.php");
require_once('../includes/classes/common.php');
//$_SESSION['captcha'] = simple_php_captcha(array(),'TRANSFER');
spl_autoload_register(function ($class_name) {
    include '../includes/classes/' . $class_name . '.php';
});
$_parent = basename(__FILE__);
//$code_array = unserialize($_SESSION['_CAPTCHA']['config']);//['code'];
// here you can perform all the checks you need on the user submited variables
//$_SESSION['security_number']=rand(10000,99999);
getlables("1355,9,1097,1224,1195,890,1168,1167,20,1166,1126,1125,1124,1163,244,1161,1159,1144,1145,1105,1160,1156,1157,1155,1154,1153,1152,1151,1150,730,1148,1147,896,1139,1143,1144,1146,1140,1141,1142,1138,1137,21,1135,300,1136,1096,1100,260,20,1097,1098,1099,1101,1102,1103,1104,1105,1106,1107,1108,1109,1110,1111,1112,1113,1114,1115,1133,1134");
?>
<link href="includes/javascript/w2ui-1.4.3.css" rel="stylesheet" type="text/css"/>
<script src="includes/javascript/w2ui-1.4.3.min.js" type="text/javascript"></script>
<script type="text/javascript">
    var searchtex = '';
    function getinfo(frm_id, theid, action, pagedata, urlpage, element) {
       
        

        $("#action").val(action);

        urlpage = 'load.php'


        // $( "#radioedit" ).prop( "checked", true);

        //  $( "#radioadd" ).prop( "checked", true);

        $("#theid").val(theid);


        var pageinfo = JSON.stringify($("#frmrepay").serializeArray());

        var data1 = JSON.stringify(JSON.parse('{"pageinfo":' + pageinfo + "}"));
//
        showValues('frmrepay', theid, action, data1, urlpage, element).done(function () {
            
            $(function () {
                $("#tab3").html('');
                populateForm('frmrepay', jsonObj['data']);
//                    var pageinfo =  JSON.stringify($("#DueTotal, #product_prodid").serializeArray());      		
//                    var data1 = JSON.stringify(JSON.parse('{"pageinfo":'+pageinfo+"}"));   
//                    showValues('frmrepay','','loadform',data1,'addedit.php','element');
                searchtext = $('input[name=radiosclient]:checked').val();
                
                if(searchtext=='GRPLOANSREP'){
                     $('#tab1').hide();                   
                     $('#tab3').show();
                }
           
                $("#toppanel").hide();
            });

        });

    }

    function calculateCharge() {

        var pageinfo = JSON.stringify($("#DuePrincipal, #DueInterest, #DueCommission, #DuePenalty, #txtproduct").serializeArray());

        var data1 = JSON.stringify(JSON.parse('{"pageinfo":' + pageinfo + "}"));

        showValues('frmrepay', '', 'loadform', data1, 'addedit.php', 'element');

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
        width:auto;
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
        background:#d6d5d7;
        z-index:100;
    }
    .loader span:nth-child(4){
        background:#2FCC71;

    }
 
 #customers {
   
    padding:3px;

}

#customers td, #customers th {
    padding: 0px;
}

#customers tr:nth-child(even){background-color: #f2f2f2;}

#customers tr:hover {background-color: #ddd;}

#customers th {
    padding: 0px;
    text-align: left;
    background-color: #4CAF50;
    color: white;
}

.scrollable {
    height: 100%;
    overflow: auto;
}
</style>
<?php
getlables("1219,1503,271,1096,1520,1405,24,1656,819,1236,1237,1136,1238,1144,1145,1105,1181,170,470,1239,1743,1240,1036");
require('../' . DIR_WS_INCLUDES . 'pageheader.php');
?>
<form   id="frmrepay" name="frmrepay" >  
    
    <div id="tab-example" style="margin:0px;display: inline-block;">
        <div id="LoanApptabs" style="display: inline-block; height:auto;padding:2px;"></div>

        <span id="toppanel"></span>
        
        <div id="tab1" class="tab"> 
           
                <span class="radiooptions">
                    <input type="radio" id="INDLOANS" name="radiosclient"value="INDLOANSREP"  checked="checked">
                    <label for="INDLOANS">Individuals</label>
                    <input type="radio" id="GRPLOANS" name="radiosclient"value="GRPLOANSREP" >
                    <label for="GRPLOANS">Groups</label>
                    <input type="radio" id="BUSLOANS" name="radiosclient" value="BUSLOANSREP" >
                    <label for="BUSLOANS">Business</label>
                    
               </span>
                 <span class="radiooptionsitem" >
                    <a class='icons button-tran' id="radioedit" title="<?php echo $lablearray['1036']; ?>">&nbsp;</a>
                </span>
             
                <?php echo $lablearray['1355']; ?> <input type="us-date" id="txtpayDate" name="txtpayDate" value="" size='10'>  <?php echo $lablearray['1656']; ?> <input type="text" id="OutBal" name="OutBal" value="0" disabled="disabled">
                <h1 id='div_name' class="indicator"></h1>
                 
                <input id="action" name="action" type="hidden" value="data">
                <input id="theid" name="theid" type="hidden" value="XXXXXXX">
                <input id="txtlnr" name="txtlnr" type="hidden" value="">
                <input id="client_idno" name="client_idno" type="hidden" value="">
                <input id="txtSavAcc" name="txtSavAcc" type="hidden" value="">
                <div class="input-container" >
                    <span><?php echo $lablearray['1096']; ?><br><input type="text" id="txtproduct" name="txtproduct" value="" size='6' readonly></span>
                    <span><?php echo $lablearray['1097']; ?><br><input type="text" id="txtlnr" name="txtlnr" value="" disabled size='8'></span>
                    <span ><?php echo $lablearray['24']; ?><br><?php echo DrawComboFromArray(array(), 'PAYMODES', '', 'PAYMODES', '', 'PAYMODES'); ?></span>
                    
                    <span><?php echo $lablearray['819']; ?><br><input type="text" id="txtvoucher" name="txtvoucher" value="" size='6'></span>
                    <span><?php echo $lablearray['1520']; ?><br><?php echo DrawComboFromArray(array(), 'SPRODID', '', 'SAVPROD', '', 'SPRODID'); ?></span>
                    <span id="modes"></span>
                </div >     
                
                <div class="checkbox-container" >
                     
                 
                        <span><input name="chkcloseLoan" id="chkcloseLoan" type="checkbox" value="Y">&nbsp;<?php echo $lablearray['1236']; ?></span>
                           							
                                <span><input name="chkignoreFutureInterest" id="chkignoreFutureInterest" type="checkbox" value="Y">&nbsp;<?php echo $lablearray['1237']; ?></span>
                            							
                                <span><input name="chkServicefee" id="chkServicefee" type="checkbox" value="Y">&nbsp;<?php echo $lablearray['1503']; ?> <input type="numeric" id="txtchargeamount" name="txtchargeamount" value="0.0"><input type="hidden" id="txtfeeacc" name="txtfeeacc" value=""></span>
                           
                         
                    
                </div>             

                <div class="loan-payment-container">                   
                    		
                    <div onclick="$('#toppanel').hide();" style="display: inline-block;">	
                        <legend style="background:#FEA29C; color:#666666"><?php echo $lablearray['1238']; ?></legend>	
                        <table  border="0" cellpadding="2">
                            <tr>
                                <td align="right">&nbsp;<?php echo $lablearray['1144']; ?></td>
                                <td>&nbsp;<input type="numeric" id="ArrearPrincipal" name="ArrearPrincipal" disabled="disabled" value='0' size='16' ></td>
                            </tr>
                            <tr>
                                <td align="right">&nbsp;<?php echo $lablearray['1145']; ?></td>
                                <td>&nbsp;<input type="numeric" id="ArrearInterest" name="ArrearInterest" disabled="disabled" size='16' value='0'></td>
                            </tr>
                            <tr>
                                <td align="right">&nbsp;<?php echo $lablearray['1105']; ?></td>
                                <td>&nbsp;<input type="numeric" id="ArrearCommision" name="ArrearCommision" disabled="disabled" size='16' value='0'></td>
                            </tr>
                            <tr>
                                <td align="right">&nbsp;<?php echo $lablearray['1181']; ?></td>
                                <td>&nbsp;<input type="numeric"id="ArrearPenalty" name="ArrearPenalty" disabled="disabled" size='16' value='0'></td>
                            </tr>
                            <tr>
                                <td align="right">&nbsp;<?php echo $lablearray['170']; ?></td>
                                <td>&nbsp;<input type="numeric" id="ArrearOther" name="ArrearOther" disabled="disabled" value='0' size='16' constraints="{pattern: '+0.000;-0.000'}"></td>
                            </tr>
                            <tr>
                                <td align="right">&nbsp;<?php echo $lablearray['470']; ?></td>
                                <td>&nbsp;<input type="numeric" id="ArrearTotal" name="ArrearTotal" disabled="disabled" value='0' size='16'  constraints="{pattern: '+0.000;-0.000'}"></td>
                            </tr>

                        </table>
                    </div>

                    <div onclick="$('#toppanel').hide();"  style="display: inline-block;">	
                            <legend style="background:#8DFC91;color:#666666"><?php echo $lablearray['1239']; ?></legend>					
                            <table width="100%" border="0" cellpadding="2">
                                <tr>
                                    <td align="right">&nbsp;<?php echo $lablearray['1144']; ?></td>
                                    <td>&nbsp;<input type="numeric" id="prepaidPrincipal" name="prepaidPrincipal" disabled="disabled" value='0' size='16'></td>
                                </tr>
                                <tr>
                                    <td align="right">&nbsp;<?php echo $lablearray['1145']; ?></td>
                                    <td>&nbsp;<input type="numeric" id="prepaidInterest" name="prepaidInterest" disabled="disabled" value='0' size='16'></td>
                                </tr>
                                <tr>
                                    <td align="right">&nbsp;<?php echo $lablearray['1105']; ?></td>
                                    <td>&nbsp;<input type="numeric" id="prepaidCommision" name="prepaidCommision" disabled="disabled" value='0' size='16'></td>
                                </tr>
                                <tr>
                                    <td align="right">&nbsp;<?php echo $lablearray['1181']; ?></td>
                                    <td>&nbsp;<input type="numeric" id="prepaidPenalty" name="prepaidPenalty" disabled="disabled" value='0' size='16'></td>
                                </tr>
                                <tr>
                                    <td align="right">&nbsp;<?php echo $lablearray['170']; ?></td>
                                    <td>&nbsp;<input type="numeric" id="prepaidOther" name="prepaidOther" disabled="disabled" value='0' size='16'>

                                    </td>
                                </tr>

                                <td align="right">&nbsp;<?php echo $lablearray['470']; ?></td>
                                <td>&nbsp;<input type="numeric"  id="prepaidTotal" name="prepaidTotal" disabled="disabled" value='0' size='16'></td>
                                </tr>

                            </table>

</div>	
                                      
                        <div style="display: inline-block;">
                          <legend style="background:#8DFC91;color:#666666"><?php echo $lablearray['1743']; ?></legend>					
                            <table  border="0" cellpadding="2">
                                <tr>
                                    <td align="right">&nbsp;<?php echo $lablearray['1144']; ?></td>
                                    <td>&nbsp;<input type="numeric" id="DuePrincipal" name="DuePrincipal" value='0' size='16'></td>
                                </tr>
                                <tr>
                                    <td align="right">&nbsp;<?php echo $lablearray['1145']; ?></td>
                                    <td>&nbsp;<input type="numeric" id="DueInterest" name="DueInterest" value='0' size='16'></td>
                                </tr>
                                <tr>
                                    <td align="right">&nbsp;<?php echo $lablearray['1105']; ?></td>
                                    <td>&nbsp;<input type="numeric" id="DueCommission" name="DueCommission" value='0' size='16'></td>
                                </tr>
                                <tr>
                                    <td align="right">&nbsp;<?php echo $lablearray['1181']; ?></td>
                                    <td>&nbsp;<input type="numeric" id="DuePenalty" name="DuePenalty"value='0' size='16'></td>
                                </tr>
                                <tr>
                                    <td align="right">&nbsp;<?php echo $lablearray['170']; ?></td>
                                    <td>&nbsp;<input type="numeric" id="DueOther" name="DueOther" value='0' size='16'></td>
                                </tr>
                                <tr>
                                    <td align="right">&nbsp;<?php echo $lablearray['470']; ?></td>
                                    <td>&nbsp;<input type="numeric" id="DueTotal" name="DueTotal" value='0' size='16' class="total"><a href="#" onClick="getinfo('frmrepay',$('txtlnr').val(),'edit','','load.php','')">Distribute</a></td>
                                </tr>

                            </table>
                            
                            <div id='div_deno' style="margin:0px;display: inline-block;width:auto"></div>
                            </d>
                    </div>                  
                        <fieldset>	
                            <legend style="background:#8DFC91;color:#666666"><?php echo $lablearray['1240']; ?></legend>	

                            <table border="0" cellpadding="2">
                                <tr>
                                    <td>&nbsp;<?php echo $lablearray['1144']; ?> <input type="numeric" id="PrincipalOver" name="PrincipalOver" value='0' disabled="disabled" size='9'></td>
                                    <td>&nbsp;<?php echo $lablearray['1145']; ?><input type="numeric"id="InterestOver" name="InterestOver" value='0' disabled="disabled" size='9'></td>
                                    <td>&nbsp;<?php echo $lablearray['1105']; ?><input type="numeric" id="CommissionOver" name="CommissionOver" value='0' disabled="disabled" size='9'></td>
                                    <td>&nbsp;<?php echo $lablearray['1181']; ?><input type="numeric" id="PenaltyOver" type="text" name="PenaltyOver" value='0' disabled="disabled" size='9'></td>
                                    <td>&nbsp;<?php echo $lablearray['170']; ?><input type="numeric" id="OtherOver" name="OtherOver" value='0' disabled="disabled" size='9'></td>
                                    <td>&nbsp;<?php echo $lablearray['470']; ?><input type="numeric"id="TotalOver" name="TotalOver" value='0' disabled="disabled" size='9'></td>

                                </tr>
                            </table>

                        </fieldset>
                                       
               


        </div>
      
        <div id="tab3" class="tab" style="width:100%;"></div>                    
        <?php getlables("9,1097,1655,890,1654,1657,20,1163,244,1161,1159,317,1144,1145,1105,1160,730,896,1139,1143,1146,1140,1141,1142,1138,1137,21,1135,300,1136,1096,260,20,1114,1115"); ?>
        <p align="right"><button class="btn" name="Go"  type="button" onClick="CloseDialog(vFloatingPane.id);"  id="btnscancel"><?php echo $lablearray['300']; ?></button><button class="btn" name="btnBack"  type="button"   id="btnBack"><?php echo $lablearray['1161']; ?></button><button class="btn" name="btnSave"  type="button"   id="btnSave"><?php echo $lablearray['20']; ?></button></p>
</form>	
<script type="text/javascript">

// remove tab
    $().w2destroy('LoanApptabs');   
    
       function updatetotals(cclass){
            
            var ntotalprinc = 0;
            var ntotalint = 0;
            var ntotalcomm = 0;
            var ntotalpen = 0;
            var ntotalvat = 0;
            var ntotal = 0;
            
            $( ".PRINC,.INT,.COMM,.PEN,.VAT").each(function( index, element ) {
                
                var myClass = $(this).attr("class");
                
                switch(myClass) {                
                case 'PRINC':
                    ntotalprinc = ntotalprinc + parseFloat($(this).val().replace(/,/g, ''));
                    ntotal = ntotal + parseFloat($(this).val().replace(/,/g, ''));        
                    $("#DuePrincipal").val(ntotalprinc);
                    break;
                    
                case 'INT':
                    ntotalint = ntotalint + parseFloat($(this).val().replace(/,/g, ''));
                    ntotal = ntotal + parseFloat($(this).val().replace(/,/g, ''));       
                    $("#DueInterest").val(ntotalint);
                    break;
                    
                case 'COMM':
                    ntotalcomm = ntotalcomm + parseFloat($(this).val().replace(/,/g, ''));
                    ntotal = ntotal + parseFloat($(this).val().replace(/,/g, ''));
             
                    $("#DueCommission").val(ntotalcomm); 
                    break;
                    
                case 'PEN':
                    ntotalpen = ntotalpen + parseFloat($(this).val().replace(/,/g, ''));
                    ntotal = ntotal + parseFloat($(this).val().replace(/,/g, ''));
                 
                    $("#DuePenalty").val(ntotalpen);
                    break;
                    
                 case 'VAT':
                    ntotalvat = ntotalvat + parseFloat($(this).val().replace(/,/g, ''));
                    ntotal = ntotal + parseFloat($(this).val().replace(/,/g, ''));
                    $("#DueVAT").val(ntotalvat);
                    
                    break;
                        
                default:
                    break;
                }                
                
            });
            
            $( "#DuePrincipal" ).trigger( "keyup" );
            
            
              var ntotalall = $("#OutBal").val();
//                
             ntotalall = parseFloat(ntotalall.replace(/,/g, ''));
//           

             if(ntotal > ntotalall){
               displaymessage("frmrepay", "<?php echo $lablearray['1657']; ?>", "INFO.");  
             }
             
         
        }
       

    $(document).ready(function () {
        
     
         $('input[name=radiosclient]').click(function(){
             $('#toppanel').show('slide', {direction: 'left'}, 1000);
             $("#toppanel").show();
             showValues('frmLoanapp1','toppanel','search',$('input[name=radiosclient]:checked').val(),'load.php');
         });
        
        $("#radioedit").click(function () {
            
                              
            $("#toppanel").html(htmlString);
           // $('#toppanel').show('slide', {direction: 'left'}, 1000);

            searchtext = $('input[name=radiosclient]:checked').val();
           //TODO: improve on this we should not allow call the API call when closing the panel
            $("#toppanel").toggle();

            showValues('frmLoanapp1', 'toppanel', 'search', searchtext, 'load.php');

        });

        $("#txtpayDate").change(function (  ) {
            $("#DuePrincipal,#DueInterest,#DuePenalty,#DueCommission,#DueTotal").val('0');

        });

        $("#DuePrincipal,#DueInterest,#DuePenalty,#DueCommission").keyup(function () {

            if ($('#PAYMODES').val() == 'SA') {
                calculateCharge();
            } else {
                var ntotal = parseFloat($('#DuePrincipal').val().replace(/,/g, '')) + parseFloat($('#DueInterest').val().replace(/,/g, '')) + parseFloat($('#DuePenalty').val().replace(/,/g, '')) + parseFloat($('#DueCommission').val().replace(/,/g, ''));

                $('#DueTotal').val(ntotal);
            }
        });

         $("#DuePrincipal,#DueInterest,#DuePenalty,#DueCommission").keyup(function () {

            if ($('#PAYMODES').val() == 'SA') {
                calculateCharge();
            } else {
                var ntotal = parseFloat($('#DuePrincipal').val().replace(/,/g, '')) + parseFloat($('#DueInterest').val().replace(/,/g, '')) + parseFloat($('#DuePenalty').val().replace(/,/g, '')) + parseFloat($('#DueCommission').val().replace(/,/g, ''));

                $('#DueTotal').val(ntotal);
            }
        });

        $("#DueTotal").change(function () {

           
        });

        $("#chkcloseLoan").click(function (event) {

            if ($(this).is(':checked')) {
                $('#chkignoreFutureInterest').prop('checked', true);
            } else {
                $('#chkignoreFutureInterest').prop('checked', false);
            }

            if ($('#txtlnr').val() != "") {
                calculateCharge();
                // getinfo('frmLoanapp1',$('#txtlnr').val(),'add','','addedit.php','');                  
            }


        });


        //w2utils.settings['date_format'] = '<?php echo SETTING_DATE_FORMAT; ?>';
        //$('input[type=eu-date]').w2field('date',{ blocked : [ '4/14/2011', '4/15/2011' ]});

        var config = {
            tabs: {
                name: 'LoanApptabs',
                active: 'tab1',
                tabs: [
                    {id: 'tab1', caption: '<?php echo $lablearray['1655']; ?>'},                
                    {id: 'tab3', caption: '<?php echo $lablearray['1654']; ?>'}

                ],
                onClick: function (event) {
                    $('.tab').hide();
              
                    $('#' + event.target).show();
                    
//                    if (event.target === 'tab2') {
//                        w2ui['grid_schedule'].refresh();
//                    }
                }
            }
        };
        //w2ui['tabs'].destroy();

        $(function () {

            $('#LoanApptabs').w2tabs(config.tabs);
            $('#tab1').show();

        });

    });

    $(document).ready(function () {

        w2utils.date(new Date());

        $(function () {
            $('input[type=us-date]').w2field('date', {format: '<?php echo SETTING_DATE_FORMAT ?>'})
            $('#ArrearPrincipal').w2field('float');
            $('#ArrearInterest').w2field('float');
            $('#ArrearCommision').w2field('float');
            $('#ArrearPenalty').w2field('float');
            $('#ArrearOther').w2field('float');
            $('#ArrearTotal').w2field('float');

            $('#prepaidPrincipal').w2field('float');
            $('#prepaidInterest').w2field('float');
            $('#prepaidPenalty').w2field('float');
            $('#prepaidCommission').w2field('float');
            $('#prepaidOther').w2field('float');
            $('#prepaidTotal').w2field('float');
		
            $('#DuePrincipal').w2field('float');
            $('#DueInterest').w2field('float');
            $('#DuePenalty').w2field('float');
            $('#DueCommission').w2field('float');
            $('#DueOther').w2field('float');
            $('#DueTotal').w2field('float');

        });



        //w2destroy('grid');

        $().w2destroy('grid_schedule');

        $('#grdgrpMembers').w2grid({
            name: 'grid_schedule',
            recid: 'item_id',
            recordsPerPage: 100,
            method: 'POST',
            columns: [
                {field: 'item_id', caption: 'ID', size: '50px'},
                {field: 'date', caption: "<?php echo $lablearray['317']; ?>", size: '20%', editable: {type: 'date'}},
                {field: 'principal', caption: "<?php echo $lablearray['1144']; ?>", size: '20%', editable: {type: 'float'}},
                {field: 'interest', caption: "<?php echo $lablearray['1145']; ?>", size: '20%', editable: {type: 'float'}},
                {field: 'commission', caption: "<?php echo $lablearray['1105']; ?>", size: '10%', editable: {type: 'float'}},
                {field: 'penalty', caption: "<?php echo $lablearray['1160']; ?>", size: '10%', editable: {type: 'float'}},
                {field: 'other', caption: "<?php echo $lablearray['1160']; ?>", size: '10%', editable: {type: 'float'}},
                {field: 'memid', caption: "<?php echo $lablearray['1159']; ?>", size: '10%'}
            ]
                    //
        });

        // payment mode
        $("#PAYMODES").change(function () {
            showValues('frmrepay', 'modes', 'search', 'PAYMODES', 'load.php?id=' + $('#client_idno').val(), $('#PAYMODES').val()).done(
                function () {
                    if ($("#PAYMODES").val() == 'SA') {
                        
                        if($("#txtlnr").val()==""){
                           $("#PAYMODES").val('');
                        }
                       // calculateCharge();
                    }

                });

        });

        $("#btnSave").click(function () {

            var pageinfo = JSON.stringify($("#frmrepay").serializeArray());


            var data1 = JSON.stringify(JSON.parse('{"pageinfo":' + pageinfo + "}"));

            // save details of loan
            //showValues('frmrepay',$('#txtlnr').val(),$( "#action" ).val(),pagedata);
            showValues('frmrepay', '', 'add', data1, 'addedit.php', $('#txtlnr').val());
            $('#toppanel').hide();



        });

    });
</script>
</BODY>
</HTML>