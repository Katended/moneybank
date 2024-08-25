<?php
require_once('../includes/application_top.php');
//require_once("../simple-php-captcha-master/simple-php-captcha.php");
//$_SESSION['captcha'] = simple_php_captcha(array(),'TRANSFER');
spl_autoload_register(function ($class_name) {
    include '../includes/classes/' . $class_name . '.php';
});
$_parent = basename(__FILE__);
getlables("21,1097,300,1161,20,1199,1495");
// TO DO: Add receipts Printing
?>
<script type="text/javascript">

    var searchtext = '';
    var act = '';

    $("#timedeposits").click(function () {

        act = 'EDIT';
        $('#action').val('edit');

        // alert(searchtext);
        searchtext = $('input[name=radiosclient]:checked').val() + 'TDS';
        
        
        
        showValues('frmTDeposit', 'toppanel', 'search', searchtext, 'load.php?act=' + act).done(
                function () {
                    // $('#POITable').fixedHeaderTable({ footer: true, cloneHeadToFoot: true, altClass: 'odd', autoShow: false });
                    // $('#POITable').fixedHeaderTable('show', 1000); 
                });

    });


    function calculateCharge() {

        // check if all control are filled
        if (!$('#txtDate').val() || !$('#product_prodid').val() || !$('#PAYMODES').val() || !$('#txtintrate').val() || !$('#txtamount').val() || !$('#TDSTATUS').val() || !$('#INSTYPE').val() || !$('#txtperiod').val()) {
            displaymessage('frmTDeposit', '<?php echo $lablearray['1495']; ?>', 'INFO')
            return;

        }


        var pageinfo = JSON.stringify($("#frmTDeposit").serializeArray());

        var data1 = JSON.stringify(JSON.parse('{"pageinfo":' + pageinfo + "}"));

        showValues('frmTDeposit', $('#theid').val(), 'eval', data1, 'load.php', '');

    }


    function getClients(){


        act = 'add';
        $('#action').val('edit');
        //  searchtext = $('input[name=radiosclient]:checked').val()+'SAVACC'
        searchtext = $('input[name=radiosclient]:checked').val();

        showValues('frmTDeposit', 'toppanel', 'search', searchtext, 'load.php?act=' + act).done(
                function () {
                              
                });

    }


    $("#radiotran").click(function () {

        if ($("#theid").val() == "") {
            getClients();
            displaymessage('frmTDeposit', '<?php echo $lablearray['1199']; ?>', 'INFO')
            return;
        }
        showValues('frmTDeposit', 'savdata', 'search', 'TDTRAN', 'load.php?act=edit&cid=' + $("#theid").val() + '&product_prodid=' + $("#product_prodid").val()).done(
                function () {
                    $("#savdata").show();

                })

    });

    function getinfo(frm_id, theid, action, pagedata, urlpage, element) {


        $("#action").val(action);

        if (action == 'add') {
            $("#savdata").empty();
            $("#toppanel").empty();
        }
        urlpage = 'addedit.php';
        if (action === 'add' || action === 'edit' || action === 'load' || action === 'loadform') {
            urlpage = 'load.php';

        }
        if(action === 'loadform'){
                $("#frmTDeposit :input").not("[id=txtvoucher],[id=btnSearch], [id=txtDate],[id=PAYMODES],[id=cashaccounts_code],[id=btnSave],[id=product_prodidfr],[id=product_prodidfr],[class=chkgrd]").attr("disabled", true);
          }else{
             $("#frmTDeposit *:disabled").attr("disabled", false);  
        }
        var pageinfo = JSON.stringify($("#frmTDeposit").serializeArray());

        var data1 = JSON.stringify(JSON.parse('{"pageinfo":' + pageinfo + "}"));


        showValues('frmTDeposit',theid, action, data1, urlpage, element).done(
                function () {
                   $("#frmTDeposit *:disabled").attr("disabled", false);  
                  switch(action) {
                    case 'REVERSE':
                    case 'WITHDRAW':
                        $("#radiotran").trigger("click");
                        break;
                    case 'loadform':
                      //  showValues('frmTDeposit', 'savdata', 'search', 'TDTRAN', 'load.php?act=edit&cid=' + $("#client_idno").val());              
                      //  $("#trans").show();
                        break;
                    default:
                        showValues('frmTDeposit', 'savdata', 'search', 'TDTRAN', 'load.php?act=edit&cid=' + $("#client_idno").val());              
                        $("#savdata").show();
                    } 

                

                });



    }
