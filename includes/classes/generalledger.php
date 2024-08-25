<?php
// call parent function of  of this function
include_once('connectionfactory.php'); 
require_once('productconfig.php');
require_once('common.php');
require_once('financial_class.php');

class GeneralLedger {

	private static $_instance = null;
	private static $FinFuncObj;
	private static $commonObj;
	private static $connObj;
	private static $Transactions_array = array();
	
	public  function GeneralLedger(){		
		
		
		$commonObj = new Common;			
		//self::$connObj = ConnectionFactory::getInstance();			
		//$FinFuncObj = new Financial();
	
		
		 if (self::$_instance === null) {
          //  self::$_instance = new self;
         }
		 
		  return $this;
	}
	
	
	public static function getInstance ()
    {
        if (self::$_instance === null) {
            self::$_instance = new self;
        }

        return self::$_instance;
    }
	
	public static function prepareTransactions($transactions = array()){		
			
			if(count($transactions)>0){
				self::$Transactions_array[self::$commonObj->generateTransactionCode()] = $transactions;	
			}	
	}
	
	

  
}?>