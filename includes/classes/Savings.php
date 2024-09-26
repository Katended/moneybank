<?php
include_once('connectionfactory.php');
require_once('productconfig.php');
require_once('common.php');
require_once('financial_class.php');

Class Savings extends ProductConfig {

    Public static $connObj;
    Public static $membershipid;
    Public static $asatdate;
    Public static $savacc;
    Public static $prodid;
    Public static $savaccid;
    public static $bal_array = array();
    Public static $aLines = null;

    public static function getInstance() {

        if (self::$_instance === null) {
            self::$_instance = new self;
        }

        return self::$_instance;
    }

    public static function setProps($savacc, $prodid, $membershipid, $asatdate) {

        self::$savacc = $savacc;
        self::$prodid = $prodid;
        self::$membershipid = $membershipid;
        self::$asatdate = $asatdate;
    }

    /**
     * getSavingsAccounts
     * 
     * This function is used to get Savings Accounts 
     * @$pageparams string 
     */
    public static function getSavingsTransactions($savingsAccountID = '')
    {
        $acc_array = Common::$connObj->SQLSelect("SELECT savaccounts_account FROM " . TABLE_SAVTRANSACTIONS . " WHERE  savaccounts_account='" . $savingsAccountID . "' LIMIT 1");

        if (isset($acc_array[0][1])):
            return 0;
        else:
            return $acc_array['count'];
        endif;
    }

    public static function getSavingsDeleteAccount($savingsAccountID = '')
    {
        Common::$connObj->SQLDelete(TABLE_SAVACCOUNTS, "savaccounts_account", $savingsAccountID);
    }


    /**
     * getSavingsAccounts
     * 
     * This function is used to get Savings Accounts 
     * @$pageparams string 
     */
    public static function getSavingsAccounts($pageparams = '', $theid = '', $cWhere = '') {
        // Construct the WHERE clause based on pageparams and theid
        if ($theid != "") {
            switch ($pageparams) {
                case 'INDSAVACC':
                case 'BUSSAVACC':
                    $cWhere = " AND c.client_idno='" . $theid . "'";
                    break;

                case 'GRPSAVACC':
                case 'MEMSAVACC':
                    $cWhere = " AND (c.entity_idno='" . $theid . "' OR c.members_idno='" . $theid . "')";
                    break;

                default:
                    break;
            }
        }

        // Construct the query based on pageparams
        $query = '';
        switch ($pageparams) {
            case 'INDSAVACC':
            case 'GRPSAVACC':
            case 'BUSSAVACC':

                $query = " FROM " . TABLE_SAVACCOUNTS . " sa 
                          JOIN " . TABLE_VCLIENTS . " c ON sa.client_idno = c.client_idno";
                break;
    
            case 'MEMSAVACC':
                $query = " FROM " . TABLE_SAVACCOUNTS . " sa 
                          JOIN " . TABLE_MEMBERS . " c ON sa.client_idno = c.client_idno" . $cWhere;
                break;
    
            default:
                break;
        }
    
        return $query;
    }

    /**
     * getSavingsAccounts
     * 
     * This function is used to get Savings Accounts 
     * @$pageparams string 
     */
    public static function getClientDetails($pageparams = '', $theid = '', $cWhere = '') {


        switch ($pageparams):
            case 'IND':
            case 'BUSS':
                if ($theid != ""):
                    $cWhere = " AND c.client_idno='" . $theid . "'";
                endif;
                break;

            // case 'GRP':                
            // case 'MEM':
            //     if ($theid != ""):
            //         $cWhere = " AND (c.entity_idno='".$theid. "' OR c.members_idno='".$theid."')";
            //     endif;
            //     break;

            default:
                break;
        endswitch;
        
        DataTable::prepareFieldList(array('','','client_idno', 'name','client_regdate'));
       
        switch ($pageparams):
            case 'IND':
                
                NewGrid::$fieldlist = array("c.client_idno","CONCAT(c.client_surname,' ',c.client_firstname,' ',c.client_middlename) name","c.client_regdate");
                Datatable::$searchable = array("c.client_idno","c.client_surname","c.client_firstname","c.client_middlename","c.client_regdate");
                NewGrid::$order = " ORDER BY c.client_idno";
                NewGrid::$keyfield = "c.client_idno";
                $query = " FROM " . TABLE_VCLIENTS . " c "; // . $clienttype;       
                DataTable::$where_condition = "  c.client_type='I' ".$cWhere;
                break;
            // TODO
            // case 'GRPS':
                
            //     NewGrid::$fieldlist = array("sa.savaccounts_id","sa.entity_idno","CONCAT(c.entity_name) name","sa.savaccounts_account","sa.product_prodid");
            //     Datatable::$searchable = array('sa.entity_idno',"c.entity_name","sa.savaccounts_account","sa.product_prodid");
            //     NewGrid::$order =" ORDER BY sa.savaccounts_account DESC,sa.product_prodid";
            //     $query = " FROM " . TABLE_SAVACCOUNTS . " sa," . TABLE_ENTITY . " c " ;
            //     DataTable::$where_condition = " c.entity_idno=sa.client_idno ".$cWhere;
            //     break;

            // case 'BUSS':
                
            //     NewGrid::$fieldlist = array("sa.savaccounts_id","sa.client_idno","client_bussname name","sa.savaccounts_account","sa.product_prodid");
            //     Datatable::$searchable = array('sa.client_idno',"client_bussname","sa.savaccounts_account","sa.product_prodid");
            //     NewGrid::$order =" ORDER BY sa.savaccounts_account DESC,sa.product_prodid";
            //     $query = " FROM " . TABLE_SAVACCOUNTS . " sa," . TABLE_VCLIENTS . " c " . $cWhere;
            //     DataTable::$where_condition =" c.client_idno=sa.client_idno  AND client_type='B' ".$cWhere;
            //     break;

            // case 'MEMS':
                
            //     NewGrid::$fieldlist = array("sa.savaccounts_id","CONCAT(c.members_firstname,' ',c.members_middlename,' ',c.members_lastname) name","sa.client_idno","sa.savaccounts_account","sa.product_prodid","c.members_idno");
            //     NewGrid::$order =" ORDER BY sa.savaccounts_account DESC,sa.product_prodid,c.members_idno";
            //     $query = " FROM " . TABLE_SAVACCOUNTS . " sa," . TABLE_MEMBERS . " c   ". $cWhere;
            //     DataTable::$where_condition =" c.entity_idno=sa.client_idno  ".$cWhere;
            //     break;

            default:
                break;
        endswitch;

        return $query;
    }


    /**
     * getSavingsBalance
     * 
     * This function is used to get Savings Balance of a Savings Account
     * @param string $prodid :Savings Product
     * @param string $acc  :Savings Account
     * @param date $ddate  : As at
     * Returns array
     */
    // public static function getSavingsBalance($prodid = '', $savacc = '', $ddate = '',$memid) {
    public static function getSavingsBalance($amount = 0) {

        // check savings balance
        $parameters = array();
        Common::prepareParameters($parameters, 'asat', self::$asatdate);

        if (self::$savaccid != ""):

            Common::prepareParameters($parameters, 'savaccountsid', self::$savaccid);
            Common::prepareParameters($parameters, 'code', 'SAVBALSBYID');
            self::$bal_array = Common::common_sp_call(serialize($parameters), '', Common::$connObj, false);

            $balarray = self::$bal_array[0];
            reset($balarray);
            $balance = array_sum(array_column($balarray, 'balance'));
            
            if($balance==0):
                $balance   = $balarray['balance'];
            endif;

        //  $balance = Common::sum_array('members_idno', self::$membershipid, 'balance', self::$bal_array[1]);

        else:

            Common::prepareParameters($parameters, 'productid', self::$prodid);
            Common::prepareParameters($parameters, 'account', self::$savacc);
            Common::prepareParameters($parameters, 'memid', self::$membershipid);
            Common::prepareParameters($parameters, 'asat', self::$asatdate);
            Common::prepareParameters($parameters, 'code', 'SAVBALS');           
            
            
            self::$bal_array = Common::common_sp_call(serialize($parameters), '', Common::$connObj, true);
            $balance = self::$bal_array['balance'];

        endif;


        $balance = bcsub($balance, abs($amount), SETTING_ROUNDING);

        if ($balance < 0):
            return false;
        else:
            return true;
        endif;
    }

    /**

      /**
     * updateSavings
     * 
     * This function is used to update savings transactions
     * @param array $formdata: Data from the form
     * Returns array
     */
    public static function updateSavings(&$formdata) {


        try {

            self::$aLines = array();
            $accounts_array = array();
            $products_array = array();

            Bussiness::$Conn->setAutoCommit();

            Bussiness::$Conn->beginTransaction();

            Common::getlables("1027,1028,171,1504", "", "", Common::$connObj);
            
      
            foreach ($formdata as $key => $value) {

                $value['MEMID'] =$value['MEMID']??'';
                
                // used in computation of balances
                Common::deleteElementByValue($value['SAVACC'],$accounts_array);
                Common::deleteElementByValue($value['PRODUCT_PRODID'], $products_array);
                 
                $accounts_array[] = $value['SAVACC'];
                $products_array[] = $value['PRODUCT_PRODID'];


                if ($value['TTYPE'] == 'OSA') { // Open Savings Account
                    $value['TABLE'] = TABLE_SAVACCOUNTS;
                    Bussiness::covertArrayToXML(array($value), false);


                    if ($value['RSAMOUNT'] > 0) :

                        $value['TABLE'] = TABLE_CLIENTSAVE;
                        $amount = $value['AMOUNT'];
                        $value['AMOUNT'] = $value['RSAMOUNT'];
                        Bussiness::covertArrayToXML(array($value), false);

                        $value['AMOUNT'] = $amount;

                    endif;

                    if ($value['AMOUNT'] > 0):
                        $value['TTYPE'] = 'SD';
                    endif;
                }



                // TRANSACTION DESCRIPTIONS
                switch ($value['TTYPE']) {
                    case 'SC':
                        $value['DESC'] = Common::$lablearray['1504'] . ' ' . $value['SAVACC'] . ' ' . $value['MEMID'];
                        break;
                    case 'SD':
                        $value['DESC'] = Common::$lablearray['1027'] . ' ' . $value['SAVACC'] . ' ' . $value['MEMID'];
                        break;

                    case 'SW':
                        $value['DESC'] = Common::$lablearray['1028'] . ' ' . $value['SAVACC'] . ' ' . $value['MEMID'];
                        break;

                    case 'SA':
                    case 'IT':
                        $value['DESC'] = Common::$lablearray['171'] . ' ' . $value['SAVACC'] . ' ' . $value['MEMID'];
                        break;

                    default:
                        break;
                }
                
                $ctype1 = (isset($value['CLIENTIDNO']) ? Common::getClientType($value['CLIENTIDNO']) : Common::getClientType($value['SAVACC']));

                //CHECK SEE IF WE ARE POSTING TO SLs ONLY
               // if ($value['POSTTOSL']):
                    $value['TABLE'] = TABLE_SAVTRANSACTIONS;
                    Bussiness::covertArrayToXML(array($value), false);
               // endif;
            // TEMPORARY DISABLING THIS 21-MAR-2024
            // DO NOT SEE ITS VALUE IN THE MOMOMENT
            // CHECK SEE IF WE ARE POSTING TO SLs ONLY
              //  if ($value['POSTTOGL'] == false):
               //     continue;
              //  endif;

                switch ($value['TTYPE']) {

                    case 'SD': // Deposit                    
                    case 'SW': // Withdraw
                    case 'SC': // Service fee                        

                        if ($value['TTYPE'] == 'SC'):
                            self::$aLines[] = array('AMOUNT' => abs($value['AMOUNT']), 'DESC' => $value['DESC'], 'TTYPE' => $value['TTYPE'], 'CLIENTIDNO' => $value['CLIENTIDNO'], 'PRODUCT_PRODID' => $value['PRODUCT_PRODID'], 'CTYPE' => $ctype1, 'GLACC' => '', 'TRANCODE' => 'SF000', 'SIDE' => 'DR', 'SAVACC' => $value['SAVACC'], 'BRANCHCODE' => $value['BRANCHCODE']);
                        else:
                            self::$aLines[] = array('AMOUNT' => abs($value['AMOUNT']), 'DESC' => $value['DESC'], 'TTYPE' => $value['TTYPE'], 'CLIENTIDNO' => $value['CLIENTIDNO'], 'PRODUCT_PRODID' => $value['PRODUCT_PRODID'], 'CTYPE' => $ctype1, 'GLACC' => '', 'TRANCODE' => ($value['TTYPE'] == 'SW' ? 'SW000' : 'SF000'), 'SIDE' => ($value['TTYPE'] == 'SD' ? 'CR' : 'DR'), 'SAVACC' => $value['SAVACC'], 'BRANCHCODE' => $value['BRANCHCODE']);
                        endif;

                        break;

                    case 'SA':
                    case 'IT':

                        // Bussiness::covertArrayToXML(array($value), false);
//                        $tep_prod = $value['PRODUCT_PRODID'];
//                        $tep_savacc = $value['SAVACC'];
//                        $tep_amt = $value['AMOUNT'];
//
//                        $accounts_to_array = $value['ACCOUNTSTO'];
//
//                        $nCount = count($accounts_to_array);
//
//                        if ($nCount == 0 && $value['SAVACCTO'] != ''):
//                            $accounts_to_array[] = array('SAVACCTO' => $value['SAVACCTO'], 'PRODUCT_PRODIDTO' => $value['PRODUCT_PRODIDTO'], 'MEMIDTO' => $value['MEMIDTO'], 'AMOUNTTO' => $value['AMOUNT']);
//                        endif;
//                        foreach ($accounts_to_array as $thekey => $thevalue) {
//
//                            $acc_array = array();
//                            
//                            if($thevalue['CLIENTIDNO']==""):
//                                $acc_array = Common::$connObj->SQLSelect("SELECT client_idno FROM " . TABLE_SAVACCOUNTS . " WHERE  savaccounts_account='" . $thevalue['SAVACCTO'] . "' AND product_prodid='" . $value['PRODUCT_PRODIDTO'] . "' GROUP BY client_idno");
//                                $ctype = Common::getClientType($acc_array[0]['client_idno']);
//                            else:
//                                $ctype = Common::getClientType($thevalue['CLIENTIDNO']);
//                            endif;
//                          
//
                        //    $accounts_array[] = $value['SAVACC'];
//
//                            $products_array[] = $thevalue['PRODUCT_PRODID'];
                        //$value['SAVACC'] = $thevalue['SAVACCTO'];
                        // $value['PRODUCT_PRODID'] = $value['PRODUCT_PRODIDTO'];
                        // $value['AMOUNT'] = abs($thevalue['AMOUNTTO']);
//                            if ($nCount == 1) {
//                                Bussiness::covertArrayToXML(array($value), false);
//                            } else {
                        //  Bussiness::covertArrayToXML(array($value), false);
                        // }

                        self::$aLines[] = array('AMOUNT' => abs($value['AMOUNT']), 'DESC' => $value['DESC'], 'TTYPE' => 'SD', 'CLIENTIDNO' => $value['CLIENTIDNO'], 'PRODUCT_PRODID' => $value['PRODUCT_PRODID'], 'CTYPE' => $ctype1, 'GLACC' => '', 'TRANCODE' => 'SD000', 'BANKID' => $thevalue['BID'], 'SIDE' => 'CR', 'SAVACC' => $thevalue['SAVACC'], 'BRANCHCODE' => $value['BRANCHCODE']);

                        // $nCount--;
                        // }
                        //$value['PRODUCT_PRODID'] = $tep_prod;
                        //$value['SAVACC'] = $tep_savacc;
                        // $value['AMOUNT'] = $tep_amt;

                        break;

                    default:
                        break;
                }

                switch ($value['MODE']) {
                    case 'SA':
                    case 'SAV':
                        self::$aLines[] = array('AMOUNT' => abs($value['AMOUNT']), 'DESC' => $value['DESC'], 'TTYPE' => $value['MODE'], 'CLIENTIDNO' => $value['CLIENTIDNO'], 'PRODUCT_PRODID' => $value['PRODUCT_PRODID'], 'CTYPE' => $ctype1, 'GLACC' => '', 'TRANCODE' => 'SD000', 'SIDE' => 'DR', 'SAVACC' => $value['SAVACC'], 'BRANCHCODE' => $value['BRANCHCODE']);
                        break;

                    case 'CA':
                        self::$aLines[] = array('AMOUNT' => abs($value['AMOUNT']), 'DESC' => $value['DESC'], 'TTYPE' => $value['MODE'], 'CLIENTIDNO' => $value['CLIENTIDNO'], 'PRODUCT_PRODID' => $value['PRODUCT_PRODID'], 'CTYPE' => $ctype1, 'GLACC' => $value['GLACC'], 'TRANCODE' => ($value['TTYPE'] == 'SW' ? 'SW000' : 'SD000'), 'SIDE' => ($value['TTYPE'] == 'SD' ? 'DR' : 'CR'), 'SAVACC' => $value['SAVACC'], 'BRANCHCODE' => $value['BRANCHCODE']);
                        break;

                    case 'CQ':
                    case 'DB': // Direct To Bank  

                        if ($value['MODE'] == 'DB'):
                            Common::addKeyValueToArray($value, 'cheques_no', 'CHEQNO');
                            $value['CHEQNO'] = 'DB-' . $value['TCODE'];
                        endif;

                        $bank_acc = Common::getBankDetails();

                        $value['BID'] = $bank_acc['bankbranches_id'];
                        Common::addKeyValueToArray($value, 'BANKGL', $bank_acc['chartofaccounts_accountcode']);
                        switch ($value['TTYPE']) {
                            case 'SD':
                                $side = 'DR';
                                break;

                            case 'SW':
                            case 'SA':
                                $side = 'CR';
                                break;

                            default:
                                break;
                        }

                        $value['TABLE'] = TABLE_CHEQS;

                        Bussiness::covertArrayToXML(array($value), false);

                        self::$aLines[] = array('AMOUNT' => abs($value['AMOUNT']), 'DESC' => $value['DESC'], 'TTYPE' => 'SP', 'PRODUCT_PRODID' => $value['PRODUCT_PRODID'], 'GLACC' => $value['BANKGL'], 'TRANCODE' => 'CC002', 'SIDE' => $side, 'DESC' => Common::$lablearray["1203"], 'CTYPE' => $ctype1, 'BRANCHCODE' => $value['BRANCHCODE']); // Post Cheque on Suspence
                        break;

                    default:
                        break;
                }

                if (Common::$lablearray['E01'] != "") {
                    Bussiness::$Conn->cancelTransaction();
                    throw new Exception(Common::$lablearray['E01']);
                }
            }


            foreach (self::$aLines as $key => $val) {

                self::$aLines[$key]['DATE'] = $value['DATE'];
                self::$aLines[$key]['BANKID'] = $value['BANKID']??'';
                //  self::$aLines[$key]['BRANCHCODE'] = $value['BRANCHCODE'];
                self::$aLines[$key]['TCODE'] = $value['TCODE'];
                self::$aLines[$key]['FUNDCODE'] = $value['FUNDCODE']??'';
                self::$aLines[$key]['DONORCODE'] = $value['DONORCODE']??'';

                if ($value['TTYPE'] != "SA"):
                    self::$aLines[$key]['CLIENTIDNO'] = $value['CLIENTIDNO'];
                    self::$aLines[$key]['MODE'] = $value['MODE'];
                endif;

                self::$aLines[$key]['TABLE'] = TABLE_GENERALLEDGER;
            }

            Common::returnTransactionOptions(self::$aLines, Common::$connObj);

            if (Common::$lablearray['E01'] != "") {
                throw new Exception(Common::$lablearray['E01']);
            }

            Bussiness::covertArrayToXML(self::$aLines, true);

            // save 
            Bussiness::PrepareData(true);

            
            // THIS IS DONE AT THE DATABASE LEVEL 
            // IN SPs sp_update_savings_balances and 

//            if (count($accounts_array) > 0):
//                Common::updateSavingsBalance($accounts_array, $products_array, Common::$connObj);
//            endif;
            
        } catch (Exception $e) {
            
            Bussiness::$Conn->cancelTransaction();
            Common::$lablearray['E01'] = $e->getMessage();
        }
    }

}?>