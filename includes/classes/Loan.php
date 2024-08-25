<?php
include_once('connectionfactory.php');
require_once('productconfig.php');
require_once('common.php');
require_once('SerializeUnserialize.php');
require_once('financial_class.php');

class Loan extends ProductConfig {

    public static $incomingvars = array('grace' => 0,
        'INTTYPE' => 'FR',
        'INSTYPE' => 'M',
        'lamount' => 0,
        'intrate' => 0,
        'grace' => 0,
        'gracecompint' => 0,
        'no_of_inst' => 0,
        'allintpaidfirstinstallment' => 'F',
        'intgrace' => '',
        'intpaidatdisbursement' => '',
        'alsograce' => '',
        'productcode' => '',
        'insintgrace' => '',
        'startDate' => '',
        'adjustDueDatesTo' => '0',
        'adjusttononworkingday' => '',
        'nInterest' => 0,
        'intCompounded' => '',
        'annualnterestRate' => 'Y',
        'GRP' => array());
    private static $startDateActual = '';
    private static $tempfilename = '';
    private static $extraDays = 0;
    private static $InstallmentsInGrace = 0;
    private static $installmentFrequency = 1;
    private static $no_of_inst_in_grace = 0;
    public static $loanShedule;
    public static $expDate = '';
    public static $monthlyDateAdjust = '';
    public static $InterestInGrace = 0;
    private static $FinFuncObj;
    private static $commonObj;
    Public static $connObj;
    private static $tempTotalInterest = 0;
    private static $tempTotalCommission = 0;
    private static $intCompounded = 0;
    private static $princCompounded = 0;
    private static $memberid = '';
    private static $nRowCount = 0;
    public static $disbursements = array();
    public static $loanpayments = array();
    public static $loandues = array();
    public static $loanduesbeforeduedate = array();
    public static $currentloandues = array();
    public static $outstanding = array();
    public static $prepaiddues = array();
    public static $overpayments = array('pay_date' => '', 'due_principal' => 0, 'due_interest' => 0, 'due_penalty' => 0, 'due_commission' => 0, 'due_vat' => 0, 'Total' => 0, 'members_idno' => '');
    public static $loanarrears = array();
    public static $loantotaldues = array();
    public static $loanappdetails = array();
    public static $loanproductsettings = array();
    public static $balances = array();
    public static $cLnr = '';
    public static $clientid = '';
    public static $clienttype = '';
    public static $paydate = '';
    private static $pulldatescloser = 0;
    private static $_instance = null;
    public static $paymentpriority = 'PRINC-INT-COMM-PEN';
    public static $callmodule = '';
    public static $isBulkInsert = false;
    public static $payments = array();
    public static $actualpayments = array();

    //  public static $outstanding = array();

    public function __construct($aFormvars = array(), $cLnr = '') {

        self::$commonObj = new Common;



        self::$connObj = ConnectionFactory::getInstance();

        // check see if there are values passed in array
        if (count($aFormvars) > 0) {
            // overwrite the default values of array incomingvars with vaues coming from the form(both array should have the same keys)
            static::$incomingvars = array_merge(static::$incomingvars, $aFormvars);
        }

        // check see if this is a DD Loan or DA loan- Initialise the  financial functions  object
        if (static::$incomingvars['INTTYPE'] == 'DD' || static::$incomingvars['INTTYPE'] == 'DA') {
            self::$FinFuncObj = new Financial();
        }

        if (self::$_instance === null) {
            //  self::$_instance = new self;
        }

        self::$cLnr = $cLnr;

        if (self::$cLnr != "") {
            self::$loanappdetails = call_user_func_array('array_merge', self::$connObj->SQLSelect("SELECT l.*,"
                            . "CASE WHEN INSTR(l.client_idno,'G')>0 THEN (SELECT entity_name  FROM " . TABLE_ENTITY . " g WHERE g.entity_idno=l.client_idno) ELSE (SELECT CONCAT(client_surname,' ',client_firstname,' ',client_middlename)  FROM " . TABLE_CLIENTS . " c WHERE c.client_idno=l.client_idno) END name "
                            . " FROM " . TABLE_LOAN . " l  WHERE loan_number='" . $cLnr . "'"));

            SerializeUnserialize::getInstance()->put_serialized_data('loandetails_' . Common::replace_string(self::$loanappdetails['loan_number']) . '.txt', self::$loanappdetails);

            // check see if we are to charge interest            
            self::$loanproductsettings = self::$connObj->SQLSelect("SELECT product_prodid,productconfig_paramname name,productconfig_value value FROM " . TABLE_PRODUCTCONFIG . "  WHERE productconfig_paramname IN('CHARGE_INT','ALLOW_OVERPAYMENTS','PAY_PRIORITY') AND product_prodid='" . self::$loanappdetails['product_prodid'] . "' AND branch_code='" . self::$loanappdetails['branch_code'] . "'");
        }
        parent::__construct(self::$loanappdetails['product_prodid'], self::$loanappdetails['branch_code']);

        return $this;
    }

    public static function getInstance() {
        if (self::$_instance === null) {
            self::$_instance = new self;
        }

        return self::$_instance;
    }

    // This function us used to calculate the number of installment that might fall the grace period
    //(if any) based on the frequnsy of payment
    public static function getNumberOfInstallInGrace() {

        //	$installmentDays = 1;
        self::getInstallmentFrequency();
        //echo self::$incomingvars['alsograce']


        if (self::$incomingvars['intgrace'] == 'Y' && self::$incomingvars['insintgrace'] == 'Y') {

            // get number of  WHOLE installment 
            self::$no_of_inst_in_grace = ceil(self::$incomingvars['grace'] / self::$installmentFrequency);

            // check see if we are addding installemtns in the grace period
            // check see if their is a remainder of days ,those days will also be considered as an installment
            if (self::$incomingvars['insintgrace'] == 'Y' && self::$incomingvars['grace'] - ( self::$installmentFrequency * self::$no_of_inst_in_grace) > 0) {

                self::$no_of_inst_in_grace = self::$no_of_inst_in_grace + 1;
            }


            return self::$no_of_inst_in_grace;
        }
    }

    // This function us used to get the frequency at which the schedule will be genaretated
    public static function getInstallmentFrequency() {


        switch (self::$incomingvars['INSTYPE']) {
            case 'D': //Daily
                self::$installmentFrequency = 1;
                break;

            case 'W': // Weekly
                self::$installmentFrequency = 7;
                break;

            case 'B': // Bi-Weekly
                self::$installmentFrequency = 14;
                break;

            case 'H':
                self::$installmentFrequency = 15;
                break;

            case 'O':
                self::$installmentFrequency = 28;
                break;

            case 'M': // Monthly
                self::$installmentFrequency = 30;
                break;

            case 'T': // Two-monthyly
                self::$installmentFrequency = 60;
                break;

            case 'Q': // Quartery
                self::$installmentFrequency = 90;
                break;

            case 'F': // Four-monthly
                self::$installmentFrequency = 120;
                break;

            case 'I':
                self::$installmentFrequency = 150;
                break;

            case 'S': // Semi-Annually
                self::$installmentFrequency = 180;
                break;

            case 'E':
                self::$installmentFrequency = 180;
                break;

            case 'A': // Annually
                self::$installmentFrequency = DAYSINYEAR;
                break;
        }
    }

    // This function is used to create the number of installment to be incoporated
    // P: Partial Installment in terms of days,C: Complete Installent in terns of days
    public static function createInstallmentSchedule() {

        if (static::$incomingvars['INSTYPE'] == 'D') {
            static::$incomingvars['adjustDueDatesTo'] = 0;
        }

        // check see if interest is deductable at disbursment
        // add one installment for the interest
        if (self::$incomingvars['intpaidatdisbursement'] == 'Y') {
            self::$InstallmentsInGrace = self::$InstallmentsInGrace + 1;
            //self::$incomingvars['no_of_inst'] = self::$incomingvars['no_of_inst'] + 1;
        }

        if (self::$incomingvars['grace'] > 0) {
            self::getNumberOfInstallmentsInGracePeriod(self::$incomingvars['startDate']);
        } else {


            // check see if there is no grace period but we have to pay interest at disbursement
            if (self::$incomingvars['intpaidatdisbursement'] == 'Y') {
                self::$incomingvars['no_of_inst'] = self::$incomingvars['no_of_inst'] + 1;
            }
        }

        if (self::$InstallmentsInGrace > 0 && self::$incomingvars['grace'] > 0) {
            $tempDate['date'] = trim(self::$loanShedule[count(self::$loanShedule) - 1]['date']);
        } else {
            $tempDate['date'] = trim(self::$incomingvars['startDate']);
        }
        $nCount = 1;


        // looop to create schedule
        for ($nCount = 1; $nCount <= self::$incomingvars['no_of_inst']; $nCount++) {

            //self::$loanShedule[0]['memid']= $nCount;			
            if ($nCount == 1 && self::$InstallmentsInGrace > 0) {
                if (self::$incomingvars['intpaidatdisbursement'] != 'Y') {

                    //$tempDate['date'] = trim(self::$loanShedule[count(self::$loanShedule)-1]['date']);

                    $tempDate = Common::calculateDate('+', $tempDate['date'], 0, self::$incomingvars['INSTYPE'], self::$incomingvars['adjustDueDatesTo'], self::$incomingvars['adjusttononworkingday']);
                    //self::$loanShedule[0]['memid']= $tempDate['date'];				
                } else {
                    //$tempDate =	Common::calculateDate('+',$tempDate['date'],0,self::$incomingvars['INSTYPE'],self::$incomingvars['adjustDueDatesTo']);				
                    $tempDate = Common::calculateDate('+', $tempDate['date'], 0, self::$incomingvars['INSTYPE'], self::$incomingvars['adjustDueDatesTo'], self::$incomingvars['adjusttononworkingday']);
                }
            } else {


                //calculateDate($addsub='+',$dtDate='',$no_of_dmy=0,$myd='D',$adjustDateTo=0
                $tempDate = Common::calculateDate('+', $tempDate['date'], 0, self::$incomingvars['INSTYPE'], self::$incomingvars['adjustDueDatesTo'], self::$incomingvars['adjusttononworkingday']);
            }

            // check see if we have grace period without installments in grace period
            self::$loanShedule[] = array('item_id' => ++self::$nRowCount, 'date' => trim($tempDate['date']), 'principal' => 0, 'interest' => 0, 'commission' => 0.0, 'penalty' => 0.0, 'other' => 0, 'state' => 'C', 'instype' => 'D', 'memid' => self::$memberid);

            //}
        }
    }

    // This function get the number of installment in the grace period
    public static function getNumberOfInstallmentsInGracePeriod($dtDate = '') {

        $nPrevDate = '';


        //get end of grace period date
        $endDate = Common::calculateDate('+', $dtDate, self::$incomingvars['grace'], 'D', 0, self::$incomingvars['adjusttononworkingday']);



        $date1 = date_create_from_format(SETTING_DATE_FORMAT, $endDate['date']);
        $prevDate = $dtDate;
        $i = 0;
        $tempDate['date'] = $dtDate;

        do {

            $tempDate = Common::calculateDate('+', $tempDate['date'], 0, self::$incomingvars['INSTYPE'], self::$incomingvars['adjustDueDatesTo'], self::$incomingvars['adjusttononworkingday']);

            $date2 = date_create_from_format(SETTING_DATE_FORMAT, trim($tempDate['date']));



            // ge number of days between two days
            $interval = date_diff($date2, $date1);

            $nDays = $interval->format('%R%a');

            if ($nDays == 0) { // check see if days are he same
                self::$InstallmentsInGrace = self::$InstallmentsInGrace + 1;
                self::$extraDays = 0;
                $i = 1;
            } elseif ($nDays < 0) { // check see if date is beyond end of grace period- it would bring negatives
                self::$InstallmentsInGrace = self::$InstallmentsInGrace + 1;

                // get extra interest days- days that do can not make a full installment
                // i.e end of grace period minus last installment date

                $date3 = date_create_from_format(SETTING_DATE_FORMAT, $nPrevDate);
                $interval = date_diff($date3, $date1);

                self::$extraDays = $interval->format('%a');
                $i = 1;
            } elseif ($nDays > 0) {

                self::$extraDays = 0;
                self::$InstallmentsInGrace = self::$InstallmentsInGrace + 1;
            }

            // check see if we have gave extra grace period
            if (self::$extraDays > 0) {

                //if there are extra days we adjust the existing days			 	
                foreach (self::$loanShedule as $key => $val) {
                    $tempDate = Common::calculateDate('+', self::$loanShedule[$key]['date'], self::$extraDays, 'D', self::$incomingvars['adjustDueDatesTo'], self::$incomingvars['adjusttononworkingday']);
                    self::$loanShedule[$key]['date'] = trim($tempDate['date']);
                }

                $tempDate = Common::calculateDate('+', $dtDate, self::$extraDays, 'D', self::$incomingvars['adjustDueDatesTo'], self::$incomingvars['adjusttononworkingday']);

                // insert elment of extra days at the begining					 
                array_unshift(self::$loanShedule, array('item_id' => ++self::$nRowCount, 'date' => trim($tempDate['date']), 'principal' => 0, 'interest' => 0, 'commission' => 0, 'penalty' => 0, 'other' => 0, 'state' => 'P', 'instype' => 'G', 'state' => '', 'memid' => self::$memberid));
                $i = 1;
            } else {

                self::$loanShedule[] = array('item_id' => ++self::$nRowCount, 'date' => trim($tempDate['date']), 'principal' => 0, 'interest' => 0, 'commission' => 0, 'penalty' => 0, 'other' => 0, 'state' => 'C', 'instype' => 'G', 'state' => '', 'memid' => self::$memberid);
            }

            $nPrevDate = $tempDate['date'];
        } while ($i == 0);
    }

    /**
     * distributeLoanPayment
     * 
     * This function is used to distribute a Loan repayments across the different payable items of a loan schedule
     * @param aLines transactions     
     */
    public static function distributePayments(&$aLines) {
      
        $paymentpriority = Common::get_array_elements_with_key_in_3D_array(self::$loanproductsettings, 'PAY_PRIORITY');

        $paypriorities_array = explode("-", $paymentpriority['PAY_PRIORITY']);

        foreach ($paypriorities_array as $key => $pval):

            switch ($pval):

                case 'PRINC': // PRINCIPAL

                    if (self::$payments['totalpay'] >= self::$actualpayments['nprinc']) {
                        $aLines['PRI']['AMOUNT'] = bcadd($aLines['PRI']['AMOUNT'], self::$actualpayments['nprinc'], SETTING_ROUNDING);
                        self::$payments['totalpay'] = bcsub(self::$payments['totalpay'], self::$actualpayments['nprinc'], SETTING_ROUNDING);
                    } else {
                        $aLines['PRI']['AMOUNT'] = bcadd($aLines['PRI']['AMOUNT'], self::$payments['totalpay'], SETTING_ROUNDING);
                        self::$payments['totalpay'] = 0;
                    }

                    if ($aLines['PRI']['AMOUNT'] > self::$outstanding[self::$clientid]['oprinc']):
                        self::$overpayments[0]['Total'] = bcadd(self::$overpayments[0]['Total'], bcsub($aLines['PRI']['AMOUNT'], self::$outstanding[self::$clientid]['oprinc'], SETTING_ROUNDING), SETTING_ROUNDING);
                    endif;

                    break;

                case 'INT': // INTEREST

                    if (self::$payments['totalpay'] >= self::$actualpayments['nint']) {
                        $aLines['INT']['AMOUNT'] = bcadd($aLines['INT']['AMOUNT'], self::$actualpayments['nint'], SETTING_ROUNDING);
                        self::$payments['totalpay'] = bcsub(self::$payments['totalpay'], self::$actualpayments['nint'], SETTING_ROUNDING);
                    } else {
                        $aLines['INT']['AMOUNT'] = bcadd($aLines['INT']['AMOUNT'], self::$payments['totalpay'], SETTING_ROUNDING);
                        self::$payments['totalpay'] = 0;
                    }
                    if ($aLines['INT']['AMOUNT'] > self::$outstanding[self::$clientid]['oint']):
                        self::$overpayments[0]['Total'] = bcadd(self::$overpayments[0]['Total'], bcsub($aLines['INT']['AMOUNT'], self::$outstanding[self::$clientid]['oint'], SETTING_ROUNDING), SETTING_ROUNDING);
                    endif;

                    break;

                case 'COMM': // COMMISSION
                case 'COM':

                    if (self::$payments['totalpay'] >= self::$actualpayments['ncomm']) {
                        $aLines['COM']['AMOUNT'] = bcadd($aLines['COM']['AMOUNT'], self::$actualpayments['ncomm'], SETTING_ROUNDING);
                        self::$payments['totalpay'] = bcsub(self::$payments['totalpay'], self::$actualpayments['ncomm'], SETTING_ROUNDING);
                    } else {
                        $aLines['COM']['AMOUNT'] = bcadd($aLines['COM']['AMOUNT'], self::$payments['totalpay'], SETTING_ROUNDING);
                        self::$payments['totalpay'] = 0;
                    }

                    if ($aLines['COM']['AMOUNT'] > self::$outstanding[self::$clientid]['ocomm']):
                        self::$overpayments[0]['Total'] = bcadd(self::$overpayments[0]['Total'], bcsub($aLines['COM']['AMOUNT'], self::$outstanding[self::$clientid]['ocomm'], SETTING_ROUNDING), SETTING_ROUNDING);
                    endif;

                    break;

                case 'PEN': // PENALTY

                    if (self::$payments['totalpay'] >= self::$actualpayments['npen']) {
                        $aLines['PEN']['AMOUNT'] = bcadd($aLines['PEN']['AMOUNT'], self::$actualpayments['npen'], SETTING_ROUNDING);
                        self::$payments['totalpay'] = bcsub(self::$payments['totalpay'], self::$actualpayments['npen'], SETTING_ROUNDING);
                    } else {
                        $aLines['PEN']['AMOUNT'] = bcadd($aLines['PEN']['AMOUNT'], self::$payments['totalpay'], SETTING_ROUNDING);
                        self::$payments['totalpay'] = 0;
                    }

                    if ($aLines['PEN']['AMOUNT'] > self::$outstanding[self::$clientid]['open']):
                        self::$overpayments[0]['Total'] = bcadd(self::$overpayments[0]['Total'], bcsub($aLines['PEN']['AMOUNT'], self::$outstanding[self::$clientid]['open'], SETTING_ROUNDING), SETTING_ROUNDING);
                    endif;

                    break;

            endswitch;
            
            if((self::$overpayments[0]['Total']??0)>0):
               $aLines['OVR']['AMOUNT'] = self::$overpayments[0]['Total']; 
            endif;           
            
            if (self::$payments['totalpay'] <= 0):
                break;
            endif;

        endforeach;
    }

