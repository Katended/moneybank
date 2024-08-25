<?php
require_once('../includes/application_top.php');

if($_GET['action']=="process"){
	$students_sregno = replaces_underscores($_POST['students_sregno']);	
}else{
	$students_sregno = replaces_underscores($_GET['students_sregno']);
}

// get student name
$student_query = tep_db_query("SELECT CONCAT(students_firstname,' ',students_lastname) AS Name FROM " .TABLE_STUDENTS." WHERE students_sregno='".$students_sregno."'");	 
$student = tep_db_fetch_array($student_query);	
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3c.org/TR/1999/REC-html401-19991224/loose.dtd">
<HTML lang="<? echo $_SESSION['P_LANG'];?>"><HEAD>
<TITLE>Payments</TITLE>
<meta http-equiv="Content-Type" content="text/html; charset=utf8">
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
<META content="MSHTML 6.00.2900.3268" name=GENERATOR>
<link rel="stylesheet" href="../styles/CALENDAR.CSS"> 
<LINK href="../stylesheet.css" type=text/css rel=stylesheet>
<script type="text/javascript" src="../includes/javascript/gridsearch.js" language="javascript"></script>
<link  media="screen"  type="text/css" rel="stylesheet" href="../includes/javascript/de.css"></link>
<script type="text/javascript" src="../includes/javascript/jquery-1.6.2.min.js"></script>
<script type="text/javascript" src="../includes/javascript/jquery-ui-1.8.16.custom.min.js"></script>
<script type="text/javascript" src="../includes/javascript/jquery.pnotify.js"></script>
<script type="text/javascript" src="../includes/javascript/collapsiblepanel.js"></script>
<script type="text/javascript" src="../includes/javascript/commonfunctions.js"></script>
<script src="../includes/javascript/PopBox.js" type="text/javascript"></script>
<script language="JavaScript" src="../includes/javascript/calendar_us.js"></script>
<script language="JavaScript"  type="text/javascript">
	var url ='';
	var iface = '';
	url="../addedit.php"; 
</script>
<?php require('../'.DIR_WS_INCLUDES . 'initmenu.php'); ?>
			<form action="pupilpaymentreport.php?action=insert" method="post" style="width:100%;" id='frmpupilpaymentreport' name='frmpupilpaymentreport' onReset="document.getElementById('action').value='add';">
			 <input name="action"  value="<?php if($_GET['action']!=""){ echo $_GET['action'];}else{echo 'add';}?>" id="action" type="hidden">
			 <input name="students_sregno" id="students_sregno" type="hidden" value="<?php echo $students_sregno;?>">
			
				<table width="100%" border="0" cellspacing="0" cellpadding="0">				
				   <tr>
					<td ><h2 style='margin:0;'>Payments</h2></td>
					<td><h1 style='float:right;'><?php echo $student['Name'];?></h1></td>
				  </tr>
				  <tr>
					<td colspan="2"  align="right">
							From:<input name="datefrom" class="yellowfield"  id="datefrom" type="text" size="15" width="32" value=""  readonly/>
							<script language="JavaScript">
								new tcal ({
									// form name
									'formname': 'frmpupilpaymentreport',
									// input name
									'controlname': 'datefrom'
								});
							</script>
							To:<input name="dateto" class="yellowfield"  id="dateto" type="text" size="15" width="32" value=""  readonly/>
							<script language="JavaScript">
								new tcal ({
									// form name
									'formname': 'frmpupilpaymentreport',
									// input name
									'controlname': 'dateto'
								});
							</script> Item:	
							<select name="requirements_id" id="requirements_id" >
							<option id='' value=''>Select Item</option>
							<?PHP 
		
							$requirements_query = tep_db_query("SELECT requirements_id,requirements_name FROM ".TABLE_REQUIREMENTS." ORDER BY requirements_id");
						
							 while ($requirements = tep_db_fetch_array($requirements_query)){
									
								if($requirements['requirements_id']==$requirements_id){	
									echo "<option id='".$requirements['requirements_id']."' value='".$requirements['requirements_id']."'>".$requirements['requirements_name']."</option>";	
								}else{		
									echo "<option id='".$requirements['requirements_name']."' value='".$requirements['requirements_id']."'>".$requirements['requirements_name']."</option>";
								}
							}
							?>
							</select>						
							 Search <input name="search"  value="" type="text" size="36" id='search' onKeyUp="showResult('searchterm='+this.value+'&frmid=frmpupilpaymentreport&action=search&students_sregno=<?php echo $students_sregno;?>&datefrom=' + document.getElementById('datefrom').value +'&dateto='+ document.getElementById('dateto').value,'txtHint')" onFocus="clearTextFied('search')"/><input name="Go" type="button" value=" Go " type="button" onClick="showResult('searchterm=' + document.getElementById('search').value +'&frmid=frmpupilpaymentreport&action=search&students_sregno=<?php echo $students_sregno;?>&datefrom=' + document.getElementById('datefrom').value +'&dateto='+ document.getElementById('dateto').value+'&requirements_id='+document.getElementById('requirements_id').value,'txtHint')"></td>
				
				  </tr>
				  <tr>
					<td >					
							</td>
					<td align="right"><input name="download" type="button" class="bgbutton" id="download" onClick="openPopupListWindow('<?php echo DIR_WS_CATALOG;?>downloadlistpdf.php?rcode=PS&students_sregno=<?php echo $students_sregno;?>')" value="Download PDF"></td>
				  </tr>
				  <tr>
					<td colspan="2" id='txtHint' align="center"></td>
				</tr>										 
				</table>										
			</form>	
      <?php require('../'.DIR_WS_INCLUDES . 'userfooter.php'); ?>		
			 
</BODY>
</HTML>
