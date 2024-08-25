<?php
require_once('../includes/application_top.php');
$glaccounts = getAccountLevels();
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3c.org/TR/1999/REC-html401-19991224/loose.dtd">
<HTML lang="<? echo $_SESSION['P_LANG'];?>"><HEAD>
<TITLE>Public Holidays</TITLE>
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
<link rel="stylesheet" href="../styles/calendar.css">
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
var publicholidays_reoccurs="N";
url="../addedit.php";

function updateForm(){
	if(!IsNullEmptyField('publicholidays_date','Date missing.')){
		return false;
	}
	
	if(document.getElementById('publicholidays_reoccurs').checked==true){
		publicholidays_reoccurs="Y";	
	}
	
	showResult('frmid=frmpublicholidays&publicholidays_id=' + document.getElementById('publicholidays_id').value + '&publicholidays_date='+ document.getElementById('publicholidays_date').value+'&publicholidays_reoccurs='+document.getElementById('publicholidays_reoccurs').value+'&publicholidays_description='+document.getElementById('publicholidays_description').value+'&action='+document.getElementById('action').value,'');
		
		document.getElementById('publicholidays_reoccurs').checked =false;
		document.getElementById('publicholidays_date').value ="";
		document.getElementById('publicholidays_description').value ="";
		document.getElementById('action').value ="add";
	
}

</script>
<?php require('../'.DIR_WS_INCLUDES . 'initmenu.php'); ?>

<form action="#" method="post" style="width:100%;height:auto;" id='frmpublicholidays' name='frmpublicholidays' onReset="document.getElementById('action').value='add';">
			
			<input name="publicholidays_id" type="hidden"  id="publicholidays_id" value="">
			<input name="action" type="hidden"  id="action" value="add">
			
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
					<td align="right" valign="bottom"></td>
					<td >
                   Date&nbsp;<br>
     <input name="publicholidays_date" class="yellowfield"  id="publicholidays_date" type="text" size="15" width="32" value="<?php echo changeMySQLDateToPageFormat($students_dateenrolled);?>"  readonly/>
		<script language="JavaScript">
			new tcal ({
				// form name
				'formname': 'frmpublicholidays',
				// input name
				'controlname': 'publicholidays_date'
			});
		</script>
		<?php echo TEXT_FIELD_REQUIRED;?>
                    </td>
				  </tr>
                <tr height="25">
					<td ></td>
					<td >
                    <br>
					Description<br>
					<?php echo tep_draw_textarea_field('publicholidays_description', '', '50', '7', '','', $retainuserinput);?></td>
				</tr> 
				<tr height="25">
					<td ></td>
					<td ><input name="publicholidays_reoccurs" type="checkbox" value="Y" id="publicholidays_reoccurs">&nbsp;Reoccurs</td>
				</tr>

				



				 <tr height="25">
					<td align="right"></td>
					<td  align="center">&nbsp;<input  type="reset" value="  Clear  " id='reset' class="actbutton"><input  id="save" type="button" value="  Save  "  onClick="updateForm()" class="actbutton"></td>
				</tr>
				</table>
				<tr>
					<td colspan="2" align="center">
						<table width="100%" border="0" cellspacing="1" cellpadding="0">
						  <tr>
							<td  colspan="2" align="right">Search <input name="search"  value="" type="text" size="50" id='search' onKeyUp="showResult('searchterm='+this.value+'&frmid=frmpublicholidays&action=search','txtHint')"/><input type="button" value="Search" onClick="showResult('frmid=frmpublicholidays&action=search&searchterm='+document.getElementById('search').value,'txtHint');" class="actbutton"><input name="download" type="button" id="download" onClick="parent.document.location='downloadlist.php?columncheck=7&Holidays&timestamp=<?php echo strtotime("now"); ?>'" value="Download" class="actbutton"></td>
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
			 showResult('frmid=frmpublicholidays','txtHint')
		  </script>

</BODY>
</HTML>
