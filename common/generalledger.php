<?php
 // general ledger
  class GeneralLedger {
  
  	# define the general ledger properties
	var $students_sregno;
	var $tcode;	
	var $accountcode_debit;
	var $accountcode_credit;
	var $amount;
	var $generalledger_description;		
	var $generalledger_voucher;	
	var $users_id;	
	var $transactiontypes_id;	
	var $generalledger_datecreated;	
  		
  	function PostTransactionToGL(){  
  		
	# Debit
		tep_db_query("INSERT INTO " . TABLE_GENERALLEDGER . "(
			students_sregno,
			tcode,
			chartofaccounts_accountcode,
			generalledger_description,
			generalledger_debit,			
			generalledger_voucher,
			users_id,	
			transactiontypes_id,				
			generalledger_datecreated					
			) VALUES ('". 
			$this->students_sregno ."','" . 
			$this->tcode ."','" .
			$this->accountcode_debit ."','" .
			$this->generalledger_description ."','" .
			$this->amount ."','" .			
			$this->generalledger_voucher."','" .
			$this->users_id."','".
			$this->transactiontypes_id."',".	
			changeDateFromPageToMySQLFormat($this->generalledger_datecreated).")");	
		
		# Credit
		tep_db_query("INSERT INTO " . TABLE_GENERALLEDGER . "(
			students_sregno,
			tcode,
			chartofaccounts_accountcode,
			generalledger_description,			
			generalledger_credit,
			generalledger_voucher,
			users_id,	
			transactiontypes_id,				
			generalledger_datecreated					
			) VALUES ('". 
			$this->students_sregno ."','" . 
			$this->tcode ."','" .
			$this->accountcode_credit ."','" .
			$this->generalledger_description ."','" .				
			$this->amount . "','" .
			$this->generalledger_voucher."','" .
			$this->users_id."','".
			$this->transactiontypes_id."',".	
			changeDateFromPageToMySQLFormat($this->generalledger_datecreated).")");			 
		
				
  	}
	
  }
?>