    /**
     * distributeLoanPayment
     * 
     * This function is used to distribute a Loan repaymet across the different payable items of a loan
     * @param aLines transactions 
     * @params $formdata: data from the form
     */
    public static function distributeLoanPayment(&$aLines, $formdata) {

        try {
            if (!isset($aLines)):
                $aLines = array();
            endif;
            
            $formdata['VAT'] =($formdata['VAT']??'0');
            $formdata['PEN'] =($formdata['PEN']??'0');
            $formdata['PEN'] =($formdata['PEN']??'0');
            
            Common::getlables("1144,1145,1105,1181,893,1240", "", "", self::$connObj);


            // GET LOAN ON DUES
            // check see if file name has ben passes
            // TOTAL PAID
            self::$payments['totalpay'] = $formdata['AMOUNT'];

            if (self::$tempfilename == ''):
                Common::prepareParameters($parameters, 'branch_code', '');
                Common::prepareParameters($parameters, 'product_prodid', $formdata['LPRODID']);
                Common::prepareParameters($parameters, 'client_type', '');
                Common::prepareParameters($parameters, 'asatdate', $formdata['DATE']);
                Common::prepareParameters($parameters, 'loan_number_fr', $formdata['LNR']);
                Common::prepareParameters($parameters, 'loan_number_to', $formdata['LNR']);
                Common::prepareParameters($parameters, 'code', 'INDREPAYLOANS');

                $loan_dues_array = Common::common_sp_call(serialize($parameters), '', self::$connObj);

                self::$tempfilename = 'loan_dues_array_' . Common::replace_string($formdata['LNR']) . '.txt';

                SerializeUnserialize::getInstance()->put_serialized_data(self::$tempfilename, $loan_dues_array);

            else:

                $loan_dues_array = SerializeUnserialize::getInstance()->get_unserialized_data(self::$tempfilename);

            endif;

            if ($loan_dues_array[0]['client_idno'] != ''):
                self::$clientid = $loan_dues_array[0]['client_idno'];
            endif;

            if ($formdata['MEMID'] != ''):
                self::$clientid = $formdata['MEMID'];
            endif;

            self::$outstanding[self::$clientid]['tout'] = ($loan_dues_array[0]['outbal'] + $loan_dues_array[0]['outint'] + $loan_dues_array[0]['outcomm'] + $loan_dues_array[0]['outpen'] + $loan_dues_array[0]['outvat']);

            $overpay = Common::get_array_elements_with_key_in_3D_array(self::$loanproductsettings, 'ALLOW_OVERPAYMENTS');

            if ($formdata['AMOUNT'] > self::$outstanding[self::$clientid]['tout'] && $overpay['ALLOW_OVERPAYMENTS'] == '0'):
                Common::$lablearray['E01'] = Common::$lablearray['1690'] . ' ' . Common::number_format_locale_display(bcsub($formdata['AMOUNT'], self::$outstanding[self::$clientid]['tout'], SETTING_ROUNDING)) . " - " . $formdata['LNR'];
                return;
            endif;

            // AMOUNTS OUTSTANING

            self::$outstanding[self::$clientid]['oprinc'] = Common::searchForId($loan_dues_array, preg_match('[G]', self::$clientid)?'members_idno':'client_idno', self::$clientid, 'outbal'); //$loan_dues_array['outbal'] ;
            self::$outstanding[self::$clientid]['oint'] = Common::searchForId($loan_dues_array, preg_match('[G]', self::$clientid)?'members_idno':'client_idno', self::$clientid, 'outint'); //$loan_dues_array['outint'] ;
            self::$outstanding[self::$clientid]['open'] = Common::searchForId($loan_dues_array, preg_match('[G]', self::$clientid)?'members_idno':'client_idno', self::$clientid, 'outpen'); //$loan_dues_array['outpen'];
            self::$outstanding[self::$clientid]['ocomm'] = Common::searchForId($loan_dues_array, preg_match('[G]', self::$clientid)?'members_idno':'client_idno', self::$clientid, 'outcomm'); //$loan_dues_array['outvat'] ;
            self::$outstanding[self::$clientid]['ovat'] = Common::searchForId($loan_dues_array, preg_match('[G]', self::$clientid)?'members_idno':'client_idno', self::$clientid, 'outvat'); //$loan_dues_array['outvat'] ;

            // self::$outstanding[self::$clientid]['oprinc'] = Common::searchForId($loan_dues_array, 'members_idno', self::$clientid, 'outbal'); //$loan_dues_array['outbal'] ;
            // self::$outstanding[self::$clientid]['oint'] = Common::searchForId($loan_dues_array, 'members_idno', self::$clientid, 'outint'); //$loan_dues_array['outint'] ;
            // self::$outstanding[self::$clientid]['ocomm'] = Common::searchForId($loan_dues_array, 'members_idno', self::$clientid, 'outcomm'); //$loan_dues_array['outcomm'] ;
            // self::$outstanding[self::$clientid]['open'] = Common::searchForId($loan_dues_array, 'members_idno', self::$clientid, 'outpen'); //$loan_dues_array['outpen'];
            // self::$outstanding[self::$clientid]['ovat'] = Common::searchForId($loan_dues_array, 'members_idno', self::$clientid, 'outvat'); //$loan_dues_array['outvat'] ;
           
            // AMOUNTS PAID FROM  THE FORM
            self::$actualpayments['nprinc'] = $formdata['PRI'];
            self::$actualpayments['nint'] = $formdata['INT'];
            self::$actualpayments['ncomm'] = $formdata['COM'];
            self::$actualpayments['npen'] = $formdata['PEN'];
            self::$actualpayments['nvat'] = $formdata['VAT'];

            // INITIALISE TRANSACTIONS 
            $aLines['PRI'] = array('AMOUNT' => 0, 'TTYPE' => 'PRI', 'PRODUCT_PRODID' => $formdata['LPRODID'], 'GLACC' => '', 'TRANCODE' => 'LD000', 'SIDE' => 'CR', 'DESC' => Common::$lablearray["1144"] . ' ' . $formdata['LNR']);
            $aLines['INT'] = array('AMOUNT' => 0, 'TTYPE' => 'INT', 'PRODUCT_PRODID' => $formdata['LPRODID'], 'GLACC' => '', 'TRANCODE' => 'IL001', 'SIDE' => 'CR', 'DESC' => Common::$lablearray["1145"] . ' ' . $formdata['LNR']);
            $aLines['COM'] = array('AMOUNT' => 0, 'TTYPE' => 'COM', 'PRODUCT_PRODID' => $formdata['LPRODID'], 'GLACC' => '', 'TRANCODE' => 'LN000', 'SIDE' => 'CR', 'DESC' => Common::$lablearray["1105"] . ' ' . $formdata['LNR']);
            $aLines['PEN'] = array('AMOUNT' => 0, 'TTYPE' => 'PEN', 'PRODUCT_PRODID' => $formdata['LPRODID'], 'GLACC' => '', 'TRANCODE' => 'LN002', 'SIDE' => 'CR', 'DESC' => Common::$lablearray["1181"] . ' ' . $formdata['LNR']);
            $aLines['VAT'] = array('AMOUNT' => 0, 'TTYPE' => 'VAT', 'PRODUCT_PRODID' => $formdata['LPRODID'], 'GLACC' => '', 'TRANCODE' => 'VO000', 'SIDE' => 'CR', 'DESC' => Common::$lablearray["893"] . ' ' . $formdata['LNR']);
            $aLines['OVR'] = array('AMOUNT' => 0, 'TTYPE' => 'OVR', 'PRODUCT_PRODID' => $formdata['LPRODID'], 'GLACC' => '', 'TRANCODE' => 'OV000', 'SIDE' => 'CR', 'DESC' => Common::$lablearray["1240"] . ' ' . $formdata['LNR']);

            self::distributePayments($aLines);

            // UPDATE AMOUNT AFTER DISTRIBUTION
            $formdata['PRI'] = $aLines['PRI']['AMOUNT'];
            $formdata['INT'] = $aLines['INT']['AMOUNT'];
            $formdata['COM'] = $aLines['COM']['AMOUNT'];
            $formdata['PEN'] = $aLines['PEN']['AMOUNT'];
            $formdata['OVR'] = $aLines['OVR']['AMOUNT'];



            // CHECK SEE IF WE HAVE AN OVERPAYMENT
            if ((self::$overpayments[0]['Total']??0) > 0):
                $aLines['OVR']['AMOUNT'] = self::$overpayments[0]['Total'];
            endif;

            //  $nCount = 0;
//            while ($Totalpay > 0):
//
//                $Totalpayactual = $nPrinc + $nInt + $nComm + $nPen + $nvat;
//
//                if ($Totalpayactual == 0):
//                    break;
//                endif;
//
//                switch (self::$paymentpriority) {
//
//                    case 'PRINC-INT-COM-PEN':
//                        goto principal;
//                        goto interest;
//                        goto commission;
//                        goto penalty;
//                        goto vat;
//
//                        break;
//
//                    case 'PRINC-COM-PEN-INT':
//
//                        goto principal;
//                        goto commission;
//                        goto penalty;
//                        goto interest;
//                        goto vat;
//                        break;
//
//                    case 'INT-PRINC-COM-PEN':
//
//                        goto interest;
//                        goto principal;
//                        goto commission;
//                        goto penalty;
//                        goto vat;
//
//                        break;
//
//                    case 'INT-COM-PRINC-PEN':
//
//                        goto interest;
//                        goto commission;
//                        goto principal;
//                        goto penalty;
//                        goto vat;
//
//                        break;
//
//                    case 'INT-COM-PEN-PRINC':
//
//                        goto interest;
//                        goto commission;
//                        goto penalty;
//                        goto principal;
//                        goto vat;
//
//                        break;
//
//                    case 'INT-PEN-COM-PRINC':
//
//                        goto interest;
//                        goto penalty;
//                        goto commission;
//                        goto principal;
//                        goto vat;
//
//                        break;
//
//                    case 'INT-PEN-PRINC-COM':
//
//                        goto interest;
//                        goto penalty;
//                        goto principal;
//                        goto commission;
//                        goto vat;
//
//                        break;
//
//                    case 'PRINC-INT-PRINC':  // HBPP PREPARMENTS CAN ONLY BE MADE ON PRINCIPAL
//                        if ($nCount == 0):
//                            goto principal;
//                            goto interest;
//                        else:
//                            goto principal;
//                            self::$pulldatescloser = 1;
//                        endif;
//
//                        break;
//
//                    default:
//                        if ($nCount == 0):
//                            goto principal;
//                            goto interest;
//                        else:
//                            goto principal;
//                            self::$pulldatescloser = 1;
//                        endif;
//                        break;
//                }
//
//                $nCount++;
//
//            endwhile;
            // PRINCIPAL
//            principal: {
//
//                if ($Totalpay > 0) {
//                    if ($Totalpay >= $nPrinc) {
//                        $aLines['PRI']['AMOUNT'] = bcadd($aLines['PRI']['AMOUNT'], $nPrinc, SETTING_ROUNDING);
//                        $Totalpay = bcsub($Totalpay, $nPrinc, SETTING_ROUNDING);
//                    } else {
//                        $aLines['PRI']['AMOUNT'] = bcadd($aLines['PRI']['AMOUNT'], $Totalpay, SETTING_ROUNDING);
//                        $Totalpay = 0;
//                    }
//
//                    if ($formdata['PRI'] > $oPrinc):
//                        $overpayment = bcadd($overpayment, bcsub($formdata['PRI'], $oPrinc, SETTING_ROUNDING), SETTING_ROUNDING);
//                    endif;
//                }
//            }
            // INTEREST 
//            interest: {
//                if ($Totalpay > 0) {
//
//                    if ($Totalpay >= $nInt) {
//                        $aLines['INT']['AMOUNT'] = bcadd($aLines['INT']['AMOUNT'], $nInt, SETTING_ROUNDING);
//                        $Totalpay = bcsub($Totalpay, $nInt, SETTING_ROUNDING);
//                    } else {
//                        $aLines['INT']['AMOUNT'] = bcadd($aLines['INT']['AMOUNT'], $Totalpay, SETTING_ROUNDING);
//                        $Totalpay = 0;
//                    }
//                    if ($formdata['INT'] > $oInt):
//                        $overpayment = bcadd($overpayment, bcsub($formdata['INT'], $oInt, SETTING_ROUNDING), SETTING_ROUNDING);
//                    endif;
//                }
//            }
            // COMMISISON
//            commission: {
//                if ($Totalpay > 0) {
//
//                    if ($Totalpay >= $nComm) {
//                        $aLines['COM']['AMOUNT'] = bcadd($aLines['COM']['AMOUNT'], $nComm, SETTING_ROUNDING);
//                        $Totalpay = bcsub($Totalpay, $nComm, SETTING_ROUNDING);
//                    } else {
//                        $aLines['COM']['AMOUNT'] = bcadd($aLines['COM']['AMOUNT'], $Totalpay, SETTING_ROUNDING);
//                        $Totalpay = 0;
//                    }
//
//                    if ($formdata['COM'] > $oComm):
//                        $overpayment = bcadd($overpayment, bcsub($formdata['COM'], $oComm, SETTING_ROUNDING), SETTING_ROUNDING);
//                    endif;
//                }
//            }
            // PENALTY
//            penalty: {
//                if ($Totalpay > 0) {
//
//                    if ($Totalpay >= $nPen) {
//                        $aLines['PEN']['AMOUNT'] = bcadd($aLines['PEN']['AMOUNT'], $nPen, SETTING_ROUNDING);
//                        $Totalpay = bcsub($Totalpay, $nPen, SETTING_ROUNDING);
//                    } else {
//                        $aLines['PEN']['AMOUNT'] = bcadd($aLines['PEN']['AMOUNT'], $Totalpay, SETTING_ROUNDING);
//                        $Totalpay = 0;
//                    }
//
//                    if ($formdata['PEN'] > $oPen):
//                        $overpayment = bcadd($overpayment, bcsub($formdata['PEN'], $oPen, SETTING_ROUNDING), SETTING_ROUNDING);
//                    endif;
//                }
//            }
            // TO DO: VAT
//            vat: {
//                if ($Totalpay > 0) {
//
//                    if ($Totalpay >= $nvat) {
//                        $aLines['VAT']['AMOUNT'] = bcadd($aLines['VAT']['AMOUNT'], $nvat, SETTING_ROUNDING);
//                        $Totalpay = bcsub($Totalpay, $nvat, SETTING_ROUNDING);
//                        $nvat = 0;
//                    } else {
//                        $aLines['VAT']['AMOUNT'] = bcadd($aLines['VAT']['AMOUNT'], $Totalpay, SETTING_ROUNDING);
//                        $Totalpay = 0;
//                    }
//                }
//            }
            // CHECK SEE IF WE HAVE AN OVERPAYMENT
//            if ($overpayment > 0):
//                $aLines['OVR']['AMOUNT'] = $overpayment;
//            endif;
//
//            // remove ements with 0 amounts
//            if ($aLines['PRI']['AMOUNT'] == '0')
//                unset($aLines['PRI']);
//            if ($aLines['COM']['AMOUNT'] == '0')
//                unset($aLines['COM']);
//            if ($aLines['INT']['AMOUNT'] == '0')
//                unset($aLines['INT']);
//            if ($aLines['PEN']['AMOUNT'] == '0')
//                unset($aLines['PEN']);
//            if ($aLines['VAT']['AMOUNT'] == '0')
//                unset($aLines['VAT']);
//            if ($aLines['OVR']['AMOUNT'] == '0')
//                unset($aLines['OVR']);
        return $formdata;
        } catch (Exception $e) {
            Common::$lablearray['E01'] = $e->getMessage();
        }
    }

