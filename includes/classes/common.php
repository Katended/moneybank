<?php
class Common {

    public static $connObj;
    public static $lablearray = array();
    public static $error = '';
    public static $xml = '';
    Static $xmlObj;
    public static $aLines = array();
    public static $errorlevel = '';
    public static $sav_accounts_array = array();
    public static $sav_products_array = array();
    public static $accounts_array = array();

    //uniqid � Generate a unique ID
    public static function uniqidReal($lenght = 13) {
        // uniqid gives 13 chars, but you could adjust it to your needs.
        if (function_exists("random_bytes")) {
            $bytes = random_bytes(ceil($lenght / 2));
        } elseif (function_exists("openssl_random_pseudo_bytes")) {
            $bytes = openssl_random_pseudo_bytes(ceil($lenght / 2));
        } else {
            throw new Exception("no cryptographically secure random function available");
        }
        return substr(bin2hex($bytes), 0, $lenght);
    }

     public static function createResponse($status, $message='', $table = [],$data = null) {
    //     // Validate status
        if (!in_array(strtoupper($status), ['ERR','WAR','INFO','MSG','SQLSTATE','OK','DATA','DATA','FORM'])) {
            throw new InvalidArgumentException('Status must be "success" or "failure".');
        }
    
        // Create the response array
        $response = [
            "status" => $status,
            "message" =>($status=='OK'?'Information Saved':$message),
            "table" => $table,
            "data" => $data
        ];
        
        $jason =json_encode($response);

        $jason = str_replace("\\\\", '', $jason);
        
        return $jason;
     }

    # function checks if date is a MySQl date
    public static function validateMySQLDate($date ='') {
        try {

            $bresults = true;

            if (strpos($date, '-') !== false):
                $dt = new DateTime(trim($date));
            else:
                $bresults = false;
            endif;
        } catch (Exception $e) {
            return false;
        }
        return $bresults;
    }

    public static function getAllSavingsDetails($clientid = '') {

        self::prepareParameters($parameters, 'branch_code', '');
        self::prepareParameters($parameters, 'client_idno', $clientid);
        self::prepareParameters($parameters, 'product_prodid', '');
        self::prepareParameters($parameters, 'code', 'SAVACCOUNTDETAILS');

        $results = self::common_sp_call(serialize($parameters), false, self::$connObj);

        return $results;
    }

    # function removes slashes from string and replaces them with an underscore

    public static function replace_string($subject, $search = '/', $replacement = '_') {
        $string = str_replace($search, $replacement, $subject);
        return $string;
    }

    # function removes underscores from string and replaces them with a slashes

    public static function replaces_hyhpen($subject) {

        return self::replace_string($subject, '/-/', '/'); // preg_replace('/-/', '/' , $stringvalue);
    }

    # function removes underscores from string and replaces them with a slashes

    public static function replaces_underscores($stringvalue) {


        if (trim($stringvalue) == 'undefined'):
            return '';
        else:
            return self::replace_string($stringvalue, '_', '/');
        endif;
    }

    public static function checkHolidays($dDate = '') {

        $results_array = self::$connObj->SQLSelect("SELECT publicholidays_date FROM publicholidays WHERE publicholidays_date='" . $dDate . "'");
        // $results_array = self::$connObj->SQLSelect("SELECT publicholidays_date FROM publicholidays");
        return (is_null($results_array) ? false : true);
    }

    public static function getNumberofDaysInMonth($month, $Year) {

        return cal_days_in_month(CAL_GREGORIAN, $month, $Year);
    }

//      public static function defineCosntants($module='') {
//          
//        switch($module):
//    
//        case 'T':
//            if(!define('TABLE_TDEPOSIT')) define('TABLE_TDEPOSIT', 'timedeposit');
//            if(!define('TABLE_TDEPOSITTRANS')) define('TABLE_TDEPOSITTRANS', 'timedeposittrans');
//            break;
//        default:
//            break;
//        endswitch;
//      }  

    /* DESCRIPTION:  THIS FUNCTION IS USED TO GET ALL BANK ACOUNT DETAILS */
    public static function getBankDetails() {

        $parameters = array();
        self::prepareParameters($parameters, 'theid1', '');
        self::prepareParameters($parameters, 'theid2', '');
        self::prepareParameters($parameters, 'code', 'IDEXISTS');
        self::prepareParameters($parameters, 'idtype', 'BANKDETAILS');
        self::prepareParameters($parameters, 'branch_code', '');
        $banks_array = self::common_sp_call(serialize($parameters), '', Self::$connObj, false);

        return $banks_array;
    }

    /* DESCRIPTION:  THIS FUNCTION IS USED VARIDATE A GREGORIAN DATE
     * AND FIND OUT IF IT IS IN THE SET SYSTEM FORMAT
     *   $tdate: The Date
     * //bool checkdate ( int $month , int $day , int $year )      
     */

    public static function getParamValue($param, $prodid) {

        $result_array = self::$connObj->SQLSelect("SELECT COALESCE(productconfig_value,0) val FROM " . TABLE_PRODUCTCONFIG . " WHERE  productconfig_paramname='" . $param . "' AND product_prodid='" . $prodid . "'");

        return $result_array;
    }

    /* DESCRIPTION:  THIS FUNCTION IS USED VARIDATE A GREGORIAN DATE
     * AND FIND OUT IF IT IS IN THE SET SYSTEM FORMAT
     *   $tdate: The Date
     * //bool checkdate ( int $month , int $day , int $year )      
     */

    public static function checkDate($tdate) {

        $tdate_array = explode('/', $tdate);

        if (count($tdate_array) == 3) {
            switch (SETTING_DATE_FORMAT) {

                case 'MM/DD/YYYY':
                case 'm/d/Y':
                    return checkdate($tdate_array[0], $tdate_array[1], $tdate_array[2]);
                    break;

                case 'DD/MM/YYYY':
                case 'dd/mm/YYYY':
                    return checkdate($tdate_array[1], $tdate_array[0], $tdate_array[2]);
                    break;

                case 'MM/DD/YYYY':
                    return checkdate($tdate_array[0], $tdate_array[1], $tdate_array[2]);
                    break;

                case 'YYYY/MM/DD':
                    return checkdate($tdate_array[1], $tdate_array[2], $tdate_array[0]);
                    break;

                default:
                    return false;
                    break;
            }
        } else {
            return false;
        }
    }

    /* DESCRIPTION:  THIS FUNCTION IS USED PREPARE  A CURRENCY DENOMINATION FOR A PARTICULAR PRODUCT CURRENCY 

     */

