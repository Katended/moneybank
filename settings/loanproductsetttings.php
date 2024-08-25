<?php
require_once('../includes/application_top.php');
require_once('../includes/classes/common.php');


spl_autoload_register(function ($class_name) {
    include '../includes/classes/' . $class_name . '.php';
});


$_parent = basename(__FILE__);
$code_array = unserialize($_SESSION['_CAPTCHA']['config']); //['code'];
// if the user is not logged on, redirect them to the login page
if (AuthenticateAccess('LOGIN') == 0) {
    //tep_redirect(tep_href_link(FILENAME_DEFAULT));
    //tep_redirect(tep_href_link(FILENAME_LOGIN));
}

//session_start();
// here you can perform all the checks you need on the user submited variables
$_SESSION['security_number'] = rand(10000, 99999);
$products = getProducts();

$results_query = tep_db_query("SELECT branch_code, licence_organisationname FROM " . TABLE_LICENCE . " WHERE licence_build='" . $_SESSION['licence_build'] . "'");

while ($cats = tep_db_fetch_array($results_query)) {
    $operators[$cats['branch_code']] = $cats['licence_organisationname'];
}


getlables("316,1458,1580,1442,1659,1578,1484,1513,1514,1421,1322,1420,1171,9,1097,1460,1096,1176,306,1419,1136,1409,1410,1102,1100,1101,1103,1411,1179,1251,1412,1413,1414,1415,1416,920");
?>
<link href="includes/javascript/w2ui-1.4.3.css" rel="stylesheet" type="text/css"/>
<script src="includes/javascript/w2ui-1.4.3.min.js" type="text/javascript"></script>
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
        padding:5px;
        position: relative;
        display:none;

    }


    .loader{
        margin:100px;
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
<?php require('../'.DIR_WS_INCLUDES . 'pageheader.php'); ?>
<div id="tab-example" style="margin:0px;">
    <p align="center"> <?php echo $lablearray['1096']; ?>&nbsp;<?php echo DrawComboFromArray($products, 'product_prodid', '', 'combo', '', ''); ?> <?php echo $lablearray['316']; ?> <?php echo DrawComboFromArray('branch_code', 'branch_code', '', 'operatorbranches', '', ''); ?></p>
    <div id="LoanApptabs" style="width:100%; height: 20px;padding:2px;"></div>
    <div id="toppanel" class="sidepanel">			
    </div>

    <p id='messages' align="center" style="color:#FF0000;margin:0px;"></p>

    <div id="tab1" class="tab"> 

        <form   id="frmloanproductsettings1" name="frmloanproductsettings1">

            <table  border="0" cellpadding="0">
                <tr>
                    <td colspan="2">				
                        <table border="0" cellpadding="0">
                            <tr>
                                <td valign='top' >
                                    <table border="0" cellpadding="0">
                                        <tr>
                                            <td></td>						
                                            <td align="right">Activate</td>
                                        </tr>

                                        <tr>
                                            <td><?php echo $lablearray['1409']; ?><br><input  type="text" id="MAXIMUM_LOAN_AMOUNT" name="MAXIMUM_LOAN_AMOUNT" value='0'></td><td align="right"> <input name="MAXIMUM_LOAN_AMOUNT_ACTIVATED" id="MAXIMUM_LOAN_AMOUNT_ACTIVATED" type="checkbox" value="1" /></td>						

                                        </tr>
                                        <tr>
                                            <td><?php echo $lablearray['1410']; ?><br><input type="text" id="MINIMUM_LOAN_AMOUNT" name="MINIMUM_LOAN_AMOUNT" value='0'></td><td>&nbsp;</td>						

                                        </tr>

                                        <tr>
                                            <td><?php echo $lablearray['1100']; ?><br><input type="text" id="INTEREST_RATE" name="INTEREST_RATE" value='0'></td><td align="right"> <input name="INTEREST_RATE_ACTIVATED" id="INTEREST_RATE_ACTIVATED" type="checkbox" value="1" /></td>						
                                        </tr>			 
                                        <tr>
                                            <td><?php echo $lablearray['1101']; ?><br><input type="text" id="NUMBER_OF_INSTALLMENTS" name="NUMBER_OF_INSTALLMENTS" value='0'/></td><td align="right"> <input name="NUMBER_OF_INSTALLMENTS_ACTIVATED" id="NUMBER_OF_INSTALLMENTS_ACTIVATED" type="checkbox" value="1" /></td>						

                                        </tr>

                                        <tr>
                                            <td><?php echo $lablearray['1103']; ?><br><?php echo DrawComboFromArray(array(), 'INSTALLMENT_TYPE', '', 'INSTYPE', '', 'INSTYPE'); ?></td><td align="right"><input name="INSTALLMENT_TYPE_ACTIVATED" id="INSTALLMENT_TYPE_ACTIVATED" type="checkbox" value="1" /></td>						

                                        </tr>

                                        <tr>
                                            <td><?php echo $lablearray['1102']; ?><br><?php echo DrawComboFromArray(array(), 'INTEREST_TYPE', '', 'INTTYPE', '', 'INTTYPE'); ?></td><td align="right"><input name="INTEREST_TYPE_ACTIVATED" id="INTEREST_TYPE_ACTIVATED" type="checkbox" value="1" /></td>						

                                        </tr>

                                        <tr>
                                            <td colspan="2">

                                                <fieldset>
                                                    <p> <?php echo $lablearray['1411']; ?><br><input type="text" id="SAVINGS_GUARANTEE_AMOUNT" name="SAVINGS_GUARANTEE_AMOUNT" value="0" maxlength="3"></p><p> <?php echo $lablearray['1179']; ?><br><input type="text" id="SAVINGS_GUARANTEE_AMOUNT" name="SAVINGS_GUARANTEE_AMOUNT" value="0"></p> 

                                                    <p align='right'><input name="SAVINGS_GUARANTEE_AMOUNT_ACTIVATED" id="SAVINGS_GUARANTEE_AMOUNT_ACTIVATED" type="checkbox" value="1" /></p>
                                                </fieldset>



                                            </td>						

                                        </tr>
                                         <tr>
                                            <td colspan="2"><?php echo $lablearray['1251']; ?><br><?php echo Common::DrawComboFromArray(array(), 'CURRENCIES_ID', '', 'CURRENCIES', '', 'CURRENCIES'); ?></td>						

                                        </tr>
                                        <tr>
                                            <td ><?php echo $lablearray['1442']; ?><br><input type="text" id="SERVICE_FEE" name="SERVICE_FEE" value="0">%</td>						
                                            <td ></td>
                                        </tr>

                                        <tr>
                                            <td ><?php echo $lablearray['1460']; ?><br><?php echo Common::DrawComboFromArray(array(),'PAY_PRIORITY','','PAY_PRIORITY','','','frmloanproductsettings1');?></td>						
                                            <td ></td>
                                        </tr>
                                        <tr>
                                            <td ><?php echo $lablearray['1484']; ?><br><?php echo Common::DrawComboFromArray(array(),'REF_PRIORITY','','REF_PRIORITY','','','frmloanproductsettings1');?></td>						
                                            <td ></td>
                                        </tr>
                                        
                                    </table>
                                </td >

                                <td valign='top' > 
                                    <fieldset >
                                        <legend><?php echo $lablearray['1513']; ?></legend>
                                        <table  border="0" cellpadding=1 style="float:left;" cellspacing='3'>


                                            <tr>
                                                <td><?php echo Common::DrawComboFromArray(array(),'SAV_AT_REPAY','','SAV_AT_REPAY','','','');?> <?php echo $lablearray['1514']; ?> <input name="SAVING_AT_LOAN_REPAY_AMT" id="SAVING_AT_LOAN_REPAY_AMT" type="numeric" value="0" /></td>						
                                            </tr>
                                        </table>
                                    </fieldset > 
                                    <fieldset >
                                        <legend><?php echo $lablearray['1412']; ?></legend>
                                        <table  border="0" cellpadding=1 style="float:left;" cellspacing='5'>


                                            <tr>
                                                <td><input name="PRI_IN_ARR" id="PRI_IN_ARR" type="checkbox" value="1" /> <?php echo $lablearray['1413']; ?></td>						

                                            </tr>


                                            <tr>
                                                <td><input name="INT_IN_ARR" id="INT_IN_ARR" type="checkbox" value="1" /> <?php echo $lablearray['1414']; ?></td>						

                                            </tr>

                                            <tr>
                                                <td><input name="COM_IN_ARR" id="COM_IN_ARR" type="checkbox" value="1" /> <?php echo $lablearray['1415']; ?></td>						

                                            </tr>

                                            <tr>
                                                <td><input name="PEN_IN_ARR" id="PEN_IN_ARR" type="checkbox" value="1" /> <?php echo $lablearray['1416']; ?></td>						

                                            </tr>



                                        </table>
                                    </fieldset >  
                                    <fieldset >
                                        <table cellpading='2' cellspacing='3'>
                                            <TR>
                                            <td><?php echo $lablearray['1421']; ?><br><input name="INT_DAYS" id="INT_DAYS" type="text" value="365" /></td>
                                            <td><?php echo $lablearray['1420']; ?><br><input name="INT_WEEKS" id="INT_WEEKS" type="text" value="52" /></td>
                                            </tr>
                                            <TR>
                                            <td colspan='2'><input name="CHARGE_INT" id="CHARGE_INT" type="checkbox" value="1" /> <?php echo $lablearray['1419']; ?></td>
                                            </TR>
                                            <TR>
                                            <td colspan='2'><input name="RECALC_INT" id="RECALC_INT" type="checkbox" value="1" /> <?php echo $lablearray['1322']; ?></td>
                                            </TR>
                                            <TR>
                                            <td colspan='2'><input name="NO_INT" id="NO_INT" type="checkbox" value="1" /> <?php echo $lablearray['1458']; ?></td>
                                            </TR>
                                            <TR>
                                            <td colspan='2'><input name="LOAN_COM_FROM_SAV" id="LOAN_COM_FROM_SAV" type="checkbox" value="1" /> <?php echo $lablearray['1578']; ?></td>
                                            </TR>
                                             <TR>
                                            <td colspan='2'><input name="PULL_DUES_AFTER_PREPAYMENTS" id="PULL_DUES_AFTER_PREPAYMENTS" type="checkbox" value="1" /> <?php echo $lablearray['1580']; ?></td>
                                            </TR>
                                             <TR>
                                            <td colspan='2'><input name="ALLOW_OVERPAYMENTS" id="ALLOW_OVERPAYMENTS" type="checkbox" value="1" /> <?php echo $lablearray['1659']; ?></td>
                                            </TR>
                                        </table>    
                                    </fieldset>
                                    </form>
                                    <form   id="frmloanproductsettings2" name="frmloanproductsettings2">
                                        <fieldset >
                                            <?php getlables("1176,1417,730,1177,920,1418"); ?>
                                            <legend><?php echo $lablearray['1176']; ?></legend>
                                            <table width="100%" border="0" cellpadding="0">
                                                <tr>
                                                    <td><?php echo $lablearray['920']; ?></td>
                                                    <td >&nbsp;<?php echo $lablearray['1417']; ?></td>
                                                    <td> <?php echo $lablearray['1179']; ?></td>
                                                    <td >&nbsp;<?php echo $lablearray['1418']; ?></td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <?php echo DrawComboFromArray(array(), 'fees_id', '', 'FEES', '', 'FEES'); ?> 
                                                    </td>
                                                    <td >
                                                        <?php echo DrawComboFromArray(array(), 'LOANPROCESSLEVELS', '', 'LOANPROCESSLEVELS', '', 'LOANPROCESSLEVELS'); ?> 
                                                    </td>
                                                    <td  ><input  type="text" id="SAVINGS_GUARANTEE_AMOUNT" name="SAVINGS_GUARANTEE_AMOUNT" value="0"></td>
                                                    <td  ><input type="text" id="SAVINGS_GUARANTEE_AMOUNT_PER" name="SAVINGS_GUARANTEE_AMOUNT_PER" value="0" size='4'><button class="btn" name="btn1177"  type="button"   id="btn1177"><?php echo $lablearray['1177']; ?></button></td>
                                                </tr>
                                                <tr>
                                                    <td colspan="4" id='feesdiv'></td>					
                                                </tr>
                                            </table>

                                        </fieldset>
                                    </form>

                                </td></tr>
                        </table>

                    </td>


                </tr>

            </table>
            <div id='feesconfigdiv'></div>
            
            <?php getlables("300,20"); ?>
            <p align="right"><button class="btn" name="Go"  type="button" onClick="CloseDialog(vFloatingPane.id);"  id="btnscancel"><?php echo $lablearray['300']; ?></button><button class="btn" name="btnSave"  type="button"   id="btnSave"><?php echo $lablearray['20']; ?></button></p>
    </div>
    <?php getlables("1171,9,1097,1096,1176,306,1136,730,20,270,886,885,761"); ?>

    <form  id="frmloanproductsettings3" name="frmloanproductsettings3">
        <div id="tab2" class="tab">

            <table width="100%" border="0" cellpadding="2">
                <tr>
                    <td align="center" valign="top">	

                        <table width="100%" border="0" cellpadding="2">
                            <tr>
                                <td colspan='3' align='center'><?php echo $lablearray['270']; ?><?php echo Common::DrawComboFromArray(array(), 'LOANPRODGLACC', '', 'LOANPRODGLACC', '', 'LOANPRODGLACC'); ?></td>			
                        
                               
                            </tr>
                            
                            <tr>
                                <td><?php echo $lablearray['886']; ?></td>			
                                <td><?php echo $lablearray['885']; ?></td>
                                <td><?php echo $lablearray['761']; ?></td>
                               
                               
                            </tr>		  
                            <tr>
                                
                                <td><?php echo Common::DrawComboFromArray(array(), 'COACOMBOIND', '', 'COACOMBO', '', 'COACOMBO'); ?></td>
                                <td><?php echo Common::DrawComboFromArray(array(), 'COACOMBOGRP', '', 'COACOMBO', '', 'COACOMBO'); ?></td>	
                                <td ><?php echo Common::DrawComboFromArray(array(),'COAGENERAL', '', 'COACOMBO', '', 'COACOMBO'); ?></td>
                                
                            </tr>
                            <tr>
                            <td colspan='3' align="center"><div id='glaccounts'></div><button class="btn" name="btn20"  type="button"   id="btn20"><?php echo $lablearray['20']; ?></button></td>	
                            </tr>
                        </table>
                        
                    </td>
                </tr>


            </table>

        </div>
        <?php getlables("1042,1174,172,300,1161,244,20"); ?>
        
    </form>

    <script type="text/javascript">

        // remove tab
        $().w2destroy('LoanApptabs');
        $(document).ready(function () {
            //w2utils.settings['date_format'] = '<?php echo SETTING_DATE_FORMAT; ?>';
            //$('input[type=eu-date]').w2field('date',{ blocked : [ '4/14/2011', '4/15/2011' ]});
            $('input[type=eu-date]').w2field('date', {format: 'd/m/yyyy'});
            var config = {
                tabs: {
                    name: 'LoanApptabs',
                    active: 'tab1',
                    tabs: [
                        {id: 'tab1', caption: '<?php echo $lablearray['172']; ?>'},
                        {id: 'tab2', caption: '<?php echo $lablearray['1174']; ?>'}

                    ],
                    onClick: function (event) {
                        $('.tab').hide();
                        $('#' + event.target).show();
                        if (event.target == 'tab2') {
                            showValues('frmloanproductsettings3', 'glaccounts', 'search', 'LOANPRODGLACC', 'load.php', $('#product_prodid').val());
                        } else if (event.target == 'tab1') {
                            showValues('frmloanproductsettings2', 'feesdiv', 'search', 'FEES', 'load.php', $('#product_prodid').val());
                        }

                    }
                }
            }



            //w2ui['tabs'].destroy();

            $(function () {

                $('#LoanApptabs').w2tabs(config.tabs);
                $('#tab1').show();

            });

            //showValues('frmloanproductsettings1','feesconfigdiv','loadelement','','load.php');

        });
    </script>

    <script type="text/javascript">

        $(document).ready(function () {

            $("#btnSave").click(function () {

                var pageinfo = JSON.stringify($("#frmloanproductsettings1, #branch_code").serializeArray());
                //var data1 = JSON.parse('{"pageinfo":'+pageinfo+"}");	
                var data1 = JSON.stringify(JSON.parse('{"pageinfo":' + pageinfo + "}"));
                //frm,theid,action,pageparams,urlpage,keyparam
                showValues('frmloanproductsettings1', $('#product_prodid').val(), $("#action").val(), data1, 'addedit.php');
                w2ui['LoanApptabs'].refresh();

            });

            $("#btn1177").click(function () {

                var pageinfo = JSON.stringify($("#frmloanproductsettings2, #branch_code").serializeArray());
                //var data1 = JSON.parse('{"pageinfo":'+pageinfo+"}");	
                var data1 = JSON.stringify(JSON.parse('{"pageinfo":' + pageinfo + "}"));
                showValues('frmloanproductsettings2', $('#product_prodid').val(), 'add', data1).done(function () {
                    //w2ui['LoanApptabs'].refresh();
                    //showValues('frmloanproductsettings2','feesdiv','search','FEES','load.php');

                });

            });

            $("#product_prodid").change(function () {

                showValues('frmloanproductsettings1', '', 'loadform', '', 'load.php', $('#product_prodid').val()).done(function () {
                    showValues('frmloanproductsettings3', 'glaccounts', 'search', 'LOANPRODGLACC', 'load.php', $('#product_prodid').val());
                    // showValues('frmloanproductsettings2','feesdiv','search','FEES','load.php',$('#product_prodid').val());
                });

            });

            showValues('frmloanproductsettings1', '', 'loadform', '', 'load.php', $('#product_prodid').val()).done(function () {
                showValues('frmloanproductsettings2', 'feesdiv', 'search', 'FEES', 'load.php', $('#product_prodid').val());
            });



            showValues('frmloanproductsettings2', 'feesdiv', 'search', 'FEES', 'load.php', $('#product_prodid').val());
            //showValues('frmloanproductsettings2','feesdiv','search','FEES','load.php',$('#product_prodid').val());

            $("#btn20").click(function () {

                var pageinfo = JSON.stringify($("#frmloanproductsettings3, #branch_code").serializeArray());


                //var data1 = JSON.parse('{"pageinfo":'+pageinfo+"}");	
                var data1 = JSON.stringify(JSON.parse('{"pageinfo":' + pageinfo + "}"));
                showValues('frmloanproductsettings3', $('#product_prodid').val(), 'add', data1, 'addedit.php', $('#product_prodid').val()).done(function () {

                    showValues('frmloanproductsettings3', 'glaccounts', 'search', 'LOANPRODGLACC', 'load.php', $('#product_prodid').val());

                });

            });


        });
    </script>
</BODY>
</HTML>