</script>

<style>

    input[type="numeric"] {
        margin: 0; 
        font-family: sans-serif;
        font-size: 12px;
        box-shadow: none;

    }
    /**
    STYLES ARE NESSESARY FOR THE TABS TO DISPLAY WELL
    */
    .tab {
        width:auto;
        height:auto;
        border: 1px solid silver;
        display: none;
        padding: 1px;
        overflow: hidden;

    }

    #gridata {
        width: 40%;
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
        color:#616161;
        width:auto;
        float:left;
        font-size:18px;
        margin-right:5px;
        letter-spacing:1px;
        font-weight:200;        
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
        -webkit-animation:3s infinite linear;
        -moz-animation:3s infinite linear;
        -o-animation:3s infinite linear;

    }


    .loader span:nth-child(2){
        background:#E84C3D;
        -webkit-animation:kiri 1.2s infinite linear;
        -moz-animation:kiri 1.2s infinite linear;
        -o-animation:kiri 1.2s infinite linear;

    }
    .loader span:nth-child(3){
        background:#F1C40F;
        z-index:100;
    }
    .loader span:nth-child(4){
        background:#2FCC71;
        -webkit-animation:kanan 1.2s infinite linear;
        -moz-animation:kanan 1.2s infinite linear;
        -o-animation:kanan 1.2s infinite linear;
    }

    .tab:focus {
        background-color:red;
    }
    @-webkit-keyframes kanan {
        0% {-webkit-transform:translateX(20px);
        }

        50%{-webkit-transform:translateX(-20px);
        }

        100%{-webkit-transform:translateX(20px);
             z-index:200;
        }
    }
    @-moz-keyframes kanan {
        0% {-moz-transform:translateX(20px);
        }

        50%{-moz-transform:translateX(-20px);
        }

        100%{-moz-transform:translateX(20px);
             z-index:200;
        }
    }
    @-o-keyframes kanan {
        0% {-o-transform:translateX(20px);
        }

        50%{-o-transform:translateX(-20px);
        }

        100%{-o-transform:translateX(20px);
             z-index:200;
        }
    }




    @-webkit-keyframes kiri {
        0% {-webkit-transform:translateX(-20px);
            z-index:200;
        }
        50%{-webkit-transform:translateX(20px);
        }
        100%{-webkit-transform:translateX(-20px);
        }
    }

    @-moz-keyframes kiri {
        0% {-moz-transform:translateX(-20px);
            z-index:200;
        }
        50%{-moz-transform:translateX(20px);
        }
        100%{-moz-transform:translateX(-20px);
        }
    }
    @-o-keyframes kiri {
        0% {-o-transform:translateX(-20px);
            z-index:200;
        }
        50%{-o-transform:translateX(20px);
        }
        100%{-o-transform:translateX(-20px);
        }
    }

    #customers td, #customers th {
        padding: 0px;
    }

    #customers tr:nth-child(even){background-color: #f2f2f2;}

    #customers tr:hover {background-color: #ddd;}

   
