<?php
require_once('../includes/application_top.php');

 $glaccounts = getAccountLevels();
 
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3c.org/TR/1999/REC-html401-19991224/loose.dtd">
<HTML lang="<? echo $_SESSION['P_LANG'];?>"><HEAD>
<TITLE></TITLE>
<meta http-equiv="Content-Type" content="text/html; charset=utf8">
<META content="MSHTML 6.00.2900.3268" name=GENERATOR>
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
<link rel="stylesheet" href="../styles/CALENDAR.CSS"> 
<script language="JavaScript" src="../includes/javascript/calendar_us.js"></script>
<script type="text/javascript" src="../includes/javascript/commonfunctions.js"></script>
<script language="JavaScript"  type="text/javascript">

var url ='';
var iface = '';
url="../addedit.php";

var from ='<?php echo $_POST['txtFrom'];?>';
var to = '<?php echo $_POST['txtto'];?>';

if(from!="" && to!=""){
	window.open('downloadlistpdf.php?rcode=TB', 'open_window', 'menubar, toolbar, location, directories, status, scrollbars, resizable, dependent, width=640, height=480, left=0, top=0')
}

function printOptions(){
	if(!IsNullEmptyField('txtprintoptions','Please select a print option') || !IsNullEmptyField('txtFrom','Please select date') ||  !IsNullEmptyField('txtTo','Please select date')){
		return;
	}else{
		url=document.getElementById('txtprintoptions').value+'rcode=TB&txtTo='+document.getElementById('txtTo').value+'&txtFrom='+document.getElementById('txtFrom').value;
		openPopupListWindow(url);
	}
}
</script>
<?php require('../'.DIR_WS_INCLUDES . 'initmenu.php'); ?>
			<form action="trialbal.php?action=insert" method="post" style="width:100%;height:auto;" id='frmtrialbalance' name='frmtrialbalance' onReset="document.getElementById('action').value='add';">
	
			
			<input name="bankbranches_id" type="hidden"  id="bankbranches_id" value="">
			<input name="action" type="hidden"  id="action" value="add">
			<input name="txtprintoptions" type="hidden"  id="txtprintoptions" value="">
			 
			 <table width="100%" border="0" cellspacing="0" cellpadding="0">
				<tr>
				<td  colspan="2">
				<table width="100%" border="0" cellspacing="2" cellpadding="0">
					<tr>
					<td colspan="2" align="center"><span id="status" style='color:#006600;'></span></td>
				  </tr>
				   <tr>
					<td colspan="2" align="center"><?php echo TEXT_FIELD_REQUIRED;?>Required</td>
				  </tr>
				  
				  <tr>
					<td colspan="2" align="center">
					
					
					<table width="100%" border="0" cellspacing="0" cellpadding="0">
					  <tr>
						<td>From&nbsp;</td><td><input name="txtFrom" class="yellowfield"  id="txtFrom" type="text" size="15" width="32" value="<?php echo changeMySQLDateToPageFormat($students_dateenrolled);?>"  readonly/>
							<script language="JavaScript">
								new tcal ({
									// form name
									'formname': 'frmtrialbalance',
									// input name
									'controlname': 'txtFrom'
								});
							</script><?php echo TEXT_FIELD_REQUIRED;?>&nbsp;</td><td>To &nbsp;</td><td>
							 <input name="txtTo" class="yellowfield"  id="txtTo" type="text" size="15" width="32" value="<?php echo changeMySQLDateToPageFormat($students_dateenrolled);?>"  readonly/>
							<script language="JavaScript">
								new tcal ({
									// form name
									'formname': 'frmtrialbalance',
									// input name
									'controlname': 'txtTo'
								});
							</script>                          
                            <?php echo TEXT_FIELD_REQUIRED;?> 
							</td>
					  </tr>
					   <tr>
						<td>From Account:&nbsp;</td><td><?php echo DrawComboFromArray($glaccounts,'accountcode_from','')?></td><td>To Account:&nbsp;</td>
						<td><?php echo DrawComboFromArray($glaccounts,'accountcode_to','')?>                         
							</td>
					  </tr>
					</table>
					</td>
				  </tr>
				
				 
				 <tr height="25">
					<td align="right"></td>
					<td  align="right">
				
					
					&nbsp;<img src="../images/PDF.GIF" title="Download to PDF" border="0" class="imgclass" onClick="if(document.getElementById('txtFrom').value=='' || document.getElementById('txtTo').value==''){alert('Invalid date specified. Please check the date range.')}else{openPopupListWindow('../downloadlistpdf.php?rcode=BD&txtTo='+document.getElementById('txtTo').value+'&txtFrom='+document.getElementById('txtFrom').value)}"><img src="../images/EXCL.GIF" title="Download to Excel"  onClick="parent.document.location='../downloadlist.php?columncheck=7&filename=Report&timestamp=<?php echo strtotime("now"); ?>'" class="imgclass"></td>
				</tr>				 
				</table>				
				
				 <tr height="25">
					<td align="center" colspan="2" ><?php echo drawPrintOptions();?></td>
				
				</tr>							
				</table> 


			</form>					
			
			
	<?php require('../'.DIR_WS_INCLUDES . 'userfooter.php'); ?>	 	  
</BODY>
</HTML>
