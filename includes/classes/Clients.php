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
        
        $replacements = [
            'branch_code' => 'BRCODE',
            'client_postad' => 'POSTAD',
            'client_enddate' => 'EDATE',
            'client_city' => 'CITY',
            'client_addressphysical' => 'PAD',
            'client_tel1' => 'TEL1',
            'client_tel2' => 'TEL2',
            'clientcode' => 'CCODE',
            'client_regstatus' => 'STATUS',
            'areacode_code' => 'ACODE',
            'bussinesssector_code' => 'BCODE',
            'client_surname' => 'SNAME',
            'client_firstname' => 'FNAME',
            'client_middlename' => 'MNAME',
            'client_gender' => 'GENDER',
            'client_emailad' => 'EMAIL',
            'client_postad' => 'PAD',
            'client_cat1' => 'CAT1',
            'client_cat2' => 'CAT2',
            'costcenters_code' => 'CCODE',
            'client_idno' => 'CLIENTIDNO',
            'client_regdate' => 'RDATE',
            'client_enddate' => 'EDATE',
            'client_bday' => 'BDAY',
            'client_occupation' => 'OCP',
            'client_kinname' => 'KIN',
            'client_maritalstate' => 'MSTATE',
            'entity_name' => 'ENAME',
            'member_regdate' => 'MRDATE',
            'member_firstname' => 'FNAME',
            'member_middlename' => 'MNAME',
            'member_lastname' => 'LNAME',
            'member_maritalstate' => 'MSTAT',
            'member_children' => 'CHILD',
            'member_dependants' => 'DEP',
            'member_category1_id1' => 'CAT1',
            'member_category1_id2' => 'CAT2',
            'member_educationlevel_id' => 'EDUC',
            'member_income' => 'INCOME',
            'member_clientlanguages_id1' => 'LANG1',
            'member_clientlanguages_id2' => 'LANG2',
            'member_incomecategories_id' => 'INCOMEID',
            'member_email' => 'EMAIL',
            'member_regstatus' => 'STATUS',
            'member_no' => 'MNO',
            'member_enddate' => 'MEDATE',
            'member_bday' => 'BDAY',
            'member_regstatus' => 'STATUS',
            'members_idno' => 'MID',
            'document_issuedate' => 'IDATE',
            'document_docexpiry' => 'DOCEXP',
            'documenttypes_id' => 'DOCID',
            'document_serial' => 'SERIAL',
            'client_type' => 'CTYPE',
            'branch_code' => 'BRCODE',
            'theid' => 'CLIENTIDNO',
        ];
        
        $dateFields = ['RDATE', 'EDATE', 'BDAY', 'MRDATE', 'MEDATE'];
        
        foreach ($formdata as $key => $value) {
            if (array_key_exists($key, $replacements)) {
                $formdata[$replacements[$key]] = $value;
                unset($formdata[$key]);
            } elseif (in_array($key, $dateFields)) {
                $formdata[$key] = !empty($formdata[$key])
                    ? Common::changeDateFromPageToMySQLFormat($formdata[$key])
                    : null;
            }
        }

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