<?php
require_once('includes/application_top.php');
require_once('includes/classes/GibberishAES.php');
require_once('includes/classes/productconfig.php');
require_once('includes/classes/financial_class.php');
error_reporting(E_ALL ^ E_NOTICE);

//// Require the bundled autoload file - the path may need to change
// based on where you downloaded and unzipped the SDK
//require __DIR__ . '/twilio-php-master/Twilio/autoload.php';
//use Twilio\Rest\Client;
      
//response.setHeader("Content-Type", "application/json; charset=ISO-8859-1");
// 19/04/2017
// Note. variable :$Option Has been depleted
// Please use alines direct on the function
// See Case: frmSave
$newgrid = new Grid;

$newgrid->Conn = $Conn;
// prepare XML datafrmcashentries
//frl
Common::$connObj = &$Conn;
// $grid_lables_lablearray = getlables("32,33,34,69,244,245,272,273,605,272,273,697,960,963,964,965,962,970");

# default links for the grid on the page
$frmid = $_POST['frmid'];

global $fieldlist_array;

$fieldlist_array = array();

// access auhtentication
/*  if(!isset($_POST['ignoreops'])){ // set ignoreops if you want this authentication to be ignored
  switch($_POST['action']){

  case 'add':
  if(AuthenticateAction('ADD')=='0'){
  echo informationUpdate("fail",$grid_lables_lablearray['697'],"");
  exit();
  }
  break;

  case 'edit':
  if(AuthenticateAction('EDIT')=='0'){
  echo informationUpdate("fail",$grid_lables_lablearray['697'],"");
  exit();
  }
  break;

  case 'delete':
  if(AuthenticateAction('DELETE')=='0'){
  echo informationUpdate("fail",$grid_lables_lablearray['697'],"");
  exit();
  }
  break;

  case 'update':
  if(AuthenticateAction('UPDATE')=='0'){
  echo informationUpdate("fail",$grid_lables_lablearray['697'],"");
  exit();
  }
  break;

  case 'search':
  if(AuthenticateAction('SEARCH')=='0'){
  echo informationUpdate("fail",$grid_lables_lablearray['697'],"");
  exit();
  }
  break;

  default:
  break;
  }
  }
 */
spl_autoload_register(function ($class_name) {
    include 'includes/classes/' . $class_name . '.php';
});

Bussiness::$Conn = $Conn;
// some forms dont need data decoding
switch ($_POST['frmid']) {

case 'frmmanagetransactions':
break;
default:
    if (isset($_POST['pageparams'])) {
         $objects = (array) json_decodeData($_POST['pageparams'], true);

        if (isset($objects['pageinfo'])) {            
            $array1 = Common::convertobjectToArray($objects['pageinfo']);
            $formdata = Common::array_flatten($array1);
        } else {
            $array1 = Common::convertobjectToArray($objects);
            $formdata = Common::array_flatten($array1);
        }

        if ($_POST['action'] == 'SERIALIZE') {

            //$results = Bussiness::$Conn->SQLGenerateReport('');
            //   

            $arr = $objects['pageinfo'];

            $report_selected_fieldlist_array = array();
            array_walk_recursive($arr, function($v, $k) use($key, &$report_selected_fieldlist_array) {
                if ($k == 'value')
                    array_push($report_selected_fieldlist_array, $v);
            });
            unset($_SESSION['report_selected_fieldlist_array']);
            $_SESSION['report_selected_fieldlist_array'] = serialize($report_selected_fieldlist_array);
            exit();
        }
    }
}

Common::$lablearray['E01'] = '';
    switch ($_POST['frmid']) {
    case 'frmsms':
      Common::getlables("1617,1618", "", "", $Conn);
      switch($_POST['action']){
      case 'sendall':
          if($formdata['modems']==''):                
            echo 'ERR ' . Common::$lablearray['1618'];
            exit(); 
          endif;
           $sms_results = Common::$connObj->SQLSelect("SELECT devicemessage_id,tel,devicemessage_msg FROM " .TABLE_DEVICEMESSAGE. " WHERE devicemessage_status='Q' AND tel!='' ORDER BY devicemessage_date DESC");
           
           $nsms = $formdata['txtNumSMS']; 
           
           if ($nsms==0):
             $nsms = 0; 
           endif;
           
           $ncount = 0;
           
           foreach($sms_results As $thekey=>$theval):
               
               $ncount++;
           
               if($ncount > $nsms && $nsms!=0):
                   break;
               endif;
               
               if($theval['tel']!=''):
                  // Sms::send($theval['tel'],$theval['devicemessage_msg'],$theval['devicemessage_id'],$formdata['modems']);
                   Sms::send($theval['tel'],$theval['devicemessage_msg'],$theval['devicemessage_id'],$formdata['modems']);
                  
                   $Conn->Query("UPDATE ".TABLE_DEVICEMESSAGE." SET devicemessage_status='S' WHERE devicemessage_id='".$theval['devicemessage_id']."'" );
                  
               else:
                   $ncount--;
               endif;
               
               sleep(1);

           endforeach;
           
           echo 'INFO.' . Common::$lablearray['1617']." ". $ncount." messages sent.";
           
           exit();
           
           break;
        case 'delete':
             $Conn->SQLDelete(TABLE_DEVICEMESSAGE, 'devicemessage_id', $formdata['theid'],$formdata['modems']);
            break;
        case 'add': 
            
            if($formdata['modems']==''):                
                echo 'ERR ' . Common::$lablearray['1618'];
               exit(); 
            endif;
            
            $device_results = Common::$connObj->SQLSelect("SELECT modem_id FROM " .TABLE_MODEM. " WHERE modem_port='".$formdata['modems']."'");
           
            if (Sms::send($formdata['txtNumber'],$formdata['txtMessage'],$formdata['theid'],$formdata['modems'])):
                
                Bussiness::PrepareData(true);

                // Bussiness::$Conn->endTransaction();            
                echo "MSG "."Message was successfully sent";      
                
            else:
                 echo "MSG "."Failed to sent message"; 
            endif;
            
            break;
            
        default:
            $slnr ='';
            // GET SELECTED CLIENTS
            $selectedloans  = preg_grep ('/^grid_checkbox(\w+)/i', array_keys($formdata));

            if(count($selectedloans)>0):

                  foreach ($selectedloans as $key => $loan_number) {                   
                       $lnr_array[] = $formdata[$loan_number];
                  }                     

                  $slnr = implode(",", $lnr_array);


            endif;

        Common::prepareParameters($parameters, 'code', 'SMS'); 
        Common::prepareParameters($parameters, 'acode', $formdata['areacode_code']); 
        Common::prepareParameters($parameters, 'branch_code', $formdata['branch_code']);
        Common::prepareParameters($parameters, 'asatdate', Common::changeDateFromPageToMySQLFormat($formdata['txtDate']));   
        Common::prepareParameters($parameters, 'product_prodid', $formdata['product_prodid']); 
        Common::prepareParameters($parameters, 'n_days', $formdata['txtnDays']);
        Common::prepareParameters($parameters, 'name', '');  
        Common::prepareParameters($parameters, 'loannumbers',$slnr); 
     
        $results = Common::common_sp_call(serialize($parameters), '', Common::$connObj, false); 
        
      
            getlables("1610"); 
            echo "MSG:" .$results[0]['msg'].$lablearray['1610'];
            break;
         }
        break;

    case 'frmreportsui':
        Common::prepareParameters($parameters, 'code', 'PROVISION');  
        Common::prepareParameters($parameters, 'vpost', '1');  
        Common::prepareParameters($parameters, 'pDate', Common::changeDateFromPageToMySQLFormat($formdata['pDate']));
        Common::prepareParameters($parameters, 'branch_code', $formdata['branch_code']);
        Common::prepareParameters($parameters, 'product_prodid', $formdata['product_prodid']);
        Common::prepareParameters($parameters, 'class1b', $formdata['class1b']);
        Common::prepareParameters($parameters, 'class2b', $formdata['class2b']);
        Common::prepareParameters($parameters, 'class3b', $formdata['class3b']);
        Common::prepareParameters($parameters, 'class4b', $formdata['class4b']);
        Common::prepareParameters($parameters, 'class5a', $formdata['class5a']);
        Common::prepareParameters($parameters, 'class1per', $formdata['class1per']);
        Common::prepareParameters($parameters, 'class2per', $formdata['class2per']);
        Common::prepareParameters($parameters, 'class2per', $formdata['class2per']);
        Common::prepareParameters($parameters, 'class3per', $formdata['class3per']);
        Common::prepareParameters($parameters, 'class4per', $formdata['class4per']);
        Common::prepareParameters($parameters, 'class5per', $formdata['class5per']);      
        $results = Common::common_sp_call(serialize($parameters), '', Common::$connObj, true);   
        
        if($results['id']=='2'):
            getlables("772");       
            echo "MSG:" . $lablearray['772'].' '.$formdata['product_prodid'];
            exit();
        endif;
               
        if($results['id']=='3'):
            getlables("1554");       
            echo "MSG:" . $lablearray['1554'].' '.$formdata['product_prodid'];
            exit();
        endif;
        
        echo "1111111";         
        break;   

    case 'frmmanagetransactions':
        switch($_POST['action']){
        case 'reverse':
            
            $theid = Common::tep_db_prepare_input($_POST['theid']);

            if ($theid == "") {
                getlables("1339");
                echo "MSG:" . $lablearray['1339'];
                exit();
            }
            
            Common::reverseTransaction(array($theid),'A', $_SESSION['user_id'], $Conn);
            //Common::reverseTransaction(array($theid), 'A', $_SESSION['user_id'], $Conn);
            break;
        default:  
           

            if(!isset($_POST['pageparams'])){
                getlables("1748");
                echo "MSG:" . $lablearray['1748'];
                exit();
            }

            $formdata = $_POST['pageparams'];

            if(count($formdata)>0){
              
                 Common::getlables("1193,1529", "", "", $Conn);

                $tcode = Common::generateTransactionCode($_SESSION['user_id']);

                foreach ($formdata as $key => &$value) {
                    
                    
                    Common::replace_key_function($value, 'date', 'DATE');
                    
                    $value['DATE'] = Common::changeDateFromPageToMySQLFormat($value['DATE']);
                   
                    Common::replace_key_function($value, 'tcode', 'TCODE');
                    
                    $value['TCODE'] = $tcode;
                    
                    Common::replace_key_function($value, 'accountcode', 'GLACC');
                    Common::replace_key_function($value, 'voucher', 'VOUCHER');
                    Common::replace_key_function($value, 'debit', 'DEBIT');
                    Common::replace_key_function($value, 'credit', 'CREDIT');
                    Common::replace_key_function($value, 'currencies', 'CURRENCIES_ID');
                    Common::replace_key_function($value, 'description', 'DESC');
                    Common::replace_key_function($value, 'bcode', 'BRANCHCODE');
                    Common::replace_key_function($value, 'trcode', 'TRANCODE');
                    Common::replace_key_function($value, 'costcenters', 'CCODE');
                                        
                    if ($value['CURRENCIES_ID']==""):
                       echo 'ERR '.Common::$lablearray['1529']; 
                       exit();
                    endif;

                    $forexrates_id = 0;

                    // check see if user is transacting in foregn currency	
                    if (SETTTING_CURRENCY_ID != $value['CURRENCIES_ID']) {
                           
                            $ex_rate_array = Common::getExchangeRate($data_acc['CURRENCIES_ID'],$value['DATE']);
                           
                            if(Common::$lablearray['E01']!=""):
                                echo 'MSG.'.Common::$lablearray['E01'];                              
                                exit();
                            endif;
                            
                            $forexrates_id = $ex_rate_array['forexrates_id'];
                            
                            $ex_rate = $ex_rate_array['forexrates_midrate'];

                            if ($ex_rate == "" || $ex_rate == 0) {
                               
                                echo 'ERR '. self::$lablearray['1193'].' '.$value['CURRENCIES_ID'];                              
                                break 2;
                                
                            }
                            
                            Common::addKeyValueToArray($value, 'FCAMT', $forexrates_id);
                        } else {

                            $ex_rate = 1;
                        }
                    
                        Common::addKeyValueToArray($value, 'FXID', $forexrates_id);
                        
                        $value['DEBIT'] = $value['DEBIT'] * $ex_rate;
                        
                        $value['CREDIT'] = $value['CREDIT'] * $ex_rate;
                        
                        if($ex_rate > 1){
                           $ex_rate = 1;                            
                        }else{
                          $ex_rate = 0;  
                        }
                     
                    $value['FCAMT'] = ($value['DEBIT'] * $ex_rate)+ ($value['CREDIT'] * $ex_rate);  
                    
                 
                    Common::addKeyValueToArray($value, 'TABLE', TABLE_GENERALLEDGER);
                    Common::addKeyValueToArray($value,'FUNDCODE', (isset($value['FUNDCODE']) ? $value['FUNDCODE'] : '0000' ));
                    Common::addKeyValueToArray($value,'DONORCODE', (isset($value['DONORCODE']) ? $value['DONORCODE'] : '0000' ));
                    Common::addKeyValueToArray($value,'CURRENCIES_ID', (isset($value['CURRENCIES_ID']) ? $value['CURRENCIES_ID'] : '' ));
                    Common::addKeyValueToArray($value,'PRODUCT_PRODID', (isset($value['PRODUCT_PRODID']) ? $value['PRODUCT_PRODID'] : '' ));
                    Common::addKeyValueToArray($value,'CCODE', (isset($value['CCODE']) ? $value['CCODE'] : '' ));
                   
                   
                    
                }
                Bussiness::$Conn->setAutoCommit();                
                Bussiness::$Conn->beginTransaction(); 
              
                Bussiness::covertArrayToXML($formdata, true);                
                //$tabledata['xml_data'] = Common::$xml;
                // save 
                Bussiness::PrepareData(true);
                
               // Bussiness::$Conn->endTransaction();
            }
           
            break;
        }
        echo "1111111"; 
        break;
       
    case 'frmfees':

        if ($formdata['txtDate'] == "") {
            Common::getlables("1200", "", "", $Conn);
            echo 'ERR ' . Common::$lablearray['1200'];
            exit();
        }

        Common::replace_key_function($formdata, 'PAYMODES', 'MODE');

        Common::replace_key_function($formdata, 'txtlnr', 'LNR');

        $parameters = array();

        Common::prepareParameters($parameters, 'theid1', $formdata['LNR']);
        Common::prepareParameters($parameters, 'theid2', '');
        Common::prepareParameters($parameters, 'code', 'IDEXISTS');
        Common::prepareParameters($parameters, 'idtype', 'LOANNO');
        Common::prepareParameters($parameters, 'branch_code', Common::extractBranchCode($formdata['LNR']));
        $loan_array = Common::common_sp_call(serialize($parameters), '', $Conn, true);

        if ($loan_array['lnr'] == "") {
            Common::getlables("1346", "", "", $Conn);
            echo 'ERR ' . Common::$lablearray['1346'];
            exit();
        }
        $formdata['txtDate'] = Common::changeDateFromPageToMySQLFormat($formdata['txtDate']);
        Common::replace_key_function($formdata, 'txtDate', 'DATE');
        Common::replace_key_function($formdata, 'SAVPROD', 'SPRODID');
        Common::replace_key_function($formdata, 'txtAmount', 'AMOUNT');

        if ($formdata['MODE'] == 'SA') { // Pay from Savings
            if ($formdata['SPRODID'] == "") {
                Common::getlables("1435", "", "", $Conn);
                echo 'ERR ' . Common::$lablearray['1435'];
                exit();
            }

            // get savings account
            $accounts_array = Common::getSavingsAccountForProductNoNames($loan_array['cid'], $formdata['SPRODID'], "S");

            // check see if account exists
            if ($accounts_array[0]['savaccounts_account'] == "") {
                Common::getlables("1510", "", "", $Conn);
                echo 'ERR ' . Common::$lablearray['1510'];
                exit();
            }
            
            $formdata['SAVACC'] = $accounts_array[0]['savaccounts_account'];

            // get savings Balances
             Savings::$prodid = $formdata['SPRODID'];
             Savings::$asatdate = $formdata['DATE'];
             Savings::$savacc = $accounts_array[0]['savaccounts_account'];
         
            // check if balances are sufficient
            if(Savings::getSavingsBalance($formdata['AMOUNT'])){                
           
                Common::getlables("1216", "", "", $Conn);          
                echo 'ERR '.Common::$lablearray['1216'].' '.$accounts_array['savaccounts_account'];
                exit();            
             
            }
        }

        $ctype = Common::getClientType($loan_array['cid']);

        Common::addKeyValueToArray($formdata, 'CTYPE', $ctype);
        Common::addKeyValueToArray($formdata, 'PRODUCT_PRODID', $loan_array['prodid']);
        Common::addKeyValueToArray($formdata, 'FUNDCODE', $loan_array['fcode']);
        Common::addKeyValueToArray($formdata, 'DONORCODE', $loan_array['dcode']);

        $formdata['client_idno'] = $loan_array['cid'];


        Common::replace_key_function($formdata, 'client_idno', 'CLIENTIDNO');


        Common::replace_key_function($formdata, 'txtvoucher', 'VOUCHER');

        Common::replace_key_function($formdata, 'cashaccounts_code', 'GLACC');


        $formdata['TCODE'] = Common::generateTransactionCode($_SESSION['user_id']);
        $formdata['AMOUNT'] = Common::number_format_locale_compute($formdata['AMOUNT']);

        Common::getlables("1105,1203,311,1213", "", "", $Conn);
        
        $form_data = array($formdata);
        
        Loan::updateLoan($form_data, 'LC');
        if (Common::$lablearray['E01'] != "") {
            echo 'MSG ' . Common::$lablearray['E01'];
            exit();
        }
        echo '1111111';
        
        break;
    case 'frmmodemsettings':
        
        Common::getlables("1609", "", "", $Conn);
        
        $modem_results = $Conn->SQLSelect("SELECT modem_port FROM " .TABLE_MODEM. " WHERE modem_port='" . $formdata['txtPort'] . "'");
       
        if(isset($modem_results[0]['modem_port']) && $formdata['action']=='add'):
            
            if($modem_results[0]['modem_port']==$formdata['txtPort']):
                echo 'MSG ' . Common::$lablearray['1609'];
                exit();  
            endif;
              
        endif;

        Common::replace_key_function($formdata, 'theid', 'MID');
        Common::replace_key_function($formdata, 'txtDevice', 'DNAME');
        Common::replace_key_function($formdata, 'txtPort', 'PORT');
        Common::replace_key_function($formdata, 'cmbBitsPerSecond', 'BPS');
        Common::replace_key_function($formdata, 'action','ACTION');
        
        $form_data = array($formdata);
        
        Modem::updateModem($form_data);
        
        if (Common::$lablearray['E01'] != "") {
            echo 'MSG ' . Common::$lablearray['E01'];
            exit();
        }
        
        echo '1111111';
        
        break;
    case 'frmimportdata':
        
        Loan::$isBulkInsert = true;
        
        $data_code = Common::tep_db_prepare_input($_POST['data_code']);

        if (isset($_POST['frmid'])) {
            $filename = $_FILES["upload_field"]["name"];
            $ext = Common::getFileExtension($filename);
            Common::getlables("1346,1347,218", "", "", $Conn);


            if ($ext[1] == '') {
                echo '<div class="w2ui-error">' . Common::$lablearray['1346'] . '</div>';
                exit();
            }

            if ($_POST['data_code'] == '') {
                echo '<div class="w2ui-error">' . Common::$lablearray['1347'] . '</div>';
                exit();
            }

            if ($ext[1] != 'csv') {
                echo '<div class="w2ui-error">' . Common::$lablearray['1346'] . '</div>';
                exit();
            }
        }
        if ($data_code == '') {
            echo '<div class="w2ui-error">' . Common::$lablearray['1347'] . '</div>';
            exit();
        }

        $handle = fopen($_FILES["upload_field"]["tmp_name"], "r");

   
       $Conn->setAutoCommit();

        Loan::$connObj = $Conn;

        switch ($data_code) {
            case 'LOANREPAY':
                break;
            case 'DOC':            
                $values_to_post = $Conn->preparefieldList(TABLE_DOCUMENT, true);
                 break;

            case 'IND':            
                $values_to_post = $Conn->preparefieldList(TABLE_CLIENTS, true);
                break;
            
            case 'GRP':
            case 'BUS':
                $values_to_post = $Conn->preparefieldList(TABLE_ENTITY, true);
                break;
            
            case 'GRPMEM':
                $values_to_post = $Conn->preparefieldList(TABLE_MEMBERS, true);
                break;

            case 'SAVETRAN':
                //$values_to_post_savta = $Conn->preparefieldList(TABLE_SAVTRANSACTIONS, true);
               // $values_to_post_gl = $Conn->preparefieldList(TABLE_GENERALLEDGER, true);
                break;

            case 'LOANAPPL':

                break;
        }

        $tot = 0;
        $errormsg = '';

        //ALL BANK BRANCH DETAILS
        if(!isset($banks_array)):
          $banks_array = Common::getBankDetails();  
        endif;
        

        // $data_acc = self::searchArray($banks_array, 'bankaccounts_accno', 'CURRENCIES_ID');
        // $currency = $data_acc['productconfig_value'];
        Common::getlables("1726,893,1457,1229,1471,1406,1216,1216,1240,1144,1145,1105,1181,67,1459,772,1193,36,35,1025,1178,885,306,1197,1203,1452,1451,311,1096,1230,1105,306,24,293,1097,1455,1456,1340,1453,704,1229,291", "", "", $Conn);

        while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {

            $tot++;

            $formdata = array();

            // Because our templates have  header rows we need to skip them 
            if ($tot <= 1) {
                continue;
            }

            // VALIDATIONS
            switch ($data_code) {
                case 'GLTRANS':

                    Common::getlables("230,1498,1499,293,1451,1452,317,297,673,677,680,704", "", "", $Conn);

                    // Date
                    if (trim($data[0]) == '') {
                        $errormsg = "<div style='padding:2px;'>" . Common::$lablearray['293'] . ' <b>' . Common::$lablearray['317'] . '</b> ' . Common::$lablearray['1451'] . ' ' . ($tot - 1) . ' ' . Common::$lablearray['1452'] . " 1<div>";
                        break 2;
                    }

                    if (!Common::checkDate($data[0]) || strlen($data[0])<=8) {
                        $errormsg.="<div style='padding:2px;'>" . Common::$lablearray['230'] . ' ' . Common::$lablearray['1451'] . ' ' . ($tot - 1) . ' ' . Common::$lablearray['1452'] . " 1 " . Common::$lablearray['1453'] . " '<b>" . SETTING_DATE_FORMAT . "</b>'<div>";
                        break 2;
                    }

                    // Debit
                    if ($data[3] == '') {
                        $errormsg.="<div style='padding:2px;'>" . Common::$lablearray['289'] . ' <b>' . Common::$lablearray['1499'] . '</b> ' . Common::$lablearray['1451'] . ' ' . ($tot - 1) . ' ' . Common::$lablearray['1452'] . " 4<div>";
                        break 2;
                    }

                    // Credit
                    if ($data[4] == '') {
                        $errormsg.="<div style='padding:2px;'>" . Common::$lablearray['297'] . ' <b>' . Common::$lablearray['1498'] . '</b> ' . Common::$lablearray['1451'] . ' ' . ($tot - 1) . ' ' . Common::$lablearray['1452'] . " 5<div>";
                        break 2;
                    }

                    // Branch code
                    if ($data[5] == '') {
                        $errormsg.="<div style='padding:2px;'>" . Common::$lablearray['673'] . ' ' . Common::$lablearray['1451'] . ' ' . ($tot - 1) . ' ' . Common::$lablearray['1452'] . " 6<div>";
                        break 2;
                    }

                    // check see if gl account
                    if ($data[3] > 0):

                        $acc = Common::checkifAccountExists($data[6]);

                        if ($acc['response'] == '0') {
                            $errormsg.="<div style='padding:2px;'>" . Common::$lablearray['704'] . ' ' . $data[6] . ' ' . Common::$lablearray['1451'] . ' ' . ($tot - 1) . ' ' . Common::$lablearray['1452'] . " 7<div>";

                            break 2;
                        }

                        // Account to debit
                        if ($data[6] == '') {
                            $errormsg.="<div style='padding:2px;'>" . Common::$lablearray['677'] . ' ' . Common::$lablearray['1451'] . ' ' . ($tot - 1) . ' ' . Common::$lablearray['1452'] . " 7<div>";
                            break 2;
                        }
                    endif;

                    if ($data[4] > 0):

                        $acc = Common::checkifAccountExists($data[7]);

                        if ($acc['response'] == 0) {
                            $errormsg.="<div style='padding:2px;'>" . Common::$lablearray['704'] . ' ' . $data[7] . ' ' . Common::$lablearray['1451'] . ' ' . ($tot - 1) . ' ' . Common::$lablearray['1452'] . " 8<div>";

                            break 2;
                        }
                        // Account to Credit
                        if ($data[7] == '') {
                            $errormsg.="<div style='padding:2px;'>" . Common::$lablearray['680'] . ' ' . Common::$lablearray['1451'] . ' ' . ($tot - 1) . ' ' . Common::$lablearray['1452'] . " 8<div>";
                            break 2;
                        }

                    endif;

                    break;
                case 'LOANREPAY':
                case 'LOANDISB':


                  //  $ctype = Common::getClientType($data[0]);

                    // Loan Number
                    if ($data[0] == '') {
                        $errormsg.="<div style='padding:2px;'>" . Common::$lablearray['293'] . ' <b>' . Common::$lablearray['1097'] . '</b> ' . Common::$lablearray['1451'] . ' ' . ($tot - 1) . ' ' . Common::$lablearray['1452'] . " 1<div>";
                        break 2;
                    }

                    // Does the Loan Number exist?        
                    $parameters = array();
                    Common::prepareParameters($parameters, 'theid1', $data[0]);
                    Common::prepareParameters($parameters, 'theid2', '');
                    Common::prepareParameters($parameters, 'code', 'IDEXISTS');
                    Common::prepareParameters($parameters, 'idtype', 'LOANNO');
                    Common::prepareParameters($parameters, 'branch_code', Common::extractBranchCode($data[0]));
                    $loan_array = Common::common_sp_call(serialize($parameters), '', $Conn, true);

                    // Loan does not exist or not yet approved.
                    if ($loan_array['lnr'] == "") {
                        $errormsg = "<div style='padding:2px;'>" . Common::$lablearray['1457'] . ' ' . Common::$lablearray['1451'] . ' ' . ($tot - 1) . ' ' . Common::$lablearray['1452'] . " 1<div>";
                        break 2;
                    }

                    if ($loan_array['response'] == '0') {
                        $errormsg.="<div style='padding:2px;'>" . Common::$lablearray['1097'] . ' <b>' . Common::$lablearray['1455'] . '</b> ' . Common::$lablearray['1451'] . ' ' . ($tot - 1) . ' ' . Common::$lablearray['1452'] . " 2 :" . $data[0] . "<div>";
                        break 2;
                    }

                    // check date
                    if (!Common::checkDate($data[2]) || strlen($data[2])<=8) {
                        $errormsg.="<div style='padding:2px;'>" . Common::$lablearray['293'] . ' <b>' . Common::$lablearray['317'] . '</b> ' . Common::$lablearray['1451'] . ' ' . ($tot - 1) . ' ' . Common::$lablearray['1452'] . " 3 : " . Common::$lablearray['1453'] . " '<b>" . SETTING_DATE_FORMAT . "</b>'<div>";
                        break 2;
                    }


                    // check mode of payment
                    if ($data[3] == '') {
                        $errormsg.="<div style='padding:2px;'>" . Common::$lablearray['293'] . ' <b>' . Common::$lablearray['24'] . '</b> ' . Common::$lablearray['1451'] . ' ' . ($tot - 1) . ' ' . Common::$lablearray['1452'] . " 4<div>";
                        break 2;
                    }

                    $_pararesult = $Conn->SQLSelect("SELECT productconfig_paramname,productconfig_value,productconfig_ind as ind,productconfig_grp as grp FROM " . TABLE_PRODUCTCONFIG . " WHERE  productconfig_paramname IN ('SAVINGS_ACC','PAY_PRIORITY') AND product_prodid='" . $loan_array['prodid'] . "'");


                    // Check product/GL
                    if ($data[3] == 'SA') {


                        if ($data[5] == ""):
                            $errormsg.="<div style='padding:2px;'>" . Common::$lablearray['1406'] . ' ' . Common::$lablearray['1178'] . '(' . $data[5] . ')</b> ' . Common::$lablearray['1451'] . ' ' . ($tot - 1) . ' ' . Common::$lablearray['1452'] . " 6<div>";
                            break 2;
                        endif;

                        $sav_array = $Conn->SQLSelect("SELECT productconfig_paramname,productconfig_value,productconfig_ind as ind,productconfig_grp as grp FROM " . TABLE_PRODUCTCONFIG . " WHERE  productconfig_paramname IN ('SAVINGS_ACC') AND product_prodid='" . $data[5] . "'");

                        // check see if configuration of the product are okay

                        $para_array = Common::searchArray($sav_array, 'productconfig_paramname', 'SAVINGS_ACC');

                        if ($ctype == 'I' && $para_array['ind'] == '') {
                            $errormsg.="<div style='padding:2px;'>" . Common::$lablearray['293'] . '. <b>' . Common::$lablearray['704'] . " " . Common::$lablearray['1178'] . '(' . $data[5] . ')</b> ' . Common::$lablearray['1451'] . ' ' . ($tot - 1) . ' ' . Common::$lablearray['1452'] . " 6<div>";
                            break 2;
                        }

                        if ($ctype == 'G' && $para_array['grp'] == '') {
                            $errormsg.="<div style='padding:2px;'>" . Common::$lablearray['293'] . '. <b>' . Common::$lablearray['704'] . " " . Common::$lablearray['885'] . '(' . $data[5] . ')</b> ' . Common::$lablearray['1451'] . ' ' . ($tot - 1) . ' ' . Common::$lablearray['1452'] . " 6<div>";
                            break 2;
                        }
                    }

                    // check cheque details
                    if ($data[3] == 'CQ') {

                        if ($data[12] == "") {
                            $errormsg.="<div style='padding:2px;'>" . Common::$lablearray['293'] . ' ' . Common::$lablearray['36'] . ' </b> ' . ' ' . ($tot - 1) . ' ' . Common::$lablearray['1452'] . " 13<div>";
                            break 2;
                        }

                        if ($data[13] == "") {
                            $errormsg.="<div style='padding:2px;'>" . Common::$lablearray['293'] . ' <b>' . Common::$lablearray['35'] . '</b> ' . Common::$lablearray['1451'] . ' ' . ($tot - 1) . ' ' . Common::$lablearray['1452'] . " 14<div>";
                            break 2;
                        }
                    }

                    $formdata = array();

                    $para_array = array();

                    $para_array = Common::searchArray($_pararesult, 'productconfig_paramname', 'PAY_PRIORITY');

                    Loan::$paymentpriority = $para_array['productconfig_value'];

                   // $data[8] = $data[8] + (isset($data[9]))? $data[9]:0;
                    
                    Common::addKeyValueToArray($formdata, 'CTYPE', $ctype);
                    Common::addKeyValueToArray($formdata, 'LNR', $data[0]);
                    Common::addKeyValueToArray($formdata, 'TCODE', Common::generateTransactionCode($_SESSION['user_id'])); // to be generated
                    Common::addKeyValueToArray($formdata, 'DATE', Common::changeDateFromPageToMySQLFormat($data[2]));
                    
                    if($data_code=='LOANDISB'):                        
                        Common::addKeyValueToArray($formdata, 'AMOUNT', Common::number_format_locale_compute($data[1]));
                        else:
                        Common::addKeyValueToArray($formdata, 'AMOUNT', Common::number_format_locale_compute($data[8]));
                    endif;
                    
                    Common::addKeyValueToArray($formdata, 'FUNDCODE', $loan_array['fcode']);
                    Common::addKeyValueToArray($formdata, 'DONORCODE', $loan_array['dcode']);
                    Common::addKeyValueToArray($formdata, 'CLIENTIDNO', $loan_array['cid']);
                    Common::addKeyValueToArray($formdata, 'LPRODID', $loan_array['prodid']);
                    Common::addKeyValueToArray($formdata, 'BRANCHCODE', Common::extractBranchCode($loan_array['cid']));  // Must be valided

                    break;
                default:
                    break;
            }



            switch ($data_code) {
                case 'GLTRANS':
                    
                    Common::addKeyValueToArray($formdata, 'TCODE', $data[1]);
                    Common::addKeyValueToArray($formdata, 'DATE', Common::changeDateFromPageToMySQLFormat($data[0]));
                    Common::addKeyValueToArray($formdata, 'DESC', $data[2]);
                    Common::addKeyValueToArray($formdata, 'FUNDCODE', $data[12]);
                    Common::addKeyValueToArray($formdata, 'DONORCODE', $data[13]);
                    Common::addKeyValueToArray($formdata, 'DEBIT', $data[3]);
                    Common::addKeyValueToArray($formdata, 'CREDIT', $data[4]);
                    Common::addKeyValueToArray($formdata, 'VOUCHER', $data[14]);
                    Common::addKeyValueToArray($formdata, 'TABLE', TABLE_GENERALLEDGER);

                    // check see if gl account
                    if ($data[3] > 0):

                        $acc = Common::checkifAccountExists($data[6]);
                        if ($acc['response'] == 0) {
                            break 2;
                        }

                        Common::addKeyValueToArray($formdata, 'GLACC', $data[6]);
                    endif;

                    if ($data[4] > 0):

                        $acc = Common::checkifAccountExists($data[7]);

                        if ($acc['response'] == 0) {

                            break 2;
                        }

                        Common::addKeyValueToArray($formdata, 'GLACC', $data[7]);
                    endif;

                    Common::addKeyValueToArray($formdata, 'BRANCHCODE', $data[5]);
                    Common::addKeyValueToArray($formdata, 'TRANCODE', $data[15]);
                    Common::addKeyValueToArray($formdata, 'FXID', $data[9]);
                    Common::addKeyValueToArray($formdata, 'FCAMT', Common::number_format_locale_compute($data[10]));
                    Common::addKeyValueToArray($formdata, 'CURRENCIES_ID', $data[16]);
                    Common::addKeyValueToArray($formdata, 'CLIENTIDNO', $data[8]);
                    Common::addKeyValueToArray($formdata, 'PRODUCT_PRODID', $data[11]);
                    Common::addKeyValueToArray($formdata, 'CCODE', $data[17]);

                    $form_data[] = $formdata;

                    break;

                case 'LOANREPAY':

                    if ($data[3] == 'CA') { // Cash
                        if ($data[6] == "") { // Check GL Account for Cash
                            $errormsg.="<div style='padding:2px;'> <b>" . Common::$lablearray['1459'] . '</b> ' . Common::$lablearray['1451'] . ' ' . ($tot - 1) . ' ' . Common::$lablearray['1452'] . " 9<div>";
                            break;
                        }
                    }
                    if ($data[3] == 'SA') {

                      if ($data[4] == "") {
                            $errormsg.="<div style='padding:2px;'>" . Common::$lablearray['1197'] . ' ' . Common::$lablearray['1096'] . ' </b> ' . Common::$lablearray['1451'] . ' ' . ($tot - 1) . ' ' . Common::$lablearray['1452'] . " 5<div>";
                            break 2;
                        }

                        if ($data[5] == "") {
                            $errormsg.="<div style='padding:2px;'>" . Common::$lablearray['293'] . ' ' . Common::$lablearray['1197'] . ' </b> ' . Common::$lablearray['1451'] . ' ' . ($tot - 1) . ' ' . Common::$lablearray['1452'] . " 6<div>";
                            break 2;
                        }
                      
                        Savings::$prodid = $data[5];
                        Savings::$asatdate = Common::changeDateFromPageToMySQLFormat($formdata['DATE']);
                        Savings::$savacc = $data[4];
                    
                        if (!Savings::getSavingsBalance($formdata['AMOUNT'])) {
                            $errormsg.="<div style='padding:2px;'>" . Common::$lablearray['1216'] . '(' . $data[4] . ')' . ' - ' . $grpbalance . ' ' . Common::$lablearray['1471'] . ' ' . $formdata['DATE'] . ' </div>'; // Insufficient funds on account to complete transaction
                            break 2;
                        }
                    }
                    if(isset($data[6])):
                          Common::addKeyValueToArray($formdata, 'GLACC', $data[6]);
                    else:
                        Common::addKeyValueToArray($formdata, 'GLACC', $data[16]);
                    endif;
                   
                    Common::addKeyValueToArray($formdata, 'PRI', (isset($data[8])?$data[8]:0));
                    Common::addKeyValueToArray($formdata, 'INT', (isset($data[9])?$data[9]:0));
                    Common::addKeyValueToArray($formdata, 'COM', '0');
                    Common::addKeyValueToArray($formdata, 'PEN', '0');
                    Common::addKeyValueToArray($formdata, 'AMOUNT',$formdata['PRI'] + $formdata['INT'] + $formdata['COM'] + $formdata['PEN']);
                    Common::addKeyValueToArray($formdata, 'SAMOUNT',  Common::number_format_locale_compute((isset($data[1])?$data[1]:0)));
                    
                    Common::addKeyValueToArray($formdata, 'VOUCHER', $data[7]);
                    Common::addKeyValueToArray($formdata, 'CHEQNO', $data[12]);
                    Common::addKeyValueToArray($formdata, 'MODE', $data[3]);
                    
                    Common::addKeyValueToArray($formdata, 'MEMID', $data[14]);
                    Common::addKeyValueToArray($formdata, 'SAVACC', $data[4]);
                    Common::addKeyValueToArray($formdata, 'SPRODID', $data[5]);
                    Common::addKeyValueToArray($formdata, 'DESC', Common::$lablearray['1449']);
                    Common::addKeyValueToArray($formdata, 'BACCNO', $data[13]);

                   
                    
                    if ($formdata['MODE'] == 'CQ') { // By cheque
                        $data_acc = Common::searchArray($banks_array, 'bankaccounts_accno', $data[13]);
                        $bankaccounts_id = $data_acc['bankaccounts_id'];
                        Common::addKeyValueToArray($formdata, 'BID', $bankaccounts_id);
                  //      Common::addKeyValueToArray($formdata, 'LSTATUS', 'LD');
                        Common::addKeyValueToArray($formdata, 'BANKGL', $data_acc['chartofaccounts_accountcode']);
                    }

                  // Common::addKeyValueToArray($formdata, 'TABLE', TABLE_LOANPAYMENTS);
                    
                   // Loan::$callmodule ='S';
                    
                    $form_data[] = $formdata;
                    
                    // Loan::updateLoan($form_data, 'LR');
                    // if (Common::$lablearray['E01'] != "") {
                    //    echo 'MSG ' . Common::$lablearray['E01'];
                    //    exit();
                    // }
                  
                    break;

                case 'LOANDISB':

                    // check disbursement date
                    if ($data[2] == '') {
                        $errormsg.="<div style='padding:2px;'>" . Common::$lablearray['293'] . ' <b>' . Common::$lablearray['1340'] . '</b> ' . Common::$lablearray['1451'] . ' ' . ($tot - 1) . ' ' . Common::$lablearray['1452'] . " 3 :" . $data[0] . "<div>";
                        continue 2;
                    }

                    // Check GL account
                    if ($data[4] == '') {
                        $errormsg.="<div style='padding:2px;'>" . Common::$lablearray['293'] . ' <b>' . Common::$lablearray['306'] . '</b> ' . Common::$lablearray['1451'] . ' ' . ($tot - 1) . ' ' . Common::$lablearray['1452'] . " 5<div>";
                        continue 2;
                    }

                    if ($data[3] == 'SA') {

                        if ($data[5] == "") {
                            $errormsg.="<div style='padding:2px;'>" . Common::$lablearray['293'] . ' ' . Common::$lablearray['1096'] . ' </b> ' . Common::$lablearray['1451'] . ' ' . ($tot - 1) . ' ' . Common::$lablearray['1452'] . " 6<div>";
                            break 2;
                        }

                        if ($data[6] == "") {
                            $errormsg.="<div style='padding:2px;'>" . Common::$lablearray['293'] . ' ' . Common::$lablearray['1197'] . ' </b> ' . Common::$lablearray['1451'] . ' ' . ($tot - 1) . ' ' . Common::$lablearray['1452'] . " 7<div>";
                            break 2;
                        }
                    }
                    if ($data[3] == 'CQ') { // bu cheque
                        if ($data[12] == "") {
                            $errormsg.="<div style='padding:2px;'>" . Common::$lablearray['293'] . ' <b>' . Common::$lablearray['36'] . '</b> ' . Common::$lablearray['1451'] . ' ' . ($tot - 1) . ' ' . Common::$lablearray['1452'] . " 13<div>";
                            break;
                        }

                        if ($data[13] == "") {
                            $errormsg.="<div style='padding:2px;'>" . Common::$lablearray['293'] . ' <b>' . Common::$lablearray['35'] . '</b> ' . Common::$lablearray['1451'] . ' ' . ($tot - 1) . ' ' . Common::$lablearray['1452'] . " 14<div>";
                            break;
                        }
                    }


                    // add to disbursement table

                    Common::addKeyValueToArray($formdata, 'VAT', Common::number_format_locale_compute($data[10]));
                    Common::addKeyValueToArray($formdata, 'AMOUNT', Common::number_format_locale_compute($data[1]));
                    Common::addKeyValueToArray($formdata, 'VOUCHER', $data[8]);
                    Common::addKeyValueToArray($formdata, 'COMM', Common::number_format_locale_compute($data[11]));
                    Common::addKeyValueToArray($formdata, 'CHEQNO', $data[12]);
                    Common::addKeyValueToArray($formdata, 'MODE', $data[3]);
                    Common::addKeyValueToArray($formdata, 'GLACC', $data[4]);
                    Common::addKeyValueToArray($formdata, 'CYCLE', $data[9]);
                    Common::addKeyValueToArray($formdata, 'FUNDCODE', $loan_array['fcode']);
                    Common::addKeyValueToArray($formdata, 'DONORCODE', $loan_array['dcode']);
                    Common::addKeyValueToArray($formdata, 'MEMID', $data[7]);
                    Common::addKeyValueToArray($formdata, 'SAVACC', $data[6]);
                    Common::addKeyValueToArray($formdata, 'SPRODID', $data[5]);
                    Common::addKeyValueToArray($formdata, 'DTYPE', 'DD');

                    if ($data[1] < $loan_array['amt']) {
                        $formdata['DTYPE'] = 'PD';
                    }

                    Common::addKeyValueToArray($formdata, 'TABLE', TABLE_DISBURSEMENTS);
                    Common::addKeyValueToArray($formdata, 'DESC', Common::$lablearray['1229']);
                    Common::addKeyValueToArray($formdata, 'BACCNO', $data[13]);
                    Common::addKeyValueToArray($formdata, 'STAT', Common::number_format_locale_compute($data[14]));

                    Common::addKeyValueToArray($formdata, 'LSTATUS', 'LD');

                    if ($data[3] == 'CQ') {
                        Common::addKeyValueToArray($formdata, 'CQSTAT', 'C');
                    }


                    if ($formdata['MODE'] == 'CQ') { // By cheque
                        $data_acc = Common::searchArray($banks_array, 'bankaccounts_accno', $data[13]);
                        $bankaccounts_id = $data_acc['bankaccounts_id'];
                        Common::addKeyValueToArray($formdata, 'BID', $bankaccounts_id);
                        Common::addKeyValueToArray($formdata, 'LSTATUS', 'LD');
                        Common::addKeyValueToArray($formdata, 'BANKGL', $data_acc['chartofaccounts_accountcode']);
                    }
                    
                    $form_data = array($formdata);

                    Loan::updateLoan($form_data, $formdata['LSTATUS']);
                    if (Common::$lablearray['E01'] != "") {
                        echo 'MSG ' . Common::$lablearray['E01'];
                        exit();
                    }
                    break;

                case 'LOANAPPL':

                    // VALIDATIONS: Loan Amount,Interest Rate,Interest Type,Installement Type,Application Date
                    Common::getlables("1454,1093,1434,293,1453,1098,1103,1291,1451,1452,1100,1102", "", "", Common::$connObj);

                    // Validate Loan Amount
                    if ($data[0] == '' || $data[0] <= 0) {
                        $errormsg.="<div style='padding:2px;'>" . Common::$lablearray['293'] . ' <b>' . Common::$lablearray['1291'] . '</b> ' . Common::$lablearray['1451'] . ' ' . ($tot - 1) . ' ' . Common::$lablearray['1452'] . " 1<div>";
                        break;
                    }
                    
                    // Validate Loan Amount
                    if ($data[1] == '' || $data[1] <= 0) {
                        $errormsg.="<div style='padding:2px;'>" . Common::$lablearray['293'] . ' <b>' . Common::$lablearray['1100'] . '</b> ' . Common::$lablearray['1451'] . ' ' . ($tot - 1) . ' ' . Common::$lablearray['1452'] . " 2<div>";
                        break;
                    }

                    // Interest Type
                    if ($data[2] != 'FR' && $data[2] != 'DD' && $data[2] != 'DA') {
                        $errormsg.="<div style='padding:2px; '>" . Common::$lablearray['293'] . ' <b>' . Common::$lablearray['1102'] . '</b> ' . Common::$lablearray['1451'] . ' ' . ($tot - 1) . ' ' . Common::$lablearray['1452'] . " 3<div>";
                        break;
                    }

                    // Installement Type
                    if (!preg_match('[W|D|B|H|O|M|T|Q|F|I|S|E|A]', $data[16])) {
                        $errormsg.="<div style='padding:2px;'>" . Common::$lablearray['293'] . ' <b>' . Common::$lablearray['1103'] . '</b> ' . Common::$lablearray['1451'] . ' ' . ($tot - 1) . ' ' . Common::$lablearray['1452'] . " 17<div>";
                        break;
                    }

                    // Product
                    if ($data[5] == "") {
                        $errormsg.="<div style='padding:2px;'>" . Common::$lablearray['293'] . ' <b>' . Common::$lablearray['1434'] . '</b> ' . Common::$lablearray['1451'] . ' ' . ($tot - 1) . ' ' . Common::$lablearray['1452'] . " 5<div>";
                        break;
                    }

                    // Application Date
                    if ($data[13] == '') {
                        $errormsg.="<div style='padding:2px; '>" . Common::$lablearray['293'] . ' <b>' . Common::$lablearray['1098'] . '</b> ' . Common::$lablearray['1451'] . ' ' . ($tot - 1) . ' ' . Common::$lablearray['1452'] . " 3<div>";
                        break;
                    }
                    // Validate dates
                    // Application date/freeze date
                    if (!Common::checkDate($data[13])|| strlen($data[13])<=8) {
                        $errormsg.="<div style='padding:2px;'>" . Common::$lablearray['293'] . ' <b>' . Common::$lablearray['1098'] . '</b> ' . Common::$lablearray['1451'] . ' ' . ($tot - 1) . ' ' . Common::$lablearray['1452'] . " 14 " . Common::$lablearray['1453'] . " '<b>" . SETTING_DATE_FORMAT . "</b>'<div>";
                        break;
                    }

                    // Freeze Date
                    if ($data[26] != "") {

                        if (!Common::checkDate($data[27]) || strlen($data[27])<=8) {
                            $errormsg.="<div style='padding:2px;'>" . Common::$lablearray['293'] . ' <b>' . Common::$lablearray['1454'] . '</b> ' . Common::$lablearray['1451'] . ' ' . ($tot - 1) . ' ' . Common::$lablearray['1452'] . " 27 " . Common::$lablearray['1453'] . " '<b>" . SETTING_DATE_FORMAT . "</b>'<div>";
                            break;
                        }
                    }
                    
                    if ($data[23] == "") {
                        $errormsg.="<div style='padding:2px; '>" . Common::$lablearray['293'] . ' <b>' . Common::$lablearray['1093'] . '</b> ' . Common::$lablearray['1451'] . ' ' . ($tot - 1) . ' ' . Common::$lablearray['1452'] . " 34<div>";
                    }
                    break;
                default:
                    break;
            }
            for ($c = 0; $c < 1; $c++) {
                switch ($data_code) {

                    case 'LOANAPPL'; 
                        // Loan Applications
                        // Initialise loan variable//  
                        $id = Common::identidyClientType($data[23]);
                        Common::addKeyValueToArray($formdata, 'LNR', Common::generateID($data[23], $id, 'LOANNO'));
                        Common::addKeyValueToArray($formdata, 'LAMNT', Common::number_format_locale_compute($data[0]));
                        Common::addKeyValueToArray($formdata, 'FCODE', $data[17]);
                        Common::addKeyValueToArray($formdata, 'INTRATE', Common::number_format_locale_compute($data[1]));
                        Common::addKeyValueToArray($formdata, 'INTAMNT', '0'); // To be updated
                        Common::addKeyValueToArray($formdata, 'USERID', $_SESSION['user_id']);
                        Common::addKeyValueToArray($formdata, 'START', $data[13]);
                        Common::addKeyValueToArray($formdata, 'GRACE', $data[5]);
                        Common::addKeyValueToArray($formdata, 'NINST', $data[3]);
                        Common::addKeyValueToArray($formdata, 'LEXP', '');
                        Common::addKeyValueToArray($formdata, 'FIRSTINS', '');
                        Common::addKeyValueToArray($formdata, 'UD1', $data[18]);
                        Common::addKeyValueToArray($formdata, 'UD2', $data[19]);
                        Common::addKeyValueToArray($formdata, 'UD3', $data[20]);
                        //   Common::addKeyValueToArray($formdata, 'ADATE', $data[13]);

                        Common::addKeyValueToArray($formdata, 'DATE', $data[13]);
                        Common::addKeyValueToArray($formdata, 'INTTYPE', $data[2]);
                        Common::addKeyValueToArray($formdata, 'INSTYPE', $data[16]);
                        Common::addKeyValueToArray($formdata, 'AGRACE', $data[6]);
                        Common::addKeyValueToArray($formdata, 'INTDAY', '');
                        Common::addKeyValueToArray($formdata, 'INTDIS', $data[14]);
                        Common::addKeyValueToArray($formdata, 'PRODID', $data[4]);
                        Common::addKeyValueToArray($formdata, 'BRANCHCODE', Common::extractBranchCode($data[23]));  // Must be valided
                        Common::addKeyValueToArray($formdata, 'MEMID', $data[24]);
                        Common::addKeyValueToArray($formdata, 'INTF', $data[9]);
                        Common::addKeyValueToArray($formdata, 'LINS', '');
                        Common::addKeyValueToArray($formdata, 'COMM', Common::number_format_locale_compute($data[25]));
                        Common::addKeyValueToArray($formdata, 'FREEZE', $data[26]);
                        Common::addKeyValueToArray($formdata, 'EXDATE', ''); // to be updated
                        Common::addKeyValueToArray($formdata, 'LPD', ''); // to be updated
                        Common::addKeyValueToArray($formdata, 'INTUPF', ''); // to be updated
                        Common::addKeyValueToArray($formdata, 'INSTG', $data[8]);
                        Common::addKeyValueToArray($formdata, 'GCOMP', $data[7]);
                        Common::addKeyValueToArray($formdata, 'AJDDU', $data[10]);
                        Common::addKeyValueToArray($formdata, 'AJDW', $data[11]);
                        Common::addKeyValueToArray($formdata, 'COMPINT', $data[12]);
                        Common::addKeyValueToArray($formdata, 'INTD', $data[15]);
                        Common::addKeyValueToArray($formdata, 'INTAMNT', 0); // to be updated
                        Common::addKeyValueToArray($formdata, 'LNCAT1', $data[19]);
                        Common::addKeyValueToArray($formdata, 'LNCAT2', $data[20]);
                        Common::addKeyValueToArray($formdata, 'LNCAT3', $data[21]);
                        Common::addKeyValueToArray($formdata, 'DCODE', $data[22]);
                        Common::addKeyValueToArray($formdata, 'CCODE', $data[23]);
                        Common::addKeyValueToArray($formdata, 'MEMID', $data[24]);
                        Common::addKeyValueToArray($formdata, 'COMM', $data[25]);
                        Common::addKeyValueToArray($formdata, 'FREEZE', $data[26]);
                        Common::addKeyValueToArray($formdata, 'LSTATUS', $data[27]);
                        Common::addKeyValueToArray($formdata, 'AMTPR', $data[28]);              
                        Common::addKeyValueToArray($formdata, 'TABLE', TABLE_LOAN);
                        //  Common::addKeyValueToArray($formdata, 'BACC',TABLE_LOAN); 

                        $form_data = array($formdata);
                        
                        Loan::updateLoan($form_data, 'PA');
                        if (Common::$lablearray['E01'] != "") {
                            echo 'MSG ' . Common::$lablearray['E01'];
                            exit();
                        }
                        break;
                  
                
                    case 'IND': // import Individuals
                   

                        if ($data[0] == "") {
                             $errormsg.="<div style='padding:2px;'>" . Common::$lablearray['316'] . ' <b>' . Common::$lablearray['291'] . '</b> ' . Common::$lablearray['1451'] . ' ' . ($tot - 1) . ' ' . Common::$lablearray['1452'] . " 1 " . Common::$lablearray['1453'] . " '<b>" . SETTING_DATE_FORMAT . "</b>'<div>";
                             break;
                        }                        
                        
                        if ($data[1] == "") {
                             $errormsg.="<div style='padding:2px;'>" . Common::$lablearray['1019'] . Common::$lablearray['291'] . ' ' . ($tot - 1) . ' ' . Common::$lablearray['1452'] . " 27 " . Common::$lablearray['1453'] .' '.$data[1] . " '<b>" . SETTING_DATE_FORMAT . "</b>'<div>";
                             break;
                        }
                        
                        if ($data[1] != "") {        
                            if (!Common::checkDate($data[1]) || strlen($data[1])<=8) {
                                $errormsg.="<div style='padding:2px;'>" . Common::$lablearray['293'] .' '.Common::$lablearray['1019']. ' <b>' . Common::$lablearray['1454'] . '</b> ' . Common::$lablearray['1451'] . ' ' . ($tot - 1) . ' ' . Common::$lablearray['1452'] . " 2 " . Common::$lablearray['1453'] . " '<b>" . SETTING_DATE_FORMAT . "</b>'<div>";
                                break;
                            }else{
                                $data[1] =Common::changeDateFromPageToMySQLFormat($data[1]);
                            }
                        }

                        if ($data[5] != "") {        
                            if (!Common::checkDate($data[5]) || strlen($data[5])<=8) {                          
                                $errormsg.="<div style='padding:2px;'>" . Common::$lablearray['230'] .' '.Common::$lablearray['1640']. ' <b>' . Common::$lablearray['1454'] . '</b> ' . Common::$lablearray['1451'] . ' ' . ($tot - 1) . ' ' . Common::$lablearray['1452'] . " 6 " . Common::$lablearray['1453'] .' '. $data[5]." '<b>" . SETTING_DATE_FORMAT . "</b>'<div>";
                                break;
                            }else{
                                $data[5] =Common::changeDateFromPageToMySQLFormat($data[5]);
                            }
                        }   
                        
                        // Client Type
                                              
                        $values_to_post ['clientcode'] = NULL;
                        $values_to_post ['client_type'] = 'I';
                                                   
                        // client id number                          
                        if ($data[6] == "") {
                            if($values_to_post ['client_type']=="I")
                                $subtype ='CLIENT';

                            if($values_to_post ['client_type']=="G")
                                $subtype ='GROUP';                            
                                
                            if($values_to_post ['client_type']=="B")
                                $subtype ='BUSINESS';
                            
                            if($values_to_post ['client_type']=="M")
                                $subtype ='MEMBER';

                            $values_to_post ['client_idno'] = Common::generateID($data[0].'/'.$values_to_post ['client_type'],$values_to_post ['client_type'],$subtype,'');
                        } else {

                            $code_array = $Conn->SQLSelect("SELECT client_idno FROM " . TABLE_CLIENTS. " WHERE client_idno='".$data[6]. "'",true);
                    
                            if($code_array[0]['client_idno']!=''):
                                Common::getlables("1093,1725", "", "",Common::$connObj);
                                $errormsg.="<div style='padding:2px;'>" . Common::$lablearray['1093'] .' ' . Common::$lablearray['1725'] . ' ' . Common::$lablearray['1451'] . ' ' . ($tot - 1) . ' ' . Common::$lablearray['1452'] . " 7 <b>" . $data[6]. "</b><div>";
                                break;
                            endif;

                            

                            if (preg_match('/'.$data[6].'/i', $str_client_codes)):
                                $errormsg.="<div style='padding:2px;'>" . Common::$lablearray['1093'] .' '.Common::$lablearray['1640']. ' <b>' . Common::$lablearray['1454'] . '</b> ' . Common::$lablearray['1451'] . ' ' . ($tot - 1) . ' ' . Common::$lablearray['1452'] . " 7 " . $data[6]. "<div>";
                                break;
                            endif;
                            
                            $str_client_codes.=$data[6];

                            $values_to_post ['client_idno'] = $data[6];
                        }

                       
                        $values_to_post ['branch_code'] = $data[0]; 
                        $values_to_post ['client_regdate'] = $data[1]; // Registration date
                        $values_to_post ['client_firstname'] = $data[2]; // firstname
                        $values_to_post ['client_middlename'] = $data[3]; // Middlename
                        $values_to_post ['client_surname'] = $data[4]; // Surname
                        $values_to_post ['client_bday'] = $data[5]; // Birth Date
                        
                        
                        $values_to_post ['client_postad'] = $data[7]; // Postal Address
                        $values_to_post ['client_gender'] = $data[8]; // Gender
                        $values_to_post ['client_city'] = $data[9]; // City
                        $values_to_post ['client_addressphysical'] = $data[10]; // Physical Address

                        // TO DO: Add validation for areacode
                        $values_to_post ['areacode_code'] = $data[11]; // Area Code
                        $values_to_post ['client_maritalstate'] = $data[12]; //  Marital Status
                        $values_to_post ['client_tel1'] = $data[13]; //  Telepehone 1
                        $values_to_post ['client_tel2'] = $data[14]; //  Telepehone 2
                        $values_to_post ['client_emailad'] = $data[15]; //  Email
                    
                       // $values_to_post ['clientcode'] = $data[15];//  End Date
                        // TO DO: Add validation for costcenter
                        $values_to_post ['costcenters_code'] = $data[17]; //  Cost Center
                        $values_to_post ['client_cat1'] = $data[18]; //  Client Category 1
                        $values_to_post ['client_cat2'] = $data[19]; //  Client Category 2 
                        $values_to_post ['bussinesssector_code'] = $data[20]; //  Businnes Sector
                        $values_to_post ['client_regstatus'] = $data[21]; //  Registration Status
                        $values_to_post ['entity_type'] = 'I'; //  Client Type

                        //  end date
                        if ($data[16] != ""):
                            if (!Common::checkDate($data[16]) || strlen($data[16])<=8) {                               
                                $errormsg.="<div style='padding:2px;'>" . Common::$lablearray['230'] .' '.Common::$lablearray['1249']. ' <b>' . Common::$lablearray['1454'] . '</b> ' . Common::$lablearray['1451'] . ' ' . ($tot - 1) . ' ' . Common::$lablearray['1452'] . " 27 " . Common::$lablearray['1453'] . " '<b>" . SETTING_DATE_FORMAT . "</b>'<div>";
                                break;
                            }else{
                                $values_to_post ['client_enddate']= Common::changeDateFromPageToMySQLFormat($data[16]);
                            }  
                        else: 
                            $values_to_post ['client_enddate']=NULL;                     
                        endif; 




                        $values_to_post ['user_accesscode'] = $_SESSION['user_accesscode'];

                       if($errormsg==""){
                            $Conn->SQLInsert(array(TABLE_CLIENTS =>$values_to_post), false);  
                            $Conn->endTransaction();                      
                       }else{
                            $Conn->cancelTransaction();  
                       }
                       
                       
                        if ($Conn::$error!=""):
                           $errormsg="<div style='padding:2px;'>" .$Conn::$error.' ' . Common::$lablearray['1451'] . ' ' . ($tot - 1) . " </div>";
                           break;
                        endif;                                
                        
                        break;

                    case 'GRPMEM':  // import Group Members 

                        // branch code
                        if ($data[0] == "") {                          
                            $errormsg.="<div style='padding:2px;'>" . Common::$lablearray['316'] . Common::$lablearray['291'] . ' ' . ($tot - 1) . ' ' . Common::$lablearray['1452'] . " 1 " . Common::$lablearray['1453'] . "</div>";    
                            break;
                       }                        
                       
                       // Registration Date
                       if ($data[1] == "") {
                            $errormsg.="<div style='padding:2px;'>" . Common::$lablearray['1019'] . Common::$lablearray['291'] . ' ' . ($tot - 1) . ' ' . Common::$lablearray['1452'] . " 2 " . Common::$lablearray['1453'] .' '.$data[1] . " '<b>" . SETTING_DATE_FORMAT . "</b>'<div>";
                            break;
                       }
                       
                        // Registration Date
                       if ($data[1] != "") {        
                           if (!Common::checkDate($data[1]) || strlen($data[1])<=8) {
                               $errormsg.="<div style='padding:2px;'>" . Common::$lablearray['293'] .' '.Common::$lablearray['1019']. ' <b>' . Common::$lablearray['1454'] . '</b> ' . Common::$lablearray['1451'] . ' ' . ($tot - 1) . ' ' . Common::$lablearray['1452'] . " 3 " . Common::$lablearray['1453'] . " '<b>" . SETTING_DATE_FORMAT . "</b>'<div>";
                               break;
                           }else{
                               $data[1] =Common::changeDateFromPageToMySQLFormat($data[1]);
                           }
                       }

                       // Birth Date
                       if ($data[5] != "") {        
                           if (!Common::checkDate($data[5]) || strlen($data[5])<=8) {                          
                               $errormsg.="<div style='padding:2px;'>" . Common::$lablearray['230'] .' '.Common::$lablearray['1640']. ' <b>' . Common::$lablearray['1454'] . '</b> ' . Common::$lablearray['1451'] . ' ' . ($tot - 1) . ' ' . Common::$lablearray['1452'] . " 6 " . Common::$lablearray['1453'] .' '. $data[5]." '<b>" . SETTING_DATE_FORMAT . "</b>'<div>";
                               break;
                           }else{
                               $data[5] =Common::changeDateFromPageToMySQLFormat($data[5]);
                           }
                       }   
                       
                       // Group Code
                       if($data[22]!==""):
                           if (preg_match('/G/i', $data[22])): // Group Member  
                               $values_to_post ['entity_idno'] = $data[22]; 
                           else:
                               Common::getlables("293,1727", "", "",Common::$connObj);
                               $errormsg.="<div style='padding:2px;'>" . Common::$lablearray['293'] .' ' . Common::$lablearray['1727'] . ' ' . Common::$lablearray['1451'] . ' ' . ($tot - 1) . ' ' . Common::$lablearray['1452'] . " 23 <b>" . $data[22]. "</b><div>";
                               break;
                           endif;
                     
                       else:
                            Common::getlables("1727,291", "", "",Common::$connObj);
                            $errormsg.="<div style='padding:2px;'>" . Common::$lablearray['1727'] .' ' . Common::$lablearray['291'] . ' ' . Common::$lablearray['1451'] . ' ' . ($tot - 1) . ' ' . Common::$lablearray['1452'] . " 23 <b>" . $data[22]. "</b><div>";
                            break; 
                       endif;

                   
                       // member id                         
                       if ($data[6] == "") {                         
                           $values_to_post ['members_idno'] = Common::generateID($data[0].'/'.'M','MEMBER','M');
                       } else {

                           $code_array = $Conn->SQLSelect("SELECT members_idno FROM " . TABLE_MEMBERS. " WHERE client_idno='".$data[6]. "'",true);
                   
                           if($code_array[0]['members_idno']!=''):
                               Common::getlables("1093,1725", "", "",Common::$connObj);
                               $errormsg.="<div style='padding:2px;'>" . Common::$lablearray['1093'] .' ' . Common::$lablearray['1725'] . ' ' . Common::$lablearray['1451'] . ' ' . ($tot - 1) . ' ' . Common::$lablearray['1452'] . " 7 <b>" . $data[6]. "</b><div>";
                               break;
                           endif;                           

                           if (preg_match('/'.$data[6].'/i', $str_client_codes)):
                               $errormsg.="<div style='padding:2px;'>" . Common::$lablearray['1093'] .' '.Common::$lablearray['1640']. ' <b>' . Common::$lablearray['1454'] . '</b> ' . Common::$lablearray['1451'] . ' ' . ($tot - 1) . ' ' . Common::$lablearray['1452'] . " 7 " . $data[6]. "<div>";
                               break;
                           endif;
                           
                           $str_client_codes.= $data[6];

                           $values_to_post ['members_idno'] = $data[6];
                       }


                        // member no                         
                        if ($data[25] == "") {                         
                            $values_to_post ['members_no'] = Common::generateID($data[0].'/'.'M','MEMBERNO',$data[22]);
                        } else {
 
                            $code2_array = $Conn->SQLSelect("SELECT members_no FROM " . TABLE_MEMBERS. " WHERE entity_idno='".$data[22]. "' AND members_no='".$data[25]."'",true);
                    
                            if($code2_array[0]['members_no']!=''):
                                Common::getlables("1241,1725", "", "",Common::$connObj);
                                $errormsg.="<div style='padding:2px;'>" . Common::$lablearray['1241'] .' ' . Common::$lablearray['1725'] . ' ' . Common::$lablearray['1451'] . ' ' . ($tot - 1) . ' ' . Common::$lablearray['1452'] . " 26 <b>" . $data[25]. "</b><div>";
                                break;
                            endif;                            
 
                            if (preg_match('/'.$data[25].'/i', $strmem_client_codes)):
                                $errormsg.="<div style='padding:2px;'>" . Common::$lablearray['1093'] .' '.Common::$lablearray['1640']. ' <b>' . Common::$lablearray['1454'] . '</b> ' . Common::$lablearray['1451'] . ' ' . ($tot - 1) . ' ' . Common::$lablearray['1452'] . " 7 " . $data[6]. "<div>";
                                break;
                            endif;
                            
                            $strmem_client_codes.= $data[25];
 
                            $values_to_post ['members_no'] = $data[25];
                        }

                      
                       $values_to_post ['branch_code'] = $data[0]; 
                       $values_to_post ['members_regdate'] = $data[1]; // Registration date
                       $values_to_post ['members_firstname'] = $data[2]; // firstname
                       $values_to_post ['members_middlename'] = $data[3]; // Middlename
                       $values_to_post ['members_lastname'] = $data[4]; // Surname
                       $values_to_post ['members_bday'] = $data[5]; // Birth Date
                       
                       
                       $values_to_post ['members_postad'] = $data[7]; // Postal Address
                       $values_to_post ['members_gender'] = $data[8]; // Gender
                       $values_to_post ['members_city'] = $data[9]; // City
                       $values_to_post ['members_addressphysical'] = $data[10]; // Physical Address

                       // TO DO: Add validation for areacode
                       $values_to_post ['areacode_code'] = $data[11]; // Area Code
                       $values_to_post ['members_maritalstate'] = $data[12]; //  Marital Status
                       $values_to_post ['members_tel1'] = $data[13]; //  Telepehone 1
                       $values_to_post ['members_tel2'] = $data[14]; //  Telepehone 2
                       $values_to_post ['members_email'] = $data[15]; //  Email
                       $values_to_post ['members_children'] = $data[23]; //  Children
                       $values_to_post ['members_dependants'] = $data[24]; //  Dependants
                       $values_to_post ['incomecategories_id'] = $data[27]; //  Income
                   
                       $values_to_post ['members_educ'] = $data[26]; //  Education
                       
                   
                       // TO DO: Add validation for costcenter
                       $values_to_post ['costcenters_code'] = $data[17]; //  Cost Center
                       $values_to_post ['members_cat1'] = $data[18]; //  Client Category 1
                       $values_to_post ['members_cat2'] = $data[19]; //  Client Category 2 
                       $values_to_post ['bussinesssector_code'] = $data[20]; //  Businnes Sector
                       $values_to_post ['members_regstatus'] = $data[21]; //  Registration Status

                       //  end date
                       if ($data[16] != ""):
                           if (!Common::checkDate($data[16]) || strlen($data[16])<=8) {                               
                               $errormsg.="<div style='padding:2px;'>" . Common::$lablearray['230'] .' '.Common::$lablearray['1249']. ' <b>' . Common::$lablearray['1454'] . '</b> ' . Common::$lablearray['1451'] . ' ' . ($tot - 1) . ' ' . Common::$lablearray['1452'] . " 27 " . Common::$lablearray['1453'] . " '<b>" . SETTING_DATE_FORMAT . "</b>'<div>";
                               break;
                           }else{
                               $values_to_post ['members_enddate']= Common::changeDateFromPageToMySQLFormat($data[16]);
                           }  
                       else: 
                           $values_to_post ['members_enddate']=NULL;                     
                       endif; 

                       $values_to_post ['user_accesscode'] = $_SESSION['user_accesscode'];

                      if($errormsg==""){
                           $Conn->SQLInsert(array(TABLE_MEMBERS =>$values_to_post), false);  
                           $Conn->endTransaction();                      
                      }else{
                           $Conn->cancelTransaction();  
                      }
                      
                      
                       if ($Conn::$error!=""):
                          $errormsg="<div style='padding:2px;'>" .$Conn::$error.' ' . Common::$lablearray['1451'] . ' ' . ($tot - 1) . " </div>";
                          break;
                       endif;                                
                       
                       break;

                    case 'DOC': // Documents

                        //check if clientcode is empty
                        if ($data[0] == "") {
                            Common::getlables("1022,291", "", "",Common::$connObj);
                            $errormsg.="<div style='padding:2px;'>" . Common::$lablearray['1022'] . Common::$lablearray['291'] . ' ' . ($tot - 1) . ' ' . Common::$lablearray['1452'] . " 1 " . Common::$lablearray['1453'] . " </div>";
                            break;
                        }

                         // check if clientcode exists
                         if ($data[0] != "" && (preg_match('[I]', $data[0]) || preg_match('[M]', $data[0]))) {

                            // check if individual exists
                            if(preg_match('[I]', $data[0])):
                                $ind_code_array = $Conn->SQLSelect("SELECT client_idno FROM " . TABLE_CLIENTS. " WHERE client_idno='".$data[0]. "'",true);
                    
                                if($ind_code_array[0]['client_idno']==''):
                                    Common::getlables("1728", "", "",Common::$connObj);
                                    $errormsg.="<div style='padding:2px;'>" . Common::$lablearray['1728'] .' ' .  Common::$lablearray['1451'] . ' ' . ($tot - 1) . ' ' . Common::$lablearray['1452'] . " 1 <b>" . $data[0]. "</b></div>";
                                    break;
                                endif;  

                            endif;

                            // check if member exists
                            if(preg_match('[M]', $data[0])):
                                $mem_code_array = $Conn->SQLSelect("SELECT members_idno FROM " . TABLE_MEMBERS. " WHERE members_idno='".$data[0]. "'",true);
                    
                                if($mem_code_array[0]['members_idno']==''):
                                    Common::getlables("1728", "", "",Common::$connObj);
                                    $errormsg.="<div style='padding:2px;'>" . Common::$lablearray['1728'] .' ' .  Common::$lablearray['1451'] . ' ' . ($tot - 1) . ' ' . Common::$lablearray['1452'] . " 1 <b>" . $data[0]. "</b></div>";
                                    break;
                                endif;  

                            endif;

                           
                        }else{
                            Common::getlables("1728", "", "",Common::$connObj);
                            $errormsg.="<div style='padding:2px;'>" . Common::$lablearray['1728'] . ' ' . ($tot - 1) . ' ' . Common::$lablearray['1452'] . " 1 " . Common::$lablearray['1453'] . " ".$data[0] ."</div>";
                            break;
                        }

                        // Document type
                        if ($data[1] == "") {
                            Common::getlables("1729", "", "",Common::$connObj);
                            $errormsg.="<div style='padding:2px;'>" . Common::$lablearray['1729'] . ' '. ($tot - 1) . ' ' . Common::$lablearray['1452'] . " 2 " . Common::$lablearray['1453'] . " </div>";
                            break;
                        }

                        // Document Number
                        if ($data[2] == "") {
                            Common::getlables("1731", "", "",Common::$connObj);
                            $errormsg.="<div style='padding:2px;'>" . Common::$lablearray['1731'] . ' '. ($tot - 1) . ' ' . Common::$lablearray['1452'] . " 3 " . Common::$lablearray['1453'] . " </div>";
                            break;
                        }

                        // Issue date
                        if ($data[3] == "") {
                            Common::getlables("1730", "", "",Common::$connObj);
                            $errormsg.="<div style='padding:2px;'>" . Common::$lablearray['1730'] . ' '. ($tot - 1) . ' ' . Common::$lablearray['1452'] . " 4 " . Common::$lablearray['1453'] . " </div>";
                            break;

                        }else{

                            if (!Common::checkDate($data[3]) || strlen($data[3])<=8) {
                                Common::getlables("293,905", "", "",Common::$connObj);
                                $errormsg.="<div style='padding:2px;'>" . Common::$lablearray['293'] .' '.Common::$lablearray['905']. ' <b>' . Common::$lablearray['1454'] . '</b> ' . Common::$lablearray['1451'] . ' ' . ($tot - 1) . ' ' . Common::$lablearray['1452'] . " 4 " . Common::$lablearray['1453'] . " '<b>" . SETTING_DATE_FORMAT . "</b>'<div>";
                                break;
                            }

                        }                      

                        // Expiry date
                        if ($data[4] != "") {
                            if (!Common::checkDate($data[4]) || strlen($data[4])<=8) {
                                Common::getlables("293,1062", "", "",Common::$connObj);
                                $errormsg.="<div style='padding:2px;'>" . Common::$lablearray['293'] .' '.Common::$lablearray['1062']. ' <b>' . Common::$lablearray['1454'] . '</b> ' . Common::$lablearray['1451'] . ' ' . ($tot - 1) . ' ' . Common::$lablearray['1452'] . " 5 " . Common::$lablearray['1453'] . " '<b>" . SETTING_DATE_FORMAT . "</b>'<div>";
                                break;
                            }else{
                                $values_to_post ['document_docexpiry'] = Common::changeDateFromPageToMySQLFormat($data[4]); // Expiry Date
                            }
                        }else{
                            $values_to_post ['document_docexpiry'] = NULL;                  

                        }

                       

                        $values_to_post ['clientcode'] = $data[0]; // Client Code  
                        $values_to_post ['documenttypes_id'] = $data[1]; // Document Type
                        $values_to_post ['document_serial'] = $data[2]; // Document Number
                        $values_to_post ['document_issuedate'] = Common::changeDateFromPageToMySQLFormat($data[3]); // Issue Date
                        $values_to_post ['document_priority'] = $data[4]; // Issueing Authority
                        $values_to_post ['document_issueauthority'] = $data[5]; // Issueing Authority
                        if($errormsg==""){
                            $Conn->SQLInsert(array(TABLE_DOCUMENT => $values_to_post), false);  
                            $Conn->endTransaction(); 
                            
                        }else{
                            $Conn->cancelTransaction();  
                            $errormsg="<div style='padding:2px;'>" .$Conn::$error.' ' . Common::$lablearray['1451'] . ' ' . ($tot - 1) . ' ' . Common::$lablearray['1452'] . " 27 " . Common::$lablearray['1453'] . " '<b>" . SETTING_DATE_FORMAT . "</b>'<div>";
                            break;
                        }

                        
                        if ($Conn::$error!=""):
                            $errormsg="<div style='padding:2px;'>" .$Conn::$error.' ' . Common::$lablearray['1451'] . ' ' . ($tot - 1) . ' ' . Common::$lablearray['1452'] . " 27 " . Common::$lablearray['1453'] . " '<b>" . SETTING_DATE_FORMAT . "</b>'<div>";
                            break;
                        endif;  

                        break;  

                    case 'GRP': // Import Groups
                    case 'BUS':      
                        
                        if ($data[0] == "") {
                            $errormsg.="<div style='padding:2px;'>" . Common::$lablearray['673'] . Common::$lablearray['1451'] . ' ' . ($tot - 1) . ' ' . Common::$lablearray['1452'] . " 3 " . Common::$lablearray['1453'] . " ".$data[0] ."</div>";
                            break;
                        }
                        
                        if ($data[1] != "") {        
                            if (!Common::checkDate($data[1]) || strlen($data[1])<=8) {
                                $errormsg.="<div style='padding:2px;'>" . Common::$lablearray['293'] .' '.Common::$lablearray['1019']. ' <b>' . Common::$lablearray['1454'] . '</b> ' . Common::$lablearray['1451'] . ' ' . ($tot - 1) . ' ' . Common::$lablearray['1452'] . " 2 " . Common::$lablearray['1453'] . " '<b>" . SETTING_DATE_FORMAT . "</b>'</div>";
                                break;
                            }
                        }else{
                            $errormsg.="<div style='padding:2px;'>" . Common::$lablearray['293'] .' '.Common::$lablearray['1019']. ' <b>' . Common::$lablearray['1454'] . '</b> ' . Common::$lablearray['1451'] . ' ' . ($tot - 1) . ' ' . Common::$lablearray['1452'] . " 2 " . Common::$lablearray['1453'] . " '<b>" . SETTING_DATE_FORMAT . "</b>'</div>";
                            break;
                        }
                               
                        if ($data[2] == "") {
                            $errormsg.="<div style='padding:2px;'>" . Common::$lablearray['1726'] . Common::$lablearray['291'] . ' ' . ($tot - 1) . ' ' . Common::$lablearray['1452'] . " 4 " . Common::$lablearray['1453'] . " </div>";
                            break;
                        }

                        if (preg_match('[ACT|INA]', $data[9])):
                                $values_to_post ['entity_regstatus'] = $data[9];  
                        else:
                            Common::getlables("1242,291", "", "",Common::$connObj);
                            $errormsg.="<div style='padding:2px;'>" . Common::$lablearray['1242'] .' '.Common::$lablearray['291']. ' <b>' . Common::$lablearray['1454'] . '</b> ' . Common::$lablearray['1451'] . ' ' . ($tot - 1) . ' ' . Common::$lablearray['1452'] . " 10  (ACT or INA )</div>";
                            break;                         
                        endif;
                        
                        $values_to_post ['branch_code'] = $data[0]; // branch code   
                        

                        // check entity identification code
                        if ($data[14] == "") {   
                            switch($data_code):
                                case 'GRP':
                                    $values_to_post ['entity_idno'] = Common::generateID($values_to_post ['branch_code'].'/'.'G','G','GROUP'); //Group Code
                                    $values_to_post ['entity_type'] ='G';
                                    break;

                                case 'BUS':
                                    $values_to_post ['entity_idno'] = Common::generateID($values_to_post ['branch_code'].'/'.'B','B','BUSINESS'); //Group Code
                                    $values_to_post ['entity_type'] ='B';
                                    break;

                            endswitch;
                          

                        } else {
                            


                            $code_array = $Conn->SQLSelect("SELECT entity_idno FROM " . TABLE_ENTITY. " WHERE entity_idno='".$data[14]. "'",true);
                   
                           if($code_array[0]['entity_idno']!=''):
                               Common::getlables("1093,1725", "", "",Common::$connObj);
                               $errormsg.="<div style='padding:2px;'>" . Common::$lablearray['1093'] .' ' . Common::$lablearray['1725'] . ' ' . Common::$lablearray['1451'] . ' ' . ($tot - 1) . ' ' . Common::$lablearray['1452'] . " 15 <b>" . $data[14]. "</b></div>";
                               break;
                           endif;                           

                           if (preg_match('/'.$data[14].'/i', $str_client_codes)):
                               $errormsg.="<div style='padding:2px;'>" . Common::$lablearray['1093'] .' '.Common::$lablearray['1640']. ' <b>' . Common::$lablearray['1454'] . '</b> ' . Common::$lablearray['1451'] . ' ' . ($tot - 1) . ' ' . Common::$lablearray['1452'] . " 15 " . $data[14]. "</div>";
                               break;
                           endif;
                           
                           $str_client_codes.= $data[14];

                           $values_to_post ['entity_idno'] = $data[14];
                      
                        }  

                        $values_to_post ['entity_regdate'] = Common::changeDateFromPageToMySQLFormat($data[1]); // Registration date                           
                        $values_to_post ['entity_name'] = $data[2]; // Entity Name                               
                        $values_to_post ['entity_postad'] = $data[3]; // Postal Address
                        $values_to_post ['entity_city'] = $data[4]; // City
                        $values_to_post ['entity_addressphysical'] = $data[5]; // Physical Address
                        $values_to_post ['entity_tel1'] = $data[6]; // Telephone 1
                        
                        $values_to_post ['entity_tel2'] = $data[7]; // Telephone 2
                        $values_to_post ['bussinesssector_code'] = $data[8]; //  Bussiness Sector
                        $values_to_post ['entity_regstatus'] = $data[9]; //  Registration Status

                        // TO DO: Add validation for areacode

                        $values_to_post ['areacode_code'] = $data[10]; // Area Code

                        if ($data[11] != "") {        
                            if (!Common::checkDate($data[11]) || strlen($data[11])<=8) {
                                $errormsg.="<div style='padding:2px;'>" . Common::$lablearray['293'] .' '.Common::$lablearray['1019']. ' <b>' . Common::$lablearray['1454'] . '</b> ' . Common::$lablearray['1451'] . ' ' . ($tot - 1) . ' ' . Common::$lablearray['1452'] . " 11 " . Common::$lablearray['1453'] . " '<b>" . SETTING_DATE_FORMAT . "</b>'<div>";
                                break;
                            }

                            $values_to_post ['entity_enddate'] = Common::changeDateFromPageToMySQLFormat($data[11]);
                            
                        } else{
                            $values_to_post ['entity_enddate']   = NULL;
                        }  
                        

                        

                        $values_to_post ['costcenters_code'] = $data[12]; // Cost center
                            
                        
                        $values_to_post ['user_accesscode'] = $_SESSION['user_accesscode'];

                        $values_to_post ['entity_regcode'] = $data[13]; // Registration Code

                        if($errormsg==""){
                            $Conn->SQLInsert(array(TABLE_ENTITY => $values_to_post), false);  
                            $Conn->endTransaction(); 
                            
                        }else{
                            $Conn->cancelTransaction();  
                            $errormsg="<div style='padding:2px;'>" .$Conn::$error.' ' . Common::$lablearray['1451'] . ' ' . ($tot - 1) . ' ' . Common::$lablearray['1452'] . " 27 " . Common::$lablearray['1453'] . " '<b>" . SETTING_DATE_FORMAT . "</b>'<div>";
                            break;
                        }

                        
                        if ($Conn::$error!=""):
                            $errormsg="<div style='padding:2px;'>" .$Conn::$error.' ' . Common::$lablearray['1451'] . ' ' . ($tot - 1) . ' ' . Common::$lablearray['1452'] . " 27 " . Common::$lablearray['1453'] . " '<b>" . SETTING_DATE_FORMAT . "</b>'<div>";
                            break;
                        endif;                                        
                        
                        break;
                       
                    case 'SAVETRAN':
                       
                         if (!Common::checkDate($data[4]) || strlen($data[4])<=8) {
                            $errormsg.="<div style='padding:2px;'>" . Common::$lablearray['230'] . ' ' . Common::$lablearray['1451'] . ' ' . ($tot - 1) . ' ' . Common::$lablearray['1452'] . " 1 " . Common::$lablearray['1453'] . " '<b>" . SETTING_DATE_FORMAT . "</b>'<div>";
                            break 2;
                        }
                        
                       unset($formdata);
                        
                       // FORMAT AMOUNTS 
                       $data[4] = Common::changeDateFromPageToMySQLFormat($data[4]);
                       $data[6] = Common::number_format_locale_compute($data[6]);
                         
                       Common::getlables("1216", "", "",Common::$connObj);
                      
                       if ($data[3] == 'SW' || $data[3] == 'SA' || $data[3] == 'IT') {
                           
                           // CHECK SAVINGS BALANCES
                           Savings::$prodid = $data[1];
                           Savings::$asatdate = $data[0];
                           Savings::$savacc = $data[4];       
                         
                           if (!Savings::getSavingsBalance($data[6])){ 
                                                          
                                echo "MSG:" . Common::$lablearray['1216'] . ' - ' .array_sum(array_column(Savings::$bal_array,'balance')).' '.$data[0].' '.$data[1]; // Insufficient funds on account to complete transaction
                                exit();
                            }
                
                            $data[6] = -1 * $data[6];                         
                         
                       }
                        
                        Common::addKeyValueToArray($formdata, 'TCODE',Common::generateTransactionCode($_SESSION['user_id']));
                       
                
                        Common::addKeyValueToArray($formdata, 'CTYPE', Common::getClientType($data[0])); 
                        Common::addKeyValueToArray($formdata, 'DATE', $data[4]);                        
                        Common::addKeyValueToArray($formdata, 'PRODUCT_PRODID',$data[1]);
                        Common::addKeyValueToArray($formdata, 'VOUCHER', $data[5]);
                        Common::addKeyValueToArray($formdata, 'SAVACC', $data[0]);
                        Common::addKeyValueToArray($formdata, 'AMOUNT', $data[6]);
                        Common::addKeyValueToArray($formdata, 'CHEQNO', $data[10]);
                        Common::addKeyValueToArray($formdata, 'MEMID', $data[2]);
                        Common::addKeyValueToArray($formdata, 'MODE', $data[12]);
                        Common::addKeyValueToArray($formdata, 'TTYPE', $data[3]);
                        Common::addKeyValueToArray($formdata, 'CLIENTIDNO','');
                        Common::addKeyValueToArray($formdata, 'CHEQNO', $data[10]);
                        Common::addKeyValueToArray($formdata, 'GLACC', $data[11]);                  

                        Common::prepareParameters($parameters, 'theid1',$data[0]);
                        Common::prepareParameters($parameters, 'theid2',$data[1]);
                        Common::prepareParameters($parameters, 'code', 'IDEXISTS');
                        Common::prepareParameters($parameters, 'idtype', 'SAVEDETAILS');
                        Common::prepareParameters($parameters, 'branch_code', $formdata['BRANCHCODE']);
                       if($data[0]==''){
                            $save_array = Common::common_sp_call(serialize($parameters), '', Common::$connObj, true);
                       }else{
                           $save_array['client_idno']=$data[0];
                       }
                        Common::addKeyValueToArray($formdata, 'CLIENTIDNO', $save_array['client_idno']);
           
                       
                        if ($data[3] == 'SA' || $data[3] == 'IT') {
                            Common::addKeyValueToArray($formdata, 'SAVACCTO', $data[7]);
                            Common::addKeyValueToArray($formdata, 'PRODUCT_PRODIDTO', $data[8]);                
                            Common::addKeyValueToArray($formdata, 'MEMIDTO', $data[9]);
                            Common::addKeyValueToArray($formdata, 'AMOUNTTO', $data[6]);
                        }
                        
                        $form_data[] = $formdata;
                        
                        break;

                    default:
                        break;
                }
            }
        }

        // 2 d array importations
        // BUIL INSERT 
        if (count($form_data) > 0):
            
            switch ($data_code) {
            
                 case 'LOANAPPL':
                    
                     if($errormsg!=""):
                         echo $errormsg;
                         exit();
                     else:
                        Loan::$isBulkInsert = true;                     
                        Loan::updateLoan($form_data, 'PA'); 
                        
                        if (Common::$lablearray['E01'] != ""):
                            echo 'MSG ' . Common::$lablearray['E01'];
                            exit();
                        endif;
                    endif;
                    break;
                    
                case 'SAVETRAN':
                    Savings::$isBulkInsert = true;
                    Savings::updateSavings($form_data);
                    break;

                case 'GLTRANS':
                    Savings::$isBulkInsert = true;
                    Bussiness::covertArrayToXML($form_data, true);
                    Bussiness::PrepareData(true);

                    if (Common::$error != "") {
                        $errormsg = Common::$error;
                    }
                    break;
                case 'RESCHEDULE':    
                case 'LOANREPAY':
                    Loan::updateLoan($form_data, 'LR');
                    if (Common::$lablearray['E01'] != "") {
                        echo 'MSG ' . Common::$lablearray['E01'];
                        exit();
                    }
                    break;
                    
                default:
                    break;
            }



        endif;
        
        fclose($handle);
        
        $errormsg.= Common::$lablearray['E01'];
        if ($errormsg == "") {
            echo "<div id='div1' style='height:auto;margin:0px;border:1px solid #EEEEEE;'> <div class='success'>" . Common::$lablearray['218'] . "</div></div>";
        } else {
            echo '<div id="div1" style="height:190px;padding:4px;margin:0px;border:1px solid #EEEEEE;overflow:scroll;color:red;">' . $errormsg . "</div>";
        }

        break;

//    case 'frmgeneralsettings':
//        switch ($_POST['action']) {
//            case 'add':
//                //  $objects = (array)json_decodeData($_POST['pageparams']);					
//                //  $formdata = array_flatten(Common::convertobjectToArray($objects['pageinfo']));
//                $formdata = Common::decodeSerialisedPagedata($_POST['pageparams']);
//                $Conn->ReferenceFieldList['configuration'] = array('configuration_key' => 'NAME_OF_INSTITUTION');
//
//                $Conn->SQLUpdate(array('configuration' => array('configuration_value' => $formdata['NAME_OF_INSTITUTION'])), false);
//
//                $Conn->ReferenceFieldList['configuration'] = array('configuration_key' => 'TELEPHONE');
//                $Conn->SQLUpdate(array('configuration' => array('configuration_value' => $formdata['TELEPHONE'])), false);
//
//                $Conn->ReferenceFieldList['configuration'] = array('configuration_key' => 'SETTING_STUDENT_PHOTO_DIR_PATH');
//                $Conn->SQLUpdate(array('configuration' => array('configuration_value' => $formdata['SETTING_STUDENT_PHOTO_DIR_PATH'])), false);
//
//                $Conn->ReferenceFieldList['configuration'] = array('configuration_key' => 'SETTTING_CURRENCY_ID');
//                $Conn->SQLUpdate(array('configuration' => array('configuration_value' => $formdata['SETTTING_CURRENCY_ID'])), false);
//
//                $Conn->ReferenceFieldList['configuration'] = array('configuration_key' => 'SETTING_DATE_FORMAT');
//                $Conn->SQLUpdate(array('configuration' => array('configuration_value' => $formdata['SETTING_DATE_FORMAT'])), false);
//
//                $Conn->ReferenceFieldList['configuration'] = array('configuration_key' => 'SETTING_DEFAULT_LANGUAGE');
//                $Conn->SQLUpdate(array('configuration' => array('configuration_value' => $formdata['SETTING_DEFAULT_LANGUAGE'])), false);
//
//                $Conn->ReferenceFieldList['configuration'] = array('configuration_key' => 'STARTFINYEAR');
//                $Conn->SQLUpdate(array('configuration' => array('configuration_value' =>Common::changeDateFromPageToMySQLFormat($formdata['STARTFINYEAR']))), false);
//
//                $Conn->ReferenceFieldList['configuration'] = array('configuration_key' => 'SETTING_PROFIT_LOSS_ACC');
//                $Conn->SQLUpdate(array('configuration' => array('configuration_value' => $formdata['SETTING_PROFIT_LOSS_ACC'])), false);
//
//                $Conn->ReferenceFieldList['configuration'] = array('configuration_key' => 'SETTING_INTERBRANCH_ACC');
//                $Conn->SQLUpdate(array('configuration' => array('configuration_value' => $formdata['SETTING_INTERBRANCH_ACC'])), false);
//
//                $Conn->ReferenceFieldList['configuration'] = array('configuration_key' => 'SETTING_POSTING_CLOSED_PERIOD_SL');
//                $Conn->SQLUpdate(array('configuration' => array('configuration_value' => $formdata['SETTING_POSTING_CLOSED_PERIOD_SL'])), false);
//
//                $Conn->ReferenceFieldList['configuration'] = array('configuration_value' => 'SETTING_POSTING_CLOSED_PERIOD_GL');
//                $Conn->SQLUpdate(array('configuration' => array('configuration_key' => $formdata['SETTING_POSTING_CLOSED_PERIOD_GL'])), false);
//                
//                $Conn->ReferenceFieldList['configuration'] = array('configuration_value' => 'SETTING_PAYMODE');
//                $Conn->SQLUpdate(array('configuration' => array('configuration_key' => $formdata['SETTING_PAYMODE'])), true);
//
//                
//                echo '1111111';
//                break 2;
//               
//            default:
//                break 2;
//        }
//        
//        break;
    case 'frmproducts':
         switch ($_POST['action']) {

         case 'add':
         case 'edit':
            if ($formdata['txtproduct'] == "") {
               getlables("1521");
               echo "MSG:" . $lablearray['1521'];
               exit();
           }
            if ($formdata['txtproductcode'] == "") {
               getlables("1521");
               echo "MSG:" . $lablearray['1521'];
               exit();
           }
           
           Common::replace_key_function($formdata, 'txtproductcode', 'PCODE');
           
           Common::replace_key_function($formdata, 'txtproduct', 'PNAME');           
           
           Common::replace_key_function($formdata, 'action', 'ACTION');
           
           Common::replace_key_function($formdata, 'theid', 'OPCODE');
                      
           $form_data[]= $formdata;
           
           Products::updateProducts($form_data);
           
            echo "1111111";
            break 2;
        
        default:
            break 2;
        }
        
        
        break;
    case 'frmsavaccounts':
        switch ($_POST['action']) {

            case 'add':
            case 'update':

                getlables("1040,1199,218");

                if ($formdata['branch_code'] == "") {
                    getlables("1521");
                    echo "MSG:" . $lablearray['1521'];
                    exit();
                }

                if ($formdata['product_prodid'] == "") {
                    getlables("1435");
                    echo "MSG " . $lablearray['1435'];
                    exit();
                }

                if ($formdata['client_idno'] == "") {
                    getlables("1199");
                    echo "INFO." . $lablearray['1199'];
                    exit();
                }
                
                $formdata['txtOpenDate'] = Common::changeDateFromPageToMySQLFormat($formdata['txtOpenDate']);
                $formdata['txtamount'] = Common::number_format_locale_compute($formdata['txtamount']);
                $formdata['txtrepaysavtamount'] = Common::number_format_locale_compute($formdata['txtrepaysavtamount']);

                // replace invalid key

                Common::replace_key_function($formdata, 'txtOpenDate', 'DATE');
                Common::replace_key_function($formdata, 'txtvoucher', 'VOUCHER');
                Common::replace_key_function($formdata, 'txtamount', 'AMOUNT');
                Common::replace_key_function($formdata, 'product_prodid', 'PRODUCT_PRODID');
                Common::replace_key_function($formdata, 'PAYMODES', 'MODE');
                Common::replace_key_function($formdata, 'CMBFREQUENCY', 'FREQ');
                Common::replace_key_function($formdata, 'txtrepaysavtamount', 'RSAMOUNT');
                Common::replace_key_function($formdata, 'LOANPROD', 'LPRODID');
                Common::replace_key_function($formdata, 'client_idno', 'CLIENTIDNO');
                Common::replace_key_function($formdata, 'branch_code', 'BRANCHCODE');

                $formdata['REPAYSAVAMOUNT']= $formdata['REPAYSAVAMOUNT']??0;
                $formdata['TTYPE'] = $formdata['TTYPE']??'';

                if($formdata['MODE'] == 'CA'){
                   Common::replace_key_function($formdata, 'cashaccounts_code', 'GLACC');                    
                }
                
                if ($formdata['MODE'] == 'CQ') {
                    Common::replace_key_function($formdata, 'cheques_no', 'CHEQNO');

                    if ($formdata['CHEQNO'] == "") {
                        getlables("185");
                        echo "INFO." . $lablearray['185'];
                        exit();
                    }

                    Common::replace_key_function($formdata, 'bankbranches_id', 'BACCNO');
                }

                $formdata['TTYPE'] = (($formdata['AMOUNT'] > 0) ? 'SD' : '' );

                if ($formdata['REPAYSAVAMOUNT'] > 0 && $formdata['FREQ'] == "") {

                    getlables("1198");
                    echo "MSG:" . $lablearray['1198'];
                    exit();
                }

                $formdata['MEMID'] = '';
             
                $acc_array = $Conn->SQLSelect("SELECT productconfig_value FROM " . TABLE_PRODUCTCONFIG . " WHERE productconfig_paramname='CLIENTCODE_IS_SAVACC' AND product_prodid='".$formdata['PRODUCT_PRODID'] . "' AND branch_code='".$formdata['BRANCHCODE']."'",true);
                
                $formdata['CTYPE'] = Common::getClientType($formdata['CLIENTIDNO']);
            

                if($acc_array[0]['productconfig_value']=='1'):
                    $formdata['SAVACC'] = $formdata['CLIENTIDNO'];                
                else: 
                     $formdata['SAVACC'] = Common::generateID($formdata['CLIENTIDNO'], 'S','SAVACC',$formdata['CLIENTIDNO']);                
                endif;   
                     
               
                $formdata['CTYPE'] = Common::getClientType($formdata['CLIENTIDNO']);
            
                // if ($formdata['action'] == 'update') {
                //     echo "MSG:" . Common::$lablearray['E01'];
                //     break 2;
                // }
                
                $formdata['TTYPE']='OSA';
                
                if($formdata['AMOUNT']>0){                    
                    $formdata['TCODE'] =Common::generateTransactionCode($_SESSION['user_id']);
                }
                
                $form_data[]= $formdata;
                Savings::updateSavings($form_data);

                if(Common::$lablearray['E01']!=""){                    
                    echo 'ERR '.Common::$lablearray['E01'];
                    exit();
                }
                getlables("218");
                echo "1111111";
                break 2;

            case 'edit':
                break 2;
            default:
                break 2;
        }
        break;
    case 'frmcalcint': // post savings interest
        getlables("621,1434,1435");

        switch ($_POST['action']) {
            case 'add':
                if ($formdata['branch_code'] == "") {
                    getlables("1436");
                    echo "MSG:" . $lablearray['1436'];
                    exit();
                }

                if ($formdata['txtDate'] == "") {

                    echo "MSG:" . $lablearray['621'];
                    exit();
                }

                if ($formdata['product_prodid'] == "") {

                    echo "MSG:" . $lablearray['1434'];
                    exit();
                }

                if (substr($formdata['product_prodid'], 0, 1) != "S") {
                    echo "MSG:" . $lablearray['1435'];
                    exit();
                }

                Common::prepareParameters($parameters, 'tdate', Common::changeDateFromPageToMySQLFormat($formdata['txtDate']));
                Common::prepareParameters($parameters, 'product_prodid', $formdata['product_prodid']);
                Common::prepareParameters($parameters, 'branch_code', $formdata['branch_code']);
                Common::prepareParameters($parameters, 'user_id', $_SESSION['user_id']);
                Common::prepareParameters($parameters, 'code', 'CALCSAVINT');
                Common::prepareParameters($parameters, 'plang', P_LANG);
                Common::prepareParameters($parameters, 'action', 'post');
                Common::prepareParameters($parameters, 'client_regstatus', $formdata['client_regstatus']);
                Common::prepareParameters($parameters, 'tcode', Common::generateTransactionCode($_SESSION['user_id']));

                $results = Common::common_sp_call(serialize($parameters), '', $Conn, true);

                if ($results['state'] == '0') {
                    echo "WAR" . $results['msg'];
                } else {
                    echo '1111111';
                }
                break;
            default:
                break;
        }


        break;
        
    case 'frmTDeposit':
         switch ($_POST['action']) {
            case 'WITHDRAW':
           
                Common::getlables("1618,1612,186,1611,185,186,1339,1674", "", "", Common::$connObj);                
                
                $theid = Common::tep_db_prepare_input($_POST['keyparam']);
                
                Tdeposit::$transactioncode = $theid;
                 
                $tdeposit_array = Tdeposit::getTimeDeposit();
                
              //  $tdeposit_array = $Conn->SQLSelect("SELECT timedeposit_status FROM " . TABLE_TDEPOSITTRANS . " tr,".TABLE_TDEPOSIT." t WHERE  tr.timedeposit_number=t.timedeposit_number  AND tr.transactioncode='" .$theid."'");
                
                if(count($tdeposit_array)>0): 
                    
                    if($formdata['TDSTATUS']=='TW' && $tdeposit_array[0]['status']=='TW'):
                        echo "MSG " . Common::$lablearray['1618'];
                        exit();
                    endif;
                    
                endif;
                
                
                 // check see if a transaction is selected
                if($theid==''):                              
                    echo "MSG" .Common::$lablearray['1339'];
                    exit();
                endif;
                
                 if ($formdata['txtDate'] == ''):
                    echo "MSG:" . Common::$lablearray['186'];
                    exit();
                endif;
                
                 // check if time deposit is withdrawn
//                if($formdata['txtDate']==''):                              
//                    echo "MSG:" .Common::$lablearray['186'];
//                    exit();
//                endif;
                
                 // get time deposit details  
              //  TDeposit::$transactioncode = $theid;
                 
              // $tdeposit_array  =   TDeposit::getTimeDeposit();               
                
               // $matdate = Common::changeMySQLDateToPageFormat(TDeposit::$tdeposit_array['matdate']);
               // $curdate = Common::getcurrentDateTime('D');
                
                // check if time deposit is withdrawn
                if(TDeposit::$tdeposit_array['status']=='TW'):
                    getlables("1612");                
                    echo "MSG " . Common::$lablearray['1612'];
                    exit();
                endif;
                
                $curdate = Common::getcurrentDateTime('D');
                $matdate = Common::changeMySQLDateToPageFormat($tdeposit_array[0]['ddate']);
                
                // check see of maturity date has arrived
                if($curdate < $matdate):                                  
                    echo "MSG " . Common::$lablearray['1611'];
                    exit();                
                endif;
                
                Common::addKeyValueToArray($formdata, 'TDATE', $tdeposit_array[0]['timedeposit_date']); 
                Common::addKeyValueToArray($formdata, 'CURDATE',$curdate);    
                Common::addKeyValueToArray($formdata, 'MATDATE',$tdeposit_array[0]['timedeposit_matdate']); 
                Common::addKeyValueToArray($formdata, 'OINTAMT',0);    
                              
                Common::addKeyValueToArray($formdata, 'TDNO',$tdeposit_array[0]['timedeposit_number']);    
                Common::addKeyValueToArray($formdata, 'INTCAP',isset($tdeposit_array[0]['timedeposit_intcapital'])?$tdeposit_array[0]['timedeposit_intcapital']:'N');                
                Common::addKeyValueToArray($formdata, 'TCODE',$theid);
                Common::addKeyValueToArray($formdata, 'OTCODE',$theid);
                Common::addKeyValueToArray($formdata, 'STATUS','TW');            
                Common::replace_key_function($formdata, 'txtDate', 'DATE'); // for other option
                Common::replace_key_function($formdata, 'txtvoucher','VOUCHER');
                Common::addKeyValueToArray($formdata, 'THEID',$_POST['theid']);
                Common::addKeyValueToArray($formdata, 'INTTYPE',$tdeposit_array[0]['timedeposit_instype']);
                Common::replace_key_function($formdata, 'product_prodid', 'PRODUCT_PRODID');
                $formdata['PRODUCT_PRODID'] = $tdeposit_array[0]['product_prodid'];
                
                Common::addKeyValueToArray($formdata, 'INT',$tdeposit_array[0]['timedeposit_interestrate']);
                Common::addKeyValueToArray($formdata, 'PERIOD',$tdeposit_array[0]['timedeposit_period']);
                Common::addKeyValueToArray($formdata, 'AMOUNT',$tdeposit_array[0]['timedeposit_amount']);
                Common::replace_key_function($formdata, 'product_prodidfr', 'PRODUCT_PRODIDFR');     
                Common::addKeyValueToArray($formdata, 'CLIENTIDNO',$tdeposit_array[0]['client_idno']);
                Common::addKeyValueToArray($formdata, 'BRANCHCODE',Common::extractBranchCode($tdeposit_array[0]['client_idno']));              
                Common::replace_key_function($formdata, 'PAYMODES', 'MODE');               
                
                Common::replace_key_function($formdata, 'client_idno', 'CLIENTIDNO');            
                Common::replace_key_function($formdata, 'cashaccounts_code', 'GLACC');
                Common::addKeyValueToArray($formdata, 'MEMID','');
                Common::addKeyValueToArray($formdata, 'TTYPE',$formdata['STATUS']);
                
                if ($formdata['MODE'] == 'CQ') {

                    Common::replace_key_function($formdata, 'cheques_no', 'CHEQNO');

                    if ($formdata['CHEQNO'] == "") {                       
                        echo "INFO.".Common::$lablearray['185'];
                        exit();
                    }

                    Common::replace_key_function($formdata, 'bankbranches_id', 'BACCNO');
                    Common::addKeyValueToArray($formdata, 'STAT', 'Q');
                }

                $td_amounts_array = Common::get_array_elements_with_key($formdata, 'AMT_');
                Common::deleteElementByValue('0.0', $td_amounts_array);

                $temp_data = $formdata;

                foreach ($td_amounts_array as $thekey => $theval) {

                    $formdata['MEMID'] = Common::replace_string($thekey, 'AMT_', '');

                    $formdata['MEMID'] = Common::replaces_underscores($formdata['MEMID']);
               
                    $formdata['AMOUNT'] = $formdata[$thekey];

                    $nAmount = $nAmount + $formdata['AMOUNT'];

                    $formdata['POSTTOSL'] = true;

                    $formdata['POSTTOGL'] = false;

                    $form_data[] = $formdata;
                }


             //  $formdata = $temp_data;
               
               if (count($td_amounts_array) > 0):
                    if ($formdata['AMOUNT']!=$nAmount):
                        echo "INFO.".Common::$lablearray['1674'];
                        exit();
                    endif;
                endif;
                
                 if (!preg_match('[G]', $formdata['CLIENTIDNO'])):
                    $formdata['POSTTOSL'] = true;
                endif;
                                
                $formdata['POSTTOGL'] = true;
                
                $form_data[] = $formdata;                     
                
                Tdeposit::updateTimeDeposit($form_data);                   
                
                if(Common::$lablearray['E01']!=""):
                    echo 'WAR:'.Common::$lablearray['E01'];
                else:
                    echo "1111111";               
                endif;       
                
                break;
             
            case 'REVERSE':

                $theid = Common::tep_db_prepare_input($_POST['theid']);

                if ($theid == "") {
                    getlables("1339");
                    echo "MSG:" . $lablearray['1339'];
                    exit();
                }
                Common::reverseTransaction(array(TRIM($theid)), 'T', $_SESSION['user_id'], Common::$connObj);
                echo "1111111"; 
                break;
                
            case 'add':
            case 'update':    
                
                Common::getlables("1674,1625,186,1621,1198,1617,185,1199,1209,1210,1602,1603,218", "", "", Common::$connObj);
                
                if($_POST['keyparam']!=""):
                    Tdeposit::$transactioncode = Common::tep_db_prepare_input($_POST['keyparam']);
                    Tdeposit::getTimeDeposit();               
                    $matdate = Common::changeMySQLDateToPageFormat(Tdeposit::$tdeposit_array['matdate']);
                endif;           

                $curdate = Common::changeMySQLDateToPageFormat(Common::getcurrentDateTime('D'));
                
                switch($formdata['TDSTATUS']): 
                    
                case 'TR': 
                    if ($_POST['keyparam']==""):
                        echo "MSG " . Common::$lablearray['1621'];
                        exit();
                    endif;

                        // check see of maturity date has arrived
                    if($curdate < $matdate):                                      
                        echo "MSG " . Common::$lablearray['1620'];
                        exit();                                                                  
                    endif;
                    
                    // Common::addKeyValueToArray($formdata, 'TDATE', Tdeposit::$tdeposit_array['ddate']); 
                    Common::addKeyValueToArray($formdata, 'CURDATE',Common::changeDateFromPageToMySQLFormat($curdate));    
                    Common::addKeyValueToArray($formdata, 'MATDATE',Common::changeDateFromPageToMySQLFormat($matdate));
                    Common::addKeyValueToArray($formdata, 'OINTAMT',Tdeposit::$tdeposit_array['intamt']);
                    Common::addKeyValueToArray($formdata, 'OMATVAL',Tdeposit::$tdeposit_array['matval']);
                    break;  
               
                case 'TM':
                     // check see of maturity date has arrived
                    if($curdate >= $matdate):                                      
                        echo "MSG:" . Common::$lablearray['1625'];
                        exit();                                                                  
                    endif;
                    
                    break;
                    
                default:
                    break;
                endswitch;
//                if(count($tdeposit_array)>0 && $formdata['TDSTATUS']=='TD'): 
//                    
//                    if($formdata['TDSTATUS']=='TD' && $tdeposit_array[0]['timedeposit_status']=='TD'):
//                        echo "MSG:" . Common::$lablearray['1617'];
//                        exit();
//                    endif;
//                    
//                endif;
              
                
               
                if ($formdata['product_prodid'] == "") { //CHECK TIME DEPOSIT
                    getlables("1198");
                    echo "MSG" . Common::$lablearray['1198'];
                    exit();
                }

                if ($formdata['client_idno'] == "") { // CHECK CLIENT
                   
                    echo "MSG" . Common::$lablearray['1199'];
                    exit();
                }

                if ($formdata['TDSTATUS'] == "") { //CHECK TRANSACTION TYPE
                   
                    echo "MSG" . Common::$lablearray['1209'];
                    exit();
                }

                if ($formdata['txtamount'] == "" || $formdata['txtamount'] <= 0) { // CHECK AMOUNT
                  
                    echo "MSG" . Common::$lablearray['1210'];
                    exit();
                }          
                           
                if ($formdata['txtperiod'] == '') { // TENURE
                  
                    echo "MSG" . Common::$lablearray['1602'];
                    exit();
                }
                
                
                if ($formdata['INSTYPE'] == '') {// INTEREST CALCULATION PERIOD
                   
                    echo "MSG" . Common::$lablearray['1603'];
                    exit();
                }

               
                if ($formdata['txtDate'] == '') { // CHECK DATE                   
                    echo "MSG" . Common::$lablearray['186'];
                    exit();
                }

               
                // TO DO:
                // ADD VALIDATION FOR TRANSACTION TYPES. MAKE SURE ALL PASSED TYPE ARE KNOWN IN THE SYSTEM           
                $formdata['txtamount'] = Common::number_format_locale_compute($formdata['txtamount']);
         
                if (isset($formdata['branch_code'])) {
                    Common::replace_key_function($formdata, 'branch_code', 'branch_code');
                } else {
                    $formdata['branch_code'] = BRANCHCODE;
                }
                
                 // replace invalid key                            
                if ($formdata['txtamount'] > 0) {    
              
                    
//                    Tdeposit::$incomingvars['intcap'] = isset($formdata['chkintCapital'])? $formdata['chkintCapital']:'N';
//                    Tdeposit::$incomingvars['amount']   = $formdata['txtamount'];
//                    Tdeposit::$incomingvars['int']      = $formdata['txtintrate'];
//                    Tdeposit::$incomingvars['inttype']      = $formdata['INSTYPE'];                
//                    Tdeposit::$incomingvars['period']   = $formdata['txtperiod'];              
//                    Tdeposit::$incomingvars['status']   = $formdata['TDSTATUS'];                               
//                    Tdeposit::$incomingvars['date']   = $formdata['txtDate'];
                    
                   // Tdeposit::calculateInterest();    
                    
                   // $matdate =  Common::calculateDate('+', $formdata['txtDate'], Tdeposit::$incomingvars['period'], $formdata['INSTYPE'],0);                
               
                   // $formdata['txtDate'] = Common::changeDateFromPageToMySQLFormat($formdata['txtDate']);
                    
                    $tcode = Common::generateTransactionCode($_SESSION['user_id']); 
                    
                    Common::replace_key_function($formdata, 'chkintCapital','INTCAP');
                    // Common::addKeyValueToArray($formdata, 'MATVAL', Tdeposit::$incomingvars['matval']);
                   // Common::addKeyValueToArray($formdata, 'MATDATE', Common::changeDateFromPageToMySQLFormat($matdate['matdate']));
                   // $formdata['MATDATE'] = Common::changeDateFromPageToMySQLFormat($matdate['date']);
                    Common::replace_key_function($formdata, 'txttdnumber','TDNO');
                    Common::addKeyValueToArray($formdata, 'TCODE',$tcode);
                    Common::addKeyValueToArray($formdata, 'OTCODE',Tdeposit::$transactioncode);
                    Common::replace_key_function($formdata, 'txtperiod', 'PERIOD');
                    Common::replace_key_function($formdata, 'transactioncode', 'TCODE');
                    Common::replace_key_function($formdata, 'txtDate', 'DATE');
                    Common::replace_key_function($formdata, 'txtvoucher', 'VOUCHER');
                    Common::replace_key_function($formdata, 'txtintrate', 'INT');
                    Common::replace_key_function($formdata, 'txtperiod', 'PERIOD');                    
                    Common::addKeyValueToArray($formdata, 'INTTYPE',$formdata['INSTYPE']);
                    Common::addKeyValueToArray($formdata, 'INTTYPE',0); 
                   // Common::replace_key_function($formdata, 'branch_code', Common::extractBranchCode($formdata['client_idno']));
                    
                    $formdata['INTCAP']=isset($formdata['chkintCapital'])? $formdata['chkintCapital']:'N';
                    Common::replace_key_function($formdata, 'txtsavaccount', 'SAVACC');
                    Common::replace_key_function($formdata, 'product_prodidfr', 'PRODUCT_PRODIDFR');
                    Common::replace_key_function($formdata, 'product_prodid', 'PRODUCT_PRODID');
                    
                    if ($formdata['cmbsavaccounts']!= ""):
                        $savaccounts = explode(":", $formdata['cmbsavaccounts']);
                        $formdata['SAVACC'] = $savaccounts[0];
                        $formdata['PRODUCT_PRODIDFR'] = $savaccounts[1];                    
                    endif;

                    Common::replace_key_function($formdata, 'PAYMODES', 'MODE');
                    Common::replace_key_function($formdata, 'TDSTATUS', 'STATUS');
                    Common::replace_key_function($formdata, 'txtamount', 'AMOUNT');
                     
                    if($formdata['STATUS']=='TR'):
                       Common::addKeyValueToArray($formdata, 'OAMOUNT',Tdeposit::$tdeposit_array['amount']);                           
                    endif;                    
                    
                    
                    Common::replace_key_function($formdata, 'client_idno', 'CLIENTIDNO');
                    Common::replace_key_function($formdata, 'branch_code', 'BRANCHCODE');
                    
                    Common::replace_key_function($formdata, 'cashaccounts_code', 'GLACC');
                    
                    if ($formdata['MODE'] == 'CQ') {

                        Common::replace_key_function($formdata, 'cheques_no', 'CHEQNO');

                        if ($formdata['CHEQNO'] == "") {                           
                            echo "INFO.".Common::$lablearray['185'];
                            exit();
                        }

                        Common::replace_key_function($formdata, 'bankbranches_id', 'BACCNO');
                        Common::addKeyValueToArray($formdata, 'STAT', 'Q');
                    }

                if (!preg_match('[G]', $formdata['CLIENTIDNO'])):
                    $formdata['POSTTOSL'] = true;
                endif;

                 $formdata['POSTTOGL'] = true;
                 
                $form_data[] = $formdata;
                 
                $td_amounts_array = Common::get_array_elements_with_key($formdata, 'AMT_');
                Common::deleteElementByValue('0.0', $td_amounts_array);

                if(count($td_amounts_array)>0):
                    
          
                    $temp_data = $formdata;

                    foreach ($td_amounts_array as $thekey => $theval) {

                        $formdata['MEMID'] = Common::replace_string($thekey, 'AMT_', '');

                        $formdata['MEMID'] = Common::replaces_underscores($formdata['MEMID']);

                        $formdata['AMOUNT'] = $formdata[$thekey];

                        $nAmount = $nAmount + $formdata['AMOUNT'];

                        $formdata['POSTTOSL'] = true;

                        $formdata['POSTTOGL'] = false;

                        $form_data[] = $formdata;
                    }
                    
                    $formdata = $temp_data;
                   
                    if ($formdata['AMOUNT']!= $nAmount):
                        echo "INFO." . Common::$lablearray['1674'];
                        exit();
                    endif;
                
                endif;
                 
                 // $form_data[] = $formdata;

                Tdeposit::updateTimeDeposit($form_data);

                if (Common::$lablearray['E01'] != ''):
                    echo 'ERR ' . Common::$lablearray['E01'];
                    exit();
                endif;
                }
                if ($formdata['STATUS']=='TR' || $formdata['STATUS']=='TW' || $formdata['STATUS']=='TD'):
                     echo "1111111"; 
                
                else:
                    echo "MSG.".Common::$lablearray['218'];
                endif; 
                break;
         }
        break;
        
    case 'frmSave':    
        
        Savings::$connObj = $Conn;
        $accounts_array = array();
        $products_array = array();

        switch ($_POST['action']) {
            case 'reverse':
                
                $theid = Common::tep_db_prepare_input($_POST['theid']);

                if ($theid == "") {
                    getlables("1339");
                    echo "MSG:" . $lablearray['1339'];
                    exit();
                }

                Common::reverseTransaction(array(TRIM($theid)), 'S', $_SESSION['user_id'], $Conn);
                echo "1111111"; 
                break;
                
            case 'add':
            case 'update':

                if ($formdata['product_prodid'] == "") {
                    getlables("1198");
                    echo "INFO:" . $lablearray['1198'];
                    exit();
                }

                if ($formdata['client_idno'] == "") {
                    getlables("1199");
                    echo "INFO:" . $lablearray['1199'];
                    exit();
                }

                if ($formdata['ttype'] == "") {
                    getlables("1209");
                    echo "INFO:" . $lablearray['1209'];
                    exit();
                }

                if ($formdata['txtamount'] == "" || $formdata['txtamount'] <= 0) {
                    getlables("1210");
                    echo "INFO:" . $lablearray['1210'];
                    exit();
                }

                // check if we are transfering to savings
                if ($formdata['txtDate'] == '') {
                    getlables("186");
                    echo "INFO:" . $lablearray['186'];
                    exit();
                }

                if ($formdata['client_idno'] == "") {
                    getlables("1199");
                    echo "MSG:" . $lablearray['1199'];
                    exit();
                }

                // removed invalid characters-to prevent SQL injection
                //$formdata = Common::tep_db_prepare_input(array_flatten(Common::convertobjectToArray($objects['pageinfo'])));					
                
                $tmpdate = $formdata['txtDate'];
                
                $formdata['txtDate'] = Common::changeDateFromPageToMySQLFormat($formdata['txtDate']);
                
                $formdata['txtamount'] = Common::number_format_locale_compute($formdata['txtamount']);
                
                if(isset($formdata['txtchargeamount'])):
                   $formdata['txtchargeamount'] = Common::number_format_locale_compute($formdata['txtchargeamount']); 
                   Common::replace_key_function($formdata, 'txtchargeamount', 'CHARGE');
                endif;
       
                // TO DO:
                // ADD VALIDATION FOR TRANSACTION TYPES. MAKE SURE ALL PASSED TYPE ARE KNOWN IN THE SYSTEM           
               if ($formdata['ttype'] == 'SA'):
                    $formdata['MODE'] ='';
               endif;
               
                
                if ($formdata['ttype'] == 'SA' || $formdata['ttype'] == 'SW'):
                    
                    $nTotwithdraw = $formdata['txtamount'] + $formdata['CHARGE'];
                   
                
                    Savings::$savaccid = $formdata['theid'];
                
                 
                   // Savings::$prodid = $formdata['product_prodid'];
                  //  Savings::$asatdate = $formdata['txtDate'];
                    
                   
                    if (!Savings::getSavingsBalance($nTotwithdraw)) {
                        getlables("1216");
                        echo "MSG:" . $lablearray['1216'] . ' - ' . $grpbalance; // Insufficient funds on account to complete transaction
                        exit();
                    }
                    
                endif;
                
                // replace invalid key                            
                if ($formdata['txtamount'] > 0) {

                    $formdata['transactioncode'] = Common::generateTransactionCode($_SESSION['user_id']);
                    Common::replace_key_function($formdata, 'transactioncode', 'TCODE');
                    Common::replace_key_function($formdata, 'txtDate', 'DATE');
                    Common::replace_key_function($formdata, 'txtvoucher', 'VOUCHER');
                    Common::replace_key_function($formdata, 'txtamount', 'AMOUNT');
                    Common::replace_key_function($formdata, 'txtsavaccount', 'SAVACC');
                    Common::replace_key_function($formdata, 'PAYMODES', 'MODE');
                    Common::replace_key_function($formdata, 'ttype', 'TTYPE');
                    Common::replace_key_function($formdata, 'product_prodid', 'PRODUCT_PRODID');
                    Common::replace_key_function($formdata, 'client_idno', 'CLIENTIDNO');
                    Common::replace_key_function($formdata, 'branch_code', 'BRANCHCODE');
                    Common::replace_key_function($formdata, 'product_prodidto', 'PRODUCT_PRODIDTO');
                    Common::replace_key_function($formdata, 'cashaccounts_code', 'GLACC');
                    
                    Common::addKeyValueToArray($formdata, 'POSTTOGL',true);
                    Common::addKeyValueToArray($formdata, 'POSTTOSL',true);
                    Common::addKeyValueToArray($formdata, 'ACCOUNTSTO','');
                    
                    $formdata['BRANCHCODE'] = Common::extractBranchCode($formdata['CLIENTIDNO']);
                   
                    if ($formdata['MODE'] == 'CQ') {

                        Common::replace_key_function($formdata, 'cheques_no', 'CHEQNO');

                        if ($formdata['CHEQNO'] == "") {
                            getlables("185");
                            echo "INFO." . $lablearray['185'];
                            exit();
                        }

                        Common::replace_key_function($formdata, 'bankbranches_id', 'BACCNO');
                        Common::addKeyValueToArray($formdata, 'STAT', 'Q');
                    }
                    
                    if ($formdata['TTYPE'] == 'SA' || $formdata['TTYPE'] == 'SW') {                        
                        $formdata['AMOUNT'] = -1 * $formdata['AMOUNT'];                        
                    }  
                    
                    $form_data[] = $formdata;
                    
                    // check see if its is savings transfers                 
                    if ($formdata['TTYPE'] == 'SA') {

                        $nAmount = 0;
                        
                        $formdata['AMOUNT'] = abs($formdata['AMOUNT']);
                         
                        $tranfer_amounts_array = Common::get_array_elements_with_key($formdata, 'txt_acc_amt_to_');
                                               
                        Common::deleteElementByValue('0.0',$tranfer_amounts_array);
                        
                        $temp_data = $formdata; 
                        
                        foreach ($tranfer_amounts_array as $thekey => $theval) {

                            $theid = Common::replace_string($thekey, 'txt_acc_amt_to_', '');

                            $theid = Common::replaces_underscores($theid);
                            
                            $formdata['AMOUNT'] = $formdata[$thekey];
                            
                            if (preg_match('[M]', $theid)):
                                $acc_array = $Conn->SQLSelect("SELECT a.client_idno,a.savaccounts_account,a.product_prodid FROM " . TABLE_SAVACCOUNTS . " a, ".TABLE_MEMBERS." m WHERE m.entity_idno =a.client_idno AND m.members_idno='" . $theid . "'");
                                $formdata['SAVACC'] = $acc_array[0]['savaccounts_account'];
                                $formdata['MEMID'] = $theid;
                            else:
                                $acc_array = $Conn->SQLSelect("SELECT client_idno,savaccounts_account,product_prodid FROM " . TABLE_SAVACCOUNTS . " WHERE  savaccounts_id='" . $theid . "'");
                            endif;
                            
                            $ctype = Common::getClientType($acc_array[0]['client_idno']);

                            //$aLines[] = array('AMOUNT' => $key, 'TTYPE' => 'SD', 'PRODUCT_PRODID' => $formdata['PRODUCT_PRODID'], 'CTYPE' => $ctype, 'GLACC' => '', 'TRANCODE' => 'SD000', 'BANKID' => '', 'SIDE' => 'CR', 'SAVACC' => substr($str, 15, strlen($str)), 'DATE' => $formdata['DATE']);

                            $nAmount = $nAmount + $key;
                            
                            
                            
                            // $accounts_to_array[] = array('SAVACCTO' => substr($str, 15, strlen($str)), 'AMOUNTTO' => $key);
                            $formdata['ACCOUNTSTO'] = $acc_array[0]['savaccounts_account'];
                            //$formdata['TTYPE'] = 'SD';
                            $formdata['PRODUCT_PRODIDTO'] = $acc_array[0]['product_prodid'];
                            $formdata['CLIENTIDNO'] = $acc_array[0]['client_idno'];
                            $formdata['BRANCHCODE'] = Common::extractBranchCode($acc_array[0]['client_idno']);
                            
                            
                            $formdata['POSTTOSL']= true;
                            
                            $formdata['POSTTOGL']= true;
                                                        
                            $form_data[] = $formdata;
                        }
                    } 
                    
                    $formdata['MEMID'] = '';
                    
                   // $formdata = ($temp_data??""); 
                   // unset($temp_data);                  
                    
                       // CHECK SEE IF ITS A GROUP TRANSACTION
                     if (preg_match('[G]', ($formdata['CLIENTIDNO']??''))):

                            $memamounts = Common::get_array_elements_with_key($formdata, 'AMT_');
                  
                            $formdata['POSTTOGL']= false;
                            $formdata['POSTTOSL']= true;
                            
                            $mem_bal_array = Savings::$bal_array[1];
                               
                            foreach ($memamounts as $key => $val):
                               
                                $formdata['MEMID'] = Common::replaces_underscores(Common::replace_string($key, 'AMT_', ''));

                                $formdata['AMOUNT'] = Common::number_format_locale_compute($formdata[$key]);
                                
                                $formdata['AMOUNT'] = -1 * abs($formdata['AMOUNT']); 
                                
                                if ($formdata['TTYPE'] == 'SA' || $formdata['TTYPE'] == 'SW'):
                               
                                    $membalance = Common::sum_array('members_idno', $formdata['MEMID'], 'balance',$mem_bal_array);

                                    if ($membalance < abs($formdata['AMOUNT'])):

                                        $memname = Common::sum_array('members_idno', $formdata['MEMID'], 'name',$mem_bal_array);

                                        getlables("1666");
                                        echo "ERR " . $lablearray['1666'] . " " . $memname . ":" . $formdata['MEMID'];
                                        exit();

                                    endif;
                                endif;
                                
                                if(abs($formdata['AMOUNT'])>0):
                                    $form_data[] = $formdata;
                                    unset($formdata[$key]);
                                endif;                     
                                

                            endforeach;

                        endif;
                        $formdata['CHARGE'] =($formdata['CHARGE']??0);
                        if ($formdata['CHARGE'] > 0):

                            $formdata['DATE'] = Common::changeDateFromPageToMySQLFormat($tmpdate);
                            $formdata['TCODE'] = Common::generateTransactionCode($_SESSION['user_id']);
                            $formdata['TTYPE'] = 'SC';
                            $formdata['AMOUNT'] = -1 * $formdata['CHARGE'];
                            $form_data[] = $formdata;

                            // CHECK SEE IF ITS A GROUP TRANSACTION
                            if (count($memamounts) > 0):

                                $memcharge = Common::get_array_elements_with_key($formdata, 'CHARGE_');

                                foreach ($memcharge as $key => $val):

                                    $memid = Common::replace_string($key, 'CHARGE_', 'AMT_');
                                    
                                   // unset($formdata[$key]);
                                    
                                    $memamount = $formdata[$memid];

                                    $formdata['MEMID'] = Common::replaces_underscores($key);

                                    $formdata['AMOUNT'] = Common::number_format_locale_compute($formdata[$key]);

                                    $tamount = bcadd($formdata['AMOUNT'], $memamount, SETTING_ROUNDING);

                                    if ($tamount < $memamount):
                                        getlables("1666");
                                        echo "ERR " . $lablearray['1666'] . " " . $memname . ":" . $formdata['MEMID'];
                                        exit();
                                    endif;
                                    
                                    if($formdata['AMOUNT']>0):
                                       $form_data[] = $formdata; 
                                       unset($formdata[$key]);
                                    endif;
                                    
                                endforeach;

                            endif;

                        endif;
                                       
                    Savings::updateSavings($form_data);                    

                    if (Common::$lablearray['E01'] != ''):
                        echo 'ERR ' . Common::$lablearray['E01'];
                        exit();
                    endif;
                }
         
                echo "1111111";
                
                break 2;
            case 'loadform':
                if(isset($formdata['product_prodid'])):               
                
                    $charge_array = Common::getParamValue('CHARGE_ON_WITHDRAW',$formdata['product_prodid']); // $Conn->SQLSelect("SELECT productconfig_value val FROM " . TABLE_PRODUCTCONFIG . " WHERE  productconfig_paramname='CHARGE_ON_WITHDRAW' AND product_prodid='" . $formdata['product_prodid']."'");
                    $formdata['txtamount'] = Common::number_format_locale_compute($formdata['txtamount']);
                    $main_array=array();
                    Common::push_element_into_array($main_array, 'txtchargeamount', ($formdata['txtamount']*($charge_array[0]['val']/100)));     
                    $jason = json_encode(array('data' => $main_array));
                    $jason = str_replace("\\\\", '', $jason);
                    echo $jason;
                endif;
                exit();
                break 2;
            case 'reverse':
               Common::reverseTransaction($_POST['theid'],'S', $_SESSION['user_id'], Common::$connObj);
               echo 'INFO.' . Common::$lablearray['E01'];
               exit();
               break 2;
            default:
                break 2;
        }
        break;

    case 'frmsavrepay':
        Common::getlables("1406,1195", "", "", $Conn);

        if ($array1['paydetails']['paymodes'] == "") {
            echo "INFO.".Common::$lablearray['1195'];
            exit();
        }

        $loans = $array1['loans']??array();
        $formdata  = array();
        foreach ($loans as $value) {
            $tcode = Common::generateTransactionCode($_SESSION['user_id']);

            // check see if we are withdrawing from Savings
            if($array1['paydetails']['paymodes']== "SA") {

                //get savings balance

            }

            $formdata[] = array(
                'DATE' => Common::changeDateFromPageToMySQLFormat($array1['paydetails']['txtpayDate']),
                'MODE' => $array1['paydetails']['paymodes'],
                'SAVPROD' => $value['prodid'],
                'MEMID' => $value['member'],
                'BRANCHCODE' =>  substr($value['lnrno'], 0, 2),
                'FUNDCODE' => $value['fund'],
                'DONORCODE' => $value['donor'],
                'LNR' => $value['lnrno'],
                'CLIENTIDNO' => $value['clientcode'],
                'PRODUCT_PRODID' => $value['prodid'],
                'CTYPE' => Common::getClientType($value['clientcode']),
                'PRI' => $value['pric']??0,
                'INT' => $value['int']??0,
                'COM' => $value['com']??0,
                'PEN' => $value['pen']??0,
                'VAT' => $value['vat']??0,                     
                'CASHGL' => $array1['paydetails']['cashaccounts'],
                'TCODE' => $tcode,                    
                'VOUCHER' => $array1['paydetails']['voucher'],
                'CHEQNO' => $array1['paydetails']['cheqno']??'',
                'BID' => $array1['paydetails']['bankid']??'',
                'STAT' => 'Q',
                'AMT' => $value['AMT']??0,
                'CHARGEFEE'=>''
            );        
   
         }
 
         Common::prepareTransForXML($formdata, 'LR');        
         
         Bussiness::PrepareData(true);

         if (Common::$error != "") {
             echo Common::$error;
         } else {
             echo '1111111';
         }
         break;
 
    case 'frmrepay':

        $formdata['DuePrincipal'] = Common::number_format_locale_compute(($formdata['DuePrincipal']??0));
        $formdata['DueInterest'] = Common::number_format_locale_compute(($formdata['DueInterest']??0));
        $formdata['DueCommission'] = Common::number_format_locale_compute(($formdata['DueCommission']??0));
        $formdata['DuePenalty'] = Common::number_format_locale_compute(($formdata['DuePenalty']??0));
        $formdata['Duevat'] = Common::number_format_locale_compute(($formdata['Duevat']??0));
        Common::replace_key_function($formdata, 'txtlnr', 'LNR');
        Common::replace_key_function($formdata, 'members_idno', 'MEMID');
        Common::replace_key_function($formdata, 'txtvoucher', 'VOUCHER');
        Common::replace_key_function($formdata, 'client_idno', 'CLIENTIDNO');
        Common::replace_key_function($formdata, 'txtproduct', 'LPRODID');
        
        Common::replace_key_function($formdata, 'DuePrincipal','PRI');
        Common::replace_key_function($formdata, 'DueInterest','INT');
        Common::replace_key_function($formdata, 'DueCommission','COM');
        Common::replace_key_function($formdata, 'DuePenalty','PEN');
        Common::replace_key_function($formdata, 'Duevat','VAT');
        Common::replace_key_function($formdata, 'TotalOver','OVR');
        Common::replace_key_function($formdata, 'cashaccounts_code', 'GLACC');
        Common::replace_key_function($formdata, 'PAYMODES', 'MODE');
        Common::replace_key_function($formdata, 'txtpayDate', 'DATE');  
       
        $formdata['AMOUNT'] = $formdata['PRI'] + $formdata['INT'] + $formdata['COM'] + $formdata['PEN'] + $formdata['VAT']+ ($formdata['SAVAMT']??0);
   
        Common::getlables("1195,1737", "", "", Common::$connObj);
      
        switch ($_POST['action']) {
            case 'loadform':
               
                $charge_array = Common::getParamValue('CHARGE_ON_WITHDRAW',$formdata['txtproduct']); 
                $formdata['DueTotal'] = Common::number_format_locale_compute($formdata['DueTotal']);
                $main_array=array();
                
                if(isset($charge_array[0]['1'])):                    
                    Common::push_element_into_array($main_array, 'txtchargeamount', '0.0');                     
                else:                 
                                
                    $nTotal  = $formdata['DuePrincipal'] + $formdata['DueInterest'] + $formdata['DueCommission']+ $formdata['DuePenalty'];
                
                    Common::push_element_into_array($main_array, 'txtchargeamount',  round($nTotal*($charge_array[0]['val']/100),SETTING_ROUNDING));     
                    Common::push_element_into_array($main_array, 'DueTotal', ($nTotal+ round($nTotal*($charge_array[0]['val']/100),SETTING_ROUNDING)));     
                endif;
                
                $jason = json_encode(array('data' => $main_array));
                $jason = str_replace("\\\\", '', $jason);
                echo $jason;
                break;
                
            case 'add':
                $formdata['DueTotal'] = Common::number_format_locale_compute($formdata['DueTotal']);
                
                if($formdata['AMOUNT']!=$formdata['DueTotal']){
                    echo "INFO." .Common::$lablearray['1737'].$formdata['DueTotal'].'-'.$formdata['AMOUNT'];
                    exit();
                }

                if (!isset($formdata['MODE'])) {                 
                     echo "INFO." .Common::$lablearray['1195'];
                     exit();
                 }

                 if ($formdata['MODE'] == "CQ" && $formdata['cheques_no'] == '') {
                    echo "INFO." . self::$lablearray['185'];
                    exit();
                }

                 if (!isset($formdata['DATE'])) {                 
                    echo "INFO." . self::$lablearray['1196'];
                    exit();
                }

                 $formdata['LSTATUS'] =$formdata['LSTATUS']??'';
                             
                
                 $formdata['DATE'] = Common::changeDateFromPageToMySQLFormat($formdata['DATE']);
                 $formdata['TTYPE'] = $formdata['MODE'];
                
                 $formdata['TCODE'] = Common::generateTransactionCode($_SESSION['user_id']);
  
                
                 $formdata['SAVAMT'] =($formdata['SAVAMT']??0);

                 
                 $ctype = Common::getClientType($formdata['CLIENTIDNO']);
                 
                 $formdata['CTYPE'] =  $ctype;
               

                
                Common::replace_key_function($formdata, 'txtchargeamount','SFEE');
                  
                // cheque
                if ($formdata['MODE'] == 'CQ') {
                   
                        $data_acc = Common::searchArray($banks_array, 'bankaccounts_accno', $data[13]);
                        $bankaccounts_id = $data_acc['bankaccounts_id'];
                        Common::addKeyValueToArray($formdata, 'BID', $bankaccounts_id);
                        Common::addKeyValueToArray($formdata, 'LSTATUS', 'LD');
                        Common::addKeyValueToArray($formdata, 'BANKGL', $data_acc['chartofaccounts_accountcode']);
                        Common::addKeyValueToArray($formdata, 'CQSTAT', 'Q');
                }
                
               $loandetails_array = SerializeUnserialize::getInstance()->get_unserialized_data('loandetails_'. Common::replace_string($formdata['LNR']).'.txt');
            
                // Savings Transfer
               Common::addKeyValueToArray($formdata, 'SAVACC','');
               Common::addKeyValueToArray($formdata, 'SPRODID','');
               
                if ($formdata['MODE'] == "SA") {
                    
                     if(isset($formdata['cmbsavaccounts'])):
                     
                        $accoundetails = explode(":", $formdata['cmbsavaccounts']); 
                  
                        $formdata['SAVACC'] = $accoundetails [0];
                        $formdata['SPRODID'] = $accoundetails [1];
                        
                        if($formdata['SAVACC']==""):
                              echo 'WAR' . Common::$lablearray['1197'];
                              exit();
                        endif;
                  
                    else:                  
                        $sav_acc = Common::getSavingsAccountForProductNoNames($loandetails_array['client_idno'],$formdata['SPRODID'],"S");
                        $formdata['SAVACC'] = $sav_acc[0]['savaccounts_account'];
                    endif;
                    
                    // CHECK SEE OF A SAVINGS PRODUCT IS SELECTED
                    if($formdata['SPRODID']==''):
                        
                        Common::getlables("1593", "", "", $Conn);

                        echo 'ERR ' . Common::$lablearray['1593'];
                        exit();
                    endif;                
                    
                } else {
                    Common::replace_key_function($formdata, 'cashaccounts_code', 'GLACC');                 
                }
                
                $results = Common::getClientDetails($loandetails_array['client_idno']);
          
                $formdata['FUNDCODE'] = $loandetails_array['fund_code'];
                $formdata['DONORCODE'] = $loandetails_array['donor_code'];            
                $formdata['BRANCHCODE'] = $loandetails_array['branch_code'];                
                $formdata['CCODE'] = $results[0]['costcenters_code'];
               
              
                // COMMENTED USED FOR DESPOSITS AT LOAN REPAYMENTS
//                if($formdata['SAVAMT']>0):
//                     $sav_acc2 = Common::getSavingsAccountForProductNoNames($Conn,$loan::$loanappdetails['client_idno'],$formdata['MPRODID'],"S");
//                     $formdata['MSAVACC'] = $sav_acc2[0]['savaccounts_account']; 
//                endif;
              

                // check see if we are closing the loan
                if (isset($formdata['SFEE'])) {

                    if ($formdata['SFEE'] > 0) {
                        $formdata['CHARGEFEE'] = 'Y';  
                        
                        //TODO: Check Savings Balances
                      //  $formdata['SFEE'] = $formdata['txtchargeamount'];                    
                    }                   
                }                
                
                if(isset($formdata['chkcloseLoan'])):
                 //   Common::addKeyValueToArray($formdata, 'CHARGEFEE', 'Y');
                    Common::addKeyValueToArray($formdata, 'CLOSE', '1');
                endif;
                
                $principal = Common::get_array_elements_with_key($formdata,'PRINC_');
                
                $outstanding_array = SerializeUnserialize::getInstance()->get_unserialized_data('loan_'. Common::replace_string($formdata['LNR']).'.txt');
                 
                // check see if its a group
                // [repare member payments
                Common::addKeyValueToArray($formdata, 'MEMID', '');
                         
                if (preg_match('[G]',$formdata['CLIENTIDNO'])):
               
                    foreach($principal as $key=>$val):
                        // Key sample format before replacement: PRINC_PP_M_0090908
                        $memid = Common::replace_string($key,'PRINC_', '');
                
                        $formdata['MEMID'] = Common::replaces_underscores($memid);
                        $formdata['PRI'] = Common::number_format_locale_compute($formdata[$key]);
                        
                        $formdata['INT']  =  Common::number_format_locale_compute($formdata[Common::replace_string($key,'PRINC_', 'INT_')]);
                        $formdata['COM']  =  Common::number_format_locale_compute($formdata[Common::replace_string($key,'PRINC_', 'COMM_')]);
                        $formdata['PEN']  =  Common::number_format_locale_compute($formdata[Common::replace_string($key,'PRINC_', 'PEN_')]);
                        $formdata['VAT']  =  Common::number_format_locale_compute($formdata[Common::replace_string($key,'PRINC_', 'VAT_')]);
                        $formdata['OVR']  =  Common::number_format_locale_compute($formdata[Common::replace_string($key,'PRINC_', 'OVR_')]);                        
                        $formdata['AMOUNT'] = $formdata['PRI'] + $formdata['INT']+ $formdata['COM'] +  $formdata['PEN'] + $formdata['VAT'];
                        
                        $memamount =  $outstanding_array[$formdata['MEMID']]['Total'];
                       
                        if(!isset($alloverpayments)):
                            $alloverpayments = Common::getProductConfigDetails($loandetails_array['product_prodid'],$loandetails_array['branch_code'],'ALLOW_OVERPAYMENTS');
                        endif;
                        
                        if($alloverpayments['val']=='1'):
                             if($formdata['AMOUNT'] > $memamount):                                 
                                Common::getlables("1658", "", "", $Conn); 
                                echo 'MSG ' . Common::$lablearray['1658'].' '.$formdata['MEMID'].' '.bcsub($formdata['AMOUNT'], $memamount,SETTING_ROUNDING);;
                                exit();
                            endif;
                        endif;
                        
                        if($formdata['AMOUNT']>0):
                            $form_data[] = $formdata;  
                        endif;                        
                
                    endforeach;                   
                
                else:                    
                    $form_data[] = $formdata;
                endif;
                
               
                
                $_POST = array();       
                $loan = new Loan(array(), $formdata['LNR']);
                unset($formdata);
                $loan::updateLoan($form_data,'LR');
                
                if (Common::$lablearray['E01'] != "") {
                    echo 'MSG ' . Common::$lablearray['E01'];
                    exit();
                }
                        
                echo '1111111';
       
                break;

            default:
                break;
        }
        break;
    case 'frmloanproductsettings3':
    case 'frmsavproductsettings3':
    case 'frmtimedepositsettings3':
        switch ($_POST['action']) {
            case 'add':

                $formdata = Common::decodeSerialisedPagedata($_POST['pageparams']);

                // $tableTemplate = array_map('array_flip', array_values($Conn->preparefieldList('productconfig')));
                // $flat1 = call_user_func_array('array_merge', $tableTemplate);
                
                switch ($_POST['frmid']) {
                    case 'frmtimedepositsettings3':
                        Common::getlables("1605,1606", "", "", $Conn);

                        $glitems = array('TIMEDEPOSIT_ACC' => Common::$lablearray['1606'],
                            'INT_TD_ACC' =>Common::$lablearray['1605']);
                        
                            $flat1['DGRP'] = 'TD_ACCOUNTS';          
                          
                            $flat1['NAME'] = $formdata['PRODGLTDACC'];
                            $flat1['DESC'] = $glitems[$formdata['PRODGLTDACC']];                        
                          
                        
                        break;
                    
                    case 'frmsavproductsettings3':
                        
                        $glitems = array('SAVINGS_ACC' => 'Savings',
                        'STAT_RECEIVED_ACC' => 'Stationery Received',
                        'INT_SAV_ACC' => 'Interest on Savings',
                        'INT_OD_ACC' => 'Interest on Overdrafts',
                        'COMM_SAV_ACC' => 'Commision Received on Savings',
                        'WITHHOLDING_TAX_ACC' => 'Withholding Tax');

                        $flat1['DGRP'] = 'SAV_ACCOUNTS';
                        $flat1['NAME'] = $formdata['SAVPRODGLACC'];
                        $flat1['DESC'] = $glitems[$formdata['SAVPRODGLACC']];
                        break;

                    case 'frmloanproductsettings3':
                        Common::getlables("1461,105,1463,1462,1145,1105,401,1469,1464,1465,1181,1464,1203,1466,1467,1468,1442,1181", "", "", $Conn);

                        $glitems = array(
                        'PRINCIPAL_OUTSTANDING_ACC' => Common::$lablearray['1461'],
                        'PROV_BAD_DEBTS_ACC' => Common::$lablearray['105'],
                        'PROV_COST_ACC' => Common::$lablearray['1462'],
                        'INT_RECEIVED_ACC' => Common::$lablearray['401'],
                        'COMM_RECEIVED_ACC' => Common::$lablearray['1105'],
                        'PEN_RECEIVED_ACC' => Common::$lablearray['1181'],
                        'LOANS_WRITTEN_OFF_ACC' => Common::$lablearray['1463'],
                        'ACCRUED_INTEREST_ACC' => Common::$lablearray['1464'],
                        'LOANS_RECOVERED_ACC' => Common::$lablearray['1465'],
                        'SUSPENCE_ACC' => Common::$lablearray['1203'],
                        'ACCRUED_PENALTIES_ACC' => Common::$lablearray['1466'],
                        'CURRENCY_DIFF_ACC' => Common::$lablearray['1467'],
                        'LOAN_COMMISSION_ACC' => Common::$lablearray['1468'],
                        'LOAN_OVERPAYMENT_ACC' => Common::$lablearray['1469'],
                        'SERVICE_FEE_ACC' => Common::$lablearray['1442']);

                        $flat1['DGRP'] = 'LOAN_ACCOUNTS';
                        $flat1['NAME'] = $formdata['LOANPRODGLACC'];
                        $flat1['DESC'] = $glitems[$formdata['LOANPRODGLACC']];
                        
                        break;

                    default:
                        break;
                }

                $flat1['PRODUCT_PRODID'] = $_POST['keyparam'];               
                $flat1['INDACC'] = $formdata['COACOMBOIND'];
                $flat1['GRPACC'] = $formdata['COACOMBOGRP'];
                $flat1['GACC'] = $formdata['COAGENERAL'];
                $flat1['BRANCHCODE'] = $formdata['branch_code'];
                $flat1['TABLE']= TABLE_PRODUCTCONFIG;
  
                $form_data[]= $flat1;
                
                Bussiness::covertArrayToXML($form_data, true);
                // $tabledata['xml_data'] = Common::$xml;                
                Bussiness::PrepareData(true);
              
               // Bussiness::$Conn->endTransaction();
                       
                echo '1111111';
                break;

            default:
                break;
        }
        break;

    case 'frmloanproductsettings2':
        switch ($_POST['action']) {
            case 'add':


                $formdata = Common::decodeSerialisedPagedata($_POST['pageparams']);
                
                $tableTemplate = array_map('array_flip', array_values($Conn->preparefieldList('feesconfig')));

                $flat1 = call_user_func_array('array_merge', $tableTemplate);

                $flat1['fees_id'] = $formdata['fees_id'];
                $flat1['feesconfig_level'] = $formdata['LOANPROCESSLEVELS'];
                $flat1['feesconfig_amt'] = Common::number_format_locale_compute($formdata['SAVINGS_GUARANTEE_AMOUNT']);
                $flat1['feesconfig_per'] = Common::number_format_locale_compute($formdata['SAVINGS_GUARANTEE_AMOUNT_PER']);
                $flat1['product_prodid'] = $_POST['keyparam'];
                
                $Conn->SQLDelete('feesconfig', 'fees_id', $formdata['fees_id']);
                
                $Conn->SQLInsert(array('feesconfig' => $flat1), true);

                echo '1111111';

                break;
            case 'edit':
                break;
            default:
                break;
        }
        break;
    case 'frmsaveproductsettings1':
    case 'frmloanproductsettings1':
    case 'frmtimedepositsettings1':    
        $formdata = Common::decodeSerialisedPagedata($_POST['pageparams']);
        $parameters['productprodid'] = $_POST['theid'];
        $parameters['currencies_id'] = $formdata['CURRENCIES_ID'];
        $parameters['branchcode'] = $formdata['branch_code'];
        
        switch ($_POST['frmid']) {
           case 'frmtimedepositsettings1':
               $parameters['interest_rate'] = $formdata['INTEREST_RATE']; 
               $parameters['interest_rate_activated'] = $formdata['INTEREST_RATE_ACTIVATED'];
               
               $parameters['code'] = 'UPDTDSETTINGS';
                break;

            case 'frmsaveproductsettings1':
                $parameters['minimun_sav_bal'] = Common::number_format_locale_compute($formdata['MINIMUM_SAV_BAL']);
                $parameters['minimun_sav_bal_activated'] = $formdata['MINIMUM_SAV_BAL_ACTIVATED'];
                $parameters['minimun_sav_bal_earn'] = Common::number_format_locale_compute($formdata['MINIMUM_SAV_BAL_EARN']);
                $parameters['minimun_sav_bal_earn_activated'] = $formdata['MINIMUM_SAV_BAL_EARN_ACTIVATED'];
                $parameters['sav_int_rate'] = Common::number_format_locale_compute($formdata['SAV_INT_RATE']);
                $parameters['sav_int_period'] = $formdata['SAV_INT_PERIOD'];
                $parameters['int_cal_method'] = $formdata['INT_CAL_METHOD'];
                $parameters['charge_on_withdraw'] = $formdata['CHARGE_ON_WITHDRAW'];
                $parameters['int_start_date'] = Common::changeDateFromPageToMySQLFormat($formdata['INT_START_DATE']);
                $parameters['clientcode_is_savacc'] = $formdata['CLIENTCODE_IS_SAVACC'];
                $parameters['per_int_topay'] = $formdata['PER_INT_TOPAY'];
                
                
                $parameters['code'] = 'UPDSAVSETTINGS';
                break;

            case 'frmloanproductsettings1':

                $parameters['maximun_loan_amount'] = Common::number_format_locale_compute($formdata['MAXIMUM_LOAN_AMOUNT']);
                $parameters['maximun_loan_amount_activated'] = Common::number_format_locale_compute($formdata['MINIMUM_LOAN_AMOUNT']);
                $parameters['savings_guarantee_amount_per'] = $formdata['SAVINGS_GUARANTEE_AMOUNT_PER'];
                $parameters['savings_guarantee_amount'] = Common::number_format_locale_compute($formdata['SAVINGS_GUARANTEE_AMOUNT']);
                $parameters['savings_guarantee_amount_activated'] = $formdata['SAVINGS_GUARANTEE_AMOUNT_ACTIVATED'];
                $parameters['number_of_installments'] = $formdata['NUMBER_OF_INSTALLMENTS'];
                $parameters['number_of_installments_activated'] = $formdata['NUMBER_OF_INSTALLMENTS_ACTIVATED'];
                $parameters['minimum_loan_amount'] = Common::number_format_locale_compute($formdata['MINIMUM_LOAN_AMOUNT']);
                $parameters['interest_type'] = $formdata['INTEREST_TYPE'];
                $parameters['interest_type_activated'] = $formdata['INTEREST_TYPE_ACTIVATED'];
                $parameters['interest_rate'] = Common::number_format_locale_compute($formdata['INTEREST_RATE']);
                $parameters['interest_rate_activated'] = $formdata['INTEREST_RATE_ACTIVATED'];
                $parameters['installment_type'] = $formdata['INSTALLMENT_TYPE'];
                $parameters['installment_type_activated'] = $formdata['INSTALLMENT_TYPE_ACTIVATED'];

                $parameters['pri_in_arr'] = empty($formdata['PRI_IN_ARR']) ? '0' : '1';
                $parameters['int_in_arr'] = empty($formdata['INT_IN_ARR']) ? '0' : '1';
                $parameters['com_in_arr'] = empty($formdata['COM_IN_ARR']) ? '0' : '1';
                $parameters['pen_in_arr'] = empty($formdata['PEN_IN_ARR']) ? '0' : '1';
                $parameters['charge_int'] = empty($formdata['CHARGE_INT']) ? '0' : '1';
                $parameters['int_days'] = empty($formdata['INT_DAYS']) ? '356' : $formdata['INT_DAYS'];
                $parameters['int_weeks'] = empty($formdata['INT_WEEKS']) ? '52' : $formdata['INT_WEEKS'];
                $parameters['recalc_int'] = $formdata['RECALC_INT'];
                $parameters['loan_com_from_sav'] = $formdata['LOAN_COM_FROM_SAV'];
                $parameters['pull_dues_after_prepayments'] = $formdata['PULL_DUES_AFTER_PREPAYMENTS'];
                $parameters['allow_overpayments'] = $formdata['ALLOW_OVERPAYMENTS'];
               
                $parameters['no_int'] = $formdata['NO_INT'];
                $parameters['service_fee'] = $formdata['SERVICE_FEE'];
                $parameters['service_fee_acc'] = $formdata['SERVICE_FEE_ACC'];
                $parameters['pay_priority'] = empty($formdata['PAY_PRIORITY']) ? 'PRINC-INT-COM-PEN' : $formdata['PAY_PRIORITY'];
                $parameters['ref_priority'] = empty($formdata['REF_PRIORITY']) ? 'PRINC-INT-COM-PEN' : $formdata['REF_PRIORITY'];
                $parameters['sav_at_repay'] = empty($formdata['SAV_AT_REPAY']) ? '0' : $formdata['SAV_AT_REPAY'];
                $parameters['saving_at_loan_repay_amt'] = empty($formdata['SAVING_AT_LOAN_REPAY_AMT']) ? '0' : $formdata['SAVING_AT_LOAN_REPAY_AMT'];
                $parameters['code'] = 'UPDLOANSETTINGS';

                break;

            default:
                break;
        }


        $Conn->sp_call($parameters, '');

        echo '1111111';
        break;

        case 'frmLoanapp3': // ADD LOAN APPLICATION


        //require_once('includes/classes/loan.php');
        // DO TO:
        // Add primary key constrain on loan
        // Catch exception once the same primary key has been entered - gracefully
        // ADD LOAN DETAILS
            
        $formdata = Common::decodeSerialisedPagedata($_POST['pageparams']);
        
         // for some reason  we should not flatten $objects['gridinfo'] as above
       $gridinfo = Common::convertobjectToArray($objects['gridinfo']);
       Common::replace_key_function($formdata, 'client_idno','CLIENTIDNO');    
       $formdata['CTYPE'] = Common::getClientType($formdata['CLIENTIDNO']);
        
       $formdata['BRANCHCODE'] = Common::extractBranchCode($formdata['CLIENTIDNO']);
       $formdata['SAVACC'] = $formdata['CLIENTIDNO'];                

       Common::addKeyValueToArray($formdata, 'LNR', Common::generateID($formdata['BRANCHCODE'],'', 'LOANNO',''));      
       
       Common::replace_key_function($formdata,'lamount', 'LAMNT');
       $formdata['LAMNT'] = Common::number_format_locale_compute($formdata['LAMNT']);
       Common::replace_key_function($formdata, 'fund_code','FCODE'); 
       Common::replace_key_function($formdata, 'intrate','INTRATE');   
       $formdata['INTRATE'] = Common::number_format_locale_compute($formdata['INTRATE']);
       Common::addKeyValueToArray($formdata, 'USERID', $_SESSION['user_id']);       
       Common::addKeyValueToArray($formdata, 'INTAMNT', '0'); // To be updated       
       Common::replace_key_function($formdata, 'startDate','START'); 
       
       $formdata['START'] = Common::changeDateFromPageToMySQLFormat($formdata['START']);
               
       Common::replace_key_function($formdata, 'grace','GRACE'); 
       Common::replace_key_function($formdata, 'no_of_inst','NINST');         
       Common::replace_key_function($formdata, 'allintpaidfirstinstallment', 'FIRSTINS'); 
       Common::replace_key_function($formdata, 'loan_udf1','UD1');
       Common::replace_key_function($formdata, 'loan_udf2','UD2'); 
       Common::replace_key_function($formdata, 'loan_udf3','UD3'); 
       Common::replace_key_function($formdata, 'client_idno','CLIENTIDNO'); 
       Common::replace_key_function($formdata, 'loan_inttype','INTTYPE');       
       Common::replace_key_function($formdata, 'loan_insttype','INSTYPE');
       Common::replace_key_function($formdata, 'intgrace','AGRACE');
       Common::replace_key_function($formdata, 'loan_intindays','INTDAY');
       Common::replace_key_function($formdata, 'intpaidatdisbursement','INTDIS');
       Common::replace_key_function($formdata, 'product_prodid','PRODUCT_PRODID');
       Common::replace_key_function($formdata, 'donor_code','DCODE');
       Common::replace_key_function($formdata, 'intgrace','INTCG');
       Common::replace_key_function($formdata, 'comm','COMM');
       Common::replace_key_function($formdata, 'freezedate','FREEZE');
       Common::addKeyValueToArray($formdata, 'LEXP', '');      
       Common::replace_key_function($formdata, 'gracecompint','GCOMP');
       Common::replace_key_function($formdata, 'loanpurpose_id','LPD');
       Common::replace_key_function($formdata, 'loan_intindays','INTIND');
       Common::replace_key_function($formdata, 'loan_inupfront','INTUPF');     
       Common::replace_key_function($formdata, 'action','ACTION');
       
       // ADD GROUP MEMBER LOANS              
        $memberloans  = preg_grep ('/^MEM_(\w+)/i', array_keys($formdata));

        if(count($memberloans)>0):                    

            foreach ($memberloans as $newstring):                
               $memid = Common::replaces_underscores(substr($newstring,4, strlen($newstring)));
               $formdata['MEMLOANS'][$memid] = array('CLIENTIDNO'=>$formdata['CLIENTIDNO'],'LNR'=>$formdata['LNR'],'MID'=>$memid,'LAMNT'=>$formdata[$newstring],'TABLE'=>TABLE_MEMBERLOANS,'INTAMNT'=>0,'ACTION'=>$formdata['ACTION']);
            endforeach;

        endif;
       
        // ADD DUES
        $nCount = count($gridinfo);
        $nCt = 0;
        $nint = 0;
        $nPrinc = 0;
        $mem_id ='';
        $inst = $formdata['NINST'];
        
        foreach ($gridinfo as &$value) {           
            
            $nCt++;
            
            $expdate = common::changeDateFromPageToMySQLFormat($value['date']);
            
            $nPrinc =  $nPrinc + $value['principal'];
            
            $formdata['DUES'][] = array('LNR'=>$formdata['LNR'],'MEMID'=>$value['memid'],'PRINC'=>$value['principal'],'INT'=>$value['interest'],'PEN'=>$value['penalty'],'COMM'=>$value['commission'],'DATE'=>$expdate,'TABLE'=>TABLE_DUES,'ACTION'=>$formdata['ACTION']);

             if($mem_id!=$value['memid']):  
                 $mid = $value['memid'];
             
                  $filtered_array = array_filter($gridinfo, function ($element) use ($mid,&$formdata) { 
                        if($mid ==$element['memid']):
                           $formdata['MEMLOANS'][$mid]['INTAMNT'] = $formdata['MEMLOANS'][$mid]['INTAMNT'] + $element['interest'];
                        endif;
                     } ); 
             
                                   
                    
             endif; 
              
    
        
            $mem_id = $value['memid'];            
                  
           
        }
        if(count($memberloans)>0):
            if($nPrinc<=0 || $nPrinc != $formdata['LAMNT']):
               Common::getlables("1642", "", "", Common::$connObj);            
               echo 'MSG.'.Common::$lablearray['1642']; 
               exit();
            endif;
        endif;
        
        $formdata['LEXP'] = $expdate;
        
        // ADD GUARANTORS
        if ($formdata['txtclientcode1'] != ""):
            $formdata['GUA'][] = array('CID'=>$formdata['txtclientcode1'],'LNR'=>$formdata['LNR']);      
        endif;
        
        if ($formdata['txtclientcode2'] != ""):
            $formdata['GUA'][] = array('CID'=>$formdata['txtclientcode2'],'LNR'=>$formdata['LNR']);      
        endif;
        
        if ($formdata['txtclientcode3'] != ""):
            $formdata['GUA'][] = array('CID'=>$formdata['txtclientcode3'],'LNR'=>$formdata['LNR']);      
        endif;

        switch ($_POST['action']) {
            case 'add':
                $form_data[] = $formdata;

                Loan::addeditLoan($form_data);
                
                if(Common::$lablearray['E01']!=''):                    
                   echo 'MSG.'.Common::$lablearray['E01'];                
                else:                
                    echo '1111111';                
                endif;
                
                break 2;

            case 'update':

                // update loan details
                $Conn->SQLUpdate(array('loan' => $flat1), true);

                // delete dues
                $Conn->SQLDelete('dues', "loan_number", $formdata['loan_number']);

                // save due details
                $Conn->SQLInsert(array('dues' => $flat3), true);
                //echo informationUpdate('success',$lablearray['218']);
                echo '1111111';
                break 2;

            default:
                break 2;
        }

        break;
    
    case 'frmLoanappother':
        $flat1['loan_number'] = $flat2['loan_number'];
        $flat1['loan_status'] = 'PA';      // Pending Approval             
        $flat1['loan_datecreated'] = Common::getcurrentDateTime();
        $Conn->SQLUpdate(array('loan' => $flat1), true);
        break;

    case 'frmLoanapp1':

        switch ($_POST['action']) {
            case 'update':

           
                switch ($formdata['lstatus']):
                    case 'WO': //WRITE OFF
                    case 'RF': //REFINANCING
                    case 'LD': //REFINANCING
                    case 'DE': // DELETING
                    case 'AP': // LOAN APPLICATION
                        
                        $selectedloans = common::get_array_elements_with_key($formdata, 'grid_checkbox__');

                        //VALIDATIONS
                        if (count($selectedloans) == 0) {
                            // FOR SOME REASON get_does not get_array_elements_with_key when an elemnt end with two underscore
                            $selectedloans = common::get_array_elements_with_key($formdata, 'grid_checkbox_');
                            if (count($selectedloans) == 0) {
                                $lablearray = getlables("1224");
                                echo "MSG:" . $lablearray["1224"]; // Please select Loan.
                                break 2;
                            }
                        }

                        if ($formdata['startDate'] == ""):
                            $lablearray = getlables("186");
                            echo "MSG:" . $lablearray["186"]; // Please select status
                            break 2;
                        endif;


                        if ($formdata['lstatus'] == ""):
                            $lablearray = getlables("1232");
                            echo "MSG:" . $lablearray["1232"]; // Please select status
                            break 2;
                        endif;

                        if ($formdata['lstatus'] != "AP" && $formdata['lstatus'] != "RF") :
                            if ($formdata['txtvoucher'] == ""):
                                $lablearray = getlables("1577");
                                echo "MSG:" . $lablearray["1577"]; // Please select status
                                break 2;
                            endif;
                        endif;

                        break;
                        
                    case 'RS': // RESCHEDULE
                        break;
                    default:
                        break;
                endswitch;



                switch ($formdata['lstatus']) {
                    
                    case 'RS': //RESCHEDUE
                        Common::getlables("1688,1689", "", "", Common::$connObj);
                        
                        $array_schedule = Common::get_array_elements_with_key_in_3D_array($array1, 'rowid');
                        
                        foreach($array_schedule as $memid=>$val):
                            $loan_dues[] = array('ID'=>Common::uniqidReal(),'LNR'=>$formdata['loannumber'],'MEMID'=>$value['MEMID'],'PRINC'=>$formdata['PRINC_'.$val],'INT'=>$formdata['INT_'.$val],'PEN'=>$formdata['PEN_'.$val],'COMM'=>$formdata['COMM_'.$val],'DATE'=>Common::changeDateFromPageToMySQLFormat($formdata['DATE_'.$val]),'TABLE'=>TABLE_DUES);                        
                            $nprinc +=$formdata['PRINC_'.$val];
                        endforeach;
                        
                        $loan_amount = Common::get_array_elements_with_key_in_3D_array($array1, 'lamount');
                        
                        if($nprinc!=$loan_amount[0]):
                            echo "ERR " . Common::$lablearray['1688']."(".Common::number_format_locale_display($loan_amount[0]).") ".Common::$lablearray['1689']." ".Common::number_format_locale_display($nprinc);
                            exit();
                        endif;                              
                       
                        $form_data[]['DUES'] = $loan_dues;
                        
                        Loan::$isBulkInsert = true;
                       if(count($loan_dues)>0):
                            $results = Loan::updateLoan($form_data, 'RS');
                       endif;                      

                        if (Common::$lablearray['E01'] != "") {                            
                            echo 'MSG ' . Common::$lablearray['E01'];
                            exit();
                        }
                        echo '1111111';
                        exit();
                        break;
                         
                    case 'WO': //WRITE OFF
                       Common::getlables("772,695", "", "", Common::$connObj);
                        Common::replace_key_function($formdata, 'LSTATUS', 'WO');
                        Common::replace_key_function($formdata, 'USERID', $_SESSION['user_id']);

                        $form_data[] =$formdata;
                                
                        $results = Loan::updateLoan($form_data, 'WO');

                        if (Common::$lablearray['E01'] != "") {                            
                            echo 'MSG ' . Common::$lablearray['E01'];
                            exit();
                        }
                        switch($results['id']){                        
                        case '1':
                            echo '1111111';
                             break;
                        case '2':
                            echo "MSG:" . Common::$lablearray["695"];
                        case '3':
                            echo "MSG:" . Common::$lablearray["772"];
                            break 3;
                        }
                        break 2;
                     
                    case 'RF': //REFINANCING

                        Common::getlables("1475,1492,1491", "", "", $Conn);

                        if ($formdata['txttopupperloan'] <= 0 || $formdata['txttopupperloan'] == "") {
                            echo "MSG:" . Common::$lablearray["1491"]; // Please enter the amount
                            break 2;
                        }


//                        if ($formdata['txtAmount'] <= 0):
//                            echo "MSG:" . Common::$lablearray["1492"]; // Please enter the amount
//                            break 2; 
//                        endif; 
//                                
//                        if( $formdata['txttopupperloan']<0):
//                            echo "MSG:" . Common::$lablearray["1492"]; // Please enter the amount
//                            break 2; 
//                        endif;
                           
                        Common::replace_key_function($formdata, 'txttopupperloan', 'TOPUP');

                        if (!isset($formdata['chkignoreallout'])) {
                            Common::replace_key_function($formdata, 'IGNOREOUT', 'N');
                        } else {
                            Common::replace_key_function($formdata, 'chkignoreallout', 'IGNOREOUT');
                        }

                        if (!isset($formdata['chkcompcurduesignorefuture'])) {
                            Common::replace_key_function($formdata, 'COMPDUESIGNOREFUTURE', 'N');
                        } else {
                            Common::replace_key_function($formdata, 'chkcompcurduesignorefuture', 'COMPDUESIGNOREFUTURE');
                        }

                       // Common::replace_key_function($formdata, 'txtAmount', 'AMOUNT');
                       // Common::replace_key_function($formdata, 'LSTATUS', 'LD');
                       // $formdata['LSTATUS']='AP';
                       // $formdata[] = 'LD'
                        Common::replace_key_function($formdata, 'USERID', $_SESSION['user_id']);

                       // $formdata['AMOUNT'] = Common::tep_db_prepare_input($formdata['AMOUNT']);

                        $form_data = array($formdata);
                                
                        Loan::updateLoan($form_data, 'RF');

                        if (Common::$lablearray['E01'] != "") {
                            echo 'MSG ' . Common::$lablearray['E01'];
                            exit();
                        }
                        echo '1111111';
                        
                        break 2;

                     case 'DE': // DELETING
                        
                        foreach ($selectedloans as $key => $loan_number) {
                            // delete loan dues
                            Common::deleteItem($loan_number, 'LOAN');

                        }
                        echo '1111111';
                        break 2;
                    
                    default: // DIBURSE, REJECT , APPROVE
                        //VALIDATIONS

                        $formdata['PAYMODES'] =$formdata['PAYMODES'] ??'';
                      
                        Common::getlables("1579,1216,1198,1195,185,1216,1196,1231,1555,218", "", "", Common::$connObj);
                        
                        if ($formdata['PAYMODES'] == "" && $formdata['lstatus'] != 'RJ' && $formdata['lstatus'] != 'AP') {
                           // getlables("1195");
                            echo "ERR" .Common::$lablearray['1195'];
                            break 2;
                        }

                        if ($formdata['PAYMODES'] == "CQ" && $formdata['cheques_no'] == '') {
                           // getlables("185");
                            echo "MSG:" .Common::$lablearray['185'];
                            exit();
                        }


                        if ($formdata['startDate'] == "") {
                            //$lablearray = getlables("1196");
                            echo "MSG:" . Common::$lablearray["1196"];
                            break 2;
                        }

                        if ($formdata['PAYMODES'] == "SA") {
                            // check product
                            if ($formdata['product_prodid'] == "") {
                                // $lablearray = getlables("1231");
                                echo "MSG:" . Common::$lablearray["1231"];
                                break 2;
                            }
                        }
                    
                    Common::addKeyValueToArray($formdata, 'MEMID', '');    
                    Common::replace_key_function($formdata, 'txtAmount', 'LAMNT'); 
                    Common::addKeyValueToArray($formdata, 'DATE',Common::changeDateFromPageToMySQLFormat($formdata['startDate']));                   
                    Common::replace_key_function($formdata, 'txtvoucher', 'VOUCHER');
                    Common::replace_key_function($formdata, 'PAYMODES', 'MODE');              
                    Common::replace_key_function($formdata, 'SAVPROD', 'SPRODID');
                    Common::replace_key_function($formdata, 'ACTION', '');
                    
                 ///   Common::replace_key_function($formdata, 'txtAmount', 'LAMNT');
                    Common::replace_key_function($formdata, 'txtcommission', 'COMM');
                    Common::replace_key_function($formdata, 'txtstationery', 'STAT');
                    Common::replace_key_function($formdata, 'lstatus', 'LSTATUS');
                    
                    Common::addKeyValueToArray($formdata, 'MEMID', ''); 
                    Common::addKeyValueToArray($formdata, 'LNR', '');                   
                    Common::addKeyValueToArray($formdata, 'VAT', 0);
                   // Common::addKeyValueToArray($formdata, 'AMOUNT', 0);
                    Common::addKeyValueToArray($formdata, 'DTYPE', 'DD');                                   
                    Common::addKeyValueToArray($formdata, 'CYCLE', 0);
                    Common::addKeyValueToArray($formdata, 'FUNDCODE','');
                    Common::addKeyValueToArray($formdata, 'DONORCODE','');
                    Common::addKeyValueToArray($formdata, 'MEMID', '');
                    Common::addKeyValueToArray($formdata, 'SAVACC','');
                    Common::addKeyValueToArray($formdata, 'TCODE','');
                    Common::addKeyValueToArray($formdata, 'LPRODID','');
                    Common::addKeyValueToArray($formdata, 'DTYPE', 'DD');
                    Common::addKeyValueToArray($formdata, 'USERID', $_SESSION['user_id']);

                    if($formdata['MODE']=='CQ'):
                    //  Common::replace_key_function($formdata, 'bankbranches_id', 'BACCNO');
                    //  Common::replace_key_function($formdata, 'cheques_no','CHEQNO');
                     else:
                          Common::replace_key_function($formdata, 'cashaccounts_code', 'GLACC');                    
                     endif;
            
                     $formdata['LSTATUS'] = $formdata['LSTATUS']??'';
                     $formdata['STAT'] = $formdata['STAT']??'0';
                     $formdata['COMM'] = $formdata['COMM']??'0';



                    $lstatus = $formdata['LSTATUS'];
                    
                    foreach ($selectedloans as $key => $loan_number) {
                        
                        //  CHECK STATUS
                        switch ($lstatus):
                            case 'LD':
                                $disb_array = $Conn->SQLSelect("SELECT loan_number,disbursements_amount FROM " . TABLE_DISBURSEMENTS . " WHERE  loan_number='" . $loan_number . "'");
                                if (isset($disb_array[0]['loan_number']) && $formdata['lstatus'] == 'LD'):
                                    if ($disb_array[0]['disbursements_amount'] == $loan_array[0]['loan_amount']):
                                        echo "MSG." . Common::$lablearray["1579"] . ' ' . $disb_array[0]['loan_number'];
                                        exit();
                                    endif;
                                endif;
                                break;
                            default:
                                break;
                        endswitch;

                        // CHECK IF WE ARE REFINANCING THIS LOAN 
                        If($formdata['LSTATUS']=='LD'): 
                            $loan_array = $Conn->SQLSelect("SELECT l.client_idno,l.product_prodid,l.fund_code,l.donor_code,l.loan_amount,COALESCE((SELECT ls.loan_amount FROM " . TABLE_LOANSTATUSLOG." ls WHERE ls.loan_number=l.loan_number AND (ls.loan_status='RFAP' OR ls.loan_status='RF') ),0) topup,(SELECT r.loan_noofinst FROM " . TABLE_REFINANCED."  r WHERE r.loan_number=l.loan_number AND r.refinanced_status='RFAP' GROUP BY r.loan_number) loan_noofinst FROM  ".TABLE_LOAN."  l WHERE  l.loan_number='" . $loan_number . "'");
                        else:
                            $loan_array = $Conn->SQLSelect("SELECT l.client_idno,l.product_prodid,l.fund_code,l.donor_code,l.loan_amount,COALESCE((SELECT ls.loan_amount FROM " . TABLE_LOANSTATUSLOG." ls WHERE ls.loan_number=l.loan_number AND (ls.loan_status='RFAP' OR ls.loan_status='RF') ),0) topup,l.loan_noofinst FROM  ".TABLE_LOAN."  l WHERE  l.loan_number='" . $loan_number . "'");
                        endif; 
                        
                        Common::addKeyValueToArray($formdata, 'NINST',$loan_array[0]['loan_noofinst']); 
                        
                        if($loan_array[0]['topup']>0):
                             $formdata['TOPUP'] = $loan_array[0]['topup'];                           
                             If($formdata['LSTATUS']=='LD'):                               
                                                        
                               $formdata['LSTATUS'] = 'RFLD';   
                             endif;
                                                    
                        else:
                             Common::addKeyValueToArray($formdata, 'LAMNT',$loan_array[0]['loan_amount']);                     
                           //  $formdata['AMOUNT'] = $loan_array[0]['loan_amount'];                        
                        endif;   
                        
                                              
                        $nstat = $formdata['STAT'];
                        $ncom  = $formdata['COMM'];                         
                    
                      //  $formdata['AMOUNT'] = $loan_array[0]['loan_amount']; //- ($formdata['STAT']+ $formdata['COMM']);                        
                        $formdata['TCODE'] = Common::generateTransactionCode($_SESSION['user_id']);
                        $formdata['LPRODID'] = $loan_array[0]['product_prodid'];
                        $formdata['FUNDCODE'] = $loan_array[0]['fund_code'];
                        $formdata['DONORCODE'] = $loan_array[0]['donor_code']; 
                        $formdata['BRANCHCODE'] = BRANCHCODE; 
                        $formdata['CLIENTIDNO'] = $loan_array[0]['client_idno']; 
                        $formdata['CTYPE'] = Common::getClientType($loan_array[0]['client_idno']); 
                        $formdata['LNR'] = $loan_number;

                        $nstat = (int)$formdata['STAT']??0;
                        $ncom  = (int)$formdata['COMM']??0;
                        $commfromsavings = false;
                        // check see if commision is to be deducted from savings
                        if($ncom > 0):
                           $prod_config_array = (Common::$connObj->SQLSelect("SELECT productconfig_value FROM ".TABLE_PRODUCTCONFIG." WHERE product_prodid='".$formdata['LPRODID']."' AND productconfig_paramname='LOAN_COM_FROM_SAV'")??array('productconfig_value'=>'0'));
                            if(isset($prod_config_array[0]['productconfig_value'])){
                                $commfromsavings = ($prod_config_array[0]['productconfig_value']=='1') || false;
                            }   
                        endif;
                        
                        if($commfromsavings):
                                                        
                            if($formdata['SPRODID']==""):                                
                              echo "MSG." . Common::$lablearray["1198"];
                              exit();
                            endif;
                                                    
                            Common::addKeyValueToArray($formdata, 'LOAN_COM_FROM_SAV', '1');  
                      
                            // GET SAVINGS BALANCE 
                            Savings::$prodid = $formdata['SPRODID'];
                            Savings::$savacc = $loan_array[0]['client_idno'];
                            Savings::$asatdate = $formdata['DATE'];
                            Savings::$membershipid = '';
                            $Sav_bal_array = Savings::getSavingsBalance();
                            
                            Common::addKeyValueToArray($formdata, 'SAVACC',$Sav_bal_array['savaccounts_account']);
                            
                            
                            // CHECK SAVINGS BALANCE IS SUFFICIENT
                            if((int)$Sav_bal_array['balance'] < (int)$formdata['COMM']):                                
                              echo "MSG." . Common::$lablearray["1216"].' '.$loan_array[0]['client_idno'].' '.$formdata['SPRODID'].' '.$Sav_bal_array['balance'];
                              exit();
                            endif;
                            
                        else:
                                                          
                              $lbal  = $loan_array[0]['loan_amount'] - ($nstat+$ncom);
                              
                              if($lbal <=0 && ($nstat >0  || $ncom >0)):
                                echo "MSG:" . Common::$lablearray["1555"];
                                BREAK 2;
                              endif;                               
                        
                        endif;
                        
                        // get product parameter                    
                        // get savings account
                        
                        if($formdata['MODE']=='SA'):
                            $sav_array = Common::getSavingsAccountForProductNoNames($loan_array[0]['client_idno'], $formdata['SPRODID'],"S") ;
                            $formdata['SAVACC'] = $sav_array[0]['savaccounts_account'];    
                        endif;
                        
                        $form_data[] = $formdata;
                      }
                      
                    Loan::$isBulkInsert = true;
                    
                    Loan::updateLoan($form_data,$formdata['LSTATUS']);
                    
                    if (Common::$lablearray['E01'] != "") {
                        echo 'MSG:' . Common::$lablearray['E01'];
                        exit();
                    }else{
                    //Bussiness::$Conn->endTransaction();
                        echo "1111111";   
                        exit();
                    }
                }
                
                break 2;
                
            default:

                $formdata['lamount'] = Common::number_format_locale_compute($formdata['lamount']??0);
                $formdata['intrate'] = Common::number_format_locale_compute($formdata['intrate']??0);
                $formdata['principal'] = Common::number_format_locale_compute(($formdata['principlalastinstallment']??0));
                $formdata['comm'] = Common::number_format_locale_compute(($formdata['comm']??0));
                
                // get group member loans
                $memberloans  = preg_grep ('/^MEM_(\w+)/i', array_keys($formdata));
                 
                if(count($memberloans)>0):                    

                    foreach ($memberloans as $newstring):
                    
                       $memid = Common::replaces_underscores(substr($newstring,4, strlen($newstring)));
                
                       Loan::$incomingvars['GRP'][$memid] = $formdata[$newstring];
                       
                    endforeach;
                    
                endif;
                 
                foreach (Loan::$incomingvars as $key => $val) {
                    if($key!='GRP'):
                        Loan::$incomingvars[$key] = ($formdata[$key]??0);
                    endif;
                    
                }

                if (!defined('SETTING_ROUNDING')) {
                    define('SETTING_ROUNDING', 2);
                }

                if (!defined('SETTING_INT_DAYS')) {
                    define('SETTING_INT_DAYS', 365);
                }

                if (!defined('SETTING_DATE_FORMAT')) {
                    define('SETTING_DATE_FORMAT', 'd/m/Y');
                }
                
                if(count($memberloans)>0):  
                     $loan_data =  Loan::updateMemberSchedule();
                else:
                    $loan_data = Loan::updateInstallmentSchedule(); 
                endif;
                
                $_SESSION['loan_data'] = serialize($loan_data);
                $_SESSION['formdata'] = serialize($formdata);
                $_SESSION['rpt'] = 'LLCARD';

                $jason = json_encode($loan_data);

                $jason = str_replace("\\\\", '', $jason);
                
                print_r($jason);

                break 2;
        }
        break;

    case 'frmClients':
        Common::getlables("969,1732,1561,1019,1635,186,1639,291,186,1679,1749,1680", "", "", $Conn);
       
        Common::replace_key_function($formdata, 'action', 'ACTION');
        
       
        
        switch($formdata['client_type']):
        case 'I':        
                      
             if ($formdata['client_regdate'] == '' &&  $formdata['client_idno']==''):        
                echo "MSG " .Common::$lablearray['1639'];
                exit();
            endif;
            
            if($formdata['client_firstname']=='' ||  $formdata['client_surname']==""):            
                echo "MSG." .Common::$lablearray['1561'];
                exit();         
            endif;
            
         
            Common::replace_key_function($formdata, 'client_regdate', 'RDATE');
            break;
            
        case 'G':
        case 'B':

            if($formdata['entity_name']==''):               
                echo "MSG." .Common::$lablearray['1561'];
                exit();         
            endif;
            
           // if(preg_match('[B]', $formdata['client_type'])):
                if ($formdata['client_regdate'] == ''):        
                    echo "MSG.".Common::$lablearray['1639'];
                    exit();
                endif;

                Common::replace_key_function($formdata, 'client_regdate', 'RDATE');

                $formdata['RDATE'] = common::changeDateFromPageToMySQLFormat($formdata['RDATE']);
            // endif;
            break;
            
        case 'M':

            $results = Common::getClientDetails($formdata['client_idno']);
           
            if(!isset($results[0]['client_idno'])){
                echo "MSG." .Common::$lablearray['1749'];
                exit();
            }                
                      
            if($formdata['member_regdate'] == '' && $formdata['member_no']==''):                                       
                echo "MSG." .Common::$lablearray['1679'];
                exit();
            endif;
            
             if ($formdata['member_regstatus'] == ''):                                       
                echo "MSG " .Common::$lablearray['1680'];
                exit();
            endif;
            
            break;
        default:
            break;        
        endswitch;
         
        Common::replace_key_function($formdata, 'client_type', 'CTYPE');        
        
        
        if($formdata['ACTION']=='edit'):
            if($formdata['theid']==""):
                 echo "MSG." .Common::$lablearray['1635'];
                exit();
            endif;              
        endif;
        switch($formdata['CTYPE']):
            
            case 'G':
            case 'I':
            case 'B':
              //  $formdata = Clients::updateRenameKeys($formdata,$formdata['CTYPE']);               
                
                if(preg_match('[I]', $formdata['CTYPE'])):
                    if($formdata['client_bday']!=""):
                    $formdata['client_bday'] =  common::changeDateFromPageToMySQLFormat($formdata['client_bday']);
                    endif;               
                endif; 

              //  if(preg_match('[G]', $formdata['CTYPE'])):
                    $formdata = Clients::updateRenameKeys($formdata,($formdata['CTYPE']=='G')?'M':$formdata['CTYPE']);
             //   endif;           

                

                break;
           
           case 'M':

                 // check registration date
                if($formdata['member_regdate']==""):
                    echo "MSG." .Common::$lablearray['1732'];
                    exit();
                endif;
                
                $formdata = Clients::updateRenameKeys($formdata,'M');

                if($formdata['MID']==''):
                   $formdata['ACTION'] ='add';                
                endif;
                
                break;

            case 'D':

                $formdata = Document::updateRenameKeys($formdata);

                if($formdata['IDATE']==""):
                    echo "MSG." .Common::$lablearray['1732'];
                    exit();
                endif;

                if($formdata['SERIAL']==""):
                    echo "MSG." .Common::$lablearray['1732'];
                    exit();
                 endif;               
                

                break;
               
            default:
                break;                
        endswitch;
         
        $form_data[] = $formdata;
        
        switch($formdata['CTYPE']):            
        case 'G':
        case 'I':
        case 'B':
        case 'M':
            if($formdata['BRCODE']==''):       
                echo "MSG." .Common::$lablearray['969'];
                exit();         
            endif;

            Clients::updateClient($form_data);
            break;
        case 'D':
            Document::updateDocument($form_data);
            break;    
        default:
            break;    
        endswitch;

        if(Common::$lablearray['E01']!=""):
            echo "MSG.".Common::$lablearray['E01'];      
         else:
            echo "1111111";     
        endif;
        
        break;
 
    case 'frmlogin':

        $lablearray = getlables("1,2,3,4,9,5,883,331,642,642,643,644,645,646,260,647,648,649,913,883");

        $username = tep_db_prepare_input($_POST['username']);

        $password = fnEncrypt($_POST['password'],'PASSWORD');

        //$password = fnEncrypt(trim($password), 'PASSWORD');


        $user_accesscode = tep_db_prepare_input($_POST['user_accesscode']);

        //echo 'pass:'.$password;
        //echo 'usercode:'.$user_accesscode;
        //echo "SELECT employees_id,user_id, user_firstname, user_lastname,user_password,  user_username,user_accesscode FROM ". TABLE_USERS. " WHERE user_username='".tep_db_input(trim($username))."' AND trim(user_password)='".$password."' AND user_isactive='Y' AND user_accesscode='".tep_db_input(trim($user_accesscode))."'";
        //exit();

        $query_results = tep_db_query("SELECT employees_id,user_id, user_firstname, user_lastname,user_password,  user_username,user_accesscode,user_usercode FROM " . TABLE_USERS . " WHERE user_username='" . tep_db_input(trim($username)) . "' AND trim(user_password)='" . $password . "' AND user_isactive='Y' AND user_accesscode='" . tep_db_input(trim($user_accesscode)) . "'");
//	echo "SELECT employees_id,user_id, user_firstname, user_lastname,user_password,  user_username,user_accesscode FROM ". TABLE_USERS. " WHERE user_username='".tep_db_input(trim($username))."' AND trim(user_password)='".$password."' AND user_isactive='Y' AND user_accesscode='".tep_db_input(trim($user_accesscode))."'";
        //exit();


        if (tep_db_num_rows($query_results) > 0) {

            $user = tep_db_fetch_array($query_results);
            $user_firstname = $user['user_firstname'];
            $user_username = $user['user_username'];
            
            

            $query_results_1 = tep_db_query("SELECT ub.branch_code,ob.licence_build,ub.parentbranch FROM " . TABLE_USERBRANCHES . " ub," . TABLE_BANKBRANCHES . " ob WHERE ob.branch_code=ub.branch_code AND user_accesscode='" . tep_db_input(trim($user_accesscode)) . "' AND ub.parentbranch ='Y'");


            if (!tep_db_num_rows($query_results_1)) {
                echo "<p class='messageBox'>" . $lablearray['883'] . "</p>";
                exit();
            }


            $branch_code_array = tep_db_fetch_array($query_results_1);

            // define default branch for user
            $_SESSION['BRANCHCODE'] = $branch_code_array['branch_code'];

            // level 2 authentication
            //echo "SELECT accesscode FROM ".TABLE_MODULEACCESSCODES." WHERE accesscode='".fnEncrypt(tep_db_prepare_input($_POST['passcode']),'LOGIN')."' AND modules_code='LOGIN' AND session_id='".tep_session_id()."'";
            $lablearray = getlables("1,2,3,4,9,5,883,331,642,642,643,644,645,646,260,647,648,649,913");
            $query_results_2 = tep_db_query("SELECT accesscode FROM " . TABLE_MODULEACCESSCODES . " WHERE accesscode='" . fnEncrypt(tep_db_prepare_input($_POST['passcode']), 'LOGIN') . "' AND modules_code='LOGIN' AND session_id='" . tep_session_id() . "'");

            if (!tep_db_num_rows($query_results_2)) {

                echo $lablearray['913'];
                exit();
            }


            $query_results = tep_db_query("SELECT employees_id,user_id, user_firstname, user_lastname,user_password,  user_username,user_accesscode,user_usercode FROM " . TABLE_USERS . " WHERE user_username='" . tep_db_input(trim($username)) . "' AND trim(user_password)='" . trim($password) . "' AND user_isactive='Y'");


            $user_id = $user['user_id'];
            tep_session_register('user_id');
            tep_session_register('user_usercode');
            tep_session_register('user_username');
            tep_session_register('user_firstname');
            //tep_session_register('branch_code');
            //$_SESSION['branch_code'] = $branch_code;
            $_SESSION['user_username'] = $user['user_firstname'] . " " . $user['user_lastname'];
            $_SESSION['user_accesscode'] = $user['user_accesscode'];
            $_SESSION['user_usercode'] = $user['user_usercode'];
            getUserRights();

            tep_update_whos_online();

            if (AuthenticateAccess('LOGIN') == 1) {

                $query_results = tep_db_query("SELECT last_page_url FROM " . TABLE_WHOS_ONLINE . " WHERE session_id='" . tep_session_id() . "' AND user_id='" . $_SESSION['user_id'] . "'");

                if ($_POST['username'] != "") {
                    //$timestamp = strtotime(date());
                    tep_db_query("UPDATE " . TABLE_USERS . " SET user_lang='" . tep_db_input($_SESSION['P_LANG']) . "',user_passexp='" . strtotime(date('Y-m-d H:i:s')) . "' WHERE user_username='" . tep_db_input($username)."'");
                }

                $_SESSION['branch_code'] = $branch_code_array['branch_code'];

                $_SESSION['licence_build'] = $branch_code_array['licence_build'];

                $urladdress = tep_db_fetch_array($query_results);
                echo '1';
            } else {
                echo '1';
            }
        } else {
            echo $lablearray['642'];
        }

        break;


    case 'frmforexrates':

        switch ($_POST['action']) {
            case 'add':  // Update												

                if ($_POST['branch_code'] == "") {
                    getlables("673");
                    echo informationUpdate("fail", $lablearray['673'], ''); // branch code missing
                    break;
                }

                // check forex rate date before posting- should not be date before or already posted
                $check_query = tep_db_query("SELECT forexrates_id FROM " . TABLE_FOREXRATES . " WHERE  forexrates_date>=" . common::changeDateFromPageToMySQLFormat($_POST['forexrates_date']));

                if (tep_db_num_rows($check_query) > 0) {
                    getlables("711");
                    echo informationUpdate("fail", $lablearray['711'], '');
                    break;
                }

                $forexrates_date = common::changeDateFromPageToMySQLFormat($_POST['forexrates_date']);

                // add benign transation
                $nNew_Debit = 0;
                $nNew_Credit = 0;

                $query_Account = tep_db_query("SELECT currencies_id,chartofaccounts_accountcode,currencies_code FROM " . TABLE_CURRENCIES . " WHERE  currencies_id='" . tep_db_prepare_input($_POST['currencies_id']) . "'");

                $Account_array = tep_db_fetch_array($query_Account);
                // check see if revaluation account is set
                if ($Account_array['chartofaccounts_accountcode'] == "") {
                    getlables("712");
                    echo informationUpdate("fail", $lablearray['712'] . ' ' . $Account_array['currencies_code'], '');
                    break;
                }


                // get all accounts for revaluation				 
                $query_revaluation = tep_db_query("SELECT (SELECT chartofaccounts_tgroup FROM " . TABLE_CHARTOFACCOUNTS . " WHERE  chartofaccounts_accountcode=gl.chartofaccounts_accountcode) as chartofaccounts_tgroup,gl.chartofaccounts_accountcode, SUM(generalledger_debit)debit,sum(generalledger_credit)credit,SUM(IF(IFNULL(generalledger_debit,0)>0,generalledger_fcamount,0)) dfcamount,SUM(IF(IFNULL(generalledger_credit,0)>0,generalledger_fcamount,0)) cfcamount FROM " . TABLE_GENERALLEDGER . " gl  WHERE gl.chartofaccounts_accountcode IN (SELECT chartofaccounts_accountcode FROM " . TABLE_CHARTOFACCOUNTS . " WHERE  currencies_id='" . $Account_array['currencies_id'] . "') AND branch_code='" . $_POST['branch_code'] . "' GROUP BY gl.chartofaccounts_accountcode");

                if (!tep_db_num_rows($query_revaluation)) {
                    getlables("714");
                    echo informationUpdate("fail", $lablearray['714'], ''); // sorry, there are no accounts to revalue
                    break;
                }

                getlables("713");


                tep_db_query("INSERT INTO " . TABLE_FOREXRATES . " (currencies_id,forexrates_buyrate,forexrates_midrate,forexrates_sellrate,forexrates_datecreated,forexrates_date,branch_code) VALUES ('" . $Account_array['currencies_id'] . "','" . tep_db_prepare_input($_POST['forexrates_buyrate']) . "','" . tep_db_prepare_input($_POST['forexrates_midrate']) . "','" . tep_db_prepare_input($_POST['forexrates_sellrate']) . "',NOW()," . common::changeDateFromPageToMySQLFormat($_POST['forexrates_date']) . ",'" . $_POST['branch_code'] . "')");


                while ($query_array = tep_db_fetch_array($query_revaluation)) {

                    // reinitialise variable
                    $nAmount = 0;

                    $generalledger_description = $lablearray['713'] . ' ' . $query_array['chartofaccounts_accountcode'] . ':' . $Account_array['currencies_code']; // Currency Revalution
                    // checks ee if its a debit balance
                    if ($query_array['debit'] > 0 && ($query_array['chartofaccounts_tgroup'] == '1' || $query_array['chartofaccounts_tgroup'] == '4' || $query_array['chartofaccounts_tgroup'] == '5' || $query_array['chartofaccounts_tgroup'] == '8' || $query_array['chartofaccounts_tgroup'] == '9')) {  //$Account_Bal = $query_array['debit'];
                        $nAmount = ($query_array['dfcamount'] * $_POST["forexrates_midrate"]) - $query_array['debit'];
                    }

                    // checks ee if its a credit balance
                    if ($query_array['credit'] > 0 && ($query_array['chartofaccounts_tgroup'] == '2' || $query_array['chartofaccounts_tgroup'] == '3')) {
                        $nAmount = ($query_array['cfcamount'] * $_POST["forexrates_midrate"]) - $query_array['credit'];
                    }

                    // check see if amount has appreciated					
                    if ($nAmount > 0) {
                        $sql[] = array(abs($nAmount), '0', '', $query_array['chartofaccounts_accountcode'], '', '', $forexrates_date, $_SESSION['user_id'], $generalledger_description, 'E02', '', getCurrencyID($query_array['chartofaccounts_accountcode']));   // Debit
                        $sql[] = array('0', abs($nAmount), '', $Account_array['chartofaccounts_accountcode'], '', '', $forexrates_date, $_SESSION['user_id'], $generalledger_description, 'E02', '', getCurrencyID($Account_array['chartofaccounts_accountcode']));   // Credit															
                    }


                    // check see if amount has devaluaed
                    if ($nAmount < 0) {

                        $sql[] = array(0, abs($nAmount), '', $query_array['chartofaccounts_accountcode'], '', '', $forexrates_date, $_SESSION['user_id'], $generalledger_description, 'E02', '', getCurrencyID($query_array['chartofaccounts_accountcode']));   // Debit
                        $sql[] = array(abs($nAmount), 0, '', $Account_array['chartofaccounts_accountcode'], '', '', $forexrates_date, $_SESSION['user_id'], $generalledger_description, 'E02', '', getCurrencyID($Account_array['chartofaccounts_accountcode']));   // Credit															
                    }
                }


                $tcode = Common::generateTransactionCode($_SESSION['user_id']);

                PostTransactionsGeneral($sql, $tcode, $_POST['branch_code']);



                getlables("345");
                echo informationUpdate("success", $lablearray['345'], "");
                break;

            case 'update':  // Update
                if ($_POST["currencies_id_old"] != $_POST["currencies_id"]) {
                    // check see if we are chnaging currency that is already attached to transactions
                    $tran_results = tep_db_query("select tcode FROM " . TABLE_GENERALLEDGER . " WHERE forexrates_id!='0'");

                    if (tep_db_num_rows($tran_results) > 0) {
                        getlables("345");
                        echo informationUpdate("fail", $lablearray['345'], '');
                        break 2;
                    }
                }

                //tep_db_query("UPDATE " .TABLE_CURRENCIES." SET currencies_name='".tep_db_prepare_input($_POST["currencies_name"])."',currencies_code='".tep_db_prepare_input($_POST["currencies_code"])."',currencies_symbolleft='".tep_db_prepare_input($_POST["currencies_symbolleft"])."',currencies_symbolright='".tep_db_prepare_input($_POST['currencies_symbolright'])."',currencies_decimalpoint='".tep_db_prepare_input($_POST["currencies_decimalpoint"])."',currencies_isbase='".tep_db_prepare_input($_POST["currencies_isbase"])."' WHERE currencies_id='".tep_db_prepare_input($_POST["currencies_id"])."'");				
                //getlables("345");
                //
				//echo informationUpdate("success",$lablearray['345'],'showResult("frmid=frmcurrencies","txtHint")');							

                break;

            case 'edit':  // Edit				 
                $currency_results = tep_db_query("SELECT * FROM " . TABLE_CURRENCIES . " WHERE currencies_id='" . tep_db_prepare_input($_POST['id']) . "'");
                $currency = tep_db_fetch_array($currency_results);

                echo "formObj.action.value = 'update';\n";
                echo "formObj.currencies_id_old.value = '" . $currency["currencies_id"] . "';\n";
                echo "formObj.currencies_name.value = '" . $currency["currencies_name"] . "';\n";
                echo "formObj.currencies_id.value = '" . $currency["currencies_id"] . "';\n";
                echo "formObj.currencies_symbolleft.value = '" . $currency["currencies_symbolleft"] . "';\n";
                echo "formObj.currencies_symbolright.value = '" . $currency["currencies_symbolright"] . "';\n";
                echo "formObj.currencies_decimalplaces.value = '" . $currency["currencies_decimalplaces"] . "';\n";

                if ($currency["currencies_isbase"] == 'Y') {
                    echo "formObj.currencies_isbase.checked = true;\n";
                    echo "formObj.currencies_isbase.value = 'Y';\n";
                } else {
                    echo "formObj.currencies_isbase.checked = false;\n";
                    echo "formObj.currencies_isbase.value = 'N';\n";
                }

                break;

            case 'search':  // Search				 
                $query = "SELECT fr.forexrates_id,c.currencies_name,c.currencies_code,IFNULL(fr.forexrates_buyrate,0.00) AS forexrates_buyrate,IFNULL(fr.forexrates_midrate,0.00) AS forexrates_midrate,IFNULL(fr.forexrates_sellrate,0.00) AS forexrates_sellrate,DATE_FORMAT(fr.forexrates_date,'%d/%m/%Y') forexrates_date,c.flag FROM " . TABLE_FOREXRATES . " fr LEFT JOIN " . TABLE_CURRENCIES . " c ON  fr.currencies_id=c.currencies_id  WHERE c.currencies_name LIKE '%" . tep_db_prepare_input($_POST['searchterm']) . "%' OR c.currencies_code LIKE '%" . tep_db_prepare_input($_POST['searchterm']) . "%'";
                break;

            default:
                $query = "SELECT fr.forexrates_id,c.currencies_name,c.currencies_code,IFNULL(fr.forexrates_buyrate,0.00) AS forexrates_buyrate,IFNULL(fr.forexrates_midrate,0.00) AS forexrates_midrate,IFNULL(fr.forexrates_sellrate,0.00) AS forexrates_sellrate,DATE_FORMAT(fr.forexrates_date,'%d/%m/%Y')forexrates_date FROM " . TABLE_FOREXRATES . " fr LEFT JOIN " . TABLE_CURRENCIES . " c ON fr.currencies_id=c.currencies_id GROUP BY c.currencies_id ORDER BY c.currencies_id,c.currencies_code";
                break;
        }


        getlables("659,654,317,660,661,662,664");

        $_SESSION['reportname'] = $lablearray['664'];
        $_SESSION['reporttitle'] = $lablearray['664'];
        $_SESSION['downloadlist'] = $query;

        //$newgrid->extraFields[0] ="flag";
        //$newgrid->cpara = "FLAG";

        $fieldlist = array('currencies_name', 'currencies_code', 'forexrates_buyrate', 'forexrates_midrate', 'forexrates_sellrate', 'forexrates_date');
        $keyfield = 'forexrates_id';
        $gridcolumnnames = array($lablearray['659'], $lablearray['654'], $lablearray['660'], $lablearray['661'], $lablearray['662'], $lablearray['317'], '');

        break;
    
        case 'frmcurrencydeno':

        switch ($_POST['action']) {
            case 'add':  // Update
                Common::replace_key_function($formdata, 'CURRENCIES_ID', 'CURRID');
                Common::replace_key_function($formdata, 'currency_deno', 'DEN');
                $form_data = array($formdata);
                Bussiness::$isBulkInsert = true;
                Currencies::updateCurrency($form_data);
             
                break;

            case 'update':  // Update
                tep_db_query("UPDATE " . TABLE_CURRENCYDENO . " SET currencydeno_deno='" . tep_db_prepare_input($_POST["currency_deno"]) . "' WHERE currencydeno_id='" . tep_db_prepare_input($_POST["currencydeno_id"]) . "'");

                break;

            case 'edit':  // Edit				 
                $currency = Bussiness::$Conn->SQLSelect("SELECT currencies_id,currencydeno_id,currencydeno_deno FROM " . TABLE_CURRENCYDENO . " WHERE currencydeno_id='" . tep_db_prepare_input($_POST['theid']) . "'");

                echo "formObj.action.value = 'update';\n";
                 echo "SelectItemInList(\"CURRENCIES_ID\",\"" . $currency[0]['currencies_id'] . "\");\n";
                echo "formObj.currencydeno_id.value = '" . $currency[0]["currencydeno_id"] . "';\n";             
                echo "formObj.currency_deno.value = '" . $currency[0]["currencydeno_deno"] . "';";

                break;

            default:
             
                break;
        }
        
        echo '1111111';
//        getlables("652,659,654,658,655,656,657,667");
//        $_SESSION['reportname'] = $lablearray['652'];
//        $_SESSION['reporttitle'] = $lablearray['652'];
//        $_SESSION['downloadlist'] = $query;
//
//        $fieldlist = array('name', 'currencies_name', 'currencies_code', 'currencies_decimalplaces', 'currencies_isbase', 'currencies_symbolleft', 'currencies_symbolright');
//        $keyfield = 'currencies_id';
//        $gridcolumnnames = array($lablearray['667'], $lablearray['659'], $lablearray['654'], $lablearray['657'], $lablearray['658'], $lablearray['655'], $lablearray['656']);

        break;

    case 'frmcurrencies':



        switch ($_POST['action']) {
            case 'add':  // Update

                if ($_POST["chartofaccounts_accountcode"] == "") {
                    $lablearray = getlables("710");
                    echo informationUpdate("fail", $lablearray['710'], '');
                    break;
                }

                tep_db_query("INSERT INTO " . TABLE_CURRENCIES . " (currencies_name,currencies_code,currencies_symbolleft,currencies_symbolright,currencies_decimalpoint,currencies_decimalplaces,currencies_isbase,chartofaccounts_accountcode) VALUES ('" . tep_db_prepare_input($_POST['currencies_name']) . "','" . tep_db_prepare_input($_POST['currencies_code']) . "','" . tep_db_prepare_input($_POST['currencies_symbolleft']) . "','" . tep_db_prepare_input($_POST['currencies_symbolright']) . "','" . tep_db_prepare_input($_POST['currencies_decimalpoint']) . "','" . tep_db_prepare_input($_POST['currencies_decimalplaces']) . "','" . tep_db_prepare_input($_POST['currencies_isbase']) . "','" . tep_db_prepare_input($_POST['chartofaccounts_accountcode']) . "'");
                $lablearray = getlables("345");
                echo informationUpdate("success", $lablearray['345'], 'showResult("frmid=frmcurrencies","txtHint")');
                break;

            case 'update':  // Update
                if ($_POST["currencies_isbase"] == 'Y') {
                    tep_db_query("UPDATE " . TABLE_CURRENCIES . " SET currencies_isbase='N'");
                }

                tep_db_query("UPDATE " . TABLE_CURRENCIES . " SET currencies_name='" . tep_db_prepare_input($_POST["currencies_name"]) . "',currencies_code='" . tep_db_prepare_input($_POST["currencies_code"]) . "',currencies_symbolleft='" . tep_db_prepare_input($_POST["currencies_symbolleft"]) . "',currencies_symbolright='" . tep_db_prepare_input($_POST['currencies_symbolright']) . "',currencies_decimalpoint='" . tep_db_prepare_input($_POST["currencies_decimalpoint"]) . "',currencies_isbase='" . tep_db_prepare_input($_POST["currencies_isbase"]) . "',chartofaccounts_accountcode='" . tep_db_prepare_input($_POST["chartofaccounts_accountcode"]) . "' WHERE currencies_id='" . tep_db_prepare_input($_POST["currencies_id"]) . "'");

                getlables("345");

                echo informationUpdate("success", $lablearray['345'], 'showResult("frmid=frmcurrencies","txtHint")');

                break;

            case 'edit':  // Edit				 
                $currency = Bussiness::$Conn->SQLSelect("SELECT currencies_id,currencies_name,currencies_code,currencies_symbolleft,currencies_symbolright,currencies_decimalplaces,currencies_decimalplaces ,chartofaccounts_accountcode,flag FROM " . TABLE_CURRENCIES . " WHERE currencies_id='" . tep_db_prepare_input($_POST['theid']) . "'");

                echo "formObj.action.value = 'update';\n";
                echo "formObj.currencies_id.value = '" . $currency[0]["currencies_id"] . "';\n";
                echo "formObj.currencies_name.value = '" . $currency[0]["currencies_name"] . "';\n";
                echo "formObj.currencies_code.value = '" . $currency[0]["currencies_code"] . "';\n";
                echo "formObj.currencies_symbolleft.value = '" . $currency[0]["currencies_symbolleft"] . "';\n";
                echo "formObj.currencies_symbolright.value = '" . $currency[0]["currencies_symbolright"] . "';\n";
                echo "formObj.currencies_decimalplaces.value = '" . $currency[0]["currencies_decimalplaces"] . "';\n";
                echo "SelectItemInList(\"chartofaccounts_accountcode\",\"" . $currency[0]['chartofaccounts_accountcode'] . "\");\n";
                echo "document.getElementById('flag').innerHTML = \"<img border='0' src='../" . DIR_WS_FLAG_IMAGES . $currency[0]['flag'] . "'>\";\n";

                if ($currency["currencies_decimalplaces"] == 'Y') {
                    echo "formObj.currencies_isbase.checked = true;\n";
                } else {
                    echo "formObj.currencies_isbase.checked = false;\n";
                }
                break;

            case 'search':  // Search				 
                $query = "SELECT * FROM " . TABLE_CURRENCIES . " WHERE currencies_name LIKE '%" . tep_db_prepare_input($_POST['searchterm']) . "%' OR currencies_code LIKE '%" . tep_db_prepare_input($_POST['searchterm']) . "%'";
                break;

            default:
                $query = "SELECT name,currencies_name,currencies_code,currencies_decimalplaces,currencies_symbolleft,currencies_symbolright,currencies_isbase FROM " . TABLE_CURRENCIES . " ORDER BY currencies_name ASC";
                break;
        }
        getlables("652,659,654,658,655,656,657,667");
        $_SESSION['reportname'] = $lablearray['652'];
        $_SESSION['reporttitle'] = $lablearray['652'];
        $_SESSION['downloadlist'] = $query;

        $fieldlist = array('name', 'currencies_name', 'currencies_code', 'currencies_decimalplaces', 'currencies_isbase', 'currencies_symbolleft', 'currencies_symbolright');
        $keyfield = 'currencies_id';
        $gridcolumnnames = array($lablearray['667'], $lablearray['659'], $lablearray['654'], $lablearray['657'], $lablearray['658'], $lablearray['655'], $lablearray['656']);

        break;


    case 'frmmanageusers':     
     //  $formdata = Common::decodeSerialisedPagedata($_POST['pageparams']);

       $branchinfo = Common::convertobjectToArray($objects['branchinfo']);

        switch ($_POST['action']) {
             case 'delete':  // delete
                Common::getlables("218", "", "", $Conn);
                 Bussiness::$Conn->SQLDelete(TABLE_USERS,'user_id', $_POST['theid']);
                 echo 'INFO.'.Common::$lablearray['218']; 
                 exit();
                break;
            case 'add':  // add
            case 'update':  // update    
                // VALIDATIONS
                Common::getlables("213,214,649,1563,1564,1565,1521,977", "", "", $Conn);
               
                if($formdata['user_firstname']==""):
                     echo 'ERR '.Common::$lablearray['213']; 
                     exit();
                endif;
                
                if($formdata['user_lastname']==""):
                     echo 'ERR '.Common::$lablearray['214']; 
                     exit();
                endif;
                
                if($formdata['user_username']=="649"):
                     echo 'ERR '.Common::$lablearray['']; 
                     exit();
                endif;      
                
                if($formdata['action']!='update'){
                    if($formdata['user_password']==""):
                         echo 'ERR '.Common::$lablearray['1563']; 
                         exit();
                    endif;
                }
                
                if($formdata['action']!='update'):
                     if($formdata['user_password2']==""):
                        echo 'ERR '.Common::$lablearray['1563']; 
                        exit();
                     endif;
                endif;
               
                
                if($formdata['user_password']!='' || $formdata['user_password2']!=''):
                    if($formdata['user_password']!=$formdata['user_password2']):
                         echo 'ERR '.Common::$lablearray['1564']; 
                         exit();
                    endif;
                endif;
                
                if($formdata['lang']==""):
                     echo 'ERR '.Common::$lablearray['1565']; 
                     exit();
                endif;
                
                if($formdata['licence_build']==""):
                     echo 'ERR '.Common::$lablearray['1521']; 
                     exit();
                endif;
                
                 if($formdata['pbranch_code']==""):
                     echo 'ERR '.Common::$lablearray['1521']; 
                     exit();
                endif;
                                
                // CHECK USER             
                if($formdata['action']!="update"):
                    
                    $user_results =  Bussiness::$Conn->SQLSelect("SELECT user_id,user_password FROM " . TABLE_USERS . " WHERE user_username='" . tep_db_prepare_input($formdata['user_username']) . "' OR user_accesscode = '" . tep_db_prepare_input($formdata['user_accesscode']) . "'");
                    if ($user_results[0]['user_id']!='') {
                       echo 'ERR '.Common::$lablearray['977']; 
                       exit();
                    }
                  $uid = Common::uniqidReal(); 
                  Common::addKeyValueToArray($formdata, 'UCODE',$uid);   
                  
                else:
                    
                    if($formdata['user_password']=='' || $formdata['user_password2']==''):                     
                         $formdata['user_password'] = $user_results[0]['user_password'];                   
                    endif;
                
                    Common::replace_key_function($formdata, 'usercode', 'UCODE');  
                endif;
             
                // ADD USER           
                Common::replace_key_function($formdata, 'action', 'ACTION'); 
                Common::replace_key_function($formdata, 'user_firstname', 'FNAME');    
                Common::replace_key_function($formdata, 'user_lastname', 'LNAME');
                Common::replace_key_function($formdata, 'user_middlename', 'MNAME'); 
                Common::replace_key_function($formdata, 'user_username', 'UNAME'); 
                Common::replace_key_function($formdata, 'user_password', 'PWD');
                Common::replace_key_function($formdata, 'user_accesscode', 'ACODE'); 
                Common::replace_key_function($formdata, 'lang', 'LANG'); 
                Common::replace_key_function($formdata, 'licence_build', 'LIC'); 
                Common::replace_key_function($formdata, 'pbranch_code', 'PBRANCHCODE'); 
                Common::replace_key_function($formdata, 'user_isactive', 'ACTIVE'); 
                Common::replace_key_function($formdata, 'pass_expdate', 'EXP');
                Common::replace_key_function($formdata, 'user_id','UID'); 
                $formdata['PWD'] = fnEncrypt($formdata['PWD'], 'PASSWORD');  
                
                Bussiness::$Conn->setAutoCommit();
                Bussiness::$Conn->beginTransaction();
                
                // ADD BRANCHES
                if(count($branchinfo)>0){
                    Common::addKeyValueToArray($formdata, 'TABLE',TABLE_USERBRANCHES); 
                    foreach($branchinfo as $key=>$branch):
                        unset($form_data);
                        if($branch==$formdata['PBRANCHCODE']):
                            $formdata['PBRANCHCODE']='Y';
                        else:
                            $formdata['PBRANCHCODE']='N'; 
                        endif;
                        
                       Common::addKeyValueToArray($formdata, 'BRANCHCODE',$branch);
                       $form_data[]= $formdata;
                       Bussiness::covertArrayToXML($form_data, false);
                    endforeach;
                }
                unset($form_data);
               
                Common::addKeyValueToArray($formdata, 'TABLE',TABLE_USERS);
                $form_data[]= $formdata;
                Bussiness::covertArrayToXML($form_data, true);
               // $tabledata['xml_data'] = Common::$xml;                
                Bussiness::PrepareData(true);
              
               // Bussiness::$Conn->endTransaction();
    
                echo '1111111';
                 
                break;

            case 'edit': // edit
            case 'eval': // edit    
                $user_results = Bussiness::$Conn->SQLSelect("SELECT * FROM " . TABLE_USERS . " WHERE user_id='" . $_POST['theid'] . "'");
                echo "formObj.action.value = 'update';\n";
                echo "formObj.user_id.value = '" . $user_results[0]["user_id"] . "';\n";
                echo "formObj.usercode.value = '" . $user_results[0]["user_usercode"] . "';\n";
                echo "formObj.action.value ='update';\n";
                echo "formObj.user_firstname.value = '" . $user_results[0]["user_firstname"] . "';\n";
                echo "formObj.user_lastname.value = '" . $user_results[0]["user_lastname"] . "';\n";
                echo "formObj.user_middlename.value = '" . $user_results[0]["user_middlename"] . "';\n";
                echo "formObj.user_username.value = '" . $user_results[0]["user_username"] . "';\n";
                echo " $('#user_username').attr('disabled','disabled');\n";
                echo "formObj.user_password.value ='';\n";
                echo "formObj.user_password2.value ='';\n";

                if ($user_results[0]["user_isactive"] == 'Y') {
                    echo "formObj.user_isactive.checked = true;\n";
                    echo "formObj.user_isactive.value = 'Y';\n";
                } else {
                    echo "formObj.user_isactive.checked = false;\n";
                    echo "formObj.user_isactive.value = 'N';\n";
                }
                echo "formObj.user_accesscode.value = '" . $user_results[0]["user_accesscode"] . "';\n";

                //echo "formObj.user_passexp.value = '".changeMySQLDateToPageFormat($users["user_passexp"])."';\n";
                echo "SelectItemInList(\"lang\",\"" . $user_results[0]["user_lang"] . "\");\n";

                $lic_results = Bussiness::$Conn->SQLSelect("SELECT  licence_build FROM " . TABLE_USERBRANCHES . " WHERE user_usercode='" . $user_results[0]["user_usercode"] . "'");
     
                echo "SelectItemInList(\"licence_build\",\"" . $lic_results[0]['licence_build'] . "\");\n";
              
                $operatorbranches = array();

                $branch_results = Bussiness::$Conn->SQLSelect("SELECT op.branch_code, bankbranches_name,us.parentbranch,us.user_usercode FROM " . TABLE_OPERATORBRANCHES . " op LEFT JOIN ".TABLE_USERBRANCHES." us ON  op.branch_code=us.branch_code  WHERE op.licence_build='" . $lic_results[0]['licence_build'] . "'");

                $operatorbranches = array();
                
                $bselected = "";
                $pcodeselected = "";
                foreach($branch_results as $key=>$value) {
                    $operatorbranches[$value['branch_code']] = $value['bankbranches_name'];
                    
                   // $bselected = $bselected . "$('#branch_code option:eq(0)').prop('selected', 'selected');";
                   if($user_results[0]["user_usercode"]==$value['user_usercode']):
                       $bselected = $bselected . "$('#branch_code option[value=\'" . $value['branch_code'] . "\']').prop('selected',true);\n"; 
                   endif;
                    
                     
                   if($value['parentbranch']=='Y'):
                       $pcodeselected =$value['branch_code'];  
                    endif;
                }
			
                $combo = DrawComboFromArray($operatorbranches, 'branch_code', '', 'combo', '', 'multiple');
                
                echo "$('#txtBranches').html(\"" . $combo . "\");\n";
                echo "SelectItemInList(\"pbranch_code\",\"" . $pcodeselected . "\");\n";
                echo $bselected;            

                break;

            case 'update':  // Update
                getlables("345");

                // check if password has been changed
                if (isset($_POST['user_password'])) {

                    tep_db_query("UPDATE " . TABLE_USERS . " SET user_firstname='" . tep_db_prepare_input($_POST["user_firstname"]) . "',user_middlename='" . tep_db_prepare_input($_POST["user_middlename"]) . "',user_lastname='" . tep_db_prepare_input($_POST["user_lastname"]) . "',user_lang='" . tep_db_prepare_input($_POST["lang"]) . "',user_password='" . fnEncrypt($_POST['user_password'], 'PASSWORD') . "',user_isactive='" . tep_db_prepare_input($_POST["user_isactive"]) . "',user_passexp=" . common::changeDateFromPageToMySQLFormat($_POST["user_passexp"]) . ",user_accesscode='" . tep_db_prepare_input($_POST["user_accesscode"]) . "' WHERE user_id='" . tep_db_prepare_input($_POST["user_id"]) . "'");
                } else {
                    tep_db_query("UPDATE " . TABLE_USERS . " SET user_firstname='" . tep_db_prepare_input($_POST["user_firstname"]) . "',user_middlename='" . tep_db_prepare_input($_POST["user_middlename"]) . "',user_lastname='" . tep_db_prepare_input($_POST["user_lastname"]) . "',user_lang='" . tep_db_prepare_input($_POST["lang"]) . "',user_isactive='" . tep_db_prepare_input($_POST["user_isactive"]) . "',user_passexp=" . common::changeDateFromPageToMySQLFormat($_POST["user_passexp"]) . ",user_accesscode='" . tep_db_prepare_input($_POST["user_accesscode"]) . "' WHERE user_id='" . tep_db_prepare_input($_POST["user_id"]) . "'");
                }

                $branches = $_POST['branches'];


                tep_db_query("DELETE FROM " . TABLE_USERBRANCHES . " WHERE user_accesscode='" . tep_db_prepare_input($_POST['user_accesscode']) . "'");

                if (isset($branches)) {
                    foreach ($branches as $key => $value) {
                        tep_db_query("INSERT INTO " . TABLE_USERBRANCHES . "(user_accesscode,user_usercode,branch_code,licence_build) VALUES('" . tep_db_prepare_input($_POST['user_accesscode']) . "','" . tep_db_prepare_input($_POST['usercode']) . "','" . $value . "','" . tep_db_prepare_input($_POST['licence_build']) . "')");
                    }
                }
                echo $lablearray['345'];
                break;

            case 'delete':  // Delete

                /* $query_results = tep_db_query("SELECT communitycordinators_id FROM ".TABLE_COMMUNITYCORDINATORS." WHERE careprogrammes_id ='".tep_db_prepare_input($_POST['id'])."'");

                  if(tep_db_num_rows($query_results)>0){

                  getlables("512");
                  echo informationUpdate("fail",$lablearray['512'],'');
                  return;
                  } */

                break;

            case 'search': // search
                $query = "SELECT user_id, user_firstname,user_lastname,user_username,user_accesscode,user_usercode,DATE_FORMAT(last_login,'%d/%m/%Y') AS last_login,IF(user_isactive='Y','" . $lablearray['274'] . "','" . $lablearray['639'] . "') As user_isactive FROM " . TABLE_USERS . "  WHERE  (user_firstname LIKE '" . $_POST["searchterm"] . "%' OR user_lastname LIKE '" . $_POST["searchterm"] . "%' OR  user_username '" . $_POST["searchterm"] . "%') ORDER BY user_firstname,user_lastname ASC";
                break;

            default:  // no action specified
                $query = "SELECT user_id,user_firstname,user_lastname,user_username,user_accesscode,user_usercode,DATE_FORMAT(last_login,'%d/%m/%Y') AS last_login,IF(user_isactive='Y','" . $lablearray['274'] . "','" . $lablearray['639'] . "') As user_isactive FROM " . TABLE_USERS . " ORDER BY user_firstname,user_lastname DESC";
                break;
        }


        $_SESSION['reportname'] = $lablearray['159'];
        $_SESSION['reporttitle'] = $lablearray['159'];
        $_SESSION['downloadlist'] = $query;

        $fieldlist = array('user_firstname', 'user_lastname', 'user_username', 'user_isactive', 'user_accesscode', 'last_login');
        $keyfield = 'user_id';
        $gridcolumnnames = array($lablearray['238'], $lablearray['240'], $lablearray['3'], $lablearray['197'], $lablearray['976'], $lablearray['586']);

        break;


    case 'frmautoupdate':

        switch ($_POST['action']) {

            case 'step1':

                ini_set('max_execution_time', 60);

                $getVersions = file_get_contents('http://www.thebursar.com/thebursar/versions/current-release-versions.php') or die('ERROR');

                if ($getVersions != '') {


                    $thelastbuild = get_licenceInfo();

                    //If we managed to access that file, then lets break up those release versions into an array.
                    $message = $message . '<p>Current Build: ' . $thelastbuild . '</p>';
                    $availableversions = explode(",", $getVersions);
                    $_SESSION['versionList'] = explode(",", $getVersions);
                    $versionList = $availableversions;
                    $found = false;
                    foreach ($versionList as $aV) {
                        // compare current version with versions on website

                        if ($aV > $thelastbuild) {

                            $versionList[] = $aV;
                            //$message = $message.'<p>Now reading current releases list..</p>';
                            //$message = $message. '<p>New Update Found: '.$aV.'</p>';
                            //	$message = $message. "<p>Click <a href=\"#\" onClick=updateForm(\"step2\",\"\") >here to download updates</a></p>";
                            $found = true;
                        }

                        if ($found == true) {
                            $message = "<p style=\"font-size:16px;\"><a href=\"#\" onClick=updateForm(\"step2\",\"\") style=\"font-size:20px;color:#FF6600\" ><b>Click here to download updates</b></a></p>";
                        }
                    }


                    if ($found == false) {
                        $message = $message . '<p>Sorry, no updates available.</p>';
                    } else {
                        $_SESSION['versionList'] = $versionList;
                    }
                } else
                    $message = '<p>Could not find latest realeases.</p>';

                echo "document.getElementById(\"txtHint\").innerHTML='" . $message . "'";

                break;

            case 'step2':
                //Download The File If We Do Not Have It			
                $versionList = $_SESSION['versionList'];
                //print_r( $_SESSION['versionList']);
                //unset($versionList[1]);

                foreach ($versionList as $key => $value) {

                    $filepath = realpath('');

                    if (!is_file($filepath . '/Updates/' . trim($value) . '.zip')) {


                        $message = $message . '<p>Downloading New Update</p>';

                        $newUpdate = file_get_contents('http://www.thebursar.com/thebursar/versions/downloads/' . trim($value) . '.zip');
                        if (!is_dir($filepath . '/Updates/'))
                            mkdir($filepath . '/Updates/');
                        $dlHandler = fopen($filepath . '/Updates/' . trim($value) . '.zip', 'w');

                        if (!fwrite($dlHandler, $newUpdate)) {
                            $message = $message . '<p>Could not save new update. Operation aborted.</p>';
                            exit();
                        }
                        fclose($dlHandler);
                        $message = $message . '<p>Update downloaded and saved</p>';
                    } else
                        $message = $message . '<p>Update already downloaded.</p>';
                }

                $message = $message . '<p>Update Ready <a href=\"#\" onClick=updateForm(\"step3\",\"' . trim($value) . '\") >Install Now?.</a></p>';

                echo "document.getElementById(\"txtHint\").innerHTML=document.getElementById(\"txtHint\").innerHTML +'" . $message . "'";
                break;

            case 'step3':

                if ($_POST['build'] != "") {

                    $filepath = realpath('');

                    //Open The File And Do Stuff
                    $zipHandle = zip_open($filepath . '/Updates/' . $_POST['build'] . '.zip');
                    $message = $message . '<ul>';
                    while ($aF = zip_read($zipHandle)) {

                        $thisFileName = zip_entry_name($aF);
                        $thisFileDir = dirname($thisFileName);

                        //Continue if its not a file
                        if (substr($thisFileName, -1, 1) == '/')
                            continue;

                        //Make the directory if we need to...
                        if (!is_dir($filepath . '/' . $thisFileDir)) {
                            mkdir($filepath . '/' . $thisFileDir);
                            $message = $message . '<li>Created Directory ' . $thisFileDir . '</li>';
                        }


                        //Overwrite the file
                        if (!is_dir($filepath . '/' . $thisFileName)) {
                            $message = $message . '<li>' . $thisFileName . '...........';
                            $contents = zip_entry_read($aF, zip_entry_filesize($aF));
                            $contents = str_replace("\\r\\n", "\\n", $contents);
                            $updateThis = '';

                            // check see if we should exute code in this file or not
                            if ($thisFileName == 'upgrade.php') {
                                $upgradeExec = fopen('upgrade.php', 'w');
                                fwrite($upgradeExec, $contents);
                                fclose($upgradeExec);
                                include ('upgrade.php');
                                unlink('upgrade.php');
                                $message = $message . ' EXECUTED</li>';
                            } else { // Else 
                                $updateThis = fopen($filepath . '/' . $thisFileName, 'w');
                                fwrite($updateThis, $contents);
                                fclose($updateThis);
                                unset($contents);
                                $message = $message . ' UPDATED</li>';
                            }
                        }
                    }


                    $updated = true;
                }

                $message = $message . '</ul>';

                if ($updated == true) {
                    tep_db_query("UPDATE " . TABLE_LICENCE . " set licence_build='" . $_POST['build'] . "'");
                    $message = $message . '<p >Update was successfull!</p>';
                } else if ($found != true)
                    $message = $message . '<p>Update failed!.</p>';

                echo "document.getElementById(\"txtHint\").innerHTML=document.getElementById(\"txtHint\").innerHTML +'" . $message . "'";
                break;

            default:
                break;
        }

        break;


    case 'frmcloseperiod';
            switch ($formdata['selectaction']) {

            case 'C':
            case 'O':    

                Common::getlables("436,1533,1534,1535,1536,1537", "", "",$Conn);
                
                if (STARTFINYEAR == "") {                         
                    echo "INFO".Common::$lablearray['436'];
                    break 2;
                }
                
                if($formdata['selectperiod']=='' || $formdata['selectaction']==''):
                    echo "INFO".Common::$lablearray['1533'];
                    break 2;
                endif;
                
                if($formdata['txtTo']==''):
                    echo "INFO".Common::$lablearray['1534'];
                    break 2;
                endif;
                           
                Common::prepareParameters($parameters, 'branch_code', BRANCHCODE);
                Common::prepareParameters($parameters, 'enddate', Common::changeDateFromPageToMySQLFormat($formdata['txtTo']));
                Common::prepareParameters($parameters, 'startdate', Common::changeDateFromPageToMySQLFormat($formdata['txtFrom']));                
                Common::prepareParameters($parameters, 'user_id', $_SESSION['user_id']);
                Common::prepareParameters($parameters, 'caction', $formdata['selectaction']);
                Common::prepareParameters($parameters, 'cperiod', $formdata['selectperiod']);
                Common::prepareParameters($parameters, 'plang',   $_SESSION['P_LANG']); 
                Common::prepareParameters($parameters, 'code', 'OPENCLOSEPERIOD');
                $results_array = Common::common_sp_call(serialize($parameters), '', $Conn, true);
        
                if($results_array['ouput']=='0'):
                    echo "MSG.".Common::$lablearray['1535'];
                    break 2;
                endif;
                
                if($results_array['ouput']=='1' && $formdata['selectaction']=='C'):
                    echo "MSG.".Common::$lablearray['1536']; 
                    break 2;
                endif;
                
                if($results_array['ouput']=='1' && $formdata['selectaction']=='O'):
                    echo "MSG.".Common::$lablearray['1537']; 
                    break 2;
                endif;
                                
                if($results_array['ouput']=='3' && $formdata['selectaction']=='C'):
                   echo "MSG.".Common::$lablearray['1535']; 
                   break 2;
                endif;
         
             break;
            default:
                break;
        }
        break;

    case 'frmcashentries':

        switch ($_POST['action']) {        
          
            case 'add':
               Common::getlables("666,1531,1459,1216", "", "", $Conn);
                if($formdata['currencies_id']==""):                    
                    echo 'ERR '.Common::$lablearray['666'];
                    exit();                    
                endif;
                
                if($formdata['cashitems']==""):                    
                    echo 'ERR '.Common::$lablearray['1531'];
                    exit();                    
                endif;
                
                
                if($formdata['cashaccounts_id']==""):                    
                    echo 'ERR '.Common::$lablearray['1459'];
                    exit();                    
                endif;
                
                Common::addKeyValueToArray($formdata, 'TCODE', Common::generateTransactionCode($_SESSION['user_id']));
                Common::replace_key_function($formdata, 'txtdate', 'DATE');
                $formdata['DATE'] = Common::changeDateFromPageToMySQLFormat($formdata['DATE']);                
                Common::replace_key_function($formdata, 'txtAmount', 'AMOUNT');              
                Common::replace_key_function($formdata, 'cashaccounts_id', 'GLACC1');
                 Common::replace_key_function($formdata, 'txtBalance', 'BALANCE');
                Common::replace_key_function($formdata, 'cashitems', 'GLACC2');
                Common::replace_key_function($formdata, 'txtDescription', 'DESC');
                Common::replace_key_function($formdata, 'currencies_id', 'CURRENCIES_ID');
                Common::replace_key_function($formdata, 'txtVoucher', 'VOUCHER');
                Common::addKeyValueToArray($formdata, 'TRANCODE','MB000');
            
                
                if (SETTTING_CURRENCY_ID != $formdata['CURRENCIES_ID']) {
                    
                    $ex_rate_array = Common::getExchangeRate($formdata['CURRENCIES_ID'],$formdata['DATE']);
                    $formdata['FXID'] = $ex_rate_array['forexrates_id'];                                                  
                    $formdata['FCAMT'] =$formdata['AMOUNT'];
                    $formdata['AMOUNT']=  $formdata['AMOUNT']*$ex_rate_array['forexrates_midrate'];
                }
                
               
                 // payment
                if ($formdata['type'] == 'P') {
                    
                     if(abs($formdata['AMOUNT'])>$formdata['BALANCE']){
                           echo 'ERR '.Common::$lablearray['1216'];
                           exit();
                      }
                
                    
                    $aLines[] = array('DEBIT' =>0,'CREDIT' => abs($formdata['AMOUNT']), 'GLACC' => $formdata['GLACC1']);
                    $aLines[] = array('DEBIT' => abs($formdata['AMOUNT']),'CREDIT'=>0, 'GLACC' => $formdata['GLACC2']);                    

                }

                // receipt
                if ($formdata['type'] == 'R') {
                    
                    $aLines[] = array('DEBIT' =>abs($formdata['AMOUNT']),'CREDIT' =>0, 'GLACC' => $formdata['GLACC1']);
                    $aLines[] = array('DEBIT' =>0,'CREDIT'=>abs($formdata['AMOUNT']), 'GLACC' => $formdata['GLACC2']);

                }        
                
                
                foreach ($aLines as $key => $value) {

                    $aLines[$key]['DATE'] = $formdata['DATE'];               
                    $aLines[$key]['BRANCHCODE'] = BRANCHCODE;
                    $aLines[$key]['TCODE'] = $formdata['TCODE'];
                    $aLines[$key]['FUNDCODE'] = (isset($formdata['FUNDCODE']) ? $formdata['FUNDCODE'] : '0000' );
                    $aLines[$key]['DONORCODE'] = (isset($formdata['DONORCODE']) ? $formdata['DONORCODE'] : '00000' );                   
                    $aLines[$key]['VOUCHER'] = $formdata['VOUCHER'];
                    $aLines[$key]['CCODE'] = $formdata['CCODE'];
                    $aLines[$key]['FXID'] = (isset($formdata['FXID']) ? $formdata['FXID'] : '0' ); 
                    $aLines[$key]['FCAMT'] = (isset($formdata['FCAMT']) ? $formdata['FCAMT'] : '0' ); 
                    $aLines[$key]['TRANCODE'] = $formdata['TRANCODE'];
                    $aLines[$key]['CURRENCIES_ID'] = $formdata['CURRENCIES_ID'];
                    $aLines[$key]['DESC'] = $formdata['DESC'];
                    $aLines[$key]['CLIENTIDNO'] = '';
                    $aLines[$key]['TABLE'] = TABLE_GENERALLEDGER;
                }
                Bussiness::$Conn->setAutoCommit();
                Bussiness::$Conn->beginTransaction();
                Bussiness::covertArrayToXML($aLines, true);
                
                if (Common::$lablearray['E01'] != "") {
                     Bussiness::$Conn->cancelTransaction();
                     echo 'ERR '. Common::$lablearray['E01'];
                     exit();
                }else{
                   
                    Bussiness::PrepareData(true);//array("FORMDATA" => $tabledata, "OPTIONS" => array('' => 1)), TABLE_XMLTRANS, false);
                  //  Bussiness::$Conn->endTransaction();
                    
                }
                echo '1111111';
           
                break 2;

            default:
                //$query = "SELECT  * FROM ".TABLE_CASHITEMS." ORDER BY cashitems_id DESC";
                break 2;
        }


    case 'frmcashitems':
        switch ($_POST['action']) {

            case 'add':
                $query_results = tep_db_query("SELECT cashitems_name FROM " . TABLE_CASHITEMS . " WHERE  cashitems_name='" . $_POST['cashitems_name'] . "'");

                if (tep_db_num_rows($query_results) > 0) {
                    getlables("308");
                    echo informationUpdate('fail', $lablearray['308']); // This information is already registered in the system
                    break 2;
                } else {
                    tep_db_query("INSERT INTO " . TABLE_CASHITEMS . " (cashitems_name,chartofaccounts_accountcode) VALUES ('" . tep_db_prepare_input($_POST['cashitems_name']) . "','" . tep_db_prepare_input($_POST['chartofaccounts_accountcode']) . "')");
                    getlables("218");
                    echo informationUpdate('add', $lablearray['218']);
                }
                break;

            case 'update':
                tep_db_query("UPDATE " . TABLE_CASHITEMS . " SET cashitems_name='" . tep_db_prepare_input($_POST['cashitems_name']) . "',chartofaccounts_accountcode='" . $_POST['chartofaccounts_accountcode'] . "' WHERE cashitems_id='" . $_POST['cashitems_id'] . "'");
                getlables("218");
                echo informationUpdate("", $lablearray['218']);
                break;

            case 'edit':
                $results_query = tep_db_query("SELECT  * FROM " . TABLE_CASHITEMS . " WHERE cashitems_id='" . $_POST['id'] . "'");

                $results = tep_db_fetch_array($results_query);
                echo "formObj.cashitems_id.value = '" . $results['cashitems_id'] . "';\n";
                echo "formObj.cashitems_name.value = '" . $results['cashitems_name'] . "';\n";
                echo "SelectItemInList(\"chartofaccounts_accountcode\",\"" . $results['chartofaccounts_accountcode'] . "\");\n";
                echo "formObj.action.value = 'update';";

                break;
            case 'search':
                $query = "SELECT * FROM " . TABLE_CASHITEMS . " WHERE (cashitems_name LIKE '%" . tep_db_prepare_input($_POST["searchterm"]) . "%'  OR chartofaccounts_accountcode  LIKE '%" . tep_db_prepare_input($_POST["searchterm"]) . "%')";
                break;

            default:
                $query = "SELECT  * FROM " . TABLE_CASHITEMS . " ORDER BY cashitems_id DESC";
                break;
        }


        $lables_array = $grid_lables_lablearray + getlables("304,309,306");
        $_SESSION['reportname'] = $lablearray['304'];
        $_SESSION['reporttitle'] = $lablearray['309'] . " " . $lablearray['304'];
        $_SESSION['downloadlist'] = $query;
        $fieldlist = array('cashitems_name', 'chartofaccounts_accountcode');
        $keyfield = 'cashitems_id';
        $gridcolumnnames = array($lablearray['304'], $lablearray['306']);
        break;


    case 'frmgeneralsettings':

        switch ($_POST['action']) {

            case 'update':


                $timestamp = strtotime(common::changeDateFromPageToMySQLFormat(tep_db_prepare_input($_POST['STARTFINYEAR'])));
                $day = date('d', $timestamp);
                //echo $day;
                //exit();
                // check see if date the the 1st of the month
                if ($day != "1" || $day != "01") {
                    $lablearray = getlables("435");
                    echo informationUpdate("fail", $lablearray['435'], "");
                    break;
                }
                require_once('settings/settings.php');
                $setting = new Settings();
                $setting->configurationsettings = array('DEFAULT_LANGUAGE' => tep_db_prepare_input($formdata['setting_default_language']), 'NAME_OF_INSTITUTION' => tep_db_prepare_input($formdata['NAME_OF_INSTITUTION']), 'SETTTING_STUDENT_PHOTO_DIR_PATH' => tep_db_prepare_input($formdata['setting_student_photo_dir_path']), 'SETTTING_STAFF_PHOTO_DIR_PATH' => tep_db_prepare_input($formdata['setting_staff_photo_dir_path']), 'SETTTING_CURRENCY_ID' => tep_db_prepare_input($formdata['setting_currency_code']), 'SETTTING_DATE_FORMAT' => tep_db_prepare_input($formdata['setting_date_format']), 'SETTTING_ROUND_TO' => tep_db_prepare_input($formdata['setting_round_to'])
                    , 'SETTING_DAYS_MONTH' => tep_db_prepare_input($formdata['SETTING_DAYS_MONTH'])
                    , 'SETTING_DAYS_WEEK' => tep_db_prepare_input($formdata['SETTING_DAYS_WEEK'])
                    , 'ACC_PROFIT_LOSS' => tep_db_prepare_input($formdata['ACC_PROFIT_LOSS'])
                    , 'STARTFINYEAR' => tep_db_prepare_input($formdata['STARTFINYEAR'])
                    , 'SETTING_EXCLUDE_HOLIDAYS' => tep_db_prepare_input($formdata['SETTING_EXCLUDE_HOLIDAYS'])
                    , 'SETTING_EXCLUDE_WEEKENDS' => tep_db_prepare_input($formdata['SETTING_EXCLUDE_WEEKENDS'])
                    , 'SETTING_POSTING_CLOSED_PERIOD_SL' => tep_db_prepare_input($formdata['SETTING_POSTING_CLOSED_PERIOD_SL'])
                    , 'SETTING_POSTING_CLOSED_PERIOD_GL' => tep_db_prepare_input($formdata['SETTING_POSTING_CLOSED_PERIOD_GL'])
                    , 'SETTTING_CURRENCY_ID' => tep_db_prepare_input($formdata['SETTTING_CURRENCY_ID'])
                    , 'SETTING_PAYMODE' => tep_db_prepare_input($formdata['SETTING_PAYMODE'])
                    , 'SETTING_TAX_CLASSES' => tep_db_prepare_input($formdata['SETTING_TAX_CLASSES'])
                    , 'SETTING_CURRENCY_DENO' => tep_db_prepare_input($formdata['SETTING_CURRENCY_DENO'])
                    , 'SETTING_TAX_ON_IND_INcOME' => tep_db_prepare_input($formdata['SETTING_TAX_ON_IND_INcOME']));

                $setting->UpdateSettings();
                $setting->UpdateAccounts();
               //$lablearray = getlables("218");

                echo '1111111';
                break 2;
            default:
                break 2;
        }
        break;

    case 'frmpublicholidays':

        switch ($_POST['action']) {

            case 'view':
                $query = "SELECT * FROM " . TABLE_TAXES . " WHERE taxes_id='" . $_POST['taxes_id'] . "'";
                break;

            case 'add':

                tep_db_query("INSERT INTO " . TABLE_PUBLICHOLIDAYS . " (publicholidays_date,publicholidays_description,publicholidays_reoccurs) VALUES (" . common::changeDateFromPageToMySQLFormat($_POST['publicholidays_date']) . ",'" . $_POST['publicholidays_description'] . "','" . $_POST['publicholidays_reoccurs'] . "')");

                echo informationUpdate("", "Information has been successfully added", "showResult('frmid=frmpublicholidays','txtHint')");
                break;

            case 'delete':
                tep_db_query("DELETE FROM " . TABLE_PUBLICHOLIDAYS . " WHERE publicholidays_id='" . $_POST['id'] . "'");
                break;

            case 'update':
                tep_db_query("UPDATE " . TABLE_PUBLICHOLIDAYS . " SET publicholidays_date =" . common::changeDateFromPageToMySQLFormat($_POST['publicholidays_date']) . ",publicholidays_description='" . $_POST['publicholidays_description'] . "',publicholidays_reoccurs='" . $_POST['publicholidays_reoccurs'] . "' WHERE publicholidays_id = '" . $_POST['publicholidays_id'] . "'");

                echo informationUpdate("", "Information has been successfully updated.", "showResult('frmid=frmpublicholidays','txtHint')");
                break;

            case 'edit':
                $results_query = tep_db_query("SELECT * FROM " . TABLE_PUBLICHOLIDAYS . " WHERE publicholidays_id ='" . $_POST['id'] . "'");

                $results = tep_db_fetch_array($results_query);
                echo "formObj.publicholidays_id.value = '" . $results['publicholidays_id'] . "';\n";
                echo "formObj.publicholidays_date.value = '" . changeMySQLDateToPageFormat($results['publicholidays_date']) . "';\n";
                echo "formObj.publicholidays_description.value = '" . $results['publicholidays_description'] . "';\n";

                if ($results['publicholidays_reoccurs'] == "Y") {
                    echo "formObj.publicholidays_reoccurs.checked = true;\n";
                } else {
                    echo "formObj.publicholidays_reoccurs.checked = false;\n";
                }

                echo "formObj.action.value = 'update';\n";
                break;
            default:
                $query = "SELECT publicholidays_id,DATE_FORMAT(publicholidays_date,'%d/%m/%Y') AS publicholidays_date,publicholidays_description,publicholidays_reoccurs FROM " . TABLE_PUBLICHOLIDAYS;
                break;
        }

        $_SESSION['reportname'] = "Public Holidays";
        $_SESSION['reporttitle'] = "Public Holidays";
        $_SESSION['downloadlist'] = $query;
        $fieldlist = array('publicholidays_date', 'publicholidays_description', 'publicholidays_reoccurs');
        $keyfield = 'publicholidays_id';
        $gridcolumnnames = array('Date', 'Description', 'Reccurs');

        break;


    case 'frmpayroll':

        $onclick = "NON";
        $newgrid->cpara = "PAYROLL";
        $newgrid->ColumnRightHeading = "Cheque Number";
        $newgrid->OnclickHeaderCheckbox = 'checkallUnckeckall(this)';

        $mutiplecheques = $_POST['mutiplecheque'];
        $paydifferentperiods = $_POST['paydifferentperiods'];
        $multiplebankacounts = $_POST['multiplebankacounts'];


        if ($_POST['paydifferentperiods'] == "1") {
            $date_from = explode(",", $_POST['date_from']);
            $date_to = explode(",", $_POST['date_to']);
        }

        if ($_POST['mutiplecheque'] == "1") {
            $cheqnos = explode(",", $_POST['cheques_no']);
        }

        if ($_POST['multiplebankacounts'] == "1") {
            $bankbranches = explode(",", $_POST['mbankbranches']);
        }

        // quite all elenemts in this commma delimted list


        $newgrid->extraFields[''] = "";

        switch ($_POST['action']) {

            case 'view':
                $query = "SELECT * FROM " . TABLE_EMPLOYEES . " WHERE employees_id='" . $_POST['employees_id'] . "'";
                break;

            case 'add':

                $holidays = array();

                $holiday_query = tep_db_query("SELECT publicholidays_id,publicholidays_date FROM " . TABLE_PUBLICHOLIDAYS);

                while ($theday = tep_db_fetch_array($holiday_query)) {
                    $holidays[$theday['publicholidays_id']] = $theday['publicholidays_date'];
                }

                if ($_POST['departments_id'] != "") {
                    $SQL = "  departments_id = '" . trim($_POST['departments_id']) . "'";
                }

                if ($_POST['employees_payperiod'] != "" && $SQL != "") {

                    $SQL = $SQL . "   AND employees_payperiod = '" . trim($_POST['employees_payperiod']) . "'";
                } else {

                    $SQL = " employees_payperiod LIKE '%" . trim($_POST['employees_payperiod']) . "%'";
                }

                $accCredit = $_POST['transactiontypes_code'];

                $_POST['selectedemployees'] = "'" . str_replace(",", "','", $_POST['selectedemployees']) . "'";

                // get all employees and their gross
                $employees_query = tep_db_query("Select CONCAT(employees_firstname,' ',employees_lastname) as Name,employees_id,employees_payperiod,socialsecurityorg_id,employees_grosspay,employees_commencementdate FROM " . TABLE_EMPLOYEES . " WHERE " . $SQL . "  AND employees_id IN (" . $_POST['selectedemployees'] . ") AND employees_commencementdate IS NOT NULL");

                if (!tep_db_num_rows($employees_query)) {
                    $lablesarray = getlables("770");
                    echo informationUpdate("fail", $lablesarray['770'], "showResult('frmid=frmpayroll','txtHint')");
                   // break 3;
                }

                if ($mutiplecheques == false && $_POST['transactiontypes_code'] == "CQ") {
                    $bank_query = tep_db_query("Select chartofaccounts_accountcode,bankaccounts_accno FROM " . TABLE_BANKACCOUNTS . " WHERE bankbranches_id='" . $_POST['bankbranches_id'] . "'");

                    //	echo "Select chartofaccounts_accountcode,bankaccounts_accno FROM ".TABLE_BANKACCOUNTS." WHERE bankaccounts_accno='".$_POST['bankaccounts_accno']."'";
                    $bank_gl_account = tep_db_fetch_array($bank_query);
                    $accCredit = $bank_gl_account['chartofaccounts_accountcode'];

                    if ($bank_gl_account == "") {
                        $lablesarray = getlables("704");
                        echo informationUpdate("fail", $lablesarray['704'] . " " . $bank_gl_account['bankaccounts_accno'], "");
                        break 2;
                    }
                }

                $bsaved = true;

                // for view puposes only

                while ($employee_array = tep_db_fetch_array($employees_query)) {

                    $NetPay = $employee_array['employees_grosspay'];

                    // log these details, if use does not have a gross pay 
                    // we are not gona calculate salary
                    if ($NetPay == 0 || $NetPay == "") {
                        continue;
                    }

                    $tcode = Common::generateTransactionCode($_SESSION['user_id']);

                    // get account to credit
                    switch ($_POST['transactiontypes_code']) {

                        case 'CA':// Cash
                            $accCredit = $_POST['cashaccounts_code'];
                            break;

                        case 'CQ': // get bank GL account
                            if ($mutiplecheques == "1") { // use multiple cheques						
                                $bank_query = tep_db_query("Select chartofaccounts_accountcode,bankaccounts_accno FROM " . TABLE_BANKACCOUNTS . " WHERE bankaccounts_accno='" . $_POST['bankbranches' . $employee_array['employees_id']] . "'");
                                $bank_gl_account = tep_db_fetch_array($bank_query);
                                $accCredit = $bank_query['chartofaccounts_accountcode'];
                            }

                            break;

                        case 'BT': // Bank Transfer

                            if ($multiplebankacounts == "1") { // pay frm multiple bank accounts				
                                $bank_query = tep_db_query("Select chartofaccounts_accountcode,bankaccounts_accno FROM " . TABLE_BANKACCOUNTS . " WHERE bankbranches_id='" . $_POST['mbankbranches' . $employee_array['employees_id']] . "'");
                                $bank_gl_account = tep_db_fetch_array($bank_query);
                                $accCredit = $bank_query['chartofaccounts_accountcode'];

                                if ($bank_query['chartofaccounts_accountcode'] == "") {
                                    $bsaved = false;
                                    $lablesarray = getlables("704");
                                    echo informationUpdate("fail", $lablesarray['704'] . " " . $bank_query['bankaccounts_accno'], "");
                                    break 3;
                                }
                            }
                            break;

                        default:
                            break;
                    }


                    // check see if we are paying for multiple periods							
                    if ($_POST['paydifferentperiods'] == "1") {
                        $_POST['date_to'] = $_POST['date_to' . $employee_array['employees_id']];
                        $lastpaydate = $_POST['date_from' . $employee_array['employees_id']];
                    } else {
                        // get last paydate				
                        // check see if we have last pay date	
                        $lastpay_query = tep_db_query("SELECT employees_id,MAX(employeesalaries_paydate) AS thedate FROM " . TABLE_EMPLOYEESALARIES . " WHERE employees_id='" . $employee_array['employees_id'] . "'");
                        $lastpaydate_array = tep_db_fetch_array($lastpay_query);

                        if ($lastpaydate_array['thedate'] != "") {
                            $lastpaydate = changeMySQLDateToPageFormat($lastpaydate_array['thedate']);
                        } else { // use commmencement date
                            $lastpaydate = changeMySQLDateToPageFormat($employee_array['employees_commencementdate']);
                        }

                        // check see if this date is initialised
                        if ($lastpaydate == "") {
                            continue;
                        }

                        $_POST['date_to'] = $_POST['general_date'];
                    }


                    if ($_POST['employees_payperiod'] != "") {
                        $employees_payperiod = $_POST['employees_payperiod'];
                    } else {
                        $employees_payperiod = $employee_array['employees_payperiod'];
                    }


                    // this array has the number of times indocating the number of pay-periods due for payment	
                    if ($_POST['date_to'] < $lastpaydate) {
                        echo informationUpdate("fail", $employee_array['Name'] . " already paid!", "");
                        continue;
                    }
                    $grid_lables_lablearray = $grid_lables_lablearray + getlables("461");

                    if (SETTING_DAYS_MONTH == "") {

                        //	echo informationUpdate("fail",$grid_lables_lablearray['461']);

                        echo informationUpdate("fail", $grid_lables_lablearray['461'], "", "");
                        break 3;
                    }

                    switch ($employees_payperiod) {

                        case 'optH':
                            $workdaysarray = getWorkingPayablePeriod($lastpaydate, $_POST['date_to'], $holidays, "HOURS");
                            break;

                        case 'optW':
                            $workdaysarray = getWorkingPayablePeriod($lastpaydate, $_POST['date_to'], $holidays, "WEEKS");
                            break;

                        case 'optM':

                            $workdaysarray = getWorkingPayablePeriod($lastpaydate, $_POST['date_to'], $holidays, "MONTHS");
                            $nfrequency = $workdaysarray['frequency'];

                            break;

                        case 'optA':
                            $workdaysarray = getWorkingPayablePeriod($lastpaydate, $_POST['date_to'], $holidays, "YEARS");
                            break;

                        case 'optSA':
                            $workdaysarray = getWorkingPayablePeriod($lastpaydate, $_POST['date_to'], $holidays, "MONTHS");
                            break;

                        default:
                            break;
                    }

                    // remove two top elements of unwanted info in this array
                    array_shift($workdaysarray);
                    array_shift($workdaysarray);

                    $nCount = 1;

                    $curdate = getcurrentDateTime();

                    tep_db_BeginTransaction();
                    if ($_POST['mode'] == "1") {

                        if (ACC_SALARIES_WAGES == "") {
                            tep_db_Rollback();
                            $bsaved = false;
                            echo informationUpdate("fail", $grid_lables_lablearray['461'] . " " . $grid_lables_lablearray['462'], "");
                            break 3;
                        }

                        // loop through the array
                        foreach ($workdaysarray as $thekey => $thevalue) {

                            $NetPay = $employee_array['employees_grosspay'];

                            $tcode = Common::generateTransactionCode($_SESSION['user_id']);

                            // get taxes							  
                            tep_db_query("INSERT INTO " . TABLE_EMPLOYEESALARIES . " (employees_id,employeesalaries_gross,employeesalaries_paydate,employeesalaries_periodending,tcode) VALUES('" . $employee_array['employees_id'] . "','" . $employee_array['employees_grosspay'] . "',NOW()," . common::changeDateFromPageToMySQLFormat(date("Y-m-d", $thevalue)) . ",'" . $tcode . "')");

                            $last_insert_id = tep_db_insert_id();


                            // Awarding of allowances should be put on a different interface
                            $allowances_query = tep_db_query("SELECT allowancesrates_rate,allowancesrates_amount,a.allowances_id FROM " . TABLE_ALLOWANCESRATES . " as ar," . TABLE_ALLOWANCES . " as a WHERE a.allowances_id = ar.allowances_id AND ar.employees_id='" . $employee_array['employees_id'] . "'");

                            while ($allowance_array = tep_db_fetch_array($allowances_query)) {

                                if ($allowance_array['allowancesrates_rate'] > 0) {
                                    $nAllowance = $allowance_array['allowancesrates_rate'] * $employee_array['employees_grosspay'];
                                    $employee_array['employees_grosspay'] = $employee_array['employees_grosspay'] + $nAllowance;
                                } else {
                                    $employee_array['employees_grosspay'] = $employee_array['employees_grosspay'] + $allowance_array['allowancesrates_amount'];
                                    $nAllowance = $allowance_array['allowancesrates_amount'];
                                }

                                if ($nAllowance > 0) {
                                    tep_db_query("INSERT INTO " . TABLE_SALAYADJUSTIMENTS . " (id,salaryadjustiments_amount,salaryadjustiments_type,salaryadjustiments_periodending,tcode,employees_id) VALUES('" . $allowance_array['allowances_id'] . "','" . $nAllowance . "','A'," . common::changeDateFromPageToMySQLFormat(date("Y-m-d", $thevalue)) . ",'" . $tcode . "','" . $employee_array['employees_id'] . "')");
                                }
                            }

                            goto calculate;


                            // get social security
                            /* $social_security_query  = tep_db_query("SELECT socialsecurityrates_rate,chartofaccounts_accountcode,socialsecurityorg_name FROM  ".TABLE_SOCIALSECURITRATES." r ,".TABLE_SOCIALSECURITYORG." o WHERE r.socialsecurityorg_id=o.socialsecurityorg_id AND r.socialsecurityorg_id='".$employee_array['socialsecurityorg_id']."' AND socialsecurityrates_iscurrent='Y'");


                              $grid_lables_lablearray = $grid_lables_lablearray + getlables("462,461");

                              while($social_security_array = tep_db_fetch_array($social_security_query)){

                              if($social_security_array['chartofaccounts_accountcode']==""){
                              tep_db_Rollback();
                              $bsaved = false;
                              echo informationUpdate("fail",$grid_lables_lablearray['462']." ".$social_security_array['socialsecurityorg_name'],"");
                              break 4;
                              }

                              if($social_security_array['socialsecurityrates_rate'] > 0){

                              $nSAmount  = $employee_array['employees_grosspay']*($social_security_array['socialsecurityrates_rate']/100);

                              $NetPay = $NetPay - $nSAmount;

                              tep_db_query("INSERT INTO ".TABLE_SALAYADJUSTIMENTS." (id,salaryadjustiments_amount,salaryadjustiments_type,salaryadjustiments_periodending,tcode,employees_id) VALUES('".$social_security_array['socialsecurityorg_id']."','".($nSAmount*-1)."','S',".common::changeDateFromPageToMySQLFormat(date("Y-m-d",$thevalue)).",'".$tcode."','".$employee_array['employees_id']."')");

                              $sql[] = array($nSAmount,'0','',$social_security_array['chartofaccounts_accountcode'],'','',$curdate,$_SESSION['user_id'],$employee_array['Name'].':'.$social_security_array['socialsecurityorg_name'],'W01','',getCurrencyID($social_security_array['chartofaccounts_accountcode'])); 		// Debit
                              $sql[] = array('0',$nSAmount,'',$accCredit,'','',$curdate,$_SESSION['user_id'],$employee_array['Name'].':'.$social_security_array['socialsecurityorg_name'],'W01','',getCurrencyID($accCredit)); 		// Credit

                              }

                              } */

                            /* // get taxes
                              $taxes_query  = tep_db_query("SELECT t.taxes_id,chartofaccounts_accountcode,taxrates_rate,taxes_name FROM  ".TABLE_EMPLOYEESTAXES." as et,".TABLE_TAXES." as t,".TABLE_CHARGES." tr WHERE tr.taxes_code = CONCAT('T',CAST(t.taxes_id AS Char(5)))  AND et.taxes_id=t.taxes_id  AND et.employees_id='".$employee_array['employees_id']."'");

                              while($taxes_array = tep_db_fetch_array($taxes_query)){

                              if($taxes_array['chartofaccounts_accountcode']==""){
                              tep_db_Rollback();
                              $bsaved = false;

                              echo informationUpdate("fail",$grid_lables_lablearray['461']." ".$taxes_array['taxes_name'],"");
                              break 4;
                              }


                              if($taxes_array['taxrates_rate'] > 0){

                              $nTax  = $employee_array['employees_grosspay']*($taxes_array['taxrates_rate']/100);

                              $NetPay = $NetPay - $nTax;

                              tep_db_query("INSERT INTO ".TABLE_SALAYADJUSTIMENTS." (id,salaryadjustiments_amount,salaryadjustiments_type,salaryadjustiments_periodending,tcode,employees_id) VALUES('".$taxes_array['taxes_id']."','".($nTax*-1)."','T',".common::changeDateFromPageToMySQLFormat(date("Y-m-d",$thevalue)).",'".$tcode."','".$employee_array['employees_id']."')");

                              $sql[] = array($nTax,'0','',$taxes_array['chartofaccounts_accountcode'],'','',$curdate,$_SESSION['user_id'],$employee_array['Name'].':'.$taxes_array['taxes_name'],'W01','',getCurrencyID($accDebit)); 		// Debit
                              $sql[] = array('0',$nTax,'',$accCredit,'','',$curdate,$_SESSION['user_id'],$employee_array['Name'].':'.$taxes_array['taxes_name'],'W01','',getCurrencyID($accDebit)); 		// Credit

                              }

                              } */



                            /* // get deductions/Loans/penalties									
                              $deductions_query  = tep_db_query("SELECT chartofaccounts_accountcode,edm.employeedeductions_id, edm.employeedeductionsamounts_amounts,employeedeductions_name FROM ".TABLE_EMPLOYEEDEDUCTIONAMOUNTS." edm,".TABLE_EMPLOYEEDEDUCTIONS." ed WHERE  ed.employeedeductions_id=edm.employeedeductions_id AND employees_id='".$employee_array['employees_id']."'");

                              while($deductions_array = tep_db_fetch_array($deductions_query)){


                              if($deductions_array['chartofaccounts_accountcode']==""){
                              tep_db_Rollback();
                              $bsaved = false;
                              echo informationUpdate("fail",$grid_lables_lablearray['461']." ".$deductions_array['employeedeductions_name']." not configured.","");
                              break 4;
                              }

                              if($deductions_array['employeedeductionsamounts_amounts']>0){

                              $NetPay = $NetPay - $deductions_array['employeedeductionsamounts_amounts'] ;

                              tep_db_query("INSERT INTO ".TABLE_SALAYADJUSTIMENTS." (id,salaryadjustiments_amount,salaryadjustiments_type,salaryadjustiments_periodending,tcode,employees_id) VALUES('".$deductions_array['employeedeductions_id']."','".$deductions_array['employeedeductionsamounts_amounts']."','D',".common::changeDateFromPageToMySQLFormat(date("Y-m-d",$thevalue)).",'".$tcode."','".$employee_array['employees_id']."')");

                              $sql[] = array($deductions_array['employeedeductionsamounts_amounts'],'0','',$deductions_array['chartofaccounts_accountcode'],'','',$curdate,$_SESSION['user_id'],$employee_array['Name'].':'.$deductions_array['employeedeductions_name'] ,'W01','',getCurrencyID($deductions_array['chartofaccounts_accountcode'])); 		// Debit
                              $sql[] = array('0',$deductions_array['employeedeductionsamounts_amounts'],'',$accCredit,'','',$curdate,$_SESSION['user_id'],$employee_array['Name'].':'.$deductions_array['employeedeductions_name'],'W01','',getCurrencyID($accCredit)); 		// Credit

                              }
                              }
                             */

                            // Salary
                            $sql[] = array($NetPay, '0', '', ACC_SALARIES_WAGES, '', '', $curdate, $_SESSION['user_id'], $employee_array['Name'] . ':' . 'Net Salary', 'W01', '', getCurrencyID(ACC_SALARIES_WAGES));   // Debit
                            $sql[] = array('0', $NetPay, '', $accCredit, '', '', $curdate, $_SESSION['user_id'], $employee_array['Name'] . ':' . 'Net Salary', 'W01', '', getCurrencyID($accCredit));   // Credit

                            PostTransactionsGeneral($sql, $tcode);

                            $nCount = $nCount + 1;
                        }
                    } else {


                        $data = $data . '<tr style="Color:#000000">';
                        $data = $data . '<td>' . $employee_array['employees_id'] . '</td><td nowrap>' . $employee_array['Name'] . '</td><td>' . $employee_array['employees_ssn'] . '</td><td>' . $employee_array['employees_address'] . '</td><td>' . date('F d, Y', strtotime(changeMySQLDateToPageFormat($employee_array['employees_appointmentdate']))) . '</td><td>' . $employee_array['employees_grosspay'] . '</td>';

                        foreach ($workdaysarray as $thekey => $thevalue) {


                            $NetPay = $employee_array['employees_grosspay'];

                            goto calculate;

                            //$tcode  = Common::generateTransactionCode($_SESSION['user_id']);														
                            // Awarding of allowances should be put on a different interface
                            /*  $allowances_query  = tep_db_query("SELECT allowancesrates_rate,allowancesrates_amount,a.allowances_id FROM ".TABLE_ALLOWANCESRATES." as ar,".TABLE_ALLOWANCES." as a WHERE a.allowances_id = ar.allowances_id AND ar.employees_id='".$employee_array['employees_id']."'");

                              while($allowance_array = tep_db_fetch_array($allowances_query)){

                              if($allowance_array['allowancesrates_rate']>0){
                              $nAllowance  = $allowance_array['allowancesrates_rate'] * $employee_array['employees_grosspay'];
                              $employee_array['employees_grosspay'] = $employee_array['employees_grosspay'] +$nAllowance;
                              }else{
                              $employee_array['employees_grosspay']  =$employee_array['employees_grosspay'] + $allowance_array['allowancesrates_amount'];
                              $nAllowance = $allowance_array['allowancesrates_amount'];
                              }

                              if($nAllowance > 0){
                              tep_db_query("INSERT INTO ".TABLE_SALAYADJUSTIMENTS." (id,salaryadjustiments_amount,salaryadjustiments_type,salaryadjustiments_periodending,tcode,employees_id) VALUES('".$allowance_array['allowances_id']."','".$nAllowance."','A',".common::changeDateFromPageToMySQLFormat(date("Y-m-d",$thevalue)).",'".$tcode."','".$employee_array['employees_id']."')");
                              }

                              } */





                            /* // get social security
                              computer_social_security:{

                              $social_security_query  = tep_db_query("SELECT socialsecurityrates_rate,chartofaccounts_accountcode,socialsecurityorg_name FROM  ".TABLE_SOCIALSECURITRATES." r ,".TABLE_SOCIALSECURITYORG." o WHERE r.socialsecurityorg_id=o.socialsecurityorg_id AND r.socialsecurityorg_id='".$employee_array['socialsecurityorg_id']."' AND socialsecurityrates_iscurrent='Y'");

                              $data = $data.'<td>';
                              while($social_security_array = tep_db_fetch_array($social_security_query)){


                              if($social_security_array['socialsecurityrates_rate'] > 0){

                              $nSAmount  = $employee_array['employees_grosspay']*($social_security_array['socialsecurityrates_rate']/100);

                              $NetPay = $NetPay - $nSAmount;

                              $data = $data.$social_security_array['socialsecurityorg_name']." - ".$nSAmount.'<br>';

                              }


                              }
                              $data = $data.'</td>';
                              } */

                            /* // get taxes

                              compute_taxes:{

                              $taxes_query  = tep_db_query("SELECT t.taxes_id,chartofaccounts_accountcode,taxrates_rate,taxes_name FROM  ".TABLE_EMPLOYEESTAXES." as et,".TABLE_TAXES." as t,".TABLE_CHARGES." tr WHERE tr.taxes_code = CONCAT('T',CAST(t.taxes_id AS Char(5)))  AND et.taxes_id=t.taxes_id  AND et.employees_id='".$employee_array['employees_id']."'");

                              $data = $data.'<td>';

                              while($taxes_array = tep_db_fetch_array($taxes_query)){


                              if($taxes_array['taxrates_rate'] > 0){

                              $nTax  = $employee_array['employees_grosspay']*($taxes_array['taxrates_rate']/100);

                              $NetPay = $NetPay - $nTax;

                              $data = $data.$taxes_array['taxes_name']." - ".$nTax.'<br>';

                              }

                              }
                              $data = $data.'</td>';
                              } */

                            /* // get deductions/Loans/penalties							  
                              compute_other_deductions:{
                              $deductions_query  = tep_db_query("SELECT chartofaccounts_accountcode,edm.employeedeductions_id, edm.employeedeductionsamounts_amounts,employeedeductions_name FROM ".TABLE_EMPLOYEEDEDUCTIONAMOUNTS." edm,".TABLE_EMPLOYEEDEDUCTIONS." ed WHERE  ed.employeedeductions_id=edm.employeedeductions_id AND employees_id='".$employee_array['employees_id']."'");

                              $data = $data.'<td>';

                              while($deductions_array = tep_db_fetch_array($deductions_query)){


                              if($deductions_array['employeedeductionsamounts_amounts']>0){

                              $NetPay = $NetPay - $deductions_array['employeedeductionsamounts_amounts'] ;

                              $data = $data.$deductions_array['employeedeductions_name']." - ".$deductions_array['employeedeductionsamounts_amounts'].'<br>';

                              }
                              }

                              $data = $data.'</td>';
                              } */

                            tep_db_Rollback();
                        }

                        calculate: {

                            $grid_lables_lablearray = $grid_lables_lablearray + getlables("772,461,773");

                            switch (SETTING_TAX_ON_IND_INcOME) {

                                case '1': // Tax on Gross Income , Statutory Deductions,Other Deductions
                                    $data = $data . '<td>';
                                    goto compute_taxes;
                                    $data = $data . '</td>';
                                    $data = $data . '<td>';
                                    goto computer_social_security;
                                    $data = $data . '</td>';
                                    $data = $data . '<td>';
                                    goto compute_other_deductions;
                                    $data = $data . '</td>';
                                    break;

                                case '2': // Statutory Deductions on Gross Income ,Taxes,Other Deductions
                                    $data = $data . '<td>';
                                    goto computer_social_security;
                                    $data = $data . '</td>';
                                    $data = $data . '<td>';
                                    goto compute_taxes;
                                    $data = $data . '</td>';
                                    $data = $data . '<td>';
                                    goto compute_other_deductions;
                                    $data = $data . '</td>';

                                    break;

                                case '3'://Other Deductions, Statutory Deductions on Gross Income ,Taxes
                                    $data = $data . '<td>';
                                    goto compute_other_deductions;
                                    $data = $data . '</td>';
                                    $data = $data . '<td>';
                                    goto computer_social_security;
                                    $data = $data . '</td>';
                                    $data = $data . '<td>';
                                    goto compute_taxes;
                                    $data = $data . '</td>';

                                    break;

                                default:
                                    break;
                            }
                        }

                        computer_social_security: {
                            // get social security
                            $social_security_query = tep_db_query("SELECT socialsecurityrates_rate,chartofaccounts_accountcode,socialsecurityorg_name FROM  " . TABLE_SOCIALSECURITRATES . " r ," . TABLE_SOCIALSECURITYORG . " o WHERE r.socialsecurityorg_id=o.socialsecurityorg_id AND r.socialsecurityorg_id='" . $employee_array['socialsecurityorg_id'] . "' AND socialsecurityrates_iscurrent='Y'");




                            while ($social_security_array = tep_db_fetch_array($social_security_query)) {

                                if ($social_security_array['chartofaccounts_accountcode'] == "") {
                                    tep_db_Rollback();
                                    $bsaved = false;
                                    echo informationUpdate("fail", $grid_lables_lablearray['772'] . " " . $social_security_array['socialsecurityorg_name'] . "<br><a href=\"" . DIR_WS_CATALOG . "settings/socialsecurity.php\">" . $grid_lables_lablearray['773'] . "</a>", "");
                                    break 4;
                                }

                                if ($social_security_array['socialsecurityrates_rate'] > 0) {

                                    $nSAmount = $employee_array['employees_grosspay'] * ($social_security_array['socialsecurityrates_rate'] / 100);

                                    $NetPay = $NetPay - $nSAmount;

                                    tep_db_query("INSERT INTO " . TABLE_SALAYADJUSTIMENTS . " (id,salaryadjustiments_amount,salaryadjustiments_type,salaryadjustiments_periodending,tcode,employees_id) VALUES('" . $social_security_array['socialsecurityorg_id'] . "','" . ($nSAmount * -1) . "','S'," . common::changeDateFromPageToMySQLFormat(date("Y-m-d", $thevalue)) . ",'" . $tcode . "','" . $employee_array['employees_id'] . "')");

                                    $sql[] = array($nSAmount, '0', '', $social_security_array['chartofaccounts_accountcode'], '', '', $curdate, $_SESSION['user_id'], $employee_array['Name'] . ':' . $social_security_array['socialsecurityorg_name'], 'W01', '', getCurrencyID($social_security_array['chartofaccounts_accountcode']));   // Debit
                                    $sql[] = array('0', $nSAmount, '', $accCredit, '', '', $curdate, $_SESSION['user_id'], $employee_array['Name'] . ':' . $social_security_array['socialsecurityorg_name'], 'W01', '', getCurrencyID($accCredit));   // Credit
                                }
                            }
                        }
                        // end social security
                        // get deductions/Loans/penalties	 
                        compute_other_deductions: {
                            $deductions_query = tep_db_query("SELECT chartofaccounts_accountcode,edm.employeedeductions_id, edm.employeedeductionsamounts_amounts,employeedeductions_name FROM " . TABLE_EMPLOYEEDEDUCTIONAMOUNTS . " edm," . TABLE_EMPLOYEEDEDUCTIONS . " ed WHERE  ed.employeedeductions_id=edm.employeedeductions_id AND employees_id='" . $employee_array['employees_id'] . "'");

                            while ($deductions_array = tep_db_fetch_array($deductions_query)) {


                                if ($deductions_array['chartofaccounts_accountcode'] == "") {
                                    tep_db_Rollback();
                                    $bsaved = false;
                                    echo informationUpdate("fail", $grid_lables_lablearray['772'] . " " . $deductions_array['employeedeductions_name'] . " <a href=\"" . DIR_WS_CATALOG . "settings/employeedeductions.php\">" . $grid_lables_lablearray['773'] . "</a>", "");
                                    break 4;
                                }

                                if ($deductions_array['employeedeductionsamounts_amounts'] > 0) {

                                    $NetPay = $NetPay - $deductions_array['employeedeductionsamounts_amounts'];

                                    tep_db_query("INSERT INTO " . TABLE_SALAYADJUSTIMENTS . " (id,salaryadjustiments_amount,salaryadjustiments_type,salaryadjustiments_periodending,tcode,employees_id) VALUES('" . $deductions_array['employeedeductions_id'] . "','" . $deductions_array['employeedeductionsamounts_amounts'] . "','D'," . common::changeDateFromPageToMySQLFormat(date("Y-m-d", $thevalue)) . ",'" . $tcode . "','" . $employee_array['employees_id'] . "')");

                                    $sql[] = array($deductions_array['employeedeductionsamounts_amounts'], '0', '', $deductions_array['chartofaccounts_accountcode'], '', '', $curdate, $_SESSION['user_id'], $employee_array['Name'] . ':' . $deductions_array['employeedeductions_name'], 'W01', '', getCurrencyID($deductions_array['chartofaccounts_accountcode']));   // Debit
                                    $sql[] = array('0', $deductions_array['employeedeductionsamounts_amounts'], '', $accCredit, '', '', $curdate, $_SESSION['user_id'], $employee_array['Name'] . ':' . $deductions_array['employeedeductions_name'], 'W01', '', getCurrencyID($accCredit));   // Credit
                                }
                            }
                        }
                        // end deductions/Loans/penalties	



                        compute_taxes: {
                            // get taxes
                            $taxes_query = tep_db_query("SELECT t.taxes_id,chartofaccounts_accountcode,taxrates_rate,taxes_name FROM  " . TABLE_EMPLOYEESTAXES . " as et," . TABLE_TAXES . " as t," . TABLE_CHARGES . " tr WHERE tr.taxes_id = t.taxes_id  AND et.taxes_id=t.taxes_id  AND et.employees_id='" . $employee_array['employees_id'] . "'");

                            while ($taxes_array = tep_db_fetch_array($taxes_query)) {

                                if ($taxes_array['chartofaccounts_accountcode'] == "") {
                                    tep_db_Rollback();
                                    $bsaved = false;

                                    echo informationUpdate("fail", $grid_lables_lablearray['772'] . " " . $taxes_array['taxes_name'] . " <a href=\"" . DIR_WS_CATALOG . "/settings/managetaxes.php\">" . $grid_lables_lablearray['773'] . "</a>", "");
                                    break 4;
                                }


                                if ($taxes_array['taxrates_rate'] > 0) {

                                    $nTax = $employee_array['employees_grosspay'] * ($taxes_array['taxrates_rate'] / 100);

                                    $NetPay = $NetPay - $nTax;

                                    tep_db_query("INSERT INTO " . TABLE_SALAYADJUSTIMENTS . " (id,salaryadjustiments_amount,salaryadjustiments_type,salaryadjustiments_periodending,tcode,employees_id) VALUES('" . $taxes_array['taxes_id'] . "','" . ($nTax * -1) . "','T'," . common::changeDateFromPageToMySQLFormat(date("Y-m-d", $thevalue)) . ",'" . $tcode . "','" . $employee_array['employees_id'] . "')");

                                    $sql[] = array($nTax, '0', '', $taxes_array['chartofaccounts_accountcode'], '', '', $curdate, $_SESSION['user_id'], $employee_array['Name'] . ':' . $taxes_array['taxes_name'], 'W01', '', getCurrencyID($accDebit));   // Debit
                                    $sql[] = array('0', $nTax, '', $accCredit, '', '', $curdate, $_SESSION['user_id'], $employee_array['Name'] . ':' . $taxes_array['taxes_name'], 'W01', '', getCurrencyID($accDebit));   // Credit

                                    $data = $data . $taxes_array['taxes_name'] . " - " . $nTax . '<br>';
                                }
                            }
                        }


                        $lables_array1 = getlables("6,7,8,9,11,12,13,14,15,16,18");
                        $lables_array = $lables_array1 + $grid_lables_lablearray;

                        $header_data = $header_data . '<table border="0" cellpadding="1" cellspacing="0" width="100%">';
                        $header_data = $header_data . '<tr><td colspan="2"><font size="18" Color="#3B4E87">' . $lablearray['6'] . '</font><br>' . $lablearray['7'] . ' ' . date('F d, Y', strtotime($_POST['general_date'])) . '<br><br></td></tr>';
                        $header_data = $header_data . '</table>';
                        $header_data = $header_data . '<table border="0" cellspacing="0" cellpadding="2" width="100%" style="Color:#FFFFFF">';
                        $header_data = $header_data . '<tr bgcolor="#3B4E87">';
                        $header_data = $header_data . '<td valign="bottom">' . $lablearray['8'] . '</td><td bgcolor="#3B4E87" valign="bottom">' . $lablearray['9'] . '</td><td valign="bottom">' . $lablearray['10'] . '</td><td valign="bottom">' . $lablearray['11'] . '</td><td valign="bottom">' . $lablearray['12'] . '</td><td valign="bottom">' . $lablearray['13'] . '</td><td>' . $lablearray['14'] . '</td><td>' . $lablearray['15'] . '</td><td>' . $lablearray['16'] . '</td><td>' . $lablearray['17'] . '</td>';
                        $header_data = $header_data . '</tr>';

                        $data = $header_data . $data . '<td>' . $NetPay . '</td></tr></table>';

                        $_SESSION['reportname'] = "EPI";
                        $_SESSION['payrolldata'] = $data;
                        //  echo informationUpdate("success","Employee benefits have been successfully calculated.","","");	
                        $lablesarray = getlables("769");
                        echo "document.getElementById('topsublinks').innerHTML='<a href=\"#\" onClick=\"openInNewWindow()\">" . $lablesarray['769'] . " " . $_POST['date_to'] . " </a>';";
                        break 3;
                    }
                }

                if ($nCount > 0 && $bsaved == true) {
                    tep_db_Commit();
                    $lablesarray = getlables("767");
                    echo informationUpdate("success", $lablesarray['767'], "");
                    break 2;
                } else {
                    tep_db_Rollback();
                    $lablesarray = getlables("768");
                    echo informationUpdate("fail", $lablesarray['768'], "");

                    break 2;
                }


                break;

            case 'search':
                $query = "SELECT employees_id,CONCAT(employees_firstname,' ',employees_lastname) as Name,employees_grosspay,positions_name,departments_name,employees_status FROM " . TABLE_EMPLOYEES . " AS e INNER JOIN " . TABLE_POSITIONS . " AS p ON p.positions_id=e.positions_id  INNER JOIN " . TABLE_DEPARTMENTS . " AS d ON d.departments_id=e.departments_id AND (employees_firstname LIKE '%" . tep_db_prepare_input($_POST["searchterm"]) . "%' OR employees_lastname LIKE '%" . tep_db_prepare_input($_POST["searchterm"]) . "%'  OR positions_name  LIKE '%" . tep_db_prepare_input($_POST["searchterm"]) . "%')";
                break;

            case 'edit':

                break;
            case 'delete':
                echo informationUpdate("", "Information has been successfully updated.", "showResult('frmid=frmemployees','txtHint')");

                break;

            case 'update':
                echo informationUpdate("", "Information has been successfully updated.", "showResult('frmid=frmemployees','txtHint')");
                break;

            default:
                //  we display all employees
                $newgrid->numberof_rows_on_page = 10000;
                $query = "SELECT employees_id,CONCAT(employees_firstname,' ',employees_lastname) as Name,employees_grosspay,positions_name,departments_name,employees_status FROM " . TABLE_EMPLOYEES . " AS e LEFT JOIN " . TABLE_POSITIONS . " AS p ON p.positions_id=e.positions_id  LEFT JOIN " . TABLE_DEPARTMENTS . " AS d ON d.departments_id=e.departments_id";
                break;
        }


        $_SESSION['reportname'] = "List of Employers";
        $_SESSION['reporttitle'] = "List of Employers";
        $_SESSION['downloadlist'] = $query;

        $lables_array1 = getlables("9,13,37,68,33,32,34,36,39,35,42,38,69");
        $lables_array = $lables_array1 + $grid_lables_lablearray;

        $fieldlist = array('Name', 'employees_grosspay', 'positions_name', 'employees_status');
        $keyfield = 'employees_id';
        $gridcolumnnames = array($lablearray['9'], $lablearray['13'], $lablearray['37'], $lablearray['68']);
        break;

    case 'frmtaxes':

        switch ($_POST['action']) {

            case 'view':
                $query = "SELECT * FROM " . TABLE_TAXES . " WHERE taxes_id='" . $_POST['taxes_id'] . "'";
                break;


            case 'delete':
                tep_db_BeginTransaction();
                if (tep_db_num_rows(tep_db_query("SELECT taxes_id FROM " . TABLE_CHARGES . " WHERE taxes_id='" . $_POST['id'] . "'")) > 0) {
                    tep_db_Rollback();
                    $lablearray = getlables("754");
                    echo informationUpdate("success", $lablearray['754'], "");
                    break;
                }
                tep_db_query("DELETE FROM " . TABLE_CHARGES . " WHERE taxes_id='" . $_POST['id'] . "'");
                tep_db_Commit();
                $lablearray = getlables("34");
                echo informationUpdate("success", $lablearray['34'], 'showResult("frmid=frmtaxes","txtHint")');
                break;

            case 'add':

                tep_db_query("INSERT INTO " . TABLE_TAXES . " (taxes_name,chartofaccounts_accountcode) VALUES ('" . $_POST['taxes_name'] . "','" . $_POST['chartofaccounts_accountcode'] . "')");
                echo informationUpdate("", "Information has been successfully added", "showResult('frmid=frmtaxes','txtHint')");
                break;

            case 'update':
                tep_db_query("UPDATE " . TABLE_TAXES . " SET taxes_name ='" . $_POST['taxes_name'] . "',chartofaccounts_accountcode='" . $_POST['chartofaccounts_accountcode'] . "' WHERE taxes_id = '" . $_POST['taxes_id'] . "'");
                $lablearray = getlables("34");
                echo informationUpdate("success", $lablearray['34'], "");
                break;

            case 'edit':
                $results_query = tep_db_query("SELECT * FROM " . TABLE_TAXES . " WHERE taxes_id ='" . $_POST['id'] . "'");
                $results = tep_db_fetch_array($results_query);
                echo "formObj.taxes_id.value = '" . $results['taxes_id'] . "';\n";
                echo "formObj.taxes_name.value = '" . $results['taxes_name'] . "';\n";
                echo "formObj.chartofaccounts_accountcode.value = '" . $results['chartofaccounts_accountcode'] . "';\n";

                echo "formObj.action.value = 'update';\n";
                break;

            default:


                $query = "SELECT * FROM " . TABLE_TAXES;
                break;
        }

        $_SESSION['reportname'] = "Taxes";
        $_SESSION['reporttitle'] = "Taxes";
        $_SESSION['downloadlist'] = $query;
        $fieldlist = array('taxes_name', 'chartofaccounts_accountcode');
        $keyfield = 'taxes_id';
        $gridcolumnnames = array('Tax', 'GL Account');

        break;

  
    case 'frmemployees':

        switch ($_POST['action']) {

            case 'upload':

                //print_r($_FILES);
                //exit();						
                foreach ($_FILES as $key => $file) {

                    if ($key == 'contract_file') {
                        $uploaddir = 'uploads/contracts/';
                    }

                    if ($key == 'photo_file') {
                        $uploaddir = 'uploads/empphotos/';
                    }

                    if (move_uploaded_file($file['tmp_name'], $uploaddir . basename($file['name']))) {
                        $files[] = $uploaddir . $file['name'];
                    } else {
                        $error = true;
                    }
                }

                $data = array('success' => 'Form was submitted');
                echo json_encode($data);

                break;

            case 'view':
                $query = "SELECT * FROM " . TABLE_EMPLOYEES . " WHERE employees_id='" . $_POST['employees_id'] . "'";
                break;

            case 'add':
                tep_db_query("START TRANSACTION");

                tep_db_query("INSERT INTO " . TABLE_EMPLOYEES . " (employees_firstname,employees_lastname,employees_othernames,employees_qualifications,employmenttype_id,employees_appointmentdate,employees_ssn,socialsecurityorg_id,branch_code,employees_payperiod,employees_status,departments_id,employees_casualleave,employees_paidleave,employees_sickleave,employees_otherleave,employees_emailaddress,employees_address,employees_telephone,employees_image,employees_grosspay,employees_commencementdate,employees_gender,employees_maritalstatus) VALUES ('" . $_POST['employees_firstname'] . "','" . $_POST['employees_lastname'] . "','" . $_POST['employees_othernames'] . "','" . $_POST['employees_qualifications'] . "','" . $_POST['employmenttype_id'] . "'," . common::changeDateFromPageToMySQLFormat($_POST['employees_appointmentdate']) . ",'" . $_POST['employees_ssn'] . "','" . $_POST['socialsecurityorg_id'] . "','" . BRANCHCODE . "','" . $_POST['employees_payperiod'] . "','" . $_POST['employees_status'] . "','" . $_POST['departments_id'] . "','" . $_POST['employees_casualleave'] . "','" . $_POST['employees_paidleave'] . "','" . $_POST['employees_sickleave'] . "','" . $_POST['employees_otherleave'] . "','" . $_POST['employees_emailaddress'] . "','" . $_POST['employees_address'] . "','" . $_POST['employees_telephone'] . "','" . $employees_image . "','" . $_POST['employees_grosspay'] . "'," . common::changeDateFromPageToMySQLFormat($_POST['employees_commencementdate']) . ",'" . $_POST['employees_gender'] . "','" . $_POST['employees_maritalstatus'] . "')");

                $employees_id = tep_db_insert_id();

                if ($_POST['employees_grosspay'] > 0) {
                    tep_db_query("INSERT INTO " . TABLE_EMPLOYEESALARIES . " (employeesalaries_gross,employeesalaries_deductions,employeesalaries_taxamount,employees_id) VALUES('" . $_POST['employees_grosspay'] . "','0','0','" . $employees_id . "')");
                }
                // payment deductions
                tep_db_query("DELETE FROM " . TABLE_EMPLOYEEDEDUCTIONAMOUNTS . " WHERE employees_id='" . $employees_id . "'");

                $deductions_query = tep_db_query("SELECT employeedeductions_id FROM " . TABLE_EMPLOYEEDEDUCTIONS);

                while ($values_array = tep_db_fetch_array($deductions_query)) {

                    if ($_POST['D' . $values_array['employeedeductions_id']] != "") {
                        tep_db_query("INSERT INTO " . TABLE_EMPLOYEEDEDUCTIONAMOUNTS . " (employees_id,employeedeductions_id,employeedeductionsamounts_amounts) VALUES('" . $employees_id . "','" . $values_array['employeedeductions_id'] . "','" . $_POST['D' . $values_array['employeedeductions_id']] . "')");
                    }
                }

                // taxes
                tep_db_query("DELETE FROM " . TABLE_EMPLOYEESTAXES . " WHERE employees_id='" . $employees_id . "'");

                $taxes_query = tep_db_query("SELECT taxes_id FROM " . TABLE_TAXES);

                while ($taxes_array = tep_db_fetch_array($taxes_query)) {

                    if ($_POST['T' . $taxes_array['taxes_id']] != "") {

                        tep_db_query("INSERT INTO " . TABLE_EMPLOYEESTAXES . " (employees_id,taxes_id) VALUES('" . $employees_id . "','" . $taxes_array['taxes_id'] . "')");
                    }
                }


                //allowances
                tep_db_query("DELETE FROM " . TABLE_ALLOWANCESRATES . " WHERE employees_id='" . $employees_id . "'");

                $allow_query = tep_db_query("SELECT allowances_id FROM " . TABLE_ALLOWANCES);

                while ($allow_array = tep_db_fetch_array($allow_query)) {

                    if ($_POST['TA' . $allow_array['allowances_id']] != "" || $_POST['TB' . $allow_array['allowances_id']] != "") {

                        if ($_POST['D' . $values_array['employeedeductions_id']] == "") {
                            $_POST['D' . $values_array['employeedeductions_id']] = 0;
                        }

                        if ($_POST['TB' . $allow_array['allowances_id']] == "") {
                            $_POST['TB' . $allow_array['allowances_id']] = 0;
                        }

                        tep_db_query("INSERT INTO " . TABLE_ALLOWANCESRATES . " (allowances_id,allowancesrates_rate,allowancesrates_amount,employees_id) VALUES('" . $allow_array['allowances_id'] . "','" . $_POST['TA' . $allow_array['allowances_id']] . "','" . $_POST['TB' . $allow_array['allowances_id']] . "','" . $employees_id . "')");
                    }
                }

                tep_db_query("COMMIT");

                echo informationUpdate("", "Employee has been successfully registered.", "showResult('frmid=frmemployees','txtHint')");
                break;

            case 'edit':
                $results_query = tep_db_query("SELECT * FROM " . TABLE_EMPLOYEES . " WHERE employees_id ='" . $_POST['id'] . "'");

                $results = tep_db_fetch_array($results_query);

                echo "formObj.employees_id.value = '" . $results['employees_id'] . "';\n";
                echo "formObj.employees_firstname.value = '" . $results['employees_firstname'] . "';\n";
                echo "formObj.employees_lastname.value = '" . $results['employees_lastname'] . "';\n";
                echo "formObj.employees_qualifications.value = '" . $results['employees_qualifications'] . "';\n";
                echo "formObj.employees_othernames.value = '" . $results['employees_othernames'] . "';\n";
                echo "formObj.employees_appointmentdate.value = '" . changeMySQLDateToPageFormat($results['employees_appointmentdate']) . "';\n";
                echo "formObj.employees_commencementdate.value = '" . changeMySQLDateToPageFormat($results['employees_commencementdate']) . "';\n";
                echo "SelectItemInList(\"employmenttype_id\",\"" . $results["employmenttype_id"] . "\");\n";
                echo "SelectItemInList(\"positions_id\",\"" . $results["positions_id"] . "\");\n";
                echo "SelectItemInList(\"socialsecurityorg_id\",\"" . $results["socialsecurityorg_id"] . "\");\n";
                echo "SelectItemInList(\"departments_id\",\"" . $results["departments_id"] . "\");\n";
                echo "SelectItemInList(\"employees_maritalstatus\",\"" . $results["employees_maritalstatus"] . "\");\n";
                echo "SelectItemInList(\"employees_gender\",\"" . $results["employees_gender"] . "\");\n";

                echo "formObj.employees_ssn.value = '" . $results['employees_ssn'] . "';\n";
                echo "formObj.employees_grosspay.value = '" . $results['employees_grosspay'] . "';\n";
                echo "formObj.employees_casualleave.value = '" . $results['employees_casualleave'] . "';\n";
                echo "formObj.employees_paidleave.value = '" . $results['employees_paidleave'] . "';\n";
                echo "formObj.employees_sickleave.value = '" . $results['employees_sickleave'] . "';\n";
                echo "formObj.employees_otherleave.value = '" . $results['employees_otherleave'] . "';\n";
                echo "formObj.employees_telephone.value = '" . $results['employees_telephone'] . "';\n";
                echo "formObj.employees_address.value = '" . $results['employees_address'] . "';\n";
                echo "formObj.employees_emailaddress.value = '" . $results['employees_emailaddress'] . "';\n";

                if ($results['employees_payperiod'] != "") {
                    echo "formObj." . $results['employees_payperiod'] . ".checked = true;\n";
                }

                //echo "formObj.socialsecurityrates_rate.value = '".$results['socialsecurityrates_rate']."';\n";
                echo "SelectItemInList(\"socialsecurityorg_id\",\"" . $results["socialsecurityorg_id"] . "\");\n";

                if ($results['employees_status'] != "") {
                    echo "formObj." . $results['employees_status'] . ".checked = true;\n";
                }

                echo "formObj.action.value = 'update';\n";

                // payment deductions				
                $deductions_query = tep_db_query("SELECT * FROM " . TABLE_EMPLOYEEDEDUCTIONAMOUNTS . " WHERE employees_id='" . $results["employees_id"] . "'");

                while ($values_array = tep_db_fetch_array($deductions_query)) {

                    echo "formObj.D" . $values_array["employeedeductions_id"] . ".value = '" . $values_array["employeedeductionsamounts_amounts"] . "';\n";
                }

                // taxes

                $taxes_query = tep_db_query("SELECT CONCAT('T',taxes_id) As taxes_id FROM " . TABLE_EMPLOYEESTAXES . " WHERE employees_id='" . $results["employees_id"] . "'");

                while ($taxes_array = tep_db_fetch_array($taxes_query)) {

                    echo "document.getElementById('" . $taxes_array['taxes_id'] . "').checked = true;\n";
                }


                //allowances
                $allow_query = tep_db_query("SELECT * FROM " . TABLE_ALLOWANCESRATES . " WHERE employees_id='" . $results["employees_id"] . "'");

                while ($allow_array = tep_db_fetch_array($allow_query)) {

                    if ($allow_array['allowancesrates_rate'] != "") {

                        echo "formObj.A" . $allow_array['allowances_id'] . ".checked = True;\n";
                        echo "formObj.B" . $allow_array['allowances_id'] . ".disabled =False;\n";
                    }

                    if ($allow_array['allowancesrates_amount'] != "") {
                        echo "formObj.A" . $allow_array['allowances_id'] . ".checked = False;\n";
                        echo "formObj.B" . $allow_array['allowances_id'] . ".disabled =True;\n";
                    }

                    echo "formObj.TA" . $allow_array['allowances_id'] . ".value = '" . $allow_array['allowancesrates_rate'] . "';\n";
                    echo "formObj.TB" . $allow_array['allowances_id'] . ".value = '" . $allow_array['allowancesrates_amount'] . "';\n";
                }

                break;

            case 'delete':

                tep_db_query("START TRANSACTION");
                tep_db_query("DELETE FROM " . TABLE_EMPLOYEES . " WHERE employees_id='" . $_POST['id'] . "'");
                tep_db_query("DELETE FROM " . TABLE_EMPLOYEEDEDUCTIONAMOUNTS . " WHERE employees_id='" . $_POST['id'] . "'");
                tep_db_query("DELETE FROM " . TABLE_EMPLOYEESTAXES . " WHERE employees_id='" . $_POST['id'] . "'");
                tep_db_query("DELETE FROM " . TABLE_ALLOWANCESRATES . " WHERE employees_id='" . $_POST['id'] . "'");
                tep_db_query("INSERT INTO " . TABLE_EMPLOYEESALARIES . " WHERE employees_id='" . $_POST['id'] . "'");
                tep_db_query("COMMIT");

                echo informationUpdate("", "All information relating to this employee has been successfully updated.", "showResult('frmid=frmemployees','txtHint')");

                break;

            case 'update':
                tep_db_query("START TRANSACTION");

                tep_db_query("UPDATE " . TABLE_EMPLOYEES . " SET employees_firstname='" . $_POST['employees_firstname'] . "',employees_lastname='" . $_POST['employees_lastname'] . "',employees_othernames='" . $_POST['employees_othernames'] . "',employees_qualifications='" . $_POST['employees_qualifications'] . "',employmenttype_id='" . $_POST['employmenttype_id'] . "',employees_appointmentdate=" . common::changeDateFromPageToMySQLFormat($_POST['employees_appointmentdate']) . ",employees_ssn='" . $_POST['employees_ssn'] . "',socialsecurityorg_id='" . $_POST['socialsecurityorg_id'] . "',branch_code='" . BRANCHCODE . "',employees_payperiod='" . $_POST['employees_payperiod'] . "',employees_status='" . $_POST['employees_status'] . "',departments_id='" . $_POST['departments_id'] . "',employees_casualleave='" . $_POST['employees_casualleave'] . "',employees_paidleave='" . $_POST['employees_paidleave'] . "',employees_sickleave='" . $_POST['employees_sickleave'] . "',employees_otherleave='" . $_POST['employees_otherleave'] . "',employees_emailaddress='" . $_POST['employees_emailaddress'] . "',employees_address='" . $_POST['employees_address'] . "',employees_telephone='" . $_POST['employees_telephone'] . "',employees_image='" . $_POST['employees_image'] . "',employees_grosspay='" . $_POST['employees_grosspay'] . "',employees_maritalstatus='" . $_POST['employees_maritalstatus'] . "',employees_gender='" . $_POST['employees_gender'] . "' WHERE employees_id='" . $_POST['employees_id'] . "'");


                // payment deductions
                tep_db_query("DELETE FROM " . TABLE_EMPLOYEEDEDUCTIONAMOUNTS . " WHERE employees_id='" . $_POST['employees_id'] . "'");

                $deductions_query = tep_db_query("SELECT employeedeductions_id FROM " . TABLE_EMPLOYEEDEDUCTIONS);

                while ($values_array = tep_db_fetch_array($deductions_query)) {

                    if ($_POST['D' . $values_array['employeedeductions_id']] != "") {

                        tep_db_query("INSERT INTO " . TABLE_EMPLOYEEDEDUCTIONAMOUNTS . " (employees_id,employeedeductions_id,employeedeductionsamounts_amounts) VALUES('" . $_POST['employees_id'] . "','" . $values_array['employeedeductions_id'] . "','" . $_POST['D' . $values_array['employeedeductions_id']] . "')");
                    }
                }

                // taxes
                tep_db_query("DELETE FROM " . TABLE_EMPLOYEESTAXES . " WHERE employees_id='" . $_POST['employees_id'] . "'");

                $taxes_query = tep_db_query("SELECT taxes_id FROM " . TABLE_TAXES);

                while ($taxes_array = tep_db_fetch_array($taxes_query)) {

                    if ($_POST['T' . $taxes_array['taxes_id']] != "") {

                        tep_db_query("INSERT INTO " . TABLE_EMPLOYEESTAXES . " (employees_id,taxes_id) VALUES('" . $_POST['employees_id'] . "','" . $taxes_array['taxes_id'] . "')");
                    }
                }

                //allowances
                tep_db_query("DELETE FROM " . TABLE_ALLOWANCESRATES . " WHERE employees_id='" . $_POST['employees_id'] . "'");

                $allow_query = tep_db_query("SELECT allowances_id FROM " . TABLE_ALLOWANCES);

                while ($allow_array = tep_db_fetch_array($allow_query)) {

                    if ($_POST['TA' . $allow_array['allowances_id']] != "" || $_POST['TB' . $allow_array['allowances_id']] != "") {

                        if ($_POST['D' . $values_array['employeedeductions_id']] == "") {
                            $_POST['D' . $values_array['employeedeductions_id']] = 0;
                        }

                        if ($_POST['TB' . $allow_array['allowances_id']] == "") {
                            $_POST['TB' . $allow_array['allowances_id']] = 0;
                        }

                        tep_db_query("INSERT INTO " . TABLE_ALLOWANCESRATES . " (allowances_id,allowancesrates_rate,allowancesrates_amount,employees_id) VALUES('" . $allow_array['allowances_id'] . "','" . $_POST['TA' . $allow_array['allowances_id']] . "','" . $_POST['TB' . $allow_array['allowances_id']] . "','" . $_POST['employees_id'] . "')");
                    }
                }

                tep_db_query("COMMIT");
                echo informationUpdate("", "Information has been successfully updated.", "showResult('frmid=frmemployees','txtHint')");
                break;

            default:
                $query = "SELECT employees_id,CONCAT(employees_firstname,' ',employees_lastname) as Name,DATE_FORMAT(employees_appointmentdate,'%d/%m/%Y') As employees_appointmentdate,employees_ssn,employees_emailaddress,positions_name,employees_telephone,IF(employees_status='AC','Activive',IF(employees_status='OL','On Leave',IF(employees_status='TE','Terminated',IF(employees_status='LE','Left','Unknown')))) AS employees_status FROM " . TABLE_EMPLOYEES . " AS e LEFT JOIN " . TABLE_POSITIONS . " AS p ON p.positions_id=e.positions_id ";
                break;
        }


        $_SESSION['reportname'] = "List of Employers";
        $_SESSION['reporttitle'] = "List of Employers";
        $_SESSION['downloadlist'] = $query;
        $fieldlist = array('Name', 'Appointment Date', 'employees_appointmentdate', 'positions_name', 'employees_telephone', 'employees_emailaddress', 'employees_status');
        $keyfield = 'employees_id';
        $gridcolumnnames = array('Name', 'Appointment Date', 'Social Security No.', 'Position', 'Telephone', 'Email Address', 'Status');
        break;

    case 'frmsocialsecurityrates':
        switch ($_POST['action']) {

            case 'view':
                $query = "SELECT * FROM " . TABLE_SOCIALSECURITRATES . " WHERE socialsecurityorg_id='" . $_POST['socialsecurityorg_id'] . "'";
                break;

            case 'add':

                tep_db_query("INSERT INTO " . TABLE_SOCIALSECURITRATES . " (socialsecurityorg_id,socialsecurityrates_rate,socialsecurityrates_iscurrent) VALUES ('" . $_POST['socialsecurityorg_id'] . "','" . $_POST['socialsecurityrates_rate'] . "','" . $_POST['socialsecurityrates_iscurrent'] . "')");
                echo informationUpdate("", "Information has been successfully added", "showResult('frmid=frmsocialsecurityrates','txtHint')");
                break;

            case 'edit':
                $results_query = tep_db_query("SELECT * FROM " . TABLE_SOCIALSECURITRATES . " WHERE socialsecurityrates_id ='" . $_POST['id'] . "'");

                $results = tep_db_fetch_array($results_query);
                echo "formObj.socialsecurityrates_id.value = '" . $results['socialsecurityrates_id'] . "';\n";
                echo "formObj.socialsecurityrates_rate.value = '" . $results['socialsecurityrates_rate'] . "';\n";
                echo "SelectItemInList(\"socialsecurityorg_id\",\"" . $results["socialsecurityorg_id"] . "\");\n";
                echo "formObj.action.value = 'update';\n";
                if ($results['socialsecurityrates_iscurrent'] == 'Y') {
                    echo "formObj.socialsecurityrates_iscurrent.checked = true;\n";
                } else {
                    echo "formObj.socialsecurityrates_iscurrent.checked = false;\n";
                }

                break;

            case 'delete':
                $results_query = tep_db_query("SELECT * FROM " . TABLE_SOCIALSECURITRATES . " WHERE socialsecurityorg_id='" . $_POST['id'] . "'");

                if (tep_db_num_rows($results_query) > 0) {
                    echo informationUpdate("fail", "Oganisation has security rates set. Please delete rates first.", "");
                    return;
                } else {
                    tep_db_query("DELETE FROM " . TABLE_SOCIALSECURITRATES . " WHERE socialsecurityorg_id ='" . $_POST['id'] . "'");
                    echo informationUpdate("", "The organisation has been successfully deleted", "showResult('frmid=frmsocialsecurityrates','txtHint')");
                }
                break;

            case 'update':
                tep_db_query("UPDATE " . TABLE_SOCIALSECURITRATES . " SET socialsecurityrates_rate ='" . $_POST['socialsecurityrates_rate'] . "',socialsecurityrates_iscurrent='" . $_POST['socialsecurityrates_iscurrent'] . "' WHERE socialsecurityrates_id = '" . $_POST['socialsecurityrates_id'] . "'");
                echo informationUpdate("", "Information has been successfully updated.", "showResult('frmid=frmsocialsecurityrates','txtHint')");
                break;

            default:
                $query = "SELECT socialsecurityrates_id,socialsecurityorg_name,socialsecurityrates_rate,socialsecurityrates_iscurrent FROM " . TABLE_SOCIALSECURITRATES . ' as sr ,' . TABLE_SOCIALSECURITYORG . ' AS so WHERE so.socialsecurityorg_id=sr.socialsecurityorg_id';
                break;
        }


        $_SESSION['reportname'] = "Social Security  rates";
        $_SESSION['reporttitle'] = "Social Security rates";
        $_SESSION['downloadlist'] = $query;
        $fieldlist = array('socialsecurityorg_name', 'socialsecurityrates_rate', 'socialsecurityrates_iscurrent');
        $keyfield = 'socialsecurityrates_id';
        $gridcolumnnames = array('Organisation', 'Rate', 'Status');

        break;
    case 'frmsocialsecurity':

        switch ($_POST['action']) {

            case 'add':
                $results_query = tep_db_query("SELECT * FROM " . TABLE_SOCIALSECURITYORG . " WHERE socialsecurityorg_name='" . $_POST['socialsecurityorg_name'] . "'");

                if (tep_db_num_rows($results_query) > 0) {
                    echo informationUpdate("fail", "Information is already registered ni the system.", "");
                    return;
                }
                tep_db_query("INSERT INTO " . TABLE_SOCIALSECURITYORG . " (socialsecurityorg_name,chartofaccounts_accountcode) VALUES ('" . $_POST['socialsecurityorg_name'] . "','" . $_POST['chartofaccounts_accountcode'] . "')");
                echo informationUpdate("", "Organisation has been successfully added", "showResult('frmid=frmsocialsecurity','txtHint')");
                break;

            case 'edit':
                $results_query = tep_db_query("SELECT * FROM " . TABLE_SOCIALSECURITYORG . " WHERE socialsecurityorg_id ='" . $_POST['id'] . "'");
                $results = tep_db_fetch_array($results_query);
                echo "formObj.socialsecurityorg_id.value = '" . $results['socialsecurityorg_id'] . "';\n";
                echo "SelectItemInList(\"chartofaccounts_accountcode\",\"" . $results["chartofaccounts_accountcode"] . "\");\n";
                echo "formObj.action.value = 'update';\n";
                echo "formObj.socialsecurityorg_name.value = '" . $results['socialsecurityorg_name'] . "';\n";
                break;

            case 'delete':
                $results_query = tep_db_query("SELECT * FROM " . TABLE_SOCIALSECURITRATES . " WHERE socialsecurityorg_id='" . $_POST['id'] . "'");

                if (tep_db_num_rows($results_query) > 0) {
                    echo informationUpdate("fail", "Oganisation has security rates set. Please delete rates first.", "");
                    return;
                } else {
                    tep_db_query("DELETE FROM " . TABLE_SOCIALSECURITYORG . " WHERE socialsecurityorg_id ='" . $_POST['id'] . "'");
                    echo informationUpdate("", "Organisation has been successfully deleted", "showResult('frmid=frmsocialsecurity','txtHint')");
                }
                break;

            case 'update':
                tep_db_query("UPDATE " . TABLE_SOCIALSECURITYORG . " SET socialsecurityorg_name ='" . $_POST['socialsecurityorg_name'] . "',chartofaccounts_accountcode='" . $_POST['chartofaccounts_accountcode'] . "' WHERE socialsecurityorg_id = '" . $_POST['socialsecurityorg_id'] . "'");
                echo informationUpdate("", "information has been successfully updated.", "showResult('frmid=frmsocialsecurity','txtHint')");
                break;

            default:
                $query = "Select * FROM " . TABLE_SOCIALSECURITYORG . " where socialsecurityorg_name LIKE '%" . tep_db_prepare_input($_POST["searchterm"]) . "%'";
                break;
                break;
        }

        $_SESSION['reportname'] = "Social Security Organisations";
        $_SESSION['reporttitle'] = "Social Security Organisations";
        $_SESSION['downloadlist'] = $query;
        $fieldlist = array('socialsecurityorg_name', 'chartofaccounts_accountcode');
        $keyfield = 'socialsecurityorg_id';
        $gridcolumnnames = array('Organisation name', 'GL Account');

        break;

    case 'frmconfigurecharges':

        switch ($_POST['action']) {

            case 'add':

                tep_db_BeginTransaction();
                $operatorbranches_query = tep_db_query("SELECT branch_code FROM " . TABLE_OPERATORBRANCHES . " WHERE bankbranches_id='" . tep_db_prepare_input($_POST['bankbranches_id']) . "'");

                $results = tep_db_fetch_array($operatorbranches_query);
                tep_db_query("UPDATE " . TABLE_CHARGERATES . " SET chargesrates_activated ='N'  WHERE branch_code='" . tep_db_prepare_input($_POST['branch_code']) . "' AND charges_id='" . tep_db_prepare_input($_POST['charges_id']) . "'");
                //echo "UPDATE " .TABLE_CHARGERATES." SET chargesrates_activated ='N'  WHERE branch_code='".$results['branch_code']."' AND charges_id='".tep_db_prepare_input($_POST['charges_id'])."'";

                if ($_POST['chargesrates_activated'] == 'Y') {

                    $chargesrates_activated = 'Y';
                } else {
                    $chargesrates_activated = 'N';
                }
                // get the branch operator code
                //echo "INSERT INTO ".TABLE_CHARGERATES." (charges_id,chargesrates_from,chargesrates_to,chargesrates_per,chargesrates_amount,branch_code,chargesrates_datecreated,chargesrates_activated,chargesrates_vat,chargesrates_fixed) VALUES ('".tep_db_prepare_input($_POST['charges_id'])."','".tep_db_prepare_input($_POST['chargesrates_from'])."','".tep_db_prepare_input($_POST['chargesrates_to'])."','".tep_db_prepare_input($_POST['chargesrates_per'])."','".tep_db_prepare_input($_POST['chargesrates_amount'])."','".tep_db_prepare_input($_POST['branch_code'])."',NOW(),'".tep_db_prepare_input($_POST['chargesrates_activated'])."','".tep_db_prepare_input($_POST['chargesrates_vat'])."','".tep_db_prepare_input($_POST['chargesrates_fixed'])."')";
                tep_db_query("INSERT INTO " . TABLE_CHARGERATES . " (charges_id,chargesrates_from,chargesrates_to,chargesrates_per,chargesrates_amount,branch_code,chargesrates_datecreated,chargesrates_activated,chargesrates_vat,chargesrates_fixed,licence_build,chargesrates_stage) VALUES ('" . tep_db_prepare_input($_POST['charges_id']) . "','" . tep_db_prepare_input($_POST['chargesrates_from']) . "','" . tep_db_prepare_input($_POST['chargesrates_to']) . "','" . tep_db_prepare_input($_POST['chargesrates_per']) . "','" . tep_db_prepare_input($_POST['chargesrates_amount']) . "','" . tep_db_prepare_input($_POST['branch_code']) . "',NOW(),'" . tep_db_prepare_input($_POST['chargesrates_activated']) . "','" . tep_db_prepare_input($_POST['chargesrates_vat']) . "','" . tep_db_prepare_input($_POST['chargesrates_fixed']) . "','" . tep_db_prepare_input($_POST['licence_build']) . "','" . tep_db_prepare_input($_POST['chargesrates_stage']) . "')");

                $lablearray = getlables("345");
                if (tep_db_Commit()) {

                    //echo informationUpdate("success",$lablearray['345'],"showResult('frmid=frmconfigurecharges','txtHint')");
                } else {

                    //if (tep_db_inTransaction()){
                    tep_db_rollback();
                    //}
                    //echo 'success'.$lablearray['345'];
                }

                echo $lablearray['345'];
                //echo informationUpdate("success",$lablearray['345'],"showResult('frmid=frmconfigurecharges','txtHint')");

                break 2;

            case 'edit':
                $results_query = tep_db_query("SELECT rc.chargesrates_stage,rc.chargesrates_id,rc.branch_code, rc.chargesrates_id,rc.charges_id,chargesrates_from,chargesrates_to,rc.chargesrates_per,chargesrates_amount,chargesrates_activated FROM " . TABLE_CHARGERATES . " rc INNER JOIN " . TABLE_CHARGES . " c WHERE c.charges_id=rc.charges_id  AND rc.chargesrates_id='" . $_POST['id'] . "'");


                $results = tep_db_fetch_array($results_query);

                echo "formObj.action.value = 'add';\n";

                echo "SelectItemInList(\"charges_id\",\"" . $results["charges_id"] . "\");\n";
                echo "formObj.chargesrates_id.value = '" . $results["chargesrates_id"] . "';\n";

                echo "formObj.chargesrates_id.value = '" . $results["chargesrates_id"] . "';\n";

                if ($results["chargesrates_fixed"] == 'Y') {

                    echo "formObj.chargesrates_from.disabled = true;\n";
                    echo "formObj.chargesrates_to.disabled = true;\n";
                    echo "formObj.chargesrates_amount.disabled = true;\n";
                    echo "formObj.chargesrates_amount.value = '0.00';\n";
                    echo "formObj.chargesrates_from.value = '" . $results["chargesrates_from"] . "';\n";
                    echo "formObj.chargesrates_to.value = '" . $results["chargesrates_to"] . "';\n";
                    echo "formObj.chargesrates_per.value = '" . $results["chargesrates_per"] . "';\n";
                    echo "formObj.chargesrates_fixed.checked =false;\n";
                } else {
                    echo "formObj.chargesrates_from.value = '" . $results["chargesrates_from"] . "';\n";
                    echo "formObj.chargesrates_to.value = '" . $results["chargesrates_to"] . "';\n";
                    echo "formObj.chargesrates_per.value = '" . $results["chargesrates_per"] . "';\n";
                    echo "formObj.chargesrates_from.disabled = false;\n";
                    echo "formObj.chargesrates_to.disabled = false;\n";
                    echo "formObj.chargesrates_per.disabled = true;\n";
                    echo "formObj.chargesrates_amount.disabled = false;\n";
                    echo "formObj.chargesrates_amount.value = '" . $results["chargesrates_amount"] . "';\n";
                    echo "formObj.chargesrates_fixed.checked =true;\n";
                }
                echo "SelectItemInList(\"bankbranches_id\",\"" . $results["branch_code"] . "\");\n";
                echo "SelectItemInList(\"licence_build\",\"" . $results["licence_build"] . "\");\n";
                echo "SelectItemInList(\"chargesrates_stage\",\"" . $results["chargesrates_stage"] . "\");\n";
                echo "formObj.chargesrates_fixed.checked =true;\n";

                break 2;

            case 'delete':

                tep_db_query("DELETE FROM " . TABLE_CHARGERATES . " WHERE chargesrates_id='" . tep_db_prepare_input($_POST['id']) . "'");
                $lablearray = getlables('345');
                echo informationUpdate("success", $lablearray['345'], "showResult('frmid=frmconfigurecharges','txtHint')");

                break 2;
            case 'update':

                tep_db_query("UPDATE " . TABLE_CHARGERATES . " SET chargesrates_activated ='" . tep_db_prepare_input($_POST['chargesrates_activated']) . "', chargesrates_from='" . tep_db_prepare_input($_POST['chargesrates_from']) . "',chargesrates_to='" . tep_db_prepare_input($_POST['chargesrates_to']) . "',chargesrates_per='" . tep_db_prepare_input($_POST['chargesrates_per']) . "',chargesrates_amount='" . tep_db_prepare_input($_POST['chargesrates_amount']) . "',branch_code='" . tep_db_prepare_input($_POST['branch_code']) . "',chargesrates_vat='" . tep_db_prepare_input($_POST['chargesrates_vat']) . "',licence_build='" . tep_db_prepare_input($_POST['licence_build']) . "' WHERE chargesrates_id='" . tep_db_prepare_input($_POST['chargesrates_id']) . "'");


                /* $thepost = $_POST;
                  if($_POST['section']==1){
                  if(VAT_ON_FEES_ENABLED=='Y'){
                  if(ACC_VAT_ON_FEES==''){
                  echo informationUpdate("fail","Sorry, V.A.T Account not configured!","");
                  return;
                  }
                  }
                  while (list($key, $value) = each($thepost)) {
                  if($value!="frmvat" && $value!="update" && $key!="section"){
                  $query_result = tep_db_query("select vat_itemcode FROM ".TABLE_VAT." WHERE vat_itemcode='".$key."'");

                  if(tep_db_num_rows($query_result)>0){
                  tep_db_query("UPDATE ".TABLE_VAT." set vat_percentage =".$value." WHERE vat_itemcode='".$key."'");
                  }else{
                  tep_db_query("INSERT INTO ".TABLE_VAT." (vat_itemcode,vat_datecreated,vat_percentage) VALUES ('".$key."',NOW(),'". $value."')");
                  }
                  }
                  }

                  tep_db_query("UPDATE " .TABLE_ACCOUNTSCONFIG." SET accountsconfig_value ='".$_POST['VAT_ON_FEES_ENABLED']."' WHERE accountsconfig_key = '".$_POST['benabled']."'");

                  echo informationUpdate("","V.A.T has been successfully updated.","showResult('frmid=frmvat&section=1','section1');");
                  }
                 */

                break;

            default:
                switch ($_SESSION['P_LANG']) {

                    case 'EN':
                       
                        $charges_name_fieldname = 'charges_name_en';
                     
                        break;

                    case 'FR':
                   
                        $charges_name_fieldname = 'charges_name_fr';
               
                        break;

                    case 'SWA':
       
                        $charges_name_fieldname = 'charges_name_sa';
                     
                        break;

                    case 'JA':
                 
                        $charges_name_fieldname = 'charges_name_ja';
                   
                        break;

                    case 'SP':
                       
                        $charges_name_fieldname = 'charges_name_sp';
                        break;

                    case 'LUG':
                       
                        $charges_name_fieldname = 'charges_name_lug';
                    
                        break;

                    default:
                       
                        $charges_name_fieldname = 'charges_name_eng';
                    
                        break;
                }


                $query = "Select c." . $charges_name_fieldname . " as charges_name,cr.*,ob.bankbranches_name,cr.chargesrates_vat,cr.chargesrates_fixed,cr.chargesrates_id,cr.chargesrates_stage FROM " . TABLE_CHARGERATES . " cr , " . TABLE_CHARGES . " c, " . TABLE_OPERATORBRANCHES . " ob WHERE  cr.charges_id = c.charges_id AND ob.branch_code=cr.branch_code";


                $_SESSION['reportname'] = '';
                $_SESSION['reporttitle'] = "";
                $_SESSION['downloadlist'] = $query;
                $fieldlist = array('charges_name', 'chargesrates_from', 'chargesrates_to', 'chargesrates_amount', 'chargesrates_activated', 'chargesrates_per', 'chargesrates_vat', 'chargesrates_fixed', 'bankbranches_name', 'chargesrates_stage');
                $keyfield = 'chargesrates_id';
                $lables = getlables("920,921,922,923,924,766,926,927,928,893,934,1003,915");
                $gridcolumnnames = array($lables['920'], $lables['921'], $lables['922'], $lables['927'], $lables['766'], $lables['924'], $lables['915'], $lables['934'], $lables['928'], $lables['1003']);


                break;
        }



        break;

    case 'frmreconciliation':

        //$actionlinks ="<a href='#'  onClick=\"getFormData('frmid=".$_POST['frmid']."','edit')\" title ='Select and click to edit'>Unreconcile</a><a href='#'  onClick=\"getFormData('frmid=".$_POST['frmid']."','delete','".$_POST['frmid']."')\" title ='Select and click to reconcile'>Reconcile</a>";
        $actionlinks = "";


        $newgrid->paging['acc'] = $_POST['acc'];
        $newgrid->paging['txtFrom'] = $_POST['txtFrom'];
        $newgrid->paging['txtTo'] = $_POST['txtTo'];
        $newgrid->paging['bankaccounts_accno'] = $_POST['bankaccounts_accno'];
        $newgrid->checkedColor = "#D7FECB";

        if ($_POST['txtlastrecondate'] != "") {
            $recondate = common::changeDateFromPageToMySQLFormat($_POST['txtlastrecondate']);
        } else {
            $recondate = STARTFINYEAR;
        }

        switch ($_POST['action']) {

            case 'recalcalculate':

                $action = substr($_POST['signedamt'], strlen($_POST['signedamt']) - 2, strlen($_POST['signedamt']));

                $amount = substr($_POST['signedamt'], 0, strlen($_POST['signedamt']) - 2);

                if ($action == 'D+' || $action == 'D-') {

                    if ($action == 'D+') {
                        $txtbalancecleared = (float) ($_POST['txtbalancecleared'] + $amount);
                        echo "formObj.txtDeposits.value ='" . (float) ($_POST['txtDeposits'] + $amount) . "';\n";
                        echo "formObj.txtbalancecleared.value ='" . $txtbalancecleared . "';\n";
                        echo "formObj." . $_POST['elementid'] . ".value ='" . $amount . "D-';\n";
                    } else {

                        $txtbalancecleared = (float) ($_POST['txtDeposits'] - $amount);
                        echo "formObj.txtDeposits.value ='" . (float) ($_POST['txtDeposits'] - $amount) . "';\n";
                        echo "formObj.txtbalancecleared.value ='" . $txtbalancecleared . "';\n";
                        echo "formObj." . $_POST['elementid'] . ".value ='" . $amount . "D+';\n";
                    }

                    echo "document.getElementById('txtdifference').value ='" . (float) ($_POST['txtstatementbal'] + $_POST['txtOpeningBalance'] + $txtbalancecleared) . "';\n";
                }

                if ($action == 'P+' || $action == 'P-') {

                    if ($action == 'D+') {
                        $txtbalancecleared = (float) ($_POST['txtbalancecleared'] + $amount);
                        echo "formObj.txtPayments.value ='" . (float) ($_POST['txtDeposits'] - $amount) . "';\n";
                        echo "formObj.txtbalancecleared.value ='" . $txtbalancecleared . "';\n";
                        echo "formObj." . $_POST['elementid'] . ".value ='" . $amount . "P-';";
                    } else {
                        $txtbalancecleared = (float) ($_POST['txtbalancecleared'] - $amount);
                        echo "formObj.txtPayments.value ='" . (float) ($_POST['txtDeposits'] + $amount) . "';\n";
                        echo "formObj.txtbalancecleared.value ='" . $txtbalancecleared . "';\n";
                        echo "formObj." . $_POST['elementid'] . ".value ='" . $amount . "P+';\n";
                    }
                }

                break;

            case 'postcharge':

                $glaccount = $_POST['glaccount'];
                $lablearray = getlables("413");
                $query_result = tep_db_query("SELECT chartofaccounts_accountcode FROM " . TABLE_BANKACCOUNTS . " WHERE bankaccounts_accno='" . $_POST['bankaccounts_accno'] . "'");
                $Account = tep_db_fetch_array($query_result);
                $sql[] = array($_POST['amount'], '0', '', $_POST['glaccount'], 0, '', date("Y-m-d H:i:s", strtotime($_POST['date'])), $_SESSION['user_id'], $lablearray['413'], 'I02', '', getCurrencyID($_POST['glaccount']));   // Debit
                $sql[] = array('0', $_POST['amount'], '', $Account['chartofaccounts_accountcode'], 0, '', date("Y-m-d H:i:s", strtotime($_POST['date'])), $_SESSION['user_id'], $lablearray['413'], 'I02', '', getCurrencyID($Account['chartofaccounts_accountcode']));   // Credit
                $tcode = Common::generateTransactionCode($_SESSION['user_id']);
                PostTransactionsGeneral($sql, $tcode);
                $lablearray = getlables("218");
                echo informationUpdate('success', $lablearray['218']);
                break;

            case 'postinterest':

                $glaccount = $_POST['glaccount'];
                $lablearray = getlables("412");
                $query_result = tep_db_query("SELECT chartofaccounts_accountcode FROM " . TABLE_BANKACCOUNTS . " WHERE bankaccounts_accno='" . $_POST['bankaccounts_accno'] . "'");
                $Account = tep_db_fetch_array($query_result);
                $sql[] = array($_POST['amount'], '0', '', $Account['chartofaccounts_accountcode'], 0, '', date("Y-m-d H:i:s", strtotime($_POST['date'])), $_SESSION['user_id'], $lablearray['412'], 'I01', '', getCurrencyID($Account['chartofaccounts_accountcode']));   // Debit
                $sql[] = array('0', $_POST['amount'], '', $_POST['glaccount'], 0, '', date("Y-m-d H:i:s", strtotime($_POST['date'])), $_SESSION['user_id'], $lablearray['412'], 'I01', '', getCurrencyID($_POST['glaccount']));   // Credit
                $tcode = Common::generateTransactionCode($_SESSION['user_id']);
                PostTransactionsGeneral($sql, $tcode);
                echo informationUpdate('', 'Information saved!');
                break;

            case 'save':

                tep_db_query("UPDATE " . TABLE_RECONCILIATIONHISTORY . " SET reconciliationhistory_closed='1' WHERE tcode='" . $_POST['tcode'] . "' AND bankstatement_datecreated <=" . common::changeDateFromPageToMySQLFormat($_POST['txtStatementDate']));
                echo informationUpdate('', 'Information saved!');

                break;
            case 'unreconcileperiod':

                tep_db_query("DELETE FROM " . TABLE_RECONCILIATIONHISTORY . " WHERE bankstatement_datecreated >= " . common::changeDateFromPageToMySQLFormat($ctcode) . "' AND bankaccounts_accno='" . $_POST['bankaccounts_accno'] . "'");

                echo informationUpdate('', 'Transaction ' . $ctcode . ' unreconciled!');

                break;
            case 'unreconcile':
                $ctcode = substr($_POST['tcode'], 14, strlen($_POST['tcode']));
                tep_db_query("DELETE FROM " . TABLE_RECONCILIATIONHISTORY . " WHERE tcode='" . $ctcode . "' AND bankaccounts_accno='" . $_POST['bankaccounts_accno'] . "'");


                $action = substr($_POST['signedamt'], strlen($_POST['signedamt']) - 2, strlen($_POST['signedamt']));

                $amount = substr($_POST['signedamt'], 0, strlen($_POST['signedamt']) - 2);

                if ($action == 'D+' || $action == 'D-') {

                    if ($action == 'D+') {
                        $txtbalancecleared = (float) ($_POST['txtbalancecleared'] + $amount);
                        echo "formObj.txtDeposits.value ='" . (float) ($_POST['txtDeposits'] + $amount) . "';\n";
                        echo "formObj.txtbalancecleared.value ='" . $txtbalancecleared . "';\n";
                        echo "formObj." . $_POST['elementid'] . ".value ='" . $amount . "D-';\n";
                    } else {
                        $txtbalancecleared = (float) ($_POST['txtDeposits'] - $amount);
                        echo "formObj.txtDeposits.value ='" . (float) ($_POST['txtDeposits'] - $amount) . "';\n";
                        echo "formObj.txtbalancecleared.value ='" . $txtbalancecleared . "';\n";
                        echo "formObj." . $_POST['elementid'] . ".value ='" . $amount . "D+';\n";
                    }
                }

                if ($action == 'P+' || $action == 'P-') {

                    if ($action == 'D+') {
                        $txtbalancecleared = (float) ($_POST['txtbalancecleared'] + $amount);
                        echo "formObj.txtPayments.value ='" . (float) ($_POST['txtDeposits'] - $amount) . "';\n";
                        echo "formObj.txtbalancecleared.value ='" . $txtbalancecleared . "';\n";
                        echo "formObj." . $_POST['elementid'] . ".value ='" . $amount . "P-';";
                    } else {
                        $txtbalancecleared = (float) ($_POST['txtbalancecleared'] - $amount);
                        echo "formObj.txtPayments.value ='" . (float) ($_POST['txtDeposits'] + $amount) . "';\n";
                        echo "formObj.txtbalancecleared.value ='" . $txtbalancecleared . "';\n";
                        echo "formObj." . $_POST['elementid'] . ".value ='" . $amount . "P+';\n";
                    }
                }

                $EndingBal = (float) ($_POST['txtOpeningBalance'] + $_POST['txtDeposits'] - $_POST['txtPayments']);

                echo "document.getElementById('txtdifference').value ='" . (float) ($_POST['txtstatementbal'] - $EndingBal) . "';\n";

                echo informationUpdate('', 'Transaction ' . $ctcode . ' unreconciled!');

                break;

            case 'reconcile':
                $ctcode = substr($_POST['tcode'], 14, strlen($_POST['tcode']));
                // get bank accounts
                $query_result = tep_db_query("SELECT chartofaccounts_accountcode FROM " . TABLE_BANKACCOUNTS . " WHERE bankaccounts_accno='" . $_POST['bankaccounts_accno'] . "'");

                $Account = tep_db_fetch_array($query_result);

                //getTransaction						

                tep_db_query("DELETE FROM " . TABLE_RECONCILIATIONHISTORY . " WHERE tcode='" . $ctcode . "'");

                $tran_result = tep_db_query("SELECT generalledger_debit,generalledger_credit FROM " . TABLE_GENERALLEDGER . " WHERE tcode='" . $ctcode . "' AND chartofaccounts_accountcode='" . $Account['chartofaccounts_accountcode'] . "'");



                $transaction_results = tep_db_fetch_array($tran_result);

                tep_db_query("INSERT INTO " . TABLE_RECONCILIATIONHISTORY . " (chartofaccounts_accountcode,tcode,debit,credit,bankaccounts_accno,bankstatement_datecreated) VALUES('" . $Account['chartofaccounts_accountcode'] . "','" . $ctcode . "','" . $transaction_results['generalledger_debit'] . "','" . $transaction_results['generalledger_credit'] . "','" . $_POST['bankaccounts_accno'] . "'," . common::changeDateFromPageToMySQLFormat($_POST['txtStatementDate']) . ")");

                $action = substr($_POST['signedamt'], strlen($_POST['signedamt']) - 2, strlen($_POST['signedamt']));

                $amount = substr($_POST['signedamt'], 0, strlen($_POST['signedamt']) - 2);

                if ($action == 'D+' || $action == 'D-') {

                    if ($action == 'D+') {
                        $txtbalancecleared = (float) ($_POST['txtbalancecleared'] + $amount);
                        echo "formObj.txtDeposits.value ='" . (float) ($_POST['txtDeposits'] + $amount) . "';\n";
                        echo "formObj.txtbalancecleared.value ='" . $txtbalancecleared . "';\n";
                        echo "formObj." . $_POST['elementid'] . ".value ='" . $amount . "D-';\n";
                    } else {
                        $txtbalancecleared = (float) ($_POST['txtDeposits'] - $amount);
                        echo "formObj.txtDeposits.value ='" . (float) ($_POST['txtDeposits'] - $amount) . "';\n";
                        echo "formObj.txtbalancecleared.value ='" . $txtbalancecleared . "';\n";
                        echo "formObj." . $_POST['elementid'] . ".value ='" . $amount . "D+';\n";
                    }
                }

                if ($action == 'P+' || $action == 'P-') {

                    if ($action == 'D+') {
                        $txtbalancecleared = (float) ($_POST['txtbalancecleared'] + $amount);
                        echo "formObj.txtPayments.value ='" . (float) ($_POST['txtDeposits'] - $amount) . "';\n";
                        echo "formObj.txtbalancecleared.value ='" . $txtbalancecleared . "';\n";
                        echo "formObj." . $_POST['elementid'] . ".value ='" . $amount . "P-';";
                    } else {
                        $txtbalancecleared = (float) ($_POST['txtbalancecleared'] - $amount);
                        echo "formObj.txtPayments.value ='" . (float) ($_POST['txtDeposits'] + $amount) . "';\n";
                        echo "formObj.txtbalancecleared.value ='" . $txtbalancecleared . "';\n";
                        echo "formObj." . $_POST['elementid'] . ".value ='" . $amount . "P+';\n";
                    }
                }

                $EndingBals = (float) ($_POST['txtOpeningBalance'] + $_POST['txtDeposits'] - $_POST['txtPayments']);

                echo "document.getElementById('txtdifference').value ='" . (float) ($_POST['txtstatementbal'] - $EndingBal) . "';\n";

                echo informationUpdate('', 'Transaction ' . $ctcode . ' reconciled!');

                break;

            case 'getbeginbal':

                $query_result = tep_db_query("SELECT ABS(IFNULL(SUM(generalledger_debit),0.00000)-IFNULL(SUM(generalledger_credit),0.00000)) AS bal FROM " . TABLE_GENERALLEDGER . " WHERE chartofaccounts_accountcode='" . $_POST['chartofaccounts_accountcode'] . "' AND generalledger_datecreated <=" . common::changeDateFromPageToMySQLFormat($_POST['txtlastrecondate']));

                $balance = tep_db_fetch_array($query_result);

                //$lablearray = getlables("373");

                if ($balance['bal'] > 0) {
                    $nDr = $balance['bal'];
                    $nCr = 0;
                } else {
                    $nCr = $balance['bal'];
                    $nDr = 0;
                }

                tep_db_query("DELETE FROM " . TABLE_RECONCILIATIONHISTORY . " WHERE tcode='00000000000' AND reconciliationhistory_closed='0'");
                tep_db_query("INSERT INTO " . TABLE_RECONCILIATIONHISTORY . " (chartofaccounts_accountcode,tcode,debit,credit,bankaccounts_accno,bankstatement_datecreated) VALUES('" . $_POST['chartofaccounts_accountcode'] . "','00000000000','" . $nDr . "','" . $nCr . "','" . $_POST['bankaccounts_accno'] . "',NOW)");

                echo "formObj.txtOpeningBalance.value ='" . (float) round($balance['bal'], SETTTING_ROUND_TO) . "';";

                break;

            case 'getgl':

                $query_result = tep_db_query("SELECT chartofaccounts_accountcode FROM " . TABLE_BANKACCOUNTS . " WHERE bankaccounts_accno='" . $_POST['acc'] . "'");

                $account = tep_db_fetch_array($query_result);

                $query_result = tep_db_query("SELECT ABS(IFNULL(SUM(generalledger_debit),0.00000)-IFNULL(SUM(generalledger_credit),0.00000)) AS bal FROM " . TABLE_GENERALLEDGER . " WHERE chartofaccounts_accountcode='" . $account['chartofaccounts_accountcode'] . "' AND generalledger_datecreated <='" . $recondate . "'");

                $balance = tep_db_fetch_array($query_result);

                if ($balance['bal'] == "") {
                    //$balance['bal']	=(float)round(0,SETTTING_ROUND_TO)	;
                }
                echo "formObj.chartofaccounts_accountcode.value ='" . $account['chartofaccounts_accountcode'] . "';\n";
                echo "formObj.txtlastrecondate.value ='" . changeMySQLDateToPageFormat($recondate) . "';\n;";
                echo "formObj.txtOpeningBalance.value ='" . (float) round($balance['bal'], SETTTING_ROUND_TO) . "';\n";
                echo "formObj.txtbalancecleared.value ='" . (float) round($balance['bal'], SETTTING_ROUND_TO) . "';\n";


                break;

            case 'update':

                // extract accounts
                $adjusted_info = explode("-", $_POST['thepost']);

                foreach ($adjusted_info as $currentPart) {

                    list($key, $value) = explode("_", $currentPart);
                    $adjusted_accounts[$key] = $value;
                }


                foreach ($adjusted_accounts as $key => $value) {

                    if (substr($key, 0, 3) == "ACC") {
                        $nAmount = $nAmount + str_replace(",", "", $adjusted_accounts['D' . $adjusted_accounts[$key]]);
                        $nCredit = $nCredit + str_replace(",", "", $adjusted_accounts['C' . $adjusted_accounts[$key]]);
                        $nDebit = $nDebit + str_replace(",", "", $adjusted_accounts['D' . $adjusted_accounts[$key]]);

                        tep_db_query("UPDATE " . TABLE_GENERALLEDGER . " SET generalledger_debit='" . str_replace(",", "", $adjusted_accounts['D' . $adjusted_accounts[$key]]) . "',generalledger_credit ='" . str_replace(",", "", $adjusted_accounts['C' . $adjusted_accounts[$key]]) . "',chartofaccounts_accountcode='" . $adjusted_accounts[$key] . "' WHERE chartofaccounts_accountcode='" . substr($key, 3, strlen($key)) . "' AND tcode='" . $_POST['tcode'] . "'");
                    }
                }


                tep_db_query("UPDATE " . TABLE_CHEQS . " SET cheqs_amount='" . $nAmount . "'  WHERE tcode='" . $_POST['tcode'] . "'");

                tep_db_query("UPDATE " . TABLE_RECONCILIATIONHISTORY . " SET debit='" . $nDebit . "',Credit='" . $nCredit . "'  WHERE tcode='" . $_POST['tcode'] . "'");

                tep_db_query("UPDATE " . TABLE_STUDENTSPAYMENTS . " SET studentspayments_amount='" . $nAmount . "' WHERE tcode='" . $_POST['tcode'] . "'");

                tep_db_query("UPDATE " . TABLE_PAYMENTSCREDITORS . " SET invoicepayments_amount='" . $nAmount . "' WHERE tcode='" . $_POST['tcode'] . "'");

                echo informationUpdate('success', 'Information has been successfully updated.', "showResult('frmid=frmreconciliation&action=gettran&tcode=" . $_POST['tcode'] . "','txtHint2')");

                break;

            case 'gettran':

                $query = "SELECT tcode,generalledger_datecreated,generalledger_description,generalledger_voucher, chartofaccounts_name,gl.chartofaccounts_accountcode,generalledger_debit,generalledger_credit FROM " . TABLE_GENERALLEDGER . " AS gl," . TABLE_CHARTOFACCOUNTS . " as coa WHERE gl.chartofaccounts_accountcode=coa.chartofaccounts_accountcode AND gl.tcode='" . $_POST['tcode'] . "'";

                $newgrid->Uinter = 'txtHint2';
                $newgrid->addcheckbox = false;
                $newgrid->cCaption = "Modify Transaction : <input type='hidden' id='txthiddentcode' name='txthiddentcode' value='" . $_POST['tcode'] . "'>" . $_POST['tcode'];
                $newgrid->cpara = "RECONCILE";
                $newgrid->extraFields[0] = "generalledger_voucher";
                $fieldlist = array('generalledger_datecreated', 'chartofaccounts_accountcode', 'generalledger_debit', 'generalledger_credit');
                $keyfield = 'tcode';
                $gridcolumnnames = array('Date', 'Account', 'Debit', 'Credit');
                $total_query = tep_db_query("SELECT SUM(generalledger_debit) AS Debit,SUM(generalledger_credit) As Credit FROM " . TABLE_GENERALLEDGER . " AS gl WHERE gl.tcode='" . $_POST['tcode'] . "'");
                $total = tep_db_fetch_array($total_query);
                $newgrid->lastcontrols = "<table cellpadding='0' cellspacing='0' border='0'>
											<tr>
												<td></td>		
												<td>Total Debit<br><input name='txtDebitBalance' type='text' id='txtDebitBalance' value='" . $total['Debit'] . "' disabled></td>
												<td>Total Credit<br><input name='txtCreditBalance' type='text' id='txtCreditBalance' value='" . $total['Credit'] . "' disabled></td>		
										  </tr>
									  </table>";

                echo $newgrid->getdata($query, $fieldlist, $keyfield, $gridcolumnnames, $_POST['frmid'], $actionlinks, $actionlinks2, $onclick, $Uinter, $chkname, $defaultsortfield, $gridsorton, $addfilelinks);

                $query = "";

                break;

            case 'search':
                $query_result = tep_db_query("SELECT chartofaccounts_accountcode FROM " . TABLE_BANKACCOUNTS . " WHERE bankaccounts_accno='" . $_POST['bankaccounts_accno'] . "'");
                $Account = tep_db_fetch_array($query_result);

                tep_db_query("DROP TABLE IF EXISTS UnReconciled");
                $cunion = "
			CREATE TEMPORARY TABLE UnReconciled AS SELECT tcode,(select cheqs_no FROM " . TABLE_CHEQS . " where tcode=sp.tcode) as cheqs_no, 'DC' as cheqs_status,DATE_FORMAT(studentspayments_datecreated,'%d/%m/%Y') as cheqs_datecleared ,ROUND(studentspayments_amount," . SETTTING_ROUND_TO . ") as cheqs_amount,(SELECT CONCAT(students_sregno,' ',students_firstname,'',students_lastname) FROM " . TABLE_STUDENTS . " AS s WHERE s.students_sregno=sp.students_sregno) AS description FROM " . TABLE_STUDENTSPAYMENTS . " as sp WHERE (transactiontypes_code='BD' OR transactiontypes_code='BT' OR transactiontypes_code='CQ') AND  DATE_FORMAT(studentspayments_datecreated,'%Y-%m-%d') BETWEEN '" . $recondate . "' AND " . common::changeDateFromPageToMySQLFormat($_POST['txtStatementDate']) . " AND tcode in (select tcode FROM " . TABLE_CHEQS . ") AND tcode IN (SELECT tcode FROM " . TABLE_GENERALLEDGER . ") 
			UNION
			SELECT p.tcode,(SELECT cheqs_no FROM " . TABLE_CHEQS . " as c WHERE c.tcode=p.tcode) as cheqs_no, 'UC' as cheqs_status,DATE_FORMAT(invoicepayments_datecreated,'%d/%m/%Y') as cheqs_datecleared ,ROUND(invoicepayments_amount," . SETTTING_ROUND_TO . ")  as cheqs_amount,(select creditors_name FROM " . TABLE_CREDITORS . " as c WHERE c.creditors_id=p.creditors_id) AS description FROM " . TABLE_PAYMENTSCREDITORS . " as p WHERE tcode IN (SELECT tcode FROM " . TABLE_GENERALLEDGER . ") AND p.invoicepayments_datecreated >='" . $recondate . "' AND p.invoicepayments_datecreated <=" . common::changeDateFromPageToMySQLFormat($_POST['txtStatementDate']) . " AND tcode in (select tcode FROM " . TABLE_CHEQS . ")";

                tep_db_query($cunion);
                tep_db_query("DROP TABLE IF EXISTS UnReconciled1");
                tep_db_query("DROP TABLE IF EXISTS UnReconciled1a");
                tep_db_query("DROP TABLE IF EXISTS UnReconciled1b");

                tep_db_query("CREATE TEMPORARY TABLE UnReconciled1a AS SELECT UnReconciled.* FROM UnReconciled," . TABLE_CHEQS . " as c WHERE UnReconciled.tcode=c.tcode AND c.bankaccounts_accno='" . $_POST['bankaccounts_accno'] . "'");

                tep_db_query("CREATE TEMPORARY TABLE UnReconciled1b AS SELECT tcode,'N/A' AS cheqs_no,IF(ISNULL(generalledger_credit)> 0,'DC','UC') as cheqs_status,DATE_FORMAT(generalledger_datecreated,'%d/%m/%Y') as cheqs_datecleared ,ROUND(generalledger_credit," . SETTTING_ROUND_TO . ") as cheqs_amount, generalledger_description AS description  FROM " . TABLE_GENERALLEDGER . " WHERE tcode NOT IN (SELECT tcode FROM " . TABLE_CHEQS . ") AND chartofaccounts_accountcode='" . $Account['chartofaccounts_accountcode'] . "'");

                tep_db_query("CREATE TEMPORARY TABLE UnReconciled1 AS SELECT * FROM UnReconciled1a UNION  SELECT * FROM UnReconciled1b");

                tep_db_query("DROP TABLE IF EXISTS UnReconciled2");

                tep_db_query("CREATE TEMPORARY TABLE UnReconciled2 AS SELECT UnReconciled1.*,IF(ISNULL(rc.tcode),'No','Yes') as status FROM UnReconciled1 LEFT JOIN " . TABLE_RECONCILIATIONHISTORY . " AS rc ON rc.tcode=UnReconciled1.tcode ORDER BY cheqs_datecleared ASC");

                tep_db_query("DROP TABLE IF EXISTS UnReconciled1");

                $newgrid->cpara = "RECONCILE";

                $newgrid->addcolumnRight = true;

                $newgrid->checkboxvaluefield = "cheqs_amount";
                //$newgrid->addcheckbox = false;

                $newgrid->numberof_rows_on_page = "10000";

                // get checked checked boxes

                $tcode_results = tep_db_query("SELECT tcode FROM " . TABLE_RECONCILIATIONHISTORY . " WHERE bankstatement_datecreated <=" . common::changeDateFromPageToMySQLFormat($_POST['txtStatementDate']) . " GROUP BY tcode");


                // thse transactions will be checked when the grid loads
                while ($row = tep_db_fetch_array($tcode_results)) {
                    $newgrid->checked[$row['tcode']] = $row['tcode'];
                }



                $_SESSION['reportname'] = '';
                $_SESSION['reporttitle'] = "";
                //	$_SESSION['downloadlist'] = $query;
                $fieldlist = array('tcode', 'cheqs_datecleared', 'cheqs_no', 'cheqs_amount', 'description');
                $keyfield = 'tcode';
                $gridcolumnnames = array('Transaction#', 'Date Posted', 'Cheque Number', 'Amount', 'Description');

                $query1 = "SELECT UnReconciled2.*, CONCAT(CAST(cheqs_amount as char),'P-') as action FROM UnReconciled2  WHERE cheqs_status='UC'";
                $query2 = "SELECT UnReconciled2.*, CONCAT(CAST(cheqs_amount as char),'D+') as action FROM UnReconciled2  WHERE cheqs_status='DC'";

                $left_results = tep_db_query("SELECT SUM(cheqs_amount) as bal FROM UnReconciled2  WHERE cheqs_status='UC' AND tcode in (SELECT tcode FROM " . TABLE_RECONCILIATIONHISTORY . " WHERE credit >'0')");
                $left_total = tep_db_fetch_array($left_results);
                $right_results = tep_db_query("SELECT SUM(cheqs_amount) as bal FROM UnReconciled2  WHERE cheqs_status='DC' AND tcode in (SELECT tcode FROM " . TABLE_RECONCILIATIONHISTORY . " WHERE debit >'0')");
                $right_total = tep_db_fetch_array($right_results);

                // this is used when loading a second time
                if ($_POST['load'] == "text") {


                    echo "formObj.txtbalancecleared.value ='" . (float) ($right_total['bal'] - $left_total['bal']) . "';\n";
                    echo"formObj.txtDeposits.value ='" . ($right_total['bal']) . "';\n";
                    $EndingBal = (float) (($_POST['txtOpeningBalance'] + $right_total['bal']) - $left_total['bal']);
                    echo "formObj.txtdifference.value ='" . ($_POST['txtstatementbal'] - $EndingBal) . "';\n";

                    if ($left_total['bal'] == "") {
                        $left_total['bal'] = 0;
                    }

                    echo "formObj.txtPayments.value ='" . ($left_total['bal']) . "';\n";
                    //echo informationUpdate('','Information Updated');
                    break;
                }

                $newgrid->checkboxvaluefield = "action";
                $newgrid->ToolTipText = "Click to modify transaction";
                $newgrid->lablesarray = $lables_array;

                $newgrid->cCaption = "Cheques and Payments";
                // get table 1
                $ctable = '<table style="border:1px solid #999999;"><tr><td valign="top">' . $newgrid->getdata($query1, $fieldlist, $keyfield, $gridcolumnnames, $_POST['frmid'], $actionlinks, $actionlinks2, $onclick, $Uinter, $chkname, $defaultsortfield, $gridsorton, $addfilelinks) . '</td><td  valign="top">';

                // get table 2
                $newgrid->cCaption = "Deposits and Credits";
                $ctable = $ctable . $newgrid->getdata($query2, $fieldlist, $keyfield, $gridcolumnnames, $_POST['frmid'], $actionlinks, $actionlinks2, $onclick, $Uinter, $chkname, $defaultsortfield, $gridsorton, $addfilelinks) . "</tr></table>";

                //		$evaluatelater .= "document.getElementById('txtHint').InnerHTML='".$ctable."';\n";			
                echo $ctable;
                $query = "";

                break;

            default:
                break;
        }


        break;
    case 'frmsearch':


        $newgrid->paging['searchterm'] = $_POST['searchterm'];
        $newgrid->paging['classes_id'] = $_POST['classes_id'];
        $newgrid->paging['classcategories_id'] = $_POST['classes_id'];

        getlables('515,516,517,518,519,520,521');


        $actionlinks = "<div class='topbar'></div><div class='shadowblockmenu'><ul class='claybricks'><li><a href='#'  onClick='if(window.confirm(\"" . $lablearray['515'] . "\")==true){SelectForm(this,\"DELETE\");}' title =\"" . $lablearray['516'] . "\">" . $lablearray['516'] . "</a></li><li><a href='#'  onClick='javascript:SelectForm(this,\"EDITPROFILE\")' title =\"" . $lablearray['517'] . "\">" . $lablearray['517'] . "</a></li><li><a href='#'  onClick='javascript:SelectForm(this,\"PAY\")' title =\'" . $lablearray['518'] . "'>" . $lablearray['518'] . "</a><a href='#'  onClick='javascript:SelectForm(this,\"VIEWPAYSTAT\")' title='" . $lablearray['519'] . "'>" . $lablearray['519'] . "</a></li><li><a href='#'  onClick='javascript:SelectForm(this,\"ADDREPORT\")' title='" . $lablearray['520'] . "'>" . $lablearray['520'] . "</a></li><li><a href='#'  onClick='javascript:SelectForm(this,\"VIEWACREPORT\")' title='" . $lablearray['521'] . "'>" . $lablearray['521'] . "</a></li></ul></div>";

        if ($_POST['searchterm'] == "") {
            $SQL = " s.students_firstname LIKE '%%'";
        } else {
            $SQL = " s.students_firstname LIKE '" . trim($_POST['searchterm']) . "%' OR s.students_lastname LIKE '" . trim($_POST['searchterm']) . "%' OR s.students_sregno  LIKE '" . trim($_POST['searchterm']) . "%' OR c.classes_name  LIKE '" . trim($_POST['searchterm']) . "%'";
        }

        if ($_POST['classes_id'] != "") {
            $SQL = $SQL . "  AND c.classes_id = '" . trim($_POST['classes_id']) . "' ";
        }

        if ($_POST['classcategories_id'] != "") {
            $SQL = $SQL . "  AND s.classcategories_id = '" . trim($_POST['classcategories_id']) . "'";
        }

        //echo  getdata($query,array('students_sregno','students_firstname','students_lastname','class'),'students_sregno',array('Admission Number',"Firstname","Lastname","Class"),$_POST['frmid'],array('sortfield'=>$_POST['sortfield'],'sortorder'=>$_POST['sortorder'],'setnumber'=>$_POST['setnumber'],'start'=>$_POST['start'],'page'=>$_POST['page'],'searchterm'=>$_POST['searchterm']),true,$actionlinks);

        $query = "SELECT UPPER(s.students_sregno) AS students_sregno,s.students_firstname,s.students_lastname,s.students_level,(select classes_name FROM " . TABLE_STUDENTCLASSES . " sc  WHERE sc.students_sregno=s.students_sregno AND studentclasses_currentflag='Y' group by sc.students_sregno) as class,students_image,students_gender,students_homeaddress FROM " . TABLE_STUDENTS . "  as s LEFT JOIN " . TABLE_STUDENTCLASSES . " AS sc ON sc.students_sregno=s.students_sregno LEFT JOIN " . TABLE_CLASSES . " as c ON c.classes_id=sc.classes_id WHERE " . $SQL . " group by s.students_sregno";

        $_SESSION['reportname'] = '';
        $_SESSION['reporttitle'] = "";
        $_SESSION['downloadlist'] = $query;

        $newgrid->extraFields['Photo'] = "students_image";
        $newgrid->cpara = "MAINSEARCH";

        $lables_array = $grid_lables_lablearray + getlables("610,238,240,194,199,611");

        $fieldlist = array('students_sregno', 'students_firstname', 'students_lastname', 'class', 'students_gender', 'students_homeaddress');
        $keyfield = 'students_sregno';
        $gridcolumnnames = array($lablearray['610'], $lablearray['238'], $lablearray['240'], $lablearray['194'], $lablearray['199'], $lablearray['611']);

        break;



    case 'frmcashreport':

        $newgrid->paging['id'] = $_POST['id'];
        $newgrid->paging['name1'] = $_POST['name1'];
        $newgrid->paging['name2'] = $_POST['name2'];
        $newgrid->paging['name3'] = $_POST['name3'];
        $newgrid->paging['hid'] = $_POST['hid'];
        $newgrid->paging['fr'] = $_POST['fr'];
        $newgrid->paging['cfheader_cfincrease'] = $_POST['cfheader_cfincrease'];
        $newgrid->paging['cfheader_id'] = $_POST['cfheader_id'];
        $newgrid->paging['isDebit'] = $_POST['isDebit'];

        switch ($_POST['action']) {
            case 'delete':


                switch ($_POST['section']) {

                    case '1':
                        tep_db_query("DELETE FROM " . TABLE_CFREPORTS . " WHERE  cfReports_id='" . $_POST['id'] . "'");

                        break;

                    case '2':
                        tep_db_query("DELETE FROM " . TABLE_CFHEADER . " WHERE  cfheader_id='" . $_POST['id'] . "'");
                        break;

                    case '3':
                        tep_db_query("DELETE FROM " . TABLE_CFLABEL . " WHERE  cflabel_id='" . $_POST['id'] . "'");
                        break;
                    default:
                        break;
                }

                break;

            case 'add':

                switch ($_POST['section']) {

                    case '1':
                        $query_results = tep_db_query("SELECT cfReports_name FROM " . TABLE_CFREPORTS . " WHERE  cfReports_name='" . $_POST['name1'] . "'");

                        if (tep_db_num_rows($query_results) > 0) {
                            echo informationUpdate('fail', 'Information already registered in system');
                        } else {
                            tep_db_query("INSERT INTO " . TABLE_CFREPORTS . " (cfReports_name) VALUES ('" . tep_db_prepare_input($_POST['name1']) . "')");
                            //echo informationUpdate('add','');
                        }
                        break;

                    case '2':
                        $query_results = tep_db_query("SELECT cfheader_id FROM " . TABLE_CFHEADER . " WHERE  cfheader_en='" . $_POST['name2'] . "'");

                        if (tep_db_num_rows($query_results) > 0) {
                            echo informationUpdate('present', '');
                        } else {
                            tep_db_query("INSERT INTO " . TABLE_CFHEADER . " (cfReports_id,cfheader_en,cfheader_cfincrease) VALUES ('" . tep_db_prepare_input($_POST['hid']) . "','" . $_POST['name2'] . "','" . $_POST['cfheader_cfincrease'] . "')");
                            echo informationUpdate('', 'Successfully addedd');
                        }
                        break;

                    case '3':
                        $query_results = tep_db_query("SELECT cflabel_id FROM " . TABLE_CFLABEL . " WHERE  cflabel_en ='" . $_POST['name3'] . "'");

                        if (tep_db_num_rows($query_results) > 0) {
                            echo informationUpdate('fail', 'Information already registred in system');
                        } else {
                            tep_db_query("INSERT INTO " . TABLE_CFLABEL . " (cfheader_id,cflabel_en,chartofaccounts_accountcode_from,chartofaccounts_accountcode_to,cflabel_isdebit) VALUES ('" . tep_db_prepare_input($_POST['hid']) . "','" . tep_db_prepare_input($_POST['name3']) . "','" . tep_db_prepare_input($_POST['fr']) . "','" . tep_db_prepare_input($_POST['to']) . "','" . tep_db_prepare_input($_POST['IsDebit']) . "')");
                            echo informationUpdate('', 'Successfuly registered');
                        }
                        break;
                    default:
                        break;
                }

                break;

            case 'update':
                //tep_db_query("UPDATE ".TABLE_CFREPORTS." SET cfReports_name='".tep_db_prepare_input($_POST['cfReports_name'])."' WHERE roles_id='".$_POST['cfReports_id']."'");

                switch ($_POST['section']) {

                    case '1':
                        tep_db_query("UPDATE " . TABLE_CFREPORTS . " SET cfReports_name='" . tep_db_prepare_input($_POST['name1']) . "' WHERE cfReports_id='" . $_POST['hid'] . "'");
                        break;

                    case '2':
                        tep_db_query("UPDATE " . TABLE_CFHEADER . " SET cfReports_id='" . tep_db_prepare_input($_POST['hid']) . "',cfheader_en='" . tep_db_prepare_input($_POST['name2']) . "',cfheader_cfincrease='" . tep_db_prepare_input($_POST['cfheader_cfincrease']) . "' WHERE cfheader_id='" . $_POST['cfheader_id'] . "'");
                        break;

                    case '3':
                        tep_db_query("UPDATE " . TABLE_CFLABEL . " SET cfheader_id='" . tep_db_prepare_input($_POST['hid']) . "',cflabel_en='" . tep_db_prepare_input($_POST['name3']) . "',chartofaccounts_accountcode_from='" . tep_db_prepare_input($_POST['fr']) . "',chartofaccounts_accountcode_to='" . tep_db_prepare_input($_POST['to']) . "',cflabel_isdebit='" . tep_db_prepare_input($_POST['IsDebit']) . "' WHERE cfheader_id='" . $_POST['hid'] . "' AND cflabel_id='" . $_POST['id'] . "'");
                        break;
                }
                echo informationUpdate("", "Successfully updated");
                break;

            case 'edit':

                if ($_POST['rid'] != "") {

                    $query = "SELECT  cfheader_id,cfReports_id,cfheader_en FROM " . TABLE_CFHEADER . " WHERE cfReports_id='" . $_POST['rid'] . "'";

                    $results_query = tep_db_query($query);

                    $select2 = "<select name='header1' id='header1' onChange='javascript:selectReportName(this);'>";
                    $select2 .= "<option id='' value=''>Select report headername...</option>";
                    while ($results = tep_db_fetch_array($results_query)) {
                        $select2.= "<option id='" . $results['cfheader_id'] . "' value='" . $results['cfheader_en'] . "'>" . $results['cfheader_en'] . "</option>";
                    }

                    $select2.= "</select>";
                    echo "document.getElementById('definedheadersrpt').innerHTML =\"" . $select2 . "\";";
                }


                // defined lables
                if ($_POST['hid'] != "") {
                    $query = "SELECT cflabel_id,cfheader_id,cflabel_en,chartofaccounts_accountcode_from,chartofaccounts_accountcode_to FROM " . TABLE_CFLABEL . " WHERE cfheader_id='" . $_POST['hid'] . "'";

                    $results_query = tep_db_query($query);

                    $select3 = "<select name='lable1' id='lable1' onChange='javascript:selectReportName(this);'>";
                    $select3 .= "<option id='' value=''>Select label...</option>";
                    while ($results = tep_db_fetch_array($results_query)) {
                        $select3.= "<option id='" . $results['cflabel_id'] . "' value='" . $results['cflabel_en'] . "'>" . $results['cflabel_en'] . "</option>";
                    }

                    $select3.= "</select>";
                    //echo "SelectItemInList(\"chartofaccounts_accountcode_from\",\"".$results['chartofaccounts_accountcode_from']."\");\n";
                    //echo "SelectItemInList(\"chartofaccounts_accountcode_to\",\"".$results['chartofaccounts_accountcode_to']."\");\n";
                    echo "document.getElementById('definedlabels').innerHTML =\"" . $select3 . "\";\n";

                    $query = "SELECT  cfheader_cfincrease FROM " . TABLE_CFHEADER . " WHERE cfheader_id='" . $_POST['hid'] . "'";

                    $results_query = tep_db_query($query);

                    $results_array = tep_db_fetch_array($results_query);

                    if ($results_array['cfheader_cfincrease'] == "Y") {
                        echo "document.getElementById('cfheader_cfincrease').checked = true;\n";
                    } else {
                        echo "document.getElementById('cfheader_cfincrease').checked = false;\n";
                    }
                    echo $_POST['hid'];
                }

                // label selected
                if ($_POST['id1'] != "") {
                    $query = "SELECT cflabel_id,chartofaccounts_accountcode_from,chartofaccounts_accountcode_to,cflabel_isdebit FROM " . TABLE_CFLABEL . " WHERE cflabel_id='" . $_POST['id1'] . "'";
                    $results_query = tep_db_query($query);
                    $results = tep_db_fetch_array($results_query);
                    echo "SelectItemInList(\"chartofaccounts_accountcode_from\",\"" . $results['chartofaccounts_accountcode_from'] . "\");\n";
                    echo "SelectItemInList(\"chartofaccounts_accountcode_to\",\"" . $results['chartofaccounts_accountcode_to'] . "\");\n";
                    echo "formObj.cflabel_id.value = '" . $results['cflabel_id'] . "';\n";
                    // is debit
                    if ($results['cflabel_isdebit'] == "Y") {
                        echo "formObj.debit.checked ='true';";
                    } else {
                        echo "formObj.credit.checked ='true';";
                    }
                }

                if ($_POST['id'] != "") {
                    $query = "SELECT cflabel_id,cfheader_id,cflabel_en FROM " . TABLE_CFLABEL . " WHERE cfheader_id='" . $_POST['id'] . "'";
                } else {
                    $query = "";
                }

                break;

            default:

                // defined reports
                $query = "SELECT  cfReports_id,cfReports_name FROM " . TABLE_CFREPORTS . " ORDER BY cfReports_name ASC";

                $results_query = tep_db_query($query);

                $select1 = "<select name='cfReports_name1' id='cfReports_name1' onChange='javascript:selectReportName(this);'>";
                $select1 .= "<option id='non' value='non'>Select report name...</option>";
                while ($results = tep_db_fetch_array($results_query)) {
                    $select1.= "<option id='" . $results['cfReports_id'] . "' value='" . $results['cfReports_name'] . "'>" . $results['cfReports_name'] . "</option>";
                }

                $select1 .= "</select>";

                echo "document.getElementById('action1').innerHTML ='add';";
                echo "document.getElementById('action2').innerHTML ='add';";
                echo "document.getElementById('action3').innerHTML ='add';";
                echo "document.getElementById('definedrpt').innerHTML =\"" . $select1 . "\";";

                $query = "";

                /* // defined headers

                  if($_POST[rid]!=""){

                  $query = "SELECT  cfheader_id,cfheader_en FROM ".TABLE_CFHEADER." WHERE cfheader_reportid='".$_POST['rid']."'";

                  $results_query =tep_db_query($query);

                  $select2 = "<select name='header1' id='header1' onChange='javascript:selectReportName(this);'>";
                  $select2 .= "<option id='' value=''>Select report headername...</option>";
                  while($results = tep_db_fetch_array($results_query)){
                  $select2.= "<option id='".$results['cfheader_id']."' value='".$results['cfheader_en']."'>".$results['cfheader_en']."</option>";
                  }

                  $select2.= "</select>";

                  echo "document.getElementById('definedheadersrpt').innerHTML =\"".$select2."\";";


                  }


                  // defined lables
                  if($_POST['hid']!=""){
                  $query = "SELECT cflabel_id,cfheader_id,cflabel_en,chartofaccounts_accountcode_from,chartofaccounts_accountcode_to FROM ".TABLE_CFLABEL." WHERE cfheader_id='".$_POST['hid']."'";

                  $results_query =tep_db_query($query);

                  $select3 = "<select name='lable1' id='lable1' onChange='javascript:selectReportName(this);'>";
                  $select3 .= "<option id='' value=''>Select label...</option>";
                  while($results = tep_db_fetch_array($results_query)){
                  $select3.= "<option id='".$results['cflabel_id']."' value='".$results['cflabel_en']."'>".$results['cflabel_en']."</option>";
                  }

                  $select3.= "</select>";
                  //echo "SelectItemInList(\"chartofaccounts_accountcode_from\",\"".$results['chartofaccounts_accountcode_from']."\");\n";
                  //echo "SelectItemInList(\"chartofaccounts_accountcode_to\",\"".$results['chartofaccounts_accountcode_to']."\");\n";
                  echo "document.getElementById('definedlabels').innerHTML =\"".$select3."\";";
                  }

                  // label selected
                  if($_POST['id1']!=""){
                  $query = "SELECT chartofaccounts_accountcode_from,chartofaccounts_accountcode_to FROM ".TABLE_CFLABEL." WHERE cflabel_id='".$_POST['id1']."'";
                  $results_query =tep_db_query($query);
                  $results = tep_db_fetch_array($results_query);
                  echo "SelectItemInList(\"chartofaccounts_accountcode_from\",\"".$results['chartofaccounts_accountcode_from']."\");\n";
                  echo "SelectItemInList(\"chartofaccounts_accountcode_to\",\"".$results['chartofaccounts_accountcode_to']."\");\n";
                  }

                  if($_POST['id']!=""){
                  $query = "SELECT cflabel_id,cfheader_id,cflabel_en FROM ".TABLE_CFLABEL." WHERE cfheader_id='".$_POST['id']."'";
                  }else{
                  $query = "";
                  } */

                break;
        }



        $_SESSION['reportname'] = 'Lables';
        $_SESSION['reporttitle'] = "List of Lables";
        $_SESSION['downloadlist'] = $query;
        $fieldlist = array('cflabel_en', 'chartofaccounts_accountcode_from', 'chartofaccounts_accountcode_to', 'cflabel_isdebit');
        $keyfield = 'cflabel_id';
        $gridcolumnnames = array('Lable', 'From', 'To', 'Debit?');
        break;


    case 'frmroleoperations':

        switch ($_POST['action']) {

            case 'getmoduleoperations':

                $moperations_query2 = tep_db_query("SELECT operations_id FROM " . TABLE_OPERATIONS);

                while ($moperations = tep_db_fetch_array($moperations_query2)) {
                    echo "document.getElementById('Operations" . $moperations['operations_id'] . "').checked=false;\n";
                }

                $operations_query = tep_db_query("SELECT operations_id FROM " . TABLE_MODULESOPERATIONS . " WHERE modules_id='" . $_POST['modules_id'] . "' AND roles_id='" . $_POST['roles_id'] . "'");

                if (tep_db_num_rows($operations_query) > 0) {

                    while ($operations_array = tep_db_fetch_array($operations_query)) {
                        echo "document.getElementById('Operations" . $operations_array['operations_id'] . "').checked = true;\n";
                    }
                }

                break 2;

            case 'update':
                tep_db_query("DELETE FROM " . TABLE_MODULESOPERATIONS . " WHERE  modules_id='" . $_POST['modules_id'] . "'");

                $permission_query = tep_db_query("SELECT operations_id FROM " . TABLE_OPERATIONS);

                while ($results = tep_db_fetch_array($permission_query)) {

                    if ($_POST['p' . $results['operations_id']] != "") {
                        tep_db_query("INSERT INTO " . TABLE_MODULESOPERATIONS . " (modules_id,operations_id,user_id,roles_id) VALUES ('" . $_POST['modules_id'] . "','" . $results['operations_id'] . "','" . $_POST['user_id'] . "','" . $_POST['roles_id'] . "')");
                    }
                }

                getlables("218");
                echo informationUpdate("success", $lablearray['218'], "");
                break 2;

            case 'edit':

                switch ($_POST['panel']) {

                    case '1':
                        $moperations_query = tep_db_query("SELECT operations_id FROM " . TABLE_MODULESOPERATIONS . " modules_id='" . $_POST['id'] . "'");

                        if (tep_db_num_rows($moperations_query) > 0) {
                            while ($moperations = tep_db_fetch_array($moperations_query)) {
                                echo "document.getElementById('Operations" . $moperations['operations_id'] . "').checked=true;\n";
                            }
                        } else {

                            $moperations_query2 = tep_db_query("SELECT operations_id FROM " . TABLE_OPERATIONS);

                            while ($moperations = tep_db_fetch_array($moperations_query2)) {
                                echo "document.getElementById('Operations" . $moperations['operations_id'] . "').checked=false;\n";
                            }
                        }

                        break 2;

                    default:


                        $permissions_query = tep_db_query("SELECT p.modules_id,modules_code,modules_description FROM " . TABLE_MODULES . " p ," . TABLE_ROLESMODULES . " rp  WHERE p.modules_id=rp.modules_id and rp.roles_id='" . $_POST['id'] . "' GROUP BY p.modules_id");

                        if (tep_db_num_rows($permissions_query) == '0') {
                            getlables("606");
                            echo informationUpdate("fail", $lablearray['606'], "");
                            break 2;
                        }
                        
                        switch ($_SESSION['P_LANG']) {

                            case 'EN':
                                $roles_name = 'roles_name_eng';
                           
                                break;

                            case 'FR':
                                $roles_name = 'roles_name_fr';
                          
                                break;

                            case 'SWA':
                                $roles_name = 'roles_name_sa';
                        
                                break;

                            case 'JA':
                                $roles_name = 'roles_name_ja';
                              
                                break;

                            case 'SP':
                                $roles_name = 'roles_name_sp';
                              
                                break;

                            case 'LUG':
                                $roles_name = 'roles_name_lug';
                             
                                break;

                            default:
                                $roles_name = 'roles_name_eng';
                            
                                break;
                        }

                        $roles_query = tep_db_query("SELECT " . $roles_name . " as roles_name FROM " . TABLE_ROLES . "  WHERE roles_id='" . $_POST['id'] . "'");
                        $rolename = tep_db_fetch_array($roles_query);
                        getlables("592,604");
                        $checkbox = $lablearray['592'] . "<br><div class='scrollablecheckboxlist'>";
                        $checkbox = $checkbox . "<table cellpading='2' border='0' width='100%'>";

                        switch ($_SESSION['P_LANG']) {

                            case 'EN':

                                $operations_description_lang = 'operations_description_eng';

                                break;

                            case 'FR':

                                $operations_description_lang = 'operations_description_fr';

                                break;

                            case 'SWA':

                                $operations_description_lang = 'operations_description_sa';

                                break;

                            case 'JA':

                                $operations_description_lang = 'operations_description_ja';

                                break;

                            case 'SP':

                                $operations_description_lang = 'operations_description_sp';

                                break;

                            case 'LUG':

                                $operations_description_lang = 'operations_description_lug';

                                break;

                            default:

                                $operations_description_lang = 'operations_description_eng';

                                break;
                        }

                        while ($results = tep_db_fetch_array($permissions_query)) {

                            $operations_query = tep_db_query("SELECT (SELECT " . $operations_description_lang . " as operations_description FROM " . TABLE_OPERATIONS . " o  WHERE o.operations_id=mo.operations_id) As operations_name FROM " . TABLE_MODULESOPERATIONS . " mo  WHERE modules_id='" . $results['modules_id'] . "'");
                            //echo "SELECT (SELECT operations_description FROM ".TABLE_OPERATIONS." o  WHERE o.operations_id=mo.operations_id) As operations_name FROM " . TABLE_MODULESOPERATIONS." mo  WHERE modules_id='".$results['modules_id']."'";
                            while ($operations_array = tep_db_fetch_array($operations_query)) {
                                $operations = $operations . "<div class='operationdiv'>" . $operations_array['operations_name'] . "</div>";
                            }

                            $checkbox = $checkbox . "<tr><td nowrap ><input name='modules' type='radio' value='" . $results['modules_id'] . "' onClick='getRoleOperations(this.value)'/>" . $results['modules_description'] . "</td><td align='left' nowrap>" . $operations . "</td></tr>";
                            $operations = "";
                            $operations_array = array();
                        }

                        $checkbox = $checkbox . "</table>";


                        $checkbox = $checkbox . "</div>";


                        echo "document.getElementById('roles_id').value = '" . $_POST['id'] . "';\n";
                        echo "document.getElementById('roles').innerHTML = '" . $rolename['roles_name'] . "';\n";
                        echo "document.getElementById('action').value = 'update';\n";
                        echo "document.getElementById('modulescontent').innerHTML = \"" . $checkbox . "\";\n";

                        // operations 
                        $operations = $operations . "<div style='margin-left:50px;' >";
                        $operations = $operations . $lablearray['604'] . "<br><table cellpading='2' border='0' width='100%'>";

                        $operations_query = tep_db_query("SELECT  operations_id,operations_code," . $operations_description_lang . " as operations_description FROM " . TABLE_OPERATIONS);

                        while ($operations_array = tep_db_fetch_array($operations_query)) {
                            $operations = $operations . "<tr><td nowrap valign='top' ><input name='operations' id='Operations" . $operations_array['operations_id'] . "' type='checkbox' value='" . $operations_array['operations_id'] . "'/>&nbsp;&nbsp;&nbsp;" . $operations_array['operations_description'] . "</td></tr>";
                        }

                        $operations = $operations . "</table>";

                        $operations = $operations . "</div>";

                        echo "document.getElementById('operationscontent').innerHTML = \"" . $operations . "\";\n";
                        break 2;
                }


                break;

            default:
                $query = "SELECT  roles_id, " . $roles_name . " AS roles_name FROM " . TABLE_ROLES . " ORDER BY " . $roles_name . " ASC";
                break;
        }

        // this is a link for the grid
        $actionlinks = "<a href='#'  onClick=\"getFormData('frmid=" . $_POST['frmid'] . "','edit')\" title ='Edit'><img src='../images/edit.png' border='0'></a>";

        getlables("37");

        $_SESSION['reportname'] = $lablearray['37'];
        $_SESSION['reporttitle'] = $lablearray['37'];
        $_SESSION['downloadlist'] = $query;
        $fieldlist = array('roles_name');
        $keyfield = 'roles_id';
        $gridcolumnnames = array($lablearray['37']);
        break;

    case 'frmrolemodules':

        switch ($_POST['action']) {
            case 'add':
            case 'update':

                $permission = Common::convertobjectToArray($objects['permissions']);                
                $cashaccounts = Common::convertobjectToArray($objects['cashaccounts']);
                Common::replace_key_function($formdata, 'roles_id', 'ROLEID'); 
                Common::addKeyValueToArray($formdata, 'TABLE',TABLE_ROLESMODULES);
                
                foreach($permission as $key=>$val):                                         
                    Common::addKeyValueToArray($formdata, 'MID',$val);                
                    $form_data[]  = $formdata;                    
                endforeach;
                
                Common::addKeyValueToArray($formdata, 'TABLE',TABLE_ROLESCASHACCOUNTS);
                
                foreach($cashaccounts as $key=>$val):                                         
                    Common::addKeyValueToArray($formdata, 'GLACC',$val);                
                    $form_data[]  = $formdata;                    
                endforeach;
                           
                Bussiness::covertArrayToXML($form_data, true);
              //  $tabledata['xml_data'] = Common::$xml;                
                Bussiness::PrepareData(true);
   
                echo "1111111";

                break;

//            case 'edit':
//                $results_query = tep_db_query("SELECT roles_id, " . $roles_name . " AS roles_name FROM " . TABLE_ROLES . " WHERE roles_id='" . $_POST['id'] . "'");
//
//                $results = tep_db_fetch_array($results_query);
//
//                echo "formObj.roles_id.value = '" . $results['roles_id'] . "';\n";
//                echo "document.getElementById('roles_name').innerHTML = '" . $results['roles_name'] . "';\n";
//
//                $roles_query = tep_db_query("SELECT modules_id FROM " . TABLE_ROLESMODULES . " WHERE roles_id='" . $_POST['id'] . "'");
//
//                while ($results = tep_db_fetch_array($roles_query)) {
//
//                    if ($results['modules_id'] != "") {
//                        echo "formObj.Permissions" . $results['modules_id'] . ".checked =true;\n";
//                    }
//                }
//
//                $cashaccounts_query = tep_db_query("SELECT chartofaccounts_accountcode FROM " . TABLE_ROLESCASHACCOUNTS . " WHERE roles_id='" . $_POST['id'] . "'");
//
//                if (tep_db_num_rows($cashaccounts_query) > 1) {
//
//                    while ($cashaccounts = tep_db_fetch_array($cashaccounts_query)) {
//
//                        if ($cashaccounts['chartofaccounts_accountcode'] != "") {
//                            echo "formObj.cashaccounts" . $cashaccounts['chartofaccounts_accountcode'] . ".checked =true;\n";
//                        }
//                    }
//                } else {
//                    echo "formObj.cashaccounts.checked =true;\n";
//                }
//                break;

            default:

                break;
        }


        break;


    case 'frmuserroles':

        switch ($_POST['action']) {

            case 'update':
            case 'add':
                
                $roles = Common::convertobjectToArray($objects['roles']);
                if(count($roles)==0): 
                    
                     Common::getlables("1576", "", "", $Conn);
                     echo 'INFO.'.Common::$lablearray['1576'];                  
                              
                    exit();                
                endif;
                
                Common::addKeyValueToArray($formdata, 'TABLE',TABLE_USERROLES);
                Common::addKeyValueToArray($formdata, 'UID',$_POST['theid']);
                Common::addKeyValueToArray($formdata, 'ROLEID','');
                Common::replace_key_function($formdata, 'action', 'ACTION');
               
                
                foreach($roles as $key=>$roleid):                     
                    
                    Common::addKeyValueToArray($formdata, 'ROLEID',$roleid); 

                    $form_data[]  = $formdata;
                
                endforeach;
              
                Bussiness::covertArrayToXML($form_data, true);
               // $tabledata['xml_data'] = Common::$xml;                
                Bussiness::PrepareData(true);
          
                echo '1111111';
                
                break;
            default:
            
            break;
        }


     
        break;


    case 'frmroles':
        switch ($_POST['action']) {

            case 'add':
            case 'update':    
                // VALIDATIONS
                Common::getlables("572,576", "", "", $Conn);
                if($formdata['roles_name']==""):
                     echo 'ERR '.Common::$lablearray['572']; 
                     exit();
                endif;
                
                // CHECK ROLE
                $roles_results =  Bussiness::$Conn->SQLSelect("SELECT " . $roles_name . " AS roles_name FROM " . TABLE_ROLES . " WHERE  " . $roles_name . "='" . $formdata['roles_name'] . "'");
                
                if ($roles_results[0]['roles_name']!='') :
                   echo 'ERR '.Common::$lablearray['576']; 
                   exit();
                else:
                    // ADD ROLE           
                    Common::replace_key_function($formdata, 'action', 'ACTION'); 
                    Common::replace_key_function($formdata, 'roles_name', 'ROLE');
                    Common::replace_key_function($formdata, 'roles_id', 'ROLEID');
                    Common::addKeyValueToArray($formdata, 'TABLE',TABLE_ROLES); 
                    Common::addKeyValueToArray($formdata, 'LANG',$_SESSION['P_LANG']); 
                     $form_data[]= $formdata;                  
                    Bussiness::covertArrayToXML($form_data, true);
                           
                    Bussiness::PrepareData(true);
                
                endif;
                  
                echo '1111111';    
               
                break;

            case 'update':
                tep_db_query("UPDATE " . TABLE_ROLES . " SET " . $roles_name . "='" . tep_db_prepare_input($_POST['roles_name']) . "' WHERE roles_id='" . $_POST['roles_id'] . "'");
                getlables("576");
                echo informationUpdate('success', $lablearray['345']);
                break;

            case 'edit':
                $results_query = tep_db_query("SELECT  roles_id," . $roles_name . " AS roles_name FROM " . TABLE_ROLES . " WHERE roles_id='" . $_POST['id'] . "'");

                $results = tep_db_fetch_array($results_query);

                echo "formObj.roles_id.value = '" . $results['roles_id'] . "';\n";
                echo "formObj.roles_name.value = '" . $results['roles_name'] . "';\n";
                echo "formObj.action.value = 'update';";

                break;

            default:
                //$query = "SELECT  roles_id," . $roles_name . " As roles_name FROM " . TABLE_ROLES . " ORDER BY " . $roles_name . " ASC";
                break;
        }


        getlables("577,574,578");
        $_SESSION['reportname'] = $lablearray['574'];
        $_SESSION['reporttitle'] = $lablearray['577'];
        $_SESSION['downloadlist'] = $query;
        $fieldlist = array('roles_name');
        $keyfield = 'roles_id';
        $gridcolumnnames = array($lablearray['578']);
        break;


    case 'frmcashaccounts':
      //  $formdata = Common::decodeSerialisedPagedata($_POST['pageparams']);
        $cashaccountsTemplate = array_map('array_flip', array_values($Conn->preparefieldList('cashaccounts')));
        $cashaccounts_array = call_user_func_array('array_merge', $cashaccountsTemplate);

        $cashaccounts_array['cashaccounts_name'] = $formdata['cashaccounts_name'];
        $cashaccounts_array['chartofaccounts_accountcode'] = $formdata['chartofaccounts_accountcode'];
        $cashaccounts_array['currencies_id'] = $formdata['currencies_id'];
        $cashaccounts_array['branch_code'] = $formdata['branch_code'];
        
        switch ($_POST['action']) {

            case 'add':

                $query_results = $Conn->SQLSelect("SELECT cashaccounts_name FROM " . TABLE_CASHACCOUNTS . " WHERE  cashaccounts_name='" . $formdata['cashaccounts_name'] . "' limit 1");

                if (count($query_results) > 0) {
                    getlables("576");
                    echo 'MSG:' . $lablearray['576'];
                } else {
                    $Conn->SQLInsert(array('cashaccounts' => $cashaccounts_array), true);
                    echo '1111111';
                }
                break;
            case 'delete':
                $Conn->setAutoCommit();
                $Conn->ReferenceFieldList['cashaccounts'] = array('cashaccounts_id' => $formdata['cashaccounts_id']);
                $Conn->SQLDelete('cashaccounts');
                $Conn->setAutoCommit(true);
              //  $Conn->SQLInsert(array('cashaccounts' => $cashaccounts_array), true);
                echo '1111111';

                break;    
            case 'update':
                $Conn->setAutoCommit();
                $Conn->ReferenceFieldList['cashaccounts'] = array('cashaccounts_id' => $formdata['cashaccounts_id']);
                $Conn->SQLDelete('cashaccounts');
                $Conn->SQLInsert(array('cashaccounts' => $cashaccounts_array), true);
                echo '1111111';

                break;

            case 'edit':
                $results_query = tep_db_query("SELECT  c.flag,cc.currencies_id,c.flag,c.currencies_id,cc.cashaccounts_id, cc.cashaccounts_name,cc.chartofaccounts_accountcode FROM " . TABLE_CASHACCOUNTS . " cc LEFT JOIN " . TABLE_CURRENCIES . " c ON c.currencies_id=cc.currencies_id WHERE cashaccounts_id='" . $_POST['id'] . "'");

                $results = tep_db_fetch_array($results_query);
                echo "formObj.action.value = 'update';";
                echo "formObj.cashaccounts_id.value = '" . $results['cashaccounts_id'] . "';\n";
                echo "formObj.cashaccounts_name.value = '" . $results['cashaccounts_name'] . "';\n";
                echo "formObj.branch_code.value = '" . $results['branch_code'] . "';\n";
                echo "SelectItemInList(\"chartofaccounts_accountcode\",\"" . $results['chartofaccounts_accountcode'] . "\");\n";
                echo "SelectItemInList(\"currencies_id\",\"" . $results['currencies_id'] . "\");\n";

                break;

            default:
               // $query = "SELECT  cc.currencies_id,c.currencies_code,cashaccounts_id,cc.cashaccounts_name,cc.chartofaccounts_accountcode FROM " . TABLE_CASHACCOUNTS . " as cc LEFT JOIN  " . TABLE_CURRENCIES . " c ON c.currencies_id=cc.currencies_id ORDER BY cashaccounts_name ASC";
              //  break;
        }


          break;
    case 'frmbankaccounts':
        switch ($_POST['action']) {
            case 'load':
                echo DrawComboFromArray(array(), 'bankbranches_id', $_POST['id'], 'bankbranches');
                break;

            case 'add':
                $query_results = tep_db_query("SELECT  bankaccounts_id FROM " . TABLE_BANKACCOUNTS . " WHERE  bankaccounts_accno='" . $_POST['bankaccounts_accno'] . "'");

                if (tep_db_num_rows($query_results) > 0) {
                    echo informationUpdate('fail', 'Information already registered in system');
                } else {
                    tep_db_query("INSERT INTO " . TABLE_BANKACCOUNTS . " (bankaccounts_accno,bankbranches_id,chartofaccounts_accountcode) VALUES ('" . tep_db_prepare_input($_POST['bankaccounts_accno']) . "','" . tep_db_prepare_input($_POST['bankbranches_id']) . "','" . tep_db_prepare_input($_POST['chartofaccounts_accountcode']) . "')");
                    echo informationUpdate('add', '');
                }
                break;

            case 'update':
                tep_db_query("UPDATE " . TABLE_BANKACCOUNTS . " SET bankaccounts_accno='" . tep_db_prepare_input($_POST['bankaccounts_accno']) . "',bankbranches_id='" . $_POST['bankbranches_id'] . "',chartofaccounts_accountcode='" . $_POST['chartofaccounts_accountcode'] . "' WHERE bankaccounts_id='" . $_POST['bankaccounts_id'] . "'");
                echo informationUpdate('', '');
                break;

            case 'edit':
                $results_query = tep_db_query("SELECT  ba.bankaccounts_id,bb.banks_id,ba.chartofaccounts_accountcode,bb.bankbranches_id,bankaccounts_accno FROM " . TABLE_BANKACCOUNTS . " as ba, " . TABLE_BANKBRANCHES . " AS bb WHERE  ba.bankbranches_id=bb.bankbranches_id AND ba.bankaccounts_id='" . $_POST['id'] . "'");

                $results = tep_db_fetch_array($results_query);

                echo "document.getElementById(\"branchname\").innerHTML=\"" . DrawComboFromArray(array(), 'bankbranches_id', $results['banks_id'], 'bankbranches') . "\";\n";

                echo "SelectItemInList(\"banks_id\",\"" . $results['banks_id'] . "\");\n";

                echo "SelectItemInList(\"bankbranches_id\",\"" . $results['bankbranches_id'] . "\");\n";

                echo "formObj.bankaccounts_accno.value = '" . $results['bankaccounts_accno'] . "';\n";
                echo "SelectItemInList(\"chartofaccounts_accountcode\",\"" . $results['chartofaccounts_accountcode'] . "\");\n";
                echo "formObj.action.value = 'update';";
                break;

            default:
                $query = "SELECT  ba.bankaccounts_accno,ba.bankaccounts_id,b.banks_name FROM " . TABLE_BANKACCOUNTS . " as ba," . TABLE_BANKBRANCHES . " as bb, " . TABLE_BANKS . " as b WHERE b.banks_id=bb.banks_id AND ba.bankbranches_id=bb.bankbranches_id  GROUP BY bankaccounts_accno";

                break;
        }


//        //$_SESSION['banks_id'] = $_POST['id'];
//        $_SESSION['reportname'] = 'Bank Accounts';
//        $_SESSION['reporttitle'] = "List of Bank Accounts";
//        $_SESSION['downloadlist'] = $query;
//
//        $fieldlist = array('banks_name', 'bankaccounts_accno');
//        $keyfield = 'bankaccounts_id';
//        $gridcolumnnames = array('Branch Name', 'Account Number');
        break;

    case 'frmbankbranches':
        getlables('997,996,576,218,996');
        switch ($_POST['action']) {

            case 'add':
                $query_results = tep_db_query("SELECT  branch_code FROM " . TABLE_BANKBRANCHES . " WHERE  bankbranches_name='" . tep_db_prepare_input($_POST['bankbranches_name']) . "'  AND branch_code=' " . tep_db_prepare_input($_POST['branch_code']) . "'");

                if (tep_db_num_rows($query_results) > 0) {
                    echo informationUpdate('fail', $lablearray['994'] . "<br> " . tep_db_prepare_input($_POST['bankbranches_name']) . " " . tep_db_prepare_input($_POST['branch_code']));
                } else {
                    $query_results1 = tep_db_query("SELECT  (max(branch_code)+1)branch_code FROM " . TABLE_BANKBRANCHES . " WHERE  licence_build='" . tep_db_prepare_input($_POST['licence_build']) . "'");
                    $results = tep_db_fetch_array($query_results1);
                    tep_db_query("INSERT INTO " . TABLE_BANKBRANCHES . " (bankbranches_name,branch_code,licence_build,branch_code) VALUES ('" . tep_db_prepare_input($_POST['organisationname']) . "','" . tep_db_prepare_input($results['branch_code']) . "','" . tep_db_prepare_input($_POST['licence_build']) . "','" . tep_db_prepare_input($_POST['branch_code']) . "')");
                    echo informationUpdate('success', $lablearray['218']);
                }
                break;

            case 'edit':

                $results_query = tep_db_query("SELECT  bankbranches_id,bankbranches_name,branch_code,licence_build,branch_code FROM " . TABLE_BANKBRANCHES . " WHERE  bankbranches_id='" . tep_db_prepare_input($_POST['id']) . "'");

                $results = tep_db_fetch_array($results_query);

                echo "SelectItemInList(\"licence_build\",\"" . $results['licence_build'] . "\");\n";
                echo "formObj.bankbranches_id.value = '" . $results['bankbranches_id'] . "';\n";
                echo "formObj.organisationname.value = '" . $results['bankbranches_name'] . "';\n";
                echo "formObj.branch_code.value = '" . $results['branch_code'] . "';\n";
                echo "formObj.action.value = 'update';";

                break;

            case 'update':
                tep_db_query("UPDATE " . TABLE_BANKBRANCHES . " SET bankbranches_name='" . tep_db_prepare_input($_POST['organisationname']) . "',licence_build='" . tep_db_prepare_input($_POST['licence_build']) . "',branch_code='" . tep_db_prepare_input($_POST['branch_code']) . "' WHERE bankbranches_id='" . tep_db_prepare_input($_POST['bankbranches_id']) . "'");
                echo informationUpdate('success', $lablearray['218']);
                break;

            case 'delete':
                tep_db_query("DELETE FROM " . TABLE_BANKBRANCHES . " WHERE bankbranches_id='" . tep_db_prepare_input($_POST['id']) . "'");
                break;

            //case 'getinfo':
            //	$query = "SELECT  bankbranches_id, branch_code,bankbranches_name FROM ".TABLE_BANKBRANCHES." WHERE  licence_build='".tep_db_prepare_input($_POST['id'])."'";
            //	break;
            //case 'load':
            //	echo DrawComboFromArray(array(),'bankbranches_id',$_POST['id'],'bankbranches');
            //	break;

            case 'search': // search
                $query = "SELECT  bankbranches_id,branch_code,bankbranches_name,branch_code FROM " . TABLE_BANKBRANCHES . " WHERE licence_build='" . tep_db_prepare_input($_POST['id']) . "' ORDER BY branch_code DESC ";
                break;

            default:
//                if (isset($_POST['id'])) {
//                    $the_id = tep_db_prepare_input($_POST['id']);
//                } else {
//                    $the_id = $_SESSION['licence_build'];
//                }
//
//                $query = "SELECT bankbranches_id, branch_code,bankbranches_name,branch_code FROM " . TABLE_BANKBRANCHES . " WHERE licence_build='" . $the_id . "' ORDER BY branch_code DESC ";
                break;
        }

//        $_SESSION['banks_id'] = $_POST['id'];
//        $_SESSION['reportname'] = 'Bank Branches';
//        $_SESSION['reporttitle'] = "List of Bank Branches";
//        $_SESSION['downloadlist'] = $query;
//
//        $fieldlist = array('branch_code', 'bankbranches_name', 'branch_code');
//        $keyfield = 'bankbranches_id';
//        $gridcolumnnames = array($lablearray['996'], $lablearray['997'], '');

        break;

    case 'frmbanks':
        getlables("155,576,345,994,611,218");
        switch ($_POST['action']) {

            
            case 'add':
                $query_results = tep_db_query("SELECT  licence_build FROM " . TABLE_LICENCE . " WHERE  licence_organisationname='" . $_POST['licence_organisationname'] . "'");

                if (tep_db_num_rows($query_results) > 0) {
                    echo informationUpdate('fail', $lablearray['576']);
                } else {
                    $query_results = tep_db_query("SELECT  (max(licence_build)+1)licence_build FROM " . TABLE_LICENCE . " WHERE  licence_organisationname='" . $_POST['licence_organisationname'] . "'");
                    $results = tep_db_fetch_array($results_query);
                    tep_db_query("INSERT INTO " . TABLE_LICENCE . " (licence_organisationname,licence_address,licence_build) VALUES ('" . tep_db_prepare_input($_POST['organisationname']) . "','" . tep_db_prepare_input($_POST['licence_address']) . "','" . $results['licence_build'] . "')");
                    echo informationUpdate('success', $lablearray['345']);
                }
                break;

            case 'edit':
                $results_query = tep_db_query("SELECT  licence_build, licence_organisationname,licence_address FROM " . TABLE_LICENCE . " WHERE  licence_build='" . $_POST['id'] . "'");
                //echo "SELECT chartofaccounts_id,chartofaccounts_parent,chartofaccounts_name,chartofaccounts_header,chartofaccounts_id,chartofaccounts_description FROM ".TABLE_CHARTOFACCOUNTS." WHERE chartofaccounts_id='".$_POST['id']."'";
                $results = tep_db_fetch_array($results_query);
                echo "formObj.organisationname.value = '" . $results['licence_organisationname'] . "';\n";
                echo "formObj.licence_address.value = '" . $results['licence_address'] . "';\n";
                echo "formObj.licence_build.value = '" . $results['licence_build'] . "';\n";
                echo "formObj.action.value = 'update';";

                break;

            case 'update':
                tep_db_query("UPDATE " . TABLE_LICENCE . " SET licence_organisationname='" . tep_db_prepare_input($_POST['organisationname']) . "',licence_address='" . tep_db_prepare_input($_POST['licence_address']) . "' WHERE licence_build='" . tep_db_prepare_input($_POST['licence_build']) . "'");
                echo informationUpdate('', $lablearray['218'], "showResult('frmid=frmbanks','txtHint')");
                break;

            case 'delete':
                tep_db_query("DELETE FROM " . TABLE_LICENCE . " WHERE licence_build='" . $_POST['id'] . "'");
                break;
            default:
                $query = "SELECT licence_build,licence_address,licence_organisationname FROM " . TABLE_LICENCE . " ORDER BY licence_build DESC";
                break;
        }

//        $_SESSION['reportname'] = $lablearray['155'];
//        $_SESSION['reporttitle'] = $lablearray['155'];
//        $_SESSION['downloadlist'] = $query;
//        $fieldlist = array('licence_organisationname', 'licence_address');
//        $keyfield = 'licence_build';
//        $gridcolumnnames = array($lablearray['994'], $lablearray['611']);

        break;

    case 'frmcoa':

        switch ($_POST['action']) {

            case 'add':
            case 'update':
                // getlables("155,576,345,994,611,218");
                getlables("1574,439,1575");

                if($formdata['chartofaccounts_name']==''):
                   echo 'MSG.'.$lablearray['439'];
                   exit(); 
                endif;  

                if($formdata['chartofaccounts_accountcode']==''):
                   echo 'MSG.'.$lablearray['1575'];
                   exit(); 
                endif;
             
                $acc_array = $Conn->SQLSelect("SELECT  chartofaccounts_accountcode acc FROM " . TABLE_CHARTOFACCOUNTS . " WHERE  chartofaccounts_accountcode='" . $formdata['chartofaccounts_accountcode'] . "' OR chartofaccounts_name='" . $formdata['chartofaccounts_name'] . "'");
              
                if ($acc_array[0]['acc']!="") {
                    
                    echo 'MSG.'.Common::$lablearray['1574'];
                    exit();
                  
                } else {

                    if ($formdata['chartofaccounts_parent'] != "") {
                        $acc_array = $Conn->SQLSelect("SELECT  IF(chartofaccounts_parent=0,0,chartofaccounts_level) AS chartofaccounts_level,chartofaccounts_groupcode,chartofaccounts_bitem FROM " . TABLE_CHARTOFACCOUNTS . " WHERE  chartofaccounts_accountcode='" . $_POST['chartofaccounts_parent'] . "'");
                        $chartofaccounts_level = (int) $acc_array[0]['chartofaccounts_level'] + 1;
                        $chartofaccounts_groupcode = generategroupcode($acc_array[0]['chartofaccounts_groupcode'], $chartofaccounts_level);
                        $bitem =$acc_array[0]['chartofaccounts_bitem'];
                    } else {
                        $chartofaccounts_level = 0;
                    }
                    Bussiness::$Conn->setAutoCommit();                
                    Bussiness::$Conn->beginTransaction(); 
                    
                    Common::addKeyValueToArray($formdata, 'TABLE',TABLE_CHARTOFACCOUNTS);
                    Common::replace_key_function($formdata, 'chartofaccounts_name', 'NAME');                 
                    Common::addKeyValueToArray($formdata, 'LEVEL',$chartofaccounts_level );
                    Common::replace_key_function($formdata, 'chartofaccounts_parent', 'PARENT');
                    Common::replace_key_function($formdata, 'chartofaccounts_header', 'HEADER');
                    Common::replace_key_function($formdata, 'chartofaccounts_accountcode', 'GLACC');
                    Common::replace_key_function($formdata, 'chartofaccounts_tgroup', 'TGRP');                    
                    Common::addKeyValueToArray($formdata, 'GCODE',$chartofaccounts_groupcode );
                    Common::replace_key_function($formdata, 'currencies_id', 'CURRENCIES_ID');
                    Common::replace_key_function($formdata, 'chartofaccounts_revalue', 'RVAL');
                    Common::replace_key_function($formdata, 'chartofaccounts_description','DESC');
                    Common::addKeyValueToArray($formdata, 'id',$_POST['theid']);
                    Common::addKeyValueToArray($formdata, 'action',$formdata['action']);
                    Common::addKeyValueToArray($formdata, 'BITEM',$bitem);
                    
                    $form_data[]= $formdata;
                    Bussiness::covertArrayToXML($form_data, true);                
                    //$tabledata['xml_data'] = Common::$xml;
                    Bussiness::PrepareData(true);
                  //  Bussiness::$Conn->endTransaction();                
                    echo '1111111';
                  
                }

                break;
//            case 'update':
//
//                //$_results = tep_db_query("SELECT  forexrates_id FROM ".TABLE_GENERALLEDGER." g ".TABLE_FOREXRATES." f  WHERE  g.forexrates_id=f.forexrates_id AND f.currencies_code!='".$_POST['currencies_code']."' AND g.forexrates_id!='0'");
//                //if(tep_db_num_rows($_results) > 0){
//                //	$lables = getlables("437");
//                //	echo informationUpdate('update',$lables['671'],"");
//                //	break 2;
//                //}
//
//                if ($_POST['chartofaccounts_parent'] != "") {
//
//                    $parent_results = tep_db_query("SELECT  chartofaccounts_level FROM " . TABLE_CHARTOFACCOUNTS . " WHERE  chartofaccounts_accountcode='" . $_POST['chartofaccounts_parent'] . "'");
//
//                    $parent = tep_db_fetch_array($parent_results);
//
//                    $chartofaccounts_level = $parent['chartofaccounts_level'] + 1;
//                } else {
//                    $chartofaccounts_level = 0;
//                }
//
//
//                // check see if this account has transaction of a different currency
//                // check see if this account is revaluable - to be implimnted
//                // before we make an update let check and see if this account has children, and that will help us know what to update
//                $accounts_results = tep_db_query("SELECT chartofaccounts_id  FROM " . TABLE_CHARTOFACCOUNTS . " WHERE chartofaccounts_accountcode='" . $_POST['chartofaccounts_oldaccountcode'] . "' AND chartofaccounts_parent='0'");
//
//
//                if (tep_db_num_rows($accounts_results) > 0) {
//                    tep_db_query("UPDATE " . TABLE_CHARTOFACCOUNTS . " SET chartofaccounts_name='" . $_POST['chartofaccounts_name'] . "',chartofaccounts_header='Y', chartofaccounts_accountcode='" . $_POST['chartofaccounts_accountcode'] . "',chartofaccounts_revalue='" . $_POST['chartofaccounts_revalue'] . "',currencies_id='" . $_POST['currencies_id'] . "' WHERE chartofaccounts_accountcode='" . $_POST['ccurrent_selected_acc'] . "'");
//                    tep_db_query("UPDATE " . TABLE_CHARTOFACCOUNTS . " SET chartofaccounts_parent='" . $_POST['chartofaccounts_accountcode'] . "',chartofaccounts_revalue='" . $_POST['chartofaccounts_revalue'] . "',currencies_id='" . $_POST['currencies_id'] . "' WHERE chartofaccounts_parent='" . $_POST['ccurrent_selected_acc'] . "'");
//                } else {
//                    tep_db_query("UPDATE " . TABLE_CHARTOFACCOUNTS . " SET chartofaccounts_name='" . $_POST['chartofaccounts_name'] . "',chartofaccounts_level='" . $chartofaccounts_level . "', chartofaccounts_parent='" . $_POST['chartofaccounts_parent'] . "', chartofaccounts_header='" . $_POST['chartofaccounts_header'] . "', chartofaccounts_accountcode='" . $_POST['chartofaccounts_accountcode'] . "',chartofaccounts_tgroup='" . $_POST['chartofaccounts_tgroup'] . "',chartofaccounts_revalue='" . $_POST['chartofaccounts_revalue'] . "',currencies_id='" . $_POST['currencies_id'] . "' WHERE chartofaccounts_accountcode='" . $_POST['ccurrent_selected_acc'] . "'");
//                }
//
//                //tep_db_query("UPDATE ".TABLE_CHARTOFACCOUNTS." SET chartofaccounts_name='".$_POST['chartofaccounts_name']."',chartofaccounts_level='".$chartofaccounts_level."', chartofaccounts_parent='".$_POST['chartofaccounts_parent']."', chartofaccounts_header='".$_POST['chartofaccounts_header']."', chartofaccounts_accountcode='".$_POST['chartofaccounts_accountcode']."',chartofaccounts_tgroup='".$_POST['chartofaccounts_tgroup']."' WHERE chartofaccounts_accountcode='".$_POST['ccurrent_selected_acc']."'");
//                $lables = getlables("437");
//                echo informationUpdate('update', $lables['437'], "showResult('frmid=frmcoa','txtHint')");
//
//                break;

            case 'delete':
                Common::getlables("1574,439,1575", "", "", Common::$connObj);

                // if this is a header account with children
                $acc_array = $Conn->SQLSelect("SELECT COUNT(chartofaccounts_parent) as num FROM " . TABLE_CHARTOFACCOUNTS . " WHERE  chartofaccounts_parent='" . $formdata['ccurrent_selected_acc'] . "'");

               // $chartofaccounts_num = tep_db_fetch_array($results_query);

                if ($acc_array[0]['num'] >0) {
                     echo 'MSG.Sorry you can not delete this account. The account has sub-accounts';
                   
                } else {

//                    // check closed periods
//                    $closed_array = $Conn->SQLSelect("SELECT closedperiod_year FROM ' . TABLE_CLOSEDPERIODS . ' ORDER BY closedperiod_year ASC");
//                    $cMsg = "";
//                    while ($closed = tep_db_fetch_array($results_query)) {
//
//                        // check see if there are transaction posted to this account
//                        $closed_query = tep_db_fetch_array('SELECT closedperiod_year FROM YR' . $closed['closedperiod_year'] . ' WHERE chartofaccounts_accountcode=' . $_POST['id'] . "'");
//
//                        if (tep_db_num_rows($closed_query) > 0) {
//
//                            $cMsg = "Account " . $_POST['id'] . " has " . tep_db_num_rows($closed_query) . " transactions posted to it in the year " . $closed['closedperiod_year'];
//                            echo informationUpdate('', $cMsg, "showResult('frmid=frmcoa','txtHint')");
//                            break;
//                        }
//                    }
//
//                    $results_query = tep_db_query("SELECT COUNT(tcode) as num  FROM " . TABLE_GENERALLEDGER . " WHERE  chartofaccounts_accountcode='" . $_POST['id'] . "'");
//
//                    $p_num = tep_db_fetch_array($results_query);
//
//                    if ($p_num['num'] > 0) {
//                        echo informationUpdate('failure', 'Sorry, you can not delete account.<br> Account has ' . $p_num['num'] . ' Transactions posted on it.', 'messageStackError');
//                    } else {
                        tep_db_query("DELETE FROM " . TABLE_CHARTOFACCOUNTS . " WHERE chartofaccounts_accountcode='" . $formdata['ccurrent_selected_acc'] . "'");
                        
                        echo '1111111';
                        // echo informationUpdate('', 'Information has been successfully deleted', "showResult('frmid=frmcoa','txtHint')");
                   // }
                }
                break;
            default:
                if ($_POST['action'] == '') {
                    echo DrawCoA();
                }
                break;
        }


        break;

  


    case 'frmlanguages': // Care programmes
        //$actionlinks ="<a href='#'  onClick=\"getFormData('frmid=".$_POST['frmid']."','edit','".$_POST['frmid']."')\"><img src='../images/edit.png' border='0'></a><a href='#'  onClick=\"if(checkForSelectedCheckbox()){if(confirm('Are you sure you want to delete this Item?')){getFormData('frmid=".$_POST['frmid']."','delete','".$_POST['frmid']."')}}\" title ='Delete'><img src='../images/delete.png' border='0'></a>";

        switch ($_POST['action']) {

            case 'edit': // edit

                $query_results = tep_db_query("SELECT translations_id,translations_ja,translations_eng,translations_fr,translations_sp,translations_swa,translations_lug FROM " . TABLE_TRANSLATIONS . " WHERE translations_id='" . $_POST['id'] . "'");

                // concatenate string to populate form
                $translation = tep_db_fetch_array($query_results);

                echo "formObj.translations_eng.value = '" . $translation["translations_eng"] . "';\n";
                echo "formObj.translations_fr.value = '" . $translation["translations_fr"] . "';\n";
                echo "formObj.translations_sp.value = '" . $translation["translations_sp"] . "';\n";
                echo "formObj.translations_swa.value = '" . $translation["translations_swa"] . "';\n";
                echo "formObj.translations_lug.value = '" . $translation["translations_lug"] . "';\n";
                echo "formObj.translations_ja.value = '" . $translation["translations_ja"] . "';\n";
                echo "formObj.translations_id.value = '" . $translation["translations_id"] . "';\n";
                echo "formObj.action.value ='update';\n";

                tep_db_free_result($query_results);
                break;

            case 'update':  // Update
                getlables("345");
                tep_db_query("UPDATE " . TABLE_TRANSLATIONS . " SET translations_eng='" . tep_db_prepare_input($_POST["translations_eng"]) . "',translations_fr='" . tep_db_prepare_input($_POST["translations_fr"]) . "',translations_sp='" . tep_db_prepare_input($_POST["translations_sp"]) . "',translations_swa='" . tep_db_prepare_input($_POST["translations_swa"]) . "',translations_ja='" . $_POST["translations_ja"] . "' WHERE translations_id='" . $_POST["translations_id"] . "'");
               switch ($_SESSION['P_LANG']) {

                    case 'EN':

                        $Language = 'translations_eng';
                        break;

                    case 'FR':

                        $Language = 'translations_fr';
                        break;

                    case 'SWA':

                        $Language = 'translations_sa';
                        break;

                    case 'JA':

                        $Language = 'translations_ja';
                        break;

                    case 'SP':
                        $Language = 'translations_sp';
                        break;

                    case 'LUG':

                        $Language = 'translations_lug';
                        break;

                    default:

                        $Language = 'translations_eng';
                        break;
                }
                $query = "SELECT * FROM " . TABLE_TRANSLATIONS . "  WHERE  " . $Language . " LIKE '%" . $_POST["searchterm"] . "%' ORDER BY " . $Language . " ASC";
                break;

            default:  // no action specified
                $query = "SELECT * FROM " . TABLE_TRANSLATIONS . " ORDER BY translations_id DESC";
                break;
        }

        getlables("630,631,632,633,634,635");

        $_SESSION['reportname'] = $lablearray['218'];

        $_SESSION['reporttitle'] = $lablearray['218'];

        $_SESSION['downloadlist'] = $query;

        $fieldlist = array("translations_eng");
        $keyfield = 'translations_id';
        $gridcolumnnames = array($lablearray['630']);

        break;
}
// check see if we have a query to execute
if (isset($query)) {

    //getlables("32,33,34");

    $newgrid->lablesarray = $lables_array;

    echo $newgrid->getdata($query, $fieldlist, $keyfield, $gridcolumnnames, $frmid, $actionlinks, $actionlinks2, $onclick, $Uinter, $chkname, $defaultsortfield, $gridsorton, $addfilelinks);
}?>