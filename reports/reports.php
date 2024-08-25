<?php
require('../includes/application_top.php');
//error_reporting(E_ALL & ~E_NOTICE);
//require_once __DIR__ .'/includes/classes/pdf.php';
require_once('../includes/classes/dompdf/lib/html5lib/Parser.php');
require_once('../includes/classes/dompdf/lib/php-font-lib/src/FontLib/Autoloader.php');
require_once('../includes/classes/dompdf/lib/php-svg-lib/src/autoload.php');
/*
 * New PHP 5.3.0 namespaced autoloader
 */
require_once('../includes/classes/dompdf/src/Autoloader.php');
Dompdf\Autoloader::register();

use Dompdf\Adapter\CPDF;
use Dompdf\Dompdf;
use Dompdf\Exception;
$options = new \Dompdf\Options();
$options->set('dpi', 100);
$options->set('isPhpEnabled', TRUE);
$options->set('isHtml5ParserEnabled', true);
//$options->set('isRemoteEnabled', true);

/*NOTES:
 * 
 * 31/12/2016: To much inline styles slow the report to render or vene making it fail to display
 * 
 * 
 * 
 */
class pdf {

	public  $baddHeader = true;
	public  $htmlHeader = '';
    public  $contentHeader = '';
    public  $ccsstyles = '';
	public  $baddFooter = true;
	public  $htmlFooter = '';
	public  static $rtype = 'HTML';
    public $multiplepages =false;
    public static $footnote ='';
	public  $html = '';
	public $content ='';
	private static $instance;
	
	private  function  __construct(){
		
    $this->htmlHeader='<html>
	  <head>
	   <style>
           html *
            {
               font-size: 10pt !important;
               color: #000 !important;
               font-family:"Proxima Nova Regular","Helvetica",Arial,sans-serif;
            }
                       
             @media all {
                                  
                html *
                {
                   font-size: 9pt !important;
                   color: #000 !important;
                   font-family:"Proxima Nova Regular","Helvetica",Arial,sans-serif;
             
                }
                
                 table{
                        border-collapse: collapse;
                        font-size: 9pt !important;
                  }
                  table td {              
                     padding:1px;                                                                            
                     align:right;
                  }   
                  
                            
                                                                                               
            }  
       
              @page {
                @footnote {
                  border-top: 1pt solid black;
                }
              }
            #footer {
              page-break-before: always;
            }

