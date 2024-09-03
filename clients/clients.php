<?php
require_once('../includes/application_top.php');
// require_once("../simple-php-captcha-master/simple-php-captcha.php");
// $_SESSION['captcha'] = simple_php_captcha(array(),'TRANSFER');
$_parent = basename(__FILE__);
/// $code_array = unserialize($_SESSION['_CAPTCHA']['config']);//['code'];
//echo  $code_array['code']; 
// tep_db_query("INSERT INTO ".TABLE_TRANSFERCODES." (transfercodes_datecreated,transfers_code,bankbranches_code) VALUES (NOW(),'".$code_array['code']."','".$_SESSION['bankbranches_code']."')");
$retainvalues = false;
// fnEncrypt(trim($password),'PASSWORD');
/* $printerList = printer_list(PRINTER_ENUM_LOCAL);
  //  var_dump($printerList);
  $printerName = $printerList[3]['NAME'];


  $fhandle = fopen("D:\CCS Documents\Artciles_Nov_2014.docx","rb");
  $content= fread($fhandle,filesize("D:\CCS Documents\Artciles_Nov_2014.docx"));
  if($ph = printer_open($printer)) {
  //$content = "hello";
  //	printer_start_doc($ph, "Start Doc");
  // Set print mode to RAW and send PDF to printer
  printer_set_option($ph, PRINTER_MODE, "RAW");
  printer_write($ph,$content);
  printer_close($ph);
  //  printer_end_doc($ph);
  }

 */
// if the user is not logged on, redirect them to the login page
if (AuthenticateAccess('LOGIN') == 0) {
    //tep_redirect(tep_href_link(FILENAME_DEFAULT));
    tep_redirect(tep_href_link(FILENAME_LOGIN));
}


$results_query = tep_db_query("SELECT branch_code, licence_organisationname FROM " . TABLE_LICENCE . " WHERE licence_build='" . $_SESSION['licence_build'] . "'");

while ($cats = tep_db_fetch_array($results_query)) {
    $operators[$cats['branch_code']] = $cats['licence_organisationname'];
}



$results_query1 = tep_db_query("SELECT areacode_code,areacode_name FROM " . TABLE_AREACODES);

while ($codes = tep_db_fetch_array($results_query1)) {
    $acodes[$codes['areacode_code']] = $codes['areacode_name'];
}


$doctypes_results = tep_db_query("SELECT documenttypes_id,IF('" . $_SESSION['P_LANG'] . "'='FR',documenttypes_name_fr,documenttypes_name_en) as  documenttypes_name FROM " . TABLE_DOCUMENTTYPES);

while ($cats1 = tep_db_fetch_array($doctypes_results)) {
    $doctypes[$cats1['documenttypes_id']] = $cats1['documenttypes_name'];
}

$doctypes_results1 = tep_db_query("SELECT documentpriority_id,IF('" . $_SESSION['P_LANG'] . "'='FR',documentpriority_fr,documentpriority_en) as  documentpriority FROM " . TABLE_DOCUMENTPRIORITY);

while ($cats2 = tep_db_fetch_array($doctypes_results1)) {
    $docprority[$cats2['documentpriority_id']] = $cats2['documentpriority'];
}

$clientcat_results1 = tep_db_query("SELECT category1_id,category1_name FROM " . TABLE_CATEGORY1);

while ($clientcat = tep_db_fetch_array($clientcat_results1)) {
    $clientcats1[$clientcat['category1_id']] = $clientcat['category1_name'];
}

$costcenters_results = tep_db_query("SELECT costcenters_code,costcenters_name FROM " . TABLE_COSTCENTERS);

while ($clientcat = tep_db_fetch_array($costcenters_results)) {
    $costcenters[$clientcat['costcenters_code']] = $clientcat['costcenters_name'];
}

$clientcat_results2 = tep_db_query("SELECT category2_id,category2_name FROM " . TABLE_CATEGORY2);

