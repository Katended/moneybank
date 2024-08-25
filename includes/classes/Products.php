<?php
include_once('connectionfactory.php');
require_once('productconfig.php');
require_once('common.php');
Class Products extends ProductConfig {

    Public static $connObj;
    Public static $aLines = null;

    public static function getInstance() {
        if (self::$_instance === null) {
            self::$_instance = new self;
        }

        return self::$_instance;
    }   

    /**
     * updateProducts
     * 
     * This function is used to update Products
     * @param array $formdata: Data from the form
     * Returns array
     */
    public static function updateProducts(&$formdata) {

        // self::$aLines = array();

        try {

            Bussiness::$Conn->AutoCommit = false;

            Bussiness::$Conn->beginTransaction();

            foreach ($formdata as $key => $value) {

                $value['TABLE'] = TABLE_PRODUCT;
                Bussiness::covertArrayToXML(array($value), true);

            }

      
            // save 
            Bussiness::PrepareData(true);

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