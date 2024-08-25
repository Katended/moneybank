<?php
 // Settings
  class Settings {
  
		# define the Setting properties
		var $configurationsettings;	
			
		function UpdateSettings(){ 			
			
			foreach($this->configurationsettings as $key =>$value){			
				tep_db_query("UPDATE " . TABLE_CONFIGURATION ." SET configuration_value='".$value."' WHERE configuration_key='".$key."'");				
			}		
						
					
		}
		
		function UpdateAccounts(){ 			
			
			foreach($this->configurationsettings as $key =>$value){			
				tep_db_query("UPDATE " . TABLE_ACCOUNTSCONFIG ." SET accountsconfig_value='".$value."' WHERE accountsconfig_key='".$key."'");				
			}		
						
					
		}	
		
	
 }
?>