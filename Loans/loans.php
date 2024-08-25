<?php
require_once('../includes/application_top.php');
require_once("../simple-php-captcha-master/simple-php-captcha.php");

//  $_SESSION['captcha'] = simple_php_captcha(array(),'TRANSFER');
$_parent = basename(__FILE__);
//$code_array = unserialize($_SESSION['_CAPTCHA']['config']);//['code'];
// if the user is not logged on, redirect them to the login page
if (AuthenticateAccess('LOGIN') == 0) {
    //tep_redirect(tep_href_link(FILENAME_DEFAULT));
    //tep_redirect(tep_href_link(FILENAME_LOGIN));
}

//session_start();
// here you can perform all the checks you need on the user submited variables
$_SESSION['security_number'] = rand(10000, 99999);
//$inttypearray = getInterestTypes();
//$instypearray = getInstallmentTypes();
$funds = getFunds();
$donors = getDonors();
$loancat = getLoanCategory();

$results_query = tep_db_query("SELECT branch_code, licence_organisationname FROM " . TABLE_LICENCE . " WHERE licence_build='" . $_SESSION['licence_build'] . "'");

while ($cats = tep_db_fetch_array($results_query)) {
    $operators[$cats['branch_code']] = $cats['branch_code'].' '.$cats['licence_organisationname'];
}

