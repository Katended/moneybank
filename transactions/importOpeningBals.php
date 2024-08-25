<?php
 require_once('../includes/application_top.php');
 require_once('../includes/classes/READER.PHP');
    
 if($_SESSION['user_name']==''){
	//	tep_redirect(tep_href_link(FILENAME_LOGIN, 'page=' . $_GET['page']));	
	}

if($_SESSION['user_isadmin']=='N') {		
	//	$_SESSION['msg']="Access Denied.<br> You don't have have sufficient priviledges to view this page.";
	//	tep_redirect(tep_href_link('error.php'));	
 }
 
 
 
$save = 'N';

if(move_uploaded_file($_FILES['file']['tmp_name'],DIR_WS_TEMP_FILES.'temfile.xls')==1 && $_POST['save']=="N"){
	   
	if (eregi('(xls|XLS)$',$_FILES['file']['tmp_name'])) {		
		$fi = file(DIR_WS_TEMP_FILES.$_FILES['file']['name']);
		
		$fi2 = fopen(DIR_WS_TEMP_FILES.$_FILES['file']['name'],"w");
		
		foreach ($fi as $lne){
			$n = rtrim ($lne);
			fputs ($fi2,"$n\n");
		}
	}else{
		$save = 'N';
		//$msg =" Invalid file format! Please use a Microsoft Excel file to load clients ro contact Sync Software";	
	}
	
	$save = 'Y';
	$fullpath = DIR_WS_TEMP_FILES.'temfile.xls';
}else{
	
	if ($_FILES['file']['tmp_name']!=""){
		$save = 'N';
		//$msg =" File could not be moved. Please check folder Write Permissions of the Server.";	
	}
}
 
if($_POST['save']=="Y"){
	  $reader = new Spreadsheet_Excel_Reader();
	  $reader->setUTFEncoder('iconv');
	  $reader->setOutputEncoding('UTF-8');
	  
	  $reader->read($_POST['fullpath']);
	  	  
	  foreach($reader->sheets as $k=>$data){	  
		
		  $i = 0;
		  if ($data[numRows]!=0){
			  foreach($data['cells'] as $row){
				  
				  $students_sregno = tep_db_prepare_input($row[1]);
				  $requirements_id = tep_db_prepare_input($row[2]);
				  $studentspayments_amount = tep_db_prepare_input($row[3]);
				  $transactiontypes_code = tep_db_prepare_input($row[4]);
				  $studentspayments_voucher = tep_db_prepare_input($row[5]);
				  $user_id = tep_db_prepare_input($row[6]);
				  $feecategories_id = tep_db_prepare_input($row[7]);
												 				  
				  $db_query=tep_db_query("SELECT studentspayments_id FROM " . TABLE_STUDENTSPAYMENTS . " WHERE transactiontypes_code='".tep_db_input($transactiontypes_code)."' AND students_sregno='".tep_db_input($students_sregno)."'");
				  
				  if(!tep_db_num_rows($db_query)){
					  if($i!=0){						
						 tep_db_query("INSERT INTO " . TABLE_STUDENTSPAYMENTS . " (students_sregno,requirements_id,studentspayments_amount,transactiontypes_code,studentspayments_voucher,user_id,feecategories_id,studentspayments_balance,studentspayments_datecreated,studentspayments_dateupdated) values ('".tep_db_input($students_sregno)."','".tep_db_input($requirements_id)."','".tep_db_input($studentspayments_amount)."','". tep_db_input($transactiontypes_code)."','".tep_db_input($studentspayments_voucher)."','".tep_db_input($user_id)."','".tep_db_input($feecategories_id)."','".tep_db_input($studentspayments_amount)."',NOW(),NOW())");
					 	 $pid  = tep_db_insert_id();
					  }			
			  
			  	   }
					  $i++;						
				  }
			  }
	  }
	  
	  
	if(file_exists(DIR_WS_TEMP_FILES.'temfile.xls')){
		unlink(DIR_WS_TEMP_FILES.'temfile.xls');
	 }
	 
	 $msg ="Students' Opening balances have been successfully loaded.";
	
}?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3c.org/TR/1999/REC-html401-19991224/loose.dtd">
<HTML <?php echo HTML_PARAMS; ?>>
<HEAD>
<TITLE><?php echo NAME_OF_INSTITUTION;?></TITLE>
<META http-equiv=Content-Type content="text/html; charset=utf8">
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
<META content="MSHTML 6.00.2900.3268" name=GENERATOR>
<script language="JavaScript"  type="text/javascript">	
	var url ;
	var UInter;
	
	function sendRequest(){
	
		if(IsNullEmptyField('filepath','Please enter the location of the directory where your file is saved. \n Please remember the Full colon.  e.g C:/') && IsNullEmptyField('file','Please select a file')){
			UInter ='resulttext';
			url ='"addedit.php"'
			document.getElementById(UInter).innerHTML = "<p style='text-align:center;color:#B8CBDA;'>Loading Students..<br><img src='images/loading.gif'></p>";
			makeRequest('frmid=frmimportstudents&file='+document.getElementById('file').value + '&filepath='+document.getElementById('filepath').value);
		}
	}

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
<?php require('../'.DIR_WS_INCLUDES . 'initmenu.php'); 
getlables('255,256,20,2');
?>
 <form action="importOpeningBals.php?action=<?php echo $action;?>" method="post" enctype="multipart/form-data"  id="frmimportstudents" name="frmimportstudents">   
 	<span class="error"><?PHP echo $msg;?></span>
 	<?php echo tep_draw_hidden_field('save',$save); ?>
    <?php echo tep_draw_hidden_field('fullpath',$fullpath); ?>
	<table width="100%" border="0" cellspacing="2" cellpadding="2">
		
	  <tr>
		<td align="right" valign="bottom"><?php echo $lablearray['255'];?></td>
		<td><input name="file" type="file"  id="file"  size="80" width="100"></td>
	  </tr> 
	  <tr>
		<td colspan="2" align="center">
		<input name="submit" type="submit" value="<?php echo $lablearray['255'];?>" class="actbutton"><input name="submit" type="submit" value="<?php echo $lablearray['20'];?>" class="actbutton"><input name="Reset" type="reset" value="<?php echo $lablearray['20'];?>" class="actbutton"></td>
	  </tr>
	</table>
   
  </form>
  			
 <?php require('../'.DIR_WS_INCLUDES . 'userfooter.php'); ?>
</BODY>
</HTML>