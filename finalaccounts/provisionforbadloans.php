<?php
require_once('../includes/application_top.php');
//	
//	$classes_query = tep_db_query("select * from " . TABLE_CLASSES);
//	  while ($classes_array = tep_db_fetch_array($classes_query)) {
//			$classes[$classes_array['classes_id']] = $classes_array['classes_name'];
//	 }
//	 #	used to populate the transaction types drop down
//	$requirements_types_query = tep_db_query("select requirements_id, requirements_name from " . TABLE_REQUIREMENTS." ORDER BY requirements_name");
//	 while ($requirements = tep_db_fetch_array($requirements_types_query)) {
//      	$requirements_types[$requirements['requirements_id']] = $requirements['requirements_name'];
//    }
?>
<script language="JavaScript"  type="text/javascript">
    var url = '';
    var iface = '';
    url = "../addedit.php";

    var cTo = "";
    var cfrom = "";
    var cper = "";

    function getdata(paging, formid, action, searchterm) {
        cTo = "";
        cfrom = "";
        cper = "";

        if (document.getElementById('txtRDay').value == "") {
            alert('Please select date');
            return;
        }
        // from
        for (i = 0; i < document.frmcalcprovision.textfrclasses.length; i++) {

            if (i == 0) {
                cfrom = document.frmcalcprovision.textfrclasses[i].value;
            } else {
                cfrom = cfrom + ',' + document.frmcalcprovision.textfrclasses[i].value;
            }


        }
        // to
        for (i = 0; i < document.frmcalcprovision.texttoclasses.length; i++) {

            if (i == 0) {
                cTo = document.frmcalcprovision.texttoclasses[i].value;
            } else {
                cTo = cTo + ',' + document.frmcalcprovision.texttoclasses[i].value;
            }

        }

        // percentage
        for (i = 0; i < document.frmcalcprovision.textperclasses.length; i++) {

            if (i == 0) {
                cper = document.frmcalcprovision.textperclasses[i].value;
            } else {
                cper = cper + ',' + document.frmcalcprovision.textperclasses[i].value;
            }

        }

        if (document.getElementById('txtRDay').checked == true) {
            if (document.getElementById('txtRDay').value == "" || !isPosInteger('txtblockfigure', 'Please enter a valud amount')) {
                alert('Please enter the amount');
                return;
            }
            str = paging + '&action=' + action + '&frmid=frmcalcprovision&txtRDay=' + document.getElementById('txtRDay').value + '&block=1&amount=' + document.getElementById('txtblockfigure').value;
        } else {

            //if(!isPosInteger('textfrclasses1','Invalid value in Class 1') || !isPosInteger('texttoclasses1','Invalid value in Class 1') || !isPosInteger('textperclasses1','Invalid value in Class 1') && !isPosInteger('textfrclasses2','Invalid value in Class 2') || !isPosInteger('texttoclasses2','Invalid value in Class 2') || !isPosInteger('textperclasses2','Invalid value in Class 2') && !isPosInteger('textfrclasses3','Invalid value in Class 3') || !isPosInteger('texttoclasses3','Invalid value in Class 3') || !isPosInteger('textperclasses3','Invalid value in Class 3') || !isPosInteger('textfrclasses4','Invalid value in Class 4') || !isPosInteger('texttoclasses4','Invalid value in Class 4') || !isPosInteger('textperclasses4','Invalid value in Class 4') || !isPosInteger('textfrclasses5','Invalid value in Class 5') || !isPosInteger('textperclasses5','Invalid value in Class 5')){
            //	return;
            //}
            str = paging + '&action=' + action + '&frmid=frmcalcprovision&txtRDay=' + document.getElementById('txtRDay').value + '&from=' + cfrom + '&to=' + cTo + '&per=' + cper;
        }

        if (action == 'calculate') {
            showResult(str, 'txtHint');
        } else {
            showResult(str, '');
        }
    }

    function calProvision() {

        str = 'action=calculate&frmid=frmcalcprovision&txtRDay=' + document.getElementById('txtRDay').value;

        showResult(str, 'txtHint')

    }

// selecct which option is gona be used to write off arrears
   function disablesboxes() {

        if (document.getElementById('chkusblockfigure').checked == true) {

            if (document.getElementById('txtRDay').value == "") {
                document.getElementById('chkusblockfigure').checked = false;
                alert('Pease select a reporting date');
                return false;
            }

            document.getElementById('textfrclasses1').disabled = true;
            document.getElementById('texttoclasses1').disabled = true;
            document.getElementById('textperclasses1').disabled = true;

            document.getElementById('textfrclasses2').disabled = true;
            document.getElementById('texttoclasses2').disabled = true;
            document.getElementById('textperclasses2').disabled = true;

            document.getElementById('textfrclasses3').disabled = true;
            document.getElementById('texttoclasses3').disabled = true;
            document.getElementById('textperclasses3').disabled = true;

            document.getElementById('textfrclasses4').disabled = true;
            document.getElementById('texttoclasses4').disabled = true;
            document.getElementById('textperclasses4').disabled = true;

            document.getElementById('textfrclasses5').disabled = true;
            document.getElementById('textperclasses5').disabled = true;

            document.getElementById('txtblockfigure').disabled = false;

            showResult('frmid=frmcalcprovision&txtRDay=' + document.getElementById('txtRDay').value, 'arrears');

        } else {

            document.getElementById('textfrclasses1').disabled = false;
            document.getElementById('texttoclasses1').disabled = false;
            document.getElementById('textperclasses1').disabled = false;

            document.getElementById('textfrclasses2').disabled = false;
            document.getElementById('texttoclasses2').disabled = false;
            document.getElementById('textperclasses2').disabled = false;

            document.getElementById('textfrclasses3').disabled = false;
            document.getElementById('texttoclasses3').disabled = false;
            document.getElementById('textperclasses3').disabled = false;

            document.getElementById('textfrclasses4').disabled = false;
            document.getElementById('texttoclasses4').disabled = false;
            document.getElementById('textperclasses4').disabled = false;

            document.getElementById('textfrclasses5').disabled = false;
            document.getElementById('textperclasses5').disabled = false;

            document.getElementById('txtblockfigure').disabled = true;

        }

        document.getElementById('txtHint').innerHTML = "";

    }
