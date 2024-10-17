<?php
include_once('connectionfactory.php');
require_once('productconfig.php');
require_once('common.php');
require_once('financial_class.php');

Class Savings extends ProductConfig {

    Public static $connObj;
    public static $membershipid;
    public static $asatdate;
    public static $savacc;
    Public static $prodid;
    public static $clientidno;
    public static $balance;
    Public static $savaccid;
    public static $bal_array = array();
    Public static $aLines = null;
    private static $_instance = null;

    public static function getInstance() {

        if (self::$_instance === null) {
            self::$_instance = new self;
        }

       // return self::$_instance;
    }

    /**
     * setProps
     * Sets properties for savings account details based on the provided balance array.
     *
     * @param array self::bal_array        Array containing savings account details
     */
    public static function setProps()
    {
        self::$savacc = self::$bal_array['savaccounts_account'] ?? '';
        self::$prodid = self::$bal_array['product_prodid'] ?? '';
        self::$clientidno = self::$bal_array['client_idno'] ?? '';
        self::$membershipid = self::$bal_array['membership_id'] ?? '';
        self::$asatdate = self::$asatdate;
        self::$balance = self::$bal_array['balance'] ?? 0;
    }

    /**
     * updateRenameKeys
     * 
     * This function is used to update keys
     * @param array $formdata: Data from the form    
     */
    public static function updateRenameKeys(&$formdata, $ctype = '')
    {

        $replacements = [
            'txtOpenDate' => 'DATE',
            'txtvoucher' => 'VOUCHER',
            'txtamount' => 'AMOUNT',
            'product_prodid' => 'PRODUCT_PRODID',
            'PAYMODES' => 'MODE',
            'CMBFREQUENCY' => 'FREQ',
            'txtrepaysavtamount' => 'RSAMOUNT',
            'LOANPROD' => 'LPRODID',
            'client_idno' => 'CLIENTIDNO',
            'branch_code' => 'BRANCHCODE',
            'cashaccounts_code' => 'GLACC',
            'cheques_no' => 'CHEQNO',
            'bankbranches_id' => 'BACCNO',

        ];

        $dateFields = ['DATE'];

        foreach ($formdata as $key => $value) {

            if (isset($replacements[$key])):
                $formdata[$replacements[$key]] = $value;

                if (in_array($replacements[$key], $dateFields)) {
                    $formdata[$replacements[$key]] = !empty($formdata[$replacements[$key]])
                        ? Common::changeDateFromPageToMySQLFormat($value)
                        : '';
                }
                unset($formdata[$key]);

            endif;
        }

        return $formdata;
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
            return 1;
        endif;
    }

    public static function getSavingsDeleteAccount($savingsAccountID = '')
    {
        Common::$connObj->SQLDelete(TABLE_SAVACCOUNTS, "savaccounts_id", $savingsAccountID);
    }

    /**
     * getSavingsAccounts
     * 
     * This function is used to get Savings Accounts 
     * @$pageparams string 
     */
    public static function getSavingsAccountById()
    {
        $result = Common::$connObj->SQLSelect("SELECT savaccounts_account,product_prodid,client_idno FROM " . TABLE_SAVACCOUNTS . " WHERE savaccounts_id = '" . self::$savaccid . "'", true, true);

        self::$savacc = $result['savaccounts_account'];
        self::$prodid = $result['product_prodid'];
        self::$clientidno = $result['client_idno'];
    }

    
    /**
     * getSavingsAccounts
     * 
     * This function is used to get Savings Accounts 
     * @$pageparams string 
     */
    public static function getSavingsAccountsQuery($pageparams = '', $theid = '', $cWhere = '')
    {

        try {

            if ($theid != "") {
                switch ($pageparams) {
                    case 'INDSAVACC':
                    case 'GRPSAVACC':
                    case 'BUSSAVACC':
                        $cWhere = " AND c.client_idno='" . $theid . "'";
                        break;

                    case 'MEMSAVACC':
                        $cWhere = " AND (c.entity_idno='" . $theid . "' )";
                        break;

                    default:
                        break;
                }
            }

        $query = '';

        switch ($pageparams) {
            case 'INDSAVACC':            
            case 'BUSSAVACC':
                case 'GRPSAVACC':
                

                $query =  TABLE_SAVACCOUNTS . " sa 
                          JOIN " . TABLE_VCLIENTS . " c ON sa.client_idno = c.client_idno" . $cWhere;
                break;

                case 'MEMSAVACC':
                $query =  TABLE_SAVACCOUNTS . " sa 
                          JOIN " . TABLE_MEMBERS . " c ON sa.client_idno = c.entity_idno" . $cWhere;
                break;
    
            default:
                break;
        }
    
        return $query;

        } catch (Exception $e) {
            Common::$lablearray['E01'] = $e->getMessage();
            return Common::createResponse('err', $e->getMessage());
        }
    }


    /**
     * getSavingsAccounts
     * 
     * This function is used to get Savings Accounts 
     * @$pageparams string 
     */
    public static function getClientDetails($pageparams = '', $theid = '', $cWhere = '') {

        try {
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
        } catch (Exception $e) {
            Common::$lablearray['E01'] = $e->getMessage();
            return Common::createResponse('err', $e->getMessage());
        }
    }

    /*     
     * getSavingsTransactionsById
     * This function is used to get Savings Transactions of a Savings Account
     * Returns String
    */
    public static function getSavingsTransactionsById()
    {

        return sprintf(
            "(
                SELECT
                    savaccounts_account,
                    product_prodid
                FROM
                    savaccounts
                WHERE
                    savaccounts_id = '%s'
            ) AS s,
            savtransactions t
            WHERE
                t.savaccounts_account = s.savaccounts_account
                AND t.product_prodid = s.product_prodid",
            self::$savaccid
        );
    }

    /*     
     * getSavingsBalance
     * This function is used to get Savings Balance of a Savings Account
     * @param string $prodid : Savings Product
     * @param string $acc  : Savings Account
     * @param date $ddate  : As at
     * Returns array
    */
    /**
     * getSavingsBalance
     * Retrieves the savings balance of a savings account.
     *
     * @return bool        Returns true if balance is non-negative, false otherwise
     */
    public static function getSavingsBalance()
    {
        try {
            $parameters = [];

            Common::prepareParameters($parameters, 'asat', self::$asatdate);
            Common::prepareParameters($parameters, 'productid', self::$prodid);
            Common::prepareParameters($parameters, 'accountid', self::$savacc);
            Common::prepareParameters($parameters, 'memid', self::$membershipid);
            Common::prepareParameters($parameters, 'code', 'SAVBALS');

            self::$bal_array = Common::common_sp_call(serialize($parameters), '', Common::$connObj, false);

            self::setProps();
            
        } catch (Exception $e) {
            Common::$lablearray['E01'] = $e->getMessage();
            return Common::createResponse('err', $e->getMessage());
        }
    }


    /*     
     * getGroupSavingsBalances
     * This function is used to get Savings Balance for Group members 
     * 
    */
    public static function getGroupMemberSavingsBalances()
    {

        try {

            // check savings balance
            $parameters = array();

            self::$bal_array = array();

            Common::prepareParameters(
                $parameters,
                'asat',
                self::$asatdate
            );
            Common::prepareParameters($parameters, 'account', self::$savacc);
            Common::prepareParameters($parameters, 'productid', self::$prodid);
            Common::prepareParameters($parameters, 'code', 'GROUPMEMBERSAVBALS');

            self::$bal_array = Common::common_sp_call(serialize($parameters), '', Common::$connObj, false);  
            
        } catch (Exception $e) {
            Common::$lablearray['E01'] = $e->getMessage();
            return Common::createResponse('err', $e->getMessage());
        }
    }

    /*     
     * getCurrentBalances
     * This function is used to get Savings Balance for Group members 
     * 
    */
    public static function getSumBalances()
    {

        try {
            
            $total = 0;

            $i = 0;

            foreach (self::$bal_array as $item) {
                $total += $item['balance'];
                $i++;
            }

            self::$balance = $total;

        } catch (Exception $e) {
            Common::$lablearray['E01'] = $e->getMessage();
        }
    }


    /**
     * updateSavings
     * 
     * This function is used to update savings transactions
     * @param array $formdata: Data from the form
     * Returns array
     */
    public static function updateSavings(&$formdata)
    {


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
                if ($value['AMOUNT'] > 0) {
                    $value['TABLE'] = TABLE_SAVTRANSACTIONS;
                    Bussiness::covertArrayToXML(array($value), false);
                } else {
                    continue;
                }
                   
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