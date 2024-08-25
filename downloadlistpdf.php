<?php
require_once('includes/application_top.php');
// this file should always be included in order to print pdf documents

//setlocale(LC_ALL, 'fra_fra');

if(!isset($_SESSION['reporttitle'])){
	$_SESSION['reporttitle'] ="";
}
require_once('includes/TCPDF.PHP');
// extend TCPF with custom functions
class MYPDF extends TCPDF {
    
	public $type  	='PDF';
	public $data	="";
	
    // Load table data from file
	// $Type this is type of data being passed to be loaded
    public function LoadData($Type) {
	
		$this->SetFont('helvetica','', 10);
		$this->SetCreator(NAME_OF_INSTITUTION);
		//$this->SetAuthor('Nicola Asuni');
		//$this->SetTitle('TCPDF Example 011');
		//$this->SetSubject('TCPDF Tutorial');
		//$this->SetKeywords('TCPDF, PDF, example, test, guide');
	
		// set default header data
		$this->SetHeaderData(LOGO, PDF_HEADER_LOGO_WIDTH,'',ADDRESS,TELEPHONE);
		//$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE.' 001', PDF_HEADER_STRING, array(0,64,255), array(0,64,128));
		
		// set header and footer fonts
		$this->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
		$this->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
		
		// set default monospaced font
		$this->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
		
		//set margins
		$this->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
		
		$this->SetHeaderMargin(2);
		//$this->SetFooterMargin(PDF_MARGIN_HEADER);
		
		//set auto page breaks
		$this->SetAutoPageBreak(TRUE, 2);
		
		//set image scale factor
		$this->setImageScale(PDF_IMAGE_SCALE_RATIO); 
		
		//set some language-dependent strings
		$this->setLanguageArray($l); 		
		
		$this->setPrintHeader(false);
		//$this->SetPrintFooter(false);
		$this->setPrintFooter(false);
		$this->SetFont('', '', 10);
				
		$rowcolor ="#D5E7FF";
	
		switch($this->Type){
			
			case 'FILE':	// from file
				// Read file lines
				$lines = file($file);
				$data = array();
				foreach($lines as $line) {
					$data[] = explode(';', chop($line));
				}
				break;
			
			case 'HTML': // from database to HTML
				//$result_query = tep_db_query($_SESSION['downloadlist']);
				$this->data = "";			
				//$document->writeHTML($this->data, true, 0, false, 0);	
				
				break;
			
			case 'DATABASE': // from database to array
			
				$result_query = tep_db_query($_SESSION['downloadlist']);
			
				$data = array();
				
				while($row = mysql_fetch_row($result_query)){
					$data[] = $row;
					continue;
				}
								
				break;
			
			default:
			
			break;		
		
		}
		
    }
		    
    // Colored table
    public function ColoredTable($header,$data) {
        // Colors, line width and bold font
        $this->SetFillColor(255, 0, 0);
        $this->SetTextColor(255);
        $this->SetDrawColor(128, 0, 0);
        $this->SetLineWidth(0.3);
        $this->SetFont('', 'B');
        // Header
        $w = array(40, 35, 40, 45);
        for($i = 0; $i < count($header); $i++)
        $this->Cell($w[$i], 7, $header[$i], 1, 0, 'C', 1);
        $this->Ln();
        // Color and font restoration
        $this->SetFillColor(224, 235, 255);
        $this->SetTextColor(0);
        $this->SetFont('');
        // Data
        $fill = 0;
		$fieldlist = $_SESSION['fieldlist'];
        foreach($data as $row) {
			/*if(count($fieldlist)>0){
				
				$this->Cell($w[0], 6, $row[0], 'LR', 0, 'L', $fill);
			}else{
			
			}*/
            $this->Cell($w[0], 6, $row[0], 'LR', 0, 'L', $fill);
            $this->Cell($w[1], 6, $row[1], 'LR', 0, 'L', $fill);
        	$this->Cell($w[2], 6, $row[2], 'LR', 0, 'R', $fill);
         //   $this->Cell($w[3], 6, number_format($row[3]), 'LR', 0, 'R', $fill);
            $this->Ln();
            $fill=!$fill;
        }
        $this->Cell(array_sum($w), 0, '', 'T');
    }
	