    /**
     * addeditLoan
     * 
     * This function is used to update loan details
     * @param array $formdata: Data from the form    
     */
    public static function addeditLoan(&$form_data) {

        try {

            foreach ($form_data as $key => &$value) {

                switch ($value['ACTION']):
                    case 'add':
                        // LOAN LOAN
                        $value['TABLE'] = TABLE_LOAN;
                        Bussiness::covertArrayToXML(array($value), false);

                        // ADD DUES
                        $loan_dues = $value['DUES'];
                        if (!is_null($loan_dues)):
                            Bussiness::covertArrayToXML($loan_dues, false);
                        endif;

                        // ADD GROUP LOAN DETAILS                    
                        $mem_loans = $value['MEMLOANS']??array();
                        if (count($mem_loans)>0):
                            Bussiness::covertArrayToXML($mem_loans, false);
                        endif;

                        // ADD LOAN STATUS
                        $value['TABLE'] = TABLE_LOANSTATUSLOG;
                        $value['AMOUNT'] = $value['LAMNT'];
                        $value['LSTATUS'] = 'PA';
                        Bussiness::covertArrayToXML(array($value), true);
                        break;

                    default:
                        break;

                endswitch;
            }


            // save 
            Bussiness::PrepareData(true);
        } catch (Exception $e) {
            Bussiness::$Conn->cancelTransaction();
            Common::$lablearray['E01'] = $e->getMessage();
        }
    }

