<?php
require_once('../includes/application_top.php');
require_once("../simple-php-captcha-master/simple-php-captcha.php");
require_once('../includes/classes/common.php');
$_SESSION['captcha'] = simple_php_captcha(array(), 'TRANSFER');

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

$_SESSION['rpt'] = 'SAVINTRPT';
Common::getreportColumnList('SAVINTRPTS', $Conn);
getlables("1096");
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
<?php getlables("300,20,19,317,1433,316,1242,68,1243,1244,1245"); ?>
<form   id="frmcalcint" name="frmcalcint">
    <p id='status' align="center" style="color:#FF0000;margin:1px;"></p>
    <p id='messages' align="center" style="color:#FF0000;margin:0px;"></p>
    <fieldset>
    <table cellpadding='2'>
        <tr>
            
            <td><?php echo $lablearray['316'];?><br><?php echo DrawComboFromArray('branch_code','branch_code','','operatorbranches','','multiple');?></td>
            <td><?php echo $lablearray['317']; ?><br><input type="us-date" class='date' id="txtDate" name="txtDate" value=""></td>
            <td><?php echo $lablearray['1096']; ?><br><?php echo DrawComboFromArray($products, 'product_prodid', '', 'combo', '', ''); ?></td>
        
        <td>
         <?php echo $lablearray['1242'];?><br>
         <select  id="client_regstatus" name="client_regstatus" multiple>                
           <option value="ACT" selected><?php echo $lablearray['68'];?></option>
           <option value="INA"><?php echo $lablearray['1243'];?></option>									
           <option value="EXT"><?php echo $lablearray['1244'];?></option>
           <option value="CLO"><?php echo $lablearray['1245'];?></option>	
       </select> <?php echo TEXT_FIELD_REQUIRED; ?> 
        <td>
        </tr>
        
    </table>
      </fieldset>
   
      <div id="data"></div>
    <p align='right'><button class="btn" name="Go"  type="button" onClick="CloseDialog(vFloatingPane.id);"  id="btnscancel"><?php echo $lablearray['300']; ?></button><button class="btn" name="btnCalculate"  type="button"   id="btnCalculate"><?php echo $lablearray['19']; ?></button><button class="btn" name="btnPost"  type="button"   id="btnPost"><?php echo $lablearray['1433']; ?></button></p><p align='center'><?php echo printOptions('',"&source=EXT&rpt=SAVINTRPTS&product_prodid='+$('#product_prodid').val()+'&date='+$('#txtDate').val()+'&branch_code='+$('#branch_code option:selected').val()");?></p>
</form>


<script type="text/javascript">

    $(document).ready(function () {

        $('input[type=us-date]').w2field('date',  { format: '<?php echo SETTING_DATE_FORMAT?>'})

        $("#btnSave").click(function () {

            // var pageinfo = JSON.stringify($("#frmcalcint").serializeArray());
            //var data1 = JSON.parse('{"pageinfo":'+pageinfo+"}");	
            // var data1 = JSON.stringify(JSON.parse('{"pageinfo":' + pageinfo + "}"));
            //frm,theid,action,pageparams,urlpage,keyparam
            // showValues('frmcalcint', $('#product_prodid').val(), $("#action").val(), data1, 'addedit.php');


        });


        $("#product_prodid").change(function () {

//                showValues('frmcalcint', '', 'loadform', '', 'load.php', $('#product_prodid').val()).done(function () {
//                    showValues('frmcalcint', 'glaccounts', 'search', 'SAVPRODGLACC', 'load.php', $('#product_prodid').val());
//                    
//                });

        });

        // showValues('frmsaveproductsettings1', '', 'loadform', '', 'load.php', $('#product_prodid').val()).done(function () {

    });



    $("#btnCalculate").click(function () {

//                var pageinfo = JSON.stringify($("#frmsavproductsettings3").serializeArray());
//
//
//                //var data1 = JSON.parse('{"pageinfo":'+pageinfo+"}");	
//                var data1 = JSON.stringify(JSON.parse('{"pageinfo":' + pageinfo + "}"));
//                showValues('frmsavproductsettings3', $('#product_prodid').val(), 'add', data1, 'addedit.php', $('#product_prodid').val()).done(function () {
//
    //alert($('#branch_code option:selected').val());            
        showValues('frmcalcint', 'data', 'search', 'CALSAVCINT', 'load.php?date='+ $('#txtDate').val()+'&rpt=SAVINTRPT'+'&branch_code='+$('#branch_code option:selected').val()+'&client_regstatus='+$('#client_regstatus').val(), $('#product_prodid').val());
        
         //$("#btnviewreport").trigger('click');


    });
    
    
     $("#btnPost").click(function () {
         var pageinfo = JSON.stringify($("#frmcalcint").serializeArray());
         var data1 = JSON.parse('{"pageinfo":'+pageinfo+"}");	
         var data1 = JSON.stringify(JSON.parse('{"pageinfo":' + pageinfo + "}"));
         showValues('frmcalcint', $('#product_prodid').val(), 'add', data1, 'addedit.php');
         
     });
    
</script>
</BODY>
</HTML>