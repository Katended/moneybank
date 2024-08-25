<?php
require_once('../includes/application_top.php');
require_once('../includes/classes/common.php');
?>
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
 <?php getlables("1594,1251,274,316"); ?> 
<?php require('../'.DIR_WS_INCLUDES . 'pageheader.php'); ?>
<div id="tab-example" style="margin:0px;">
    <p align="center"> <?php echo $lablearray['1096']; ?>&nbsp;<?php echo Common::DrawComboFromArray(array(), 'product_prodid', '', 'TPROD', '', ''); ?> <?php echo $lablearray['316']; ?> <?php echo DrawComboFromArray('branch_code', 'branch_code', '', 'operatorbranches', '', ''); ?></p>


    <div id="LoanApptabs" style="width:100%; height: 25px;padding:2px;"></div>
    <div id="toppanel" class="sidepanel">			
    </div>

    <p id='messages' align="center" style="color:#FF0000;margin:0px;"></p>

    <div id="tab1" class="tab"> 

        <form   id="frmtimedepositsettings1" name="frmtimedepositsettings1">

            <table  border="0" cellpadding="0">
                <tr>
                    <td colspan="2">				
                        <table border="0" cellpadding="0">
                            <tr>
                                <td valign='top' >
                                    <table border="0" cellpadding="0">
                                        

                                        <tr>
                                            <td colspan="2"><?php echo $lablearray['1594']; ?><br><input  type="text" id="INTEREST_RATE" name="INTEREST_RATE" value='0'> <input name="INTEREST_RATE_ACTIVATED" id="INTEREST_RATE_ACTIVATED" type="checkbox" value="1" /> <?php echo $lablearray['274']; ?></td>						

                                        </tr>      
                                         <tr>
                                        <td colspan="2"><?php echo $lablearray['1251']; ?><br><?php echo DrawComboFromArray(array(), 'CURRENCIES_ID', '', 'CURRENCIES', '', 'CURRENCIES'); ?></td>						
                                    </tr>
                                      
                                                                             
                                    
                                     

                                    </table>
                                  </form>

                                </td >
                                
                                <td valign='top' align="right"> 
                                    
                                      
                                    
                                   
                                
                                </td></tr></table>

                    </td>


                </tr>

            </table>
    <?php getlables("300,20"); ?>        
    <p align="right"><button class="btn" name="Go"  type="button" onClick="CloseDialog(vFloatingPane.id);"  id="btnscancel"><?php echo $lablearray['300']; ?></button><button class="btn" name="btnSave"  type="button"   id="btnSave"><?php echo $lablearray['20']; ?></button></p>

    </div>
    </FORM>
    <?php getlables("1171,9,1097,1096,1176,306,1136,730,20,270,886,761,885"); ?>

    <form  id="frmtimedepositsettings3" name="frmtimedepositsettings3">
        <div id="tab2" class="tab">

                        <table width="100%" border="0" cellpadding="2">
                            <tr>
                                <td colspan='4' align='center'><?php echo $lablearray['270']; ?> <?php echo DrawComboFromArray(array(), 'PRODGLTDACC', '', 'PRODGLTDACC', '', 'PRODGLTDACC'); ?></td>			
                              
                            </tr>
                            <tr>
                                <td><?php echo $lablearray['886']; ?></td>			
                                <td><?php echo $lablearray['885']; ?></td>
                                <td><?php echo $lablearray['761']; ?></td>
                                <td></td>
                            </tr>
                            
                            <tr>
                                
                                <td><?php echo DrawComboFromArray(array(), 'COACOMBOIND', '', 'COACOMBO', '', 'COACOMBO'); ?></td>
                                <td><?php echo DrawComboFromArray(array(), 'COACOMBOGRP', '', 'COACOMBO', '', 'COACOMBO'); ?></td>	
                                <td><?php echo Common::DrawComboFromArray(array(),'COAGENERAL', '', 'COACOMBO', '', 'COACOMBO'); ?></td>	
                                <td></td>
                            </tr>
                            
                             <tr>
                               
                                <td align="center" colspan="4"><button class="btn" name="btn20"  type="button"   id="btn20"><?php echo $lablearray['20']; ?></button></td>	
                            </tr>
                        </table>
                        <div id='glaccounts'></div>
                    

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
                            showValues('frmtimedepositsettings3', 'glaccounts', 'search', 'TDPRODGLACC', 'load.php', $('#product_prodid').val());
                        } 

                    }
                }
            }



            //w2ui['tabs'].destroy();

            $(function () {

                $('#LoanApptabs').w2tabs(config.tabs);
                $('#tab1').show();

            });

            //showValues('frmtimedepositsettings1','feesconfigdiv','loadelement','','load.php');

        });
    </script>

    <script type="text/javascript">

        $(document).ready(function () {

            $('input[type=us-date]').w2field('date',  { format: '<?php echo SETTING_DATE_FORMAT?>'})

            $("#btnSave").click(function () {

                var pageinfo = JSON.stringify($("#frmtimedepositsettings1 ,#branch_code").serializeArray());
                //var data1 = JSON.parse('{"pageinfo":'+pageinfo+"}");	
                var data1 = JSON.stringify(JSON.parse('{"pageinfo":' + pageinfo + "}"));
                //frm,theid,action,pageparams,urlpage,keyparam
                showValues('frmtimedepositsettings1', $('#product_prodid').val(), $("#action").val(), data1, 'addedit.php');
                w2ui['LoanApptabs'].refresh();

            });


            $("#product_prodid").change(function () {

                showValues('frmtimedepositsettings1', '', 'loadform', '', 'load.php', $('#product_prodid').val()).done(function () {
                    showValues('frmtimedepositsettings3', 'glaccounts', 'search', 'TDPRODGLACC', 'load.php', $('#product_prodid').val());
                    
                });

            });

            showValues('frmtimedepositsettings1', '', 'loadform', '', 'load.php', $('#product_prodid').val()).done(function () {
              
            });



            $("#btn20").click(function () {

                var pageinfo = JSON.stringify($("#frmtimedepositsettings3 ,#branch_code").serializeArray());


                //var data1 = JSON.parse('{"pageinfo":'+pageinfo+"}");	
                var data1 = JSON.stringify(JSON.parse('{"pageinfo":' + pageinfo + "}"));
                showValues('frmtimedepositsettings3', $('#product_prodid').val(), 'add', data1, 'addedit.php', $('#product_prodid').val()).done(function () {

                    showValues('frmtimedepositsettings3', 'glaccounts', 'search', 'TDPRODGLACC', 'load.php', $('#product_prodid').val());

                });

            });


        });
    </script>
</BODY>
</HTML>