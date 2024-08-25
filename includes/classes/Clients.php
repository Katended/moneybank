<?php
include_once('connectionfactory.php');
require_once('productconfig.php');
require_once('common.php');

Class Clients extends ProductConfig {

    Public Static $aLines = null;

    Public static $connObj;
    Public static $clientid ='';

    Public static $client_array = array();
    Public static $members_array = array();
    public function __Construct() {
        parent::ProductConfig();
        return $this;
    }

    public static function getInstance() {
        if (self::$_instance === null) {
            self::$_instance = new self;
        }
        return self::$_instance;
    }
    /**
     * getGroupmemberDetails
     * 
     * This function is used to group member details
     * @param array $formdata: Data from the form    
     */
    public static function getGroupmemberDetails() {      
       // Common::$connObj
        
        self::$members_array = Common::$connObj->SQLSelect("SELECT entity_idno,members_idno,CONCAT(members_firstname,' ',members_middlename,' ',members_lastname) Name,members_no FROM " .TABLE_MEMBERS. " WHERE entity_idno='" .self::$clientid."' ORDER BY members_no,members_idno,members_firstname");
        return  self::$members_array ;
               
    }
    /**
     * getClientDetails
     * 
     * This function is used to client details
     * @param array $formdata: Data from the form    
     */
    public static function getClientDetails() {

        try {

            switch (self::$clientid):
                case (preg_match('/I/', self::$clientid) ? true : false) :
                case (preg_match('/B/', self::$clientid) ? true : false) :
                    self::$client_array = Common::$connObj->SQLSelect("SELECT branch_code,client_idno,CONCAT(client_surname,' ',client_firstname,' ',client_middlename) Name FROM " . TABLE_CLIENTS . " WHERE client_idno='" . self::$clientid . "'");
                    break;

                case (preg_match('/G/', self::$clientid) ? true : false) :
                    self::$client_array = Common::$connObj->SQLSelect("SELECT branch_code,entity_idno,entity_name Name FROM " . TABLE_ENTITY . " WHERE entity_idno='" . self::$clientid . "'");
                    self::getGroupmemberDetails();
                    break;

                case (preg_match('/M/', self::$clientid) ? true : false) :
                    self::$client_array = Common::$connObj->SQLSelect("SELECT entity_idno,members_idno,CONCAT(members_firstname,' ',members_middlename,' ',members_lastname) Name,members_no FROM " . TABLE_MEMBERS . " WHERE members_idno='" . self::$clientid . "'");
                    break;
            endswitch;
            
        } catch (Exception $e) {
            Bussiness::$Conn->cancelTransaction();
            Common::$lablearray['E01'] = $e->getMessage();
        }
    }


     /**
     * updateClient
     * 
     * This function is used to update keys
     * @param array $formdata: Data from the form    
     */
    public static function updateRenameKeys(&$formdata,$ctype ='') {

        Common::replace_key_function($formdata, 'branch_code', 'BRCODE');

        switch($ctype):
        case 'G':
        case 'B':
        case 'I':          
            
            
            Common::replace_key_function($formdata, 'client_postad', 'POSTAD');
            Common::replace_key_function($formdata, 'client_enddate', 'EDATE');
            Common::replace_key_function($formdata, 'client_city', 'CITY');
            Common::replace_key_function($formdata, 'client_addressphysical', 'PAD');
            Common::replace_key_function($formdata, 'client_tel1', 'TEL1');
            Common::replace_key_function($formdata, 'client_tel2', 'TEL2');
            Common::replace_key_function($formdata, 'clientcode', 'CCODE');
            Common::replace_key_function($formdata, 'client_regstatus', 'STATUS');
            Common::replace_key_function($formdata, 'areacode_code', 'ACODE');
            Common::replace_key_function($formdata, 'bussinesssector_code', 'BCODE');               
            Common::replace_key_function($formdata, 'client_surname', 'SNAME');
            Common::replace_key_function($formdata, 'client_firstname', 'FNAME');
            Common::replace_key_function($formdata, 'client_middlename', 'MNAME');
            Common::replace_key_function($formdata, 'client_gender', 'GENDER');            
            Common::replace_key_function($formdata, 'client_emailad', 'EMAIL');
            Common::replace_key_function($formdata, 'client_postad', 'PAD');      
            Common::replace_key_function($formdata, 'client_cat1', 'CAT1');
            Common::replace_key_function($formdata, 'client_cat2', 'CAT2');
            Common::replace_key_function($formdata, 'costcenters_code', 'CCODE');                               
            Common::replace_key_function($formdata, 'client_idno', 'CLIENTIDNO');
            Common::replace_key_function($formdata, 'client_regdate', 'RDATE');
            Common::replace_key_function($formdata, 'client_idno', 'EDATE');
            Common::replace_key_function($formdata, 'client_bday', 'BDAY');                
            Common::replace_key_function($formdata, 'client_occupation', 'OCP');
            Common::replace_key_function($formdata, 'client_kinname', 'KIN');
            Common::replace_key_function($formdata, 'client_maritalstate', 'MSTATE');
            $formdata['MSTATE'] =  $formdata['MSTATE']??'';

            break;
      
        case 'M':

            Common::replace_key_function($formdata, 'client_idno', 'CLIENTIDNO');
            Common::replace_key_function($formdata, 'member_regdate', 'MRDATE');
            Common::replace_key_function($formdata, 'member_firstname', 'FNAME');
            Common::replace_key_function($formdata, 'member_middlename', 'MNAME');
            Common::replace_key_function($formdata, 'member_lastname', 'LNAME');
            Common::replace_key_function($formdata, 'member_maritalstate', 'MSTAT');
            Common::replace_key_function($formdata, 'member_children', 'CHILD');
            Common::replace_key_function($formdata, 'member_dependants', 'DEP');
            Common::replace_key_function($formdata, 'member_category1_id1', 'CAT1');
            Common::replace_key_function($formdata, 'member_category1_id2', 'CAT2');
            Common::replace_key_function($formdata, 'member_educationlevel_id', 'EDUC');
            Common::replace_key_function($formdata, 'member_income', 'INCOME');
            Common::replace_key_function($formdata, 'member_clientlanguages_id1', 'LANG1');
            Common::replace_key_function($formdata, 'member_clientlanguages_id2', 'LANG2');
            Common::replace_key_function($formdata, 'member_incomecategories_id', 'INCOMEID');
            Common::replace_key_function($formdata, 'member_email', 'EMAIL');
            Common::replace_key_function($formdata, 'member_regstatus', 'STATUS');
            Common::replace_key_function($formdata, 'member_no', 'MNO');
            Common::replace_key_function($formdata, 'member_enddate', 'MEDATE');
            Common::replace_key_function($formdata, 'member_bday', 'BDAY');
            Common::replace_key_function($formdata, 'member_regstatus', 'STATUS');
            Common::replace_key_function($formdata, 'members_idno', 'MID');
       
            break;

        case 'D':
            
            Common::replace_key_function($formdata, 'document_issuedate', 'IDATE');
            Common::replace_key_function($formdata, 'document_docexpiry', 'DOCEXP');         
            Common::replace_key_function($formdata, 'documenttypes_id', 'DOCID');
            Common::replace_key_function($formdata, 'document_serial', 'SERIAL');
            
            break;

        default:
            break;
        endswitch;

        Common::replace_key_function($formdata, 'client_type', 'CTYPE');        
        Common::replace_key_function($formdata, 'branch_code', 'BRCODE');        
        Common::replace_key_function($formdata, 'theid', 'CLIENTIDNO');

        // fix dates
        switch($ctype):
            case 'I':
            case 'B':
            case 'G':

                // Registration Date
                if($formdata['RDATE']!=''):
                    $formdata['RDATE'] = Common::changeDateFromPageToMySQLFormat($formdata['RDATE']);
                else:
                    $formdata['RDATE'] = NULL;
                endif;

                // End Date
                if($formdata['EDATE']!=''):
                    $formdata['EDATE'] = Common::changeDateFromPageToMySQLFormat($formdata['EDATE']);
                else:
                    $formdata['EDATE'] = NULL;
                endif;

                // Birth Date
                if($formdata['BDAY']!=''):
                    $formdata['BDAY'] = Common::changeDateFromPageToMySQLFormat($formdata['BDAY']);
                else:
                    $formdata['BDAY'] = NULL;
                endif;

                break;

            case 'M': 

                if($formdata['MRDATE']!="")
                    $formdata['MRDATE'] = Common::changeDateFromPageToMySQLFormat($formdata['MRDATE']);
    
                if($formdata['MEDATE']!=""):
                    $formdata['MEDATE'] = Common::changeDateFromPageToMySQLFormat($formdata['MEDATE']);
                else:
                    $formdata['MEDATE'] = "NULL";    
                endif;

                if($formdata['BDAY']!=""):
                    $formdata['BDAY'] = Common::changeDateFromPageToMySQLFormat($formdata['BDAY']);
                else:
                    $formdata['BDAY'] = "NULL";    
                endif;
           
                break;

            default:
                break;
            endswitch;


        return $formdata;
        
    }


    /**
     * updateClient
     * 
     * This function is used to update Time Deposit transactions
     * @param array $formdata: Data from the form    
     */
    public static function updateClient(&$formdata) {

        self::$aLines = array();

        try {

    
           Bussiness::$Conn->setAutoCommit(true);

            Bussiness::$Conn->beginTransaction();

            $amtExtra = 0;

            $nCount = count($formdata);

            $x = 1;

            foreach ($formdata as $key => &$value) {

                $x++;
                
                 if ($value['BRCODE'] == ""):
                    $value['BRCODE'] = Common::extractBranchCode($value['CLIENTIDNO']);
                endif;
                
                switch ($value['CTYPE']):
                    case 'I':
                    case 'B':
                        
                        if($value['ACTION'] =='add'):                   
                            $value['CLIENTIDNO'] = Common::generateID($value['BRCODE'].'/'.$value['CTYPE'], $value['CTYPE'],($value['CTYPE']=='I')?'CLIENT':'BUSINESS' );                     
                        endif;
                        
                        if(($value['SERIAL']??'')!=""):
                            $value['TABLE'] = TABLE_DOCUMENT;
                            Bussiness::covertArrayToXML(array($value), false);
                        endif;
                        if($value['CTYPE']=='B'):
                            $value['TABLE'] = TABLE_ENTITY;
                        else:
                            $value['TABLE'] = TABLE_CLIENTS;
                        endif;                     
                        
                        break;

                    case 'M':
                        
                        if(($value['SERIAL']??'')!=""):
                            $value['TABLE'] = TABLE_DOCUMENT;
                            Bussiness::covertArrayToXML(array($value), false);
                        endif;
                        
                         // check see if the member number is registered
                         if($value['ACTION'] =='add'): 
                            $members_array = call_user_func_array('array_merge', Common::$connObj->SQLSelect("SELECT members_no FROM " . TABLE_MEMBERS . "  WHERE members_no='" . $value['MNO'] . "' AND entity_idno='".$value['CLIENTIDNO']."'")); 

                            if(isset($members_array['members_no'])):               
                                Common::getlables("1637", "", "", $Conn);
                                throw new Exception(Common::$lablearray['1637'].'-'.$value['MNO']);                                    
                            endif;
                            // $value['GROUPIDNO']
                            // GET MEMBER IDs
                            
                            $value['MID'] = Common::generateID($value['BRCODE'].'/'.$value['CTYPE'],'M','MEMBER',$value['CLIENTIDNO']);
                            $value['MNO'] = Common::generateID($value['BRCODE'].'/M','M','MEMBERNO',$value['CLIENTIDNO']);
                            
                        endif;
                        
                        $value['TABLE'] = TABLE_MEMBERS;
                        
                        break;

                    case 'G':

                        if($value['ACTION'] =='add'):                   
                            $value['CLIENTIDNO'] = Common::generateID($value['BRCODE'].'/'.$value['CTYPE'], $value['CTYPE'], 'GROUP');                     
                        endif;

                        if($value['MID']=='XXX/M'):
                            
                            $action = $value['ACTION'];

                            $value['ACTION'] ='add';

                            $value['MID'] =  Common::generateID($value['BRCODE'].'/M','M','MEMBERID',$value['CLIENTIDNO']); //Common::generateID($value['BRCODE'].'/M', 'M', $value['CLIENTIDNO'] );                     
                            $value['MNO'] = Common::generateID($value['BRCODE'].'/MNO','M','MEMBERNO',$value['CLIENTIDNO']);                            
                            $value['TABLE'] = TABLE_MEMBERS;
                            Bussiness::covertArrayToXML(array($value), false);    
                            $value['ACTION'] = $action;
                        endif;
                        
                        
                        $value['TABLE'] = TABLE_ENTITY;
                        break;

                    default:
                        //TABLE_DOCUMENT                        
                        $value['TABLE'] = TABLE_CLIENTS;
                        break;

                endswitch;
                          
                if ($x >= $nCount):
                    Bussiness::covertArrayToXML(array($value), true);
                else:
                    Bussiness::covertArrayToXML(array($value), false);
                endif;
            }

            $tabledata['xml_data'] = Common::$xml;

            // save 
            Bussiness::PrepareData(array("FORMDATA" => $tabledata, "OPTIONS" => array('' => 1)), TABLE_XMLTRANS, false);

            if (Common::$lablearray['E01'] != "") {
                Bussiness::$Conn->cancelTransaction();
                throw new Exception(Common::$lablearray['E01']);
            }

            Bussiness::$Conn->endTransaction();
            
        } catch (Exception $e) {
            Bussiness::$Conn->cancelTransaction();
            Common::$lablearray['E01'] = $e->getMessage();
        }
    }

}
?>