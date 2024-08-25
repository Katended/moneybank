<?php
  require('../includes/application_top.php');
 if($_POST['submit']=="Save"){
	
	tep_db_query("UPDATE " .TABLE_ACCOUNTSCONFIG." SET accountsconfig_value ='".$_POST['ACC_TUITION_RECIEVABLE']."' WHERE accountsconfig_key = 'ACC_TUITION_RECIEVABLE'");
	tep_db_query("UPDATE " .TABLE_ACCOUNTSCONFIG." SET accountsconfig_value ='".$_POST['ACC_CASHBOOK_HAND']."' WHERE accountsconfig_key = 'ACC_CASHBOOK_HAND'"); 
	tep_db_query("UPDATE " .TABLE_ACCOUNTSCONFIG." SET accountsconfig_value ='".$_POST['ACC_TUTION_FEES']."' WHERE accountsconfig_key = 'ACC_TUTION_FEES'"); 
	tep_db_query("UPDATE " .TABLE_ACCOUNTSCONFIG." SET accountsconfig_value ='".$_POST['ACC_OTHER_INCOMES']."' WHERE accountsconfig_key = 'ACC_OTHER_INCOMES'"); 
	tep_db_query("UPDATE " .TABLE_ACCOUNTSCONFIG." SET accountsconfig_value ='".$_POST['ACC_INTEREST_ON_FOREIGN_EXCHANGE']."' WHERE accountsconfig_key = 'ACC_INTEREST_ON_FOREIGN_EXCHANGE'"); 
	tep_db_query("UPDATE " .TABLE_ACCOUNTSCONFIG." SET accountsconfig_value ='".$_POST['ACC_DONATIONS']."' WHERE accountsconfig_key = 'ACC_DONATIONS'"); 
	tep_db_query("UPDATE " .TABLE_ACCOUNTSCONFIG." SET accountsconfig_value ='".$_POST['ACC_ADMIN_EXP']."' WHERE accountsconfig_key = 'ACC_ADMIN_EXP'"); 
	tep_db_query("UPDATE " .TABLE_ACCOUNTSCONFIG." SET accountsconfig_value ='".$_POST['ACC_SALARIES_WAGES']."' WHERE accountsconfig_key = 'ACC_SALARIES_WAGES'"); 
	tep_db_query("UPDATE " .TABLE_ACCOUNTSCONFIG." SET accountsconfig_value ='".$_POST['ACC_PRINTING_STATIONERY']."' WHERE accountsconfig_key = 'ACC_PRINTING_STATIONERY'");
	tep_db_query("UPDATE " .TABLE_ACCOUNTSCONFIG." SET accountsconfig_value ='".$_POST['ACC_DISCOUNTS']."' WHERE accountsconfig_key = 'ACC_DISCOUNTS'");
	tep_db_query("UPDATE " .TABLE_ACCOUNTSCONFIG." SET accountsconfig_value ='".$_POST['ACC_PROVISION_DEBTS']."' WHERE accountsconfig_key = 'ACC_PROVISION_DEBTS'");
	tep_db_query("UPDATE " .TABLE_ACCOUNTSCONFIG." SET accountsconfig_value ='".$_POST['ACC_BAD_DEBTS']."' WHERE accountsconfig_key = 'ACC_BAD_DEBTS'");
	tep_db_query("UPDATE " .TABLE_ACCOUNTSCONFIG." SET accountsconfig_value ='".$_POST['ACC_SUSPENSE']."' WHERE accountsconfig_key = 'ACC_SUSPENSE'");
	tep_db_query("UPDATE " .TABLE_ACCOUNTSCONFIG." SET accountsconfig_value ='".$_POST['ACC_CHEQS']."' WHERE accountsconfig_key = 'ACC_CHEQS'");
	tep_db_query("UPDATE " .TABLE_ACCOUNTSCONFIG." SET accountsconfig_value ='".$_POST['ACC_DEP_EXP']."' WHERE accountsconfig_key = 'ACC_DEP_EXP'");
	tep_db_query("UPDATE " .TABLE_ACCOUNTSCONFIG." SET accountsconfig_value ='".$_POST['ACC_VAT_ON_FEES']."' WHERE accountsconfig_key = 'ACC_VAT_ON_FEES'");
}
 $glaccounts = getAccountLevels(); 
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title><?php echo NAME_OF_INSTITUTION;?></title>
<link rel="stylesheet" href="../includes/Dojo/dijit/themes/claro/claro.css" media="screen">

