<?php
require_once('../includes/application_top.php');
//require_once("../simple-php-captcha-master/simple-php-captcha.php");
//$_SESSION['captcha'] = simple_php_captcha(array(),'TRANSFER');

spl_autoload_register(function ($class_name) {
    include '../includes/classes/' . $class_name . '.php';
});
$_parent = basename(__FILE__);
//getlables("21,1097,300,1161,20");
?>
<script type="text/javascript">
    var searchtext = '';
    var act = '';

    $(document).ready(function() {

        $("#PAYMODES").trigger("change");

        const radios = document.getElementsByName("radiosclient");
        radios.forEach((radio) => {
            radio.addEventListener('click', () => {

                const tableSelector = '#grid_accounts';

                if ($.fn.DataTable.isDataTable(tableSelector)) {
                    $(tableSelector).DataTable().clear().destroy();
                    $(tableSelector).empty().removeAttr('style');
                }

                showValues('frmsavaccounts', 'gridata', 'search', radio.value, 'load.php');
            });
        });
    });

    // Listen for click events on the entire document
    document.addEventListener('click', function(event) {

        // Check if the clicked element is a checkbox with the class 'row-checkbox'
        if (event.target.matches('input[type="checkbox"].row-checkbox')) {
            if (event.target.checked) loadSavingAccounts();
        }

    });

    function loadSavingAccounts() {

        var tags = document.getElementsByName('radiosclient');
        var TXTPAGE = '';

        for (var i = 0; i < tags.length; ++i) {
            if (tags[i].checked) {
                TXTPAGE = tags[i].value + "SAVACC";
            }
        }

        return showValues('frmsavaccounts', 'accounts', 'search', TXTPAGE, 'load.php', $('#client_idno').val());
    }
</script>
<?php
require('../' . DIR_WS_INCLUDES . 'pageheader.php');
getlables("20,1515,1403,271,1751,21,1516,24,300,1161,1024,373,299,317,1197,1096,373,9,654,316,317,1443,1723,1724");
?>
<form id="frmsavaccounts" name="frmsavaccounts">
    <table cellpadding="0" cellspacing="0" width="100%">
        <tr>
            <td align="center">
                <?php echo Common::clientOptions("S"); ?>
            </td>
        </tr>
        <tr>
            <td id='InfoBox' align="center"></td>
        </tr>
    </table>
    <fieldset class="grid-options">
        <div>
            <table cellpadding="0" cellspacing="0" width="100%">

                <tr>
                    <td colspan="3">
                        <input id="action" name="action" type="hidden" value="add">
                        <input id="theid" name="theid" type="hidden" value="">
                        <span id="Name"></span>
                    </td>
                </tr>
                <tr>
                    <td><?php echo $lablearray['1197']; ?><br><input type="text" id="txtsavaccount" name="txtsavaccount" value="" readonly=""></td>
                    <td><?php echo $lablearray['654']; ?><br><input type="text" id="client_idno" name="client_idno" value="" readonly=""></td>
                    <td></td>
                </tr>
                <tr>
                    <td><?php echo $lablearray['317']; ?><br><input type="us-date" class='date' id="txtOpenDate" name="txtOpenDate" value=""></td>
                    <td><?php echo $lablearray['1096']; ?><br><?php echo DrawComboFromArray(array(), 'product_prodid', '', 'SAVPROD', '', 'SAVPROD'); ?></td>
                    <td><?php echo $lablearray['24']; ?><br><?php echo Common::DrawComboFromArray(array(), 'PAYMODES', '', 'PAYMODES', '', 'PAYMODES'); ?></td>
                </tr>
                <tr>
                    <td><?php echo $lablearray['316']; ?><br><?php echo DrawComboFromArray('branch_code', 'branch_code', 'PP', 'operatorbranches', '', ''); ?></td>
                    <td><?php echo $lablearray['299']; ?><?php echo $lablearray['1751']; ?><br><input type="text" id="txtvoucher" name="txtvoucher" value=""></td>
                    <td><?php echo $lablearray['373']; ?><br><input type="numeric" id="txtamount" name="txtamount" value='0'></td>
                </tr>
                <tr>
                    <td colspan="3" align="center" id='modes'>

                    </td>
                </tr>
                <tr>
                    <td colspan="3">
                        <span id='modes'></span>
                        <fieldset>
                            <legend><?php echo $lablearray['1516']; ?></legend><?php echo $lablearray['271']; ?><?php echo $lablearray['1751']; ?> <input type="numeric" id="txtrepaysavtamount" name="txtrepaysavtamount" value='0'> <?php echo $lablearray['1515']; ?><?php echo $lablearray['1751']; ?> <?php echo DrawComboFromArray(array(), 'CMBFREQUENCY', '', "FREQUENCY", "", "", "frmsavaccounts"); ?> <?php echo $lablearray['1403']; ?> <?php echo Common::DrawComboFromArray(array(), 'LOANPROD', 'LOANPROD', 'LOANPROD', "", "", ""); ?>
                        </fieldset>
                    </td>
                </tr>
                <tr>

            </table>
        </div>

        <div syle="max-height:500px;">
            <table id="grid_accounts" width="100%">
            </table>
        </div>
    </fieldset>
    <p style="text-align:center;">
        <button class="btn" name="Go" type="button" id="btnscancel"><?php echo $lablearray['300']; ?></button><button class="btn" name="btnBack" type="button" id="btnBack"><?php echo $lablearray['1161']; ?></button><button class="btn" name="btnSave" type="button" id="btnSave"><?php echo $lablearray['20']; ?></button>
    </p>
    <table id="grid_gridata" width="100%" syle="max-height:500px;">
    </table>
</form>
<script type="text/javascript">
    $(document).ready(function() {

        $('input[type=us-date]').w2field('date', {
            format: '<?php echo SETTING_DATE_FORMAT ?>'
        });

        $("#btnSave").click(function() {


            $(this).prop('disabled', true);

            var pageinfo = JSON.stringify($("#frmsavaccounts").serializeArray());
            var data1 = JSON.stringify(JSON.parse('{"pageinfo":' + pageinfo + "}"));

            showValues('frmsavaccounts', '', $('#action').val(), data1, 'addedit.php', $('#theid').val()).done(function() {
                $("#btnSave").prop('disabled', false);
                loadSavingAccounts();
                //  resetFormExcluding("frmsavaccounts", ["client_idno", "action", "branch_code"]);
            });


        });

    });
</script>
</BODY>

</HTML>