              #header {
                page-break-after: avoid;
                border: 1px solid #EEEEE;
                border-radius: 10px;
                margin:5px;
                }
                .smallheading {
                    font-size:0.9em !important;
                }
              #footer { position: fixed; bottom: -35px; height: 25px; }

                @page:left{
                  @bottom-left {
                    content: "Page " counter(page) " of " counter(pages);
                  }
                }


           .whiteBackground { background-color: #fff; text-align:right; }
           .grayBackground { background-color: red; text-align:right;} 
  
         </style></head><body>';
         $this->htmlHeader='<html><body>' ;
         $this->ccsstyles = $this->htmlHeader;
       
         if($this->baddHeader==true) 
          $this->Header();

         if ($this->baddFooter==true) 
          $this->Footer();
	}
	
	public static function getInstance($r_type='')
	  {
        if($r_type!=""){
            self::$rtype = $r_type;
        }
        
        
        if ( is_null( self::$instance ) )
        {
            self::$instance = new self();
        }

        return self::$instance;
	  }	
	  
	public  function Header(){
            
                
		switch(self::$rtype){
		
		
			case 'PDF':
            if($this->multiplepages){
             $this->htmlHeader='<table width="100%">
				<tr>
					<td><img src="../images/logo1.png" style="width:90px;height:90px;"></td>
					<td align="right">'.NAME_OF_INSTITUTION.'<br>'.ADDRESS.' Telphone </td>
					<td align="right">Printed By: '.$_SESSION['user_username'].' '.date('l \t\h\e jS').' '.date('M').' '.date('Y').'</td>
				</tr>
				</table>';
                                    
                // $this->htmlHeader.='<div id="header">
                // <table width="100%" border="0" cellpadding="0" cellspacing="0" align="center">
				//  <tr>
				// 	<td><img  src="../images/logo1.png" style="width:60%;height:60%;"></td>
				// 	<td align="left" valign="top"><p><h1 style="margin:0px;">'.NAME_OF_INSTITUTION.'</h1><br>'.ADDRESS.'</p><p class="smallheading">Telephone.'.TELEPHONE.'</p></td>
				// 	<td align="left"><p class="smallheading">Printed by: '.$_SESSION['user_username'].'</p><p class="smallheading">Date: '.date('l \t\h\e jS m Y').'</p></td>
				//   </tr>                                 
				// </table>
               // </div>';                            
                              
            }else{

            $this->htmlHeader.='					
                <table width="100%" >
                <tr>
                    <td><img src="../images/logo1.png" style="width:90px;height:90px;"></td>
                    <td align="right">'.NAME_OF_INSTITUTION.'<br>'.ADDRESS.' Telphone.'.TELEPHONE.'</td>
                    <td align="right">Printed By: '.$_SESSION['user_username'].' '.date('l \t\h\e jS').' '.date('M').' '.date('Y').'</td>
                </tr>
                </table>';
                                
                // $this->htmlHeader.='<div >
                // <table width="100%" border="0" cellpadding="0" cellspacing="0" align="center">
				//   <tr>
				// 	<td><img  src="../images/logo1.png" style="width:60%;height:60%;"></td>
				// 	<td align="left" valign="top"><p><h1 style="margin:0px;">'.NAME_OF_INSTITUTION.'</h1><br>'.ADDRESS.'</p><p class="smallheading">Telephone.'.TELEPHONE.'</p></td>
				// 	<td align="left"><p class="smallheading">Printed by: '.$_SESSION['user_username'].'</p><p class="smallheading">Date: '.date('l \t\h\e jS m Y').'</p></td>
				//   </tr>
				// </table></div>';                                  
                            
                }                                                                
				break;
			
			case 'HTML':
                            
                             $this->htmlHeader='<html>
                                    <head>
                                     <style>
                                    html 
                                    {
                                       font-size: 11pt 
                                       color: #000 !important;
                                       font-family:"Proxima Nova Regular","Helvetica",Arial,sans-serif;
                                    }
                                    
                                  @media all {
                                  
                                    html 
                                    {
                                       font-size: 10pt !important;
                                       color: #000 !important;
                                       font-family:"Proxima Nova Regular","Helvetica",Arial,sans-serif;
                                    }
                                    @page {                            
                                         size:A4;
                                         8.5in;
                                        
                                      }
                                      
                                     table{
                                        border-collapse: collapse;
                                      }
                                      table td {              
                                         padding:1px;                                                                            
                                         align:right;
                                      }     
                                     
                                                                                                
                                  }  
                                    
                                    
                                      @page {
                                        @footnote {
                                          border-top: 1pt solid black;
                                        }
                                      }

                                      html 
                                      {
                                         font-size: 10pt !important;
                                         color: #000 !important;
                                         font-family:"Proxima Nova Regular","Helvetica",Arial,sans-serif;
                                      }
                                    #footer {
                                      page-break-before: always;
                                    }

                                      #header {
                                        page-break-after: avoid;
                                        border: 1px solid #cccccc;
                                        border-radius: 10px;
                                        margin:5px 0;
                                        padding:10px;
                                        color:#9b9b9b;
                                    }
                                      #footer { position: fixed; bottom: -35px; height: 25px; }
                                     
                                        @page:left{
                                          @bottom-left {
                                            content: "Page " counter(page) " of " counter(pages);
                                          }
                                        }                                     
                                    .headingBackground { background-color: #cccccc }    
                                   .whiteBackground { background-color: #fff; text-align:right; }
                                   .grayBackground { background-color:#f1f2f3   ; text-align:right;} 

                                   </style></head><body>
                                  ';
                            
                            
        //=============== FOR PRODUCTION                    
//                            
//				$this->htmlHeader.='<div id="header"><table width="100%" border="0" cellpadding="0" cellspacing="0">
//				  <tr>
//					<td><img src="../images/logo1.jpeg" style="width:50%;height:50%;"></td>
//					<td align="left" valign="top"><p><h1 style="margin:0px;">'.NAME_OF_INSTITUTION.'</h1><br>'.ADDRESS.' Telphone.'.TELEPHONE.'</p></td>
//					<td align="left"><p>Printed by: '.$_SESSION['user_username'].'</p><p>Date: '.date('l \t\h\e jS').'</p></td>
//				  </tr>
//				</table></div>';
//	//=============== FOR PRODUCTION			
	//=============== STAGING
                     
                $this->htmlHeader.='<div id="header"><table width="95%" border="0" cellpadding="0" cellspacing="0" align="center">
				  <tr>
					<td><img src="../images/logo1.png" style="width:50%;height:50%;"></td>
					<td align="left" valign="top"><p><h1 style="margin:0px;">'.NAME_OF_INSTITUTION.'</h1><br>'.ADDRESS.' Telphone.'.TELEPHONE.'</p></td>
					<td align="left"><p>Printed by: '.$_SESSION['user_username'].'</p><p>Date: '.date('l \t\h\e jS').'</p></td>
				  </tr>
				</table></div>';
          //=============== STAGING
			break;
			case 'EXCEL':
			
			break;
			
			default:
     //=============== FOR PRODUCTION
//				$this->htmlHeader='<div id="header"><table width="100%" border="0" cellpadding="5" cellspacing="0">
//				  <tr>
//					<td><img src="../images/logo1.jpeg" style="width:50%;height:50%;"></td>
//					<td align="left" valign="top"><p><h1 style="margin:0px;">'.NAME_OF_INSTITUTION.'</h1><br>'.ADDRESS.' Telphone.'.TELEPHONE.'</p></td>
//					<td align="right"><p>Printed by: '.$_SESSION['user_username'].'</p><p>Date: '.date('l \t\h\e jS m Y').'</p></td>
//				  </tr>
//				</table>';
                            
    //=============== FOR PRODUCTION  
                           //=============== STAGING
                $this->htmlHeader='<div id="header">
                <table width="100%" border="0" cellpadding="5" cellspacing="0">
				  <tr>
					<td><img src="../images/logo1.png" style="width:50%;height:50%;"></td>
					<td align="left" valign="top"><p><h1 style="margin:0px;">'.NAME_OF_INSTITUTION.'</h1><br>'.ADDRESS.' Telphone.'.TELEPHONE.'</p></td>
					<td align="right"><p>Printed by: '.$_SESSION['user_username'].'</p><p>Date: '.date('l \t\h\e jS m Y').'</p></td>
				  </tr>
				</table>';
                            
    //=============== STAGING
                               
			break;
			
		
		}
		
	  
	
	}


	public  function Footer(){
        $this->htmlFooter ='<div><footer>'.self::$footnote.'</footer><div id="footer" class="pagenum"></div></div>';           
     }
	
	public  function buildHMTL(){
	               
        if($this->multiplepages){
            $this->content = $this->html.'<div style="page-break-after: always;">'.$this->content.' '.$this->htmlFooter.'</div></body>';
        }else{ 

        // $this->html =$this->htmlHeader.$this->html.'</body></html>';
        
        $this->html = $this->htmlHeader.$this->html.$this->content.$this->htmlFooter.'</body></html>';
        
        //    $this->html = '<html>
        //     <body>
        //     <div id="header"> 
        //     <table width="100%" > 
        //     <tr> <td><img src="../images/logo1.png" style="width:90px;height:90px;"></td> 
        //     <td align="left">
        //     CALTEX <br>Kagga Road, East II Zone, Nansana Town Council, Wakiso District Telphone.</p></td> 
        //     <td align="right">Printed By: System Admin Saturday the 30th Mar 2024</td> </tr> 
        //     </table>
        //      <table width="100%" border="0" cellpadding="2" cellspacing="0" >
        //      <tr><td colspan="4" align="center" style="font-size:28px;">Disbursements</td></tr> 
        //      <tr><td colspan="4" align="center"></td></tr> 
        //      <tr> <td align="left" colspan="2"> Start Date: <b>01/03/2020</b></td> <td align="left" colspan="2"> End Date: <b>30/03/2024</b></td> </tr> 
        //      <tr> <td align="left"> Branch: <b>All</b></td> <td align="left"> Currency: <b>All</b></td> <td align="left"> Client Category 1: <b>All</b></td> <td align="left"></td> </tr> 
        //      <tr> <td align="left"> Loan Officer: <b>All</b></td> <td align="left"> Client Category 2: <b>All</b></td> <td align="left"> Client Category 3: <b>All</b></td> <td align="left"></td> </tr> 
        //      <tr> <td align="left"> Ordered By: <b></b></td> <td align="left"> Loan Category 1: <b>All</b></td> <td align="left"> Loan Category 2: <b>All</b></td> <td align="left"></td> </tr> 
        //      <tr> <td align="left"> Grouped By: <b></b></td> <td align="left"> Product: <b>All</b></td> <td align="left"> Fund: <b>All</b></td> <td align="left"> Geographical Area: <b>All</b></td> </tr> 
        //      <tr> <td align="left"></td> <td align="left"> Bussiness Sector:All</b></td> <td align="left"> Cost Center:All</b></td> <td align="left"></td> </tr>
        //      </table>
            
        //      <table cellspacing="0" cellpadding="1" border="1" width="100%">
        //      <tr><td class="headingBackground"><b>Firstname</b></td><td class="headingBackground"><b>Surname</b></td><td class="headingBackground"><b>Loan Number</b></td><td class="headingBackground"><b>Disbursement Date</b></td><td class="headingBackground"><b>Amount Applied</b></td><td class="headingBackground"><b>Amount Disbursed</b></td></tr>
        //      <tr><td class="graybackground">David</td><td class="graybackground">Katende</td><td class="graybackground">PP/005287</td><td class="graybackground">2020-03-01 18:48:06</td><td class="graybackground">5000.00</td><td class="graybackground">505000.00</td></tr></table></div>
        //      </body></html>';
        
        }



      //  echo htmlspecialchars($this->html);
       // exit();
		return  $this->html;	
	}
	
	public  function displayPDF($content=''){
	
		$dompdf = new Dompdf(); 
       // $dompdf->getOptions()->setChroot('C:\xampp\htdocs\moneybankonline\images'); 
                
        if($this->multiplepages){
            $dompdf->loadHtml($this->content);
            self::buildHMTL('');
        }else{
            // $dompdf->setOptions('isHtml5ParserEnabled', true);
            $dompdf->loadHtml(self::buildHMTL(''));
        }
		 
				
		

                            
		// (Optional) Setup the paper size and orientation
		$dompdf->setPaper('A4', 'Potrait');
		
		// Render the HTML as PDF
		$dompdf->render();
		
		// Get the generated PDF file contents
		$pdf = $dompdf->output();
               
		// Output the generated PDF to Browser
		//$dompdf->stream();
		//$dompdf->stream('my.pdf',array('Attachment'=>1));
		$dompdf->stream('my.pdf',array('Attachment'=>0,'isRemoteEnabled' => true));
	}
	
}