<script src="../includes/Dojo/dojo/dojo.js"
        data-dojo-config="async:true, parseOnLoad:true">
</script>
<script>
require(["dijit/layout/BorderContainer", "dijit/layout/TabContainer", "dijit/layout/ContentPane", "dojo/parser"]);
		
		
		// create the BorderContainer and attach it to our appLayout div
var appLayout = new BorderContainer({
    design: "headline"
}, "appLayout");
 
 
// create the TabContainer
var contentTabs = new TabContainer({
    region: "center",
    id: "contentTabs",
    tabPosition: "bottom",
    "class": "centerPanel",
    href: "contentCenter.html"
})
 
// add the TabContainer as a child of the BorderContainer
appLayout.addChild( contentTabs );
 
// create and add the BorderContainer edge regions
appLayout.addChild(
    new ContentPane({
        region: "top",
        "class": "edgePanel",
        content: "Header content (top)"
    })
)
appLayout.addChild(
    new ContentPane({
        region: "left",
        id: "leftCol", "class": "edgePanel",
        content: "Sidebar content (left)",
        splitter: true
    })
);
 
// Add initial content to the TabContainer
contentTabs.addChild(
    new ContentPane({
        href: "contentGroup1.html",
        title: "Group 1"
    })
)
 
// start up and do layout
appLayout.startup();
</script> 

<script language="javascript" src="../includes/javascript/ajax.js"></script>
<script type="text/javascript" src="../includes/javascript/gridsearch.js" language="javascript"></script>
<link  media="screen"  type="text/css" rel="stylesheet" href="../includes/javascript/de.css"></link>
<script type="text/javascript" src="../includes/javascript/jquery-1.6.2.min.js"></script>
<script type="text/javascript" src="../includes/javascript/jquery-ui-1.8.16.custom.min.js"></script>
<script type="text/javascript" src="../includes/javascript/jquery.pnotify.js"></script>
<script src="../styles/jquerytooltip.js" type="text/javascript"></script>
<script src="../styles/JTIP.JS" type="text/javascript"></script>
<script src="../includes/javascript/commonfunctions.js" type="text/javascript"></script>
<script src="../includes/javascript/scroll.js" type="text/javascript"></script>
<link rel="stylesheet" type="text/css" href="../styles/GLOBAL.CSS">
<LINK href="../stylesheet.css" type="text/css" rel="stylesheet">


<?php require('../'.DIR_WS_INCLUDES . 'initmenu.php'); ?>
<h1>General Ledger Accounts Settings</h1>




