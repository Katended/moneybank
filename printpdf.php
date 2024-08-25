<?php
require_once('includes/application_top.php');
require_once('dompdf_config.inc.php');

$dompdf = new DOMPDF();

$html = '<html><head>
  <style>
    @page { margin: 50px 50px; }
    #header { position: fixed; left: 0px; top: -18px; right: 0px; height: 150px; background-color: orange; text-align: center; }
    #footer { position: fixed; left: 0px; bottom: -180px; right: 0px; height: 150px; background-color: lightblue; }
     .footer { position: fixed; bottom: 0px; }
     .pagenum:before { content: counter(page); }
  </style>
<body>
  <div id="header">
    <h1>Widgets Express</h1>
  </div>
  <div id="footer">
    <p class="page">Page </p>
  </div>
 <div id="content">

      <p> ';
	  
	  

			  
if($_GET['rcode']!=""){
	//$_SESSION['reportname'] = $_GET['rcode'];
	////$_SESSION['exams_id'] = $_GET['exams_id'];
	//$_SESSION['termsdefinition_id'] = $_GET['termsdefinition_id'];
	//$_SESSION['classes_id'] = $_GET['classes_id'];
	//$_SESSION['subjectsmarks_year'] = $_GET['subjectsmarks_year'];
	//$_SESSION['level'] = $_GET['level'];
	//$_SESSION['students_sregno'] = $_GET['students_sregno'];
	//$_SESSION['mode'] = $_GET['mode'];
}

getlables("310");

