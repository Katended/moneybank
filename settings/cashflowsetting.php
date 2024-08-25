<?php
require_once('../includes/application_top.php');

$glaccounts = getAccountLevels();


$results = tep_db_query(" SELECT * FROM ".TABLE_CFREPORTS);
$reports = array();
while($reports_array = tep_db_fetch_array($results)){
	$reports['cfReports_id'] = $reports_array['cfReports_name'];
}

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
<script type="text/javascript" src="../includes/javascript/gridsearch.js" language="javascript"></script>
<link  media="screen"  type="text/css" rel="stylesheet" href="../includes/javascript/de.css"></link>
<script type="text/javascript" src="../includes/javascript/jquery-1.6.2.min.js"></script>
<script type="text/javascript" src="../includes/javascript/jquery-ui-1.8.16.custom.min.js"></script>
<script type="text/javascript" src="../includes/javascript/jquery.pnotify.js"></script>
<script type="text/javascript" src="../includes/javascript/commonfunctions.js"></script>
<script language="JavaScript"  type="text/javascript">
	
	var url ='';
	var iface = '';
	url = "../addedit.php";
		
	
	function selectReportName (obj){	
		
		switch(obj.id){
		
		case 'cfReports_name1':
		
			document.getElementById('cfReports_name').disabled =false;
			document.getElementById('cfReports_name').value = obj.value;
			document.getElementById('cfReports_id').value = obj.options[obj.selectedIndex].id;	
			document.getElementById('action1').value ='update';
			showResult('frmid=frmcashreport&action=edit&rid='+ obj.options[obj.selectedIndex].id,'')
			document.getElementById('txtHint').innerHTML ="";
			document.getElementById('header').disabled =false;
			document.getElementById('cfheader_cfincrease').disabled =false;
			break;
		
		case 'header1':
		
			document.getElementById('header').disabled =false;
			document.getElementById('cfheader_cfincrease').disabled = false;
			document.getElementById('header').value = obj.value;
			document.getElementById('action2').value ='update';
			document.getElementById('cfheader_id').value = obj.options[obj.selectedIndex].id;
			showResult('frmid=frmcashreport&action=edit&hid='+ obj.options[obj.selectedIndex].id,'');
			document.getElementById('txtHint').innerHTML ="";
			document.getElementById('lable').disabled=false;
			//showResult('frmid=frmcashreport&id='+ obj.options[obj.selectedIndex].id,'txtHint')
			break;
			
		case 'lable1':
	
			document.getElementById('lable').disabled =false;
			document.getElementById('lable').value = obj.value;
			document.getElementById('action3').value ='update';
			document.getElementById('cflabel_id').value = obj.options[obj.selectedIndex].id;	
			showResult('frmid=frmcashreport&action=edit&id1='+ obj.options[obj.selectedIndex].id,'');
			//document.getElementById('txtHint').innerHTML ="";
			window.setTimeout("showResult('frmid=frmcashreport&action=edit&id="+ document.getElementById('cfheader_id').value+"','txtHint')",2000);
				
			break;
				
		}
		
	}	
	function UpdateForm(section,action){
			if(action =='del'){
				switch(section){
				case '1':
					showResult('frmid=frmcashreport&section=1&action=delete&id='+document.getElementById('cfReports_id').value,'definedrpt')
					break;
				
				case '2':					
					showResult('frmid=frmcashreport&section=2&action=delete&id='+ document.getElementById('cfheader_id').value+'&','definedheadersrpt')
					break;
					
				case '3':					
					showResult('frmid=frmcashreport&section=3&action=delete&id='+ document.getElementById('cflabel_id').value,'definedlabels')
					break;
						
				}
				
				
			
			}else{
				// get is debit
				var IsDebit="";
				
				if(document.getElementById('debit').checked==true){
					IsDebit ='Y';
				}
				
				if(document.getElementById('credit').checked==true){
					IsDebit ='N';
				}
				
				if(document.getElementById('cfheader_cfincrease').checked==true){
					cfheader_cfincrease ='Y';
				}else{
					cfheader_cfincrease ='N';
				
				}	
				
				switch(section){
								
				case '1':
					showResult('frmid=frmcashreport&section=1&name1='+ document.getElementById('cfReports_name').value+'&action=' + document.getElementById('action1').value+'&hid='+ document.getElementById('cfReports_id').value,'definedrpt')
					showResult('frmid=frmcashreport','');	
					document.getElementById('header').disabled =false;				
					document.getElementById('txtHint').innerHTML ="";
					document.getElementById('cfReports_name').value="";
					break;
				
				case '2':
					if(document.getElementById('cfReports_id').value==""){
						alert('Please select report name.');
						return
					}				
					showResult('frmid=frmcashreport&section=2&name2='+ document.getElementById('header').value+'&action='+ document.getElementById('action2').value+'&hid='+document.getElementById('cfReports_id').value+'&cfheader_id='+document.getElementById('cfheader_id').value+'&cfheader_cfincrease='+cfheader_cfincrease,'')
					document.getElementById('lable').disabled=false;
					document.getElementById('txtHint').innerHTML ="";
					document.getElementById('header').value="";		
					document.getElementById('cfheader_cfincrease').checked = false;			
					showResult('frmid=frmcashreport&action=edit&rid='+ document.getElementById('cfReports_id').value,'')
					document.getElementById('txtHint').innerHTML ="";
					break;
					
				case '3':
					if(document.getElementById('header').value==""){
						alert('Please select report header.');
						return
					}
					showResult('frmid=frmcashreport&section=3&id='+document.getElementById('cflabel_id').value+'&name3='+ document.getElementById('lable').value+'&action=' + document.getElementById('action3').value+'&hid='+document.getElementById('cfheader_id').value+'&fr='+document.getElementById('chartofaccounts_accountcode_from').value+'&to='+document.getElementById('chartofaccounts_accountcode_to').value+ '&IsDebit='+IsDebit,'')
					document.getElementById('lable').value="";
					//window.setTimeout("showResult('frmid=frmcashreport&action=edit&id="+ obj.options[obj.selectedIndex].id+"','txtHint')",2000);				
					//showResult('frmid=frmcashreport&action=edit&hid='+ document.getElementById('cfheader_id').value,'');
					
					showResult('frmid=frmcashreport&action=edit&hid='+ document.getElementById('cfheader_id').value,'');
					document.getElementById('txtHint').innerHTML ="";
					break;
						
				}
			
			}	
			
	}	
	
	
	
	function clearform(section){
		switch(section){
		
			case '1':
				document.getElementById('cfReports_name').value='';
				document.getElementById('action1').value='add';		
				break;
			
			case '2':
				document.getElementById('header').value='';
				document.getElementById('action2').value='add';
				break;
				
			case '3':
				document.getElementById('lable').value='';
				document.getElementById('action3').value='add';
				break;
					
		}
	}
	

