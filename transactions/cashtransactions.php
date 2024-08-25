<?php
require_once('../includes/application_top.php');

$cashaccounts_query = tep_db_query("select * from " . TABLE_CASHACCOUNTS);
  while ($cashaccounts_array = tep_db_fetch_array($cashaccounts_query)) {
		$cashaccounts[$cashaccounts_array['chartofaccounts_accountcode']] = $cashaccounts_array['cashaccounts_name']." ".$cashaccounts_array['chartofaccounts_accountcode'];
 }
 
 $cashitems_query = tep_db_query("select * from " . TABLE_CASHITEMS);
  while ($cashitems_array = tep_db_fetch_array($cashitems_query)) {
		$cashitems[$cashitems_array['chartofaccounts_accountcode']] = $cashitems_array['cashitems_name'];
 }
 $glaccounts = getAccountLevels();
 
 getlables("317,291,316,26,271,318,264,321,186");
 

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3c.org/TR/1999/REC-html401-19991224/loose.dtd">
<HTML lang="<? echo $_SESSION['P_LANG'];?>"><HEAD>
<TITLE><?php echo $lablearray['313'];?>/<?php echo $lablearray['314'];?></TITLE>
<meta http-equiv="Content-Type" content="text/html; charset=utf8">
<META content="MSHTML 6.00.2900.3268" name=GENERATOR>
<link rel="stylesheet" href="../styles/CALENDAR.CSS">
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
<LINK href="../stylesheet.css" type=text/css rel=stylesheet>
<script language="JavaScript" src="../includes/javascript/calendar_us.js"></script>
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
 

function viewreport(){
	
	if(!IsNullEmptyField('txtFrom',"<?php echo $lablearray['186'];?>") || !IsNullEmptyField('txtTo',"<?php echo $lablearray['186'];?>")){
		return;
	}	
		
	window.open('../downloadlistpdf.php?rcode=CASHRPT&txtFrom='+document.getElementById('txtFrom').value+'&txtTo='+document.getElementById('txtTo').value+'&cashaccounts_id='+document.getElementById('cashaccounts_id').value+'&cashitems='+document.getElementById('cashitems').value+'&branchcode='+document.getElementById('branchcode').value);
	
}

</script>
<?php require('../'.DIR_WS_INCLUDES . 'initmenu.php');
getlables("248,311,316,306,317,271,249,318,314,319,21,267,242,307,20,320,264,42");
?>

<form action="#"  style="width:100%;height:auto;" id='frmcashentries' name='frmcashentries' onReset="document.getElementById('action').value='add';">
			
<input name="cashitems_id" type="hidden"  id="cashitems_id" value="">
<input name="action" type="hidden"  id="action" value="add">
 <?php echo $_SERVER['DOCUMENT_ROOT'];?>
 <table width="100%" border="0" cellspacing="0" cellpadding="0">
		<tr>
		<td  colspan="2">
		<table width="100%" border="0" cellspacing="2" cellpadding="3">
		<tr>
			<td colspan="2" align="center"><span id="status" style='color:#006600;'></span></td>
		  </tr>
		  <tr>
			<td valign="bottom"><?php echo $lablearray['38'];?><br>
			<input name="txtFrom"   id="txtFrom" type="text" size="15" width="32" value="<?php echo changeMySQLDateToPageFormat($students_dateenrolled);?>"  readonly/>
			<script language="JavaScript">
				new tcal ({
					// form name
					'formname': 'frmcashentries',
					// input name
					'controlname': 'txtFrom'
				});
			</script>
			</td>
			<td valign="bottom"><?php echo $lablearray['39'];?><br>
			<input name="txtTo"  id="txtTo" type="text" size="15" width="32" value="<?php echo changeMySQLDateToPageFormat($students_dateenrolled);?>"  readonly/>
			<script language="JavaScript">
				new tcal ({
					// form name
					'formname': 'frmcashentries',
					// input name
					'controlname': 'txtTo'
				});
			</script>
			</td>
		
		  </tr>
		 
		   <tr>
			<td  valign="bottom"><?php echo $lablearray['311'];?><br>
			<select id='cashaccounts_id' name='cashaccounts_id'>
			<option value=""><?php echo $lablearray['42'];?></option>
			<?php
			
				foreach($cashaccounts as $key=>$value)
				{
				echo "<option value='".$key."' id='".$key."'>".$value."</option>";
				
				}
			
			?>
			</select>
			</td>
			<td >
			<?php echo $lablearray['316'];?><br><?php echo DrawComboFromArray($branchcodelist,'branchcode','')?>
			</td>
		  </tr>
		  <tr>
			<td >
			<?php echo $lablearray['318'];?><br>
			<select id='cashitems' name='cashitems'>
			<option value=""><?php echo $lablearray['42'];?></option>
			<?php
			
				foreach($cashitems as $key=>$value)
				{
				echo "<option value='".$key."' id='".$key."'>".$value.":".$key."</option>";
				
				}
			
			?>
			</select>
			</td>
			<td >			
			</td>
		  </tr>
							 
		  <tr height="25">
			<td align="right"></td>
			<td  align="center">&nbsp;<input  type="reset" value="  <?php echo $lablearray['242'];?>  " id='reset' class="actbutton"><input  id="save" type="button" value="<?php echo $lablearray['248']; ?>  "  onClick="viewreport()" class="actbutton"></td>
		</tr>				 
		</table>				
									
		</table> 

</form>					
<?php require('../'.DIR_WS_INCLUDES . 'userfooter.php'); ?>			
<script language="JavaScript"  type="text/javascript">
    // showResult('frmid=frmcashentries','txtHint')
</script>	  
</BODY>
</HTML>