<form name="frmgeneralsettings" id="frmgeneralsettings"  action="manageglaccounts.php"  method="post" enctype="multipart/form-data" >
				<table cellpadding="6" cellspacing="0" border="0" width="100%">
				
                    
                     <tr>
						<td>Tution Outstanding</td><td><?php echo DrawComboFromArray($glaccounts,'ACC_TUTION_FEES',ACC_TUTION_FEES); //echo getDrawComboFromArray($glaccounts,'acc_tution_fees',ACC_TUTION_FEES);?></td>
					</tr>						
					
					<tr>
						<td>Scholarships</td><td><?php echo DrawComboFromArray($glaccounts,'ACC_SCHOLARSHIP_FEES',ACC_SCHOLARSHIP_FEES); //echo getDrawComboFromArray($glaccounts,'acc_scholarship_fees',ACC_SCHOLARSHIP_FEES);?></td>
					</tr>
					<tr>
						<td colspan="2" ><h2>Default Cash Account</h2></td>
					</tr>
					<tr>
						<td>Default Cash Account</td><td><?php echo DrawComboFromArray($glaccounts,'ACC_CASHBOOK_HAND',ACC_CASHBOOK_HAND); //echo getDrawComboFromArray($glaccounts,'acc_other_incomes',ACC_OTHER_INCOMES);?></td>
					</tr>					
				
					<tr>
						<td colspan="2" ><h2>Incomes</h2></td>
					</tr>
					<tr>
						<td>Tution Recievable</td><td><?php echo DrawComboFromArray($glaccounts,'ACC_TUITION_RECIEVABLE',ACC_TUITION_RECIEVABLE); //echo getDrawComboFromArray($glaccounts,'acc_other_incomes',ACC_OTHER_INCOMES);?></td>
					</tr>
					<tr>
						<td>Other Incomes</td><td><?php echo DrawComboFromArray($glaccounts,'ACC_OTHER_INCOMES',ACC_OTHER_INCOMES); //echo getDrawComboFromArray($glaccounts,'acc_other_incomes',ACC_OTHER_INCOMES);?></td>
					</tr>
					<tr>
						<td>Interest On Foreign Exchange</td><td><?php echo DrawComboFromArray($glaccounts,'ACC_INTEREST_ON_FOREIGN_EXCHANGE',ACC_INTEREST_ON_FOREIGN_EXCHANGE); //echo getDrawComboFromArray($glaccounts,'acc_interest_on_foreign_exchange',ACC_INTEREST_ON_FOREIGN_EXCHNAGE);?></td>
					</tr>
					<tr>
						<td colspan="2" ><h2>Discounts and Concessions</h2></td>
					</tr>
					<tr>
						<td>Discounts</td><td><?php echo DrawComboFromArray($glaccounts,'ACC_DISCOUNTS',ACC_DISCOUNTS); //echo getDrawComboFromArray($glaccounts,'acc_discounts',ACC_DISCOUNTS);?></td>
					</tr>
					<tr>
						<td colspan="2" ><h2>Grants</h2></td>
					</tr>
										
					<tr>
						<td>Provision for bad debts </td><td><?php echo DrawComboFromArray($glaccounts,'ACC_PROVISION_DEBTS',ACC_PROVISION_DEBTS);?></td>
					</tr>
					<tr>
						<td>Bad debts </td><td><?php echo DrawComboFromArray($glaccounts,'ACC_BAD_DEBTS',ACC_BAD_DEBTS);?></td>
					</tr>
                    
                    <tr>
						<td colspan="2" ><h2>Depreciation</h2></td>
					</tr>
                     <tr>
					<td>Accumulated Depreciation</td><td><?php echo DrawComboFromArray($glaccounts,'ACC_DEP_EXP',ACC_DEP_EXP); //echo getDrawComboFromArray($glaccounts,'acc_salaries_wages',ACC_SALARIES_WAGES);?></td>
					</tr>
					<tr>
						<td colspan="2" ><h2>Expenses</h2></td>
					</tr>
                    <tr>
					<td>Administrative Expenses</td><td><?php echo DrawComboFromArray($glaccounts,'ACC_ADMIN_EXP',ACC_ADMIN_EXP); //echo getDrawComboFromArray($glaccounts,'acc_salaries_wages',ACC_SALARIES_WAGES);?></td>
					</tr>
					<tr>
					<td>Salaries and Wages</td><td><?php echo DrawComboFromArray($glaccounts,'ACC_SALARIES_WAGES',ACC_SALARIES_WAGES); //echo getDrawComboFromArray($glaccounts,'acc_salaries_wages',ACC_SALARIES_WAGES);?></td>
					</tr>
					<tr>
					<td>Printing and Stationery</td><td><?php echo DrawComboFromArray($glaccounts,'ACC_PRINTING_STATIONERY',ACC_PRINTING_STATIONERY);//echo getDrawComboFromArray($glaccounts,'acc_printing_stationery',ACC_PRINTING_STATIONAERY);?></td>
					</tr>
					<tr>
						<td>Cheques Account</td><td><?php echo DrawComboFromArray($glaccounts,'ACC_CHEQS',ACC_CHEQS); //echo getDrawComboFromArray($glaccounts,'acc_tution_fees',ACC_TUTION_FEES);?></td>
					</tr>
					<tr>
						<td>Suspense Account</td><td><?php echo DrawComboFromArray($glaccounts,'ACC_SUSPENSE',ACC_SUSPENSE); //echo getDrawComboFromArray($glaccounts,'acc_tution_fees',ACC_TUTION_FEES);?></td>
					</tr>
					<tr height="25">
                        <td></td>
                        <td></td>
					</tr>                   
                    <tr>
                        <td></td>
                        <td><input  type="submit" id="submit" name="submit" value="Save" class="actbutton" ><input name="Reset" type="reset" value="Clear" class="actbutton"></td>
					</tr>									
				</table>
             </form>			
    	  <?php require('../'.DIR_WS_INCLUDES . 'userfooter.php'); ?>
	
</BODY>
</HTML>