	//Page header
    public function Header() {
        // Logo
        $image_file = K_PATH_IMAGES.'budge.jpg';
        $this->Image($image_file, 7, 7, 28, '', 'JPG', '', 'T', false, 300, '', false, false, 0, false, false, false);
        // Set font
        $this->SetFont('helvetica', 'B', 20);
        // Title
        $this->Cell(155, 10, NAME_OF_INSTITUTION."\n", 0, false, 'C', 0, '', 0, false, 'M', 'M');
		$this->SetFont('helvetica', '', 9);
		//$this->Cell(0, 14, EMAIL_FROM."\n", 0, false, 'C', 0, '', 0, false, 'M', 'M');		
	 	$this->MultiCell(100, 4,ADDRESS.'\n'.EMAIL_FROM, 0, 'J', 1, 1, 90, 10, true, 0, false, true, 20, 'T', true);
		$this->setTextShadow(array('enabled'=>true, 'depth_w'=>0, 'depth_h'=>0.01, 'color'=>array(196,196,196), 'opacity'=>1, 'blend_mode'=>'Normal'));
	
	}
	
	
	
}

// create new PDF document
$document = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, false, 'ISO-8859-1', false);
//$document->setHeaderFont(array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
//$document->SetHeaderMargin(11);
//set margins
$document->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
//$document->SetHeaderMargin(PDF_MARGIN_HEADER);
//$document->SetFooterMargin(PDF_MARGIN_FOOTER);
//($ln='', $lw=0, $ht='', $hs='', $tc=array(0,0,0), $lc=array(0,0,0))

//$document->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH,NAME_OF_INSTITUTION,ADDRESS,array(ADDRESS,''),array(0,64,255), array(0,64,128));											
//$document->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', '10'));
$document->SetLeftMargin('5');
$document->SetTopMargin('2');
$document->SetRightMargin('5');

$document->setTextShadow(array('enabled'=>true, 'depth_w'=>0, 'depth_h'=>0.01, 'color'=>array(196,196,196), 'opacity'=>1, 'blend_mode'=>'Normal'));
$document->setPrintHeader(false);
/*
// set document information
$document->SetCreator(PDF_CREATOR);
$document->SetAuthor('Nicola Asuni');
$document->SetTitle('TCPDF Example 011');
$document->SetSubject('TCPDF Tutorial');
$document->SetKeywords('TCPDF, PDF, example, test, guide');

// set default header data
//$document->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE, PDF_HEADER_STRING);

// set header and footer fonts
$document->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$document->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// set default monospaced font
$document->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);



set auto page breaks
$document->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

//set image scale factor
$document->setImageScale(PDF_IMAGE_SCALE_RATIO); 

//set some language-dependent strings
$document->setLanguageArray($l); 

// ---------------------------------------------------------

// set font
$document->SetFont('helvetica', '', 11);

// add a page
$document->AddPage();*/

//Column titles
//$header = array('Firstname', 'Lastname', 'Amount', 'Pop. (thousands)');

//Data loading
//$data = $document->LoadData('../cache/table_data_demo.txt');


getlables("310");


function vsprintf_named($format, $args) {
    $names = preg_match_all('/%\((.*?)\)/', $format, $matches, PREG_SET_ORDER);

    $values = array();
    foreach($matches as $match) {
        $values[] = $args[$match[1]];
    }

    $format = preg_replace('/%\((.*?)\)/', '%', $format);
    return vsprintf($format, $values);
}