ini_set('max_execution_time', 0);
Common::$connObj = &$Conn;

/* NOTICE:
 * 
 * In cases where report does not display report details:
 * 1. Check thats Report code $_SESSION['rpt'] is passed.
 * 2. Fatal error: Call to a member function get_cellmap() on null. Solution check see if all table have an openig and closing tag
 */
// check see if the report is being requested from on report form

if (isset($_GET['source'])) {

    if ($_GET['source'] == 'EXT') {


        switch ($_GET['rpt']) {

            case 'SAVINTRPTS':
                $sp_parameters[] = array('name' => 'branch_code', 'value' =>$_GET['branch_code']);
                $sp_parameters[] = array('name' => 'user_id', 'value' => $_SESSION['user_id']);
                $sp_parameters[] = array('name' => 'code', 'value' => $_GET['rpt']);
                $sp_parameters[] = array('name' => 'tdate', 'value' => $_GET['date']);                
                $sp_parameters[] = array('name' => 'product_prodid', 'value' => $_GET['product_prodid']);
                $_SESSION['parameters'] = serialize($sp_parameters);
                break;
            
            default:
                break;
        }
    }
}

$_SESSION['rpt'] = isset($_SESSION['rpt']) ? $_SESSION['rpt'] : '';

// get report type
$rtype = isset($_GET['rtype']) ? $_GET['rtype'] : '';

