<?php
require_once('../includes/application_top.php');
$glaccounts = getAccountLevels();
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3c.org/TR/1999/REC-html401-19991224/loose.dtd">
<HTML lang="<? echo $_SESSION['P_LANG'];?>"><HEAD>
<TITLE></TITLE>
<meta http-equiv="Content-Type" content="text/html; charset=utf8">
<META content="MSHTML 6.00.2900.3268" name=GENERATOR>
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
	if(IsNullEmptyField('socialsecurityorg_name','Please enter the organisation name.')){
		showResult('frmid=frmsocialsecurity&socialsecurityorg_id=' + document.getElementById('socialsecurityorg_id').value + '&socialsecurityorg_name='+ document.getElementById('socialsecurityorg_name').value+'&action='+document.getElementById('action').value+'&chartofaccounts_accountcode='+document.getElementById('chartofaccounts_accountcode').value,'');
		document.getElementById('socialsecurityorg_id').value ="";
		document.getElementById('socialsecurityorg_name').value ="";
		document.getElementById('action').value ="add";
		SelectItemInList("chartofaccounts_accountcode","");		
	
	}
} 

</script>
<?php require('../'.DIR_WS_INCLUDES . 'initmenu.php'); ?>
			<form action="#" method="post" style="width:100%;height:auto;" id='frmsocialsecurity' name='frmsocialsecurity' onReset="document.getElementById('action').value='add';">
			<h1>Social Security Organisations</h1>
			<input name="socialsecurityorg_id" type="hidden"  id="socialsecurityorg_id" value="">
			<input name="action" type="hidden"  id="action" value="add">
			 
			 <table width="100%" border="0" cellspacing="0" cellpadding="0">
				<tr>
				<td  colspan="2" valign="top">
				<table width="100%" border="0" cellspacing="2" cellpadding="0">
					  <tr>
					<td colspan="2" align="center"></td>
				  </tr>
				   <tr>
					<td colspan="2" align="center"><?php echo TEXT_FIELD_REQUIRED;?>Required</td>
				  </tr>
				   <tr>
					<td >Social Security Oragnisation name</td>
					<td><?php echo tep_draw_input_field('socialsecurityorg_name',$socialsecurityorg_name,'',false,'text',$retainvalues,'50'); ?><?php echo TEXT_FIELD_REQUIRED;?></td>
				  </tr>
                                   
				  <tr height="25">
					<td >GL Account</td>
					<td ><?php echo DrawComboFromArray($glaccounts,'chartofaccounts_accountcode','');?></td>
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
							<td  colspan="2" align="right">Search <input name="search"  value="" type="text" size="50" id='search' onKeyUp="showResult('searchterm='+this.value+'&frmid=frmsocialsecurity&action=search','txtHint')"/><input type="button" value="Search" onClick="showResult('frmid=frmcreditors&action=search&searchterm='+document.getElementById('search').value,'txtHint');" class="actbutton"><input name="download" type="button" id="download" onClick="parent.document.location='downloadlist.php?columncheck=7&Fee_Categories&timestamp=<?php echo strtotime("now"); ?>'" value="Download" class="actbutton"></td>
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
			 showResult('frmid=frmsocialsecurity','txtHint')
		  </script>
	  
</BODY>
</HTML>
