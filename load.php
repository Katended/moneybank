<?php
require_once('includes/application_top.php');
require_once('includes/classes/GibberishAES.php');
require_once('includes/classes/productconfig.php');
require_once('includes/classes/common.php');
require_once('includes/classes/financial_class.php');

$newgrid = new Grid;

$newgrid->Conn = $Conn;
Common::$connObj = &$Conn;
Bussiness::$Conn = $Conn;
$newgrid->queryoptions = array();
$newgrid->queryoptions['container'] = '';
$frmid = filter_input(INPUT_POST, 'frmid');
$pageparams ='';
$action ='';
$main_array = array();

$newgrid->queryoptions['searchterm'] = isset($_GET['searchterm'])?$_GET['searchterm']:"";

if (isset($_GET['page'])) {

    $newgrid->queryoptions['setnumber'] = $_GET['setnumber'];
    $newgrid->queryoptions['start'] = $_GET['start'];
    $newgrid->queryoptions['page'] = $_GET['page'];
    $newgrid->queryoptions['sortorder'] = 'ASC';
    $newgrid->queryoptions['container'] = $_GET['container'];
    $newgrid->queryoptions['frmid'] = $_GET['frmid'];
    $newgrid->queryoptions['searchterm'] = $_GET['searchterm'];

} else {

    $newgrid->queryoptions['frmid'] = $_POST['frmid'];
    $newgrid->queryoptions['setnumber'] = 1;
    $newgrid->queryoptions['start'] = 0;
    $newgrid->queryoptions['page'] = 1;
    $newgrid->queryoptions['sortorder'] = 'ASC';
    $newgrid->queryoptions['container'] = isset($_POST['keyparam'])?$_POST['keyparam']:'';
    $newgrid->queryoptions['searchterm'] = isset($_POST['searchterm'])?$_POST['searchterm']:'';
}

// this function is used to convert a JAson Object to an array
//function objectToArray($d) {
//    if (is_object($d)) {
//        // Gets the properties of the given object
//        // with get_object_vars function
//        $d = get_object_vars($d);
//    }
//
//    if (is_array($d)) {
//        /*
//         * Return array converted to object
//         * Using __FUNCTION__ (Magic constant)
//         * for recursive call
//         */
//        return array_map(__FUNCTION__, $d);
//    } else {
//        // Return array
//        return $d;
//    }
//}
// this functin is used to flatten an array
//function array_flatten($array) {
//    $result = array();
//    foreach ($array as $key => $value) {
//
//        $result[$value['name']] = $value['value'];
//    }
//    return $result;
//}
spl_autoload_register(function ($class_name) {
    include 'includes/classes/' . $class_name . '.php';
});

$cWhere = "";

$elementID = '';

$newgrid->formid = $_POST['frmid'];

$newgrid->listURL = 'load.php?';

if(isset($_POST['pagevars'])):
    $formdata = Common::array_flatten($_POST['pagevars']);
endif;


if (isset($_GET['page'])) {
    if ($_GET['page'] >= 0) {
        $newgrid->queryoptions['pageparams'] = $_GET['pageparams']??'';
        $pageparams = $_GET['pageparams']??'';
    }
}

switch ($_GET['act']??'') {

    case 'ADD':
        // $actionlinks = "<a href='#'  onClick=\"getinfo('".$_POST['frmid'].",$( 'body').data( 'gridchk'),'add','','adedit.php')\" title ='".$grid_lables_lablearray['307']."'><img src='images/plus.gif' border='0'></a>";           
        break;

    case 'EXT':

        switch ($_POST['frmid']) {

            case 'frmSave':

                $newgrid->addcheckbox = false;

                $newgrid->extraFields[0] = "Amount";
                //$newgrid->subRows = true;
                $newgrid->cpara = "TRANSFERSAVINGS";

                if (isset($_GET['searchterm'])) {
                    $cWhere = " AND sa.savaccounts_account !='" . $_GET['acc'] . "'"; //  sa.product_prodid !='".$_GET['product_prodid']."') ";
                }

                $elementID = 'client_idnoto';

            default:

                break;
        }

        break;
    default:

        break;
}

