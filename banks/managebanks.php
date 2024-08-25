<?php
require_once('../includes/application_top.php');
 $_parent = basename(__FILE__);

?>

<script type="text/javascript" language="javascript">

var url ='';
var iface = '';

url="../addedit.php";
 
function updateForm(){
	if(IsNullEmptyField('organisationname',"<?php echo $lablearray['995'];?>")){
	
		getFormData('frmid=frmbanks&licence_build=' + document.getElementById('licence_build').value + '&organisationname='+ document.getElementById('organisationname').value+'&licence_address='+document.getElementById('licence_address').value,document.getElementById('action').value,'frmbanks');
		document.getElementById('licence_build').value ="";
		showResult('frmid=frmbanks','txtHint')
	}
} 

</script>
<?php require('../'.DIR_WS_INCLUDES . 'pageheader.php');
getlables("994,242,307,914,21,267,611");?>

<fieldset> 

			<form action="managebanks.php?action=insert" method="post" style="width:100%;height:auto;" id='frmbanks' name='frmbanks' onReset="document.getElementById('action').value='add';">
			
			<input name="licence_build" type="hidden"  id="licence_build" value="">
			<input name="action" type="hidden"  id="action" value="add">
			 
			 <table width="100%" border="0" cellspacing="0" cellpadding="0">
				<tr>
				<td  colspan="2" valign="top">
				<table width="100%" border="0" cellspacing="2" cellpadding="0">
				 <tr>
					<td colspan="2" align="center"><span id="status" style='color:#006600;'></span></td>
				  </tr>
				   <tr>
					<td colspan="2" align="center"><?php echo TEXT_FIELD_REQUIRED;?></td>
				  </tr>
				   <tr>
					<td align="right" valign="middle"><?php echo $lablearray['994'];?></td>
					<td align="center"><?php echo tep_draw_input_field('organisationname','','',false,'text','','100'); ?><?php echo TEXT_FIELD_REQUIRED;?></td>
				  </tr>
				  
				  <tr>
					<td align="right" valign="middle"><?php echo $lablearray['611'];?></td>
					<td align="center"> <?php echo tep_draw_textarea_field('licence_address', 'licence_address', '30', '5', '','', '');?>
								<?php echo TEXT_FIELD_REQUIRED;?></td>
				  </tr>
                 
				  <tr height="25">
					<td ></td>
					<td ></td>
				</tr>
				 <tr height="25">
					<td align="right"></td>
					<td  align="center">&nbsp;<input  type="reset" value="  <?php echo $lablearray['242'];?>  " id='reset' class="actbutton"><input  id="save" type="button" value="  <?php echo $lablearray['914'];?>  "  onClick="updateForm()" class="actbutton"></td>
				</tr>				 
				</table>				
				<tr>
					<td colspan="2" align="center">
						<table width="100%" border="0" cellspacing="1" cellpadding="0">						 
						  <tr>
							<td  colspan="2" align="right"><?php echo $lablearray['21'];?> <input name="search"  value="" type="text" size="50" id='search' onKeyUp="showResult('searchterm='+this.value+'&frmid=frmfeecategories&action=search','txtHint')"/><input type="button" value="<?php echo $lablearray['21'];?>" onClick="showResult('frmid=frmbanks&action=search&searchterm='+document.getElementById('search').value,'txtHint');" class="actbutton"><input name="download" type="button" id="download" onClick="parent.document.location='downloadlist.php?columncheck=7&Fee_Categories&timestamp=<?php echo strtotime("now"); ?>'" value="<?php echo $lablearray['267'];?>" class="actbutton"></td>
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
		
		  <script language="JavaScript"  type="text/javascript">
                      
                      //showValues('frmsms', 'txtHint', 'search','SMS', 'load.php');
                      showValues('frmbanks', 'txtHint', 'search','BANKS', 'load.php');
			// showResult('frmid=frmbanks','txtHint')
		  </script>
	  
</BODY>
</HTML>
