<?php
include_once('connectionfactory.php');
require_once('productconfig.php');
require_once('common.php');

Class Document extends ProductConfig {

    Public Static $aLines = null;

    Public static $connObj;
    Public static $clientcode ='';

    Public static $documents_array= array();
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
     * getClientDocuments
     * 
     * This function is used to client documents
     * @param array $formdata: Data from the form    
     */
    public static function getClientDocuments() {

        try {          
                
         self::$documents_array = Common::$connObj->SQLSelect("SELECT * FROM " . TABLE_DOCUMENT . " WHERE clientcode='" . self::$clientcode . "'");
                  
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
    public static function updateRenameKeys(&$formdata) {

        Common::replace_key_function($formdata, 'branch_code', 'BRCODE');

        Common::replace_key_function($formdata, 'document_issuedate', 'IDATE');
        Common::replace_key_function($formdata, 'document_docexpiry', 'DOCEXP');         
        Common::replace_key_function($formdata, 'documenttypes_id', 'DOCID');
        Common::replace_key_function($formdata, 'document_serial', 'SERIAL');
        Common::replace_key_function($formdata, 'document_priority', 'PRI');
        Common::replace_key_function($formdata, 'document_issuedby', 'AUTH');  
        Common::replace_key_function($formdata, 'branch_code', 'BRCODE');  
        Common::replace_key_function($formdata, 'members_idno', 'CLIENTIDNO');
        
        if($formdata['IDATE']!=''):
            $formdata['IDATE'] = Common::changeDateFromPageToMySQLFormat($formdata['IDATE']);
        else:
            $formdata['IDATE'] = NULL;
        endif;
        
        if($formdata['DOCEXP']!=''):
            $formdata['DOCEXP'] = Common::changeDateFromPageToMySQLFormat($formdata['DOCEXP']);
        else:
            $formdata['DOCEXP'] = NULL;
        endif;

        return $formdata;
        
    }


    /**
     * updateDocument
     * 
     * This function is used to update document details
     * @param array $formdata: Data from the form    
     */
    public static function updateDocument(&$formdata) {

        self::$aLines = array();

        try {

            Bussiness::$Conn->AutoCommit = false;

            Bussiness::$Conn->beginTransaction();

  
            $amtExtra = 0;

            $nCount = count($formdata);

            $x = 1;

            foreach ($formdata as $key => &$value) {

                $x++;
            
                if($value['SERIAL']!=""):
                    $value['TABLE'] = TABLE_DOCUMENT;                   
                endif;                  

                 
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