// SEARCH
if ($_POST['action'] == 'search') {

    if ($pageparams == '') {
        $pageparams = Common::tep_db_prepare_input($_POST['pageparams']);
        $newgrid->queryoptions['pageparams'] = $pageparams;
    }
   
   
    // prepare links
    $actionlinks = Common::prepareLinks($_POST['frmid'], $action, $pageparams);

    // $newgrid->queryoptions['pageparams'] = $pageparams;
    // select client type
    switch ($pageparams) {
        case 'IND':
        case 'GIND':
        case 'INDSAVACC':
        case 'INDREPAYLOAN':
        case 'ADDINDLOANS':
        case 'INDLOANS':
        case 'INDLOANSREP':
            $clienttype = "client_type = 'I'";
            break;

        case 'GRP':
        case 'GGRP':
        case 'GRPSAVACC':
        case 'ADDGRPLOANS':
        case 'GRPLOANSREP':
            $clienttype = "client_type = 'G'";
            break;

        case 'BUSS':
        case 'BUSSAVACC':
        case 'ADDBUSLOANS':
        case 'BUSLOANSREP':
            $clienttype = "client_type = 'B'";
            break;

        default:
            $clienttype = "client_type LIKE  '%%'";
            break;
    }

    switch ($pageparams) {
        case 'IND':
        case 'GIND':
        case 'INDSAVACC':
        case 'INDREPAYLOAN':
        case 'ADDINDLOANS':
        case 'INDLOANS':
        case 'GRP':
        case 'GGRP':
        case 'GRPSAVACC':
        case 'MEMSAVACC':
        case 'ADDGRPLOANS':
        case 'BUSS':
        case 'BUSSAVACC':
        case 'ADDBUSLOANS':
        case 'INDLOANSREP':
        case 'GRPLOANSREP':
        case 'BUSLOANSREP':
            
            // determine if user has used a search term           
            $searchterm =  (isset($_POST['searchterm'])?$_POST['searchterm']:$_POST['search']['value']??'');
           


                $sanitizedSearchTerm = htmlspecialchars($searchterm, ENT_QUOTES);

                $newgrid->queryoptions['searchterm'] = htmlspecialchars($searchterm, ENT_QUOTES);

               

                switch ($pageparams) {
                    case 'MEMSAVACC':
                        $conditions = [
                            sprintf("c.entity_idno LIKE '%%%s%%'", $sanitizedSearchTerm),
                            sprintf("sa.product_prodid LIKE '%%%s%%'", $sanitizedSearchTerm)
                        ];
                        break;
                
                    case 'MEMSAVACC':
                        $conditions = [
                            sprintf("c.entity_name LIKE '%%%s%%'", $sanitizedSearchTerm),
                            sprintf("c.entity_idno LIKE '%%%s%%'", $sanitizedSearchTerm),
                            sprintf("sa.product_prodid LIKE '%%%s%%'", $sanitizedSearchTerm)
                        ];
                        break;
                
                    case 'ADDINDLOANS':
                    case 'IND':
                        $conditions = [
                            sprintf("c.client_firstname LIKE '%%%s%%'", $sanitizedSearchTerm),
                            sprintf("c.client_surname LIKE '%%%s%%'", $sanitizedSearchTerm),
                            sprintf("c.client_idno LIKE '%%%s%%'", $sanitizedSearchTerm),
                            sprintf("c.client_middlename LIKE '%%%s%%'", $sanitizedSearchTerm)
                        ];
                        break;
                
                    case 'ADDGRPLOANS':
                    case 'GRPLOANSREP':
                    case 'GRP':
                        $conditions = [
                            sprintf("c.entity_name LIKE '%%%s%%'", $sanitizedSearchTerm),
                            sprintf("c.entity_idno LIKE '%%%s%%'", $sanitizedSearchTerm)
                        ];
                        break;
                
                    default:
                        $conditions = [
                            sprintf("c.client_firstname LIKE '%%%s%%'", $sanitizedSearchTerm),
                            sprintf("c.client_surname LIKE '%%%s%%'", $sanitizedSearchTerm),
                            sprintf("c.client_idno LIKE '%%%s%%'", $sanitizedSearchTerm),
                            sprintf("c.client_middlename LIKE '%%%s%%'", $sanitizedSearchTerm)
                        ];
                        break;
                }
                
                $cWhere .= " AND (" . implode(' OR ', $conditions) . ")";
                // endif;
          //  }

            if ($newgrid->cpara != 'TRANSFERSAVINGS'):
                $newgrid->cpara = $pageparams;
            endif;

            break;
    }
    
    $languageMap = [
        'EN' => [
            'roles_name' => 'roles_name_eng',
            'charges_name_fieldname' => 'charges_name_en',
            'operations_description_lang' => 'operations_description_eng',
            'Language' => 'translations_eng',
        ],
        'FR' => [
            'roles_name' => 'roles_name_fr',
            'charges_name_fieldname' => 'charges_name_fr',
            'operations_description_lang' => 'operations_description_fr',
            'Language' => 'translations_fr',
        ],
        'SWA' => [
            'roles_name' => 'roles_name_sa',
            'charges_name_fieldname' => 'charges_name_sa',
            'operations_description_lang' => 'operations_description_sa',
            'Language' => 'translations_sa',
        ],
        'JA' => [
            'roles_name' => 'roles_name_ja',
            'charges_name_fieldname' => 'charges_name_ja',
            'operations_description_lang' => 'operations_description_ja',
            'Language' => 'translations_ja',
        ],
        'SP' => [
            'roles_name' => 'roles_name_sp',
            'charges_name_fieldname' => 'charges_name_sp',
            'operations_description_lang' => 'operations_description_sp',
            'Language' => null, // Assuming no translation for SP
        ],
        'LUG' => [
            'roles_name' => 'roles_name_lug',
            'charges_name_fieldname' => 'charges_name_lug',
            'operations_description_lang' => 'operations_description_lug',
            'Language' => 'translations_lug',
        ],
    ];
    
    $lang = $_SESSION['P_LANG'] ?? 'EN'; // Default to 'EN' if not set
    $settings = $languageMap[$lang] ?? $languageMap['EN']; // Fallback to 'EN' if language not found
    
    $roles_name = $settings['roles_name'];
    $charges_name_fieldname = $settings['charges_name_fieldname'];
    $operations_description_lang = $settings['operations_description_lang'];
    $Language = $settings['Language'];

    // select query
    switch ($pageparams) {

        case 'ROLES': // Roles
            // set language for roles
            $actionlinks = "<a href='#'  onClick=\"getinfo('" . $_POST['frmid'] . "',$('body').data('gridchk'),'eval','','load.php')\" ><img src='images/edit.png' border='0'></a><a href='#'  onClick=\"getinfo('" . $frmid . "',$('body').data('gridchk'),'delete','','addedit.php')\"><img src='images/delete.png' border='0'></a>";

            $newgrid->cPage = 'ROLES';

            if (isset($_GET['searchterm'])) {
                $cWhere = $roles_name . " LIKE '%" . Common::tep_db_prepare_input($_GET['searchterm']) . "%' OR user_username LIKE '%" . Common::tep_db_prepare_input($_GET['searchterm']) . "%'";
            }

            Common::getlables("577,574,578", "", "", Common::$connObj);

            $query = "SELECT  roles_id," . $roles_name . " As roles_name FROM " . TABLE_ROLES . " " . $cWhere . " ORDER BY " . $roles_name . " ASC";
           
            $fieldlist = array('roles_name');
            $newgrid->keyfield = 'roles_id';
            $gridcolumnnames = array(Common::$lablearray['578']);

            break;

        case 'USERS': // Users
            $actionlinks = "<a href='#'  onClick=\"getinfo('" . $_POST['frmid'] . "',$('body').data('gridchk'),'eval','','addedit.php')\" ><img src='images/edit.png' border='0'></a><a href='#'  onClick=\"getinfo('" . $frmid . "',$('body').data('gridchk'),'delete','','addedit.php')\"><img src='images/delete.png' border='0'></a>";

            // $onclick = "$('#keyparam').val($(this).val());";
            $newgrid->cPage = 'USERS';

            if (isset($_GET['searchterm'])) {
                $cWhere = " user_firstname LIKE '%" . Common::tep_db_prepare_input($_GET['searchterm']) . "%' OR user_username LIKE '%" . Common::tep_db_prepare_input($_GET['searchterm']) . "%'";
            }
            Common::getlables("238,240,3,197,976,586,274,639,1562", "", "", Common::$connObj);
            $query = "SELECT user_id,user_firstname,user_lastname,user_username,user_accesscode,user_usercode, last_login,IF(user_isactive='Y','" . Common::$lablearray['274'] . "','" . Common::$lablearray['639'] . "') As user_isactive FROM " . TABLE_USERS . " " . $cWhere . " ORDER BY user_firstname,user_lastname DESC";
            $fieldlist = array('user_username', 'user_firstname', 'user_lastname', 'user_isactive', 'user_accesscode', 'last_login');
            $newgrid->keyfield = 'user_id';
            $gridcolumnnames = array(Common::$lablearray['238'], Common::$lablearray['240'], Common::$lablearray['3'], Common::$lablearray['197'], Common::$lablearray['1562'], Common::$lablearray['586']);

            break;

        case 'ALLLOANS': // All Loans            
//            $newgrid->sp_code = 'LOANDUESSUM';
            $newgrid->cPage = 'ALLLOANS';
//            $newgrid->idfield = 'loan_number';
//            $newgrid->extraFields[0] = "loan_amount";
//            $newgrid->Conn = $Conn;
//
//            $newgrid->sp_parameters[] = array('name' => 'branch_code', 'value' => $_GET['branch_code']);
//            $newgrid->sp_parameters[] = array('name' => 'product_prodid', 'value' => $_GET['product_prodid']);
//            $newgrid->sp_parameters[] = array('name' => 'asatdate', 'value' => Common::changeDateFromPageToMySQLFormat($_GET['asatdate']));
//            $newgrid->sp_parameters[] = array('name' => 'code', 'value' => 'LOANDUESSUM');
//            $newgrid->sp_parameters[] = array('name' => 'user_id', 'value' => $_SESSION['user_id']);
//            $newgrid->sp_parameters[] = array('name' => 'client_type', 'value' => 'I');
            $newgrid->cpara = 'ALLLOANS';
            if (isset($_GET['searchterm'])) {
                $cWhere = $cWhere . " AND  ( l.loan_number LIKE '%" . Common::tep_db_prepare_input($_GET['searchterm']) . "%' OR c.client_surname LIKE '%" . Common::tep_db_prepare_input($_GET['searchterm']) . "%' OR c.client_middlename LIKE '%" . Common::tep_db_prepare_input($_GET['searchterm']) . "%' OR c.client_middlename LIKE '%" . Common::tep_db_prepare_input($_GET['searchterm']) . "%')";
            }

            $newgrid->sortfield = 'l.loan_number';

            $query = "SELECT CONCAT(c.client_firstname,' ',c.client_middlename,' ',c.client_surname) name,l.loan_number,l.client_idno,l.loan_amount,loan_startdate FROM " . TABLE_LOAN . " l," . TABLE_VCLIENTS . " c  WHERE c.client_idno=l.client_idno AND " . $clienttype . " AND l.loan_number IN (SELECT loan_number FROM " . TABLE_LOANSTATUSLOG . " sl WHERE sl.loan_number=l.loan_number AND sl.loan_status='LD')";

            $fieldlist = array('loan_number', 'name', 'loan_amount');
            $newgrid->keyfield = 'loan_number';
            Common::getlables("1097,1023,1093,1099,1098,271", "", "", $Conn);
            $gridcolumnnames = array(Common::$lablearray['1097'], Common::$lablearray['1023'], Common::$lablearray['271'],);

            break;

        case 'COLLECTINTEREST':

            // if ($_GET['product_prodid'] == ""):
            //     Common::getlables("1406", "", "", $Conn);
            //     echo "INFO " . Common::$lablearray['1097'];
            //     exit();
            // endif;

            $newgrid->sp_code = 'COLLECTINTEREST';
            $newgrid->cpara = 'COLLECTINTEREST';
            $newgrid->keyfield = 'loan_number';
            $newgrid->Conn = $Conn;

//            $newgrid->numberof_rows_on_page = 10000;
            $newgrid->displayPageCount = false;    
            $newgrid->checkmultselect = true;
            $actionlinks = "";
            $newgrid->extraFields[0] = "client_idno";

            $newgrid->sp_parameters[] = array('name' => 'branch_code', 'value' => '');
            $newgrid->sp_parameters[] = array('name' => 'product_prodid', 'value' => ($_GET['product_prodid']?? ""));
            $newgrid->sp_parameters[] = array('name' => 'asatdate', 'value' => Common::changeDateFromPageToMySQLFormat($_GET['asatdate']));
            $newgrid->sp_parameters[] = array('name' => 'code', 'value' => 'COLLECTINTEREST');
            $newgrid->sp_parameters[] = array('name' => 'regstatus', 'value' => $_GET['client_regstatus']);
            //   $newgrid->sp_parameters[] = array('name' => 'user_id', 'value' => $_SESSION['user_id']);
            $fieldlist = array('loan_number','name', 'outprinc','outint','outcom','outpen');

            $newgrid->keyfield = 'loan_number';

            Common::getlables("1097,9,1145,1738,1739,1740,1741", "", "", $Conn);
            $gridcolumnnames = array(Common::$lablearray['1097'], Common::$lablearray['9'], Common::$lablearray['1738'], Common::$lablearray['1739'], Common::$lablearray['1740'], Common::$lablearray['1741']);

            break;

        case 'INDREPAYLOANS': // Loans for Approval
            //$actionlinks = "<a href='#'  onClick=\"getinfo('".$_POST['frmid']."',bvar.data('gridchk'),'edit','','load.php')\" title ='".$grid_lables_lablearray['272']."'><img src='images/edit.png' border='0'></a><a href='#'  onClick=\"getinfo('".$frmid."',bvar.data('gridchk'),'delete','','addedit.php')\" title ='".$grid_lables_lablearray['272']."'><img src='images/delete.png' border='0'></a>";
//            $newgrid->sp_code = 'INDREPAYLOANS';
//            $newgrid->cpara = 'INDREPAYLOANS';
//            $newgrid->idfield = 'loan_number';
//            $newgrid->extraFields[0] = "loan_amount";
//            $newgrid->Conn = $Conn;
//
//            $newgrid->sp_parameters[] = array('name' => 'branch_code', 'value' => $_GET['branch_code']);
//            $newgrid->sp_parameters[] = array('name' => 'product_prodid', 'value' => $_GET['product_prodid']);
//            $newgrid->sp_parameters[] = array('name' => 'asatdate', 'value' => Common::changeDateFromPageToMySQLFormat($_GET['asatdate']));
//            $newgrid->sp_parameters[] = array('name' => 'code', 'value' => 'INDREPAYLOANS');
//            $newgrid->sp_parameters[] = array('name' => 'user_id', 'value' => $_SESSION['user_id']);
//            $newgrid->sp_parameters[] = array('name' => 'client_type', 'value' => 'I');

            if (isset($_GET['searchterm'])) {
                $cWhere = $cWhere . " AND  ( l.loan_number LIKE '%" . Common::tep_db_prepare_input($_GET['searchterm']) . "%' OR c.client_surname LIKE '%" . Common::tep_db_prepare_input($_GET['searchterm']) . "%' OR c.client_middlename LIKE '%" . Common::tep_db_prepare_input($_GET['searchterm']) . "%' OR c.client_middlename LIKE '%" . Common::tep_db_prepare_input($_GET['searchterm']) . "%')";
            }

            $query = "SELECT CONCAT(c.client_firstname,' ',c.client_middlename,' ',c.client_surname) name,l.loan_number,l.client_idno,l.loan_amount,loan_startdate FROM " . TABLE_LOAN . " l," . TABLE_VCLIENTS . " c," . TABLE_LOANSTATUSLOG . " lg  WHERE c.client_idno=l.client_idno AND " . $clienttype . " AND lg.loan_number=l.loan_number  AND l.loan_number IN (SELECT loan_number FROM " . TABLE_LOANSTATUSLOG . " sl WHERE sl.loan_number=l.loan_number AND sl.loan_status='LD')";
            $newgrid->keyfield = 'loan_number';
            $fieldlist = array('loan_number', 'name', 'loan_amount');
            $newgrid->keyfield = 'loan_number';
            Common::getlables("1097,1023,1093,1099,1098,271", "", "", $Conn);
            $gridcolumnnames = array(Common::$lablearray['1097'], Common::$lablearray['1023'], Common::$lablearray['271'],);

            break;

        case 'LOANFEES': // Loans for Approval
            Common::getlables("272", "", "", $Conn);
            $actionlinks = "<a href='#'  onClick=\"showValues('" . $frmid . "','','edit','','load.php',$( 'body').data( 'gridchk'))\" title ='" . Common::$lablearray['272'] . "'><img src='images/plus.gif' border='0'></a>";

            $onclick = "checkunckeck(this.value);";
            if (isset($_GET['searchterm'])) {
                $cWhere = $cWhere . " AND  ( l.loan_number LIKE '%" . Common::tep_db_prepare_input($_GET['searchterm']) . "%' OR c.client_surname LIKE '%" . Common::tep_db_prepare_input($_GET['searchterm']) . "%' OR c.client_middlename LIKE '%" . Common::tep_db_prepare_input($_GET['searchterm']) . "%')";
            }

            $query = "SELECT CONCAT(c.client_surname,' ',c.client_firstname,' ',c.client_middlename) name,l.loan_number,l.client_idno,l.loan_amount,loan_startdate FROM " . TABLE_LOAN . " l," . TABLE_VCLIENTS . " c," . TABLE_LOANSTATUSLOG . " lg  WHERE c.client_idno=l.client_idno AND " . $clienttype . " AND lg.loan_number=l.loan_number AND lg.loan_status='PA'";
            $fieldlist = array('loan_number', 'name', 'client_idno', 'loan_amount', 'loan_startdate');
            $newgrid->keyfield = 'loan_number';
            Common::getlables("1097,1023,1093,1099,1098,271", "", "", $Conn);
            $gridcolumnnames = array(Common::$lablearray['1097'], Common::$lablearray['1023'], Common::$lablearray['1093'], Common::$lablearray['271'], Common::$lablearray['1098']);

            break;

        case 'LOANAPPROVE': // Loans for Approval

            $actionlinks = "";
            $onclick = "$('#keyparam').val($(this).val());";
            $newgrid->cPage = 'LOANAPPROVE';

            if (isset($_GET['searchterm'])) {
                $cWhere = $cWhere . " AND  ( l.loan_number LIKE '%" . Common::tep_db_prepare_input($_GET['searchterm']) . "%' OR c.client_surname LIKE '%" . Common::tep_db_prepare_input($_GET['searchterm']) . "%' OR c.client_middlename LIKE '%" . filter_input(INPUT_GET, 'searchterm') . "%')";
            }

            $query = "SELECT CONCAT(c.client_firstname,' ',c.client_middlename,' ',c.client_surname) name,l.loan_number,l.client_idno,l.loan_amount,loan_startdate FROM " . TABLE_LOAN . " l," . TABLE_VCLIENTS . " c, ".TABLE_LOANSTATUSLOG." ls WHERE c.client_idno=l.client_idno AND  ls.loan_number = l.loan_number AND " . $clienttype . " AND ls.loan_status ='PA'";
            $fieldlist = array('loan_number', 'name', 'client_idno', 'loan_amount', 'loan_startdate');
            $newgrid->keyfield = 'loan_number';

            $newgrid->sortfield = ' l.loan_adate';
            $newgrid->sortorder = ' DESC';
            Common::getlables("1097,1023,1093,1099,1098,271", "", "", $Conn);
            $gridcolumnnames = array(Common::$lablearray['1097'], Common::$lablearray['1023'], Common::$lablearray['1093'], Common::$lablearray['271'], Common::$lablearray['1098']);

//            break;
//        case 'LOANAPPROVE': // Loans for Approval
//            // $actionlinks = "<a href='#'  onClick=\"getinfo('".$_POST['frmid']."',bvar.data('gridchk'),'edit','','load.php')\" title ='".$grid_lables_lablearray['272']."'><img src='images/edit.png' border='0'></a><a href='#'  onClick=\"getinfo('".$frmid."',bvar.data('gridchk'),'delete','','addedit.php')\" title ='".$grid_lables_lablearray['272']."'><img src='images/delete.png' border='0'></a>";
//            $actionlinks = "";
//            $onclick = "$('#keyparam').val($(this).val());";
//            $newgrid->cPage = 'LOANAPPROVE';
//
//            if (isset($_GET['searchterm'])) {
//                $cWhere = $cWhere . "AND  ( l.loan_number LIKE '%" . Common::tep_db_prepare_input($_GET['searchterm']) . "%' OR c.client_surname LIKE '%" . Common::tep_db_prepare_input($_GET['searchterm']) . "%' OR c.client_middlename LIKE '%" . Common::tep_db_prepare_input($_GET['searchterm']) . "%' OR c.client_firstname LIKE '%" . Common::tep_db_prepare_input($_GET['searchterm']) . "%')";
//            }
//            $query = "SELECT CONCAT(c.client_firstname,' ',c.client_middlename,' ',c.client_surname) name,l.loan_number,l.client_idno,l.loan_amount,loan_startdate FROM " . TABLE_LOAN . " l," . TABLE_VCLIENTS . " c," . TABLE_LOANSTATUSLOG . " lg  WHERE c.client_idno=l.client_idno AND " . $clienttype . " AND lg.loan_number=l.loan_number  AND l.loan_number NOT IN (SELECT loan_number FROM " . TABLE_LOANSTATUSLOG . " sl WHERE sl.loan_number=l.loan_number AND (sl.loan_status='AP' OR sl.loan_status='LD'))";
//            $fieldlist = array('loan_number', 'name', 'client_idno', 'loan_amount', 'loan_startdate');
//            $newgrid->keyfield = 'loan_number';
//            Common::getlables("1097,1023,1093,1099,1098,271", "", "", $Conn);
//            $gridcolumnnames = array(Common::$lablearray['1097'], Common::$lablearray['1023'], Common::$lablearray['1093'], Common::$lablearray['271'], Common::$lablearray['1098']);

            break;

        case 'LOANDISBURSE': // Loans disbursed

            Common::getlables("1472,271,1473,1097,9,1093,271,1098,1229,1340", "", "", $Conn);

            if (isset($_GET['searchterm'])) {
                $cWhere = $cWhere . " AND ( l.loan_number LIKE '%" . Common::tep_db_prepare_input($_GET['searchterm']) . "%' OR c.client_surname LIKE '%" . Common::tep_db_prepare_input($_GET['searchterm']) . "%' OR c.client_middlename LIKE '%" . Common::tep_db_prepare_input($_GET['searchterm']) . "%' OR c.client_firstname LIKE '%" . Common::tep_db_prepare_input($_GET['searchterm']) . "%')";
            }


            $newgrid->cpara = $pageparams;
            $newgrid->cPage = $newgrid->cpara;
            $newgrid->OnclickHeaderCheckbox = "$(\".chkgrd\").attr(\"checked\", \"true\");";
            $query = "SELECT CONCAT(c.client_firstname,' ',c.client_middlename,' ',c.client_surname) name,l.loan_number,l.client_idno,l.loan_amount,loan_startdate FROM " . TABLE_LOAN . " l," . TABLE_VCLIENTS . " c," . TABLE_LOANSTATUSLOG . " lg  WHERE c.client_idno=l.client_idno AND " . $clienttype . " AND lg.loan_number=l.loan_number AND lg.loan_status='AP' AND l.loan_number NOT IN (SELECT loan_number FROM " . TABLE_LOANSTATUSLOG . " sl WHERE sl.loan_number=l.loan_number AND sl.loan_status='LD') ORDER BY l.loan_number DESC";
            $fieldlist = array('loan_number', 'name', 'client_idno', 'loan_amount', 'loan_startdate', 'disbursements_date', 'disbursements_amount');
            $newgrid->keyfield = 'loan_number';

            $gridcolumnnames = array(Common::$lablearray['1097'], Common::$lablearray['9'], Common::$lablearray['1093'], Common::$lablearray['271'], Common::$lablearray['1098'], Common::$lablearray['1340'], Common::$lablearray['1229']);

            break;


        case 'LOANDISBURSED': // Loans disbursed

            Common::getlables("1472,271,1473,1097,9,1093,271,1098,1229,1340", "", "", $Conn);

            if (isset($_GET['searchterm'])) {
                $cWhere = $cWhere . " AND ( l.loan_number LIKE '%" . Common::tep_db_prepare_input($_GET['searchterm']) . "%' OR c.client_surname LIKE '%" . Common::tep_db_prepare_input($_GET['searchterm']) . "%' OR c.client_middlename LIKE '%" . Common::tep_db_prepare_input($_GET['searchterm']) . "%' OR c.client_firstname LIKE '%" . Common::tep_db_prepare_input($_GET['searchterm']) . "%')";
            }


            $newgrid->cpara = $pageparams;
            $newgrid->cPage = $newgrid->cpara;
            $newgrid->OnclickHeaderCheckbox = "$(\".chkgrd\").attr(\"checked\", \"true\");";
            $query = "SELECT CONCAT(c.client_firstname,' ',c.client_middlename,' ',c.client_surname) name,l.loan_number,l.client_idno,l.loan_amount,loan_startdate,d.disbursements_date,d.disbursements_amount FROM " . TABLE_LOAN . " l," . TABLE_VCLIENTS . " c," . TABLE_DISBURSEMENTS . " d  WHERE c.client_idno=l.client_idno AND " . $clienttype . " AND d.loan_number=l.loan_number AND c.client_regstatus!='EXT'";

            $fieldlist = array('loan_number', 'name', 'client_idno', 'loan_amount', 'loan_startdate', 'disbursements_date', 'disbursements_amount');
            $newgrid->keyfield = 'loan_number';

            $gridcolumnnames = array(Common::$lablearray['1097'], Common::$lablearray['9'], Common::$lablearray['1093'], Common::$lablearray['271'], Common::$lablearray['1098'], Common::$lablearray['1340'], Common::$lablearray['1229']);

            break;

        case 'BANKACCCOUNTS':
            $newgrid->cpara = $pageparams;
            $newgrid->cPage = $newgrid->cpara;
            $query = "SELECT  ba.bankaccounts_accno,ba.bankaccounts_id,b.banks_name FROM " . TABLE_BANKACCOUNTS . " as ba," . TABLE_BANKBRANCHES . " as bb, " . TABLE_BANKS . " as b WHERE b.banks_id=bb.banks_id AND ba.bankbranches_id=bb.bankbranches_id  GROUP BY bankaccounts_accno";
            $fieldlist = array('banks_name', 'bankaccounts_accno');
            $newgrid->keyfield = 'bankaccounts_id';
            $gridcolumnnames = array('Branch Name', 'Account Number');
            break;
        
        case 'CURRENCYDENO':
            Common::getlables("1693", "", "", $Conn);

            if ($_POST['keyparam'] == 'savdata'):
                echo "MSG " . Common::$lablearray['1199'];
                exit();
            endif;

            NewGrid::$actionlinks = "<a data-balloon='" . Common::$lablearray['272'] . "' data-balloon-pos='down' href='#'  onClick=\"getinfo('" . $_POST['frmid'] . "',$('body').data('gridchk'),'edit','','addedit.php')\" ><img src='images/edit.png' border='0'></a><a data-balloon='" . Common::$lablearray['273'] . "' data-balloon-pos='down' href='#'  onClick=\"getinfo('" . $_POST['frmid'] . "',$('body').data('gridchk'),'delete','','addedit.php')\"><img src='images/delete.png' border='0'></a>";;

            NewGrid::$keyfield = 'currencydeno_id';
            NewGrid::$columntitle = array('',Common::$lablearray['1693']);
            NewGrid::$fieldlist = array('currencydeno_id','currencydeno_id', 'currencydeno_deno','currencies_id');
            NewGrid::$sSQL = " FROM " . TABLE_CURRENCYDENO; 
            NewGrid::$searchcatparam = $pageparams;
            NewGrid::$grid_id = 'grid_' . $_POST['keyparam'];
            NewGrid::$request = $_POST;
            
           // if(isset($_POST['keyparam'])):
                DataTable::$where_condition =" currencies_id ='".Common::tep_db_prepare_input($_POST['keyparam'])."'";             
            //endif;
                   
            if (isset($_POST['grid_id'])):
                echo NewGrid::getData();
            else:
                echo NewGrid::generateDatatableHTML();
            endif;

            exit();
            
            break;
            
        case 'CURRENCIES':
//            $newgrid->cpara = $pageparams;
//            $newgrid->cPage = $newgrid->cpara;
//            $query = "SELECT name,currencies_name,currencies_code,currencies_decimalplaces,currencies_symbolleft,currencies_symbolright,currencies_isbase FROM " . TABLE_CURRENCIES . " ORDER BY currencies_name ASC";
//            $fieldlist = array('name', 'currencies_name', 'currencies_code', 'currencies_decimalplaces', 'currencies_isbase', 'currencies_symbolleft', 'currencies_symbolright');
//            $keyfield = 'currencies_id';
//            Common::getlables("667,659,654,657,658,655,656", "", "", $Conn);
//            $gridcolumnnames = array(Common::$lablearray['667'], Common::$lablearray['659'], Common::$lablearray['654'], Common::$lablearray['657'], Common::$lablearray['658'], Common::$lablearray['655'], Common::$lablearray['656']);

            Common::getlables("9,654,657,658,272", "", "", $Conn);

            if ($_POST['keyparam'] == 'savdata'):
                echo "MSG " . Common::$lablearray['1199'];
                exit();
            endif;

            NewGrid::$actionlinks = "<a data-balloon='" . Common::$lablearray['272'] . "' data-balloon-pos='down' href='#'  onClick=\"getinfo('" . $_POST['frmid'] . "',$('body').data('gridchk'),'edit','','addedit.php')\" ><img src='images/edit.png' border='0'></a><a data-balloon='" . Common::$lablearray['273'] . "' data-balloon-pos='down' href='#'  onClick=\"getinfo('" . $_POST['frmid'] . "',$('body').data('gridchk'),'delete','','addedit.php')\"><img src='images/delete.png' border='0'></a>";;

            NewGrid::$keyfield = 'currencies_id';
            NewGrid::$columntitle = array('',Common::$lablearray['9'], Common::$lablearray['654'], Common::$lablearray['657'], Common::$lablearray['658']);
            NewGrid::$fieldlist = array('currencies_id','currencies_id', 'currencies_name', 'currencies_code', 'currencies_decimalplaces', 'currencies_isbase');
            NewGrid::$sSQL = " FROM " . TABLE_CURRENCIES; 
            NewGrid::$searchcatparam = $pageparams;
            NewGrid::$grid_id = 'grid_' . $_POST['keyparam'];

            NewGrid::$request = $_POST;

            if (isset($_POST['grid_id'])):
                echo NewGrid::getData();
            else:
                echo NewGrid::generateDatatableHTML();
            endif;

            exit();

            break;

        case 'TAXES':
            $newgrid->cpara = $pageparams;
            $newgrid->cPage = $newgrid->cpara;
            $query = "SELECT * FROM " . TABLE_TAXES;
            $fieldlist = array('taxes_name', 'chartofaccounts_accountcode'); //array('banks_name', 'bankaccounts_accno');
            $keyfield = 'taxes_id';
            $gridcolumnnames = array('Tax', 'GL Account');
            break;

        case 'BANKBRANCH':
            $newgrid->cpara = $pageparams;
            $newgrid->cPage = $newgrid->cpara;
            Common::getlables("996,997", "", "", $Conn);
            $query = "SELECT  bankbranches_id,branch_code,bankbranches_name,branch_code FROM " . TABLE_BANKBRANCHES . "  ORDER BY branch_code DESC ";
            $fieldlist = array('branch_code', 'bankbranches_name', 'branch_code');
            $newgrid->keyfield = 'bankbranches_id';
            $gridcolumnnames = array($lablearray['996'], $lablearray['997'], '');
            break;

        case 'BANKS':
            $newgrid->cpara = $pageparams;
            $newgrid->cPage = $newgrid->cpara;
            Common::getlables("611,994", "", "", $Conn);
            $query = "SELECT licence_build,licence_address,licence_organisationname FROM " . TABLE_LICENCE . " ORDER BY licence_build DESC";
            $fieldlist = array('licence_organisationname', 'licence_address');
            $newgrid->keyfield = 'licence_build';
            $gridcolumnnames = array(Common::$lablearray['994'], Common::$lablearray['611']);
            break;

        case 'SMS': // SMS
            $actionlinks = "<a href='#'  onClick=\"getinfo('" . $_POST['frmid'] . "',$('body').data('gridchk'),'edit','','load.php')\" ><img src='images/edit.png' border='0'></a><a href='#'  onClick=\"getinfo('" . $_POST['frmid'] . "',$('body').data('gridchk'),'delete','','addedit.php')\"><img src='images/delete.png' border='0'></a>";
            $newgrid->cpara = $pageparams;
            $newgrid->cPage = $newgrid->cpara;
            $newgrid->numberof_rows_on_page = 10000;
            Common::getlables("317,1093,1611,197,522", "", "", $Conn);
            $query = "SELECT d.devicemessage_id,devicemessage_date,d.clientid,d.devicemessage_msg,d.devicemessage_status ,c.client_tel1,c.client_tel2 FROM " . TABLE_DEVICEMESSAGE . " d, " . TABLE_VCLIENTS . " c WHERE d.clientid=c.client_idno ORDER BY d.devicemessage_date DESC";
            $fieldlist = array('devicemessage_date', 'clientid', 'devicemessage_msg', 'client_tel1', 'devicemessage_status');
            $newgrid->keyfield = 'devicemessage_id';
            $gridcolumnnames = array(Common::$lablearray['317'], Common::$lablearray['1093'], Common::$lablearray['1611'], Common::$lablearray['522'], Common::$lablearray['197']);
            break;

        case 'TRANSACTION':

            Common::getlables("317,1524,306,299,289,297,1251,264,316,1208", "", "", $Conn);

            $actionlinks = Common::prepareLinks($_POST['frmid'], 'reverse', $pageparams);

            $newgrid->Conn = $Conn;
            $newgrid->sp_code = 'GETTRAN';
            $newgrid->sp_parameters[] = array('name' => 'branch_code', 'value' => '');
            $newgrid->sp_parameters[] = array('name' => 'code', 'value' => 'GETTRAN');
            $newgrid->sp_parameters[] = array('name' => 'tcode', 'value' => $_GET['searchterm']);          
            $newgrid->keyfield = 'transactioncode';
            $newgrid->cpara = $pageparams;
            $newgrid->cPage = $newgrid->cpara;
            $fieldlist = array('generalledger_tday', 'transactioncode', 'chartofaccounts_accountcode', 'generalledger_voucher', 'generalledger_debit', 'generalledger_credit', 'currencies_id', 'generalledger_description', 'branch_code', 'trancode', '');
            $gridcolumnnames = array(Common::$lablearray['317'], Common::$lablearray['1524'], Common::$lablearray['306'], Common::$lablearray['299'], Common::$lablearray['289'], Common::$lablearray['297'], Common::$lablearray['1251'], Common::$lablearray['264'], Common::$lablearray['316'], Common::$lablearray['1208'], "");

            break;
        case 'REFINANCE': // Loans to Refinance            

            Common::getlables("1097,9,1229,1478,1461,1476,1477,1478,1479", "", "", $Conn);
            if (isset($_GET['searchterm'])) {
                $actionlinks = "<a href='#'  onClick=\"getinfo('frmLoanapp1',$('body').data('gridchk'),'edit','" . $pageparams . "','load.php')\" title ='" . $grid_lables_lablearray['272'] . "'><img src='images/plus.gif' border='0'></a>";

                $newgrid->sp_code = 'SEARCHLOAN';
                $newgrid->sp_parameters[] = array('name' => 'searchterm', 'value' => filter_input(INPUT_GET, 'searchterm'));
                $newgrid->sp_parameters[] = array('name' => 'code', 'value' => 'SEARCHLOAN');
                $newgrid->keyfield = 'loan_number';
                $newgrid->cpara = $pageparams;
                $newgrid->cPage = $newgrid->cpara;
                $fieldlist = array('loan_number', 'name', 'loan_amount', 'outprinc', 'outint', 'outcomm');

                $gridcolumnnames = array(Common::$lablearray['1097'], Common::$lablearray['9'], Common::$lablearray['1229'], Common::$lablearray['1461'], Common::$lablearray['1476'], Common::$lablearray['1477']);
            } else {
                common::getlables("9,21,1097,1096,1494,317,24,300,1222,819,1101,1490,1474,20,1472,1473,1100,1489", "", "", Common::$connObj);
                echo "<p alig='center'>" . common::$lablearray['21'] . " <input id='txtsearchterm' name='txtsearchterm' value='' size=50 placeholder='Name /Loan Number'></p>";
                exit();
            }

            break;

        case 'RESCHEDULE': // Loan Reschedule       

            Common::getlables("1569,21,317,1144,1145,1181,1105", "", "", $Conn);
            if (isset($_GET['searchterm'])) {

                $loan = new Loan(array(), $_GET['searchterm']);

                common::getlables("21,1143,300,20,1144,1145,1688,1688,1105,1181,1160,1687,1686", "", "", Common::$connObj);

                $dues_results = $Conn->SQLSelect("SELECT d.due_id keyparam,d.due_date ddate,d.due_principal principal,d.due_interest interest,d.due_penalty penalty,d.due_commission commission,members_idno FROM " . TABLE_DUES . " d WHERE d.loan_number='".$loan::$cLnr."'");

                $html = "<p align='right'>" . common::$lablearray['21'] . " <input id='txtsearchterm' name='txtsearchterm' value='' size=50 placeholder='Loan Number'></p>";

                $html .= "<input id='lstatus' type='hidden' name='lstatus' value='RS'><input type='hidden' id='loannumber' name='loannumber' value='".$loan::$loanappdetails['loan_number']."'><table width='100%' id='tbldues'>
                        <tr><td>Client Name:<br>" . $loan::$loanappdetails['name'] . "</td>
                        <td>Loan Number:<br>". $loan::$loanappdetails['loan_number'] . "</td>
                        <td>Loan Amount:<br><input type='text' id='lamount' name='lamount' value='" . $loan::$loanappdetails['loan_amount'] . "'></td></tr>
                        <tr><td>Interest Rate:<br><input type='text' id='intrate' name='intrate' value='" . loan::$loanappdetails['loan_tint'] . "'></td>
                        <td>Number of Installments:<br><input type='text' id='noofinst' name='noofinst' value='" . $loan::$loanappdetails['loan_noofinst'] . "'></td>
                        <td>Interest Type:<br>" . $loan::$loanappdetails['loan_inttype'] . "</td></tr>
                        <tr><td>Installment Type:<br>" . $loan::$loanappdetails['loan_insttype'] . "</td>
                        <td>Interest Amount:<br><input type='text' id='intamt' name='intamt' value='" . loan::$loanappdetails['loan_intamount'] . "'></td><td></td></tr>
                        </table>";

                $html .= "<fieldset>";
                $html .= "<div class='scroll'><div style='filter: alpha(opacity=90);opacity: 0.9;position: fixed; top:10;width: 100%;background-color:#C7F8B1;'><span   onClick=\"AddRow('tblschedule','ADD')\" data-balloon='" . Common::$lablearray['1686'] . "' data-balloon-pos='down'><img src='images/addrow.png' border='0' ></span> <span onClick=\"deleteRow('tblschedule')\" data-balloon='" . Common::$lablearray['1687'] . "' data-balloon-pos='down'><img src='images/deleterow.png' border='0' ></span></div>";
                $html .= "<table cellpading='1' width='100%' cellpacing='0' id='tblschedule' style='margin-top:50px;'>";
                $html .= "<thead><th></th><th>" . Common::$lablearray['1143'] . "</th><th>" . Common::$lablearray['1144'] . "</th><th>" . common::$lablearray['1145'] . "</th><th>" . common::$lablearray['1105'] . "</th><th>" . common::$lablearray['1181'] . "</th><th>" . common::$lablearray['1160'] . "</th></thead>";
                $html .= "<tbody>";
             
                foreach ($dues_results as $thekey => $thevalue) {

                    if ($thevalue['members_idno'] != $prevmemno):
                        $html .= "<tr><td colspan='7'>member Name</td></tr>";
                    endif;

                    $html .= "<tr><td><input class='chkgrd' type='checkbox' id='ID_" . $thevalue['due_id'] . "' name='due_id[]' value='" . $thevalue['due_id'] . "'></td><td><input size='12' type='us-date' data-dojo-type='dijit/form/DateTextBox' id='DATE_" . $thevalue['keyparam'] . "' name='DATE_" . $thevalue['keyparam'] . "' value='" . Common::changeMySQLDateToPageFormat($thevalue['ddate']) . "'></td><td><input type='hidden' id='rowid_" . $thevalue['keyparam'] . "' name='rowid' value='" . $thevalue['keyparam'] . "'><input size='15' class='princ' type='text' id='PRINC_" . $thevalue['keyparam'] . "' name='PRINC_" . $thevalue['keyparam'] . "' value='" . $thevalue['principal'] . "'></td><td><input size='15' type='text' id='INT_" . $thevalue['keyparam'] . "' name='INT_" . $thevalue['keyparam'] . "' value='" . $thevalue['interest'] . "'></td><td><input size='15' type='text' id='COMM_" . $thevalue['keyparam'] . "' name='COMM_" . $thevalue['keyparam'] . "' value='" . $thevalue['commission'] . "'></td><td><input size='15' type='text' id='PEN_" . $thevalue['keyparam'] . "' name='PEN_" . $thevalue['keyparam'] . "' value='" . $thevalue['penalty'] . "'></td><td><input type='text' size='4' id='OTH_" . $thevalue['keyparam'] . "' name='OTH_" . $thevalue['keyparam'] . "' value='0.0'></td></tr>";
                    
                    $prevmemno = $thevalue['members_idno'];

                }
                

                $html .= "</tbody>";
                $html .= '</table></div></fieldset>';
                $html .= '<div><button class="btn" name="btnscancel" value="" style="float:right;margin:5px;" type="button" onClick="CloseDialog(\'myDialogId1\')"  id="btnscancel">' . common::$lablearray['300'] . '</button><button class="btn" name="Go"  type="button"   id="btnSave" onClick="UpdateData(\'RESCHEDULE\')" style="float:right;margin:5px;" > ' . common::$lablearray['20'] . '</button></div>';
                
//                 $html .= "<script>
//                     $( 'princ' ).keyup(function() {
//                    alert( 'Handler for .keyup() called.');
//                });</script>";
                 
                echo $html;
                exit();

            } else {
                // common::getlables("9,21,1097,1096,1494,317,24,300,1222,819,1101,1490,1474,20,1472,1473,1100,1489", "", "", Common::$connObj);
                echo common::$lablearray['21'] . " <input id='txtsearchterm' name='txtsearchterm' value='' size=50 placeholder='Loan Number'>";
                exit();
            }

            break;

        case 'WRITEOFF': // Write off
            Common::getlables("24,1222,1096,176,20,1490,271,1097,9,1229,1478,1461,1476,1477,1478,1479", "", "", $Conn);

            if (isset($_GET['searchterm'])) {

                $newgrid->sp_code = 'SEARCHLOAN';
                $newgrid->sp_parameters[] = array('name' => 'searchterm', 'value' => filter_input(INPUT_GET, 'searchterm'));
                $newgrid->sp_parameters[] = array('name' => 'code', 'value' => 'SEARCHLOAN');
                $newgrid->keyfield = 'loan_number';
                $newgrid->cpara = $pageparams;
                $newgrid->cPage = $newgrid->cpara;
                $fieldlist = array('loan_number', 'name', 'loan_amount', 'outprinc', 'outint', 'outcomm');

                $gridcolumnnames = array(Common::$lablearray['1097'], Common::$lablearray['9'], Common::$lablearray['1229'], Common::$lablearray['1461'], Common::$lablearray['1476'], Common::$lablearray['1477']);
            } else {
                echo "<fieldset style='width:100%;'>  " . common::$lablearray['21'] . " <input id='txtsearchterm' name='txtsearchterm' value='' size=50 placeholder='Name /Loan Number'></fieldset>"
                . "<p align='center' class='information'>" . common::$lablearray['1494'] . "</p>"
                . "<fieldset id='modes'>" . Common::$lablearray['24'] . " " . Common::DrawComboFromArray(array(), 'PAYMODES', '', 'PAYMODES', '', 'PAYMODES', 'frmSave') . "<input type='hidden' id='pageparams' name='pageparams' value='REFINANCE'><table cellpading='5' width='100%' cellpacing='0'>"
                . "<tr><td>" . common::$lablearray['317'] . "<br><input type='us-date' id='startDate' name='startDate' disabled></td><td>" . common::$lablearray['1100'] . "<br><input type='numeric' id='txtintrate' name='txtloan_noofinst' value='0.0' disabled></td><td> " . common::$lablearray['819'] . '<br> <input type="text" id="txtvoucher" name="txtvoucher" value="" disabled></td></tr>'
                . common::$lablearray['1222'] . ' <select name="lstatus" id="lstatus"><option id="WO" value="WO" selected>' . common::$lablearray['176'] . '</option></select> ' . common::$lablearray['271'] . " <input type='numeric' id='txtAmount' name='txtAmount' value='0.0'>" . '<button class="btn" name="Go"  type="button"   id="btnsearch" onClick="UpdateData(\'WRITEOFF\')" > ' . common::$lablearray['20'] . " </button>"
                . '<div><button class="btn" name="btnscancel" value="" style="float:right;margin:5px;" type="button" onClick="CloseDialog(\'myDialogId1\')"  id="btnscancel">' . common::$lablearray['300'] . '</button><button class="btn" name="Go"  type="button"   id="btnSave" onClick="UpdateData(\'REFINANCE\')" style="float:right;margin:5px;" disabled> ' . common::$lablearray['20'] . '</button></fieldset></div><fieldset id="txtHint"></fieldset>';

                // echo  "<fieldset><fieldset style='float:right;padding:5px;'>".Common::$lablearray['317']." <input type='us-date' id='startDate' name='startDate'>".Common::$lablearray['1096']." ".DrawComboFromArray(array(), 'LOANPROD', '', 'LOANPROD', '', 'LOANPROD')." <input type='hidden' id='pageparams' name='pageparams' value='WRITEOFF'> "
                //  .common::$lablearray['1222'].' <select name="lstatus" id="lstatus"><option id="WO" value="WO" selected>'.common::$lablearray['176'].'</option></select> '. common::$lablearray['271']." <input type='numeric' id='txtAmount' name='txtAmount' value='0.0'>".'<button class="btn" name="Go"  type="button"   id="btnsearch" onClick="UpdateData(\'WRITEOFF\')" > '.common::$lablearray['20']." </button></fieldset></fieldset>";
                exit();
            }
            break;

        case 'ADDGRPLOANS':
        case 'BUSS':
        case 'GRP': 

            if($pageparams=='GRP' || $pageparams=='ADDGRPLOANS'):
                $query = " FROM " . TABLE_ENTITY . " c  WHERE entity_type='G' ".$cWhere;
            else:
                $query = " FROM " . TABLE_ENTITY . " c  WHERE entity_type='B' ".$cWhere;
            endif;            
       
            Common::getlables("9,1093,1019,484,1665", "", "", $Conn);
            NewGrid::$columntitle = array( Common::$lablearray['1093'],Common::$lablearray['9'], Common::$lablearray['1019'], Common::$lablearray['484']);
          
            NewGrid::$fieldlist = array('entity_idno','entity_name','entity_regdate','entity_enddate');
           
            NewGrid::$grid_id = 'grid_'.($_POST['keyparam']??'');
            NewGrid::$request = $_POST;
            NewGrid::$sSQL = $query;       
            NewGrid::$order =' ORDER BY c.entity_idno DESC ';
            NewGrid::$searchcatparam = $pageparams;

            $data = NewGrid::getData();

            echo $data;

            exit();

        case 'IND':

            Common::getlables("1018,1019,1093,240,484,1665,887", "", "", $Conn);

            //  $query = Savings::getClientDetails($pageparams, "", $cWhere);
            $query = " FROM " . TABLE_CLIENTS . " c " . " WHERE 1=1 " . $cWhere;

            NewGrid::$columntitle = array(
                Common::$lablearray['1093'],
                Common::$lablearray['1018'],
                Common::$lablearray['887'],
                Common::$lablearray['1019'],
                Common::$lablearray['484']
            );
            NewGrid::$fieldlist = array(
                'client_idno',
                'client_surname',
                'client_firstname',
                'client_regdate',
                'client_enddate'
            );
                
            NewGrid::$grid_id = 'grid_'.($_POST['keyparam']??'');
            NewGrid::$request = $_POST;
            NewGrid::$sSQL = $query;
            NewGrid::$order = ' ORDER BY client_idno DESC ';
            NewGrid::$searchcatparam = $pageparams;

            $data = NewGrid::getData();

            echo $data;

            exit();            
         
            break;

        case 'GIND':
            $query = "SELECT CONCAT(trim(c.client_firstname),' ',trim(c.client_middlename),' ',trim(c.client_surname)) name,c.client_idno FROM " . TABLE_VCLIENTS . " c WHERE " . $clienttype;
            $fieldlist = array('name', 'client_idno');
            $newgrid->keyfield = 'client_idno';
            Common::getlables("9,1093", "", "", $Conn);
            $gridcolumnnames = array(Common::$lablearray['9'], Common::$lablearray['1093']);
            $actionlinks = "<a href='#'  onClick=\"getginfo('" . $_POST['frmid'] . "',$( 'body').data( 'gridchk'),'edit','','load.php','" . $_POST['keyparam'] . "')\" title ='" . $grid_lables_lablearray['272'] . "'><img src='images/plus.gif' border='0'></a>";
            break;

        case 'GGRP':
        case 'GMEM':

            // $query = "SELECT members_idno, members_no,CONCAT(members_firstname,' ',members_middlename,' ',members_lastname) name,members_maritalstate,members_regdate,members_enddate,members_dependants,members_children,members_cat1,(SELECT category1_name FROM " . TABLE_CATEGORY1 . " WHERE category1_id=members_cat1) cat1,(SELECT category2_name FROM " . TABLE_CATEGORY2 . " WHERE category2_id=members_cat2) cat2,members_cat2,members_educ,(SELECT incomecategories_bracket FROM " . TABLE_INCOMECATEGORIES . " WHERE incomecategories_id=m.incomecategories_id) incomecategories,incomecategories_id,(SELECT clientlanguages_name FROM " . TABLE_CLIENTLAGUAGES . " WHERE clientlanguages_id=members_lang1) clientlanguages1,members_lang1,(SELECT clientlanguages_name FROM " . TABLE_CLIENTLAGUAGES . " WHERE clientlanguages_id=m.members_lang2) clientlanguages2,members_lang2 FROM " . TABLE_MEMBERS . " m WHERE m.entity_idno='" . Common::tep_db_prepare_input($_POST['keyparam']) . "'";
            // $fieldlist = array('name', 'members_no', 'members_regdate', 'members_enddate');
            // $newgrid->keyfield = 'members_idno';
            // Common::getlables("9,1241,1019,1084", "", "", $Conn);
            // $gridcolumnnames = array(Common::$lablearray['9'], Common::$lablearray['1241'], Common::$lablearray['1019'], Common::$lablearray['1084']);
            // $actionlinks = "<a href='#'  onClick=\"getinfo('" . $_POST['frmid'] . "',$('body').data('gridchk'),'edit','','load.php','M')\" title ='" . $grid_lables_lablearray['272'] . "'><img src='images/edit.png' border='0'></a>";
            $clientId = $_POST['keyparam'];
            $searchValue = $_POST['search']['value'];

            $query = sprintf(" FROM " . TABLE_MEMBERS . " WHERE  entity_idno ='%s' AND (members_no LIKE'%%%s%%'  OR members_firstname LIKE'%%%s%%' OR members_lastname LIKE'%%%s%%')", $clientId, $searchValue, $searchValue, $searchValue);
            Common::getlables("1159,1241,887,900,1019,1071", "", "", $Conn);
            NewGrid::$columntitle = array(
                Common::$lablearray['1159'],
                Common::$lablearray['1241'],
                Common::$lablearray['887'],
                Common::$lablearray['900'],
                Common::$lablearray['1019'],
                Common::$lablearray['1071']
            );

            NewGrid::$fieldlist = array(
                'members_idno',
                'members_no',
                'members_firstname',
                'members_lastname',
                'members_regdate',
                'members_enddate'
            );

            NewGrid::$grid_id = 'grid_' . ($_POST['keyparam'] ?? '');
            NewGrid::$request = $_POST;
            NewGrid::$sSQL = $query;
            NewGrid::$order = ' ORDER BY members_idno ';
            NewGrid::$searchcatparam = $pageparams;

            $data = NewGrid::getData();

            echo  $data;

            exit();
            
            break;

        case 'CDOCS':

            $query = "SELECT d.document_id,d.document_serial,documenttypes_name_en documenttype,d.document_issuedate,d.document_docexpiry,d.document_issueauthority FROM " .TABLE_DOCUMENT . " d, ".TABLE_DOCUMENTTYPES." t WHERE d.clientcode='".$_POST['keyparam']."' AND t.documenttypes_id=d.documenttypes_id";
            $fieldlist = array('documenttype', 'document_serial', 'document_issuedate','document_docexpiry', 'document_issueauthority');
            $newgrid->keyfield = 'document_id';
            Common::getlables("1734,277,905,1062,628", "", "", $Conn);
            $gridcolumnnames = array(Common::$lablearray['1734'], Common::$lablearray['277'], Common::$lablearray['905'], Common::$lablearray['1062'], Common::$lablearray['628']);
            $actionlinks = "<a href='#'  onClick=\"getinfo('" . $_POST['frmid'] . "',$('body').data('gridchk'),'search','DOCS','load.php','')\" title ='" . $grid_lables_lablearray['272'] . "'><img src='images/edit.png' border='0'></a>";
    
            break;

         case 'SHATRAN':
            
            Common::getlables("317,298,1383,1707,1384", "", "", $Conn);

            NewGrid::$actionlinks = "<a class='divlinks' href='#'  onClick=\"getinfo('" . $_POST['frmid'] . "',$( 'body').data( 'gridchk'),'reverse','','addedit.php')\" title ='" . $grid_lables_lablearray['272'] . "'><img src='images/icons/withdraw.png' border='0'></a>";
           
            DataTable::$where_condition =" product_prodid='".$formdata['product_prodid']."' AND client_idno='".Common::replaces_underscores($_GET['clientidno'])."'";
            NewGrid::$order =' ORDER BY tday DESC ';
            NewGrid::$keyfield = 'transactioncode';
            NewGrid::$columntitle = array('',  Common::$lablearray['317'], Common::$lablearray['298'], Common::$lablearray['1383'], Common::$lablearray['1707'], Common::$lablearray['1384']);
            NewGrid::$fieldlist = array('transactioncode', 'transactioncode', 'tday', 'noofshares', 'norminalval', 'sharevalue');
            NewGrid::$sSQL = " FROM " . TABLE_SHATRANSACTIONS; //." WHERE savaccounts_account='".$_GET['account']."' AND product_prodid='".$_GET['product_prodid']."' AND members_idno='".Common::replaces_underscores($_GET['memid'])."'";
            NewGrid::$searchcatparam = $pageparams;
            NewGrid::$grid_id = 'grid_' . $_POST['keyparam'];

            NewGrid::$request = $_POST;

            if (isset($_POST['grid_id'])):
                echo NewGrid::getData();
            else:
                echo NewGrid::generateDatatableHTML();
            endif;

            exit();

            break;

        case 'SAVTRAN':
            
            Common::getlables("317,301,1208,271,1287,1265,1287,249,1199", "", "", $Conn);

//            if ($_POST['keyparam'] == 'savdata'):
//                echo "MSG " . Common::$lablearray['1199'];
//                exit();
//            endif;

            NewGrid::$actionlinks = "<a class='divlinks' href='#'  onClick=\"getinfo('" . $_POST['frmid'] . "',$( 'body').data( 'gridchk'),'reverse','','addedit.php')\" title ='" . $grid_lables_lablearray['272'] . "'><img src='images/icons/withdraw.png' border='0'></a>";
//            toolbar
//            $newgrid->Conn = $Conn;            
//            $newgrid->sp_code = 'SAVTRAN';
//            
//            $newgrid->sp_parameters[] = array('name' => 'code', 'value' => 'SAVTRAN');  
//            $newgrid->sp_parameters[] = array('name' => 'account', 'value' =>$_GET['account']);
//            $newgrid->sp_parameters[] = array('name' => 'productid', 'value' =>$_GET['product_prodid']);
//            $newgrid->sp_parameters[] = array('name' => 'memid', 'value' =>Common::replaces_underscores($_GET['memid']));
//            $newgrid->sp_parameters[] = array('name' => 'asat', 'value' =>'');
//            
//            $fieldlist = array('savtransactions_tday', 'transactioncode', 'transactiontypes_code', 'savtransactions_amount', 'savtransactions_balance', 'cheqs_no');
//           
//            $newgrid->keyfield = 'transactioncode';            
//            $newgrid->queryoptions['productid'] = $_GET['product_prodid'];
//            $newgrid->queryoptions['account'] = $_GET['account'];
//            $newgrid->queryoptions['memid'] = Common::replaces_underscores($_GET['memid']);
//            $newgrid->queryoptions['asat'] = '';
//            $newgrid->queryoptions['container'] = 'section2';
//            
//            
//            $gridcolumnnames = array(Common::$lablearray['317'], Common::$lablearray['301'], Common::$lablearray['1208'], Common::$lablearray['271'], Common::$lablearray['1287'], Common::$lablearray['1265']);
           
            DataTable::$where_condition =" savaccounts_account='".$formdata['txtsavaccount']."' AND product_prodid='".$formdata['product_prodid']."' AND members_idno='".Common::replaces_underscores($_GET['memid'])."'";
            NewGrid::$order =' ORDER BY savtransactions_tday DESC ';
            NewGrid::$keyfield = 'transactioncode';
            NewGrid::$columntitle = array('',  Common::$lablearray['317'], Common::$lablearray['1208'], Common::$lablearray['271'], Common::$lablearray['249'], Common::$lablearray['1265']);
            NewGrid::$fieldlist = array('transactioncode', 'transactioncode', 'savtransactions_tday', 'transactiontypes_code', 'savtransactions_amount', 'savtransactions_balance', 'cheqs_no');
            NewGrid::$sSQL = " FROM " . TABLE_SAVTRANSACTIONS; //." WHERE savaccounts_account='".$_GET['account']."' AND product_prodid='".$_GET['product_prodid']."' AND members_idno='".Common::replaces_underscores($_GET['memid'])."'";
            NewGrid::$searchcatparam = $pageparams;
            NewGrid::$grid_id = 'grid_' . $_POST['keyparam'];

            NewGrid::$request = $_POST;

            if (isset($_POST['grid_id'])):
                echo NewGrid::getData();
            else:
                echo NewGrid::generateDatatableHTML();
            endif;

            exit();

            break;

        case 'TDS':

            Common::getlables("301,317", "", "", $Conn);
            $actionlinks = "<a href='#'  onClick=\"getinfo('" . $_POST['frmid'] . "',$( 'body').data( 'gridchk'),'reverse','','addedit.php')\" title ='" . Common::$lablearray['272'] . "'><img src='images/icons/withdraw.png' border='0'></a>";
            $query = "SELECT timedeposit_number, product_prodid FROM " . TABLE_TDEPOSIT . " ORDER BY timedeposit_date DESC ";
            $fieldlist = array('timedeposit_number', 'product_prodid');
            $newgrid->keyfield = 'timedeposit_number';
            $gridcolumnnames = array(Common::$lablearray['301'], Common::$lablearray['317']);

            break;

        case 'TDTRAN':

            // Common::defineCosntants('TDEPOSIT');
            Common::getlables("272,301,1601,317,1594,271,197,1624,1526,1675,1028", "", "", $Conn);
            $actionlinks = "<a href='#'  onClick=\"getinfo('" . $_POST['frmid'] . "',$( 'body').data( 'gridchk'),'reverse','','addedit.php')\" data-balloon='" . Common::$lablearray['1526'] . "' data-balloon-pos='down' ><img src='images/icons/reverse.png' border='0'></a> <a href='#'  onClick=\"getinfo('" . $_POST['frmid'] . "',$( 'body').data( 'gridchk'),'edit','','load.php')\" data-balloon='" . Common::$lablearray['1675'] . "' data-balloon-pos='up'><img src='images/icons/renew.png' border='0'></a> <a href='#'  onClick=\"getinfo('" . $_POST['frmid'] . "',$( 'body').data( 'gridchk'),'loadform','','load.php')\" data-balloon='" . Common::$lablearray['1028'] . "' data-balloon-pos='down'><img src='images/icons/withdraw.png' border='0'></a>";
            $query = "SELECT d.product_prodid, t.transactioncode,t.timedeposit_number,t.timedeposit_date,t.timedeposit_interestrate,t.timedeposit_amount,t.timedeposit_status,t.cheqs_no FROM " . TABLE_TDEPOSITTRANS . " t ," . TABLE_TDEPOSIT . " d  WHERE t.timedeposit_number=d.timedeposit_number AND d.client_idno='" . $_GET['cid'] . "' ORDER BY t.timedeposit_date DESC ";
            $fieldlist = array('transactioncode', 'timedeposit_number', 'timedeposit_date', 'timedeposit_interestrate', 'timedeposit_amount', 'timedeposit_status');
            $newgrid->keyfield = 'transactioncode';
            $newgrid->queryoptions['product_prodid'] = $_GET['product_prodid'];
            $newgrid->queryoptions['cid'] = $_GET['cid'];
            $newgrid->queryoptions['container'] = 'section2';
            $gridcolumnnames = array(Common::$lablearray['301'], Common::$lablearray['1601'], Common::$lablearray['317'], Common::$lablearray['1594'], Common::$lablearray['271'], Common::$lablearray['197']);

            break;

        case 'MEMSAVACC':
        case 'INDSAVACC':
        case 'GRPSAVACC':
        case 'BUSSAVACC': //Client Savings Accounts

            Common::getlables("1665,9,296,1633", "", "", $Conn);

            $query = Savings::getSavingsAccounts($pageparams, "", $cWhere);
         
            NewGrid::$actionlinks = "<a class='divlinks' onClick=\"getinfo('" . $_POST['frmid'] . "',$( 'body').data( 'gridchk'),'edit','','load.php')\" data-balloon='" . Common::$lablearray['1665'] . "'  data-balloon-pos='up' data-balloon-length='large'>&nbsp;<img src='images/plus.gif' border='0' >&nbsp;</span>";

            
            //  NewGrid::$fieldlist= array('name',  'savaccounts_account','product_prodid');
//            if ($pageparams == 'MEMSAVACC'):
//                $newgrid->keyfield = 'members_idno';
//            else:
//                $newgrid->keyfield = 'savaccounts_id';
//            endif;

            NewGrid::$keyfield = "savaccounts_id";           
            NewGrid::$request = $_POST;
            NewGrid::$sSQL = $query;
            NewGrid::$searchcatparam = $pageparams;
            NewGrid::$columntitle = array('', Common::$lablearray['296'], Common::$lablearray['9'], Common::$lablearray['1633']);
            NewGrid::$grid_id = 'grid_' . $_POST['keyparam'];

            if (isset($_POST['grid_id'])):
              //   print_r($_POST);                
               echo NewGrid::getData();
               
            else:
               echo NewGrid::generateDatatableHTML();
            endif;
            exit();
            break;

        case 'GRPLOANS':
            if (isset($_GET['searchterm'])) {
                $cWhere = $cWhere . " OR l.loan_number LIKE '%" . filter_input(INPUT_GET, 'searchterm') . "%'";
            }
            $query = "SELECT c.entity_name name,l.loan_number,l.client_idno,loan_amount,loan_startdate FROM " . TABLE_LOAN . " l," . TABLE_ENTITY . " c  WHERE c.entity_idno=l.client_idno AND   l.loan_number NOT IN (SELECT w.loan_number FROM " . TABLE_WRITTENOFF . ' w WHERE w.loan_number=l.loan_number)';
            $fieldlist = array('loan_number', 'name', 'client_idno', 'loan_amount', 'loan_startdate');
            $newgrid->keyfield = 'loan_number';
            Common::getlables("1097,9,1093,1099,1098", "", "", $Conn);
            $gridcolumnnames = array(Common::$lablearray['1097'], Common::$lablearray['9'], Common::$lablearray['1093'], Common::$lablearray['1099'], Common::$lablearray['1098']);
            break;

        case 'MEMLOANS':
        case 'INDLOANS':
        case 'BUSLOANS':

            // DO TO: Add enhanacement query for group member loans

            if (isset($_GET['searchterm'])) {
                $cWhere = $cWhere . " OR l.loan_number LIKE '%" . filter_input(INPUT_GET, 'searchterm') . "%'";
            }

            $query = "SELECT CONCAT(c.client_surname,' ',c.client_firstname,' ',c.client_middlename) name,l.loan_number,l.client_idno,loan_amount,loan_startdate FROM " . TABLE_LOAN . " l," . TABLE_VCLIENTS . " c ";
            DataTable::$where_condition = " c.client_idno=l.client_idno AND " . $clienttype . " AND  l.loan_number NOT IN (SELECT w.loan_number FROM " . TABLE_WRITTENOFF . " w WHERE w.loan_number=l.loan_number)";
            $fieldlist = array('loan_number', 'name', 'client_idno', 'loan_amount', 'loan_startdate');
            $newgrid->keyfield = 'loan_number';
            Common::getlables("1097,9,1093,1099,1098", "", "", $Conn);
            $gridcolumnnames = array(Common::$lablearray['1097'], Common::$lablearray['9'], Common::$lablearray['1093'], Common::$lablearray['1099'], Common::$lablearray['1098']);

            break;

        case 'GRPLOANSREP':

            if (isset($_GET['searchterm'])) {
                $cWhere = $cWhere . " OR l.loan_number LIKE '%" . filter_input(INPUT_GET, 'searchterm') . "%'";
            }
            $newgrid->sortfield = ' l.loan_number';
            $newgrid->sortorder = ' DESC';

            $query = "SELECT c.entity_name name,l.loan_number,l.client_idno,loan_amount,loan_startdate FROM " . TABLE_LOAN . " l," . TABLE_ENTITY . " c  WHERE c.entity_idno=l.client_idno AND  l.loan_number NOT IN (SELECT w.loan_number FROM " . TABLE_WRITTENOFF . " w WHERE w.loan_number=l.loan_number) AND l.loan_number IN (SELECT loan_number FROM " . TABLE_DISBURSEMENTS . " d WHERE d.loan_number=l.loan_number)";
            $fieldlist = array('loan_number', 'name', 'client_idno', 'loan_amount', 'loan_startdate');
            $newgrid->keyfield = 'loan_number';
            Common::getlables("1097,9,1093,1099,1098", "", "", $Conn);
            $gridcolumnnames = array(Common::$lablearray['1097'], Common::$lablearray['9'], Common::$lablearray['1093'], Common::$lablearray['1099'], Common::$lablearray['1098']);

            break;
        case 'MEMLOANSREP':
        case 'INDLOANSREP':
        case 'BUSLOANSREP':
            if (isset($_GET['searchterm'])) {
                $cWhere = $cWhere . " OR l.loan_number LIKE '%" . filter_input(INPUT_GET, 'searchterm') . "%'";
            }
            $newgrid->sortfield = ' l.loan_number';
            $newgrid->sortorder = ' DESC';

            $query = "SELECT CONCAT(c.client_surname,' ',c.client_firstname,' ',c.client_middlename) name,l.loan_number,l.client_idno,loan_amount,loan_startdate FROM " . TABLE_LOAN . " l," . TABLE_VCLIENTS . " c  WHERE c.client_idno=l.client_idno AND " . $clienttype . " AND  l.loan_number NOT IN (SELECT w.loan_number FROM " . TABLE_WRITTENOFF . " w WHERE w.loan_number=l.loan_number) AND l.loan_number IN (SELECT loan_number FROM " . TABLE_DISBURSEMENTS . " d WHERE d.loan_number=l.loan_number)";
            $fieldlist = array('loan_number', 'name', 'client_idno', 'loan_amount', 'loan_startdate');
            $newgrid->keyfield = 'loan_number';
            Common::getlables("1097,9,1093,1099,1098", "", "", $Conn);
            $gridcolumnnames = array(Common::$lablearray['1097'], Common::$lablearray['9'], Common::$lablearray['1093'], Common::$lablearray['1099'], Common::$lablearray['1098']);

            break;

        case 'ADDINDLOANS':
            if (isset($_GET['searchterm'])) {
                $cWhere = $cWhere . " OR l.loan_number LIKE '%" . filter_input(INPUT_GET, 'searchterm') . "%'";
            }
            $query = "SELECT c.entity_name name,l.loan_number,l.client_idno,loan_amount,loan_startdate FROM " . TABLE_LOAN . " l," . TABLE_ENTITY . " c  WHERE c.entity_idno=l.client_idno AND   l.loan_number NOT IN (SELECT w.loan_number FROM " . TABLE_WRITTENOFF . ' w WHERE w.loan_number=l.loan_number)';
            $fieldlist = array('loan_number', 'name', 'client_idno', 'loan_amount', 'loan_startdate');
            $newgrid->keyfield = 'loan_number';
            Common::getlables("1097,9,1093,1099,1098", "", "", $Conn);
            $gridcolumnnames = array(Common::$lablearray['1097'], Common::$lablearray['9'], Common::$lablearray['1093'], Common::$lablearray['1099'], Common::$lablearray['1098']);
            break;



        case 'FEES':
            $query = "SELECT (SELECT fees_name FROM " . TABLE_FEES . " f WHERE f.fees_id=fc.fees_id )fees_id,(SELECT product_name from " . TABLE_PRODUCT . " p where p.product_prodid=fc.product_prodid)  product_prodid,feesconfig_amt,feesconfig_per FROM " . TABLE_FEESCONFIG . " fc WHERE product_prodid='" . $_POST['keyparam'] . "'";
            $fieldlist = array('fees_id', 'feesconfig_amt', 'feesconfig_per', 'product_prodid');
            $newgrid->keyfield = 'fees_id';
            Common::getlables("920,887,1180,1096", "", "", $Conn);
            //$actionlinks ="<a href='#'  onClick=\"getinfo('".$frmid."',bvar.data('gridchk'),'add','','load.php')\" title ='".$grid_lables_lablearray['272']."'><img src='images/edit.png' border='0'></a>";
            $gridcolumnnames = array(Common::$lablearray['920'], Common::$lablearray['887'], Common::$lablearray['1180'], Common::$lablearray['1096']);
            break;

        case 'USERROLES':
            $actionlinks = "<a href='#'  onClick=\"getinfo('" . $_POST['frmid'] . "',$('body').data('gridchk'),'edit','','load.php')\" ><img src='images/edit.png' border='0'></a><a href='#'  onClick=\"getinfo('" . $frmid . "',$('body').data('gridchk'),'delete','','addedit.php')\"><img src='images/delete.png' border='0'></a>";

            $query = "SELECT  user_id,user_username, user_firstname, user_lastname FROM " . TABLE_USERS . " ORDER BY user_id ASC";

            Common::getlables("3,238,240,584,159", "", "", Common::$connObj);
            $fieldlist = array('user_username', 'user_firstname', 'user_lastname');
            $newgrid->keyfield = 'user_id';
            $gridcolumnnames = array(Common::$lablearray['3'], Common::$lablearray['238'], Common::$lablearray['240']);

            break;

        case 'SAVPRODGLACC':
            $query = "SELECT (SELECT product_name from " . TABLE_PRODUCT . " p where p.product_prodid=pg. product_prodid) product_prodid,productconfig_ind, productconfig_grp,  productconfig_description FROM " . TABLE_PRODUCTCONFIG . " pg  WHERE product_prodid='" . $_POST['keyparam'] . "'AND productconfig_datagroup ='SAV_ACCOUNTS'";
            $fieldlist = array('product_prodid', 'productconfig_ind', 'productconfig_grp', 'productconfig_description');
            $newgrid->keyfield = 'productconfig_id';
            Common::getlables("1096,264,1178,885,272", "", "", Common::$connObj);
            $gridcolumnnames = array(Common::$lablearray['1096'], Common::$lablearray['1178'], Common::$lablearray['885'], Common::$lablearray['264']);

            break;

        case 'MODEMS':
            $actionlinks = "<a href='#'  onClick=\"getinfo('" . $_POST['frmid'] . "',$('body').data('gridchk'),'edit','','load.php')\" ><img src='images/edit.png' border='0'></a><a href='#'  onClick=\"getinfo('" . $frmid . "',$('body').data('gridchk'),'delete','','addedit.php')\"><img src='images/delete.png' border='0'></a>";
            $query = "SELECT * FROM " . TABLE_MODEM;
            $fieldlist = array('modem_name', 'modem_bitrate', 'modem_port');
            $newgrid->keyfield = 'modem_id';
            Common::getlables("1605,1606,1607", "", "", Common::$connObj);
            $gridcolumnnames = array(Common::$lablearray['1605'], Common::$lablearray['1606'], Common::$lablearray['1607']);
            break;

        case 'CALSAVCINT':

            if ($_GET['branch_code'] == "") {
                getlables("1436");
                echo "MSG " . $lablearray['1436'];
                exit();
            }
            if (substr($_POST['keyparam'], 0, 1) != "S") {
                getlables("1435");
                echo "MSG " . $lablearray['1435'];
                exit();
            }


            $newgrid->Conn = $Conn;
            $newgrid->sp_code = 'CALCSAVINT';
            $newgrid->sp_parameters[] = array('name' => 'branch_code', 'value' => $_GET['branch_code']);
            $newgrid->sp_parameters[] = array('name' => 'code', 'value' => 'CALCSAVINT');
            $newgrid->sp_parameters[] = array('name' => 'tdate', 'value' => Common::changeDateFromPageToMySQLFormat($_GET['date']));
            $newgrid->sp_parameters[] = array('name' => 'product_prodid', 'value' => $_POST['keyparam']);
            $newgrid->sp_parameters[] = array('name' => 'user_id', 'value' => $_SESSION['user_id']);
            $newgrid->sp_parameters[] = array('name' => 'client_regstatus', 'value' => $_GET['client_regstatus']);

            $newgrid->keyfield = 'client_idno';
            $fieldlist = array('name', 'client_idno', 'product_prodid', 'savaccounts_account', 'interest', 'periodstart', 'periodend');
            Common::getlables("9,1022,1096,296,1145,483,484", "", "", $Conn);
            $gridcolumnnames = array(Common::$lablearray['9'], Common::$lablearray['1022'], Common::$lablearray['1096'], Common::$lablearray['296'], Common::$lablearray['1145'], Common::$lablearray['483'], Common::$lablearray['484']);

            break;

        case 'LOANPRODGLACC':
        case 'PRODGLACC':
            $query = "SELECT (SELECT product_name from " . TABLE_PRODUCT . " p where p.product_prodid=pg. product_prodid) product_prodid,productconfig_ind, productconfig_grp,productconfig_value, productconfig_description FROM " . TABLE_PRODUCTCONFIG . " pg  WHERE product_prodid='" . $_POST['keyparam'] . "' AND productconfig_datagroup ='LOAN_ACCOUNTS'";

            $fieldlist = array('product_prodid', 'productconfig_ind', 'productconfig_grp', 'productconfig_value', 'productconfig_description');
            $newgrid->keyfield = 'productconfig_id';
            Common::getlables("1096,264,1178,885,272,761", "", "", $Conn);
            $gridcolumnnames = array(Common::$lablearray['1096'], Common::$lablearray['1178'], Common::$lablearray['885'], Common::$lablearray['761'], Common::$lablearray['264']);
            break;

        case 'CASHACCOUNTS':
            $query = "SELECT  cc.currencies_id,c.currencies_code,cashaccounts_id,cashaccounts_name,cc.chartofaccounts_accountcode FROM " . TABLE_CASHACCOUNTS . " as cc LEFT JOIN  " . TABLE_CURRENCIES . " c ON c.currencies_id=cc.currencies_id ORDER BY cashaccounts_name ASC";
            //$actionlinks ="<a href='#'  onClick=\"showValues('".$frmid."','','eval','','load.php',bvar.data('gridchk'))\" title ='".$grid_lables_lablearray['272']."'><img src='images/edit.png' border='0'></a><a href='#'  onClick=\"showValues('".$frmid."','user_username','eval','','load.php','',bvar.data('gridchk'))\" title ='".$grid_lables_lablearray['272']."'><img src='images/delete.png' border='0'></a>";		
            Common::getlables("442,306,350,26", "", "", $Conn);
            $_SESSION['reportname'] = Common::$lablearray['26'];
            $_SESSION['reporttitle'] = Common::$lablearray['26'];
            $_SESSION['downloadlist'] = $query;
            $fieldlist = array('cashaccounts_name', 'chartofaccounts_accountcode', 'currencies_code');
            $newgrid->keyfield = 'cashaccounts_id';
            $gridcolumnnames = array(Common::$lablearray['442'], Common::$lablearray['306'], Common::$lablearray['350']);
            break;

        case 'ROLEPERSMISSIONS':
            $actionlinks = "<a href='#'  onClick=\"getinfo('" . $_POST['frmid'] . "',$('body').data('gridchk'),'eval','','load.php')\" ><img src='images/edit.png' border='0'></a><a href='#'  onClick=\"getinfo('" . $frmid . "',$('body').data('gridchk'),'delete','','addedit.php')\"><img src='images/delete.png' border='0'></a>";

            $newgrid->extraFields[0] = "";
            $newgrid->cpara = "ROLEPERMISSIONS";
            $query = "SELECT roles_id, " . $roles_name . " as roles_name FROM " . TABLE_ROLES . " ORDER BY " . roles_name . " ASC";

            $fieldlist = array('roles_name');
            $newgrid->keyfield = 'roles_id';
            $gridcolumnnames = array('Role', 'Access To');

            break;
        case 'PAYMODES':

            switch ($_POST['keyparam']) {
                case 'CA':
                    echo DrawCashAccounts($_SESSION['roles']);
                    exit();
                    break 2;

                case 'CQ':
                case 'DB':
                    // bank
                    echo Common::DrawCheqBanks($_POST['keyparam']);
                    exit();
                    break 2;

                case 'SA':
                    echo Common::SavAccounts($_GET['id'], $Conn);
                    exit();
                    break 2;

                default:
                    exit();
                    break 2;
            }
            break;
        default:
            break;
    }

    $where = '';

    if ($query == "" && $newgrid->sp_code == "") {
        $lablearray = getlables("1271");
        echo "<span class='info'>" . $lablearray['1271'] . "</div>";
        exit();
    }

    // determine if user has used a search term
    if (isset($_GET['searchterm']) || $cWhere != "") {
        $query = $query . $cWhere;
    }

    $newgrid->lablesarray = $lables_array;

    $data =  $newgrid->getdata($query . $where, $fieldlist, $gridcolumnnames, $actionlinks, $onclick, $chkname);

    echo $data;               
   
    exit();
}