while ($clientcat = tep_db_fetch_array($clientcat_results2)) {
    $clientcats2[$clientcat['category2_id']] = $clientcat['category2_name'];
}

$incomecat_results2 = tep_db_query("SELECT incomecategories_id,incomecategories_bracket FROM " . TABLE_INCOMECATEGORIES);

while ($incomecat = tep_db_fetch_array($incomecat_results2)) {
    $incomecats[$incomecat['incomecategories_id']] = $incomecat['incomecategories_bracket'];
}

$busssector_results2 = tep_db_query("SELECT bussinesssector_code,bussinesssector_name FROM " . TABLE_BUSSINESSECTOR);

while ($incomecat = tep_db_fetch_array($busssector_results2)) {
    $busssector[$incomecat['bussinesssector_code']] = $incomecat['bussinesssector_name'];
}



$educ_results2 = tep_db_query("SELECT educationlevel_id,educationlevel_level FROM " . TABLE_EDUCATIONLEVEL);

while ($educlvl = tep_db_fetch_array($educ_results2)) {
    $education[$educlvl['educationlevel_id']] = $educlvl['educationlevel_level'];
}


$Lang_results2 = tep_db_query("SELECT clientlanguages_id,clientlanguages_name FROM " . TABLE_CLIENTLAGUAGES);

while ($lang = tep_db_fetch_array($Lang_results2)) {
    $clientlang[$lang['clientlanguages_id']] = $lang['clientlanguages_name'];
}

