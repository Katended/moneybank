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
 getLicenceCounts();
 getlables("345");
$save = 'N';

if (is_file(DIR_WS_TEMP_FILES.'temfile.xls')) { unlink(DIR_WS_TEMP_FILES.'temfile.xls'); }

if($_FILES['file']['tmp_name']!=""){
	
	if(move_uploaded_file($_FILES['file']['tmp_name'],DIR_WS_TEMP_FILES.'temfile.xls')>0 && $_POST['save']=="N"){
		  	if (eregi('(xls|XLS)$',$_FILES['file']['name'])=='1') {				
				/*$fi = file(DIR_WS_TEMP_FILES.$_FILES['file']['name']);
				$fi2 = fopen(DIR_WS_TEMP_FILES.$_FILES['file']['name'],"w");		
				foreach ($fi as $lne){
					$n = rtrim ($lne);
					fputs ($fi2,"$n\n");
				}*/
			}else{
				$save = 'N';
				$msg =" Invalid file format! Please use a Microsoft Excel file to load clients or contact Sync";	
			}
			
			$save = 'Y';
			$fullpath = DIR_WS_TEMP_FILES.'temfile.xls';
		}else{
			$save = 'N';
			$msg =" File could not be moved. Please check folder Write Permissions of the Server.";	
		}
	}