</style>
<?php
require('../' . DIR_WS_INCLUDES . 'pageheader.php');
getlables("21,1604,886,885,1015,19,2,1673,1663,1598,1214,24,20,1096,21,24,882,300,271,391,1600,299,298,1093,1214,1594,654,1208,1595,1591,1607,1443,1560");
?>
<form id="frmTDeposit" name='frmTDeposit'>
    <p id='status' align="center" style="color:#FF0000;margin:2px;"></p>
    <input id="action" name="action" type="hidden" value="add">
    <input id="theid" name="theid" type="hidden" value="">   
    
    <table cellpadding="0" width="100%" border="0" cellspacing="0">
        <tr> <td><span class="metroblock commentsblock left"><span class="indicator" id='div_name'> </span></span></td>          
            <td  valign="top"><?php echo Common::clientOptions(); ?></td><td align="right"> <button class="btn" id="btnSearch" style="align:right;margin-left:50px;"  type="button" onclick="getClients();"><?php echo $lablearray['21']; ?></button></td>
        </tr> 
        
        <tr>           
            <td align="CENTER" colspan="3"><?php echo $lablearray['1595']; ?> <input type="text" id="txtmatvalue" name="txtmatvalue" value="0.0" disabled> <?php echo $lablearray['1596']; ?> <input type="us-date" class='date' id="txtmDate" name="txtmDate" value="" disabled> <button type="button" class="btn" id="calc" name="calc"  type="button" OnClick="calculateCharge()"><?php echo $lablearray['19']; ?></button><?php echo $lablearray['1208']; ?><?php echo Common::DrawComboFromArray(array(), 'TDSTATUS', '', 'TDSTATUS', '', 'TDSTATUS', 'frmTDeposit'); ?></td>
        </tr>  
      
    </table> 
  
    <div id="tab-example" style="margin-top:0px;">
        <div id="LoanApptabs" style="width:100%;padding:0px;float:center;margin:0px;" ></div>
        <div id="tab1" class="tab">                       
            
            <div id="savdata" style='width:auto;padding:10px;'></div>
           
            <table cellpadding="2" width="100%" border="0" cellspacing="0">
                <tr>
                    <td valign="top">

                        <table cellpadding="2" width="100%">
                            <tr>
                                <td><?php echo $lablearray['298']; ?><br><input type="us-date" class='date' id="txtDate" name="txtDate" value=""><?php echo TEXT_FIELD_REQUIRED; ?></td>
                                <td><?php echo $lablearray['1214']; ?><br><?php echo DrawComboFromArray(array(), 'product_prodidfr', '', 'SAVPROD', '', 'SAVPROD'); ?></td>      
                                <td><?php echo $lablearray['299']; ?><br><input type="text" id="txtvoucher" name="txtvoucher" value=""><?php echo TEXT_FIELD_REQUIRED; ?></td>
                            </tr>
                            <tr>
                                <td><?php echo $lablearray['1093']; ?><br><input type="text" id="client_idno" name="client_idno" value="" readonly="readonly" ></td>

                                <td ><?php echo $lablearray['1096']; ?><br><?php echo Common::DrawComboFromArray(array(), 'product_prodid', '', 'TPROD', '', ''); ?><?php echo TEXT_FIELD_REQUIRED; ?></td>
                                <td><?php echo $lablearray['391']; ?><br><input id="txttdnumber" name="txttdnumber" type="text" value="">

                            </tr>

                            <tr>
                                <td>

                                    <?php echo $lablearray['24']; ?><br><?php echo Common::DrawComboFromArray(array(), 'PAYMODES', '', 'PAYMODES', '', 'PAYMODES', 'frmSave'); ?><?php echo TEXT_FIELD_REQUIRED; ?>
                                </td>   

                                <td>
                                    <?php echo $lablearray['1594']; ?><br>
                                    <input type="text" id="txtintrate" name="txtintrate" value="0.0" maxlength="4"><?php echo TEXT_FIELD_REQUIRED; ?> 

                                </td>
                                <td>
                                    <?php echo $lablearray['271']; ?><br>
                                    <input type="text" id="txtamount" name="txtamount" value="0"  ><?php echo TEXT_FIELD_REQUIRED; ?> 
                                </td>
                            </tr>

                            <tr>
                                <td>
                                    <?php echo $lablearray['1598']; ?><br>
                                    <?php echo Common::DrawComboFromArray(array(), 'INSTYPE', '', 'INSTYPE', '', 'INSTYPE', 'frmTDeposit'); ?><?php echo TEXT_FIELD_REQUIRED; ?>

                                </td>  
                                <td>
                                    <?php echo $lablearray['882']; ?><br>
                                    <input type="text" id="txtperiod" name="txtperiod" value="0"><?php echo TEXT_FIELD_REQUIRED; ?> 

                                </td>
                                <td>
                                    <?php echo $lablearray['1600']; ?><br>
                                    <?php echo Common::DrawComboFromArray(array(), 'FREQ', '', 'INSTYPE', '', 'FREQ', 'frmTDeposit'); ?><?php echo TEXT_FIELD_REQUIRED; ?>

                                </td>
                            </tr>
                            <tr>
                                <td  nowrap colspan='3' align='center'><span id="modes"></span></td>

                            </tr>
                              <tr>
                                <td  nowrap colspan='3' align='right'><input name="chkintCapital" id="chkintCapital" value='Y' type="checkbox">  <?php echo $lablearray['1604']; ?></td>
                            </tr>
                        </table>
                    </td>

                    <td  valign='top'>
                        <div id="toppanel"></div>
                    </td>


                </tr>


            </table> 


            </fieldset>
           
        </div>
        <div id="tab2" class="tab">         
        </div>
    </div>
    <p align="right"><button type="reset" id='reset' class="btn" name="Go"  type="button"><?php echo $lablearray['2']; ?></button> <button class="btn" name="Go"  type="button" onClick="CloseDialog(vFloatingPane.id);"  id="btnscancel"><?php echo $lablearray['300']; ?></button><button class="btn" name="btnSave"  type="button"   id="btnSave"><?php echo $lablearray['20']; ?></button></p>   
