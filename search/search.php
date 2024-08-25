<?php
  require_once('../includes/application_top.php');
  require_once('../includes/functions/password_funcs.php');
  require_once('../includes/classes/class.listmanager.php');
   /* set the cache limiter to 'private' */


	// if the user is not logged on, redirect them to the login page
  	if(AuthenticateAccess('LOGIN')==0){
		//tep_redirect(tep_href_link(FILENAME_DEFAULT));
		tep_redirect(tep_href_link(FILENAME_LOGIN));

	}
	if($_GET['action']=="delete"){
		tep_db_query("DELETE FROM " . TABLE_STUDENTS . " WHERE students_sregno='".replaces_underscores($_GET['students_sregno'])."'");
	}
	$_SESSION['classes_id'] = $_POST['classes_id'];
	$_SESSION['termsdefinition_id'] =$_POST['termsdefinition_id'];
	$_SESSION['exams_id'] = $_POST['exams_id'];
	$_SESSION['termmarks_year'] = $_POST['termmarks_year'];
	$_SESSION['level'] = $_POST['level'];

	$level = $_POST['level'];
	$classes_query = tep_db_query("select * from " . TABLE_CLASSES);
	 while ($classes_array = tep_db_fetch_array($classes_query)) {
			$classes[$classes_array['classes_id']] = $classes_array['classes_name'];
	 }
	if ($_POST['checkbox']!=""){
		if($_POST['submit']=="Edit Profile"){
			tep_redirect(tep_href_link('addpupil.php?'.$_POST['checkbox'].'&action=edit'));
		}elseif($_POST['submit']=="Add Results"){
			tep_redirect(tep_href_link('addresults.php?'.$_POST['checkbox'].'&action=add'));

		}elseif($_POST['submit']=="Academic Reports"){

			$level = substr($_POST['checkbox'],strlen($_POST['checkbox'])-1,strlen($_POST['checkbox']));

			if($level=="P" ||$level=="N"){

				tep_redirect(tep_href_link('viewresults.php?'.$_POST['checkbox'].'&action=view'));

			}elseif($level=="O"){
				tep_redirect(tep_href_link('viewresultsolevel.php?'.$_POST['checkbox'].'&action=view'));

			}elseif($level=="A"){
				tep_redirect(tep_href_link('viewresultsalevel.php?'.$_POST['checkbox'].'&action=view'));
			}

		}elseif($_POST['submit']=="View Statement"){
			tep_redirect(tep_href_link('pupilpaymentreport.php?'.$_POST['checkbox'].'&action=view'));
		}elseif($_POST['submit']=="Make Payment"){
			tep_redirect(tep_href_link('makepayment.php?'.$_POST['checkbox'].'&action=view'));
		}


	}


	$listmanager = new ListManager;
	$listmanager->listURL = "viewstudents.php";
	$listmanager->defaultsortorder = "ASC";

	# This list will be sorted by weekending date by default
	$listmanager->defaultsortby = "p.students_firstname";

	$listmanager->processRequest($_GET);
	//$listmanager->defaultsortfield = "members_firstname";
	$defaultsearchfieldtext = "Search students";

	$action = tep_db_prepare_input($_POST['action']);

	$students_sregno = $_POST['students_sregno2'];

	$pid = replaces_underscores($_POST['pid']);


	if($_POST['submit']=="Delete"){
		tep_db_query("DELETE FROM " . TABLE_TRANSACTIONS . " WHERE students_sregno='".$pid."'");
		tep_db_query("DELETE FROM " . TABLE_TERMMARKS . " WHERE students_sregno='".$pid."'");
		tep_db_query("DELETE FROM " . TABLE_EXAMDEFINITION . " WHERE students_sregno='".$pid."'");
		tep_db_query("DELETE FROM " . TABLE_STUDENTS. " WHERE students_sregno='".$pid."'");
		$msg = "Student/Pupil details have been successfully deleted";
		$students_sregno="";
	}elseif($_POST['submit']=="Promote" && $_POST['pid']!=""){
		$student_details_query = tep_db_query("SELECT classes_id FROM " . TABLE_STUDENTS . " WHERE students_sregno='".$pid."'");
		$student = tep_db_fetch_array($student_details_query);
		tep_db_query("UPDATE " . TABLE_STUDENTS. " SET classes_id='".($student['classes_id']+1)."'");
		$msg = "Student/Pupil has been successfully promoted";
	}elseif($_POST['submit']=="Demote" && $_POST['pid']!=""){
		$student_details_query = tep_db_query("SELECT classes_id FROM " . TABLE_STUDENTS . " WHERE students_sregno='".$pid."'");
		$student = tep_db_fetch_array($student_details_query);
		tep_db_query("UPDATE " . TABLE_STUDENTS . " SET classes_id='".(int)($student['classes_id']-1)."'");
		$msg = "Student/Pupil has been successfully demoted";
	}



	$classes_id = tep_db_prepare_input($_POST['classes_id']);
	$students_firstname = tep_db_prepare_input($_POST['students_firstname']);
	$students_lastname = tep_db_prepare_input($_POST['students_lastname']);
	$tran_balance = tep_db_prepare_input($_POST['tran_balance']);
	$transactiontypes_id = tep_db_prepare_input($_POST['transactiontypes_id']);
	$coperator = tep_db_prepare_input($_POST['operator']);
	$borders = tep_db_prepare_input($_POST['borders']);

	$tran_date = date('Y-m-d');
	$retainvalue=true;
	$error=false;
	$transSQL="";
	if($operator==''){
		$balancesql="";
		$fieldSQL="";

	}else{
		$whereSQL="";
		$transSQL="";
		if($coperator=='=(equal to)'){
			$balancesql=" and t.tran_balance =".$tran_balance;
		}elseif($coperator=='<= (less than or equal to)'){
			$balancesql=" and t.tran_balance <= ".$tran_balance;
		}elseif($coperator=='>= (greater than or equal to)'){
			$balancesql=" and t.tran_balance >= ".$tran_balance;
		}elseif($coperator=='> (greater than)'){
			$balancesql=" and t.tran_balance > ".$tran_balance;
		}elseif($coperator=='< (less than)'){
			$balancesql=" and t.tran_balance < ".$tran_balance;
		}
		if($balancesql!="" || $transactiontypes_id!=""){

			$transSQL=" left join ".TABLE_TRANSACTIONS." as t on p.students_sregno=t.students_sregno left join ".TABLE_TRANSACTIONTYPES." as tt on tt.transactiontypes_id=t.transactiontypes_id ";
			$fieldSQL=", t.tran_recieptno,tt.transactiontypes_name,t.tran_date,t.tran_debit,t.tran_credit, t.tran_balance";
			$flagSQL=" and t.currentflag='Y'";
		}
	}
	#	get students  transactional details
	#	begin buildig query
	$SQL="";
	if($students_firstname!=""){
		$SQL=" p.students_firstname like '%".$students_firstname."%' or p.students_lastname like '%".$students_firstname."%'";
	}
	if($students_lastname!=""){
		if($SQL!=""){
			$SQL.=" or p.students_lastname like '%".$students_lastname."%' or p.students_firstname like '%".$students_lastname."%'";
		}else{
			$SQL.=" p.students_lastname like '%".$students_lastname."%' or p.students_firstname like '%".$students_lastname."%'";
		}
	}

	if($students_sregno!=""){
		if($SQL!=""){
			$SQL.=" or p.students_sregno like '%".$students_sregno."%'";
		}else{
			$SQL.=" p.students_sregno like '%".$students_sregno."%'";
		}
	}

	if($transactiontypes_id!=""){
		if($SQL!=""){
			$SQL.=" or t.transactiontypes_id like '%".$transactiontypes_id."%' AND t.tran_balance like '%".$tran_balance."%' ";
		}else{
			$SQL.="t.transactiontypes_id like '%".$transactiontypes_id."%' AND t.tran_balance like '%".$tran_balance."%'";
		}
	}
	if($classes_id!=""){
		if($SQL!=""){
			$SQL.=" AND c.classes_id like '%".$classes_id."%'";
		}else{
			$SQL.= " c.classes_id ='".$classes_id."'";
		}
	}
	if($level!=""){
		if($SQL!=""){
			$SQL.=" AND p.students_level ='".$level."'";
		}else{
			$SQL.= " p.students_level ='".$level."'";
		}
	}


	if($SQL!="" && $_GET['action']!=""){
		$_SESSION['sql'] = $SQL;
	}else{
		if($_GET['action']==""){
			$SQL="";
			$_SESSION['sql']="";
		}else{

			$SQL = $_SESSION['sql'];
		}
	}

	if($SQL!=""){
		$whereSQL=" WHERE ";
	}
	if($transactiontypes_id!=""){
		$whereSQL=" LEFT JOIN transactions AS t ON p.students_sregno=t.students_sregno WHERE ";
	}



	$allresultsquery = "select p.students_gender,p.students_image,(select classes_name  FROM ".TABLE_CLASSES." WHERE classes_id=sc.classes_id ) AS classes_name,p.students_sregno, p.students_firstname,p.students_level,p.students_lastname ".$fieldSQL." from ". TABLE_STUDENTS." as p LEFT JOIN ".TABLE_STUDENTCLASSES." sc ON sc.students_sregno=p.students_sregno ".$transSQL." ".$whereSQL.$SQL.$balancesql. $flagSQL." ORDER BY p.students_firstname,p.students_lastname";
	
	
	// check see if we can use the cached query
	if($_GET['page']=="" && $_POST['Submit']==""){
			$query_results_query = tep_db_query("SELECT querycache_query FROM " . TABLE_QUERYCACHE . " ORDER BY querycache_id DESC LIMIT 1");

			// check see if we have got any query
			if(tep_db_num_rows($query_results_query)){
				$query_array = tep_db_fetch_array($query_results_query);
				$allresultsquery = $query_array['querycache_query'];
				$querymsg = "You seem to be interested in the previous results. The System has restored your previous query.";
			}else{
				tep_db_query("DELETE FROM " . TABLE_QUERYCACHE);
			}
	}elseif($_POST['Submit']=="Search" && $_POST['restore']=="restore"){
			tep_db_query('INSERT INTO ' . TABLE_QUERYCACHE . ' (querycache_query,querycache_datecreated) values ("'.$allresultsquery.'",NOW())');
			$_SESSION['restore']='restore';

	}


	$_SESSION['downloadlist'] = $allresultsquery;// "SELECT p.students_sregno, CONCAT(p.students_firstname,' ',p.students_lastname) as Name,(select classes_name  FROM ".TABLE_CLASSES." WHERE classes_id=sc.classes_id ) AS classes_name,p.students_level from ". TABLE_STUDENTS." as p ".$transSQL." left join ".TABLE_CLASSES." as c on c.classes_id=p.classes_id".$whereSQL.$SQL.$balancesql. $flagSQL." ORDER BY p.students_firstname,p.students_lastname";;

	if($_POST['page']!=""){
		$allresultsquery = $_SESSION['page_sql'];
	}else{
		 $_SESSION['page_sql'] = $allresultsquery;
	}

	$results = tep_db_query($allresultsquery);

	$listmanager->setTotalNumberOfRows(tep_db_num_rows($results));

	$allresultsquery = $allresultsquery." ".$listmanager->getLimitSQL();

	$students_trans_details_query = tep_db_query($allresultsquery);
	
	getlables("259");

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3c.org/TR/1999/REC-html401-19991224/loose.dtd">
<HTML lang="<? echo $_SESSION['P_LANG'];?>"><HEAD>
<LINK href="../stylesheet.css" type=text/css rel=stylesheet>
<script language="JavaScript" src="../includes/javascript/calendar_us.js"></script>
<script type="text/javascript" src="../includes/javascript/gridsearch.js" language="javascript"></script>
<link  media="screen"  type="text/css" rel="stylesheet" href="../includes/javascript/de.css"></link>
<script type="text/javascript" src="../includes/javascript/jquery-1.6.2.min.js"></script>
<script type="text/javascript" src="../includes/javascript/jquery-ui-1.8.16.custom.min.js"></script>
<script type="text/javascript" src="../includes/javascript/jquery.pnotify.js"></script>
<script type="text/javascript" src="../includes/javascript/commonfunctions.js"></script>
<script src="../styles/JTIP.JS" type="text/javascript"></script>
<script src="../includes/javascript/scroll.js" type="text/javascript"></script>