if($_POST['save']=="Y"){
	  $reader = new Spreadsheet_Excel_Reader();
	  $reader->setUTFEncoder('iconv');
	  $reader->setOutputEncoding('UTF-8');
	  
	  $reader->read($_POST['fullpath']);
	  
	  /* This prints the sheet name and offset - i have not idea what the offset..find more about the offset
	  foreach ($reader->boundsheets as $k=>$sheet){
	   /  print_r($sheet);
	  }*/
	  
	  $save ='N';
	  
	  tep_db_query("START TRANSACTION");
	  
	  foreach($reader->sheets as $k=>$data){
	  
		
		  $i = 0;
		  if ($data[numRows]!=0){
			  foreach($data['cells'] as $row){
				  
				  $students_sregno = tep_db_prepare_input($row[1]);
				  $students_unebindexno = tep_db_prepare_input($row[2]);
				  $students_firstname = trim(tep_db_prepare_input($row[3]));
				  $students_lastname = trim(tep_db_prepare_input($row[4]));
				  $students_othernames = tep_db_prepare_input($row[5]);
				  $students_gender = tep_db_prepare_input($row[6]);
				  $students_dateenrolled = changeDateFromPageToMySQLFormat($row[7]);	
				  $students_isborder = tep_db_prepare_input($row[8]);		
				  $students_fathername = tep_db_prepare_input($row[9]);		
				  $students_mothername = tep_db_prepare_input($row[10]);			
				  $students_gurdianname = tep_db_prepare_input($row[11]);		
				  $students_fathermobile = tep_db_prepare_input($row[12]);		
				  $students_mothermobile = tep_db_prepare_input($row[13]);		
				  $students_homeaddress = tep_db_prepare_input($row[14]);
				  $students_nextofkin = tep_db_prepare_input($row[15]);
				  $students_house = tep_db_prepare_input($row[16]);
				  $classes_id = tep_db_prepare_input($row[17]);	
				  $students_dateofbirth = changeDateFromPageToMySQLFormat($row[18]);
				  $students_health = tep_db_prepare_input($row[19]);
				  $students_isactive = tep_db_prepare_input($row[20]);
				  $students_previous_schools = tep_db_prepare_input($row[21]);
				  $students_country = tep_db_prepare_input($row[22]);
				  $students_level = tep_db_prepare_input($row[23]);
				  $students_hometelephonenumber= tep_db_prepare_input($row[24]);	
				  $students_comment = tep_db_prepare_input($row[25]);
				  $fees_categoriesid = tep_db_prepare_input($row[26]);
				  $classcategories_id = tep_db_prepare_input($row[27]);
				  
				  $relationsguardian = explode(",",tep_db_prepare_input($row[28]));
				  $deceased = explode(",",tep_db_prepare_input($row[29]));
				  $villagezones_id = tep_db_prepare_input($row[30]);
				  $students_gurdiantelephone  = tep_db_prepare_input($row[31]);
				  $students_othercontacts = tep_db_prepare_input($row[32]);
				
				//print_r($row);	
				
				//echo '<br>';		
				 	
				  $db_query=tep_db_query("SELECT students_sregno FROM " . TABLE_STUDENTS . " WHERE students_sregno='".$students_sregno."'");
								  
				  if(!tep_db_num_rows($db_query)){
					  if($i!=0){
						  tep_db_query("INSERT INTO " . TABLE_STUDENTS . " (students_sregno,students_unebindexno,students_firstname,students_lastname,students_othernames,students_gender,students_dateenrolled,students_fathername,students_mothername,students_gurdianname,students_fathermobile,students_mothermobile,students_homeaddress,students_hometelephonenumber,students_nextofkin,students_gurdiantelephone,classcategories_id,students_isborder,students_house,students_dateofbirth,students_comment,students_health,students_isactive,students_image,students_level,students_previous_schools,students_religion,students_country,students_othercontacts) values ('" . tep_db_input($students_sregno) . "','" . tep_db_input($students_unebindexno) ."','" . tep_db_input($students_firstname) . "','" . tep_db_input($students_lastname) ."','" . tep_db_input($students_othernames)."','".tep_db_input($students_gender)."',".$students_dateenrolled.",'".tep_db_input($students_fathername)."','".tep_db_input($students_mothername)."','".tep_db_input($students_gurdianname)."','".tep_db_input($students_fathermobile)."','".tep_db_input($students_mothermobile)."','".tep_db_input($students_homeaddress)."','".tep_db_input($students_hometelephonenumber)."','".tep_db_input($students_nextofkin)."','".tep_db_input($students_gurdiantelephone)."','".tep_db_input($classcategories_id)."','".tep_db_input($students_isborder)."','".tep_db_input($students_house)."',".$students_dateofbirth.",'".tep_db_input($students_comment)."','".tep_db_input($students_health)."','".tep_db_input($students_isactive)."','".tep_db_input($students_image)."','".$students_level."','".$students_previous_schools."','".$students_religion."','".$students_country."','".tep_db_input($students_othercontacts)."')");
					  $pid  = tep_db_insert_id();
					  
						  if($classes_id != ""){
							tep_db_query("INSERT INTO " . TABLE_STUDENTCLASSES . " (classes_id,studentclasses_currentflag,students_sregno,studentclasses_datecreated) VALUES ('".$classes_id."','Y','".tep_db_input($students_sregno)."',NOW())");  
						  }
						  
						  if($fees_categoriesid != ""){
							tep_db_query("INSERT INTO " . TABLE_STUDENTFEECATEGORIES . " (students_sregno,feecategories_id,studentsfeecategories_currentflag) VALUES ('".$students_sregno."','".$fees_categoriesid."','Y')");  
						  }
					  
					  		if(count($deceased)>0){
								
								foreach($relationsguardian  as $key=>$value){
									tep_db_query("INSERT INTO " .TABLE_RELATIONSDECEASED. " (students_sregno,relations_id) values ('".$students_sregno."','".$value."')");
								}
							
							}
							
							
							if(count($relationsguardian)> 0){
								
								foreach($relationsguardian as $key=>$value){
									tep_db_query("INSERT INTO " .TABLE_RELATIONSGUARDIAN. " (students_sregno,relations_id) values ('".$students_sregno."','".$value."')");
								}
							
							}
					  
					  }
				  
				  	
					}
					  $i++;						
				  }
			  }
	  }
	  
	  tep_db_query("COMMIT");
	if (is_file(DIR_WS_TEMP_FILES.'temfile.xls')) { unlink(DIR_WS_TEMP_FILES.'temfile.xls'); }
	 
	 $msg ="<p style='color:#FFFFFF;' align='center'>".$lablearray['345']."</p>";
	
}?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3c.org/TR/1999/REC-html401-19991224/loose.dtd">
<HTML <?php echo HTML_PARAMS; ?>>
<HEAD>
<TITLE><?php echo NAME_OF_INSTITUTION;?> - Import Students</TITLE>
<META http-equiv=Content-Type content="text/html; charset=utf8">
<LINK href="../stylesheet.css" type=text/css rel=stylesheet>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3c.org/TR/1999/REC-html401-19991224/loose.dtd">
<HTML lang="<? echo $_SESSION['P_LANG'];?>"><HEAD>
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
<script src="../includes/javascript/commonfunctions.js" type="text/javascript"></script>
<script src="../includes/javascript/scroll.js" type="text/javascript"></script>
<script type="text/javascript" src="../includes/javascript/commonfunctions.js"></script>
<?php require('../'.DIR_WS_INCLUDES . 'initmenu.php');
	getlables("20,609,255,2,256,409,410,243,411");
 ?>			
	
 <form action="importstudents.php?action=<?php echo $action;?>" method="post" enctype="multipart/form-data"  id="frmimportstudents" name="frmimportstudents" onSubmit="">   

 <span id="resulttext" style="height:90px;"><BR><BR></span>
 	<?php echo tep_draw_hidden_field('save',$save); ?>
    <?php echo tep_draw_hidden_field('fullpath',$fullpath); ?>
	<table width="100%" border="0" cellspacing="2" cellpadding="2">
		<tr>
		<td align="right" valign="bottom" colspan="2"></td>
	  </tr> 
	  <tr>
		<td align="right" valign="bottom"><?php echo $lablearray['255'];?></td>
		<td><input name="file" type="file"  id="file"  size="80" width="100"></td>
	  </tr> 
	  <tr>
		<td colspan="2" align="center">
		<?php if ($save=='Y'){?>
		<input name="submit" type="submit" value="<?php echo $lablearray['20'];?>" class="actbutton">
		<?php }else{?>
		<input name="submit" type="submit" value="<?php echo $lablearray['609'];?>" class="actbutton">
		<?php }
		
		
		?>
		<input name="Reset" type="reset" value="<?php echo $lablearray['2'];?>" class="actbutton"></td>
	  </tr>
	</table>
  
  </form>
   <?php require('../'.DIR_WS_INCLUDES . 'userfooter.php'); ?>				
</BODY></HTML>