getlables("1677,1742,1581,1219,1041,1039,1474,1040,1234,1218,1015,176,272,885,1178,1169,271,1093,316,1168,1167,20,1495,1126,1125,1124,1163,244,1161,1159,317,1144,1145,1105,1160,1158,1158,1156,1157,1154,1157,1155,1154,1157,1153,1152,1151,1150,730,1148,1147,896,1139,1143,1144,1145,1105,1140,1141,1142,1138,1137,21,1113,300,1136,1096,1100,260,20,1097,1098,1099,1101,1102,1103,1104,1105,1106,1107,1108,1109,1110,1111,1112,1113,1114,1115,1127,1128,1129,1130,1131,1132,1133,1134");
?>
<script type="text/javascript">
    var htmlLoanApp = '';
    var htmlApprove = '';
    var htmlDisburse = '';

    $(" #btnSearch").click(function () {

        if (document.getElementById('radiorefinance').checked || document.getElementById('radioreschedule').checked || document.getElementById('radioapprove').checked) {

            if ($('#txtsearchterm').val() == "") {

                displaymessage('frmLoanapp1', '<?php echo $lablearray['1581']; ?>', 'INFO')
                return;
            }

            var paramtext = document.getElementById('radioreschedule').value;


            showValues('frmLoanapp1', 'tab-example', 'search', paramtext, 'load.php?searchterm=' + $('#txtsearchterm').val()).done(function () {
                //  w2utils.date(new Date());

                w2utils.date(new Date());
                $('input[type=us-date]').w2field('date', {format: '<?php echo SETTING_DATE_FORMAT ?>'});
                $("#PAYMODES").trigger("change");
            });
            return;
            
          
        }
   
        $("#griddata").html(htmlString);
        $('#griddata').show('slide', {direction: 'left'}, 1000);
        var searchtext = '';
        if (document.getElementById('radioadd').checked) {

            switch ($('input[name=radiosclient]:checked').val()) {
                case 'INDLOANS':
                    searchtext = 'IND';
                    break;
                case 'GRPLOANS':
                    searchtext = 'GRP';
                    break;

                case 'BUSLOANS':
                    searchtext = 'BUSS';
                    break;

                case 'MEMLOANS':
                    searchtext = 'GMEM';
                    break;
            }


        } else {
            var searchtext = 'ADD' + $('input[name=radiosclient]:checked').val();
        }


        showValues('frmLoanapp1', 'griddata', 'search', searchtext, 'load.php');

    });

    function newPage(TXTPAGE) {
        $("#clientcat").show();
        switch (TXTPAGE) {
            case 'LOANAPPLY':
                vFloatingPane.refresh();
                break;

            case 'LOANAPPROVE':
                showValues('frmLoanapp1', 'tab-example', 'search', TXTPAGE, 'load.php').done(function () {
                    w2utils.date(new Date());
                    $('input[type=us-date]').w2field('date', {format: '<?php echo SETTING_DATE_FORMAT ?>'});
                });
                break;

            case 'LOANDISBURSE':
            case 'LOANDISBURSED':


                showValues('frmLoanapp3', 'tab-example', 'search', TXTPAGE, 'load.php').done(function () {
                    w2utils.date(new Date());
                    //  $( "#radiodisbursed" ).trigger( "click" ); 
                    $('input[type=us-date]').w2field('date', {format: '<?php echo SETTING_DATE_FORMAT ?>'});
                    $("#PAYMODES").trigger("change");
                });
                break;

            case 'REFINANCE':
            case 'WRITEOFF':

                showValues('frmLoanapp3', 'tab-example', 'search', TXTPAGE, 'load.php').done(function () {
                    w2utils.date(new Date());
                    $('input[type=us-date]').w2field('date', {format: '<?php echo SETTING_DATE_FORMAT ?>'});
                    $("#PAYMODES").trigger("change");
                });
                break;

            case 'RESCHEDULE':
                showValues('frmLoanapp1', 'tab-example', 'search', TXTPAGE, 'load.php').done(function () {
                    $("#clientcat").hide();
                    w2utils.date(new Date());
                    $('input[type=us-date]').w2field('date', {format: '<?php echo SETTING_DATE_FORMAT ?>'});
                });
                break;

            default:
                break;
        }


    }

    // SAVE
    function UpdateData(TXTPAGE) {
        
        
        var pageinfo = JSON.stringify($("#frmLoanapp1").serializeArray());
     
        var data1 = JSON.stringify(JSON.parse('{"pageinfo":' + pageinfo + "}"));

        showValues('frmLoanapp1', $('#theid').val(), 'update', data1, 'addedit.php', '').done(TXTPAGE,
                function () {

                    switch (TXTPAGE) {

                        case 'LOANAPPROVE':
                            $("#div_name").html("");
                            $("#radioapprove").trigger("click");
                            break;

                        case 'LOANDISBURSE':

                            $("#radiodisburse").trigger("click");
                            $("#PAYMODES").trigger("change");
                            break;


                        case 'LOANDISBURSED':
                            $("#radiodisbursed").trigger("click");
                            break;

                        case 'RESCHEDULE':
                            //    $( "#radioreschedule" ).trigger( "click" );                                

                            break;

                        case 'REFINANCE':
                            //  $( "#radiorefinance" ).trigger( "click" );  
                            // $( "#PAYMODES" ).trigger( "change" );
                            break;


                        case 'WRITEOFF':
                            $("#radiowriteoff").trigger("click");
                            $("#PAYMODES").trigger("change");
                            break;
                        default:

                    }


                });


    }

    function getinfo(frm_id, theid, action, pagedata, urlpage, element) {

        $('#griddata').hide();

        switch (pagedata) {
            case 'REFINANCE':
            case 'LOANDISBURSE':
                $("#action").val('update');
                $("#loan_number").val(theid);

                showValues('frmLoanapp1', theid, 'edit', pagedata, 'load.php?action=edit', pagedata);

            default:

                TXTPAGE = selectClientType();

                $("#action").val(action);
                $("#client_idno").val(theid);
                $("#client_idno2").val(theid);

                if (TXTPAGE == 'GRPLOANS') {
                    // get group members                       
                    showValues('frmLoanapp1', theid, 'loadform', 'MEM', 'load.php', 'MEM');
                } else {
                    showValues(frm_id, theid, 'edit', pagedata, 'load.php', TXTPAGE)
                }

                $('#griddata').show();

                break;
        }



    }

    function loadinfo1(frm_id) {

        var dfrd1 = $.Deferred();

        populateForm(frm_id, jsonObj['data']);
        dfrd1.resolve()
        return $.when(dfrd1).done(function () {
            // console.log('Done 1');
            // Both asyncs tasks are done
        }).promise();

        //w2ui['grid_schedule'].clear();
        //w2ui['grid_schedule'].add(jsonObj['gridinfo']);
        //w2ui['grid_schedule'].refresh();
        //w2ui['grid_schedule'].stateSave();	

    }

    function loadinfo2() {
        var dfrd2 = $.Deferred();
        w2ui['grid_schedule'].clear();
        w2ui['grid_schedule'].refresh();
        w2ui['grid_schedule'].add(jsonObj['gridinfo']);
        w2ui['grid_schedule'].resize();
        w2ui['grid_schedule'].refresh();
        $("#chkupdateloan").attr("disabled", false);

        dfrd2.resolve()
        return dfrd2.promise();
    }

    // showValues('frmLoanapp1', 'griddata', 'search', searchtext, 'load.php');
    function selectClientType() {

        var tags = document.getElementsByName('radiosclient');

        for (var i = 0; i < tags.length; ++i)
        {
            if (tags[i].checked) {

                TXTPAGE = tags[i].value;
            }
        }

        return TXTPAGE
    }

    function openClients(cpar) {


        TXTPAGE = selectClientType();


        switch (TXTPAGE) {
            case 'INDLOANS':
//        $( "#Indfieldset" ).show( "slow" );			
//	$( "#Grpfieldset" ).hide();    
//       
//	$('#memdetails').attr('disabled','disabled');
//         dojo.style(dijit.byId("mem_details").controlButton.domNode,{display:"none"}); 

                TXTPAGE = 'GIND';
                break;

            case 'GRPLOANS':

//        $( "#Grpfieldset" ).show( "slow" );	
//	$('#memdetails').removeAttr('disabled');
//        dojo.style(dijit.byId("mem_details").controlButton.domNode,{display:"inline-block"});
//        $("#mem_details").children().prop('disabled',false); 
//	$( "#Indfieldset").hide();

                TXTPAGE = 'GGRP';
                break;

            case 'BUSLOANS':

//       $( "#Grpfieldset" ).show( "slow" );
//	$('#memdetails').removeAttr('disabled');	
//	$( "#Indfieldset" ).hide();
//         dojo.style(dijit.byId("mem_details").controlButton.domNode,{display:"none"});       
                TXTPAGE = 'GBUSS';
                break;

            case 'MEMLOANS':
                TXTPAGE = 'GMEM';
                break;
            default:
                break;
        }

        showValues('frmLoanapp3', 'toppanel', 'search', TXTPAGE, 'load.php', cpar).done(function () {

        });

        $('#toppanel').show();

        $('#toppanel').css({top: '30%', left: '50%', margin: '-' + ($('#toppanel').height() / 2) + 'px 0 0 -' + ($('#toppanel').width() / 2) + 'px'});

        $("table").click(function (e) {

            if (e.target.id != 'btnSearch') {
                $("#toppanel").hide();
            }
        });

    }

    function getginfo(frm_id, theid, action, pagedata, urlpage, element) {

        $("#toppanel").hide();


        showValues('frmLoanapp3', theid, action, pagedata, urlpage, element).done(function (element) {


        });
        $("#toppanel").hide();
    }

    var nRow = 0;

    function deleteRow(tname) {

        $("input.chkgrd:checkbox").each(function () {
            // var  row =  $(this);
            if ($(this).prop("checked")) {
                var i = this.parentNode.parentNode.rowIndex;
                document.getElementById(tname).deleteRow(i);
            }
        });

    }

    var row

    function getRowID(id) {

        id = id + 1;
        return id;
    }

    function AddRow(tname, caction) {
        var table = document.getElementById(tname);
        var tableRef = document.getElementById(tname).getElementsByTagName('tbody')[0];
        var row = table.insertRow(tableRef.rows.length + 1)
        switch (caction) {
            case 'ADD':
                var rowid = getRowID(tableRef.rows.length);
                row.setAttribute('id', 'R' + rowid);
                var cell0 = row.insertCell(0);
                var cell1 = row.insertCell(1);
                var cell2 = row.insertCell(2);
                var cell3 = row.insertCell(3);
                var cell4 = row.insertCell(4);
                var cell5 = row.insertCell(5);
                var cell6 = row.insertCell(6);

                cell0.innerHTML = "<input type='checkbox'  class='chkgrd' value='' id='chk_'" + rowid + "' name='chk_'" + rowid + "'>"
                cell1.innerHTML = "<input size='12' type='us-date' data-dojo-type='dijit/form/DateTextBox' id='DATE_" + rowid + "' name='DATE_" + rowid + "' value=''>";
                cell2.innerHTML = "<input type='hidden' id='ID_" + rowid + "' name='ID_" + rowid + "' value='" + rowid + "'><input size='15' type='text' id='PRINC_" + rowid + "' name='PRINC_" + rowid + "' value='0.0'>"
                cell3.innerHTML = "<input size='15' type='text' id='INT_'" + rowid + "' name='INT_'" + rowid + "' value='0.0'>";
                cell4.innerHTML = "<input size='15' type='text' id='COMM_'" + rowid + "' name='COMM_'" + rowid + "' value='0.0'>";
                cell5.innerHTML = "<input size='15' type='text' id='PEN_'" + rowid + "' name='PEN_'" + rowid + "' value='0.0'>";
                cell6.innerHTML = "<input type='text' size='4' id='OTH'" + rowid + "' name='OTH'" + rowid + "' value='0.0'>";
                w2utils.date(new Date());
                $('input[type=us-date]').w2field('date', {format: '<?php echo SETTING_DATE_FORMAT ?>'});

                break;
            case 'DELETE':


                break;

        }

    }