</script>		
<?php
require('../' . DIR_WS_INCLUDES . 'pageheader.php');
Common::getlables("317,1545,271,1096,38,39,1296,1297,1298,1299,1300,1546,300", "", "", $Conn);
?>
<fieldset>
    <form action="" method="post"  id='frmcalcprovision' name='frmcalcprovision' style='height:100%'>

        <table width="100%" border="0" cellspacing="2" cellpadding="0">
            <tr>
                <td ><?php echo Common::$lablearray['317']; ?><br><input name="txtRDay" class="yellowfield"  id="txtRDay" type="us-date" size="15" width="32" value=""/>
                </td>
                <td  valign="top">
                    <input type="checkbox" name="chkusblockfigure" id="chkusblockfigure" onClick="disablesboxes();"> <?php echo Common::$lablearray['1545']; ?><br> 
                </td>
            </tr>

            <tr>
                <td><?php echo Common::$lablearray['1096']; ?><br><?php echo Common::DrawComboFromArray(array(), 'product_prodid', '', 'LOANPROD', '', 'LOANPROD'); ?></td>
                <td valign="top"><?php echo Common::$lablearray['271']; ?><br><input type="text" id="txtblockfigure" name="txtblockfigure" value=""></td>
            </tr>
            <tr>
                <td colspan="2" align="center">
                    <table width="100%" border="0" cellspacing="0" cellpadding="1">
                        <tr>
                            <td></td>
                            <td><?php echo Common::$lablearray['38']; ?></td>
                            <td><?php echo Common::$lablearray['39']; ?></td>
                            <td>%</td>						
                        </tr>


                        <tr>
                            <td><?php echo Common::$lablearray['1296']; ?></td>
                            <td ><input name="textfrclasses" type="text" id="textfrclasses1" size='5' value="1"></td>
                            <td>&nbsp;<input name="texttoclasses" type="text" id="texttoclasses1" size='5' value="30"></td>
                            <td>&nbsp;<input name="textperclasses" type="text" id="textperclasses1" size='4' value="100"></td>

                        </tr>
                        <tr>
                            <td><?php echo Common::$lablearray['1297']; ?></td>
                            <td ><input name="textfrclasses" type="text" id="textfrclasses2" size='5' value="31"></td>
                            <td>&nbsp;<input name="texttoclasses" type="text" id="texttoclasses2" size='5' value="60"></td>
                            <td>&nbsp;<input name="textperclasses" type="text" id="textperclasses2" size='4' value="100"></td>

                        </tr>
                        <tr>
                            <td><?php echo Common::$lablearray['1298']; ?></td>
                            <td ><input name="textfrclasses" type="text" id="textfrclasses3" size='5' value="61"></td>
                            <td>&nbsp;<input name="texttoclasses" type="text" id="texttoclasses3" size='5' value="90"></td>
                            <td>&nbsp;<input name="textperclasses" type="text" id="textperclasses3" size='4' value="100"></td>

                        </tr>
                        <tr>
                            <td><?php echo Common::$lablearray['1299']; ?></td>
                            <td ><input name="textfrclasses" type="text" id="textfrclasses4" size='5' value="91"></td>
                            <td>&nbsp;<input name="texttoclasses" type="text" id="texttoclasses4" size='5' value="120"></td>
                            <td>&nbsp;<input name="textperclasses" type="text" id="textperclasses4" size='4' value="100"></td>

                        </tr>
                        <tr>
                            <td><?php echo Common::$lablearray['1300']; ?></td>
                            <td ><input name="textfrclasses" type="text" id="textfrclasses5" size='5' value="121"></td>
                            <td>&nbsp <?php echo Common::$lablearray['1546']; ?></td>
                            <td>&nbsp;<input name="textperclasses" type="text" id="textperclasses5" size='4' value="100"></td>

                        </tr>

                    </tr>
                </table>

        </td>

        </tr>

        <tr>
            <td colspan="2" align="center" valign="top">
                <button class="btn" name="Search" value="Search" type="button" onClick="getdata('', '', 'calculate', '');"  id="btnSearch"><?php echo Common::$lablearray['300']; ?></button> 
            </td>
        </tr>
        <tr>
            <td colspan="2" align="center" valign="top">


                <span id="txtHint" style='align:center;border:1px soild #D5E7FF;'></span>


            </td>
        </tr>


        </table>		
    </form>		
    <fieldset>    
        <script language="javascript">

            $('input[type=us-date]').w2field('date');
            //showResult('frmid=frmcalcprovision&txtRDay='+'<?php echo date("d/m/Y"); ?>','arrears');
            //document.getElementById('txtHint').innerHTML ="";
        </script>
        </BODY>
        </HTML>
