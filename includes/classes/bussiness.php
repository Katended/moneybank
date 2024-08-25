<?php
class Bussiness {

    Public static $Conn;
    private static $instance;
    private static $data;
    public static $sSQL;
    public static $sqlValues;
    public static $aFieldList;
    public static $keyValues;
    public static $isBulkInsert;
    static $xml;
    Static $xmlObj;

    private function __construct() {
        
    }

    //private function __clone() {}

    public static function getInstance(&$Conn) {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }

        unset(self::$atables);

        unset(self::$avalues);

        self::$Conn = $Conn;
        return self::$instance;
    }

    //THIS FUNCTION IS USED TO PREPARE A BULK INSERT STATEMENT
    public static function prepareBulkStatement($myarraydata, $isDone = false) {


        try {


            foreach ($myarraydata as $key => $value) {

                if (!isset(self::$aFieldList[$value['TABLE']])):
                    self::$aFieldList[$value['TABLE']] = self::$Conn->preparefieldList($value['TABLE'], true);
                endif;

                switch ($value['TABLE']) {
                   
                           
                    case TABLE_DEVICEMESSAGE:

//                        FIELDS
//                        ********************************* 
//                        devicemessage_id
//                        devicemessage_date
//                        device_id
//                        devicemessage_msg
//                        devicemessage_status
//                        clientid
//                        tel
//                        loan_number
//                        devicemessage_response
//                        ********************************* 
                        unset(self::$aFieldList[$value['TABLE']]['clientid'], self::$aFieldList[$value['TABLE']]['tel'], self::$aFieldList[$value['TABLE']]['loan_number'], self::$aFieldList[$value['TABLE']]['devicemessage_response']);

                        self::$sqlValues[$value['TABLE']][] = "('"
                                . $value['DMID']
                                . "',NOW()"
                                . ",NULL"
                                . ", '" . $value['MSG']
                                . "', '" . $value['STAT']
                                . "')";


                        break;
                     case TABLE_CURRENCYDENO:

//                        FIELDS
//                        ********************************* 
//                       currencies_id
//                       currencydeno_deno
//                        ********************************* 
                        unset(self::$aFieldList[$value['TABLE']]['currencydeno_id']);

                        self::$sqlValues[$value['TABLE']][] = "('"
                                . $value['CURRID']
                                . "', '" . $value['DEN']
                                . "')";
              
                        break;
                    
                    case TABLE_MODEM:

//                        FIELDS
//                        ********************************* 
//                        modem_id
//                        modem_name
//                        modem_bitrate
//                        modem_port
//                        ********************************* 
                        unset(self::$aFieldList[$value['TABLE']]['modem_id']);

                        self::$sqlValues[$value['TABLE']][] = "('"
                                . $value['MID']
                                . ", '" . $value['DNAME']
                                . "', '" . $value['BPS']
                                . "', '" . $value['PORT']
                                . "')";

//                        
                        break;

                    case TABLE_CHARTOFACCOUNTS:

//                        $record->addAttribute('name', $value['NAME']);
//                        $record->addAttribute('level', $value['LEVEL']);
//                        $record->addAttribute('parent', $value['PARENT']);
//                        $record->addAttribute('header', $value['HEADER']);
//                        $record->addAttribute('glacc', $value['GLACC']);
//                        $record->addAttribute('tgrp', $value['TGRP']);
//                        $record->addAttribute('gcode', $value['GCODE']);
//                        $record->addAttribute('curid', $value['CURRENCIES_ID']);
//                        $record->addAttribute('rval', $value['RVAL']);
//                        $record->addAttribute('bitem', $value['BITEM']);
//                        $record->addAttribute('desc', $value['DESC']);
//                        $record->addAttribute('action', $value['ACTION']);

                        break;

                    case TABLE_ROLESCASHACCOUNTS:

//                        $record->addAttribute('roleid', $value['ROLEID']);
//                        $record->addAttribute('glacc', $value['GLACC']);
                        break;

                    case TABLE_ROLESMODULES:

//                        $record->addAttribute('roleid', $value['ROLEID']);
//                        $record->addAttribute('mid', $value['MID']);
                        break;

                    case TABLE_USERROLES:

//                        $record->addAttribute('roleid', $value['ROLEID']);
//                        $record->addAttribute('action', $value['ACTION']);
//                        $record->addAttribute('uid', $value['UID']);
                        break;

                    case TABLE_ROLES:

//                        $record->addAttribute('role', $value['ROLE']);
//                        $record->addAttribute('roleid', $value['ROLEID']);
//                        $record->addAttribute('action', $value['ACTION']);
//                        $record->addAttribute('lang', $value['LANG']);
//                        break;

                    case TABLE_USERBRANCHES:

//                        $record->addAttribute('ucode', $value['UCODE']);
//                        $record->addAttribute('brcode', $value['BRANCHCODE']);
//                        $record->addAttribute('lic', $value['LIC']);
//                        $record->addAttribute('acode', $value['ACODE']);
//                        $record->addAttribute('pbrcode', $value['PBRANCHCODE']);
//                        $record->addAttribute('action', $value['ACTION']);
                        break;

                    case TABLE_USERS:

//                        $record->addAttribute('fname', $value['FNAME']);
//                        $record->addAttribute('lname', $value['LNAME']);
//                        $record->addAttribute('mname', $value['MNAME']);
//                        $record->addAttribute('uname', $value['UNAME']);
//                        $record->addAttribute('pwd', $value['PWD']);
//                        $record->addAttribute('email', $value['EMAIL']);
//                        $record->addAttribute('lang', $value['LANG']);
//                        $record->addAttribute('acode', $value['ACODE']);
//                        $record->addAttribute('ucode', $value['UCODE']);
//                        $record->addAttribute('active', $value['ACTIVE']);
//                        $record->addAttribute('action', $value['ACTION']);
//                        $record->addAttribute('uid', $value['UID']);
//                        $record->addAttribute('exp', $value['EXP']);
                        break;

                    case TABLE_CLIENTSAVE:

//                        FIELDS
//                        *********************************  
//                        client_idno
//                        savaccounts_account
//                        product_prodid
//                        groupmembership_id
//                        clientsave_amount
//                        last_updatedate
//                        *********************************

                        self::$sqlValues[$value['TABLE']][] = "('"
                                . $value['CLIENTIDNO']
                                . "', '" . $value['SAVACC']
                                . "', '" . $value['PRODUCT_PRODID']
                                . "', '" . $value['MEMID']??''
                                . "', '" . $value['AMOUNT']
                                . "',NOW())";


                        break;

                    case TABLE_SAVACCOUNTS:

//                        FIELDS
//                        *********************************                      
//                        client_idno
//                        savaccounts_account
//                        product_prodid
//                        savaccounts_opendate
//                        savaccounts_closedate
//                        savaccounts_id
//                        groupmembership_id
//                        *********************************  
                        unset(self::$aFieldList[$value['TABLE']]['savaccounts_closedate'], self::$aFieldList[$value['TABLE']]['savaccounts_id'], self::$aFieldList[$value['TABLE']]['groupmembership_id']);

                        self::$sqlValues[$value['TABLE']][] = "('"
                                . $value['CLIENTIDNO']
                                . "', '" . $value['SAVACC']
                                . "', '" . $value['PRODUCT_PRODID']
                                . "', '" . $value['DATE']
                                . "')";


                        break;

                    case TABLE_REFINANCED:

//                        FIELDS
//                        *********************************
//                        loan_number
//                        refinanced_startdate
//                        refinanced_originalamt
//                        refinanced_addedamt
//                        loan_noofinst
//                        loan_tint
//                        user_id
//                        refinanced_datecreated
//                        refinanced_bal
//                        *********************************
                        self::$sqlValues[$value['TABLE']][] = "('"
                                . $value['LNR']
                                . "', '" . $value['DATE']
                                . "', '" . $value['LAMNT']
                                . "', '" . $value['TOPUP']
                                . "', '" . $value['NOINS']
                                . "', '" . $value['INTRATE']
                                . "', '" . $value['USERID']
                                . "', NOW()"
                                . ", '" . $value['PRI']
                                . "')";


                        break;

                    case TABLE_DISBURSEMENTS:

//                        FIELDS
//                        *********************************
//                        transactioncode
//                        loan_number
//                        disbursements_date
//                        disbursements_vat
//                        disbursements_voucher
//                        disbursements_stationery
//                        disbursements_amount
//                        disbursements_commission
//                        cheqs_no
//                        cash
//                        cycle
//                        groupmembership_id
//                        disbursements_type
//                        paymode
//                        user_id
//                        *********************************
                        self::$sqlValues[$value['TABLE']][] = "('"
                                . $value['LNR']
                                . "', '" . $value['DATE']
                                ."', '" .  $value['TCODE']                           
                                . "', '" . $value['VAT']
                                . "', '" . $value['VOUCHER']
                                . "', '" . $value['STAT']
                                . "', '" . $value['LAMNT']
                                . "', '" . $value['COMM']
                                . "', '" . ($value['CHEQNO']??'')
                                . "', '" . ($value['MODE']??'')
                                . "', '" . $value['CYCLE']
                                . "', '" . ($value['MEMID']??'')
                                . "', '" . ($value['DTYPE']??'')
                                . "', '" . ($value['MODE']??'')
                                . "', '" . $_SESSION['user_id']
                                . "')";

                        break;

                    case TABLE_DUES:
                    case TABLE_REFSCHEDULE:

                        //                        FIELDS
//                        *********************************
//                        loan_number
//                        due_date
//                        due_id
//                        due_principal
//                        due_interest
//                        due_penalty
//                        due_commission
//                        due_vat
//                        groupmembership_id
//                        due_status
//                        *********************************
                        $value['DATE'] = isset($value['DATE']) ? $value['DATE'] : $value['date'];

                        self::$keyValues[$value['TABLE']][$value['LNR']] = $value['LNR'];

                        self::$sqlValues[$value['TABLE']][] = "('"
                                . $value['LNR']
                                . "', DATE('" . $value['DATE']
                                . "'), '" . ($value['ID']??'')
                                . "', '" . (isset($value['PRINC']) ? $value['PRINC'] : $value['principal'])
                                . "', '" . (isset($value['INT']) ? $value['INT'] : $value['interest'])
                                . "', '" . (isset($value['PEN']) ? $value['PEN'] : $value['penalty'])
                                . "', '" . (isset($value['COMM']) ? $value['COMM'] : $value['commission'])
                                . "', '0'"
                                . ", '" . ($value['MEMID']??'')
                                . "', '" .($value['STATUS']??'')
                                . "')";

                        break;

                    case TABLE_LOANSTATUSLOG:

//                        FIELDS
//                       *********************************                        
//                        loan_number
//                        loan_status
//                        loan_datecreated
//                        loan_amount
//                        user_id
//                        *********************************                       
      
                        self::$sqlValues[$value['TABLE']][] = "('"
                                . $value['LNR']
                                . "', '" . $value['LSTATUS']
                                . "', '" . $value['DATE']
                                . "', '" . $value['LAMNT']
                                . "', '" . $value['USERID'] . "')";

                        break;

                    case TABLE_LOAN:

//                        FIELDS
//                        *********************************             
//                        loan_number
//                        client_idno
//                        loan_amount
//                        fund_code
//                        loan_tint
//                        loan_intamount
//                        user_id
//                        loan_startdate
//                        loan_grace
//                        loan_noofinst
//                        loan_exp
//                        loan_status
//                        loan_firstinst
//                        loan_udf1
//                        loan_udf2
//                        loan_udf3
//                        loan_adate
//                        loan_inttype
//                        loan_insttype
//                        loan_alsograce
//                        loan_intdays
//                        loan_intdeductedatdisb
//                        product_prodid
//                        donor_code
//                        branch_code
//                        groupmembership_id
//                        loan_intcgrace
//                        loan_intfirst
//                        loan_lastinstpp
//                        loan_insintgrac
//                        loan_comm
//                        loan_freezedate
//                        loan_expdisb
//                        loan_gracecompd
//                        loan_intindays
//                        loanpurpose_id
//                        loan_inupfront
//                        *********************************

                        unset(self::$aFieldList[$value['TABLE']]['loan_status']);

                        $value['UD1'] = (isset($value['UD1']) ? $value['UD1'] : '0000');
                        $value['UD1'] = (isset($value['UD2']) ? $value['UD2'] : '0000');
                        $value['UD1'] = (isset($value['UD3']) ? $value['UD3'] : '0000');
                        $value['FCODE'] = isset($value['FCODE']) ? $value['FCODE'] : '0000';
                        $value['DCODE'] = (isset($value['DCODE']) ? $value['DCODE'] : '00000');

                        self::$sqlValues[$value['TABLE']][] = "('"
                                . $value['LNR']
                                . "','" . $value['CLIENTIDNO']
                                . "','" . $value['LAMNT']
                                . "','" . $value['FCODE']
                                . "','" . $value['INTRATE']
                                . "','" . $value['INTAMNT']
                                . "','" . $value['USERID']
                                . "','" . $value['START']
                                . "','" . $value['GRACE']
                                . "','" . $value['NINST']
                                . "','" . $value['LEXP']
                                . "','" . $value['FIRSTINS']
                                . "','" . $value['UD1']
                                . "','" . $value['UD2']
                                . "','" . $value['UD3']
                                . "','" . $value['DATE']
                                . "','" . $value['INTTYPE']
                                . "','" . $value['INSTYPE']
                                . "','" . $value['AGRACE']
                                . "','" . $value['INTDAY']
                                . "','" . $value['INTDIS']
                                . "','" . $value['PRODUCT_PRODID']
                                . "','" . $value['DCODE']
                                . "','" . $value['BRANCHCODE']
                                . "','" . ($value['MEMID']??'')
                                . "','" . $value['INTCG']
                                . "','" . $value['INTF']
                                . "','" . $value['LINS']
                                . "','" . $value['INTCG']
                                . "','" . $value['COMM']
                                . "','" . $value['FREEZE']
                                . "','" . $value['EXDATE']
                                . "','" . $value['GCOMP']
                                . "','" . $value['INTDAY']
                                . "','" . $value['LPD']
                                . "','" . $value['INTUPF']
                                . "')";
                        break;

                    case TABLE_LOANFEES:

//                        FIELDS
//                        *********************************                  
//                        loan_number
//                        client_idno
//                        transactioncode
//                        loanfee_amount
//                        groupmembership_id
//                        last_updatedate
//                        loanfee_type
//                        loanfee_date
//                        loanfee_voucher
//                        paymode
//                        user_id
//                      *********************************

                        self::$sqlValues[$value['TABLE']][] = "('"
                                . $value['LNR']
                                . "', '" . $value['CLIENTIDNO']
                                . "', '" . $value['TCODE']
                                . "', '" . $value['AMOUNT']
                                . "', '" . $value['MEMID']??''
                                . "',NOW()"
                                . ", ''"
                                . "','" . $value['DATE']
                                . "','" . $value['VOUCHER']
                                . "','" . $value['MODE']
                                . "','" . $_SESSION['user_id']
                                . "')";


                        break;

                    case TABLE_CHEQS:

//                        FIELDS
//                        *********************************  
//                        cheqs_no
//                        bankaccounts_accno
//                        bankbranches_id
//                        cheqs_status
//                        cheqs_datecleared
//                        cheqs_amount
//                        cheqs_datecreated
//                        cheqs_type
//                        transactioncode
//                        client_idno
//                        *********************************                      

                        self::$sqlValues[$value['TABLE']][] = "('"
                                . $value['CHEQNO']
                                . "', '" . $value['BACCNO']
                                . "', '" . $value['BID']
                                . "', '" . $value['CQSTAT']
                                . "', NULL"
                                . ", '" . $value['AMOUNT']
                                . "', '" . $value['DATE']
                                . "', '" . $value['CQTYPE']
                                . "', '" . $value['TCODE']
                                . "', '" . $value['CLIENTIDNO']
                                . "')";
                        break;

                    case TABLE_LOANPAYMENTS:

//                        FIELDS
//                        *********************************  
//                        loan_number
//                        loanpayments_date
//                        groupmembership_id
//                        loanpayments_principal
//                        loanpayments_interest
//                        loanpayments_commission
//                        loanpayments_penalty
//                        loanpayments_vat
//                        transactioncode
//                        paymode
//                        loanpayments_voucher
//                        loanpayments_id
//                        user_id
//                        *********************************  
                        
                        self::$keyValues[$value['TABLE']][] = $value['TCODE'];

                        self::$sqlValues[$value['TABLE']][] = "('"
                                . $value['LNR']
                                . "', '" . $value['DATE']
                                . "', '" . $value['MEMID']??''
                                . "', '" . $value['PRI']
                                . "', '" . $value['INT']
                                . "', '" . $value['COM']
                                . "', '" . $value['PEN']
                                . "', '" . $value['VAT']
                                . "', '" . $value['TCODE']
                                . "', '" . $value['MODE']
                                . "', '" . $value['VOUCHER']
                                . "', '" . Common::uniqidReal()
                                . "', '" . $_SESSION['user_id']
                                . "')";

                        break;

                    case TABLE_GENERALLEDGER:

//                        FIELDS
//                        *********************************
//                        chartofaccounts_accountcode
//                        generalledger_tday
//                        transactioncode
//                        generalledger_updated
//                        generalledger_description
//                        generalledger_id
//                        fund_code
//                        donor_code
//                        generalledger_credit
//                        generalledger_voucher
//                        user_id
//                        generalledger_debit
//                        branch_code
//                        trancode
//                        generalledger_locked
//                        forexrates_id
//                        generalledger_fcamount
//                        currencies_id
//                        client_idno
//                        product_prodid
//                        costcenters_code
//                        *********************************

                        self::$sqlValues[$value['TABLE']][] = "('"
                                . $value['GLACC']
                                . "', '" . $value['DATE']
                                . "', '" . $value['TCODE']
                                . "', NOW()"
                                . ", '" . $value['DESC']
                                . "', '" . Common::uniqidReal()
                                . "', '" . $value['FUNDCODE']
                                . "', '" . $value['DONORCODE']
                                . "', '" . $value['CREDIT']
                                . "', '" . $value['VOUCHER']
                                . "', '" . $_SESSION['user_id']
                                . "', '" . $value['DEBIT']
                                . "', '" . $value['BRANCHCODE']
                                . "', '" . $value['TRANCODE']
                                . "','0'"
                                . ", '" . $value['FXID']
                                . "', '" . $value['FCAMT']
                                . "', '" . $value['CURRENCIES_ID']
                                . "', '" . $value['CLIENTIDNO']
                                . "', '" . $value['PRODUCT_PRODID']
                                . "', '" . $value['CCODE']
                                . "')";

                        break;

                    case TABLE_SAVTRANSACTIONS:

//                        FIELDS
//                        *********************************
//                        savaccounts_account
//                        product_prodid
//                        savtransactions_tday
//                        transactioncode
//                        savtransactions_voucher
//                        savtransactions_amount
//                        savtransactions_balance
//                        savtransactions_commission
//                        cheqs_no
//                        groupmembership_id
//                        transactiontypes_code
//                        paymode
//                        user_id
//                        last_updatedate
//                        *********************************                        
                        unset(self::$aFieldList[$value['TABLE']]['last_updatedate']);
                        self::$sqlValues[$value['TABLE']][] = "('"
                                . $value['SAVACC']
                                . "', '" . $value['PRODUCT_PRODID']
                                . "', '" . $value['DATE']
                                . "', '" . $value['TCODE']
                                . "', '" . $value['VOUCHER']
                                . "', '" . $value['AMOUNT']
                                . "', 0"
                                . ", '" . $value['COMM']
                                . "', '" . $value['CHEQNO']
                                . "', '" . $value['MEMID']??''
                                . "', '" . $value['TTYPE']
                                . "', '" . $value['MODE']
                                . "', '" . $_SESSION['user_id']
                                . "'";
                        break;

                    default:
                        break;
                }
            }

            if ($isDone):
                foreach (self::$aFieldList as $key => $fvalue):
                    self::$sSQL[$key] = 'INSERT INTO ' . $key . ' (' . implode(',', array_keys($fvalue)) . ') VALUES ' . implode(',', self::$sqlValues[$key]);
                endforeach;
            endif;
        } catch (Exception $e) {
            Self::$lablearray['E01'] = $e->getMessage();
        }
    }

    # This functin get the structure of the table

    private static function getStruct($table) {

        $tabletstruct = array_map('array_flip', array_values(self::$Conn->preparefieldList($table)));

        //self::$data = call_user_func_array('array_merge',$tabletstruct);

        self::$data = array_map(function($val) {
            return 'NULL';
        }, call_user_func_array('array_merge', $tabletstruct));

        return self::$data;
    }

    /* DESCRIPTION:  THIS FUNCTION IS USED CREATE AN EXML STRING FROM AN ARRAY
     * PARAMETERS:
     * $myarray - The Array
     * $myTable - The Table in which array willbe inserted
     * NOTE:
     */

    public static function covertArrayToXML($myarraydata, $isDone = true) {

        //  print_r($myarraydata);
        // $xmlstr = '<xml version="1.0" standalone="yes"></xml>';
        // check see if this object is already initialised

        try {

            if (!is_object(self::$xmlObj) && !self::$isBulkInsert) {
                self::$xmlObj = new SimpleXMLElement('<xml version="1.0" standalone="yes"></xml>');
            }

            //$record = $table->addChild('record', '');
            $prevtable = '';

            foreach ($myarraydata as $key => $value) {

                // CHECK SEE IF BULKS INSERT IS ON
                if (self::$isBulkInsert):
                    self::prepareBulkStatement($myarraydata, $isDone);
                    break;
                endif;

                if ($value['TABLE'] != $prevtable) {
                    $table = self::$xmlObj->addChild('table');
                    $table->addAttribute('tname', $value['TABLE']);
                    $table->addAttribute('action', (isset($value['ACTION']) ? $value['ACTION'] : ''));
                }

                $record = $table->addChild('record');

                switch ($value['TABLE']) {

                    case TABLE_DEVICEMESSAGE:

                        $record->addAttribute('action', $value['ACTION']);
                        $record->addAttribute('dmid', $value['DMID']);
                        $record->addAttribute('res', $value['RES']);
                        $record->addAttribute('status', $value['STAT']);
                        break;

                    case TABLE_MODEM:

                        $record->addAttribute('action', $value['ACTION']);
                        $record->addAttribute('mid', $value['MID']);
                        $record->addAttribute('dname', $value['DNAME']);
                        $record->addAttribute('port', $value['PORT']);
                        $record->addAttribute('bps', $value['BPS']);

                        break;

                    case TABLE_MEMBERLOANS:

                        $record->addAttribute('lnr', $value['LNR']);
                        $record->addAttribute('mid', $value['MID']);
                        $record->addAttribute('cid', $value['CLIENTIDNO']);
                        $record->addAttribute('lamt', $value['LAMNT']);
                        $record->addAttribute('intamt', $value['INTAMNT']);

                        break;

                    case TABLE_GUARANTORS:

                        $record->addAttribute('lnr', $value['LNR']);
                        $record->addAttribute('cid', $value['CID']);

                        break;

                    case TABLE_DOCUMENT:

                        $record->addAttribute('docid', $value['DOCID']);
                        $record->addAttribute('serial', $value['SERIAL']);
                        $record->addAttribute('docexp', $value['DOCEXP']);
                        $record->addAttribute('idate', $value['IDATE']);
                        $record->addAttribute('pri', $value['PRI']);
                        $record->addAttribute('auth', $value['AUTH']);
                        $record->addAttribute('url', $value['URL']);
                        $record->addAttribute('cid', $value['CLIENTIDNO']);

                        break;

                    case TABLE_CLIENTS:

                        $record->addAttribute('cid', $value['CLIENTIDNO']);
                        $record->addAttribute('sname', $value['SNAME']);
                        $record->addAttribute('fname', $value['FNAME']);
                        $record->addAttribute('mname', $value['MNAME']);
                        $record->addAttribute('gender', $value['GENDER']);
                        $record->addAttribute('email', $value['EMAIL']);
                        $record->addAttribute('pad', $value['PAD']);
                        $record->addAttribute('docid', ($value['DOCID']??''));
                        $record->addAttribute('serial', ($value['SERIAL']??''));
                        $record->addAttribute('docexp', ($value['DOCEXP']??''));
                        $record->addAttribute('cat1', $value['CAT1']);
                        $record->addAttribute('cat2', $value['CAT2']);
                        $record->addAttribute('ccode', $value['CCODE']);
                        $record->addAttribute('edate', ($value['EDATE'])??'');
                        $record->addAttribute('postad', $value['POSTAD']);
                        $record->addAttribute('city', $value['CITY']);
                        $record->addAttribute('tel1', $value['TEL1']);
                        $record->addAttribute('tel2', $value['TEL2']);
                        $record->addAttribute('status', $value['STATUS']);
                        $record->addAttribute('acode', $value['ACODE']);
                        $record->addAttribute('brcode', $value['BRCODE']);
                        $record->addAttribute('bday', $value['BDAY']);
                        $record->addAttribute('ocp', $value['OCP']);
                        $record->addAttribute('kin', $value['KIN']);
                        $record->addAttribute('rdate', $value['RDATE']);
                        $record->addAttribute('mstate', $value['MSTATE']);
                        $record->addAttribute('bcode', $value['BCODE']);
                        $record->addAttribute('ctype', $value['CTYPE']);
                        break;

                    case TABLE_ENTITY:

                        $record->addAttribute('gid', $value['CLIENTIDNO']??'');
                        $record->addAttribute('ename', $value['ENAME']??'');
                        $record->addAttribute('rdate', $value['RDATE']??'');
                        $record->addAttribute('postad', $value['POSTAD']??'');
                        $record->addAttribute('city', $value['CITY']??'');
                        $record->addAttribute('pad', $value['PAD']??'');
                        $record->addAttribute('tel1', $value['TEL1']??'');
                        $record->addAttribute('tel2', $value['TEL2']??'');
                        $record->addAttribute('ccode', $value['CCODE']??'');
                        $record->addAttribute('status', $value['STATUS']??'');
                        $record->addAttribute('acode', $value['ACODE']??'');
                        $record->addAttribute('brcode', $value['BRCODE']??'');
                        $record->addAttribute('regcode', $value['REGCODE']??'');
                        $record->addAttribute('ctype', $value['CTYPE']??'');

                        break;

                    case TABLE_MEMBERS:

                        $record->addAttribute('mid', $value['MID']);
                        $record->addAttribute('rdate', $value['MRDATE']);
                        $record->addAttribute('edate', $value['MEDATE']);
                        $record->addAttribute('fname', $value['FNAME']);
                        $record->addAttribute('mname', $value['MNAME']);
                        $record->addAttribute('lname', $value['LNAME']);
                        $record->addAttribute('mstat', $value['MSTAT']);
                        $record->addAttribute('dep', $value['DEP']);
                        $record->addAttribute('child', $value['CHILD']);
                        $record->addAttribute('cat1', $value['CAT1']);
                        $record->addAttribute('cat2', $value['CAT2']);
                        $record->addAttribute('lang', $value['LANG']);
                        $record->addAttribute('educ', $value['EDUC']);
                        $record->addAttribute('income', $value['INCOME']);
                        $record->addAttribute('lang1', $value['LANG1']);
                        $record->addAttribute('lang2', $value['LANG2']);
                        $record->addAttribute('incomeid', $value['INCOMEID']);
                        $record->addAttribute('email', $value['EMAIL']);
                        $record->addAttribute('gid', $value['CLIENTIDNO']);
                        $record->addAttribute('status', $value['STATUS']);
                        $record->addAttribute('mno', $value['MNO']);
                        $record->addAttribute('ctype', $value['CTYPE']);
                        $record->addAttribute('brcode', $value['BRCODE']);
                        break;

                    case TABLE_PRODUCT:

                        $record->addAttribute('pcode', $value['PCODE']);
                        $record->addAttribute('pname', $value['PNAME']);
                        $record->addAttribute('opcode', $value['OPCODE']);

                        break;

                    case TABLE_DELETEDTRANS:

                        $record->addAttribute('tcode', $value['TCODE']);
                        $record->addAttribute('module', $value['MODULE']);
                        $record->addAttribute('uid', $value['UID']);
                        $record->addAttribute('brcode', $value['BRCODE']);

                        break;

                    case TABLE_PRODUCTCONFIG:

                        $record->addAttribute('name', $value['NAME']);
                        $record->addAttribute('prodid', $value['PRODUCT_PRODID']);
                        $record->addAttribute('indacc', $value['INDACC']);
                        $record->addAttribute('grpacc', $value['GRPACC']);
                        $record->addAttribute('gacc', $value['GACC']);
                        $record->addAttribute('dgrp', $value['DGRP']);
                        $record->addAttribute('desc', $value['DESC']);
                        $record->addAttribute('brcode', $value['BRANCHCODE']);

                        break;

                    case TABLE_TDEPOSIT:

                        $record->addAttribute('tdno', $value['TDNO']);
                        $record->addAttribute('cid', $value['CLIENTIDNO']);
                        $record->addAttribute('prodid', $value['PRODUCT_PRODID']);
                        $record->addAttribute('brcode', $value['BRANCHCODE']);
                        $record->addAttribute('memid', $value['MEMID']??'');

                        break;

                    case TABLE_TDEPOSITTRANS:

                        $record->addAttribute('tdno', $value['TDNO']);
                        $record->addAttribute('tcode', $value['TCODE']);
                        $record->addAttribute('otcode', $value['OTCODE']);
                        $record->addAttribute('date', $value['DATE']);
                        $record->addAttribute('status', $value['STATUS']);
                        $record->addAttribute('int', $value['INT']);
                        $record->addAttribute('intamt', $value['INTAMT']);
                        $record->addAttribute('amt', $value['AMOUNT']);
                        $record->addAttribute('cheqno', $value['CHEQNO']);
                        $record->addAttribute('voucher', $value['VOUCHER']);
                        $record->addAttribute('period', $value['PERIOD']);
                        $record->addAttribute('matval', $value['MATVAL']);
                        $record->addAttribute('matdate', $value['MATDATE']);
                        $record->addAttribute('intcap', $value['INTCAP']);
                        $record->addAttribute('freq', $value['FREQ']);
                        $record->addAttribute('instype', $value['INSTYPE']);
                        $record->addAttribute('ointamt', $value['OINTAMT']);
                        $record->addAttribute('omatval', $value['OMATVAL']);
                        $record->addAttribute('memid', $value['MEMID']??'');

                        break;

                    case TABLE_CHARTOFACCOUNTS:

                        $record->addAttribute('name', $value['NAME']);
                        $record->addAttribute('level', $value['LEVEL']);
                        $record->addAttribute('parent', $value['PARENT']);
                        $record->addAttribute('header', $value['HEADER']);
                        $record->addAttribute('glacc', $value['GLACC']);
                        $record->addAttribute('tgrp', $value['TGRP']);
                        $record->addAttribute('gcode', $value['GCODE']);
                        $record->addAttribute('curid', $value['CURRENCIES_ID']);
                        $record->addAttribute('rval', $value['RVAL']);
                        $record->addAttribute('bitem', $value['BITEM']);
                        $record->addAttribute('desc', $value['DESC']);

                        break;

                    case TABLE_ROLESCASHACCOUNTS:

                        $record->addAttribute('roleid', $value['ROLEID']);
                        $record->addAttribute('glacc', $value['GLACC']);

                        break;

                    case TABLE_ROLESMODULES:
                        $record->addAttribute('roleid', $value['ROLEID']);
                        $record->addAttribute('mid', $value['MID']);
                        break;

                    case TABLE_USERROLES:

                        $record->addAttribute('roleid', $value['ROLEID']);
                        $record->addAttribute('uid', $value['UID']);

                        break;

                    case TABLE_ROLES:

                        $record->addAttribute('role', $value['ROLE']);
                        $record->addAttribute('roleid', $value['ROLEID']);
                        $record->addAttribute('lang', $value['LANG']);

                        break;

                    case TABLE_USERBRANCHES:

                        $record->addAttribute('ucode', $value['UCODE']);
                        $record->addAttribute('brcode', $value['BRANCHCODE']);
                        $record->addAttribute('lic', $value['LIC']);
                        $record->addAttribute('acode', $value['ACODE']);
                        $record->addAttribute('pbrcode', $value['PBRANCHCODE']);

                        break;

                    case TABLE_USERS:

                        $record->addAttribute('fname', $value['FNAME']);
                        $record->addAttribute('lname', $value['LNAME']);
                        $record->addAttribute('mname', $value['MNAME']);
                        $record->addAttribute('uname', $value['UNAME']);
                        $record->addAttribute('pwd', $value['PWD']);
                        $record->addAttribute('email', $value['EMAIL']);
                        $record->addAttribute('lang', $value['LANG']);
                        $record->addAttribute('acode', $value['ACODE']);
                        $record->addAttribute('ucode', $value['UCODE']);
                        $record->addAttribute('active', $value['ACTIVE']);
                        $record->addAttribute('uid', $value['UID']);
                        $record->addAttribute('exp', $value['EXP']);

                        break;

                    case TABLE_CLIENTSAVE:

                        $record->addAttribute('fname', $value['FNAME']);
                        $record->addAttribute('prodid', $value['PRODUCT_PRODID']);
                        $record->addAttribute('acc', $value['SAVACC']);
                        $record->addAttribute('amt', $value['AMOUNT']);
                        $record->addAttribute('memid', $value['MEMID']??'');
                        $record->addAttribute('freq', $value['FREQ']);
                        $record->addAttribute('lprodid', $value['LPRODID']);

                        break;

                    case TABLE_SAVACCOUNTS:

                        $record->addAttribute('cid', $value['CLIENTIDNO']);
                        $record->addAttribute('prodid', $value['PRODUCT_PRODID']);
                        $record->addAttribute('acc', $value['SAVACC']);
                        $record->addAttribute('odate', $value['DATE']);

                        break;

                    case TABLE_REFINANCED:

                        $record->addAttribute('lnr', $value['LNR']);
                        $record->addAttribute('date', $value['DATE']);
                        $record->addAttribute('amt', $value['LAMNT']);
                        $record->addAttribute('add', $value['TOPUP']);
                        $record->addAttribute('inst', $value['NOINS']);
                        $record->addAttribute('uid', $value['USERID']);

                        break;

                    case TABLE_DISBURSEMENTS:

                        $record->addAttribute('lnr', $value['LNR']);
                        $record->addAttribute('tcode', $value['TCODE']);
                        $record->addAttribute('date', $value['DATE']);
                        $record->addAttribute('vat', $value['VAT']);
                        $record->addAttribute('amt', abs($value['AMOUNT']));
                        $record->addAttribute('voc', $value['VOUCHER']);
                        $record->addAttribute('comm', $value['COMM']);
                        $record->addAttribute('cheqno', $value['CHEQNO']);
                        $record->addAttribute('cash', $value['MODE']);
                        $record->addAttribute('cycle', $value['CYCLE']);
                        $record->addAttribute('memid', $value['MEMID']??'');
                        $record->addAttribute('stat', $value['STAT']);
                        $record->addAttribute('dtype', $value['DTYPE']);
                        $record->addAttribute('mode', $value['MODE']);
                        $record->addAttribute('uid', $_SESSION['user_id']);

                        break;

                    case TABLE_DUES:

                        $record->addAttribute('id', $value['ID']??'');
                        $record->addAttribute('lnr', $value['LNR']??'');
                        $record->addAttribute('princ', (isset($value['PRINC']) ? $value['PRINC'] : $value['principal']));
                        $record->addAttribute('int', (isset($value['INT']) ? $value['INT'] : $value['interest']));
                        $record->addAttribute('pen', (isset($value['PEN']) ? $value['PEN'] : $value['penalty']));
                        $record->addAttribute('comm', (isset($value['COMM']) ? $value['COMM'] : $value['commission']));
                        $record->addAttribute('date', (isset($value['DATE']) ? $value['DATE'] : $value['date']));
                        $record->addAttribute('memid', ($value['MEMID'] ??''));
                        $record->addAttribute('vat', '0');

                        break;

                    case TABLE_LOANSTATUSLOG:

                        $record->addAttribute('lnr', $value['LNR']); // to be updated
                        $record->addAttribute('lstatus', $value['LSTATUS']);

                        $value['AMOUNT'] = (isset($value['LAMNT']) ? $value['LAMNT'] : $value['AMOUNT']);
                        $value['AMOUNT'] = (isset($value['TOPUP']) ? $value['TOPUP'] : $value['AMOUNT']);

                        $record->addAttribute('ldate', $value['DATE']??''); // to be updated

                        $record->addAttribute('amt', abs($value['AMOUNT'])??0);

                        $record->addAttribute('uid', $value['USERID']);

                        break;

                    case TABLE_LOAN:

                        $value['CLIENTIDNO'] = (isset($value['CLIENTIDNO']) ? $value['CLIENTIDNO'] : $value['CCODE']);

                        $record->addAttribute('lnr', $value['LNR'] ?? '');
                        $record->addAttribute('cid', $value['CLIENTIDNO'] ?? '');
                        $record->addAttribute('lamt', $value['LAMNT'] ?? '');
                        $record->addAttribute('fcode', $value['FCODE'] ?? '0000');
                        $record->addAttribute('int', $value['INTRATE'] ?? '');
                        $record->addAttribute('intamt', $value['INTAMNT'] ?? '');
                        $record->addAttribute('uid', $value['USERID'] ?? '');
                        $record->addAttribute('sdate', $value['START'] ?? '');
                        $record->addAttribute('grace', $value['GRACE'] ?? '');
                        $record->addAttribute('noofinst', $value['NINST'] ?? '');
                        $record->addAttribute('lexp', $value['LEXP'] ?? '');
                        $record->addAttribute('firstinst', $value['FIRSTINS'] ?? '');
                        $record->addAttribute('udf1', $value['UD1'] ?? '0000');
                        $record->addAttribute('udf2', $value['UD2'] ?? '0000');
                        $record->addAttribute('udf3', $value['UD3'] ?? '0000');
                        $record->addAttribute('adate', $value['DATE'] ?? '');
                        $record->addAttribute('inttype', $value['INTTYPE'] ?? '');
                        $record->addAttribute('insttype', $value['INSTYPE'] ?? '');
                        $record->addAttribute('alsograce', $value['AGRACE'] ?? 0);
                        $record->addAttribute('intdays', $value['INTDAY'] ?? 0);
                        $record->addAttribute('intdeductedatdisb', $value['INTDIS'] ?? '');
                        $record->addAttribute('prodid', $value['PRODUCT_PRODID'] ?? '');
                        $record->addAttribute('dcode', $value['DCODE'] ?? '00000');
                        $record->addAttribute('brcode', $value['BRANCHCODE'] ?? '');
                        $record->addAttribute('memid', $value['MEMID'] ?? '');
                        $record->addAttribute('intcgrace', $value['INTCG'] ?? '');
                        $record->addAttribute('intfirst', $value['INTF'] ?? '');
                        $record->addAttribute('lastinstpp', $value['LINS'] ?? '');
                        $record->addAttribute('comm', $value['COMM'] ?? '');
                        $record->addAttribute('freezed', $value['FREEZE'] ?? '');
                        $record->addAttribute('expdisb', $value['EXDATE'] ?? '');
                        $record->addAttribute('gracecompd', $value['GCOMP'] ?? '');
                        $record->addAttribute('intindays', $value['INTIND'] ?? '');
                        $record->addAttribute('pid', $value['LPD'] ?? '');
                        $record->addAttribute('inupfront', $value['INTUPF'] ?? '');

                        break;

                    case TABLE_LOANFEES:

                        $record->addAttribute('date', $value['DATE']);
                        $record->addAttribute('lnr', $value['LNR']);
                        $record->addAttribute('cid', $value['CLIENTIDNO']);
                        $record->addAttribute('tcode', $value['TCODE']);
                        $record->addAttribute('amt', $value['AMOUNT']);
                        $record->addAttribute('memid', $value['MEMID']??'');

                        break;

                    case TABLE_CHEQS:

                        $value['CQSTAT'] = (isset($value['CQSTAT']) ? $value['CQSTAT'] : 'Q' );
                        $value['CLIENTIDNO'] = (isset($value['CLIENTIDNO']) ? $value['CLIENTIDNO'] : '' );
                        $value['CQTYPE'] = (isset($value['CQTYPE']) ? $value['CQTYPE'] : '' );
                        $record->addAttribute('cheqno', $value['CHEQNO']??'');
                        $record->addAttribute('accno', $value['BACCNO']);
                        $record->addAttribute('bid', $value['BID']);
                        $record->addAttribute('cqst', $value['CQSTAT']);
                        $record->addAttribute('amt', abs($value['AMOUNT']));
                        $record->addAttribute('date', $value['DATE']);
                        $record->addAttribute('cqtype', $value['CQTYPE']);
                        $record->addAttribute('tcode', $value['TCODE']);
                        $record->addAttribute('cid', $value['CLIENTIDNO']);

                        break;

                    case TABLE_LOANPAYMENTS: // Loan Repayments                           

                        $value['CLOSE'] = (isset($value['CLOSE']) ? $value['CLOSE'] : '0' );

                        $record->addAttribute('memid', $value['MEMID']??'');
                        $record->addAttribute('lnr', $value['LNR']);
                        $record->addAttribute('princ', $value['PRI']);
                        $record->addAttribute('int', $value['INT']);
                        $record->addAttribute('pen', $value['PEN']);
                        $record->addAttribute('comm', $value['COM']);
                        $record->addAttribute('vat', $value['VAT']);
                        $record->addAttribute('date', $value['DATE']);
                        $record->addAttribute('tcode', $value['TCODE']);
                        $record->addAttribute('mode', $value['MODE']);
                        $record->addAttribute('voucher', $value['VOUCHER']);
                        $record->addAttribute('ovr', (isset($value['OVR']) ? $value['OVR'] : '0'));
                        $record->addAttribute('close', $value['CLOSE']);
                        $record->addAttribute('pulldates', $value['PULLDATES']);
                        //$record->addAttribute('action', $value['ACTION']);
                        $record->addAttribute('status', (isset($value['STATUS']) ? $value['STATUS'] : ''));

                        break;

                    case TABLE_GENERALLEDGER: // GL Transactions

                        $record->addAttribute('tcode', $value['TCODE']);
                        $record->addAttribute('date', $value['DATE']);
                        $record->addAttribute('desc', $value['DESC']);
                        $record->addAttribute('fcode', $value['FUNDCODE']);
                        $record->addAttribute('dcode', $value['DONORCODE']);
                        $record->addAttribute('dr', $value['DEBIT']);
                        $record->addAttribute('cr', $value['CREDIT']);
                        $record->addAttribute('voucher', $value['VOUCHER']??'');
                        $record->addAttribute('uid', $_SESSION['user_id']);
                        $record->addAttribute('glacc', $value['GLACC']);
                        $record->addAttribute('brcode', $value['BRANCHCODE']);
                        $record->addAttribute('trcode', $value['TRANCODE']);
                        $record->addAttribute('fxid', $value['FXID']);
                        $record->addAttribute('fcamt', $value['FCAMT']);

                        //  fcamount -- to be computed at the server side                    
                        $record->addAttribute('curid', $value['CURRENCIES_ID']);
                        $record->addAttribute('cid', ($value['CLIENTIDNO']??''));
                        $record->addAttribute('prodid', $value['PRODUCT_PRODID']);
                        $record->addAttribute('costc', $value['CCODE']);

                        break;

                    case TABLE_SAVTRANSACTIONS:  // balance will be computed on the server     

                        $record->addAttribute('tcode', $value['TCODE']);
                        $record->addAttribute('date', $value['DATE']);
                        $record->addAttribute('desc', $value['DESC']);
                        $record->addAttribute('prodid', $value['PRODUCT_PRODID']);
                        $record->addAttribute('voucher', $value['VOUCHER']);
                        $record->addAttribute('acc', $value['SAVACC']);
                        $record->addAttribute('amt', $value['AMOUNT']);                     
                        $record->addAttribute('memid', $value['MEMID']??"");
                        $record->addAttribute('mode', $value['MODE']);
                        $record->addAttribute('ttype', $value['TTYPE']);
                        $record->addAttribute('cid', $value['CLIENTIDNO']);
                        $record->addAttribute('cheqno', $value['CHEQNO']??'');
                        $record->addAttribute('uid', $_SESSION['user_id']);

                        $value['COMM'] = (isset($value['COMM']) ? $value['COMM'] : '0' );

                        $record->addAttribute('comm', $value['COMM']);

                        break;

                    default:
                        break;
                }

                // $nCount = $nCount + 1;
                $prevtable = $value['TABLE'];
            }

            if ($isDone && is_object(self::$xmlObj)) {

                self::$xml = self::$xmlObj->asXML();
                //echo "<pre>".htmlentities($nxml)."</pre>";
                // exit();
            
                self::$sSQL[TABLE_XMLTRANS] = str_replace("<?xml version=\"1.0\"?>\n", '', self::$xml);
            }

            //catch exception
        } catch (Exception $e) {
            Self::$lablearray['E01'] = $e->getMessage();
        }
    }

    # This function is used to records transaction to tables for committ

    public static function PrepareData($commitchanges = true) {

        try {

            
            foreach (Bussiness::$sSQL as $table => $tran_array) {

                self::$data['xmltrans_dynamicsqlid'] = '';
                
                $vtables = $table;
                
                if (self::$isBulkInsert):
                    $table = TABLE_XMLTRANS;
                endif;

                self::getStruct($table);

                switch ($table) {

                    case TABLE_XMLTRANS:
                        self::$data['xmltrans_id'] = Common::uniqidReal();
                        self::$data['xmltrans_data'] = $tran_array;
                        self::$data['user_id'] = $_SESSION['user_id'];
                        self::$data['xmltrans_status'] = 'N';

                        if (is_array(self::$keyValues)){

                            if(isset(self::$keyValues[$vtables])){
                                self::$data['xmltrans_table'] = $vtables;
                                self::$data['xmltrans_keyvalues'] = "'" . implode("','", self::$keyValues[$vtables]) . "'";
                            }
                                
                        }else{
                            self::$data['xmltrans_table'] = '';
                            self::$data['xmltrans_keyvalues'] = '';
                        }

                        if (self::$isBulkInsert):
                            self::$data['xmltrans_dynamicsqlid'] = self::$data['xmltrans_id'];
                        else:
                            self::$data['xmltrans_dynamicsqlid'] = '0';
                        endif;

                        break;

                      
                    case TABLE_DUES:
                        self::$data['loan_number'] = $tran_array['loan_number'];
                        self::$data['due_principal'] = $tran_array['due_principal'];
                        self::$data['due_interest'] = $tran_array['due_interest'];
                        self::$data['due_penalty'] = $tran_array['due_penalty'];
                        self::$data['due_commission'] = $tran_array['due_commission'];
                        self::$data['due_vat'] = $tran_array['due_vat'];
                        self::$data['due_date'] = $tran_array['due_date'];
                        self::$data['members_idno'] = $tran_array['members_idno'];
                        break;

                    case TABLE_LOANSTATUSLOG:
                        self::$data['loan_number'] = $tran_array['loan_number'];
                        self::$data['loan_status'] = $tran_array['loan_status'];
                        self::$data['loan_datecreated'] = $tran_array['loan_datecreated'];
                        self::$data['loan_amount'] = $tran_array['loan_amount'];
                        break;

                    case TABLE_DISBURSEMENTS:
                        self::$data['loan_number'] = $tran_array['loan_number'];
                        self::$data['disbursements_vat'] = $tran_array['disbursements_vat'];
                        self::$data['disbursements_date'] = $tran_array['disbursements_date'];
                        self::$data['disbursements_voucher'] = $tran_array['disbursements_voucher'];
                        self::$data['disbursements_stationery'] = $tran_array['disbursements_stationery'];
                        self::$data['disbursements_commission'] = $tran_array['disbursements_commission'];
                        self::$data['disbursements_amount'] = $tran_array['disbursements_amount'];
                        self::$data['cheqs_no'] = $tran_array['cheqs_no'];
                        self::$data['cash'] = $tran_array['cash'];
                        self::$data['transactioncode'] = $tran_array['transactioncode'];
                        self::$data['cycle'] = $tran_array['cycle'];
                        self::$data['members_idno'] = $tran_array['members_idno'];
                        break;

                    case TABLE_LOANPAYMENTS:
                        self::$data['loan_number'] = $tran_array['loan_number'];
                        self::$data['loanpayments_date'] = $tran_array['loanpayments_date'];
                        self::$data['members_idno'] = $tran_array['members_idno'];
                        self::$data['loanpayments_principal'] = $tran_array['loanpayments_principal'];
                        self::$data['loanpayments_interest'] = $tran_array['loanpayments_interest'];
                        self::$data['loanpayments_commission'] = $tran_array['loanpayments_commission'];
                        self::$data['loanpayments_penalty'] = $tran_array['loanpayments_penalty'];
                        self::$data['loanpayments_vat'] = $tran_array['loanpayments_vat'];
                        self::$data['transactioncode'] = $tran_array['transactioncode'];
                        self::$data['transactiontypes_code'] = $tran_array['transactiontypes_code'];
                        self::$data['loanpayments_voucher'] = $tran_array['voucher'];
                        break;

                    case TABLE_GENERALLEDGER:

                        if (empty($tran_array['transactioncode'])) {
                            $tcode = Common::generateTransactionCode($_SESSION['user_id']);
                        }

                        $datetime = getcurrentDateTime();

                        $val['DEBIT'] = abs($val['DEBIT']);
                        $val['CREDIT'] = abs($val['CREDIT']);


                        $tran_array['generalledger_voucher'] = (isset($tran_array['voucher']) ? $tran_array['voucher'] : '' );

                        self::$data['generalledger_voucher'] = $tran_array['voucher'];
                        self::$data['user_id'] = $_SESSION['user_id'];
                        self::$data['branch_code'] = $tran_array['branch_code'];
                        self::$data['generalledger_tday'] = $tran_array['generalledger_tday'];
                        self::$data['fund_code'] = $tran_array['FUNDCODE'];
                        self::$data['donor_code'] = $tran_array['DONORCODE'];
                        self::$data['generalledger_locked'] = 'N';
                        //  self::$data['generalledger_debit'] = $val['DEBIT'];
                        // self::$data['generalledger_credit'] = $val['CREDIT'];
                        self::$data['transactioncode'] = $tran_array['transactioncode'];
                        self::$data['trancode'] = $val['TRANCODE'];
                        self::$data['currencies_id'] = $val['currencies_id'];
                        self::$data['generalledger_updated'] = $datetime;

                        // check see if we are transacting in foregn currency	
                        if (SETTTING_CURRENCY_ID != $val['CURRENCIES_ID']) {

                            $ex_rate_array = Common::getExchangeRate($val['CURRENCIES_ID'], $tran_array['generalledger_tday']);
                            self::$data['forexrates_id'] = $ex_rate_array['forexrates_id'];
                            $ex_rate = $ex_rate_array['forexrates_midrate'];

                            if ($val['DEBIT'] > 0):
                                self::$data['generalledger_fcamount'] = $val['DEBIT'];
                            endif;


                            if ($val['CREDIT'] > 0):
                                self::$data['generalledger_fcamount'] = $val['CREDIT'];
                            endif;
                        }else {

                            self::$data['forexrates_id'] = 0;
                            $ex_rate = 1;
                            self::$data['generalledger_fcamount'] = '0';
                            $ex_rate_array['forexrates_midrate'] = 1;
                        }


                        self::$data['generalledger_debit'] = ($val['DEBIT'] * $ex_rate);
                        self::$data['generalledger_credit'] = ($val['CREDIT'] * $ex_rate);
                        self::$data['currencies_id'] = $val['CURRENCIES_ID'];
                        self::$data['chartofaccounts_accountcode'] = $val['GLACC'];
                        self::$data['client_idno'] = $tran_array['client_idno'];
                        self::$data['generalledger_description'] = $val['DESC'];
                        self::$data['product_prodid'] = $val['PRODUCT_PRODID'];
                        break;

                    case TABLE_SAVACCOUNTS:

                        self::$data['client_idno'] = $tran_array['client_idno'];
                        self::$data['savaccounts_opendate'] = $tran_array['savaccounts_opendate'];
                        self::$data['product_prodid'] = $tran_array['product_prodid'];
                        self::$data['savaccounts_account'] = $tran_array['savaccounts_account'];

                        break;

                    case TABLE_SAVTRANSACTIONS:

                        switch ($tran_array['savtransactions_ttype']) {

                            case 'SW':
                                $tran_array['savtransactions_amount'] = $tran_array['savtransactions_amount'] * -1;
                                break;

                            default:
                                break;
                        }
                        // check if its an internal transfer
                        // use savings accounts that have been passed
                        if ($tran_array['transactiontypes_code'] == 'IT') {
                            self::$data['savaccounts_account'] = $val['SAVACC'];
                            self::$data['savtransactions_amount'] = $val['AMOUNT'];
                        } else {
                            self::$data['savaccounts_account'] = $tran_array['savaccounts_account'];
                            self::$data['savtransactions_amount'] = $tran_array['savtransactions_amount'];
                        }

                        self::$data['savtransactions_tday'] = $tran_array['savtransactions_tday'];
                        self::$data['product_prodid'] = $tran_array['product_prodid'];
                        self::$data['transactioncode'] = $tran_array['transactioncode'];
                        self::$data['transactiontypes_code'] = $tran_array['transactiontypes_code'];
                        self::$data['cheqs_no'] = $tran_array['cheqs_no'];
                        self::$data['members_idno'] = $tran_array['member_id'];
                        self::$data['user_id'] = $_SESSION['user_id'];
                        self::$data['savtransactions_voucher'] = $tran_array['voucher'];

                        break;

                    default:
                        break;
                }


                // CHECK SEE IF WE ARE USIN DYNAMIC SQL
                if (isset($data_array)){
                
                    if ($data_array['FORMDATA']['action'] == 'update') {
                        self::$Conn->ReferenceFieldList[$table] = array(self::$Conn->keyFields[$table] => $data_array['FORMDATA']['theid']); // where clause
                        self::$Conn->SQLUpdate(array($table => self::$data), true);
                    }
                } else {
                    self::$Conn->setAutoCommit($commitchanges);
                    self::$Conn->SQLInsert(array($table => self::$data));
                }

                unset(Bussiness::$sSQL[$table]);

                if (self::$isBulkInsert):

                    $parameters['code'] = 'DYNAMICSQL';
                    $parameters['dynamicsqluuid'] = self::$data['xmltrans_dynamicsqlid'];
                    self::$Conn->sp_call($parameters, '');

                endif;
            }
        } catch (Exception $e) {
            self::$Conn->cancelTransaction();
            Common::$lablearray['E01'] = $e->getMessage();
            throw new Exception($e->getMessage());
        }
    }

}