//session_start();
// here you can perform all the checks you need on the user submited variables
$_SESSION['security_number'] = rand(10000, 99999);
getlables("1199,1733,1511,730,1241,391,9,260,447,1635,1219,1259,1582,886,1242,1640,208,1641,68,1243,1090,42,1244,1245,1095,885,585,1094,447,888,540,1093,1092,1091,1090,1069,224,225,260,20,1089,1017,886,1089,1086,1086,887,1015,1016,887,888,1016,1019,199,484,1018,316,1020,1021,1022,21,887,899,900,905,628,905,1049,1050,11,1052,1053,1054,1056,1057,1058,1060,1061,1062,1063,1064,1065,1066,1067,1068,1068,1069,1070,1071,1072,1073,1074,1075,1076,1077,1078,1079,1080,1081,1082,1083,1084,888");
?>
<script language="javascript">
    function newPage(cpar) {

        var tags = document.getElementsByName('client_type');

        for (var i = 0; i < tags.length; ++i) {
            if (tags[i].checked) {
                TXTPAGE = tags[i].value;
            }
        }

        switch (TXTPAGE) {
          
            case 'I':

                //  $("#frmClients" ).reset();
                $("#Indfieldset").show("slow");
                $("#Grpfieldset").hide();
                $('#memdetails').attr('disabled', 'disabled');
                
                dojo.style(dijit.byId("mem_details").controlButton.domNode, {
                    display: "none"
                });

                dijit.byId('regtabs').selectChild(dijit.byId('idgrpbuss'));
                TXTPAGE = 'IND';
                break;

            case 'G':
                $("#Grpfieldset").show("slow");
                $('#memdetails').removeAttr('disabled');
                dojo.style(dijit.byId("mem_details").controlButton.domNode, {
                    display: "inline-block"
                });
                $("#mem_details").children().prop('disabled', false);
                dijit.byId('regtabs').selectChild(dijit.byId('idgrpbuss'));
                $("#Indfieldset").hide();
                TXTPAGE = 'GRP';
                break;

            case 'B':
                //   $("#frmClients" ).reset();
                $("#Grpfieldset").show("slow");
                $('#memdetails').removeAttr('disabled');
                $("#Indfieldset").hide();
                dijit.byId('regtabs').selectChild(dijit.byId('idgrpbuss'));
                //   dojo.style(dijit.byId("mem_details").controlButton.domNode,{display:"none"});       
                TXTPAGE = 'BUSS';
                break;

            case 'M':

                $('#memdetails').removeAttr('disabled');
                dojo.style(dijit.byId("mem_details").controlButton.domNode, {
                    display: "inline-block"
                });
                dijit.byId('regtabs').selectChild(dijit.byId('mem_details'));
                $("#idgrpbuss").hide();
                $("#mem_details").children().prop('disabled', false);
                $("#Grpfieldset").hide();
                $("#Indfieldset").hide();
                TXTPAGE = 'GRP';
                // displaymessage('', "<?php echo $lablearray['1635']; ?>", 'INFO');          
                break;

            default:
                break;
        }


        w2utils.date(new Date());

        // $('input[type=us-date]').w2field('date');

        if (cpar == '') {
            return;
        }
        var searchterm = '';


        if (typeof($("input[type=search]").val()) !== 'undefined') {

            searchterm = $("input[type=search]").val();
        }

        showValues('frmClients', 'toppanel', 'search', TXTPAGE, 'load.php?searchterm=' + searchterm, TXTPAGE).done(function(){
            if(TXTPAGE=='G'){
                showValues('frmClients', 'grdgrpMembers', 'search', 'GMEM', 'load.php', $('#client_idno').val());                
            }

        });
        
        $('#toppanel').css({
            top: '30%',
            left: '50%',
            margin: '-' + ($('#myDialogId1').height() / 5) + 'px 0 0 -' + ($('#toppanel').width() / 2) + 'px'
        });

        $('#toppanel').show();

    }

    function getinfo(frm_id, ajaxdatadiv, action, pagedata, urlpage, element) {

        $("#toppanel").hide();
        
        if (element == 'GMEM') {
            showValues('frmClients', ajaxdatadiv, 'edit', '', 'load.php', 'GMEM').done(function() {

            });
            return;
        }

        showValues(frm_id, ajaxdatadiv, action, pagedata, urlpage, element).done(function() {
            $(function() {

                // if (element == 'GRP' || element == 'GMEM') {


                //     showValues('frmClients', 'grdgrpMembers', 'search', 'GMEM', 'load.php', $('#client_idno').val());
                // }


                //  populateForm(frm_id, jsonObj['data']);
            });

        });

        $("#toppanel").hide();
    }


    // $("#frmClients").click(function(e) {

    //     if (e.target.id != 'btnSearch') {
    //         $("#toppanel").hide();
    //     }

    // });

    $(document).ready(function() {
        $("#mem_details").children().prop('disabled', true);
    });

    $("#lgdocs").click(function(event) {
        if (event.target.id == 'lgdocs') {
            if ($('#members_idno').val().length == "0") {
                displaymessage('', "<?php echo $lablearray['1199']; ?>", 'INFO');
                return;
            }

            showValues('frmClients', 'ClientDocs', 'search', 'CDOCS', 'load.php', $('#members_idno').val());
            $("#iddocs").show;
        }

    });
