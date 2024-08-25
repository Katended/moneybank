<?php
require_once('../includes/application_top.php');
require_once('../includes/functions/password_funcs.php');
require_once('../includes/classes/class.listmanager.php');

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3c.org/TR/1999/REC-html401-19991224/loose.dtd">
<HTML <?php echo HTML_PARAMS; ?>>
<HEAD>
<TITLE>TheBursar 2010</TITLE>
<meta http-equiv="Content-Type" content="text/html; charset=utf8">
<script language="javascript" src="../includes/javascript/commonfunctions.js"></script>
<script language="JavaScript" src="../includes/javascript/calendar_us.js"></script>
<script language="javascript" src="../includes/javascript/checkform.js"></script>
<LINK href="../stylesheet.css" type=text/css rel=stylesheet>
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
<link rel="stylesheet" href="../styles/CALENDAR.CSS"> 
<META content="MSHTML 6.00.2900.3268" name=GENERATOR>
<script type="text/javascript" src="../includes/javascript/gridsearch.js" language="javascript"></script>
<link  media="screen"  type="text/css" rel="stylesheet" href="../includes/javascript/de.css"></link>
<script type="text/javascript" src="../includes/javascript/jquery-1.6.2.min.js"></script>
<script type="text/javascript" src="../includes/javascript/jquery-ui-1.8.16.custom.min.js"></script>
<script type="text/javascript" src="../includes/javascript/jquery.pnotify.js"></script>

<script language="JavaScript"  type="text/javascript">

var n = '';
var a = '';
var c = '';
var url ='';

url="remote.php";

function getdata(paging,formid,action,searchterm) { 
	str= paging + '&frmid=frmrevenuerpt'+ '&tran_datefrom='+encodeURI(document.getElementById('tran_datefrom').value) + '&tran_dateto=' + encodeURI(document.getElementById('tran_dateto').value);// + '&operator=' + encodeURI(document.getElementById('operator').value) + '&amount='+ encodeURI(document.getElementById('txtamount').value);
	document.getElementById('txtHint').innerHTML = "<p style='text-align:center'>loading your request...<br><img src='images/loading.gif'></p>";
	makeRequest(str);	
}
</script>
<?php require('../'.DIR_WS_INCLUDES . 'initmenu.php'); ?>	

<form action="revenuereport.php" method="post"  id="frmrevenuerpt" style="height:100%;" name="frmrevenuerpt" onSubmit="return IsNullEmptyField('tran_datefrom','Please select the From date') && IsNullEmptyField('tran_dateto','Please select the To date')">
<fieldset>
<legend>View Revenue Report</legend>
<span style="float:right;margin-top:10px;"><img src="../images/PDF.GIF" title="Download to PDF" border="0" class="imgclass" onClick="openPopupListWindow('downloadlistpdf.php')"><img src="../images/EXCL.GIF" title="Download to Excel"  onClick="parent.document.location='downloadlist.php?columncheck=7&filename=Revenue_reportt&timestamp=<?php echo strtotime("now"); ?>'" class="imgclass"></span>
<table width="100%" border="0" cellspacing="0" cellpadding="2">
<tr>
<td><img src='../images/MONEY.JPG' border='0'></td><td>
From:
	<?php if($_GET['id']!=""){
		$classes_nexttermends = changeMySQLDateToPageFormat($classes_nexttermends);
	}	
	?>
	<input name="tran_datefrom" class="yellowfield"  id="tran_datefrom" type="text" size="15" width="32" value="<?php echo changeMySQLDateToPageFormat($tran_datefrom);?>"  readonly/>
	<script language="JavaScript">
		new tcal ({
			// form name
			'formname': 'frmrevenuerpt',
			// input name
			'controlname': 'tran_datefrom'
		});
	</script>                     
	To
	<?php	
	if($_GET['id']!=""){
		$classes_nexttermends = changeMySQLDateToPageFormat($classes_nexttermends);
	}
	?>
	<input name="tran_dateto" class="yellowfield"  id="tran_dateto" type="text" size="15" width="32" value="<?php echo changeMySQLDateToPageFormat($tran_dateto);?>"  readonly/>
	<script language="JavaScript">
		new tcal ({
			// form name
			'formname': 'frmrevenuerpt',
			// input name
			'controlname': 'tran_dateto'
		});
	</script> 
	<input  type="button" value="Search"  onClick="getdata();" class="actbutton">      	
</td>
</tr>
</table>
</fieldset>
<span id='txtHint'></span>           
</form>				
<?php require('../'.DIR_WS_INCLUDES . 'userfooter.php'); ?>
</BODY></HTML>