</script>
<?php require('../'.DIR_WS_INCLUDES . 'initmenu.php'); ?>
<form action="" method="post" style="width:100%;height:auto;" id='frmcashreport' name='frmcashreport'>
		
			 
			 <input type="hidden" id="cfReports_id" name="cfReports_id">
			  <input type="hidden" id="cfheader_id" name="cfheader_id">
			  <input type="hidden" id="cflabel_id" name="cflabel_id">			  
			 <input type="hidden" id="action1" name="action1" value="add">
			  <input type="hidden" id="action2" name="action2" value="add">
			   <input type="hidden" id="action3" name="action3" value="add">
			 <table width="100%" border="0" cellspacing="0" cellpadding="0">
				<tr>
				<td  colspan="2">
				<table width="100%" border="0" cellspacing="2" cellpadding="0">
					
				   <tr>
					<td colspan="2" align="center"><?php echo TEXT_FIELD_REQUIRED;?>Required</td>
				  </tr>
				   <tr>
					<td >
					Defined reports		
					</td>
					<td id='definedrpt'></td>
				  </tr>
				   <tr>
					<td >
					Report Title					
					</td>
					<td >
						<input type="text" value="" name="cfReports_name" id='cfReports_name' size="80" ><?php echo TEXT_FIELD_REQUIRED;?>
						</td>
				  </tr>
				
				  <tr height="25">
					<td align="right" colspan="2" ><input  type="button" value="  Add New  " id='reset' class="actbutton" onClick="clearform('1')"><input  type="button" value="  Save  " id='save' class="actbutton" onClick="UpdateForm('1','')"></td>
				
				</tr>
				 <tr height="25">
					<td align="center" colspan="2"><hr></td>			
				</tr>
				 
				 <tr>
					<td >
					Defined headers					
					</td>
					<td id='definedheadersrpt'></td>
				  </tr>
				  
				  <tr>
					<td >
					Header name
					</td>
					<td ><input type="text" value="" name="header" id='header' size="80" disabled="disabled"><?php echo TEXT_FIELD_REQUIRED;?>
				</td>
				</tr>
				<tr>
					<td >
					Increases Cashflow?
					</td>
					<td ><input type="checkbox" value="Y" name="cfheader_cfincrease" id='cfheader_cfincrease' disabled="disabled"><?php echo TEXT_FIELD_REQUIRED;?>
				</td>
				</tr>
				  <tr height="25">
					<td align="right" colspan="2" ><input  type="button" value="  Add New  " id='reset' class="actbutton" onClick="clearform('2')"><input  type="button" value="  Save  " id='save' class="actbutton" onClick="UpdateForm('2','')"></td>
				
				</tr>
				 <tr height="25">
					<td align="center" colspan="2"><hr></td>			
				</tr>
				  <tr>
					<td >
					Defined Lables		
					</td>
					<td id='definedlabels'>
				</td>
				  </tr>
				  <tr>
					<td >
					Lable name
					</td>
					<td ><input type="text" value="" name="lable" id='lable' size="80" disabled="disabled"><?php echo TEXT_FIELD_REQUIRED;?>
				</td>
				</tr>
				  <tr>
					<td >
					Accounts 			
					</td>
					<td >From <?php echo DrawComboFromArray($glaccounts,'chartofaccounts_accountcode_from','')?>  To  <?php echo DrawComboFromArray($glaccounts,'chartofaccounts_accountcode_to','')?>
				</td>
				  </tr>
				  
				  <tr>
					<td >
						
					</td>
					<td ><input type="radio" name="debitcredit" id="debit" value='D'>Debit <input type="radio" name="debitcredit" id="credit" value='C'> Credit
     				</td>
				  </tr>
				  
				  <tr height="25">
					<td align="right" colspan="2" ><input  type="button" value="  Add New  " id='reset' class="actbutton" onClick="clearform('3')"><input  type="button" value="  Save  " id='save' class="actbutton" onClick="UpdateForm('3','')"></td>
				
				</tr>
				
				
												 
				</table>				
				
				<tr>
					<td colspan="2" id='txtHint' align="center"></td>
				</tr>							
				</table> 


				</form>					
	<?php require('../'.DIR_WS_INCLUDES . 'userfooter.php'); ?>		
	  <script language="JavaScript"  type="text/javascript">
		showResult('frmid=frmcashreport','');
		document.getElementById('txtHint').innerHTML ="";
				
	  </script>
		 	  
</BODY>
</HTML>