</script>
<?php require('../' . DIR_WS_INCLUDES . 'pageheader.php'); ?>
<form id="frmClients" name="frmClients" style="width:auto;margin:auto;">
    <input id="action" name="action" type="hidden" value='add' />
    <input id="ajaxdatadiv" name="ajaxdatadiv" type="hidden" value='' />
   
    <table width="100%" border="0" cellpadding='0'>
        <tr> 
            <td> 
            <?php echo $lablearray['316']; ?><?php echo DrawComboFromArray('branch_code', 'branch_code', '', 'operatorbranches', '', ''); ?>     
                             
                       </td>       
            <td> 
                           
                <?php echo Common::clientOptions("C"); ?>   
                <div class="indicator" id='div_name'></div>        
            </td>
           
        </tr>
    
        <table>

            <table width="100%" border="0" cellpadding='0'>
                <tr>
                    <td >
                        <fieldset id="Indfieldset" style="display:block;width:auto;padding:5px;">
                            <legend><?php echo $lablearray['1016']; ?></legend>
                           
                            <table width="100%" border="0" cellpadding="5px;" cellspacing="0">
                                <tr>
                                    <td>
                                        <?php echo $lablearray['1242']; ?> <br>
                                        <select id="client_regstatus" name="client_regstatus" required>
                                            <option value="ACT" selected><?php echo $lablearray['68']; ?></option>
                                            <option value="INA"><?php echo $lablearray['1243']; ?></option>
                                            <option value="EXT"><?php echo $lablearray['1244']; ?></option>
                                            <option value="CLO"><?php echo $lablearray['1245']; ?></option>
                                            <option value="REC"><?php echo $lablearray['1582']; ?></option>
                                        </select>
                                    </td>
                                    <td>
                                        <?php echo $lablearray['1018']; ?><br>
                                        <?php echo tep_draw_input_field('client_surname', '', '', true, 'text', $retainvalues, '20'); ?>
                                    </td>
                                    <td><?php echo $lablearray['887']; ?><br>
                                        <?php echo tep_draw_input_field('client_firstname', '', '', true, 'text', $retainvalues, '20'); ?></td>
                                    <td><?php echo $lablearray['888']; ?><?php echo $lablearray['1511'];?><br>
                                        <?php echo tep_draw_input_field('client_middlename', '', '', false, 'text', $retainvalues, '20'); ?></td>
                                </tr>

                                <tr>

                                <tr>
                                    <td>
                                        <?php echo $lablearray['199']; ?> <br>

                                        <select id="client_gender" name="client_gender" required>
                                            <option value="M"><?php echo $lablearray['224']; ?></option>
                                            <option value="F"><?php echo $lablearray['225']; ?></option>
                                            <option value="U" selected><?php echo $lablearray['1069']; ?></option>

                                        </select>


                                    </td>
                                    <td>
                                        <?php echo $lablearray['1640']; ?><br>
                                        &nbsp;<input type="us-date" id="client_bday" name="client_bday" constraints="{datePattern:'<?php echo Common::convertDateJSFormat() ?>', strict:true}">
                                    </td>
                                    <td> <?php echo $lablearray['1022']; ?><?php echo $lablearray['1511'];?><br>
                                        <?php echo tep_draw_input_field('clientcode', '', '', false, 'text', $retainvalues, '20'); ?></td>
                                    <td><?php echo $lablearray['1641']; ?><?php echo $lablearray['1511'];?><br><?php echo tep_draw_input_field('client_occupation', '', '', false, 'text', $retainvalues, '20'); ?></td>
                                </tr>
                                <tr>
                                    <td><?php echo $lablearray['208']; ?><?php echo $lablearray['1511'];?><br><?php echo tep_draw_input_field('client_kinname', '', '', false, 'text', $retainvalues, '20'); ?></td>
                                    <td><?php echo $lablearray['1019']; ?><br><input type="us-date" id="client_regdate" name="client_regdate" constraints="{datePattern:'<?php echo Common::convertDateJSFormat() ?>', strict:true}"></td>
                                    <td> </td>
                                    <td> </td>
                                </tr>

                            </table>
                        </fieldset>

                        <fieldset id="Grpfieldset" style="display:none; padding:5px;">
                            <legend><?php echo $lablearray['1020']; ?></legend>
                            <table width="100%" border="0" cellpadding="2" cellspacing="2">
                                <tr>
                                    <td>
                                        <?php echo $lablearray['1021']; ?><br>
                                        <?php echo tep_draw_input_field('entity_name', '', '', false, 'text', $retainvalues, '20'); ?>
                                    </td>
                                    <td>
                                         <?php echo $lablearray['1019']; ?><br><input type="us-date" id="client_regdate" name="client_regdate" constraints="{datePattern:'<?php echo Common::convertDateJSFormat() ?>', strict:true}">
                                    </td>
                                    <td>
                                         <?php echo $lablearray['484']; ?><?php echo $lablearray['1511'];?><br><input type="us-date" id="entity_enddate" name="entity_enddate" constraints="{datePattern:'<?php echo Common::convertDateJSFormat() ?>', strict:true}">
                                    </td>
                                    
                                    <td>

                                        <?php echo $lablearray['1733']; ?><?php echo $lablearray['1511'];?><br>
                                        <?php echo tep_draw_input_field('entity_regcode', '', '', false, 'text', $retainvalues, '20'); ?>

                                    </td>


                                    <td>
                                        <?php echo $lablearray['1022']; ?><?php echo $lablearray['1511'];?><br>
                                        <?php echo tep_draw_input_field('client_idno', '', '', false, 'text', $retainvalues, '20'); ?>
                                    </td>
                                </tr>

                            </table>
                        </fieldset>
                        <div style="display:block;margin:0px;width:auto;">

                            <div >
                                <div data-dojo-type="dijit/layout/TabContainer" style="width:auto;height:auto;margin:5px;" tabPosition="right-h" id='regtabs'>

                                    <div data-dojo-type="dijit/layout/ContentPane" title="<?php echo $lablearray['1092']; ?>" id="idgrpbuss">
                                        <fieldset style="width:auto;">
                                            <legend><?php echo $lablearray['1092']; ?></legend>
                                            <table width="100%" border="0" cellpadding="0">

                                                <tr>
                                                    <td>&nbsp;<?php echo $lablearray['1050']; ?><br><?php echo tep_draw_input_field('client_city', '', '', false, 'text', $retainvalues, '20'); ?></td>
                                                    <td>&nbsp;<?php echo $lablearray['11']; ?><br><?php echo tep_draw_input_field('client_addressphysical', '', '', false, 'text', $retainvalues, '20'); ?></td>
                                                </tr>

                                                <tr>
                                                    <td>&nbsp;<?php echo $lablearray['1052']; ?><br><?php echo DrawComboFromArray($acodes, 'areacode_code', '', 'combo', '', ''); ?></td>
                                                    <td>&nbsp;<?php echo $lablearray['1053']; ?><br><?php echo tep_draw_input_field('client_tel1', '', '', false, 'text', $retainvalues, '20'); ?></td>
                                                </tr>

                                                <tr>
                                                    <td>&nbsp;<?php echo $lablearray['585']; ?><?php echo $lablearray['1511'];?><br><?php echo tep_draw_input_field('client_emailad', '', '', false, 'text', $retainvalues, '20'); ?></td>
                                                    <td>&nbsp;<?php echo $lablearray['1054']; ?><?php echo $lablearray['1511'];?><br><?php echo tep_draw_input_field('client_tel2', '', '', false, 'text', $retainvalues, '20'); ?></td>
                                                </tr>

                                                <tr>
                                                    <td></td>
                                                    <td>&nbsp;<?php echo $lablearray['1049']; ?><?php echo $lablearray['1511'];?><br><?php echo tep_draw_input_field('client_postad', '', '', false, 'text', $retainvalues, '20'); ?> </td>
                                                </tr>

                                            </table>
                                        </fieldset>
                                    </div>




                                    <div data-dojo-type="dijit/layout/ContentPane" title="<?php echo $lablearray['1090']; ?>" id="mem_details">

                                        <fieldset id="memdetails" style="margin:0px; width:auto;">
                                            <legend><?php echo $lablearray['1090']; ?></legend>

                                            <div style="display:block;width:auto;margin-top:0px;padding:10px;height:100%">
                                                <table width="100%" border="0" cellpadding="1" cellspacing="0">
                                                    <tr>
                                                        <td>&nbsp;<?php echo $lablearray['887']; ?><br><?php echo tep_draw_input_field('member_firstname', '', '', false, 'text', $retainvalues, '28'); ?></td>
                                                        <td>&nbsp;<?php echo $lablearray['888']; ?><?php echo $lablearray['1511'];?><br><?php echo tep_draw_input_field('member_middlename', '', '', false, 'text', $retainvalues, '28'); ?></td>
                                                        <td>&nbsp;<?php echo $lablearray['900']; ?><br><?php echo tep_draw_input_field('member_lastname', '', '', false, 'text', $retainvalues, '28'); ?></td>
                                                        <td>&nbsp;<?php echo $lablearray['1064']; ?><?php echo $lablearray['1511'];?><br>
                                                            <select id="member_maritalstate" name="member_maritalstate">
                                                                <option value="M"><?php echo $lablearray['1065']; ?></option>
                                                                <option value="S"><?php echo $lablearray['1067']; ?></option>
                                                                <option value="D"><?php echo $lablearray['1068']; ?></option>
                                                                <option value="U"><?php echo $lablearray['1069']; ?></option>

                                                            </select>

                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>&nbsp;<?php echo $lablearray['1070']; ?><br><input type="us-date" id="member_regdate" name="member_regdate"></td>
                                                        <td>&nbsp;<?php echo $lablearray['1071']; ?><?php echo $lablearray['1511'];?><br><input type="us-date" id="member_enddate" name="member_enddate"></td>
                                                        <td>&nbsp;<?php echo $lablearray['1640']; ?><br><input type="us-date" id="member_bday" name="member_bday"></td>
                                                        <td>&nbsp;<?php echo $lablearray['540']; ?><?php echo $lablearray['1511'];?><br><?php echo tep_draw_input_field('member_children', '0', '', false, 'text', $retainvalues, '5'); ?></td>
                                                    </tr>

                                                    <tr>
                                                        <td>&nbsp;<?php echo $lablearray['1074']; ?><?php echo $lablearray['1511'];?><br><?php echo DrawComboFromArray($clientcats1, 'member_category1_id1', 'xx', 'combo', '', ''); ?> </td>
                                                        <td>&nbsp;<?php echo $lablearray['1075']; ?><?php echo $lablearray['1511'];?><br><?php echo DrawComboFromArray($clientcats2, 'member_category2_id2', 'xx', 'combo', '', ''); ?></td>
                                                        <td>&nbsp;<?php echo $lablearray['1076']; ?><?php echo $lablearray['1511'];?><br><?php echo DrawComboFromArray($education, 'member_educationlevel_id', 'xx', 'combo', '', ''); ?></td>
                                                        <td>&nbsp;<?php echo $lablearray['1094']; ?><?php echo $lablearray['1511'];?><br><?php echo DrawComboFromArray($incomecats, 'member_incomecategories_id', 'xx', 'combo', '', ''); ?></td>
                                                    </tr>

                                                    <tr>
                                                        <td>&nbsp;<?php echo $lablearray['1078']; ?><?php echo $lablearray['1511'];?><br><?php
                                                                                                        echo DrawComboFromArray($clientlang, 'member_clientlanguages_id1', '', 'combo', '', '');
                                                                                                        ?></td>
                                                        <td>&nbsp;<?php echo $lablearray['1079']; ?><?php echo $lablearray['1511'];?><br><?php
                                                                                                        echo DrawComboFromArray($clientlang, 'member_clientlanguages_id2', '', 'combo', '', '');
                                                                                                        ?></td>
                                                        <td>&nbsp;<?php echo $lablearray['1241']; ?><br><?php echo tep_draw_input_field('member_no', '0000', '', false, 'text', $retainvalues, '15'); ?></td>
                                                        <td> <?php echo $lablearray['1242']; ?>
                                                            <select id="member_regstatus" name="member_regstatus">
                                                                <option value=""><?php echo $lablearray['42']; ?></option>
                                                                <option value="ACT" selected><?php echo $lablearray['68']; ?></option>
                                                                <option value="INA"><?php echo $lablearray['1243']; ?></option>
                                                                <option value="EXT"><?php echo $lablearray['1244']; ?></option>
                                                                <option value="CLO"><?php echo $lablearray['1245']; ?></option>
                                                            </select>
                                                        </td>
                                                    </tr>

                                                    <tr>
                                                        <td>&nbsp;<?php echo $lablearray['447']; ?><?php echo $lablearray['1511'];?><br><?php echo tep_draw_input_field('member_income', '0', '', false, 'text', $retainvalues, '5'); ?></td>
                                                        <td>&nbsp;<?php echo $lablearray['585']; ?><?php echo $lablearray['1511'];?><br><?php echo tep_draw_input_field('member_email', '0', '', false, 'text', $retainvalues, '30'); ?></td>
                                                        <td>&nbsp;<?php echo $lablearray['1072']; ?><?php echo $lablearray['1511'];?><br><?php echo tep_draw_input_field('member_dependants', '0', '', false, 'text', $retainvalues, '5'); ?> </td>
                                                        <td></td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="5" align="center">
                                                            <p align='center'><a name="btnAddMem" class="s10"  id="btnAddMem" name="btnAddMem" alt="Add member"><?php echo $lablearray['730']; ?></a></p>
                                                            <div id='grdgrpMembers' style="width:auto;height:100%;display:block;margin:0px;padding:5px;overflow:scroll;">

                                                            </div>
                                                        </td>
                                                    </tr>
                                                </table>
                                            </div>
                                        </fieldset>
                                    </div>
                                    <div id='iddocs' style="width:auto;display:none;">
                                     
                                        <span class="indicator_small" id='div_name2'> </span>
                                        <div>
                                            <input id="members_idno" name="members_idno" type="hidden" value='' />
                                            <table  border="0" cellpadding="0">
                                                <tr>
                                                    <td colspan="2">&nbsp;
                                                        <table border="0" cellpadding="2">
                                                            <tr>
                                                                <td>&nbsp;<?php echo $lablearray['1060']; ?></td>
                                                                <td>&nbsp;<?php
                                                                            echo DrawComboFromArray($doctypes, 'documenttypes_id', '', 'combo', '', '');
                                                                            ?></td>
                                                                <td>&nbsp;<?php echo $lablearray['1061']; ?></td>
                                                                <td>&nbsp;<?php echo tep_draw_input_field('document_serial', '', '', false, 'text', $retainvalues, '20'); ?></td>
                                                            </tr>
                                                            <tr>
                                                                <td>&nbsp;<?php echo $lablearray['905']; ?></td>
                                                                <td>&nbsp;<input type="us-date" id="document_issuedate" name="document_issuedate" constraints="{datePattern:'<?php echo Common::convertDateJSFormat() ?>', strict:true}">
                                                                </td>
                                                                <td>&nbsp;<?php echo $lablearray['628']; ?></td>
                                                                <td>&nbsp;<?php echo tep_draw_input_field('document_issuedby', '', '', false, 'text', $retainvalues, '20');  ?>
                                                                </td>
                                                            </tr>

                                                            <tr>
                                                                <td>&nbsp;<?php echo $lablearray['1062']; ?><?php echo $lablearray['1511'];?></td>
                                                                <td>&nbsp;<input type="us-date" id="document_docexpiry" name="document_docexpiry" constraints="{datePattern:'<?php echo Common::convertDateJSFormat() ?>', strict:true}">

                                                                </td>
                                                                <td>&nbsp;<?php echo $lablearray['1063']; ?><?php echo $lablearray['1511'];?></td>
                                                                <td>&nbsp;

                                                                    <?php
                                                                    echo DrawComboFromArray($docprority, 'document_priority', '', 'combo', '', '');
                                                                    ?>
                                                                </td>
                                                            </tr>



                                                        </table>

                                                    </td>

                                                </tr>

                                            </table>

                                            <p align='right'><button class="btn" type="button" id="btnAddMemDocs" name="btnAddMemDocs"><?php echo $lablearray['730']; ?></button></p>
                                        </div>

                                    </div>

                                    <div data-dojo-type="dijit/layout/ContentPane" title="<?php echo $lablearray['1089']; ?>">
                                        <div id='grdLoans'></div>
                                    </div>

                                    <div data-dojo-type="dijit/layout/ContentPane" title="<?php echo $lablearray['1086']; ?>">
                                        <div id='grbSavings'></div>
                                    </div>
                                    <div data-dojo-type="dijit/layout/ContentPane" title="<?php echo $lablearray['1089']; ?>">
                                        <div id='grdShares'></div>
                                    </div>

                                    <div data-dojo-type="dijit/layout/ContentPane" title="<?php echo $lablearray['1017']; ?>">
                                        <fieldset>
                                            <legend><?php echo $lablearray['1017']; ?></legend>
                                            <table width="100%" border="0">
                                                <tr>

                                                    <td>&nbsp;</td>

                                                    <td>&nbsp;<?php echo $lablearray['1081']; ?><?php echo $lablearray['1511'];?></td>
                                                    <td>&nbsp;<?php echo DrawComboFromArray($clientcats1, 'client_cat1', '', 'combo', '', ''); ?>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>&nbsp;<?php echo $lablearray['1082']; ?><?php echo $lablearray['1511'];?></td>
                                                    <td>&nbsp;<?php echo DrawComboFromArray($costcenters, 'costcenters_code', '', 'combo', '', ''); ?></td>

                                                    <td>&nbsp;<?php echo $lablearray['1259']; ?><?php echo $lablearray['1511'];?></td>
                                                    <td>&nbsp;<?php echo DrawComboFromArray($busssector, 'bussinesssector_code', '', 'combo', '', ''); ?></td>

                                                </tr>

                                                <tr>
                                                    <td>&nbsp;<?php echo $lablearray['1083']; ?><?php echo $lablearray['1511'];?></td>
                                                    <td>&nbsp;<?php echo DrawComboFromArray($clientcats2, 'client_cat2', '', 'combo', '', ''); ?>

                                                    <td>&nbsp;<?php echo $lablearray['1084']; ?><?php echo $lablearray['1511'];?></td>
                                                    <td>&nbsp;<input type="us-date" id="client_enddate" name="client_enddate" value="" constraints="{datePattern:'<?php echo Common::convertDateJSFormat() ?>', strict:true}">
                                                    </td>
                                                </tr>

                                            </table>
                                        </fieldset>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div style="padding:2px;align:right;width:auto;float:right;"><button class="btn" name="btnSave" type="button" id="btnSave"><?php echo $lablearray['20']; ?></button><button class="btn" name="btnsearch" type="button" onClick="CloseDialog(vFloatingPane.id);" id="btnsearch"><?php echo $lablearray['260']; ?></button></div>
                    </td>
                    <td></td>
                </tr>
            </table>

