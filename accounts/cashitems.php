<?php
require_once('../includes/application_top.php');


/*$classes_query = tep_db_query("select * from " . TABLE_CLASSES);
  while ($classes_array = tep_db_fetch_array($classes_query)) {
		$classes[$classes_array['classes_id']] = $classes_array['classes_name'];
 }
 #	used to populate the transaction types drop down
$transaction_types_query = tep_db_query("select transactiontypes_id, transactiontypes_name from " . TABLE_TRANSACTIONTYPES." ORDER BY transactiontypes_name");
 while ($transactiontypes = tep_db_fetch_array($transaction_types_query)) {
	$transaction_types[$transactiontypes['transactiontypes_id']] = $transactiontypes['transactiontypes_name'];
}

$results_query = tep_db_query("SELECT divisioncategories_id, divisioncategories_name FROM " . TABLE_DIVISIONCATEGORIES);
 
while ($cats = tep_db_fetch_array($results_query)) {
	$divcats[$cats['divisioncategories_id']] = $cats['divisioncategories_name'];
}*/

 $glaccounts = getAccountLevels();
 
 getlables("305,291,306")
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3c.org/TR/1999/REC-html401-19991224/loose.dtd">
<HTML lang="<? echo $_SESSION['P_LANG'];?>"><HEAD>
<TITLE></TITLE>
<meta http-equiv="Content-Type" content="text/html; charset=utf8">
<META content="MSHTML 6.00.2900.3268" name=GENERATOR>
<link rel="stylesheet" href="../includes/Dojo/dijit/themes/claro/claro.css" media="screen">
<script src="../includes/Dojo/dojo/dojo.js"
        data-dojo-config="async:false, parseOnLoad:true">
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
<LINK href="../stylesheet.css" type=text/css rel=stylesheet>
<script type="text/javascript" src="../includes/javascript/gridsearch.js" language="javascript"></script>
<link  media="screen"  type="text/css" rel="stylesheet" href="../includes/javascript/de.css"></link>
<script type="text/javascript" src="../includes/javascript/jquery-1.6.2.min.js"></script>
<script type="text/javascript" src="../includes/javascript/jquery-ui-1.8.16.custom.min.js"></script>
<script type="text/javascript" src="../includes/javascript/jquery.pnotify.js"></script>
<script type="text/javascript" src="../includes/javascript/collapsiblepanel.js"></script>
<script type="text/javascript" src="../includes/javascript/commonfunctions.js"></script>
<script language="JavaScript"  type="text/javascript">

var url ='';
var iface = '';

url="../addedit.php";
 
function updateForm(){
	if(IsNullEmptyField('cashitems_name',"<?php echo $lablearray['305'];?> <?php echo $lablearray['291'];?>") && IsNullEmptyField('chartofaccounts_accountcode','<?php echo $lablearray['306'];?> <?php echo $lablearray['291'];?>')){
		getFormData('frmid=frmcashitems&cashitems_id=' + document.getElementById('cashitems_id').value + '&cashitems_name='+ document.getElementById('cashitems_name').value+'&chartofaccounts_accountcode='+document.getElementById('chartofaccounts_accountcode').value,document.getElementById('action').value,'frmcashitems');
		document.getElementById('cashitems_id').value ="";
		showResult('frmid=frmcashitems','txtHint')
	}
} 

</script>
<?php require('../'.DIR_WS_INCLUDES . 'initmenu.php');
getlables("21,305,306,21,242,267,20,307");
?>

<form action="#"  style="width:100%;height:auto;" id='frmcashitems' name='frmcashitems' onReset="document.getElementById('action').value='add';">
			
<input name="cashitems_id" type="hidden"  id="cashitems_id" value="">
<input name="action" type="hidden"  id="action" value="add">
 
 <table width="100%" border="0" cellspacing="0" cellpadding="0">
				<tr>
				<td  colspan="2">
				<table width="100%" border="0" cellspacing="2" cellpadding="0">
					  <tr>
					<td colspan="2" align="center"><span id="status" style='color:#006600;'></span></td>
				  </tr>
				   <tr>
					<td align="right" valign="bottom"> <?php echo $lablearray['305'];?></td>
					<td ><?php echo tep_draw_input_field('cashitems_name','','',false,'text',$retainvalues,'60'); ?><?php echo TEXT_FIELD_REQUIRED;?></td>
				  </tr>
				  <tr>
					<td align="right" valign="bottom"><?php echo $lablearray['306'];?></td>
					<td ><?php echo DrawComboFromArray($glaccounts,'chartofaccounts_accountcode','')?><?php echo TEXT_FIELD_REQUIRED;?></td>
				  </tr>
                 
				  <tr height="25"  >
					<td ></td>
					<td ></td>
				</tr>
				 <tr height="25">
					<td align="right"></td>
					<td  align="center">&nbsp;<input  type="reset" value="  <?php echo $lablearray['242'];?>  " id='reset' class="actbutton"><input  type="reset" value="  <?php echo $lablearray['307']; ?>  " id='reset' class="actbutton"  onclick="document.getElementById('action').value='add'"><input  id="save" type="button" value="  <?php echo $lablearray['20']; ?>  "  onClick="updateForm()" class="actbutton"></td>
				</tr>				 
				</table>				
				<tr>
					<td colspan="2" align="center">
						<table width="100%" border="0" cellspacing="1" cellpadding="0">						 
						  <tr>
							<td  colspan="2" align="right"><?php echo $lablearray['21'];?><input name="search"  value="" type="text" size="50" id='search' onKeyUp="showResult('searchterm='+this.value+'&frmid=frmcashitems&action=search','txtHint')"/><input type="button" value=" <?php echo $lablearray['21']; ?>" onClick="showResult('frmid=frmcashitems&action=search&searchterm='+document.getElementById('search').value,'txtHint');" class="actbutton"><input name="download" type="button" id="download" onClick="parent.document.location='downloadlist.php?columncheck=7&Cash_Items&timestamp=<?php echo strtotime("now"); ?>'" value="<?php echo $lablearray['267'];?>" class="actbutton"></td>
							<td></td>
						  </tr>
						</table>					
					 </td>
				</tr>
				<tr>
					<td colspan="2" id='txtHint' align="center"></td>
				</tr>							
				</table> 


			</form>					
	<?php require('../'.DIR_WS_INCLUDES . 'userfooter.php'); ?>			
  <script language="JavaScript"  type="text/javascript">
     showResult('frmid=frmcashitems','txtHint')
  </script>	  
</BODY>
</HTML>