</script>
<style>
    /**
    STYLES ARE NESSESARY FOR THE TABS TO DISPLAY WELL
    */
    .tab {
        width: auto;
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
</style>
<?php require('../' . DIR_WS_INCLUDES . 'pageheader.php'); ?>
<form   id="frmLoanapp1" name="frmLoanapp1" name='frmLoanapp1'>
    <div class="client_options">
        <span>
            <input  type="radio" id="radioadd" name="radiosclientloan" value="addedit" checked onclick="newPage('LOANAPPLY')">
            <label for="radioadd"><?php echo $lablearray['1112']; ?></label>
        </span>
        <span>
            <input  type="radio" id="radioapprove" name="radiosclientloan" value="LOANAPPROVE" onclick="newPage('LOANAPPROVE')">
            <label for="radioapprove"><?php echo $lablearray['1039']; ?></label>
        </span>
        <span>
            <input type="radio" id="radiodisburse" name="radiosclientloan" value="LOANDISBURSE" onclick="newPage('LOANDISBURSE')" >
            <label for="radiodisburse"><?php echo $lablearray['1040']; ?></label>
        </span>
        <span>
            <input type="radio" id="radiodisbursed" name="radiosclientloan" value="LOANDISBURSED" onclick="newPage('LOANDISBURSED')" >
            <label for="radiodisbursed"><?php echo $lablearray['1041']; ?></label>
        </span>
        <span>
            <input type="radio" id="radiorefinance" name="radiosclientloan" value="REFINANCE" onclick="newPage('REFINANCE')" >
            <label for="radiorefinance"><?php echo $lablearray['1474']; ?></label>
        </span>
        <span>
            <input type="radio" id="radiowriteoff" name="radiosclientloan" value="WRITEOFF" onclick="newPage('WRITEOFF')" >
            <label for="radiowriteoff"><?php echo $lablearray['176']; ?></label>
        </span>
        <span>
            <input type="radio" id="radioreschedule" name="radiosclientloan" value="RESCHEDULE" onclick="newPage('RESCHEDULE')" >
            <label for="radioreschedule"><?php echo $lablearray['1677']; ?></label>
        </span>
        <button class="btn" id="btnSearch" style="float:right;"  type="button" value="<?php echo $lablearray['21']; ?>"><?php echo $lablearray['21']; ?></button>
    </div>
    <div id='clientcat' ><?php echo Common::clientOptions("L"); ?></div>
    <span class="indicator_small clientcat-item " id='div_name'> </span>
    
    
    <p id='status' align="center" ></p>
    <div id="tab-example2"></div>
    <div id="tab-example">

        <div id="LoanApptabs" style="width:100%; height:50px;padding:0px;float:left;margin:0px;"></div>

        <div id="griddata" class="sidepanel"></div>

        <div id="tab1" class="tab">          

            <input id="action" name="action" type="hidden" value="data">
            <input id="theid" name="theid" type="hidden" value="">
            
            <div class="input-loan-terms">
                <span><?php echo $lablearray['1093']; ?><br><input id="client_idno" name="client_idno" value=""  type="hidden"> <input type="text" id="client_idno2" name="client_idno2" value="" disabled="disabled"></span><span><?php echo $lablearray['316']; ?><br><?php echo DrawComboFromArray($operators, 'BRANCHCODE', '') ?></span>
                <span><?php echo $lablearray['1096']; ?><br><?php echo DrawComboFromArray(array(), 'product_prodid', '', 'LOANPROD', '', ''); ?></span>
                <span><?php echo $lablearray['1097']; ?><br><input type="text" id="loan_number" name="loan_number" value="N/A" disabled></span>
                <span><?php echo $lablearray['1098']; ?><br><input type="us-date"   id='startDate' name='startDate' constraints="{datePattern:'<?php echo Common::convertDateJSFormat() ?>', strict:true}"></span>
                <span><?php echo $lablearray['271']; ?><br><input  id="lamount" name="lamount" value="0" ></span>
                <span><?php echo $lablearray['1100']; ?><br><input  id="intrate" name="intrate" value="1"></span>
                <span><?php echo $lablearray['1103']; ?><br><?php echo DrawComboFromArray(array(), 'INSTYPE', 'M', 'INSTYPE', '', ''); ?></span>
                <span><?php echo $lablearray['1102']; ?><br><?php echo DrawComboFromArray(array(), 'INTTYPE', 'FR', 'INTTYPE', '', ''); ?></span>
                <span><?php echo $lablearray['1101']; ?><br><input  id="no_of_inst" name="no_of_inst" value="1" style="width:40px;"></span>
                <span><?php echo $lablearray['1168']; ?><br><?php echo DrawComboFromArray($donors, 'donor_code', '', 'combo', 'FUNDCODE'); ?></span>
                <span><?php echo $lablearray['1107']; ?><br><?php echo DrawComboFromArray($funds, 'fund_code', '', 'combo', 'FUNDCODE'); ?></span>
                <span><input name="annualnterestRate" id="annualnterestRate" type="checkbox" value="N" checked> <?php echo $lablearray['1742']; ?></span>
                                   
                <!--
                    <tr>
                        <td>&nbsp;<?php echo $lablearray['1104']; ?><br><input id="grace" name="grace" value="0" class="w2field" disabled></td>
                        <td>&nbsp;<?php echo $lablearray['1105']; ?><br><input  id="comm" name="comm" value="0" class="w2field" disabled></td>
                        <td>&nbsp;<?php echo $lablearray['1106']; ?><br><input  id="principlalastinstallment" name="principlalastinstallment" value="0" disabled></td>
                        <td>&nbsp;<?php echo $lablearray['1107']; ?><br><?php echo DrawComboFromArray($funds, 'fund_code', '', 'combo', 'FUNDCODE'); ?></td>
                    </tr>

                    <tr>
                        <td>&nbsp;<?php echo $lablearray['1108']; ?><br><?php echo DrawComboFromArray($loancat, 'loan_udf1', '', 'combo', 'CAT1'); ?></td>
                        <td>&nbsp;<?php echo $lablearray['1109']; ?><br><?php echo DrawComboFromArray($loancat, 'loan_udf2', '', 'combo', 'CAT2'); ?></td>
                        <td>&nbsp;<?php echo $lablearray['1110']; ?><br><?php echo DrawComboFromArray($loancat, 'loan_udf3', '', 'combo', 'CAT3'); ?></td>
                        <td><?php echo $lablearray['1167']; ?><br><input type="us-date" id="freezedate" name="freezedate"></td>
                    </tr>
                -->                            
                <div id="gridmem"></div>
            </div>

            <fieldset style='display:none;'>
                <legend><?php echo $lablearray['1113']; ?></legend>
                <table width="95%" border="0" cellpadding="0">
                    <tr>
                        <td >
                            <p><input name="intgrace" id="intgrace" type="checkbox" value="Y" />&nbsp;<?php echo $lablearray['1127']; ?>
                            <p><input name="gracecompint" id="gracecompint" type="checkbox" value="Y" />&nbsp; <?php echo $lablearray['1128']; ?>
                            <p><input name="insintgrace" id="insintgrace" type="checkbox" value="Y" />&nbsp; <?php echo $lablearray['1129']; ?>
                            <p><input name="intpaidatdisbursement" id="intpaidatdisbursement" type="checkbox" value="Y" />&nbsp; <?php echo $lablearray['1130']; ?>
                            <p><input name="adjusttononworkingday" id="adjusttononworkingday" type="checkbox" value="Y" />&nbsp; <?php echo $lablearray['1158']; ?>
                        </td >	
                        <td>
                            <p><input name="allintpaidfirstinstallment" id="allintpaidfirstinstallment" type="checkbox" value="Y" />&nbsp; <?php echo $lablearray['1131']; ?>
                            <p><input name="loan_intindays" id="loan_intindays" type="checkbox" value="Y" />&nbsp; <?php echo $lablearray['1132']; ?>
                            <p><input name="loan_checkNonWorkingDays" id="loan_checkNonWorkingDays" type="checkbox" value="Y" />&nbsp; <?php echo $lablearray['1133']; ?>				
                            <p align="right"><input name="chkupdateloan" id="chkupdateloan" type="checkbox" value="Y"  disabled="disabled"/>&nbsp;<?php echo $lablearray['1169']; ?></p>
                        </td >

                    </tr>

                </table>

            </fieldset>
        </div>
</form>


<form action="#"  id="frmLoanapp2" name="frmLoanapp2" style="z-index:9999;">
    <div id="tab2" class="tab">

        <table width="100%" border="0" cellpadding="2">
            <tr>
                <td align="center" valign="top">


                    <table width="80%" border="0" cellpadding="2">
                        <tr>
                            <td>

                                <?php echo $lablearray['1143']; ?><br>
                                <input name="txtduedate" type="text"  type="us-date"  constraints="{datePattern:'<?php echo Common::convertDateJSFormat() ?>', strict:true}">
                            </td>
                            <td>

                                <?php echo $lablearray['1144']; ?><br>
                                <input name="txtdueprincipal" type="text" />
                            </td>
                            <td>

                                <?php echo $lablearray['1145']; ?><br>
                                <input name="txtdueinterest" type="text" />
                            </td>

                            <td>

                                <?php echo $lablearray['1105']; ?><br>
                                <input name="txtduecommission" type="text" />
                            </td>
                            <td  valign="bottom">
                                <button class="btn" name=""  type="button" ><?php echo $lablearray['730']; ?></button>
                            </td>
                        </tr>
                    </table>

                </td>
            </tr>
            <tr>
                <td align="center" style="height:200px;" valign="top"><div id='grdgrpMembers' style="width:97%;height:300px;margin:0px;padding:0px;clear:both;display:block;"></div>
                </td>
            </tr>
            <tr>
                <td align="center"  valign="middle"><button class="btn" name=""  type="button" id="btnRoundUp"><?php echo $lablearray['1140']; ?></button></td>
            </tr>
            <tr>
                <td align="center"  valign="top">

                    <table width="80%" border="0" cellpadding="2">
                        <tr>
                            <td >
                                <?php echo $lablearray['896']; ?>
                            </td>
                            <td>


                                <input name="txttotdueprincipal" type="text" />
                            </td>
                            <td>


                                <input name="txttotdueinterest" type="text" />
                            </td>
                            <td>


                                <input name="txttotduecommission" type="text" />
                            </td>

                            <td>


                                <input name="txttottotal" type="text" />
                            </td>
                            <td  valign="bottom">

                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td align="center"  valign="middle"><?php echo $lablearray['1147']; ?><input name="txtdate" type="text" value="1"  maxlength="2"  /><?php echo $lablearray['1148']; ?><button class="btn" name="Go"  type="button"   id="btnsearch"><?php echo $lablearray['1158']; ?></button>


                </td>
            </tr>
        </table>
        <?php echo printOptions(); ?>		
    </div>
</form>
<form   id="frmLoanapp3" name="frmLoanapp3">
    <input id="theid" name="theid" type="hidden" value="" >
    <div id="wrapper" style="text-align:center"><div id="toppanel" style="display:none;width:50%;border-radius: 10px;padding:10px;background-color: lightgrey;border: 1px solid #CCCCCC;position: absolute; top: 40%; left: 10%; background-color:#FFFFFF; box-shadow: 10px 10px 5px #888888;"></div></div>  
    <div id="tab3" class="tab">
        
            <table width="100%" border="0" cellpadding="2">
                <tr>
                    <td><?php echo $lablearray['1150']; ?>&nbsp;<input type="text" id="txtclientcode1" name="txtclientcode1" value=""><button class="btn" id="btnSearch" style="align:right;margin-left:50px;"  type="button" value="<?php echo $lablearray['21']; ?>" onclick="openClients('GUARANTOR1')"><?php echo $lablearray['21']; ?></button></td>
                    <td>&nbsp;</td>
                </tr>
                <tr>
                    <td><?php echo $lablearray['1151']; ?>&nbsp;<input type="text" id="txtclientcode2" name="txtclientcode2" value="" ><button class="btn" id="btnSearch" style="align:right;margin-left:50px;"  type="button" value="<?php echo $lablearray['21']; ?>" onclick="openClients('GUARANTOR2')"><?php echo $lablearray['21']; ?></button></td>
                    <td>&nbsp;</td>
                </tr>
                <tr>
                    <td><?php echo $lablearray['1152']; ?>&nbsp;<input type="text" id="txtclientcode3" name="txtclientcode3" value="" ><button class="btn" id="btnSearch" style="align:right;margin-left:50px;"  type="button" value="<?php echo $lablearray['21']; ?>" onclick="openClients('GUARANTOR3')"><?php echo $lablearray['21']; ?></button></td>
                    <td>&nbsp;</td>
                </tr>
            </table>
       
       
            <table width="100%" border="0" cellpadding="2">
                <tr>
                    <td><?php echo $lablearray['1153']; ?>&nbsp;<input type="text" id="collateral_description1" name="collateral_description1" value="N/A"></td>
                    <td><?php echo $lablearray['1157']; ?><input type="text" id="collateral_value1" name="collateral_value1" value="0"></td>
                </tr>
                <tr>
                    <td><?php echo $lablearray['1154']; ?>&nbsp;<input type="text" id="collateral_description2" name="collateral_description2" value="N/A"></td>
                    <td><?php echo $lablearray['1157']; ?><input type="text" id="collateral_value2" name="collateral_value2" value="0"></td>
                </tr>
                <tr>
                    <td><?php echo $lablearray['1155']; ?>&nbsp;<input type="text" id="collateral_description3" name="collateral_description3" value="N/A"></td>
                    <td><?php echo $lablearray['1157']; ?><input type="text" id="collateral_value3" name="collateral_value3" value="0"></td>
                </tr>
                <tr>
                    <td><?php echo $lablearray['1156']; ?>&nbsp;<input type="text" id="collateral_description4" name="collateral_description4" value="N/A"></td>
                    <td><?php echo $lablearray['1157']; ?><input type="text" id="collateral_value4" name="collateral_value4" value="0"></td>
                </tr>              
            </table>        
            <?php echo printOptions(); ?></div>
            
    </div>

</form>
<form action="#"  id="frmLoanapp4" name="frmLoanapp2" style="z-index:9999;">
    <div id="tab4" class="tab">

    </div>
</form>
<p align="center"><button class="btn" name="Go" value="Go" type="button" onClick="CloseDialog(vFloatingPane.id);"  id="btnscancel"><?php echo $lablearray['300']; ?></button><button class="btn" name="btnBack"  type="button"  value="btnBack"  id="btnBack"><?php echo $lablearray['1161']; ?></button><button class="btn" name="btnNext"  type="button" value="btnNext"  id="btnNext"><?php echo $lablearray['244']; ?></button></p>
</div>

</div>
<script type="text/javascript">

// remove tab
    $().w2destroy('LoanApptabs');
    $(document).ready(function () {



        //w2utils.settings['date_format'] = '<?php echo SETTING_DATE_FORMAT; ?>';
        //$('input[type=eu-date]').w2field('date',{ blocked : [ '4/14/2011', '4/15/2011' ]});
        // $('input[type=eu-date]').w2field('date', {format: 'd/m/yyyy'});
        var config = {
            tabs: {
                name: 'LoanApptabs',
                active: 'tab1',
                tabs: [
                    {id: 'tab1', caption: '<?php echo $lablearray['1113']; ?>'},
                    {id: 'tab2', caption: '<?php echo $lablearray['1138']; ?>'},
                    {id: 'tab3', caption: '<?php echo $lablearray['1115']; ?>'},
                    {id: 'tab4', caption: '<?php echo $lablearray['1677']; ?>'}
                ],
                onClick: function (event) {
                    $('.tab').hide();
                    $('#' + event.target).show();
                    if (event.target == 'tab2') {
                        w2ui['grid_schedule'].refresh();
                    }
                    if (event.target == 'tab4') {
                        // w2ui['grid_schedule'].refresh();



                    }
                }
            }
        }

        $(function () {

            $('#LoanApptabs').w2tabs(config.tabs);
            $('#tab1').show();

        });

    });
</script>

<script type="text/javascript">

    $(document).ready(function () {

        htmlLoanApp = $("#tab-example").html();

        w2utils.date(new Date());
        $('input[type=us-date]').w2field('date', {format: '<?php echo SETTING_DATE_FORMAT ?>'});

        $(function () {
            $('#grace').w2field('int');
            $('#intrate').w2field('int');
            $('#lamount').w2field('int');
            $('#no_of_inst').w2field('int');
            $('#comm').w2field('int');
            $('#principlalastinstallment').w2field('int');
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
                {field: 'other', caption: "<?php echo $lablearray['1160']; ?>", size: '10%', editable: {type: 'float', }},
                {field: 'memid', caption: "<?php echo $lablearray['1159']; ?>", size: '10%'}
            ]
                    //
        });

        function roundNumber(num, decimals) {
            //return Number(Math.round(value*10)/10); //'e'+decimals)+'e-'+decimals);		
            //return Math.ceil(value/10)*10;

            var match = ('' + num).match(/(?:\.(\d+))?(?:[eE]([+-]?\d+))?$/);
            if (!match) {
                return 0;
            }
            nDecimals = Math.max(
                    0,
                    // Number of digits right of decimal point.
                            (match[1] ? match[1].length : 0)
                            // Adjust for scientific notation.
                            - (match[2] ? +match[2] : 0));


            switch (nDecimals) {

                case 1:
                    return Math.ceil(num / 10) * 10;
                    break;

                case 2:
                    return Math.round(num * 10) / 10;
                    break;

                case 3:
                    return Math.round(num * 100) / 100;
                    break;

                case 4:
                    return Math.round(num * 1000) / 1000;
                    break;

                case 5:
                    return Math.round(num * 10000) / 10000
                    break;


                case 6:
                    return Math.round(num * 100000) / 100000;
                    break;

                default:
                    return Math.ceil(num / 10) * 10;
                    break;

            }


        }
        $("#btnRoundUp").click(function () {

            var nTotalPrinc = 0;
            var nTotalInterest = 0;
            var nRoundTotalPrinc = 0;
            var nRoundTotalInterest = 0;
            var nCount = 0;
            var nInt = 0;
            var nPrinc = 0;

            $(w2ui['grid_schedule'].records).each(function (i, change) {

                nTotalPrinc = nTotalPrinc + change.principal;

                nTotalInterest = nTotalInterest + change.interest;

            });


            $(w2ui['grid_schedule'].records).each(function (i, change) {

                nInt = roundNumber(change.interest, 0);

                nPrinc = roundNumber(change.principal, 0);

                nRoundTotalPrinc = nRoundTotalPrinc + nPrinc;

                nRoundTotalInterest = nRoundTotalInterest + nInt;

                if (nRoundTotalInterest > nTotalInterest) {
                    nInt = nInt - (nRoundTotalInterest - nTotalInterest);
                    //nInt = new Number(nInt+'').toFixed(parseInt(3));
                    nInt = Math.round(nInt * 1000) / 1000;
                }

                w2ui['grid_schedule'].records[i]['principal'] = nPrinc;

                w2ui['grid_schedule'].records[i]['interest'] = nInt;


            });

            //alert(w2ui['grid_schedule'].records);
            w2ui['grid_schedule'].refresh();

        });

        $("#btnNext").click(function () {
            var pagedata;
            var pageinfo = JSON.stringify($("#frmLoanapp1,#frmLoanapp2,#frmLoanapp3").serializeArray());
            var data1 = JSON.stringify(JSON.parse('{"pageinfo":' + pageinfo + "}"));

            var vtab = w2ui['LoanApptabs'].active
            // check see if user has client to save details in tab 3
            if (vtab == 'tab3') {

                var gridinfo = JSON.stringify(w2ui['grid_schedule'].records);
                var data1 = JSON.parse('{"pageinfo":' + pageinfo + "}");
                var data2 = JSON.parse('{"gridinfo":' + gridinfo + "}");
                var object = $.extend({}, data1, data2)
                pagedata = JSON.stringify(object);

                

                // save details of loan
                showValues('frmLoanapp3', $('#theid').val(), $("#action").val(), pagedata, 'addedit.php').done(function () {

               //     $('#client_idno').val('');
               //     $('#client_idno2').val('');
                    //  $('#intrate').val('');
                    $('#lamount').val('')
                    //   $('#grace').val('0.0'')
                    $('#principlalastinstallment').val('0.0')                 
                    $('#tab3').hide();
                    w2ui['LoanApptabs'].active = 'tab1';
                    $('#tab1').show();
                    w2ui['grid_schedule'].clear();
                    w2ui['grid_schedule'].refresh();

                });

                return;
            }



            switch (w2ui['LoanApptabs'].active) {
                case 'tab1':

//                    if (!validateForm("frmLoanapp1", "text", ":not('#theid'):not('#txtclientcode1'):not('#txtclientcode2'):not('#txtclientcode3')", "<?php echo $lablearray['1495']; ?>")) {
//                        return;
//                    } else {
//                        $("#status").text('');
//                    }
////                 
                    $('#tab1').hide();
                    $('#tab3').hide();
                    w2ui['LoanApptabs'].active = 'tab2';
                    $('#tab2').show();

                    $("#btnNext").html("<?php echo $lablearray['244']; ?>");
                    if ($('#action').val() != 'edit' || $('#chkupdateloan').is(':checked')) {
                   
                        showValues('frmLoanapp1', $('#theid').val(), 'add', data1, 'addedit.php').done(function () {

                            w2ui['grid_schedule'].clear();
                            w2ui['grid_schedule'].refresh();
                            w2ui['grid_schedule'].add(jsonObj);
                            w2ui['grid_schedule'].refresh();
                            w2ui['grid_schedule'].stateSave();

                        });
                    }

                    break;

                case 'tab2':

                    $('#tab1').hide();
                    $('#tab2').hide();
                    w2ui['LoanApptabs'].active = 'tab3';
                    $("#action").val("add");
                    $('#tab3').show();

//                    if ($('#action').val() != 'edit' || $('#chkupdateloan').is(':checked')) {
//                        showValues('frmLoanapp1', $('#theid').val(), $('#action').val(), '', 'load.php').done(function () {
//                            w2ui['grid_schedule'].clear();
//                            w2ui['grid_schedule'].add(jsonObj);
//                            w2ui['grid_schedule'].refresh();
//                            w2ui['grid_schedule'].stateSave();
//                        });
//
//                    }

                    $("#btnNext").html("<?php echo $lablearray['20']; ?>");


                    break;
                case 'tab3':
                    break;

                case 'tab4':
                    break;

                default:
                    break;
            }
            $('#griddata').hide('slide', {direction: 'left'}, 1000);
            w2ui['LoanApptabs'].refresh();

        });

        $("#btnBack").click(function () {

            switch (w2ui['LoanApptabs'].active) {
                case 'tab2':

                    w2ui['LoanApptabs'].active = 'tab1';

                    TXTPAGE = selectClientType();

                    if (TXTPAGE == 'GRPLOANS') {
                        $("#griddata").show();
                    }

                    $('#tab2').hide();
                    $('#tab3').hide();
                    $('#tab1').show();
                    $("#btnNext").html("<?php echo $lablearray['244']; ?>");
                    break;

                case 'tab3':

                    w2ui['LoanApptabs'].active = 'tab2';
                    w2ui['grid_schedule'].refresh();
                    $('#tab1').hide();
                    $('#tab3').hide();
                    $('#tab2').show();
                    $("#btnNext").html("<?php echo $lablearray['244']; ?>");
                    break;

                default:
                    break;
            }

            w2ui['LoanApptabs'].refresh();

        });

        //alert(w2ui['LoanApptabs'].active);
        //$('#tab2').show();
        $("#product_prodid").change(function () {

            showValues('frmLoanapp1', '', 'loadform', '', 'load.php', $("#product_prodid").val());

        });
        $("#product_prodid").trigger("change");

    });


</script>
</BODY>
</HTML>
