	<?php
require_once('../includes/application_top.php');
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3c.org/TR/1999/REC-html401-19991224/loose.dtd">
<HTML lang="<? echo $_SESSION['P_LANG'];?>"><HEAD>
<TITLE></TITLE>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
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
<script type="text/javascript" src="../includes/javascript/collapsiblepanel.js"></script>
<script type="text/javascript" src="../includes/javascript/commonfunctions.js"></script>
<script language="JavaScript"  type="text/javascript">

var url ='';
var iface = '';

url="../addedit.php";
 
function updateForm(){

	
	if(IsNullEmptyField('socialsecurityorg_id','Please enter the organisation name.') && IsNullEmptyField('socialsecurityrates_rate','Please enter the rate')){
		
		if(document.getElementById('socialsecurityrates_iscurrent').checked==true){
			socialsecurityrates_iscurrent ='Y';	
		}else{
			socialsecurityrates_iscurrent ='N';		
		}
		
		showResult('frmid=frmsocialsecurityrates&socialsecurityrates_id=' + document.getElementById('socialsecurityrates_id').value + '&socialsecurityrates_rate='+ document.getElementById('socialsecurityrates_rate').value+'&action='+document.getElementById('action').value+'&socialsecurityrates_iscurrent='+socialsecurityrates_iscurrent,'');
	
		SelectItemInList("socialsecurityorg_id","");	
		document.getElementById('socialsecurityorg_id').value ="";
		document.getElementById('socialsecurityrates_rate').value ="";		
		document.getElementById('socialsecurityrates_iscurrent').checked =false;
		document.getElementById('action').value ="add";
	
	}
} 

</script>
<?php require('../'.DIR_WS_INCLUDES . 'initmenu.php'); ?>
<style type="text/css">
<!--
body,td,th {
	font-size: 0.9em;
}
-->
</style><form action="#" method="post" style="width:100%;height:auto;" id='frmsocialsecurityrates' name='frmsocialsecurityrates' onReset="document.getElementById('action').value='add';">
			<h1>Provident Funds(P.F) facilit(Social Security)</h1>
			<input name="socialsecurityrates_id" type="hidden"  id="socialsecurityrates_id" value="">
			<input name="action" type="hidden"  id="action" value="add">
			 
			 <table width="100%" border="0" cellspacing="0" cellpadding="0">
				<tr>
				<td  colspan="2" valign="top">
				<table width="100%" border="0" cellspacing="2" cellpadding="0">
					  <tr>
					<td colspan="2" align="center"><span id="status" style='color:#006600;'></span></td>
				  </tr>
				   <tr>
					<td colspan="2" align="center"><?php echo TEXT_FIELD_REQUIRED;?>Required</td>
				  </tr>
				   <tr>
					<td valign="bottom">Organisation</td>
					<td align="left">
                    <?php
                    
					$results_query = tep_db_query("SELECT * FROM ".TABLE_SOCIALSECURITYORG);
					
					?>
                    <select name="socialsecurityorg_id" id="socialsecurityorg_id" onChange="showResult('frmid=frmsocialsecurityrates&socialsecurityorg_id='+this.value+'&action=view','txtHint')">
                    <option value="">Select Organisation</option>
                    <?PHP 
                    while($results = tep_db_fetch_array($results_query)){
                    
                      echo "<option id='".$results['socialsecurityorg_id']."' value='".$results['socialsecurityorg_id']."'>".$results['socialsecurityorg_name']."</option>";
                     
                    }	
                    ?>
                    
                    </select>
                    </td>
				  </tr>
                                   
				  <tr height="25">
					<td >Rate</td>
					<td >&nbsp;<input name="socialsecurityrates_rate" id="socialsecurityrates_rate" type="text" onKeyPress="return EnterNumericOnly(event,'socialsecurityrates_rate')">%<?php echo TEXT_FIELD_REQUIRED;?></td>
				</tr>
                
                 <tr height="25">
					<td ></td>
					<td >&nbsp;<input name="socialsecurityrates_iscurrent" id="socialsecurityrates_iscurrent" type="checkbox"> Activate this rate</td>
				</tr>
				 <tr height="25">
					<td align="right"></td>
					<td  align="center">&nbsp;<input  type="reset" value="  Clear  " id='reset' class="actbutton"><input  id="save" type="button" value="  Save  "  onClick="updateForm()" class="actbutton"></td>
				</tr>				 
				</table>				
				<tr>
					<td colspan="2" id='txtHint' align="center"></td>
				</tr>							
				</table> 


			</form>					
				
	<?php require('../'.DIR_WS_INCLUDES . 'userfooter.php'); ?>		
		 <script language="JavaScript"  type="text/javascript">
			 showResult('frmid=frmsocialsecurityrates','txtHint')
		  </script> 	  
</BODY>
</HTML>
