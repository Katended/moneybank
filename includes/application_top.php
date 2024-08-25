<?php
if (!isset($_SESSION)) {  
    session_start();
    ob_start();	 
}

ini_set('max_execution_time', 6000); 

// Set the local configuration parameters - mainly for developers
//  if (file_exists('includes/local/configure.php')) include('includes/local/configure.php');
define('PAGE_PARSE_START_TIME', microtime());

// Set the level of error reporting
// 
 error_reporting(E_ERROR);
//error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED);

require_once('configure.php');

// Define the project version
define('PROJECT_VERSION', 'Moneybank');

	 
// set php_self in the local scope
  $PHP_SELF = (isset($_SERVER['PHP_SELF']) ? $_SERVER['PHP_SELF'] : $_SERVER['SCRIPT_NAME']);

// include the list of project database tables
  require( 'database_tables.php');

// customization for the design layout
//  define('BOX_WIDTH', 125); // how wide the boxes should be in pixels (default: 125)

// Define how do we update currency exchange rates
// Possible values are 'oanda' 'xe' or ''
define('CURRENCY_SERVER_PRIMARY', 'oanda');
define('CURRENCY_SERVER_BACKUP', 'xe');

// include the database functions
  require_once(DIR_WS_FUNCTIONS . 'database.php');

  global $link;

  require(DIR_WS_CLASSES . 'connectionfactory.php');
   
// make a connection to the database... now
  $link = tep_db_connect() or die('ERR Sorry, we unable to connect to the database server!');

// set general application wide parameters
  $configuration_query = tep_db_query('select configuration_key as cfgKey, configuration_value as cfgValue from ' . TABLE_CONFIGURATION);
  while ($configuration = tep_db_fetch_array($configuration_query)) {
   
    if (!defined($configuration['cfgKey'])) {
                define($configuration['cfgKey'], $configuration['cfgValue']);
	   }
	
  }

  // set Accounts application wide parameters
  /*$configuration_query = tep_db_query('select accountsconfig_key as cfgKey, accountsconfig_value as cfgValue from ' . TABLE_ACCOUNTSCONFIG);
  while ($configuration = tep_db_fetch_array($configuration_query)) {
    define($configuration['cfgKey'], $configuration['cfgValue']);
  }*/


// define our general functions used application-wide
  require(DIR_WS_FUNCTIONS . 'general.php');
  
  
  require(DIR_WS_FUNCTIONS . 'commonfunctions.php');

// initialize the logger class
  require(DIR_WS_CLASSES . 'logger.php');


// define how the session functions will be used
 require(DIR_WS_FUNCTIONS . 'sessions.php');

    
 require('filenames.php');
 
// set the session cookie parameters
   if (function_exists('session_set_cookie_params')) {

    session_set_cookie_params(0, DIR_WS_ADMIN);
  } elseif (function_exists('ini_set')) {
    ini_set('session.cookie_lifetime', '0');
    ini_set('session.cookie_path', DIR_WS_ADMIN);
  }
// include(DIR_WS_LANGUAGES.'english/pdfeng.php');

// define our localization functions
  require(DIR_WS_FUNCTIONS . 'localization.php');

// Include validation functions (right now only email address)
  require(DIR_WS_FUNCTIONS . 'validations.php');


// entry/item info classes
  //require(DIR_WS_CLASSES . 'object_info.php');

// email classes
  require(DIR_WS_CLASSES . 'mime.php');
  
 // require(DIR_WS_CLASSES . 'email.php');

// file uploading class
 // require(DIR_WS_CLASSES . 'upload.php');

// calculate category path
  if (isset($_GET['cPath'])) {
    $cPath = $_GET['cPath'];
  } else {
    $cPath = '';
  }

  if (tep_not_null($cPath)) {
    $cPath_array = tep_parse_category_path($cPath);
    $cPath = implode('_', $cPath_array);
    $current_category_id = $cPath_array[(sizeof($cPath_array)-1)];
  } else {
    $current_category_id = 0;
  }