switch ($_SESSION['rpt']) {

    case 'LLCARD':
    case 'MLLCARD':
    case 'RFLLCARD':
        $parameters['code'] = $_SESSION['rpt'];
        break;

    default:       
    
      
        $selected_fields_array = unserialize($_SESSION['report_columns']);
       
        $grouporder = unserialize($_SESSION['grouporder']);

        $db_fields_description = array_flip(unserialize($_SESSION['db_report_columns']));

        $d_esc_grp = '';
        $d_esc_order = '';

        if (isset($grouporder['group_by'])) {
            if ($grouporder['group_by'] != "") {
                $desc_id = $db_fields_description[$grouporder['group_by']];
                Common::getlables($desc_id, "", "", Common::$connObj);
                $d_esc_grp = Common::$lablearray[$desc_id];
            }
        }


        if (isset($grouporder['order_by'])) {
            if ($grouporder['order_by'] != "") {
                $desc_id = $db_fields_description[$grouporder['order_by']];
                Common::getlables($desc_id, "", "",Common::$connObj);
                $d_esc_order = Common::$lablearray[$desc_id];
            }
        }

        $tlansationsids = implode(",", array_flip($selected_fields_array));
        $noflat = unserialize($_SESSION['parameters']);

        $parameters = Common::array_flatten($noflat);
        
        pdf::$footnote = $parameters['footnote'];
        if($parameters['code']=='PLEDGERMULTIPLE'){
           $reports_data = Common::common_sp_call($_SESSION['parameters'], '', Common::$connObj);
       }else{
           $reports_data = Common::common_sp_call($_SESSION['parameters'], 'REPORT', Common::$connObj);   
       }
    
      
        // get report name
        $parameters['RPTNAME'] = 'Report Name';

        $report_sub_fieldlist = unserialize($_SESSION['report_sub_fieldlist']);
      
        break;
}


