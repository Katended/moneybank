<?php
include_once('connectionfactory.php');
require_once('common.php');
Class Modem extends ProductConfig {

    Public static $connObj;

    public static function getInstance() {
        if (self::$_instance === null) {
            self::$_instance = new self;
        }

        return self::$_instance;
    }

   
    /**
     * updateSavings
     * 
     * This function is used to update Modem Settings
     * @param array $formdata: Data from the form
     * Returns array
     */
    public static function updateModem(&$formdata) {

        try {

            Bussiness::$Conn->AutoCommit = false;

            Bussiness::$Conn->beginTransaction();

            foreach ($formdata as $key => $value) {
                $value['TABLE'] = TABLE_MODEM;
                Bussiness::covertArrayToXML(array($value), true);               
            }
            
            $tabledata['xml_data'] = Common::$xml;  
             
            Bussiness::PrepareData(array("FORMDATA" => $tabledata, "OPTIONS" => array('' => 1)), TABLE_XMLTRANS, false);

            if (Common::$lablearray['E01'] != "") {
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