// default open navigation box
//  if (!tep_session_is_registered('selected_box')) {
//    tep_session_register('selected_box');
//    $selected_box = 'configuration';
//  }

  if (isset($_GET['selected_box'])) {
    $selected_box = $_GET['selected_box'];
  }
  require('classes/DataAccess.php');

  global  $oDataAccess;
 
  
 $oDataAccess = new TheBursarDataAccess();
  
 require(DIR_WS_FUNCTIONS . 'whos_online.php');
 
 if(basename($_SERVER['PHP_SELF'])!="addedit.php" && basename($_SERVER['PHP_SELF'])!="remote.php" && basename($_SERVER['PHP_SELF'])!="index.php" && basename($_SERVER['PHP_SELF'])!="downloadlistpdf.php"){
 	tep_update_whos_online();
 }
 // check see if we are not on the index page and not addedit
 if (basename($_SERVER['PHP_SELF']) != "index.php" && basename($_SERVER['PHP_SELF']) != "addedit.php") {

    if (AuthenticateAccess('LOGIN') == 0) {
        tep_redirect(FILENAME_LOGIN);
    }

    // has user logged on
    if (AuthenticateAccess('LOGIN') == 0) {
        
    } else {

        define('FILENAME_PAGE', 'dashboard.php');

        switch (basename(__FILE__)) {

            case "managebankbranches.php":
                if (AuthenticateAccess('SETT') == 0) {
                    tep_redirect(tep_href_link(FILENAME_PAGE));
                }
                $_SESSION['modules_id'] = 19;
                break;

            case "managecashaccounts.php":
                if (AuthenticateAccess('SETT') == 0) {
                    tep_redirect(tep_href_link(FILENAME_PAGE));
                }
                $_SESSION['modules_id'] = 19;
                break;

            case "settingsgeneral.php":
                if (AuthenticateAccess('SETT') == 0) {
                    tep_redirect(tep_href_link(FILENAME_PAGE));
                }
                $_SESSION['modules_id'] = 19;
                break;

            case "dashboard.php":
                if (AuthenticateAccess('LOGIN') == 0) {
                    // tep_redirect(tep_href_link(FILENAME_LOGIN));
                }
                $_SESSION['modules_id'] = '';
                break;

            case "adduser.php":
                if (AuthenticateAccess('SETT') == 0) {
                    tep_redirect(tep_href_link(FILENAME_PAGE));
                }
                $_SESSION['modules_id'] = 19;
                break;

            case "manageusers.php":
                if (AuthenticateAccess('SETT') == 0) {
                    tep_redirect(tep_href_link(FILENAME_PAGE));
                }
                $_SESSION['modules_id'] = 19;
                break;

            case "makepayment.php":
                if (AuthenticateAccess('ACCN') == 0) {
                    tep_redirect(tep_href_link(FILENAME_PAGE));
                }
                $_SESSION['modules_id'] = 1;
                break;

            case "importpayment.php":
                if (AuthenticateAccess('ACCN') == 0) {
                    tep_redirect(tep_href_link(FILENAME_PAGE));
                }
                $_SESSION['modules_id'] = 1;
                break;



            case "managetransactions.php":
                if (AuthenticateAccess('ACCN') == 0) {
                    tep_redirect(tep_href_link(FILENAME_PAGE));
                }
                $_SESSION['modules_id'] = 1;
                break;

            case "cashflow.php":
                if (AuthenticateAccess('ACCN') == 0) {
                    tep_redirect(tep_href_link(FILENAME_PAGE));
                }
                $_SESSION['modules_id'] = 1;
                break;

            case "provisionforbadloans.php":
                if (AuthenticateAccess('ACCN') == 0) {
                    tep_redirect(tep_href_link(FILENAME_PAGE));
                }
                $_SESSION['modules_id'] = 1;
                break;

            case "trialbal.php":
                if (AuthenticateAccess('ACCN') == 0) {
                    tep_redirect(tep_href_link(FILENAME_PAGE));
                }
                $_SESSION['modules_id'] = 1;
                break;

            case "balancesheet.php":
                if (AuthenticateAccess('ACCN') == 0) {
                    tep_redirect(tep_href_link(FILENAME_PAGE));
                }
                $_SESSION['modules_id'] = 1;
                break;

            case "incomeExp.php":
                if (AuthenticateAccess('ACCN') == 0) {
                    tep_redirect(tep_href_link(FILENAME_PAGE));
                }
                $_SESSION['modules_id'] = 1;
                break;

            case "importOpeningBals.php":
                if (AuthenticateAccess('ACCN') == 0) {
                    tep_redirect(tep_href_link(FILENAME_PAGE));
                }
                $_SESSION['modules_id'] = 1;
                break;

            case "cashitems.php":
                if (AuthenticateAccess('ACCN') == 0) {
                    tep_redirect(tep_href_link(FILENAME_PAGE));
                }
                $_SESSION['modules_id'] = 1;
                break;

            case "cashentries.php":
                if (AuthenticateAccess('ACCN') == 0) {
                    tep_redirect(tep_href_link(FILENAME_PAGE));
                }
                $_SESSION['modules_id'] = 1;
                break;

            case "cashtransactions.php":
                if (AuthenticateAccess('ACCN') == 0) {
                    tep_redirect(tep_href_link(FILENAME_PAGE));
                }
                $_SESSION['modules_id'] = 1;
                break;



            case "configuretaxrates.php":
                if (AuthenticateAccess('SETT') == 0) {
                    tep_redirect(tep_href_link(FILENAME_PAGE));
                }
                $_SESSION['modules_id'] = 1;
                break;

            case "reconciliationreport.php":
                if (AuthenticateAccess('ACCN') == 0) {
                    tep_redirect(tep_href_link(FILENAME_PAGE));
                }
                $_SESSION['modules_id'] = 1;
                break;

            case "managecoa.php":
                if (AuthenticateAccess('ACCN') == 0) {
                    tep_redirect(tep_href_link(FILENAME_PAGE));
                }
                $_SESSION['modules_id'] = 1;
                break;

            case "revenuereport.php":
                if (AuthenticateAccess('TRAN') == 0) {
                    tep_redirect(tep_href_link(FILENAME_PAGE));
                }
                $_SESSION['modules_id'] = 1;
                break;

            case "closeperiod.php":
                if (AuthenticateAccess('TRAN') == 0) {
                    tep_redirect(tep_href_link(FILENAME_PAGE));
                }
                $_SESSION['modules_id'] = 1;
                break;

            case "breakdown.php":
                if (AuthenticateAccess('LOGIN') == 0) {
                    tep_redirect(tep_href_link(FILENAME_PAGE));
                }
                $_SESSION['modules_id'] = 1;
                break;




            case "managerolepermissions.php":
                if (AuthenticateAccess('SETT') == 0) {
                    tep_redirect(tep_href_link(FILENAME_PAGE));
                }
                $_SESSION['modules_id'] = 19;
                break;

            case "manageuserroles.php":
                if (AuthenticateAccess('SETT') == 0) {
                    tep_redirect(tep_href_link(FILENAME_PAGE));
                }
                $_SESSION['modules_id'] = 19;
                break;

            case "roleoperations.php":
                if (AuthenticateAccess('SETT') == 0) {
                    tep_redirect(tep_href_link(FILENAME_PAGE));
                }
                $_SESSION['modules_id'] = 19;
                break;

            case "manageroles.php":
                // has user logged on
                if (AuthenticateAccess('SETT') == 0) {
                    tep_redirect(tep_href_link(FILENAME_PAGE));
                }
                $_SESSION['modules_id'] = 19;
                break;

            default:
                break;
        }
    }
}
//ob_end_flush();
if( !isset($_SESSION['last_access']) || (time() - $_SESSION['last_access']) > 60 ) 
$_SESSION['last_access'] = time();
if(!defined("P_LANG")){
define("P_LANG",'EN');

}
//getBranchCodeList();

//if ($_SERVER['REMOTE_ADDR']=='192.168.0.23' || $_SERVER['REMOTE_ADDR']=='127.0.0.1'):
//    //header("Location: https://www.google.com"); /* Redirect browser */
//    //exit();
//else:
//   // header("Location: https://www.google.com"); /* Redirect browser */
//   // exit();
//endif;
require_once('classes/common.php');
require_once('classes/bussiness.php');
global $Conn;
if(is_null($Conn)){     
   $Conn = ConnectionFactory::getInstance();
   Common::$connObj = $Conn; 
}?>