<script language="JavaScript"  type="text/javascript">
var n = '';
var a = '';
var c = '';
var url ='';
var iface='';
url="../addedit.php";

 function getdata(paging,formid,action,searchterm) {
	str= paging + '&frmid=frmsearch'+ '&n='+searchterm;
 	document.getElementById('txtHint').innerHTML = "<p style='text-align:center;'><?php echo $lablearray['259'];?><br><img src='images/loading.gif'></p>";
	makeRequest(str);
 }
 
$(document).ready(function(){

	$(".btn-slide").click(function(){
		$("#panel").slideToggle("slow");
		$(this).toggleClass("active"); return false;
	});
	
	


});

function searchClicked(){
	showResult('searchterm='+document.getElementById('search').value+'&frmid=frmsearch&name=reuslts[]'+'&classes_id='+document.getElementById('classes_id').value+'&classcategories_id='+document.getElementById('classcategories_id').value,'txtHint')	
}
</script>
<link rel="stylesheet" type="text/css" href="../students/styles/global.css">
<TITLE><?php echo NAME_OF_INSTITUTION;?></TITLE>
<META http-equiv="Content-Type" content="text/html; charset=utf8">
<LINK href="../stylesheet.css" type="text/css" rel="stylesheet">
<LINK href="../styles/SCROLL.CSS" type="text/css rel=stylesheet">
<META content="MSHTML 6.00.2900.3268" name=GENERATOR>
<script type="text/javascript">

