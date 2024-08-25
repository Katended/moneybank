<?php
include_once('connectionfactory.php');
require_once('productconfig.php');
require_once('common.php');
require_once('financial_class.php');

Class Shares extends ProductConfig {

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
    public static function getSavingsAccounts($pageparams = '', $theid = '', $cWhere = '') {


        switch ($pageparams):
            case 'INDSAVACC':
            case 'BUSSAVACC':
                if ($theid != ""):
                    $cWhere = " AND c.client_idno='" . $theid . "'";
                endif;
                break;

            case 'GRPSAVACC':                
            case 'MEMSAVACC':
                if ($theid != ""):
                    $cWhere = " AND (c.entity_idno='".$theid. "' OR c.members_idno='".$theid."')";
                endif;
                break;

            default:
                break;
        endswitch;
        
        DataTable::prepareFieldList(array('','savaccounts_id','savaccounts_account','name','product_prodid'));
       
        switch ($pageparams):
            case 'INDSAVACC':
                
                NewGrid::$fieldlist = array("sa.savaccounts_id",'sa.client_idno',"CONCAT(c.client_surname,' ',c.client_firstname,' ',c.client_middlename) name",'sa.savaccounts_account','sa.product_prodid');
                Datatable::$searchable = array('sa.client_idno',"c.client_surname","c.client_firstname","c.client_middlename","sa.savaccounts_account","sa.product_prodid");
                NewGrid::$order = " ORDER BY sa.savaccounts_account DESC,sa.product_prodid";
                $query = " FROM " . TABLE_SAVACCOUNTS . " sa," . TABLE_VCLIENTS . " c "; // . $clienttype;       
                DataTable::$where_condition = " c.client_idno=sa.client_idno  AND client_type='I' ".$cWhere;
                break;

            case 'GRPSAVACC':
                
                NewGrid::$fieldlist = array("sa.savaccounts_id","sa.entity_idno","CONCAT(c.entity_name) name","sa.savaccounts_account","sa.product_prodid");
                Datatable::$searchable = array('sa.entity_idno',"c.entity_name","sa.savaccounts_account","sa.product_prodid");
                NewGrid::$order =" ORDER BY sa.savaccounts_account DESC,sa.product_prodid";
                $query = " FROM " . TABLE_SAVACCOUNTS . " sa," . TABLE_ENTITY . " c " ;
                DataTable::$where_condition = " c.entity_idno=sa.client_idno ".$cWhere;
                break;

            case 'BUSSAVACC':
                
                NewGrid::$fieldlist = array("sa.savaccounts_id","sa.client_idno","client_bussname name","sa.savaccounts_account","sa.product_prodid");
                Datatable::$searchable = array('sa.client_idno',"client_bussname","sa.savaccounts_account","sa.product_prodid");
                NewGrid::$order =" ORDER BY sa.savaccounts_account DESC,sa.product_prodid";
                $query = " FROM " . TABLE_SAVACCOUNTS . " sa," . TABLE_VCLIENTS . " c " . $cWhere;
                DataTable::$where_condition =" c.client_idno=sa.client_idno  AND client_type='B' ".$cWhere;
                break;

            case 'MEMSAVACC':
                
                NewGrid::$fieldlist = array("sa.savaccounts_id","CONCAT(c.members_firstname,' ',c.members_middlename,' ',c.members_lastname) name","sa.client_idno","sa.savaccounts_account","sa.product_prodid","c.members_idno");
                NewGrid::$order =" ORDER BY sa.savaccounts_account DESC,sa.product_prodid,c.members_idno";
                $query = " FROM " . TABLE_SAVACCOUNTS . " sa," . TABLE_MEMBERS . " c   ". $cWhere;
                DataTable::$where_condition =" c.entity_idno=sa.client_idno  ".$cWhere;
                break;

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
    public static function updateShares(&$formdata) {

        self::$aLines = array();

        try {

            Bussiness::$Conn->AutoCommit = false;

            Bussiness::$Conn->beginTransaction();

            Common::getlables("1027,1028,171,1504", "", "", Common::$connObj);
            
            $accounts_array =array();
            $products_array =array();
            foreach ($formdata as $key => $value) {
                
                // used in computation of balances
                Common::deleteElementByValue($value['SAVACC'],$accounts_array);
                Common::deleteElementByValue($value['PRODUCT_PRODID'],$products_array); 
                $accounts_array[] = $value['SAVACC'];
                $products_array[] = $value['PRODUCT_PRODID'];


                if ($value['TTYPE'] == 'SA') { // Open Savings Account
                    $value['TABLE'] = TABLE_SAVTRANSACTIONS;
                    Bussiness::covertArrayToXML(array($value), false);
                }

                // TRANSACTION DESCRIPTIONS
                switch ($value['TTYPE']) {
                    case 'BS':
                        $value['DESC'] = Common::$lablearray['1504'];
                        break;
                    
                    case 'SS':
                        $value['DESC'] = Common::$lablearray['1027'];
                        break;

                    case 'TS':
                        $value['DESC'] = Common::$lablearray['1028'];
                        break;

                    default:
                        break;
                }
                
                $ctype1 = (isset($value['CLIENTIDNO']) ? Common::getClientType($value['CLIENTIDNO']) : Common::getClientType($value['SAVACC']));

                //CHECK SEE IF WE ARE POSTING TO SLs ONLY
                if ($value['POSTTOSL']):
                    $value['TABLE'] = TABLE_SAVTRANSACTIONS;
                    Bussiness::covertArrayToXML(array($value), false);
                endif;

                //CHECK SEE IF WE ARE POSTING TO SLs ONLY
                if ($value['POSTTOGL'] == false):
                    continue;
                endif;

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
   
                        self::$aLines[] = array('AMOUNT' => abs($value['AMOUNT']), 'DESC' => $value['DESC'], 'TTYPE' => 'SD', 'CLIENTIDNO' => $value['CLIENTIDNO'], 'PRODUCT_PRODID' => $value['PRODUCT_PRODID'], 'CTYPE' => $ctype1, 'GLACC' => '', 'TRANCODE' => 'SD000', 'BANKID' => $thevalue['BID'], 'SIDE' => 'CR', 'SAVACC' => $thevalue['SAVACC'], 'BRANCHCODE' => $value['BRANCHCODE']);

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
                self::$aLines[$key]['BANKID'] = $value['BANKID'];
                //  self::$aLines[$key]['BRANCHCODE'] = $value['BRANCHCODE'];
                self::$aLines[$key]['TCODE'] = $value['TCODE'];
                self::$aLines[$key]['FUNDCODE'] = $value['FUNDCODE'];
                self::$aLines[$key]['DONORCODE'] = $value['DONORCODE'];

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