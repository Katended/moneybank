<?php
  require_once('../includes/application_top.php'); 
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3c.org/TR/1999/REC-html401-19991224/loose.dtd">
<HTML lang="fr-FR"><HEAD>
<TITLE>Update</TITLE>
<meta http-equiv="Content-Type" content="text/html; charset=utf8">
<META content="MSHTML 6.00.2900.3268" name=GENERATOR>
<LINK href="../stylesheet.css" type=text/css rel=stylesheet>
<link rel="stylesheet" href="../styles/CALENDAR.CSS">
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
	function updateForm(step,build){
	
		$('#status').html('<img src="../images/loading.gif" border="0">');
		showResult('frmid=frmautoupdate&action='+step+'&build='+build,"");	
		$('#status').html('');
	} 
</script>
<?php require('../'.DIR_WS_INCLUDES . 'initmenu.php'); 
getlables("169");
?>
	<form action="" method="POST" style="width:100%;height:100%;" id='frmautoupdate' name='frmautoupdate' >
		 <input name="action"  value="<?php if($_GET['action']!=""){ echo $_GET['action'];}else{echo 'add';}?>" id="action" type="hidden">
		 <table width="100%" border="0" cellspacing="0" cellpadding="0">
				<tr>
				<td  colspan="2">
				<table width="100%" border="0" cellspacing="0" cellpadding="0">				
				   <tr>
					<td colspan="2" align="center" id="newstat"><span id="status"></span></td>
				  </tr>				  
					 
				</table>				
				
				<tr height="200px">
					<td colspan="2" align="left"><div id='txtHint' class='scrollableupdate'></div></td>
				</tr>	
				
				<tr>
					<td align="right"></td>
					<td align="right"><input  type="button" value="  <?php echo $lablearray['169'];?>  "  onClick="updateForm('step1')" class="actbutton"></td>
				</tr>					
				</table>			
			</form>					
				
       <?php require('../'.DIR_WS_INCLUDES . 'userfooter.php'); ?>           
     
	  </BODY>
	  <script language="JavaScript"  type="text/javascript">
	  //	document.getElementById('txtHint').innerHTML = "<p style='text-align:center;color:#B8CBDA;'><?php echo $lablearray['259'];?>.<br><img src='../images/loading.gif'></p>";
		//window.setTimeout("showResult('frmid=frmautoupdate','txtHint')",2000);
		
	  </script>
 </HTML>