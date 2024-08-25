<?php
require_once('../includes/application_top.php');
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3c.org/TR/1999/REC-html401-19991224/loose.dtd">
<HTML lang="<? echo $_SESSION['P_LANG'];?>"><HEAD>
<TITLE></TITLE>
<meta http-equiv="Content-Type" content="text/html; charset=utf8">
<META content="MSHTML 6.00.2900.3268" name=GENERATOR>
<LINK href="../stylesheet.css" type=text/css rel=stylesheet>
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
	
	showResult('frmid=frmlanguages&translations_id=' + document.getElementById('translations_id').value + '&translations_eng='+ document.getElementById('translations_eng').value+'&translations_fr='+document.getElementById('translations_fr').value+'&translations_ja='+document.getElementById('translations_ja').value+'&translations_lug='+document.getElementById('translations_lug').value+'&action='+document.getElementById('action').value,'');
	document.getElementById('translations_eng').value ="";
	document.getElementById('translations_sp').value ="";
	document.getElementById('translations_fr').value ="";
	document.getElementById('translations_ja').value ="";
	document.getElementById('translations_lug').value ="";	
	document.getElementById('translations_eng').value ="";
	//showResult('frmid=frmlanguages','txtHint')
	
} 

</script>
<?php require('../'.DIR_WS_INCLUDES . 'initmenu.php'); 

getlables("630,631,632,633,634,635");

?>

<form action="#"  style="width:100%;height:auto;" id='frmlanguages' name='frmlanguages' onReset="document.getElementById('action').value='add';">
			
<input name="translations_id" type="hidden"  id="translations_id" value="">
<input name="action" type="hidden"  id="action" value="add">
 
 <table width="100%" border="0" cellspacing="0" cellpadding="0">
				<tr>
				<td  colspan="2">
				<table width="100%" border="0" cellspacing="2" cellpadding="0">
					  <tr>
					<td colspan="2" align="center"><span id="status" style='color:#006600;'></span></td>
				  </tr>
				 
				   <tr>
					<td  valign="middle"><?php echo $lablearray['630'];?><br><?php 					
					echo tep_draw_textarea_field('translations_eng', '', '50', '5', $translations_eng,'', $retainuserinput);
					 ?></td>
					<td >
					<?php echo $lablearray['631'];?><br>
					<?php 
					echo tep_draw_textarea_field('translations_fr', '', '50', '5', $translations_fr,'', $retainuserinput);
					 ?>
					</td>
				  </tr>
				   <tr>
					<td valign="middle">
					<?php echo $lablearray['632'];?><br><?php 					
					echo tep_draw_textarea_field('translations_sp', '', '50', '5', $translations_sp,'', $retainuserinput);
					 ?>
					</td>
					<td >
					<?php echo $lablearray['634'];?><br>
					<?php 
					echo tep_draw_textarea_field('translations_ja', '', '50', '5', $translations_ja,'', $retainuserinput);
					 ?>
					</td>
				  </tr>
				  				  
				  <tr>
					<td  valign="middle"><?php echo $lablearray['634'];?><br><?php 
					echo tep_draw_textarea_field('translations_lug', '', '50', '5', $translations_lug,'', $retainuserinput);
					 ?></td>
					<td ><?php echo $lablearray['635'];?><br>
					<?php 
					echo tep_draw_textarea_field('translations_swa', '', '50', '5', $translations_swa,'', $retainuserinput);
					 ?>
					</td>
				  </tr>
				  
				  <tr height="25">
					<td ></td>
					<td ></td>
				</tr>
				 <tr height="25">
					<td align="right"></td>
					<td  align="center">&nbsp;<input  type="reset" value="  Clear  " id='reset' class="actbutton"><input  type="reset" value="  Add New " id='reset' class="actbutton"  onclick="document.getElementById('action').value='add'"><input  id="save" type="button" value="  Save  "  onClick="updateForm()" class="actbutton"></td>
				</tr>				 
				</table>				
				<tr>
					<td colspan="2" align="center">
						<table width="100%" border="0" cellspacing="1" cellpadding="0">						 
						  <tr>
							<td  colspan="2" align="right">Search <input name="search"  value="" type="text" size="50" id='search' onKeyUp="showResult('searchterm='+this.value+'&frmid=frmlanguages&action=search','txtHint')"/><input type="button" value="Search" onClick="showResult('frmid=frmlanguages&action=search&searchterm='+document.getElementById('search').value,'txtHint');" class="actbutton"><input name="download" type="button" id="download" onClick="parent.document.location='downloadlist.php?columncheck=7&Fee_Categories&timestamp=<?php echo strtotime("now"); ?>'" value="Download" class="actbutton"></td>
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
     showResult('frmid=frmlanguages','txtHint')
  </script>	  
</BODY>
</HTML>