</script>
<?php require('../'.DIR_WS_INCLUDES . 'initmenu.php'); 
getlables("21,181,182,183");
?>
	 <form  method="POST" id='frmsearch'>
				<input name="person" type="hidden" value="student">
				<table width="100%" border="0" cellspacing="0" cellpadding="0" style="margin:0px;">
				  <tr>
					<td align="right" nowrap valign="top">
					
                    <select name="classes_id" id="classes_id" >
                              <option value=""><?php echo $lablearray['181'];?></option>
                              <?php
							$classes_query = tep_db_query("SELECT classes_id,classes_name FROM " . TABLE_CLASSES. " ORDER BY classes_name");
							while ($class = tep_db_fetch_array($classes_query)) {

								echo "<option id='".$class['classes_id']."' value='".$class['classes_id']."'>".$class['classes_name']."</option>";

							}
							?>
                            </select>
                            <select name="classcategories_id" id="classcategories_id" >
                              <option value=""><?php echo $lablearray['182'];?></option>
                              <?php
							$class_cat_query = tep_db_query("SELECT classcategories_id,classcategories_name FROM " . TABLE_CLASSCATEGORIES. " ORDER BY classcategories_id DESC");
							while ($class_cat = tep_db_fetch_array($class_cat_query)) {
								if($class_cat['classcategories_id']==$classcategories_id){
									echo "<option id='".$class_cat['classcategories_id']."' value='".$class_cat['classcategories_id']."' selected>".$class_cat['classcategories_name']."</option>";
								}else{
									echo "<option id='".$class_cat['classcategories_id']."' value='".$class_cat['classcategories_id']."'>".$class_cat['classcategories_name']."</option>";
								}
							}
							?>
                            </select>
                            <input name="search"  type="text" size="50" id='search'  value="" title="<?php echo $lablearray['183'];?>" onClick="searchClicked()"><input name="Go" value="<?php echo $lablearray['21'];?>" type="button" onClick="searchClicked()"  class="actbutton" id="btnsearch">
                    </td>
				  </tr>
				  <tr>
					<td  id='txtHint' align="center"></td>
				  </tr>
				</table>

				</form>
 <?php require('../'.DIR_WS_INCLUDES . 'userfooter.php'); ?>
	  <script>
	// execute your scripts when the DOM is ready. this is a good habit
	$(function() {
		// initialize scrollable
	//	$("div.scrollable").scrollable();
	});
</script>

</BODY>
 </HTML>
