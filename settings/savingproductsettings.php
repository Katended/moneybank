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


getlables("1428,274,1430,1429,1616,1421,1583,1322,1420,1171,9,1097,1572,1096,1427,1176,306,1419,1136,1424,1426,1425,1102,1100,1101,1103,1411,1179,1251,1412,1413,1414,1415,1416,920");
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
<?php require('../'.DIR_WS_INCLUDES . 'pageheader.php'); ?>
<div id="tab-example" style="margin:0px;">
    <p align="center"> <?php echo $lablearray['1096']; ?>&nbsp;<?php echo DrawComboFromArray($products, 'product_prodid', '', 'combo', '', ''); ?> <?php echo $lablearray['316']; ?> <?php echo DrawComboFromArray('branch_code', 'branch_code', '', 'operatorbranches', '', ''); ?></p>


    <div id="LoanApptabs" style="width:100%; height: 25px;padding:2px;"></div>
    <div id="toppanel" class="sidepanel">			
    </div>

    <p id='messages' align="center" style="color:#FF0000;margin:0px;"></p>

    <div id="tab1" class="tab"> 

        <form   id="frmsaveproductsettings1" name="frmsaveproductsettings1">

            <table  border="0" cellpadding="0">
                <tr>
                    <td colspan="2">				
                        <table border="0" cellpadding="0">
                            <tr>
                                <td valign='top' >
                                    <fieldset>
                                    <table border="0" cellpadding="0">
                                        <tr>
                                            <td></td>						
                                            <td align="right"><?php echo $lablearray['274']; ?></td>
                                        </tr>

                                        <tr>
                                            <td><?php echo $lablearray['1424']; ?><br><input  type="text" id="MINIMUM_SAV_BAL" name="MINIMUM_SAV_BAL" value='0'></td><td align="right"> <input name="MINIMUM_SAV_BAL_ACTIVATED" id="MINIMUM_SAV_BAL_ACTIVATED" type="checkbox" value="1" /></td>						

                                        </tr>
                                         <tr>
                                            <td><?php echo $lablearray['1426']; ?><br><input  type="text" id="MINIMUM_SAV_BAL_EARN" name="MINIMUM_SAV_BAL_EARN" value='0'></td><td align="right"> <input name="MINIMUM_SAV_BAL_EARN_ACTIVATED" id="MINIMUM_SAV_BAL_EARN_ACTIVATED" type="checkbox" value="1" /></td>						

                                        </tr>
                                        <tr>
                                            <td><?php echo $lablearray['1425']; ?><br><input type="text" id="SAV_INT_RATE" name="SAV_INT_RATE" value='0'></td><td>&nbsp;</td>						

                                        </tr>
                                        
                                         <tr>
                                            <td ><?php echo $lablearray['1583']; ?><br><input type="text" id="CHARGE_ON_WITHDRAW" name="CHARGE_ON_WITHDRAW" value="0.0">%</td>						
                                            <td ></td>
                                        </tr>
                                        
                                         <tr>
                                            <td><?php echo $lablearray['1427']; ?><br><input type="text" id="SAV_INT_PERIOD" name="SAV_INT_PERIOD" value='0'><?php echo $lablearray['1430']; ?></td><td>&nbsp;</td>						

                                        </tr>
                                        
                                        
                                    
                                        <tr>
                                            <td colspan="2"><?php echo $lablearray['1251']; ?><br><?php echo DrawComboFromArray(array(), 'CURRENCIES_ID', '', 'CURRENCIES', '', 'CURRENCIES'); ?></td>						
                                        </tr>

                                    </table>
                                    
                                        </fieldset>
                                  </form>

                                </td >
                                
                                <td valign='top' align="right"> 
                                    <fieldset>
                                      <table border="0" cellpadding="10" width="70%" >
                                        <tr>
                                            					
                                            <td > <?php echo $lablearray['1429']; ?><br>
                                    <input type="us-date" class="date" id="INT_START_DATE" name="INT_START_DATE" value=""></td>
                                        </tr>
                                        <tr>
                                            					
                                            <td>
                                                
                                                 <br><?php echo $lablearray['1428']; ?><br>
                                                <?php echo DrawComboFromArray(array(), 'INT_CAL_METHOD', '', 'SAVINTCAL', '', 'INT_CAL_METHOD'); ?>
                                
                                            </td>
                                        </tr>
                                        <tr>
                                            					
                                            <td align='left'>
                                              
                                               <input name="CLIENTCODE_IS_SAVACC" id="CLIENTCODE_IS_SAVACC" type="checkbox" value="1" /> <?php echo $lablearray['1572']; ?> 
                                
                                            </td>
                                        </tr>
                                        <tr>
                                            					
                                            <td align='left'>   
                                              <br><?php echo $lablearray['1616']; ?><br>
                                              <input type="text" id="PER_INT_TOPAY" name="PER_INT_TOPAY" value='0'>
                                            </td>
                                        </tr>
                                         </table>
                                    
                                   </fieldset>
                                
                                </td></tr></table>

                    </td>


                </tr>

            </table>
    <?php getlables("300,20"); ?>        
    <p align="right"><button class="btn" name="Go"  type="button" onClick="CloseDialog(vFloatingPane.id);"  id="btnscancel"><?php echo $lablearray['300']; ?></button><button class="btn" name="btnSave"  type="button"   id="btnSave"><?php echo $lablearray['20']; ?></button></p>

    </div>
    <?php getlables("1171,9,1097,1096,1176,306,1136,730,20,270,886,885"); ?>

    <form  id="frmsavproductsettings3" name="frmsavproductsettings3">
        <div id="tab2" class="tab">

            <table width="100%" border="0" cellpadding="2">
                <tr>
                    <td align="center" valign="top">	

                        <table width="100%" border="0" cellpadding="2">
                            <tr>
                                <td><?php echo $lablearray['270']; ?></td>			
                                <td><?php echo $lablearray['886']; ?></td>
                                <td><?php echo $lablearray['885']; ?></td>
                                <td></td>
                            </tr>		  
                            <tr>
                                <td><?php echo DrawComboFromArray(array(), 'SAVPRODGLACC', '', 'PRODGLSAV', '', 'SAVPRODGLACC'); ?></td>
                                <td><?php echo DrawComboFromArray(array(), 'COACOMBOIND', '', 'COACOMBO', '', 'COACOMBO'); ?></td>
                                <td><?php echo DrawComboFromArray(array(), 'COACOMBOGRP', '', 'COACOMBO', '', 'COACOMBO'); ?></td>	
                                <td align="right"><button class="btn" name="btn20"  type="button"   id="btn20"><?php echo $lablearray['20']; ?></button></td>	
                            </tr>		  
                        </table>
                        <div id='glaccounts'></div>
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
                            showValues('frmsavproductsettings3', 'glaccounts', 'search', 'SAVPRODGLACC', 'load.php', $('#product_prodid').val());
                        } 

                    }
                }
            }



            //w2ui['tabs'].destroy();

            $(function () {

                $('#LoanApptabs').w2tabs(config.tabs);
                $('#tab1').show();

            });

            //showValues('frmsaveproductsettings1','feesconfigdiv','loadelement','','load.php');

        });
    </script>

    <script type="text/javascript">

        $(document).ready(function () {

            $('input[type=us-date]').w2field('date',  { format: '<?php echo SETTING_DATE_FORMAT?>'})

            $("#btnSave").click(function () {

                var pageinfo = JSON.stringify($("#frmsaveproductsettings1 ,#branch_code").serializeArray());
                //var data1 = JSON.parse('{"pageinfo":'+pageinfo+"}");	
                var data1 = JSON.stringify(JSON.parse('{"pageinfo":' + pageinfo + "}"));
                //frm,theid,action,pageparams,urlpage,keyparam
                showValues('frmsaveproductsettings1', $('#product_prodid').val(), $("#action").val(), data1, 'addedit.php');
                w2ui['LoanApptabs'].refresh();

            });


            $("#product_prodid").change(function () {

                showValues('frmsaveproductsettings1', '', 'loadform', '', 'load.php', $('#product_prodid').val()).done(function () {
                    showValues('frmsavproductsettings3', 'glaccounts', 'search', 'SAVPRODGLACC', 'load.php', $('#product_prodid').val());
                    
                });

            });

            showValues('frmsaveproductsettings1', '', 'loadform', '', 'load.php', $('#product_prodid').val()).done(function () {
              
            });



            $("#btn20").click(function () {

                var pageinfo = JSON.stringify($("#frmsavproductsettings3,#branch_code").serializeArray());


                //var data1 = JSON.parse('{"pageinfo":'+pageinfo+"}");	
                var data1 = JSON.stringify(JSON.parse('{"pageinfo":' + pageinfo + "}"));
                showValues('frmsavproductsettings3', $('#product_prodid').val(), 'add', data1, 'addedit.php', $('#product_prodid').val()).done(function () {

                    showValues('frmsavproductsettings3', 'glaccounts', 'search', 'SAVPRODGLACC', 'load.php', $('#product_prodid').val());

                });

            });


        });
    </script>
</BODY>
</HTML>