</form>	

<script type="text/javascript">
    $(document).ready(function () {
        $().w2destroy('LoanApptabs');

        var config = {
            tabs: {
                name: 'LoanApptabs',
                active: 'tab1',
                tabs: [
                    {id: 'tab1', caption: '<?php echo $lablearray['1673']; ?>'},
                    {id: 'tab2', caption: '<?php echo $lablearray['1663']; ?>'}
                ],
                onClick: function (event) {
                    $('.tab').hide();
                    $('#' + event.target).show();
//                    if (event.target == 'tab1') {
//                        w2ui['grid_schedule'].refresh();
//                    }
                }
            }
        }

        $(function () {

            $('#LoanApptabs').w2tabs(config.tabs);
            $('#tab1').show();

        });

        $('input[type=us-date]').w2field('date', {format: '<?php echo SETTING_DATE_FORMAT ?>'})
        $("#PAYMODES").trigger("change");
//      $('#txtamount').blur(function(e) {
//         // alert($('select#ttype').val());
//        if( $('select#ttype').val()=='SW' || $('select#ttype').val()=='SA'){
//            var pageinfo =  JSON.stringify($("#txtamount, #product_prodid, #ttype").serializeArray());      		
//            var data1 = JSON.stringify(JSON.parse('{"pageinfo":'+pageinfo+"}"));   
//            showValues('frmTDeposit','','loadform',data1,'addedit.php','element');
//        }
//         
//     });
//    
//     $('#txtsearchterm').keydown(function(e) {
//        if(e.keyCode === 13) {
//            showValues('frmTDeposit','transferacc','search', $('input[name=radiosclient]:checked').val()+'SAVACC','load.php?act=EXT&searchterm='+$('#txtsearchterm').val());
//        }
//    });

        // payment mode
        $("#PAYMODES").change(function () {            
            showValues('frmTDeposit', 'modes', 'search', 'PAYMODES', 'load.php?id='+$('#client_idno').val(), $('#PAYMODES').val());
        });

        $("#btnSave").click(function () {
            
            if($('#TDSTATUS').val()=='TW'){
                $('#action').val('WITHDRAW');
           
               
            }else{
                $('#action').val('add');
            }

            $(this).prop('disabled', true); // didable save button
            var pageinfo = JSON.stringify($("#frmTDeposit").serializeArray());
            var data1 = JSON.stringify(JSON.parse('{"pageinfo":' + pageinfo + "}"));
            var client_idno =  $("#client_idno").val();
            showValues('frmTDeposit', '', $('#action').val(), data1, 'addedit.php', $('#theid').val()).done(
                    function () {
                        // $("#txtamount, #txtvoucher").val("");
                        showValues('frmTDeposit', 'savdata', 'search', 'TDTRAN', 'load.php?act=edit&acc=' + $("#client_idno").val() + '&product_prodid=' + $("#product_prodid").val()).done(
                                function () {
                                   
                                     showValues('frmTDeposit', 'savdata', 'search', 'TDTRAN', 'load.php?act=edit&cid=' + client_idno);              
                                      $("#savdata").show();
                                });

                        //  $( "#radiotran" ).trigger( "click" );           

                        //  populateForm('frmTDeposit',jsonObj['data']);
                    });


            // document.getElementById("#frmTDeposit").reset();
            $(this).prop('disabled', false);
        });

        $('#product_prodidto').on('change', function () {
            // searchtext = $('input[name=radiosclient]:checked').val()+'SAVACC';
            showValues('frmTDeposit', 'transferacc', 'search', returnClientType($('input[name=radiosclient]:checked').val()), 'load.php?act=EXT&searchterm=' + $('#product_prodidto').val() + '&client_idno=' + $('#client_idno').val() + '&acc=' + $('#txtsavaccount').val() + '&product_prodid=' + $('#product_prodid').val());

        });
//    

        $(document.body).on('keypress', '#txtsearchterm', function (event) {

            str = "";
            $('input[type=text][name=txtamountto]').each(function () {
                str += $(this).val() + "$";
                //alert(str);
            });


            if (event.which == 13) {
                event.preventDefault();
                showValues('frmTDeposit', 'transferacc', 'search', searchtext, 'load.php?act=EXT&searchterm=' + $('#txtsearchterm').val());
            }

        });


    });
</script>
</BODY>
</HTML>