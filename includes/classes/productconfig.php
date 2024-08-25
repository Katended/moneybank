<?php
class ProductConfig extends ConnectionFactory 
{
	public  $thedata;	
	
	
	public  function __construct($product_prodid='',$branchcode=BRANCHCODE){
		if(!defined('TABLE_PRODUCTCONFIG')){
                    define('TABLE_PRODUCTCONFIG' ,'productconfig');
		}
		
		
		 // get connection
		 $connObj= parent::getInstance();		 
		 // execute conection
		 $this->thedata =  $connObj->SQLSelect("SELECT * FROM ".TABLE_PRODUCTCONFIG." WHERE product_prodid='".$product_prodid."' AND branch_code='".$branchcode."'");
		 
		 // define product public vars
		 self::defineproductvars();
		//print_r(self::$thedata);
	}
	
	
	
	public  function defineproductvars(){
	
		  foreach($this->thedata as $key => $val){
		  	
				define($val['productconfig_paramname'],$val['productconfig_value']);			
				//echo $val['productconfig_value'];
		  }
		  
		
	
	}

}

//$prod = new ProductConfig();
//$prod->defineproductvars();
?>