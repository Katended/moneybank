<?php

  require('../includes/application_top.php');
  require('../includes/functions/password_funcs.php');   
   session_start();
  //echo date('Y',mktime('2007-12-12'));
  
  //echo "select t.tran_id from ". TABLE_TRANSACTIONS." as t left join ".TABLE_TRANSACTIONTYPES." as tt on tt.transactiontypes_id=t.transactiontypes_id LEFT JOIN ".TABLE_STUDENTS." p WHERE t.students_sregno = p.students_sregno AND t.students_sregno='".$students_sregno."' and  t.transactiontypes_id = '".$transactiontypes_id."' and Year(p.students_dateenrolled)='2007' and t.tran_ischarge='Y' ORDER BY t.tran_id DESC LIMIT 1";
  
  if($_SESSION['user_name']==''){
		tep_redirect(tep_href_link(FILENAME_LOGIN, 'page=' . $_GET['page'])); 
	
	}

if($_GET['action']=='go'){
	$classes_id = $_POST['classes_id'];
	$classes_nexttermbegins = $_POST['classes_nexttermbegins'];
	$classes_nexttermends = $_POST['classes_nexttermends'];
	$terms_id = $_POST['terms_id'];
	$term_nexttermbegins = $_POST['term_nexttermbegins'];
	$term_nexttermends  = $_POST['term_nexttermends'];
	if($_POST['disableclassfields']=="C"){	
		tep_db_query("UPDATE " . TABLE_CLASSES . " SET classes_nexttermbegins=".changeDateFromPageToMySQLFormat($classes_nexttermbegins) .",classes_nexttermends=".changeDateFromPageToMySQLFormat($classes_nexttermends)." WHERE classes_id='".$classes_id."'");  	
	}elseif($_POST['disableschoolfields']=="S"){
		tep_db_query("UPDATE " . TABLE_TERMS . " SET terms_currentflag='N' WHERE terms_currentflag='Y'");  	
		tep_db_query("INSERT INTO " . TABLE_TERMS . " (terms_name,terms_year,terms_begins,terms_ends,terms_datecreated,terms_currentflag)	values ('" . tep_db_input($terms_id) . "',YEAR(NOW())," . changeDateFromPageToMySQLFormat($term_nexttermbegins) . "," .changeDateFromPageToMySQLFormat($term_nexttermends).",NOW(),'Y')");
	}
	
	$msg= "The school calendar has been successfully updated.";
}	
 $classes_query = tep_db_query("select * from " . TABLE_CLASSES);
  while ($classes_array = tep_db_fetch_array($classes_query)) {
      	$classes[$classes_array['classes_id']] = $classes_array['classes_name'];
 }
 	
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3c.org/TR/1999/REC-html401-19991224/loose.dtd">
<HTML <?php echo HTML_PARAMS; ?>><HEAD><TITLE>Set Dates</TITLE>
<META http-equiv=Content-Type content="text/html; charset=iso-8859-1">
<LINK href="../stylesheet.css" type=text/css rel=stylesheet>
<script language="javascript" src="../includes/javascript/commonfunctions.js"></script>
<script language="javascript" src="../includes/javascript/calendar.js"></script>
<script language="javascript" src="../includes/javascript/checkform.js"></script>
<script language="javascript" type="text/javascript">

function enabledisableFields(calendar){
	if(calendar=='school'){		
		if(document.getElementById('disableschoolfields').checked==true){
			document.getElementById('terms_id').disabled=false;
		}else{		
			document.getElementById('terms_id').disabled=true;
				
		}
	}
	
	if(calendar=='class'){		
		if(document.getElementById('disableclassfields').checked==true){
			document.getElementById('classes_id').disabled=false;
			
		}else{		
			document.getElementById('classes_id').disabled=true;
				
		}
	}
	
}
/*function checkFields(){
	if(document.getElementById('disableclassfields').checked==true){
		if(document.getElementById('classes_id').value==""}{
			alert('Classs is missing. Please select class');
			return false;
		}
		if(document.getElementById('classes_nexttermends').value==""){
			alert('The Term  begin date is missing. Please enter the term\'s begin date');
			return false;
		}
		if(document.getElementById('classes_nexttermbegins').value==""){
			alert('The Term  end date is missing. Please enter the term\'s end date');
			return false;
		}
	}
	
	if(document.getElementById('disableschoolfields').checked==true){
		if(document.getElementById('terms_id').value==""}{
			alert('Term is missing. Please select term');
			return false;
		}
		if(document.getElementById('term_nexttermbegins').value==""){
			alert('The Term  begin date is missing. Please enter the term\'s begin date');
			return false;
		}
		if(document.getElementById('term_nexttermend').value==""){
			alert('The Term  end date is missing. Please enter the term\'s end date');
			return false;
		}
	}
}*/
</script>


