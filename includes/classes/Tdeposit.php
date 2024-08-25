<?php
require_once('connectionfactory.php');
require_once('productconfig.php');
require_once('common.php');

Class Tdeposit extends ProductConfig {
     
    Public Static $aLines = null;
    Public Static  $tdeposit_array = array('tnumber'=>'','amount'=>0,'intrate'=>0,'intamt'=>0,'period'=>0,'ddate'=>'','instype'=>'','matdate'=>'','status'=>'','intcapital'=>'N','prodid'=>''); 
    Public Static  $transactioncode ='';

    
    public static function getInstance() {
        if (self::$_instance === null) {
            self::$_instance = new self;
        }          
        return self::$_instance;
    }
    
    /**
     * getTimeDeposit
     * 
     * This function is used to get time deposit details
     * @param array $formdata: Data from the form    
     */
    public static function getTimeDeposit() {
        
        $deposit_array = Common::$connObj->SQLSelect("SELECT tr.*,t.client_idno,t.product_prodid FROM " . TABLE_TDEPOSITTRANS . " tr," . TABLE_TDEPOSIT . " t WHERE  tr.timedeposit_number=t.timedeposit_number  AND tr.transactioncode='" . self::$transactioncode . "'");
                
        self::$tdeposit_array['tnumber'] = $deposit_array[0]['timedeposit_number'];
        self::$tdeposit_array['amount'] = $deposit_array[0]['timedeposit_amount'];
        self::$tdeposit_array['intrate'] = $deposit_array[0]['timedeposit_interestrate'];
        self::$tdeposit_array['intamt'] = $deposit_array[0]['timedeposit_intamt'];        
        self::$tdeposit_array['period'] = $deposit_array[0]['timedeposit_period'];
        self::$tdeposit_array['instype'] = $deposit_array[0]['timedeposit_instype'];
        self::$tdeposit_array['matdate'] = $deposit_array[0]['timedeposit_matdate']; 
        self::$tdeposit_array['ddate'] = $deposit_array[0]['timedeposit_date'];
        self::$tdeposit_array['status'] = $deposit_array[0]['timedeposit_status'];
        self::$tdeposit_array['intcap'] = $deposit_array[0]['timedeposit_intcapital'];
        self::$tdeposit_array['prodid'] = $deposit_array[0]['product_prodid'];
        
        return $deposit_array;
    }
     /*
     * calculateInterest
     * This function is used to calculate interest on time deposits     
     */
     public static function calculateInterest($action='') {
                
         // claculate maturity date
         if($action=='ADD'):
             $matdate =  Common::calculateDate('+', self::$tdeposit_array['ddate'], self::$tdeposit_array['period'],self::$tdeposit_array['instype'],'0','Y');        
              self::$tdeposit_array['matdate'] =  $matdate['date'];
         endif;
       
       
        
        switch (self::$tdeposit_array['instype']) {
        case 'D':
            self::$tdeposit_array['intamt'] =  (self::$tdeposit_array['amount'] * (self::$tdeposit_array['intrate'] / 100) * (self::$tdeposit_array['period'] / SETTING_INT_DAYS));
            break;

        case 'W':
            self::$tdeposit_array['intamt'] =  (self::$tdeposit_array['amount'] * (self::$tdeposit_array['intrate']  / 100) * ((self::$tdeposit_array['period'] / 7) / SETTING_INT_WEEK));
            break;

        case 'B':
            self::$tdeposit_array['intamt'] =  (self::$tdeposit_array['amount']  * (self::$tdeposit_array['intrate']  / 100) * ((self::$tdeposit_array['period'] / 14) / (SETTING_INT_WEEK / 2)));
            break;

        case 'O':
            self::$tdeposit_array['intamt'] =  (self::$tdeposit_array['amount']  * (self::$tdeposit_array['intrate']  / 100) * ((self::$tdeposit_array['period'] / 28) / (SETTING_INT_WEEK / 4)));
            break;

        case 'M':
            self::$tdeposit_array['intamt'] = (self::$tdeposit_array['amount']  * (self::$tdeposit_array['intrate']  / 100) * (self::$tdeposit_array['period'] / 12));
            break;
        }
        
        self::$tdeposit_array['matval'] = bcadd(self::$tdeposit_array['amount'] , self::$tdeposit_array['intamt'],SETTING_ROUNDING);
            
    }

    /**
     * updateTimeDeposit
     * 
     * This function is used to update Time Deposit transactions
     * @param array $formdata: Data from the form    
     */
    public static function updateTimeDeposit(&$formdata) {

        self::$aLines = array();
        
        Common::getlables("311,1615,1216,1605,171,1216,382", "", "", Common::$connObj); 
        
        $accounts_array =array();
        $products_array =array();
            
        try {

            Bussiness::$Conn->AutoCommit = false;

            Bussiness::$Conn->beginTransaction();

           // Common::defineCosntants('T');
            $amtExtra = 0;            
            
            foreach ($formdata as $key => &$value) {
                
                if($value['BRANCHCODE']==""):
                    $value['BRANCHCODE']   = Common::extractBranchCode($value['CLIENTIDNO']);
                endif;
                
                $formdate = $value['DATE'];
             
                // GET TD NO
                if($value['STATUS']=='TD'):
                    
                  $value['TABLE'] = TABLE_TDEPOSIT;
                  
                  $ctype = Common::getClientType($value['CLIENTIDNO']);

                  $value['TDNO']  =  Common::generateID($value['CLIENTIDNO'],$ctype,'TIMEDEP');
                  
                    if($value['POSTTOSL']==true):
                        Bussiness::covertArrayToXML(array($value), false);    
                    endif;
                
                endif;
                              
                
                $value['TABLE'] = TABLE_TDEPOSITTRANS;
                
                if($value['STATUS']=='TR' || $value['STATUS']=='TW'):
                    
                   // $value['DATE'] = Common::changeDateFromPageToMySQLFormat($value['DATE']);
                
                
                    $nfullDays =  Common::getNumberOfDaysBetweenDates(Common::changeMySQLDateToPageFormat(self::$tdeposit_array['ddate']),Common::changeMySQLDateToPageFormat(self::$tdeposit_array['matdate']));
                  
                    $actualDays =   Common::getNumberOfDaysBetweenDates(Common::changeMySQLDateToPageFormat(self::$tdeposit_array['ddate']),Common::changeMySQLDateToPageFormat($value['DATE']));
                  
                    if($actualDays < $nfullDays):
                        
                      //  if($value['INSTYPE']=='M'):
                             Self::$tdeposit_array['period'] = ROUND(($actualDays/$nfullDays),SETTTING_ROUND_TO);                        
                      //  endif;
                    
                    endif;
                            
                    // calculate original interest            
                     self::calculateInterest('');                     
                     
                    $value['OINTAMT'] = self::$tdeposit_array['intamt'];
                    $value['OMATVAL'] = self::$tdeposit_array['matval'];
                   
                endif;
                
                if($value['STATUS']=='TR'):
                    if($value['INTCAP']=='Y'):
                        $value['AMOUNT'] =  $value['AMOUNT'] + $value['OINTAMT'];
                    endif;  
                endif;
                
                // check see if we are creating new time deposit
                // calculate new interest 
                if($value['STATUS']=='TR' || $value['STATUS']=='TD'):
                    
                    Self::$tdeposit_array['amount'] = $value['AMOUNT'];           
                    Self::$tdeposit_array['period'] = $value['PERIOD'];
                    Self::$tdeposit_array['intrate'] = $value['INT'];
                    Self::$tdeposit_array['ddate'] = $formdate;
                    Self::$tdeposit_array['instype'] = $value['INSTYPE'];
                    self::calculateInterest('ADD');
                endif;
                
                $value['INTAMT'] = self::$tdeposit_array['intamt'];

                $value['MATDATE'] = Common::changeDateFromPageToMySQLFormat(self::$tdeposit_array['matdate']);
                
                $value['MATVAL'] = self::$tdeposit_array['matval'];    
                
                $value['DATE'] = Common::changeDateFromPageToMySQLFormat($value['DATE']);
                
                if($value['POSTTOSL']==true):
                    Bussiness::covertArrayToXML(array($value), false);    
                endif;                     
               
                        
                $ctype1 = (isset($value['CLIENTIDNO']) ? Common::getClientType($value['CLIENTIDNO']): Common::getClientType($value['SAVACC']));
                
                //RENEW AND CAPITALISE INTEREST
                if($value['POSTTOGL']=='TR' && $value['INTCAP']=='Y'):
                    continue;
                endif;
                
                //ADD TO  GL          
                switch ($value['STATUS']) {

                case 'TD': // Open         
                case 'TR': // Renew
                case 'TM': // Modify
                   
                    // TAKE ONLY INTEREST FOR RENEWALS
                    if ($value['POSTTOGL'] == true):
                            if ($value['STATUS'] == 'TR'):


                                // WITHDRAW                        
                                self::$aLines[] = array('AMOUNT' => abs($value['OINTAMT']), 'DESC' => Common::$lablearray['1615'] . ' ' . $value['TDNO'], 'TTYPE' => $value['STATUS'], 'CLIENTIDNO' => $value['CLIENTIDNO'], 'PRODUCT_PRODID' => $value['PRODUCT_PRODID'], 'CTYPE' => $ctype1, 'GLACC' => '', 'TRANCODE' => 'TW000', 'SIDE' => 'DR', 'SAVACC' => $value['SAVACC']);

                                //RENEW
                                self::$aLines[] = array('AMOUNT' => abs($value['OINTAMT']), 'DESC' => Common::$lablearray['1615'] . ' ' . $value['TDNO'], 'TTYPE' => $value['STATUS'], 'CLIENTIDNO' => $value['CLIENTIDNO'], 'PRODUCT_PRODID' => $value['PRODUCT_PRODID'], 'CTYPE' => $ctype1, 'GLACC' => '', 'TRANCODE' => 'TD000', 'SIDE' => 'CR', 'SAVACC' => $value['SAVACC']);



                            //                           if($value['POSTTOGL']==true): 
                            //                                // WITHDRAW                        
                            //                              self::$aLines[] = array('AMOUNT' => abs($value['OAMOUNT']), 'DESC' => Common::$lablearray['1614'] . ' ' . $value['TDNO'], 'TTYPE' => $value['STATUS'], 'CLIENTIDNO' => $value['CLIENTIDNO'], 'PRODUCT_PRODID' => $value['PRODUCT_PRODID'], 'CTYPE' => $ctype1, 'GLACC' => '', 'TRANCODE' => 'TW000', 'SIDE' => 'DR', 'SAVACC' => $value['SAVACC']);
                            //
    //                                //RENEW
                            //                              self::$aLines[] = array('AMOUNT' => abs($value['AMOUNT']), 'DESC' =>$value['DESC'] . ' ' . $value['TDNO'], 'TTYPE' => $value['STATUS'], 'CLIENTIDNO' => $value['CLIENTIDNO'], 'PRODUCT_PRODID' => $value['PRODUCT_PRODID'], 'CTYPE' => $ctype1, 'GLACC' => '', 'TRANCODE' => 'TD000', 'SIDE' => 'CR', 'SAVACC' => $value['SAVACC']);
                            //                          endif;
                            //                          
                            //                          if($value['AMOUNT'] > $value['OAMOUNT']):
                            //                             $value['AMOUNT'] = $value['AMOUNT'] - $value['OAMOUNT'];                                        
                            //                          endif;  
                            //                          
                            //                          // WE ARE RENEWING SO NO NEED OF PAYMENT MODE
                            //                          // THE MONEY IS ALREADY IN THE ORGANISATION
                            //                           $value['MODE'] ='';

                            else:
                                self::$aLines[] = array('AMOUNT' => abs($value['AMOUNT']), 'DESC' => $value['DESC'] . '-' . $value['TDNO'], 'TTYPE' => $value['STATUS'], 'CLIENTIDNO' => $value['CLIENTIDNO'], 'PRODUCT_PRODID' => $value['PRODUCT_PRODID'], 'CTYPE' => $ctype1, 'GLACC' => '', 'TRANCODE' => 'TD000', 'SIDE' => 'CR', 'SAVACC' => $value['SAVACC']);

                            endif;
                        endif;

                        break;

                case 'TW': // Close Time Deposit  
                   if($value['POSTTOGL']==true):                     
                        self::$aLines[] = array('AMOUNT' => abs($value['AMOUNT']), 'DESC' => $value['DESC'].'-'.$value['TDNO'], 'TTYPE' => $value['STATUS'], 'CLIENTIDNO' => $value['CLIENTIDNO'], 'PRODUCT_PRODID' => $value['PRODUCT_PRODID'], 'CTYPE' => $ctype1, 'GLACC' => '', 'TRANCODE' => 'TW000', 'SIDE' => 'DR', 'SAVACC' => $value['SAVACC']);
                    endif;        
                    break;

                default:
                    break;
                }
                
                  $intAmt = 0;
                  
                 // INTEREST
                 if( $value['STATUS'] =='TW'):
                     
                     if(self::$tdeposit_array['intamt'] > 0):
                         if($value['POSTTOGL']==true): 
                            self::$aLines[] = array('AMOUNT' => abs(self::$tdeposit_array['intamt']), 'DESC' => Common::$lablearray['1615'].'-'.$value['TDNO'], 'TTYPE' => 'DINT', 'CLIENTIDNO' => $value['CLIENTIDNO'], 'PRODUCT_PRODID' => $value['PRODUCT_PRODID'], 'CTYPE' => $ctype1, 'GLACC' => '', 'TRANCODE' => 'TDI00', 'SIDE' => 'DR', 'SAVACC' => $value['SAVACC']);                      
                         endif; 
                     endif;
                     
                     if($value['STATUS'] =='TW'):
                         $value['AMOUNT'] =($value['AMOUNT']+ self::$tdeposit_array['intamt']); 
                     else:
                          $intAmt = abs(self::$tdeposit_array['intamt']);
                     endif;
                                                             
                 endif;
                         
                 // CASH, SAVINGS OR  CHEQUE
                switch ($value['MODE']) {
                case 'CA': // Cach
                    
                    $trancode1 = 'CB000';
                    $trancode2 = 'CB000';
                    $trancode3 = 'CB000';
                    
                    $amount1 = abs($amtExtra);
                    $amount2 = abs($value['AMOUNT']);
                    $amount3 = abs($intAmt);
                    $glacc = $value['GLACC'];
                    $mode = $value['MODE'];
                    
                    $prodid =  $value['PRODUCT_PRODID'];
                                            
                    $desc1 = Common::$lablearray['311'];
                    $desc2 = Common::$lablearray['311'];
                    $desc3 =Common::$lablearray['311'].' '.$value['TDNO'] ;

                    break;
               
                case 'SA': // Savings Transfer  
                    
                   
                    if($value['AMOUNT']>0):                        
                                                           
                        if ($value['STATUS']=='TD' || $value['STATUS']=='TM') {
                            $value['AMOUNT'] = $value['AMOUNT']*-1;
                        }  

                        if($value['STATUS'] =='TR' || $value['STATUS'] =='TD' ){
                            
                            Savings::$prodid =  $value['PRODUCT_PRODIDFR'];
                            Savings::$savacc  =  $value['SAVACC'];      
                            Savings::$membershipid =  $value['MEMID'];   
                            Savings::$asatdate =  $value['DATE'];       
                                    
                            if(!Savings::getSavingsBalance($value['AMOUNT'])):
                                Common::$lablearray['E01'] =  Common::$lablearray['1216'].' '.$value['SAVACC'].' '.$bal_array['balance'];                            
                                THROW NEW Exception(Common::$lablearray['E01']);
                            endif;
                        
                           
                        }
                        
                         $value['TABLE'] = TABLE_SAVTRANSACTIONS;
                         
                        $tempprodid = $value['PRODUCT_PRODID'];              

                        $value['PRODUCT_PRODID'] = $value['PRODUCT_PRODIDFR'];

                        $value['TTYPE'] = $value['MODE'];                              
                         
                         // INTEREST
                        if($intAmt > 0):
                                                      
                            $tdAmt = $value['AMOUNT'];
                        
                            $value['AMOUNT'] = $intAmt;
                            
                            if($value['POSTTOSL']==true):
                                Bussiness::covertArrayToXML(array($value), false);
                            endif;
                         //   self::$aLines[] = array('AMOUNT' => abs($intAmt), 'DESC' => Common::$lablearray['171'].' '.$value['TDNO'], 'TTYPE' => $value['MODE'] , 'CLIENTIDNO' => $value['CLIENTIDNO'], 'PRODUCT_PRODID' => $value['PRODUCT_PRODIDFR'], 'CTYPE' => $ctype1, 'GLACC' =>'', 'TRANCODE' =>'TDI00', 'SIDE' => 'CR', 'SAVACC' => $value['SAVACC']);       
                            
                            $value['AMOUNT'] = $tdAmt;
                            
                        endif;                        
                       
                        $value['PRODUCT_PRODID']= $tempprodid;
                                    
                        
                        // TIME DESPOSIT
                        if(abs($value['AMOUNT'])>0):
                            
                            if($value['POSTTOSL']==true):
                                Bussiness::covertArrayToXML(array($value), false);
                            endif;
                            //self::$aLines[] = array('AMOUNT' => abs($value['AMOUNT']), 'DESC' =>Common::$lablearray['171'], 'TTYPE' => $value['MODE'] , 'CLIENTIDNO' => $value['CLIENTIDNO'], 'PRODUCT_PRODID' => $value['PRODUCT_PRODIDFR'], 'CTYPE' => $ctype1, 'GLACC' =>'', 'TRANCODE' => ($value['STATUS'] == 'TW' ? 'SD000' : 'SW000'), 'SIDE' =>($value['STATUS'] == 'TW'? 'CR' : 'DR'), 'SAVACC' => $value['SAVACC']);
                        endif;
                        
                        $trancode1 = ($value['STATUS'] == 'TD' ||$value['STATUS'] == 'TR'  ? 'SD000' : 'SW000');
                        $trancode2 = ($value['STATUS'] == 'TD' ||$value['STATUS'] == 'TR'  ? 'SD000' : 'SW000');
                        $trancode3 = ($value['STATUS'] == 'TD' ||$value['STATUS'] == 'TR'  ? 'SD000' : 'SW000');
                       
                        $amount1 = abs($amtExtra);
                        $amount2 = abs($value['AMOUNT']);
                        $amount3 = abs($intAmt);
                        $prodid =  $value['PRODUCT_PRODIDFR'];                   
                        $glacc = '';
                        $mode = $value['MODE'];
                       
                        $desc1 = Common::$lablearray['171'].' '.$value['TDNO'];
                        $desc2 = Common::$lablearray['171'];
                        $desc3 =Common::$lablearray['171'];
                        
                        $accounts_array[] = $value['SAVACC'];
                        $products_array[] = $value['PRODUCT_PRODIDFR'];
                    
                    endif; 
                    
                    break;

                case 'CQ': // cheque
                    
                     if($value['AMOUNT']>0): 
                         
                        $bank_acc = Common::getBankDetails();

                        $value['BID'] = $bank_acc['bankbranches_id'];

                        Common::addKeyValueToArray($value, 'BANKGL', $bank_acc['chartofaccounts_accountcode']);

                        $value['TABLE'] = TABLE_CHEQS;
                        
                        if($value['POSTTOSL']==true):
                            Bussiness::covertArrayToXML(array($value), false);                    
                        endif;
                      //  self::$aLines[] = array('AMOUNT' => abs($value['AMOUNT']), 'DESC' => Common::$lablearray['382'], 'TTYPE' => 'SP', 'PRODUCT_PRODID' => $value['PRODUCT_PRODID'], 'GLACC' => $value['BANKGL'], 'TRANCODE' =>($value['STATUS'] == 'WD' ? 'TW000' : 'TD000'), 'SIDE' =>($value['STATUS'] == 'TW' || $value['STATUS'] == 'TR'? 'CR' : 'DR'), 'DESC' => Common::$lablearray["1203"], 'CTYPE' => $ctype1); // Post Cheque on Suspence
                    endif; 
                    
                    $trancode1 = ($value['STATUS'] == 'TD' ||$value['STATUS'] == 'TR'  ? 'SD000' : 'SW000');
                    $trancode2 = ($value['STATUS'] == 'TD' ||$value['STATUS'] == 'TR'  ? 'SD000' : 'SW000');
                    $trancode3 = ($value['STATUS'] == 'TD' ||$value['STATUS'] == 'TR'  ? 'SD000' : 'SW000');
                       
        
                    $amount1 = abs($amtExtra);
                    $amount2 = abs($value['AMOUNT']);
                    $amount3 = abs($intAmt);
                    $prodid =  $value['PRODUCT_PRODID'];                   
                    $glacc = $value['BANKGL'];
                    $mode = 'SP';
                
                    $desc1 = Common::$lablearray['382'];
                    $desc2 = Common::$lablearray['382'];
                    $desc3 = Common::$lablearray['382'];
                        
                    break;
                    
                default: 
                    break;
                }
                
                if($value['POSTTOGL']==true):
                
                    if($value['STATUS'] =='TR'):
                       if($amtExtra > 0):
                            self::$aLines[] = array('AMOUNT' => $amount1, 'DESC' =>$desc1, 'TTYPE' =>$mode, 'CLIENTIDNO' => $value['CLIENTIDNO'], 'PRODUCT_PRODID' =>$prodid, 'CTYPE' => $ctype1, 'GLACC' => $value['GLACC'], 'TRANCODE' =>$trancode1, 'SIDE' => ($value['STATUS'] == 'TR'? 'CR' : 'DR'), 'SAVACC' => $value['SAVACC']);                             
                       endif;
                    else:                    
                        self::$aLines[] = array('AMOUNT' => $amount2, 'DESC' =>$desc2, 'TTYPE' =>$mode, 'CLIENTIDNO' => $value['CLIENTIDNO'], 'PRODUCT_PRODID' =>$prodid, 'CTYPE' => $ctype1, 'GLACC' => $value['GLACC'], 'TRANCODE' =>$trancode2, 'SIDE' =>($value['STATUS'] == 'TW'? 'CR' : 'DR'), 'SAVACC' => $value['SAVACC']);
                    endif;

                    // INTEREST
                     if($intAmt > 0):
                          self::$aLines[] = array('AMOUNT' => $amount3, 'DESC' =>$desc3, 'TTYPE' =>$mode , 'CLIENTIDNO' => $value['CLIENTIDNO'], 'PRODUCT_PRODID' =>$prodid, 'CTYPE' => $ctype1, 'GLACC' =>$value['GLACC'], 'TRANCODE' =>$trancode3, 'SIDE' =>($value['STATUS'] == 'TW' || $value['STATUS'] == 'TR' ? 'CR' : 'DR'), 'SAVACC' => $value['SAVACC']);                             
                     endif;   
                 
                endif;

            }
            
          
            
            if (Common::$lablearray['E01'] != "") {
                Bussiness::$Conn->cancelTransaction();
                THROW NEW Exception(Common::$lablearray['E01']);
            } else {

                foreach (self::$aLines as $key => $val) {

                    self::$aLines[$key]['DATE'] = $value['DATE'];
                    self::$aLines[$key]['BANKID'] = $value['BANKID'];
                    self::$aLines[$key]['BRANCHCODE'] = $value['BRANCHCODE'];
                    self::$aLines[$key]['TCODE'] = $value['TCODE'];
                    self::$aLines[$key]['FUNDCODE'] = $value['FUNDCODE'];
                    self::$aLines[$key]['DONORCODE'] = $value['DONORCODE'];

                    if ($value['TTYPE'] != "SA"):
                        self::$aLines[$key]['CLIENTIDNO'] = $value['CLIENTIDNO'];
                        self::$aLines[$key]['MODE'] = $value['MODE'];
                    endif;
                 
                    self::$aLines[$key]['TABLE'] = TABLE_GENERALLEDGER;
                }
            }
            
            Common::returnTransactionOptions(self::$aLines, Common::$connObj);
            
            Bussiness::covertArrayToXML(self::$aLines, true);


            // save 
            Bussiness::PrepareData(true);

            if (Common::$lablearray['E01'] != "") {
                 Bussiness::$Conn->cancelTransaction();
                throw new Exception(Common::$lablearray['E01']);                
            }

            Bussiness::$Conn->endTransaction();
            
            if (count($accounts_array) > 0):
                Common::updateSavingsBalance($accounts_array, $products_array, Common::$connObj);
            endif;
                        
        } catch (Exception $e) { 
             Bussiness::$Conn->cancelTransaction();
            Common::$lablearray['E01']=$e->getMessage();
           
        }
        
    }

  
}
?>