$main_array = array();

switch ($_POST['frmid']) {
    case 'frmsms':
        switch ($_POST['action']) {
            case 'edit':
                $message_results = $Conn->SQLSelect("SELECT * FROM " . TABLE_DEVICEMESSAGE . " WHERE devicemessage_id='" . $_POST['keyparam'] . "'");
                Common::push_element_into_array($main_array, 'action', 'add');
                Common::push_element_into_array($main_array, 'keyparam', $message_results[0]['devicemessage_id']);
                Common::push_element_into_array($main_array, 'txtDateCreated', $message_results[0]['devicemessage_date']);
                Common::push_element_into_array($main_array, 'txtNumber', $message_results[0]['tel']);
                Common::push_element_into_array($main_array, 'txtMessage', $message_results[0]['devicemessage_msg']);
                $jason = json_encode(array('data' => $main_array));
                $jason = str_replace("\\\\", '', $jason);
                echo $jason;
                break;

            default:
                $slnr = '';

                $formdata = Common::decodeSerialisedPagedata($_POST['pageparams']);

                // GET SELECTED CLIENTS
                $selectedloans = preg_grep('/^grid_checkbox(\w+)/i', array_keys($formdata));

                if (count($selectedloans) > 0):

                    foreach ($selectedloans as $key => $loan_number) {
                        $lnr_array[] = $formdata[$loan_number];
                    }

                    $slnr = implode(",", $lnr_array);


                endif;

                Common::prepareParameters($parameters, 'code', 'SMS');
                Common::prepareParameters($parameters, 'branch_code', $formdata['branch_code']);
                Common::prepareParameters($parameters, 'asatdate', Common::changeDateFromPageToMySQLFormat($formdata['txtDate']));
                Common::prepareParameters($parameters, 'product_prodid', $formdata['product_prodid']);
                Common::prepareParameters($parameters, 'n_days', $formdata['txtnDays']);
                Common::prepareParameters($parameters, 'name', '');
                Common::prepareParameters($parameters, 'acode', $formdata['areacode_code']);
                Common::prepareParameters($parameters, 'loannumbers', $slnr);

                $results = Common::common_sp_call(serialize($parameters), '', Common::$connObj, false);
                getlables("1610");
                echo "MSG " . $results[0]['msg'] . $lablearray['1610'];
                break;
        }
        break;
    case 'frmmodemsettings':
        switch ($_POST['action']) {
            case 'edit':
                $modem_results = $Conn->SQLSelect("SELECT * FROM " . TABLE_MODEM . " WHERE modem_id='" . $_POST['keyparam'] . "'");

                Common::push_element_into_array($main_array, 'action', 'update');
                Common::push_element_into_array($main_array, 'keyparam', $modem_results[0]['modem_id']);
                Common::push_element_into_array($main_array, 'txtDevice', $modem_results[0]['modem_name']);
                Common::push_element_into_array($main_array, 'cmbBitsPerSecond', $modem_results[0]['modem_bitrate']);
                Common::push_element_into_array($main_array, 'txtPort', $modem_results[0]['modem_port']);
                $jason = json_encode(array('data' => $main_array));
                $jason = str_replace("\\\\", '', $jason);
                echo $jason;
                break;

            default:
                break;
        }
        break;
    case 'frmuserroles':
        switch ($_POST['action']) {
            case 'edit':
                $userroles_results = $Conn->SQLSelect("SELECT user_id, user_username,concat(user_username,' ',user_firstname,' ',user_lastname) as Name,user_email_address,last_login FROM " . TABLE_USERS . " WHERE user_id='" . $_POST['keyparam'] . "'");

                Common::push_element_into_array($main_array, 'action', 'update');
                Common::push_element_into_array($main_array, 'user_id', $userroles_results[0]['user_id']);
                Common::push_element_into_array($main_array, 'Name', $userroles_results[0]['Name']);
                Common::push_element_into_array($main_array, 'user_username', $userroles_results[0]['user_username']);
                Common::push_element_into_array($main_array, 'user_email_address', $userroles_results[0]['user_email_address']);
                Common::push_element_into_array($main_array, 'last_login', $userroles_results[0]['last_login']);

                $roles_array = $Conn->SQLSelect("SELECT r.roles_id,COALESCE(u.user_id,'')user_id FROM " . TABLE_ROLES . ' r LEFT JOIN ' . TABLE_USERROLES . " u ON u.roles_id=r.roles_id AND  u.user_id='" . $_POST['keyparam'] . "'");

                foreach ($roles_array as $key => $val):
                    if ($val['user_id'] != ""):
                        Common::push_element_into_array($main_array, 'Roles' . $val['roles_id'], $val['roles_id']);
                    else:
                        Common::push_element_into_array($main_array, 'Roles' . $val['roles_id'], '');
                    endif;

                endforeach;

                $jason = json_encode(array('data' => $main_array));
                $jason = str_replace("\\\\", '', $jason);
                echo $jason;
                break;
            default:
                break;
        }
        break;
    case 'frmroles':

        switch ($_POST['action']) {
            case 'edit':
//                $roles_results = $Conn->SQLSelect("SELECT roles_id,".$roles_name." as roles_name FROM " . TABLE_ROLES . " WHERE  roles_id='" . tep_db_prepare_input($_POST['keyparam']) . "'");
                Common::push_element_into_array($main_array, 'roles_id', $roles_results[0]['roles_id']);
                Common::push_element_into_array($main_array, 'roles_name', $roles_results[0]['roles_name']);
                Common::push_element_into_array($main_array, 'action', 'update');
                $jason = json_encode(array('data' => $main_array));
                $jason = str_replace("\\\\", '', $jason);
                echo $jason;
                break;
            default:
                break;
        }
        break;

    case 'frmmanageusers':


        $formdata = Common::decodeSerialisedPagedata($_POST['pageparams']);

        $branches = $formdata['branches'];

        if (isset($formdata['usercode'])) {

            if ($formdata['usercode'] != "") {
                //  $cWhere = " AND ob.usercode='" . $formdata['usercode'] . "'";
            }
        }

        $results_query = tep_db_query("SELECT op.branch_code, bankbranches_name FROM " . TABLE_USERBRANCHES . " us," . TABLE_OPERATORBRANCHES . " op WHERE op.branch_code=us.branch_code  AND op.licence_build='" . tep_db_prepare_input($formdata['licence_build']) . "' " . $cWhere);

        $operatorbranches = array();

        while ($cats = tep_db_fetch_array($results_query)) {
            $operatorbranches[$cats['branch_code']] = $cats['bankbranches_name'];
        }

        if (count($operatorbranches) == 0) {
            $operatorbranches = array();
        }

        // echo "SELECT op.branch_code, bankbranches_name FROM " . TABLE_USERBRANCHES . " us," . TABLE_OPERATORBRANCHES . " op WHERE op.branch_code=us.branch_code  AND op.licence_build='" . tep_db_prepare_input($formdata['licence_build']) . "' " . $cWhere;
        echo DrawComboFromArray($operatorbranches, 'branch_code', '', 'combo', '', 'multiple');
        break;

    case 'frmfees':

        $loan_array = call_user_func_array('array_merge', $Conn->SQLSelect("SELECT loan_number,client_idno FROM " . TABLE_LOAN . "  WHERE loan_number='" . tep_db_prepare_input($_POST['keyparam']) . "'"));
        Common::push_element_into_array($main_array, 'txtlnr', $loan_array['loan_number']);
        Common::push_element_into_array($main_array, 'client_idno', $loan_array['client_idno']);
        $jason = json_encode(array('data' => $main_array));
        $jason = str_replace("\\\\", '', $jason);
        echo $jason;
        break;

    case 'frmreportsui':

        switch ($_POST['action']) {
            case 'edit':
                $client_array = $Conn->SQLSelect("SELECT client_idno,savaccounts_account,product_prodid FROM " . TABLE_SAVACCOUNTS . "  WHERE savaccounts_id='" . tep_db_prepare_input($_POST['keyparam']) . "'");

                Common::push_element_into_array($main_array, 'client_idno',$client_array[0]['client_idno']);
                Common::push_element_into_array($main_array, 'savaccounts_account', $client_array[0]['savaccounts_account']);
                Common::push_element_into_array($main_array, 'product_prodid', $client_array[0]['product_prodid']);
                $jason = json_encode(array('data' => $main_array));
                $jason = str_replace("\\\\", '', $jason);
                echo $jason;
                break 2;

            case 'loadelement':
                echo Common::getreportColumnList($_POST['keyparam'], $Conn);
                break;
            default:
                echo Common::getreportColumnList($_POST['keyparam'], $Conn);
                break;
        }
        break;

    case 'frmreportsuiportrsk':

        echo '<p>Arrears Classifications in Days</p>'
        . '<table cellpadding="2" cellspacing="0" width="100%">'
        . '<tr>'
        . '<td>Class 1: From <input value="1" id="class1a" type="text" name="class1a">'
        . '</td>'
        . '<td>to <input value="60" id="class1b" type="text" name="class1b">'
        . '</td>'
        . '</tr>'
        . '<tr>'
        . '<td>Class 2: From <input value="61" id="class2a" type="text" name="class2a">'
        . '</td>'
        . '<td>to <input value="90" id="class2b" type="text" name="class2b">'
        . '</td>'
        . '</tr>'
        . '<tr>'
        . '<td>Class 3: From <input value="91" id="class3a" type="text" name="class3a">'
        . '</td>'
        . '<td>to <input value="120" id="class3b" type="text" name="class3b">'
        . '</td>'
        . '</tr>'
        . '<tr>'
        . '<td>Class 4: From <input value="121" id="class4a" type="text" name="class4a">'
        . '</td>'
        . '<td>to <input value="150" id="class4b" type="text" name="class4b">'
        . '</td>'
        . '</tr>'
        . '<tr>'
        . '<td>Class 5: From <input value="151" id="class5a" type="text" name="class5a">'
        . '</td>'
        . '<td> to <input value="180" id="class5b" type="text" name="class5b">'
        . '</td>'
        . '</tr>'
        . '<tr>'
        . '<td>Class 6: From <input value="181" id="class6a" type="text" name="class6a">'
        . '</td>'
        . '<td> to <input value="211" id="class6b" type="text" name="class6b">'
        . '</td>'
        . '</tr>'
        . '<tr>'
        . '<td>Class 7: <input value="212" id="class7" type="text" name="class7">'
        . '</td>'
        . '<td></td>'
        . '</tr>'
        . '</table>';

        break;

    case 'frmLoanapp3':

        $client_array = call_user_func_array('array_merge', $Conn->SQLSelect("SELECT client_idno FROM " . TABLE_VCLIENTS . "  WHERE client_idno='" . tep_db_prepare_input($_POST['keyparam']) . "'"));

        switch ($_POST['keyparam']) {
            case 'LOANDISBURSE':
                break;

            case 'GUARANTOR1':
                Common::push_element_into_array($main_array, 'txtclientcode1', $client_array['client_idno']);
                break;

            case 'GUARANTOR2':
                Common::push_element_into_array($main_array, 'txtclientcode2', $client_array['client_idno']);
                break;

            case 'GUARANTOR3':
                Common::push_element_into_array($main_array, 'txtclientcode3', $client_array['client_idno']);
                break;

            default:
                break;
        }

        $jason = json_encode(array('data' => $main_array));
        $jason = str_replace("\\\\", '', $jason);
        echo $jason;

        break;
    case 'frmClients':
        switch ($_POST['action']) {

            case 'edit':

                if (preg_match('[G]', $_POST['keyparam']) || preg_match('[B]', $_POST['keyparam'])):

                    $client_array = call_user_func_array('array_merge', $Conn->SQLSelect("SELECT * FROM " . TABLE_ENTITY . "  WHERE entity_idno='" . tep_db_prepare_input($_POST['keyparam']) . "'"));
                    Common::push_element_into_array($main_array, 'client_idno', $client_array['entity_idno']);
                    Common::push_element_into_array($main_array, 'client_regdate', Common::changeMySQLDateToPageFormat($client_array['entity_regdate']));
                    Common::push_element_into_array($main_array, 'div_name', $client_array['entity_name']);
                    Common::push_element_into_array($main_array, 'keyparam', $_POST['keyparam']);
                    Common::push_element_into_array($main_array, 'entity_name', $client_array['entity_name']);
                    Common::push_element_into_array($main_array, 'client_postad', $client_array['entity_postad']);
                    Common::push_element_into_array($main_array, 'client_city', $client_array['entity_city']);
                    Common::push_element_into_array($main_array, 'client_addressphysical', $client_array['entity_addressphysical']);
                    Common::push_element_into_array($main_array, 'client_tel1', $client_array['entity_tel1']);
                    Common::push_element_into_array($main_array, 'client_tel2', $client_array['entity_tel2']);
                    Common::push_element_into_array($main_array, 'bussinesssector_code', $client_array['bussinesssector_code']);
                    Common::push_element_into_array($main_array, 'client_regstatus', $client_array['entity_regstatus']);
                    Common::push_element_into_array($main_array, 'areacode_code', $client_array['areacode_code']);
                    Common::push_element_into_array($main_array, 'branch_code', $client_array['branch_code']);
                    Common::push_element_into_array($main_array, 'costcenters_code', $client_array['costcenters_code']);
                    
                elseif (preg_match('[I]', $_POST['keyparam'])):

                    $client_array = call_user_func_array('array_merge', $Conn->SQLSelect("SELECT * FROM " . TABLE_VCLIENTS . "  WHERE client_idno='" . tep_db_prepare_input($_POST['keyparam']) . "'"));
                    Common::push_element_into_array($main_array, 'branch_code', $client_array['branch_code']);
                    Common::push_element_into_array($main_array, 'clientcode', $client_array['clientcode']);
                    Common::push_element_into_array($main_array, 'client_idno', $client_array['client_idno']);
                    Common::push_element_into_array($main_array, 'keyparam', $client_array['client_idno']);
                    Common::push_element_into_array($main_array, 'div_name', $client_array['client_surname'] . ' ' . $client_array['client_middlename'] . ' ' . $client_array['client_firstname'] . '<br>' . $client_array['client_idno']);
                    Common::push_element_into_array($main_array, 'branch_code', $client_array['branch_code']);
                    Common::push_element_into_array($main_array, 'client_firstname', $client_array['client_firstname']);
                    Common::push_element_into_array($main_array, 'client_middlename', $client_array['client_middlename']);
                    Common::push_element_into_array($main_array, 'client_surname', $client_array['client_surname']);
                    Common::push_element_into_array($main_array, 'client_gender', $client_array['client_gender']);

                    Common::push_element_into_array($main_array, 'bussinesssector_code', $client_array['bussinesssector_code']);
                    Common::push_element_into_array($main_array, 'client_regdate', Common::changeMySQLDateToPageFormat($client_array['client_regdate']));
                    Common::push_element_into_array($main_array, 'client_postad', $client_array['client_postad']);
                    Common::push_element_into_array($main_array, 'client_city', $client_array['client_city']);
                    Common::push_element_into_array($main_array, 'client_regstatus', $client_array['client_regstatus']);
                    Common::push_element_into_array($main_array, 'client_addressphysical', $client_array['client_addressphysical']);
                    Common::push_element_into_array($main_array, 'areacode_code', $client_array['areacode_code']);
                    Common::push_element_into_array($main_array, 'client_tel1', $client_array['client_tel1']);
                    Common::push_element_into_array($main_array, 'client_tel2', $client_array['client_tel2']);
                    Common::push_element_into_array($main_array, 'client_emailad', $client_array['client_emailad']);
                    Common::push_element_into_array($main_array, 'client_costcenter', $client_array['client_costcenter']);
                    Common::push_element_into_array($main_array, 'client_enddate', Common::changeMySQLDateToPageFormat($client_array['client_enddate']));
                    Common::push_element_into_array($main_array, 'category1_id1', $client_array['client_cat1']);
                    Common::push_element_into_array($main_array, 'category2_id2', $client_array['client_cat2']);

                    $document_array = call_user_func_array('array_merge', $Conn->SQLSelect("SELECT * FROM " . TABLE_DOCUMENT . "  WHERE clientcode='" . $client_array['client_idno'] . "'"));

                    Common::push_element_into_array($main_array, 'documenttypes_id', $document_array['documenttypes_id']);
                    Common::push_element_into_array($main_array, 'document_issuedate', Common::changeMySQLDateToPageFormat($document_array['document_issuedate']));
                    Common::push_element_into_array($main_array, 'document_docexpiry', Common::changeMySQLDateToPageFormat($document_array['document_docexpiry']));
                    Common::push_element_into_array($main_array, 'document_priority', $document_array['document_priority']);
                
               
                  

                elseif (preg_match('[M]', $_POST['keyparam'])):

                    $client_array = call_user_func_array('array_merge', $Conn->SQLSelect("SELECT * FROM " . TABLE_MEMBERS . "  WHERE members_idno='" . $_POST['keyparam'] . "'"));

                    Common::push_element_into_array($main_array, 'branch_code', $client_array['branch_code']);
                    Common::push_element_into_array($main_array, 'keyparam', $client_array['entity_idno']);
                    Common::push_element_into_array($main_array, 'client_idno', $client_array['entity_idno']);
                    Common::push_element_into_array($main_array, 'members_idno', $client_array['members_idno']);
                    Common::push_element_into_array($main_array, 'member_no', $client_array['members_no']);
                    Common::push_element_into_array($main_array, 'member_firstname', $client_array['members_firstname']);
                    
                    Common::push_element_into_array($main_array, 'member_middlename', $client_array['members_middlename']);
                    Common::push_element_into_array($main_array, 'member_lastname', $client_array['members_lastname']);
                    
                    Common::push_element_into_array($main_array, 'div_name2', $client_array['members_firstname'].' '.$client_array['members_middlename'].' '.$client_array['members_lastname'].'<p>'.$client_array['members_idno'].'</p>');
                    
                    Common::push_element_into_array($main_array, 'member_maritalstate', $client_array['members_maritalstate']);

                    Common::push_element_into_array($main_array, 'member_regdate', Common::changeMySQLDateToPageFormat($client_array['members_regdate']));
                    Common::push_element_into_array($main_array, 'member_enddate', Common::changeMySQLDateToPageFormat($client_array['members_enddate']));
                    Common::push_element_into_array($main_array, 'member_dependants', $client_array['members_dependants']);
                    Common::push_element_into_array($main_array, 'member_children', $client_array['members_children']);
                    Common::push_element_into_array($main_array, 'member_category1_id1', $client_array['members_cat1']);
                    Common::push_element_into_array($main_array, 'member_category2_id2', $client_array['members_cat2']);
                    Common::push_element_into_array($main_array, 'member_educationlevel_id', $client_array['members_educ']);
                    Common::push_element_into_array($main_array, 'member_incomecategories_id', $client_array['incomecategories_id']);
                    Common::push_element_into_array($main_array, 'member_clientlanguages_id1', $client_array['members_lang1']);
                    Common::push_element_into_array($main_array, 'member_clientlanguages_id2', $client_array['members_lang2']);
                    Common::push_element_into_array($main_array, 'member_regstatus', $client_array['members_regstatus']);
                    Common::push_element_into_array($main_array, 'member_income', $client_array['members_income']);
                    Common::push_element_into_array($main_array, 'member_email', $client_array['members_email']);

                endif;

                Common::push_element_into_array($main_array, 'action', 'update');

                // $jason = json_encode(array('data' => $main_array));
                // $jason = str_replace("\\\\", '', $jason);
                echo Common::createResponse('form', '', [], $main_array);
                break 2;

            default:
                break 2;
        }
        break;

    case 'frmrolemodules':
        switch ($_POST['action']) {
            case 'edit':
            case 'eval':
                $rolesmodule_results = $Conn->SQLSelect("SELECT modules_id FROM " . TABLE_ROLESMODULES . " WHERE roles_id='" . $_POST['keyparam'] . "'");

                // Common::push_element_into_array($main_array, 'action', $_POST['action']);
                $main_array = array();
                Common::push_element_into_array($main_array, 'keyparam', $_POST['keyparam']);
                Common::push_element_into_array($main_array, 'roles_id', $_POST['keyparam']);

                foreach ($rolesmodule_results as $key => $val):
                    Common::push_element_into_array($main_array, 'Modules' . $val['modules_id'], $val['modules_id']);
                endforeach;

                $cashacc_results = $Conn->SQLSelect("SELECT chartofaccounts_accountcode FROM " . TABLE_ROLESCASHACCOUNTS . " WHERE roles_id='" . $_POST['keyparam'] . "'");


                foreach ($cashacc_results as $key => $val):
                    Common::push_element_into_array($main_array, 'cashaccounts' . $val['chartofaccounts_accountcode'], $val['chartofaccounts_accountcode']);
                endforeach;

                $jason = json_encode(array('data' => $main_array));

                $jason = str_replace("\\\\", '', $jason);

                echo $jason;
                break 2;
            default:
                break 2;
        }
        break;
    case 'frmcashaccounts':
        switch ($_POST['action']) {

            case 'eval':
                $data_array = call_user_func_array('array_merge', $Conn->SQLSelect("SELECT  c.flag,cc.currencies_id,c.flag,c.currencies_code,cc.cashaccounts_id, cc.cashaccounts_name,cc.chartofaccounts_accountcode FROM " . TABLE_CASHACCOUNTS . " cc LEFT JOIN " . TABLE_CURRENCIES . " c ON c.currencies_id=cc.currencies_id WHERE cashaccounts_id='" . $_POST['keyparam'] . "'"));
                echo "formObj.action.value = 'update';\n";
                echo "formObj.cashaccounts_id.value = '" . $data_array['cashaccounts_id'] . "';\n";
                echo "formObj.cashaccounts_name.value = '" . $data_array['cashaccounts_name'] . "';\n";
                echo "SelectItemInList(\"chartofaccounts_accountcode\",\"" . $data_array['chartofaccounts_accountcode'] . "\");\n";
                echo "SelectItemInList(\"currencies_id\",\"" . $data_array['currencies_id'] . "\");\n";
                echo "document.getElementById('flag').innerHTML = \"<img border='0' src='../" . DIR_WS_FLAG_IMAGES . $data_array['flag'] . "'>\";\n";
                echo "formObj.action.value = 'update';";

                break;
            default:
                break;
        }
        break;

    case 'frmuserroles':
        switch ($_POST['action']) {

            case 'eval':
                $results_query = tep_db_query("SELECT user_id, user_username,concat(user_surname,' ',user_firstname,' ',user_lastname) as Name,user_email_address,last_login FROM " . TABLE_USERS . " WHERE user_id='" . $_POST['keyparam'] . "'");

                $results = tep_db_fetch_array($results_query);
                getlables("9,3,585,586");
                echo "document.getElementById('user_id').value = '" . $results['user_id'] . "';\n";

                $string = "<p> <b>" . $lablearray['9'] . "</b> : " . $results['Name'] . "</p>";
                $string .= "<p> <b>" . $lablearray['3'] . "</b> : " . $results['user_username'] . "</p>";
                $string .= "<p> <b>" . $lablearray['585'] . "</b> : " . $results['user_email_address'] . "</p>";
                $string .= "<p> <b>" . $lablearray['586'] . "</b> : " . $results['last_login'] . "</p>";

                echo "document.getElementById('user_username').innerHTML = \"" . $string . "\";\n";
                //echo $string;

                $roles_query = tep_db_query("SELECT roles_id FROM " . TABLE_USERROLES . " WHERE user_id='" . $_POST['keyparam'] . "'");

                while ($results = tep_db_fetch_array($roles_query)) {

                    if ($results['roles_id'] != "") {
                        echo "formObj.Roles" . $results['roles_id'] . ".checked =true;\n";
                    }
                }
                break;
            default:
                break;
        }
        break;
    case 'frmloanproductsettings1':
    case 'frmsaveproductsettings1':
        switch ($_POST['action']) {
            case 'edit':
            case 'loadform':
                if ($_POST['frmid'] == 'frmloanproductsettings1') {
                    $data_array = $Conn->SQLSelect("SELECT productconfig_description,productconfig_paramname,productconfig_value FROM " . TABLE_PRODUCTCONFIG . " WHERE productconfig_paramname IN('SAVINGS_GUARANTEE_AMOUNT_PER','SAVINGS_GUARANTEE_AMOUNT_ACTIVATED','SAVINGS_GUARANTEE_AMOUNT','NUMBER_OF_INSTALLMENTS_ACTIVATED','NUMBER_OF_INSTALLMENTS','MINIMUM_LOAN_AMOUNT','MAXIMUM_LOAN_AMOUNT_ACTIVATED','MAXIMUM_LOAN_AMOUNT','INTEREST_TYPE_ACTIVATED','INTEREST_TYPE','INTEREST_RATE_ACTIVATED','INTEREST_RATE','INSTALLMENT_TYPE_ACTIVATED','INSTALLMENT_TYPE','CURRENCIES_ID','NO_INT','LOAN_COM_FROM_SAV','INT_DAYS','INT_IN_ARR','INT_WEEKS','PEN_IN_ARR','PRI_IN_ARR','RECALC_INT','CHARGE_INT','PAY_PRIORITY','REF_PRIORITY','SERVICE_FEE','SAV_AT_REPAY','SAVING_AT_LOAN_REPAY_AMT','PULL_DUES_AFTER_PREPAYMENTS') AND product_prodid='" . tep_db_prepare_input($_POST['keyparam']) . "'");
                } else {
                    $data_array = $Conn->SQLSelect("SELECT productconfig_description,productconfig_paramname,productconfig_value FROM " . TABLE_PRODUCTCONFIG . " WHERE productconfig_paramname IN('MINIMUM_SAV_BAL','MINIMUM_SAV_BAL_ACTIVATED','MINIMUM_SAV_BAL_EARN','MINIMUM_SAV_BAL_EARN_ACTIVATED','SAV_INT_RATE','SAV_INT_PERIOD','CHARGE_ON_WITHDRAW','CURRENCIES_CODE','INT_START_DATE','INT_CAL_METHOD','CLIENTCODE_IS_SAVACC','PER_INT_TOPAY','CURRENCIES_ID') AND product_prodid='" . tep_db_prepare_input($_POST['keyparam']) . "'");
                }
                foreach ($data_array as $key => $value) {
                    Common::push_element_into_array($main_array, $value['productconfig_paramname'], $value['productconfig_value']);
                }
                $jason_data = json_encode(array('data' => $main_array));
                $jason_data = str_replace("\\\\", '', $jason_data);
                echo $jason_data;
                break;

            default:
                break;
        }

        break;
    case 'frmcoa':

        $results_acc = $Conn->SQLSelect("SELECT  chartofaccounts_revalue,currencies_id,chartofaccounts_groupcode,chartofaccounts_accountcode,chartofaccounts_parent,chartofaccounts_name,chartofaccounts_header,chartofaccounts_tgroup,chartofaccounts_description FROM " . TABLE_CHARTOFACCOUNTS . " WHERE  chartofaccounts_accountcode='" . $_POST['keyparam'] . "'");

        Common::push_element_into_array($main_array, 'chartofaccounts_name', $results_acc[0]["chartofaccounts_name"]);
        Common::push_element_into_array($main_array, 'chartofaccounts_parent', $results_acc[0]["chartofaccounts_parent"]);
        Common::push_element_into_array($main_array, 'chartofaccounts_accountcode', $results_acc[0]["chartofaccounts_accountcode"]);
        Common::push_element_into_array($main_array, 'chartofaccounts_oldaccountcode', $results_acc[0]["chartofaccounts_oldaccountcode"]);
        Common::push_element_into_array($main_array, 'chartofaccounts_tgroup', $results_acc[0]["chartofaccounts_tgroup"]);
        Common::push_element_into_array($main_array, 'currencies_id', $results_acc[0]["currencies_id"]);
        Common::push_element_into_array($main_array, 'action', 'update');

        if ($results_acc[0]["chartofaccounts_revalue"] == 'Y') {
            Common::push_element_into_array($main_array, 'chartofaccounts_revalue', '1');
        } else {
            Common::push_element_into_array($main_array, 'chartofaccounts_revalue', '0');
        }

        if ($results_acc[0]["chartofaccounts_header"] == 'Y') {
            Common::push_element_into_array($main_array, 'chartofaccounts_header', '1');
        } else {
            Common::push_element_into_array($main_array, 'chartofaccounts_header', '0');
        }
        $jason = json_encode(array('data' => $main_array));
        $jason = str_replace("\\\\", '', $jason);
        echo $jason;
        break;
    case 'frmShares':
        switch ($_POST['action']) {

            case 'loadform':

                $savacc_array = $Conn->SQLSelect("SELECT client_idno,CONCAT(client_surname,' ',client_firstname,' ',client_middlename) As Name FROM " . TABLE_VCLIENTS . "  WHERE client_idno='" . tep_db_prepare_input($_POST['keyparam']) . "'", TRUE);
                // $savacc_array = tep_db_fetch_array($savacc_query);
                Common::push_element_into_array($main_array, 'client_idno', $savacc_array[0]['client_idno']);
                Common::push_element_into_array($main_array, 'infoBox', $savacc_array[0]['Name'] . " : " . $savacc_array[0]['client_idno']);
                Common::push_element_into_array($main_array, 'action', 'add');
              //  Common::push_element_into_array($main_array, 'keyparam', '');
              //  Common::push_element_into_array($main_array, 'product_prodid', '');
              //  Common::push_element_into_array($main_array, 'txtOpenDate', '');
               // Common::push_element_into_array($main_array, 'txtsavaccount', '');
                $jason = json_encode(array('data' => $main_array));
                $jason = str_replace("\\\\", '', $jason);
                echo $jason;
                break;

            default:
//                $savacc_query = tep_db_query("SELECT CONCAT(client_surname,' ',client_firstname,' ',client_middlename) As Name,s.savaccounts_id,s.client_idno,savaccounts_account,s.product_prodid,savaccounts_opendate,savaccounts_closedate FROM " . TABLE_VCLIENTS . " c LEFT OUTER JOIN  " . TABLE_SAVACCOUNTS . " s ON c.client_idno=s.client_idno AND  s.savaccounts_id='" . tep_db_prepare_input($_POST['keyparam']) . "'");
//                $savacc_array = tep_db_fetch_array($savacc_query);
//                Common::push_element_into_array($main_array, 'client_idno', $savacc_array['client_idno']);
//                Common::push_element_into_array($main_array, 'action', 'update');
//                Common::push_element_into_array($main_array, 'keyparam', $savacc_array['savaccounts_id']);
//                Common::push_element_into_array($main_array, 'InfoBox', $savacc_array['Name'] . " : " . $savacc_array['client_idno']);
//                Common::push_element_into_array($main_array, 'product_prodid', $savacc_array['product_prodid']);
//                Common::push_element_into_array($main_array, 'txtOpenDate', Common::changeMySQLDateToPageFormat($savacc_array['savaccounts_opendate']));
//                Common::push_element_into_array($main_array, 'txtsavaccount', $savacc_array['savaccounts_account']);
//                $jason = json_encode(array('data' => $main_array));
//                $jason = str_replace("\\\\", '', $jason);
//                echo $jason;
                break;
                break;
        }
        break;
    case 'frmsavaccounts':
        switch ($_POST['action']) {
           
            case 'loadform':
            case 'edit':    
                $savacc_array = $Conn->SQLSelect("SELECT client_idno,CONCAT(client_surname,' ',client_firstname,' ',client_middlename) As Name FROM " . TABLE_VCLIENTS . "  WHERE client_idno='" . tep_db_prepare_input($_POST['keyparam']) . "'", TRUE);
                // $savacc_array = tep_db_fetch_array($savacc_query);
                Common::push_element_into_array($main_array, 'client_idno', $savacc_array[0]['client_idno']);
                Common::push_element_into_array($main_array, 'InfoBox', $savacc_array[0]['Name'] . " : " . $savacc_array[0]['client_idno']);
                Common::push_element_into_array($main_array, 'action', 'add');
                Common::push_element_into_array($main_array, 'keyparam', '');
                Common::push_element_into_array($main_array, 'product_prodid', '');
                Common::push_element_into_array($main_array, 'txtOpenDate', '');
                Common::push_element_into_array($main_array, 'txtsavaccount', '');
                $jason = json_encode(array('data' => $main_array));
                $jason = str_replace("\\\\", '', $jason);
                echo $jason;
                break;

            default:
                $savacc_query = tep_db_query("SELECT CONCAT(client_surname,' ',client_firstname,' ',client_middlename) As Name,s.savaccounts_id,c.client_idno,savaccounts_account,s.product_prodid,savaccounts_opendate,savaccounts_closedate FROM " . TABLE_VCLIENTS . " c LEFT OUTER JOIN  " . TABLE_SAVACCOUNTS . " s ON c.client_idno=s.client_idno AND  s.client_idno='" . tep_db_prepare_input($_POST['keyparam']) . "'");
                $savacc_array = tep_db_fetch_array($savacc_query);
                Common::push_element_into_array($main_array, 'client_idno', $savacc_array['client_idno']);
                Common::push_element_into_array($main_array, 'action', 'update');
                Common::push_element_into_array($main_array, 'keyparam', $savacc_array['savaccounts_id']);
                Common::push_element_into_array($main_array, 'InfoBox', $savacc_array['Name'] . " : " . $savacc_array['client_idno']);
                Common::push_element_into_array($main_array, 'product_prodid', $savacc_array['product_prodid']);
                Common::push_element_into_array($main_array, 'txtOpenDate', Common::changeMySQLDateToPageFormat($savacc_array['savaccounts_opendate']));
                Common::push_element_into_array($main_array, 'txtsavaccount', $savacc_array['savaccounts_account']);
                $jason = json_encode(array('data' => $main_array));
                $jason = str_replace("\\\\", '', $jason);
                echo $jason;
                break;
                break;
        }
        break;

    case 'frmcashentries':

        switch ($_POST['action']) {

            case 'loadform':
                $results = $Conn->SQLSelect("SELECT IF(ISNULL(SUM(generalledger_debit)),0,SUM(generalledger_debit))-IF(ISNULL(SUM(generalledger_credit)),0,SUM(generalledger_credit)) as bal FROM " . TABLE_GENERALLEDGER . "  WHERE  chartofaccounts_accountcode='" . $_POST['keyparam'] . "'");
                Common::push_element_into_array($main_array, 'txtBalance', $results[0]['bal']);
                $jason = json_encode(array('data' => $main_array));
                $jason = str_replace("\\\\", '', $jason);
                echo $jason;
                break 2;

            case 'select':
                $query = "SELECT chartofaccounts_accountcode, tcode,generalledger_description,generalledger_datecreated,generalledger_debit,generalledger_credit,branch_code FROM " . TABLE_GENERALLEDGER . " WHERE chartofaccounts_accountcode='" . $formdata['cashitems'] . "' ORDER BY generalledger_datecreated DESC";

                break;
            case 'search':
                $query = "SELECT * FROM " . TABLE_CASHITEMS . " WHERE (cashitems_name LIKE '%" . tep_db_prepare_input($_POST["searchterm"]) . "%'  OR chartofaccounts_accountcode  LIKE '%" . tep_db_prepare_input($_POST["searchterm"]) . "%')";
                break;
        }
        $lables_array = $grid_lables_lablearray + getlables("301,264,289,297,322");
        $fieldlist = array('chartofaccounts_accountcode', 'generalledger_description', 'generalledger_debit', 'generalledger_credit', 'branch_code');
        $keyfield = 'cashitems_id';
        $gridcolumnnames = array($lablearray['301'], $lablearray['264'], $lablearray['289'], $lablearray['297'], $lablearray['322']);
        break;

    case 'frmDash':
        $parameters = array();

        Common::prepareParameters($parameters, 'code', 'DASHBOARD');
        Common::prepareParameters($parameters, 'plang', 'EN');
        $dash_array = Common::common_sp_call(serialize($parameters), '', $Conn, false);
        $nCount = 0;
        foreach ($dash_array as $the => $value) {
            Common::push_element_into_array($main_array, 'h_' . $nCount, number_format_locale_display($dash_array[$nCount]['value']));
            Common::push_element_into_array($main_array, 'lbl_' . $nCount, $dash_array[$nCount]['txtlabel']);
            $nCount++;
        }
        $jason = json_encode(array('data' => $main_array));
        $jason = str_replace("\\\\", '', $jason);

        echo $jason;

        break;
    case 'frmSave':
        $formdata = Common::decodeSerialisedPagedata($_POST['pageparams']);

        switch ($_POST['action']) {

            case 'edit':
            case 'add':
                Common::getlables("1664,1694,1199,470", "", "", $Conn);

                if (!isset($_POST['keyparam'])):
                    echo "MSG " . Common::$lablearray['1199'];
                    exit();
                endif;

                Savings::$asatdate = '';
                Savings::$savaccid = Common::tep_db_prepare_input($_POST['keyparam']);


                Savings::getSavingsBalance();

                $balarray = Savings::$bal_array[0];
                if (isset($balarray['client_idno'])):

                    Common::push_element_into_array($main_array, 'client_idno', $balarray['client_idno']);
                    Common::push_element_into_array($main_array, 'action', 'add');
                    Common::push_element_into_array($main_array, 'keyparam', Savings::$savaccid);
                    Common::push_element_into_array($main_array, 'product_prodid', $balarray['product_prodid']);
                    Common::push_element_into_array($main_array, 'div_name', $balarray['name']);

                    Common::push_element_into_array($main_array, 'txtBalance', Common::number_format_locale_display($balarray['balance']));
                    Common::push_element_into_array($main_array, 'txtsavaccount', $balarray['savaccounts_account']);

                    $members_array = Savings::$bal_array[1];

                else:

                    Common::push_element_into_array($main_array, 'client_idno', $balarray[0]['client_idno']);
                    Common::push_element_into_array($main_array, 'action', 'add');
                    Common::push_element_into_array($main_array, 'keyparam', Savings::$savaccid);
                    Common::push_element_into_array($main_array, 'product_prodid', $balarray[0]['product_prodid']);
                    Common::push_element_into_array($main_array, 'div_name', $balarray[0]['name']);

                    Common::push_element_into_array($main_array, 'txtBalance', Common::number_format_locale_display($balarray[0]['balance']));
                    Common::push_element_into_array($main_array, 'txtsavaccount', $balarray[0]['savaccounts_account']);


                endif;

                // denominations for currecny
                if(SETTING_CURRENCY_DENO=='checked'):
              
             
//                    $denom_array = $Conn->SQLSelect("SELECT currencydeno_deno deno,(SELECT currencies_code from ".TABLE_CURRENCIES." c WHERE c.currencies_id=cd.currencies_id) ccode FROM " . TABLE_CURRENCYDENO . " cd ,".TABLE_PRODUCTCONFIG." pc WHERE cd.currencies_id=pc.productconfig_value  AND pc.productconfig_paramname='CURRENCIES_ID' AND product_prodid='".$balarray['product_prodid']."'");
//                    $deno = "<script>"
//                            . "$('.qty1').keyup(function() {
//                                var vsum =0;
//                                $('.qty1').each(function(){
//                                    var deno = $(this).attr('id');   
//                                    if(!isNaN(deno)){
//                                        var cur = parseFloat($(this).val()*deno);
//                                        vsum = vsum + cur;
//                                      
//                                    }
//                                });
//                                
//                              alert(vsum)
//                              $('.total').val(vsum);
//                                
//                            });</script><fieldset><legend>".Common::$lablearray['1694']."</legend><table cellpadding='0' width='100%' cellspacing='0'>";
//                    foreach ($denom_array AS $dkey => $dval):
//
//                        $deno.="<tr><td align=right>".$dval['ccode']." ".$dval['deno']." x </td><td align=right><input class='qty1' id='" . $dval['deno'] . "' name='".$dval['deno']."' value=0></td></tr>";
//
//                    endforeach;
//                 
//                    $deno .="</table></fieldset>"; 

                     Common::push_element_into_array($main_array, 'section2', Common::displayDenominations($balarray['product_prodid']));
                     
                endif; 
                // CHECK SEE IF ITS A GROUP MEMBERS
                if (preg_match('[G]', $balarray[0]['client_idno'])):

                    $mems = "<div  style='overflow:scroll; height:200px;padding:5x;'  >";
                    $mems .= "<table cellpadding='0' width='100%' cellspacing='0' id='customers'>";
                    $mems .= "<tr><th>Name</th><th>Amount</th><th>Charge</th><th>Balance</th><tr>";

                    $combo = "<div><select id='memids' name='memids'>";

                    foreach ($members_array AS $dkey => $dval):
                        $memid = Common::replace_string($dval['members_idno']);
                        $mems .= "<tr><td>" . $dval['name'] . " " . $dval['members_no'] . "</td><td><input type='numeric' name='AMT_" . $memid . "' id='AMT_" . $memid . "' size='16' value='0.0' class='AMT' onKeyUp=updatetotals()\></td><td><input type='numeric' size='16' name='CHARGE_" . $memid . "' id='CHARGE_" . $memid . "' class='CHARGE' onKeyUp=updatetotals('CHARGE') value='0.0'></td><td><input type='numeric' name='BAL_" . $memid . "' id='BAL_" . $memid . "' size='16' value='" . Common::number_format_locale_display($dval['balance']) . "' class='BAL'\ disabled=disabled></td><tr>";
                        $combo .= "<option id='MEM_" . $memid . "' name='MEM_" . $memid . "' value='" . $memid . "'>" . $dval['name'] . " " . $dval['members_no'] . "</option>";
                    endforeach;

                    $combo .= "</select><button type='button' class='btn' name='Go'  type='button' onClick=\"$( '#radiotran' ).trigger( 'click' )\">" . Common::$lablearray['1664'] . "</button></div>";

                    $mems .= "</table></div>" . $combo;

                endif;

                Common::push_element_into_array($main_array, 'section1', $mems);

                $jason = json_encode(array('data' => $main_array));

                $jason = str_replace("\\\\", '', $jason);

                echo $jason;

                break 2;
            default:
                break;
        }
        break;

    case 'frmTDeposit':

        $formdata = Common::decodeSerialisedPagedata($_POST['pageparams']);
        switch ($_POST['action']):
            
       
        case 'eval':
        case 'edit':       
            Common::getlables("1210,1683,1684,186,1685,1434", "", "", Common::$connObj);

            if ($formdata['txtamount'] == ''):
                echo "MSG " . Common::$lablearray['1210'];
                exit();
            endif;

            if ($formdata['txtintrate'] == ''):
                echo "MSG " . Common::$lablearray['1683'];
                exit();
            endif;

            if ($formdata['txtperiod'] == ''):
                echo "MSG " . Common::$lablearray['1684'];
                exit();
            endif;

            if ($formdata['txtDate'] == ''):
                echo "MSG " . Common::$lablearray['186'];
                exit();
            endif;

            if ($formdata['INSTYPE'] == ''):
                echo "MSG " . Common::$lablearray['1685'];
                exit();
            endif;

            if ($formdata['product_prodid'] == ''):
                echo "MSG " . Common::$lablearray['1434'];
                exit();
            endif;
            
        default:
            break;
        endswitch;    
       
        switch ($_POST['action']) {
            case 'eval':

                // Public Static  $tdeposit_array = array('tnumber'=>'','amount'=>0,'intrate'=>0,'intamt'=>0,'period'=>0,'ddate'=>'','instype'=>'','matdate'=>'','status'=>'','intcapital'=>'N','prodid'=>''); 
                Tdeposit::$tdeposit_array['amount'] = $formdata['txtamount'];
                Tdeposit::$tdeposit_array['intrate'] = $formdata['txtintrate'];
                Tdeposit::$tdeposit_array['period'] = $formdata['txtperiod'];
                Tdeposit::$tdeposit_array['ddate'] = $formdata['txtDate'];
                Tdeposit::$tdeposit_array['instype'] = $formdata['INSTYPE'];
                Tdeposit::$tdeposit_array['prodid'] = $formdata['product_prodid'];

                $tdepositarray = Tdeposit::calculateInterest('ADD');

                echo "formObj.txtmatvalue.value = '" . Tdeposit::$tdeposit_array['matval'] . "';\n";
                echo "formObj.txtmDate.value = '" . Tdeposit::$tdeposit_array['matdate'] . "';\n";

                break;
            case 'edit':
            case 'loadform':
                // check see if a transaction is selected
                if ($_POST['keyparam'] == ''):
                    Common::getlables("1339", "", "", Common::$connObj);
                    echo "MSG " . Common::$lablearray['1339'];
                    exit();
                endif;

                Tdeposit::$transactioncode = Common::tep_db_prepare_input($_POST['keyparam']);

                $tdeposit_array = Tdeposit::getTimeDeposit();

                // $tdeposit_array = $Conn->SQLSelect("SELECT timedeposit_status FROM " . TABLE_TDEPOSITTRANS . " tr,".TABLE_TDEPOSIT." t WHERE  tr.timedeposit_number=t.timedeposit_number  AND tr.transactioncode='" .$keyparam."'");

                if ($tdeposit_array[0]['timedeposit_status'] == 'TW'):
                    Common::getlables("1612", "", "", Common::$connObj);
                    echo "MSG " . Common::$lablearray['1612'];
                    exit();
                endif;


//                 // check if time deposit is withdrawn
//                if($formdata['txtDate']==''):                              
//                    echo "MSG " .Common::$lablearray['186'];
//                    exit();
//                endif;
//                               
                // get time deposit details
                //  $tdeposit_array = $Conn->SQLSelect("SELECT t.product_prodid, tr.timedeposit_number,tr.timedeposit_interestrate,tr.timedeposit_intamt,tr.timedeposit_amount,tr.timedeposit_period,tr.timedeposit_instype,tr.timedeposit_freq,tr.timedeposit_matval,tr.timedeposit_matdate,tr.timedeposit_intcapital,tr.timedeposit_status FROM " . TABLE_TDEPOSITTRANS . " tr,".TABLE_TDEPOSIT." t WHERE  tr.timedeposit_number=t.timedeposit_number  AND tr.transactioncode='" .$keyparam."'");
                //   $matdate = Tdeposit::$tdeposit_array[0]['timedeposit_matdate'];
                //    $curdate = Common::getcurrentDateTime('D');
                // check if time deposit is withdrawn
//                if($tdeposit_array[0]['timedeposit_status']=='TW'):
//                    getlables("1612");                
//                    echo "MSG " . Common::$lablearray['1612'];
//                    exit();
//                endif;
                // check see of maturity date has arrived
//                if($curdate < $matdate):                                  
//                    echo "MSG " . Common::$lablearray['1611'];
//                    exit();
//                
//                endif;
                Common::push_element_into_array($main_array, 'action', 'update');
                Common::push_element_into_array($main_array, 'keyparam', $_POST['keyparam']);
                Common::push_element_into_array($main_array, 'client_idno', $tdeposit_array[0]['client_idno']);
                Common::push_element_into_array($main_array, 'txtmatvalue', $tdeposit_array[0]['timedeposit_matval']);
                Common::push_element_into_array($main_array, 'txtmDate', Common::changeMySQLDateToPageFormat($tdeposit_array[0]['timedeposit_matdate']));
                // Common::push_element_into_array($main_array, 'txtDate', Common::changeMySQLDateToPageFormat($tdeposit_array[0]['timedeposit_date']));
                Common::push_element_into_array($main_array, 'txttdnumber', $tdeposit_array[0]['timedeposit_number']);
                Common::push_element_into_array($main_array, 'txtintrate', $tdeposit_array[0]['timedeposit_interestrate']);
                Common::push_element_into_array($main_array, 'txtamount', $tdeposit_array[0]['timedeposit_amount']);
                Common::push_element_into_array($main_array, 'INSTYPE', $tdeposit_array[0]['timedeposit_instype']);
                Common::push_element_into_array($main_array, 'txtperiod', $tdeposit_array[0]['timedeposit_period']);
                Common::push_element_into_array($main_array, 'product_prodid', $tdeposit_array[0]['product_prodid']);
                Common::push_element_into_array($main_array, 'FREQ', $tdeposit_array[0]['timedeposit_freq']);
                Common::push_element_into_array($main_array, 'chkintCapital', ($tdeposit_array[0]['timedeposit_intcapital'] == 1) ? 'checked' : '');

                if ($_POST['action'] == 'loadform'):
                    Common::push_element_into_array($main_array, 'TDSTATUS', 'TW');
                else:
                    Common::push_element_into_array($main_array, 'TDSTATUS', 'TR');
                endif;

                if (preg_match('[G]', $tdeposit_array[0]['client_idno'])):
                    Clients::$clientid = Common::tep_db_prepare_input($tdeposit_array[0]['client_idno']);

                    Clients::getClientDetails($tdeposit_array[0]['client_idno']);

                    Common::getlables("1664", "", "", $Conn);

                    $mems = "<div  style='overflow:scroll; height:200px;padding:5x;'  >";
                    $mems .= "<table cellpadding='0' width='100%' cellspacing='0' id='customers'>";
                    $mems .= "<tr><th>Name</th><th>Amount</th><th>Charge</th><th>Balance</th><tr>";

                    $combo = "<div><select id='memids' name='memids'>";

                    foreach (Clients::$members_array AS $dkey => $dval):
                        $memid = Common::replace_string($dval['members_idno']);
                        $mems .= "<tr><td>" . $dval['Name'] . " " . $dval['members_no'] . "</td><td><input type='numeric' name='AMT_" . $memid . "' id='AMT_" . $memid . "' size='16' value='0.0' class='AMT' onKeyUp=updatetotals()\></td><td><input type='numeric' size='16' name='CHARGE_" . $memid . "' id='CHARGE_" . $memid . "' class='CHARGE' onKeyUp=updatetotals('CHARGE') value='0.0'></td><td><input type='numeric' name='BAL_" . $memid . "' id='BAL_" . $memid . "' size='16' value='" . Common::number_format_locale_display($dval['balance']) . "' class='BAL'\ disabled=disabled></td><tr>";
                        $combo .= "<option id='MEM_" . $memid . "' name='MEM_" . $memid . "' value='" . $memid . "'>" . $dval['Name'] . " " . $dval['members_no'] . "</option>";
                    endforeach;

                    $combo .= "</select><button type='button' class='btn' name='Go'  type='button' onClick=\"$( '#radiotran' ).trigger( 'click' )\">" . Common::$lablearray['1664'] . "</button></div>";

                    $mems .= "</table></div>" . $combo;

                    Common::push_element_into_array($main_array, 'tab2', $mems);

                endif;

                $jason = json_encode(array('data' => $main_array));

                $jason = str_replace("\\\\", '', $jason);

                echo $jason;
                
                break;
        
            // case 'edit':
            case 'add':

                $_POST['keyparam'] = Common::tep_db_prepare_input($_POST['keyparam']);


                if (preg_match('[G]', $_POST['keyparam'])):

                    Clients::$clientid = Common::tep_db_prepare_input($_POST['keyparam']);

                    Clients::getClientDetails(Clients::$clientid);

                    Common::getlables("1664", "", "", $Conn);

                    $mems = "<div  style='overflow:scroll; height:200px;padding:5x;'  >";
                    $mems .= "<table cellpadding='0' width='100%' cellspacing='0' id='customers'>";
                    $mems .= "<tr><th>Name</th><th>Amount</th><th>Charge</th><th>Balance</th><tr>";

                    $combo = "<div><select id='memids' name='memids'>";

                    foreach (Clients::$members_array AS $dkey => $dval):
                        $memid = Common::replace_string($dval['members_idno']);
                        $mems .= "<tr><td>" . $dval['Name'] . " " . $dval['members_no'] . "</td><td><input type='numeric' name='AMT_" . $memid . "' id='AMT_" . $memid . "' size='16' value='0.0' class='AMT' onKeyUp=updatetotals()\></td><td><input type='numeric' size='16' name='CHARGE_" . $memid . "' id='CHARGE_" . $memid . "' class='CHARGE' onKeyUp=updatetotals('CHARGE') value='0.0'></td><td><input type='numeric' name='BAL_" . $memid . "' id='BAL_" . $memid . "' size='16' value='" . Common::number_format_locale_display($dval['balance']) . "' class='BAL'\ disabled=disabled></td><tr>";
                        $combo .= "<option id='MEM_" . $memid . "' name='MEM_" . $memid . "' value='" . $memid . "'>" . $dval['Name'] . " " . $dval['members_no'] . "</option>";
                    endforeach;

                    $combo .= "</select><button type='button' class='btn' name='Go'  type='button' onClick=\"$( '#radiotran' ).trigger( 'click' )\">" . Common::$lablearray['1664'] . "</button></div>";

                    $mems .= "</table></div>" . $combo;

                    Common::push_element_into_array($main_array, 'tab2', $mems);
                    Common::push_element_into_array($main_array, 'client_idno', Clients::$client_array[0]['entity_idno']);
                // Common::push_element_into_array($main_array, 'keyparam', Clients::$client_array[0]['entity_idno']); 

                else:

                    Clients::$clientid = Common::tep_db_prepare_input($_POST['keyparam']);
                    Clients::getClientDetails(Clients::$clientid);
                    Common::push_element_into_array($main_array, 'keyparam', $_POST['keyparam']);
                    Common::push_element_into_array($main_array, 'client_idno', Clients::$client_array[0]['client_idno']);

                endif;

                Common::push_element_into_array($main_array, 'action', 'add');
                Common::push_element_into_array($main_array, 'div_name', Clients::$client_array[0]['Name']);

                $jason = json_encode(array('data' => $main_array));

                $jason = str_replace("\\\\", '', $jason);

                echo $jason;

                break;

            default:
                break;
        }

        break;

    case 'frmrepay':

        switch ($_POST['action']) {

            case 'edit': // Distributes Payment
                
                $formdata = Common::decodeSerialisedPagedata($_POST['pageparams']);
              
                $formdata['DuePrincipal'] = Common::number_format_locale_compute(($formdata['DuePrincipal']??0));
                $formdata['DueInterest'] = Common::number_format_locale_compute(($formdata['DueInterest']??0));
                $formdata['DueCommission'] = Common::number_format_locale_compute(($formdata['DueCommission']??0));
                $formdata['DuePenalty'] = Common::number_format_locale_compute(($formdata['DuePenalty']??0));
                $formdata['Duevat'] = Common::number_format_locale_compute(($formdata['Duevat']??0));
                $formdata['AMOUNT'] =  Common::number_format_locale_compute($formdata['DueTotal']??0);
                $formdata['OUTBAL'] = 0;

                Common::replace_key_function($formdata, 'txtpayDate', 'DATE');  
                Common::replace_key_function($formdata, 'DuePrincipal','PRI');
                Common::replace_key_function($formdata, 'DueInterest','INT');
                Common::replace_key_function($formdata, 'DueCommission','COM');
                Common::replace_key_function($formdata, 'DuePenalty','PEN');
                Common::replace_key_function($formdata, 'Duevat','VAT');
                Common::replace_key_function($formdata, 'TotalOver','OVR');
                Common::replace_key_function($formdata, 'txtlnr', 'LNR');
                Common::replace_key_function($formdata, 'members_idno', 'MEMID');
                Common::replace_key_function($formdata, 'txtvoucher', 'VOUCHER');
                Common::replace_key_function($formdata, 'client_idno', 'CLIENTIDNO');
                Common::replace_key_function($formdata, 'txtproduct', 'LPRODID');

                $form_data[] = $formdata;
                $loan = new Loan(array(), $formdata['LNR']);
                $formdata = $loan::updateLoan($form_data,'DP');
                
                Common::push_element_into_array($main_array, 'DuePrincipal', Common::number_format_locale_display($formdata['PRI']));
                Common::push_element_into_array($main_array, 'DueInterest', Common::number_format_locale_display($formdata['INT']));
                Common::push_element_into_array($main_array, 'DueCommission', Common::number_format_locale_display($formdata['COM']));
                Common::push_element_into_array($main_array, 'DuePenalty', Common::number_format_locale_display($formdata['PEN']));               
                Common::push_element_into_array($main_array, 'TotalOver', Common::number_format_locale_display($formdata['OVR']));
                $jason = json_encode(array('data' => $main_array));
                $jason = str_replace("\\\\", '', $jason);
                echo $jason;
                break;

            case 'add':

                //$objects = (array)json_decodeData($_POST['pageparams']);
                // $formdata = array_flatten(objectToArray($objects['pageinfo']));
                $formdata = Common::decodeSerialisedPagedata($_POST['pageparams']);

                $keyparam = Common::tep_db_prepare_input($_POST['keyparam']);

               Common::getlables("1348,1349,1589,1694", "", "", Common::$connObj);
                // $lablearray = getlables("1348,1349,1589,");

                if ($keyparam == "") :
                    echo 'INFO.' . Common::$lablearray['1348'];
                    exit();
                endif;

                if ($formdata['txtpayDate'] == "") {
                    echo 'INFO.' . Common::$lablearray['1349'];
                    exit();
                }

                $loan = new Loan(array(), $keyparam);
                $loan::$paydate = Common::changeDateFromPageToMySQLFormat($formdata['txtpayDate'], true);

                $loan::$cLnr = $keyparam;
                $loan::$clienttype = Common::getClientType($loan::$loanappdetails['client_idno']);

                $loan::getDisbursements();
                $loan::getLoanDues();
                $loan::getLoanPayments();
                $loan::getcurrentloandues();
                $loan::calculateBalances();


                Common::push_element_into_array($main_array, 'div_name', $loan::$loanappdetails['name']);
                Common::push_element_into_array($main_array, 'client_idno', $loan::$loanappdetails['client_idno']);
                Common::push_element_into_array($main_array, 'txtlnr', $loan::$cLnr);
                Common::push_element_into_array($main_array, 'txtproduct', $loan::$loanappdetails['product_prodid']);
                Common::push_element_into_array($main_array, 'ArrearPrincipal', Common::number_format_locale_display(array_sum(array_column($loan::$loanarrears, 'due_principal'))));
                Common::push_element_into_array($main_array, 'ArrearInterest', Common::number_format_locale_display(array_sum(array_column($loan::$loanarrears, 'due_interest'))));
                Common::push_element_into_array($main_array, 'ArrearCommision', Common::number_format_locale_display(array_sum(array_column($loan::$loanarrears, 'due_penalty'))));
                Common::push_element_into_array($main_array, 'ArrearPenalty', Common::number_format_locale_display(array_sum(array_column($loan::$loanarrears, 'due_commission'))));
                Common::push_element_into_array($main_array, 'ArrearOther', '0');
                Common::push_element_into_array($main_array, 'ArrearTotal', Common::number_format_locale_display(array_sum(array_column($loan::$loanarrears, 'Total'))));


                Common::push_element_into_array($main_array, 'prepaidPrincipal', Common::number_format_locale_display(array_sum(array_column($loan::$prepaiddues, 'due_principal'))));
                Common::push_element_into_array($main_array, 'prepaidInterest', Common::number_format_locale_display(array_sum(array_column($loan::$prepaiddues, 'due_interest'))));
                Common::push_element_into_array($main_array, 'prepaidPenalty', Common::number_format_locale_display(array_sum(array_column($loan::$prepaiddues, 'due_penalty'))));
                Common::push_element_into_array($main_array, 'prepaidCommission', Common::number_format_locale_display(array_sum(array_column($loan::$prepaiddues, 'due_commission'))));
                Common::push_element_into_array($main_array, 'prepaidOther', '0');
                Common::push_element_into_array($main_array, 'prepaidTotal', Common::number_format_locale_display(array_sum(array_column($loan::$prepaiddues, 'Total'))));


                // check see if we are recalculating interest
                $_pararesult = $Conn->SQLSelect("SELECT productconfig_paramname,productconfig_value FROM " . TABLE_PRODUCTCONFIG . " WHERE  productconfig_paramname  IN ('RECALC_INT','SERVICE_FEE_ACC','SERVICE_FEE','SAV_AT_REPAY') AND product_prodid='" . $loan::$loanappdetails['product_prodid'] . "'");
                $data_array = Common::searchArray($_pararesult, 'productconfig_paramname', 'RECALC_INT');
                $calcint = $data_array['productconfig_value'];
                $data_array = array();
                $data_array = Common::searchArray($_pararesult, 'productconfig_paramname', 'SERVICE_FEE_ACC');
                $fee_acc = $data_array['productconfig_value'];
                $data_array = array();
                $data_array = Common::searchArray($_pararesult, 'productconfig_paramname', 'SERVICE_FEE');

                $fee = $data_array['productconfig_value'];

                $princ_outstanding = array_sum(array_column(Loan::$loantotaldues, 'due_principal')) - array_sum(array_column(Loan::$loanpayments, 'loanpayments_principal'));


                if ($calcint == '1') {

                    Common::prepareParameters($parameters, 'branch_code', '');
                    Common::prepareParameters($parameters, 'loan_number', $loan::$loanappdetails['loan_number']);
                    Common::prepareParameters($parameters, 'members_idno', $loan::$loanappdetails['members_idno']);
                    Common::prepareParameters($parameters, 'paydate', $loan::$paydate);
                    Common::prepareParameters($parameters, 'principalbal', $princ_outstanding);
                    Common::prepareParameters($parameters, 'product_prodid', $loan::$loanappdetails['product_prodid']);
                    Common::prepareParameters($parameters, 'code', 'RECALINT');

                    $result = Common::common_sp_call(serialize($parameters), false, $Conn);

                    $loan::$currentloandues[0]['due_interest'] = $result[0]['interest']; //+ $loan::$loanarrears[0]['due_interest'];

                    $loan::$currentloandues[0]['Total'] = $loan::$currentloandues[0]['due_principal'] + $loan::$loanarrears[0]['due_principal'] + $result[0]['interest'] + $loan::$loanarrears[0]['due_interest'] + $loan::$currentloandues[0]['due_commission'] + $loan::$loanarrears[0]['due_commission'] + $loan::$currentloandues[0]['due_penalty'] + $loan::$loanarrears[0]['due_penalty'];
                } else {

                    $loan::$currentloandues[0]['Total'] = $loan::$currentloandues[0]['Total'] + $loan::$loanarrears[0]['due_principal'] + $loan::$loanarrears[0]['due_interest'] + $loan::$loanarrears[0]['due_penalty'];
                }


                // OUTSTANDING BALANCE

                Common::push_element_into_array($main_array, 'OutBal', Common::number_format_locale_display(array_sum(array_column($loan::$outstanding, 'Total'))));

                if (isset($formdata['chkcloseLoan'])) {

                    $out_princ = array_sum(array_column(Loan::$disbursements, 'disbursements_amount'));
                    $pric_paid = array_sum(array_column(Loan::$loanpayments, 'loanpayments_principal'));

                    $due_penalty = array_sum(array_column(Loan::$loantotaldues, 'due_penalty'));
                    $pen_paid = array_sum(array_column(Loan::$loanpayments, 'loanpayments_penalty'));

                    $due_commission = array_sum(array_column(Loan::$loantotaldues, 'due_commission'));
                    $com_paid = array_sum(array_column(Loan::$loanpayments, 'loanpayments_commission'));

                    $due_interest = array_sum(array_column(Loan::$loantotaldues, 'due_interest'));
                    $int_paid = array_sum(array_column(Loan::$loanpayments, 'loanpayments_interest'));

                    $total_due = ($out_princ - $pric_paid) + ($due_interest - $int_paid) + ($due_commission - $com_paid) + ($due_penalty - $pen_paid);

                    if ($total_due <= 0):
                        echo 'MSG.' . Common::$lablearray['1589']; // Loan is fully paid                       
                        exit();
                    endif;

                    if ($fee > 0) {
                        $fee = ($fee / 100) * ($out_princ - $pric_paid);
                    }


                    Common::push_element_into_array($main_array, 'txtfeeacc', $fee_acc);
                    Common::push_element_into_array($main_array, 'DuePrincipal', ($out_princ - $pric_paid));

                    if ($calcint == '1' || $formdata['chkignoreFutureInterest'] == 'Y') {
                        $due_interest = 0;
                        $int = ($loan::$currentloandues[0]['due_interest'] + $loan::$loanarrears[0]['due_interest']);
                    } else {
                        $int = $due_interest - $int_paid;
                    }


                    // CHECK SEE IF ITS A GROUP MEMBERS
                    if (preg_match('[G]', $loan::$loanappdetails['client_idno'])):

                        Clients::$clientid = $loan::$loanappdetails['client_idno'];

                        $members_array = Clients::getGroupmemberDetails();

                        $mems = "<div  style='overflow:scroll; height:200px;'  id='customers'>";
                        $mems .= "<table padding='2'>";
                        $mems .= "<tr><th>Name</th><th>Principal</th><th>Interest</th><th>Commission</th><th>Penalty</th><th>VAT</th><th>Over-paid</th><th>Outstanding</th><tr>";

                        foreach ($members_array AS $dkey => $dval):

                            //      $Tprinc = Common::sum_array('members_idno', $dval['members_idno'], 'due_principal', self::$loantotaldues);

                            $mPrinc = array_sum(array_column($loan::$outstanding, 'due_principal')); // , $loan::$loanarrears[$dval['members_idno']]['due_principal'],SETTING_ROUNDING);
                            $mInt = bcadd($loan::$currentloandues[$dval['members_idno']]['due_interest'], $loan::$loanarrears[$dval['members_idno']]['due_interest'], SETTING_ROUNDING);
                            $mComm = bcadd($loan::$currentloandues[$dval['members_idno']]['due_commission'], $loan::$loanarrears[$dval['members_idno']]['due_commission'], SETTING_ROUNDING);
                            $mPen = bcadd($loan::$currentloandues[$dval['members_idno']]['due_penalty'], $loan::$loanarrears[$dval['members_idno']]['due_penalty'], SETTING_ROUNDING);
                            $mVat = bcadd($loan::$currentloandues[$dval['members_idno']]['due_vat'], $loan::$loanarrears[$dval['members_idno']]['due_vat'], SETTING_ROUNDING);

                            $mOut = $loan::$outstanding[$dval['members_idno']]['Total'];

                            $mOver = $loan::$overpayments[$dval['members_idno']]['Total'];

                            $memid = Common::replace_string($dval['members_idno']);

                            $mems .= "<tr><td>" . $dval['Name'] . " " . $dval['members_no'] . "</td><td><input type='numeric' name='PRINC_" . $memid . "' id='PRINC_" . $memid . "' size='11' value='" . Common::number_format_locale_display($mPrinc) . "' class='PRINC' onKeyUp=updatetotals()\></td><td><input type='numeric' size='11' name='INT_" . $memid . "' id='INT_" . $memid . "' class='INT' onKeyUp=updatetotals() value='" . Common::number_format_locale_display($mInt) . "'></td><td><input type='numeric' size='11' name='COMM_" . $memid . "' id='COMM_" . $memid . "' value='" . Common::number_format_locale_display($mComm) . "' class='COMM' onKeyUp=updatetotals() \></td><td><input type='numeric' size='11' name='PEN_" . $memid . "' id='PEN_" . $memid . "' class='PEN' onKeyUp=updatetotals() value='" . Common::number_format_locale_display($mPen) . "'></td><td><input type='numeric' size='11' name='VAT_" . $memid . "' id='VAT_" . $memid . "' class='VAT' onKeyUp=updatetotals() value='" . Common::number_format_locale_display($mVat) . "'></td><td><input type='numeric' size='11' name='OVR_" . $memid . "' id='OVR_" . $memid . "' value='" . Common::number_format_locale_display($mOver) . "' disabled=disabled></td><td><input size='11' type='numeric' name='OUT_" . $memid . "' id='OUT_" . $memid . "' value='" . Common::number_format_locale_display($mOut) . "' disabled=disabled></td><tr>";

                        endforeach;

                        $mems .= "</table></div>";

                    endif;

                    Common::push_element_into_array($main_array, 'DueInterest', $int);
                    Common::push_element_into_array($main_array, 'DuePenalty', ($due_penalty - $pen_paid));
                    Common::push_element_into_array($main_array, 'DueCommission', ($due_commission - $com_paid));
                    Common::push_element_into_array($main_array, 'DueOther', '0');
                    Common::push_element_into_array($main_array, 'DueTotal', ($out_princ - $pric_paid) + ($due_interest - $int_paid) + ($due_commission - $com_paid) + ($due_penalty - $pen_paid));
                }else {


                    // CHECK SEE IF ITS A GROUP MEMBERS
                    if (preg_match('[G]', $loan::$loanappdetails['client_idno'])):

                        Clients::$clientid = $loan::$loanappdetails['client_idno'];

                        $members_array = Clients::getGroupmemberDetails();

                        $mems = "<div  style='overflow:scroll; height:200px;'  id='customers'>";
                        $mems .= "<table padding='2'>";
                        $mems .= "<tr><th>Name</th><th>Principal</th><th>Interest</th><th>Commission</th><th>Penalty</th><th>VAT</th><th>Over-paid</th><th>Outstanding</th><tr>";

                        foreach ($members_array AS $dkey => $dval):

                            //      $Tprinc = Common::sum_array('members_idno', $dval['members_idno'], 'due_principal', self::$loantotaldues);

                            $mPrinc = bcadd($loan::$currentloandues[$dval['members_idno']]['due_principal'], $loan::$loanarrears[$dval['members_idno']]['due_principal'], SETTING_ROUNDING);
                            $mInt = bcadd($loan::$currentloandues[$dval['members_idno']]['due_interest'], $loan::$loanarrears[$dval['members_idno']]['due_interest'], SETTING_ROUNDING);
                            $mComm = bcadd($loan::$currentloandues[$dval['members_idno']]['due_commission'], $loan::$loanarrears[$dval['members_idno']]['due_commission'], SETTING_ROUNDING);
                            $mPen = bcadd($loan::$currentloandues[$dval['members_idno']]['due_penalty'], $loan::$loanarrears[$dval['members_idno']]['due_penalty'], SETTING_ROUNDING);
                            $mVat = bcadd($loan::$currentloandues[$dval['members_idno']]['due_vat'], $loan::$loanarrears[$dval['members_idno']]['due_vat'], SETTING_ROUNDING);

                            $mOut = $loan::$outstanding[$dval['members_idno']]['Total'];

                            $mOver = $loan::$overpayments[$dval['members_idno']]['Total'];

                            $memid = Common::replace_string($dval['members_idno']);

                            $mems .= "<tr><td>" . $dval['Name'] . " " . $dval['members_no'] . "</td><td><input type='numeric' name='PRINC_" . $memid . "' id='PRINC_" . $memid . "' size='11' value='" . Common::number_format_locale_display($mPrinc) . "' class='PRINC' onKeyUp=updatetotals('PRINC')\></td><td><input type='numeric' size='11' name='INT_" . $memid . "' id='INT_" . $memid . "' class='INT' onKeyUp=updatetotals('INT') value='" . Common::number_format_locale_display($mInt) . "'></td><td><input type='numeric' size='11' name='COMM_" . $memid . "' id='COMM_" . $memid . "' value='" . Common::number_format_locale_display($mComm) . "' class='COMM' onKeyUp=updatetotals('COMM') \></td><td><input type='numeric' size='11' name='PEN_" . $memid . "' id='PEN_" . $memid . "' class='PEN' onKeyUp=updatetotals('PEN') value='" . Common::number_format_locale_display($mPen) . "'></td><td><input type='numeric' size='11' name='VAT_" . $memid . "' id='VAT_" . $memid . "' class='VAT' onKeyUp=updatetotals('VAT') value='" . Common::number_format_locale_display($mVat) . "'></td><td><input type='numeric' size='11' name='OVR_" . $memid . "' id='OVR_" . $memid . "' value='" . Common::number_format_locale_display($mOver) . "' disabled=disabled></td><td><input size='11' type='numeric' name='OUT_" . $memid . "' id='OUT_" . $memid . "' value='" . Common::number_format_locale_display($mOut) . "' disabled=disabled></td><td><input size='11' type='numeric' name='CHARGE_" . $memid . "' id='CHARGE_" . $memid . "' value='" . Common::number_format_locale_display($mOut) . "' disabled=disabled></td><tr>";


                        endforeach;

                        $mems .= "</table></div>";

                    endif;

                    $fee = 0;
                    $due_princ = bcadd(array_sum(array_column(Loan::$currentloandues, 'due_principal')), array_sum(array_column(Loan::$loanarrears, 'due_principal')), SETTING_ROUNDING);
                    $due_int = bcadd(array_sum(array_column(Loan::$currentloandues, 'due_interest')), array_sum(array_column(Loan::$loanarrears, 'due_interest')), SETTING_ROUNDING);
                    $due_comm = bcadd(array_sum(array_column(Loan::$currentloandues, 'due_commission')), array_sum(array_column(Loan::$loanarrears, 'due_commission')), SETTING_ROUNDING);
                    $due_pen = bcadd(array_sum(array_column(Loan::$currentloandues, 'due_penalty')), array_sum(array_column(Loan::$loanarrears, 'due_penalty')), SETTING_ROUNDING);
                    $due_vat = bcadd(array_sum(array_column(Loan::$currentloandues, 'due_vat')), array_sum(array_column(Loan::$loanarrears, 'due_vat')), SETTING_ROUNDING);
                    $due_total = bcadd(array_sum(array_column(Loan::$currentloandues, 'Total')), array_sum(array_column(Loan::$loanarrears, 'Total')), SETTING_ROUNDING);

                    Common::push_element_into_array($main_array, 'txtfee', $fee);
                    Common::push_element_into_array($main_array, 'txtfeeacc', $fee_acc);
                    Common::push_element_into_array($main_array, 'DuePrincipal', Common::number_format_locale_display($due_princ));
                    Common::push_element_into_array($main_array, 'DueInterest', Common::number_format_locale_display($due_int));
                    Common::push_element_into_array($main_array, 'DuePenalty', Common::number_format_locale_display($due_pen));
                    Common::push_element_into_array($main_array, 'DueCommission', Common::number_format_locale_display($due_comm));
                    Common::push_element_into_array($main_array, 'DueOther', '0');
                    Common::push_element_into_array($main_array, 'DueTotal', Common::number_format_locale_display($due_total));
                }

                Common::push_element_into_array($main_array, 'tab3', $mems);
                Common::push_element_into_array($main_array, 'PrincipalOver', Common::number_format_locale_display(array_sum(array_column(Loan::$overpayments, 'due_principal'))));
                Common::push_element_into_array($main_array, 'InterestOver', Common::number_format_locale_display(array_sum(array_column(Loan::$overpayments, 'due_interest'))));
                Common::push_element_into_array($main_array, 'PenaltyOver', Common::number_format_locale_display(array_sum(array_column(Loan::$overpayments, 'due_penalty'))));
                Common::push_element_into_array($main_array, 'CommissionOver', Common::number_format_locale_display(array_sum(array_column(Loan::$overpayments, 'due_commission'))));
                Common::push_element_into_array($main_array, 'TotalOver', Common::number_format_locale_display(array_sum(array_column(Loan::$overpayments, 'Total'))));

                if ($formdata['PAYMODES'] == 'SA'):
                    $charge_array = Common::getParamValue('CHARGE_ON_WITHDRAW', $formdata['product_prodid']);
                    Common::push_element_into_array($main_array, 'txtchargeamount', ($loan::$overpayments[0]['Total'] * ($charge_array[0]['val'] / 100)));
                else:
                    Common::push_element_into_array($main_array, 'txtchargeamount', '0');
                endif;
                
                Common::push_element_into_array($main_array, 'div_deno', Common::displayDenominations($loan::$loanappdetails['product_prodid']));
                
                $jason = json_encode(array('data' => $main_array));
                $jason = str_replace("\\\\", '', $jason);
                echo $jason;
                break 2;
            default:
                break;
        }

        break;

    case 'frmLoanapp1':

        if ($_POST['keyparam'] == 'REFINANCE') {
            $loan_array = $Conn->SQLSelect("SELECT loan_tint,loan_number,loan_noofinst FROM " . TABLE_LOAN . " WHERE loan_number='" . $_POST['keyparam'] . "'");
            Common::push_element_into_array($main_array, 'action', 'update');
            Common::push_element_into_array($main_array, 'loan_number', $loan_array[0]['loan_number']);
            Common::push_element_into_array($main_array, 'txtintrate', $loan_array[0]['loan_tint']);
            Common::push_element_into_array($main_array, 'txtloan_noofinst', $loan_array[0]['loan_noofinst']);
            Common::push_element_into_array($main_array, 'InfoBox', '' . $loan_array[0]['Name'] . ' ' . $_POST['keyparam'] );
            $jason = json_encode(array('data' => $main_array));
            $jason = str_replace("\\\\", '', $jason);
            echo $jason;
            exit();
        }

        if ($_POST['keyparam'] == 'LOANDISBURSE') {
            $loan_array = $Conn->SQLSelect("SELECT loan_amount FROM " . TABLE_LOAN . " WHERE loan_number='" . $_POST['keyparam'] . "'");
            Common::push_element_into_array($main_array, 'txtAmount', $loan_array[0]['loan_amount']);
            Common::push_element_into_array($main_array, 'startDate', Common::getcurrentDateTime('D'));
            $jason = json_encode(array('data' => $main_array));
            $jason = str_replace("\\\\", '', $jason);
            echo $jason;
            exit();
        }

        if ($_POST['keyparam'] == 'CLIENTDETAILS') {
            $savacc_array = $Conn->SQLSelect("SELECT client_idno,CONCAT(client_surname,' ',client_firstname,' ',client_middlename) As Name FROM " . TABLE_VCLIENTS . " c WHERE  c.client_idno='" . tep_db_prepare_input($_POST['keyparam']) . "'");

            Common::push_element_into_array($main_array, 'client_idno', $savacc_array[0]['client_idno']);
            Common::push_element_into_array($main_array, 'action', 'add');
            Common::push_element_into_array($main_array, 'keyparam', $savacc_array[0]['client_idno']);
            Common::push_element_into_array($main_array, 'InfoBox', $savacc_array[0]['Name']);
            $jason = json_encode(array('data' => $main_array));
            $jason = str_replace("\\\\", '', $jason);
            echo $jason;
            exit();
        }

        if ($_POST['keyparam'] == 'MEM') {
            $main_array = array();
            Clients::$clientid = $_POST['keyparam'];
            Clients::getClientDetails();
            if (count(Clients::$members_array) > 0):

                $mems = "<div><div  style='overflow:scroll;'  >";
                $mems .= "<table cellpadding='2' width='100%' cellspacing='2' id='customers' class='tablestyleblock'>";
                $mems .= "<tr><th>Name</th><th>Loan Amount</th><tr>";


                foreach (Clients::$members_array AS $dkey => $dval):
                    $memid = Common::replace_string($dval['members_idno']);
                    $mems .= "<tr><td> " . $dval['Name'] . " " . $dval['members_no'] . "</td><td style='border:  thin inset  grey;'><input type='numeric' name='MEM_" . $memid . "' id='AMT_" . $memid . "' size='16' value='0.0' class='AMT' ></td></tr>";
                endforeach;

                $mems .= "</table></div></div>";
            endif;

            Common::push_element_into_array($main_array, 'action', 'add');
            Common::push_element_into_array($main_array, 'div_name', Clients::$client_array[0]['Name'] . '<br>' . Clients::$client_array[0]['entity_idno']);
            Common::push_element_into_array($main_array, 'griddata', $mems);
            $jason = json_encode(array('data' => $main_array));
            $jason = str_replace("\\\\", '', $jason);
            echo $jason;
            exit();
        }
        switch ($_POST['action']) {

            case 'loadform':
                $main_array = array();
                $_pararesult = Common::$connObj->SQLSelect("SELECT productconfig_paramname,productconfig_value,productconfig_ind,productconfig_grp FROM " . TABLE_PRODUCTCONFIG . " WHERE  productconfig_paramname IN ('INTEREST_TYPE','NUMBER_OF_INSTALLMENTS','INTEREST_RATE','CURRENCIES_ID','INSTALLMENT_TYPE') AND product_prodid='" . $_POST['keyparam'] . "'");

                $data_array = Common::searchArray($_pararesult, 'productconfig_paramname', 'INTEREST_RATE');
                Common::push_element_into_array($main_array, 'intrate', round($data_array['productconfig_value'], 2));

//                $instype_array = Common::searchArray($_pararesult, 'productconfig_paramname', 'INSTALLMENT_TYPE');
//                
//                if($instype_array['productconfig_value']!=""):
//                    Common::push_element_into_array($main_array, 'INSTYPE',$instype_array['productconfig_value']); 
//                endif;
                $inttype_array = Common::searchArray($_pararesult, 'productconfig_paramname', 'INTEREST_TYPE');

//                if($inttype_array['productconfig_value']!=""):
//                    Common::push_element_into_array($main_array, 'INSTYPE',$inttype_array['productconfig_value']);                  
//                endif;

                $insno_array = Common::searchArray($_pararesult, 'productconfig_paramname', 'NUMBER_OF_INSTALLMENTS');
                Common::push_element_into_array($main_array, 'no_of_inst', $insno_array['productconfig_value']);

                $jason = json_encode(array('data' => $main_array));
                $jason = str_replace("\\\\", '', $jason);
                echo $jason;
                break 2;

            case 'grid':

                //$ar = $Conn->SQLSelect("SELECT * FROM ".TABLE_DUES." WHERE loan_number='".tep_db_prepare_input($_POST['keyparam'])."'");
                //print_r($ar);
                //echo json_encode(array("page"=>1,'records'=>$ar));
                break 2;

            case 'edit':

                $main_array = array();

                // CHECK SEE IF WE ARE PPLYING FOR ALOAN-CLIENT CODE IS SENT 
                if (preg_match('[B]', $_POST['keyparam']) || preg_match('[G]', $_POST['keyparam']) || preg_match('[I]', $_POST['keyparam']) || preg_match('[M]', $_POST['keyparam'])):
                    Clients::$clientid = $_POST['keyparam'];
                    Clients::getClientDetails();
                    Common::push_element_into_array($main_array, 'action','data');
                    Common::push_element_into_array($main_array, 'keyparam', $_POST['keyparam']);
                    Common::push_element_into_array($main_array, 'div_name', Clients::$client_array[0]['name'] . ' ' . Clients::$client_array[0]['client_idno']);
                    $jason = json_encode(array('data' => $main_array));
                else:
                    $loan_query = tep_db_query("SELECT * FROM " . TABLE_LOAN . " WHERE loan_number='" . tep_db_prepare_input($_POST['keyparam']) . "'");
                    $client_array = tep_db_fetch_array($loan_query);
                endif;

                if (isset($client_array)):

                    Common::push_element_into_array($main_array, 'loan_number', $client_array['loan_number']);
                    Common::push_element_into_array($main_array, 'action', 'update');
                    Common::push_element_into_array($main_array, 'client_idno', $client_array['client_idno']);
                    Common::push_element_into_array($main_array, 'client_idno2', $client_array['client_idno']);
                    Common::push_element_into_array($main_array, 'lamount', $client_array['loan_amount']);
                    Common::push_element_into_array($main_array, 'fund_code', $client_array['fund_code']);
                    Common::push_element_into_array($main_array, 'intrate', $client_array['loan_tint']);
                    Common::push_element_into_array($main_array, 'startDate', Common::changeMySQLDateToPageFormat($client_array['loan_startdate']));
                    Common::push_element_into_array($main_array, 'grace', $client_array['loan_grace']);
                    Common::push_element_into_array($main_array, 'no_of_inst', $client_array['loan_noofinst']);
                    Common::push_element_into_array($main_array, 'loan_adate', $client_array['loan_adate']);
                    Common::push_element_into_array($main_array, 'loan_udf1', $client_array['loan_udf1']);
                    Common::push_element_into_array($main_array, 'loan_udf2', $client_array['loan_udf2']);
                    Common::push_element_into_array($main_array, 'loan_udf3', $client_array['loan_udf3']);
                    Common::push_element_into_array($main_array, 'INTTYPE', $client_array['loan_inttype']);
                    Common::push_element_into_array($main_array, 'INSTYPE', $client_array['loan_insttype']);
                    Common::push_element_into_array($main_array, 'intgrace', $client_array['loan_alsograce']);
                    Common::push_element_into_array($main_array, 'allintpaidfirstinstallment', $client_array['loan_intpaidfirstduedate']);
                    Common::push_element_into_array($main_array, 'intpaidatdisbursement', $client_array['loan_intdeductedatdisb']);
                    Common::push_element_into_array($main_array, 'product_prodid', $client_array['product_prodid']);
                    Common::push_element_into_array($main_array, 'donor_code', $client_array['donor_code']);
                    Common::push_element_into_array($main_array, 'branch_code', $client_array['branch_code']);
                    Common::push_element_into_array($main_array, 'gracecompint', $client_array['loan_gracecompd']);
                    Common::push_element_into_array($main_array, 'loan_intindays', $client_array['loan_intdays']);
                    Common::push_element_into_array($main_array, 'comm', $client_array['loan_comm']);
                    Common::push_element_into_array($main_array, 'freezedate', $client_array['loan_freezedate']);
                    Common::push_element_into_array($main_array, 'chkupdateloan', 'Y');

                    // get gurantors
                    $guarantor_array = $Conn->SQLSelect("SELECT client_idno FROM " . TABLE_GUARANTORS . " WHERE loan_number='" . $client_array['loan_number'] . "'", false);
                    $i = 1;
                    foreach ($guarantor_array as $key => $value) {

                        switch ($i) {
                            case 1:
                                Common::push_element_into_array($main_array, 'txtclientcode1', $value['client_idno']);
                                break;

                            case 2:
                                Common::push_element_into_array($main_array, 'txtclientcode2', $value['client_idno']);
                                break;

                            case 3:
                                Common::push_element_into_array($main_array, 'txtclientcode3', $value['client_idno']);
                                break;
                        }

                        $i++;
                    }

                    $ar = $Conn->SQLSelect("SELECT due_id as item_id,due_date as date,due_principal as principal,due_interest as interest,due_commission as commission,due_penalty as penalty,members_idno as memid  FROM " . TABLE_DUES . " WHERE loan_number='" . tep_db_prepare_input($_POST['keyparam']) . "'");
                    //$jason = json_encode(array($main_array,$ar));
                    $jason = json_encode(array('data' => $main_array, 'gridinfo' => $ar));
                //$jason = json_encode($ar);

                endif;
            
                $jason = str_replace("\\\\", '', ($jason??''));
                echo $jason;
                break 2;

            default:
                break 2;
        }

        break;

    default:
        break;
}?>