switch ($parameters['code']) {
    case 'TDRPT': // Time deposit Report
    case 'CLIENTLOANFREQ': //Client Loan cycles report
    case 'PROFITPERPERIOD':
    case 'CLIENTRPTS': // Client Reports
    case 'SAVTILL': // Savings Tillsheet
    case 'SAVBALRPT': // Saving Balances Report
    case 'SAVINTRPTS': // Saving Balances Report
    case 'SAVSTAT': // Saverstatement
    case 'OUTBAL': // Outstandign Balances
    case 'GUARANTORS': // Report on Guarantors
    case 'ARRERPT': // Arrears Report
    case 'PORTRSK':  // Portfolio At Risk
    case 'BREAKPERACC': // Breakdown Per Account
    case 'TRIALB': // Trial Balance
    case 'BALANCESHEET': // Balancesheet
    case 'INCOMEEXP': // Income and Expenditure
    case 'CASHFLOW': // Cashflow
    case 'EQUITYCHA': // Changes in Equity
    case 'DISBURSEMENTS': //
    case 'LOANREP': // Payments made in a period
    case 'PLEDGER': // Personal legder
    case 'DUESLN': // Expected Payments Due Report and Template
    case 'DEBITCREDIT': // Check Debit and Credit
    case 'TRANINPERIOD': // Transactions Made in Period
    case 'PROVISION': // Transactions Made in Period
    case 'INTSAVRPT':// Report on savings Interest
    case 'GETTRAN':
    case 'SMSMESSAGES':
    case 'CHARTOFACCOUNTS':
        
       Common::getlables("483,484,1550,1539,317,1538,1144,1541,1432,1374,1234,1254,1337,1323,1324,463,107,1303,1295,1280,1266,1278,1277,1276,1261,1262,39,43,316,1251,1081,1111,1083,1253,1108,1109,1110,1096,1107,1246,1259,1082,1260,1046", "", "", Common::$connObj);

       reset($selected_fields_array);
      
       Common::getlables($tlansationsids, "", "", Common::$connObj);
      
       require_once('reportheader.php');
      
       // pdf::getInstance()->Footer;
       // pdf::getInstance()->html.='<div id="content" >';
        pdf::getInstance()->html= '<div><table cellspacing="0" cellpadding="1" border="1" width="100%">';
        pdf::getInstance()->html.='<tr>';
        // pretare column headers
        foreach ($selected_fields_array as $key => $val) {

            pdf::getInstance()->html.='<td class="headingBackground"><b>'.Common::$lablearray[$key].'</b>';

            // check see if we have sub-heading in the header
            if (!empty($report_sub_fieldlist[$key])) {
                pdf::getInstance()->html.=' <sub>' . $report_sub_fieldlist[$key] . '</sub>';
            }

            pdf::getInstance()->html.='</td>';
        }
        pdf::getInstance()->html.='</tr>';
        
        // GROUP BY
        // $previousvalue ="";
        $x = 1;
        $previousvalue = "";
        
        $nCount = count($selected_fields_array);
    
        foreach ($reports_data as $dkey => &$dval) {

            // $class = ($x%2 == 0)? 'whiteBackground': 'graybackground';
            $subtotal = '';
            if ($grouporder['group_by'] != "") {
                if ($x == 1 || $previousvalue != $dval[$grouporder['group_by']]) {
                    
                    // check see if row for grouping has ST or GT
                    // this mean that this is the row that has the totals
                    $subtotal = $dval[$grouporder['group_by']];
                   
                    if($x ==1){
                        $previousvalue = $dval[$val];
                    }

                    If ($dval[$grouporder['group_by']] == 'GT') {
                        pdf::getInstance()->html.='<tr>';
                        pdf::getInstance()->html.='<td colspan="' . count($selected_fields_array) . '" style="padding:8px;background:#FFFFFF;" valign="bottom"><b><upper>' . $d_esc_grp . ': ' . $previousvalue . '</upper></b></td>';
                        pdf::getInstance()->html.='</tr>';
                    }
                }
            }

            $class = ($x % 2 == 0) ? 'whiteBackground' : 'graybackground';

            // create records
            pdf::getInstance()->html.='<tr>';
                        
            foreach ($selected_fields_array as $key => $val) {
              
                $alignment = Common::check_if_string_numeric($dval[$val]);
                $alignment = ($alignment) ? "right" : "left";

                if ($subtotal == 'ST' || $subtotal == 'GT') {
                    if ($dval[$val] == 'ST') {
                        $dval[$val] = Common::$lablearray['1324'];
                    }

                    if ($dval[$val] == 'GT') {
                        $dval[$val] = Common::$lablearray['1323'];
                    }
                }
                if ($subtotal == 'ST' || $subtotal == 'GT') {
                    pdf::getInstance()->html.='<td align="' . $alignment . '" class="' . $class . '"><b>' . $dval[$val] . '</b></td>';
                } else {
                    
                    switch($parameters['code']):
                    case 'PLEDGER':
                        if($dval['voucher']=='GUA'):
                            if($val=='descr'){
                               pdf::getInstance()->html.='<td align="' . $alignment . '" class="' . $class . '" colspan="'.$nCount.'">'.Common::$lablearray['1539'].' <b>' . $dval[$val] . '</b></td>';
                            }else{
                              // pdf::getInstance()->html.='<td></td>';
                            }
                        else:
                            pdf::getInstance()->html.='<td align="' . $alignment . '" class="' . $class . '">' . $dval[$val] . '</td>';
                        endif;
                        
                        break;
                    default:
                        
                      //  $theval = Common::validateMySQLDate($dval[$val]);
                       // pdf::getInstance()->html.='<td align="' . $alignment . '" class="' . $class . '">' .($theval)? $dval[$val]:$dval[$val]. '</td>';
                       // pdf::getInstance()->html.='<td  class="' . $class . '">' .($theval==true? Common::changeMySQLDateToPageFormat(trim($dval[$val])):$dval[$val]).'</td>';
                        pdf::getInstance()->html.='<td  class="' . $class . '">' .$dval[$val].'</td>';
                    endswitch;
                        
                    
                }
            }

            pdf::getInstance()->html.='</tr>';

            if ($grouporder['group_by'] != "") {
                $previousvalue = $dval[$grouporder['group_by']];
            }
            $x++;
        }

        pdf::getInstance()->html.='</table></div>';
     //   pdf::getInstance()->html.= pdf::$footnote."</div>";

        break;
        
    case 'PLEDGERMULTIPLE': // Personal legder
        
         Common::getlables("1025,1394,1145,1105,1046,43,317,1144,1208,264,299,1261,1267,316", "", "", Common::$connObj);

        pdf::getInstance()->html = pdf::getInstance($rtype)->ccsstyles;
        
        $reports_data = Common::common_sp_call($_SESSION['parameters'], '', Common::$connObj);
        // get all clients who fall in th details
        
       // $parameters =  unserialize($_SESSION['parameters']);
        $p =1;
        
         pdf::getInstance($rtype)->Header();
         pdf::$rtype ='';
        foreach ($reports_data as $dkey => $dval) {
            
           $parameterscode =  unserialize($_SESSION['parameters']);
           Common::prepareParameters($parameterscode, 'client_idno', $dval['client_idno']);
           Common::updateValuein3DArray($parameterscode, 'code','PLEDGER');
           
           $parameters['client_idno']  =$dval['client_idno'];
           
           $reports_data2 = Common::common_sp_call(serialize($parameterscode), 'REPORT', Common::$connObj); 
          
           // require('reportheader.php');

             pdf::getInstance()->subHeader = '<table width="100%" border="0" cellpadding="2" cellspacing="2"  >
            <tr><td colspan="4" align="center"><h1>'.Common::$lablearray['1046'].'</h1></td></tr>
           <tr><td colspan="4" align="center"><h2 >'.$dval['name'].'('.$dval['client_idno'].')<p>Address:'.$dval['client_addressphysical'].'<p></h2></td></tr></table>';
                     
                     
            // pdf::getInstance()->html.='<div id="content" >';
             pdf::getInstance()->Header();
             pdf::getInstance()->html.=pdf::getInstance()->htmlHeader;
            pdf::getInstance()->html.= pdf::getInstance()->subHeader.'<table cellspacing="2" cellpadding="2" border="1" width="100%">';
            pdf::getInstance()->html.='<tr>';
            $n = 1;
            // pretare column headers
            foreach ($selected_fields_array as $key => $val) {

                pdf::getInstance()->html.='<td style="background:#EEEEEE;color:white;"><b>' . Common::$lablearray[$key] . '</b>';

                // check see if we have sub-heading in the header
                if (!empty($report_sub_fieldlist[$key])) {
                    pdf::getInstance()->html.=' <sub>' . $report_sub_fieldlist[$key] . '</sub></td>';
                }
            }
            pdf::getInstance()->html.='</tr>';

            // GROUP BY
            // $previousvalue ="";
            $x = 1;
            $previousvalue = "";

            $nCount = count($selected_fields_array);

            foreach ($reports_data2 as $dkey => &$dval) {

                    // $class = ($x%2 == 0)? 'whiteBackground': 'graybackground';
                    $subtotal = '';
                    if ($grouporder['group_by'] != "") {
                        if ($x == 1 || $previousvalue != $dval[$grouporder['group_by']]) {

                            $subtotal = $dval[$grouporder['group_by']];

                            if ($x == 1) {
                                $previousvalue = $dval[$val];
                            }
                            //                    If ($dval[$grouporder['group_by']] != 'GT') {
                            //                        pdf::getInstance()->html.='<tr>';
                            //                        pdf::getInstance()->html.='<td colspan="' . count($selected_fields_array) . '" style="padding:10px;background:#FFFFFF;" valign="bottom"><b><upper>' . $d_esc_grp . ': ' . $previousvalue . '</upper></b></td>';
                            //                        pdf::getInstance()->html.='</tr>';
                            //                    }
                        }
                    }

                $class = ($x % 2 == 0) ? 'whiteBackground' : 'graybackground';

                    // create records
                    pdf::getInstance()->html.='<tr>';


                    foreach ($selected_fields_array as $key => $val) {

                        $alignment = Common::check_if_string_numeric($dval[$val]);
                        $alignment = ($alignment) ? "right" : "left";

                            if ($subtotal == 'ST' || $subtotal == 'GT') {
                                if ($dval[$val] == 'ST') {
                                    $dval[$val] = Common::$lablearray['1324'];
                                }

                                if ($dval[$val] == 'GT') {
                                    $dval[$val] = Common::$lablearray['1323'];
                                }
                            }

                        if ($subtotal == 'ST' || $subtotal == 'GT') {
                            pdf::getInstance()->html.='<td align="' . $alignment . '" class="' . $class . '"><b>' . $dval[$val] . '</b></td>';
                        } else {

                            switch ($parameters['code']):
                                case 'PLEDGER':
                                    if ($dval['voucher'] == 'GUA'):
                                        if ($val == 'descr') {
                                            pdf::getInstance()->html.='<td align="' . $alignment . '" class="' . $class . '" colspan="' . $nCount . '">' . Common::$lablearray['1539'] . ' <b>' . $dval[$val] . '</b></td>';
                                        } else {
                                            // pdf::getInstance()->html.='<td></td>';
                                        }
                                    else:
                                        pdf::getInstance()->html.='<td align="' . $alignment . '" class="' . $class . '">' . $dval[$val] . '</td>';
                                    endif;

                                    break;
                                default:
                                    pdf::getInstance()->html.='<td align="' . $alignment . '" class="' . $class . '">' . $dval[$val] . '</td>';
                            endswitch;
                        }
                    }

                    pdf::getInstance()->html.='</tr>';

                    if ($grouporder['group_by'] != "") {
                        $previousvalue = $dval[$grouporder['group_by']];
                    }
               
                    $x++;
               
              
                }
                
              if($rtype=='HTML'):                              
                pdf::getInstance()->html.='</table>';
                pdf::getInstance()->html.="<footer style='page-break-before: always;margin:0px;'>".pdf::$footnote."</footer></div>";
              else:
                 pdf::getInstance()->html.=pdf::getInstance()->Footer();
              endif;

               
            }
                      
           
            
            


        break;
        
    case 'LLCARD':
    case 'RFLLCARD':
        
        if($parameters['code'] =='RFLLCARD'):
            
         else:
            $loan_data_1 = $_SESSION['loan_data'];
            $loan_data = unserialize($loan_data_1);
            $formarvars_1 = $_SESSION['formdata'];
            $formarvars = unserialize($formarvars_1); 
        endif;
    
        // $parameters['code'] ='LLCARD';
        
        pdf::getInstance()->rtype =$rtype;
        Common::getlables("483,484,1045,1443,611,1096,1097,1100,1101,1102,1103,1107,1104,1105,1291,317,264,1144,1145,1105,1181,1356,249,1285,1286,1288,1444
", "", "", Common::$connObj);
        require_once('reportheader.php');
        
        pdf::getInstance()->html.='<table class="celldata" width="100%"> 
        <tr><th>'.Common::$lablearray['317'].'</th><th>'.Common::$lablearray['264'].'</th><th>'.Common::$lablearray['1144'].'</th><th>'.Common::$lablearray['1145'].'</th><th>'.Common::$lablearray['1105'].'</th><th>'.Common::$lablearray['1181'].'</th><th>'.Common::$lablearray['1356'].'</th><th>'.Common::$lablearray['249'].'</th><th>'.Common::$lablearray['1285'].'</th><th>'.Common::$lablearray['1286'].'</th><th>'.Common::$lablearray['1288'].'</th><th>'.Common::$lablearray['1444'].'</th></tr>';

        $nBalance = 0;
        $nPrincipal = 0;
        $nInterest = 0;
        $nPenalty = 0;
        $nTotalBal = 0;

        foreach ($loan_data as $key => $val) {

            $nBalance = $nBalance + $loan_data[$key]['principal'] + $loan_data[$key]['interest'] + $loan_data[$key]['penalty'];
            $nPrincipal = $nPrincipal + $loan_data[$key]['principal'];
            $nInterest = $nInterest + $loan_data[$key]['interest'];
            $nPenalty = $nPenalty + $loan_data[$key]['penalty'];
            pdf::getInstance()->html.='<tr><td class="even">' . $loan_data[$key]['date'] . '</td><td>Installment Due</td><td align="right">' . $loan_data[$key]['principal'] . '</td><td align="right">' . $loan_data[$key]['interest'] . '</td><td>' . $loan_data[$key]['commission'] . '</td><td align="right">' . $loan_data[$key]['penalty'] . '</td><td class="even" align="right"></td><td align="right">' . $nBalance . '</td><td align="right">' . $nPrincipal . '</td><td align="right">' . $nInterest . '</td><td align="right">' . $nPenalty . '</td><td align="right">' . $nBalance . '</td></tr>';
        }
        
        pdf::getInstance()->html.='</table>';
        pdf::getInstance()->html . '</body></html>';
        break;
        
    case 'MLLCARD':
       Common::getlables("1497,1261,1045,1443,611,1096,1097,1100,1101,1102,1103,1107,1104,1105,1291,317,264,1144,1145,1105,1181,1356,1595,1285,1286,1288,1444
", "", "", Common::$connObj);
       $parameters = unserialize($_SESSION['parameters']);
       Common::arrayreplaceValue($parameters,'code','MLLCARD');
       Common::prepareParameters($parameters, 'isdisbursed', '');
       Common::prepareParameters($parameters, 'includewoff', '');
       Common::prepareParameters($parameters, 'client_regstatus', '');
 
    
       $_SESSION['parameters'] = serialize($parameters);
       
       $loan_data = Common::common_sp_call($_SESSION['parameters'], '', Common::$connObj, false);
  
        pdf::getInstance($rtype)->multiplepages= true;
        // det card detailts
      
        foreach ($loan_data as $key => $val) {
            $parameters = array();
            
            Common::prepareParameters($parameters,'loan_number',$loan_data[$key]["loan_number"]);
            Common::prepareParameters($parameters,'members_idno','');
            Common::prepareParameters($parameters,'code','MLLCARDDETAILS'); 
          
            $_SESSION['parameters'] = serialize($parameters);
           
            $loan_card_details = Common::common_sp_call($_SESSION['parameters'], '', Common::$connObj, false);
            pdf::getInstance($rtype)->Header();
            // get report header
            
             pdf::getInstance($rtype)->htmlHeader.='<div class="rounded"><table width="100%" border="0" cellpadding="2" cellspacing="0"  >
            <tr>
                  <td align="left" >'.Common::$lablearray['1443'].': <b>'.$loan_data[$key]['client_firstname'].' '.$loan_data[$key]['client_middlename'].' '.$loan_data[$key]['client_surname'].'</b></td>
                  <td align="left" >'.Common::$lablearray['611'].': <b>'.$loan_data[$key]['client_addressphysical'].'</b></td>
                  <td align="left">'.Common::$lablearray['1097'].': <b>'.$loan_data[$key]["loan_number"]. '</b></td>
                  <td align="left">'.Common::$lablearray['1096'].': <b>'.$loan_data[$key]["product_prodid"]. '</b></td>
            </tr>
             <tr>
                  <td align="left">'.Common::$lablearray['1100'].': <b>' . $loan_data[$key]["loan_tint"] . '</b></td>
                  <td align="left">'.Common::$lablearray['1101'].': <b>' .  $loan_data[$key]["loan_noofinst"] . '</b></td>
                  <td align="left">'.Common::$lablearray['1102'].': <b>' . $loan_data[$key]["loan_insttype"] . '</b></td>
                  <td align="left">'.Common::$lablearray['1103'].': <b>' . $loan_data[$key]["loan_inttype"]. '</b></td>
            </tr >
             <tr>
                  <td align="left">'.Common::$lablearray['1107'].': <b>' . $loan_data[$key]["fund_code"] . '</b></td>
                  <td align="left">'.Common::$lablearray['1104'].': <b>' . $loan_data[$key]["loan_grace"] . '</b></td>
                  <td align="left">'.Common::$lablearray['1105'].': <b>' . $loan_data[$key]["loan_comm"] . '</b></td>
                  <td align="left">'.Common::$lablearray['1291'].': <b>' . $loan_data[$key]['loan_amount'] . '</b></td>
            </tr>
          </table></div>'; 
          
            pdf::getInstance()->html ='<div class="rounded" style="page-break-after: always;"><table cellspacing="0" cellpadding="2" border="1" width="100%" > 
            <tr style="padding:2px;"><th>'.Common::$lablearray['317'].'</th><th>'.Common::$lablearray['264'].'</th><th>'.Common::$lablearray['1144'].'</th><th>'.Common::$lablearray['1145'].'</th><th>'.Common::$lablearray['1105'].'</th><th>'.Common::$lablearray['1181'].'</th><th>'.Common::$lablearray['1356'].'</th><th>'.Common::$lablearray['1595'].'</th><th>'.Common::$lablearray['1285'].'</th><th>'.Common::$lablearray['1286'].'</th><th>'.Common::$lablearray['1288'].'</th><th>'.Common::$lablearray['1444'].'</th></tr>';                        
            foreach ($loan_card_details as $key => $val) {
                pdf::getInstance()->html.='<tr ><td>'.Common::changeMySQLDateToPageFormat($loan_card_details[$key]['tdate']) .'</td><td>'.$loan_card_details[$key]['descrip4'].'</td><td align="right">' . round($loan_card_details[$key]['principal'], SETTTING_ROUND_TO) . '</td><td align="right">' . round($loan_card_details[$key]['interest'], SETTTING_ROUND_TO) . '</td><td>' . round($loan_card_details[$key]['commission'], SETTTING_ROUND_TO) . '</td><td align="right">' . round($loan_card_details[$key]['penalty'], SETTTING_ROUND_TO) . '</td><td class="even" align="right"></td><td align="right">' . $loan_card_details[$key]['ttotal'] . '</td><td align="right">' . $loan_card_details[$key]['bprincipal'] . '</td><td align="right">' . $loan_card_details[$key]['binterest'] . '</td><td align="right">' . $loan_card_details[$key]['bpenalty'] . '</td><td align="right">' . $loan_card_details[$key]['due'] . '</td></tr>';
            }
            
            pdf::getInstance()->html.='</table></div>'; 
            pdf::getInstance()->content.= pdf::getInstance()->ccsstyles.'<div  style="page-break-after: always;" >'.pdf::getInstance()->htmlHeader. pdf::getInstance()->html.'</div>';
            
        }
        pdf::getInstance()->content .='</body></html>';
        

        break;
    
    default:
        break;
}


//$xstr = pdf::getInstance()->html;
//require_once('reportheader.php');
//
////pdf::getInstance()->Header();
//
//pdf::getInstance()->multiplepages= true;
//
//pdf::getInstance()->content= pdf::getInstance()->ccsstyles.'<div  style="page-break-after: always;" class="reportdetails">'.pdf::getInstance()->htmlHeader.$xstr.'</div>';
//pdf::getInstance()->content.='<div style="page-break-after: always;" class="reportdetails"></div><div  >'.pdf::getInstance()->htmlHeader.$xstr.'</div></body></html>';
switch ($rtype) {

    case 'PDF':
    
        pdf::getInstance('')->displayPDF();
        break;
    
    case 'HTML':
        switch ($parameters['code']) {
        case 'PLEDGERMULTIPLE':
           // echo htmlspecialchars(pdf::getInstance()->html);
            
            // header("Content-Type: text/plain");
             echo pdf::getInstance()->html.'</BODY></HTML>';
            break;
        
        default:
          /// echo htmlspecialchars(pdf::getInstance()->htmlHeader);
          // echo htmlspecialchars(pdf::getInstance()->html);
            
            echo pdf::getInstance()->htmlHeader.'</BODY></HTML>';;
            echo pdf::getInstance()->html.'</BODY></HTML>';;
            break;

         }
        break;

    case 'EXCEL':

        header('Content-type: application/excel');
        $filename = 'filename.xls';
        header('Content-Disposition: attachment; filename=' . $filename);

        $data = '<html xmlns:x="urn:schemas-microsoft-com:office:excel">
    <head>
            <!--[if gte mso 9]>
            <xml>
                    <x:ExcelWorkbook>
                            <x:ExcelWorksheets>
                                    <x:ExcelWorksheet>
                                            <x:Name>Sheet 1</x:Name>
                                            <x:WorksheetOptions>
                                                    <x:Print>
                                                            <x:ValidPrinterInfo/>
                                                    </x:Print>
                                            </x:WorksheetOptions>
                                    </x:ExcelWorksheet>
                            </x:ExcelWorksheets>
                    </x:ExcelWorkbook>
            </xml>
            <![endif]-->
    </head>

    <body>
    <table width="100%" border="0" cellpadding="0" cellspacing="0">
                      <tr>
                            <td></td>
                            <td align="left" valign="top"><p><h1 style="margin:0px;">' . NAME_OF_INSTITUTION . '</h1><br>' . ADDRESS . ' Telphone.' . TELEPHONE . '</p></td>
                            <td align="left"><p>Printed By: ' . $_SESSION['user_username'] . '</p><p>Date: ' . date('l \t\h\e jS m Y') . '</p></td>
                      </tr>
                    </table>' .
                pdf::getInstance()->html . '</body></html>';

        echo $data;
        //pdf::getInstance()->displayPDF();	
        break;
    default:
        break;
}
?>