    public static function displayDenominations($product_prodid = '') {
        $denom_array = self::$connObj->SQLSelect("SELECT currencydeno_deno deno,(SELECT currencies_code from " . TABLE_CURRENCIES . " c WHERE c.currencies_id=cd.currencies_id) ccode FROM " . TABLE_CURRENCYDENO . " cd ," . TABLE_PRODUCTCONFIG . " pc WHERE cd.currencies_id=pc.productconfig_value  AND pc.productconfig_paramname='CURRENCIES_ID' AND product_prodid='" . $product_prodid . "'");
        $deno = "<script>"
                . "$('.qty1').keyup(function() {
                    var vsum =0;
                    $('.qty1').each(function(){
                        var deno = $(this).attr('id');   
                        if(!isNaN(deno)){
                            var cur = parseFloat($(this).val()*deno);
                            vsum = vsum + cur;

                        }
                    });

            
                  $('.total').val(vsum);
                                
                });</script><fieldset><legend>" . Common::$lablearray['1694'] . "</legend><table cellpadding='0' width='100%' cellspacing='0'>";
        foreach ($denom_array AS $dkey => $dval):

            $deno .= "<tr><td align=right>" . $dval['ccode'] . " " . $dval['deno'] . " x </td><td align=right><input class='qty1' id='" . $dval['deno'] . "' name='" . $dval['deno'] . "' value=0></td></tr>";

        endforeach;

        $deno .= "</table></fieldset>";

        return $deno;
    }

    /* DESCRIPTION:  THIS FUNCTION IS USED PREPARE  TRANSACTIONS FOR XML CONVERTION 
     *   $formdata: 3 Array eg $formdata[]=array()
     * $trantype; Transaction type  
     *  NOTE: THIS FUNCTION WILL BE REPALCED BY Loan:updateLoan()
     * 
     */

    public static function prepareTransForXML(&$formdata, $trantype) {

        try {


            self::$aLines = array();

            self::getlables("1407,1493,139,67,1404,1205,311,1144,1145,1105,1181,139,1205,1205", "", "", self::$connObj);

            $nTotal = 0;

            foreach ($formdata as $str => &$value) {

                switch ($trantype) {

                    case 'RFLD': // REFINANCE
                        $loanpay = array();

                        $value['ACTION'] = $trantype;
                        $loanpay[] = array('TCODE' => $value['TCODE'], 'PRI' => $value['PRI'], 'INT' => $value['INT'], 'COM' => $value['COM'], 'PEN' => $value['PEN'], 'VAT' => $value['VAT'], 'TABLE' => TABLE_LOANPAYMENTS, 'DATE' => $value['DATE'], 'SAVACC' => $value['SAVACC'], 'DESC' => $value['DESC'], 'PRODUCT_PRODID' => $value['PRODUCT_PRODID'], 'VOUCHER' => $value['VOUCHER'], 'CHEQNO' => $value['CHEQNO'], 'MEMID' => $value['MEMID'], 'TCODE' => $value['TCODE'], 'LNR' => $value['LNR'], 'MODE' => $value['MODE'], 'CCODE' => $value['CCODE'], 'ACTION' => 'RFLD');
                        Bussiness::covertArrayToXML($loanpay, false);

                        //LOAN DUES
                        $loan_dues = $value['LOANDUES'];

                        $nCount = 1;

                        foreach ($loan_dues as $key => &$val) {
                            self::replace_key_function($loan_dues[$key], 'date', 'DATE');
                            $loan_dues[$key]['DATE'] = self::changeDateFromPageToMySQLFormat($loan_dues[$key]['DATE']);
                            $loan_dues[$key]['LNR'] = $value['LNR'];
                            $loan_dues[$key]['ID'] = $nCount;
                            $loan_dues[$key]['TABLE'] = TABLE_DUES;
                            $nCount++;
                        }

                        Bussiness::covertArrayToXML($loan_dues, false);

                        //DISBURSEMENT
                        // sleep(1);

                        $value['DATE'] = Common::changeDateFromPageToMySQLFormat($value['ODATE'], true, 2);
                        $originalamount = $value['AMOUNT'];
                        $value['AMOUNT'] = $value['TOPUP'] + $value['PRI'];
                        $value['TABLE'] = TABLE_DISBURSEMENTS;
                        Bussiness::covertArrayToXML(array($value), false);

                        $value['AMOUNT'] = $value['TOPUP'];

                        //REFINANCED
                        $value['TABLE'] = TABLE_REFINANCED;
                        Bussiness::covertArrayToXML(array($value), false);

                        // LOAN STATUS                      
                        $value['LSTATUS'] = 'RF';
                        $value['TABLE'] = TABLE_LOANSTATUSLOG;
                        Bussiness::covertArrayToXML(array($value), false);

                        // WE RETURN AT THIS POINT BECAUSE WE DONT WANT TO DIBURSE AT THIS POINT
                        // GOTA APPROVE THE TOPUP FIRST
                        return;

                        // GENERAL LEDGER                   
                        //Principal 
                        if ($value['TOPUP'] > 0) {
                            self::$aLines[] = array('TCODE' => $value['TCODE'], 'AMOUNT' => $value['TOPUP'], 'TTYPE' => 'LD', 'PRODUCT_PRODID' => $value['LPRODID'], 'GLACC' => '', 'TRANCODE' => 'LD000', 'SIDE' => 'DR', 'DESC' => self::$lablearray["1493"] . ' ' . $value['LNR'], 'DATE' => $value['DATE']);
                        }

                        //stationery 
                        if ($value['COMM'] > 0) {
                            self::$aLines[] = array('TCODE' => $value['TCODE'], 'AMOUNT' => $value['COMM'], 'TTYPE' => 'STA', 'PRODUCT_PRODID' => $value['LPRODID'], 'GLACC' => '', 'TRANCODE' => 'LN000', 'SIDE' => 'CR', 'DESC' => self::$lablearray["1230"] . ' ' . $value['LNR'], 'DATE' => $value['DATE']);
                            $value['TOPUP'] -= $value['COMM'];
                        }

                        //Commission 
                        if ($value['STAT'] > 0) {
                            self::$aLines[] = array('TCODE' => $value['TCODE'], 'AMOUNT' => $value['STAT'], 'TTYPE' => 'COM', 'PRODUCT_PRODID' => $value['LPRODID'], 'GLACC' => '', 'TRANCODE' => 'SC000', 'SIDE' => 'CR', 'DESC' => self::$lablearray["1105"] . ' ' . $value['LNR'], 'DATE' => $value['DATE']);
                            $value['TOPUP'] -= $value['STAT'];
                        }

                        foreach (self::$aLines as $key => $val) {
                            self::$aLines[$key]['DATE'] = $value['DATE'];
                            self::$aLines[$key]['CLIENTIDNO'] = $value['CLIENTIDNO'];
                            self::$aLines[$key]['CTYPE'] = $value['CTYPE'];
                            self::$aLines[$key]['BRANCHCODE'] = $value['BRANCHCODE'];
                            self::$aLines[$key]['TCODE'] = $value['TCODE'];
                            self::$aLines[$key]['FUNDCODE'] = $value['FUNDCODE'];
                            self::$aLines[$key]['DONORCODE'] = $value['DONORCODE'];
                            self::$aLines[$key]['TABLE'] = TABLE_GENERALLEDGER;
                        }

                        $nTotal = $value['TOPUP'];

                        $cashside = 'CR';
                        
                    case 'RF': // REFINANCE
//                        $loanpay = array();
//
//                        $value['ACTION'] = $trantype;
                        //   $loanpay[] = array('TCODE' => $value['TCODE'], 'PRI' => $value['PRI'], 'INT' => $value['INT'], 'COM' => $value['COM'], 'PEN' => $value['PEN'], 'VAT' => $value['VAT'], 'TABLE' => TABLE_LOANPAYMENTS, 'DATE' =>$value['DATE'], 'SAVACC' => $value['SAVACC'], 'DESC' => $value['DESC'], 'PRODUCT_PRODID' => $value['PRODUCT_PRODID'], 'VOUCHER' => $value['VOUCHER'], 'CHEQNO' => $value['CHEQNO'], 'MEMID' => $value['MEMID'], 'TCODE' => $value['TCODE'], 'LNR' => $value['LNR'], 'MODE' => $value['MODE'], 'CCODE' => $value['CCODE'],'ACTION'=>$trantype,'STATUS' =>'RF');
                        // Bussiness::covertArrayToXML($loanpay, false);
                        //LOAN DUES
//                        $loan_dues = $value['LOANDUES'];
//                        
//                        $nCount =1;
//                        
//                        foreach ($loan_dues as $key => &$val) {
//                            self::replace_key_function($loan_dues[$key], 'date','DATE'); 
//                                       
//                            $loan_dues[$key]['DATE'] = self::changeDateFromPageToMySQLFormat($loan_dues[$key]['DATE']);
//                            $loan_dues[$key]['LNR'] = $value['LNR'];
//                            $loan_dues[$key]['ID'] = $nCount;
//                            $loan_dues[$key]['STATUS'] = 'RF';
//                            $loan_dues[$key]['TABLE'] = TABLE_DUES;
//                            $nCount++;
//                        }
//                    
//                        Bussiness::covertArrayToXML($loan_dues, false);
                        //DISBURSEMENT
                        // sleep(1);
//                        $value['DATE'] = Common::changeDateFromPageToMySQLFormat($value['ODATE'],true,2);
                        $originalamount = $value['AMOUNT'];
//                        $value['AMOUNT'] = $value['TOPUP'] + $value['PRI'];
                        //  $value['TABLE'] = TABLE_DISBURSEMENTS;                       
                        // Bussiness::covertArrayToXML(array($value), false);

                        $value['AMOUNT'] = $value['TOPUP'];

                        //REFINANCED
                        $value['TABLE'] = TABLE_REFINANCED;
                        Bussiness::covertArrayToXML(array($value), false);

                        // LOAN STATUS 
                        $value['LSTATUS'] = 'RF';
                        $value['TABLE'] = TABLE_LOANSTATUSLOG;
                        Bussiness::covertArrayToXML(array($value), false);

                        // WE RETURN AT THIS POINT BECAUSE WE DONT WANT TO DIBURSE AT THIS POINT
                        // GOTA APPROVE THE TOPUP FIRST
                        return;

                        // GENERAL LEDGER                   
                        //Principal 
//                        if ($value['TOPUP'] > 0) {
//                            self::$aLines[] = array('TCODE' => $value['TCODE'], 'AMOUNT' =>$value['TOPUP'], 'TTYPE' => 'LD', 'PRODUCT_PRODID' => $value['LPRODID'], 'GLACC' => '', 'TRANCODE' => 'LD000', 'SIDE' => 'DR', 'DESC' => self::$lablearray["1493"] . ' ' . $value['LNR'],'DATE' =>$value['DATE']);
//                        }
//
//                        //stationery 
//                        if ($value['COMM'] > 0) {
//                            self::$aLines[] = array('TCODE' => $value['TCODE'], 'AMOUNT' => $value['COMM'], 'TTYPE' => 'STA', 'PRODUCT_PRODID' => $value['LPRODID'], 'GLACC' => '', 'TRANCODE' => 'LN000', 'SIDE' => 'CR', 'DESC' => self::$lablearray["1230"] . ' ' . $value['LNR'],'DATE' =>$value['DATE']);
//                            $value['TOPUP'] -= $value['COMM'];
//                        }
//
//                        //Commission 
//                        if ($value['STAT'] > 0) {
//                            self::$aLines[] = array('TCODE' => $value['TCODE'], 'AMOUNT' => $value['STAT'], 'TTYPE' => 'COM', 'PRODUCT_PRODID' => $value['LPRODID'], 'GLACC' => '', 'TRANCODE' => 'SC000', 'SIDE' => 'CR', 'DESC' => self::$lablearray["1105"] . ' ' . $value['LNR'],'DATE' =>$value['DATE']);
//                            $value['TOPUP'] -= $value['STAT'];
//                        }
//
//                        foreach (self::$aLines as $key => $val) {
//                            self::$aLines[$key]['DATE'] = $value['DATE'];
//                            self::$aLines[$key]['CLIENTIDNO'] = $value['CLIENTIDNO'];
//                            self::$aLines[$key]['CTYPE'] = $value['CTYPE'];
//                            self::$aLines[$key]['BRANCHCODE'] = $value['BRANCHCODE'];
//                            self::$aLines[$key]['TCODE'] = $value['TCODE'];
//                            self::$aLines[$key]['FUNDCODE'] = $value['FUNDCODE'];
//                            self::$aLines[$key]['DONORCODE'] = $value['DONORCODE'];
//                            self::$aLines[$key]['TABLE'] = TABLE_GENERALLEDGER;
//                        }                        
//
//                        $nTotal = $value['TOPUP'];
//                        
//                        $cashside ='CR';
                        break;

                    case 'LR':

                        $loanpay = array();

                        $loanpay[] = array('TCODE' => $value['TCODE'], 'PRI' => $value['PRI'], 'INT' => $value['INT'], 'COM' => $value['COM'], 'PEN' => $value['PEN'], 'VAT' => $value['VAT'], 'TABLE' => TABLE_LOANPAYMENTS, 'DATE' => $value['DATE'], 'SAVACC' => $value['SAVACC'], 'DESC' => $value['DESC'], 'PRODUCT_PRODID' => $value['PRODUCT_PRODID'], 'VOUCHER' => $value['VOUCHER'], 'CHEQNO' => $value['CHEQNO'], 'MEMID' => $value['MEMID'], 'TCODE' => $value['TCODE'], 'LNR' => $value['LNR'], 'MODE' => $value['MODE'], 'CCODE' => $value['CCODE']);
                        Bussiness::covertArrayToXML($loanpay, false);


                        // Principal
                        if ($value['PRI'] > 0) {
                            $nTotal = $nTotal + $value['PRI'];
                            self::$aLines[] = array('TTYPE' => 'PRI', 'AMOUNT' => $value['PRI'], 'PRODUCT_PRODID' => $value['PRODUCT_PRODID'], 'CTYPE' => $ctype, 'GLACC' => '', 'TRANCODE' => 'LN002', 'BANKID' => $value['BANKBRANCHID'], 'TABLE' => TABLE_GENERALLEDGER, 'DESC' => self::$lablearray['1144'], 'SIDE' => 'CR', 'BRANCHCODE' => $value['BRANCHCODE'], 'FUNDCODE' => $value['FUNDCODE'], 'DONORCODE' => $value['DONORCODE'], 'CLIENTIDNO' => $value['CLIENTIDNO'], 'TCODE' => $value['TCODE'], 'CTYPE' => $value['CTYPE'], 'DATE' => $value['DATE'], 'CCODE' => $value['CCODE']);
                        }

                        // Interest
                        if ($value['INT'] > 0) {
                            $nTotal = $nTotal + $value['INT'];
                            self::$aLines[] = array('TTYPE' => 'INT', 'AMOUNT' => $value['INT'], 'PRODUCT_PRODID' => $value['PRODUCT_PRODID'], 'CTYPE' => $ctype, 'GLACC' => '', 'TRANCODE' => 'IL001', 'BANKID' => $value['BANKBRANCHID'], 'TABLE' => TABLE_GENERALLEDGER, 'DESC' => self::$lablearray['1145'], 'SIDE' => 'CR', 'BRANCHCODE' => $value['BRANCHCODE'], 'FUNDCODE' => $value['FUNDCODE'], 'DONORCODE' => $value['DONORCODE'], 'CLIENTIDNO' => $value['CLIENTIDNO'], 'TCODE' => $value['TCODE'], 'CTYPE' => $value['CTYPE'], 'DATE' => $value['DATE'], 'CCODE' => $value['CCODE']);
                        }

                        // Commission
                        if ($value['COM'] > 0) {
                            $nTotal = $nTotal + $value['COM'];
                            self::$aLines[] = array('TTYPE' => 'COM', 'AMOUNT' => $value['COM'], 'PRODUCT_PRODID' => $value['PRODUCT_PRODID'], 'CTYPE' => $ctype, 'GLACC' => '', 'TRANCODE' => 'LN000', 'BANKID' => $value['BANKBRANCHID'], 'TABLE' => TABLE_GENERALLEDGER, 'DESC' => self::$lablearray['1105'], 'SIDE' => 'CR', 'BRANCHCODE' => $value['BRANCHCODE'], 'FUNDCODE' => $value['FUNDCODE'], 'DONORCODE' => $value['DONORCODE'], 'CLIENTIDNO' => $value['CLIENTIDNO'], 'TCODE' => $value['TCODE'], 'CTYPE' => $value['CTYPE'], 'DATE' => $value['DATE'], 'CCODE' => $value['CCODE']);
                        }

                        // Penalty
                        if ($value['PEN'] > 0) {
                            $nTotal = $nTotal + $value['PEN'];
                            self::$aLines[] = array('TTYPE' => 'PEN', 'AMOUNT' => $value['PEN'], 'PRODUCT_PRODID' => $value['PRODUCT_PRODID'], 'CTYPE' => $ctype, 'GLACC' => '', 'TRANCODE' => 'LP000', 'BANKID' => $value['BANKBRANCHID'], 'TABLE' => TABLE_GENERALLEDGER, 'DESC' => self::$lablearray['1181'], 'SIDE' => 'CR', 'BRANCHCODE' => $value['BRANCHCODE'], 'FUNDCODE' => $value['FUNDCODE'], 'DONORCODE' => $value['DONORCODE'], 'CLIENTIDNO' => $value['CLIENTIDNO'], 'TCODE' => $value['TCODE'], 'CTYPE' => $value['CTYPE'], 'DATE' => $value['DATE'], 'CCODE' => $value['CCODE']);
                        }

                        // Vat
                        if ($value['VAT'] > 0) {
                            $nTotal = $nTotal + $value['VAT'];
                            self::$aLines[] = array('TTYPE' => 'VAT', 'AMOUNT' => $value['VAT'], 'PRODUCT_PRODID' => $value['PRODUCT_PRODID'], 'CTYPE' => $ctype, 'GLACC' => '', 'TRANCODE' => 'VO000', 'BANKID' => $value['BANKBRANCHID'], 'TABLE' => TABLE_GENERALLEDGER, 'DESC' => self::$lablearray['1404'], 'SIDE' => 'CR', 'BRANCHCODE' => $value['BRANCHCODE'], 'FUNDCODE' => $value['FUNDCODE'], 'DONORCODE' => $value['DONORCODE'], 'CLIENTIDNO' => $value['CLIENTIDNO'], 'TCODE' => $value['TCODE'], 'CTYPE' => $value['CTYPE'], 'DATE' => $value['DATE'], 'CCODE' => $value['CCODE']);
                        }

                        // Service Fee
                        if ($value['CHARGEFEE'] == 'Y') {
                            if ($value['SFEE'] > 0) {
                                $nTotal = $nTotal + $value['SFEE'];
                                self::$aLines[] = array('TTYPE' => 'SFEE', 'AMOUNT' => $value['SFEE'], 'PRODUCT_PRODID' => $value['PRODUCT_PRODID'], 'CTYPE' => $ctype, 'GLACC' => $value['SFEEACC'], 'TRANCODE' => 'SF000', 'BANKID' => $value['BANKBRANCHID'], 'TABLE' => TABLE_GENERALLEDGER, 'DESC' => self::$lablearray['1504'], 'SIDE' => 'CR', 'BRANCHCODE' => $value['BRANCHCODE'], 'FUNDCODE' => $value['FUNDCODE'], 'DONORCODE' => $value['DONORCODE'], 'CLIENTIDNO' => $value['CLIENTIDNO'], 'TCODE' => $value['TCODE'], 'CTYPE' => $value['CTYPE'], 'DATE' => $value['DATE'], 'CCODE' => $value['CCODE']);
                            }
                        }

                        if ($value['MODE'] == "SA") {
                            // self::$aLines[] = array('TTYPE' => 'LR', 'AMOUNT' => $value['AMOUNT'], 'PRODUCT_PRODID' => $value['PRODUCT_PRODID'], 'CTYPE' => $ctype, 'GLACC' => $value['CASHGL'], 'TRANCODE' => 'CC002', 'BANKID' => $value['BANKBRANCHID'], 'TABLE' => TABLE_GENERALLEDGER, 'DESC' => self::$lablearray['1205'], 'SIDE' => 'DR', 'BRANCHCODE' => $value['BRANCHCODE'], 'FUNDCODE' => $value['FUNDCODE'], 'DONORCODE' => $value['DONORCODE'], 'CLIENTIDNO' => $value['CLIENTIDNO'], 'TCODE' => $value['TCODE']);
                            // check  savings balances
                            self::prepareParameters($parameters, 'branch_code', '');
                            self::prepareParameters($parameters, 'client_idno', $value['CLIENTIDNO']);
                            self::prepareParameters($parameters, 'product_prodid', $value['SAVPROD']);
                            self::prepareParameters($parameters, 'code', 'SAVACCOUNTDETAILS');

                            $results = self::common_sp_call(serialize($parameters), false, self::$connObj);

                            // TO DO: Add use case of Overdrafts
                            if ($results[0]['balance'] < $nTotal) {
                                self::removeElementWithValue(self::$aLines, 'CLIENTIDNO', $value['CLIENTIDNO']);
                                self::$error = "WAR." . self::$lablearray['1407'];
                                self::$errorlevel = 'INFO';
                                continue 2; // if we do not have sufficient funds continue witht he next element
                            }

                            $sav[] = array();
                            unset($sav);
                            $sav[] = array('TCODE' => $value['TCODE'], 'TABLE' => TABLE_SAVTRANSACTIONS, 'DATE' => $value['DATE'], 'SAVACC' => $value['SAVACC'], 'DESC' => self::$lablearray['1205'], 'PRODUCT_PRODID' => $value['SAVPROD'], 'VOUCHER' => $value['VOUCHER'], 'CHEQNO' => $value['CHEQNO'], 'MEMID' => $value['MEMID'], 'AMOUNT' => -1 * $nTotal, 'TCODE' => $value['TCODE'], 'MODE' => 'LR-SA', 'CLIENTIDNO' => $value['CLIENTIDNO']);

                            Bussiness::covertArrayToXML($sav, false);
                        }

                        break;
                    default:
                        break;
                }



                if ($value['MODE'] == 'SA') {

                    self::$aLines[] = array('TTYPE' => 'LR-SA', 'AMOUNT' => $nTotal,
                        'PRODUCT_PRODID' => ($value['SAVPROD'] = '' ? $value['SAVPROD'] : $value['SPRODID']),
                        'CTYPE' => $value['CTYPE'],
                        'TABLE' => TABLE_GENERALLEDGER,
                        'DATE' => $value['DATE'],
                        'GLACC' => '', 'TRANCODE' => 'SW000',
                        'BANKID' => $value['BANKBRANCHID'],
                        'DESC' => self::$lablearray['1205'], 'SIDE' => "DR",
                        "SAVACC" => $value['SAVACC'], 'BRANCHCODE' => $value['BRANCHCODE'], 'FUNDCODE' => $value['FUNDCODE'], 'DONORCODE' => $value['DONORCODE'], 'CLIENTIDNO' => $value['CLIENTIDNO'], 'TCODE' => $value['TCODE'], 'CCODE' => $value['CCODE']);
                    $nTotal = 0;
                }
            }


            // cheque
            if ($value['MODE'] == 'CQ' || $value['MODE'] == 'DB') {

                $lablearray['1205'] = self::$lablearray['67'] . ' ' . $value['CHEQNO'];

                if ($value['MODE'] == 'CQ') {

                    $cheq[] = array();
                    unset($sav);
                    $cheq[] = array('TCODE' => $value['TCODE'], 'TABLE' => TABLE_CHEQS, 'DATE' => $value['DATE'], 'SAVACC' => $value['SAVACC'], 'DESC' => $value['DESC'], 'PRODUCT_PRODID' => $value['PRODUCT_PRODID'], 'VOUCHER' => $value['VOUCHER'], 'CHEQNO' => $value['CHEQNO'], 'MEMID' => $value['MEMID'], 'AMOUNT' => $nTotal, 'TCODE' => $value['TCODE']);
                    Bussiness::covertArrayToXML($cheq, false);
                    self::$aLines[] = array('TTYPE' => 'SP', 'TABLE' => TABLE_GENERALLEDGER, 'AMOUNT' => $nTotal, 'PRODUCT_PRODID' => $value['PRODUCT_PRODID'], 'CTYPE' => $ctype, 'GLACC' => $value['CASHGL'], 'TRANCODE' => 'CC002', 'BANKID' => $value['BANKBRANCHID'], 'TABLE' => TABLE_GENERALLEDGER, 'DESC' => self::$lablearray['139'] . ' ' . $value['cheques_no'], 'SIDE' => 'DR', 'BRANCHCODE' => $value['BRANCHCODE'], 'FUNDCODE' => $value['FUNDCODE'], 'DONORCODE' => $value['DONORCODE'], 'CLIENTIDNO' => $value['CLIENTIDNO'], 'TCODE' => $value['TCODE'], 'CTYPE' => $value['CTYPE'], 'DATE' => $value['DATE'], 'CCODE' => $value['CCODE']);
                } else {
                    self::$aLines[] = array('TTYPE' => 'DB', 'TABLE' => TABLE_GENERALLEDGER, 'AMOUNT' => $nTotal, 'PRODUCT_PRODID' => $value['PRODUCT_PRODID'], 'CTYPE' => $ctype, 'GLACC' => $value['CASHGL'], 'TRANCODE' => 'CC002', 'BANKID' => $value['BANKBRANCHID'], 'TABLE' => TABLE_GENERALLEDGER, 'DESC' => self::$lablearray['139'] . ' ' . $value['cheques_no'], 'SIDE' => 'DR', 'BRANCHCODE' => $value['BRANCHCODE'], 'FUNDCODE' => $value['FUNDCODE'], 'DONORCODE' => $value['DONORCODE'], 'CLIENTIDNO' => $value['CLIENTIDNO'], 'TCODE' => $value['TCODE'], 'CTYPE' => $value['CTYPE'], 'DATE' => $value['DATE'], 'CCODE' => $value['CCODE']);
                }
            } elseif ($value['MODE'] == 'CA') {
                if ($cashside == "") {

                    $cashside = "DR";
                }

                self::$aLines[] = array('TTYPE' => $value['MODE'], 'AMOUNT' => $nTotal,
                    'PRODUCT_PRODID' => $value['PRODUCT_PRODID'],
                    'CTYPE' => $value['CTYPE'],
                    'TABLE' => TABLE_GENERALLEDGER,
                    'DATE' => $value['DATE'],
                    'CLIENTIDNO' => $value['CLIENTIDNO'],
                    'GLACC' => (isset($value['GLACC']) ? $value['GLACC'] : $value['CASHGL']), 'TRANCODE' => 'LS000',
                    'BANKID' => $value['BANKBRANCHID'],
                    'DESC' => self::$lablearray['311'], 'SIDE' => $cashside,
                    "SAVACC" => ($value['MODE'] == "SA" ? substr($value['SAVACC'], 0, strrpos($value['SAVACC'], ":") - 1) : ""), 'BRANCHCODE' => $value['BRANCHCODE'], 'FUNDCODE' => $value['FUNDCODE'], 'DONORCODE' => $value['DONORCODE'], 'CLIENTIDNO' => $value['CLIENTIDNO'], 'TCODE' => $value['TCODE'], 'CTYPE' => $value['CTYPE'], 'CCODE' => $value['CCODE']);
            }

            // check see if we have any transactions to deal with
            if (count(self::$aLines) > 0) {
                reset(self::$aLines);
                self::returnTransactionOptions(self::$aLines, self::$connObj);
            }

            if (self::$error != "" && self::$errorlevel == "" || (self::$lablearray['E01'] != "")) {
                echo "ERR" . self::$error . ' ' . self::$lablearray['E01'];
                exit();
            }

            if (count(self::$aLines) > 0) {
                Bussiness::covertArrayToXML(self::$aLines, true);
                self::$aLines = array();
            }
        } catch (Exception $e) {
            Self::$lablearray['E01'] = $e->getMessage();
        }
    }

    /* DESCRIPTION:  THIS FUNCTION IS USED REMOVE AN ELEMENT  WITH A VALUE
     * PARAMETERS:
     * $array - The Array 
     * $key - The Key
     * $value - The Value
     */

    public static function removeElementWithValue(&$array, $key, $value) {
        foreach ($array as $subKey => $subArray) {
            if ($subArray[$key] == $value) {
                unset($array[$subKey]);
            }
        }
        return $array;
    }

    /* DESCRIPTION:  THIS FUNCTION IS USED VALIDATE A TRANSACTION
     * PARAMETERS:
     * $myarraydata - The Array to validate
     * $ttype - Transaction type
     * NOTE:
     */

    public static function validateTransaction($myarraydata, $ttype, &$Conn) {

        self::getlables("1195,185,1196", "", "", $Conn);

        switch ($ttype) {

            case 'LOAN':
                if ($myarraydata['PAYMODES'] == "") {

                    echo "INFO." . self::$lablearray['1195'];
                    exit();
                }

                if ($myarraydata['PAYMODES'] == "CQ" && $myarraydata['cheques_no'] == '') {

                    echo "INFO." . self::$lablearray['185'];
                    exit();
                }

                if ($myarraydata['txtpayDate'] == "" || !isset($myarraydata['txtpayDate'])) {

                    echo "INFO." . self::$lablearray['1196'];
                    exit();
                }

                break;

            default:
                break;
        }
    }

    /* DESCRIPTION:  THIS FUNCTION IS USED CREATE AN EXML STRING FROM AN ARRAY
     * PARAMETERS:
     * $myarray - The Array
     * $myTable - The Table in which array willbe inserted
     * NOTE:
     */

    public static function covertArrayToXML($myarraydata, $bcloseXMLTags = true) {

        //  print_r($myarraydata);
        // $xmlstr = '<xml version="1.0" standalone="yes"></xml>';
        // check see if this object is already initialised

        try {
            if (!is_object(self::$xmlObj)) {
                self::$xmlObj = new SimpleXMLElement('<xml version="1.0" standalone="yes"></xml>');
            }

            //$record = $table->addChild('record', '');
            $prevtable = '';

            foreach ($myarraydata as $key => $value) {

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

                        $record->addAttribute('cid', ($value['CTYPE'] = 'M' ? $value['MID'] : $value['CLIENTIDNO']));

                        break;

                    case TABLE_CLIENTS:

                        $record->addAttribute('ctype', $value['CTYPE']);
                        $record->addAttribute('cid', $value['CLIENTIDNO']);
                        $record->addAttribute('sname', $value['SNAME']);
                        $record->addAttribute('fname', $value['FNAME']);
                        $record->addAttribute('mname', $value['MNAME']);
                        $record->addAttribute('gender', $value['GENDER']);
                        $record->addAttribute('email', $value['EMAIL']);
                        $record->addAttribute('pad', $value['PAD']);
                        $record->addAttribute('docid', $value['DOCID']);
                        $record->addAttribute('serial', $value['SERIAL']);
                        $record->addAttribute('docexp', $value['DOCEXP']);
                        $record->addAttribute('cat1', $value['CAT1']);
                        $record->addAttribute('cat2', $value['CAT2']);
                        $record->addAttribute('ccode', $value['CCODE']);
                        $record->addAttribute('edate', $value['EDATE']);
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

                        break;

                    case TABLE_ENTITY:

                        $record->addAttribute('gid', $value['CLIENTIDNO']);
                        $record->addAttribute('gname', $value['GNAME']);
                        $record->addAttribute('rdate', $value['RDATE']);
                        $record->addAttribute('postad', $value['POSTAD']);
                        $record->addAttribute('city', $value['CITY']);
                        $record->addAttribute('pad', $value['PAD']);
                        $record->addAttribute('tel1', $value['TEL1']);
                        $record->addAttribute('tel2', $value['TEL2']);
                        $record->addAttribute('ccode', $value['CCODE']);
                        $record->addAttribute('status', $value['STATUS']);
                        $record->addAttribute('acode', $value['ACODE']);
                        $record->addAttribute('brcode', $value['BRCODE']);

                        break;

                    case TABLE_MEMBERS:

                        $record->addAttribute('mid', $value['MID']);
                        $record->addAttribute('rdate', $value['RDATE']);
                        $record->addAttribute('edate', $value['EDATE']);
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
                        $record->addAttribute('memid', $value['MEMID']);

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
                        $record->addAttribute('cqno', $value['CHEQNO']);
                        $record->addAttribute('voucher', $value['VOUCHER']);
                        $record->addAttribute('period', $value['PERIOD']);
                        $record->addAttribute('matval', $value['MATVAL']);
                        $record->addAttribute('matdate', $value['MATDATE']);
                        $record->addAttribute('intcap', $value['INTCAP']);
                        $record->addAttribute('freq', $value['FREQ']);
                        $record->addAttribute('instype', $value['INSTYPE']);
                        $record->addAttribute('ointamt', $value['OINTAMT']);
                        $record->addAttribute('omatval', $value['OMATVAL']);
                        $record->addAttribute('memid', $value['MEMID']);

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
                        $record->addAttribute('memid', $value['MEMID']);
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
                        $record->addAttribute('amt', $value['LAMOUNT']);
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
                        $record->addAttribute('qno', $value['CHEQNO']);
                        $record->addAttribute('cash', $value['MODE']);
                        $record->addAttribute('cycle', $value['CYCLE']);
                        $record->addAttribute('memid', $value['MEMID']);
                        $record->addAttribute('stat', $value['STAT']);
                        $record->addAttribute('dtype', $value['DTYPE']);
                        $record->addAttribute('mode', $value['MODE']);
                        $record->addAttribute('uid', $_SESSION['user_id']);

                        break;

                    case TABLE_DUES:

                        $record->addAttribute('id', $value['ID']);
                        $record->addAttribute('lnr', $value['LNR']);
                        $record->addAttribute('princ', (isset($value['PRINC']) ? $value['PRINC'] : $value['principal']));
                        $record->addAttribute('int', (isset($value['INT']) ? $value['INT'] : $value['interest']));
                        $record->addAttribute('pen', (isset($value['PEN']) ? $value['PEN'] : $value['penalty']));
                        $record->addAttribute('comm', (isset($value['COMM']) ? $value['COMM'] : $value['commission']));
                        $record->addAttribute('date', (isset($value['DATE']) ? $value['DATE'] : $value['date']));
                        $record->addAttribute('memid', (isset($value['MEMID']) ? $value['MEMID'] : $value['memid']));
                        $record->addAttribute('vat', '0');

                        break;

                    case TABLE_LOANSTATUSLOG:

                        $record->addAttribute('lnr', $value['LNR']); // to be updated
                        $record->addAttribute('lstatus', $value['LSTATUS']);

                        $value['DATE'] = (isset($value['ADATE']) ? $value['ADATE'] : $value['DATE']);
                        $value['AMOUNT'] = (isset($value['LAMNT']) ? $value['LAMNT'] : $value['AMOUNT']);
                        $value['AMOUNT'] = (isset($value['TOPUP']) ? $value['TOPUP'] : $value['AMOUNT']);

                        $record->addAttribute('ldate', $value['DATE']); // to be updated

                        $record->addAttribute('amt', abs($value['AMOUNT']));

                        $record->addAttribute('uid', $value['USERID']);

                        break;

                    case TABLE_LOAN:

                        $value['CLIENTIDNO'] = (isset($value['CLIENTIDNO']) ? $value['CLIENTIDNO'] : $value['CCODE']);

                        $record->addAttribute('lnr', $value['LNR']);
                        $record->addAttribute('cid', $value['CLIENTIDNO']);
                        $record->addAttribute('lamt', $value['LAMNT']);
                        $record->addAttribute('fcode', (isset($value['FCODE']) ? $value['FCODE'] : '0000'));
                        $record->addAttribute('int', $value['INTRATE']);
                        $record->addAttribute('intamt', $value['INTAMNT']);
                        $record->addAttribute('uid', $value['USERID']);
                        $record->addAttribute('sdate', $value['START']);
                        $record->addAttribute('grace', $value['GRACE']);
                        $record->addAttribute('noofinst', $value['NINST']);
                        $record->addAttribute('lexp', $value['LEXP']);
                        $record->addAttribute('firstinst', $value['FIRSTINS']);
                        $record->addAttribute('udf1', (isset($value['UD1']) ? $value['UD1'] : '0000'));
                        $record->addAttribute('udf2', (isset($value['UD2']) ? $value['UD2'] : '0000'));
                        $record->addAttribute('udf3', (isset($value['UD3']) ? $value['UD3'] : '0000'));
                        $record->addAttribute('adate', $value['ADATE']);
                        $record->addAttribute('inttype', $value['INTTYPE']);
                        $record->addAttribute('insttype', $value['INSTYPE']);
                        $record->addAttribute('alsograce', $value['AGRACE']);
                        $record->addAttribute('intdays', $value['INTDAY']);
                        $record->addAttribute('intdeductedatdisb', $value['INTDIS']);
                        $record->addAttribute('prodid', $value['PRODUCT_PRODID']);
                        $record->addAttribute('dcode', (isset($value['DCODE']) ? $value['DCODE'] : '00000'));
                        $record->addAttribute('brcode', $value['BRANCHCODE']);
                        $record->addAttribute('memid', $value['MEMID']);
                        $record->addAttribute('intcgrace', $value['INTCG']);
                        $record->addAttribute('intfirst', $value['INTF']);
                        $record->addAttribute('lastinstpp', $value['LINS']);
                        $record->addAttribute('comm', $value['COMM']);
                        $record->addAttribute('freezed', $value['FREEZE']);
                        $record->addAttribute('expdisb', $value['EXDATE']);
                        $record->addAttribute('gracecompd', $value['GCOMP']);
                        $record->addAttribute('intindays', $value['INTIND']);
                        $record->addAttribute('pid', $value['LPD']);
                        $record->addAttribute('inupfront', $value['INTUPF']);

                        break;

                    case TABLE_LOANFEES:

                        $record->addAttribute('date', $value['DATE']);
                        $record->addAttribute('lnr', $value['LNR']);
                        $record->addAttribute('cid', $value['CLIENTIDNO']);
                        $record->addAttribute('tcode', $value['TCODE']);
                        $record->addAttribute('amt', $value['AMOUNT']);
                        $record->addAttribute('memid', $value['MEMID']);

                        break;

                    case TABLE_CHEQS:

                        $value['CQSTAT'] = (isset($value['CQSTAT']) ? $value['CQSTAT'] : 'Q' );
                        $value['CLIENTIDNO'] = (isset($value['CLIENTIDNO']) ? $value['CLIENTIDNO'] : '' );
                        $value['CQTYPE'] = (isset($value['CQTYPE']) ? $value['CQTYPE'] : '' );
                        $record->addAttribute('cqnr', $value['CHEQNO']);
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

                        $record->addAttribute('memid', $value['MEMID']);
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
                        $record->addAttribute('voucher', $value['VOUCHER']);
                        $record->addAttribute('uid', $_SESSION['user_id']);
                        $record->addAttribute('glacc', $value['GLACC']);
                        $record->addAttribute('brcode', $value['BRANCHCODE']);
                        $record->addAttribute('trcode', $value['TRANCODE']);
                        $record->addAttribute('fxid', $value['FXID']);
                        $record->addAttribute('fcamt', $value['FCAMT']);

                        //  fcamount -- to be computed at the server side                    
                        $record->addAttribute('curid', $value['CURRENCIES_ID']);
                        $record->addAttribute('cid', $value['CLIENTIDNO']);
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
                        $record->addAttribute('cqno', $value['CHEQNO']);
                        $record->addAttribute('memid', $value['MEMID']);
                        $record->addAttribute('mode', $value['MODE']);
                        $record->addAttribute('ttype', $value['TTYPE']);
                        $record->addAttribute('cid', $value['CLIENTIDNO']);
                        $record->addAttribute('chqno', $value['CHEQNO']);
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

            if ($bcloseXMLTags) {

                self::$xml = self::$xmlObj->asXML();
                //echo "<pre>".htmlentities($nxml)."</pre>";
                // exit();
                // replace <?xml version="1.0" 
                self::$xml = str_replace("<?xml version=\"1.0\"?>\n", '', self::$xml);
            }

            //catch exception
        } catch (Exception $e) {
            Self::$lablearray['E01'] = $e->getMessage();
        }
    }

    /* DESCRIPTION:  THIS FUNCTION IS USED TO UPDATE SAVINGS BALANCES
     * PARAMETERS:
     * $ACCOUNTS_ARRAY - SAVINGS ACCOUNTS
     * NOTE:
     */

    public static function updateSavingsBalance($accounts_array = array(), $products_array = array(), &$Conn) {

        // create comma - delimted strings
        $comma_accounts = implode(",", $accounts_array);

        $comma_products = implode(",", $products_array);

        $parameters = array();
        self::prepareParameters($parameters, 'accounts', $comma_accounts);

        self::prepareParameters($parameters, 'products', $comma_products);

        self::prepareParameters($parameters, 'code', 'UPDSAVBAL');

        self::prepareParameters($parameters, 'branch_code', '');

        return self::common_sp_call(serialize($parameters), '', $Conn, true);
    }

    /* DESCRIPTION: THIS FUNCTION IS USED TO GENERATE REPORTS
     * PARAMETERS:
     * $REPORTCODE: USED TO IDENFITY WHAT SO TO CALL
     * $CONN: CONNECTION OBJECT
     * $PARAMETERS: PARAMETERS PASSED FROM THE INTERFACE
     * $FLATEN; WHERE TO COVERT ARRAY TO A SINGLE DIMENSION ARRAY
     */

    public static function common_sp_call($parameters, $format, &$Conn, $flaten = false) {

        // process parameters

        $parameters = unserialize($parameters);


        $key = '';
        $parameters_final = array();
        unset($parameters_final);

        // Note:Parameters MUST be ordered according to the SP signature
        array_walk($parameters, function(&$a, $key) use(&$parameters_final) {
            // $parameters_final[$key['name']] = $key['value'];
            $parameters_final[$a['name']] = $a['value'];
        });
      
        if (!isset($parameters_final['branch_code'])):
            $parameters_final['branch_code'] = BRANCHCODE;
        endif;

        $parameters_final['plang'] = $_SESSION['P_LANG'];

        if (!isset($Conn)) {
            $results = self::$connObj->sp_call($parameters_final, $format);
        } else {
            $results = self::$connObj->sp_call($parameters_final, $format);
        }

        if ($flaten) {
            if (count($results) == 0) {
                return;
            } else {
                return call_user_func_array('array_merge', $results);
            }
        } else {
            return $results;
        }
    }

    /* DESCRIPTION: THIS FUNCTION IS USED TO CHECK IF ACCOUNT EXITS
     * PARAMETERS:
     * $acc: GL Account  
     */

    public static function checkifAccountExists($acc) {

        $parameters = array();
        self::prepareParameters($parameters, 'theid1', $acc);
        self::prepareParameters($parameters, 'theid2', '');
        self::prepareParameters($parameters, 'code', 'IDEXISTS');
        self::prepareParameters($parameters, 'idtype', 'GLACC');
        self::prepareParameters($parameters, 'branch_code', '');
        $acc_array = self::common_sp_call(serialize($parameters), '', self::$connObj, true);

        return $acc_array;
    }

    /* DESCRIPTION: THIS FUNCTION IS USED TO GROUP BY OPTIONS
     * PARAMETERS:
     * $REPORTCODE: USED TO IDENFITY THE REPORT
     * $CONN: CONNECTION OBJECT   
     */

    public static function generateGroupByOrderBy($reportcode, &$Conn) {

        $report_general_groupby = ",1259";
        $report_general_orderby = ",1259";
        $disabled = "";

        self::$lablearray = array();

        // ORDER BY
        $rpt_contrs = '<div style="margin:0px;">Order By: <select id="order_by" name="order_by">';
        $rpt_contrs .= "<option value='' id=''>-------</option>";

        switch ($reportcode) {
            case 'TDRPT':
            case 'SAVBALRPT':
            case 'ARRERPT':
            case 'PORTRSK':
            case 'OUTBAL':
            case 'LOANREP':
                self::getlables("316,1259,1093,1246,1096,987,1275" . $report_general_orderby, "", "", Common::$connObj);
                break;

            case 'TRIALB':
            case 'BREAKPERACC':
            case 'INCOMEEXP':
                self::getlables("301,296,298", "", "", Common::$connObj);
                break;

            case 'CLIENTRPTS':
            case 'CLIENTLOANFREQ':
                self::getlables("9,296,316", "", "", Common::$connObj);
                break;


            default:
                break;
        }

        $select = '';
        foreach (self::$lablearray as $key => $value) {
            switch ($reportcode) {
                case 'CLIENTRPTS':
                    if ($key == '9'):
                        $select = 'selected';
                    endif;
                    break;

                default:

                    break;
            }
            $rpt_contrs .= "<option value='" . $key . "' id='" . $key . "' " . $select . ">" . $value . "</option>";
            $select = '';
        }

        $rpt_contrs .= '</select>';

        if ($reportcode == 'BREAKPERACC') {
            //$disabled="disabled"; 
        }

        // GROUP BY
        $rpt_contrs .= ' Group by: <select id="group_by" name="group_by" ' . $disabled . '>';

        if ($reportcode != 'BREAKPERACC') {
            $rpt_contrs .= "<option value='' id=''>-------</option>";
        }
        switch ($reportcode) {
            case 'TDRPT':
            case 'SAVBALRPT':
            case 'SAVTILL':
            case 'OUTBAL':
            case 'GUARANTORS':
            case 'ARRERPT':
            case 'PORTRSK':
            case 'LOANREP':
                self::getlables("316,1259,1093,1246,1096,987,1275" . $report_general_groupby, "", "", $Conn);
                break;
            case 'BREAKPERACC'; // Breakdown Per Account
                self::$lablearray = array();
                $rpt_contrs .= "<option value='296' id='296' selected>-------</option>";


            case 'BALANCESHEET'; // Balancesheet
                break;
            case 'TRIALB'; // Breakdown Per Account
            case 'INCOMEEXP'; // Income and Expenditure
            case 'CASHFLOW'; // Cashflow
            case 'EQUITYCHA'; // Changes in Equity
            case 'DEBITCREDIT'; // Changes in Equity
                self::getlables("301,296,298", "", "", $Conn);
                break;
            case 'CLIENTRPTS':
            case 'CLIENTLOANFREQ':
                unset(self::$lablearray['9']);
                unset(self::$lablearray['296']);
                break;
            default:
                break;
        }

        if (count(self::$lablearray) > 0) {
            foreach (self::$lablearray as $key => $value) {
                switch ($reportcode) {
                    case 'CLIENTRPTS':
                        if ($key == '316'):
                            $select = 'selected';
                        endif;
                        break;

                    default:
                        break;
                }

                $rpt_contrs .= "<option value='" . $key . "' id='" . $key . "' " . $select . ">" . $value . "</option>";
                $select = '';
            }
        }
        $rpt_contrs .= '</select></div>';

        return $rpt_contrs;
    }

    /* DESCRIPTION: This functiction is used to prepare convert php date format to javascript equivalents
     * PARAMETERS:
     */

    public static function convertDateJSFormat() {

        switch (SETTING_DATE_FORMAT):
            case 'dd/mm/YYYY':
            case 'DD/MM/YYYY':
                $date_format = 'dd/MM/yyyy';
                break;
            case 'YYYY/mm/dd':
                $date_format = 'yyyy/MM/dd';
                break;
            case 'YYYY-mm-dd':
                $date_format = 'yyyy-MM-dd';
                break;
            default:
                $date_format = SETTING_DATE_FORMAT;
                break;
        endswitch;

        return $date_format;
    }

    /* DESCRIPTION: This functiction is used to prepare parameters that will be sent to the stored
     * PARAMETERS:$parameters
     */

    public static function getDateFornat() {

        switch (SETTING_DATE_FORMAT):
            case 'dd/mm/YYYY':
            case 'DD/MM/YYYY':
                $date_format = 'd/m/Y';
                break;
            default:
                $date_format = SETTING_DATE_FORMAT;
                break;
        endswitch;

        return $date_format;
    }

    public static function prepareParameters(&$parameters, $key, $val) {
        $parameters[] = array('name' => $key, 'value' => $val);
    }

    /* DESCRIPTION: This functiction is used to update avalue in a 3D array
     * PARAMETERS:$parameters   
     */

    public static function updateValuein3DArray(&$parameters, $key, $val) {
        foreach ($parameters as $k => &$v) {
            if ($v['name'] == $key) {
                $v['value'] = $val;
                break;
            }
        }
    }

    /* DESCRIPTION: This functiction is used to add a Key Value Pair to an Array
     * PARAMETERS:$parameters
     * Noe. It is not the same as prepareParameters()
     */

    public static function addKeyValueToArray(&$parameters, $key, $val) {
        // $parameters = array('name' => $key, 'value' => $val);
        $parameters[$key] = $val;
    }

    // This functiction gets a file a extension
    // TO DO: check see if their are not functions like this from old code
    public static function getFileExtension($file_name = '') {
        return explode('.', $file_name);
    }

    // This functiction gets the number of days between two dates
    // TO DO: To consider non working days in configures
    public static function getNumberOfDaysBetweenDates($datetime_1 = '', $datetime_2 = '') {

        try {


            $date_format = Common::getDateFornat();

            switch (SETTING_DATE_FORMAT):
                case 'dd/mm/YYYY':
                case 'DD/MM/YYYY':
                    if (strpos($datetime_1, '-') !== false):
                        $datetime_1 = Common::changeMySQLDateToPageFormat($datetime_1);
                    endif;

                    if (strpos($datetime_2, '-') !== false):
                        $datetime_2 = Common::changeMySQLDateToPageFormat($datetime_2);
                    endif;

                    break;
                default:
                    break;
            endswitch;

            // from date
            $date1 = date_create_from_format($date_format, $datetime_1);

            // to date
            $date2 = date_create_from_format($date_format, $datetime_2);

            $interval = date_diff($date1, $date2);

            return abs($interval->format('%R%a'));
        } catch (Exception $ex) {

            Common::$lablearray['E01'] = 'MSG Invalid Date';
        }
    }

    // this function is used to generate an unformated numeric number	
    public static function sum_array($keycolumn, $comvalue, $valcolumn, $the_array) {

        $value = 0;

        foreach ($the_array as $bkey => $bval):
            if ($bval[$keycolumn] == $comvalue):
                $value = $value + $bval[$valcolumn];
            endif;
        endforeach;

        return $value;
    }

    // this function is used to generate an unformated numeric number	
//    public static function number_unformat($number, $force_number = true, $dec_point = '.', $thousands_sep = ',') {
//        if ($force_number) {
//            $number = preg_replace('/^[^\d]+/', '', $number);
//        } else if (preg_match('/^[^\d]+/', $number)) {
//            return false;
//        }
//
//        $type = (strpos($number, $dec_point) === false) ? 'int' : 'float';
//        $number = str_replace(array($dec_point, $thousands_sep), array('.', ''), $number);
//        settype($number, $type);
//        return $number;
//    }

    public static function push_element_into_array(&$main_array, $key, $value, $returnarray = false) {

        $main_array[$key] = $value;
        if ($returnarray == true) {
            return $main_array;
        }
    }

    public static function DrawCheqBanks($ttype = 'CQ') {

        $lablearraybanks = getlables("67,36,64,65,66,1136");
        //$connObj = new ConnectionFactory;
        $banks_array = self::$connObj->SQLSelect("SELECT bb.bankbranches_id as bankbranches_id, banks_name , bb.bankbranches_name as bankbranches_name,bankaccounts_accno,(SELECT currencies_id FROM " . TABLE_CHARTOFACCOUNTS . " c WHERE c.chartofaccounts_accountcode=ba.chartofaccounts_accountcode ) as currencies_id FROM " . TABLE_BANKBRANCHES . " bb, " . TABLE_BANKS . " b, " . TABLE_BANKACCOUNTS . " ba WHERE ba.bankbranches_id = bb.bankbranches_id AND bb.banks_id=b.banks_id GROUP BY bb.bankbranches_id");

        $banks = '<select id="bankbranches_id" name="bankbranches_id">';
        foreach ($banks_array as $key => $val) {
            $banks .= '<option id="' . $val['bankaccounts_accno'] . '" value="' . $val['bankaccounts_accno'] . '">' . $val['banks_name'] . " | " . $val['bankbranches_name'] . ' | ' . $val['bankaccounts_accno'] . ' (' . $val['currencies_id'] . ')</option>';
        }
        $banks .= '</select>';

        if ($ttype == 'CQ'):
            $banks .= $lablearraybanks['36'] . '<input id="cheques_no" name="cheques_no" value="" type="text" size="15">';

        endif;
        echo $banks;
    }

    public static function SavAccounts($theid = '') {

        try {

            self::getlables("1206,1096,1408,1661", "", "", self::$connObj);

            if (preg_match('[G]', $theid)) {

                $accounts_array = self::$connObj->SQLSelect("SELECT sa.savaccounts_account,sa.name,sa.client_idno,SUM(st.savtransactions_amount) balance,sa.product_prodid,(SELECT p.currencies_code FROM " . TABLE_PRODUCTCONFIG . " g," . TABLE_CURRENCIES . " p WHERE p.currencies_id=g.productconfig_value AND g.product_prodid=sa.product_prodid AND g.productconfig_paramname='CURRENCIES_ID') currency FROM
                (SELECT s.savaccounts_account,s.client_idno,s.product_prodid,entity_name name FROM " . TABLE_SAVACCOUNTS . " s," . TABLE_ENTITY . " c  WHERE c.entity_idno=s.client_idno AND s.client_idno='" . $theid . "' GROUP BY s.savaccounts_account,s.product_prodid) 
                sa LEFT JOIN " . TABLE_SAVTRANSACTIONS . " st ON st.savaccounts_account=sa.savaccounts_account GROUP BY st.savaccounts_account,st.product_prodid");
            } elseif (preg_match('[I]', $theid) || preg_match('[B]', $theid)) {

                $accounts_array = self::$connObj->SQLSelect("SELECT sa.savaccounts_account,sa.name,sa.client_idno,SUM(st.savtransactions_amount) balance,sa.product_prodid,(SELECT p.currencies_code  FROM " . TABLE_PRODUCTCONFIG . " g," . TABLE_CURRENCIES . " p  WHERE p.currencies_id=g.productconfig_value AND g.product_prodid=sa.product_prodid AND g.productconfig_paramname='CURRENCIES_ID') currency FROM
                (SELECT s.savaccounts_account,s.client_idno,s.product_prodid,CONCAT(client_surname,client_firstname,client_middlename) name FROM " . TABLE_SAVACCOUNTS . " s," . TABLE_VCLIENTS . " c  WHERE c.client_idno=s.client_idno AND s.client_idno='" . $theid . "' GROUP BY s.savaccounts_account,s.product_prodid) 
                sa LEFT JOIN " . TABLE_SAVTRANSACTIONS . " st ON st.savaccounts_account=sa.savaccounts_account GROUP BY st.savaccounts_account,st.product_prodid");
            } elseif (preg_match('[M]', $theid)) {

                $accounts_array1 = self::$connObj->SQLSelect("SELECT st.savaccounts_account,sa.name,sa.client_idno,SUM(st.savtransactions_amount) balance,st.product_prodid,sa.members_idno,(SELECT p.currencies_code FROM " . TABLE_PRODUCTCONFIG . " ," . TABLE_CURRENCIES . " p  WHERE p.currencies_id=g.productconfig_value AND g.product_prodid=sa.product_prodid AND g.productconfig_paramname='CURRENCIES_ID') currency FROM
                (SELECT m.entity_idno client_idno,CONCAT(m.members_firstname,' ',m.members_middlename,' ',m.members_lastname) name,m.members_idno FROM " . TABLE_MEMBERS . " m  WHERE m.entity_idno='" . $theid . "' GROUP BY m.entity_idno,m.members_idno) 
                sa LEFT JOIN " . TABLE_SAVTRANSACTIONS . " st ON  st.members_idno=sa.members_idno GROUP BY st.savaccounts_account,st.product_prodid,st.members_idno");
            }

            if ($accounts_array[0][1] == '1'):
                echo "ERR " . self::$lablearray['1661'];
                exit();
            endif;

            if (!isset($accounts_array)):
                echo "INFO." . self::$lablearray['1661'];
                exit();
            endif;

            if (count($accounts_array) > 0) {
                if ($theid != "") {
                    $accounts = self::$lablearray['1408'] . '<br> <select id="cmbsavaccounts" name="cmbsavaccounts">';
                    foreach ($accounts_array as $key => $val) {
                        $accounts .= '<option id="' . $val['savaccounts_id'] . '" value="' . $val['savaccounts_account'] . ":" . $val['product_prodid'] . '">' . $val['savaccounts_account'] . ' (' . $val['product_prodid'] . ') ' . $val['balance'] . ' ' . $val['currency'] . '</option>';
                    }
                } else {
                    $accounts_array = self::$connObj->SQLSelect("SELECT product_name,product_prodid FROM " . TABLE_PRODUCT . " WHERE LEFT(product_name,1)='S'");

                    $accounts = self::$lablearray['1096'] . '<br> <select id="cmbproducts" name="cmbproducts">';

                    foreach ($accounts_array as $key => $val) {
                        $accounts .= '<option id="' . $val['savaccounts_id'] . '" value="' . $val['savaccounts_account'] . ":" . $val['product_prodid'] . '">' . $val['savaccounts_account'] . ' (' . $val['product_prodid'] . ') ' . $val['balance'] . ' ' . $val['currency'] . '</option>';
                    }
                }

                $accounts .= '</select>';

                return $accounts;
            } else {
                return 'INFO ' . self::$lablearray['1206'];
            }
        } catch (Exception $ex) {

            self::$error = $ex->getMessage();

            throw new Exception(self::$error);
        }
    }

    // This function is used to generate a transaction code for the user
    // $user_id: User Id
    public static function generateTransactionCode($user_id = '') {

//        tep_db_query("UPDATE " . TABLE_USERS . " SET user_lasttcode =(user_lasttcode + 1) WHERE user_id = '" . $user_id . "'");
//
//        $tcode_query = tep_db_query("SELECT user_lasttcode AS tcode,user_usercode FROM " . TABLE_USERS . " WHERE user_id = '" . $user_id . "'");
//
//        $tcode = tep_db_fetch_array($tcode_query);
//
//        return $_SESSION['user_usercode'] . date('Y') . $tcode['tcode'];
        //$parameters_final['userid'] = $user_id;

        $parameters_final['code'] = 'TCODE';
        $parameters_final['branch_code'] = BRANCHCODE;


        $results = self::$connObj->sp_call($parameters_final, '');

        return $results[0]['id'];
    }

    // get product configurations
    // This function is used to returns values in a single dimention array
    public static function getproductglConfigs($product_prodid, $valuecolumn, $keycolumn) {
        //  $connObj = new ConnectionFactory;
        return array_column(Common::$connObj->SQLSelect("SELECT productconfig_paramname,productconfig_ind,productconfig_grp FROM " . TABLE_PRODUCTCONFIG . " WHERE productconfig_paramname IN('PRINCIPAL_OUTSTANDING_ACC','INT_RECEIVED_ACC','COMM_RECEIVED_ACC','PEN_RECEIVED_ACC','LOAN_PRINCIPAL_OVERPAYMENT_ACC','LOAN_INTEREST_OVERPAYMENT_ACC','LOAN_COMMISION_OVERPAYMENT_ACC','LOAN_COM_FROM_SAV') AND product_prodid='" . $product_prodid . "'"), $valuecolumn, $keycolumn);
    }

    public static function getExchangeRate($currencies_id, $dtDate) {


        $currency_array = self::$connObj->SQLSelect("SELECT fx.forexrates_id,fx.forexrates_midrate,c.currencies_name FROM " . TABLE_FOREXRATES . " fx, " . TABLE_CURRENCIES . " c  WHERE c.currencies_id=fx.currencies_id AND  fx.forexrates_date<='" . $dtDate . "' AND fx.currencies_id='" . $currencies_id . "' ORDER BY fx.forexrates_date DESC LIMIT 1");
        if (!isset($currency_array['forexrates_id'])):
            self::getlables("1193", "", "", self::$connObj);
            self::$lablearray['E01'] = self::$lablearray['1193'] . ' ' . $currency_array['currencies_name'];

        endif;
        $fxrates = array($currency_array['forexrates_id'] => $currency_array['forexrates_midrate']);

        return $fxrates;
    }

    //this function is used to round off,down and up
    public static function RoundUpDown($val = 0) {
        return round($val, SETTTING_ROUND_TO);
    }

    // This function convert a string to an array
    // eg key,value|key,value
    public static function convertStringtoArray($string) {

        $finalArray = array();

        $asArr = array_filter(explode('|', $string));

        foreach ($asArr as $val) {
            $tmp = explode(',', $val);
            $finalArray[$tmp[0]] = $tmp[1];
        }

        return $finalArray;
    }

    public static function deleteElementByValue($del_val, &$thearray = array()) {
        $thearray = array_diff($thearray, array($del_val));
    }

    // this function is used to extract a branch code from a string    
    public static function extractBranchCode($code) {
        return substr($code, 0, 2);
    }

    // This function is used to get product details
    public static function getProductConfigDetails($product_prodid = '', $branch_code = '', $paramname = '') {


        $parameters = array();
        self::prepareParameters($parameters, 'theid1', $paramname);
        self::prepareParameters($parameters, 'theid2', $product_prodid);
        self::prepareParameters($parameters, 'code', 'IDEXISTS');
        self::prepareParameters($parameters, 'idtype', 'PRODPARA');
        self::prepareParameters($parameters, 'branch_code', $branch_code);
        $loan_array = self::common_sp_call(serialize($parameters), '', self::$connObj, true);

        return $loan_array;
    }

    // This function is used to get client details  
    public static function getClientDetails($clientid = '') {

        self::prepareParameters($parameters, 'branch_code', substr($clientid, 0, 3));
        self::prepareParameters($parameters, 'client_idno', $clientid);
        self::prepareParameters($parameters, 'code', 'ClIENTDETAILS');


        $results = self::common_sp_call(serialize($parameters), false, self::$connObj);

        return $results;
    }

    // This function is used to get client details  
    public static function getClientNames($clientid = '') {

        self::prepareParameters($parameters, 'client_idno', Trim(self::extractBranchCode($clientid)));
        self::prepareParameters($parameters, 'client_idno', $clientid);
        self::prepareParameters($parameters, 'code', 'ClIENTDETAILS');
        $name_array = self::common_sp_call(serialize($parameters), false, self::$connObj);


        //  $name_array = self::$connObj->SQLSelect("SELECT CONCAT(client_firstname,' ',client_middlename,' ',client_surname) Name,client_addressphysical FROM " .TABLE_VCLIENTS. "  WHERE client_idno='".$client_idno."'");

        return $name_array;
    }

    // this function is used to determine whether clientcode is for an Individual, groupor bussiness
    // $code: Clientcode
    // returns: 'I','G','I','M',''
    public static function getClientType($code) {

        $ctype = '';

        switch (true) {

            case (strpos($code, 'I') !== false): // Individual               
                $ctype = 'I';
                break;

            case (strpos($code, 'G') !== false): //Group
                $ctype = 'G';
                break;

            case (strpos($code, 'B') !== false): // Business
                $ctype = 'B';
                break;

            case (strpos($code, 'M') !== false): // Review this part for Group members
                $ctype = 'M';
                break;

            default: // New
                $ctype = 'UNDEFINED';
                break;
        }

        return $ctype;
    }

    /* DESCRIPTION:  THIS FUNCTION IS USED TO GET THE CLIENT TYPE
     * PARAMETERS:
     * $idenficationid - identification Id e.g clientcode    
     * NOTE:
     */

    public static function identidyClientType($idenficationid ='') {

        $ipos = strpos($idenficationid, 'I');
        if ($ipos !== false) {
            return 'I';
        }


        $gpos = strpos($idenficationid, 'G');
        if ($gpos !== false) {
            return 'G';
        }

        
        $gpos = strpos($idenficationid, 'MNO');
        if ($gpos !== false) {
            return 'MNO';
        }


        $mpos = strpos($idenficationid, 'M');
        if ($mpos !== false) {
            return 'M';
        }

        $bpos = strpos($idenficationid, 'B');
        if ($bpos !== false) {
            return 'B';
        }

        $bpos = strpos($idenficationid, 'S');
        if ($bpos !== false) {
            return 'S';
        }

        return 'NON';
    }

    /* DESCRIPTION:  THIS FUNCTION IS USED TO IDs
     * PARAMETERS:
     * $idenficationid - identification Id e.g clientcode e.g PP/G
     * $idtype: Element ID
     * NOTE:
     */

// generateID($value['BRCODE'].'/'.$value['CTYPE'], $value['CTYPE'], Common::$connObj); 
    public static function generateID($id, $id_type,$subtype,$ccode = '') {

        $parameters_final['branch_code'] = Trim(self::extractBranchCode($id));

        $parameters_final['id_type'] = self::identidyClientType($id_type);

        $parameters_final['ccode'] = $ccode;                    

        $parameters_final['sub_type'] = $subtype;

        $parameters_final['code'] = 'GENERATEID';

        $results = self::$connObj->sp_call($parameters_final, '');

        return $results[0]['id'];
    }

    # Function to transform a date from MySQL database Format (yyyy-mm-dd) to the format displayed on pages(mm/dd/yyyy).
    # If the date from the database is NULL, it is transformed to an empty string for display on the pages

    public static function changeMySQLDateToPageFormat($mysqldate) {

        if ($mysqldate == NULL || $mysqldate == "0000-00-00 00:00:00" || $mysqldate == '0000-00-00') {
            return '';
        } else {

            $mysqldate = strtotime($mysqldate);

            switch (SETTING_DATE_FORMAT) {
                case 'm/d/Y':
                    $mysqldate = date("m/j/Y", $mysqldate);
                    break;

                case 'd/m/Y':
                    $mysqldate = date("j/m/Y", $mysqldate);
                    break;

                case 'DD/MM/YYYY':
                case 'dd/mm/YYYY':
                    $mysqldate = date("d/m/Y", $mysqldate);
                    break;

                case 'MM/DD/YYYY':
                    $mysqldate = date("m/j/Y", $mysqldate);
                    break;

                case 'YYYY/MM/DD':

                    $mysqldate = date("Y m j", $mysqldate);
                    break;

                default:
                    break;
            }
        }



        return $mysqldate;
    }

    # Function to transform a date from the format displayed on pages(mm/dd/yyyy) to the MySQL database date format (yyyy-mm-dd).
    # If the date from the database is an empty string, it is transformed to a NULL value. Note that single quotation marks are added to
    # the non-empty date.

    public static function changeDateFromPageToMySQLFormat($pagedate ="", $addtimepstamp = true, $nseconds = 1) {

        if (trim($pagedate) == "") {
            $mysqldate = "NULL";
        } else {

            switch (SETTING_DATE_FORMAT) {
                case 'DD/MM/YYYY':
                case 'dd/mm/YYYY':
                    $pagedate = str_replace('/', '-', $pagedate);
                    break;
                default:
                    break;
            }

            if ($addtimepstamp) {
                $mysqldate = date("Y-m-d", strtotime($pagedate)) . " " . Date('H:i:s', time() + $nseconds);
            } else {
                //$mysqldate = "'".date("Y-m-d H:i:s", strtotime($pagedate))."' ";
                $mysqldate = date("Y-m-d", strtotime($pagedate));
            }
        }

        return $mysqldate??'';
    }

    # Function is used to add time to a given Mysql Date

    public static function MySQLDateAddTime($MySQLdate, $hours, $minutes, $seconds) {

        if (trim($MySQLdate) == "") {
            return "NULL";
        } else {

            return date("Y-m-d H:i:s", (strtotime(date($MySQLdate)) + $seconds));
        }
    }

    //***************************************************************************************
    //Description: This function is used to add/substract a date
    //Signature: $addsub: operator $dtDate: The date to manupulate $no_of_dmy: Number to adjust the date with $myd: Days/Months/Years/Weeks  adjustDateTo:	Day in the month to adjust date to
    // Note. if SETTING_DATE_FORMAT doe snot match the date format of variable passed this will create an error. 
    //***************************************************************************************
    //Common::calculateDate('+', $tempDate['date'], 0, self::$incomingvars['INSTYPE'], self::$incomingvars['adjustDueDatesTo'],self::$incomingvars['adjusttononworkingday']);
    public static function calculateDate($addsub = '+', $dtDate = '', $no_of_dmy = '0', $myd = 'D', $adjustDateTo = 'N', $ignoreCalDaysAdj = 'N') {

        try {

            $date_format = Common::getDateFornat();

            switch (SETTING_DATE_FORMAT):
                case 'dd/mm/YYYY':
                case 'DD/MM/YYYY':
                    if (strpos($dtDate, '-') !== false):

                        $dtDate = self::changeMySQLDateToPageFormat($dtDate);
                    //  $dtDate = Common::replaces_hyhpen($dtDate);
                    endif;

                    break;
                default:
                    break;
            endswitch;


            //      Dates in the m/d/y or d-m-y formats are disambiguated by looking at the separator 
            //      between the various components: if the separator is a slash (/), then the 
            //      American m/d/y is assumed; whereas if the separator is a dash (-) or a dot (.), 
            //      then the European d-m-y format is assumed 
            $new_date = DateTime::createFromFormat($date_format, $dtDate);


            //redefine(SETTING_DATE_FORMAT ='m/d/Y';
            // add or substract date


            switch ($addsub) {

                case '+':

                    // check see whether we are adjusting date by month/day/year
                    switch ($myd) {

                        case 'D':
                            if ($no_of_dmy == 0) {
                                $no_of_dmy = '1';
                            }

                            //$date->add(new DateInterval('P1D'));	
                            $no_of_dmy = 'P' . $no_of_dmy . 'D';
                            break;

                        case 'M':

                            if ($no_of_dmy == 0) {
                                $no_of_dmy = '1';
                            }

                            $no_of_dmy = 'P' . $no_of_dmy . 'M';

                            if ($ignoreCalDaysAdj == 'N'):


                                // FOR HBPS - USE CALENDAR DAYS
                                $curDay = $new_date->format("j"); //currect date
                                $totDay = $new_date->format('t'); // total number of day in month
                                $nMonth = $new_date->format("m");
                                $nYear = $new_date->format("Y");


                                switch ($curDay):
                                    case $curDay > 15:
                                        $no_of_dmy = $totDay - $curDay;

                                        // check see if month is december                                
                                        if ($nMonth == 12):
                                            $nMonth = 1;
                                            $nYear++;
                                        else:
                                            $nMonth++;
                                        endif;

                                        $nDaysInMonth = Common::getNumberofDaysInMonth($nMonth, $nYear);


                                        // get number of days  in 
                                        // $new_date_temp = clone($new_date);
                                        // $new_date_temp->add(new DateInterval('P1M'));
                                        $no_of_dmy = $no_of_dmy + $nDaysInMonth;
                                        $no_of_dmy = 'P' . $no_of_dmy . 'D';
                                        break;

                                    case $curDay <= 15:
                                        $no_of_dmy = $totDay - $curDay;
                                        $no_of_dmy = 'P' . $no_of_dmy . 'D';
                                        break;

                                    default:
                                        break;
                                endswitch;

                            endif;


                            break;

                        case 'W':

                            if ($no_of_dmy == 0) {
                                $no_of_dmy = '1';
                            }

                            //$date->add(new DateInterval('P1W'));
                            $no_of_dmy = 'P' . $no_of_dmy . 'W';
                            break;

                        case 'B':
                            if ($no_of_dmy == 0) {
                                $no_of_dmy = '2';
                            }
                            //$date->add(new DateInterval('P2W'));
                            $no_of_dmy = 'P' . $no_of_dmy . 'W';
                            break;

                        case 'H':
                            if ($no_of_dmy == 0) {
                                $no_of_dmy = '15';
                            }
                            //$date->add(new DateInterval('P15D'));
                            $no_of_dmy = 'P' . $no_of_dmy . 'D';
                            break;

                        case 'F':
                            if ($no_of_dmy == 0) {
                                $no_of_dmy = '4';
                            }
                            //$date->add(new DateInterval('P4M'));
                            $no_of_dmy = 'P' . $no_of_dmy . 'M';
                            break;

                        case 'F':
                            if ($no_of_dmy == 0) {
                                $no_of_dmy = '6';
                            }
                            //$date->add(new DateInterval('P6M'));
                            $no_of_dmy = 'P' . $no_of_dmy . 'M';
                            break;

                        case 'E':
                            if ($no_of_dmy == 0) {
                                $no_of_dmy = '7';
                            }
                            //$date->add(new DateInterval('P7M'));
                            $no_of_dmy = 'P' . $no_of_dmy . 'M';
                            break;

                        case 'A':
                            if ($no_of_dmy == 0) {
                                $no_of_dmy = '12';
                            }
                            //$date->add(new DateInterval('P12M'));
                            $no_of_dmy = 'P' . $no_of_dmy . 'M';
                            break;

                        default:
                            $no_of_dmy = 'P' . $no_of_dmy . 'D';
                            break;
                    }

                    $new_date->add(new DateInterval($no_of_dmy));


                    break;

                case '-':
                    //$new_date->sub(new DateInterval('P'.$no_of_dmy.$myd));
                    break;

                default;
                    break;
            }

            // check see if we should adjust date to user specified date
            // if a  users specified 29th for Feb. Should we change.No. Because feb has 28 days at times

            $nDaysInMonth = Common::getNumberofDaysInMonth($new_date->format("m"), $new_date->format("Y"));


            // check see if we should adjust date
            if ($adjustDateTo > 0) {

                // check 30/31/28 day-month
                //$nDays = $nDaysInMonth + $adjustDateTo;


                if ($adjustDateTo > $nDaysInMonth) {

                    $adjustDateTo = $nDaysInMonth;
                }


                if ($adjustDateTo > 0) {
                    // if $adjustDateTo  is higher that day generated adjust forwards
                    if ($adjustDateTo > $new_date->format("j")) {
                        $nDays = $adjustDateTo - $new_date->format("j");
                        $new_date->add(new DateInterval('P' . $nDays . 'D'));
                    }

                    // if $adjustDateTo  is less that day generated adjust backwards
                    if ($new_date->format("j") > $adjustDateTo) {
                        $nDays = $new_date->format("j") - $adjustDateTo;
                        $new_date->sub(new DateInterval('P' . $nDays . 'D'));
                    }
                }
            }

            // check see if  we are checking for non working days
            if ($adjustDateTo == 'Y') {

                // loop till you get a day which is nor a public holiday
                while (Common::checkHolidays($new_date->format('Y-m-d')) > 0) {
                    $new_date->add(new DateInterval('P1D'));
                }
            }

            //echo $dtDate.'---'.$myd.'-----'.$date->format(SETTING_DATE_FORMAT).'----'.$nDaysInMonth.'<br>';
            //echo '------------------------------------------------------------------------------------<br>';
            return array('day' => $new_date->format("j"), 'month' => trim($new_date->format("m")), 'year' => trim($new_date->format("Y")), 'date' => trim($new_date->format($date_format)));
            //exit();
            // return the date object
            //return $adate;
        } catch (Exception $e) {
            Common::$lablearray['E01'] = $e->getMessage();
        }
    }

    # this function is localise numeric values for display

    public static function number_format_locale_display($number, $decimals = SETTING_ROUNDING) {
        $locale = ( isset($_COOKIE['locale']) ?
                $_COOKIE['locale'] :
                $_SERVER['HTTP_ACCEPT_LANGUAGE']
                );
        switch ($locale) {
            case 'en-us':
            case 'en-ca':
                $decimal = '.';
                $thousands = ',';
                break;
            case 'fr':
            case 'ca':
            case 'de':
            case 'en-gb':
                $decimal = ',';
                $thousands = ' ';
                break;
            case 'es':
            case 'es-mx':
            default:
                $decimal = '.';
                $thousands = ',';
        }
       
        return number_format((string)$number, $decimals, $decimal, $thousands);
    }

    # This function is format numeric values for computing

    public static function number_format_locale_compute($number, $decimals = SETTING_ROUNDING) {

        $locale = ( isset($_COOKIE['locale']) ?
                $_COOKIE['locale'] :
                $_SERVER['HTTP_ACCEPT_LANGUAGE']
                );


        if ($number == "") {
            $number = 0;
        }

        switch ($locale) {
            case 'fr':
            case 'ca':
            case 'de':
            case 'en-gb':
            case 'es':
            case 'es-mx':
            case (P_LANG == 'FR'):
            case (P_LANG == 'SP'):
                $decimal = ',';
                $thousands = '';
                $number = str_replace('.', '', $number);
                $number = str_replace($decimal, '.', $number);

                break;

            default:
                $decimal = ',';
                $thousands = '';
                $number = str_replace(',', '', $number);
        }
        //number_format(number,decimals,decimalpoint,separator) 				
        return number_format($number, $decimals, '.', $thousands);
    }

    # This functin is used to post transaction to the general ledger

    public static function tep_db_prepare_input($string) {

        if (is_string($string)) {
            return trim(stripslashes($string));
        } elseif (is_array($string)) {

            reset($string);

            while (list($key, $value) = each($string)) {
                $string[$key] = self::tep_db_prepare_input($value);
            }

            return $string;

        } else {

            return $string;
            
        }
    }

     // *This function is used to calculate Interest per installment
     public static function calculateQuotientAndRemainder($dividend, $divisor) {
        $quotient = intdiv($dividend, $divisor); // Integer division
        $remainder = $dividend % $divisor; // Modulus operation
        return array($quotient, ($remainder??0));
    }

    # This function i used to get the currect date and time
    public static function getcurrentDateTime($datepart = '') {

        if ($datepart == 'D'):
            return Date('Y-m-d');
        else:
            return Date('Y-m-d H:i:s');
        endif;
    }

    // Array MUST haave 'name' and 'value' as column names
    public static function array_flatten(&$array) {
        $result = array();
        foreach ($array as $key => $value) {
            $result[$value['name']??$key] = $value['value']??$key;
        }
        return $result;
    }

    // This ufnction is used to replace value in an array
    // Array MUST haave 'name' and 'value' pairs in a 3d array

    public static function arrayreplaceValue(&$array, $keyvalue = '', $replacement = '') {

        foreach ($array as &$value) {

            if ($value['name'] == $keyvalue) {
                $value['value'] = $replacement;
            }
        }
    }

    # Function to obtain a comma delimited string from an array

    public static function getCommaDelimitedListFromArray($array) {
        $string = "";
        # check if the array is empty and return an empty string
        if (count($array) == 0) {
            return "";
        }
        $nCount = 0;
        foreach ($array as $value) {
            if ($nCount == 0) {
                $string = trim($value);
            } else {
                $string .= "','" . trim($value);
            }
            $nCount++;
        }

        //$string = substr($string,1);

        return $string;
    }

    public static function check_if_string_numeric($str) {
        if (is_numeric($str)) {
            return true;
        }
    }

    //comma delimited lists
    public static function getlables($tlansationsids, $From = "", $To = "", &$Conn) {

        $lablearray = array();

        // check if a conneciton object has been passed
        if (!is_object($Conn)) {
            $Conn = self::$connObj;
        }

        if ($tlansationsids != "") {
            $translations_array = self::$connObj->SQLSelect("select translations_id,translations_eng, translations_fr,translations_sp,translations_swa,translations_lug,translations_runya,translations_ja FROM " . TABLE_TRANSLATIONS . " WHERE translations_id IN (" . $tlansationsids . ")");
        }

        if ($From != "" && $To != "") {
            $translations_array = self::$connObj->SQLSelect("select translations_id,translations_eng, translations_fr,translations_sp,translations_swa,translations_lug,translations_runya,translations_ja FROM " . TABLE_TRANSLATIONS . " WHERE translations_id BETWEEN " . $From . " AND " . $To);
        }

        foreach ($translations_array As $key => $val) {

            switch (P_LANG) {

                case'EN':
                    self::$lablearray[$val['translations_id']] = $val['translations_eng'];
                    break;

                case'LUG':
                    self::$lablearray[$val['translations_id']] = $val['translations_lug'];
                    break;

                case'FR':
                    self::$lablearray[$val['translations_id']] = $val['translations_fr'];
                    break;

                case'SP':
                    self::$lablearray[$lable['translations_id']] = $val['translations_sp'];
                    break;

                case'JA':
                    self::$lablearray[$val['translations_id']] = $val['translations_ja'];
                    break;

                case'RUNYA':
                    self::$lablearray[$val['translations_id']] = $val['translations_runya'];
                    break;

                default:
                    self::$lablearray[$val['translations_id']] = $val['translations_eng'];
                    break;
            }
        }
    }

// If the array return here is  empty. 
// Most probably the Transaction type passed is not yet defined in this procedure
    public static function returnTransactionOptions(&$aLines, &$Conn) {

        try {

            $account[] = array();

            self::getlables("1613,1614,1622,1470,171,1504,1240,1402,704,1229,1230,1105,1216,1203,1028,1193,1202,311,1203,1203,1027,1204,1205,772,67,696,970,704,1144,1145,1105,1181", "", "", self::$connObj);
//           $prevLinecurrency ='';
            foreach ($aLines as $key => &$value) {

                $column1 = 'productconfig_paramname';
                $column2 = 'productconfig_value';
                $nCr = 0;
                $nDr = 0;
                $forexrates_id = 0;
                $_currency = '';

                $value['TCODE'] = (isset($value['TCODE']) ? $value['TCODE'] : '' );
                $value['DESC'] = (isset($value['DESC']) ? $value['DESC'] : '' );
                $value['SAVACC'] = (isset($value['SAVACC']) ? $value['SAVACC'] : '' );
                $value['TTYPE'] = (isset($value['TTYPE']) ? $value['TTYPE'] : '' );
                $value['GLACC'] = (isset($value['GLACC']) ? $value['GLACC'] : '' );
                $value['CTYPE'] = (isset($value['CTYPE']) ? $value['CTYPE'] : '' );
                $value['AMOUNT'] = (isset($value['AMOUNT']) ? $value['AMOUNT'] : '0' );
                $value['TABLE'] = (isset($value['TABLE']) ? $value['TABLE'] : '' );
                $value['MEMID'] = (isset($value['MEMID']) ? $value['MEMID'] : '' );
                $value['PRODUCT_PRODID'] = (isset($value['PRODUCT_PRODID']) ? $value['PRODUCT_PRODID'] : '' );
                $value['BRANCHCODE'] = (isset($value['BRANCHCODE']) ? $value['BRANCHCODE'] : '' );
                $value['FUNDCODE'] = (isset($value['FUNDCODE']) ? $value['FUNDCODE'] : '0000' );
                $value['DONORCODE'] = (isset($value['DONORCODE']) ? $value['DONORCODE'] : '00000' );
                $value['CLIENTIDNO'] = (isset($value['CLIENTIDNO']) ? $value['CLIENTIDNO'] : '' );
                $value['CCODE'] = (isset($value['CCODE']) ? $value['CCODE'] : '' );
                $value['FCAMT'] = (isset($value['FCAMT']) ? $value['FCAMT'] : '0' );
                $value['CURRENCIES_ID'] = (isset($value['CURRENCIES_ID']) ? $value['CURRENCIES_ID'] : '0' );
                $_trancode = $value['TRANCODE'];
                $_glaccount = $value['GLACC'];

                // determine column to select
                switch ($value['CTYPE']) {
                    case 'B':
                    case 'I':
                        $column2 = 'productconfig_ind';
                        break;
                    case 'M':
                    case 'G':
                        $column2 = 'productconfig_grp';
                        break;

                    default:
                        $column2 = 'productconfig_value';
                        break;
                }



                $options['GLACC'] = (isset($_glaccount) ? $_glaccount : '' );

                if ($value['DESC'] == "") {
                    switch ($value['TTYPE']) {

                        case 'TD':   // Time Deposit
                        case 'TM':   // Time Deposit
                            $value['DESC'] = self::$lablearray['1613'] . ' ' . $value['TDNO'] . ' ' . $value['MEMID'];
                            break;

                        case 'TW':   // Time Deposit
                            $value['DESC'] = self::$lablearray['1614'] . ' ' . $value['TDNO'] . ' ' . $value['MEMID'];
                            break;


                        case 'TR':   // Direct to bank 
                            $value['DESC'] = self::$lablearray['1622'] . ' ' . $value['TDNO'] . ' ' . $value['MEMID'];
                            break;

                        case 'DB':   // Direct to bank 
                            $value['DESC'] = self::$lablearray['1202'];
                            break;

                        case 'CA':   // Cash
                            $value['DESC'] = self::$lablearray['311'];
                            break;

                        case 'SP':   // Suspense
                            $value['DESC'] = self::$lablearray['1203'];
                            break;

                        case 'SD':   // Savings Deposit
                            $value['DESC'] = self::$lablearray['1027'];

                            if ($value['TABLE'] != TABLE_GENERALLEDGER) {
                                $value['TABLE'] = TABLE_SAVTRANSACTIONS;
                            }
                            break;

                        case 'SC':   // Savings withdraw
                            if ($value['TABLE'] != TABLE_GENERALLEDGER) {
                                $value['TABLE'] = TABLE_SAVTRANSACTIONS;
                            }
                            // $value['DESC'] = self::$lablearray['1028'];
                            break;
                        case 'SW':   // Savings withdraw
                            if ($value['TABLE'] != TABLE_GENERALLEDGER) {
                                $value['TABLE'] = TABLE_SAVTRANSACTIONS;
                            }
                            $value['DESC'] = self::$lablearray['1028'];
                            break;

                        case 'IT':   // Savings Deposit
                            $value['DESC'] = self::$lablearray['1028'];
                            break;

                        case 'SI':   // Savings Interest
                            $value['DESC'] = self::$lablearray['1204'];
                            break;

                        case 'LR':   // Loan Repayment
                            $value['DESC'] = self::$lablearray['1205'];
                            break;

                        case 'SA':   // Transfer
                            $value['DESC'] = self::$lablearray['171'];
                            break;

                        case 'DI-SA':   // Dibursement to Savings
                            $value['DESC'] = self::$lablearray['1229'];
                            break;

                        default:
                            $value['DESC'] = '';
                            break;
                    }
                }

                switch (trim($value['TTYPE'])) {
                    case 'PRI':   // Principal  // Interest // Commision // Penalty //VAT/Fees
                    case 'INT':
                    case 'COM':
                    case 'STA':
                    case 'PEN':
                    case 'LD':
                    case 'VAT':
                    case 'OVR':
                    case 'SFEE':

                        $value['DESC'] .= ' ' . $value['LNR'] . '-' . $value['MEMID'];
                        // echo "SELECT productconfig_paramname,productconfig_value,productconfig_ind,productconfig_grp FROM " . TABLE_PRODUCTCONFIG . " WHERE  productconfig_paramname IN ('PRINCIPAL_OUTSTANDING_ACC','INT_RECEIVED_ACC','COMM_RECEIVED_ACC','CURRENCIES_ID','PEN_RECEIVED_ACC','STAT_RECEIVED_ACC') AND product_prodid='" . $value['PRODUCT_PRODID'] . "'";
                        $_pararesult = self::$connObj->SQLSelect("SELECT productconfig_paramname,productconfig_value,productconfig_ind,productconfig_grp FROM " . TABLE_PRODUCTCONFIG . " WHERE  productconfig_paramname IN ('PRINCIPAL_OUTSTANDING_ACC','INT_RECEIVED_ACC','COMM_RECEIVED_ACC','CURRENCIES_ID','PEN_RECEIVED_ACC','STAT_RECEIVED_ACC','VAT_ACC','LOAN_OVERPAYMENT_ACC','SERVICE_FEE_ACC') AND product_prodid='" . $value['PRODUCT_PRODID'] . "' AND branch_code='" . $value['BRANCHCODE'] . "'");

                        $data_acc = self::searchArray($_pararesult, 'productconfig_paramname', 'CURRENCIES_ID');
                        $currency = $data_acc['productconfig_value'];

                        if ($value['TTYPE'] == 'PRI') {
                            $data_acc = self::searchArray($_pararesult, $column1, 'PRINCIPAL_OUTSTANDING_ACC');
                            self::$lablearray['E01'] = self::$lablearray['1144'] . ' ' . self::$lablearray['1470'] . ' ' . $value['PRODUCT_PRODID'];
                        } elseif ($value['TTYPE'] == 'LD') {
                            $data_acc = self::searchArray($_pararesult, $column1, 'PRINCIPAL_OUTSTANDING_ACC');
                            self::$lablearray['E01'] = self::$lablearray['1229'] . ' ' . self::$lablearray['1470'] . ' ' . $value['PRODUCT_PRODID'];
                        } elseif ($value['TTYPE'] == 'INT') {
                            $data_acc = self::searchArray($_pararesult, $column1, 'INT_RECEIVED_ACC');
                            self::$lablearray['E01'] = self::$lablearray['1145'] . ' ' . self::$lablearray['1470'] . ' ' . $value['PRODUCT_PRODID'];
                        } elseif ($value['TTYPE'] == 'COM') {
                            $data_acc = self::searchArray($_pararesult, $column1, 'COMM_RECEIVED_ACC');
                            self::$lablearray['E01'] = self::$lablearray['1105'] . ' ' . self::$lablearray['1470'] . ' ' . $value['PRODUCT_PRODID'];
                        } elseif ($value['TTYPE'] == 'STA') {
                            $data_acc = self::searchArray($_pararesult, $column1, 'STAT_RECEIVED_ACC');
                            self::$lablearray['E01'] = self::$lablearray['1230'] . ' ' . self::$lablearray['1470'] . ' ' . $value['PRODUCT_PRODID'];
                        } elseif ($value['TTYPE'] == 'PEN') {
                            $data_acc = self::searchArray($_pararesult, $column1, 'PEN_RECEIVED_ACC');
                            self::$lablearray['E01'] = self::$lablearray['1181'] . ' ' . self::$lablearray['1470'] . ' ' . $value['PRODUCT_PRODID'];
                        } elseif ($value['TTYPE'] == 'VAT') {
                            $data_acc = self::searchArray($_pararesult, $column1, 'VAT_ACC');
                            self::$lablearray['E01'] = self::$lablearray['1402'] . ' ' . self::$lablearray['1470'] . ' ' . $value['PRODUCT_PRODID'];
                        } elseif ($value['TTYPE'] == 'OVR') {
                            $data_acc = self::searchArray($_pararesult, $column1, 'LOAN_OVERPAYMENT_ACC');
                            self::$lablearray['E01'] = self::$lablearray['1240'] . ' ' . self::$lablearray['1470'] . ' ' . $value['PRODUCT_PRODID'];
                        } elseif ($value['TTYPE'] == 'SFEE') {
                            $data_acc = self::searchArray($_pararesult, $column1, 'SERVICE_FEE_ACC');
                            self::$lablearray['E01'] = self::$lablearray['1504'] . ' ' . self::$lablearray['1470'] . ' ' . $value['PRODUCT_PRODID'];
                        }


                        if (count($data_acc) == 0) {
                            self::$error = self::$lablearray['704'] . ' ' . self::$lablearray['E01'];
                            throw new Exception(self::$lablearray['E01']);
                            break 2;
                        }

                        if ($data_acc[$column2] == "") {
                            self::$lablearray['E01'] .= self::$lablearray['704'] . " " . $value['PRODUCT_PRODID'];
                            throw new Exception(self::$lablearray['E01']);
                            break 2;
                        } else {
                            self::$lablearray['E01'] = '';
                            $value['GLACC'] = $data_acc[$column2];
                        }

                        self::$lablearray['E01'] = "";

                        $data_acc = self::searchArray($_pararesult, $column1, 'CURRENCIES_ID');

                        // check see if user is transacting in foregn currency	
                        if (SETTTING_CURRENCY_ID != $data_acc['productconfig_value']) {

                            if (!isset($value['DATE'])) {
                                $ddate = self::functiongetcurrentDateTime();
                            } else {
                                $ddate = $value['DATE'];
                            }
                            $ex_rate_array = self::getExchangeRate($data_acc['productconfig_value'], $ddate);


                            $forexrates_id = $ex_rate_array['forexrates_id'];
                            $ex_rate = $ex_rate_array['forexrates_midrate'];

                            if ($ex_rate == "" || $ex_rate == 0) {

                                self::$lablearray['E01'] = self::$lablearray['696'] . " <br><b>" . $value['PRODUCT_PRODID'] . "</b>";
                                throw new Exception(self::$lablearray['E01']);
                                break 2;
                                // throw new Exception(self::$lablearray['696']." ".$data_acc['CURRENCIES_ID']);
                            }
                        } else {

                            $ex_rate = 1;
                        }

                        break;


                    case 'CA':     // cash
                        $_cash = self::$connObj->SQLSelect("SELECT chartofaccounts_accountcode,currencies_id FROM " . TABLE_CASHACCOUNTS . " WHERE  chartofaccounts_accountcode='" . $value['GLACC'] . "' AND branch_code='" . $value['BRANCHCODE'] . "'");

                        if ($_cash[0]['chartofaccounts_accountcode'] == "") {
                            self::getlables("367", "", "", self::$connObj);
                            throw new Exception(self::$lablearray['367'] . ' ' . $value['GLACC']);
                        } else {
                            $currency = $_cash[0]['currencies_id'];
                        }

                        break;

                    case 'BK':   // Bank GL


                        break;

                    case 'SP':   // Suspence

                        $_pararesult = self::$connObj->SQLSelect("SELECT productconfig_paramname,productconfig_value,productconfig_ind,productconfig_grp FROM " . TABLE_PRODUCTCONFIG . " WHERE  productconfig_paramname IN ('SUSPENCE_ACC','CURRENCIES_ID') AND product_prodid='" . $value['PRODUCT_PRODID'] . "' AND branch_code='" . $value['BRANCHCODE'] . "'");
                        $_result = self::searchArray($_pararesult, 'productconfig_paramname', 'CURRENCIES_ID');


                        $currency = $_result['productconfig_value'];

                        $value['CURRENCIES_ID'] = $currency;

                        $data_acc = self::searchArray($_pararesult, 'productconfig_paramname', 'SUSPENCE_ACC');

                        $value['GLACC'] = $data_acc['productconfig_value'];

                        if ($value['GLACC'] == '') {
                            // throw new Exception(self::$lablearray['772'].' '.self::$lablearray['1203']);
                            self::$lablearray['E01'] = self::$lablearray['704'] . ' ' . self::$lablearray['1203'] . ' ' . $value['PRODUCT_PRODID'];
                            break 2;
                        }

                        if ($ex_rate == "" || $ex_rate == 0) {
                            //throw new Exception(self::$lablearray['1193']." ".self::$lablearray['1203']);

                            self::$lablearray['E01'] = self::$lablearray['1193'] . " " . self::$lablearray['1203'];
                            break 2;
                        }
                        break;

                    case 'CQ':   // Cheque
                    case 'DB':   // direct to bank 
                        // check see if gl accoun is passed.
                        if ($value['GLACC'] == "" && $value['BANKID'] != "") {
                            $bankbranches_acc = self::$connObj->SQLSelect("select bb.chartofaccounts_accountcode as acc,b.banks_name,bb.bankbranches_name from " . TABLE_BANKBRANCHES . " bb," . TABLE_BANKS . " b where bb.banks_id=b.banks_id AND bankbranches_id='" . $value['BANKID'] . "'");

                            $value['GLACC'] = $bankbranches_acc[0]['acc'];
                        }

                        if ($bankbranches_acc[0]['acc'] == '') {

                            self::$lablearray['E01'] = self::$lablearray['772'] . ' ' . self::$lablearray['67'] . ' ' . $bankbranches_acc[0]['banks_name'] . ' ' . $bankbranches_acc['bankbranches_name'];
                            break 2;
                            // throw new Exception(self::$lablearray['772'].' '.self::$lablearray['67']);
                        }

                        $options['DESC'] = $bankbranches_acc[0]['banks_name'] . ' ' . $bankbranches_acc[0]['bankbranches_name'] . ' ' . $options['DESC'];
                        break;

                    case 'SD':
                    case 'SI':
                    case 'SW':
                    case 'SC':
                    case 'LR-SA':
                    case 'DI-SA':
                    case 'SA':
                    case 'IT':
                    case 'TD':
                    case 'TM':
                    case 'TW':
                    case 'TR':
                    case 'DINT':
                        // check balance
                        // check savings balance
//                        if ($value['TTYPE'] == 'SC' || $value['TTYPE'] == 'SW' || $value['TTYPE'] == 'SA' || $value['TTYPE'] == 'LR-SA') {
//
//                            if($value['SIDE']!='CR'):
//                                $bal_array = self::$connObj->SQLSelect("SELECT IFNULL(SUM(savtransactions_amount),0) bal FROM " . TABLE_SAVTRANSACTIONS . " WHERE product_prodid='" . $value['PRODUCT_PRODID'] . "' AND savaccounts_account='" . $value['SAVACC'] . "' AND savtransactions_tday<='" . $value['DATE'] . "' AND members_idno='".$value['MEMID']."'");
//
//                                //CHECK CLIENT BALANCE
//                                if ($bal_array[0]['bal'] < abs($value['AMOUNT'])) {
//                                    self::$lablearray['E01'] = self::$lablearray['1216'] . ' ' . $value['SAVACC'] . ' ' . $value['PRODUCT_PRODID'];
//                                    break 2;
//                                    //throw new Exception(self::$lablearray['1216'].' '.$value['SAVACC'].' '.$value['PRODUCT_PRODID']);                  
//                                }
//                            endif;                            
//                            
//                        }

                        switch ($value['TTYPE']):
                            case 'TD':
                            case 'TM':
                            case 'TW':
                            case 'TR':
                            case 'DINT':
                            case 'SC':
                                $query_array = self::$connObj->SQLSelect("SELECT productconfig_paramname,productconfig_value,productconfig_ind,productconfig_grp FROM " . TABLE_PRODUCTCONFIG . " WHERE  productconfig_paramname IN ('TIMEDEPOSIT_ACC','INT_TD_ACC','CURRENCIES_ID','SERVICE_FEE_ACC') AND product_prodid='" . $value['PRODUCT_PRODID'] . "' AND branch_code='" . $value['BRANCHCODE'] . "'");
                                break;

                            default:
                                $query_array = self::$connObj->SQLSelect("SELECT productconfig_paramname,productconfig_value,productconfig_ind,productconfig_grp FROM " . TABLE_PRODUCTCONFIG . " WHERE  productconfig_paramname IN ('SAVINGS_ACC','CURRENCIES_ID') AND product_prodid='" . $value['PRODUCT_PRODID'] . "' AND branch_code='" . $value['BRANCHCODE'] . "'");
                                break;

                        endswitch;


                        $_result = self::searchArray($query_array, 'productconfig_paramname', 'CURRENCIES_ID');
                        $currency = $_result['productconfig_value'];


                        if (SETTTING_CURRENCY_ID != $currency) {
                            if (!isset($value['DATE'])) {
                                $ddate = self::functiongetcurrentDateTime();
                            } else {
                                $ddate = $value['DATE'];
                            }

                            $ex_rate_array = self::getExchangeRate($currency, $ddate);

                            $forexrates_id = $ex_rate_array['forexrates_id'];
                            $ex_rate = $ex_rate_array['forexrates_midrate'];

                            if ($ex_rate == "" || $ex_rate == 0) {
                                self::$lablearray['E01'] = self::$lablearray['696'] . " " . $data_acc[$column1] . " " . $data_acc['CURRENCIES_ID'];
                                throw new Exception(self::$lablearray['696'] . " " . $data_acc['CURRENCIES_ID']);
                                break 2;
                                // throw new Exception(self::$lablearray['696']." ".$data_acc['CURRENCIES_ID']);
                            }
                        } else {

                            $ex_rate = 1;
                        }

                        switch ($value['TTYPE']):
                            case 'TD':
                            case 'TM':
                            case 'TW':
                            case 'TR':
                                $data_acc = self::searchArray($query_array, $column1, 'TIMEDEPOSIT_ACC');
                                break;
                            case 'SC':
                                $data_acc = self::searchArray($query_array, $column1, 'SERVICE_FEE_ACC');
                                break;
                            case 'DINT':
                                $data_acc = self::searchArray($query_array, $column1, 'INT_TD_ACC');
                                break;

                            default:
                                $data_acc = self::searchArray($query_array, $column1, 'SAVINGS_ACC');
                                break;

                        endswitch;

                        if ($data_acc[$column2] == '') {

                            self::$lablearray['E01'] = self::$lablearray['772'] . ' ' . $value['PRODUCT_PRODID'];
                            throw new Exception(self::$lablearray['772'] . ' ' . $value['PRODUCT_PRODID']);
                            break 2;
                            // throw new Exception(self::$lablearray['772'].' '.$value['PRODUCT_PRODID']);
                        }
                        $value['GLACC'] = $data_acc[$column2];


//
//                            //TODO: check overdrafts
//                            //TODO: check savings loan guarantees                    
//                        }
                        break;

                    default:

                        self::$lablearray['E01'] = "No transaction type specified";
                        break;
                }

//                if($prevLinecurrency!=''){
//                    
//                    // check see if currenyes of the transaction are consitent
//                    if($prevLinecurrency!=$currency):
//                      throw new Exception(self::$lablearray['1216'])  
//                    endif;
//                }
//                
//                $prevLinecurrency = $currency;

                if (SETTTING_CURRENCY_ID != $currency) {
                    $value['FXAMT'] = $value['AMOUNT'];
                } else {
                    $value['FXAMT'] = 0;
                }

                if ($value['SIDE'] == 'DR') {
                    $nDr = $value['AMOUNT'] * $ex_rate;
                }

                if ($value['SIDE'] == 'CR') {
                    $nCr = $value['AMOUNT'] * $ex_rate;
                }

                $value['USERID'] = $_SESSION['user_id'];
                $value['CURRENCIES_ID'] = $currency;

                $value['DEBIT'] = $nDr;
                $value['CREDIT'] = $nCr;
                $value['TRANCODE'] = $_trancode;

                $value['FXID'] = $forexrates_id;
            }
        } catch (Exception $ex) {

            self::$error = $ex->getMessage();

            throw new Exception(self::$error);
        }
    }

    /* DESCRIPTION:  THIS FUNCTION IS USED TO DECODE SERIALISED FORM DATA
     * PARAMETERS:
     * $serialiseddata - data that has been serialised nd send form the form
     * RETURN: array is returned
     */

    public static function decodeSerialisedPagedata($serialiseddata) {
        $objects = (array) json_decodeData($serialiseddata);

        $temparray = self::convertobjectToArray($objects['pageinfo']);

        $formdata = self::array_flatten($temparray);

        return $formdata;
    }

    /* DESCRIPTION:  THIS FUNCTION IS USED TO AN OBJECT TO AN ARRAY
     * PARAMETERS:
     * $theobj  - the object
     * RETURN: array 
     */

    public static function convertobjectToArray($d) {

        if (is_object($d)) {
            // Gets the properties of the given object
            // with get_object_vars function
            $d = get_object_vars($d);
        }

        if (is_array($d)) {
            /*
             * Return array converted to object
             * Using __FUNCTION__ (Magic constant)
             * for recursive call
             */
            return array_map(['Common', 'convertobjectToArray'], $d);
        } else {
            // Return array
            return $d;
        }
    }

    /* DESCRIPTION:  THIS FUNCTION IS USED TO PREPARELIKS FOR THE GRID
     * PARAMETERS:
     * $frmid - from id
     * $elementID: Element ID
     * NOTE:
     */

     public static function prepareLinks($frmid, $action, $elementID) {
        $link = "<a href='#' onClick=\"getinfo('{$_POST['frmid']}', $( 'body').data('gridchk'), '{$action}', '', ";
        switch ($frmid) {
            case 'frmShares':
            case 'frmClients':
            case 'frmSave':
                $link .= "'load.php', '{$elementID}')\"><img src='images/plus.png' border='0' title='Click to load selection details'></a>";
                break;
    
            case 'frmmanagetransactions':
                $link .= "'addedit.php', '{$elementID}')\"><img src='images/icons/reverse.png' border='0' alt='Reverse selected transaction' title='Reverse selected transaction'></a>";
                break;
    
            default:
                $link .= "'addedit.php', '{$elementID}')\"><img src='images/plus.png' border='0' title='Click to load selection details'></a>";
                break;
        }
        return $link;
    }   

    // This function is used to search for an array element within an array        
    public static function searchArray($array, $key, $value) {

        if (is_array($array)) {

            foreach ($array as $k => $val) {

                if (is_array($val)) {
                    if ($val[$key] == $value) {
                        return $val;
                        break;
                    } else {
                        self::searchArray($val, $key, $value);
                    }
                }
            }
        }

        return array();
    }

    // This function is used to search for a value of a key in a 2D array    
    public static function searchForId($array, $searchkey, $searchval, $returncolumn) {
        foreach ($array as $key => $val) {
            if (is_array($val)):
                if ($val[$searchkey] == $searchval):
                    return $val[$returncolumn];
                endif;
            endif;
        }

        return '0';
    }

    // get all savings accounts for a client with a particular product
    public static function getSavingsAccountForProductNoNames($Ids = "", $product_prodid = "", $cRoot = "") {

//        if (!is_object($Conn)) {
//            $Conn = self::$connObj;
//        }

        if ($cRoot == "S") { // Savings
            $accounts_array = self::$connObj->SQLSelect("SELECT product_prodid,savaccounts_account FROM " . TABLE_SAVACCOUNTS . " WHERE client_idno IN ('" . $Ids . "') AND product_prodid='" . $product_prodid . "' AND (savaccounts_closedate ='0000-00-00' OR savaccounts_closedate IS NULL)");
        } elseif ($cRoot == "L") {// Loans    
            $accounts_array = self::$connObj->SQLSelect("SELECT l.loan_number as name ,s.savaccounts_account as value FROM " . TABLE_SAVACCOUNTS . " s," . TABLE_LOAN . " l WHERE l.loan_number IN ('" . $Ids . "') AND l.client_idno=s.client_idno AND s.product_prodid='" . $product_prodid . "' AND (s.savaccounts_closedate ='0000-00-00' OR s.savaccounts_closedate IS NULL) GROUP BY l.loan_number,s.savaccounts_account");
        } else {
            
        }
        if (count($accounts_array) > 0) {
            return $accounts_array;
            //return self::array_flatten($accounts_array);
        } else {
            return "";
        }
    }

//    public static function get_array_elements_with_key_in_3D_array($a, $k) {
//        $r = [];
//        array_walk_recursive ($a, function ($item, $key) use ($k, &$r) {if ($key == $k) $r[] = $item;}                           );
//        return $r;
//    }
    // this funtion is used to search for array element that have name in a 2dimension array?
    public static function get_array_elements_with_key_in_3D_array($my_array, $nkeyname) {
        if (is_array($my_array) && count($my_array) > 0) {
            foreach ($my_array as $key => $val) {
                if ($val['name'] == $nkeyname):
                    $new_array[$nkeyname] = $val['value'];
                endif;
            }
        }
        return ($new_array??array());
    }

    // this funtion is used to search for array element that have keys like?
    public static function get_array_elements_with_key($array, $key) {

        $filtered = array_filter($array, function($k) use (&$key) {
            return preg_match('(' . $key . ')', $k, $match);
        }, ARRAY_FILTER_USE_KEY);

        return $filtered;
    }

    //this function is used to extract selements with a specific key in a multidimentional array
    public static function getArrayElementswithKey($arr, $skey) {
        $field_list = array();

        array_walk_recursive($arr, function($v, $k) use($key, &$field_list) {
            // if value is not 'fieldlist' then its a column number
            if ($k == $skey)
                array_push($field_list, 'E' . $v);
        });

        return $field_list;
    }

    // this function is used to replace a keyname in an array
    public static function replace_key_function(&$array, $key1, $key2) {
        $keys = array_keys($array);
        $index = array_search($key1, $keys);

        if ($index !== false) {
            $keys[$index] = $key2;
            $array = array_combine($keys, $array);
        }else{
            $array[$key2]=$array[$key2]??'';   
        }

        return $array;
    }

    public static function functiongetcurrentDateTime() {
        return Date('Y-m-d H:i:s');
    }

    public static function DrawComboFromArray($allaccounts = array(), $fieldname = '', $selected_id = '', $type = 'combo', $onChange = "", $ctype = "", $frmid = "") {

        switch ($type) {
            case 'REF_PRIORITY':

                $lablearray = getlables("1144,1486,1485,1356,1487,1181,1105,1181,1356,1488");
                $html_out = "<select name='" . $fieldname . "' id='" . $fieldname . "'  onChange='" . $onChange . "' " . $ctype . ">";
                $html_out .= "<option value='' id=''>--------</option>";
                $html_out .= "<option value='INT_COMM_PEN_VAT' id='INT_COMM_PEN_VAT'>" . $lablearray['1486'] . "</option>";
                $html_out .= "<option value='COMM_PEN_VAT' id='COMM_PEN_VAT'>" . $lablearray['1487'] . "</option>";
                $html_out .= "<option value='PEN_VAT' id='PEN_VAT'>" . $lablearray['1488'] . "</option>";
                $html_out .= "<option value='PRIC' id='PRIC'>" . $lablearray['1144'] . "</option>";
                $html_out .= "<option value='INT' id='INT'>" . $lablearray['1145'] . "</option>";
                $html_out .= "<option value='COMM' id='COMM'>" . $lablearray['1105'] . "</option>";
                $html_out .= "<option value='PEN' id='PEN'>" . $lablearray['1181'] . "</option>";
                $html_out .= "<option value='VAT' id='VAT'>" . $lablearray['1356'] . "</option>";

                $html_out .= "</select>";
                break;

            case 'PAY_PRIORITY':
                $lablearray = getlables("1144,1145,1105,1181");
                $html_out = "<select name='" . $fieldname . "' id='" . $fieldname . "'  onChange='" . $onChange . "' " . $ctype . ">";
                //$html_out .= "<option value='PRINC-COM-INT-PEN' id='PRINC-COM-INT-PEN'>Principal Interest Commission  Penalty</option>";

                $html_out .= "<option value='' id=''>--------</option>";


                $html_out .= "<option value='PRINC-INT-COMM-PEN' id='PRINC-INT-COMM-PEN'>" . $lablearray['1144'] . ' ' . $lablearray['1145'] . ' ' . $lablearray['1105'] . ' ' . $lablearray['1181'] . "</option>";

                //$html_out .= "<option value='PRINC-COM-INT-PEN' id='PRINC-COM-INT-PEN'>Principal Commission Interest Penalty</option>";
                $html_out .= "<option value='PRINC-INT-COMM-PEN' id='PRINC-INT-COMM-PEN'>" . $lablearray['1144'] . ' ' . $lablearray['1105'] . ' ' . $lablearray['1145'] . ' ' . $lablearray['1181'] . "</option>";


                // $html_out .=  "<option value='PRINC-COM-PEN-INT' id='PRINC-COM-PEN-INT'>Principal Commission  Penalty Interest</option>";
                $html_out .= "<option value='PRINC-COMM-PEN-INT' id='PRINC-COMM-PEN-INT'>" . $lablearray['1144'] . ' ' . $lablearray['1105'] . ' ' . $lablearray['1181'] . ' ' . $lablearray['1145'] . "</option>";

                //$html_out .=  "<option value='INT-PRINC-COMM-PEN' id='INT-PRINC-COMM-PEN'>Interest Principal Commmission Penalty</option>";
                $html_out .= "<option value='INT-PRINC-COMM-PEN' id='INT-PRINC-COMM-PEN'>" . $lablearray['1145'] . ' ' . $lablearray['1144'] . ' ' . $lablearray['1105'] . ' ' . $lablearray['1181'] . "</option>";

                //  $html_out .=  "<option value='INT-COMM-PRINC-PEN' id='INT-COMM-PRINC-PEN'>Interest Commmission Principal  Penalty</option>";
                $html_out .= "<option value='INT-COMM-PRINC-PEN' id='INT-COMM-PRINC-PEN'>" . $lablearray['1145'] . ' ' . $lablearray['1105'] . ' ' . $lablearray['1144'] . ' ' . $lablearray['1181'] . "</option>";

                // $html_out .=  "<option value='INT-COMM-PEN-PRINC' id='INT-COMM-PEN-PRINC'>Interest Commmission Penalty Principal</option>";
                $html_out .= "<option value='INT-COMM-PEN-PRINC' id='INT-COMM-PEN-PRINC'>" . $lablearray['1145'] . ' ' . $lablearray['1105'] . ' ' . $lablearray['1181'] . ' ' . $lablearray['1144'] . "</option>";

                // $html_out .=  "<option value='INT-PEN-COMM-PRINC' id='INT-PEN-COMM-PRINC'>Interest Penalty Commmission  Principal</option>";
                $html_out .= "<option value='INT-PEN-COMM-PRINC' id='INT-PEN-COMM-PRINC'>" . $lablearray['1145'] . ' ' . $lablearray['1181'] . ' ' . $lablearray['1105'] . ' ' . $lablearray['1144'] . "</option>";


                // $html_out .=  "<option value='INT-PEN-PRINC-COMM' id='INT-PEN-PRINC-COMM'>Interest Penalty  Principal Commmission</option>";
                $html_out .= "<option value='INT-PEN-PRINC-COMM' id='INT-PEN-PRINC-COMM'>" . $lablearray['1145'] . ' ' . $lablearray['1181'] . ' ' . $lablearray['1144'] . ' ' . $lablearray['1105'] . "</option>";

                $html_out .= "<option value='PRINC-INT-PRINC' id='PRINC-INT-PRINC'>" . $lablearray['1144'] . ' ' . $lablearray['1145'] . ' ' . $lablearray['1144'] . " (HBPP)</option>";


                $html_out .= "</select>";
                break;
            case 'combo':
                $html_out = "<select name='" . $fieldname . "' id='" . $fieldname . "' onChange='" . $onChange . "' " . $ctype . ">";
                //$lablearray = getlables("1069");

                if (count($allaccounts) > 0) {

                    foreach ($allaccounts as $key => $value) {

                        if ($key == trim($selected_id)) {
                            $html_out = $html_out . "<option id='" . trim($key) . "' value='" . trim($key) . "' selected >" . $value . "</option>";
                        } else {
                            $html_out = $html_out . "<option id='" . trim($key) . "'  value='" . trim($key) . "'>" . $value . "</option>";
                        }
                    }
                }

                $html_out = $html_out . '</select>';

                break;


            case 'banks':

                $cwhere = "";
                $lablearray = getlables("998,999");

                //if(isset($_SESSION['licence_build'])){
                //$banks_query = tep_db_query("SELECT licence_build,licence_organisationname FROM ".TABLE_LICENCE."  WHERE licence_build='".$_SESSION['licence_build']."' ".$cwhere." ORDER BY licence_organisationname ASC");
                //	$selected_id = $_SESSION['licence_build'];
                //	}else{
                //return ' <b>'.$lablearray['998'].'</b>';
                //}
                $banks_query = tep_db_query("SELECT licence_build,licence_organisationname FROM " . TABLE_LICENCE . " ORDER BY licence_organisationname ASC");
                $html_out = "<select name='" . $fieldname . "' id='" . $fieldname . "'  onChange='" . $onChange . "'>";
                $html_out = $html_out . "<option id='' value=''>-----</option>";
                while ($banks = tep_db_fetch_array($banks_query)) {
                    if ($banks['licence_build'] == $selected_id) {
                        $html_out = $html_out . "<option id='" . $banks['licence_build'] . "' value='" . $banks['licence_build'] . "' selected >" . $banks['licence_organisationname'] . "</option>";
                    } else {
                        $html_out = $html_out . "<option id='" . $banks['licence_build'] . "'  value='" . $banks['licence_build'] . "'>" . $banks['licence_organisationname'] . "</option>";
                    }
                }
                $html_out = $html_out . '</select>';

                break;

            case 'operatorbranches':

                if ($_SESSION['user_accesscode'] != "") {
                    $cwhere = "  AND user_accesscode='" . $_SESSION['user_accesscode'] . "'"; // AND branch_code='".$_SESSION['branch_code']."'";
                }

                $banks_query = tep_db_query("SELECT bankbranches_id,ob.branch_code,bankbranches_name FROM " . TABLE_USERBRANCHES . " ub, " . TABLE_OPERATORBRANCHES . " ob  WHERE ub.branch_code=ob.branch_code " . $cwhere . " ORDER BY bankbranches_name ASC");



                $html_out = "<select name='" . $fieldname . "' id='" . $fieldname . "'  onChange='" . $onChange . "' " . $ctype . ">";

                $html_out = $html_out . "<option value='' id=''>-------</option>";

                while ($banks = tep_db_fetch_array($banks_query)) {
                    if ($banks['bankbranches_id'] == $selected_id) {
                        $html_out = $html_out . "<option id='" . $banks['branch_code'] . "' value='" . $banks['branch_code'] . "' selected >" . $banks['bankbranches_name'] . "</option>";
                    } else {
                        $html_out = $html_out . "<option id='" . $banks['branch_code'] . "'  value='" . $banks['branch_code'] . "'>" . $banks['bankbranches_name'] . "</option>";
                    }
                }
                $html_out = $html_out . '</select>';

                break;

            case 'USERS':

                if ($selected_id != "") {
                    $cwhere = "WHERE user_id='" . $selected_id . "'";
                }

                $banks_query = tep_db_query("SELECT user_id,user_firstname,user_lastname FROM " . TABLE_USERS . " " . $cwhere . " ORDER BY user_id DESC");

                $html_out = "<select name='" . $fieldname . "' id='" . $fieldname . "'  onChange='" . $onChange . "'>";

                $html_out = $html_out . "<option value='' id='' selected>-------</option>";

                while ($user = tep_db_fetch_array($banks_query)) {
                    if ($key == $selected_id) {
                        $html_out = $html_out . "<option id='" . $user['user_id'] . "' value='" . $user['user_id'] . "' >" . $user['user_firstname'] . " " . $user['user_lastname'] . "</option>";
                    } else {
                        $html_out = $html_out . "<option id='" . $user['user_id'] . "'  value='" . $user['user_id'] . "'>" . $user['user_firstname'] . " " . $user['user_lastname'] . "</option>";
                    }
                }
                $html_out = $html_out . '</select>';

                break;

            case 'roles':

                if ($selected_id != "") {
                    $cwhere = "WHERE roles_id='" . $selected_id . "'";
                }

                $banks_query = tep_db_query("SELECT roles_id,roles_name FROM " . TABLE_ROLES);

                $html_out = "<select name='" . $fieldname . "' id='" . $fieldname . "'  onChange='" . $onChange . "'>";

                $html_out = $html_out . "<option value='' id='' selected>-------</option>";

                while ($roles = tep_db_fetch_array($banks_query)) {
                    if ($key == $selected_id) {
                        $html_out = $html_out . "<option id='" . $roles['roles_id'] . "' value='" . $roles['roles_id'] . "'  >" . $roles['roles_name'] . "</option>";
                    } else {
                        $html_out = $html_out . "<option id='" . $roles['roles_id'] . "'  value='" . $roles['roles_id'] . "'>" . $roles['roles_name'] . "</option>";
                    }
                }
                $html_out = $html_out . '</select>';

                break;

            case 'TRANCODES':

                $fieldname2 = $fieldname;
                $fieldname = 'trancodes_en';

                switch (P_LANG) {


                    case 'FR':
                        $fieldname = 'trancodes_fr';
                        break;

                    case 'SP':
                        $fieldname = 'trancodes_sp';
                        break;

                    case 'SWA':
                        $fieldname = 'trancodes_swa';
                        break;

                    case 'LUG':
                        $fieldname = 'trancodes_lug';
                        break;

                    default:
                        break;
                }



                $banks_query = tep_db_query("SELECT trancodes_code," . $fieldname . " label FROM " . TABLE_TRANCODES . " ORDER BY " . $fieldname . " ASC");

                $fieldname = $fieldname2;

                $html_out = "<select name='" . $fieldname . "' id='" . $fieldname . "'  onChange='" . $onChange . "'>";

                $html_out = $html_out . "<option value='' id='' selected>-------</option>";

                while ($modules = tep_db_fetch_array($banks_query)) {
                    if ($modules['label'] == $selected_id) {
                        $html_out = $html_out . "<option id='" . $modules['trancodes_code'] . "' value='" . $modules['trancodes_code'] . "'  >" . $modules['label'] . "</option>";
                    } else {
                        $html_out = $html_out . "<option id='" . $modules['trancodes_code'] . "'  value='" . $modules['trancodes_code'] . "'>" . $modules['label'] . "</option>";
                    }
                }
                $html_out = $html_out . '</select>';


                break;

            case 'CASHACCOUNTS':

                if ($selected_id != "") {
                    //$cwhere = " WHERE roles_id='".$_sselected_id."'";
                }

                $banks_query = tep_db_query("SELECT  chartofaccounts_accountcode,cashaccounts_name FROM " . TABLE_ROLESCASHACCOUNTS . " rcc INNER JOIN " . TABLE_CASHACCOUNTS . " cc ON cc.chartofaccounts_accountcode=rcc.chartofaccounts_accountcode " . $cwhere);


                $html_out = "<select name='" . $fieldname . "' id='" . $fieldname . "'  onChange='" . $onChange . "'>";

                $html_out = $html_out . "<option value='' id='' selected>-------</option>";

                while ($modules = tep_db_fetch_array($banks_query)) {
                    if ($key == $selected_id) {
                        $html_out = $html_out . "<option id='" . $modules['chartofaccounts_accountcode'] . "' value='" . $modules['chartofaccounts_accountcode'] . "'  >" . $modules['chartofaccounts_accountcode'] . " " . $modules['cashaccounts_name'] . "</option>";
                    } else {
                        $html_out = $html_out . "<option id='" . $modules['chartofaccounts_accountcode'] . "'  value='" . $modules['chartofaccounts_accountcode'] . "'>" . $modules['chartofaccounts_accountcode'] . " " . $modules['cashaccounts_name'] . "</option>";
                    }
                }
                $html_out = $html_out . '</select>';


                break;

            case 'SAV_AT_REPAY':
                $lablearray1 = getlables("1511,1512");

                $html_out = "<select name='" . $fieldname . "' id='" . $fieldname . "'  onChange='" . $onChange . "'>";
                $html_out = $html_out . "<option value='' id='' >-------</option>";
                $html_out = $html_out . "<option value='0' id='0' >" . $lablearray1['1511'] . "</option>";
                $html_out = $html_out . "<option value='1' id='1' >" . $lablearray1['1512'] . "</option>";

                $html_out = $html_out . '</select>';

                break;

            case 'INSTYPE':

                self::getlables("44,1117,1118,1119,45,1121,1122,1165", "", "", self::$connObj);
                $html_out = "<select name='" . $fieldname . "' id='" . $fieldname . "'  onChange='" . $onChange . "'>";
                $html_out = $html_out . "<option value='' id='' >-------</option>";
                $html_out = $html_out . "<option value='W' id='W' >" . self::$lablearray['44'] . "</option>";
                $html_out = $html_out . "<option value='BW' id='BW' >" . self::$lablearray['1117'] . "</option>";
                $html_out = $html_out . "<option value='FW' id='FW' >" . self::$lablearray['1119'] . "</option>";
                $html_out = $html_out . "<option value='M' id='M' >" . self::$lablearray['45'] . "</option>";
                $html_out = $html_out . "<option value='TM' id='TM' >" . self::$lablearray['1121'] . "</option>";
                $html_out = $html_out . "<option value='Q' id='Q' >" . self::$lablearray['1122'] . "</option>";
                $html_out = $html_out . "<option value='A' id='A' >" . self::$lablearray['1165'] . "</option>";
                $html_out = $html_out . '</select>';

                break;
            case 'TPERIOD':

                self::getlables("1549,1599,1430", "", "", self::$connObj);
                $html_out = "<select name='" . $fieldname . "' id='" . $fieldname . "'  onChange='" . $onChange . "'>";
                $html_out = $html_out . "<option value='' id='' >-------</option>";
                $html_out = $html_out . "<option value='D' id='D' >" . self::$lablearray['1549'] . "</option>";
                $html_out = $html_out . "<option value='W' id='W' >" . self::$lablearray['1599'] . "</option>";
                $html_out = $html_out . "<option value='M' id='M' >" . self::$lablearray['1430'] . "</option>";
                $html_out = $html_out . '</select>';

                break;

            case 'INTTYPE':

                self::getlables("1124,1125,1126,1123", "", "", self::$connObj);
                $html_out = "<select name='" . $fieldname . "' id='" . $fieldname . "'  onChange='" . $onChange . "'>";
                $html_out = $html_out . "<option value='' id='' selected>-------</option>";
                $html_out = $html_out . "<option value='FR' id='FR'>" . self::$lablearray['1124'] . "</option>";
                $html_out = $html_out . "<option value='DA' id='DA'>" . self::$lablearray['1125'] . "</option>";
                $html_out = $html_out . "<option value='DD' id='DD'>" . self::$lablearray['1126'] . "</option>";
                $html_out = $html_out . '</select>';
                break;

            case 'FUNDCODE':
                if ($selected_id != "") {
                    $cwhere = "WHERE fund_code='" . $selected_id . "'";
                }

                $fund_query = tep_db_query("SELECT fund_code,fund_name FROM " . TABLE_FUND);


                $html_out = "<select name='" . $fieldname . "' id='" . $fieldname . "'  onChange='" . $onChange . "'>";

                $html_out = $html_out . "<option value='' id='' selected>-------</option>";

                while ($fund = tep_db_fetch_array($fund_query)) {
                    if ($key == $selected_id) {
                        $html_out = $html_out . "<option id='" . $fund['fund_code'] . "' value='" . $fund['fund_code'] . "'  >" . $fund['fund_name'] . "</option>";
                    } else {
                        $html_out = $html_out . "<option id='" . $fund['fund_code'] . "'  value='" . $fund['fund_code'] . "'>" . $fund['fund_name'] . "</option>";
                    }
                }
                $html_out = $html_out . '</select>';
                break;

            case 'CAT1':
                if ($selected_id != "") {
                    $cwhere = "WHERE category1_code='" . $selected_id . "'";
                }

                $cat1_query = tep_db_query("SELECT category1_code,category1_name FROM " . TABLE_CATEGORY1);


                $html_out = "<select name='" . $fieldname . "' id='" . $fieldname . "'  onChange='" . $onChange . "'>";

                $html_out = $html_out . "<option value='' id='' selected>-------</option>";

                while ($cat = tep_db_fetch_array($cat1_query)) {
                    if ($key == $selected_id) {
                        $html_out = $html_out . "<option id='" . $cat['category1_code'] . "' value='" . $cat['category1_code'] . "'  >" . $cat['category1_name'] . "</option>";
                    } else {
                        $html_out = $html_out . "<option id='" . $cat['category1_code'] . "'  value='" . $cat['category1_code'] . "'>" . $cat['category1_name'] . "</option>";
                    }
                }
                $html_out = $html_out . '</select>';
                break;

            case 'CAT2':
                if ($selected_id != "") {
                    $cwhere = "WHERE category2_code='" . $selected_id . "'";
                }

                $cat2_query = tep_db_query("SELECT category2_code,category2_name FROM " . TABLE_CATEGORY2);


                $html_out = "<select name='" . $fieldname . "' id='" . $fieldname . "'  onChange='" . $onChange . "'>";

                $html_out = $html_out . "<option value='' id='' selected>-------</option>";

                while ($cat = tep_db_fetch_array($cat2_query)) {
                    if ($cat['category3_code'] == $selected_id) {
                        $html_out = $html_out . "<option id='" . $cat['category2_code'] . "' value='" . $cat['category2_code'] . "'  >" . $cat['category2_name'] . "</option>";
                    } else {
                        $html_out = $html_out . "<option id='" . $cat['category2_code'] . "'  value='" . $cat['category2_code'] . "'>" . $cat['category2_name'] . "</option>";
                    }
                }
                $html_out = $html_out . '</select>';
                break;

            case 'CAT3':
                if ($selected_id != "") {
                    $cwhere = "WHERE category3_code='" . $selected_id . "'";
                }

                $cat3_query = tep_db_query("SELECT category3_code,category3_name FROM " . TABLE_CATEGORY3);


                $html_out = "<select name='" . $fieldname . "' id='" . $fieldname . "'  onChange='" . $onChange . "'>";

                $html_out = $html_out . "<option value='' id='' selected>-------</option>";

                while ($cat = tep_db_fetch_array($cat3_query)) {
                    if ($cat['category3_code'] == $selected_id) {
                        $html_out = $html_out . "<option id='" . $cat['category3_code'] . "' value='" . $cat['category3_code'] . "'  >" . $cat['category3_name'] . "</option>";
                    } else {
                        $html_out = $html_out . "<option id='" . $cat['category3_code'] . "'  value='" . $cat['category3_code'] . "'>" . $cat['category3_name'] . "</option>";
                    }
                }
                $html_out = $html_out . '</select>';
                break;

            case 'PRODUCTS': //  TO BE DEPLETED- SEET generateReportControls()


                if ($selected_id != "") {
                    $cwhere = "WHERE product_prodid='" . $selected_id . "'";
                }

                $product_query = tep_db_query("SELECT product_name,product_prodid FROM " . TABLE_PRODUCT);


                $html_out = "<select name='product_prodid' id='product_prodid'  onChange='" . $onChange . "'>";

                $html_out = $html_out . "<option value='' id='' selected>-------</option>";

                while ($product = tep_db_fetch_array($product_query)) {
                    if ($product['product_prodid'] == $selected_id) {
                        $html_out = $html_out . "<option id='" . $product['product_prodid'] . "' value='" . $product['product_prodid'] . "'  >" . $product['product_prodid'] . " " . $product['product_name'] . "</option>";
                    } else {
                        $html_out = $html_out . "<option id='" . $product['product_prodid'] . "'  value='" . $product['product_prodid'] . "'>" . $product['product_prodid'] . " " . $product['product_name'] . "</option>";
                    }
                }
                $html_out = $html_out . '</select>';

                break;

            case 'LOANPROCESSLEVELS':

                $html_out = "<SELECT name='" . $fieldname . "' id='" . $fieldname . "'  onChange='" . $onChange . "'>
                    <option id='NA' value='NA'>-------</option>
                    <option id='FEEATLA' value='FEEATLA'>Loan Application</option>
                    <option id='FEEATAP' value='FEEATAP'>Loan Approval</option>
                    <option id='FEEATLD' value='FEEATLD'>Loan Disbursement</option>
            </SELECT>";

                break;

            case 'PAYMODES':
                self::getlables("42,311,382,1213,1202", "", "", self::$connObj);

                // $lablearray1 = getlables("42,311,382,1213,1202");
                $html_out = "<SELECT name='" . $fieldname . "' id='" . $fieldname . "'  onChange='" . $onChange . "'>
                   <option id='NA' value=''" . (SETTING_PAYMODE == "" ? 'SELECTED' : "") . ">-------</option>
                   <option id='CA' value='CA'" . (SETTING_PAYMODE == "CA" ? 'SELECTED' : "") . " >" . self::$lablearray['311'] . "</option>
                   <option id='CQ' value='CQ'" . (SETTING_PAYMODE == "CQ" ? 'SELECTED' : "") . ">" . self::$lablearray['382'] . "</option>
                   <option id='SAV' value='SA'" . (SETTING_PAYMODE == "SA" ? 'SELECTED' : "") . ">" . self::$lablearray['1213'] . "</option>
                   <option id='DB' value='DB'" . (SETTING_PAYMODE == "DB" ? 'SELECTED' : "") . ">" . self::$lablearray['1202'] . "</option>
           </SELECT>
            <script>
            // payment mode
            $( '#PAYMODES' ).change(function() {
                showValues('" . $frmid . "','modes','search','PAYMODES','load.php',$('#PAYMODES').val());
            });
            </script>
            ";

                break;

            case 'SAVTTYPES':
                self::getlables("42,1027,1028,1212", "", "", self::$connObj);

                $html_out = "<select id='" . $fieldname . "' name='" . $fieldname . "'>
            <option id='' value='' >-------</option>
            <option id='SD' value='SD'>" . self::$lablearray['1027'] . "</option>
            <option id='SW' value='SW'>" . self::$lablearray['1028'] . "</option>
            <option id='SA' value='SA'>" . self::$lablearray['1212'] . "</option>
            </select>
            <script>
            // payment mode
            $( '#PAYMODES' ).change(function() {
                showValues('" . $frmid . "','modes','search','PAYMODES','load.php',$('#PAYMODES').val());
            });
            </script>
            ";

                break;
            case 'SHARETTYPES':
                self::getlables("1703,1704,171", "", "", self::$connObj);

                $html_out = "<select id='" . $fieldname . "' name='" . $fieldname . "'>
            <option id='' value='' >-------</option>
            <option id='SBH' value='SD'>" . self::$lablearray['1703'] . "</option>
            <option id='SSH' value='SW'>" . self::$lablearray['1704'] . "</option>
            <option id='SA' value='SA'>" . self::$lablearray['171'] . "</option>
            </select>
            <script>
            // payment mode
            $( '#PAYMODES' ).change(function() {
                showValues('" . $frmid . "','modes','search','PAYMODES','load.php',$('#PAYMODES').val());
            });
            </script>
            ";

                break;
            case 'TDSTATUS':
                self::getlables("429,1028,1597,1623", "", "", self::$connObj);

                $html_out = "<select id='" . $fieldname . "' name='" . $fieldname . "'>            
            <option id='TD' value='TD' selected >" . self::$lablearray['429'] . "</option>
            <option id='TW' value='TW'>" . self::$lablearray['1028'] . "</option>
            <option id='TR' value='TR'>" . self::$lablearray['1597'] . "</option>
            <option id='TM' value='TM'>" . self::$lablearray['1623'] . "</option>
   
            </select>
            <script>
            // payment mode
            $( '#PAYMODES' ).change(function() {
                showValues('" . $frmid . "','modes','search','PAYMODES','load.php',$('#PAYMODES').val());
            });
            </script>
            ";

                break;

            case 'TDSTATUS2':
                self::getlables("990,1628,1629,1630", "", "", self::$connObj);

                $html_out = "<select id='" . $fieldname . "' name='" . $fieldname . "'>            
            <option id='TD' value='TD' selected >" . self::$lablearray['1628'] . "</option>
            <option id='TW' value='TW'>" . self::$lablearray['990'] . "</option>
            <option id='TR' value='TR'>" . self::$lablearray['1629'] . "</option>
            <option id='TM' value='TM'>" . self::$lablearray['1630'] . "</option>   
            </select>
            <script>
            // payment mode
            $( '#PAYMODES' ).change(function() {
                showValues('" . $frmid . "','modes','search','PAYMODES','load.php',$('#PAYMODES').val());
            });
            </script>
            ";

                break;
            case 'FEES':

                $fees_query = tep_db_query("SELECT fees_id,fees_name FROM " . TABLE_FEES);

                $html_out = "<SELECT name='" . $fieldname . "' id='" . $fieldname . "'  onChange='" . $onChange . "'>";

                $html_out = $html_out . "<option value='' id='' selected>-------</option>";

                while ($product = tep_db_fetch_array($fees_query)) {

                    $html_out = $html_out . "<option id='" . $product['fees_id'] . "' value='" . $product['fees_id'] . "'  >" . $product['fees_name'] . "</option>";
                }
                $html_out = $html_out . '</SELECT>';

                break;

            case 'CURRENCIES':

                $currencies_query = tep_db_query("SELECT currencies_id,name,currencies_code FROM " . TABLE_CURRENCIES);


                $html_out = "<SELECT name='" . $fieldname . "' id='" . $fieldname . "'  onChange='" . $onChange . "'>";

                $html_out = $html_out . "<option value='' id='' selected>-------</option>";

                while ($product = tep_db_fetch_array($currencies_query)) {
                    if ($product['currencies_id'] == trim($selected_id)) {
                        $html_out = $html_out . "<option id='" . $product['currencies_id'] . "' value='" . $product['currencies_id'] . "' selected >" . $product['name'] . " " . $product['currencies_code'] . "</option>";
                    } else {

                        $html_out = $html_out . "<option id='" . $product['currencies_id'] . "' value='" . $product['currencies_id'] . "'  >" . $product['name'] . " " . $product['currencies_code'] . "</option>";
                    }
                }
                $html_out = $html_out . '</SELECT>';
                break;

            case 'SAVPROD':

                $currencies_query = tep_db_query("SELECT product_prodid,product_name FROM " . TABLE_PRODUCT . " WHERE LEFT(product_prodid,1)='S'");


                $html_out = "<SELECT name='" . $fieldname . "' id='" . $fieldname . "'  onChange='" . $onChange . "'>";

                $html_out = $html_out . "<option value='' id=''>-------</option>";

                while ($product = tep_db_fetch_array($currencies_query)) {
                    if ($product['product_prodid'] == $selected_id) {
                        $html_out = $html_out . "<option id='" . $product['product_prodid'] . "' value='" . $product['product_prodid'] . "' selected >" . $product['product_name'] . " " . $product['product_prodid'] . "</option>";
                    } else {

                        $html_out = $html_out . "<option id='" . $product['product_prodid'] . "' value='" . $product['product_prodid'] . "'  >" . $product['product_name'] . " " . $product['product_prodid'] . "</option>";
                    }
                }
                $html_out = $html_out . '</SELECT>';
                break;
            case 'SAVPROD':

                $currencies_query = tep_db_query("SELECT product_prodid,product_name FROM " . TABLE_PRODUCT . " WHERE LEFT(product_prodid,1)='S'");


                $html_out = "<SELECT name='" . $fieldname . "' id='" . $fieldname . "'  onChange='" . $onChange . "'>";

                $html_out = $html_out . "<option value='' id=''>-------</option>";

                while ($product = tep_db_fetch_array($currencies_query)) {
                    if ($product['product_prodid'] == $selected_id) {
                        $html_out = $html_out . "<option id='" . $product['product_prodid'] . "' value='" . $product['product_prodid'] . "' selected >" . $product['product_name'] . " " . $product['product_prodid'] . "</option>";
                    } else {

                        $html_out = $html_out . "<option id='" . $product['product_prodid'] . "' value='" . $product['product_prodid'] . "'  >" . $product['product_name'] . " " . $product['product_prodid'] . "</option>";
                    }
                }
                $html_out = $html_out . '</SELECT>';
                break;

            case 'TPROD':

                $product_query = tep_db_query("SELECT product_name,product_prodid FROM " . TABLE_PRODUCT . " WHERE LEFT(product_prodid,1)='T'");


                $html_out = "<select name='" . $fieldname . "' id='" . $fieldname . "'  onChange='" . $onChange . "'>";

                $html_out = $html_out . "<option value='' id='' selected>-----</option>";

                while ($product = tep_db_fetch_array($product_query)) {
                    if ($product['product_prodid'] == $selected_id) {
                        $html_out = $html_out . "<option id='" . $product['product_prodid'] . "' value='" . $product['product_prodid'] . "'  >" . $product['product_prodid'] . " " . $product['product_name'] . "</option>";
                    } else {
                        $html_out = $html_out . "<option id='" . $product['product_prodid'] . "'  value='" . $product['product_prodid'] . "'>" . $product['product_prodid'] . " " . $product['product_name'] . "</option>";
                    }
                }
                $html_out = $html_out . '</select>';

                break;


            case 'LOANPRODGLACC':
                self::getlables("1442,1461,105,1463,1462,1145,1105,401,1469,1464,1465,1181,1464,1203,1466,1467,1468,1442,1181", "", "", self::$connObj);
                //  $lablearray= getlables("");	
                $glitems = array();
                $glitems = array(
                    'SERVICE_FEE_ACC' => self::$lablearray['1442'],
                    'PRINCIPAL_OUTSTANDING_ACC' => self::$lablearray['1461'],
                    'PROV_BAD_DEBTS_ACC' => self::$lablearray['105'],
                    'PROV_COST_ACC' => self::$lablearray['1462'],
                    'INT_RECEIVED_ACC' => self::$lablearray['401'],
                    'PEN_RECEIVED_ACC' => self::$lablearray['1181'],
                    'LOANS_WRITTEN_OFF_ACC' => self::$lablearray['1463'],
                    'ACCRUED_INTEREST_ACC' => self::$lablearray['1464'],
                    'LOANS_RECOVERED_ACC' => self::$lablearray['1465'],
                    'SUSPENCE_ACC' => self::$lablearray['1203'],
                    'ACCRUED_PENALTIES_ACC' => self::$lablearray['1466'],
                    'CURRENCY_DIFF_ACC' => self::$lablearray['1467'],
                    'LOAN_COMMISSION_ACC' => self::$lablearray['1468'],
                    'LOAN_OVERPAYMENT_ACC' => self::$lablearray['1469']);


                asort($glitems);

                $html_out = "<select name='" . $fieldname . "' id='" . $fieldname . "'  onChange='" . $onChange . "'>";

                $html_out = $html_out . "<option value='' id='' selected>-------</option>";

                foreach ($glitems as $key => $val) {

                    $html_out = $html_out . "<option id='" . $key . "' value='" . $key . "'  >" . $val . "</option>";
                }
                $html_out = $html_out . '</select>';

                break;

            case 'COACOMBO':


                if (count(self::$accounts_array) == 0) {
                    self::$accounts_array = getAccountLevels($id = 0, $type = '');
                }

                $html_out = "<select name='" . $fieldname . "' id='" . $fieldname . "'  onChange='" . $onChange . "'>";

                foreach (self::$accounts_array as $key => $val) {
                    $html_out = $html_out . $val;
                }
                $html_out = $html_out . "</select>";

                break;
        }

        return $html_out;
    }

    /* DESCRIPTION:  THIS FUNCTION IS USED TO REVERSE LOAN
     * PARAMETERS:   
     * $modules : string of modules to be affected S: Savings L: Loans T: Time deposits O:Overfradts etc e.g SLO
     * NOTE:
     */

    public static function deleteItem($id, $item) {


        $parameters_final['branch_code'] = BRANCHCODE;
        $parameters_final['item'] = $item;
        $parameters_final['id'] = $id;
        $parameters_final['userid'] = $_SESSION['user_id'];
        $parameters_final['plang'] = P_LANG;
        $parameters_final['code'] = 'DELETEITEM';

        $resuts = self::$connObj->sp_call($parameters_final, '');

        return $resuts;
    }

    /* DESCRIPTION:  THIS FUNCTION IS USED TO REVERSE TRANSACTIONS
     * PARAMETERS:   
     * $modules : string of modules to be affected S: Savings L: Loans T: Time deposits O:Overfradts etc e.g SLO
     * NOTE:
     */

    public static function reverseTransaction($transactioncode_array, $modules, $user_id, &$Conn) {
        Bussiness::$Conn->AutoCommit = false;

        Bussiness::$Conn->beginTransaction();

        $thearray['TABLE'] = TABLE_DELETEDTRANS;
        $thearray['TCODE'] = implode(",", $transactioncode_array);
        $thearray['MODULE'] = $modules;
        $thearray['BRCODE'] = '';
        $thearray['UID'] = $_SESSION['user_id'];

        Bussiness::covertArrayToXML(array($thearray), false);

//        $parameters_final['branch_code'] = '';
//        $parameters_final['transactioncodes'] = trim($transactioncodes);
//        $parameters_final['ttype'] = '';       
//        $parameters_final['userid'] = $_SESSION['user_id'];
//        $parameters_final['plang'] = P_LANG;
//
//        // reverse client transaction?      
//        $parameters_final['ttype'] = $modules;

        $thearray['MODULE'] = 'G';

        Bussiness::covertArrayToXML(array($thearray), true);

        $tabledata['xml_data'] = Common::$xml;

        // save 
        Bussiness::PrepareData(array("FORMDATA" => $tabledata, "OPTIONS" => array('' => 1)), TABLE_XMLTRANS, false);

        Bussiness::$Conn->endTransaction();

        // $parameters_final['code'] = 'REVERSETRAN';
        // $resuts = self::$connObj->sp_call($parameters_final, '');

        return true;
    }

    // get several columns from associative array of a results set. For example geting specific columns from array beloe
    //    $recordset = array(
    //        array(
    //            'id' => 2135,
    //            'first_name' => 'John',
    //            'last_name' => 'Doe'
    //        ),
    //        array(
    //            'id' => 3245,
    //            'first_name' => 'Sally',
    //            'last_name' => 'Smith'
    //        )
    //    );
    public static function array_column_multi(array $input, array $column_keys) {
        $result = array();
        $notflat = unserialize($_SESSION['parameters']);
        $parameters = self::array_flatten($notflat);

        $column_keys = array_flip($column_keys);

        foreach ($input as $key => $el) {
            $result[$key] = array_intersect_key($el, $column_keys);
        }
        return $result;
    }

    public static function clientOptions($options = "") {



        switch ($options):

            case 'S': // Savings
                self::getlables("886,885,1015,1218", "", "", self::$connObj);
                $html = '<span class="radiooptionsnew">
           <label  data-balloon="' . self::$lablearray['886'] . '" data-balloon-pos="down">
            <input type="radio" id="INDSAVACC"  name="radiosclient" value="IND"   checked />
            <img src="images/icons/ind.png">
          </label>
           <label  data-balloon="' . self::$lablearray['885'] . '" data-balloon-pos="down">
            <input type="radio" id="GRPSAVACC"  name="radiosclient" value="GRP" />
            <img src="images/icons/grp.png">
          </label>
             <label  data-balloon="' . self::$lablearray['1015'] . '" data-balloon-pos="down">
            <input type="radio" id="BUSSAVACC" name="radiosclient" value="BUSS" />
            <img src="images/icons/bus.png">
          </label>
            <label  data-balloon="' . self::$lablearray['1218'] . '" data-balloon-pos="down">
            <input type="radio" id="BUSSAVACC" name="radiosclient" value="GRP" />
            <img src="images/icons/mem.png">
          </label>
        </span>';
                break;

            case 'C': // Clients
                self::getlables("886,1015,885,1090,1678", "", "", self::$connObj);
//         $html= '<input  type="radio" id="radioind" name="client_type" value="I" checked onClick="newPage(\'\')">
//        <label for="radioind">'. $lablearray['886'].' </label>
//
//        <input  onkeydown="" type="radio" id="radiogrp" name="client_type" value="G" onClick="newPage(\'\')">
//        <label for="radiogrp">'. $lablearray['885'].'</label>
//
//        <input type="radio" id="radiobuss" name="client_type" value="B" onClick="newPage(\'\')">
//        <label for="radiobuss">'. $lablearray['1015'].'</label>
//
//        <input type="radio" id="radiomem" name="client_type" value="M" onClick="newPage(\'\')">
//        <label for="radiomem">'. $lablearray['1090'].'</label>';
//         
                $html = '<span class="radiooptionsnew">
            <label for="radioind" data-balloon="' . self::$lablearray['886'] . '" data-balloon-pos="down">
           <input type="radio" id="radioind"  name="client_type" value="I"  onClick="newPage(\'I\')"/>
           <img src="images/icons/ind.png"></label>
          
            <label for="radiogrp" data-balloon="' . self::$lablearray['885'] . '" data-balloon-pos="down">
           <input type="radio" id="radiogrp"  name="client_type" value="G" onClick="newPage(\'G\')"/>
           <img src="images/icons/grp.png"></label>
           
            <label for="radiobuss" data-balloon="' . self::$lablearray['1015'] . '" data-balloon-pos="down">
           <input type="radio" id="radiobuss" name="client_type" value="B" onClick="newPage(\'B\')"/>
            <img src="images/icons/bus.png"> </label>
           
            <label for="radiomem" data-balloon="' . self::$lablearray['1678'] . '" data-balloon-pos="down">    
           <input type="radio" id="radiomem" name="client_type" value="M" onClick="newPage(\'M\')"/>
           <img src="images/icons/mem.png"></label>   
           
        </span>';

                break;

            case 'L': // Loans
                self::getlables("886,885,1015,1654", "", "", self::$connObj);
                $html = '<p align="center"><span class="radiooptionsnew">
          <label  data-balloon="' . self::$lablearray['886'] . '" data-balloon-pos="down">
           <input type="radio" id="INDLOANS"  name="radiosclient" value="INDLOANS" checked />
           <img src="images/icons/ind.png">
         </label>
         
          <label  data-balloon="' . self::$lablearray['885'] . '" data-balloon-pos="down">
           <input type="radio" id="GRPLOANS"  name="radiosclient" value="GRPLOANS"/>
           <img src="images/icons/grp.png">
         </label>         
            <label  data-balloon="' . self::$lablearray['1015'] . '" data-balloon-pos="down">
           <input type="radio" id="BUSLOANS" name="radiosclient" value="BUSLOANS"/>
           <img src="images/icons/bus.png">          
           </label> 
           <label  data-balloon="' . self::$lablearray['1654'] . '" data-balloon-pos="down">
           <input type="radio" id="MEMLOANS" name="radiosclient" value="MEMLOANS"/>
           <img src="images/icons/mem.png">
         </label>       
       </span></p>';

                break;

            default:

                self::getlables("886,885,1015,1218", "", "", self::$connObj);
                $html = '<span class="radiooptionsnew">
           <label  data-balloon="' . self::$lablearray['886'] . '" data-balloon-pos="down">
            <input type="radio" id="INDSAVACC"  name="radiosclient" value="IND"  onClick="getClients();" checked />
            <img src="images/icons/ind.png">
          </label>
           <label  data-balloon="' . self::$lablearray['885'] . '" data-balloon-pos="down">
            <input type="radio" id="GRPSAVACC"  name="radiosclient" value="GRP" onClick="getClients();"/>
            <img src="images/icons/grp.png">
          </label>
             <label  data-balloon="' . self::$lablearray['1015'] . '" data-balloon-pos="down">
            <input type="radio" id="BUSSAVACC" name="radiosclient" value="BUSS" onClick="getClients();"/>
            <img src="images/icons/bus.png">
          </label>
            <label  data-balloon="' . self::$lablearray['1218'] . '" data-balloon-pos="down">
            <input type="radio" id="BUSSAVACC" name="radiosclient" value="GRP" onClick="getClients();"/>
            <img src="images/icons/mem.png">
          </label>
        </span>';
                break;
        endswitch;


        return $html;
    }

    // DO TO: add the following function on a page
    // Previous functionality was removed
    // 18/12/2016
    // $( "#btnviewreport" ).click(function() {
    // Statements    
    //});
    public static function printOptions($data = '') {

        return '<p><input type="radio" name="cfimb_5" id="id_cfimb_5_4" value="PDF" >
            PDF
            <input type="radio" name="cfimb_5" id="id_cfimb_5_5" value="HTML" checked="checked">
            HTML			
            <input type="radio" name="cfimb_5" id="id_cfimb_5_6" value="EXCEL">
            EXCEL
          </p><button class="btn" name="Go"  type="button"   id="btnviewreport" >View & Print</button>';
    }

    // function used to generate extra report controls    
    public static function getClientOptions() {


        return '<span class="radiooptions">
                <input type="radio" id="INDSAVACC" name="radiosclient" value="IND"  checked>
                <label for="INDSAVACC">Individuals</label>
                <input type="radio" id="GRPSAVACC" name="radiosclient" value="GRP" >
                <label for="GRPSAVACC">Groups</label>
                <input type="radio" id="BUSSAVACC" name="radiosclient" value="BUSS" >
                <label for="BUSSAVACC">Business</label> 	
            </span>';
    }

    // function used to generate extra report controls 
    //Note: All lables must be retrieved before this function is called into the $lablearray array variable 
    public static function reportsTypeList($control = '') {

        $reportlist = '<select  id="code" name="code">';
        $reportlist .= '<option value="">-------</option>';

        switch ($control) {
            case 'CLIENTRPTS':

                self::getlables("1269,1272,1046,1543,1672,1676", "", "", self::$connObj);

                $reportlist .= '<option id="CLIENTRPTS" value="CLIENTRPTS">' . self::$lablearray['1272'] . '</option>';
                $reportlist .= '<option  id="PLEDGER" value="PLEDGER">' . self::$lablearray['1046'] . '</option>';
                $reportlist .= '<option  id="PLEDGERMULTIPLE" value="PLEDGERMULTIPLE">' . self::$lablearray['1543'] . '</option>';
                $reportlist .= '<option id="CLIENTLOANFREQ" value="CLIENTLOANFREQ">' . self::$lablearray['1672'] . '</option>';
                $reportlist .= '<option id="SMSMESSAGES" value="SMSMESSAGES">' . self::$lablearray['1676'] . '</option>';
                break;

            case 'ACCOUNTSRPTS':

                self::getlables("463,107,108,1254,1322,103,1538,1541,105,1588,109", "", "", self::$connObj);

                $reportlist .= '<option id="BREAKPERACC" value="BREAKPERACC">' . self::$lablearray['463'] . '</option>
                <option id="TRIALB" value="TRIALB">' . self:: $lablearray['107'] . '</option>									
                <option  id="BALANCESHEET" value="BALANCESHEET">' . self::$lablearray['108'] . '</option>
                <option  id="INCOMEEXP" value="INCOMEEXP">' . self::$lablearray['1254'] . '</option>
                <option  id="INCOMEEXP" value="PROFITPERPERIOD">' . self::$lablearray['1588'] . '</option>
                <option  id="EQUITYCHA" value="EQUITYCHA">' . self::$lablearray['1322'] . '</option>
                <option  id="CASHFLOW" value="CASHFLOW">' . self::$lablearray['103'] . '</option>
                <option  id="CASHFLOW" value="DEBITCREDIT">' . self::$lablearray['1538'] . '</option>
                <option  id="TRANINPERIOD" value="TRANINPERIOD">' . self::$lablearray['1541'] . '</option>
                <option  id="PROVISION" value="PROVISION">' . self::$lablearray['105'] . '</option>
                <option  id="CHARTOFACCOUNTS" value="CHARTOFACCOUNTS">' . self::$lablearray['109'] . '</option>';
                break;

            case 'SAVINGSRPTS':

                self::getlables("1266,1266,1268,1269,1267,1573", "", "", self::$connObj);
                $reportlist .= '<option id="SAVSTAT" value="SAVSTAT">' . self::$lablearray['1266'] . '</option>
                <option id="SAVBALRPT" value="SAVBALRPT">' . self:: $lablearray['1267'] . '</option>									
                <option  id="SAVTILL" value="SAVTILL">' . self::$lablearray['1268'] . '</option>
                <option  id="INTSAVRPT" value="INTSAVRPT">' . self::$lablearray['1573'] . '</option>';
                break;

            case 'TIMEDEPOSITRPTS':

                self::getlables("1627", "", "", self::$connObj);
                $reportlist .= '<option id="TDRPT" value="TDRPT">' . self::$lablearray['1627'] . '</option>';

                break;

            case 'LOANRPTS':

                self::getlables("1045,1280,1281,1431,1283,1234,1350,1496", "", "", self::$connObj);
                $reportlist .= '<option id="MLLCARD" value="MLLCARD">' . self::$lablearray['1045'] . '</option>
                <option  id="DISBURSEMENTS" value="DISBURSEMENTS">' . self::$lablearray['1234'] . '</option>
                <option  id="OUTBAL" value="OUTBAL">' . self::$lablearray['1280'] . '</option>
                <option  id="ARRERPT" value="ARRERPT">' . self::$lablearray['1281'] . '</option>
                <option  id="PORTRSK" value="PORTRSK">' . self::$lablearray['1431'] . '</option> 
                <option  id="DUESLN" value="DUESLN">' . self::$lablearray['1283'] . '</option>
                <option  id="LOANREP" value="LOANREP">' . self::$lablearray['1350'] . '</option>
                <option  id="GUARANTORS" value="GUARANTORS">' . self::$lablearray['1496'] . '</option>';
                break;

            default:
                break;
        }

        $reportlist .= '</select>';

        self::getlables("1272,1269", "", "", self::$connObj);
        return $reportlist;
    }

    // function used to generate the list of the different report types dynamically   
    // Report IU columns here
    public static function getreportColumnList($code = '', &$Conn) {
        $rpt_contrs_ext = '';

        self::getlables("1273,1270,1274", "", "", self::$connObj);

        $rpt_fieldset = '<fieldset">';
        $rpt_contrs = '<div id="panel"  style="border: 1px solid #EEEEE;border-radius: 7px;margin:0px;padding:0px;height:100%;overflow-y:scroll;"></div>'
                . '<div style="width:70%;float:left;padding:5px;"><table cellspacing="" cellpadding="2" border="0" style="border-collapse: collapse;width:100%">';
        $rpt_contrs .= '<tr>';
        $rpt_contrs .= '<td style="width:20%;white-space:nowrap;">' . self::$lablearray[1273] . '<br>';
        $rpt_contrs .= "<select multiple size='10' name='fieldlist' id='fieldlist' style='height:190px;margin:0px'>";

        // remove these elemets from array
        unset(self::$lablearray['1270']);
        unset(self::$lablearray['1273']);

        // used in cases where columss has 2 heading/captions
        $report_sub_fieldlist_array = array();

        $report_db_fieldlist_array = array();
        $report_general_fieldlist_array = array();

        unset($report_general_fieldlist_array);

        unset($_SESSION['db_report_columns']);

        // GENERAL FIELDS
        $report_general_fieldlist_array['316'] = 'branch_code';
        $report_general_fieldlist_array['887'] = 'client_firstname';
        $report_general_fieldlist_array['888'] = 'client_middlename';
        $report_general_fieldlist_array['1018'] = 'client_surname';
        $report_general_fieldlist_array['1081'] = 'client1_code';
        $report_general_fieldlist_array['1083'] = 'client2_code';
        $report_general_fieldlist_array['1253'] = 'client3_code';
        $report_general_fieldlist_array['1259'] = 'bussinesssector_code';
        $report_general_fieldlist_array['1093'] = 'client_idno';
        $report_general_fieldlist_array['1022'] = 'clientcode';
        $report_general_fieldlist_array['1082'] = 'costcenters_code';
        $report_general_fieldlist_array['1021'] = 'entity_name';
        $report_general_fieldlist_array['1246'] = 'areacode_code';
        $report_general_fieldlist_array['1096'] = 'product_prodid';
        $report_general_fieldlist_array['1251'] = 'currencies_id';
        $report_general_fieldlist_array['1108'] = 'loancategory1_code';
        $report_general_fieldlist_array['1109'] = 'loancategory2_code';
        $report_general_fieldlist_array['1096'] = 'product_prodid';
        $report_general_fieldlist_array['987'] = 'user_id';
        $report_general_fieldlist_array['1242'] = 'client_regstatus';
        $report_general_fieldlist_array['38'] = 'startDate';
        $report_general_fieldlist_array['922'] = 'endDate';
        $report_general_fieldlist_array['1275'] = 'client_type';


        // added for general
        $report_general_label = "1022,1082,1251,1275,38,922,1242,987,1096,1253,1108,1109,1251,1096,1259,316,887,888,1018,1081,1083,1093,1021,1246";

        switch ($code) {
            case 'PROFITPERPERIOD':
                self::$lablearray = array();

                self::getlables("1532,1585,447,1586,1587", "", "", self::$connObj);

                $report_db_fieldlist_array['1532'] = 'nyear';
                $report_db_fieldlist_array['1585'] = 'nmonth';
                $report_db_fieldlist_array['447'] = 'income';
                $report_db_fieldlist_array['1586'] = 'expenditure';
                $report_db_fieldlist_array['1587'] = 'ppercent';

                $report_db_fieldlist_default_array['1532'] = Common::$lablearray['1532'];
                $report_db_fieldlist_default_array['1585'] = Common::$lablearray['1585'];
                $report_db_fieldlist_default_array['447'] = Common::$lablearray['447'];
                $report_db_fieldlist_default_array['1586'] = Common::$lablearray['1586'];
                $report_db_fieldlist_default_array['1587'] = Common::$lablearray['1587'];

                unset(self::$lablearray['1532']);
                unset(self::$lablearray['1585']);
                unset(self::$lablearray['447']);
                unset(self::$lablearray['1586']);
                unset(self::$lablearray['1587']);

                break;

            case 'CHARTOFACCOUNTS':
                self::getlables("444,296,264,450", "", "", self::$connObj);
                $report_db_fieldlist_array['444'] = 'paccount';
                $report_db_fieldlist_array['296'] = 'account';
                $report_db_fieldlist_array['264'] = 'description';
                $report_db_fieldlist_array['450'] = 'header';

                $report_db_fieldlist_default_array['444'] = Common::$lablearray['444'];
                $report_db_fieldlist_default_array['296'] = Common::$lablearray['296'];
                $report_db_fieldlist_default_array['264'] = Common::$lablearray['264'];
                $report_db_fieldlist_default_array['450'] = Common::$lablearray['450'];

                unset(self::$lablearray['444']);
                unset(self::$lablearray['296']);
                unset(self::$lablearray['264']);
                unset(self::$lablearray['450']);

                break;

            case 'MLLCARD': // Loan Ledgercard
            case 'ARRERPT':  // Arrears report  
              //  $_SESSION['rpt'] = 'MLLCARD';
              $rpt_contrs_ext = '<fieldset><table cellspacing="0" cellpadding="0" style="width:100%">';

              if($code=='MLLCARD'){
                self::getlables("1445,1446,1222,43,1447,427,21", "", "", $Conn);
                $rpt_contrs_ext .='<tr>';
                $rpt_contrs_ext .='<td nowrap>' . self::$lablearray[1445] . '<br><input id="loan_number_fr" style="width:95px;padding:0px;" name="loan_number_fr" type="text" value="' . BRANCHCODE . '"></td><td>' . self::$lablearray[1446] . '<br><input id="loan_number_to" name="loan_number_to" style="width:95px;padding:0px;" type="text" value="' . BRANCHCODE . '"></td><td><button id="btnSearch" type="button" name="btnSearch" onClick=\'LoadClients("MLLCARD")\'>' . self::$lablearray[21] . '</button></td>';
                $rpt_contrs_ext .='</tr>';
                $rpt_contrs_ext .='<tr>';
                $rpt_contrs_ext .='<td colspan=3 id="panel"></td>';
                $rpt_contrs_ext .='</tr>';
                self::$lablearray = array();
              }else{
                self::getlables("1294,1097,1746,1097,1341,1413,1342,1744,1745,1746,1747" . $report_general_label, "", "", self::$connObj);
              
                $report_db_fieldlist_array['1097'] = 'loan_number';
                $report_db_fieldlist_array['1341'] = 'disamount';
                $report_db_fieldlist_array['1342'] = 'loan_amount';
                $report_db_fieldlist_array['1294'] = 'arrdays';
                $report_db_fieldlist_array['1413'] = 'arrprinc';
                $report_db_fieldlist_array['1744'] = 'arrint';
                $report_db_fieldlist_array['1745'] = 'arrcomm';
                $report_db_fieldlist_array['1746'] = 'arrpen';
                $report_db_fieldlist_array['1747'] = 'arrvat';


                
                $report_db_fieldlist_default_array['887'] = Common::$lablearray['887'];
                $report_db_fieldlist_default_array['1018'] = Common::$lablearray['1018'];
                $report_db_fieldlist_default_array['1097'] = Common::$lablearray['1097'];
                $report_db_fieldlist_default_array['1341'] = Common::$lablearray['1341'];
                $report_db_fieldlist_default_array['1342'] = Common::$lablearray['1342'];
                $report_db_fieldlist_default_array['1294'] = Common::$lablearray['1294'];
                $report_db_fieldlist_default_array['1413'] = Common::$lablearray['1413'];
                $report_db_fieldlist_default_array['1744'] = Common::$lablearray['1744'];
                $report_db_fieldlist_default_array['1745'] = Common::$lablearray['1745'];
                $report_db_fieldlist_default_array['1746'] = Common::$lablearray['1746'];
               // $report_db_fieldlist_default_array['1747'] = Common::$lablearray['1747'];
            
                unset(self::$lablearray['1018']);
                unset(self::$lablearray['887']);
                unset(self::$lablearray['1097']);
                unset(self::$lablearray['1341']);
                unset(self::$lablearray['1342']);
                unset(self::$lablearray['1294']);
                unset(self::$lablearray['1413']);
                unset(self::$lablearray['1744']);
                unset(self::$lablearray['1745']);
                unset(self::$lablearray['1746']);
                unset(self::$lablearray['1747']);

              }

              unset(self::$lablearray['922']);
              unset(self::$lablearray['38']);
             //   $rpt_fieldset = '<fieldset style="display:none;">';
               
            $rpt_contrs_ext .='<tr>'.
                '<td colspan=3 align="center">' . self::$lablearray['1294'] . '<input id="n_days" name="n_days" type="text" value="0">&nbsp;' . self::$lablearray[427].'</td>'.
                '</tr>'.
                '</table></fieldset>';
                //$rpt_contrs_ext = self::$lablearray[1445].'&nbsp;<input id="loan_number_fr" name="loan_number_fr" type="" value="'.BRANCHCODE.'"> '.self::$lablearray[1446].'&nbsp;<input id="loan_number_to" name="loan_number_to" type="" value="'.BRANCHCODE.'">';
               
                break;

            case 'SAVINTRPTS': // Interest paid on Savings - report called directly from non report page
                $report_db_fieldlist_array = array();
                $report_db_fieldlist_array['9'] = 'name';
                $report_db_fieldlist_array['1093'] = 'client_idno';
                $report_db_fieldlist_array['1096'] = 'product_prodid';
                $report_db_fieldlist_array['296'] = 'savaccounts_account';
                $report_db_fieldlist_array['1145'] = 'interest';
                $report_db_fieldlist_array['483'] = 'periodstart';
                $report_db_fieldlist_array['48'] = 'periodend';
                $_SESSION['report_columns'] = serialize($report_db_fieldlist_array);
                $grouporder = array();               
                $_SESSION['grouporder'] = serialize($grouporder);
                break;

            case 'TDRPT':
                self::getlables($report_general_label . ",362,271,1594,1631,1596,1595,882,197", "", "", self::$connObj);
                $report_db_fieldlist_array['362'] = 'timedeposit_number';
                $report_db_fieldlist_array['271'] = 'timedeposit_amount';
                $report_db_fieldlist_array['1594'] = 'timedeposit_interestrate';
                $report_db_fieldlist_array['1631'] = 'timedeposit_intamt';
                $report_db_fieldlist_array['1596'] = 'timedeposit_matdate';
                $report_db_fieldlist_array['1595'] = 'timedeposit_matval';
                $report_db_fieldlist_array['882'] = 'timedeposit_period';
                $report_db_fieldlist_array['197'] = 'timedeposit_status';

                // DEFAULT SELECTED COLUMNS COMMENTED

                $report_db_fieldlist_default_array['1093'] = Common::$lablearray['1093'];
                $report_db_fieldlist_default_array['887'] = Common::$lablearray['887'];
                $report_db_fieldlist_default_array['888'] = Common::$lablearray['888'];
                $report_db_fieldlist_default_array['1018'] = Common::$lablearray['1018'];
                $report_db_fieldlist_default_array['362'] = Common::$lablearray['362'];


                $report_db_fieldlist_default_array['271'] = Common::$lablearray['271'];
                $report_db_fieldlist_default_array['1594'] = Common::$lablearray['1594'];
                $report_db_fieldlist_default_array['1631'] = Common::$lablearray['1631'];
                $report_db_fieldlist_default_array['1596'] = Common::$lablearray['1596'];
                $report_db_fieldlist_default_array['1595'] = Common::$lablearray['1595'];
                $report_db_fieldlist_default_array['882'] = Common::$lablearray['882'];
                $report_db_fieldlist_default_array['197'] = Common::$lablearray['197'];

                unset(self::$lablearray['362']);
                unset(self::$lablearray['271']);
                unset(self::$lablearray['1594']);
                unset(self::$lablearray['1631']);
                unset(self::$lablearray['1596']);
                unset(self::$lablearray['1595']);
                unset(self::$lablearray['882']);
                unset(self::$lablearray['197']);

                unset(self::$lablearray['1093']);
                unset(self::$lablearray['887']);
                unset(self::$lablearray['888']);
                unset(self::$lablearray['1018']);

                break;

            case 'SMSMESSAGES'; //  SMS Report

                self::getlables("1625,1611,1248,1247,900," . $report_general_label, "", "", self::$connObj);
                $report_db_fieldlist_array['1625'] = 'date';
                $report_db_fieldlist_array['1611'] = 'msg';
                $report_db_fieldlist_array['1248'] = 'client_tel1';
                $report_db_fieldlist_array['1247'] = 'client_tel2';

                $report_db_fieldlist_default_array['1625'] = Common::$lablearray['1625']; // Date
                $report_db_fieldlist_default_array['1018'] = Common::$lablearray['1018']; // Surname
                $report_db_fieldlist_default_array['887'] = Common::$lablearray['887']; // Firstname
                $report_db_fieldlist_default_array['900'] = Common::$lablearray['900']; // Lastname
                $report_db_fieldlist_default_array['1248'] = Common::$lablearray['1248']; // Telephone 1
                $report_db_fieldlist_default_array['1247'] = Common::$lablearray['1247']; // Telephone 2
                $report_db_fieldlist_default_array['1611'] = Common::$lablearray['1611']; // Message

                unset(self::$lablearray['1625']);
                unset(self::$lablearray['1018']);
                unset(self::$lablearray['887']);
                unset(self::$lablearray['900']);
                unset(self::$lablearray['1248']);
                unset(self::$lablearray['1247']);
                unset(self::$lablearray['1611']);

                break;

            case 'CLIENTRPTS': // Client reports
            case 'CLIENTLOANFREQ': // Client Loan Frequency    
                self::getlables($report_general_label . ",1671,1341,11,585,1019,199,1049,1050,1064,1248,1247,484,1622,1234", "", "", self::$connObj);

                $report_db_fieldlist_array['11'] = 'client_addressphysical';
                $report_db_fieldlist_array['585'] = 'client_emailad';
                $report_db_fieldlist_array['1019'] = 'client_regdate';
                $report_db_fieldlist_array['199'] = 'client_gender';
                $report_db_fieldlist_array['1049'] = 'client_postad';
                $report_db_fieldlist_array['1050'] = 'client_city';
                $report_db_fieldlist_array['1064'] = 'client_maritalstate';
                $report_db_fieldlist_array['1248'] = 'client_tel1';
                $report_db_fieldlist_array['1247'] = 'client_tel2';
                $report_db_fieldlist_array['484'] = 'client_enddate';

                If ($code == 'CLIENTLOANFREQ'):

                    $report_db_fieldlist_array['1671'] = 'cycles';
                    $report_db_fieldlist_array['1341'] = 'amount';

                    $report_db_fieldlist_default_array['1018'] = Common::$lablearray['1018'];
                    $report_db_fieldlist_default_array['887'] = Common::$lablearray['887'];
                    $report_db_fieldlist_default_array['888'] = Common::$lablearray['888'];
                    $report_db_fieldlist_default_array['1671'] = Common::$lablearray['1671'];
                    $report_db_fieldlist_default_array['1341'] = Common::$lablearray['1341'];

                    unset(self::$lablearray['1018']);
                    unset(self::$lablearray['887']);
                    unset(self::$lablearray['888']);
                    unset(self::$lablearray['1671']);
                    unset(self::$lablearray['1341']);

                else:

                    $report_db_fieldlist_default_array['1022'] = Common::$lablearray['1022'];
                    $report_db_fieldlist_default_array['1093'] = Common::$lablearray['1093'];
                    $report_db_fieldlist_default_array['1018'] = Common::$lablearray['1018'];
                    $report_db_fieldlist_default_array['887'] = Common::$lablearray['887'];
                    $report_db_fieldlist_default_array['888'] = Common::$lablearray['888'];
                    $report_db_fieldlist_default_array['1246'] = Common::$lablearray['1246'];
                    $report_db_fieldlist_default_array['1019'] = Common::$lablearray['1019'];
                    $report_db_fieldlist_default_array['316'] = Common::$lablearray['316'];

                    unset(self::$lablearray[316]);
                    unset(self::$lablearray[1022]);
                    unset(self::$lablearray[1093]);
                    unset(self::$lablearray[1018]);
                    unset(self::$lablearray[887]);
                    unset(self::$lablearray[888]);
                    unset(self::$lablearray[1246]);
                    unset(self::$lablearray[1019]);
                endif;

                break;
            case 'INTSAVRPT': // Interest on Savings
                self::getlables($report_general_label . ",9,296,1096,1145", "", "", self::$connObj);
                $report_db_fieldlist_array['9'] = 'name';
                $report_db_fieldlist_array['296'] = 'savaccounts_account';
                $report_db_fieldlist_array['1096'] = 'product_prodid';
                $report_db_fieldlist_array['1145'] = 'interest';

                break;

            case 'SAVSTAT': // savers statement

                self::getlables("317,299,296,21,1208,301,264,289,297,249,1265", "", "", self::$connObj);

                $rpt_contrs_ext = self::$lablearray[296] . '<input id="client_idno" name="client_idno" type="hidden" value=""><input id="savaccounts_account" name="savaccounts_account" type="" value="" readonly><input type="hidden" id="product_prodid" name="product_prodid" type="" value="" readonly><button id="btnSearch" type="button" name="btnSearch" onClick=\'LoadClients("SAVSTAT")\'>' . self::$lablearray[21] . '</button>';

                if (isset(self::$lablearray[296])) {
                    unset(self::$lablearray[296]);
                }

                if (isset(self::$lablearray[21])) {
                    unset(self::$lablearray[21]);
                }

                $report_db_fieldlist_array['301'] = 'transactioncode';
                $report_db_fieldlist_array['1208'] = 'transaction_type';
                $report_db_fieldlist_array['264'] = 'description';
                $report_db_fieldlist_array['317'] = 'date';
                $report_db_fieldlist_array['289'] = 'debit';
                $report_db_fieldlist_array['297'] = 'credit';
                $report_db_fieldlist_array['271'] = 'Amount';
                $report_db_fieldlist_array['249'] = 'balance';
                $report_db_fieldlist_array['1265'] = 'cheque_status';
                $report_db_fieldlist_array['299'] = 'voucher';

                // DEFAULT SELECTED COLUMNS COMMENTED
                $report_db_fieldlist_default_array['317'] = Common::$lablearray['317'];
                $report_db_fieldlist_default_array['299'] = Common::$lablearray['299'];
                $report_db_fieldlist_default_array['1208'] = Common::$lablearray['1208'];
                $report_db_fieldlist_default_array['289'] = Common::$lablearray['289'];
                $report_db_fieldlist_default_array['297'] = Common::$lablearray['297'];
                $report_db_fieldlist_default_array['249'] = Common::$lablearray['249'];

                unset(self::$lablearray['317']);
                unset(self::$lablearray['299']);
                unset(self::$lablearray['1208']);
                unset(self::$lablearray['289']);
                unset(self::$lablearray['297']);
                unset(self::$lablearray['249']);

                break;

            case 'SAVTILL': // Saving Tillsheet

                self::getlables("301,1208,264,289,297,1265,299," . $report_general_label, "", "", self::$connObj);

                $report_db_fieldlist_array['301'] = 'transactioncode';
                $report_db_fieldlist_array['1208'] = 'transaction_type';
                $report_db_fieldlist_array['264'] = 'description';
                $report_db_fieldlist_array['317'] = 'date';
                $report_db_fieldlist_array['289'] = 'debit';
                $report_db_fieldlist_array['297'] = 'credit';
                $report_db_fieldlist_array['1265'] = 'cheque_status';
                $report_db_fieldlist_array['299'] = 'voucher';
                break;

            case 'SAVBALRPT': // Savings balances report                   
                self::getlables("249,1197," . $report_general_label, "", "", self::$connObj);
                $report_db_fieldlist_array['249'] = 'balance';
                $report_db_fieldlist_array['1197'] = 'acc';
                break;

            case 'DISBURSEMENTS': // Disbursements 
                self::getlables("1097,1098,1340,1342,1284,1018,1097,1340,1342,1284," . $report_general_label, "", "", self::$connObj);
                $report_db_fieldlist_array['1097'] = 'loan_number';
                $report_db_fieldlist_array['1098'] = 'loan_startdate';
                $report_db_fieldlist_array['1340'] = 'disb_date';
                $report_db_fieldlist_array['1342'] = 'loan_amount';
                $report_db_fieldlist_array['1284'] = 'amount_disb';


                $report_db_fieldlist_default_array['887'] = Common::$lablearray['887']; 
                $report_db_fieldlist_default_array['1018'] = Common::$lablearray['1018']; 
                $report_db_fieldlist_default_array['1097'] = Common::$lablearray['1097']; 
                $report_db_fieldlist_default_array['1340'] = Common::$lablearray['1340']; 
                $report_db_fieldlist_default_array['1342'] = Common::$lablearray['1342']; 
                $report_db_fieldlist_default_array['1284'] = Common::$lablearray['1284']; 

                unset(self::$lablearray['887']);
                unset(self::$lablearray['1018']);
                unset(self::$lablearray['1097']);
                unset(self::$lablearray['1340']);
                unset(self::$lablearray['1342']);
                unset(self::$lablearray['1284']);
           
                break;

            // case 'ARRERPT'; //  Arrears Report
            //     self::getlables("1294,1097,1341,1342," . $report_general_label, "", "", self::$connObj);
             
            //     $report_db_fieldlist_array['1294'] = 'arrdays';
            //     $report_db_fieldlist_array['1097'] = 'loan_number';
            //     $report_db_fieldlist_array['1341'] = 'disamount';
            //     $report_db_fieldlist_array['1342'] = 'loan_amount';

            //     break;

            case 'SMSMESSAGES'; //  SMS Report

                self::getlables("1625,1611,1248,1247,900," . $report_general_label, "", "", self::$connObj);
                $report_db_fieldlist_array['1625'] = 'date';
                $report_db_fieldlist_array['1611'] = 'msg';
                $report_db_fieldlist_array['1248'] = 'client_tel1';
                $report_db_fieldlist_array['1247'] = 'client_tel2';

                $report_db_fieldlist_default_array['1625'] = Common::$lablearray['1625']; // Date
                $report_db_fieldlist_default_array['1018'] = Common::$lablearray['1018']; // Surname
                $report_db_fieldlist_default_array['887'] = Common::$lablearray['887']; // Firstname
                $report_db_fieldlist_default_array['900'] = Common::$lablearray['900']; // Lastname
                $report_db_fieldlist_default_array['1248'] = Common::$lablearray['1248']; // Telephone 1
                $report_db_fieldlist_default_array['1247'] = Common::$lablearray['1247']; // Telephone 2
                $report_db_fieldlist_default_array['1611'] = Common::$lablearray['1611']; // Message

                unset(self::$lablearray['1625']);
                unset(self::$lablearray['1018']);
                unset(self::$lablearray['887']);
                unset(self::$lablearray['900']);
                unset(self::$lablearray['1248']);
                unset(self::$lablearray['1247']);
                unset(self::$lablearray['1611']);

                break;

            case 'PLEDGER':
            case 'PLEDGERMULTIPLE':
                self::getlables("21,1093,1023,1542", "", "", self::$connObj);
                if ($code == 'PLEDGER'):
                    $rpt_contrs_ext = '<p>' . self::$lablearray['1093'] . '<input id="client_idno" name="client_idno" type="text" value=""><button id="btnSearch" type="button" name="btnSearch" onClick=\'LoadClients("PLEDGER")\'>' . self::$lablearray['21'] . ' ' . self::$lablearray['1023'] . '</button></p>';
                endif;


                self::$lablearray = array();

                self::getlables("317,299,264,1208,1383,1145,1384,1385,1025,1229,1144,1105,1181,1387,1267,1388,1389,1274,1390,1391,1392,1393,1394", "", "", self::$connObj);

                $report_db_fieldlist_array['317'] = 'tdate';
                //  $report_db_fieldlist_array['264'] = 'descr';
                $report_db_fieldlist_array['299'] = 'voucher';
                $report_db_fieldlist_array['1208'] = 'ttcode';
                $report_db_fieldlist_array['1383'] = 'nshares';
                $report_db_fieldlist_array['1384'] = 'nshaval';
                $report_db_fieldlist_array['1385'] = 'tnshares';
                $report_db_fieldlist_array['1025'] = 'savamount';
                $report_db_fieldlist_array['1229'] = 'damount';
                $report_db_fieldlist_array['1144'] = 'principal';
                $report_db_fieldlist_array['1145'] = 'interest';
                $report_db_fieldlist_array['1105'] = 'commission';
                $report_db_fieldlist_array['1181'] = 'penalty';
                $report_db_fieldlist_array['1387'] = '_vat';
                $report_db_fieldlist_array['1267'] = 'savbalance';
                $report_db_fieldlist_array['1388'] = 'shabalance';
                $report_db_fieldlist_array['1389'] = 'princbal';
                $report_db_fieldlist_array['1393'] = 'vatbal';
                $report_db_fieldlist_array['1390'] = 'intbal';
                $report_db_fieldlist_array['1391'] = 'commbal';
                $report_db_fieldlist_array['1392'] = 'penbal';
                $report_db_fieldlist_array['1025'] = 'savamount';
                $report_db_fieldlist_array['1105'] = 'commission';
                $report_db_fieldlist_array['1394'] = 'tloanbal';

                // DEFAULT SELECTED COLUMNS COMMENTED
                $report_db_fieldlist_default_array['317'] = Common::$lablearray['317'];
                $report_db_fieldlist_default_array['1208'] = Common::$lablearray['1208'];
                $report_db_fieldlist_default_array['299'] = Common::$lablearray['299'];
                $report_db_fieldlist_default_array['1025'] = Common::$lablearray['1025'];
                $report_db_fieldlist_default_array['1105'] = Common::$lablearray['1105'];
                $report_db_fieldlist_default_array['1267'] = Common::$lablearray['1267'];
                $report_db_fieldlist_default_array['1144'] = Common::$lablearray['1144'];
                $report_db_fieldlist_default_array['1145'] = Common::$lablearray['1145'];
                $report_db_fieldlist_default_array['1394'] = Common::$lablearray['1394'];

//                $report_db_fieldlist_default_array['1390'] = Common::$lablearray['1390'];
//                $report_db_fieldlist_default_array['1391'] = Common::$lablearray['1391'];
//                $report_db_fieldlist_default_array['1394'] = Common::$lablearray['1394'];


                unset(self::$lablearray['299']);
                unset(self::$lablearray['317']);
                unset(self::$lablearray['1394']);
                unset(self::$lablearray['1208']);
                unset(self::$lablearray['1389']);
                unset(self::$lablearray['1025']);
                unset(self::$lablearray['1105']);
                unset(self::$lablearray['1267']);
                unset(self::$lablearray['1144']);
                unset(self::$lablearray['1145']);

                break;

            case 'LOANREP':
                self::getlables("1097,1358,1359,1360,1361,1362,1363,1364,1365,1366,1367,1351,1352,1353,1354,1368,1369,1370,1371,1372,1373" . $report_general_label, "", "", self::$connObj);
                $report_db_fieldlist_array['1097'] = 'loan_number';
                $report_db_fieldlist_array['1358'] = 'princpastdue';
                $report_db_fieldlist_array['1359'] = 'intpastdue';
                $report_db_fieldlist_array['1360'] = 'commpastdue';
                $report_db_fieldlist_array['1361'] = 'penpastdue';
                $report_db_fieldlist_array['1362'] = 'vatpastdue';
                $report_db_fieldlist_array['1363'] = 'dprincinperiod';
                $report_db_fieldlist_array['1364'] = 'dintinperiod';
                $report_db_fieldlist_array['1365'] = 'dcomminperiod';
                $report_db_fieldlist_array['1366'] = 'dpeninperiod';
                $report_db_fieldlist_array['1367'] = 'dvatinperiod';

                $report_db_fieldlist_array['1351'] = 'pprincinperiod';
                $report_db_fieldlist_array['1352'] = 'pintinperiod';
                $report_db_fieldlist_array['1353'] = 'pcomminperiod';
                $report_db_fieldlist_array['1354'] = 'ppeninperiod';
                $report_db_fieldlist_array['1368'] = 'pvatinperiod';

                $report_db_fieldlist_array['1369'] = 'rprinc';
                $report_db_fieldlist_array['1370'] = 'rint';
                $report_db_fieldlist_array['1371'] = 'rcomm';
                $report_db_fieldlist_array['1372'] = 'rpen';
                $report_db_fieldlist_array['1373'] = 'rvat';

                unset(self::$lablearray['922']);
                unset(self::$lablearray['39']);

                break;

            case 'OUTBAL': //Outstanding Balance  
            case 'GUARANTORS':
                self::getlables("1168,1101,1100,249,1284,1285,1286,1288,1289,1097,1291,1252,1292,1098,1293,1102," . $report_general_label, "", "", self::$connObj);

                $report_db_fieldlist_array['1284'] = 'disamount';
                $report_db_fieldlist_array['1285'] = 'pbalance';
                $report_db_fieldlist_array['1286'] = 'ibalance';
                $report_db_fieldlist_array['1288'] = 'penbalance';
                $report_db_fieldlist_array['1289'] = 'combalance';
                // $report_db_fieldlist_array['1290'] = 'vbalance';
                $report_db_fieldlist_array['1097'] = 'loan_number';
                $report_db_fieldlist_array['1291'] = 'loan_amount';
                $report_db_fieldlist_array['1252'] = 'fund_code';
                $report_db_fieldlist_array['1292'] = 'user_id';
                $report_db_fieldlist_array['1098'] = 'loan_adate';
                $report_db_fieldlist_array['1293'] = 'loan_isttype';
                $report_db_fieldlist_array['1102'] = 'loan_inttype';
                $report_db_fieldlist_array['1168'] = 'donor_code';
                $report_db_fieldlist_array['1101'] = 'loan_noofinst';
                $report_db_fieldlist_array['1100'] = 'loan_tint';
                break;

            case 'DUESLN':
                self::getlables("1097,1093,1144,1145,1105,1181,1450," . $report_general_label, "", "", self::$connObj);
                $report_db_fieldlist_array['1097'] = 'loan_number';
                $report_db_fieldlist_array['1144'] = 'principal';
                $report_db_fieldlist_array['1145'] = 'interest';
                $report_db_fieldlist_array['1105'] = 'commission';
                $report_db_fieldlist_array['1181'] = 'penalty';
                $report_db_fieldlist_array['1450'] = 'texpected';

                break;

            case 'PORTRSK'; // Portfolio at risk

                self::getlables("1341,1342,1294,1413,1744,1302,1018,1097,1296,1297,1298,1299,1300,1301,1735," . $report_general_label, "", "", self::$connObj);
                //$rpt_contrs_ext = '<div><input id="btnSearch" type="button" name="btnSearch" onClick=\'LoadClients("PORTRSK")\' value="' . self::$lablearray[1302] . '"></div>';
                $rpt_contrs_ext = '<div><a href="#" onClick=LoadClients("PORTRSK")>' . self::$lablearray[1302] .'</a></div>';
                unset(self::$lablearray["1302"]);
              
                $report_db_fieldlist_array['1097'] = 'loan_number';
                $report_db_fieldlist_array['1296'] = 'class1aclass1b';
                $report_db_fieldlist_array['1297'] = 'class2aclass2b';
                $report_db_fieldlist_array['1298'] = 'class3aclass3b';
                $report_db_fieldlist_array['1299'] = 'class4aclass4b';
                $report_db_fieldlist_array['1300'] = 'class5aclass5b';
                $report_db_fieldlist_array['1301'] = 'class6aclass6b';
                $report_db_fieldlist_array['1735'] = 'class7';
                $report_db_fieldlist_array['1341'] = 'disamount';
                $report_db_fieldlist_array['1342'] = 'loan_amount';
                $report_db_fieldlist_array['1294'] = 'arrdays';
                $report_db_fieldlist_array['1413'] = 'arrprinc';
                $report_db_fieldlist_array['1744'] = 'arrint';
           
                
                $report_db_fieldlist_default_array['1018'] = self::$lablearray['1018'];
                $report_db_fieldlist_default_array['887'] = self::$lablearray['887'];
                $report_db_fieldlist_default_array['1097'] = self::$lablearray['1097'];
                $report_db_fieldlist_default_array['1341'] = self::$lablearray['1341'];
                $report_db_fieldlist_default_array['1413'] = self::$lablearray['1413'];
                $report_db_fieldlist_default_array['1294'] = self::$lablearray['1294'];
                
                $report_db_fieldlist_default_array['1296'] = self::$lablearray['1296'];
                $report_db_fieldlist_default_array['1297'] = self::$lablearray['1297']; 
                $report_db_fieldlist_default_array['1298'] = self::$lablearray['1298'];
                $report_db_fieldlist_default_array['1299'] = self::$lablearray['1299'];
                $report_db_fieldlist_default_array['1300'] = self::$lablearray['1300'];
                $report_db_fieldlist_default_array['1301'] = self::$lablearray['1301'];
                $report_db_fieldlist_default_array['1735'] = self::$lablearray['1735'];
         
                
                unset(self::$lablearray["1294"]);
                unset(self::$lablearray["1413"]);
                unset(self::$lablearray["1341"]);   
                             
                unset(self::$lablearray["1296"]);
                unset(self::$lablearray["1297"]);
                unset(self::$lablearray["1298"]);
                unset(self::$lablearray["1299"]);
                unset(self::$lablearray["1300"]);
                unset(self::$lablearray["1301"]);
                unset(self::$lablearray["1735"]);
                unset(self::$lablearray["1018"]);
                unset(self::$lablearray["38"]);
                break;
                
            case 'TRIALB': // Trial Balance
            case 'INCOMEEXP': // Income and Expenditure
                self::getlables("1338,443,373,1325,465", "", "", self::$connObj);
                $report_db_fieldlist_array = array();
                $report_db_fieldlist_array['443'] = 'account'; // Account Code
                $report_db_fieldlist_array['1338'] = 'account_label'; // Account Name  
                $report_db_fieldlist_array['373'] = 'opening_balances';
                $report_db_fieldlist_array['1325'] = 'period_balances';
                $report_db_fieldlist_array['465'] = 'closing_balances';

                $report_db_fieldlist_default_array['443'] = self::$lablearray['443']; // Account Code
                $report_db_fieldlist_default_array['1338'] = self::$lablearray['1338']; // Account Name  
                $report_db_fieldlist_default_array['373'] = self::$lablearray['373'];
                $report_db_fieldlist_default_array['1325'] = self::$lablearray['1325'];
                $report_db_fieldlist_default_array['465'] = self::$lablearray['465'];

                unset(self::$lablearray['443']);
                unset(self::$lablearray['1338']);
                unset(self::$lablearray['373']);
                unset(self::$lablearray['1325']);
                unset(self::$lablearray['465']);


                break;

            case 'DEBITCREDIT': // Income and Expenditure
                self::getlables("317,306,97,264,289,297,316,301", "", "", self::$connObj);
                $report_db_fieldlist_array = array();
                $report_db_fieldlist_array['317'] = 'tday';
                $report_db_fieldlist_array['301'] = 'tcode';
                $report_db_fieldlist_array['306'] = 'account';
                $report_db_fieldlist_array['97'] = 'account_label';
                $report_db_fieldlist_array['264'] = 'description';
                $report_db_fieldlist_array['289'] = 'debit';
                $report_db_fieldlist_array['297'] = 'credit';
                $report_db_fieldlist_array['316'] = 'branch';
                break;

            case 'BALANCESHEET': // Balancesheet
                self::getlables("443,1335,1336,1338", "", "", self::$connObj);

                $report_db_fieldlist_array = array();
                $report_db_fieldlist_array['443'] = 'account'; // Account Code
                $report_db_fieldlist_array['1338'] = 'account_label'; // Account Name                
                $report_db_fieldlist_array['1335'] = 'cfirst'; // balance as at startdate
                $report_db_fieldlist_array['1336'] = 'clast'; // balance as at enddate

                $report_db_fieldlist_default_array['443'] = self::$lablearray['443']; // Account Code
                $report_db_fieldlist_default_array['1338'] = self::$lablearray['1338']; // Account Name                
                $report_db_fieldlist_default_array['1335'] = self::$lablearray['1335']; // balance as at startdate
                $report_db_fieldlist_default_array['1336'] = self::$lablearray['1336']; // balance as at enddate
//                
                unset(self::$lablearray['443']);
                unset(self::$lablearray['1338']);
                unset(self::$lablearray['1335']);
                unset(self::$lablearray['1336']);




                break;

            case 'PROVISION': // Provision

                self::getlables("38,39,316,1552,1551,1545,1551,1096,271,38,39,1296,1297,1299,1298,1300,1546,317,1096", "", "", self::$connObj);

                $rpt_contrs_ext = '<script>
                     $( "#btnSave" ).click(function() {
                        var pageinfo =  JSON.stringify($("#frmreportsui").serializeArray());			
                        var data1 = JSON.stringify(JSON.parse(\'{"pageinfo":\'+pageinfo+"}"));
                        showValues("frmreportsui","",$("#action").val(),data1,"addedit.php",$("#theid").val());
                     });

                    function disablesboxes() {
                    
                        if($("#chkusblockfigure").is(":checked")){
                            $("#class1a,#class1b,class1per,#class2a,#class2b,class2per,#class3a,#class3b,class3per,#class4a,#class4b,#class4per,#class5a,#class5per").prop("disabled", true);
                        }else{
                            $("#class1a,#class1b,class1per,#class2a,#class2b,class2per,#class3a,#class3b,class3per,#class4a,#class4b,#class4per,#class5a,#class5per").prop("disabled", false);
                        }
                
                   }        

            </script>
            <fieldset data-balloon="' . Common::$lablearray['1551'] . '" data-balloon-pos="up">
          
             <table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td align="left">' . Common::$lablearray['316'] . ' <br>' . self::generateReportControls('BRANCHES', Common::$connObj, 'branch_code') . '</td><td>' . Common::$lablearray['317'] . '<br><input type="us-date" id="pDate" name="pDate"></td>
                <td>' . Common::$lablearray['1096'] . '<br>' . DrawComboFromArray(array(), 'product_prodid', '', 'LOANPROD', '', '') . '</td>               
            </tr>
            <tr>
                <td valign="top"><input type="checkbox" name="chkusblockfigure" id="chkusblockfigure" onClick="disablesboxes();"> ' . Common::$lablearray['1545'] . '<br> 
                </td>
                <td >' . Common::$lablearray['271'] . '<br><input type="text" id="amount" name="amount" value=""></td><td></td>
                
            </tr>
           

            <tr>
                <td colspan="3" align="center">
                    <table width="100%" border="0" cellspacing="0" cellpadding="1">
                        <tr>
                            <td></td>
                            <td>' . Common::$lablearray['38'] . '</td>
                            <td>' . Common::$lablearray['39'] . '</td>
                            <td>%</td>						
                        </tr>


                        <tr>
                            <td>' . Common::$lablearray['1296'] . '</td>
                            <td ><input name="class1a" type="text" id="class1a" size="5" value="1"></td>
                            <td>&nbsp;<input name="class1b" type="text" id="class1b" size="5" value="30"></td>
                            <td>&nbsp;<input name="class1per" type="text" id="class1per" size="4" value="100"></td>

                        </tr>
                        <tr>
                            <td>' . Common::$lablearray['1297'] . '</td>
                            <td ><input name="class2a" type="text" id="class2a" size="5" value="31"></td>
                            <td>&nbsp;<input name="class2b" type="text" id="class2b" size="5" value="60"></td>
                            <td>&nbsp;<input name="class2per" type="text" id="class2per" size="4" value="100"></td>

                        </tr>
                        <tr>
                            <td>' . Common::$lablearray['1298'] . '</td>
                            <td ><input name="class3a" type="text" id="class3a" size="5" value="61"></td>
                            <td>&nbsp;<input name="class3b" type="text" id="class3b" size="5" value="90"></td>
                            <td>&nbsp;<input name="class3per" type="text" id="class3per" size="4" value="100"></td>

                        </tr>
                        <tr>
                            <td>' . Common::$lablearray['1299'] . '</td>
                            <td ><input name="class4a" type="text" id="class4a" size="5" value="91"></td>
                            <td>&nbsp;<input name="class4b" type="text" id="class4b" size="5" value="120"></td>
                            <td>&nbsp;<input name="class4per" type="text" id="class4per" size="4" value="100"></td>

                        </tr>
                        <tr>
                            <td>' . Common::$lablearray['1300'] . '</td>
                            <td ><input name="class5a" type="text" id="class5a" size="5" value="121"></td>
                            <td>&nbsp' . Common::$lablearray['1546'] . '</td>
                            <td>&nbsp;<input name="class5per" type="text" id="class5per" size="4" value="100"></td>

                        </tr>

                    </tr>
                </table>

        </td>
        </tr></table>
         <button class="btn" name="btnSave"  type="button"   id="btnSave">' . Common::$lablearray['1552'] . '</button>
        </fieldset>';
                Common::$lablearray = array();
                self::getlables("1097,9,1461,1294,1548,1096,1093", "", "", self::$connObj);
                $report_db_fieldlist_array = array();
                $report_db_fieldlist_array['1097'] = 'loan_number';
                $report_db_fieldlist_array['1093'] = 'client_idno';
                $report_db_fieldlist_array['9'] = 'name';
                $report_db_fieldlist_array['1096'] = 'product_prodid';
                $report_db_fieldlist_array['1461'] = 'pbalance';
                $report_db_fieldlist_array['1294'] = 'arrdays';
                $report_db_fieldlist_array['1548'] = 'amtprovided';

                break;

            case 'TRANINPERIOD': // Transaction made in the period

                self::getlables("298,296,264,301,289,297,1096,1082,1251,1208,316,1168,1107,1540,1251", "", "", self::$connObj);

                $report_db_fieldlist_array['298'] = 'tday';
                $report_db_fieldlist_array['296'] = 'account';
                $report_db_fieldlist_array['264'] = 'description';
                $report_db_fieldlist_array['301'] = 'tcode';
                $report_db_fieldlist_array['289'] = 'debit';
                $report_db_fieldlist_array['297'] = 'credit';
                $report_db_fieldlist_array['1096'] = 'product_prodid';
                $report_db_fieldlist_array['1082'] = 'costcenters_code';
                $report_db_fieldlist_array['1251'] = 'curcode';
                $report_db_fieldlist_array['1208'] = 'trancode';
                $report_db_fieldlist_array['316'] = 'branch';
                $report_db_fieldlist_array['1168'] = 'donor_code';
                $report_db_fieldlist_array['1107'] = 'fund_code';
                $report_db_fieldlist_array['1540'] = 'username';
                $report_db_fieldlist_array['1251'] = 'curcode';


                break;

            case 'BREAKPERACC': // Breakdown Per Account      
            case 'CASHFLOW': // Cashflow
            case 'EQUITYCHA': // Changes in Equity

                self::getlables("296,97,264,301,289,297,249,298,1096,1082,1251,1208,316,1168,1107", "", "", self::$connObj);

                $report_db_fieldlist_array = array();
                $report_db_fieldlist_array['298'] = 'transaction_date';
                $report_db_fieldlist_array['296'] = 'account';
                $report_db_fieldlist_array['97'] = 'account_label';
                $report_db_fieldlist_array['264'] = 'tdescription';
                $report_db_fieldlist_array['301'] = 'transaction_code';
                $report_db_fieldlist_array['289'] = 'debit';
                $report_db_fieldlist_array['297'] = 'credit';
                $report_db_fieldlist_array['249'] = 'balance';
                $report_db_fieldlist_array['1096'] = 'product_prodid';
                $report_db_fieldlist_array['1082'] = 'costcenters_code';
                $report_db_fieldlist_array['1251'] = 'currencies_id';
                $report_db_fieldlist_array['1208'] = 'trancode';
                $report_db_fieldlist_array['316'] = 'branch_code';
                $report_db_fieldlist_array['1168'] = 'donor_code';
                $report_db_fieldlist_array['1107'] = 'fund_code';

                break;

            default:

                break;
        }
        // 19/04/2017
        // Note: General fields will not be displayed if report code is not added here
        // add general fields
        //
        switch ($code) {
            case 'TDRPT':
            case 'CLIENTRPTS':
            case 'CLIENTLOANFREQ':
            case 'OUTBAL':
            case 'SAVTILL':
            case 'SAVBALRPT':
            case 'INTSAVRPT':
            case 'ARRERPT':
            case 'PORTRSK':
            case 'DISBURSEMENTS':
            case 'LOANREP':
            case 'DUESLN':
            case 'GUARANTORS':
                $report_db_fieldlist_array = $report_general_fieldlist_array + $report_db_fieldlist_array;
                break;

            default:
                break;
        }

        // group by/order by
        $report_db_fieldlist_array['order_by'] = 'order_by';
        $report_db_fieldlist_array['group_by'] = 'group_by';

        $selected = self::$lablearray[1274];
        unset(self::$lablearray[1273]);
        unset(self::$lablearray[1274]);

        unset($_SESSION['db_report_columns']);


        $_SESSION['db_report_columns'] = serialize($report_db_fieldlist_array);

        foreach (self::$lablearray as $key => $value) {
            $rpt_contrs .= "<option value='" . $key . "' id='" . $key . "'>" . $value . "</option>";
        }


        $rpt_contrs .= '</select>';
        $rpt_contrs .= '</td>';
        $rpt_contrs .= '<td align="center">';
        $rpt_contrs .= '<button class="btn" name="Go"  type="button" onClick="listbox_moveacross(\'fieldlist\', \'selected_columns\')">->></button><br>';
        $rpt_contrs .= '<button class="btn" name="Go"  type="button" onClick="listbox_moveacross(\'selected_columns\', \'fieldlist\')"><<-</button><br>';
        $rpt_contrs .= '<button class="btn" name="Go"  type="button" onClick="listbox_selectall(\'fieldlist\', \'selected_columns\')">>>></button><br>';
        $rpt_contrs .= '<button class="btn" name="Go"  type="button" onClick="listbox_selectall(\'selected_columns\', \'fieldlist\')"><<<</button><br>';
        $rpt_contrs .= '</td>';
        $rpt_contrs .= '<td>';
        $rpt_contrs .= "<select multiple size='10' name='selected_columns' id='selected_columns' style='height:190px;margin:0px;'>";

        // CHECK SEE IF WE HAVE DEFAULT COLUMNS
        if (isset($report_db_fieldlist_default_array) > 0):
            foreach ($report_db_fieldlist_default_array as $key => $value) {
                $rpt_contrs .= "<option value='" . $key . "' id='" . $key . "' selected>" . $value . "</option>";
            }
        endif;

        $rpt_contrs .= "</select>";
        $rpt_contrs .= '</td>';

        $rpt_contrs .= '</tr>';
        $rpt_contrs .= '<tr>';
        $rpt_contrs .= '<td colspan="3" nowrap align="center">';
        // GROUP BY AND  ORDER BY
        $rpt_contrs .= common::generateGroupByOrderBy($code, self::$connObj);
        $rpt_contrs .= '</td>';

        $rpt_contrs .= '</tr>';
        $rpt_contrs .= '</table>';
        $rpt_contrs .= '</div></fieldset>';

        return $rpt_contrs_ext . $rpt_fieldset . $rpt_contrs;
    }

    // function used to generate report controls    
    public static function generateReportControls($control = '', &$Conn, $elementid = '') {

        $rpt_contrs = '';

        switch ($control) {
            case 'IMPORTDATAOPTIONS':
                self::getlables("1091,1178,885,1218,1015,1556,1090,1112,1557,1558,1449,1559,1089,98,1024,109,1560", "", "", self::$connObj);
                $import_array['DOC'] = self::$lablearray['1091'];
                $import_array['IND'] = self::$lablearray['1178'];
                $import_array['GRP'] = self::$lablearray['885'];
                $import_array['GRPMEM'] = self::$lablearray['1090'];
                $import_array['BUS'] = self::$lablearray['1015'];
                         
                $import_array['LOANAPPL'] = self::$lablearray['1112'];
                $import_array['LOANAPPR'] = self::$lablearray['1557'];
                $import_array['LOANDISB'] = self::$lablearray['1558'];
                $import_array['LOANREPAY'] = self::$lablearray['1449'];
                $import_array['SAVETRAN'] = self::$lablearray['1559']; // Including Transfers/ Overdrafts
                $import_array['SHARTRAN'] = self::$lablearray['1089'];
                $import_array['GLTRANS'] = self::$lablearray['98'];
                $import_array['CLIENTFEE'] = self::$lablearray['1024'];
                $import_array['COA'] = self::$lablearray['109'];
                $import_array['TIMEDEPO'] = self::$lablearray['1560'];

                KSORT($import_array);

                $rpt_contrs = '<select name="data_code" id="data_code">';
                $rpt_contrs .= '<option id="" value="">-------</option>';
                foreach ($import_array as $bkey => $bval) {
                    $rpt_contrs = $rpt_contrs . '<option id="' . $bkey . '" value="' . $bkey . '">' . $bval . '</option>';
                }
                $rpt_contrs = $rpt_contrs . '</select>';

                break;

            case 'AREACODES':
                $results = self::$connObj->SQLSelect("SELECT areacode_code,areacode_name FROM " . TABLE_AREACODES . " ORDER BY areacode_name");
                if (empty($elementid)) {
                    $elementid = 'areacode_code';
                }
                $rpt_contrs = '<select name="' . $elementid . '" id="' . $elementid . '">';
                $rpt_contrs = '<select name="areacode_code" id="areacode_code">';
                $rpt_contrs .= '<option id="" value="">-------</option>';
                foreach ($results as $bkey => $bval) {

                    $rpt_contrs = $rpt_contrs . '<option id="' . $bval['areacode_code'] . '" value="' . $bval['areacode_code'] . '">' . $bval['areacode_code'] . ' ' . $bval['areacode_name'] . '</option>';
                }
                $rpt_contrs = $rpt_contrs . '</select>';

                break;
            case 'MODEMS':
                $results = self::$connObj->SQLSelect("SELECT modem_id,modem_name,modem_port FROM " . TABLE_MODEM . " ORDER BY modem_name");
//                if (empty($elementid)) {
//                    $elementid = 'areacode_code';
//                }
                //  $rpt_contrs = '<select name="' . $elementid . '" id="' . $elementid . '">';
                $rpt_contrs = '<select name="modems" id="modems">';
                $rpt_contrs .= '<option id="" value="">-------</option>';
                foreach ($results as $bkey => $bval) {

                    $rpt_contrs = $rpt_contrs . '<option id="' . $bval['modem_port'] . '" value="' . $bval['modem_port'] . '">' . $bval['modem_name'] . ' ' . $bval['modem_port'] . '</option>';
                }
                $rpt_contrs = $rpt_contrs . '</select>';

                break;

            case 'LOANCATEGORY':
                $results = self::$connObj->SQLSelect("SELECT loancategory_code,loancategory_name FROM " . TABLE_LOANCATEGORY . " ORDER BY loancategory_code");

                if (empty($elementid)) {
                    $elementid = 'loancategory_code';
                }
                $rpt_contrs = '<select name="' . $elementid . '" id="' . $elementid . '">';
                $rpt_contrs .= '<option id="" value="">-------</option>';

                foreach ($results as $bkey => $bval) {

                    $rpt_contrs = $rpt_contrs . '<option id="' . $bval['loancategory_code'] . '" value="' . $bval['loancategory_code'] . '">' . $bval['loancategory_code'] . ' ' . $bval['loancategory_name'] . '</option>';
                }
                $rpt_contrs = $rpt_contrs . '</select>';

                break;

            case 'COSTCENTERS':

                $results = self::$connObj->SQLSelect("SELECT costcenters_code,costcenters_name FROM " . TABLE_COSTCENTERS . " ORDER BY costcenters_name");
                if (empty($elementid)) {
                    $elementid = 'costcenters_code';
                }
                $rpt_contrs = '<select name="' . $elementid . '" id="' . $elementid . '">';
                $rpt_contrs .= '<option id="" value="">-------</option>';

                foreach ($results as $bkey => $bval) {

                    $rpt_contrs = $rpt_contrs . '<option id="' . $bval['costcenters_code'] . '" value="' . $bval['costcenters_code'] . '">' . $bval['costcenters_code'] . ' ' . $bval['costcenters_name'] . '</option>';
                }
                $rpt_contrs = $rpt_contrs . '</select>';

                break;

            case 'LOANFUND':
                $results = self::$connObj->SQLSelect("SELECT fund_code,fund_name FROM " . TABLE_FUND . " ORDER BY fund_name");
                if (empty($elementid)) {
                    $elementid = 'fund_code';
                }

                $rpt_contrs = '<select name="' . $elementid . '" id="' . $elementid . '">';
                $rpt_contrs .= '<option id="" value="">-------</option>';
                foreach ($results as $bkey => $bval) {

                    $rpt_contrs = $rpt_contrs . '<option id="' . $bval['fund_code'] . '" value="' . $bval['fund_code'] . '">' . $bval['fund_name'] . '</option>';
                }
                $rpt_contrs = $rpt_contrs . '</select>';

                break;

            case 'DONOR':
                $results = self::$connObj->SQLSelect("SELECT donor_code,donor_name FROM " . TABLE_DONOR . " ORDER BY donor_name");
                if (empty($elementid)) {
                    $elementid = 'donor_code';
                }

                $rpt_contrs = '<select name="' . $elementid . '" id="' . $elementid . '">';
                $rpt_contrs .= '<option id="" value="">-------</option>';
                foreach ($results as $bkey => $bval) {

                    $rpt_contrs = $rpt_contrs . '<option id="' . $bval['donor_code'] . '" value="' . $bval['donor_code'] . '">' . $bval['donor_name'] . '</option>';
                }
                $rpt_contrs = $rpt_contrs . '</select>';

                break;

            case 'CLIENTCATEGORY1':
                $results = self::$connObj->SQLSelect("SELECT category1_code,category1_name FROM " . TABLE_CATEGORY1 . " ORDER BY category1_name");
                if (empty($elementid)) {
                    $elementid = 'client1_code';
                }
                $rpt_contrs = '<select name="' . $elementid . '" id="' . $elementid . '">';

                $rpt_contrs .= '<option id="" value="">-------</option>';
                foreach ($results as $bkey => $bval) {

                    $rpt_contrs = $rpt_contrs . '<option id="' . $bval['category1_code'] . '" value="' . $bval['category1_code'] . '">' . $bval['category1_name'] . '</option>';
                }
                $rpt_contrs = $rpt_contrs . '</select>';

                break;

            case 'CLIENTCATEGORY2':
                $results = self::$connObj->SQLSelect("SELECT category2_code,category2_name FROM " . TABLE_CATEGORY2 . " ORDER BY category2_name");
                if (empty($elementid)) {
                    $elementid = 'client2_code';
                }
                $rpt_contrs = '<select name="' . $elementid . '" id="' . $elementid . '">';

                $rpt_contrs .= '<option id="" value="">-------</option>';
                foreach ($results as $bkey => $bval) {

                    $rpt_contrs = $rpt_contrs . '<option id="' . $bval['category2_code'] . '" value="' . $bval['category2_code'] . '">' . $bval['category2_name'] . '</option>';
                }
                $rpt_contrs = $rpt_contrs . '</select>';

                break;


            case 'CLIENTCATEGORY3':
                $results = self::$connObj->SQLSelect("SELECT category3_code,category3_name FROM " . TABLE_CATEGORY3 . " ORDER BY category3_name");
                if (empty($elementid)) {
                    $elementid = 'client3_code';
                }
                $rpt_contrs = '<select name="' . $elementid . '" id="' . $elementid . '">';


                $rpt_contrs .= '<option id="" value="">-------</option>';
                foreach ($results as $bkey => $bval) {

                    $rpt_contrs = $rpt_contrs . '<option id="' . $bval['category3_code'] . '" value="' . $bval['category3_code'] . '">' . $bval['category3_name'] . '</option>';
                }
                $rpt_contrs = $rpt_contrs . '</select>';

                break;

            case 'GLTRANSACTIONTYPES':

                $fieldname = 'trancodes_description';

                switch ($_SESSION['P_LANG']) {

                    case 'EN':
                        $fieldname = 'trancodes_description';
                        break;

                    case 'FR':
                        $fieldname = 'trancodes_fr';
                        break;

                    case 'SWA':
                        $fieldname = 'trancodes_swa';
                        break;

                    case 'RU':
                        $fieldname = 'trancodes_ru';
                        break;

                    default:
                        break;
                }

                $results = self::$connObj->SQLSelect("SELECT trancodes_code," . $fieldname . " name FROM " . TABLE_TRANCODES . " ORDER BY " . $fieldname);
                if (empty($elementid)) {
                    $elementid = 'trancodes_code';
                }
                $rpt_contrs = '<select name="' . $elementid . '" id="' . $elementid . '">';


                $rpt_contrs .= '<option id="" value="">-------</option>';
                foreach ($results as $bkey => $bval) {

                    $rpt_contrs = $rpt_contrs . '<option id="' . $bval['trancodes_code'] . '" value="' . $bval['trancodes_code'] . '">' . $bval['name'] . '</option>';
                }
                $rpt_contrs = $rpt_contrs . '</select>';

                break;

            case 'CURRENCIES':
                $results = self::$connObj->SQLSelect("SELECT currencies_code,name,currencies_id FROM " . TABLE_CURRENCIES . " ORDER BY name");
                if (empty($elementid)) {
                    $elementid = 'currencies_id';
                }
                $rpt_contrs = '<select name="' . $elementid . '" id="' . $elementid . '">';


                $rpt_contrs .= '<option id="" value="">-----</option>';
                foreach ($results as $bkey => $bval) {

                    $rpt_contrs = $rpt_contrs . '<option id="' . $bval['currencies_id'] . '" value="' . $bval['currencies_id'] . '">' . $bval['currencies_code'] . '</option>';
                }
                $rpt_contrs = $rpt_contrs . '</select>';

                break;

            case 'BUSSINESSECTOR':
                $results = self::$connObj->SQLSelect("SELECT bussinesssector_code,bussinesssector_name FROM " . TABLE_BUSSINESSECTOR);
                if (empty($elementid)) {
                    $elementid = 'bussinesssector_code';
                }
                $rpt_contrs = '<select name="' . $elementid . '" id="' . $elementid . '">';


                $rpt_contrs .= '<option id="" value="">-------</option>';
                foreach ($results as $bkey => $bval) {

                    $rpt_contrs = $rpt_contrs . '<option id="' . $bval['bussinesssector_code'] . '" value="' . $bval['bussinesssector_code'] . '">' . $bval['bussinesssector_name'] . '</option>';
                }
                $rpt_contrs = $rpt_contrs . '</select>';

                break;

            case 'PRODUCTS':
                $results = self::$connObj->SQLSelect("SELECT product_name,product_prodid FROM " . TABLE_PRODUCT);
                if (empty($elementid)) {
                    $elementid = 'product_prodid';
                }
                $rpt_contrs = '<select name="' . $elementid . '" id="' . $elementid . '">';

                $rpt_contrs .= '<option id="" value="">-------</option>';
                foreach ($results as $bkey => $bval) {

                    $rpt_contrs = $rpt_contrs . '<option id="' . $bval['product_prodid'] . '" value="' . $bval['product_prodid'] . '">' . $bval['product_name'] . '</option>';
                }
                $rpt_contrs = $rpt_contrs . '</select>';

                break;

            case 'BRANCHES':
                $results = self::$connObj->SQLSelect("SELECT l.branchcode, l.licence_organisationname FROM " . TABLE_USERBRANCHES . " bb," . TABLE_LICENCE . " l WHERE bb.branch_code = l.branch_code AND l.licence_build='" . $_SESSION['licence_build'] . "'  AND bb.user_usercode='" . $_SESSION["user_usercode"] . "'");

                if (empty($elementid)) {
                    $elementid = 'branch_code';
                }

                $rpt_contrs = '<select name="' . $elementid . '" id="' . $elementid . '">';

                $rpt_contrs .= '<option id="" value="">-------</option>';
                foreach ($results as $bkey => $bval) {
                    $rpt_contrs = $rpt_contrs . '<option id="' . $bval['branchcode'] . '" value="' . $bval['branchcode'] . '">' . $bval['licence_organisationname'] . '</option>';
                }
                $rpt_contrs = $rpt_contrs . '</select>';
                break;

            case 'USERS':
                $results = self::$connObj->SQLSelect("SELECT u.user_usercode,CONCAT(user_username,' ',user_firstname,' ',user_lastname) name FROM " . TABLE_USERS . " u, " . TABLE_USERBRANCHES . " bb  WHERE u.user_usercode=bb.user_usercode AND bb.branch_code IN ('" . self::getCommaDelimitedListFromArray($_SESSION['branches']) . "')");
                if (empty($elementid)) {
                    $elementid = 'user_id';
                }
                $rpt_contrs = '<select name="' . $elementid . '" id="' . $elementid . '">';

                $rpt_contrs .= '<option id="" value="">-------</option>';
                foreach ($results as $bkey => $bval) {
                    $rpt_contrs = $rpt_contrs . '<option id="' . $bval['user_usercode'] . '" value="' . $bval['user_usercode'] . '">' . $bval['name'] . '</option>';
                }
                $rpt_contrs = $rpt_contrs . '</select>';
                break;

            case 'LOANCATEGORY1':

                $results = self::$connObj->SQLSelect("SELECT loancategory_code,loancategory_name FROM " . TABLE_LOANCATEGORY);
                if (empty($elementid)) {
                    $elementid = 'loancategory1_code';
                }
                $rpt_contrs = '<select name="' . $elementid . '" id="' . $elementid . '">';


                $rpt_contrs .= '<option id="" value="">-------</option>';
                foreach ($results as $bkey => $bval) {
                    $rpt_contrs = $rpt_contrs . '<option id="' . $bval['loancategory_code'] . '" value="' . $bval['loancategory_code'] . '">' . $bval['loancategory_name'] . '</option>';
                }
                $rpt_contrs = $rpt_contrs . '</select>';
                break;

            case 'LOANCATEGORY2':

                $results = self::$connObj->SQLSelect("SELECT loancategory_code,loancategory_name FROM " . TABLE_LOANCATEGORY);
                if (empty($elementid)) {
                    $elementid = 'loancategory2_code';
                }
                $rpt_contrs = '<select name="' . $elementid . '" id="' . $elementid . '">';

                $rpt_contrs .= '<option id="" value="">-------</option>';
                foreach ($results as $bkey => $bval) {
                    $rpt_contrs = $rpt_contrs . '<option id="' . $bval['loancategory_code'] . '" value="' . $bval['loancategory_code'] . '">' . $bval['loancategory_name'] . '</option>';
                }
                $rpt_contrs = $rpt_contrs . '</select>';
                break;

            case 'ACCOUNTSRPTS': // financial reports
                $_SESSION['rpt'] = $control;


                self::getlables("1251,1318,1319,38,922,1304,1305,1306,1307,1308,1309,1310,1311,1312,1313,1314,1315,1316,1317", "", "", $Conn);
                $rpt_contrs = '<table cellspacing="0" cellpadding="0" border="0" width="100%">';
                $rpt_contrs .= '<tr>';
                $rpt_contrs .= '<td valign="top">';
                $rpt_contrs .= '<fieldset>';
                $rpt_contrs .= '<table cellspacing="0" cellpadding="0" border="0" >';
                $rpt_contrs .= '<tr>';
                $rpt_contrs .= '<td>' . self::$lablearray['38'] . ':<br><input type="us-date"  id="startDate" name="startDate"   constraints="{datePattern:\'' . self::convertDateJSFormat() . '\', strict:true}"></td><td>' . self::$lablearray['922'] . ':<br><input type="us-date"   id="endDate" name="endDate"  constraints="{datePattern:\'' . self::convertDateJSFormat() . '\', strict:true}"></td>';
                $rpt_contrs .= '</tr>';
                $rpt_contrs .= '<tr>';
                $rpt_contrs .= '<td>' . self::$lablearray['1318'] . ':<br>' . self::generateReportControls('BRANCHES', Common::$connObj, 'branch_codefr') . '</td><td>' . self::$lablearray['1319'] . ':<br>' . self::generateReportControls('BRANCHES', Common::$connObj, 'branch_codeto') . '</td>';
                $rpt_contrs .= '</tr>';
                $rpt_contrs .= '<tr>';
                $rpt_contrs .= '<td>' . self::$lablearray['1306'] . ':<br>' . self::generateReportControls('DONOR', Common::$connObj, 'donor_codefr') . '</td><td>' . self::$lablearray['1307'] . ':<br>' . self::generateReportControls('BRANCHES', Common::$connObj, 'donor_codeto') . '</td>';
                $rpt_contrs .= '</tr>';
                $rpt_contrs .= '<tr>';
                $rpt_contrs .= '<td>' . self::$lablearray['1308'] . ':<br>' . self::generateReportControls('PRODUCTS', Common::$connObj, 'product_prodidfr') . '</td><td>' . self::$lablearray['1309'] . ':<br>' . self::generateReportControls('PRODUCTS', Common::$connObj, 'product_prodidto') . '</td>';
                $rpt_contrs .= '</tr>';
                $rpt_contrs .= '<tr>';
                $rpt_contrs .= '<td>' . self::$lablearray['1310'] . ':<br>' . self::generateReportControls('COSTCENTERS', Common::$connObj, 'costcenters_codefr') . '</td><td>' . self::$lablearray['1311'] . ':<br>' . self::generateReportControls('COSTCENTERS', Common::$connObj, 'costcenters_codeto') . '</td>';
                $rpt_contrs .= '</tr>';
                $rpt_contrs .= '<tr>';
                $rpt_contrs .= '<td>' . self::$lablearray['1312'] . ':<br>' . self::generateReportControls('USERS', Common::$connObj, 'user_idfr') . '</td><td>' . self::$lablearray['1313'] . ':<br>' . self::generateReportControls('USERS', Common::$connObj, 'user_idto') . '</td>';
                $rpt_contrs .= '</tr>';
                $rpt_contrs .= '<tr>';
                $rpt_contrs .= '<td>' . self::$lablearray['1314'] . ':<br>' . self::generateReportControls('GLTRANSACTIONTYPES', Common::$connObj, 'trancodes_codefr') . '</td><td>' . self::$lablearray['1315'] . ':<br>' . self::generateReportControls('GLTRANSACTIONTYPES', Common::$connObj, 'trancodes_codeto') . '</td>';
                $rpt_contrs .= '</tr>';
                $rpt_contrs .= '<tr>';
                $rpt_contrs .= '<td>' . self::$lablearray['1316'] . ':<br>' . DrawComboFromArray(array(), 'accountcodefr', 'accountcodefr', 'COACOMBO') . '</td><td>' . self::$lablearray['1317'] . ':<br>' . DrawComboFromArray(array(), 'accountcodeto', 'accountcodeto', 'COACOMBO') . '</td>';
                $rpt_contrs .= '</tr>';
                $rpt_contrs .= '<tr>';
                $rpt_contrs .= '<td>' . self::$lablearray['1251'] . ':<br>' . self::generateReportControls('CURRENCIES', Common::$connObj, 'currencies_id') . '</td><td></td>';
                $rpt_contrs .= '</tr>';
                $rpt_contrs .= '<tr>';
                $rpt_contrs .= '<td>' . self::$lablearray['1251'] . ':<br><input type="text" maxlength=400 value="" id="footnote"></td><td></td>';
                $rpt_contrs .= '</tr>';
                $rpt_contrs .= '</table>';
                $rpt_contrs .= '</fieldset>';
                $rpt_contrs .= '</td>';

                $rpt_contrs .= '<td valign="top" colspan="2" align="center">';
                $rpt_contrs .= '<div id="div_report_columns" >';
                $rpt_contrs .= '</div>';

                /* add extra control here
                 * 
                 */
                $rpt_contrs .= "<script>                     
                        $('#code').on('change', function() {        
                           showValues('frmreportsui','div_report_columns','loadelement','','load.php',$('#code').val()).done(
                           
                                function () {
                                 //  $('input[type=us-date]').w2field('date',  { format: '<?php echo SETTING_DATE_FORMAT?>'})
                                  //  $('input[type=us-date]').w2field('date');                                  
                                });
                                 

                         });             
                     </script>";

                $rpt_contrs .= '<div>' . self::reportsTypeList($control) . '</div>';

                self::getlables("300", "", "", Common::$connObj);
                $rpt_contrs .= self::printOptions() . '<button class="btn" name="Go"  type="button" onClick="CloseDialog(\'myDialogId1\');"  id="btnscancel">' . self::$lablearray['300'] . '</button>';

                $rpt_contrs .= '</td>';

                $rpt_contrs .= '</tr>';
                $rpt_contrs .= '</table>';

                break;

            case 'TIMEDEPOSITRPTS':
            case 'SAVINGSRPTS':
            case 'CLIENTRPTS':
            case 'LOANRPTS':

                $_SESSION['rpt'] = $control;


                self::getlables("197,1208,316,42,1571,43,886,1015,1090,68,885,1257,1243,1244,1245,1052,922,38,1082,1109,1110,1111,1108,1096,1015,1251,1081,1083,1252,1082,1081,1083,1253", "", "", $Conn);

                $rpt_contrs = '<table cellspacing="0" cellpadding="0" border="0" width="80%">';
                $rpt_contrs .= '<tr>';

                $rpt_contrs .= '<td valign="top">';
                $rpt_contrs .= '<table cellspacing="0" cellpadding="0" border="0" width="100%">';
                $rpt_contrs .= '<tr><td>' . self::$lablearray['38'] . '<br><input type="us-date" id="startDate" name="startDate"  constraints="{datePattern:\'' . self::convertDateJSFormat() . '\', strict:true}"></td></tr>';
                $rpt_contrs .= '<tr><td>' . self::$lablearray['316'] . '<br>' . self::generateReportControls('BRANCHES', self::$connObj) . '</td></tr>';

                $rpt_contrs .= '<tr><td>' . self::$lablearray['1257'] . '<br>
                               <select  id="client_regstatus" name="client_regstatus">
                                <option value="">-------</option>
                                <option value="ACT">' . self::$lablearray['68'] . '</option>
                                <option value="INA">' . self:: $lablearray['1243'] . '</option>									
                                <option value="EXT">' . self::$lablearray['1244'] . '</option>
                                <option value="CLO">' . self::$lablearray['1245'] . '</option>	
                            </select>                                     
                            </td></tr>';
                $rpt_contrs .= '<tr><td>' . self::$lablearray['1081'] . '<br>' . self::generateReportControls('CLIENTCATEGORY1', self::$connObj) . '</td></tr>';
                $rpt_contrs .= '<tr><td>' . self::$lablearray['1083'] . '<br>' . self::generateReportControls('CLIENTCATEGORY2', self::$connObj) . '</td></tr>';
                $rpt_contrs .= '<tr><td>' . self::$lablearray['1253'] . '<br>' . self::generateReportControls('CLIENTCATEGORY3', self::$connObj) . '</td></tr>';
                $rpt_contrs .= '<tr><td>' . self::$lablearray['1052'] . '<br>' . self::generateReportControls('AREACODES', self::$connObj) . '</td></tr>';

                $rpt_contrs .= '<tr><td>
                            <input  type="radio" id="radioall" name="client_type" value=""  >
                            ' . self::$lablearray['43'] . '<br><input  type="radio" id="radioind" name="client_type" value="I" checked>
                            ' . self::$lablearray['886'] . '<br><input type="radio" id="radiogrp" name="client_type" value="G" >
                            ' . self::$lablearray['885'] . '<br><input type="radio" id="radiobuss" name="client_type" value="B">
                           ' . self::$lablearray['1015'] . '<br>  
                            <input type="radio" id="radiogm" name="client_type" value="GM">
                            ' . self::$lablearray['1090'];

                $rpt_contrs .= '</td></tr>';
                $rpt_contrs .= '<tr><td>';
                $rpt_contrs .= '<br>' . self::$lablearray['1571'] . '<br><input type="text" value="" id="footnote" name="footnote" style="width: 400px;">';
                $rpt_contrs .= '</td></tr>';
                $rpt_contrs .= '</table>';
                $rpt_contrs .= '</td>';

                $rpt_contrs .= '<td valign="top">';
                $rpt_contrs .= '<table cellspacing="" cellpadding="2" border="0" width="100%">';
                $rpt_contrs .= '<tr><td>' . self::$lablearray['922'] . '<br><input type="us-date" id="endDate" name="endDate"  constraints="{datePattern:\'' . self::convertDateJSFormat() . '\', strict:true}"></td></tr>';

                if ($control == 'TIMEDEPOSITRPTS'):
                    $rpt_contrs .= '<tr><td>' . self::$lablearray['197'] . '<br>' . self::DrawComboFromArray(array(), 'tdstatus', '', 'TDSTATUS2', '', 'tdstatus', 'frmreportsui') . '</td></tr>';
                else:
                    $rpt_contrs .= '<tr><td>' . self::$lablearray['1111'] . '<br>' . self::generateReportControls('USERS', self::$connObj) . '</td></tr>';
                endif;

                $rpt_contrs .= '<tr><td>' . self::$lablearray['1015'] . '<br>' . self::generateReportControls('BUSSINESSECTOR', self::$connObj) . '</td></tr>';
                $rpt_contrs .= '<tr><td>' . self::$lablearray['1109'] . '<br>' . self::generateReportControls('LOANCATEGORY1', self::$connObj) . '</td></tr>';
                $rpt_contrs .= '<tr><td>' . self::$lablearray['1108'] . '<br>' . self::generateReportControls('LOANCATEGORY2', self::$connObj) . '</td></tr>';

                $rpt_contrs .= '<tr><td>' . self::$lablearray['1251'] . '<br>' . self::generateReportControls('CURRENCIES', self::$connObj) . '</td></tr>';
                $rpt_contrs .= '<tr><td>' . self::$lablearray['1096'] . '<br>' . self::generateReportControls('PRODUCTS', self::$connObj) . '</td></tr>';
                $rpt_contrs .= '<tr><td>' . self::$lablearray['1252'] . '<br>' . self::generateReportControls('LOANFUND', self::$connObj) . '</td></tr>';
                $rpt_contrs .= '<tr><td>' . self::$lablearray['1082'] . '<br>' . self::generateReportControls('COSTCENTERS', self::$connObj) . '</td></tr>';


                $rpt_contrs .= '</table>';
                $rpt_contrs .= '</td>';

                $rpt_contrs .= '<td valign="top" colspan="2" align="center">';


                $rpt_contrs .= '<div id="div_report_columns" >';
                $rpt_contrs .= '</div>';

                /* add extra control here
                 * 
                 */

                $rpt_contrs .= "<script>                     
                        $('#code').on('change', function() {        
                           showValues('frmreportsui','div_report_columns','loadelement','','load.php',$('#code').val());

                         });             
                     </script>";

                $rpt_contrs .= '<div style="clear:both;padding-top:15px;padding-bottom:2px;margin:5px;width:auto;">' . self::reportsTypeList($control) . '</div>';


                self::getlables("300", "", "", $Conn);
                $rpt_contrs .= '<div style="width:195px;">' . self::printOptions() . '<button class="btn" name="Go"  type="button"   id="btnscancel">' . self::$lablearray['300'] . '</button></div>';

                $rpt_contrs .= '</td>';

                $rpt_contrs .= '</tr>';
                $rpt_contrs .= '</table>';

                break;

            default:
                break;
        }

        return $rpt_contrs;
    }
}?>