<STYLE type=text/css rel=stylesheet>
.like {
	FONT-WEIGHT: bold; 
	FONT-SIZE: 14px; 
	COLOR: #000066; 
	FONT-STYLE: italic;
}
.titleaction {
	FONT-WEIGHT: bold;
}
.titlegrey {
	FONT-WEIGHT: bold; 
	COLOR: #666666;
}
.feedback {
	TEXT-ALIGN: justify;
}
</STYLE>
<META content="MSHTML 6.00.2900.3268" name="GENERATOR"></HEAD>
<BODY class="main">

  
<DIV class="mnav_center" style="width:360px;text-align:left;"> 
  <?php require(DIR_WS_INCLUDES . 'userheader.php'); ?>
</DIV>
<form  style="width:360px;" action="SETDATES.PHP?action=go" method="post">
					
  
                              <?php if ($error==true || $msg!=""){?>
                              <div class="errors"> 
                                <p> <em><?php echo $msg;?></em> </p>
                              </div>
                              <?php }?>
							  <input name="datefield"  id="datefield" type="hidden" value="">
   <fieldset style="padding-left:10px;">
  <legend accesskey="B">Class Study Calendar</legend>
  <label for="billingName">&nbsp;</label>
  <input name="disableclassfields" id="disableclassfields" type="checkbox" value="C" onClick="enabledisableFields('class')">&nbsp;Update Class Calendar
  <label for="billingName">Class:</label>
  <select name="classes_id" id="classes_id" disabled="true">
	<option value="">Select class</option>
	<?PHP 
	foreach($classes as $id => $name){
		if($id==$classes_id && $action=='edit'){	
			echo "<option id='".$id."' value='".$id."' selected>".$name."</option>";
		}elseif($error=true && $id==$classes_id){	
			echo "<option id='".$id."' value='".$id."' selected>".$name."</option>";
		}else{
			echo "<option id='".$id."' value='".$id."'>".$name."</option>";
		}
	}	
	?>	
	</select><span class="inputRequirement">* Required</span>
   <br/>
   	<label for="billingAddress">Term Ends</label>
	<?php
	if($_GET['id']!=""){
		$classes_nexttermends = changeMySQLDateToPageFormat($classes_nexttermends);
	
	}
	
	?>
	<input name="classes_nexttermends" class="yellowfield"  id="classes_nexttermends" type="text" size="15" width="32" value="<?php echo $classes_nexttermends;?>"  readonly/><a href="javascript:selectDate('classes_nexttermends')"><img height=17 src="../images/CALENDAR.GIF" width="17" border="0"  alt="Click to select date"/></a>
	<BR>
	<label for="billingAddress">Next&nbsp;Begins</label>
	<?php
	if($_GET['id']!=""){
		$classes_nexttermbegins = changeMySQLDateToPageFormat($classes_nexttermbegins);
	
	}
	
	?>
	<input name="classes_nexttermbegins" class="yellowfield"  id="classes_nexttermbegins" type="text" size="15" width="32" value="<?php echo $classes_nexttermbegins;?>"  readonly/><a href="javascript:selectDate('classes_nexttermbegins')"><img height=17 src="../images/CALENDAR.GIF" width="17" border="0" alt="Click to select date"/></a>
	<BR>
	</fieldset>
	<fieldset style="margin-bottom:0px;padding-left:10px;">
	<legend accesskey="B">School Study Calendar</legend>
	<label for="billingAddress">&nbsp;</label>
	<input name="disableschoolfields" id="disableschoolfields" type="checkbox" value="S" onClick="enabledisableFields('school')">&nbsp;Update School Calendar
	<br>
	<label for="billingAddress">Term</label>
	<select name="terms_id" id="terms_id" disabled="true">
		<option value="">Select term</option>
		<option value="First term">First term</option>
		<option value="Second term">Second term</option>
		<option value="Third term">Third term</option>
	</select>
	<BR>
	<label for="billingAddress">Next Term Begins</label>
	<input name="term_nexttermbegins" class="yellowfield"  id="term_nexttermbegins" type="text" size="15" width="32" value="<?php echo $term_nexttermbegins;?>"  readonly/><a href="javascript:selectDate('term_nexttermbegins')"><img height=17 src="../images/CALENDAR.GIF" width="17" border="0" alt="Click to select date"/></a>
	<BR>
	<label for="billingAddress">Next Term Begins</label>
	<input name="term_nexttermends" class="yellowfield"  id="term_nexttermends" type="text" size="15" width="32" value="<?php echo $term_nexttermends;?>"  readonly/><a href="javascript:selectDate('term_nexttermends')"><img height=17 src="../images/CALENDAR.GIF" width="17" border="0" alt="Click to select date"/></a>
	
	</fieldset>
	<label for="billingAddress">&nbsp;</label>
	<input name="submit" type="submit" value="Go"><input name="" type="reset">
</form>
        
<TABLE cellSpacing=0 cellPadding=4 align=center border=0>
		 <?php require(DIR_WS_INCLUDES . 'footer.php'); ?>         
</TABLE>

</BODY></HTML>