    /**
     * updateLoan
     * 
     * This function is used to update loan details
     * @param array $formdata: Data from the form
     * @param string $action: Action to be executed
     * Returns Error message if any
     */
    public static function updateLoan(&$form_data, $action) {

        $aLines = array();

        try {

            Bussiness::$isBulkInsert = self::$isBulkInsert;

            Bussiness::$Conn->AutoCommit = false;
            Bussiness::$Conn->beginTransaction();
            $nCount = count($form_data);
            $nCnt = 0;

            $nTotal = 0;

            foreach ($form_data as $key => &$value) {
                $value['BANKBRANCHID'] =($value['BANKBRANCHID']??'');
                $value['BANKID'] =($value['BANKID']??'');


                if ($value['MODE'] == 'CQ'):
                  //  if (count($banks_array) > 0):
                        $banks_array = Common::$connObj->SQLSelect('SELECT bankaccounts_id, bankaccounts_accno,chartofaccounts_accountcode,bankbranches_id  FROM bankaccounts');
                  //  endif;
                endif;

                if ($value['LSTATUS'] == 'AP' || $value['LSTATUS'] == 'LD'): // CHECK IF WE SHOULD APPROVE LOAN

                    $nCnt++;

                    $value['TABLE'] = TABLE_LOANSTATUSLOG;

                    if ($nCnt == $nCount):
                        Bussiness::covertArrayToXML(array($value), true);
                    else:
                        Bussiness::covertArrayToXML(array($value), false);
                        continue;
                    endif;

                endif;

                if ($value['MODE'] == 'CQ' || $value['MODE'] == 'DB') {

                    $data_acc = Common::searchArray($banks_array, 'bankaccounts_accno', $value['bankbranches_id']);
                    Common::replace_key_function($value, 'bankbranches_id', 'BACCNO');
                    Common::addKeyValueToArray($value, 'CQSTAT', 'Q');
                    Common::replace_key_function($value, 'cheques_no', 'CHEQNO');
                    Common::addKeyValueToArray($value, 'BANKGL', $data_acc['chartofaccounts_accountcode']);
                    Common::addKeyValueToArray($value, 'BID', $data_acc['bankbranches_id']);
                }

                $value['USERID'] = $_SESSION['user_id'];

                /// check see if transaction type is set else set default
                $value['TTYPE'] = (isset($value['TTYPE']) ? $value['TTYPE'] : '' );

                if ($value['MODE'] == 'SA'):
                    Savings::setProps($value['SAVACC'], $value['SPRODID'], $value['MEMID'], $value['DATE']);
                    $Sav_bal_array = Savings::getSavingsBalance();
                endif;

                switch ($action) {
                    case 'RS': // Reschedule 

                        $loan_dues = $value['DUES'];

                        $value['TABLE'] = TABLE_DUES;

                        if (count($loan_dues) > 0):
                            Bussiness::prepareBulkStatement($loan_dues, true);
                        endif;

                        break;

                    case 'LC': // Loan Commission
                        Common::getlables("1105,1213,1203,311", "", "", self::$connObj);

                        $value['TABLE'] = TABLE_LOANFEES;

                        Bussiness::covertArrayToXML(array($value), false);

                        $aLines[] = array('AMOUNT' => $value['AMOUNT'], 'TTYPE' => 'COM', 'PRODUCT_PRODID' => $value['PRODUCT_PRODID'], 'GLACC' => '', 'TRANCODE' => 'LN000', 'SIDE' => 'CR', 'DESC' => Common::$lablearray["1105"] . ' ' . $value['LNR']);

                        if ($value['MODE'] == 'CQ') {
                            Common::replace_key_function($value, 'cheques_no', 'CHEQNO');
                        }

                        switch ($value['MODE']) {
                            case 'SA': // Savings                                

                                $value['TABLE'] = TABLE_SAVTRANSACTIONS;

                                $value['AMOUNT'] = -1 * abs($value['AMOUNT']);

                                $value['PRODUCT_PRODID'] = $value['SPRODID'];

                                // UPDATE SAVINGS
                                Bussiness::covertArrayToXML(array($value), false);

                                $aLines[] = array('AMOUNT' => abs($value['AMOUNT']), 'TTYPE' => 'SA', 'PRODUCT_PRODID' => $value['SPRODID'], 'GLACC' => '', 'TRANCODE' => 'SD000', 'SIDE' => 'DR', 'DESC' => Common::$lablearray["1213"] . ' ' . $value['SAVACC']);

                                break;

                            case 'CQ': // Cheque
                            case 'DB': // Direct To Bank   

                                if ($value['MODE'] == 'DB'):
                                    Common::addKeyValueToArray($value, 'cheques_no', 'CHEQNO');
                                    $value['CHEQNO'] = 'DB-' . $value['TCODE'];
                                endif;

                                $value['TABLE'] = TABLE_CHEQS;

                                // UPDATE CHEQUES
                                Bussiness::covertArrayToXML(array($value), false);

                                $aLines[] = array('AMOUNT' => $value['AMOUNT'], 'TTYPE' => 'SP', 'PRODUCT_PRODID' => $value['LPRODID'], 'GLACC' => $value['BANKGL'], 'BID' => $value['BID'], 'TRANCODE' => 'CC002', 'SIDE' => 'DR', 'DESC' => Common::$lablearray["1203"]); // Post Cheque on Suspence

                                break;

                            case 'CA': // Ccash                        
                                $aLines[] = array('AMOUNT' => $value['AMOUNT'], 'TTYPE' => 'CA', 'PRODUCT_PRODID' => $value['LPRODID'], 'GLACC' => $value['GLACC'], 'TRANCODE' => 'CB000', 'SIDE' => 'DR', 'DESC' => Common::$lablearray["311"]);
                                break;
                        }

                        break;

                    case 'WO': // Write Off Loan
                        $selectedloans = common::get_array_elements_with_key($value, 'grid_checkbox__');
                        if (count($selectedloans) == 0):
                            $selectedloans = common::get_array_elements_with_key($value, 'grid_checkbox_');
                        endif;
                        foreach ($selectedloans as $key => $loan_number) {
                            // get loans details
                            $lnr_array[] = $loan_number;
                        }


                        // get loans details
                        $slnr = implode(",", $lnr_array);

                        $parameters = array();
                        Common::prepareParameters($parameters, 'loan_numbers', $slnr);
                        Common::prepareParameters($parameters, 'asatdate', Common::changeDateFromPageToMySQLFormat($value['startDate']));
                        Common::prepareParameters($parameters, 'code', 'LOANWRITEOFF');

                        $loan_results = Common::common_sp_call(serialize($parameters), '', Common::$connObj, true);

                        return $loan_results;

                        break;

                    case 'RF': // Refinace Loan
                    case 'RFLD':

                        // rectreive selected loans
                        $selectedloans = common::get_array_elements_with_key($value, 'grid_checkbox__');

                        if (count($selectedloans) == 0):
                            $selectedloans = common::get_array_elements_with_key($value, 'grid_checkbox');
                        endif;

                        foreach ($selectedloans as $key => $loan_number) {
                            // get loans details
                            $lnr_array[] = $loan_number;
                        }

                        reset($selectedloans);

                        // get loans details
                        $slnr = implode(",", $lnr_array);

                        $parameters = array();
                        Common::prepareParameters($parameters, 'branch_code', '');
                        Common::prepareParameters($parameters, 'loan_numbers', $slnr);
                        Common::prepareParameters($parameters, 'asatdate', Common::changeDateFromPageToMySQLFormat($value['startDate']));
                        Common::prepareParameters($parameters, 'branch_code', '');
                        Common::prepareParameters($parameters, 'code', 'LOANDUESSUM');
                        Common::prepareParameters($parameters, 'limit', '');
                        Common::prepareParameters($parameters, 'product_prodid', '');

                        $loan_details = Common::common_sp_call(serialize($parameters), '', Common::$connObj, false);

                        // evaluate ,consolidate amount and create schedules from new amounts
                        foreach ($loan_details as $key => $loan) {
                            $lamount = 0;
                            $outprinc = $loan['outprinc'];
                            $outint = $loan['outint'];
                            $outpen = 0;
                            $outcomm = 0;
                            $outvat = 0;

                            //  if ($value['IGNOREOUT'] == 'Y') {

                            switch ($loan['ref_priority']) { // check refinancing priority
                                case 'INT_COMM_PEN_VAT':
                                    $lamount = $loan['outprinc'];
                                    break;

                                case 'COMM_PEN_VAT':
                                    $lamount = $loan['outprinc'] + $loan['outint'];
                                    $intout = $loan['outint'];
                                    break;

                                case 'PEN_VAT':
                                    $lamount = $loan['outprinc'] + $loan['outint'] + $loan['outcomm'];
                                    $outint = $loan['outint'];
                                    $outcomm = $loan['outcomm'];
                                    break;

                                case 'PEN':
                                    $lamount = $loan['outprinc'] + $loan['outpen'];
                                    $outpen = $loan['outpen'];
                                    break;

                                case 'INT':
                                    $lamount = $loan['outprinc'] + $loan['outint'];
                                    $outint = $loan['outint'];
                                    break;

                                case 'COMM':
                                    $lamount = $loan['outprinc'] + $loan['outcomm'];

                                    $outcomm = $loan['outcomm'];
                                    break;

                                case 'PEN':
                                    $lamount = $loan['outprinc'] + $loan['outpen'];
                                    $outpen = $loan['outpen'];
                                    break;

                                case 'VAT':
                                    $lamount = $loan['outprinc'] + $loan['outvat'];
                                    $outvat = $loan['outvat'];
                                    break;

                                default:
                                    $lamount = $loan['outprinc'];
                                    break;
                            }
                            // }


                            if ($value['COMPDUESIGNOREFUTURE'] == 'Y') {


                                switch ($loan['ref_priority']) { // check refinancing priority
                                    case 'INT_COMM_PEN_VAT':
                                        $lamount = $loan['outprinc'] + $loan['dueint'] + $loan['arint'] + $loan['duecomm'] + $loan['arcomm'] + $loan['duepen'] + $loan['arpen'] + $loan['duevat'] + $loan['arvat'];
                                        $outint = $loan['dueint'] + $loan['arint'];
                                        $outcomm = $loan['duecomm'] + $loan['arcomm'];
                                        $outpen = $loan['duepen'] + $loan['arpen'];
                                        $outvat = $loan['duevat'] + $loan['arvat'];
                                        break;

                                    case 'COMM_PEN_VAT':
                                        $lamount = $loan['outprinc'] + $loan['duecomm'] + $loan['arcomm'] + $loan['duepen'] + $loan['arpen'] + $loan['duevat'] + $loan['arvat'];

                                        $outcomm = $loan['duecomm'] + $loan['arcomm'];
                                        $outpen = $loan['duepen'] + $loan['arpen'];
                                        $outvat = $loan['duevat'] + $loan['arvat'];
                                        break;

                                    case 'PEN_VAT':
                                        $lamount = $loan['outprinc'] + $loan['duepen'] + $loan['arpen'] + $loan['duevat'] + $loan['arvat'];
                                        $outpen = $loan['duepen'] + $loan['arpen'];
                                        $outvat = $loan['duevat'] + $loan['arvat'];
                                        break;

                                    case 'PEN':
                                        $lamount = $loan['outprinc'] + $loan['duepen'] + $loan['arpen'];
                                        $outpen = $loan['duepen'] + $loan['arpen'];

                                        break;

                                    case 'INT':
                                        $lamount = $loan['outprinc'] + $loan['dueint'] + $loan['arint'];
                                        $outint = $loan['dueint'] + $loan['arint'];
                                        break;

                                    case 'COMM':
                                        $lamount = $loan['outprinc'] + $loan['duecomm'] + $loan['arcomm'];
                                        $outcomm = $loan['duecomm'] + $loan['arcomm'];
                                        break;



                                    case 'VAT':
                                        $lamount = $loan['outprinc'] + $loan['duevat'] + $loan['arvat'];
                                        $outvat = $loan['duevat'] + $loan['arvat'];
                                        break;

                                    default:

                                        break;
                                }
                            }

                            // what is avaiable less what is outstanding- for dibursement
                            $topupperloan = $value['TOPUP'];

                            $lamount += $value['TOPUP'];


                            // KEEP ORIGINAL DATE
                            $value['ODATE'] = $value['startDate'];
                            self::$incomingvars['startDate'] = $value['startDate'];
                            self::$incomingvars['lamount'] = $lamount;
                            self::$incomingvars['INTTYPE'] = $loan['loan_inttype'];
                            self::$incomingvars['INSTYPE'] = $loan['loan_insttype'];
                            self::$incomingvars['intrate'] = $loan['loan_tint'];
                            self::$incomingvars['grace'] = $loan['loan_grace'];
                            self::$incomingvars['gracecompint'] = $loan['loan_intcgrace'];

                            if ($value['txtloan_noofinst'] > 0) {
                                self::$incomingvars['no_of_inst'] = $value['txtloan_noofinst'];
                            } else {
                                self::$incomingvars['no_of_inst'] = $value['NINST'];
                            }


                            self::$incomingvars['allintpaidfirstinstallment'] = $loan['loan_intfirst'];
                            self::$incomingvars['intgrace'] = $loan['loan_insintgrac'];
                            self::$incomingvars['alsograce'] = $loan['loan_alsograce'];
                            self::$incomingvars['intpaidatdisbursement'] = $loan['loan_inupfront'];
                            self::$incomingvars['productcode'] = $loan['product_prodid'];

                            $newLoan = new Loan(array(), '');

                            $loan_dues = $newLoan::updateInstallmentSchedule();


                            $ctype = Common::getClientType($loan['client_idno']);

                            Common::addKeyValueToArray($value, 'CTYPE', $ctype);
                            Common::addKeyValueToArray($value, 'FUNDCODE', $loan['fcode']);
                            Common::addKeyValueToArray($value, 'DONORCODE', $loan['dcode']);
                            Common::addKeyValueToArray($value, 'LAMNT', $loan['loan_amount']);
                            Common::addKeyValueToArray($value, 'CLIENTIDNO', $loan['client_idno']);
                            Common::addKeyValueToArray($value, 'LPRODID', $loan['product_prodid']);
                            Common::addKeyValueToArray($value, 'DTYPE', 'RF');
                            Common::addKeyValueToArray($value, 'BRANCHCODE', Common::extractBranchCode($loan['client_idno']));


                            Common::replace_key_function($value, 'startDate', 'DATE');
                            Common::replace_key_function($value, 'txtvoucher', 'VOUCHER');
                            Common::replace_key_function($value, 'LOANPROD', 'PRODUCT_PRODID');
                            Common::replace_key_function($value, 'PAYMODES', 'MODE');

                            if ($value['MODE'] == 'CA') { // By cheque
                                Common::replace_key_function($value, 'cashaccounts_code', 'CASHGL');
                            }

                            if ($value['MODE'] == 'CQ') { // By cheque                         
                                $data_acc = Common::searchArray($banks_array, 'bankaccounts_accno', $value['bankbranches_id']);
                                Common::replace_key_function($value, 'cheques_no', 'CHEQNO');
                            }

                            Common::replace_key_function($value, 'txtloan_noofinst', 'NOINS');


                            Common::addKeyValueToArray($value, 'LSTATUS', 'LD');
                            Common::addKeyValueToArray($value, 'TCODE', Common::generateTransactionCode($_SESSION['user_id']));
                            Common::addKeyValueToArray($value, 'LNR', $loan['loan_number']);
                            Common::addKeyValueToArray($value, 'PRI', $outprinc);
                            Common::addKeyValueToArray($value, 'INT', $outint);
                            Common::addKeyValueToArray($value, 'PEN', $outpen);
                            Common::addKeyValueToArray($value, 'COM', $outcomm);
                            Common::addKeyValueToArray($value, 'VAT', $outvat);

                            Common::addKeyValueToArray($value, 'MEMID', $loan['members_idno']);

                            Common::addKeyValueToArray($value, 'LOANDUES', $loan_dues);
                            $value['AMOUNT'] = $lamount;
                            $value['TOPUP'] = $topupperloan;


                            Common::addKeyValueToArray($value, 'CYCLE', 0);
                            Common::addKeyValueToArray($value, 'STAT', 0);

                            $value['DATE'] = Common::changeDateFromPageToMySQLFormat($value['DATE']);

                            $_formdata = array($value);

                            Common::prepareTransForXML($_formdata, $action);
                        }

                        break;

                    case 'DP': // Distriute Payment only
                        $formdata  = array();
                        $formdata = self::distributeLoanPayment($aLines, $value); 
                        return  $formdata;

                        break;

                    case 'LR': // Loan Repayment

                        Common::getlables("1690,311", "", "", self::$connObj);

                        self::distributeLoanPayment($aLines, $value);


                        if ($aLines['PRI']['AMOUNT'] > 0)
                            Common::addKeyValueToArray($value, 'PRI', $aLines['PRI']['AMOUNT']);
                        if ($aLines['INT']['AMOUNT'] > 0)
                            Common::addKeyValueToArray($value, 'INT', $aLines['INT']['AMOUNT']);
                        if ($aLines['COM']['AMOUNT'] > 0)
                            Common::addKeyValueToArray($value, 'COM', $aLines['COM']['AMOUNT']);
                        if ($aLines['PEN']['AMOUNT'] > 0)
                            Common::addKeyValueToArray($value, 'PEN', $aLines['PEN']['AMOUNT']);
                        if ($aLines['VAT']['AMOUNT'] > 0)
                            Common::addKeyValueToArray($value, 'VAT', $aLines['VAT']['AMOUNT']);
                        if ($aLines['OVR']['AMOUNT'] > 0)
                            Common::addKeyValueToArray($value, 'OVR', $aLines['OVR']['AMOUNT']);

                        // REMOVE THESE ELEMENTS COZ WE ARE GOING TO ADD TRANSACTIONS
                        unset($aLines['PRI']);
                        unset($aLines['INT']);
                        unset($aLines['COM']);
                        unset($aLines['PEN']);
                        unset($aLines['VAT']);
                        unset($aLines['OVR']);

                        Common::getlables("1025,1201,1507,1027,1144,1145,1105,1181,1404,1240", "", "", Common::$connObj);

                        $value['TABLE'] = TABLE_LOANPAYMENTS;
                        Common::addKeyValueToArray($value, 'VAT', ($aLines['VAT']['AMOUNT']??0));
                        Common::addKeyValueToArray($value, 'PULLDATES', self::$pulldatescloser);

                        Bussiness::covertArrayToXML(array($value), false);

                        $amount = $value['AMOUNT'];
                        $tcode = $value['TCODE'];

                        // check service fee
                        if ($value['SFEE'] > 0):
                            //TODO: CHECK SAVINGS BALANCES                    
                            $value['TABLE'] = TABLE_SAVTRANSACTIONS;
                            $value['TTYPE'] = 'SA';
                            $value['AMOUNT'] = -1 * ($value['SFEE']);
                            $value['PRODUCT_PRODID'] = $value['SPRODID'];

                            $value['TCODE'] = Common::generateTransactionCode($_SESSION['user_id']);

                            Bussiness::covertArrayToXML(array($value), false);

                            $aLines[] = array('TTYPE' => 'SFEE', 'AMOUNT' => $value['SFEE'], 'PRODUCT_PRODID' => $value['LPRODID'], 'GLACC' => '', 'TRANCODE' => 'SF000', 'DESC' => Common::$lablearray['1507'] . ' ' . $value['LNR'] . ' ' . $value['MEMID'], 'SIDE' => 'CR');
                            $aLines[] = array('TTYPE' => 'SW', 'AMOUNT' => $value['SFEE'], 'PRODUCT_PRODID' => $value['SPRODID'], 'GLACC' => '', 'TRANCODE' => 'SW000', 'DESC' => Common::$lablearray['1201'] . ' ' . $value['LNR'] . ' ' . $value['MEMID'], 'SIDE' => 'DR');
                            $value['AMOUNT'] = $amount;
                            $value['TCODE'] = $tcode;

                        endif;

                        // check mandatory savings amounts
                        if ($value['SAVAMT'] > 0):
                            $value['TABLE'] = TABLE_SAVTRANSACTIONS;
                            $value['PRODUCT_PRODID'] = $value['MPRODID'];
                            $value['AMOUNT'] = $value['SAVAMT'];
                            $savacc = $value['SAVACC'];
                            $value['SAVACC'] = $value['MSAVACC'];
                            $value['TTYPE'] = 'SD';
                            Bussiness::covertArrayToXML(array($value), false);

                            $accounts_array [] = $value['MSAVACC'];
                            $products_array[] = $value['MPRODID'];

                            $aLines[] = array('TTYPE' => 'SD', 'AMOUNT' => $value['SAVAMT'], 'PRODUCT_PRODID' => $value['MPRODID'], 'GLACC' => '', 'TRANCODE' => 'SD000', 'DESC' => Common::$lablearray['1027'] . ' ' . $value['MSAVACC'] . ' ' . $value['MEMID'], 'SIDE' => 'CR');

                            $value['SAVACC'] = $savacc;
                        endif;

                        $value['AMOUNT'] = $amount;

                        // Principal
                        if ($value['PRI'] > 0) {
                            $nTotal = $nTotal + $value['PRI'];
                            $aLines[] = array('TTYPE' => 'PRI', 'AMOUNT' => $value['PRI'], 'PRODUCT_PRODID' => $value['LPRODID'], 'GLACC' => '', 'TRANCODE' => 'LN002', 'BANKID' => $value['BANKBRANCHID'], 'DESC' => Common::$lablearray['1144'] . ' ' . $value['LNR'] . ' ' . $value['MEMID'], 'SIDE' => 'CR');
                        }

                        // Interest
                        if ($value['INT'] > 0) {
                            $nTotal = $nTotal + $value['INT'];
                            $aLines[] = array('TTYPE' => 'INT', 'AMOUNT' => $value['INT'], 'PRODUCT_PRODID' => $value['LPRODID'], 'GLACC' => '', 'TRANCODE' => 'IL001', 'BANKID' => $value['BANKBRANCHID'], 'DESC' => Common::$lablearray['1145'] . ' ' . $value['LNR'] . ' ' . $value['MEMID'], 'SIDE' => 'CR');
                        }

                        // Commission
                        if ($value['COM'] > 0) {
                            $nTotal = $nTotal + $value['COM'];
                            $aLines[] = array('TTYPE' => 'COM', 'AMOUNT' => $value['COM'], 'PRODUCT_PRODID' => $value['LPRODID'], 'GLACC' => '', 'TRANCODE' => 'LN000', 'BANKID' => $value['BANKBRANCHID'], 'DESC' => Common::$lablearray['1105'] . ' ' . $value['LNR'] . ' ' . $value['MEMID'], 'SIDE' => 'CR');
                        }

                        // Penalty
                        if ($value['PEN'] > 0) {
                            $nTotal = $nTotal + $value['PEN'];
                            $aLines[] = array('TTYPE' => 'PEN', 'AMOUNT' => $value['PEN'], 'PRODUCT_PRODID' => $value['LPRODID'], 'GLACC' => '', 'TRANCODE' => 'LP000', 'BANKID' => $value['BANKBRANCHID'], 'DESC' => Common::$lablearray['1181'] . ' ' . $value['LNR'] . ' ' . $value['MEMID'], 'SIDE' => 'CR');
                        }

                        // Vat
                        if ($value['VAT'] > 0) {
                            $nTotal = $nTotal + $value['VAT'];
                            $aLines[] = array('TTYPE' => 'VAT', 'AMOUNT' => $value['VAT'], 'PRODUCT_PRODID' => $value['LPRODID'], 'GLACC' => '', 'TRANCODE' => 'VO000', 'BANKID' => $value['BANKBRANCHID'], 'DESC' => Common::$lablearray['1404'] . ' ' . $value['LNR'], 'SIDE' => 'CR');
                        }

                        // Overpayment
                        if ($value['OVR'] > 0) {
                            $nTotal = $nTotal + $value['OVR'];
                            $aLines[] = array('TTYPE' => 'OVR', 'AMOUNT' => $value['OVR'], 'PRODUCT_PRODID' => $value['LPRODID'], 'GLACC' => '', 'TRANCODE' => 'OV000', 'SIDE' => 'CR', 'DESC' => Common::$lablearray["1240"] . ' ' . $value['LNR'], 'SIDE' => 'CR');
                            //
                        }

                        switch ($value['MODE']) {
                            case 'SA': // Savings

                                $value['AMOUNT'] = -1 * abs($value['AMOUNT']);
                                $value['PRODUCT_PRODID'] = $value['SPRODID'];
                                $value['TTYPE'] = 'LR-SA';
                                $value['TABLE'] = TABLE_SAVTRANSACTIONS;


                                $accounts_array [] = $value['SAVACC'];
                                $products_array[] = $value['SPRODID'];

                                //   $accounts_array = Common::getSavingsAccountForProductNoNames(Common::$connObj, $value['CLIENTIDNO'], $value['SPRODID'], "S");
                                // chek savings balance
                                // get savings Balances

                                $value['SAVACC'] = (isset($value['SAVACC'])) ? $value['SAVACC'] : substr($value['cmbsavaccounts'], 0, strrpos($value['cmbsavaccounts'], ":"));

                                $bal_array = Savings::getSavingsBalance($value['SPRODID'], $value['SAVACC'], $value['DATE']);

                                // check if balances are sufficient
                                if ($Sav_bal_array['balance'] < abs($value['AMOUNT'])) {

                                    Common::getlables("1216", "", "", Common::$connObj);

                                    throw new Exception(Common::$lablearray['1216'] . ' ' . $accounts_array['savaccounts_account'] . ' ' . $bal_array['balance']);
                                }

                                //   Common::addKeyValueToArray($value, 'TABLE', TABLE_SAVTRANSACTIONS);
                                if (!isset($value['SPRODID'])) {
                                    Common::addKeyValueToArray($value, 'PRODUCT_PRODID', $value['SPRODID']);
                                }

                                // UPDATE SAVINGS
                                Bussiness::covertArrayToXML(array($value), false);

                                $aLines[] = array('AMOUNT' => abs($value['AMOUNT']), 'TTYPE' => 'LR-SA', 'PRODUCT_PRODID' => $value['SPRODID'], 'GLACC' => '', 'TRANCODE' => 'SD000', 'SIDE' => 'DR', 'DESC' => Common::$lablearray["1025"] . ' ' . $value['SAVACC']);

                                break;

                            case 'CQ': // Cheque
                            case 'DB': // Direct to Bank                          

                                if ($value['MODE'] == 'DB'):
                                    Common::addKeyValueToArray($value, 'cheques_no', 'CHEQNO');
                                    $value['CHEQNO'] = 'DB-' . $value['TCODE'];
                                endif;

                                $value['TABLE'] = TABLE_CHEQS;
                                $value['AMOUNT'] = $amount;
                                // UPDATE CHEQUES
                                Bussiness::covertArrayToXML(array($value), false);

                                $aLines[] = array('AMOUNT' => $value['AMOUNT'], 'TTYPE' => 'SP', 'PRODUCT_PRODID' => $value['LPRODID'], 'GLACC' => $value['BANKGL'], 'BID' => $value['BID'], 'TRANCODE' => 'CC002', 'SIDE' => 'DR', 'DESC' => Common::$lablearray["1203"]); // Post Cheque on Suspence

                                break;

                            case 'CA': // Ccash                        
                                $aLines[] = array('AMOUNT' => $value['AMOUNT'], 'TTYPE' => 'CA', 'PRODUCT_PRODID' => $value['LPRODID'], 'GLACC' => $value['GLACC'], 'TRANCODE' => 'CB000', 'SIDE' => 'DR', 'DESC' => Common::$lablearray["311"]);
                                break;
                        }


                        break;

                    case 'PA': // Loan Applications/Approval
                        // loan details
                        self::$incomingvars['startDate'] = $value['DATE'];

                        $value['DATE'] = Common::changeDateFromPageToMySQLFormat($value['DATE']);
                        $value['START'] = Common::changeDateFromPageToMySQLFormat($value['START']);

                        Bussiness::covertArrayToXML(array($value), false);

                        self::$incomingvars['grace'] = ($value['GRACE']??'0');
                        self::$incomingvars['INTTYPE'] = $value['INTTYPE'];
                        self::$incomingvars['INSTYPE'] = $value['INSTYPE'];
                        self::$incomingvars['lamount'] = ($value['LAMNT']??'0');
                        self::$incomingvars['intrate'] = ($value['INTRATE']??'0');
                        self::$incomingvars['gracecompint'] = ($value['GCOMP']??'0');
                        self::$incomingvars['txtintrate'] = ($value['INTRATE']??'0');

                        self::$incomingvars['no_of_inst'] = $value['NINST'];
                        self::$incomingvars['allintpaidfirstinstallment'] = ($value['FIRSTINS']??'');
                        self::$incomingvars['intgrace'] = $value['AGRACE'];
                        self::$incomingvars['intpaidatdisbursement'] = $value['INTDIS'];
                        self::$incomingvars['productcode'] = $value['PRODID'];
                        self::$incomingvars['insintgrace'] = $value['INSTING'];

                        self::$incomingvars['adjustDueDatesTo'] = $value['AJDDU'];
                        self::$incomingvars['adjusttononworkingday'] = $value['AJDW'];
                        self::$incomingvars['intCompounded'] = ($value['COMPINT']??'0');

                        // update loan status
                        $value['TABLE'] = TABLE_LOANSTATUSLOG;
                        Bussiness::covertArrayToXML(array($value), false);

                        // check see if the loan is imported as Approved 
                        // We need to add the Loan Application record to the table
                        if ($value['LSTATUS'] == 'LA') {
                            $value['LSTATUS'] = 'PA';
                            //  Bussiness::covertArrayToXML(array($value), false);
                        }

                        // generate dues        
                        $loan_dues = self::updateInstallmentSchedule();

                        // Add loan number to array
                        foreach ($loan_dues as $key => $val) {
                            $loan_dues[$key]['date'] = Common::changeDateFromPageToMySQLFormat($loan_dues[$key]['date']); //$value['LNR'];
                            $loan_dues[$key]['LNR'] = $value['LNR'];
                            $loan_dues[$key]['TABLE'] = TABLE_DUES;
                        }

                        Bussiness::covertArrayToXML($loan_dues, true);

                        break;

//                     case 'AP': // Approval
//                         
//                        self::$incomingvars['startDate'] = $value['ADATE'];
//                        
//                      //  $value['ADATE'] = Common::changeDateFromPageToMySQLFormat($value['ADATE']);
//                       // $value['START'] = Common::changeDateFromPageToMySQLFormat($value['START']);
//                        
//                      //  Bussiness::covertArrayToXML(array($value), false);
//
//                        self::$incomingvars['grace'] = $value['GRACE'];
//                        self::$incomingvars['INTTYPE'] = $value['INTTYPE'];
//                        self::$incomingvars['INSTYPE'] = $value['INSTYPE'];
//                        self::$incomingvars['lamount'] = $value['AMOUNT'];
//                        self::$incomingvars['intrate'] = $value['INTRATE'];
//                        self::$incomingvars['gracecompint'] = $value['GCOMP'];
//                                       
//                                                
//                        self::$incomingvars['no_of_inst'] = $value['NINST'];
//                        self::$incomingvars['allintpaidfirstinstallment'] = $value['FIRSTINS'];
//                        self::$incomingvars['intgrace'] = $value['AGRACE'];
//                        self::$incomingvars['intpaidatdisbursement'] = $value['INTDIS'];
//                        self::$incomingvars['productcode'] = $value['PRODID'];
//                        self::$incomingvars['insintgrace'] = $value['INSTING'];
//                        
//                        self::$incomingvars['adjustDueDatesTo'] = $value['AJDDU'];
//                        self::$incomingvars['adjusttononworkingday'] = $value['AJDW'];
//                        self::$incomingvars['intCompounded'] = $value['COMPINT'];
//
//                        // update loan status
////                        $value['TABLE'] = TABLE_LOANSTATUSLOG;
////                        Bussiness::covertArrayToXML(array($value), false);
//                      
//                        // generate dues        
//                        $loan_dues = self::updateInstallmentSchedule();
//
//                        // Add loan number to array
//                        foreach ($loan_dues as $key => $val) {
//                            $loan_dues[$key]['date'] = Common::changeDateFromPageToMySQLFormat($loan_dues[$key]['date']);//$value['LNR'];
//                            $loan_dues[$key]['LNR'] = $value['LNR'];
//                            $loan_dues[$key]['TABLE'] = TABLE_DUES;
//                        }
//
//                        Bussiness::covertArrayToXML($loan_dues, true);
//                        
//                        break;

                    case 'LD':

                        // DISBURSEMENTS                        
                        Common::getlables("1468,1229,1230,1105,1025,1203,67,311,1203", "", "", self::$connObj);
                        $value['TABLE'] = TABLE_DISBURSEMENTS;

                        self::$incomingvars['startDate'] = $value['startDate'];

                        if ($value['CTYPE'] == 'G'):
                            // get group distribution and disbursement
                            $mem_loan_array = Common::$connObj->SQLSelect("SELECT m.members_idno,m.memberloans_amount,l.loan_noofinst,l.loan_tint,l.loan_inttype,l.loan_insttype FROM " . TABLE_MEMBERLOANS . " m," . TABLE_LOAN . " l WHERE m.loan_number=l.loan_number AND m.loan_number='" . $value['LNR'] . "'");

                            // DISTRIBUTE DISBURSEMENTS
                            foreach ($mem_loan_array as $kaye => $val):
                                if ($val['memberloans_amount'] > 0):

                                    $nAmt = $value['AMOUNT'];

                                    $value['MEMID'] = $val['members_idno'];

                                    $value['AMOUNT'] = round(($val['memberloans_amount'] / $value['LAMNT']) * $value['AMOUNT'], SETTING_ROUNDING);

                                    self::$incomingvars['GRP'][$val['members_idno']] = $value['AMOUNT'];

                                    Bussiness::covertArrayToXML(array($value), false);

                                    $value['AMOUNT'] = $nAmt;
                                endif;

                            endforeach;

                        else:
                            //  $mem_loan_array = Common::$connObj->SQLSelect("SELECVT * FROM (SELECT l.loan_number,l.loan_noofinst,l.loan_noofinst,l.loan_tint,l.loan_inttype,l.loan_insttype FROM " . TABLE_LOAN . " l  WHERE loan_number='" . $value['LNR'] . "') AS loan ,SELECT * FROM ( SELECT loan_amount FROM ".TABLE_LOANSTATUSLOG." ks WHERE loan_number='".$value['LNR'] ."') AS ls");
                           
                            $mem_loan_array = Common::$connObj->SQLSelect("SELECT l.loan_number,ls.loan_amount,l.loan_noofinst,l.loan_tint,l.loan_inttype,l.loan_insttype,ls.loan_amount  FROM (SELECT loan_number,loan_noofinst,loan_tint,loan_inttype,loan_insttype FROM loan WHERE loan_number='" . $value['LNR'] . "') AS l ,
                            (SELECT loan_number,loan_amount,MAX(loan_datecreated) loan_datecreated FROM loanstatuslog WHERE loan_number='" . $value['LNR'] . "' GROUP BY loan_number,loan_datecreated,loan_amount ORDER BY loan_datecreated DESC LIMIT 1) AS ls WHERE ls.loan_number=l.loan_number");
                            
                            Bussiness::covertArrayToXML(array($value), false);
                        
                        endif;

                        // UPDATE SCHEDULES
                        self::$incomingvars['no_of_inst'] = $mem_loan_array[0]['loan_noofinst'];
                        self::$incomingvars['intrate'] = $mem_loan_array[0]['loan_tint'];
                        self::$incomingvars['INTTYPE'] = $mem_loan_array[0]['loan_inttype'];
                        self::$incomingvars['INSTYPE'] = $mem_loan_array[0]['loan_insttype'];

                        if ($value['CTYPE'] == 'G'):
                            $loan_dues = self::updateMemberSchedule();
                        else:
                            $loan_dues = self::updateInstallmentSchedule();
                        endif;

                        foreach ($loan_dues as $key => $val) {

                            $loan_dues[$key]['date'] = Common::changeDateFromPageToMySQLFormat($loan_dues[$key]['date']); //$value['LNR'];

                            $loan_dues[$key]['LNR'] = $value['LNR'];

                            $loan_dues[$key]['TABLE'] = TABLE_DUES;

                            $loan_dues[$key]['ACTION'] = 'DELETE';
                        }

                        Bussiness::covertArrayToXML($loan_dues, false);
                       
                        $value['AMOUNT'] = $mem_loan_array[0]['loan_amount']??0;

                        //Principal 
                        if (($value['AMOUNT']??0) > 0) {
                            $aLines[] = array('AMOUNT' => $value['AMOUNT'], 'TTYPE' => 'LD', 'PRODUCT_PRODID' => $value['LPRODID'], 'GLACC' => '', 'TRANCODE' => 'SC000', 'SIDE' => 'DR', 'DESC' => Common::$lablearray["1229"] . ' ' . $value['LNR'] . ' ' . $value['MEMID']);
                        }

                        //Commission 
                        if (($value['COMM']??0) > 0) {

                            //$aLines[] = array('AMOUNT' => $value['COMM'], 'TTYPE' => 'STA', 'PRODUCT_PRODID' => $value['LPRODID'], 'GLACC' => '', 'TRANCODE' => 'SC000', 'SIDE' => 'CR', 'DESC' => Common::$lablearray["1230"] . ' ' . $value['LNR']);
                            // CHECK SEE IF COMMISSION SHOULD BE DEDUCTED FROM SAVINGS
                            if ($value['LOAN_COM_FROM_SAV'] == '1'):
                                $amt = $value['AMOUNT'];
                                $mode = $value['MODE'];

                                $value['AMOUNT'] = -1 * abs($value['COMM']);

                                $value['COMM'] = abs($value['AMOUNT']);

                                $value['TABLE'] = TABLE_SAVTRANSACTIONS;

                                $value['TTYPE'] = 'DI-SA';

                                $value['MODE'] = 'SA';

                                // $value['PRODUCT_PRODID'] = $value['SPRODID'];

                                $accounts_array [] = $value['SAVACC'];

                                $products_array[] = $value['SPRODID'];

                                Bussiness::covertArrayToXML(array($value), false);

                                $aLines[] = array('AMOUNT' => abs($value['AMOUNT']), 'TTYPE' => 'DI-SA', 'PRODUCT_PRODID' => $value['SPRODID'], 'GLACC' => '', 'TRANCODE' => 'SW000', 'SIDE' => 'DR', 'DESC' => Common::$lablearray["1468"] . ' ' . $value['SAVACC']);
                                $aLines[] = array('AMOUNT' => abs($value['AMOUNT']), 'TTYPE' => 'COM', 'PRODUCT_PRODID' => $value['LPRODID'], 'GLACC' => '', 'TRANCODE' => 'SW000', 'SIDE' => 'CR', 'DESC' => Common::$lablearray["1468"] . ' ' . $value['SAVACC']);

                                $value['AMOUNT'] = $amt;

                                $value['MODE'] = $mode;

                            else: // CHECK SEE IF COMMISSION SHOULD BE DEDUCTED FROM DISBURSEMENT
                                $value['AMOUNT'] = $value['AMOUNT'] - $value['COMM'];
                            endif;
                        }

                        //STATIONERY 
                        if ($value['STAT'] > 0) {
                            $aLines[] = array('AMOUNT' => $value['STAT'], 'TTYPE' => 'COM', 'PRODUCT_PRODID' => $value['LPRODID'], 'GLACC' => '', 'TRANCODE' => 'LN000', 'SIDE' => 'CR', 'DESC' => Common::$lablearray["1105"] . ' ' . $value['LNR']);
                            $value['AMOUNT'] = $value['AMOUNT'] - $value['STAT'];
                        }

                        switch ($value['MODE']) {

                            case 'SA': // Savings

                                $value['TABLE'] = TABLE_SAVTRANSACTIONS;
                                $value['TTYPE'] = 'SD';
                                $value['PRODUCT_PRODID'] = $value['SPRODID'];

                                $accounts_array [] = $value['SAVACC'];
                                $products_array[] = $value['SPRODID'];
                                Bussiness::covertArrayToXML(array($value), false);
                                $aLines[] = array('AMOUNT' => $value['AMOUNT'], 'TTYPE' => 'DI-SA', 'PRODUCT_PRODID' => $value['SPRODID'], 'GLACC' => '', 'TRANCODE' => 'SD000', 'SIDE' => 'CR', 'DESC' => Common::$lablearray["1025"] . ' ' . $value['SAVACC']);

                                break;

                            case 'CQ': // Cheque
                            case 'DB': // Direct To Bank
                                if ($value['MODE'] == 'DB'):
                                    Common::addKeyValueToArray($value, 'cheques_no', 'CHEQNO');
                                    $value['CHEQNO'] = 'DB-' . $value['TCODE'];
                                endif;

                                $value['TABLE'] = TABLE_CHEQS;
                                $value['CQSTAT'] = 'C';

                                // UPDATE CHEQUES
                                Bussiness::covertArrayToXML(array($value), false);
                                $aLines[] = array('AMOUNT' => $value['AMOUNT'], 'TTYPE' => 'SP', 'PRODUCT_PRODID' => $value['LPRODID'], 'GLACC' => '', 'TRANCODE' => 'CC002', 'SIDE' => 'CR', 'DESC' => Common::$lablearray["1203"] . ' ' . $value['MEMID']); // Post Cheque on Suspence
                                $aLines[] = array('AMOUNT' => $value['AMOUNT'], 'TTYPE' => 'SP', 'PRODUCT_PRODID' => $value['LPRODID'], 'GLACC' => '', 'TRANCODE' => 'CC002', 'SIDE' => 'DR', 'DESC' => Common::$lablearray["1203"] . ' ' . $value['MEMID']); // Clear Cheque on Suspence
                                $aLines[] = array('AMOUNT' => $value['AMOUNT'], 'TTYPE' => 'BK', 'PRODUCT_PRODID' => $value['LPRODID'], 'GLACC' => $value['BANKGL'], 'BID' => $value['BID'], 'TRANCODE' => 'CC002', 'SIDE' => 'CR', 'DESC' => Common::$lablearray["67"] . " " . $value['BACCNO'] . ' ' . $value['MEMID']); // Clear Cheque on Suspence
                                break;

                            case 'CA': // Ccash
                                $aLines[] = array('AMOUNT' => $value['AMOUNT'], 'TTYPE' => $value['MODE'], 'PRODUCT_PRODID' => $value['LPRODID'], 'GLACC' => $value['GLACC'], 'TRANCODE' => 'CB000', 'SIDE' => 'CR', 'DESC' => Common::$lablearray["311"] . ' ' . $value['MEMID']);
                                break;
                        }

                        // UPDATE STATUS                      
//                        $value['TABLE'] = TABLE_LOANSTATUSLOG;
//                        Bussiness::covertArrayToXML(array($value), false);
                        break;
                }
            }
            if (Common::$lablearray['E01'] != "") {

                Bussiness::$Conn->cancelTransaction();
            } else {

                if ($value['CTYPE'] == "UNDEFINED" || $value['CTYPE'] == ""):
                    $value['CTYPE'] = Common::getClientType($value['CLIENTIDNO']);
                endif;

                // UPDATE THE GL 
                // if(count($aLines)>0):                    

                foreach ($aLines as $key => $val) {

                    $aLines[$key]['DATE'] = $value['DATE'];
                    $aLines[$key]['BANKID'] = $value['BANKID'];
                    $aLines[$key]['BRANCHCODE'] = $value['BRANCHCODE'];
                    $aLines[$key]['TCODE'] = $value['TCODE'];
                    $aLines[$key]['FUNDCODE'] = $value['FUNDCODE'];
                    $aLines[$key]['DONORCODE'] = $value['DONORCODE'];
                    $aLines[$key]['CTYPE'] = $value['CTYPE'];
                    $aLines[$key]['VOUCHER'] = $value['VOUCHER'];
                    $aLines[$key]['LNR'] = $value['VOUCHER'];

                    if ($value['TTYPE'] != "SA"):
                        $aLines[$key]['CLIENTIDNO'] = $value['CLIENTIDNO'];
                        $aLines[$key]['MODE'] = $value['MODE'];
                    endif;

                    $aLines[$key]['TABLE'] = TABLE_GENERALLEDGER;
                }

                if (count($aLines) > 0):
                    Common::returnTransactionOptions($aLines, self::$connObj);
                endif;


                if (Common::$lablearray['E01'] != "") {
                    //Bussiness::$Conn->cancelTransaction();
                    throw new Exception(Common::$lablearray['E01']);
                }

                // THIS SETION IS USED TO CALL OTHER MODULES
                if (Loan::$callmodule != ''):

                    Bussiness::covertArrayToXML($aLines, false);
                    switch (Loan::$callmodule):
                        case 'S':
                            $value['TCODE'] = Common::generateTransactionCode($_SESSION['user_id']);
                            Common::replace_key_function($value, 'SPRODID', 'PRODUCT_PRODID');
                            Common::replace_key_function($value, 'SAMOUNT', 'AMOUNT');
                            $value['TTYPE'] = 'SD';
                            $the_data = array($value);
                            Savings::updateSavings($the_data);

                            Common::replace_key_function($value, 'PRODUCT_PRODID', 'SPRODID');
                            break;
                        default:
                            break;
                    endswitch;

                else:
                    if (count($aLines) > 0):
                        Bussiness::covertArrayToXML($aLines, true);
                    endif;
                    // $tabledata['xml_data'] = Common::$xml;
                    // save 
                    Bussiness::PrepareData(true);

                    if (Common::$lablearray['E01'] != "") {
                        Bussiness::$Conn->cancelTransaction();
                        throw new Exception(Common::$lablearray['E01']);
                    }

                    Bussiness::$Conn->endTransaction();
                endif;

                if (isset($accounts_array)):
                    //$accounts_array [] = $value['SAVACC'];
                    // $products_array[] = $value['SPRODID'];
                    Common::updateSavingsBalance($accounts_array, $products_array, self::$connObj);
                endif;
            }
        } catch (Exception $e) {
            Bussiness::$Conn->cancelTransaction();
            Common::$lablearray['E01'] = $e->getMessage();
        }
    }

    // This function us used to create the  payment schedule of a loan
    public static function updateInstallmentSchedule() {

        try {

            // creates an array that has got the installment elements(still empty at this time)
            self::createInstallmentSchedule(self::$incomingvars['startDate']);

            self::$princCompounded = self::$incomingvars['lamount'];

            self::$incomingvars['allintpaidfirstinstallment'] = self::$incomingvars['allintpaidfirstinstallment']?? '';
            self::$incomingvars['intpaidatdisbursement'] = self::$incomingvars['intpaidatdisbursement']?? '';
            self::$incomingvars['allintpaidontinstallment'] = self::$incomingvars['allintpaidontinstallment']?? '';

            // looop to create schedule
            $tempDate = '';
            $tempinterest = 0;

            $tempInstallments = self::$InstallmentsInGrace;

            $tempCommission = 0;
            $tempAllocatedCommission = 0;
            $tempAllocatedPrincipal = 0;
            $tempAllocatedInterest = 0;
            $tempprincipal = 0;

            self::$tempTotalCommission = 0; //--self::calculateCommission(self::$incomingvars['lamount']);

            if (self::$incomingvars['INTTYPE'] == 'FR') {
                self::$installmentFrequency = 1;
            }

            self::$tempTotalInterest = ((self::$incomingvars['no_of_inst']) * self::calculateInterest(self::$incomingvars['lamount'], self::$incomingvars['INSTYPE'], self::$incomingvars['intrate'], self::$installmentFrequency));

            self::$tempTotalInterest = round(self::$tempTotalInterest, SETTING_ROUNDING);

            // this variable name is only used for DA loans
        
            if(self::$incomingvars['INTTYPE']=='FR'){

                list($principalquotient, $principalremainder)  = Common::calculateQuotientAndRemainder( self::$incomingvars['lamount'], self::$incomingvars['no_of_inst']);
                list($interestquotient, $interestremainder)  = Common::calculateQuotientAndRemainder(self::$tempTotalInterest, self::$incomingvars['no_of_inst']);
            

            }else{
                $pmtprincipal = self::calculateInstamentAmount(self::$incomingvars['INTTYPE'], self::$incomingvars['lamount'], self::$incomingvars['no_of_inst'], self::$InstallmentsInGrace, self::$incomingvars['intrate']);
            }
           
           

            $nLoanAmnt = self::$incomingvars['lamount'];
            $n = 1;
            $tempAllocatedPrincipal = 0;

            foreach (self::$loanShedule as $key => $val) {

                //self::$loanShedule[$key]['memid'] = self::$tempTotalInterest;

                $bPrincipal = 1;
                $bAccumulateInterest = 1;
                // check see if wee are paying all interest upfront
                //if(self::$incomingvars['intpaidatdisbursement']=='Y'){			
                //$bPrincipal = 0;
                //}
                // check see if there are installemtns in grace period	

                if(self::$loanShedule[$key]['state'] == 'P'  || self::$incomingvars['INTTYPE'] =='DD' || self::$incomingvars['INTTYPE']=='DA'):
                     $nDays = common::getNumberOfDaysBetweenDates(self::$incomingvars['startDate'], self::$loanShedule[$key]['date']);
                endif;
               

                //self::$loanShedule[$key]['memid'] = self::$loanShedule[$key]['date'];
                // calculate installments and Interest to be charged per installment
                switch (self::$incomingvars['INTTYPE']) {

                    case 'FR':
                        $tempinterest = $interestquotient + ($interestremainder - max(($interestremainder-1),0));
                                           
                        $interestremainder = max(($interestremainder-1),0);
       
                        //  if we are calculating interest in extra grace periods for flat rate loan
                        if (self::$loanShedule[$key]['state'] == 'P') {

                            //round(self::$incomingvars['lamount'] *(self::$incomingvars['intrate']/100)*($nDays/SETTING_INT_DAYS));
                            $tempinterest = round(self::$incomingvars['lamount'] * (self::$incomingvars['intrate'] / 100) * ($nDays / SETTING_INT_DAYS));
                        }

                        //self::$loanShedule[$key]['memid'] = $tempinterest;
                        $tempprincipal = $principalquotient + ($principalremainder - max(($principalremainder-1),0));
                     
                      
                        $principalremainder = max(($principalremainder-1),0);
                        break;

                    case 'DD':
                        
                        $tempinterest = self::calculateInterest($nLoanAmnt, self::$incomingvars['INSTYPE'], self::$incomingvars['intrate'], 0, $nDays);

                        $tempprincipal = $pmtprincipal;

                        //  we begin subtracting if installments in grace period  have been initialized or  when they are not present
                        if ($tempInstallments <= 0 || self::$incomingvars['insintgrace'] == 'N') {

                            // check see if principlay is still greater that tempprincipal
                            if ($nLoanAmnt > $tempprincipal) {
                                $nLoanAmnt = $nLoanAmnt - $tempprincipal;
                            }
                        }

                        break;

                    case 'DA':

                        $tempinterest = self::calculateInterest($nLoanAmnt, self::$incomingvars['INSTYPE'], self::$incomingvars['intrate'], 0, $nDays);

                        $tempprincipal = round($pmtprincipal - $tempinterest, SETTING_ROUNDING);

                        //  we begin subtracting if installments in grace period  have been initialized or  when they are not present
                        if ($tempInstallments <= 0) {
                            //echo $n.'-----';						
                            //	echo $tempInstallments.'---'.$nDays.'---'.$tempprincipal.'---'.self::$incomingvars['startDate'].'-----'.self::$loanShedule[$key]['date'].'<br>';
                            // check see if principlay is still greater that tempprincipal(i think this may be caused by grace priods)
                            if ($nLoanAmnt > $tempprincipal) {
                                $nLoanAmnt = $nLoanAmnt - $tempprincipal;
                            }
                        }

                        break;

                    default:
                        $tempinterest = round(self::$tempTotalInterest / (self::$incomingvars['no_of_inst']), SETTING_ROUNDING);
                        $tempprincipal = self::$incomingvars['lamount'] / self::$incomingvars['no_of_inst'];
                        break;
                }

                if ($tempInstallments > 0) {

                    // check see if there are installement as a result of grace period.
                    // we do not add that interest to in the evaluating actual the actual interest of payable loan
                    if (self::$loanShedule[$key]['state'] == 'P' || (self::$incomingvars['INTTYPE'] == 'FR' && $tempInstallments >= 0)) {
                        $bAccumulateInterest = 0;
                    }

                    // check see if we are compounding Interest in grace period(G)
                    /* if(self::$incomingvars['intCompounded']=='G' && self::$incomingvars['grace']>0){

                      //self::$princCompounded = self::$incomingvars['lamount'];

                      // flat rate loan
                      if(self::$incomingvars['INTTYPE']=='FR'){

                      self::$princCompounded = self::$princCompounded + $tempinterest;
                      self::$tempTotalInterest = ((self::$incomingvars['no_of_inst'])*self::calculateInterest(self::$princCompounded,self::$incomingvars['INSTYPE'],self::$incomingvars['intrate'],self::$installmentFrequency));
                      echo self::$tempTotalInterest .'<br>';

                      }


                      } */

                    $bPrincipal = 0;
                    $tempInstallments = $tempInstallments - 1;
                }

                // check see if we are to add principal Amount
                if ($bPrincipal == 0) {
                    $tempprincipal = 0.0;
                    $finalPrincipal = $tempprincipal;
                }

                self::$loanShedule[$key]['principal'] = $tempprincipal;
                $tempAllocatedPrincipal = $tempAllocatedPrincipal + $tempprincipal;

                self::$loanShedule[$key]['interest'] = $tempinterest;

                // check see if we should accumulate total interest for final computations of totals
                if ($bAccumulateInterest == 0) {
                    $tempinterest = 0;
                    $tempAllocatedInterest = 0;
                } else {
                    $tempAllocatedInterest = $tempAllocatedInterest + $tempinterest;
                }

                self::$loanShedule[$key]['commission'] = $tempCommission;

                self::$incomingvars['startDate'] = self::$loanShedule[$key]['date'];
            }

            //self::$loanShedule[$key]['memid']= $tempAllocatedInterest;
            // check see if user does not want installment in grace period
            // interest in grace to first installment
            if (self::$incomingvars['intgrace'] == 'Y' && self::$incomingvars['insintgrace'] == '' && self::$InstallmentsInGrace > 0) {

                array_walk(self::$loanShedule, function(&$a, $key) {

                    if ($key <= (self::$InstallmentsInGrace - 1)) {
                        self::$loanShedule[self::$InstallmentsInGrace]['interest'] = self::$loanShedule[self::$InstallmentsInGrace]['interest'] + self::$loanShedule[$key]['interest'];
                        self::$loanShedule[$key]['interest'] = 0.0;
                    }
                }
                );

                // remove  installment(s) from a schedule
                self::$loanShedule = array_slice(self::$loanShedule, self::$InstallmentsInGrace);
            }


            // check see if there is extra principal as a result of rounding off
            // if any add it to last installment
            if (self::$incomingvars['lamount'] != $tempAllocatedPrincipal && self::$incomingvars['INTTYPE']!='FR') {

                self::$loanShedule[count(self::$loanShedule) - 1]['principal'] = round(self::$loanShedule[count(self::$loanShedule) - 1]['principal'] + round(self::$incomingvars['lamount']), SETTING_ROUNDING) - round($tempAllocatedPrincipal, SETTING_ROUNDING);
            }


            // check see if there is extra interest as a result of rounding off
            // if any add it to last installment and the we are not compounding interest
            if (self::$tempTotalInterest != $tempAllocatedInterest && self::$incomingvars['INTTYPE'] == 'FR' && self::$incomingvars['intCompounded'] == '') {

                // check see if we are paying all interest upfront
                if (self::$incomingvars['intpaidatdisbursement'] == 'Y') {
                    self::$loanShedule[0]['interest'] = self::$tempTotalInterest;
                } else {
                    //self::$loanShedule[count(self::$loanShedule)-1]['memid'] =	$tempAllocatedInterest;		 			
                    //if(self::$incomingvars['grace']){	
                    self::$loanShedule[count(self::$loanShedule) - 1]['interest'] = (self::$loanShedule[count(self::$loanShedule) - 1]['interest'] + round(self::$tempTotalInterest, SETTING_ROUNDING)) - round($tempAllocatedInterest, SETTING_ROUNDING);
                    //}
                }
            }


            // check see if there is extra interest as a result of rounding off
            // if any add it to last installment
            if (self::$incomingvars['INTTYPE'] == 'DA' || self::$incomingvars['INTTYPE'] == 'DD') {

                // check see if we are paying all interest upfront
                if (self::$incomingvars['intpaidatdisbursement'] == 'Y') {
                    self::$loanShedule[0]['interest'] = $tempAllocatedInterest;
                }
            }

            // check see if there is extra commission as a result of rounding off
            // if any add it to last installment
            if (self::$tempTotalCommission != $tempAllocatedCommission) {
                self::$loanShedule[count(self::$loanShedule) - 1]['commission'] = self::$loanShedule[count(self::$loanShedule) - 1]['commission'] + round(self::$tempTotalCommission, SETTING_ROUNDING) - round($tempAllocatedCommission, SETTING_ROUNDING);
            }

            // initialise expiry date to last computed date of the schedules- last installement date
            self::$expDate = self::$incomingvars['startDate'];


            // check see of we are paying all interest on first/last installment or Interest  as disbursement
            // update first instalement in schuedule with total interest
            if (self::$incomingvars['allintpaidfirstinstallment'] == 'T' || self::$incomingvars['intpaidatdisbursement'] == 'Y' || self::$incomingvars['allintpaidontinstallment'] == 'L') {
                array_walk(self::$loanShedule, function(&$a, $key) {

                    if ($key != 0) {
                        self::$loanShedule[0]['interest'] = self::$loanShedule[0]['interest'] + self::$loanShedule[$key]['interest'];
                        self::$loanShedule[$key]['interest'] = 0.0;
                    }
                }
                );
            }

            // check see of we are paying all interest on last installment 
            if (self::$incomingvars['allintpaidfirstinstallment'] == 'L') {
                self::$loanShedule[count(self::$loanShedule) - 1]['interest'] = self::$loanShedule[0]['interest'];
                self::$loanShedule[0]['interest'] = 0;
            }

            return self::$loanShedule;
        } catch (Exception $e) {
            Common::$lablearray['E01'] = $e->getMessage();
            throw new Exception($e->getMessage());
        }
    }

//    //***************************************************************************************
//    //Description: This function is used to add/substract a date
//    //Signature: $addsub: operator $dtDate: The date to manupulate $no_of_dmy: Number to adjust the date with $myd: Days/Months/Years/Weeks  adjustDateTo:	Day in the month to adjust date to
//    // Note. if SETTING_DATE_FORMAT doe snot match the date format of variable passed this will create an error. 
//    //***************************************************************************************
//
//    public static function calculateDate($addsub = '+', $dtDate = '', $no_of_dmy = 0, $myd = 'D', $adjustDateTo = 0) {
//
//        try {
//
//          $date_format = Common::getDateFornat();
//          
//           switch(SETTING_DATE_FORMAT):
//             case 'dd/mm/YYYY':
//             case 'DD/MM/YYYY':
//                 if( strpos( $dtDate,'-' ) !== false ):
//                   $dtDate = Common::replaces_hyhpen($dtDate);
//                 endif;
//                 
//                break;
//                default:                
//                    break;
//            endswitch;
//         
//         
//    //      Dates in the m/d/y or d-m-y formats are disambiguated by looking at the separator 
//            //      between the various components: if the separator is a slash (/), then the 
//            //      American m/d/y is assumed; whereas if the separator is a dash (-) or a dot (.), 
//            //      then the European d-m-y format is assumed 
//        $new_date = DateTime::createFromFormat($date_format, $dtDate);
//
//
//            //redefine(SETTING_DATE_FORMAT ='m/d/Y';
//        // add or substract date
//
//        $no_of_dmy = '';
//        switch ($addsub) {
//
//            case '+':
//
//                // check see whether we are adjusting date by month/day/year
//                switch ($myd) {
//
//                    case 'D':
//                        if ($no_of_dmy == 0) {
//                            $no_of_dmy = '1';
//                        }
//
//                        //$date->add(new DateInterval('P1D'));	
//                        $no_of_dmy = 'P' . $no_of_dmy . 'D';
//                        break;
//
//                    case 'M':
//
//                        if ($no_of_dmy == 0) {
//                            $no_of_dmy = '1';
//                        }
//                        
//                        $no_of_dmy = 'P' . $no_of_dmy . 'M';
//                        
//                        // FOR HBPS - USE CALENDAR DAYS
//                        $curDay = $new_date->format("j"); //currect date
//                        $totDay = $new_date->format('t'); // total number of day in month
//                        $nMonth = $new_date->format("m");
//                        $nYear = $new_date->format("Y");
//                       
//                      
//                            switch ($curDay):
//                            case $curDay > 15:
//                                $no_of_dmy = $totDay - $curDay;
//                                
//                                // check see if month is december                                
//                                if($nMonth==12):
//                                    $nMonth = 1; 
//                                    $nYear++;
//                                else:
//                                    $nMonth++;
//                                endif;
//                                
//                                $nDaysInMonth = Common::getNumberofDaysInMonth($nMonth, $nYear);
//
//                                
//                               // get number of days  in 
//                                
//                               // $new_date_temp = clone($new_date);
//                               // $new_date_temp->add(new DateInterval('P1M'));
//                                $no_of_dmy = $no_of_dmy + $nDaysInMonth;
//                                $no_of_dmy = 'P' . $no_of_dmy . 'D';
//                                break;
//
//                            case $curDay <= 15:
//                                $no_of_dmy = $totDay - $curDay;
//                                $no_of_dmy = 'P' . $no_of_dmy . 'D';
//                                break;
//
//                            default:
//                                break;
//
//                            endswitch;
//
//                       
//                       
//                        //$date->add(new DateInterval('P1M'));
//                        
//
//                        break;
//
//                    case 'W':
//
//                        if ($no_of_dmy == 0) {
//                            $no_of_dmy = '1';
//                        }
//
//                        //$date->add(new DateInterval('P1W'));
//                        $no_of_dmy = 'P' . $no_of_dmy . 'W';
//                        break;
//
//                    case 'B':
//                        if ($no_of_dmy == 0) {
//                            $no_of_dmy = '2';
//                        }
//                        //$date->add(new DateInterval('P2W'));
//                        $no_of_dmy = 'P' . $no_of_dmy . 'W';
//                        break;
//
//                    case 'H':
//                        if ($no_of_dmy == 0) {
//                            $no_of_dmy = '15';
//                        }
//                        //$date->add(new DateInterval('P15D'));
//                        $no_of_dmy = 'P' . $no_of_dmy . 'D';
//                        break;
//
//                    case 'F':
//                        if ($no_of_dmy == 0) {
//                            $no_of_dmy = '4';
//                        }
//                        //$date->add(new DateInterval('P4M'));
//                        $no_of_dmy = 'P' . $no_of_dmy . 'M';
//                        break;
//
//                    case 'F':
//                        if ($no_of_dmy == 0) {
//                            $no_of_dmy = '6';
//                        }
//                        //$date->add(new DateInterval('P6M'));
//                        $no_of_dmy = 'P' . $no_of_dmy . 'M';
//                        break;
//
//                    case 'E':
//                        if ($no_of_dmy == 0) {
//                            $no_of_dmy = '7';
//                        }
//                        //$date->add(new DateInterval('P7M'));
//                        $no_of_dmy = 'P' . $no_of_dmy . 'M';
//                        break;
//
//                    case 'A':
//                        if ($no_of_dmy == 0) {
//                            $no_of_dmy = '12';
//                        }
//                        //$date->add(new DateInterval('P12M'));
//                        $no_of_dmy = 'P' . $no_of_dmy . 'M';
//                        break;
//
//                    default:
//                        $no_of_dmy = 'P' . $no_of_dmy . 'D';
//                        break;
//                }
//
//                $new_date->add(new DateInterval($no_of_dmy));
//
//
//                break;
//
//            case '-':
//                //$new_date->sub(new DateInterval('P'.$no_of_dmy.$myd));
//                break;
//
//            default;
//                break;
//        }
//
//        // check see if we should adjust date to user specified date
//        // if a  users specified 29th for Feb. Should we change.No. Because feb has 28 days at times
//
//        $nDaysInMonth = Common::getNumberofDaysInMonth($new_date->format("m"), $new_date->format("Y"));
//
//
//        // check see if we should adjust date
//        if ($adjustDateTo > 0) {
//
//            // check 30/31/28 day-month
//            //$nDays = $nDaysInMonth + $adjustDateTo;
//
//
//            if ($adjustDateTo > $nDaysInMonth) {
//
//                $adjustDateTo = $nDaysInMonth;
//            }
//
//
//            if ($adjustDateTo > 0) {
//                // if $adjustDateTo  is higher that day generated adjust forwards
//                if ($adjustDateTo > $new_date->format("j")) {
//                    $nDays = $adjustDateTo - $new_date->format("j");
//                    $new_date->add(new DateInterval('P' . $nDays . 'D'));
//                }
//
//                // if $adjustDateTo  is less that day generated adjust backwards
//                if ($new_date->format("j") > $adjustDateTo) {
//                    $nDays = $new_date->format("j") - $adjustDateTo;
//                    $new_date->sub(new DateInterval('P' . $nDays . 'D'));
//                }
//            }
//        }
//
//        // check see if  we are checking for non working days
//        if (self::$incomingvars['adjusttononworkingday'] == 'Y') {
//
//            // loop till you get a day which is nor a public holiday
//            while (Common::checkHolidays($new_date->format('Y-m-d')) > 0) {
//                $new_date->add(new DateInterval('P1D'));
//            }
//        }
//
//        //echo $dtDate.'---'.$myd.'-----'.$date->format(SETTING_DATE_FORMAT).'----'.$nDaysInMonth.'<br>';
//        //echo '------------------------------------------------------------------------------------<br>';
//        return array('day' => $new_date->format("j"), 'month' => trim($new_date->format("m")), 'year' => trim($new_date->format("Y")), 'date' => trim($new_date->format($date_format)));
//        //exit();
//        // return the date object
//        //return $adate;
//        
//         }catch(Exception $e) {
//          Common::$lablearray['E01'] = $e->getMessage();
//        }
//    }
    // this function is used to calculate Interest just for the grace period
    public static function calculateIntInGrace($nGrace = 0, $INSTYPE = 'D', $inTType = 'FR', $nLoanAmnt = 0, $Intrate = 1, $calIntInDays = 'N', $InstallmentFrequency = 1) {

        if ($calIntInDays == 'Y') {
            if ($inTType == 'FR') {
                //complete
                // get installment interest X fraction of grace period i.e Interest PerInstallment X 1 day/installment frequency
                //
				return round(self::calculateInterest($nLoanAmnt, $INSTYPE, $Intrate, $InstallmentFrency) * ($nGrace / SETTING_INT_DAYS), SETTING_ROUNDING);
            } else {
                return round($nLoanAmnt * ($Intrate / 100) * ($nGrace / SETTING_INT_DAYS), SETTING_ROUNDING);
            }
        } else {

            if ($INSTYPE == 'FR') {

                // get installment interest X fraction of grace period i.e Interest PerInstallment X 1 day/installment frequency
                //
				return round(self::calculateInterest($nLoanAmnt, $INSTYPE, $Intrate, $InstallmentFrequency) * ($nGrace / $InstallmentFrequency), SETTING_ROUNDING);
            } else {

                // check see if grace priod is less that installment period(frequency) an calculate the interest for that period
                // Interest for full installment  X grace period/installment frequncy
                if ($nGrace < $InstallmentFrequency) {

                    return round(($nLoanAmnt * 1 / $InstallmentFrequency * $Intrate / 100) * ($nGrace / $InstallmentFrequency), SETTING_ROUNDING);
                } else {
                    return round(($nLoanAmnt * 1 / $InstallmentFrequency * $Intrate / 100), SETTING_ROUNDING);
                }
            }
        }
    }

    // This function is used to recalculate Interest
    // At Repayment
    public static function reCalculateInterest($nLoanAmnt = 0, $INSTYPE = 'D', $Intrate = 1, $Duefrequency = 0, $nDays = 1) {


        // Variable Interest rate
    }
      

    // *This function is used to calculate Interest per installment
    public static function calculateInterest($nLoanAmnt = 0, $INSTYPE = 'D', $Intrate = 1, $Duefrequency = 0, $nDays = 1) {
        $annualInterestRateFactor = (self::$incomingvars['annualnterestRate'] == 'N') ? (1 / self::$incomingvars['no_of_inst']) : null;
    
        switch (self::$incomingvars['INTTYPE']) {
            case 'FR':
                switch ($INSTYPE) {
                    case 'D':
                        return ($nLoanAmnt * ($Intrate / 100)) * ($annualInterestRateFactor ?: ($Duefrequency / SETTING_INT_DAYS));
                    case 'W':
                        return ($nLoanAmnt * ($Intrate / 100)) * ($annualInterestRateFactor ?: (($Duefrequency / 7) / SETTING_INT_WEEKS));
                    case 'B':
                        return ($nLoanAmnt * ($Intrate / 100)) * ($annualInterestRateFactor ?: (($Duefrequency / 14) / (SETTING_INT_WEEKS / 2)));
                    case 'O':
                        return ($nLoanAmnt * ($Intrate / 100)) * ($annualInterestRateFactor ?: (($Duefrequency / 28) / (SETTING_INT_WEEKS / 4)));
                    case 'M':
                        return ($nLoanAmnt * ($Intrate / 100)) * ($annualInterestRateFactor ?: ($Duefrequency / 12));
                }
                break;
    
            case 'DA':
                return round($nLoanAmnt * ($nDays / SETTING_INT_DAYS) * ($Intrate / 100), SETTING_ROUNDING);
            
            case 'DD':
                if ($INSTYPE == 'M') {
                    return round($nLoanAmnt * (1 / 12) * ($Intrate / 100), SETTING_ROUNDING);
                } else {
                    return round($nLoanAmnt * ($nDays / SETTING_INT_DAYS) * ($Intrate / 100), SETTING_ROUNDING);
                }
                break;
    
            default:
                break;
        }
    }
    

    // this function is used to calculate the loan principal monthly/ weekly/Dailt etc installment
    public static function calculateInstamentAmount($INTTYPE, $nLoanAmnt, $nInstaments, $nGrace, $intRate = 1) {

        //$nInstaments= $nInstaments-$nGrace;
        //echo 'Install: '.$nInstaments;
        // echo 'amount: '.$nLoanAmnt;
        // exit();
        switch ($INTTYPE) {

            case'FR':
                return round($nLoanAmnt / $nInstaments, SETTING_ROUNDING);
                break;

            // case'DD':

            //     return round($nLoanAmnt / $nInstaments, SETTING_ROUNDING);

            //     break;

            case'DA':
                return Financial::PMT(($intRate / 100) / 12, $nInstaments, $nLoanAmnt, 0.0, 0);
                break;

            case'DD':
                return Financial::PMT(($intRate / 100) / 12, $nInstaments, $nLoanAmnt, 0.0, 0);

                break;

            default:
                break;
        }
    }

    // this function is used to calculate the loan principal installment
    public static function calculateCommission($nLoanAmnt) {

        // to do
        // function to calculate commision		
        return 0;
    }

    // This function is used to create a group mmer schedule
    public static function updateMemberSchedule() {

        $incomingvars = self::$incomingvars;
        $loanShedule = array();
        // add a resursive situation here
        // keep changing the loan amount	
        //  $a_values = array_values(self::$incomingvars['GRP']);
        //print_r($a_values);
        foreach (self::$incomingvars['GRP'] as $memid => $val) {

            // refrsh variables 

            self::$loanShedule = array();

            self::$incomingvars = $incomingvars;

            self::$incomingvars['lamount'] = $val;

            self::$startDateActual = '';
            self::$extraDays = 0;
            self::$InstallmentsInGrace = 0;
            self::$installmentFrequency = 1;
            self::$no_of_inst_in_grace = 0;
            self::$loanShedule = array();
            self::$expDate = '';
            self::$monthlyDateAdjust = '';
            self::$InterestInGrace = 0;
            self::$tempTotalInterest = 0;
            self::$tempTotalCommission = 0;
            self::$intCompounded = 0;
            self::$princCompounded = 0;
            self::$memberid = $memid;

            self::updateInstallmentSchedule();

            foreach (self::$loanShedule as $key => $lval) {
                array_push($loanShedule, $lval);
            }
        }

        self::$loanShedule = $loanShedule;

        return self::$loanShedule;
    }

    // this function is used to get dibursement 
    public static function getDisbursements() {

        $cWhere = '';
        // get disbursements
        switch (self::$clienttype) {

            case 'I':
            case 'B':
            case 'G':
                $cWhere = " loan_number='" . self::$cLnr . "'";
                break;
            case 'GRM':
                $cWhere = " members_idno='" . self::$memberid . "' AND loan_number='" . self::$cLnr . "'";
                break;
            default:
                $cWhere = " loan_number='" . self::$cLnr . "'";
                break;
        }

        Common::prepareParameters($parameters, 'code', 'GETLOANDISBURSEMENTS');
        Common::prepareParameters($parameters, 'userid', $_SESSION['user_id']);
        Common::prepareParameters($parameters, 'ddate', self::$paydate);
        Common::prepareParameters($parameters, 'loannumber', self::$cLnr);
        self::$disbursements = Common::common_sp_call(serialize($parameters), '', Common::$connObj, false);

        //     self::$disbursements = self::$connObj->SQLSelect("(SELECT loan_number,members_idno,SUM(disbursements_amount)disbursements_amount,SUM(disbursements_stationery)disbursements_stationery,SUM(disbursements_commission)disbursements_commission FROM " . TABLE_DISBURSEMENTS . " WHERE " . $cWhere . " AND disbursements_date<='" . common::changeDateFromPageToMySQLFormat(self::$paydate, false) . "' GROUP  BY loan_number,members_idno) UNION (SELECT loan_number,members_idno,0.0 disbursements_amount,0.0  disbursements_stationery,0.0  disbursements_commission FROM ".TABLE_DISBURSEMENTS." WHERE  " . $cWhere . " GROUP  BY loan_number,members_idno) LIMIT 1");
    }

    // this function is used to get loan dues 
    public static function getLoanDues() {

        $cWhere = '';

        switch (self::$clienttype) {

            case 'I':
            case 'B':
            case 'G':
                $cWhere = " loan_number='" . self::$cLnr . "'";
                break;
            case 'GRM':
                $cWhere = " members_idno='" . self::$memberid . "' AND loan_number='" . self::$cLnr . "'";
                break;
            default:
                break;
        }


        self::$loandues = self::$connObj->SQLSelect(""
                . "SELECT loan_number ,members_idno,SUM(due_principal) due_principal,SUM(due_interest)due_interest,SUM(due_penalty)due_penalty,SUM(due_commission)due_commission,SUM(due_vat)due_vat FROM " . TABLE_DUES . " WHERE " . $cWhere . " AND DATE(due_date)<= DATE('" . self::$paydate . "') GROUP  BY loan_number,members_idno"
                . " UNION ALL "
                . "SELECT b.loan_number,b.members_idno,0.0 due_principal ,0.0 due_interest,0.0 due_penalty ,0.0 due_commission,0.0 due_vat FROM disbursements b WHERE b.loan_number='" . self::$cLnr . "' AND NOT EXISTS(SELECT o.loan_number FROM dues o WHERE o.loan_number='" . self::$cLnr . "'  AND DATE(o.due_date)<= DATE('" . self::$paydate . "') LIMIT 1) GROUP BY b.loan_number,b.members_idno");

        self::$loanduesbeforeduedate = self::$connObj->SQLSelect(""
                . "SELECT loan_number ,SUM(due_principal) due_principal,SUM(due_interest)due_interest,SUM(due_penalty)due_penalty,SUM(due_commission)due_commission,SUM(due_vat)due_vat ,members_idno FROM " . TABLE_DUES . " WHERE " . $cWhere . " AND DATE(due_date)< DATE('" . self::$paydate . "') GROUP  BY loan_number,members_idno"
                . " UNION ALL "
                . "SELECT b.loan_number,b.members_idno,0.0 due_principal ,0.0 due_interest,0.0 due_penalty ,0.0 due_commission,0.0 due_vat FROM disbursements b WHERE b.loan_number='" . self::$cLnr . "' AND NOT EXISTS(SELECT o.loan_number FROM dues o WHERE o.loan_number='" . self::$cLnr . "'  AND DATE(o.due_date)< DATE('" . self::$paydate . "') LIMIT 1) GROUP BY b.loan_number,b.members_idno");

        self::$loantotaldues = self::$connObj->SQLSelect("SELECT loan_number ,SUM(due_principal) due_principal,SUM(due_interest)due_interest,SUM(due_penalty)due_penalty,SUM(due_commission)due_commission,SUM(due_vat)due_vat,members_idno FROM " . TABLE_DUES . " WHERE " . $cWhere . " GROUP  BY loan_number,members_idno");
    }

    // this function is used to get loan payments 
    public static function getLoanPayments() {

        $cWhere = '';

        switch (self::$clienttype) {

            case 'I':
            case 'B':
            case 'G':
                $cWhere = " loan_number='" . self::$cLnr . "'";
                break;
            case 'GRM':
                $cWhere = " members_idno='" . self::$memberid . "' AND loan_number='" . self::$cLnr . "'";
                break;
            default:
                break;
        }

        self::$loanpayments = self::$connObj->SQLSelect("SELECT loan_number,members_idno,SUM(COALESCE(loanpayments_principal,0.0))loanpayments_principal,SUM(COALESCE(loanpayments_interest,0.0))loanpayments_interest,SUM(COALESCE(loanpayments_commission,0.0))loanpayments_commission,SUM(COALESCE(loanpayments_penalty,0.0))loanpayments_penalty,SUM(COALESCE(loanpayments_vat,0.0))loanpayments_vat FROM " . TABLE_LOANPAYMENTS . " WHERE " . $cWhere . " AND DATE(loanpayments_date)<=DATE('" . common::changeDateFromPageToMySQLFormat(self::$paydate, false) . "') GROUP BY loan_number,members_idno"
                . " UNION ALL " .
                "SELECT loan_number,members_idno,0.0 loanpayments_principal ,0.0 loanpayments_interest,0.0 loanpayments_commission ,0.0 loanpayments_penalty,0.0 loanpayments_vat FROM " . TABLE_DISBURSEMENTS . " WHERE loan_number ='" . self::$cLnr . "'  AND NOT EXISTS(SELECT o.loan_number FROM " . TABLE_LOANPAYMENTS . " o WHERE o.loan_number='" . self::$cLnr . "' LIMIT 1) GROUP BY loan_number,members_idno");
    }

    // this function to calculate balances for a loan on a specified date
    public static function calculateBalances() {

        foreach (self::$disbursements AS $dkey => $dval):

            // get all dues to this date
            $due_total_principal[$dval['members_idno']] = Common::sum_array('members_idno', $dval['members_idno'], 'due_principal', self::$loantotaldues);
            $due_total_interest[$dval['members_idno']] = Common::sum_array('members_idno', $dval['members_idno'], 'due_interest', self::$loantotaldues);
            $due_total_penalty[$dval['members_idno']] = Common::sum_array('members_idno', $dval['members_idno'], 'due_penalty', self::$loantotaldues);
            $due_total_commission[$dval['members_idno']] = Common::sum_array('members_idno', $dval['members_idno'], 'due_commission', self::$loantotaldues);
            $due_total_vat[$dval['members_idno']] = Common::sum_array('members_idno', $dval['members_idno'], 'due_vat', self::$loantotaldues);

            $due_principal[$dval['members_idno']] = Common::sum_array('members_idno', $dval['members_idno'], 'due_principal', self::$loandues);
            $due_interest[$dval['members_idno']] = Common::sum_array('members_idno', $dval['members_idno'], 'due_interest', self::$loandues);
            $due_penalty[$dval['members_idno']] = Common::sum_array('members_idno', $dval['members_idno'], 'due_penalty', self::$loandues);
            $due_commission[$dval['members_idno']] = Common::sum_array('members_idno', $dval['members_idno'], 'due_commission', self::$loandues);
            $due_vat[$dval['members_idno']] = Common::sum_array('members_idno', $dval['members_idno'], 'due_vat', self::$loandues);

            $current_due_principal[$dval['members_idno']] = Common::sum_array('members_idno', $dval['members_idno'], 'due_principal', self::$currentloandues);
            $current_due_interest[$dval['members_idno']] = Common::sum_array('members_idno', $dval['members_idno'], 'due_interest', self::$currentloandues);
            $current_due_penalty[$dval['members_idno']] = Common::sum_array('members_idno', $dval['members_idno'], 'due_penalty', self::$currentloandues);
            $current_due_commission[$dval['members_idno']] = Common::sum_array('members_idno', $dval['members_idno'], 'due_commission', self::$currentloandues);
            $current_due_vat[$dval['members_idno']] = Common::sum_array('members_idno', $dval['members_idno'], 'due_vat', self::$currentloandues);


            // before due date
            $dues_before_principal[$dval['members_idno']] = Common::sum_array('members_idno', $dval['members_idno'], 'due_principal', self::$loanduesbeforeduedate); //self::$loanduesbeforeduedate['due_principal'];
            $dues_before_interest[$dval['members_idno']] = Common::sum_array('members_idno', $dval['members_idno'], 'due_interest', self::$loanduesbeforeduedate); //self::$loanduesbeforeduedate['due_interest'];
            $dues_before_penalty[$dval['members_idno']] = Common::sum_array('members_idno', $dval['members_idno'], 'due_penalty', self::$loanduesbeforeduedate); //self::$loanduesbeforeduedate['due_penalty'];
            $dues_before_commission[$dval['members_idno']] = Common::sum_array('members_idno', $dval['members_idno'], 'due_commission', self::$loanduesbeforeduedate); //self::$loanduesbeforeduedate['due_commission'];
            $dues_before_vat[$dval['members_idno']] = Common::sum_array('members_idno', $dval['members_idno'], 'due_vat', self::$loanduesbeforeduedate); //self::$loanduesbeforeduedate['due_vat'];
            // get all payments 
            $loanpayments_principal[$dval['members_idno']] = Common::sum_array('members_idno', $dval['members_idno'], 'loanpayments_principal', self::$loanpayments); //self::$loanpayments['loanpayments_principal'];
            $loanpayments_interest[$dval['members_idno']] = Common::sum_array('members_idno', $dval['members_idno'], 'loanpayments_interest', self::$loanpayments); //self::$loanpayments['loanpayments_interest'];
            $loanpayments_penalty[$dval['members_idno']] = Common::sum_array('members_idno', $dval['members_idno'], 'loanpayments_penalty', self::$loanpayments); //self::$loanpayments['loanpayments_penalty'];
            $loanpayments_commission[$dval['members_idno']] = Common::sum_array('members_idno', $dval['members_idno'], 'loanpayments_commission', self::$loanpayments); //self::$loanpayments['loanpayments_commission'];
            $loanpayments_vat[$dval['members_idno']] = Common::sum_array('members_idno', $dval['members_idno'], 'loanpayments_vat', self::$loanpayments); //self::$loanpayments['loanpayments_vat'];
            //ARREARS
            // check see if all primcial - current princial > payments. Then we haev arrears
            // principal
            $due_arrears_principal[$dval['members_idno']] = 0;
            if ($dues_before_principal[$dval['members_idno']] >= $loanpayments_principal[$dval['members_idno']]) {
                $due_arrears_principal[$dval['members_idno']] = bcsub($dues_before_principal[$dval['members_idno']], $loanpayments_principal[$dval['members_idno']], SETTING_ROUNDING);
            }

            // interest
            $due_arrears_interest[$dval['members_idno']] = 0;
            if ($dues_before_interest[$dval['members_idno']] > $loanpayments_interest[$dval['members_idno']]) {
                $due_arrears_interest[$dval['members_idno']] = bcsub($dues_before_interest[$dval['members_idno']], $loanpayments_interest[$dval['members_idno']], SETTING_ROUNDING);
            }

            // commision
            $due_arrears_commission[$dval['members_idno']] = 0;
            if ($dues_before_commission[$dval['members_idno']] > $loanpayments_commission[$dval['members_idno']]) {
                $due_arrears_commission[$dval['members_idno']] = bcsub($dues_before_commission[$dval['members_idno']], $loanpayments_commission[$dval['members_idno']], SETTING_ROUNDING);
            }

            // penalty
            $due_arrears_penalty[$dval['members_idno']] = 0;
            if ($dues_before_penalty[$dval['members_idno']] > $loanpayments_penalty[$dval['members_idno']]) {
                $due_arrears_penalty[$dval['members_idno']] = bcsub($dues_before_penalty[$dval['members_idno']], $loanpayments_penalty[$dval['members_idno']], SETTING_ROUNDING);
            }

            self::$loanarrears[$dval['members_idno']] = array('pay_date' => self::$paydate, 'due_principal' => $due_arrears_principal[$dval['members_idno']], 'due_interest' => $due_arrears_interest[$dval['members_idno']], 'due_penalty' => $due_arrears_penalty[$dval['members_idno']], 'due_commission' => $due_arrears_commission[$dval['members_idno']], 'Total' => ($due_arrears_principal[$dval['members_idno']] + $due_arrears_interest[$dval['members_idno']] + $due_arrears_penalty[$dval['members_idno']] + $due_arrears_commission[$dval['members_idno']]), 'members_idno' => $dval['members_idno']);

            //CURRENT DUES	
            //principal
            $prepaid_due_principal[$dval['members_idno']] = 0;
            $prepaid_due_principal[$dval['members_idno']] = bcsub($loanpayments_principal[$dval['members_idno']], $dues_before_principal[$dval['members_idno']], SETTING_ROUNDING);
            $prepaid_due_principal[$dval['members_idno']] = ($prepaid_due_principal[$dval['members_idno']] < 0 ? 0 : $prepaid_due_principal[$dval['members_idno']]);

            if ($prepaid_due_principal[$dval['members_idno']] >= 0) {
                $current_due_principal[$dval['members_idno']] = bcsub($current_due_principal[$dval['members_idno']], $prepaid_due_principal[$dval['members_idno']], SETTING_ROUNDING);
            } else {
                $current_due_principal[$dval['members_idno']] = 0;
            }

            // overpayments
            if ($loanpayments_principal[$dval['members_idno']] > $due_total_principal[$dval['members_idno']]) {
                self::$overpayments[$dkey][$dval['members_idno']]['due_principal'] = bcsub($loanpayments_principal[$dval['members_idno']], $due_total_principal[$dval['members_idno']], SETTING_ROUNDING);
            }

            $current_due_principal[$dval['members_idno']] = ($current_due_principal[$dval['members_idno']] < 0 ? 0 : $current_due_principal[$dval['members_idno']]);

            //interest
            $prepaid_due_interest[$dval['members_idno']] = 0;
            $prepaid_due_interest[$dval['members_idno']] = bcsub($loanpayments_interest[$dval['members_idno']], $dues_before_interest[$dval['members_idno']], SETTING_ROUNDING);
            $prepaid_due_interest[$dval['members_idno']] = ($prepaid_due_interest[$dval['members_idno']] < 0 ? 0 : $prepaid_due_interest[$dval['members_idno']]);

            if ($prepaid_due_interest[$dval['members_idno']] >= 0) {

                $current_due_interest[$dval['members_idno']] = bcsub($current_due_interest[$dval['members_idno']], $prepaid_due_interest[$dval['members_idno']], SETTING_ROUNDING);
            } else {
                $current_due_interest[$dval['members_idno']] = 0;
            }

            // overpayments interest
            if ($loanpayments_interest[$dval['members_idno']] > $due_total_interest[$dval['members_idno']]) {
                self::$overpayments[$dkey][$dval['members_idno']]['due_interest'] = bcsub($loanpayments_interest[$dval['members_idno']], $due_total_interest[$dval['members_idno']], SETTING_ROUNDING);
            }

            $current_due_interest[$dval['members_idno']] = ($current_due_interest[$dval['members_idno']] < 0 ? 0 : $current_due_interest[$dval['members_idno']]);

            //interest
            $prepaid_due_penalty[$dval['members_idno']] = 0;
            $prepaid_due_penalty[$dval['members_idno']] = bcsub($loanpayments_penalty[$dval['members_idno']], $dues_before_penalty[$dval['members_idno']], SETTING_ROUNDING);
            $prepaid_due_penalty[$dval['members_idno']] = ($prepaid_due_penalty[$dval['members_idno']] < 0 ? 0 : $prepaid_due_penalty[$dval['members_idno']]);

            if ($prepaid_due_penalty[$dval['members_idno']] >= 0) {

                $current_due_penalty[$dval['members_idno']] = bcsub($current_due_penalty[$dval['members_idno']], $prepaid_due_penalty[$dval['members_idno']], SETTING_ROUNDING);
            } else {

                $current_due_penalty[$dval['members_idno']] = 0;
            }

            if ($loanpayments_penalty[$dval['members_idno']] > $due_total_penalty[$dval['members_idno']]) {
                self::$overpayments[$dkey][$dval['members_idno']]['due_penalty'] = bcsub($loanpayments_penalty[$dval['members_idno']], $due_total_penalty[$dval['members_idno']], SETTING_ROUNDING);
            }
            $current_due_penalty[$dval['members_idno']] = ($current_due_penalty[$dval['members_idno']] < 0 ? 0 : $current_due_penalty[$dval['members_idno']]);

            // commission
            $prepaid_due_commission[$dval['members_idno']] = 0;
            $prepaid_due_commission[$dval['members_idno']] = bcsub($loanpayments_commission[$dval['members_idno']], $dues_before_commission[$dval['members_idno']], SETTING_ROUNDING);
            $prepaid_due_commission[$dval['members_idno']] = ($prepaid_due_commission[$dval['members_idno']] < 0 ? 0 : $prepaid_due_commission[$dval['members_idno']]);

            if ($prepaid_due_commission[$dval['members_idno']] >= 0) {

                $current_due_commission[$dval['members_idno']] = bcsub($current_due_commission[$dval['members_idno']], $prepaid_due_commission[$dval['members_idno']], SETTING_ROUNDING);
            } else {
                $current_due_commission[$dval['members_idno']] = 0;
            }

            if ($loanpayments_commission[$dval['members_idno']] > $due_total_commission[$dval['members_idno']]) {
                self::$overpayments[$dkey][$dval['members_idno']]['due_commission'] = bcsub($loanpayments_commission[$dval['members_idno']], $due_total_commission[$dval['members_idno']], SETTING_ROUNDING);
            }

            $current_due_commission[$dval['members_idno']] = ($current_due_commission[$dval['members_idno']] < 0 ? 0 : $current_due_commission[$dval['members_idno']]);

            // vat
            $prepaid_due_vat[$dval['members_idno']] = 0;
            $prepaid_due_vat[$dval['members_idno']] = bcsub($loanpayments_vat[$dval['members_idno']], $dues_before_vat[$dval['members_idno']], SETTING_ROUNDING);
            $prepaid_due_vat[$dval['members_idno']] = ($prepaid_due_vat[$dval['members_idno']] < 0 ? 0 : $prepaid_due_vat[$dval['members_idno']]);

            if ($prepaid_due_vat[$dval['members_idno']] >= 0) {

                $current_due_vat[$dval['members_idno']] = bcsub($current_due_vat[$dval['members_idno']], $prepaid_due_vat[$dval['members_idno']], SETTING_ROUNDING);
            } else {
                $current_due_vat[$dval['members_idno']] = 0;
            }

            if ($loanpayments_vat[$dval['members_idno']] > $due_total_vat[$dval['members_idno']]) {
                self::$overpayments[$dkey][$dval['members_idno']]['due_vat'] = bcsub($loanpayments_vat[$dval['members_idno']], $due_total_vat[$dval['members_idno']], SETTING_ROUNDING);
            }

            $current_due_vat[$dval['members_idno']] = ($current_due_vat[$dval['members_idno']] < 0 ? 0 : $current_due_vat[$dval['members_idno']]);

            $chargeint = Common::get_array_elements_with_key_in_3D_array(self::$loanproductsettings, 'CHARGE_INT');

            // check see if we are to charge interest
            if ($chargeint['CHARGE_INT'] == '1' && self::$loanappdetails['client_regstatus'] == 'EXT') {
                $current_due_interest[$dval['members_idno']] = '';
            }
            unset(self::$currentloandues[$dkey]);
            unset(self::$prepaiddues[$dkey]);
            unset(self::$outstanding[$dkey]);

            self::$currentloandues[$dval['members_idno']] = array('pay_date' => self::$paydate, 'due_principal' => $current_due_principal[$dval['members_idno']], 'due_interest' => $current_due_interest[$dval['members_idno']], 'due_penalty' => $current_due_penalty[$dval['members_idno']], 'due_commission' => $current_due_commission[$dval['members_idno']], 'due_vat' => $current_due_vat[$dval['members_idno']], 'Total' => ($current_due_principal[$dval['members_idno']] + $current_due_interest[$dval['members_idno']] + $current_due_commission[$dval['members_idno']] + $current_due_penalty[$dval['members_idno']] + $current_due_vat[$dval['members_idno']]), 'members_idno' => $dval['members_idno']);
            self::$prepaiddues[$dval['members_idno']] = array('pay_date' => self::$paydate, 'due_principal' => round($prepaid_due_principal[$dval['members_idno']], SETTING_ROUNDING), 'due_interest' => round($prepaid_due_interest[$dval['members_idno']], SETTING_ROUNDING), 'due_penalty' => round($prepaid_due_penalty[$dval['members_idno']], SETTING_ROUNDING), 'due_commission' => round($prepaid_due_commission[$dval['members_idno']], SETTING_ROUNDING), 'due_vat' => round($prepaid_due_vat[$dval['members_idno']], SETTING_ROUNDING), 'Total' => round($prepaid_due_principal[$dval['members_idno']] + $prepaid_due_interest[$dval['members_idno']] + $prepaid_due_penalty[$dval['members_idno']] + $prepaid_due_commission[$dval['members_idno']] + $prepaid_due_vat[$dval['members_idno']], SETTING_ROUNDING), 'members_idno' => $dval['members_idno']);
            self::$overpayments[$dkey][$dval['members_idno']]['Total'] = self::$overpayments[0]['due_principal'] + self::$overpayments[0]['due_interest'] + self::$overpayments[0]['due_penalty'] + self::$overpayments[0]['due_commission'] + self::$overpayments[0]['due_vat'];
            self::$outstanding[$dval['members_idno']] = array(
                'pay_date' => self::$paydate,
                'due_principal' => bcsub($dval['disbursements_amount'], Common::sum_array('members_idno', $dval['members_idno'], 'loanpayments_principal', self::$loanpayments), SETTING_ROUNDING),
                'due_interest' => bcsub(Common::sum_array('members_idno', $dval['members_idno'], 'due_interest', self::$loantotaldues), Common::sum_array('members_idno', $dval['members_idno'], 'loanpayments_interest', self::$loanpayments), SETTING_ROUNDING),
                'due_penalty' => bcsub(Common::sum_array('members_idno', $dval['members_idno'], 'due_penalty', self::$loantotaldues), Common::sum_array('members_idno', $dval['members_idno'], 'loanpayments_penalty', self::$loanpayments), SETTING_ROUNDING),
                'due_commission' => bcsub(Common::sum_array('members_idno', $dval['members_idno'], 'due_commission', self::$loantotaldues), Common::sum_array('members_idno', $dval['members_idno'], 'loanpayments_commission', self::$loanpayments), SETTING_ROUNDING),
                'due_vat' => bcsub(Common::sum_array('members_idno', $dval['members_idno'], 'due_vat', self::$loantotaldues), Common::sum_array('members_idno', $dval['members_idno'], 'loanpayments_vat', self::$loanpayments), SETTING_ROUNDING),
                'Total' => 0);

            self::$outstanding[$dval['members_idno']]['Total'] = (self::$outstanding[$dval['members_idno']]['due_principal'] + self::$outstanding[$dval['members_idno']]['due_interest'] + self::$outstanding[$dval['members_idno']]['due_penalty'] + self::$outstanding[$dval['members_idno']]['due_commission'] + self::$outstanding[$dval['members_idno']]['due_vat']);

        endforeach;

        // KEEP THE DATA FOR FURE USE
        SerializeUnserialize::getInstance()->put_serialized_data('loan_' . Common::replace_string(self::$loanappdetails['loan_number']) . '.txt', self::$outstanding);
    }

    // this function to get the current dues of a loan
    public static function getcurrentloandues() {
        $cWhere = '';

        switch (self::$clienttype) {

            case 'I':
            case 'B':
            case 'G':
                $cWhere = " loan_number='" . self::$cLnr . "'";
                break;
            case 'GRM':
                $cWhere = " members_idno='" . self::$memberid . "' AND loan_number='" . self::$cLnr . "'";
                break;
            default:
                break;
        }

        self::$currentloandues = self::$connObj->SQLSelect(
                "SELECT d.due_date,d.loan_number,d.members_idno,SUM(d.due_principal) due_principal,SUM(d.due_interest) due_interest,SUM(d.due_penalty)due_penalty,SUM(d.due_commission)due_commission,d.due_vat,0.0 as Total FROM " . TABLE_DUES . " d WHERE " . $cWhere . " AND d.due_date = DATE('" . self::$paydate . "') GROUP BY d.due_date ,d.loan_number,d.members_idno"
                . " UNION ALL "
                . "SELECT '' due_date, p.loan_number, p.members_idno,0.0 due_principal ,0.0 due_interest,0.0 due_penalty ,0.0 due_commission,0.0 due_vat,0.0 Total FROM " . TABLE_DUES . " p WHERE p.loan_number='" . self::$cLnr . "' AND NOT EXISTS(SELECT o.loan_number FROM dues o WHERE o.loan_number='" . self::$cLnr . "'  AND DATE(o.due_date)= DATE('" . self::$paydate . "') LIMIT 1) GROUP BY p.loan_number,p.members_idno");
    }

}?>