//$foo= array('name'=>'David');
//echo vsprintf_named("%(name)s is %(age)04d", $foo);
//exit();
$data ='';
switch($_GET['rcode']){

		case 'TRANSFERREPORT':
		
		
			
		
			$document->SetRightMargin('5');	
			$document->SetLeftMargin('10');	
			//$document->setFontSubsetting(true);
				$document->SetFont('helvetica', 'B', 11);
		//	$document->SetFont('freeserif','',12);			
			$document->AddPage('L','A4',true);
						
					
			
			//$results_array = tep_db_fetch_array($result_query);	
			
			$document->Write(0, 'TRANSFER REPORT', '', 0, 'R', 6, 0, false, false, 0);
			$document->SetFont('Arial', 'B', 6);
			$document->Write(0, ADDRESS, '', 0, 'R', 1, 0, false, false, 0);
			$document->Write(0,TELEPHONE, '', 0, 'R', 1, 0, false, false, 0);
			$document->Write(0,EMAIL_FROM, '', 0, 'R', 1, 0, false, false, 0);
			//$document->SetFont('times', 'B', 12);
			$document->SetFont('Arial', '', 9);
			$document->setPrintFooter(false);

			$imgdata= file_get_contents('images/BUDGESEC.JPG'); //imagecreatefromstring(file_get_contents('images/BUDGESEC.JPG'));
			
			$document->Image('@'.$imgdata,10,3,20,20);
			$document->Ln(8);
			
			$cWhere ="";
			// table header
			getlables("983,898,900,901,984,985,894,986,317,896,171,990,991,992,993,896,962,989,300,990");
			$lblstatus	= "<p>".$lablearray['993']."</p>";
			if(isset($_GET['transfer_status'])){
				if($_GET['transfer_status']!=''){
					$cWhere=" AND tc.transfercodes_status='".tep_db_prepare_input($_GET['transfer_status'])."'";					
					
					switch(tep_db_prepare_input($_GET['transfer_status'])){
					
					case 'W':		
						$lblstatus	= "<p><b>".$lablearray['171'].':</b> '.$lablearray['990']."</p>";
						break;
					
					case 'S':		
						$lblstatus	= "<p><b>".$lablearray['171'].':</b> '.$lablearray['991']."</p>";
						break;
					
					case 'T':		
						$lblstatus	= "<p><b>".$lablearray['171'].':</b> '.$lablearray['992']."</p>";
						break;
					
					default:
						
						break;
					
					
					}
				}
			}		
			
			
			
			if(isset($_GET['currencies_code'])){
				if($_GET['currencies_code']!=''){
					$cWhere=$cWhere." AND t.currencies_code='".tep_db_prepare_input($_GET['currencies_code'])."'";
				}
			}
			
			if(isset($_GET['ucode'])){
				if($_GET['ucode']!=''){
					$cWhere=$cWhere." AND t.user_usercode='".tep_db_prepare_input($_GET['ucode'])."'";
				}
			}	
				
			 $data=$data.'<table width="100%" border="0.5" cellpadding="1" bgcolor="#FFFFFF" >';
			 $result_query = tep_db_query("SELECT t.transfers_firstname,t.transfers_lastname,t.transfers_telephone,t.transfers_firstname_rec,t.transfers_lastname_rec,t.transfers_amountoreceive,t.transfers_datecreated,tc.transfercodes_status FROM ".TABLE_TRANSFERS." t,".TABLE_TRANSFERCODES." tc WHERE tc.transfers_code=t.transfers_code AND tc.operatorbranches_code='".$_SESSION['operatorbranches_code']."' AND transfercodes_datecreated   BETWEEN ".changeDateFromPageToMySQLFormat($_GET['txtFrom'],false)." AND ".changeDateFromPageToMySQLFormat($_GET['txtTo']).$cWhere);

			 	
				
				 $data=$data.'<tr >';
				$data=$data.'<td></td>';
				 $data=$data.'<td colspan="7" align="left" style="font-size:1.4em;"><p>From: <b>'.tep_db_prepare_input($_GET['txtFrom']).'</b>                    To: <b>'.tep_db_prepare_input($_GET['txtTo']).'</b></p>'.$lblstatus.'</td>';	
				
				 $data=$data.' </tr>';
				 
			 	 $data=$data.'<tr bgcolor="#000000">';
				
				 $data=$data.'<td style="bgcolor:#000000;color:#FFFFFF;">'.strtoupper($lablearray['317']).'</td>';	
				 $data=$data.'<td style="bgcolor:#000000;color:#FFFFFF;">'.strtoupper($lablearray['898']).'</td>';			
				 $data=$data.'<td style="bgcolor:#000000;color:#FFFFFF;">'.strtoupper($lablearray['900']).'</td>';
				 $data=$data.'<td style="bgcolor:#000000;color:#FFFFFF;">'.strtoupper($lablearray['901']).'</upper></td>';
				 $data=$data.'<td style="bgcolor:#000000;color:#FFFFFF;">'.strtoupper($lablearray['984']).'</upper></td>';
				 $data=$data.'<td style="bgcolor:#000000;color:#FFFFFF;">'.strtoupper($lablearray['985']).'</upper></td>';
				 $data=$data.'<td style="bgcolor:#000000;color:#FFFFFF;">'.strtoupper($lablearray['894']).'</upper></td>';
				 $data=$data.'<td style="bgcolor:#000000;color:#FFFFFF;">'.strtoupper($lablearray['986']).'</upper></td>';
				 $data=$data.' </tr>';
				 $nAmounteecieved = 0;
			 while ($transfers = tep_db_fetch_array($result_query)) {
			 
				 $data=$data.'<tr>';
				 $data=$data.'<td>'.$transfers['transfers_datecreated'].'</td>';	
				 $data=$data.'<td>'.$transfers['transfers_firstname'].'</td>';			
				 $data=$data.'<td>'.$transfers['transfers_lastname'].'</td>';
				 $data=$data.'<td>'.$transfers['transfers_telephone'].'</td>';
				 $data=$data.'<td>'.$transfers['transfers_firstname_rec'].'</td>';
				 $data=$data.'<td>'.$transfers['transfers_lastname_rec'].'</td>';
				 $data=$data.'<td>'.$transfers['transfers_amountoreceive'].'</td>';
				 $transfercodes_status ='';
				$nAmounteecieved = $nAmounteecieved +	$transfers['transfers_amountoreceive'];		
				 
				 
				 switch($transfers['transfercodes_status']){
				 case 'N':
				 	$transfercodes_status = $lablearray['989'];
				 	break;
				 case 'S':
				 	$transfercodes_status =  $lablearray['962'];
					 break;
				 case 'C':
				 	$transfercodes_status =  $lablearray['300'];
					 break;
				 case 'W':
				 	$transfercodes_status =  $lablearray['990'];
					 break;
				 default:
				 	$transfercodes_status = $lablearray['989'];
					 break;
				 }
				 
				 $data=$data.'<td>'.$transfercodes_status.'</td>';
				 $data=$data.' </tr>';
			 
			 
			 }
			 	$data=$data.'<tr >';
				 $data=$data.'<td >'.$lablearray['896'].'</td>';	
				 $data=$data.'<td style="bgcolor:#000000;color:#FFFFFF;"></td>';			
				 $data=$data.'<td style="bgcolor:#000000;color:#FFFFFF;"></td>';
				 $data=$data.'<td style="bgcolor:#000000;color:#FFFFFF;"></td>';
				 $data=$data.'<td style="bgcolor:#000000;color:#FFFFFF;"></td>';
				 $data=$data.'<td style="bgcolor:#000000;color:#FFFFFF;"></td>';
				 $data=$data.'<td >'.$nAmounteecieved.'</td>';
				// $data=$data.'<td></td>';
				 $data=$data.' </tr>';
			 $data=$data.'</table>';
			 
			 $data= utf8_decode($data);			
			
		break;
		case 'RECEIVERECEIPT':
		
			$document->SetLeftMargin('1');
			$document->SetRightMargin('1');	
			$document->SetLeftMargin('10');		
			$document->SetFont('helvetica','', 7);			
			$document->AddPage('L','A6',true);
	
			$result_query = tep_db_query("SELECT withdrawal_receipt FROM ".TABLE_TRANSFERS." t, ".TABLE_WITHDRAWAL." w  WHERE w.transfers_code=t.transfers_code AND w.transfers_code='".tep_db_prepare_input($_GET['TCODE'])."'  AND t.operatorbranches_code='".$_SESSION['operatorbranches_code']."'");
			
			//echo "SELECT receiver_amountrecieved,receiver_exchangerate,(SELECT operatorbranches_name FROM ".TABLE_OPERATORBRANCHES." opb WHERE opb.operatorbranches_code=t.operatorbranches_code)operatorbranches_name,currencies_code,transfers_code,transfers_amountoreceive,(select countries_name FROM ".TABLE_COUNTRIES." c WHERE c.countries_iso_code_3=t.countries_iso_code_3_rec) countries_name_rec,(select countries_name FROM ".TABLE_COUNTRIES." c WHERE c.countries_iso_code_3=t.country_origin) countries_name,transfers_firstname,transfers_amountoreceive,transfers_telephone_rec,transfers_middlename,transfers_lastname,transfers_telephone,transfers_address,transfers_firstname_rec,transfers_middlename_rec,transfers_lastname_rec,transfers_address_rec FROM ".TABLE_TRANSFERS." t  WHERE transfers_code='".tep_db_prepare_input($_GET['TCODE'])."'"; 
			
			$results_array = tep_db_fetch_array($result_query);	
			$document->SetFont('Arial', 'B', 12);
			$document->Write(0, 'CUSTOMER TRANSFER RECEIPT', '', 0, 'R', 6, 0, false, false, 0);
			$document->SetFont('Arial', 'B', 6);
			$document->Write(0, ADDRESS, '', 0, 'R', 1, 0, false, false, 0);
			$document->Write(0,TELEPHONE, '', 0, 'R', 1, 0, false, false, 0);
			$document->Write(0,EMAIL_FROM, '', 0, 'R', 1, 0, false, false, 0);
			$document->SetFont('times', 'B', 12);
			$document->SetFont('Arial', '', 7);
			$document->setPrintFooter(false);

			$imgdata= file_get_contents('images/BUDGESEC.JPG'); //imagecreatefromstring(file_get_contents('images/BUDGESEC.JPG'));
			
			$document->Image('@'.$imgdata,10,3,20,20);
			$document->Ln(10);
			$data = $results_array['withdrawal_receipt'];
			$data = $data."<p><br></p><p><br></p><p><br></p>".$data;
			$data = utf8_decode($data);
			 /*$data=$data.'<table width="100%" cellpadding="0"  bgcolor="#E8E8E8"  cellspacing="1" bordercolor="#999999">';
			  $data=$data.' <tr>';
				 $data=$data.'<td colspan="3">';
				
			 $data=$data.'<table width="100%" border="0" cellpadding="1">';
			   $data=$data.'<tr>';
				 $data=$data.'<td colspan="2"></td>';
			   
				 $data=$data.'<td align="right">';
				 $data=$data.'Subject to terms and conditions';
				 $data=$data.'</td>';
			   $data=$data.'</tr>';
			 $data=$data.'</table>';
			 $data=$data.'</td>';
			   
			  $data=$data.' </tr>';
			    $data=$data.'<tr>';
				$data=$data.' <td colspan="2"></td>';
				 $data=$data.'<td ></td>';
			   
			  $data=$data.' </tr>';
			   $data=$data.'<tr>';
				$data=$data.' <td colspan="2"></td>';
				 $data=$data.'<td ></td>';
			   
			  $data=$data.' </tr>';
				 $data=$data.'<tr>';
				$data=$data.' <td ><h4>SENT BY</h4></td>';
				 $data=$data.'<td ><h4>RECEIVER:</h4></td>';
				 $data=$data.'<td ><h4>TRANSFER DETAILS:</h4></td>';
			   
			  $data=$data.' </tr>';
			   $data=$data.'<tr>';
				 $data=$data.'<td >';
					
					 $data=$data.'<table width="100%" border="0" cellpadding="2" bgcolor="#FFFFFF" >';
					   $data=$data.'<tr>';
						 $data=$data.'<td>Firstname</td>';			
						 $data=$data.'<td><upper><b>'.$results_array['transfers_firstname'].'</b></upper></td>';
					  $data=$data.' </tr>';
						$data=$data.'<tr>';
						 $data=$data.'<td>Middlename</td>';			
						 $data=$data.'<td><upper><b>'.$results_array['transfers_middlename'].'</b></upper></td>';
					   $data=$data.'</tr>';
						$data=$data.'<tr>';
						 $data=$data.'<td>Lastname</td>';			
						 $data=$data.'<td><upper><b>'.$results_array['transfers_lastname'].'</b></upper></td>';
					  $data=$data.' </tr>';
						$data=$data.'<tr>';
						 $data=$data.'<td>Tel</td>';		
						 $data=$data.'<td><upper><b>'.$results_array['transfers_telephone'].'</b></upper></td>';
					   $data=$data.'</tr>';
					   $data=$data.' <tr>';
						 $data=$data.'<td>Address</td>';			
						 $data=$data.'<td>'.$results_array['transfers_address'].'</td>';
					  $data=$data.' </tr>';
						 $data=$data.'<tr>';
						 $data=$data.'<td>Transfer Origin</td>';			
						 $data=$data.'<td>'.$results_array['countries_name'].'</td>';
					  $data=$data.' </tr>';
					 $data=$data.'</table>';
			
				 $data=$data.'</td>';
			   $data=$data.'	<td bgcolor="#FFFFFF">';
				 
					 $data=$data.'<table width="100%" border="0" cellpadding="2" bgcolor="#B8D9E2">';
					   $data=$data.'<tr>';
						 $data=$data.'<td>Firstname</td>';			
						 $data=$data.'<td><b>'.trim($results_array['transfers_firstname_rec']).'</b></td>';
					  $data=$data.' </tr>';
					   $data=$data.' <tr>';
						 $data=$data.'<td>Middlename</td>';			
						 $data=$data.'<td><b>'.trim($results_array['transfers_middlename_rec']).'</b></td>';
					  $data=$data.' </tr>';
						$data=$data.'<tr>';
						 $data=$data.'<td>Lastname</td>';			
						 $data=$data.'<td><b>'.trim($results_array['transfers_lastname_rec']).'</b></td>';
					  $data=$data.' </tr>';
						$data=$data.'<tr>';
						 $data=$data.'<td>Tel</td>';			
						 $data=$data.'<td>'.$results_array['transfers_telephone_rec'].'</td>';
					  $data=$data.' </tr>';
						$data=$data.'<tr>';
						 $data=$data.'<td>Address</td>';			
						 $data=$data.'<td><upper><pre>'.$results_array['transfers_address_rec'].'</pre></upper></td>';
					   $data=$data.'</tr>';
						 $data=$data.'<tr>';
						 $data=$data.'<td></td>';			
						 $data=$data.'<td></td>';
					  $data=$data.' </tr>';
					 $data=$data.'</table>';
				
				$data=$data.'</td>';
				 $data=$data.'<td >';
					
					 $data=$data.'<table width="100%" border="0" cellpadding="2" bgcolor="#FFFFFF" >';
					  $data=$data.' <tr>';
						 $data=$data.'<td>TCode</td>';			
						 $data=$data.'<td>'.$results_array['transfers_code'].'</td>';
					  $data=$data.' </tr>';
					   $data=$data.'<tr>';
						 $data=$data.'<td>Agent Name</td>';			
						 $data=$data.'<td><upper><b>'.$results_array['operatorbranches_name'].'</b></upper></td>';
					  $data=$data.' </tr>';
						$data=$data.'<tr>';
						 $data=$data.'<td>Operator code</td>';			
						 $data=$data.'<td><upper><b>'.$results_array['operatorbranches_code'].'</b></upper></td>';
					   $data=$data.'</tr>';
						$data=$data.'<tr>';
						 $data=$data.'<td>Exchange Rate</td>';		
						 $data=$data.'<td><upper><b>'.$results_array['receiver_exchangerate'].'</b></upper></td>';
					   $data=$data.'</tr>';					  
						 $data=$data.'<tr>';
						 $data=$data.'<td>Transfer destination</td>';			
						 $data=$data.'<td>'.$results_array['countries_name_rec'].'</td>';
					  $data=$data.' </tr>';
					 $data=$data.'</table>';
			
				 $data=$data.'</td>';
			  $data=$data.'</tr>';
			  $data=$data.'<tr>';
				$data=$data.'<td >';
						$data=$data.'<table width="100%" border="0" cellpadding="2" >';
					  $data=$data.'<tr>';
						$data=$data.'<td>Amount</td>';
						$data=$data.'<td><h2>'.$results_array['transfers_amountoreceive'].'</h2>'.$results_array['currencies_code'].'</td>';
					 $data=$data.' </tr>';
					  $data=$data.' <tr>';
						$data=$data.'<td colspan="2">';											
						
						$data=$data.'</td>';
					 $data=$data.' </tr>';
					
					$data=$data.'</table>';
			
				
				$data=$data.'</td>';
			  $data=$data.' <td>';
			   
					$data=$data.'<table width="100%" border="0" cellpadding="0">';
					  $data=$data.'<tr>';
						$data=$data.'<td></td>';
						$data=$data.'<td><h2>'.$results_array['transfers_amountoreceive'].'</h2>'.$results_array['currencies_code'].'</td>';
					  $data=$data.'</tr>';
					  
					  
					  
					$data=$data.'</table>';
			   
			   $data=$data.'</td>';
				$data=$data.'<td bgcolor="#FFFFFF">';
				
						$data=$data.'<table width="100%" border="0" cellpadding="0" bgcolor="#B8D9E2">';
						  $data=$data.'<tr>';
							$data=$data.'<td><h4>TOTAL AMOUNT TO RECIEVE</h4></td>';
						  $data=$data.'</tr>';
						  $data=$data.'<tr>';
							$data=$data.'<td><h2>'.$results_array['receiver_amountrecieved'].'</h2></td>';
						  $data=$data.'</tr>';
						$data=$data.'</table>';
			
				
				$data=$data.'</td>';
			 $data=$data.' </tr>';
			 
			$data=$data.'</table>';			*/
		break;

		
		
		case 'SENDERRECEIPT':
			
			
			$document->SetRightMargin('1');	
			$document->SetLeftMargin('10');		
			$document->SetFont('helvetica','', 7);			
			$document->AddPage('P','A4',true);
			$document->SetAutoPageBreak(false, 2);			
			$lablearray = getlables("906,902,903,904,238,905,240,522,611,667,238,240,271,653,380,1005");
			
			$result_query = tep_db_query("SELECT currencies_code,transfers_code,transfers_amountoreceive,(select countries_name FROM ".TABLE_COUNTRIES." c WHERE c.countries_iso_code_3=t.countries_iso_code_3_rec) countries_name_rec,(select countries_name FROM ".TABLE_COUNTRIES." c WHERE c.countries_iso_code_3=t.countries_iso_code_3) countries_name,transfers_firstname,transfers_amountoreceive,transfers_telephone_rec,transfers_middlename,transfers_lastname,transfers_telephone,transfers_address,transfers_firstname_rec,transfers_middlename_rec,transfers_lastname_rec,transfers_address_rec FROM ".TABLE_TRANSFERS." t  WHERE transfers_code='".$_GET['TCODE']."'"); 
			
			
			$results_array = tep_db_fetch_array($result_query);	
			$document->SetFont('Arial', 'B', 12);
			$document->Write(0, 'TRANSFER RECEIPT', '', 0, 'R', 6, 0, false, false, 0);
			$document->SetFont('Arial', 'B', 6);
			$document->Write(0, ADDRESS, '', 0, 'R', 1, 0, false, false, 0);
			$document->Write(0,TELEPHONE, '', 0, 'R', 1, 0, false, false, 0);
			$document->Write(0,EMAIL_FROM, '', 0, 'R', 1, 0, false, false, 0);
			$document->SetFont('times', 'B', 12);
			//$document->SetFont('Courier', '', 7);
			$document->SetFont('courier', '', 7);
			$document->setPrintFooter(false);
			// Example of Image from data stream ('PHP rules')
///$imgdata = base64_decode('iVBORw0KGgoAAAANSUhEUgAAABwAAAASCAMAAAB/2U7WAAAABlBMVEUAAAD///+l2Z/dAAAASUlEQVR4XqWQUQoAIAxC2/0vXZDrEX4IJTRkb7lobNUStXsB0jIXIAMSsQnWlsV+wULF4Avk9fLq2r8a5HSE35Q3eO2XP1A1wQkZSgETvDtKdQAAAABJRU5ErkJggg==');

// The '@' character is used to indicate that follows an image data stream and not an image file name
//$document->Image('@'.$imgdata);

			$imgdata= file_get_contents('images/BUDGESEC.JPG'); //imagecreatefromstring(file_get_contents('images/BUDGESEC.JPG'));
			
			$document->Image('@'.$imgdata,10,3,20,20);
			$document->Ln(10);
			
		
			 $data='<table width="100%" cellpadding="0"  bgcolor="#E8E8E8"  cellspacing="0" bordercolor="#999999">';
			  $data=$data.' <tr>';
				 $data=$data.'<td colspan="3">';
				
			 $data=$data.'<table width="100%" border="0" cellpadding="2">';
			   $data=$data.'<tr>';
				 $data=$data.'<td colspan="2"></td>';
			   
				 $data=$data.'<td align="right">';
				 $data=$data.'<P>'.$lablearray['902'].'</P>';
				 $data=$data.'</td>';
			   $data=$data.'</tr>';
			 $data=$data.'</table>';
			 $data=$data.'</td>';
			   
			  $data=$data.' </tr>';
			    $data=$data.'<tr>';
				$data=$data.' <td colspan="2"></td>';
				 $data=$data.'<td ></td>';
			   
			  $data=$data.' </tr>';
			   $data=$data.'<tr>';
				$data=$data.' <td colspan="2"></td>';
				 $data=$data.'<td style="font:1 courier;">'.$lablearray['1005'].'<h2>'.$results_array['transfers_code'].'</h2><br></td>';
			   
			  $data=$data.' </tr>';
				 $data=$data.'<tr>';
				$data=$data.' <td colspan="2"><h4>'.$lablearray['903'].'</h4></td>';
				 $data=$data.'<td ><h4>'.$lablearray['904'].'</h4></td>';
			   
			  $data=$data.' </tr>';
			   $data=$data.'<tr>';
				 $data=$data.'<td colspan="2">';
					
					 $data=$data.'<table width="100%" border="0" cellpadding="2" bgcolor="#FFFFFF" >';
					   $data=$data.'<tr>';
						 $data=$data.'<td>'.$lablearray['238'].'</td>';			
						 $data=$data.'<td><upper><b>'.$results_array['transfers_firstname'].'</b></upper></td>';
					  $data=$data.' </tr>';
						$data=$data.'<tr>';
						 $data=$data.'<td>'.$lablearray['905'].'</td>';			
						 $data=$data.'<td><upper><b>'.$results_array['transfers_middlename'].'</b></upper></td>';
					   $data=$data.'</tr>';
						$data=$data.'<tr>';
						 $data=$data.'<td>'.$lablearray['240'].'</td>';			
						 $data=$data.'<td><upper><b>'.$results_array['transfers_lastname'].'</b></upper></td>';
					  $data=$data.' </tr>';
						$data=$data.'<tr>';
						 $data=$data.'<td>'.$lablearray['522'].'</td>';		
						 $data=$data.'<td><upper><b>'.$results_array['transfers_telephone'].'</b></upper></td>';
					   $data=$data.'</tr>';
					   $data=$data.' <tr>';
						 $data=$data.'<td>'.$lablearray['611'].'</td>';			
						 $data=$data.'<td>'.$results_array['transfers_address'].'</td>';
					  $data=$data.' </tr>';
						 $data=$data.'<tr>';
						 $data=$data.'<td>'.$lablearray['667'].'</td>';			
						 $data=$data.'<td>'.$results_array['countries_name'].'</td>';
					  $data=$data.' </tr>';
					 $data=$data.'</table>';
			
				 $data=$data.'</td>';
			   $data=$data.'	<td bgcolor="#FFFFFF">';
				 
					 $data=$data.'<table width="100%" border="0" cellpadding="2" bgcolor="#B8D9E2">';
					   $data=$data.'<tr>';
						 $data=$data.'<td>'.$lablearray['238'].'</td>';			
						 $data=$data.'<td><b>'.trim($results_array['transfers_firstname_rec']).'</b></td>';
					  $data=$data.' </tr>';
					   $data=$data.' <tr>';
						 $data=$data.'<td>'.$lablearray['906'].'</td>';			
						 $data=$data.'<td><b>'.trim($results_array['transfers_middlename_rec']).'</b></td>';
					  $data=$data.' </tr>';
						$data=$data.'<tr>';
						 $data=$data.'<td>'.$lablearray['240'].'</td>';			
						 $data=$data.'<td><b>'.trim($results_array['transfers_lastname_rec']).'</b></td>';
					  $data=$data.' </tr>';
						$data=$data.'<tr>';
						 $data=$data.'<td>'.$lablearray['522'].'</td>';			
						 $data=$data.'<td>'.$results_array['transfers_telephone_rec'].'</td>';
					  $data=$data.' </tr>';
						$data=$data.'<tr>';
						 $data=$data.'<td>'.$lablearray['611'].'</td>';			
						 $data=$data.'<td><upper><pre>'.$results_array['transfers_address_rec'].'</pre></upper></td>';
					   $data=$data.'</tr>';
						 $data=$data.'<tr>';
						 $data=$data.'<td>'.$lablearray['667'].'</td>';			
						 $data=$data.'<td>'.$results_array['countries_name_rec'].'</td>';
					  $data=$data.' </tr>';
					 $data=$data.'</table>';
				
				$data=$data.'</td>';
			  $data=$data.'</tr>';
			  $data=$data.'<tr>';
				$data=$data.'<td >';
						$data=$data.'<table width="100%" border="0" cellpadding="1">';
					  $data=$data.'<tr>';
						$data=$data.'<td>'.$lablearray['271'].'</td>';
						$data=$data.'<td><h2>'.$results_array['transfers_amountoreceive'].'</h2>'.$results_array['currencies_code'].'</td>';
					 $data=$data.' </tr>';
					  $data=$data.' <tr>';
						$data=$data.'<td colspan="2">';
						switch($_SESSION['P_LANG']){							
							case 'EN':
								$charges_name_fieldname='charges_name_en';
							break;
							
							case 'FR':
									$charges_name_fieldname='charges_name_fr';
							break;
							
							case 'SWA':
								$charges_name_fieldname='charges_name_sa';
							break;
							
							case 'SP':
								$charges_name_fieldname='charges_name_sp';
							break;
							
							default:
								$charges_name_fieldname='charges_name_en';
							break;
						}
						
						$charges_query = tep_db_query("SELECT ".$charges_name_fieldname." as name,transfercharges_amount,transfercharges_vat FROM ".TABLE_CHARGES." c,".TABLE_TRANSFERCHARGES." tc WHERE tc.charges_code=c.charges_code AND tc.transfers_code='".$results_array['transfers_code']."'");
						if(tep_db_num_rows($charges_query)>0){
							$data = $data.'<table width="100%" border="0">';							
							while($results_array2 = tep_db_fetch_array($charges_query)){
								$data=$data.'<tr>';
								$data = $data.'<td nowrap>'.$results_array2['name'].'</td><td align="right"><b>'.round($results_array2['transfercharges_amount'],0).'</b></td><td> VAT</td><td align="right"><pre><b>'.round($results_array2['transfercharges_vat'],2).'</b></pre></td>';
								$data=$data.'</tr>';
							}					
							$data=$data.'</table>';
						}
						 $data=$data.'</td>';
					 $data=$data.' </tr>';
					
					$data=$data.'</table>';
			
				
				$data=$data.'</td>';
			  $data=$data.' <td>';
			   
					$data=$data.'<table width="100%" border="0" cellpadding="0">';
					  $data=$data.'<tr>';
						$data=$data.'<td></td>';
						$data=$data.'<td><h2>'.$results_array['transfers_amountoreceive'].'</h2>'.$results_array['currencies_code'].'</td>';
					  $data=$data.'</tr>';
					   $data=$data.'<tr>';
						$data=$data.'<td>'.$lablearray['653'].'</td>';
						$data=$data.'<td></td>';
					 $data=$data.' </tr>';
					  
					  
					$data=$data.'</table>';
			   
			   $data=$data.'</td>';
				$data=$data.'<td bgcolor="#FFFFFF">';
				
						$data=$data.'<table width="100%" border="0" cellpadding="0" bgcolor="#B8D9E2">';
						  $data=$data.'<tr>';
							$data=$data.'<td><h4>'.$lablearray['380'].'</h4></td>';
						  $data=$data.'</tr>';
						  $data=$data.'<tr>';
							$data=$data.'<td><h2>'.$results_array['transfers_amountoreceive'].'</h2>'.$results_array['transfers_amountoreceive'].'</td>';
						  $data=$data.'</tr>';
						$data=$data.'</table>';
			
				
				$data=$data.'</td>';
			 $data=$data.' </tr>';
			 $document->Image('@'.$imgdata,10,115,20,20);
				$data=$data.'</table><p><Br></p><p><Br></p><p><Br></p><p></p>'.$data;
			$data= utf8_decode($data);	

		break;

	$document->AddPage('P','A4',true);										
													
	break;
	
	default:
	break;			
				
}


$document->writeHTML($data, false, false, false, false, '');				
$document->type ='PDF';
//$document->data = $data;
$document->Output('example_011.pdf', 'I');
?>