</form>
<div id="wrapper" style="text-align: center">
    <table id="grid_toppanel" width="100%"></table>
</div>
</div>


<script type="text/javascript">
    var data = '';


    $("#btnAddMemDocs").click(function() {

        var doccsarray = $("#iddocs *").serializeArray()

        doccsarray.push({
            name: "client_type",
            value: "D"
        });


        pageparams = JSON.stringify(doccsarray);
        //frm, ajaxdatadiv, action, pageparams

        showValues('frmClients', '', $('#action').val(), pageparams).done(function() {
            $("#lgdocs").click();
        });

    });

    $("#btnSave , #btnAddMem").click(function(event) {


        var tags = document.getElementsByName('client_type');

        for (var i = 0; i < tags.length; ++i) {
            if (tags[i].checked) {

                TXTPAGE = tags[i].value;

                if (event.target.id == 'btnAddMem') {
                    tags[i].value = 'M';
                }
            }
        }
        
        var action = $('#action').val(); 
        showValues('frmClients', '', action).done(function() {

        w2utils.date(new Date());
        //   $('#client_idno').val(ajaxdatadiv);
        const client_idno = document.getElementById("client_idno").value;
        showValues('frmClients', 'grdgrpMembers', 'search', 'GMEM', 'load.php', client_idno)
           

            
// newPage(ctype)       

        });
    });

    $(document).ready(function() {
        w2utils.date(new Date());

        $('input[type=us-date]').w2field('date', {
            format: '<?php echo SETTING_DATE_FORMAT ?>'
        })
        var myTxt = dojo.byId("document_issuedate");
    });
</script>
</BODY>

</HTML>