switch($_GET['rcode']){

		case 'MCW':	// Report on Community Workers
		
			
			$document->SetLeftMargin('5');
			$document->SetRightMargin('5');			
			$document->SetFont('helvetica','', 8);			
			$document->AddPage('L','A4',true);
			
			//$whereSQL= " communitycordinators_firstname like '%%'";
			
			if($_GET['start_date']!="" && $_GET['end_date']!=""){
				$whereSQL =" communitycordinators_admissiondate BETWEEN ".changeDateFromPageToMySQLFormat($_GET['start_date'])." AND ".changeDateFromPageToMySQLFormat($_GET['end_date']);
			}else{
				$_GET['start_date']='';
				$_GET['end_date'] ='';
			}			
					
			// check if program is selected
			if($_GET['careprogrammes_id']!="") {
				
				if($whereSQL!=""){
					$whereSQL=$whereSQL.' AND ';				
				}
				
				$whereSQL = $whereSQL." communitycordinators_id IN (SELECT communitycordinators_id FROM ".TABLE_STUDENTSCAREPROGRAMMES." WHERE careprogrammes_id='".$_GET['careprogrammes_id']."')";
			}
			
			// check if classes are selelcted
			if($_GET['classes_id']!=""){
				
				if($whereSQL!=""){
					$whereSQL=$whereSQL.' AND ';				
				}
				
				$whereSQL = $whereSQL." communitycordinators_id  IN (SELECT communitycordinators_id FROM ".TABLE_STUDENTSCOMMUNITYCORDINATOR." WHERE students_sregno IN (SELECT students_sregno FROM ".TABLE_STUDENTSCLASSES." WHERE classes_id='".$_GET['classes_id']."' AND studentclasses_currentflag='Y'))";
			}
			
			// check see if age is specified
			if($_GET['age_from']!="" && $_GET['age_to']!=""){
				
				if($whereSQL!=""){
					$whereSQL=$whereSQL.' AND ';				
				}
				
				$whereSQL = $whereSQL."  communitycordinators_id  IN (SELECT communitycordinators_id FROM ".TABLE_STUDENTSCOMMUNITYCORDINATOR." WHERE students_sregno IN (SELECT students_sregno FROM ".TABLE_STUDENTS." WHERE YEAR(NOW())- YEAR(students_dateofbirth) >='".$_GET['age_from']."' AND YEAR(NOW())-YEAR(students_dateofbirth)<='".$_GET['age_from']."'))";			
			}	
			
			if($whereSQL!=""){
				$whereSQL=' WHERE '.$whereSQL;
			}
				
				
			//echo "SELECT  communitycordinators_id,CONCAT(communitycordinators_firstname,' ',communitycordinators_lastname) AS Name,communitycordinators_admissiondate,communitycordinators_zone,communitycordinators_telphone,communitycordinators_village FROM ".TABLE_COMMUNITYCORDINATORS." ".$whereSQL;
			
			//exit();	
			$community_worker_query = tep_db_query("SELECT  communitycordinators_id,CONCAT(communitycordinators_firstname,' ',communitycordinators_lastname) AS Name,communitycordinators_admissiondate,communitycordinators_zone,communitycordinators_telphone,communitycordinators_village FROM ".TABLE_COMMUNITYCORDINATORS." ".$whereSQL);
			
			$data ="";			
			if(tep_db_num_rows($community_worker_query)<=0){
				getlables("310");	
				$data = '<table border="0" cellpadding="0" cellspacing="0" width="100%">';
				$data = $data.'<tr><td colspan="2" align="centre">'.$lablearray['310'].'</td></tr>';
				$data = $data.'</table>';	
							
			 }else{
				
				if($_GET['careprogrammes_id']!="" ){					
					$careprog_query = tep_db_query("SELECT careprogrammes_name FROM ".TABLE_STUDENTSCAREPROGRAMMES." sc,".TABLE_CAREPROGRAMMES." cp WHERE  cp.careprogrammes_id=sc.careprogrammes_id  AND  cp.careprogrammes_id='".$_GET['careprogrammes_id']."'");
					$careprog_array = tep_db_fetch_array($careprog_query);				
					$careprogram = $careprog_array['careprogrammes_name'];
				}else{
					getlables("43");
					$careprogram  = $lablearray['43'];
				}
									
				getlables("538,39,9,539,524,523,540,514,522,538,479");
				$data = '<table border="0" cellpadding="0" cellspacing="0" width="100%">';
				if($_GET['start_date']!="" && $_GET['end_date']!=""){
					$data = $data.'<tr><td colspan="2" align="center"><font size="12"><b>'.$lablearray['538'].' '.getUserDatetimeFormat($_GET['start_date']).' '.$lablearray['39'].' '.getUserDatetimeFormat($_GET['end_date']).'</b><br></font></td></tr>';
				}else{
					$data = $data.'<tr><td colspan="2" align="center"><font size="12"><b>'.$lablearray['538'].' </b><br></font></td></tr>';
				}
				$data = $data.'<tr><td colspan="2" align="center"><font size="12"><b>'.$lablearray['479'].': '.$careprogram.'</b><br></font></td></tr>';
				
				$data = $data.'<tr><td colspan="2" align="center">';
				
				if($_GET['start_date']!="" && $_GET['end_date']!=""){			
					$start_date = strtotime($_GET['start_date']);	
					$end_date = strtotime($_GET['end_date']);	
				}
									
				$cur_date  = strtotime(date('m/d/Y'));
				
				$data = $data.'<table border="0.4em" cellpadding="1" cellspacing="0" width="100%">';				
				
				// add heading						
				$data = $data.'<tr>';
				$data = $data.'<td bgcolor="#EEEEEE" align="left"><b>'.$lablearray['9'].'</b></td>';
				$data = $data.'<td bgcolor="#EEEEEE" align="right"><b>'.$lablearray['539'].'</b></td>';
				$data = $data.'<td bgcolor="#EEEEEE" align="right"><b>'.$lablearray['524'].'</b></td>';
				$data = $data.'<td bgcolor="#EEEEEE" align="right"><b>'.$lablearray['523'].'</b></td>';
				$data = $data.'<td bgcolor="#EEEEEE" align="right"><b>'.$lablearray['540'].'</b></td>';
				$data = $data.'<td bgcolor="#EEEEEE" align="right"><b>'.$lablearray['522'].'</b></td>';
				$data = $data.'</tr>';
				
				
				//$nDays = getNumberOFDays($_GET['start_date'],$_GET['end_date']); //dateDifference($_GET['start_date'],$_GET['end_date'],"DAYS",false);
			
				// add data to the table			
				while($worker_array = tep_db_fetch_array($community_worker_query)){	
					
					// get children supported by childworker
					$child_results = tep_db_query("SELECT (SELECT CONCAT(cc.students_firstname,' ',cc.students_lastname) As Name FROM ".TABLE_STUDENTS." cc  WHERE sc.students_sregno=cc.students_sregno) As Name,sc.students_sregno FROM ".TABLE_STUDENTSCOMMUNITYCORDINATOR." sc WHERE  sc.communitycordinators_id='".$worker_array['communitycordinators_id']."'");					
					//echo "SELECT (SELECT CONCAT(cc.students_firstname,' ',cc.students_lastname) As Name FROM ".TABLE_STUDENTS." cc  WHERE sc.students_sregno=cc.students_sregno) As Name,sc.students_sregno FROM ".TABLE_STUDENTSCOMMUNITYCORDINATOR." sc WHERE  sc.communitycordinators_id='".$worker_array['communitycordinators_id']."'";
					$children_string = "";
					
					$children_string = '<table border="0.1em" cellpadding="1">';
					$children_string = $children_string.'<tr><td></td><td></td></tr>';
					while($children_array = tep_db_fetch_array($child_results)){
					
						if(trim($children_array['Name'])==""){
							continue;
						}
					
						$children_string = $children_string.'<tr>';
						$children_string = $children_string.'<td align="left">'.$children_array['Name'].'</td>';
						
						// get programmes enrollment
						
						
						$programme_results = tep_db_query("SELECT students_sregno,(SELECT careprogrammes_name FROM ".TABLE_CAREPROGRAMMES." cp WHERE cp.careprogrammes_id=scp.careprogrammes_id) As careprogrammes_name  FROM ".TABLE_STUDENTSCAREPROGRAMMES." scp WHERE  students_sregno='".$children_array['students_sregno']."'");					
						$children_string = $children_string.'<td align="right">';
						while($programmes_array = tep_db_fetch_array($programme_results)){
							$children_string= $children_string.$programmes_array["careprogrammes_name"];
						}	
																
						$children_string = $children_string.'</td></tr>';										
						
					}
																	
					$children_string = $children_string.'</table>';
					
															
					$data = $data.'<tr>';
					$data = $data.'<td bgcolor="#EEEEEE" align="left" width="10%"><b>'.$worker_array['Name'].'</b></td>';
					$data = $data.'<td  width="10%">'.$worker_array['communitycordinators_admissiondate'].'</td>';
					$data = $data.'<td  width="10%">'.$worker_array['communitycordinators_village'].'</td>';
					$data = $data.'<td  width="10%">'.$worker_array['communitycordinators_zone'].'</td>';
					$data = $data.'<td bgcolor="#EEEEEE"  width="50%">'.$children_string.'</td>';
					$data = $data.'<td bgcolor="#EEEEEE" width="10%">'.$worker_array['communitycordinators_telphone'].'</td>';
					$data = $data.'</tr>';
												
				}
			
				$data = $data.'</table>';
				$data = $data.'</td>';
				$data = $data.'</tr>';
				$data = $data.'</table>';
				
			}
			
			break;
		
		case 'ATT':	// Dues Statement
		
			//$document->setPrintHeader(false);
			//$document->setPrintFooter(false);
			$document->SetLeftMargin('5');
			$document->SetRightMargin('5');
			//$document->SetTopMargin('1');
			$document->SetFont('helvetica','', 9);
			//set auto page breaks
			//$document->SetAutoPageBreak(true,0);
			$document->AddPage('L','A4',true);
			
			if($_GET['start_date']!="" && $_GET['end_date']!=""){
				$whereSQL =" AND cp.careprogrammesattendance_date BETWEEN ".changeDateFromPageToMySQLFormat($_GET['start_date'])." AND ".changeDateFromPageToMySQLFormat($_GET['end_date']);
			}
			
			if($_GET['classes_id']){
				$classes_id = " classes_id ='".$_GET['classes_id']."'";
			}else{
				$classes_id = " classes_id LIKE '%%'";
			}
			
			if($_GET['view_stat_only']=='Y'){
				
								
				$result_query = tep_db_query("SELECT (SELECT CONCAT(students_firstname,' ',students_lastname) AS name FROM ".TABLE_STUDENTS."  WHERE students_sregno=cp.students_sregno) As Name,COUNT(careprogrammesattendance_date) AS count,cp.students_sregno FROM ".TABLE_CAREPROGRAMMESATTENDANCE." cp WHERE cp.students_sregno in (SELECT students_sregno FROM ".TABLE_STUDENTCLASSES." WHERE ".$classes_id."  AND studentclasses_currentflag='Y')  ".$whereSQL. " GROUP BY cp.students_sregno");			
								
				if(tep_db_num_rows($result_query)<=0){
					getlables("310");	
					$data = '<table border="0" cellpadding="0" cellspacing="0" width="100%">';
					$data = $data.'<tr><td colspan="2" align="centre">'.$lablearray['310'].'</td></tr>';
					$data = $data.'</table>';	
								
				 }else{
					
					if($_GET['careprogrammes_id']!="" ){					
						$careprog_query = tep_db_query("SELECT careprogrammes_name FROM ".TABLE_STUDENTSCAREPROGRAMMES."  WHERE careprogrammes_id='".$_GET['careprogrammes_id']."'");
						$careprog_array = tep_db_fetch_array($careprog_query);				
						$careprogram = $careprog_array['careprogrammes_name'];
					}else{
						getlables("43");
						$careprogram  = $lablearray['43'];
					}
										
					getlables("499,39,9,500,501,502,479,514");
					$data = '<table border="0" cellpadding="0" cellspacing="0" width="100%">';
					$data = $data.'<tr><td colspan="2" align="center"><font size="12"><b>'.$lablearray['499'].' '.getUserDatetimeFormat($_GET['start_date']).' '.$lablearray['39'].' '.getUserDatetimeFormat($_GET['end_date']).'</b><br></font></td></tr>';
					$data = $data.'<tr><td colspan="2" align="center"><font size="12"><b>'.$lablearray['479'].': '.$careprogram.'</b><br></font></td></tr>';
					
					$data = $data.'<tr><td colspan="2" align="center">';
								
					$start_date = strtotime($_GET['start_date']);	
					$end_date = strtotime($_GET['end_date']);						
					$cur_date  = strtotime(date('m/d/Y'));
					$new_date =	$start_date;
					$data = $data.'<table border="0.4" cellpadding="1" cellspacing="0">';				
					
					// add heading						
					$data = $data.'<tr>';
					$data = $data.'<td bgcolor="#EEEEEE" align="left"><b>'.$lablearray['9'].'</b></td>';
					$data = $data.'<td bgcolor="#EEEEEE" align="right"><b>'.$lablearray['500'].'</b></td>';
					$data = $data.'<td bgcolor="#EEEEEE" align="right"><b>'.$lablearray['501'].'</b></td>';
					$data = $data.'<td bgcolor="#EEEEEE" align="right"><b>'.$lablearray['502'].'</b></td>';
					$data = $data.'<td bgcolor="#EEEEEE" align="right"><b>'.$lablearray['514'].'</b></td>';
					$data = $data.'</tr>';
					
					
					$nDays = getNumberOFDays($_GET['start_date'],$_GET['end_date']); //dateDifference($_GET['start_date'],$_GET['end_date'],"DAYS",false);
					
					// add data to the table			
					while($students_array = tep_db_fetch_array($result_query)){	
					
						// community workers
						$worker_results = tep_db_query("SELECT (SELECT CONCAT(cc.communitycordinators_firstname,' ',cc.communitycordinators_lastname) As Name FROM ".TABLE_COMMUNITYCORDINATORS." cc  WHERE sc.communitycordinators_id=cc.communitycordinators_id ) FROM ".TABLE_STUDENTSCOMMUNITYCORDINATOR." sc WHERE  students_sregno='".$students_array['students_sregno']."'");					
						
						$communitiy_workers_string = "";
						
						while($worker_array = tep_db_fetch_array($worker_results)){
							$communitiy_workers_string.= $worker_array['Name']."<br>";	
						}														
												
						$data = $data.'<tr>';
						$data = $data.'<td bgcolor="#EEEEEE" align="left"><b>'.trim($students_array['Name']).'</b></td>';
						$data = $data.'<td>'.$students_array['count'].'</td>';
						$data = $data.'<td>'.(int)($nDays-$students_array['count']).'</td>';
						$data = $data.'<td>'.$nDays.'</td>';
						$data = $data.'<td bgcolor="#EEEEEE">'.$communitiy_workers_string.'</td>';
						$data = $data.'</tr>';
																
					}
					
					$data = $data.'</table>';
					$data = $data.'</td>';
					$data = $data.'</tr>';
					$data = $data.'</table>';
				}
			}else{
				
				tep_db_query("DROP TABLE IF EXISTS Attn");
				
				$result_query = tep_db_query("SELECT(SELECT CONCAT(students_firstname,' ',students_lastname) AS name FROM ".TABLE_STUDENTS."  WHERE students_sregno=cp.students_sregno) As Name,cp.students_sregno FROM ".TABLE_CAREPROGRAMMESATTENDANCE." cp WHERE cp.students_sregno in (SELECT students_sregno FROM ".TABLE_STUDENTCLASSES." WHERE ".$classes_id."  AND studentclasses_currentflag='Y')  ".$whereSQL. " GROUP BY cp.students_sregno");			
				
				
				$attn_query = tep_db_query("SELECT careprogrammesattendance_date,cp.students_sregno FROM ".TABLE_CAREPROGRAMMESATTENDANCE." cp WHERE cp.students_sregno in (SELECT students_sregno FROM ".TABLE_STUDENTCLASSES." WHERE ".$classes_id."  AND studentclasses_currentflag='Y')  ".$whereSQL. " GROUP BY cp.students_sregno,careprogrammesattendance_date");			
				
				$attendance = array();
				
				while($attn_array = tep_db_fetch_array($attn_query)){
					$attendance[] = $attn_array['students_sregno'].$attn_array['careprogrammesattendance_date'];
				}
				
						
				if(tep_db_num_rows($result_query)<=0){
				
					$data = '<table border="0" cellpadding="0" cellspacing="0" width="100%">';
					$data = $data.'<tr><td colspan="2" align="centre">'.$lablearray['310'].'</td></tr>';
					$data = $data.'</table>';	
								
				 }else{
					
					$careprogram = '(All)';
					
					if($_GET['careprogrammes_id']!="" ){
					
						$careprog_query = tep_db_query("SELECT careprogrammes_name FROM ".TABLE_STUDENTSCAREPROGRAMMES."  WHERE careprogrammes_id='".$_GET['careprogrammes_id']."'");
						
						$careprog_array = tep_db_fetch_array($careprog_query);				
						$careprogram = $careprog_array['careprogrammes_name'];
					}
					
					getlables("499,479");
					$data = '<table border="0" cellpadding="0" cellspacing="0" width="100%">';
					$data = $data.'<tr><td colspan="2" align="center"><font size="12"><b>'.$lablearray['499'].' '.getUserDatetimeFormat($_GET['start_date']).' '.$lablearray['39'].' '.getUserDatetimeFormat($_GET['end_date']).'</b><br></font></td></tr>';
					$data = $data.'<tr><td colspan="2" align="center"><font size="12"><b>'.$lablearray['479'].' '.$careprogram.'</b><br></font></td></tr>';
					
					$data = $data.'<tr><td colspan="2" align="center">';
								
					$start_date = strtotime($_GET['start_date']);	
					$end_date = strtotime($_GET['end_date']);						
					$cur_date  = strtotime(date('m/d/Y'));
					$new_date =	$start_date;
					$data = $data.'<table border="0.4" cellpadding="1" cellspacing="0">';				
						
					// create column headers
					$data = $data.'<tr>';
					$data = $data.'<td width="130px" bgcolor="#EEEEEE">'.$lablearray['9'].'</td>';
					for ($i = 1; $new_date < strtotime('+1 day',$end_date); $i++) {
						if(date('d',$new_date)=='01' || date('d',$new_date)=='1'){
							$data = $data.'<td bgcolor="#EEEEEE">'.date('M',$new_date).'</td>';
						}else{
							$data = $data.'<td bgcolor="#EEEEEE" width="20px;">'.trim(date('d',$new_date)).'</td>';
						}
						$new_date =  strtotime('+1 day',$new_date);
					}
					$data = $data.'<td bgcolor="#000000" style="color:#FFFFFF;" width="20px;"><b>P</b></td>';
					$data = $data.'<td bgcolor="#000000"style="color:#FFFFFF;"  width="20px;"><b>A</b></td>';
					$data = $data.'</tr>';
					
					$new_date =	$start_date;
					
					$previous_students_sregno = "";
						
						while($students_array = tep_db_fetch_array($result_query)){
							
							// add data to the table
							
							if($previous_students_sregno=="" || $previous_students_sregno!=$students_array['students_sregno']){
								$new_date =	$start_date;
								$data = $data.'<tr>';
								$data = $data.'<td bgcolor="#EEEEEE" align="left" ><b>'.trim($students_array['Name']).'</b></td>';
							}
							$nPresent =0;
							$nAbsent =0;
							for ($i = 1; $new_date < strtotime('+1 day',$end_date); $i++) {
							
								$this_date = date('Y-m-d',$new_date);
																								
								if(in_array($students_array['students_sregno'].$this_date,$attendance,true)){
									$data = $data.'<td bgcolor="#666666" style="color:#666666;">00</td>';
									$nPresent = $nPresent + 1;
								}else{
									$data = $data.'<td bgcolor="#FFFFFF" style="color:#FFFFFF">00</td>';
									$nAbsent = $nAbsent + 1;
								}
								
								$new_date =  strtotime('+1 day',$new_date);
							}
							
							if($previous_students_sregno=="" || $previous_students_sregno!=$students_array['students_sregno']){
								$data = $data.'<td bgcolor="#EEEEEE" width="20px;"><b>'.$nPresent.'</b></td>';
								$data = $data.'<td bgcolor="#EEEEEE" width="20px;"><b>'.$nAbsent.'</b></td>';
								$data = $data.'</tr>';
							}
							
							$previous_students_sregno = $students_array['students_sregno'];
									
						}
					$data = $data.'</table>';
					$data = $data.'</td>';
					$data = $data.'</tr>';
					$data = $data.'</table>';
				}
			
			}
			break;
		case 'CLOSEPERIOD':	// Dues Statement
		
			$document->AddPage('P','A4',true);
			
			$result_query = tep_db_query("SELECT tempstorage_data FROM ".TABLE_TEMPSTORAGE."  WHERE tempstorage_id='".$_GET['id']."'");
			
			if(tep_db_num_rows($result_query)<=0){
				$data = '<table border="0" cellpadding="2" cellspacing="0" width="100%">';
				$data = $data.'<tr><td colspan="2" align="centre">'.$lablearray['310'].'</td></tr>';
				$data = $data.'</table>';				
			 }else{
			 	
			 	$transactions_array = tep_db_fetch_array($result_query);
			 	$data = $data.'<table border="0" cellpadding="0" cellspacing="0" width="100%">';
				$data = $data.'<tr><td colspan="2" align="center"><font size="18"><b>Period Transactions</b><br><br></font></td></tr>';
				$data = $data.'</table>';
				$data = $data.$transactions_array['tempstorage_data'];
			 }
			break;
		case 'RCT':		
	
			# fetch reciept
			$reciepts_query = tep_db_query("SELECT reciepts FROM " .TABLE_RECIEPTS." WHERE reciepts_code='".$_GET['recid']."'");
			$reciepts_array = tep_db_fetch_array($reciepts_query);
			$data = $reciepts_array['reciepts'];			
			$document->setPrintHeader(false);
			$document->setPrintFooter(false);
			$document->SetLeftMargin('0.1');
			$document->SetRightMargin('0.1');
			$document->SetTopMargin('0.1');
			//set auto page breaks
			$document->SetAutoPageBreak(false,0);
			$document->AddPage('L','A7',true);
			
			break;			
		case 'EPI':	// Employee Payroll information 
			
			$document->AddPage('P','A4',true);

			$document->SetFont('', '', 8);
			$data = $_SESSION['payrolldata'];
			break;
			
		case 'CASHRPT':
			
			getlables("323");
			
			$data = $data.'<table border="0" cellpadding="0" cellspacing="0" width="100%">';
			$data = $data.'<tr><td colspan="2" align="center"><font size="18"><b>'.$lablearray['323'].'</b><br><br></font></td></tr>';
			$data = $data.'</table>';
			
			// date from and date to
			tep_db_query("DROP TABLE IF EXISTS cCash");
			tep_db_query("CREATE TEMPORARY TABLE cCash AS SELECT * FROM ".TABLE_GENERALLEDGER." as gl WHERE generalledger_datecreated BETWEEN ".changeDateFromPageToMySQLFormat($_GET['txtFrom'])." AND ".changeDateFromPageToMySQLFormat($_GET['txtTo']));
						
			if($_GET['cashaccounts_id']!=''){
				tep_db_query("DROP TABLE IF EXISTS cCash1");
				tep_db_query("CREATE TEMPORARY TABLE cCash1 AS SELECT * FROM cCash WHERE chartofaccounts_accountcode='".$_GET['cashaccounts_id']."'");
			
			}else{
				tep_db_query("DROP TABLE IF EXISTS cCash1");
				tep_db_query("CREATE TEMPORARY TABLE cCash1 AS SELECT * FROM cCash WHERE chartofaccounts_accountcode IN (SELECT chartofaccounts_accountcode FROM ".TABLE_CASHACCOUNTS.")");
			}
			
			//-- Select on Branch
			
			if($_GET['branchcode']!=''){
				tep_db_query("DROP TABLE IF EXISTS cCash2");
				tep_db_query("CREATE TEMPORARY TABLE cCash2 AS SELECT * FROM cCash1 WHERE branchcode='".$_GET['branchcode']."'");
			
			}else{
				tep_db_query("DROP TABLE IF EXISTS cCash2");
				tep_db_query("CREATE TEMPORARY TABLE cCash2 AS SELECT * FROM cCash1");
			}
					
			
			// cash items
			if($_GET['cashitems']!=''){
				tep_db_query("DROP TABLE IF EXISTS cCash3");
				tep_db_query("CREATE TEMPORARY TABLE cCash3 AS SELECT * FROM cCash2 WHERE chartofaccounts_accountcode IN (SELECT tcode FROM  ".TABLE_GENERALLEDGER." WHERE chartofaccounts_accountcode='".$_GET['cashitems']."')");
			}else{
				tep_db_query("DROP TABLE IF EXISTS cCash3");
				tep_db_query("CREATE TEMPORARY TABLE cCash3 AS SELECT * FROM cCash2");

			}
			//$document->setHeader();
			//$document->AddPage('P','A4',true);
			//$document->SetTopMargin(50);
			$document->AddPage('P','A4',true);
			
			getlables("310");							
			
			$result_query = tep_db_query("select DATE_FORMAT(generalledger_datecreated,'%d/%m/%Y') as Date,tcode,generalledger_debit,generalledger_credit,generalledger_description FROM cCash3");
						
			if(tep_db_num_rows($result_query)<=0){
				$data = '<table border="0" cellpadding="2" cellspacing="0" width="100%">';
				$data = $data.'<tr><td colspan="2" align="centre">'.$lablearray['310'].'</td></tr>';
				$data = $data.'</table>';
				break;
			 }
			
			
			getlables("317,323,301,264,328,329,330");
			
			$data = '<table border="0" cellpadding="0" cellspacing="0"><tr><td><font size="18"><b>'.$lablearray['323'].'</b><br><br></font></td></tr>';
			
			$data = $data.'</table>';
			
			if($_GET['cashaccounts_id']!=''){
				tep_db_query("DROP TABLE IF EXISTS cBal");
				$result_query = tep_db_query("SELECT SUM(generalledger_debit) As Debit,SUM(generalledger_credit) As Credit,SUM(generalledger_debit)-SUM(generalledger_credit) AS Bal FROM ".TABLE_GENERALLEDGER." WHERE generalledger_datecreated < ".changeDateFromPageToMySQLFormat($_GET['txtFrom'])." AND chartofaccounts_accountcode ='".$_GET['cashaccounts_id']."'");
			
			}else{
				tep_db_query("DROP TABLE IF EXISTS cBal");
				$result_query = tep_db_query("SELECT SUM(generalledger_debit) As Debit,SUM(generalledger_credit) As Credit,SUM(generalledger_debit)-SUM(generalledger_credit) AS Bal FROM ".TABLE_GENERALLEDGER." WHERE generalledger_datecreated < ".changeDateFromPageToMySQLFormat($_GET['txtFrom'])." AND chartofaccounts_accountcode IN (SELECT chartofaccounts_accountcode FROM ".TABLE_CASHACCOUNTS.")");
			}		
			
			$value = tep_db_fetch_array($result_query);
			
			$nBal = $value['Debit'] - $value['Credit'];
			
			$nrows = 0;
			
			$data .='<table border="0" cellspacing="0" cellpadding="2">';
			$data = $data.'<tr bgcolor="#000000" style="color:#FFFFFF;">';
			$data = $data.'<td><b>'.$lablearray['317'].'</b></td><td><b>'.$lablearray['301'].'</b></td><td><b>'.$lablearray['264'].'</b></td><td><b>'.$lablearray['328'].'</b></td><td><b>'.$lablearray['329'].'</b></td>';
			$data = $data.'</tr>';
			
			$data = $data.'<tr bgcolor="'.$rowcolor.'">';			
			$data = $data.'<td></td><td></td><td>'.$lablearray['330'].'</td><td></td><td>'.$nBal.'</td>';
			$data = $data.'</tr>';
			
			$result_query = tep_db_query("select chartofaccounts_accountcode,DATE_FORMAT(generalledger_datecreated,'%d/%m/%Y') as Date,tcode,generalledger_debit,generalledger_credit,generalledger_description FROM cCash3");
			
						
			while($row = tep_db_fetch_array($result_query)){
				// check see if we have 10 records
				// add new page							
				if($nrows == 46){
					$nrows = 0;	
					$data = $data.'</table>';	
					
					$document->writeHTML($data, false, false, false, false, '');
					
					$document->AddPage('P','A4',true);		
									
				//	$data ='<table border="0" cellspacing="0" cellpadding="2">';
				//	$data = $data.'<tr>';
				//	$data = $data.'<td><b>Date</b></td><td><b>Name</b></td><td><b>Item</b></td><td><b>Amount</b></td><td><b>Class</b></td></td>';
				//	$data = $data.'</tr>';						
				}
				
				// swicth row colors
				if($rowcolor == ""){
					$rowcolor = "#D5E7FF";
				}else{
					$rowcolor = "";							
				}
											
				$data = $data.'<tr bgcolor="'.$rowcolor.'">';			
				$data = $data.'<td>'.$row['Date'].'</td><td> '.$row['tcode'].'</td><td>'.$row['generalledger_description'].'</td><td align="right">'.formatNumber($row['generalledger_debit']).'</td><td align="right">'.formatNumber($row['generalledger_credit']).'</td>';
				$data = $data.'</tr>';
				$nrows++;																	
			}
						
			$data = $data.'</table>';
				
			break;
			
		case 'DS':	// Dues Statement
		
			$document->AddPage('P','A4',true);
			
			$result_query = tep_db_query("SELECT DATE_FORMAT(sd.studentsdues_datecreated,'%d/%m/%Y') AS date,studentsdues_amount,CONCAT(students_firstname,'',students_lastname) As name,requirements_name,(SELECT classes_name FROM ".TABLE_CLASSES." AS c WHERE c.classes_id=sd.classes_id)  as class,studentsdues_amount FROM " .TABLE_STUDENTSDUES." AS sd,".TABLE_STUDENTS." AS s,".TABLE_REQUIREMENTS." AS r WHERE  r.requirements_id=sd.requirements_id AND s.students_sregno=sd.students_sregno  AND sd.students_sregno='".$_GET['students_sregno']."' AND feecategories_id='".$_GET['catid']."' AND studentsdues_datecreated >= (SELECT schoolsessionfeecategories_datecreated FROM ".TABLE_SCHOOLSESSIONFEECATEGORIES." WHERE feecategories_id='".$_GET['catid']."' AND schoolsessionfeecategories_currentflag='Y' )");
			
			
			$data = $data.'<table border="0" cellpadding="0" cellspacing="0" width="100%">';
			$data = $data.'<tr><td colspan="2" align="center"><font size="18"><b>Student Bills</b><br><br></font></td></tr>';
			$data = $data.'</table>';
					
			
			if(tep_db_num_rows($result_query)<=0){
				$data ="";
				$data = $data.'<table border="0" cellpadding="5" cellspacing="0" width="100%">';
				$data = $data.'<tr><td colspan="2" align="centre">Sorry, there is no information to diplay for this report</td></tr>';
				$data = $data.'</table>';
				break;
			 }
			 
			$nrows = 0;
			
			$data .='<table border="0" cellspacing="0" cellpadding="2">';
			$data = $data.'<tr bgcolor="#000000" style="color:#FFFFFF;">';
			$data = $data.'<td><b>Date</b></td><td><b>Name</b></td><td><b>Item</b></td><td><b>Amount</b></td><td><b>Class</b></td>';
			$data = $data.'</tr>';
					
			while($row = tep_db_fetch_array($result_query)){
				// check see if we have 10 records
				// add new page							
				if($nrows == 46){
					$nrows = 0;	
					$data = $data.'</table>';	
					
					$document->writeHTML($data, false, false, false, false, '');
					
					$document->AddPage('P','A4',true);		
									
				//	$data ='<table border="0" cellspacing="0" cellpadding="2">';
				//	$data = $data.'<tr>';
				//	$data = $data.'<td><b>Date</b></td><td><b>Name</b></td><td><b>Item</b></td><td><b>Amount</b></td><td><b>Class</b></td></td>';
				//	$data = $data.'</tr>';						
				}
				
				// swicth row colors
				if($rowcolor == ""){
					$rowcolor = "#D5E7FF";
				}else{
					$rowcolor = "";							
				}
											
				$data = $data.'<tr bgcolor="'.$rowcolor.'">';			
				$data = $data.'<td>'.$row['date'].'</td><td> '.$row['name'].'</td><td>'.$row['requirements_name'].'</td><td>'.formatNumber($row['studentsdues_amount']).'</td><td>'.$row['class'].'</td>';
				$data = $data.'</tr>';
				$nrows++;													
			}
						
			$data = $data.'</table>';
			
			break;
			
		case 'GDS':	// General Dues Statement
		
			$document->AddPage('P','A4',true);
			
			if($_GET['tcode']!=''){
				$result_query = tep_db_query("SELECT sd.students_sregno,DATE_FORMAT(sd.studentsdues_datecreated,'%d/%m/%Y') AS date,studentsdues_amount,CONCAT(students_firstname,' ',students_lastname) As name,requirements_name,(SELECT classes_name FROM ".TABLE_CLASSES." AS c WHERE c.classes_id=sd.classes_id)  as class,studentsdues_amount FROM " .TABLE_STUDENTSDUES." AS sd,".TABLE_STUDENTS." AS s,".TABLE_REQUIREMENTS." AS r WHERE  r.requirements_id=sd.requirements_id AND s.students_sregno=sd.students_sregno  AND sd.tcode='".$_GET['tcode']."' ORDER BY s.students_firstname,sd.students_sregno");
			}else{
			
				$result_query = tep_db_query("SELECT sd.students_sregno,DATE_FORMAT(sd.studentsdues_datecreated,'%d/%m/%Y') AS date,studentsdues_amount,CONCAT(students_firstname,' ',students_lastname) As name,requirements_name,(SELECT classes_name FROM ".TABLE_CLASSES." AS c WHERE c.classes_id=sd.classes_id)  as class,studentsdues_amount FROM " .TABLE_STUDENTSDUES." AS sd,".TABLE_STUDENTS." AS s,".TABLE_REQUIREMENTS." AS r WHERE  r.requirements_id=sd.requirements_id AND s.students_sregno=sd.students_sregno  AND sd.studentsdues_datecreated  BETWEEN ".changeDateFromPageToMySQLFormat($_GET['date_from'])." AND ".changeDateFromPageToMySQLFormat($_GET['date_to'])." ORDER BY s.students_firstname,sd.students_sregno");
			}
			
			$data = $data.'<table border="0" cellpadding="0" cellspacing="0" width="100%">';
			$data = $data.'<tr><td colspan="2" align="center"><font size="18"><b> General Student Bills report</b><br><br></font></td></tr>';
			$data = $data.'</table>';
					
			
			if(tep_db_num_rows($result_query)<=0){
				$data ="";
				$data = $data.'<table border="0" cellpadding="5" cellspacing="0" width="100%">';
				$data = $data.'<tr><td colspan="2" align="centre">Sorry, there is no information to diplay for this report</td></tr>';
				$data = $data.'</table>';
				break;
			 }
			 
			$nrows = 0;
			
			$data .='<table border="0" cellspacing="0" cellpadding="2">';
			$data = $data.'<tr bgcolor="#000000" style="color:#FFFFFF;">';
			$data = $data.'<td><b>Admission No.</b></td><td><b>Date</b></td><td><b>Name</b></td><td><b>Item</b></td><td><b>Amount</b></td><td><b>Class</b></td>';
			$data = $data.'</tr>';
					
			while($row = tep_db_fetch_array($result_query)){
				// check see if we have 10 records
				// add new page							
				if($nrows == 46){
					$nrows = 0;	
					$data = $data.'</table>';	
					
					$document->writeHTML($data, false, false, false, false, '');
					
					$document->AddPage('P','A4',true);		
									
								
				}
				
				// swicth row colors
				if($rowcolor == ""){
					$rowcolor = "#D5E7FF";
				}else{
					$rowcolor = "";							
				}
											
				$data = $data.'<tr bgcolor="'.$rowcolor.'">';			
				$data = $data.'<td>'.$row['students_sregno'].'</td><td>'.$row['date'].'</td><td> '.$row['name'].'</td><td>'.$row['requirements_name'].'</td><td>'.formatNumber($row['studentsdues_amount']).'</td><td>'.$row['class'].'</td>';
				$data = $data.'</tr>';
				$nrows++;													
			}
						
			$data = $data.'</table>';
			
			break;
		case 'SR':
		
		if($_GET['classes_id']!=""){
			$cWhereclause =" WHERE classes_id='".$_GET['classes_id']."'";
		}
		
		getlables("73,38,39,251,252,253,254");
		
		// get the number of students at beginning of period
		$results_before_query = tep_db_query(" SELECT COUNT(students_sregno) AS No FROM ".TABLE_STUDENTS."  WHERE students_dateenrolled <= ".changeDateFromPageToMySQLFormat($_GET['txtFrom'])." AND students_dateenrolled <=".changeDateFromPageToMySQLFormat($_GET['txtFrom']));
		
		$array_before = tep_db_fetch_array($results_before_query);
	
		// get the number of students at beginning of after
		$results_after_query = tep_db_query(" SELECT COUNT(students_sregno) AS No FROM ".TABLE_STUDENTS."  WHERE students_dateenrolled <= ".changeDateFromPageToMySQLFormat($_GET['txtFrom'])." AND students_dateenrolled <=".changeDateFromPageToMySQLFormat($_GET['txtTo']));
		
		$array_after = tep_db_fetch_array($results_after_query);
		
		
		// get the number of students at during period
		$results_inperiod_query = tep_db_query(" SELECT COUNT(students_sregno) AS No FROM ".TABLE_STUDENTS."  WHERE students_dateenrolled BETWEEN ".changeDateFromPageToMySQLFormat($_GET['txtFrom'])." AND ".changeDateFromPageToMySQLFormat($_GET['txtTo']));
		
		$array_period = tep_db_fetch_array($results_inperiod_query);
										
		$data = $data.'<div style="margin:5;border:1px #EEEEEE;">';		
						
		$data = $data.'<table border="0" cellpadding="0" cellspacing="0" width="100%">';
		$data = $data.'<tr><td colspan="2" align="center"><font size="18"><b>'.$lablearray['73'].'</b><br><br></font></td></tr>';
		$data = $data.'</table>';
		$data = $data.'<table border="0" cellpadding="2" cellspacing="2" width="100%">';
		$data = $data.'<tr bgcolor="#EEEEEE"><td align="center"><b>'.$lablearray['38'].'</b></td><td align="center"><b>'.$lablearray['39'].'</b></td></tr>';
		$data = $data.'<tr><td align="center"><b>'.date('F d, Y', strtotime($_GET['txtFrom'])).'</b></td><td align="center"><b>'.date('F d, Y', strtotime($_GET['txtTo'])).'</b></td></tr>';
		$data = $data.'</table>';
		$data = $data.'<table border="0" cellpadding="3" cellspacing="0">';
		
		if($array_before['No']==0){
			$array_before['No']=1;
		}
		
		$data = $data.'<tr ><td style="border-bottom:1 thin #EEEEEE;">'.$lablearray['251'].'</td><td style="border-bottom:1 thin #EEEEEE;">'.(($array_after['No']-$array_period['No'])/$array_before['No']*100).'</td></tr>';								
		$data = $data.'<tr ><td style="border-bottom:1 thin #EEEEEE;">'.$lablearray['252'].'</td><td style="border-bottom:1 thin #EEEEEE;">'.(($array_period['No'])/$array_before['No']*100).'</td></tr>';								
		$data = $data.'<tr ><td style="border-bottom:1 thin #EEEEEE;">'.$lablearray['253'].'</td><td style="border-bottom:1 thin #EEEEEE;">'.(($array_before['No']-$array_after['No']-$array_period['No'])/$array_before['No']*100).'</td></tr>';								
		$data = $data.'<tr ><td style="border-bottom:1 thin #EEEEEE;">'.$lablearray['254'].'</td><td style="border-bottom:1 thin #EEEEEE;">'.($array_after['No']-$array_before['No']-$array_period['No']).'</td></tr>';								
					
		$data = $data.'</table>';
			
		
		$data = $data.'</div>';
		
		$document->AddPage('L','A4',true);
		
		break;
		
	case 'SRR':	
	
		if($_GET['classes_id']!=""){
			$cWhereclause =" WHERE classes_id='".$_GET['classes_id']."'";
		}
		// get students
		tep_db_query("CREATE TEMPORARY TABLE cStudents1 AS SELECT students_sregno,students_unebindexno,CONCAT(students_firstname,'',students_lastname) As name,classcategories_id,students_gender FROM ".TABLE_STUDENTS."  WHERE students_dateenrolled >= ".changeDateFromPageToMySQLFormat($_GET['txtFrom'])." AND students_dateenrolled <=".changeDateFromPageToMySQLFormat($_GET['txtTo']));
		
		
		
		
		//echo "SELECT students_sregno,students_unebindexno,CONCAT(students_firstname,'',students_lastname) As name,classcategories_id,students_gender FROM ".TABLE_STUDENTS."  WHERE students_dateenrolled >= ".changeDateFromPageToMySQLFormat($_POST['txtFrom'])." AND students_dateenrolled <=".changeDateFromPageToMySQLFormat($_POST['txtTo']);
		/*echo "SELECT students_sregno,students_unebindexno,CONCAT(students_firstname,'',students_lastname) As name,classcategories_id,students_gender FROM ".TABLE_STUDENTS."  WHERE students_dateenrolled >= ".changeDateFromPageToMySQLFormat($_GET['txtFrom'])." AND students_dateenrolled <=".changeDateFromPageToMySQLFormat($_GET['txtTo']);
		$results_query = tep_db_query("select * from cStudents1");
		while ($array = tep_db_fetch_array($results_query)) {
			print_r($array);
		}
		exit();*/
		// get fee category
		tep_db_query("CREATE TEMPORARY TABLE cStudents2 AS SELECT cStudents1.*,feecategories_id FROM cStudents1 LEFT JOIN ".TABLE_STUDENTFEECATEGORIES." AS fc ON fc.students_sregno=cStudents1.students_sregno");
		
		
		// get class
		tep_db_query("CREATE TEMPORARY TABLE cStudents3 AS SELECT cStudents2.*,classes_id,(SELECT feecategories_name FROM ".TABLE_FEE_CATEGORIES." WHERE feecategories_id=cStudents2.feecategories_id) AS feecategories_name FROM cStudents2 LEFT JOIN ".TABLE_STUDENTCLASSES." AS sc ON sc.students_sregno=cStudents2.students_sregno");
		
		// get fee category
		tep_db_query("CREATE TEMPORARY TABLE cStudents4 AS SELECT cStudents3.*,IFNULL(classes_name,'') AS classes_name  FROM cStudents3 LEFT JOIN ".TABLE_CLASSES." AS c ON c.classes_id=cStudents3.classes_id ");
		
		// get dues
		tep_db_query("CREATE TEMPORARY TABLE cDues AS SELECT students_sregno,SUM(IFNULL(studentsdues_amount,0.00000)) as Due  FROM ".TABLE_STUDENTSDUES."  WHERE students_sregno IN ( SELECT students_sregno FROM cStudents2) GROUP BY students_sregno");
										
		// get payments
		tep_db_query("CREATE TEMPORARY TABLE cPayments AS SELECT students_sregno,SUM(IFNULL(studentspayments_amount,0.00000)) as Paid FROM  ".TABLE_STUDENTSPAYMENTS."  WHERE students_sregno IN (SELECT students_sregno FROM cStudents2) GROUP BY students_sregno");
		
		// compute payments
		tep_db_query("CREATE TEMPORARY TABLE cOuts AS SELECT cDues.students_sregno,(IFNULL(Due,0.00000)- IFNULL(Paid,0.00000)) as Balance FROM cDues LEFT JOIN cPayments ON cPayments.students_sregno=cDues.students_sregno");

		tep_db_query("CREATE TEMPORARY TABLE cStudents5 AS SELECT cStudents4.*,ROUND(IFNULL(Balance,0.00000),".SETTTING_ROUND_TO.") AS Balance FROM cStudents4 LEFT JOIN cOuts ON cOuts.students_sregno=cStudents4.students_sregno");
					
		// add balances to stduents details 
		$results_query = tep_db_query("SELECT cStudents5.* FROM cStudents5 ".$cWhereclause);
						
		$data = $data.'<div style="margin:5;border:1px #EEEEEE;text-align:center;">';
		
		if(tep_db_num_rows($results_query)>0){
			
			getlables("9,189,199,194,79,191,249");
						
			$data = $data.'<table border="0" cellpadding="0" cellspacing="0" width="100%">';
			$data = $data.'<tr><td colspan="2" align="center"><font size="18"><b>Students report</b><br><br></font></td></tr>';
			$data = $data.'</table>';
			$data = $data.'<table border="0" cellpadding="2" cellspacing="2" width="100%">';
			$data = $data.'<tr bgcolor="#EEEEEE"><td align="center"><b>From</b></td><td align="center"><b>To</b></td></tr>';
			$data = $data.'<tr><td align="center">'.date('F d, Y', strtotime($_POST['txtFrom'])).'</td><td align="center">'.date('F d, Y', strtotime($_POST['txtFrom'])).'</td></tr>';
			$data = $data.'</table>';
			$data = $data.'<table border="0" cellpadding="1" cellspacing="0">';
			$data = $data.'<tr bgcolor="#EEEEEE"><td><b>'.$lablearray['9'].'</b></td><td><b>'.$lablearray['9'].'</b></td><td><b>'.$lablearray['199'].'</b></td><td><b>'.$lablearray['194'].'</b></td><td><b>'.$lablearray['79'].'</b></td><td><b>'.$lablearray['191'].'</b></td><td align="RIGHT"><b>'.$lablearray['191'].'</b></td></tr>';
		
			while ($array = tep_db_fetch_array($results_query)) {			
				$data = $data.'<tr ><td style="border-bottom:1 thin #EEEEEE;">'.$array['students_sregno'].'</td><td style="border-bottom:1 thin #EEEEEE;">'.$array['name'].'</td><td style="border-bottom:1 thin #EEEEEE;">'.$array['students_gender'].'</td><td style="border-bottom:1 thin #EEEEEE;">'.$array['classes_name'].'</td><td style="border-bottom:1 thin #EEEEEE;">'.$array['feecategories_name'].'</td><td style="border-bottom:1 thin #EEEEEE;" >'.$array['students_unebindexno'].'</td><td align="right">'.$array['Balance'].'</td></tr>';								
			}
		
			$data = $data.'</table>';
			
		}else{
			getlables("250");
			$data = $data.$lablearray['250'];	
		}
		$data = $data.'</div>';
		
		//$document->AddPage('L','A4',true);
		
		break;
	
	case 'IR':
	
		if($_GET['classes_id']!=""){
			$cWhereclause = " AND students_sregno IN ( SELECT students_sregno FROM ".TABLE_STUDENTCLASSES." WHERE classes_id='".$_GET['classes_id']."'";
		}		
		
		$results_query = tep_db_query("SELECT students_firstname,students_lastname,CONCAT(user_firstname,user_lastname) As User,(SELECT requirements_name FROM ".TABLE_REQUIREMENTS." WHERE requirements_id=r.requirements_id) As requirements_name,DATE_FORMAT(r.itemrations_date,'%d/%m/%Y') As itemrations_date,r.students_sregno,(SELECT classes_name FROM ".TABLE_CLASSES." c WHERE c.classes_id=r.classes_id) as Class FROM ".TABLE_ITEMRATIONS." r, ".TABLE_STUDENTS." s, ".TABLE_USERS." u WHERE s.students_sregno=r.students_sregno AND r.user_id=u.user_id AND  r.itemrations_date BETWEEN ".changeDateFromPageToMySQLFormat($_GET['txtFrom'])." AND ".changeDateFromPageToMySQLFormat($_GET['txtTo'])." ".$cWhereclause." ORDER BY students_firstname,students_lastname");
		//echo "SELECT r.students_sregno,students_firstname,students_lastname,CONCAT(user_firstname,user_lastname) As User,(SELECT requirements_name FROM ".TABLE_REQUIREMENTS." WHERE requirements_id=r.requirements_id) As requirements_name,DATE_FORMAT(r.itemrations_date,'%d/%m/%Y') As itemrations_date,r.students_sregno,(SELECT classes_name FROM ".TABLE_CLASSES." c WHERE c.classes_id=r.classes_id) as Class FROM ".TABLE_ITEMRATIONS." r, ".TABLE_STUDENTS." s, ".TABLE_USERS." u WHERE s.students_sregno=r.students_sregno AND r.user_id=u.user_id AND  r.itemrations_date BETWEEN ".changeDateFromPageToMySQLFormat($_GET['txtFrom'])." AND ".changeDateFromPageToMySQLFormat($_GET['txtTo'])." ".$cWhereclause." ORDER BY students_firstname,students_lastname";
		$data = $data.'<div style="margin:5;border:1px #EEEEEE;text-align:center;">';
		
		if(tep_db_num_rows($results_query)>0){
			
			getlables("9,194,623,270,628,238,240,628,627");
						
			$data = $data.'<table border="0" cellpadding="0" cellspacing="0" width="100%">';
			$data = $data.'<tr><td colspan="2" align="center"><font size="18"><b>'.$lablearray['627'].'</b><br><br></font></td></tr>';
			$data = $data.'</table>';
			$data = $data.'<table border="0" cellpadding="2" cellspacing="2" width="100%">';
			$data = $data.'<tr bgcolor="#EEEEEE"><td align="center"><b>From</b></td><td align="center"><b>To</b></td></tr>';
			$data = $data.'<tr><td align="center">'.date('F d, Y', strtotime($_GET['txtFrom'])).'</td><td align="center">'.date('F d, Y', strtotime($_GET['txtTo'])).'</td></tr>';
			$data = $data.'</table>';
			$data = $data.'<table border="0" cellpadding="2" cellspacing="0">';
			$data = $data.'<tr bgcolor="#EEEEEE"><td align="Left"><b>'.$lablearray['238'].'</b></td><td align="Left"><b>'.$lablearray['240'].'</b></td><td align="Left"><b>'.$lablearray['194'].'</b></td><td align="Left"><b></b></td><td align="Left"><b>'.$lablearray['623'].'</b></td></tr>';
			
			$last_students_sregno ="";
			
			while ($array_values = tep_db_fetch_array($results_query)) {	
			
				if($array_values['students_sregno']==$last_students_sregno){
					$data = $data.'<tr ><td style="border-bottom:1 thin #EEEEEE;"></td><td style="border-bottom:1 thin #EEEEEE;"></td><td style="border-bottom:1 thin #EEEEEE;" colspan="2">'.$array_values['requirements_name'].' '.$lablearray['628'].': '.$array_values['User'].'</td><td style="border-bottom:1 thin #EEEEEE;">'.$array_values['itemrations_date'].'</td></tr>';	
				}else{
				
					$data = $data.'<tr ><td style="border-bottom:1 thin #999999;" align="Left"><b>'.$array_values['students_firstname'].'</b></td><td style="border-bottom:1 thin #999999;" align="Left"><b>'.$array_values['students_lastname'].'</b></td><td style="border-bottom:1 thin #999999;" align="Left"><b>'.$array_values['Class'].'</b></td><td style="border-bottom:1 thin #999999;" align="Left"></td><td style="border-bottom:1 thin #999999;"></td></tr>';								
				
					$data = $data.'<tr ><td style="border-bottom:1 thin #EEEEEE;"></td><td style="border-bottom:1 thin #EEEEEE;"></td><td style="border-bottom:1 thin #EEEEEE;" colspan="2">'.$array_values['requirements_name'].' '.$lablearray['628'].': '.$array_values['User'].'</td><td style="border-bottom:1 thin #EEEEEE;">'.$array_values['itemrations_date'].'</td></tr>';								
				
				}
				
				$last_students_sregno = $array_values['students_sregno'];
				
				//echo $array['requirements_name']."<br>";				
				
			}
		
			$data = $data.'</table>';
			
			//echo $data;
			
		}else{
			getlables("250");
			$data = $data.$lablearray['250'];	
		}
		$data = $data.'</div>';
		
		$document->AddPage('L','A4',true);
		
		break;
		
	case 'DEPRPT':
		$results_query = tep_db_query("SELECT assets_name,DATE_FORMAT(assets_beginservicedate,'%d/%m/%Y') AS assets_beginservicedate,assets_bookvalue,assets_costprice,depreciation_amount FROM ".TABLE_DEPRECIATION." as d,".TABLE_ASSETS." a WHERE depreciation_confirmed='Y' ANDdepreciation_datecreated >=".changeDateFromPageToMySQLFormat($_GET['txtFrom'])." AND depreciation_datecreated<=".changeDateFromPageToMySQLFormat($_GET['txtTo']));
		
		$data = $data.'<div style="margin:5;border:1px #EEEEEE;">';
		
		if(tep_db_num_rows($results_query)>0){
			
			$data = $data.'<table border="0" cellpadding="0" cellspacing="0" width="100%">';
			$data = $data.'<tr><td colspan="2" align="center"><font size="18"><b>Asset Depreciation report</b><br><br></font></td></tr>';
			$data = $data.'</table>';
			$data = $data.'<table border="0" cellpadding="2" cellspacing="2" width="100%">';
			$data = $data.'<tr bgcolor="#EEEEEE"><td align="center"><b>Fiscal Year</b></td><td align="center"><b>From</b></td><td align="center"><b>To</b></td></tr>';
			$data = $data.'<tr><td align="center">'.date('F d, Y', strtotime(STARTFINYEAR)).'</td><td align="center">'.date('F d, Y', strtotime($_GET['txtFrom'])).'</td><td align="center">'.date('F d, Y', strtotime($_GET['txtTo'])).'</td></tr>';
			$data = $data.'</table>';
			$data = $data.'<table border="0" cellpadding="2" cellspacing="0">';
			$data = $data.'<tr bgcolor="#EEEEEE"><td><b>Item</b></td><td><b>Begun Service</b></td><td align="right"><b>Cost Price</b></td><td align="right"><b>Book value</b></td><td align="right"><b>Depeciation amount</b></td></tr>';
		
			while ($array = tep_db_fetch_array($results_query)) {			
				$data = $data.'<tr><td>'.$array['assets_name'].'</td><td >'.$array['assets_beginservicedate'].'</td><td align="right">'.$array['assets_costprice'].'</td><td align="right">'.$array['assets_bookvalue'].'</td><td align="right">'.formatNumber($array['depreciation_amount']).'</td></tr>';								
			}
		
			$data = $data.'</table>';
			
		}else{
			$data = $data."There is no depreciation information to display.";	
		}
		$data = $data.'</div>';
		
		$document->AddPage('L','A4',true);
		
		break;
	case 'DEP': // Depreciation report<br />
		
		$results_query = tep_db_query("SELECT assets_name,DATE_FORMAT(assets_beginservicedate,'%d/%m/%Y') AS assets_beginservicedate,assets_bookvalue,assets_costprice,depreciation_amount FROM ".TABLE_DEPRECIATION." as d,".TABLE_ASSETS." a WHERE depreciation_confirmed='N'");
		
		$data = $data.'<div style="margin:5;border:1px #EEEEEE;">';
		
		if(tep_db_num_rows($results_query)>0){
			
			$data = $data.'<table border="0" cellpadding="0" cellspacing="0" width="100%">';
			$data = $data.'<tr><td colspan="2" align="center"><font size="18"><b>Asset Depreciation report</b><br><br></font></td></tr>';
			$data = $data.'</table>';
			$data = $data.'<table border="0" cellpadding="2" cellspacing="2" width="100%">';
			$data = $data.'<tr bgcolor="#EEEEEE"><td align="center"><b>Fiscal Year</b></td><td align="center"><b>Depreciation date</b></td></tr>';
			$data = $data.'<tr><td align="center">'.date('F d, Y', strtotime(STARTFINYEAR)).'</td><td align="center">'.date('F d, Y', strtotime($_GET['depdate'])).'</td></tr>';
			$data = $data.'</table>';
			$data = $data.'<table border="0" cellpadding="2" cellspacing="0">';
			$data = $data.'<tr bgcolor="#EEEEEE"><td><b>Item</b></td><td><b>Begun Service</b></td><td align="right"><b>Cost Price</b></td><td align="right"><b>Book value</b></td><td align="right"><b>Depeciation amount</b></td></tr>';
		
			while ($array = tep_db_fetch_array($results_query)) {			
				$data = $data.'<tr><td>'.$array['assets_name'].'</td><td >'.$array['assets_beginservicedate'].'</td><td align="right">'.$array['assets_costprice'].'</td><td align="right">'.$array['assets_bookvalue'].'</td><td align="right">'.formatNumber($array['depreciation_amount']).'</td></tr>';								
			}
		
			$data = $data.'</table>';
			
		}else{
			$data = $data."There is no depreciation information to display.";	
		}
		$data = $data.'</div>';
		
		$document->AddPage('L','A4',true);
		
	break;

	case 'RECON': // Reconciliation report
		
		
		// now gererate report
		$data = $data.'<div style="margin:5;border:1px #EEEEEE;">';
		
		$bankaccounts_accno = "'".$_GET['accFrom']."','".$_GET['accTo']."'";
								
		$query = "SELECT DISTINCT rh.tcode,bankaccounts_accno,IF(rh.tcode='00000000000','Opening Balance',generalledger_description) as generalledger_description,debit,credit,bankstatement_datecreated,generalledger_description FROM ".TABLE_RECONCILIATIONHISTORY." as rh LEFT JOIN ".TABLE_GENERALLEDGER." gl ON rh.tcode=gl.tcode WHERE  bankstatement_datecreated>=".changeDateFromPageToMySQLFormat($_GET['txtFrom'])." AND bankstatement_datecreated<=".changeDateFromPageToMySQLFormat($_GET['txtTo'])."  AND bankaccounts_accno IN (SELECT bankaccounts_accno FROM ".TABLE_BANKACCOUNTS." WHERE chartofaccounts_accountcode IN(".$bankaccounts_accno.")) ORDER BY rh.tcode,bankstatement_datecreated";
			
		$_SESSION['downloadlist'] = $query;
		
		$query_results = tep_db_query($query);
		
		if(tep_db_num_rows($query_results)>0){
			$data = $data.'<table border="0" cellpadding="0" cellspacing="0" width="100%">';
			$data = $data.'<tr><td colspan="2" bgcolor="#426363" style="color:#FFFFFF;" align="center"><BR><b>Bank Reconciliation Report from '.$_GET['txtFrom'].' to '.$_GET['txtTo'].'</b><BR></td></tr>';
			$data = $data.'<tr><td colspan="2" bgcolor="#426363" style="color:#FFFFFF;" align="center"><BR><b>From Account    '.$_GET['accFrom'].' To Account    '.$_GET['accTo'].'</b><BR></td></tr>';
			$data = $data.'</table>';
			$data = $data.'<table border="0" cellpadding="2" cellspacing="0">';
			$data = $data.'<tr bgcolor="#EEEEEE"><td><b>Date</b></td><td><b>Transaction Code</b></td><td><b>Description</b></td><td><b>Debit</b></td><td><b>Credit</b></td><td><b>Balance</b></td></tr>';
							
			$cDebit = 0 ;
			$cCredit = 0;
			$bankAccount ="";
			
			while ($array = tep_db_fetch_array($query_results)) {
			
				if($bankAccount!=$array['bankaccounts_accno'] && $bankAccount!=""){
					//$data = $data.'<tr bgcolor="#EEEEEE"><td></td><td></td><td></td><td><b>'.$cDebit.'</b></td><td><b>'.$cCredit.'</b></td></tr>';
					//$data = $data.'<tr bgcolor="#EEEEEE"><td></td><td></td><td></td><td><b>Balance '.$cDebit-$cCredit.'</b></td><td><b></b></td></tr>';																
					$cDebit = 0 ;
					$cCredit = 0;
					//$data = $data.'<tr><td></td><td></td><td></td><td></td><td></td></tr>';								
					//$data = $data.'<tr bgcolor="#EEEEEE"><td><b>'.$array['bankaccounts_accno'].'</b></td><td></td><td></td><td></td><td></td></tr>';								
				}
				$cDebit = $cDebit + $array['debit'];
				
				$cCredit = $cCredit + $array['credit'];
				
				$balance = $cDebit - $cCredit;			
				
				$data = $data.'<tr><td>'.$array['bankstatement_datecreated'].'</td><td>'.$array['tcode'].'</td><td>'.$array['generalledger_description'].'</td><td align="right">'.formatNumber((float)round($array['debit'],SETTTING_ROUND_TO)).'</td><td align="right">'.formatNumber((float)round($array['credit'],SETTTING_ROUND_TO)).'</td><td bgcolor="#C2D699" align="right"><b>'.formatNumber((float)round($balance,SETTTING_ROUND_TO)).'</b></td></tr>';								
				
				$bankAccount = $array['bankaccounts_accno'];
				if($bankAccount!=$array['bankaccounts_accno'] ){
					$data = $data.'<tr bgcolor="#C2D699"><td><b>Balance</b></td><td></td><td></td><td ALIGN="RIGHT"><b>'.formatNumber((float)round($cDebit-$cCredit,SETTTING_ROUND_TO)).'</b></td><td><b></b></td></tr>';
				}
				
				
			}
			
			$data = $data.'<tr><td></td><td></td><td></td><td></td><td></td></tr>';	
			//$data = $data.'<tr bgcolor="#EEEEEE"><td><b>Balance</b></td><td></td><td></td><td><b>'.formatNumber((float)round($cDebit-$cCredit,SETTTING_ROUND_TO)).'</b></td><td><b></b></td></tr>';
			//$data = $data.'<tr bgcolor="#C2D699"><td></td><td></td><td>Bank Statement Balance</td><td></td><td>'.formatNumber((float)round($_GET['statementbal'],SETTTING_ROUND_TO)).'</td></tr>';								
			
			//$data = $data."<tr bgcolor='#C2D699'><td></td><td></td><td >Bank Statement Balance</td><td></td><td>".(float)round($_GET['statementbal'],SETTTING_ROUND_TO)."</td></tr>";
			$data = $data.'</table>';
		}else{
			$data = $data."There is no information to display. Please make sure that you have reconciled your Cashbook for this period.";	
		}
		$data = $data.'</div>';
			
		$document->SetFont('', '', 11);
		
		$document->AddPage('P','A4',true);
		break;
	
	case 'ARR': // arrears report
	
		if($_GET['classes_id']!=""){
			$classes_id = $_GET['classes_id'];		
		}
		
		if($_GET['requirements_id']!=""){
			$requirements_id = $_GET['requirements_id'];							
		}
		
		if($_GET['operator']!=""){
			$op = $_GET['operator'];							
		}else{
			$op='>';		
		}
		
		if($_GET['amount']!=""){
			$amount = $_GET['amount'];							
		}
			
		if($_GET['classes_id']!="" && $_GET['requirements_id']!=""){
			$whereSQL = "sc.classes_id =". $classes_id ." AND r.requirements_id =".$requirements_id;
		}elseif($classes_id!=""){
			$whereSQL = "sc.classes_id =". $classes_id ;	
		}elseif($requirements_id!=""){
			$whereSQL=" r.requirements_id =". $requirements_id ;
		}
		
		if($whereSQL==""){
			$whereSQL = "sc.classes_id LIKE '%%' AND r.requirements_id LIKE '%%'";
		} 
		
		$query = "SELECT studentspayments_id,s.students_firstname,s.students_lastname,c.classes_name,sp.tcode,sp.studentspayments_id,tt.transactiontypes_name,sp.studentspayments_voucher, sp.studentspayments_datecreated,r.requirements_name,sp.studentspayments_balance from " .TABLE_STUDENTSPAYMENTS." as sp,".TABLE_STUDENTS." as s,".TABLE_STUDENTCLASSES." as sc,".TABLE_CLASSES." as c,".TABLE_REQUIREMENTS." as r,".TABLE_TRANSACTIONTYPES." AS tt WHERE sc.students_sregno=sp.students_sregno and c.classes_id=sc.classes_id AND s.students_sregno=sp.students_sregno AND tt.transactiontypes_code=sp.transactiontypes_code AND  sp.requirements_id=r.requirements_id AND ".$whereSQL." GROUP BY sp.students_sregno,r.requirements_id";
		
		$query_results = tep_db_query($query);
		
		$data = $data.'<div style="margin:5;border:1px #EEEEEE;">';
		
		if(tep_db_num_rows($query_results)>0){
			$data = $data.'<table border="0" cellpadding="0" cellspacing="0" width="100%">';
			$data = $data.'<tr><td colspan="2">Income and Expenditure Report</td></tr>';
			$data = $data.'</table>';
			$data = $data.'<table border="0" cellpadding="2" cellspacing="0">';
			$data = $data.'<tr bgcolor="#EEEEEE"><td >Firstname</td><td>Lastname</td><td>Class</td><td>Amount Outstanding</td></tr>';
			while ($array = tep_db_fetch_array($query_results)) {			
				$data = $data.'<tr><td>'.$array['students_firstname'].'</td><td>'.$array['students_lastname'].'</td><td>'.$array['classes_name'].'</td><td>'.$array['studentspayments_balance'].'</td></tr>';								
			}
			$data = $data.'</table>';
		}else{
			$data = $data."There is information to display.";	
		}
		$data = $data.'</div>';
		
		$document->AddPage('L','A4',true);
		
		break;
		
	case 'OS': // Outstanding balances
	
		if($_POST['classes_id']!=""){
			$classes_id = $_POST['classes_id'];		
		}
		
		if($_POST['requirements_id']!=""){
			$requirements_id = $_POST['requirements_id'];							
		}
		
	if($_POST['classes_id']!=""){
			$classes_id = $_POST['classes_id'];		
	}
	
	if($classes_id!=""){
		$whereSQL = "sc.classes_id =". $classes_id ;
	}else{	
		$whereSQL = "  sc.classes_id LIKE '%". $classes_id."%'" ;
	}

	// get the students
	tep_db_query("DROP TABLE IF EXISTS cStudents");		
	tep_db_query("CREATE TEMPORARY TABLE cStudents AS SELECT s.students_sregno,c.classes_name,c.classes_id from ".TABLE_STUDENTS." as s,".TABLE_STUDENTCLASSES." as sc,".TABLE_CLASSES." as c WHERE c.classes_id=sc.classes_id AND  ".$whereSQL." AND sc.studentclasses_currentflag ='Y' GROUP BY s.students_sregno ");
	//tep_db_query($query);
	
	if($requirements_id!=""){
		$whereSQL=" AND requirements_id =". $requirements_id ;
	}else{
	
		$whereSQL="";
	} 
		
	// get the dudes
	tep_db_query("DROP TABLE IF EXISTS cDues");	
	$query = "CREATE TEMPORARY TABLE cDues AS SELECT students_sregno,SUM(studentsdues_amount) AS Dues,requirements_id FROM ".TABLE_STUDENTSDUES." WHERE students_sregno IN (SELECT students_sregno FROM cStudents) ".$whereSQL." GROUP BY students_sregno,requirements_id";	
	tep_db_query($query);
	
	// get the payments
	tep_db_query("DROP TABLE IF EXISTS cPayments");	
	$query = "CREATE TEMPORARY TABLE cPayments AS SELECT students_sregno,SUM(studentspayments_amount) AS payments,requirements_id FROM ".TABLE_STUDENTSPAYMENTS." WHERE students_sregno IN (SELECT students_sregno FROM cStudents) ".$whereSQL." GROUP BY students_sregno,requirements_id";	
	tep_db_query($query);

			
	$query = "SELECT d.students_sregno,(select classes_name  FROM cStudents  WHERE cStudents.students_sregno=d.students_sregno) as classes_name,(select CONCAT(students_firstname ,' ',students_lastname) FROM ".TABLE_STUDENTS." ts  WHERE ts.students_sregno=d.students_sregno) as Name,(select requirements_name FROM ".TABLE_REQUIREMENTS."  AS r where r.requirements_id=d.requirements_id) as requirements_name ,SUM(IF(ISNULL(d.Dues),'0',d.Dues))-SUM(IF(ISNULL(p.payments),'0',p.payments)) AS Balance FROM cDues as d LEFT JOIN cPayments  as p ON d.students_sregno=p.students_sregno AND d.requirements_id=p.requirements_id GROUP BY d.students_sregno,d.requirements_id";
		
	tep_db_query($query);

	$query_results = tep_db_query($query);
	
	$data = $data.'<style>
		tr.blueheading{
			background-color: #0070C0;
			color:#FFFFFF;
		}
	
		tr.greyrowcolor{
			color:#000000;
			background-color: #cccccc;
			
		}
		
		td.datarow{
			color:#000000;
			border: 1px solid #999999;
			
		}
	
	</style>';
		
	$data = $data.'<div style="margin:0;border:1px solid #EEEEEE;padding:0px;height:auto;">';
	
	if(tep_db_num_rows($query_results)>0){
			$lablearray = getlables("135,238,240,194,271");
			$data = $data.'<table border="1" cellpadding="5" cellspacing="0" width="100%" height="100%">';
			$data = $data.'<tr class="blueheading"><td colspan="2" ><h2>'.$lablearray['135'].'</h2></td></tr>';
			$data = $data.'</table>';
			$data = $data.'<table  cellpadding="2" cellspacing="0" border="1">';
			$data = $data.'<tr class="greyrowcolor"><td >'.$lablearray['238'].'</td><td>'.$lablearray['240'].'</td><td>'.$lablearray['194'].'</td><td align="right">'.$lablearray['271'].'</td></tr>';
			$nbal = 0;
			while ($array = tep_db_fetch_array($query_results)) {			
				$data = $data.'<tr ><td class="datarow">'.$array['Name'].'</td><td class="datarow">'.$array['requirements_name'].'</td><td class="datarow">'.$array['classes_name'].'</td><td align="right" class="datarow">'.formatNumber($array['Balance']).'</td></tr>';								
				$nbal = $nbal + $array['Balance'];
			}
			$data = $data.'<tr  class="greyrowcolor"><td>Total</td><td></td><td></td><td align="right">'.formatNumber($nbal).'</td></tr>';								
			$data = $data.'</table>';
		}else{
			$lablearray = getlables("386");
			$data = $data.$lablearray['386'];	
		}
		$data = $data.'</div>';
		
		$document->AddPage('L','A4',true);
		
		break;
	
	case 'CF':
		
	//	start = getstartdate($_GET['txtFrom'])
		
		generateTrialBalance($_GET['txtFrom'],$_GET['txtTo'],'CF',$_GET['cfReports_id']);
		
		 $oDataAccess->setvars('cCashflow','*','SELECT',"");

		 $array_cashflow = $oDataAccess-> tep_db_perform();
		 
		//print_r($array_cashflow);
		// exit();	

		//$query_results = tep_db_query("SELECT * FROM cCashflow");
		//while($array[] = tep_db_fetch_array($query_results)) {}
		foreach($array_cashflow as $key=>$val) {
			
			// get accounts opening balances
			$query_results = tep_db_query("SELECT Startbal FROM cOpeningbals WHERE account BETWEEN '".$val['chartofaccounts_accountcode_from']."' AND '".$val['chartofaccounts_accountcode_to']."'");
			
			$array_openbal = tep_db_fetch_array($query_results);
			
								
			if($val['cflabel_isdebit']=='Y'){
			
				$query_total = tep_db_query("SELECT SUM(generalledger_debit) AS amt FROM cOpeningbals WHERE chartofaccounts_accountcode BETWEEN '".$val['chartofaccounts_accountcode_from']."' AND '".$val['chartofaccounts_accountcode_to']."'");
				$array_total = tep_db_fetch_array($query_total);
			
			}else{				
				$query_total = tep_db_query("SELECT SUM(generalledger_credit) AS amt FROM cGL WHERE chartofaccounts_accountcode BETWEEN '".$val['chartofaccounts_accountcode_from']."' AND '".$val['chartofaccounts_accountcode_to']."'");
				$array_total = tep_db_fetch_array($query_total)	;			
			}
						
					
			if(tep_db_num_rows($query_total)<=0){
				
				//$array_cashflow[$key]['total'] = 0;
				tep_db_query("UPDATE cCashflow SET total=0 WHERE cfheader_id='".$val['cfheader_id']."' AND cflabel_id='".$val['cflabel_id']."'");
			}else{
				 
				 $array_total['amt'] = (float)$array_total['amt'] + (float)$array_openbal['Startbal'];				 
				 
				
				if($array_cashflow[$key]['cfheader_cfincrease']=='Y'){
					//$array_cashflow[$key]['Balance'] = $array_total['amt'];
					tep_db_query("UPDATE cCashflow SET Balance=".(float)$array_total['amt']." WHERE cfheader_id='".$val['cfheader_id']."' AND cflabel_id='".$val['cflabel_id']."'");					
				}else{
					tep_db_query("UPDATE cCashflow SET Balance=-".(float)$array_total['amt']." WHERE cfheader_id='".$val['cfheader_id']."' AND cflabel_id='".$val['cflabel_id']."'");					
					//$array_cashflow[$key]['Balance'] = -$array_total['amt'];
				}
			}
			
			tep_db_free_result($query_results);	
			$array_total = array()	;			
			$array_openbal 		= array();		
			
		}
		
	
		//$cash_results  = tep_db_query("select * from cCashflow"); 
		//$headers = tep_db_fetch_array($cash_results);
		
				
		$data = $data.'<div style="margin:0;border:1px #EEEEEE;">';		
		$data = $data.'<table border="0" cellpadding="5" cellspacing="0" width="100%">';
		$data = $data.'<tr><td colspan="2"><font size="18"><b>Cashflow Report from '.$_GET['txtFrom'].' to '.$_GET['txtTo'].'</b></font></td></tr>';
		$data = $data.'</table>';
		
		$data = $data.'<table border="0" cellpadding="2" cellspacing="0">';
		
		$data = $data.'<tr><td colspan="8" nowrap="nowrap">';
				
		$data = $data.'</td></tr>';
			
		$data = $data.'<tr><td colspan="8" nowrap="nowrap">';
		$data = $data.'<table border="0" cellpadding="2" cellspacing="0">';
		
		// select all headers with report id
		$header_results = tep_db_query("SELECT cfheader_id,cfheader_en FROM ".TABLE_CFHEADER."  WHERE cfReports_id='".$_GET['cfReports_id']."' GROUP BY cfheader_id");
	
		
		while($headers = tep_db_fetch_array($header_results)){
			
			$data = $data.'<tr><td colspan="3" bgcolor="#000000" style="color:#FFFFFF;"><b>'.$headers['cfheader_en'].'</b></td></tr>';
		
			// gel all lables for current header
			$lables_amounts_results = tep_db_query("SELECT * FROM cCashflow  WHERE cfheader_id='".$headers['cfheader_id']."'");
			$nTotal = 0;
			$nBalance = 0;
			
			while($lables_amounts = tep_db_fetch_array($lables_amounts_results)){			
				$data = $data.'<tr><td>'.$lables_amounts['cflabel_en'].'</td><td>'.$lables_amounts['Total']. '</td><td>'.$lables_amounts['Balance']. '</td></tr>';
				$nTotal = $nTotal + $lables_amounts['Total'];
				$nBalance = $nBalance + $lables_amounts['Balance'];
			}
			
			$data = $data.'<tr><td></td><td>Totals</td><td>'.$nTotal.'</td></tr>';
			
			$data = $data.'<tr><td></td><td>Increase-Decrease to cash flow</td><td>'.$nBalance.'</td></tr>';
			
			tep_db_free_result($lables_amounts_results);
			
			$lables_amounts = array();	
		}

		$data = $data.'</table>';
		
		/*$data = $data.'<table border="0" cellpadding="1" cellspacing="0">';
		$data = $data.'<tr><td colspan="2"></td><td bgcolor="#CCCCCC"  align="right"><b>'.formatNumber($nstartdeb).'</b></td><td bgcolor="#CCCCCC"  align="right"><b>'.formatNumber($nstartcred).'</b></td><td bgcolor="#CCCCCC" align="right"><b>'.formatNumber($ndebit).'</b></td><td bgcolor="#CCCCCC" align="right"><b>'.formatNumber($ncredit).'</b></td><td bgcolor="#CCCCCC" align="right"><b>'.formatNumber($nenddeb).'</b></td><td bgcolor="#CCCCCC" align="right"><b>'.formatNumber($nendcred).'</b></td></tr>';
		$data = $data.'</table>';*/
		
		$data = $data.'</td></tr>';
		$data = $data.'</table>';
		$data = $data.'</div>';
			
		if(tep_db_num_rows($header_results)<=0){
			$data ="";
			$data = $data.'<table border="0" cellpadding="5" cellspacing="0" width="100%">';
			$data = $data.'<tr><td colspan="2" align="centre">Sorry, there is no information to diplay for this report</td></tr>';
			$data = $data.'</table>';
		 }
		
		$document->AddPage('L','A4',true);
			
		break;
	case 'INCEXP':
	
		if(STARTFINYEAR==""){
			$data = $data.'<table border="0" cellpadding="5" cellspacing="0" width="100%">';
			$data = $data.'<tr><td colspan="2">Income and Expenditure Report</td></tr>';
			$data = $data.'</table>';
			$data = $data. '<font color="red">Please set the begining fo the financial period</font>';	
			break;		
		}
		
	
		// fot his report to diaplay make sure we have accounts with type 2 int thee CoA
		generateTrialBalance($_GET['txtFrom'],$_GET['txtTo'],'INCEXP');
														
		$query = " select * from cTrialh5";
		
		$query_results =  tep_db_query($query);	
		
		$data = $data.'<div style="margin:0;border:1px #EEEEEE;">';
		
		$data = $data.'<table border="0" cellpadding="5" cellspacing="0" width="100%">';
		$data = $data.'<tr><td colspan="2" align="center"><b>Income and Expenditure Report</b></td></tr>';
		$data = $data.'</table>';
		
		$data = $data.'<table border="0" cellpadding="2" cellspacing="0">';
		//main heading
		$data = $data.'<tr bgcolor="#EEEEEE"><td ><b>Account No</b></td><td><b>Account Name</b></td><td colspan="2" align="centre"><b>Balance as at '.$_GET['txtFrom'].'</b></td><td colspan="2" align="centre"><b>Transactions</b></td><td colspan="2" align="centre"><b>Balance as at '.$_GET['txtTo'].'</b></td></tr>';

		$data = $data.'<tr><td colspan="8" nowrap="nowrap">';
		// subheading
		$data = $data.'<table border="0" cellpadding="1" cellspacing="0">';
		$data = $data.'<tr><td colspan="2"></td><td bgcolor="#CCCCCC" align="right">Debit</td><td bgcolor="#CCCCCC" align="right">Credit</td><td bgcolor="#CCCCCC" align="right">Debit</td><td bgcolor="#CCCCCC" align="right">Credit</td><td bgcolor="#CCCCCC" align="right">Debit</td><td bgcolor="#CCCCCC"  align="right">Credit</td></tr>';
		$data = $data.'</table>';
		
		$data = $data.'</td></tr>';
			
		$data = $data.'<tr><td colspan="8" nowrap="nowrap">';
		$data = $data.'<table border="0" cellpadding="2" cellspacing="0">';
		
		// nitialise amounts
		$nstartdeb = 0;
		$nstartcred = 0;
		$ndebit = 0;
		$ncredit = 0;
		$nenddeb = 0;
		$endcred = 0;
		
		while ($array = tep_db_fetch_array($query_results)) {
			$data = $data.'<tr><td bgcolor="#E9E9E9"><b>'.$array['account'].'</b></td><td bgcolor="#E9E9E9"><b>'. $array['label']. '</b></td><td align="right">'.formatNumber($array['startdeb']).'</td><td align="right">'.formatNumber($array['startcred']).'</td><td align="right">'.formatNumber($array['debit']).'</td><td align="right">'.formatNumber($array['credit']).'</td><td align="right">'.formatNumber($array['enddeb']).'</td><td align="right">'.formatNumber($array['endcred']).'</td></tr>';
		
			// sum it up
			$nstartdeb 		= $nstartdeb + $array['startdeb'];
			$nstartcred 	= $nstartcred + $array['startcred'];
			$ndebit 		= $ndebit + $array['debit'];
			$ncredit		= $ncredit + $array['credit'];
			$nenddeb 		= $nenddeb + $array['enddeb'];
			$nendcred 		= $nendcred + $array['endcred'];
		 }
		$data = $data.'</table>';
		
		$data = $data.'<table border="0" cellpadding="1" cellspacing="0">';
		$data = $data.'<tr><td colspan="2"></td><td bgcolor="#CCCCCC"  align="right"><b>'.formatNumber($nstartdeb).'</b></td><td bgcolor="#CCCCCC"  align="right"><b>'.formatNumber($nstartcred).'</b></td><td bgcolor="#CCCCCC" align="right"><b>'.formatNumber($ndebit).'</b></td><td bgcolor="#CCCCCC" align="right"><b>'.formatNumber($ncredit).'</b></td><td bgcolor="#CCCCCC" align="right"><b>'.formatNumber($nenddeb).'</b></td><td bgcolor="#CCCCCC" align="right"><b>'.formatNumber($nendcred).'</b></td></tr>';
		$data = $data.'</table>';
		
		$data = $data.'</td></tr>';
		 $data = $data.'</table>';
		 $data = $data.'</div>';
		 
		 if(tep_db_num_rows($query_results)<=0){
			$data ="";
			$data = $data.'<table border="0" cellpadding="5" cellspacing="0" width="100%">';
			$data = $data.'<tr><td colspan="2" align="centre">Sorry, there is no information to diplay for this report</td></tr>';
			$data = $data.'</table>';
		 }
		 
		
		 
		$document->AddPage('L','A4',true);
		
		 
			
		break;	
	
	case 'TB':
		
		
		if(STARTFINYEAR==""){
			$data = $data.'<table border="0" cellpadding="2" cellspacing="0">';
			//main heading
			$data = $data.'<tr bgcolor="#EEEEEE"><td >Account No</td><td>Account Name</td><td colspan="2" align="centre">Balance as at '.$_GET['txtFrom'].'</td><td colspan="2" align="centre">Transactions</td><td colspan="2" align="centre">Balance as at '.$_GET['txtTo'].'</td></tr>';
			$data = $data.'</table>';
			$data = $data. '<font color="red">Please set the begining fo the financial period</font>';	
			break;		
		}
		
		generateTrialBalance($_GET['txtFrom'],$_GET['txtTo'],'TB');
														
		$query = " select * from cTrialh5";
		
		$query_results =  tep_db_query($query);	
		
		$data = $data.'<div style="margin:0;border:1px #EEEEEE;">';
		$data = $data.'<table border="0" cellpadding="2" cellspacing="0">';
		//main heading
		$data = $data.'<tr bgcolor="#EEEEEE"><td >Account No</td><td>Account Name</td><td colspan="2" align="centre">Balance as at '.$_GET['txtFrom'].'</td><td colspan="2" align="centre">Transactions</td><td colspan="2" align="centre">Balance as at '.$_GET['txtTo'].'</td></tr>';

		$data = $data.'<tr><td colspan="8" nowrap="nowrap">';
		// subheading
		$data = $data.'<table border="0" cellpadding="1" cellspacing="0">';
		$data = $data.'<tr><td colspan="2"></td><td bgcolor="#CCCCCC" align="right">Debit</td><td bgcolor="#CCCCCC" align="right">Credit</td><td bgcolor="#CCCCCC" align="right">Debit</td><td bgcolor="#CCCCCC" align="right">Credit</td><td bgcolor="#CCCCCC" align="right">Debit</td><td bgcolor="#CCCCCC"  align="right">Credit</td></tr>';
		$data = $data.'</table>';
		
		$data = $data.'</td></tr>';
			
		$data = $data.'<tr><td colspan="8" nowrap="nowrap">';
		$data = $data.'<table border="0" cellpadding="2" cellspacing="0">';
		
		// nitialise amounts
		$nstartdeb = 0;
		$nstartcred = 0;
		$ndebit = 0;
		$ncredit = 0;
		$nenddeb = 0;
		$endcred = 0;
		
		while ($array = tep_db_fetch_array($query_results)) {
			$data = $data.'<tr><td bgcolor="#E9E9E9"><b>'.$array['account'].'</b></td><td bgcolor="#E9E9E9"><b>'. $array['label']. '</b></td><td align="right">'.formatNumber($array['startdeb']).'</td><td align="right">'.formatNumber($array['startcred']).'</td><td align="right">'.formatNumber($array['debit']).'</td><td align="right">'.formatNumber($array['credit']).'</td><td align="right">'.formatNumber($array['enddeb']).'</td><td align="right">'.formatNumber($array['endcred']).'</td></tr>';
		
			// sum it up
			$nstartdeb 		= $nstartdeb + $array['startdeb'];
			$nstartcred 	= $nstartcred + $array['startcred'];
			$ndebit 		= $ndebit + $array['debit'];
			$ncredit		= $ncredit + $array['credit'];
			$nenddeb 		= $nenddeb + $array['enddeb'];
			$nendcred 		= $nendcred + $array['endcred'];
		 }
		$data = $data.'</table>';
		
		$data = $data.'<table border="0" cellpadding="1" cellspacing="0">';
		$data = $data.'<tr><td colspan="2"></td><td bgcolor="#CCCCCC"  align="right"><b>'.formatNumber($nstartdeb).'</b></td><td bgcolor="#CCCCCC"  align="right"><b>'.formatNumber($nstartcred).'</b></td><td bgcolor="#CCCCCC" align="right"><b>'.formatNumber($ndebit).'</b></td><td bgcolor="#CCCCCC" align="right"><b>'.formatNumber($ncredit).'</b></td><td bgcolor="#CCCCCC" align="right"><b>'.formatNumber($nenddeb).'</b></td><td bgcolor="#CCCCCC" align="right"><b>'.formatNumber($nendcred).'</b></td></tr>';
		$data = $data.'</table>';
		
		$data = $data.'</td></tr>';
		 $data = $data.'</table>';
		 $data = $data.'</div>';
		 
		 if(tep_db_num_rows($query_results)<=0){
			$data ="";
			$data = $data.'<table border="0" cellpadding="5" cellspacing="0" width="100%">';
			$data = $data.'<tr><td colspan="2" align="centre">Sorry, there is no information to diplay for this report</td></tr>';
			$data = $data.'</table>';
		 }
		 
		 
		$document->AddPage('L','A4',true);
		
		//$document->writeHTML($data, false, false, false, false, '');	 
			
		break;	
	
	case 'BD':
	
		$lablesarray = getlables("317,264,296,289,464,297,249,373,465,463,38,39,464");
		
		if(STARTFINYEAR==""){
			$data = $data.'<table border="0" cellpadding="2" cellspacing="0">';
			//main heading
			$data = $data.'<tr bgcolor="#EEEEEE"><td >'.$lablesarray['463'].' '.$lablesarray['38'].' '.$_GET['txtFrom'].' to '.$_GET['txtTo'].'</td></tr>';
			$data = $data.'<tr bgcolor="#EEEEEE"><td > '.$lablesarray['38'].' '.$_GET['txtFrom'].' to '.$_GET['txtTo'].'</td></tr>';
			$data = $data.'</table>';
			$data = $data. '<font color="red">Please set the begining of the financial period</font>';	
			break;		
		}
		
	//	generateTransactionsForYear($_GET['txtFrom'] ,$_GET['txtTo']);
		
			
		// Get Transactions for selected accounts
		tep_db_query("DROP TABLE IF EXISTS cAllYears1");
	
		if($fromGL=="" && $toGL=="" ){
			tep_db_query("CREATE TEMPORARY TABLE cAllYears1 AS SELECT gl.*,(SELECT chartofaccounts_name FROM ".TABLE_CHARTOFACCOUNTS." WHERE chartofaccounts_accountcode=gl.chartofaccounts_accountcode) As chartofaccounts_name FROM ".TABLE_GENERALLEDGER." as gl");			
			
		}else{
			tep_db_query("CREATE TEMPORARY TABLE cAllYears1 AS SELECT gl.*,(SELECT chartofaccounts_name FROM ".TABLE_CHARTOFACCOUNTS." WHERE chartofaccounts_accountcode=gl.chartofaccounts_accountcode) As chartofaccounts_name FROM ".TABLE_GENERALLEDGER." AS gl WHERE gl.chartofaccounts_accountcode BETWEEN '".$fromGL."' AND '".$toGL."'  AND chartofaccounts_header='N'" );
		}
		
		
		/*$results_query =tep_db_query("SELECT * FROM cAllYears1");
		while ($transactions = tep_db_fetch_array($results_query)) {			
			print_r($transactions);		
		}*/
		
			
		//get startting Balances
		tep_db_query("CREATE TEMPORARY TABLE cStartBalance AS SELECT chartofaccounts_accountcode, SUM(generalledger_debit) - SUM(generalledger_credit) AS Bal FROM ".TABLE_GENERALLEDGER." WHERE DATE(generalledger_datecreated) < ".changeDateFromPageToMySQLFormat($_GET['txtFrom'])." GROUP BY chartofaccounts_accountcode ");											
			
					
		$glAccounts ="";
				
		$data = $data.'<div style="margin:0;border:1px #EEEEEE;">';
		$data = $data.'<table border="0" cellpadding="2" cellspacing="0">';
		//main heading
		$data = $data.'<tr bgcolor="#EEEEEE"><td colspan="8" nowrap="nowrap">'.$lablesarray['463'].' '.$lablesarray['38'].' '.$_GET['txtFrom'].' '.$lablesarray['39'].' '.$_GET['txtTo'].'</td></tr>';

		$data = $data.'<tr><td colspan="8" nowrap="nowrap">';		
		
		$data = $data.'</td></tr>';
			
		$data = $data.'<tr><td colspan="8" nowrap="nowrap">';
		$data = $data.'<table border="0" cellpadding="2" cellspacing="0">';
		$data = $data.'<tr style="bgcolor:#000000;color:#FFFFFF;" bgcolor="#000000"><td><b>'.$lablesarray['317'].'</b></td><td><b>'.$lablesarray['296'].'</b></td><td >'.$lablesarray['264'].'</td><td align="right">'.$lablesarray['289'].'</td><td align="right">'.$lablesarray['297'].'</td><td align="right">'.$lablesarray['249'].'</td><td align="right">'.$lablesarray['646'].'</td></tr>';
		
		$query_transactions =  tep_db_query("SELECT gl.*,CONCAT(user_firstname,'',user_lastname) As Name FROM cAllYears1 AS gl,".TABLE_USERS."  AS ug  WHERE gl.users_id=ug.user_id AND DATE(generalledger_datecreated) > ".changeDateFromPageToMySQLFormat($_GET['txtFrom'])." ORDER BY chartofaccounts_accountcode,generalledger_datecreated ASC");
			
		
		while ($transactions = tep_db_fetch_array($query_transactions)) {		
				
			$opening_transactions =  tep_db_query("SELECT IF(ISNULL(bal),0,bal) AS Bal FROM cStartBalance WHERE chartofaccounts_accountcode='".$transactions['chartofaccounts_accountcode']."'");
		
			$opening_bal = tep_db_fetch_array($opening_transactions);	
		
			// check if we need to prepare heading for new accounts
			if($glAccounts=="" || $glAccounts!=$transactions['chartofaccounts_accountcode']){
				$data = $data.'<tr><td bgcolor="#E9E9E9" colspan="7" nowrap><b>'.$transactions['chartofaccounts_accountcode'].':'.$transactions['chartofaccounts_name'].'</b></td></tr>';
				$data = $data.'<tr><td bgcolor="#E9E9E9" colspan="6" align="right"><b>'.$lablesarray['373'].'</b></td><td align="right"><b>'.formatNumber($opening_bal['Bal']).'</b></td></tr>';		
			}
			// swicth row colors
			if($rowcolor == ""){
				$rowcolor = "#D5E7FF";
			}else{
				$rowcolor = "";							
			}
			// compute running balances
			if($transactions['generalledger_debit']>0 || $transactions['generalledger_debit']<0){
				$opening_bal['Bal']=$opening_bal['Bal'] + $transactions['generalledger_debit'];
			}else{
				$opening_bal['Bal']= $opening_bal['Bal'] - $transactions['generalledger_credit'];
			}
			
			$data = $data.'<tr><td bgcolor="'.$rowcolor.'"><b>'.$transactions['generalledger_datecreated'].'</b></td><td bgcolor="#E9E9E9"><b>'.$transactions['chartofaccounts_accountcode'].'</b></td><td>'.$transactions['generalledger_description'].'</td><td align="right">'.formatNumber($transactions['generalledger_debit']).'</td><td align="right">'.formatNumber($transactions['generalledger_credit']).'</td><td align="right"><b>'.formatNumber($opening_bal['Bal']).'</b></td><td>'.$transactions['Name'].'</td></tr>';
					
			// check if we need to prepare footer for new accounts
			if($glAccounts=="" || $glAccounts!=$transactions['chartofaccounts_accountcode']){
				$data = $data.'<tr><td bgcolor="#E9E9E9" colspan="6" align="right"><b>'.$lablesarray['465'].'</b></td><td align="right"><b>'.formatNumber($opening_bal['Bal']).'</b></td></tr>';	
			}
					
			$glAccounts = $transactions['chartofaccounts_accountcode'];			
		
		 }
		
		$data = $data.'</table>';
				
		$data = $data.'</td></tr>';
		$data = $data.'</table>';
	 	$data = $data.'</div>';
		 
		if(tep_db_num_rows($query_transactions)<=0){
			$data ="";
			$data = $data.'<table border="0" cellpadding="5" cellspacing="0" width="100%">';
			$data = $data.'<tr><td colspan="2" align="centre">Sorry, there is no information to diplay for this report</td></tr>';
			$data = $data.'</table>';
		}
				 
		$document->AddPage('L','A4',true);
						
		break;	
					
	case 'BS':
		$document->AddPage('L','A4',true);
		if(STARTFINYEAR==""){
			$data = $data.'<table border="0" cellpadding="2" cellspacing="0">';
			//main heading
			$data = $data.'<tr bgcolor="#EEEEEE"><td >Account No</td><td>Account Name</td><td colspan="2" align="centre">Balance as at '.$_GET['txtFrom'].'</td><td colspan="2" align="centre"></td><td colspan="2" align="centre">Balance as at '.$_GET['txtTo'].'</td></tr>';
			$data = $data.'</table>';
			$data = $data. '<font color="red">Please set the begining fo the financial period</font>';	
			break;		
		}
		//======================================
		generateTrialBalance($_GET['txtFrom'],$_GET['txtTo'],'BS');
												
		$query = " select * from cTrialh5";
		
		$query_results =  tep_db_query($query);	
		
		$data = $data.'<div style="margin:0;border:1px #EEEEEE;">';
		$data = $data.'<table border="0" cellpadding="2" cellspacing="0">';
		//main heading
		$data = $data.'<tr bgcolor="#EEEEEE"><td >Account No</td><td>Account Name</td><td colspan="2" align="centre">Balance as at '.$_GET['txtFrom'].'</td><td colspan="2" align="centre"></td><td colspan="2" align="centre">Balance as at '.$_GET['txtTo'].'</td></tr>';

		$data = $data.'<tr><td colspan="8" nowrap="nowrap">';
		// subheading
		$data = $data.'<table border="0" cellpadding="1" cellspacing="0">';
		$data = $data.'<tr><td colspan="2"></td><td bgcolor="#CCCCCC" align="right">Debit</td><td bgcolor="#CCCCCC" align="right">Credit</td><td bgcolor="#CCCCCC" align="right"></td><td bgcolor="#CCCCCC" align="right"></td><td bgcolor="#CCCCCC" align="right">Debit</td><td bgcolor="#CCCCCC"  align="right">Credit</td></tr>';
		$data = $data.'</table>';
		
		$data = $data.'</td></tr>';
			
		$data = $data.'<tr><td colspan="8" nowrap="nowrap">';
		$data = $data.'<table border="0" cellpadding="2" cellspacing="0">';
		
		// nitialise amounts
		$nstartdeb = 0;
		$nstartcred = 0;
		$ndebit = 0;
		$ncredit = 0;
		$nenddeb = 0;
		$endcred = 0;
		$header1 ="";
		while ($array = tep_db_fetch_array($query_results)) {
			
			if($array['header1']!= $header1){
				$data = $data.'<tr><td bgcolor="#CCCCCC" colspan="2" align="centre"><b>'.$array['header1'].'</b></td><td align="right"></td><td align="right"></td><td align="right"></td><td align="right"></td><td align="right"></td><td align="right"></td></tr>';
			}
			
			$data = $data.'<tr><td bgcolor="#E9E9E9">'.$array['account'].'</td><td bgcolor="#E9E9E9">'. $array['label']. '</td><td align="right">'.formatNumber($array['startdeb']).'</td><td align="right">'.formatNumber($array['startcred']).'</td><td align="right"></td><td align="right"></td><td align="right">'.formatNumber($array['enddeb']).'</td><td align="right">'.formatNumber($array['endcred']).'</td></tr>';
		
			// sum it up
			$nstartdeb 		= $nstartdeb + $array['startdeb'];
			$nstartcred 	= $nstartcred + $array['startcred'];
			$ndebit 		= $ndebit + $array['debit'];
			$ncredit		= $ncredit + $array['credit'];
			$nenddeb 		= $nenddeb + $array['enddeb'];
			$nendcred 		= $nendcred + $array['endcred'];
			
			$header1 = $array['header1'];
			
		 }
		 $data = $data.'<tr><td bgcolor="#CCCCCC" colspan="2" align="centre"><b>Profit and Loss</b></td><td align="right"></td><td align="right"></td><td align="right"></td><td align="right"></td><td align="right"></td><td align="right"></td></tr>';
		// $data = $data.'<tr><td bgcolor="#E9E9E9"></td><td bgcolor="#E9E9E9">Profit/ Loss</td><td align="right"></td><td align="right"></td><td align="right"></td><td align="right"></td><td align="right"></td><td align="right"></td></tr>';
		
		// profit and loss
		tep_db_query("DROP TABLE IF EXISTS cProfit");
		
		$query ="CREATE TEMPORARY TABLE cProfit AS SELECT startdeb-startcred AS first, enddeb - endcred AS last FROM cTrials";
		
		tep_db_query($query);
		
		tep_db_query("DROP TABLE IF EXISTS fProfit");
		
		$query ="CREATE TEMPORARY TABLE fProfit AS SELECT SUM(first) as first, SUM(LAST) as last FROM cProfit";
		
		tep_db_query($query);
		
		$query = " select * from fProfit";
		
		$query_results =  tep_db_query($query);	
		
		$array = tep_db_fetch_array($query_results);
		
		$data = $data.'<tr><td bgcolor="#E9E9E9"></td><td bgcolor="#E9E9E9" align="center">Profit/ Loss</td><td  bgcolor="#CCCCCC"></td><td   bgcolor="#CCCCCC" align="right"><b>'.formatNumber($array['first']).'</b></td><td bgcolor="#CCCCCC" align="right"></td><td bgcolor="#CCCCCC" align="right"></td><td bgcolor="#CCCCCC" align="right"></td><td bgcolor="#CCCCCC" align="right"><b>'.formatNumber($array['last']).'</b></td></tr>';
		
		$data = $data.'</table>';
		
														
		$data = $data.'</td></tr>';
		$data = $data.'</table>';
		$data = $data.'</div>';
		
		if(tep_db_num_rows($query_results)<=0){
			$data ="";
			$data = $data.'<table border="0" cellpadding="5" cellspacing="0" width="100%">';
			$data = $data.'<tr><td colspan="2" align="centre">Sorry, there is no information to diplay for this report</td></tr>';
			$data = $data.'</table>';
		 }
		 
		
		
		//$document->writeHTML($data, false, false, false, false, '');	 
			
		break;				

	case 'A':	
	
		$exams_id = $_SESSION['exams_id'];
		$termsdefinition_id = $_SESSION['termsdefinition_id'];
		$classes_id = $_SESSION['classes_id'];
		$subjectsmarks_year = $_SESSION['subjectsmarks_year'];
		$students_level = $_SESSION['level'];
		$students_sregno = replaces_underscores($_SESSION['students_sregno']);
		
	
		$level ="A";
		
		if($termsdefinition_id=="1"){
				$termsdefinition_name = "First term";	
		}elseif($termsdefinition_id=="2"){
				$termsdefinition_name = "Second term";	
		}elseif($termsdefinition_id=="3"){
				$termsdefinition_name = "Third term";
		}
							
		$total_days_query = tep_db_query("SELECT DATEDIFF(terms_ends,terms_begins) AS totaldays FROM ". TABLE_TERMS." WHERE terms_name='".$termsdefinition_name."' AND terms_year='".$subjectsmarks_year."'");
		$total_days_array = tep_db_fetch_array($total_days_query);
		
		$terms_query = tep_db_query("select termsdefinition_name FROM " . TABLE_TERMSDEFINITION." WHERE termsdefinition_id='".$termsdefinition_id."'");
		$terms_array = tep_db_fetch_array($terms_query);
		$termsdefinition_name = $terms_array['termsdefinition_name'];	
		
		if($_SESSION['mode']=='All'){
			$allresultsquery = "SELECT students_gender,p.students_unebindexno,p.students_image,p.students_isborder,sc.classes_id,p.students_house,p.students_gender,(SELECT classes_name FROM ".TABLE_CLASSES." c WHERE c.classes_id=sc.classes_id) AS classes_name,c.classes_name,p.students_sregno, p.students_firstname,p.students_lastname FROM ". TABLE_STUDENTS." as p, ".TABLE_STUDENTCLASSES." AS sc WHERE sc.students_sregno=p.students_sregno AND sc.classes_id = '".$_SESSION['classes_id']."'";							
		}else{
			$allresultsquery = "SELECT students_gender,p.students_unebindexno,p.students_image,p.students_isborder,sc.classes_id,p.students_house,p.students_gender,(SELECT classes_name FROM ".TABLE_CLASSES." c WHERE c.classes_id=sc.classes_id) AS classes_name,p.students_sregno, p.students_firstname,p.students_lastname FROM ". TABLE_STUDENTS." as p , ".TABLE_STUDENTCLASSES." AS sc WHERE sc.students_sregno=p.students_sregno AND p.students_sregno = '".$students_sregno."'";
		}
		
						
		$results = tep_db_query($allresultsquery);
		
		//$data ='<table width="100%" border="1" cellspacing="0" cellpadding="0">'; 
		//$data = $data.'<tr>'; 
		//$data = $data.'<td>';
	
		//$document->startPageGroup();
			
		while($pupils = tep_db_fetch_array($results)){					
			//AddPage($orientation='', $format='', $keepmargins=false, $tocpage=false) 
			
			$document->AddPage('L','A4',true);
		
			//$document->setPageOrientation('L',true,'');	
			
			$students_sregno =	$pupils['students_sregno'];
																					
			$classes_query = tep_db_query("select * FROM " . TABLE_CLASSES." WHERE classes_id='".($classes_id + 1)."'");
			$classes_array = tep_db_fetch_array($classes_query);
			$ispromoted_class = $classes_array['classes_name'];
		
			if( $pupils['students_isborder']=="Y"){
				$fees = $classes_array['classes_borders'];			
			}else{
				$fees = $classes_array['classes_amount'];
			}
			
			//requirements_query2 = tep_db_query("select classes_exams FROM " . TABLE_CLASSES." WHERE classes_id='".$classes_id."'");;
			//$requirement2 = tep_db_fetch_array($requirements_query2);
			//$exam = $requirement2['classes_exams'];	
			
			// set font
			//$document->SetFont('', '', 9);
			$data = $data."<div style='margin:0'>";
			$data = $data.'<table width="100%" border="1" cellspacing="0" cellpadding="2">'; 
			$data = $data.'<tr>';
			$data = $data.'<td  bgcolor="#000000" colspan="2" style="color:#FFFFFF" align="center"><b>STUDENT ACADEMIC TRANSCRIPT</b></td>'; 
			$data = $data.'</tr>';
			$data = $data.'<tr>';	
			$data = $data.'<td colspan="2">';											
				$data = $data.'<table width="100%"  border="0" cellspacing="0" cellpadding="0">';
				$data = $data.'<tr>';						
					$data = $data.'<td><b>Last Name, First Name</b></td>';
					$data = $data.'<td><b>Admission Number</b></td>';
					$data = $data.'<td><b>Class</b></td>';
					$data = $data.'<td><b>Term</b></td>';
					$data = $data.'<td><b>Gender</b></td>';
					$data = $data.'<td><b>Student No.</b></td>';				
				 $data = $data.'</tr>';
				 $data = $data.'<tr>';
					$data = $data.'<td>'.$pupils['students_lastname'].' '.$pupils['students_firstname'].'</td>';
					$data = $data.'<td>'.$pupils['students_sregno'].'</td>';
					$data = $data.'<td>'.$pupils['classes_name'].'</td>';
					$data = $data.'<td>'.$termsdefinition_name.'</td>';
					$data = $data.'<td>'.$pupils['students_gender'].'</td>';
					$data = $data.'<td>'.$pupils['students_unebindexno'].'</td>';	
				  $data = $data.'</tr>';
				$data = $data.'</table>';
			$data = $data.'</td>';
			$data = $data.'</tr>';
			$data = $data.'</table>';
			
			//$document->writeHTML($data,true, false, true, false, '');
			//$document->SetFont('helvetica','', 9);
			
			//begin first exam
			
			$data =$data.'<table width="100%" border="1" cellspacing="0" cellpadding="0" >'; 
			$data = $data.'<tr>';
			$data = $data.'<td  bgcolor="#000000" colspan="3" style="color:#FFFFFF"><b>Student Academic information</b></td>'; 
			$data = $data.'</tr>';
			$data = $data.'<tr>';											
				
				$average_array = array();
				$x=0;
				$total_paper_grades=0;
				$subjects_query = tep_db_query("select subjects_id,subjects_name,subjects_code  from " . TABLE_SUBJECTS." WHERE subjects_level='A' GROUP BY subjects_code ORDER BY subjects_code ASC");
				$subjects_ids_array = array();
					
				$x= 0;
				$total_no_points=0;

				while ($subjects = tep_db_fetch_array($subjects_query)) {								
									
					$subjects_names_array[$subjects['subjects_code']] = $subjects['subjects_name'];							
					$subjects_ids_array[] = $subjects['subjects_code'];
					$average_array[$subjects['subjects_id']] = "";
					$x= $x+1;
					
				}						
	

				$data = $data.'<td  width="100%">';
			
				$data = $data.'<table border="1" cellspacing="0" cellpadding="0">';		
				$data = $data.'<tr bgcolor="#EEEEEE">';
				$data = $data.'<td  align="center" width="15%"><b>Subject</b></td>';
				$data = $data.'<td  align="center" width="15%"><b>Score</b></td>';
				$data = $data.'<td  align="center" width="14%"><b>Class Average</b></td>';
				$data = $data.'<td  align="center" width="33%"><b>Class Range</b></td>';						
				$data = $data.'<td  align="center" width="7%"><b>Weight</b></td>';
				$data = $data.'<td  align="center" width="7%"><b>Points/Units Earned</b></td>';
				$data = $data.'<td  align="center" width="7%"><b>Final Grade</b></td>';
				//$data = $data.'<td width="132"><b>Instructor Evaluational Comment</b></td>';
				
				//$nest1 ='<table cellspacing="0" cellpadding="0"><tr><td>P1</td><td>P2</td><td>P3</td><td>P4</td><td>P5</td><td>P6</td></tr></table>';
				
				$data = $data.'</tr>';	
				$data = $data.'<tr bgcolor="#EEEEEE">';
				$data = $data.'<td  align="center" width="15%"></td>';
				$data = $data.'<td  align="center" width="15%" bgcolor="#000000" style="color:#FFFFFF"><table cellspacing="0" border="1" cellpadding="0"><tr><td>P1</td><td>P2</td><td>P3</td><td>P4</td><td>P5</td><td>P6</td></tr></table></td>';
				$data = $data.'<td  align="center" width="14%" bgcolor="#000000" style="color:#FFFFFF"><table cellspacing="0" border="1" cellpadding="0"><tr><td>P1</td><td>P2</td><td>P3</td><td>P4</td><td>P5</td><td>P6</td></tr></table></td>';
				$data = $data.'<td  align="center" width="33%" bgcolor="#000000" style="color:#FFFFFF"><table cellspacing="0" border="1" cellpadding="0"><tr><td>P1</td><td>P2</td><td>P3</td><td>P4</td><td>P5</td><td>P6</td></tr></table></td>';
				$data = $data.'<td  align="center" width="7%"></td>';
				$data = $data.'<td  align="center" width="7%"></td>';
				$data = $data.'<td  align="center" width="7%"></td>';			
				//$data = $data.'<td width="132"><b>Instructor Evaluational Comment</b></td>';
				$data = $data.'</tr>';										
				$data = $data.'</table>';	
				
			$data = $data.'<table cellspacing="0" cellpadding="0" border="1">';
			//array_shift($subjects_ids_array);
			$gpoints = 0;
			//print_r($subjects_ids_array);
			
				//print_r($subjects_names_array);			
			$subjects_ids_array = array_reverse($subjects_ids_array);
			//print_r($subjects_ids_array);
			array_pop($subjects_ids_array);
			
			foreach($subjects_ids_array as $code){
				
												
				$divisionacategories_query = tep_db_query("SELECT divisioncategories_id FROM " . TABLE_SUBJECTSMARKS." AS sm LEFT JOIN ".TABLE_SUBJECTS." AS s ON sm.subjects_id=s.subjects_id  WHERE sm.subjectsmarks_year='".$subjectsmarks_year."' AND sm.exams_id='".$exams_id."' AND sm.classes_id='".$classes_id."' AND sm.subjectsmarks_level='A' AND s.subjects_code='".$code."' AND sm.students_sregno='".$students_sregno."' AND sm.termsdefinition_id='".$termsdefinition_id."' GROUP BY divisioncategories_id");
				//echo "SELECT divisioncategories_id FROM " . TABLE_SUBJECTSMARKS." AS sm LEFT JOIN ".TABLE_SUBJECTS." AS s ON sm.subjects_id=s.subjects_id  WHERE sm.subjectsmarks_year='".$subjectsmarks_year."' AND sm.exams_id='".$exams_id."' AND sm.classes_id='".$classes_id."' AND sm.subjectsmarks_level='A' AND s.subjects_code='".$code."' AND sm.students_sregno='".$students_sregno."' AND sm.termsdefinition_id='".$termsdefinition_id."' GROUP BY divisioncategories_id<br><br>";
							
				$divisionacategories_array = tep_db_fetch_array($divisionacategories_query);
				
				if($divisionacategories_array['divisioncategories_id']!=""){						
					$divisioncategories_id = $divisionacategories_array['divisioncategories_id'];
				}
				
				 // now get the marks
				$subjects_marks_range_results = tep_db_query("SELECT s.subjects_name,s.subjects_code,s.subjects_acron,sm.subjects_id,subjectsmarks_value,students_sregno,sd.subjectsettings_papers as nopapers FROM " . TABLE_SUBJECTSMARKS." AS sm LEFT JOIN ".TABLE_SUBJECTS." AS s ON sm.subjects_id=s.subjects_id LEFT JOIN ".TABLE_SUBJECTSETTINGS." as sd ON sd.subjects_code=s.subjects_code WHERE sm.subjectsmarks_year='".$subjectsmarks_year."' AND sm.exams_id='".$exams_id."' AND sm.classes_id='".$classes_id."' AND sm.subjectsmarks_level='A' AND s.subjects_code='".$code."' AND sm.termsdefinition_id='".$termsdefinition_id."' ORDER BY sm.subjectsmarks_value,sm.subjects_id ASC");
				
				$ncounter = array();
				$totmarks = array();
				$acrons = array();
				$submax = array();
				$submin = array();
				$averages = '<table cellspacing="0" border="1" cellpadding="2" with="90%"><tr><td>X1</td><td>X2</td><td>X3</td><td>X4</td><td>X5</td><td>X6</td></tr></table>';
				$ranges = '<table cellspacing="0" border="1" cellpadding="2" with="90%"><tr><td align="center">X1</td><td align="center">X2</td><td align="center">X3</td><td align="center">X4</td><td align="center">X5</td><td align="center">X6</td></tr></table>';
				
				while($range = tep_db_fetch_array($subjects_marks_range_results)){						
													
					
					// get maximum mark
					if ($range['subjectsmarks_value'] > $submax[$range['subjects_id']]){
						$submax[$range['subjects_id']]= $range['subjectsmarks_value'];
					}
					
					// get minimum mark
					if ($range['subjectsmarks_value'] < $submin[$range['subjects_id']]){
						$submin[$range['subjects_id']]= $range['subjectsmarks_value'];									
					}else{
						$submin[$range['subjects_id']]= $range['subjectsmarks_value'];	
					}
					
					$ncounter[$range['subjects_id']] = $ncounter[$range['subjects_id']]+ 1;
					$acrons[$range['subjects_id']]   = substr($range['subjects_acron'],strlen($range['subjects_acron'])-1,strlen($range['subjects_acron']));
					$totmarks[$range['subjects_id']] =  $totmarks[$range['subjects_id']] + $range['subjectsmarks_value'];														
				}
				
				foreach ($ncounter as $key => $val) {
			
					$averages = str_replace('X'.$acrons[$key],evaluateGrade((int)$totmarks[$key]/$val,'A',$key,$marks['divisioncategories_id']).'<sub>'.(int)$totmarks[$key]/$val.'</sub>',$averages);
					$ranges = str_replace('X'.$acrons[$key],evaluateGrade((int)$submin[$key],'A',$key,$marks['divisioncategories_id']).'<sub>'.$submin[$key].'</sub>-'.evaluateGrade((int)$submax[$key],'A',$key,$marks['divisioncategories_id']).'<sub>'.$submax[$key].'</sub>',$ranges);												
				}			
				
				$averages = preg_replace('(X1|X2|X3|X4|X5|X6)','',$averages);	
				$ranges = preg_replace('(X1|X2|X3|X4|X5|X6)','',$ranges);	
				
				
				$subjects_marks_query = tep_db_query("SELECT subjects_name,subjects_code,subjects_code,sm.subjectsmarks_value,sm.subjects_id,divisioncategories_id FROM " . TABLE_SUBJECTSMARKS." AS sm LEFT JOIN ".TABLE_SUBJECTS." AS s ON sm.subjects_id=s.subjects_id  WHERE sm.subjectsmarks_year='".$subjectsmarks_year."' AND sm.exams_id='".$exams_id."' AND sm.classes_id='".$classes_id."' AND sm.subjectsmarks_level='A' AND s.subjects_code='".$code."' AND sm.students_sregno='".$students_sregno."' AND sm.termsdefinition_id='".$termsdefinition_id."' ORDER BY subjects_acron,sm.subjectsmarks_value");
				
				
				//echo "SELECT subjects_code,subjects_code,sm.subjectsmarks_value,sm.subjects_id,divisioncategories_id FROM " . TABLE_SUBJECTSMARKS." AS sm LEFT JOIN ".TABLE_SUBJECTS." AS s ON sm.subjects_id=s.subjects_id  WHERE sm.subjectsmarks_year='".$subjectsmarks_year."' AND sm.exams_id='".$exams_id."' AND sm.classes_id='".$classes_id."' AND sm.subjectsmarks_level='A' AND s.subjects_code='".$code."' AND sm.students_sregno='".$students_sregno."' AND sm.termsdefinition_id='".$termsdefinition_id."' ORDER BY sm.subjectsmarks_value,s.subjects_code";

											
				if(!tep_db_num_rows($subjects_marks_query)){
					
					$data = $data.'<tr >';		
					$data = $data.'<td width="15%"><b>'.$subjects_names_array[$code].'</b></td>';	
					$data = $data.'<td width="15%"></td>';	
					//$data = $data.'<tr >';
					$data = $data.'<td width="14%"></td>';
					$data = $data.'<td width="33%"></td>';
					$data = $data.'<td width="7%">&nbsp;</td>';
					$data = $data.'<td width="7%">&nbsp;</td>';
					$data = $data.'<td width="7%">&nbsp;</td>';
				//	$data = $data.'<td width="132">&nbsp;</td>';
					$data = $data.'</tr>';
								
				}else{
																			
					$i=1;
					$total_paper_grades = 0;							
					$credits_string = '';
					$credits = '<table cellspacing="0" border="1" cellpadding="2" ><tr><td>X1</td><td>X2</td><td>X3</td><td>X4</td><td>X5</td><td>X6</td></tr></table>';
					$no_of_subjects_done=0;
					$points=0;			
					$total_no_points = 0;
					
					$nsub = 1;
					while($marks = tep_db_fetch_array($subjects_marks_query)){				
						$subjects_code = $marks['subjects_code'];
						$value = $value + (int)$marks['subjectsmarks_value'];
						$total_paper_grades = $total_paper_grades + (int)substr(evaluateGrade((int)$marks['subjectsmarks_value'],'A',$marks['subjects_id'],$divisioncategories_id),1,2);
															
						$credits = str_replace('X'.$acrons[$marks['subjects_id']],'<b>'.evaluateGrade((int)$marks['subjectsmarks_value'],'A',$marks['subjects_id'],$divisioncategories_id).'</b><sub>'.$marks['subjectsmarks_value'].'</sub>',$credits);
						
						//$credits_string = 
						
						$credits_string.=(int)substr(evaluateGrade((int)$marks['subjectsmarks_value'],'A',$marks['subjects_id'],$marks['divisioncategories_id']),1,2)."</sub>";				
						
						$i++; 						
															
					 }
					
				
					 // remove the Xes that have not been filled
					$credits = preg_replace('(X1|X2|X3|X4|X5|X6)','',$credits);		
										 
											
					 // review  credit string to incoude papaer numbers/codes for proper identification
					$data = $data.'<tr>';
					$data = $data.'<td width="15%"><b>'.$subjects_names_array[$code].'</b></td>';
					$data = $data.'<td align="center" nowrap="nowrap" width="15%">'.$credits.'</td>';
					$data = $data.'<td align="center" nowrap="nowrap" width="14%">'.$averages.'</td>';
					//sort($range);	
					$data = $data.'<td nowrap="nowrap" width="33%"><font size="8">'.$ranges.'</font></td>';		
													
					 $no_of_subjects_done = $i-1;												
																			
					// check see how many subjects are for this paper
					$subjects_papers_query = tep_db_query("SELECT subjectsettings_instavg,subjectsettings_papers as no,subjectcategories_weight FROM " .TABLE_SUBJECTSETTINGS." AS sd,".TABLE_SUBJECTCATEGORIES." AS sc WHERE sd.subjectcategories_id=sc.subjectcategories_id AND subjects_code='".$code."'");
					
					//echo "SELECT subjectsettings_instavg,subjectsettings_papers as no,subjectcategories_weight FROM " .TABLE_SUBJECTSETTINGS." AS sd,".TABLE_SUBJECTCATEGORIES." AS sc WHERE sd.subjectcategories_id=sc.subjectcategories_id AND subjects_code='".$code."'";
					$no_of_papers = tep_db_fetch_array($subjects_papers_query);
					
					
					if((int)$no_of_subjects_done == (int)$no_of_papers['no']){																
					
						$average_paper_grades = $total_paper_grades / $no_of_papers['no'];
						
						$points = getNumberofPoints($credits_string,$average_paper_grades,$no_of_papers['no'],$subjects_code,$divisioncategories_id);									
					}else{
						$points = 0;						
					}
				
								
					$total_no_points = $total_no_points + $points;	
												
					$gpoints = $gpoints + $total_no_points;								
					
					$data = $data.'<td align="center" width="7%" >'.$total_no_points*(int)$no_of_papers['subjectcategories_weight'].'</td>';
					$data = $data.'<td align="center" style="font-weight:bold;" width="7%">'.$total_no_points.'</td>';	
					$data = $data.'<td align="center" style="color:red;font-weight:bold;" width="7%">'.getGradeFromPoints($credits_string,(int)($average_paper_grades),$no_of_papers['no'],$points,$divisioncategories_id).'</td>';
					$data = $data.'</tr>';
					
					
				}							
				
				
			}
			
			
			
			$data = $data.'<tr width="100%">';
			$data = $data.'<td align="center" width="15%"></td>';
			$data = $data.'<td width="15%">&nbsp;</td>';
			$data = $data.'<td width="14%">&nbsp;</td>';
			$data = $data.'<td width="33%">&nbsp;</td>';
			$data = $data.'<td width="7%">Total&nbsp;</td>';
			$data = $data.'<td align="center" width="7%"><b>'.$gpoints.'</b></td>';
			$data = $data.'<td width="7%"></td>';					
			$data = $data.'</tr>';									
			$data = $data.'</table>';
			
			//$document->writeHTML($data, true, false, true, false, '');
			//$document->SetFont('helvetica','', 8);
									
			$data = $data.'</td>';
			$data = $data.'</tr>';
			$data = $data.'</table>';
			$data = $data.'<table border="0" cellspacing="0" cellpadding="2">';
			$data = $data.'<tr width="100%">';
			$data = $data.'<td><b>Credits and Grading Scale</b></td>';						
			$data = $data.'<td></td>';					
			$data = $data.'</tr>';						
			$data = $data.'<tr>';
			$data = $data.'<td>';
			
			$division_query = tep_db_query("SELECT divisionA_points,divisionA_name FROM " . TABLE_DIVISIONA."  WHERE divisioncategories_id ='".$divisioncategories_id."' ORDER BY divisionA_name ASC");
			
			while($division = tep_db_fetch_array($division_query)){						
				$data = $data.$division['divisionA_name'].": ".$division['divisionA_points']." Pts<br>";									
			}
			
			$data = $data.'</td>';					
			$data = $data.'<td></td>';					
			$data = $data.'</tr>';
			$data = $data.'<tr >';
			$data = $data.'<td></td>';						
			$data = $data.'<td></td>';					
			$data = $data.'</tr>';
			$data = $data.'<tr>';
			$data = $data.'<td>';
			
			$division_query = tep_db_query("SELECT gradesdefinition_name,gradesdefinition_acron FROM " .TABLE_GRADESDEFINITION."  ORDER BY gradesdefinition_id DESC");
			
			$i = 0;
			$data = $data.'<table border="0" cellspacing="0" cellpadding="0">';
			$data = $data.'<tr><td>';
			while($grades = tep_db_fetch_array($division_query)){		
											
				$data = $data."<b>".$grades['gradesdefinition_acron']."</b>: ".$grades['gradesdefinition_name']." ";								
				$i++;									
			}
			$data = $data.'</td></tr>';	
			$data = $data.'</table>';
			$data = $data.'</td>';					
			$data = $data.'<td></td>';					
			$data = $data.'</tr>';
			$data = $data.'</table>';
			$data = $data.'</div>';
			//echo $document->getNumPages();
			//exit();
			
			$document->writeHTML($data, false, false, false, false, '');
			
			// Please make sure you set this to empty string, else writeHTML will print duplicated data in the pdf
			$data = "";
			
		}						
		break;
		
	
		
		case 'REPORTCARDS':	
			
			if($_GET['mode']==""){				
					
					$examdefinition_id = substr($_GET['id'],1,strlen($_GET['id']));	
				
					$examdefinition_query = tep_db_query("SELECT * FROM ". TABLE_EXAMDEFINITION." WHERE examdefinition_id='".$examdefinition_id."'");
					$examdefinition_array = tep_db_fetch_array($examdefinition_query);
					$exams_id = $examdefinition_array['exams_id'];
					$termsdefinition_id = $examdefinition_array['termsdefinition_id'];
					$classes_id = $examdefinition_array['classes_id'];
					$subjectsmarks_year = $examdefinition_array['subjectsmarks_year'];
					$students_level = $examdefinition_array['examdefinition_level'];
					$students_sregno = $examdefinition_array['students_sregno'];
					$divisioncategories_id	= $examdefinition_array['divisioncategories_id'];
							
					$level = $students_level;
					
					$terms_query = tep_db_query("select termsdefinition_name FROM " . TABLE_TERMSDEFINITION." WHERE termsdefinition_id='".$termsdefinition_id."'");
					
					$terms_array = tep_db_fetch_array($terms_query);
					
					$termsdefinition_name = $terms_array['termsdefinition_name'];
				
					if($termsdefinition_id=="1"){
					
						$lablearray = getlables("781");				
						$termsdefinition_name =$lablearray["781"];
					
					}elseif($termsdefinition_id=="2"){
					
						$lablearray = getlables("782");
						$termsdefinition_name =$lablearray["782"];	
						
					}elseif($termsdefinition_id=="3"){
					
						$lablearray = $getlables["783"];
						$termsdefinition_name = $lablearray["783"];					
					}
					
				$termsdefinition_id = $_GET['termsdefinition_id'];
					
			}else{			
				$students_level = $_GET['level'];
				$examdefinition_year	= $_GET['examdefinition_year'];		
			}
			
		
			
		
			
		if($_GET['mode']=='ALL'){
			//$level = substr($_GET['id'],0,1);
			$level = $_GET['level'];
			$allresultsquery = "SELECT * FROM ". TABLE_EXAMDEFINITION." WHERE classes_id = '".$_GET['classes_id']."' AND examdefinition_level='".$_GET['level']."' AND examdefinition_year='".$_GET['examdefinition_year']."' AND exams_id='".$_GET['exams_id']."' AND termsdefinition_id='".$_GET['termsdefinition_id']."'";							
		
		//	echo $allresultsquery;
			//exit();
		}else{
			$id = substr($_GET['id'],1,strlen($_GET['id']));
			$level = substr($_GET['id'],0,1);
			$allresultsquery = "SELECT * FROM ". TABLE_EXAMDEFINITION." WHERE examdefinition_id = '".$id."'";									
		
		
		}
		
		
		$results = 	 tep_db_query($allresultsquery);
		// Primary Report		
		if($level=='P'){
			
						
						$document->SetFont('Arial','', 8);
					
						while($examdefinition_array = tep_db_fetch_array($results)){							
							
								// get examination details
							if($_GET['mode']=="ALL"){
							
							$students_level = $_GET['level'];
							
							$classes_d = $_GET['classes_d'];			
				
							$exams_id = $_GET['exams_id'];
																
							//$examdefinition_query = tep_db_query("SELECT * FROM ". TABLE_EXAMDEFINITION." WHERE exams_id='".$_GET['exams_id']."' AND termsdefinition_id='".$_GET['termsdefinition_id']."' AND classes_id='".$_GET['classes_id']."' AND examdefinition_year ='".$_GET['examdefinition_year']."' AND students_sregno='".$pupils['students_sregno']."'");
						//	$examdefinition_array = tep_db_fetch_array($examdefinition_query);
							
							
						//	if($examdefinition_array['examdefinition_id']==""){
							//	continue;
							//}
							
							$examdefinition_id = $examdefinition_array['examdefinition_id'];
							$exams_id = $_GET['exams_id'];
							$termsdefinition_id = $examdefinition_array['termsdefinition_id'];
							$classes_id = $examdefinition_array['classes_id'];
							$subjectsmarks_year = $examdefinition_array['subjectsmarks_year'];
							$students_level = $examdefinition_array['examdefinition_level'];
									
							$level = $students_level;
							
							$terms_query = tep_db_query("select termsdefinition_name FROM " . TABLE_TERMSDEFINITION." WHERE termsdefinition_id='".$termsdefinition_id."'");
							
							$terms_array = tep_db_fetch_array($terms_query);
							
							$termsdefinition_name = $terms_array['termsdefinition_name'];
						
							if($termsdefinition_id=="1"){	
												
								$lablearray = getlables("781");						
								$termsdefinition_name =$lablearray["781"];
							
							}elseif($termsdefinition_id=="2"){
								
								$lablearray = getlables("782");
								$termsdefinition_name =$lablearray["782"];	
								
							}elseif($termsdefinition_id=="3"){
							
								$lablearray = $getlables["783"];
								$termsdefinition_name = $lablearray["783"];
								
							}							
									
						}else{	
								
							$students_level = $_GET['level'];			
						}
							
							$document->AddPage('P','A4',true);	
							
							$allresultsquery = "SELECT students_gender,p.students_unebindexno,p.students_image,p.students_isborder,sc.classes_id,p.students_house,p.students_gender,c.classes_name,p.students_sregno, p.students_firstname,p.students_lastname FROM ". TABLE_STUDENTS." as p  INNER JOIN ".TABLE_STUDENTCLASSES." AS sc ON sc.students_sregno=p.students_sregno LEFT JOIN ".TABLE_CLASSES." as c on c.classes_id=sc.classes_id WHERE p.students_sregno = '".$examdefinition_array['students_sregno']."' group by p.students_sregno";
							$results2 = tep_db_query($allresultsquery);
							$pupils = tep_db_fetch_array($results2);
											
													
							$students_sregno =	$pupils['students_sregno'];
																									
							$classes_query = tep_db_query("select * FROM " . TABLE_CLASSES." WHERE classes_id='".($classes_id + 1)."'");
							$classes_array = tep_db_fetch_array($classes_query);
							$ispromoted_class = $classes_array['classes_name'];
						
							if( $pupils['students_isborder']=="Y"){
								$fees = $classes_array['classes_borders'];			
							}else{
								$fees = $classes_array['classes_amount'];
							}
							
							
							
							$lablearray = getlables("805,812,790,236,789,9,189,194,784,191,785,148,786,787,788,801,802,803,804,805,807,195,808,322,199");
				
							$data = $data."<div style='margin:0px;'>";
							$data = $data.'<table width="100%" border="0.1em" cellspacing="0" cellpadding="5">'; 
							$data = $data.'<tr>';
							$data = $data.'<td  bgcolor="#000000" colspan="2" style="font-size:1.3em;color:#FFFFFF" align="center">'.$lablearray['789'].'</td>'; 
							$data = $data.'</tr>';
							$data = $data.'<tr>';	
							$data = $data.'<td colspan="2">';											
								$data = $data.'<table width="100%"  border="0.1" cellspacing="0" cellpadding="2">';
								$data = $data.'<tr bgcolor="#EEEEEE" style="font-size:1em;">';						
									$data = $data.'<td>'.strtoupper($lablearray['9']).'<br><b>'.strtoupper($pupils['students_lastname'].' '.$pupils['students_firstname']).'</b></td>';
									$data = $data.'<td>'.strtoupper($lablearray['189']).'<br><b>'.strtoupper($pupils['students_sregno']).'</b></td>';
									$data = $data.'<td>'.strtoupper($lablearray['194']).'<br><b>'.strtoupper($pupils['classes_name']).'</b></td>';
									$data = $data.'<td>'.strtoupper($lablearray['784']).'<br><b>'.strtoupper($termsdefinition_name).'</b></td>';
									$data = $data.'<td>'.strtoupper($lablearray['199']).'<br><b>'.strtoupper($pupils['students_gender']).'</b></td>';
									$data = $data.'<td>'.strtoupper($lablearray['191']).'<br><b>'.strtoupper($pupils['students_unebindexno']).'</b></td>';				
								 $data = $data.'</tr>';
								 $data = $data.'<tr bgcolor="#EEEEEE">';
									$data = $data.'<td>'.strtoupper($lablearray['236']).'<br><b>'.$pupils['classcategories_name'].'</b></td>';
									$data = $data.'<td></td>';
									$data = $data.'<td>'.strtoupper($lablearray['808']).'<br><b>'.$pupils['students_isborder'].'</b></td>';
									$data = $data.'<td>'.strtoupper($lablearray['322']).'<br><b>'.$pupils['branchcode'].'</b></td>';
									$data = $data.'<td>'.strtoupper($lablearray['195']).'<br><b>'.$pupils['students_house'].'</b></td>';
									$data = $data.'<td>'.strtoupper($lablearray['807']).'<br><b>'.date('d-m-Y').'</b></td>';	
								  $data = $data.'</tr>';
								$data = $data.'</table>';
							$data = $data.'</td>';
							$data = $data.'</tr>';
							$data = $data.'</table>';						
			
							$data =$data.'<table width="100%" border="0.1em" cellspacing="0" cellpadding="2" >'; 
							$data = $data.'<tr>';
							$data = $data.'<td  bgcolor="#000000" colspan="3" style="color:#FFFFFF">'.$lablearray['785'].'</td>'; 
							$data = $data.'</tr>';
							$data = $data.'<tr>';											
							
							$average_array = array();
							
							$x=0;
							
							$total_paper_grades=0;
							
							
							$subjects_query = tep_db_query("select subjects_id,subjects_name,subjects_code,subjects_acron  from " . TABLE_SUBJECTS." WHERE subjects_level='".$level."' GROUP BY subjects_id");
			
							$subjects_ids_array = array();
							$x= 0;
							$total_no_points=0;
			
							while ($subjects = tep_db_fetch_array($subjects_query)) {								
												
								$subjects_names_array[$subjects['subjects_id']] = $subjects['subjects_name'];							
								$subjects_ids_array[] = $subjects['subjects_id'];
								$average_array[$subjects['subjects_id']] = "";
								//$subjects_codes_array[$subjects['subjects_id']] = $subjects['subjects_name'];	
								$x= $x+1;
								
							}						
							
							
						
							// get position
							$position_query = tep_db_query("SELECT ep.exampositions_position,ed.examdefinition_remarks FROM ". TABLE_EXAMPOSITIONS." ep,".TABLE_EXAMDEFINITION." ed WHERE  ed.examdefinition_id=ep.examdefinition_id and ed.examdefinition_id='".$examdefinition_id."'");
							$position_array = tep_db_fetch_array($position_query);
							$data = $data.'<td  width="100%">';
						
							$data = $data.'<table cellspacing="0" cellpadding="5" border="0.1em">';		
							
							$data = $data.'<tr bgcolor="#EEEEEE">';
							$data = $data.'<td  align="center" width="40%"></td>';
							$data = $data.'<td  align="center" width="15%" ></td>';
							$data = $data.'<td  align="center" width="15%"></td>';
							$data = $data.'<td  align="center" width="15%"></td>';
							$data = $data.'<td  align="center" width="15%" align="right">'.strtoupper($lablearray['790']).': <b>'.$position_array['exampositions_position'].'</b></td>';
							//$data = $data.'<td  align="center" width="7%"></td>';
						//	$data = $data.'<td  align="center" width="7%"></td>';
						//	$data = $data.'<td  align="center" width="7%"></td>';			
							$data = $data.'</tr>';	
							$data = $data.'<tr bgcolor="#EEEEEE">';
							$data = $data.'<td  align="center" width="40%">'.strtoupper($lablearray['148']).'</td>';
							$data = $data.'<td  align="center" width="15%">'.strtoupper($lablearray['786']).'</td>';
							$data = $data.'<td  align="center" width="15%">'.strtoupper($lablearray['787']).'</td>';
							$data = $data.'<td  align="center" width="15%">'.strtoupper($lablearray['812']).'</td>';
							$data = $data.'<td  align="center" width="15%">'.strtoupper($lablearray['788']).'</td>';						
							//$data = $data.'<td  align="center" width="7%"><b>Weight</b></td>';
						//	$data = $data.'<td  align="center" width="7%"><b>Points/Units Earned</b></td>';
							//$data = $data.'<td  align="center" width="7%"><b>Credits</b></td>';
							
							$data = $data.'</tr>';										
							$data = $data.'</table>';	
							
							$data = $data.'<table cellspacing="0" cellpadding="2" border="0.1em">';
							
							$gpoints = 0;
							
							//print_r($subjects_ids_array);
							//exit();
							
							$subjects_ids_array = array_reverse($subjects_ids_array);
							
							//array_pop($subjects_ids_array); we do not need to remove last element form this array
							
							
							$credits_array = array();
															
							//$divisionacategories_query = tep_db_query("SELECT divisioncategories_id FROM " . TABLE_SUBJECTSMARKS." AS sm LEFT JOIN ".TABLE_SUBJECTS." AS s ON sm.subjects_id=s.subjects_id  WHERE sm.subjectsmarks_year='".$subjectsmarks_year."' AND sm.exams_id='".$exams_id."' AND sm.classes_id='".$classes_id."' AND sm.subjectsmarks_level='".$level."' AND sm.students_sregno='".$students_sregno."' AND sm.termsdefinition_id='".$termsdefinition_id."' GROUP BY divisioncategories_id");
																														
						//	$divisionacategories_array = tep_db_fetch_array($divisionacategories_query);
								
							//$divisioncategories_id = $divisionacategories_array['divisioncategories_id'];		
						
						$aggr_sum =0;
						$total_score =0;
						foreach($subjects_ids_array as $code){
							
							 // now get the marks
							$subjects_marks_range_results = tep_db_query("SELECT s.subjects_name,s.subjects_code,s.subjects_acron,sm.subjects_id,subjectsmarks_value,sd.subjectsettings_papers as nopapers FROM " . TABLE_SUBJECTSMARKS." AS sm LEFT JOIN ".TABLE_SUBJECTS." AS s ON sm.subjects_id=s.subjects_id LEFT JOIN ".TABLE_SUBJECTSETTINGS." as sd ON sd.subjects_id=s.subjects_id WHERE  s.subjects_id='".$code."' AND sm.examdefinition_id='".$examdefinition_id."' ORDER BY sm.subjectsmarks_value,sm.subjects_id ASC");
							
							$ncounter = array();
							$totmarks = array();
							$acrons = array();
							$submax = array();
							$submin = array();
							
							$averages = ' ';
							$ranges = ' ';
							
							while($range = tep_db_fetch_array($subjects_marks_range_results)){																						
								
								// get maximum mark
								if ($range['subjectsmarks_value'] > $submax[$range['subjects_id']]){
									$submax[$range['subjects_id']]= $range['subjectsmarks_value'];
								}
							
								
								// get minimum mark
								if ($range['subjectsmarks_value'] < $submin[$range['subjects_id']]){
									$submin[$range['subjects_id']]= $range['subjectsmarks_value'];									
								}else{
									$submin[$range['subjects_id']]= $range['subjectsmarks_value'];	
								}
								
								$ncounter[$range['subjects_id']] = $ncounter[$range['subjects_id']]+ 1;
								//$acrons[$range['subjects_id']]   = $range['subjects_acron'];
							
								$totmarks[$range['subjects_id']] =  $totmarks[$range['subjects_id']] + $range['subjectsmarks_value'];														
							}
							
							
						
							foreach ($ncounter as $key => $val){
							
								$averages = str_replace(' ',(int)$totmarks[$key]/$val.'<sub>'.evaluateGrade((int)$totmarks[$key]/$val,'P',$key,$divisioncategories_id).'</sub>',$averages);
								$ranges = str_replace(' ',$submin[$key].'<sub>'.evaluateGrade((int)$submin[$key],'P',$key,$divisioncategories_id).'</sub>'.'-'.$submax[$key].'<sub>'.evaluateGrade((int)$submax[$key],'P',$key,$divisioncategories_id).'</sub>',$ranges);
														
							}			
							
										
							$subjects_marks_query = tep_db_query("SELECT subjects_name,subjects_code,subjects_acron,sm.subjectsmarks_value,sm.subjects_id FROM " . TABLE_SUBJECTSMARKS." AS sm LEFT JOIN ".TABLE_SUBJECTS." AS s ON sm.subjects_id=s.subjects_id  WHERE  s.subjects_id='".$code."' AND sm.examdefinition_id='".$examdefinition_id."' ORDER BY subjects_acron,sm.subjectsmarks_value");
															
							
							if(!tep_db_num_rows($subjects_marks_query)){
								
								$data = $data.'<tr >';		
								$data = $data.'<td width="40%">'.strtoupper($subjects_names_array[$code]).'</td>';	
								$data = $data.'<td width="15%"></td>';	
								$data = $data.'<td width="15%"></td>';
								$data = $data.'<td width="15%"></td>';
								$data = $data.'<td width="15%"></td>';
							//	$data = $data.'<td width="7%">&nbsp;</td>';
							//	$data = $data.'<td width="7%">&nbsp;</td>';			
							//$data = $data.'<td width="7%">&nbsp;</td>';					
								$data = $data.'</tr>';
								$test=array();			
							}else{
																						
								$i=1;
								$total_paper_grades = 0;							
								$credits_string = '';
								$credits = ' ';
								$no_of_subjects_done=0;
								$points=0;			
								$total_no_points = 0;
								$credits_num  = 0;
								$nsub = 1;
								
								while($marks = tep_db_fetch_array($subjects_marks_query)){				
									$subjects_code = $marks['subjects_acron'];
									$subjects_code_array[$marks['subjects_acron']] = evaluateGrade((int)$marks['subjectsmarks_value'],'P',$marks['subjects_id'],$divisioncategories_id);
									$value = $value + (int)$marks['subjectsmarks_value'];
									$total_paper_grades = $total_paper_grades + (int)substr(evaluateGrade((int)$marks['subjectsmarks_value'],'P', $marks['subjects_id'],$divisioncategories_id),1,2);	
									$credits = str_replace(' ','<b>'.$marks['subjectsmarks_value'].'</b><sub>'.evaluateGrade((int)$marks['subjectsmarks_value'],'P',$marks['subjects_id'],$divisioncategories_id).'</sub>',$credits);
									$credits_num = substr(evaluateGrade((int)$marks['subjectsmarks_value'],'P',$marks['subjects_id'],$divisioncategories_id),1,2);																											
									$credits_string.=(int)substr(evaluateGrade((int)$marks['subjectsmarks_value'],'P',$marks['divisioncategories_id']),1,2)."</sub>";														
									
									$credits_array[$marks['subjects_acron']] = $credits_num;
									$i++;
									
									$aggr =	substr(evaluateGrade((int)$marks['subjectsmarks_value'],'P',$marks['subjects_id'],$divisioncategories_id),1,1);
									$aggr_sum = $aggr_sum +	substr(evaluateGrade((int)$marks['subjectsmarks_value'],'P',$marks['subjects_id'],$divisioncategories_id),1,1);								
									if($marks['subjects_acron']=='MATH' || $marks['subjects_acron']=='ENG'|| $marks['subjects_acron']=='SST'|| $marks['subjects_acron']=='SCIE'){
										$total_score = $total_score + $marks['subjectsmarks_value'];
									}
								 }
								//echo $marks['subjectsmarks_value'];
								//print_r($subjects_code_array);
								//exit();
								
								 // review  credit string to incoude papaer numbers/codes for proper identification
								$data = $data.'<tr>';
								$data = $data.'<td width="40%"><b>'.strtoupper($subjects_names_array[$code]).'</b></td>';
								$data = $data.'<td align="center" nowrap="nowrap" width="15%"><b>'.$credits.'</b></td>';
								$data = $data.'<td align="center" nowrap="nowrap" width="15%"><b>'.$averages.'</b></td>';
								$data = $data.'<td align="center" nowrap="nowrap" width="15%"><b>'.$aggr.'</b></td>';
								$data = $data.'<td nowrap="nowrap" width="15%" align="center"><b>'.$ranges.'</b></td>';		
															
								 $no_of_subjects_done = $i-1;
								
							//	$data = $data.'<td align="center" width="7%" ></td>';
							//	$data = $data.'<td align="center" style="font-weight:bold;" width="7%"></td>';	
							//	$data = $data.'<td align="center" style="color:#000099;font-weight:bold;" width="7%">'.$credits_num.'</td>';
								$data = $data.'</tr>';
								
							//	$credits_array[] = $credits_num;
								
							}							
							
							
						}
							
						
						$query_results = tep_db_query("SELECT gradesettings_numofsubjectsbestdone  FROM ".TABLE_GRADESETTINGS." WHERE divisioncategories_id='".$divisioncategories_id."'");
						$gradesettings = tep_db_fetch_array($query_results);
						
						$numofsubs =(int)$gradesettings['gradesettings_numofsubjectsbestdone'];
					
						$issentialsubs_array =array();
						
						$query_results = tep_db_query("SELECT s.subjects_id  FROM ".TABLE_SUBJECTSETTINGS." AS ss ,".TABLE_SUBJECTCATEGORIES." as sc, ".TABLE_SUBJECTS." AS s WHERE s.subjects_id=ss.subjects_id AND ss.subjectcategories_id=sc.subjectcategories_id  AND ss.divisioncategories_id='".$divisioncategories_id."' AND sc.subjectcategories_acron='REQ'");
						while($gradesettings = tep_db_fetch_array($query_results)){
							$issentialsubs_array[] = $gradesettings['subjects_id'];
						}
						
						$data = $data.'<tr width="100%">';
						$data = $data.'<td align="center" width="40%"></td>';
						$data = $data.'<td width="15%" align="center"><b>'.$total_score.'</b></td>';
						$data = $data.'<td width="15%">&nbsp;</td>';
						$data = $data.'<td width="15%" align="center"><b>'.$aggr_sum.'</b></td>';
						$data = $data.'<td width="15%" align="center">&nbsp;</td>';
						//$data = $data.'<td width="54%">'.getDivision($subjects_code_array).'</td>';
					//	$data = $data.'<td width="7%">Division&nbsp;</td>';
					//	$data = $data.'<td align="center" width="7%"></td>';
					//	$data = $data.'<td width="7%" align="center" style="color:red;font-weight:bold;" >'.getDivisionO($credits_array,$issentialsubs_array,$divisioncategories_id,$numofsubs).'</td>';					
						$data = $data.'</tr>';
						
																
															
						$data = $data.'</table>';
						
					
												
						$data = $data.'</td>';			
						$data = $data.'</tr>';
						// get remarks
						$data = $data.'<tr  style="padding:5px;">';
						$data = $data.'<td  align="right" colspan="2" width="100%">'.strtoupper($lablearray['805']).": <b>".strtoupper(getDivision(array_values($credits_array))).'</b></td>';
						$data = $data.'</tr>';	
						$data = $data.'</table>';
						
						$document->writeHTML($data, false, false, false, false, '');
						
						$document->SetFont('helvetica','', 7);
						$data = '<table border="0.2" cellspacing="0" cellpadding="2">';
						$data = $data.'<tr width="100%">';
						$data = $data.'<td><b>'.$lablearray['807'].'</b></td>';						
						$data = $data.'<td></td>';					
						$data = $data.'</tr>';						
													
						$division_query = tep_db_query("SELECT division_name,divisionranges_from,divisionranges_to FROM " . TABLE_DIVISIONRANGES." AS dr, ".TABLE_DIVISION." as d WHERE d.division_id=dr.division_id AND  divisioncategories_id ='".$divisioncategories_id."' ORDER BY division_name ASC");
						
						while($division = tep_db_fetch_array($division_query)){						
							$data = $data.'<tr><td>'.$division['division_name']."</td><td align='right'> ".$division['divisionranges_from']."  to  ".$division['divisionranges_to']."</td></tr>";									
						}
						
					
					
						$data = $data.'<tr>';
						$data = $data.'<td>';
						
						$division_query = tep_db_query("SELECT gradesdefinition_name,gradesdefinition_acron FROM " .TABLE_GRADESDEFINITION."  ORDER BY gradesdefinition_id DESC");
						
						$i = 0;
						$document->SetFont('Arial','', 7);
						$data = $data.'<span style="font-size:1em;">';
						while($grades = tep_db_fetch_array($division_query)){							
							
							$data = $data." <b>".$grades['gradesdefinition_acron']." </b> : ".$grades['gradesdefinition_name']."<br>";
							
							$i++;									
						}
						$data = $data.'</span>';
					
						$data = $data.'</td>';					
						$data = $data.'<td>'.$position_array['examdefinition_remarks'].'</td>';					
						$data = $data.'</tr>';
						$data = $data.'</table>';
						$data = $data.'</div>';
						
						$document->writeHTML($data, false, false, false, false, '');
						
						// Please make sure you set this to empty string, else writeHTML will print duplicated data in the pdf
						$data = "";
						
					}
					
					
					
		}	
		//HSC School
		if($level=='A'){
			
				
				
				/*$exams_id = $_SESSION['exams_id'];
				$termsdefinition_id = $_SESSION['termsdefinition_id'];
				$classes_id = $_SESSION['classes_id'];
				$subjectsmarks_year = $_SESSION['subjectsmarks_year'];
				$students_level = $_SESSION['level'];
				$students_sregno = replaces_underscores($_SESSION['students_sregno']);
				
				
				$level ="A";
				
				if($termsdefinition_id=="1"){
						$termsdefinition_name = "First term";	
				}elseif($termsdefinition_id=="2"){
						$termsdefinition_name = "Second term";	
				}elseif($termsdefinition_id=="3"){
						$termsdefinition_name = "Third term";
				}
										
				$total_days_query = tep_db_query("SELECT DATEDIFF(terms_ends,terms_begins) AS totaldays FROM ". TABLE_TERMS." WHERE terms_name='".$termsdefinition_name."' AND terms_year='".$subjectsmarks_year."'");
				$total_days_array = tep_db_fetch_array($total_days_query);
				
				$terms_query = tep_db_query("select termsdefinition_name FROM " . TABLE_TERMSDEFINITION." WHERE termsdefinition_id='".$termsdefinition_id."'");
				$terms_array = tep_db_fetch_array($terms_query);
				$termsdefinition_name = $terms_array['termsdefinition_name'];	
				
				if($_SESSION['mode']=='All'){
					$allresultsquery = "SELECT students_gender,p.students_unebindexno,p.students_image,p.students_isborder,sc.classes_id,p.students_house,p.students_gender,(SELECT classes_name FROM ".TABLE_CLASSES." c WHERE c.classes_id=sc.classes_id) AS classes_name,c.classes_name,p.students_sregno, p.students_firstname,p.students_lastname FROM ". TABLE_STUDENTS." as p, ".TABLE_STUDENTCLASSES." AS sc WHERE sc.students_sregno=p.students_sregno AND sc.classes_id = '".$_SESSION['classes_id']."'";							
				}else{
					$allresultsquery = "SELECT students_gender,p.students_unebindexno,p.students_image,p.students_isborder,sc.classes_id,p.students_house,p.students_gender,(SELECT classes_name FROM ".TABLE_CLASSES." c WHERE c.classes_id=sc.classes_id) AS classes_name,p.students_sregno, p.students_firstname,p.students_lastname FROM ". TABLE_STUDENTS." as p , ".TABLE_STUDENTCLASSES." AS sc WHERE sc.students_sregno=p.students_sregno AND p.students_sregno = '".$students_sregno."'";
				}
				
								
				$results = tep_db_query($allresultsquery);
				
				//$data ='<table width="100%" border="1" cellspacing="0" cellpadding="0">'; 
				//$data = $data.'<tr>'; 
				//$data = $data.'<td>';
			
				//$document->startPageGroup();
				*/	
			
				$document->SetFont('helvetica','', 8);
			
				$lablearray = getlables("236,789,9,189,194,784,191,785,148,786,787,788,801,802,803,804,805,807,195,808,322,814");
				
				while($examdefinition_array = tep_db_fetch_array($results)){	
				
					// get examination details
					if($_GET['mode']=="ALL"){
						
						$students_level = $_GET['level'];
						
						$classes_d = $_GET['classes_d'];			
			
						$exams_id = $_GET['exams_id'];
															
						//$examdefinition_query = tep_db_query("SELECT * FROM ". TABLE_EXAMDEFINITION." WHERE exams_id='".$_GET['exams_id']."' AND termsdefinition_id='".$_GET['termsdefinition_id']."' AND classes_id='".$_GET['classes_id']."' AND students_sregno='".$pupils['students_sregno']."'");
						//$examdefinition_array = tep_db_fetch_array($examdefinition_query);
						
						
						
						if($examdefinition_array['examdefinition_id']==""){
							continue;
						}
						
						$examdefinition_id = $examdefinition_array['examdefinition_id'];
						$exams_id = $_GET['exams_id'];
						$termsdefinition_id = $examdefinition_array['termsdefinition_id'];
						$classes_id = $examdefinition_array['classes_id'];
						$subjectsmarks_year = $examdefinition_array['subjectsmarks_year'];
						$students_level = $examdefinition_array['examdefinition_level'];
						$divisioncategories_id	= $examdefinition_array['divisioncategories_id'];	
						$level = $students_level;
						
						$terms_query = tep_db_query("select termsdefinition_name FROM " . TABLE_TERMSDEFINITION." WHERE termsdefinition_id='".$examdefinition_array['termsdefinition_id']."'");
						
						//echo $examdefinition_array['termsdefinition_id'];
						
						//exit();
						
						$terms_array = tep_db_fetch_array($terms_query);
						
						$termsdefinition_name = $terms_array['termsdefinition_name'];
					
						if($termsdefinition_id=="1"){	
											
							$lablearray = getlables("781");						
							$termsdefinition_name =$lablearray["781"];
						
						}elseif($termsdefinition_id=="2"){
							
							$lablearray = getlables("782");
							$termsdefinition_name =$lablearray["782"];	
							
						}elseif($termsdefinition_id=="3"){
						
							$lablearray = $getlables["783"];
							$termsdefinition_name = $lablearray["783"];
							
						}							
								
					}else{	
							
						$students_level = $_GET['level'];			
					}
					
					
					
					$document->AddPage('P','A4',true);
					
					$allresultsquery = "SELECT students_gender,p.students_unebindexno,p.students_isborder,p.students_image,p.students_isborder,sc.classes_id,p.students_house,p.students_gender,c.classes_name,p.students_sregno, p.students_firstname,p.students_lastname,p.branchcode,(select cc.classcategories_name FROM ".TABLE_CLASSCATEGORIES." cc WHERE cc.classcategories_id=p.classcategories_id)classcategories_name FROM ". TABLE_STUDENTS." as p  INNER JOIN ".TABLE_STUDENTCLASSES." AS sc ON sc.students_sregno=p.students_sregno LEFT JOIN ".TABLE_CLASSES." as c on c.classes_id=sc.classes_id WHERE p.students_sregno = '".$examdefinition_array['students_sregno']."' group by p.students_sregno";
					$results2 = tep_db_query($allresultsquery);
					$pupils = tep_db_fetch_array($results2);
							
											
					$students_sregno =	$pupils['students_sregno'];
																							
					$classes_query = tep_db_query("select * FROM " . TABLE_CLASSES." WHERE classes_id='".($classes_id + 1)."'");
					
					$classes_array = tep_db_fetch_array($classes_query);
					
					$ispromoted_class = $classes_array['classes_name'];
				
					if( $pupils['students_isborder']=="Y"){
						$fees = $classes_array['classes_borders'];			
					}else{
						$fees = $classes_array['classes_amount'];
					}
										
					$data = $data."<div style='margin:0'>";
					$data = $data.'<table width="100%" border="0.1" cellspacing="0" cellpadding="5">'; 
					$data = $data.'<tr>';
					$data = $data.'<td  bgcolor="#000000" colspan="2" align="center" style="font-size:1.5em;color:#FFFFFF" >'.$lablearray['789'].'</td>'; 
					$data = $data.'</tr>';
					$data = $data.'<tr>';	
					$data = $data.'<td colspan="2">';											
						$data = $data.'<table width="100%"  border="0.1" cellspacing="0" cellpadding="2">';
						$data = $data.'<tr bgcolor="#EEEEEE" style="font-size:1em;">';						
							$data = $data.'<td><b>'.strtoupper($lablearray['9']).'</b><br>'.strtoupper($pupils['students_lastname'].' '.$pupils['students_firstname']).'</td>';
							$data = $data.'<td><b>'.strtoupper($lablearray['189']).'</b><br>'.strtoupper($pupils['students_sregno']).'</td>';
							$data = $data.'<td><b>'.strtoupper($lablearray['194']).'</b><br>'.strtoupper($pupils['classes_name']).'</td>';
							$data = $data.'<td><b>'.strtoupper($lablearray['784']).'</b><br>'.strtoupper($termsdefinition_name).'</td>';
							$data = $data.'<td><b>'.strtoupper($lablearray['199']).'</b><br>'.strtoupper($pupils['students_gender']).'</td>';
							$data = $data.'<td><b>'.strtoupper($lablearray['191']).'</b><br>'.strtoupper($pupils['students_unebindexno']).'</td>';				
						 $data = $data.'</tr>';
						 $data = $data.'<tr bgcolor="#EEEEEE">';
							$data = $data.'<td>'.$lablearray['236'].'<br>'.$pupils['classcategories_name'].'</td>';
							$data = $data.'<td></td>';
							$data = $data.'<td>'.$lablearray['808'].'<br>'.$pupils['students_isborder'].'</td>';
							$data = $data.'<td>'.$lablearray['322'].'<br>'.$pupils['branchcode'].'</td>';
							$data = $data.'<td>'.$lablearray['195'].'<br>'.$pupils['students_house'].'</td>';
							$data = $data.'<td>'.$lablearray['807'].'<br>'.date('d-m-Y').'</td>';	
						  $data = $data.'</tr>';
						$data = $data.'</table>';
					$data = $data.'</td>';
					$data = $data.'</tr>';
					$data = $data.'</table>';
					
					//$document->writeHTML($data,true, false, true, false, '');
					//$document->SetFont('helvetica','', 9);
					
					//begin first exam
					
					$data =$data.'<table width="100%" border="0" cellspacing="0" cellpadding="0" >'; 
					$data = $data.'<tr>';
					$data = $data.'<td  bgcolor="#000000" colspan="3" style="color:#FFFFFF" align="center" ><b>'.strtoupper($lablearray['785']).'</b></td>'; 
					$data = $data.'</tr>';
					$data = $data.'<tr>';											
						
						$average_array = array();
						$x=0;
						$total_paper_grades=0;
						$subjects_query = tep_db_query("select subjects_id,subjects_name,subjects_code  from " . TABLE_SUBJECTS." WHERE subjects_level='A' GROUP BY subjects_code ORDER BY subjects_code ASC");
						
						
						$subjects_ids_array = array();
							
						$x= 0;
						$total_no_points=0;
		
						while ($subjects = tep_db_fetch_array($subjects_query)) {								
											
							$subjects_names_array[$subjects['subjects_id']] = $subjects['subjects_name'];							
							$subjects_ids_array[] = $subjects['subjects_id'];
							$average_array[$subjects['subjects_id']] = "";
							$x= $x+1;
							
						}						
			

						$data = $data.'<td  width="100%">';
					
						$data = $data.'<table border="0.1em" cellspacing="0" cellpadding="1">';		
						$data = $data.'<tr bgcolor="#EEEEEE">';
						$data = $data.'<td  align="center" width="10%" style="font-size:0.8em"><b>'.strtoupper($lablearray['148']).'</b></td>';
						$data = $data.'<td  align="center" width="18%" style="font-size:0.8em"><b>'.strtoupper($lablearray['786']).'</b></td>';
						$data = $data.'<td  align="center" width="18%" style="font-size:0.8em"><b>'.strtoupper($lablearray['787']).'</b></td>';
						$data = $data.'<td  align="center" width="33%" style="font-size:0.8em"><b>'.strtoupper($lablearray['788']).'</b></td>';						
						$data = $data.'<td  align="center" width="7%" style="font-size:0.8em"><b>'.strtoupper($lablearray['801']).'</b></td>';
						$data = $data.'<td  align="center" width="7%" style="font-size:0.8em"><b>'.strtoupper($lablearray['802']).'</b></td>';
						$data = $data.'<td  align="center" width="7%" style="font-size:0.8em"><b>'.strtoupper($lablearray['813']).'</b></td>';
						//$data = $data.'<td width="132"><b>Instructor Evaluational Comment</b></td>';
						
						//$nest1 ='<table cellspacing="0" cellpadding="0"><tr><td>P1</td><td>P2</td><td>P3</td><td>P4</td><td>P5</td><td>P6</td></tr></table>';
						
						$data = $data.'</tr>';	
						$data = $data.'<tr bgcolor="#EEEEEE">';
						$data = $data.'<td  align="center" width="10%"></td>';
						$data = $data.'<td  align="center" width="18%" bgcolor="#000000" style="color:#FFFFFF"><table cellspacing="0" border="1" cellpadding="0"><tr><td colspan="6"></td></tr></table></td>';
						$data = $data.'<td  align="center" width="18%" bgcolor="#000000" style="color:#FFFFFF"><table cellspacing="0" border="1" cellpadding="0"><tr><td colspan="6"></td></tr></table></td>';
						$data = $data.'<td  align="center" width="33%" bgcolor="#000000" style="color:#FFFFFF"><table cellspacing="0" border="1" cellpadding="0"><tr><td colspan="6"></td></tr></table></td>';
						$data = $data.'<td  align="center" width="7%"></td>';
						$data = $data.'<td  align="center" width="7%"></td>';
						$data = $data.'<td  align="center" width="7%"></td>';			
						//$data = $data.'<td width="132"><b>Instructor Evaluational Comment</b></td>';
						$data = $data.'</tr>';										
						$data = $data.'</table>';	
						
					$data = $data.'<table cellspacing="0" cellpadding="0" border="0.2em">';
					//array_shift($subjects_ids_array);
					$gpoints = 0;													
					
					//print_r($subjects_names_array);			
					$subjects_ids_array = array_reverse($subjects_ids_array);
					//print_r($subjects_ids_array);
					array_pop($subjects_ids_array);
					
					foreach($subjects_ids_array as $code){

						
						 // now get the marks
						$subjects_marks_range_results = tep_db_query("SELECT s.subjects_name,s.subjects_code,s.subjects_acron,sm.subjects_id,subjectsmarks_value,sd.subjectsettings_papers as nopapers FROM " . TABLE_SUBJECTSMARKS." AS sm LEFT JOIN ".TABLE_SUBJECTS." AS s ON sm.subjects_id=s.subjects_id LEFT JOIN ".TABLE_SUBJECTSETTINGS." as sd ON sd.subjects_id=s.subjects_id WHERE  s.subjects_id='".$code."' AND sm.examdefinition_id='".$examdefinition_id."' ORDER BY sm.subjectsmarks_value,sm.subjects_id ASC");
											
						if(!tep_db_num_rows($subjects_marks_range_results)){						
							
							//$data ='<p style="color:red;font-size:2em;" align="center">'.$lablearray['814'].' '.$subjects_names_array[$code].'</p>';
							
							continue;
							
						}
							
						$ncounter = array();
						$totmarks = array();
						$acrons = array();
						$submax = array();
						$submin = array();
						
						$averages = '<table cellspacing="0" border="0.1em" cellpadding="1" width="100%"><tr><td>X1</td><td>X2</td><td>X3</td><td>X4</td><td>X5</td><td>X6</td></tr></table>';
						$ranges = '<table cellspacing="0" border="0.1em" cellpadding="1" width="100%"><tr><td align="center">X1</td><td align="center">X2</td><td align="center">X3</td><td align="center">X4</td><td align="center">X5</td><td align="center">X6</td></tr></table>';
						
						//$marks['divisioncategories_id'] = 
						
						while($range = tep_db_fetch_array($subjects_marks_range_results)){						
															
							
							// get maximum mark
							if ($range['subjectsmarks_value'] > $submax[$range['subjects_id']]){
								$submax[$range['subjects_id']]= $range['subjectsmarks_value'];
							}
							
							// get minimum mark
							if ($range['subjectsmarks_value'] < $submin[$range['subjects_id']]){
								$submin[$range['subjects_id']]= $range['subjectsmarks_value'];									
							}else{
								$submin[$range['subjects_id']]= $range['subjectsmarks_value'];	
							}
							
							$ncounter[$range['subjects_id']] = $ncounter[$range['subjects_id']]+ 1;
							$acrons[$range['subjects_id']]   = substr($range['subjects_acron'],strlen($range['subjects_acron'])-1,strlen($range['subjects_acron']));
							$totmarks[$range['subjects_id']] =  $totmarks[$range['subjects_id']] + $range['subjectsmarks_value'];														
							
						}
						
						
						foreach ($ncounter as $key => $val) {
							
							$averages = str_replace('X'.$acrons[$key],evaluateGrade((int)$totmarks[$key]/$val,'A',$key,$examdefinition_array['divisioncategories_id']).'<sub>'.(int)$totmarks[$key]/$val.'</sub>',$averages);
							$ranges = str_replace('X'.$acrons[$key],evaluateGrade((int)$submin[$key],'A',$key,$examdefinition_array['divisioncategories_id']).'<sub>'.$submin[$key].'</sub>-'.evaluateGrade((int)$submax[$key],'A',$key,$examdefinition_array['divisioncategories_id']).'<sub>'.$submax[$key].'</sub>',$ranges);												
						}			
					
					
						$averages = trim(preg_replace('(X1|X2|X3|X4|X5|X6)','',$averages));	
						$ranges = trim(preg_replace('(X1|X2|X3|X4|X5|X6)','',$ranges));	
						
					
						$subjects_marks_query = tep_db_query("SELECT subjects_name,subjects_code,subjects_acron,sm.subjectsmarks_value,sm.subjects_id FROM " . TABLE_SUBJECTSMARKS." AS sm LEFT JOIN ".TABLE_SUBJECTS." AS s ON sm.subjects_id=s.subjects_id  WHERE  s.subjects_id='".$code."' AND sm.examdefinition_id='".$examdefinition_id."' ORDER BY subjects_acron,sm.subjectsmarks_value");
						//echo "SELECT subjects_name,subjects_code,subjects_acron,sm.subjectsmarks_value,sm.subjects_id FROM " . TABLE_SUBJECTSMARKS." AS sm LEFT JOIN ".TABLE_SUBJECTS." AS s ON sm.subjects_id=s.subjects_id  WHERE  s.subjects_id='".$code."' AND sm.examdefinition_id='".$examdefinition_id."' ORDER BY subjects_acron,sm.subjectsmarks_value";
						//	exit();
						
											
						if(!tep_db_num_rows($subjects_marks_query)){
							
							$data = $data.'<tr >';		
							$data = $data.'<td width="10%">'.$subjects_names_array[$code].'</td>';	
							$data = $data.'<td width="18%"></td>';	
							//$data = $data.'<tr >';
							$data = $data.'<td width="18%"></td>';
							$data = $data.'<td width="33%"></td>';
							$data = $data.'<td width="7%">&nbsp;</td>';
							$data = $data.'<td width="7%">&nbsp;</td>';
							$data = $data.'<td width="7%">&nbsp;</td>';
						//	$data = $data.'<td width="132">&nbsp;</td>';
							$data = $data.'</tr>';
										
						}else{
																					
							$i=1;
							$total_paper_grades = 0;							
							$credits_string = '';
							$credits = '<table cellspacing="0" border="0.1em" cellpadding="2" ><tr><td>X1</td><td>X2</td><td>X3</td><td>X4</td><td>X5</td><td>X6</td></tr></table>';
							$no_of_subjects_done=0;
							$points=0;			
							$total_no_points = 0;
							
							$divisioncategories_id = $examdefinition_array['divisioncategories_id'];
							
							$nsub = 1;
							while($marks = tep_db_fetch_array($subjects_marks_query)){				
								$subjects_code = $marks['subjects_code'];
								$value = $value + (int)$marks['subjectsmarks_value'];
								// check see if subject has grading scale defined for it
								$grd = evaluateGrade((int)$marks['subjectsmarks_value'],'A',$marks['subjects_id'],$divisioncategories_id);
								if ($grd =='U' && $marks['subjectsmarks_value'] > 0){
									$lablesarray = getlables("816");
									$data = '<p style="color:red;font-weight:bold;font-size:2em" >'.$lablesarray['816'].' '.$marks['subjects_name'].' '.$marks['subjects_code'].'</p>';
									break 3;
								}
								
								$total_paper_grades = $total_paper_grades + substr(evaluateGrade((int)$marks['subjectsmarks_value'],'A',$marks['subjects_id'],$divisioncategories_id),1,2);
								
								//echo evaluateGrade((int)$marks['subjectsmarks_value'],'A',$marks['subjects_id'],$divisioncategories_id);
																						
								$credits = str_replace('X'.$acrons[$marks['subjects_id']],evaluateGrade((int)$marks['subjectsmarks_value'],'A',$marks['subjects_id'],$divisioncategories_id).'<sub>'.$marks['subjectsmarks_value'].'</sub>',$credits);
															
								$credits_string.=(int)substr(evaluateGrade((int)$marks['subjectsmarks_value'],'A',$marks['subjects_id'],$divisioncategories_id),1,2)."</sub>";				
							//echo evaluateGrade((int)$marks['subjectsmarks_value'],'A',$marks['subjects_id'],$divisioncategories_id);
							//exit();
							
						
							
								$i++; 						
																	
							 }
							 
						
							
						
							 // remove the Xes that have not been filled
							$credits = preg_replace('(X1|X2|X3|X4|X5|X6)','',$credits);		
											 
										
							 // review  credit string to incoude papaer numbers/codes for proper identification
							$data = $data.'<tr>';
							$data = $data.'<td width="10%">'.trim($subjects_names_array[$code]).'</td>';
							$data = $data.'<td align="center" nowrap="nowrap" width="18%" style="font-size:0.8em">'.$credits.'</td>';
							$data = $data.'<td align="center" nowrap="nowrap" width="18%" style="font-size:0.8em">'.$averages.'</td>';
							//sort($range);	
							$data = $data.'<td nowrap="nowrap" width="33%" style="font-size:0.8em">'.$ranges.'</td>';		
															
							 $no_of_subjects_done = $i-1;												
																			
							// check see how many subjects are for this paper
							$subjects_papers_query = tep_db_query("SELECT subjectsettings_instavg,subjectsettings_papers as no,subjectcategories_weight FROM " .TABLE_SUBJECTSETTINGS." AS sd,".TABLE_SUBJECTCATEGORIES." AS sc WHERE sd.subjectcategories_id=sc.subjectcategories_id AND subjects_id='".$code."'");
							
							//echo "SELECT subjectsettings_instavg,subjectsettings_papers as no,subjectcategories_weight FROM " .TABLE_SUBJECTSETTINGS." AS sd,".TABLE_SUBJECTCATEGORIES." AS sc WHERE sd.subjectcategories_id=sc.subjectcategories_id AND subjects_id='".$code."'";
							//exit();
							
						
							$no_of_papers = tep_db_fetch_array($subjects_papers_query);
							
							if((int)$no_of_subjects_done == (int)$no_of_papers['no']){																
							
								$average_paper_grades = $total_paper_grades / $no_of_papers['no'];
								
								$points = getNumberofPoints($credits_string,$average_paper_grades,$no_of_papers['no'],$subjects_code,$divisioncategories_id);									
							}else{
								$points = 0;						
							}
						
								
							$total_no_points = $total_no_points + $points;	
														
							$gpoints = $gpoints + $total_no_points;								
							
							$data = $data.'<td align="center" width="7%" >'.$total_no_points*(int)$no_of_papers['subjectcategories_weight'].'</td>';
							$data = $data.'<td align="center" style="font-weight:bold;" width="7%" style="font-weight:0.2em">'.$total_no_points.'</td>';	
							$data = $data.'<td align="center" style="color:red;font-weight:bold;" width="7%">'.getGradeFromPoints($credits_string,(int)($average_paper_grades),$no_of_papers['no'],$points,$divisioncategories_id).'</td>';
							$data = $data.'</tr>';
							
							
						}							
						
						
					}
					
					
					$data = $data.'<tr width="100%">';
					$data = $data.'<td align="center" width="10%"></td>';
					$data = $data.'<td width="18%">&nbsp;</td>';
					$data = $data.'<td width="18%">&nbsp;</td>';
					$data = $data.'<td width="33%">&nbsp;</td>';
					$data = $data.'<td width="7%">Total&nbsp;</td>';
					$data = $data.'<td align="center" width="7%"><b>'.$gpoints.'</b></td>';
					$data = $data.'<td width="7%"></td>';					
					$data = $data.'</tr>';									
					$data = $data.'</table>';
					
					//$document->writeHTML($data, true, false, true, false, '');
					//$document->SetFont('helvetica','', 8);
											
					$data = $data.'</td>';
					$data = $data.'</tr>';
					$data = $data.'</table>';
					$data = $data.'<table border="0" cellspacing="0" cellpadding="2">';
					$data = $data.'<tr width="100%">';
					$data = $data.'<td><b></b></td>';						
					$data = $data.'<td></td>';					
					$data = $data.'</tr>';						
					$data = $data.'<tr>';
					$data = $data.'<td>';
					
					$division_query = tep_db_query("SELECT divisionA_points,divisionA_name FROM " . TABLE_DIVISIONA."  WHERE divisioncategories_id ='".$divisioncategories_id."' ORDER BY divisionA_name ASC");
					
					while($division = tep_db_fetch_array($division_query)){						
						$data = $data.$division['divisionA_name'].": ".$division['divisionA_points']." Pts<br>";									
					}
					
					$data = $data.'</td>';					
					$data = $data.'<td></td>';					
					$data = $data.'</tr>';
					$data = $data.'<tr >';
					$data = $data.'<td></td>';						
					$data = $data.'<td></td>';					
					$data = $data.'</tr>';
					$data = $data.'<tr>';
					$data = $data.'<td>';
					
					$division_query = tep_db_query("SELECT gradesdefinition_name,gradesdefinition_acron FROM " .TABLE_GRADESDEFINITION."  ORDER BY gradesdefinition_id DESC");
					
					$i = 0;
					$data = $data.'<table border="0" cellspacing="0" cellpadding="0">';
					$data = $data.'<tr><td>';
					while($grades = tep_db_fetch_array($division_query)){		
													
						$data = $data."<b>".$grades['gradesdefinition_acron']."</b>: ".$grades['gradesdefinition_name']." ";								
						$i++;									
					}
					$data = $data.'</td></tr>';	
					$data = $data.'</table>';
					$data = $data.'</td>';					
					$data = $data.'<td></td>';					
					$data = $data.'</tr>';
					$data = $data.'</table>';
					$data = $data.'</div>';
					//echo $document->getNumPages();
					//exit();
					//$document->AddPage('L','A4',true);
					$document->writeHTML($data, false, false, false, false, '');
					
					// Please make sure you set this to empty string, else writeHTML will print duplicated data/reports in the pdf
					$data = "";
					
		}				
		
			
			
		
		
		}
		
		// ordinary Level report
				
			if($level=='O'){					
				
				$document->SetFont('helvetica','', 10);
			
				$lablearray = getlables("236,789,9,189,194,784,191,785,148,786,787,788,801,802,803,804,805,807,195,808,322");
				
				while($examdefinition_array = tep_db_fetch_array($results)){					
					
					
					// get examination details
					if($_GET['mode']=="ALL"){
						
						$students_level = $_GET['level'];
						
						$classes_d = $_GET['classes_d'];			
			
						$exams_id = $_GET['exams_id'];
															
						//$examdefinition_query = tep_db_query("SELECT * FROM ". TABLE_EXAMDEFINITION." WHERE exams_id='".$_GET['exams_id']."' AND termsdefinition_id='".$_GET['termsdefinition_id']."' AND classes_id='".$_GET['classes_id']."' AND students_sregno='".$pupils['students_sregno']."'");
						//$examdefinition_array = tep_db_fetch_array($examdefinition_query);
						
						
						if($examdefinition_array['examdefinition_id']==""){
							continue;
						}
						
						$examdefinition_id = $examdefinition_array['examdefinition_id'];
						$exams_id = $_GET['exams_id'];
						$termsdefinition_id = $examdefinition_array['termsdefinition_id'];
						$classes_id = $examdefinition_array['classes_id'];
						$subjectsmarks_year = $examdefinition_array['subjectsmarks_year'];
						$students_level = $examdefinition_array['examdefinition_level'];
						$divisioncategories_id	= $examdefinition_array['divisioncategories_id'];	
						$level = $students_level;
						
						$terms_query = tep_db_query("select termsdefinition_name FROM " . TABLE_TERMSDEFINITION." WHERE termsdefinition_id='".$examdefinition_array['termsdefinition_id']."'");
						
						$terms_array = tep_db_fetch_array($terms_query);
						
						$termsdefinition_name = $terms_array['termsdefinition_name'];
					
						if($termsdefinition_id=="1"){	
											
							$lablearray = getlables("781");						
							$termsdefinition_name =$lablearray["781"];
						
						}elseif($termsdefinition_id=="2"){
							
							$lablearray = getlables("782");
							$termsdefinition_name =$lablearray["782"];	
							
						}elseif($termsdefinition_id=="3"){
						
							$lablearray = $getlables["783"];
							$termsdefinition_name = $lablearray["783"];
							
						}							
								
					}else{	
							
						$students_level = $_GET['level'];			
					}
					
					
					
					$document->AddPage('P','A4',true);
					
					$allresultsquery = "SELECT students_gender,p.students_unebindexno,p.students_isborder,p.students_image,p.students_isborder,sc.classes_id,p.students_house,p.students_gender,c.classes_name,p.students_sregno, p.students_firstname,p.students_lastname,p.branchcode,(select cc.classcategories_name FROM ".TABLE_CLASSCATEGORIES." cc WHERE cc.classcategories_id=p.classcategories_id)classcategories_name FROM ". TABLE_STUDENTS." as p  INNER JOIN ".TABLE_STUDENTCLASSES." AS sc ON sc.students_sregno=p.students_sregno LEFT JOIN ".TABLE_CLASSES." as c on c.classes_id=sc.classes_id WHERE p.students_sregno = '".$examdefinition_array['students_sregno']."' group by p.students_sregno";
					$results2 = tep_db_query($allresultsquery);
					$pupils = tep_db_fetch_array($results2);
							
											
					$students_sregno =	$pupils['students_sregno'];
																							
					$classes_query = tep_db_query("select * FROM " . TABLE_CLASSES." WHERE classes_id='".($classes_id + 1)."'");
					
					$classes_array = tep_db_fetch_array($classes_query);
					
					$ispromoted_class = $classes_array['classes_name'];
				
					if( $pupils['students_isborder']=="Y"){
						$fees = $classes_array['classes_borders'];			
					}else{
						$fees = $classes_array['classes_amount'];
					}
					
					
					$data = $data."<div style='margin:0'>";
					$data = $data.'<table width="100%" border="1" cellspacing="0" cellpadding="5">'; 
					$data = $data.'<tr>';
					$data = $data.'<td  bgcolor="#000000" colspan="2" align="center" style="font-size:1.5em;color:#FFFFFF" >'.$lablearray['789'].'</td>'; 
					$data = $data.'</tr>';
					$data = $data.'<tr>';	
					$data = $data.'<td colspan="2">';											
						$data = $data.'<table width="100%"  border="0.1" cellspacing="0" cellpadding="2">';
						$data = $data.'<tr bgcolor="#EEEEEE" style="font-size:1em;">';						
							$data = $data.'<td><b>'.strtoupper($lablearray['9']).'</b><br>'.strtoupper($pupils['students_lastname'].' '.$pupils['students_firstname']).'</td>';
							$data = $data.'<td><b>'.strtoupper($lablearray['189']).'</b><br>'.strtoupper($pupils['students_sregno']).'</td>';
							$data = $data.'<td><b>'.strtoupper($lablearray['194']).'</b><br>'.strtoupper($pupils['classes_name']).'</td>';
							$data = $data.'<td><b>'.strtoupper($lablearray['784']).'</b><br>'.strtoupper($termsdefinition_name).'</td>';
							$data = $data.'<td><b>'.strtoupper($lablearray['199']).'</b><br>'.strtoupper($pupils['students_gender']).'</td>';
							$data = $data.'<td><b>'.strtoupper($lablearray['191']).'</b><br>'.strtoupper($pupils['students_unebindexno']).'</td>';				
						 $data = $data.'</tr>';
						 $data = $data.'<tr bgcolor="#EEEEEE">';
							$data = $data.'<td>'.$lablearray['236'].'<br>'.$pupils['classcategories_name'].'</td>';
							$data = $data.'<td></td>';
							$data = $data.'<td>'.$lablearray['808'].'<br>'.$pupils['students_isborder'].'</td>';
							$data = $data.'<td>'.$lablearray['322'].'<br>'.$pupils['branchcode'].'</td>';
							$data = $data.'<td>'.$lablearray['195'].'<br>'.$pupils['students_house'].'</td>';
							$data = $data.'<td>'.$lablearray['807'].'<br>'.date('d-m-Y').'</td>';	
						  $data = $data.'</tr>';
						$data = $data.'</table>';
					$data = $data.'</td>';
					$data = $data.'</tr>';
					$data = $data.'</table>';
					
		
					$data =$data.'<table width="100%" border="1" cellspacing="0" cellpadding="2" >'; 
					$data = $data.'<tr>';
					$data = $data.'<td  bgcolor="#000000" colspan="3" style="color:#FFFFFF"><b>'.$lablearray['785'].'</b></td>'; 
					$data = $data.'</tr>';
					$data = $data.'<tr>';											
						
						$average_array = array();
						$x=0;
						$total_paper_grades=0;
						$subjects_query = tep_db_query("select subjects_id,subjects_name,subjects_code  from " . TABLE_SUBJECTS." WHERE subjects_level='".$level."' GROUP BY subjects_id");
					
						$subjects_ids_array = array();
						$x= 0;
						$total_no_points=0;
		
						while ($subjects = tep_db_fetch_array($subjects_query)) {								
											
							$subjects_names_array[$subjects['subjects_id']] = $subjects['subjects_name'];							
							$subjects_ids_array[] = $subjects['subjects_id'];
							$average_array[$subjects['subjects_id']] = "";
							$x= $x+1;
							
						}						
			
		
						$data = $data.'<td  width="100%">';
					
						$data = $data.'<table border="1" cellspacing="0" cellpadding="0">';		
						$data = $data.'<tr bgcolor="#EEEEEE">';
						$data = $data.'<td  align="center" width="15%"><b>'.$lablearray['148'].'</b></td>';
						$data = $data.'<td  align="center" width="15%"><b>'.$lablearray['786'].'</b></td>';
						$data = $data.'<td  align="center" width="14%"><b>'.$lablearray['787'].'</b></td>';
						$data = $data.'<td  align="center" width="33%"><b>'.$lablearray['788'].'</b></td>';						
						$data = $data.'<td  align="center" width="7%"><b>'.$lablearray['801'].'</b></td>';
						$data = $data.'<td  align="center" width="7%"><b>'.$lablearray['802'].'</b></td>';
						$data = $data.'<td  align="center" width="7%"><b>'.$lablearray['803'].'</b></td>';
						
						$data = $data.'</tr>';	
						$data = $data.'<tr bgcolor="#EEEEEE">';
						$data = $data.'<td  align="center" width="15%"></td>';
						$data = $data.'<td  align="center" width="15%" ></td>';
						$data = $data.'<td  align="center" width="14%"></td>';
						$data = $data.'<td  align="center" width="33%"></td>';
						$data = $data.'<td  align="center" width="7%"></td>';
						$data = $data.'<td  align="center" width="7%"></td>';
						$data = $data.'<td  align="center" width="7%"></td>';			
						$data = $data.'</tr>';										
						$data = $data.'</table>';	
						
					$data = $data.'<table cellspacing="0" cellpadding="2" border="1">';
					
					$gpoints = 0;
					
					$subjects_ids_array = array_reverse($subjects_ids_array);
					
					array_pop($subjects_ids_array);
					
					$credits_array = array();
														
					//$divisionacategories_query = tep_db_query("SELECT divisioncategories_id FROM " . TABLE_SUBJECTSMARKS." AS sm , ".TABLE_SUBJECTS." AS s  WHERE sm.subjects_id=s.subjects_id AND sm.subjectsmarks_year='".$subjectsmarks_year."' AND sm.exams_id='".$exams_id."' AND sm.classes_id='".$classes_id."' AND sm.subjectsmarks_level='".$level."' AND sm.students_sregno='".$students_sregno."' AND sm.termsdefinition_id='".$termsdefinition_id."' GROUP BY divisioncategories_id");
																															
					//$divisionacategories_array = tep_db_fetch_array($divisionacategories_query);
						
					//$divisioncategories_id = $divisionacategories_array['divisioncategories_id'];		
					
					foreach($subjects_ids_array as $code){
						
						 // now get the marks
						$subjects_marks_range_results = tep_db_query("SELECT s.subjects_name,s.subjects_code,s.subjects_acron,sm.subjects_id,subjectsmarks_value,sd.subjectsettings_papers as nopapers FROM " . TABLE_SUBJECTSMARKS." AS sm , ".TABLE_SUBJECTS." AS s, ".TABLE_SUBJECTSETTINGS." as sd WHERE sd.subjects_code=s.subjects_code AND sm.subjects_id=s.subjects_id AND sm.examdefinition_id='".$examdefinition_id."' AND s.subjects_id='".$code."' ORDER BY sm.subjectsmarks_value,sm.subjects_id ASC");
						
						//echo "SELECT s.subjects_name,s.subjects_code,s.subjects_acron,sm.subjects_id,subjectsmarks_value,students_sregno,sd.subjectsettings_papers as nopapers FROM " . TABLE_SUBJECTSMARKS." AS sm , ".TABLE_SUBJECTS." AS s, ".TABLE_SUBJECTSETTINGS." as sd WHERE sd.subjects_code=s.subjects_code AND sm.subjects_id=s.subjects_id AND sm.subjectsmarks_year='".$subjectsmarks_year."' AND sm.exams_id='".$exams_id."' AND sm.classes_id='".$classes_id."' AND sm.subjectsmarks_level='O' AND s.subjects_id='".$code."' AND sm.termsdefinition_id='".$termsdefinition_id."' ORDER BY sm.subjectsmarks_value,sm.subjects_id ASC";
													
						$ncounter = array();
						$totmarks = array();
						$acrons = array();
						$submax = array();
						$submin = array();
						
						$averages = ' ';
						$ranges = ' ';
						
						while($range = tep_db_fetch_array($subjects_marks_range_results)){																						
							
							// get maximum mark
							if ($range['subjectsmarks_value'] > $submax[$range['subjects_id']]){
								$submax[$range['subjects_id']]= $range['subjectsmarks_value'];
							}
						
							
							// get minimum mark
							if ($range['subjectsmarks_value'] < $submin[$range['subjects_id']]){
								$submin[$range['subjects_id']]= $range['subjectsmarks_value'];									
							}else{
								$submin[$range['subjects_id']]= $range['subjectsmarks_value'];	
							}
							
							$ncounter[$range['subjects_id']] = $ncounter[$range['subjects_id']]+ 1;
														
							$totmarks[$range['subjects_id']] =  $totmarks[$range['subjects_id']] + $range['subjectsmarks_value'];														
						}
						
						
					
						foreach ($ncounter as $key => $val){
				
							$averages = str_replace(' ',evaluateGrade((int)$totmarks[$key]/$val,'O',$key,$divisioncategories_id).'<sub>'.(int)$totmarks[$key]/$val.'</sub>',$averages);
							$ranges = str_replace(' ',evaluateGrade((int)$submin[$key],'O',$key,$divisioncategories_id).'<sub>'.$submin[$key].'</sub>-'.evaluateGrade((int)$submax[$key],'O',$key,$divisioncategories_id).'<sub>'.$submax[$key].'</sub>',$ranges);
																			
						}			
													
						$subjects_marks_query = tep_db_query("SELECT subjects_name,subjects_code,subjects_code,sm.subjectsmarks_value,sm.subjects_id,(select divisioncategories_id FROM ".TABLE_EXAMDEFINITION." WHERE examdefinition_id=sm.examdefinition_id ) divisioncategories_id FROM " . TABLE_SUBJECTSMARKS." AS sm LEFT JOIN ".TABLE_SUBJECTS." AS s ON sm.subjects_id=s.subjects_id  WHERE sm.examdefinition_id='".$examdefinition_id."' AND s.subjects_id='".$code."' ORDER BY subjects_acron,sm.subjectsmarks_value");
																	
						if(!tep_db_num_rows($subjects_marks_query)){
							
							$data = $data.'<tr >';		
							$data = $data.'<td width="15%"><b>'.$subjects_names_array[$code].'</b></td>';	
							$data = $data.'<td width="15%"></td>';	
							$data = $data.'<td width="14%"></td>';
							$data = $data.'<td width="33%"></td>';
							$data = $data.'<td width="7%">&nbsp;</td>';
							$data = $data.'<td width="7%">&nbsp;</td>';							
							$data = $data.'</tr>';
										
						}else{
																					
							$i=1;
							$total_paper_grades = 0;							
							$credits_string = '';
							$credits = ' ';
							$no_of_subjects_done=0;
							$points=0;			
							$total_no_points = 0;
							$credits_num  = 0;
							$nsub = 1;
							
							while($marks = tep_db_fetch_array($subjects_marks_query)){							
											
								$subjects_code = $marks['subjects_code'];
								$value = $value + (int)$marks['subjectsmarks_value'];
								$total_paper_grades = $total_paper_grades + (int)substr(evaluateGrade((int)$marks['subjectsmarks_value'],'O',$marks['subjects_id'],$divisioncategories_id),1,2);
																	
								$credits = str_replace(' ','<b>'.evaluateGrade((int)$marks['subjectsmarks_value'],'O',$marks['subjects_id'],$divisioncategories_id).'</b><sub>'.$marks['subjectsmarks_value'].'</sub>',$credits);
								$credits_num = substr(evaluateGrade((int)$marks['subjectsmarks_value'],'O',$marks['subjects_id'],$divisioncategories_id),1,2);
							
																	
								$credits_string.=(int)substr(evaluateGrade((int)$marks['subjectsmarks_value'],'O',$marks['subjects_id'],$divisioncategories_id),1,2)."</sub>";				
								
								$i++; 						
																	
							 }
							
											;
							 // review  credit string to incoude papaer numbers/codes for proper identification
							$data = $data.'<tr>';
							$data = $data.'<td width="15%"><b>'.$subjects_names_array[$code].'</b></td>';
							$data = $data.'<td align="center" nowrap="nowrap" width="15%">'.$credits.'</td>';
							$data = $data.'<td align="center" nowrap="nowrap" width="14%">'.$averages.'</td>';
							//sort($range);	
							$data = $data.'<td nowrap="nowrap" width="33%" align="center">'.$ranges.'</td>';		
															
							 $no_of_subjects_done = $i-1;
							
							$data = $data.'<td align="center" width="7%" ></td>';
							$data = $data.'<td align="center" style="font-weight:bold;" width="7%"></td>';	
							$data = $data.'<td align="center" style="color:#000099;font-weight:bold;" width="7%">'.$credits_num.'</td>';
							$data = $data.'</tr>';
							
							
						}							
						
						$credits_array[] = $credits_num;
					}
						
					$query_results = tep_db_query("SELECT gradesettings_numofsubjectsbestdone  FROM ".TABLE_GRADESETTINGS." WHERE divisioncategories_id='".$divisioncategories_id."'");
					$gradesettings = tep_db_fetch_array($query_results);
					
					$numofsubs =(int)$gradesettings['gradesettings_numofsubjectsbestdone'];
					
					$issentialsubs_array =array();
					
					$query_results = tep_db_query("SELECT s.subjects_id  FROM ".TABLE_SUBJECTSETTINGS." AS ss ,".TABLE_SUBJECTCATEGORIES." as sc, ".TABLE_SUBJECTS." AS s WHERE s.subjects_code=ss.subjects_code AND ss.subjectcategories_id=sc.subjectcategories_id  AND ss.divisioncategories_id='".$divisioncategories_id."' AND sc.subjectcategories_acron='REQ'");
					while($gradesettings = tep_db_fetch_array($query_results)){
						$issentialsubs_array[] = $gradesettings['subjects_id'];
					}
					
					$data = $data.'<tr width="100%">';
					$data = $data.'<td align="center" width="15%"></td>';
					$data = $data.'<td width="15%">&nbsp;</td>';
					$data = $data.'<td width="14%">&nbsp;</td>';
					$data = $data.'<td width="33%">&nbsp;</td>';
					$data = $data.'<td width="7%">'.$lablearray['805'].'&nbsp;</td>';
					$data = $data.'<td align="center" width="7%"></td>';
					$data = $data.'<td width="7%" align="center" style="color:red;font-weight:bold;" >'.getDivisionO($credits_array,$issentialsubs_array,$divisioncategories_id,$numofsubs).'</td>';					
					$data = $data.'</tr>';									
					$data = $data.'</table>';
					
		
					$document->SetFont('Arial','', 8);						
					$data = $data.'</td>';
					$data = $data.'</tr>';
					$data = $data.'</table>';
					$data = $data.'<table border="0.5" cellspacing="0" cellpadding="2" style="font-size:1em;">';
					$data = $data.'<tr width="100%">';
					$data = $data.'<td><b>'.$lablearray['804'].'</b></td>';						
					$data = $data.'<td></td>';					
					$data = $data.'</tr>';						
					$data = $data.'<tr>';
					$data = $data.'<td style="font-size:1em;">';
					
					$division_query = tep_db_query("SELECT divisionA_points,divisionA_name FROM " . TABLE_DIVISIONA."  WHERE divisioncategories_id ='".$divisioncategories_id."' ORDER BY divisionA_name ASC");
					
					while($division = tep_db_fetch_array($division_query)){						
						$data = $data.$division['divisionA_name'].": ".$division['divisionA_points']."   ";									
					}
					
					$data = $data.'</td>';					
					$data = $data.'<td>'.$examdefinition_array['examdefinition_remarks'].'</td>';					
					$data = $data.'</tr>';
					$data = $data.'<tr >';
					$data = $data.'<td></td>';						
					$data = $data.'<td></td>';					
					$data = $data.'</tr>';
					$data = $data.'<tr>';
					$data = $data.'<td>';
					
					$division_query = tep_db_query("SELECT gradesdefinition_name,gradesdefinition_acron FROM " .TABLE_GRADESDEFINITION."  ORDER BY gradesdefinition_id DESC");
					
					$i = 0;
					
					$data = $data.'<span style="font-size:1em;">';
					while($grades = tep_db_fetch_array($division_query)){							
						
						$data = $data."".$grades['gradesdefinition_acron']."  : ".$grades['gradesdefinition_name']."<br>";
						
						$i++;									
					}
					$data = $data.'</span>';
				
					$data = $data.'</td>';					
					$data = $data.'<td></td>';					
					$data = $data.'</tr>';
					$data = $data.'</table>';
					$data = $data.'</div>';
					
					
					$document->writeHTML($data, false, false, false, false, '');
					//$document->SetY(-15);
        			// Set font	
		
       				$document->SetFont('helvetica', 'I', 8);
        			// Page number
        			//$document->Cell(0, 10,$data, 0, false, 'C', 0, '', 0, false, 'T', 'M');
					
					// Please make sure you set this to empty string, else writeHTML will print duplicated data in the pdf
					$data = "";
					
				}
				
			
			
			}	
	
		break;

	case 'Students list':
		$data ='<table border="0" cellspacing="0" cellpadding="0">';						
		$data = $data.'<tr bgcolor="#D5E7FF">';
		$data = $data.'<td><b>Admission No</b></td><td><b>Firstname</b></td><td><b>Lastname</b></td><td><b>Class</b></td>';	
		$data = $data.'</tr>';
		
		$nrows = 0;
		
		while($row = tep_db_fetch_array($result_query)){
			// check see if we have 10 records
			// add new page
			if($nrows == 40){
				$nrows = 0;	
				$data = $data.'</table>';	
				
				$document->writeHTML($data, true, 0, true, 0);							
				$document->AddPage();	
									
				$data ='<table border="0" cellspacing="0" cellpadding="0">';
				$data = $data.'<tr bgcolor="#D5E7FF">';
				$data = $data.'<td><b>Admission No</b></td><td><b>Firstname</b></td><td><b>Lastname</b></td><td><b>Class</b></td>';
				$data = $data.'</tr>';						
			}
			
			// swicth row colors
			if($rowcolor == ""){
				$rowcolor = "#D5E7FF";
			}else{
				$rowcolor = "";							
			}
										
			$data = $data.'<tr bgcolor="'.$rowcolor.'">';
			$data = $data.'<td>'.$row['students_sregno'].'</td><td>'.$row['students_firstname'].'</td><td>'.$row['students_firstname'].'</td><td>'.$row['class'].'</td>';
			$data = $data.'</tr>';
			$nrows++;													
		}			
		$data = $data.'</table>';
		
	
		
		break;
	
	case 'Revenue Report':	// Revenue report
		$data ='<table border="0" cellspacing="0" cellpadding="0">';						
		$data = $data.'<tr bgcolor="#D5E7FF">';
		$data = $data.'<td><b>Admission No</b></td><td><b>Firstname</b></td><td><b>Lastname</b></td><td><b>Voucher No</b></td><td align="right"><b>Amount</b></td>';	
		$data = $data.'</tr>';
		
		$nrows = 0;
		
		while($row = tep_db_fetch_array($result_query)){
			// check see if we have 10 records
			// add new page
			if($nrows == 40){
				$nrows = 0;	
				$data = $data.'</table>';	
				$document->writeHTML($data, true, 0, true, 0);
			
				$document->AddPage();	
									
				$data ='<table border="0" cellspacing="0" cellpadding="0">';
				$data = $data.'<tr bgcolor="#D5E7FF">';
				$data = $data.'<td><b>Admission No</b></td><td><b>Firstname</b></td><td><b>Lastname</b></td><td><b>Voucher No</b></td><td align="right"><b>Amount</b></td>';
				$data = $data.'</tr>';						
			}
			
			// swicth row colors
			if($rowcolor == ""){
				$rowcolor = "#D5E7FF";
			}else{
				$rowcolor = "";							
			}
										
			$data = $data.'<tr bgcolor="'.$rowcolor.'">';
			$data = $data.'<td>'.$row['students_sregno'].'</td><td>'.$row['students_firstname'].'</td><td>'.$row['students_lastname'].'</td><td>'.$row['tran_recieptno'].'</td><td>'.$row['tran_credit'].'</td>';
			$data = $data.'</tr>';
			$nrows++;													
		}			
		$data = $data.'</table>';														
		break;
		
	
	case 'PS':	// Payment Statement
			
		$document->SetFont('', '', 9);
		
		getstudentpaymentStatement("",$_POST['students_sregno']);
					
		$result_query =tep_db_query("SELECT studentspayments_id,s.students_firstname,s.students_lastname,sp.tcode,sp.studentspayments_id,tt.transactiontypes_name,sp.studentspayments_voucher, DATE_FORMAT(sp.studentspayments_datecreated,'%d/%m/%Y') AS studentspayments_datecreated,r.requirements_name,sp.studentspayments_balance,sp.studentspayments_amount from " .TABLE_STUDENTSPAYMENTS." as sp,".TABLE_STUDENTS." as s,".TABLE_REQUIREMENTS." as r,".TABLE_TRANSACTIONTYPES." AS tt WHERE  s.students_sregno=".$_GET['students_sregno']." AND tt.transactiontypes_code=sp.transactiontypes_code AND  sp.requirements_id=r.requirements_id  ORDER BY sp.students_sregno,r.requirements_id,sp.studentspayments_datecreated ASC");
		
		if(!tep_db_num_rows($result_query)){			
			$data ="Sorry there is no data to display.";
		}
		
		$data ='<table border="0" cellspacing="0" cellpadding="0">';						
		$data = $data.'<tr bgcolor="#EEEEEE">';
		$data = $data.'<td><b>Name</b></td><td><b>Date</b></td><td><b>Tran. Code</b></td><td><b>Tran. Type</b></td><td><b>Voucher/Reciept</b></td><td><b>Item</b></td><td><b>Amount</b></td><td><b>Balance</b></td>';	
		$data = $data.'</tr>';
		
		$nrows = 0;
		
		while($row = tep_db_fetch_array($result_query)){
			// check see if we have 10 records
			// add new page
						
			if($nrows == 46){
				$nrows = 0;	
				$data = $data.'</table>';	
				
				$document->writeHTML($data, false, false, false, false, '');
				
				$document->AddPage('P','A4',true);		
								
				$data ='<table border="0" cellspacing="0" cellpadding="0">';
				$data = $data.'<tr>';
				$data = $data.'<td><b>Name</b></td><td><b>Date</b></td><td><b>Tran. Code</b></td><td><b>Tran. Type</b></td><td><b>Voucher/Reciept</b></td><td><b>Item</b></td><td><b>Amount</b></td><td><b>Balance</b></td>';	
				$data = $data.'</tr>';						
			}
			
			// swicth row colors
			if($rowcolor == ""){
				$rowcolor = "#D5E7FF";
			}else{
				$rowcolor = "";							
			}
										
			$data = $data.'<tr bgcolor="'.$rowcolor.'">';			
			$data = $data.'<td>'.$row['students_firstname'].' '.$row['students_lastname'].'</td><td>'.$row['studentspayments_datecreated'].'</td><td>'.$row['tcode'].'</td><td>'.$row['transactiontypes_name'].'</td><td>'.$row['studentspayments_voucher'].'</td><td>'.$row['requirements_name'].'</td><td>'.$row['studentspayments_amount'].'</td><td>'.$row['studentspayments_balance'].'</td>';
			$data = $data.'</tr>';
			$nrows++;													
		}			
		$data = $data.'</table>';	
		
		//$document->writeHTML($data, true, 0, true, 0);
											
		$document->AddPage('P','A4',true);													
	break;
	
	default:
	break;			
				
}
$html=$html.$data.'</p></div> <div class="footer">Page: <span class="pagenum"></span></div></body></html>';
//$html= $html + '</p></div></body></html>';
$dompdf->load_html($html);
$dompdf->render();
$dompdf->stream("paulsmith.pdf",array("Attachment" => 0));
?>