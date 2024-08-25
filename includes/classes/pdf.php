<?php
//require_once('/dompdf/autoload.inc.php');
// require_once __DIR__ . '/dompdf/autoload.inc.php';

/**
 * Dompdf autoload function
 *
 * If you have an existing autoload function, add a call to this function
 * from your existing __autoload() implementation.
 *
 * @param string $class
 */
require_once __DIR__ . 'dompdf/lib/html5lib/Parser.php';
require_once __DIR__ . 'dompdf/lib/php-font-lib/src/FontLib/Autoloader.php';
require_once __DIR__ . 'dompdf/lib/php-svg-lib/src/autoload.php';

/*
 * New PHP 5.3.0 namespaced autoloader
 */
require_once __DIR__ . 'dompdf/src/Autoloader.php';

Dompdf\Autoloader::register();

use Dompdf\Adapter\CPDF;
use Dompdf\Dompdf;
use Dompdf\Exception;
$options = new \Dompdf\Options();
$options->set('dpi', 100);
$options->set('isPhpEnabled', TRUE);
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
        public $footnote ='';        
	public  $html = '';
	public $content ='';
	private static $instance;
	private static $subHeader;
        
	private  function  __construct(){
		
           $this->htmlHeader='<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
               <html>
	  <head>
	   <style>
             @media print {
                footer {page-break-after: always;}
                font-family: "Helvetica,Arial Narrow";
            }
             @page {
                margin: 10pt 0 30pt 0;
                font-family: "Helvetica,Arial Narrow";
                size: A4 Potrait;
                color: #333;
                border-top: .25pt solid #666;
                font-size: 9pt;
              
            }  
          
            #header { position: relative; left: 0px; top: -130px; right: 0px; height:auto;margin:2px;}
            #footer { position: fixed; bottom: -35px; height: 25px; text-align:right;font-family:Arial, Helvetica, sans-serif;}
            #footer .page:after { content: counter(page, upper-roman); }
            .pagenum:before {
                content: counter(page);
             
            }
             
             .rounded{
                padding:1px;
                border:0.9px solid #000000;
                border-radius: 10px 10px 10px 10px;
              
            }
          
            .celldata  table td {              
                padding:2px;                               
                background-color: #EEEEEE;
               font-family: "Helvetica,Arial Narrow";
            }
            
	 .whiteBackground { background-color: #fff; }
          .grayBackground { background-color: #cccccc;} 
            
         </style></head><body>
        ';
            
         $this->ccsstyles = $this->htmlHeader;
       
         if($this->baddHeader==true) 
          $this->Header();

         if ($this->baddFooter==true) 
          $this->Footer();
	}
	
	public static function getInstance($r_type='',$footnote='')
	  {
                self::$rtype = $r_type;
                self::$footnote = $footnote;
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
                                    $this->htmlHeader='<div id="header"><table width="100%">
					  <tr>
						<td><img src="'.DIR_FS_DOCUMENT_ROOT.'/images/logo.png" style="width:90;height:90;"></td>
						<td align="right" valign="top"><div style="border-radius: 30px 0px 0px 30px;margin:0px;padding:5px;background:#000080;color:white"><h1 style="margin:0px;">'.NAME_OF_INSTITUTION.'</h1><br>'.ADDRESS.' Telphone.'.TELEPHONE.'</div></td>
						<td align="right"><div style="border-radius: 1px 30px 30px 1px;margin:0px;padding:5px;background:#EEEEEE;color:white"><p style="color:#00000;">Printed By: '.$_SESSION['user_username'].'</p><p >'.date('l \t\h\e jS m Y').'</p></div></td>
					  </tr>
					</table>';
                            }else{
				$this->htmlHeader.='<div id="header" >					
					<table width="100%"  >
					  <tr>
						<td><img src="'.DIR_FS_DOCUMENT_ROOT.'/images/logo.png" style="width:90px;height:90px;"</td>
						<td align="right" valign="top"><div style="border-radius: 30px 0px 0px 30px;margin:0px;padding:4px;background:#000080;color:white"><h1 style="margin:0px;">'.NAME_OF_INSTITUTION.'</h1><br>'.ADDRESS.' Telphone.'.TELEPHONE.'</div></td>
						<td align="right"><div style="border-radius: 1px 30px 30px 1px;margin:0px;padding:4px;background:#EEEEEE;width:60%;"><p style="color:#00000;">Printed By: '.$_SESSION['user_username'].'</p><p >'.date('l \t\h\e jS m Y').'</p></div></td>
					  </tr>
					</table>';
                            
                            }
                                
                                
				break;
			
			case 'HTML':
				$this->htmlHeader.='<div id="header"><table width="100%" border="0" cellpadding="0" cellspacing="0">
				  <tr>
					<td><img src="'.DIR_FS_DOCUMENT_ROOT.'/images/logo.png" style="width:50%;height:50%;"></td>
					<td align="left" valign="top"><p><h1 style="margin:0px;">'.NAME_OF_INSTITUTION.'</h1><br>'.ADDRESS.' Telphone.'.TELEPHONE.'</p></td>
					<td align="left"><p>Printed by: '.$_SESSION['user_username'].'</p><p>Date: '.date('l \t\h\e jS m Y').'</p></td>
				  </tr>
				</table></div>'.$this->subHeader;
//			
			break;
			case 'EXCEL':
			
			break;
			
			default:
                  
				$this->htmlHeader='<div id="header"><table width="100%" border="0" cellpadding="5" cellspacing="0">
				  <tr>
					<td><img src="../images/logo.png" style="width:50%;height:50%;"></td>
					<td align="left" valign="top"><p><h1 style="margin:0px;">'.NAME_OF_INSTITUTION.'</h1><br>'.ADDRESS.' Telphone.'.TELEPHONE.'</p></td>
					<td align="right"><p>Printed by: '.$_SESSION['user_username'].'</p><p>Date: '.date('l \t\h\e jS m Y').'</p></td>
				  </tr>
				</table>';
                               
			break;
			
		
		}
		
	  
	
	}


	public  function Footer(){
             $this->htmlFooter ='<div id="footer" class="pagenum"></div>';           
                 
	}
	
	
	public  function buildHMTL(){
	
               
                if($this->multiplepages){
                    $this->content = $this->html.'<div style="page-break-after: always;">'.$this->content.'</div>'.$this->htmlFooter.'</body>';
                }else{            
                    $this->html = $this->htmlHeader.$this->html.'</div>'.$this->content.'</div>'.$this->htmlFooter.'</body>
                    </html>';
                }	  
		 return  $this->html;	
	
	}
	
	public  function displayPDF($content=''){
	
		$dompdf = new Dompdf();  
                
                if($this->multiplepages){
                    $dompdf->loadHtml($this->content);
                }else{
                    $dompdf->loadHtml(self::buildHMTL(''));
                }
		
		self::buildHMTL('');
                
                //$dompdf->output(['isRemoteEnabled' => true]);
                        
		// (Optional) Setup the paper size and orientation
		$dompdf->setPaper('A3', 'Potrait');
		
		// Render the HTML as PDF
		$dompdf->render();
		
		// Get the generated PDF file contents
		$pdf = $dompdf->output();
               
		// Output the generated PDF to Browser
		//$dompdf->stream();
		//$dompdf->stream('my.pdf',array('Attachment'=>1));
		$dompdf->stream('my.pdf',array('Attachment'=>0,'isRemoteEnabled' => true));
	}
        
        public function includeHeader($name) {

        // NB. MAKE SURE YOU ARE PASSING THE CORRECT REPORT KEYS AND ELEMENTS FORM FROMS report.php
//        if (isset($parameters['client_idno'])):
//            $clientdetails = Common::getClientDetails($parameters['client_idno']);
//            $name = $clientdetails[0]['name'] . " (" . $parameters['client_idno'] . ")";
//        endif;

        switch ($parameters['code']) {
            case 'INTSAVRPT':
                Common::getlables("1573,296,1096,1145,9", "", "", Common::$connObj);
                $report_db_fieldlist_array['9'] = 'name';
                $parameters['RPTNAME'] = Common::$lablearray['1573'];
                break;

            case 'TRANINPERIOD':
                $parameters['RPTNAME'] = Common::$lablearray['1541'];
                break;

            case 'DEBITCREDIT':
                $parameters['RPTNAME'] = Common::$lablearray['1538'];
                break;

            case 'CLIENTRPTS':
                $parameters['RPTNAME'] = Common::$lablearray['1276'];
                break;

            case 'SAVTILL':
                //Common::getlables("1277","","",$Conn);
                $parameters['RPTNAME'] = Common::$lablearray['1277'];
                break;

            case 'SAVBALRPT':
                //Common::getlables("1278","","",$Conn);
                $parameters['RPTNAME'] = Common::$lablearray['1278'];
                break;

            case 'SAVSTAT':
                $parameters['RPTNAME'] = Common::$lablearray['1266'];
                break;

            case 'OUTBAL':
                $parameters['RPTNAME'] = Common::$lablearray['1280'];
                break;

            case 'ARRERPT':
                $parameters['RPTNAME'] = Common::$lablearray['1295'];
                break;

            case 'PORTRSK':
                $parameters['RPTNAME'] = Common::$lablearray['1303'];
                break;

            case 'BREAKPERACC':
                $parameters['RPTNAME'] = Common::$lablearray['463'];
                break;

            case 'TRIALB':
                $parameters['RPTNAME'] = Common::$lablearray['107'];
                break;

            case 'PROVISION':
                Common::replace_key_function($parameters, 'pDate', 'startDate');
                $parameters['RPTNAME'] = Common::$lablearray['1550'];
                break;

            case 'INCOMEEXP':
                $parameters['RPTNAME'] = Common::$lablearray['1254'];
                break;

            case 'BALANCESHEET':
                $parameters['RPTNAME'] = Common::$lablearray['1337'];
                break;

            case 'DISBURSEMENTS':
                $parameters['RPTNAME'] = Common::$lablearray['1234'];
                break;

            case 'LOANREP':
                $parameters['RPTNAME'] = Common::$lablearray['1374'];
                break;

            case 'PLEDGER':
            case 'PLEDGERMULTIPLE':
                $parameters['RPTNAME'] = Common::$lablearray['1046'] . ' ' . $name;
                break;

            case 'SAVINTRPTS':
                $parameters['RPTNAME'] = Common::$lablearray['1432'];
                break;

            case 'LLCARD':
                $parameters['RPTNAME'] = Common::$lablearray['1045'];
                break;

            case 'DUESLN':
                $parameters['RPTNAME'] = Common::$lablearray['1283'];
                break;

            case 'GUARANTORS':
                $parameters['RPTNAME'] = Common::$lablearray['1497'];
                break;

            default:
                break;
        }


        if (pdf::getInstance($rtype, $parameters['footnote'])->multiplepages) {
//        pdf::getInstance($rtype)->htmlHeader ='<hr style="margin:0px;" ><div style="border: 1px solid #000000;border-radius: 0px 0px 20px 20px;">
//        <table width="100%" border="0" cellpadding="2" cellspacing="0">
//        <tr>
//              <td align="center" colspan="4"><h2 style="padding:3px;background-color:#e6f3ff;"> '.$parameters['RPTNAME'].'</h2></td>  
//
//       </tr>'; 
        } else {
//          pdf::getInstance($rtype)->htmlHeader.='<hr style="margin:0px;" ><div style="border: 1px solid #000000;border-radius: 0px 0px 20px 20px;">
//        <table width="100%" border="0" cellpadding="2" cellspacing="0">
//        <tr>
//              <td align="center" colspan="4"><h2 style="padding:3px;background-color:#e6f3ff;"> '.$parameters['RPTNAME'].'</h2></td>  
//
//       </tr>'; 
        }
        // check which report headers to use
        switch ($parameters['code']) {
            case 'BREAKPERACC':
            case 'TRIALB':
            case 'INCOMEEXP':
            case 'BALANCESHEET':
            case 'DEBITCREDIT':
            case 'TRANINPERIOD':

                pdf::getInstance()->rtype = $rtype;
                pdf::getInstance($rtype)->htmlHeader.='<div><table cellspacing="0" cellpadding="2" border="0" width="100%" ><tr>
          <td align="left">' . Common::$lablearray['1261'] . ':' . ($parameters['startDate'] == '' ? Common::$lablearray['43'] : $parameters['startDate']) . '</td>  
          <td align="left">' . Common::$lablearray['39'] . ':' . ($parameters['endDate'] == '' ? Common::$lablearray['43'] : $parameters['endDate']) . '</td>  
         <td align="left">' . Common::$lablearray['316'] . ':' . ($parameters['branch_codefr'] == '' ? Common::$lablearray['43'] : $parameters['branch_codefr']) . '</td>
        <td align="left">' . Common::$lablearray['316'] . ':' . ($parameters['branch_codeto'] == '' ? Common::$lablearray['43'] : $parameters['branch_codeto']) . '</td> 
       </tr> 
        <tr>    
            <td align="left">' . Common::$lablearray['1251'] . ':' . ($parameters['accountcodefr'] == '' ? Common::$lablearray['43'] : $parameters['accountcodefr']) . '</td>
            <td align="left">' . Common::$lablearray['1251'] . ':' . ($parameters['accountcodeto'] == '' ? Common::$lablearray['43'] : $parameters['accountcodeto']) . '</td>    
             <td align="left">' . Common::$lablearray['1111'] . ':' . ($parameters['trancodes_codefr'] == '' ? Common::$lablearray['43'] : $parameters['trancodes_codefr']) . '</td>
             <td align="left">' . Common::$lablearray['1111'] . ':' . ($parameters['trancodes_codeto'] == '' ? Common::$lablearray['43'] : $parameters['trancodes_codeto']) . '</td>
        </tr>
        <tr>                     
              <td align="left">' . Common::$lablearray['1107'] . ':' . ($parameters['costcenters_codefr'] == '' ? Common::$lablearray['43'] : $parameters['costcenters_codefr']) . '</td>
              <td align="left">' . Common::$lablearray['1082'] . ':' . ($parameters['costcenters_codeto'] == '' ? Common::$lablearray['43'] : $parameters['costcenters_codeto']) . '</td>    
              <td align="left">' . Common::$lablearray['1251'] . ':' . ($parameters['donor_codefr'] == '' ? Common::$lablearray['43'] : $parameters['donor_codefr']) . '</td>
              <td align="left">' . Common::$lablearray['1251'] . ':' . ($parameters['donor_codeto'] == '' ? Common::$lablearray['43'] : $parameters['donor_codeto']) . '</td>  
        </tr>
         <tr>
             <td align="left">' . Common::$lablearray['1096'] . ':' . ($parameters['product_prodidfr'] == '' ? Common::$lablearray['43'] : $parameters['product_prodidfr']) . '</td>
              <td align="left">' . Common::$lablearray['1096'] . ':' . ($parameters['product_prodidto'] == '' ? Common::$lablearray['43'] : $parameters['product_prodidto']) . '</td> 
             <td align="left">' . Common::$lablearray['1111'] . ':' . ($parameters['costcenters_codefr'] == '' ? Common::$lablearray['43'] : $parameters['costcenters_codefr']) . '</td>
              <td align="left">' . Common::$lablearray['1111'] . ':' . ($parameters['costcenters_codeto'] == '' ? Common::$lablearray['43'] : $parameters['costcenters_codeto']) . '</td>
          </tr>
         <tr>
             <td align="left">' . Common::$lablearray['1096'] . ':' . ($parameters['user_idfr'] == '' ? Common::$lablearray['43'] : $parameters['user_idfr']) . '</td>
            <td align="left">' . Common::$lablearray['1096'] . ':' . ($parameters['user_idto'] == '' ? Common::$lablearray['43'] : $parameters['user_idto']) . '</td>  
            <td align="left">' . Common::$lablearray['1111'] . ':' . ($parameters['currencies_id'] == '' ? Common::$lablearray['43'] : $parameters['currencies_id']) . '</td>
             <td align="left"></td>
          </tr>         
      </table></div>';
                break;
            case 'MLLCARD':
                break;
            case 'LLCARD':

                // extra header
                $name = Common::getClientNames($formarvars["client_idno"]);
                if (!isset($formarvars["loan_number"])) {

                    $formarvars["loan_number"] = '';
                    $formarvars["intType"] = '';
                    $formarvars["insType"] = '';
                }

                pdf::getInstance($rtype)->htmlHeader.='<table width="100%" border="0" cellpadding="2" cellspacing="0"  >
          <tr>
                <td align="left" >' . Common::$lablearray['1443'] . $name . '</td>
                <td align="left" >' . Common::$lablearray['611'] . '</td>
                <td align="left">' . Common::$lablearray['1097'] . ': ' . $formarvars["loan_number"] . '</td>
                <td align="left">' . Common::$lablearray['1096'] . ':' . $formarvars["product_prodid"] . '</td>
          </tr>
           <tr>
                <td align="left">' . Common::$lablearray['1100'] . ': ' . $formarvars["intrate"] . '</td>
                <td align="left">' . Common::$lablearray['1101'] . ':' . $formarvars["no_of_inst"] . '</td>
                <td align="left">' . Common::$lablearray['1102'] . ': ' . $formarvars["intType"] . '</td>
                <td align="left">' . Common::$lablearray['1103'] . ': ' . $formarvars["insType"] . '</td>
          </tr >
           <tr>
                <td align="left">' . Common::$lablearray['1107'] . ': ' . $formarvars["fund_code"] . '</td>
                <td align="left">' . Common::$lablearray['1104'] . ': ' . $formarvars["grace"] . '</td>
                <td align="left">' . Common::$lablearray['1105'] . ': ' . $formarvars["comm"] . '</td>
                <td align="left">' . Common::$lablearray['1291'] . ': ' . $formarvars['lamount'] . '</td>
          </tr>
        </table>';


                break;
            case 'SAVINTRPTS':
                break;

            case 'PLEDGER':
            case 'PLEDGERMULTIPLE':
            case 'PROVISION':
                pdf::getInstance($rtype)->htmlHeader.='<div class="rounded"><table width="100%" border="0" cellpadding="2" cellspacing="0"  >
         <tr><td colspan="4" align="center"><h2>' . $parameters['RPTNAME'] . '</h2></td></tr>
        <tr>
          <td align="left"> ' . Common::$lablearray['1261'] . ': <b>' . ($parameters['startDate'] == '' ? Common::$lablearray['43'] : $parameters['startDate']) . '</b></td>  
          <td align="left"> ' . Common::$lablearray['316'] . ': <b>' . ($parameters['branch_code'] == '' ? Common::$lablearray['43'] : $parameters['branch_code']) . '</b></td>
         <td align="left"> </td>
         <td align="left"></td>        
        </tr>     
        </table></div>';
                break;
            default:

                pdf::getInstance($rtype)->htmlHeader.='<div class="rounded"><table width="100%" border="0" cellpadding="2" cellspacing="0"  >
         <tr><td colspan="4" align="center"><h2>' . $parameters['RPTNAME'] . '</h2></td></tr>
        <tr>
          <td align="left"> ' . Common::$lablearray['1261'] . ': <b>' . ($parameters['startDate'] == '' ? Common::$lablearray['43'] : $parameters['startDate']) . '</b></td>  
          <td align="left"> ' . Common::$lablearray['316'] . ': <b>' . ($parameters['branch_code'] == '' ? Common::$lablearray['43'] : $parameters['branch_code']) . '</b></td>
         <td align="left"> ' . Common::$lablearray['1251'] . ': <b>' . ($parameters['currencies_id'] == '' ? Common::$lablearray['43'] : $parameters['currencies_id']) . '</b></td>
         <td align="left"> ' . Common::$lablearray['1081'] . ': <b>' . ($parameters['client1_code'] == '' ? Common::$lablearray['43'] : $parameters['client1_code']) . '</b></td>        
        </tr>
         <tr>
             <td align="left"> ' . Common::$lablearray['39'] . ': <b>' . ($parameters['endDate'] == '' ? Common::$lablearray['43'] : $parameters['endDate']) . '</b></td>  
              <td align="left"> ' . Common::$lablearray['1111'] . ': <b>' . ($parameters['user_id'] == '' ? Common::$lablearray['43'] : $parameters['user_usercode']) . '</b></td>
              <td align="left"> ' . Common::$lablearray['1083'] . ': <b>' . ($parameters['client2_code'] == '' ? Common::$lablearray['43'] : $parameters['client2_code']) . '</b></td>  
              <td align="left"> ' . Common::$lablearray['1253'] . ': <b>' . ($parameters['client3_code'] == '' ? Common::$lablearray['43'] : $parameters['client3_code']) . '</b></td>  
        </tr>

         <tr>  
               <td align="left"> ' . Common::$lablearray['1260'] . ': <b>' . $d_esc_order . '</b></td>   
               <td align="left"> ' . Common::$lablearray['1108'] . ': <b>' . (isset($parameters['loancategory1_code']) ? Common::$lablearray['43'] : $parameters['loancategory1_code']) . '</b></td>  
               <td align="left"> ' . Common::$lablearray['1109'] . ': <b>' . (isset($parameters['loancategory2_code']) ? Common::$lablearray['43'] : $parameters['loancategory2_code']) . '</b></td>
               <td align="left"></td>  
        </tr>
         <tr>
             <td align="left"> ' . Common::$lablearray['1262'] . ': <b>' . $d_esc_grp . '</b></td>  
              <td align="left"> ' . Common::$lablearray['1096'] . ': <b>' . ($parameters['product_prodid'] == '' ? Common::$lablearray['43'] : $parameters['product_prodid']) . '</b></td>
              <td align="left"> ' . Common::$lablearray['1107'] . ': <b>' . ($parameters['fund_code'] == '' ? Common::$lablearray['43'] : $parameters['fund_code']) . '</b></td>
              <td align="left"> ' . Common::$lablearray['1246'] . ': <b>' . ($parameters['areacode_code'] == '' ? Common::$lablearray['43'] : $parameters['areacode_code']) . '</b></td>       
        </tr>
        <tr>  
             <td align="left"></td>  
              <td align="left"> ' . Common::$lablearray['1259'] . ':' . ($parameters['bussinesssector_code'] == '' ? Common::$lablearray['43'] : $parameters['bussinesssector_code']) . '</b></td>
              <td align="left"> ' . Common::$lablearray['1082'] . ':' . ($parameters['costcenters_code'] == '' ? Common::$lablearray['43'] : $parameters['costcenters_code']) . '</b></td>   
              <td align="left"></td>
        </tr></table></div>';
                break;
        }
    }

}?>