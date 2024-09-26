<?php

//Ps. Do not PASS NULL parameters
//Ps. Do not concat NULL VARIABLES in SPs
class ConnectionFactory
{

    private static $factory;

    private $stmt;
    private static $instances = [];
    private static $key;

    private $host = DB_SERVER;
    private $dbName = DB_DATABASE;
    private $user = DB_SERVER_USERNAME;
    private $pass = DB_SERVER_PASSWORD;
    private $finaldata = array();
    public $includeKeyField = false;
    public $multiplerowsets = false;
    public $ReferenceFieldList = array(); // array containg fileds to be put in where cluase
    public $dateFields = array();
    public static $error;
    public static $allrows;
    private $AutoCommit = false;

    //  public static $report_fields = array(); // THis array contains fields /columns selected from the report interface
    public $keyFields;


    public function setAutoCommit($commit = false)
    {
        $this->AutoCommit = $commit;
    }

    public function __construct()
    {

        self::$key = md5($this->host . $this->dbName . $this->user . $this->pass);
        $this->connect();
    }

    private function connect()
    {
        $dsn = "mysql:host={$this->host};dbname={$this->dbName}";
        $options = [
            PDO::ATTR_PERSISTENT => false,
            PDO::ATTR_EMULATE_PREPARES => true,
            PDO::ATTR_CASE => PDO::CASE_LOWER,
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        ];

        self::$instances[self::$key] = new PDO($dsn, $this->user, $this->pass, $options);
    }

    public static function getInstance()
    {

        if (!isset(self::$instances[self::$key])) {
            $factory  = new self();
        }

        return $factory;
    }

    public function query($query)
    {
        $this->stmt = self::$instances[self::$key]->prepare($query);
    }

    public function bindValue($param, $value, $type = null)
    {
        if (is_null($type)) {

            switch (true) {

                case is_int($value):
                    $type = PDO::PARAM_INT;
                    break;

                case is_bool($value):
                    $type = PDO::PARAM_BOOL;
                    break;

                case is_null($value):
                    $type = PDO::PARAM_NULL;
                    break;

                default:
                    $type = PDO::PARAM_STR;
            }
        }
        $this->stmt->bindValue($param, $value, $type);
    }

    public function execute()
    {

        try {
            $results  = $this->stmt->execute();
            return $results;
        } catch (Exception $ex) {
            throw $ex;
        }
    }

    // get column names of table
    // Note. May return error if table doesnot have Primary Keys
    public function preparefieldList($cTable = '', $preparefieldlist = false)
    {

        // check see if database uis defined
        if (!defined('DB_DATABASE')) {
            echo 'Database not defined';
            exit();
        }

        $p_tname = 'TABLE_' . $cTable;

        // check whther table  is defined
        if (!defined($p_tname)) {
            define($p_tname, $cTable);
        }

        $this->getPrimaryKeys($cTable);

        // check see if we are to inlude the auto-inrement field name
        if ($this->includeKeyField) {

            $this->stmt = self::$instances[self::$key]->prepare("select column_name from information_schema.columns where table_name=:table_name AND table_schema=:table_schema ORDER BY ordinal_position ASC");
        } else {
            $this->stmt = self::$instances[self::$key]->prepare("select column_name from information_schema.columns where table_name=:table_name AND table_schema =:table_schema and extra!='auto_increment' ORDER BY ordinal_position ASC");
        }

        $this->stmt->bindValue(":table_name", $cTable);
        $this->stmt->bindValue(":table_schema", DB_DATABASE);


        $temparray = $this->resultset();

        // get all date fields we shall need to identify them when saving date and converting it to mysql format
        foreach ($temparray as $key => $val) {

            if (($val['column_type'] ?? '') == 'date') {
                $this->dateFields[] = $val['column_name'];
            }
        }

        if ($preparefieldlist) {
            $fieldsTemp = array_map('array_flip', array_values($temparray));
            $fields_array = call_user_func_array('array_merge', $fieldsTemp);

            // set '' as default value
            return array_map(function ($val) {
                return '';
            }, $fields_array);
        } else {
            return $temparray;
        }
    }

    // get primary keys 
    // This function may return an error message of table does not ave a primary key
    private function getPrimaryKeys($cTable)
    {

        $this->stmt = self::$instances[self::$key]->prepare("SELECT k.COLUMN_NAME FROM information_schema.TABLE_CONSTRAINTS t JOIN information_schema.KEY_COLUMN_USAGE k USING (CONSTRAINT_NAME, TABLE_SCHEMA, TABLE_NAME) WHERE t.CONSTRAINT_TYPE = 'PRIMARY KEY' AND t.TABLE_SCHEMA =:table_schema AND t.TABLE_NAME =:table_name");
        $this->stmt->bindValue(":table_name", $cTable);
        $this->stmt->bindValue(":table_schema", DB_DATABASE);
        // $this->keyFields = $this->resultset();


        $this->keyFields[$cTable] = implode('', array_values(call_user_func_array('array_merge', $this->resultset())));
    }

    // get entire result set
    //  FOR NOW WE CAN ONLY FEATH 2 RECORD SETS
    public function resultset()
    {

        try {

            $this->execute();
            $status = true;
            $nCount = 1;
            //    $allrows = array();
            do {

                $colcount = $this->stmt->columnCount();
                //   if($status==true):
                if ($colcount > 0) {
                    $allrows[] = $this->stmt->fetchAll(PDO::FETCH_ASSOC);
                }
                // endif;
                $nCount++;

                if ($nCount > 2):
                    $status = false;
                else:
                    $status = $this->stmt->nextRowset();
                endif;
            } while ($status);
        } catch (Exception $ex) {
            throw $ex;
        }

        if (!isset($allrows)) {
            return array();
        } elseif (count($allrows) > 1) {
            //   $this->stmt->$multiplerowsets =true;
            return $allrows;
        } else {
            //  $this->stmt->$multiplerowsets =false;
            return $allrows[0];
        }
    }

    public function single()
    {
        $this->execute();
        return $this->stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function rowCount()
    {
        return $this->stmt->rowCount();
    }

    public function lastInsertId()
    {
        return self::$instances[self::$key]->lastInsertId();
    }

    public function beginTransaction()
    {
        if (!self::$instances[self::$key]->inTransaction()) {
            return self::$instances[self::$key]->beginTransaction();
        }
    }

    public function endTransaction()
    {
        if (self::$instances[self::$key]->inTransaction()) {
            return self::$instances[self::$key]->commit();
        }
    }

    public function cancelTransaction()
    {
        if (self::$instances[self::$key]->inTransaction()) {
            return self::$instances[self::$key]->rollBack();
        }
    }

    public function debugDumpParams()
    {
        return $this->stmt->debugDumpParams();
    }

    public function queryError()
    {
        $this->qError = self::$instances[self::$key]->errorInfo();
        if (!is_null($qError[2])) {
            echo $qError[2];
        }
    }

    // this is used to insert values
    //public function SQLInsert($valuesToInsert, $table){
    public function SQLInsert($valuesToInsert, $Autocommit = true)
    {

        try {

            // check see if we are committing transaction right away
            //  $this->AutoCommit = $Autocommit;

            // get tables to insert into
            $tables = array_keys($valuesToInsert);

            // prepare statements
            foreach ($tables as $tkey => $tname) {


                $theData = $valuesToInsert[$tname];

                // check see if we have multiple rows	
                // e.g array(array())- an array of daata containing another array
                // and get table field names
                if ($theData[0] ?? false) {
                    $fieldnames = array_keys($valuesToInsert[$tname][0]);
                } else {
                    $fieldnames = array_keys(call_user_func_array('array_merge', array($valuesToInsert[$tname])));
                }


                $query = sprintf("INSERT INTO %s", $tname) . "(" . implode(",", $fieldnames) . ") VALUES (:" . implode(",:", $fieldnames) . ")";

                unset($this->stmt);
                // check see if we have already an active transaction
                if (!self::$instances[self::$key]->inTransaction()) {

                    self::$instances[self::$key]->beginTransaction();
                }

                $this->stmt = self::$instances[self::$key]->prepare($query);

                $theData = $valuesToInsert[$tname];


                // check see if we have multiple rows	
                // eg array(array())
                if (($theData[0] ?? false)) {

                    foreach ($theData as $key => $rowitem) {
                        foreach ($rowitem as $column => $value) {
                            $this->stmt->bindValue(":{$column}", $value);
                        }

                        $this->stmt->execute();
                    }
                } else {
                    foreach ($theData as $column => $value) {
                        $this->stmt->bindValue(":" . $column, $value);
                    }
                    $this->stmt->execute();
                }
            }

            // commit data to database
            if ($this->AutoCommit) {

                self::$instances[self::$key]->commit();

                if ($this->stmt->rowCount() != 0) {
                    $this->stmt->closeCursor();

                    return true;
                } else {

                    $this->stmt->closeCursor();

                    return false;
                }
            }

            // $this->SQLSelect('UNLOCK TABLES', false);
        } catch (Exception $ex) {
            //throw new Exception($ex->getMessage());
            $this->stmt->closeCursor();
            self::$error = $ex->getMessage();
            Common::$lablearray['E01'] = self::$error;
        }
    }

    // public function SQLUpdate($tname, $fieldToChange, $fieldValue, $idField, $idValue){ 
    public function SQLUpdate($valuesToInsert, $Autocommit)
    {

        // check see if we are committing transaction right away
        $this->AutoCommit = $Autocommit;

        // get tables to insert into
        $tables = array_keys($valuesToInsert);


        // check see if we have already an active transaction
        if (!self::$instances[self::$key]->inTransaction()) {

            self::$instances[self::$key]->beginTransaction();
        }


        foreach ($tables as $key => $tname) {
            if (count($this->ReferenceFieldList[$tname]) == 0) {
                $this->ReferenceFieldList[$tname] = $this->preparefieldList($tname, true);
            }
            $wfield = array_keys($this->ReferenceFieldList[$tname]);

            //  prepare field list to insert it where clause
            if (count($wfield) > 0) {
                array_walk_recursive($wfield, function (&$item, $key) {
                    $item = $item . '=:' . $item;
                });

                $place_holder_clause_fieldslist = implode(" AND ", $wfield);
            }


            if (count($wfield) <= 0) {
                $this->getPrimaryKeys($tname); // for some reason this statement takes alot of time in this function
            }

            // create an array with fieldname elements 
            $afield = array_keys($valuesToInsert[$tname]);


            // remove primary ket in fieldlist-we shoul dnot update it
            if (($key = array_search($this->keyFields[$tname], $afield)) !== false) {
                unset($afield[$key]);
            }

            // prepare field list to insert in update clause
            if (count($afield) > 0) {
                array_walk_recursive($afield, function (&$item, $key) {
                    $item = $item . '=:' . $item;
                });

                $place_holder_fieldslist = implode(",", $afield);
            }


            if (count($this->ReferenceFieldList) > 0) {

                $this->stmt = self::$instances[self::$key]->prepare(sprintf("UPDATE " . $tname . " SET " . $place_holder_fieldslist . " WHERE " . $place_holder_clause_fieldslist));
            } else {

                $this->stmt = self::$instances[self::$key]->prepare(sprintf("UPDATE " . $tname . " SET " . $place_holder_fieldslist . " WHERE " . $this->keyFields[$tname] . "=:" . $this->keyFields[$tname]));
            }

            // post data
            $theData = $valuesToInsert[$tname];

            // check see if its date -chnage it to MySQl format
            foreach ($theData as $column => $value) {

                if (in_array($column, $this->dateFields)) {
                    $this->stmt->bindValue(":" . $column, $this->changeDateFromPageToMySQLFormat($value, false));
                } else {
                    $this->stmt->bindValue(":" . $column, $value);
                }
            }

            // update where clause
            if (count($wfield) >= 0) {

                $theDataWhere = $this->ReferenceFieldList[$tname];

                foreach ($theDataWhere as $column => $value) {

                    if (in_array($column, $this->dateFields)) {
                        $this->stmt->bindValue(":" . $column, $this->changeDateFromPageToMySQLFormat($value, false));
                    } else {
                        $this->stmt->bindValue(":" . $column, $value);
                    }
                }
            }

            $this->stmt->execute();

            $this->ReferenceFieldList = array();
        }

        //$this->stmt->closeCursor();
        // commit data to database
        if ($this->AutoCommit) {
            self::$instances[self::$key]->commit();
            $this->stmt->closeCursor();

            return true;
        } else {
            //$this->stmt->closeCursor();	
            return false;
        }
    }


    public function SQLDelete($table, $idTable = '', $idValue = '')
    {
        try {
            $fieldlist = $this->ReferenceFieldList[$table] ?? [];

            // If there are reference fields, prepare the WHERE clause
            if (!empty($fieldlist)) {
                $wfield = array_keys($fieldlist);
                array_walk(
                    $wfield,
                    function (&$item) {
                        $item .= '=:' . $item;
                    }
                );
                $place_holder_clause_fieldslist = implode(
                    " AND ",
                    $wfield
                );
            } else {
                // If no reference fields, determine primary key
                if (empty($idTable)) {
                    $this->getPrimaryKeys($table);
                    $idTable = $this->keyFields[$table];
                }
                $place_holder_clause_fieldslist = "$idTable = :value";
            }

            // Prepare the DELETE statement
            $sql = sprintf("DELETE FROM %s WHERE %s", $table, $place_holder_clause_fieldslist);
            $this->stmt  = self::$instances[self::$key]->prepare($sql);

            // Begin transaction if not already in one
            if (!self::$instances[self::$key]->inTransaction()) {
                self::$instances[self::$key]->beginTransaction();
            }

            // Bind values based on the reference fields
            if (!empty($fieldlist)) {
                foreach ($fieldlist as $column => $value) {
                    $boundValue = in_array($column, $this->dateFields)
                        ? Common::changeDateFromPageToMySQLFormat($value, false)
                        : $value;
                    $this->stmt->bindValue(":" . $column, $boundValue);
                }
            } else {
                $this->stmt->bindParam(":value", $idValue);
            }

            // Execute the statement
            $this->stmt->execute();

            // Check if any rows were affected         
            self::$instances[self::$key]->commit();           
            
        } catch (Exception $e) {
            // Rollback transaction on error if needed
            if (self::$instances[self::$key]->inTransaction()) {
                self::$instances[self::$key]->rollBack();
                $this->stmt->closeCursor();
            }
            throw $e;
        }
    }


    /**
     * This function is used to call stored procedures
     * $reportcode: used to identify what sp to call
     * $parameters: Parameters passed from the interface
     *  default value for column addtemptable for some sps
     */
    public function sp_call($parameters, $format = '')
    {

        switch ($parameters['code']) {

            case 'DYNAMICSQL':
                $var_sp = 'sp_dynamic_sql';
                break;

            case 'SMSMESSAGES':
                $var_sp = 'sp_get_sms_messages';
                break;

            case 'SAVTRAN':
                $var_sp = 'sp_get_sav_transactions';
                break;

            case 'CLIENTLOANFREQ': // Client Loan Frequency Report
                $var_sp = 'sp_get_client_loan_frequency';
                break;

            case 'GETLOANDISBURSEMENTS':
                $var_sp = 'sp_get_loan_disbursements';
                break;

            case 'LOANDISBURSE':
                $var_sp = 'sp_search_loan_disburse';
                break;

            case 'TDRPT':
                $var_sp = 'sp_get_timedeposit_detail';
                break;

            case 'DELETEITEM':
                $var_sp = 'sp_delete_item';
                break;

            case 'DASHBOARD':
                $var_sp = 'sp_get_dashboard';
                break;

            case 'PROFITPERPERIOD':
                $var_sp = 'sp_get_profit_per_period';
                break;

            case 'CHARTOFACCOUNTS':
                $var_sp = 'sp_get_coa';
                break;

            case 'REFINANCEP1':
                $var_sp = 'sp_update_schedule';
                break;

            case 'SEARCHLOAN':
                $var_sp = 'sp_search_loan';
                break;

            case 'INTSAVRPT':
                $var_sp = 'sp_get_savings_interest_in_period';
                $parameters['user_id'] = $_SESSION['user_id'];
                break;

            case 'LOANWRITEOFF':
                $var_sp = 'sp_write_off_loans';
                $parameters['user_id'] = $_SESSION['user_id'];
                break;

            case 'SMS':
                $var_sp = 'sp_get_loan_arrears_details_sms';
                break;

            case 'TCODE':
                $var_sp = 'sp_generate_transactioncode_ui';
                $parameters['userid'] = $_SESSION['user_id'];
                $parameters['branch_code'] = '';
                break;

            case 'PROVISION':
                $var_sp = 'sp_calculate_provisions';

                $parameters['user_id'] = $_SESSION['user_id'];
                break;

            case 'TRANINPERIOD':
                $var_sp = 'sp_get_transactions_made';
                break;

            case 'OPENCLOSEPERIOD':
                $var_sp = 'sp_open_close_period';
                break;

            case 'COLLECTINTEREST':
                $var_sp = 'sp_get_princ_int_due';
                break;

            case 'DEBITCREDIT':
                $var_sp = 'sp_get_check_debit_credit';
                break;

            case 'INDREPAYLOANS':
                $var_sp = 'sp_get_whats_due';

                if (!isset($parameters['loan_number_fr'])) {
                    $parameters['loan_number_fr'] = '';
                }

                if (!isset($parameters['loan_number_to'])) {
                    $parameters['loan_number_to'] = '';
                }
                break;

            case 'LOANWRITEOFF':
                $var_sp = 'sp_write_off_loan';
                break;

            case 'LOANDUESSUM':
                $var_sp = 'sp_get_loan_details_summary';
                break;

            case 'CLIENTRPTS': // Client Report
                $var_sp = 'sp_get_client_details';
                break;

            case 'TIMEDEPOSITRPTS':
                $var_sp = 'sp_get_timedeposit_detail';
                break;

            case 'SAVSTAT': // Savers Statement
                $var_sp = 'sp_get_savers_statement';
                break;

            case 'SAVBALRPT': // Savings Balnces Report
                $var_sp = 'sp_get_savings_balances_detail';
                break;

            case 'SAVBALS': // Savings Balnces Report
                $var_sp = 'sp_get_savings_balances';
                break;

            case 'SAVBALSBYID': // Savings Balnces Report
                $var_sp = 'sp_get_savings_balances_by_id';
                break;

            case 'SAVTILL': // Savings Tillsheet
                $var_sp = 'sp_get_savings_tillsheet';
                break;

            case 'UPDSAVBAL': // Update Savings Balances
                $var_sp = 'sp_update_savings_balances';
                break;

            case 'OUTBAL': // Loan Outstanding Balances
                $var_sp = 'sp_get_outstanding_loan_balances';
                $parameters['loan_number_fr'] = '';
                $parameters['loan_number_to'] = '';
                break;

            case 'GUARANTORS': // Report on guarantors
                $var_sp = 'sp_get_loan_guarantors';
                break;

            case 'UPDLOANSETTINGS': // Update loan settings
                $var_sp = 'sp_update_loan_products_settings';
                break;

            case 'UPDSAVSETTINGS': // Update loan settings           
                $var_sp = 'sp_update_sav_products_settings';
                break;

            case 'UPDTDSETTINGS': // Update loan settings           
                $var_sp = 'sp_update_td_products_settings';
                break;

            case 'ARRERPT': // Arrears Report
                $var_sp = 'sp_get_loan_arrears_details';
                $parameters['addtemptable'] = '';
                $parameters['loan_number_fr'] = '';
                $parameters['loan_number_to'] = '';
                break;

            case 'PORTRSK': // Portfolio At Risk
                $var_sp = 'sp_get_loan_portfolio_at_risk_details';
                $parameters['loan_number_fr'] = '';
                $parameters['loan_number_to'] = '';
                break;

            case 'BREAKPERACC': // Breakdown per Account report
                $var_sp = 'sp_get_breakdown_per_account';
                break;

            case 'TRIALB': // Trial Balance
                $var_sp = 'sp_get_trial_balance';
                break;

            case 'BALANCESHEET': // Balance Sheet
                $var_sp = 'sp_get_balance_sheet';
                break;

            case 'GENERATEID': // generate ID
                $var_sp = 'sp_generate_id_wrapper';
                break;

            case 'INCOMEEXP': // Income and Expenditure 
                $var_sp = 'sp_get_income_expenditure';

                if (!isset($parameters['ccode'])):
                    $parameters['ccode'] = '';
                endif;
                break;

            case 'REVERSETRAN': // Reverse Transaction
                $var_sp = 'sp_reverse_transactions_wrapper';
                break;

            case 'DISBURSEMENTS': // Disbusemebts report
                $var_sp = 'sp_get_dibursements';

                break;

            case 'BANKDETAILS': // get bank details
                $var_sp = 'sp_get_bank_details';
                break;

            case 'LOANREP': // Payments made in a period
                $var_sp = 'sp_get_loan_payments_inperiod';
                $parameters['loan_number_fr'] = '';
                $parameters['loan_number_to'] = '';
                break;

            case 'PLEDGER': // Personal Ledger
                $var_sp = 'sp_get_personal_ledger';
                break;

            case 'PLEDGERMULTIPLE': // Multiple Personal Ledger
                $var_sp = 'sp_get_clients_by_areacode';
                break;

            case 'SAVACCOUNTDETAILS': // Payments made in a period
                $var_sp = 'sp_savings_details';
                break;

            case 'ClIENTDETAILS':
                $var_sp = 'sp_get_client';
                break;

            case 'RECALINT':
                $var_sp = 'sp_recalculate_int';
                break;

            case 'GETTRAN':
                $var_sp = 'sp_get_transaction';
                break;

            case 'CALCSAVINT':
            case 'SAVINTRPTS':

                // if key exists
                // it is sued to check if we should post the transaction or not
                if (isset($_GET['rpt'])):
                    $parameters['tcode'] = '';
                    $parameters['action'] = '';
                    $parameters['tdate'] = Common::changeDateFromPageToMySQLFormat($_GET['tdate']);
                    $parameters['client_regstatus'] = $_GET['client_regstatus'];
                    $parameters['areacode_code'] = $_GET['areacode_code'];
                endif;

                if (!array_key_exists("action", $parameters)) {
                    $parameters['action'] = '';
                }

                if (!array_key_exists("tcode", $parameters)) {
                    $parameters['tcode'] = '';
                }
                $var_sp = 'sp_calc_interest_on_savings';
                break;

            case 'MLLCARD':
                $var_sp = 'sp_get_loan_details';
                break;

            case 'RFLLCARD':
                $var_sp = 'sp_get_loanledgercard_ref_details';
                break;

            case 'DUESLN':
                $var_sp = 'sp_get_expected_payments_dues';
                break;

            case 'MLLCARDDETAILS':
                $var_sp = 'sp_get_loanledgercard_details';
                break;

            case 'IDEXISTS':
                $var_sp = 'sp_check_if_exists';
                break;

            case 'RESCHEDULE':
                $var_sp = 'sp_get_loan_schedule';
                break;
            default:
                break;
        }

        // LANGUAGE
        switch ($parameters['code']) {

            case 'CALCSAVINT':
            case 'SAVINTRPTS':
            case 'TRANINPERIOD':
            case 'MLLCARDDETAILS':
            case 'PROVISION':
            case 'LOANWRITEOFF':
            case 'DASHBOARD':
            case 'CHARTOFACCOUNTS':
                $parameters['plang'] = P_LANG;
                break;
        }

        // TEPORARY TABLE        
        switch ($parameters['code']) {

            case 'PORTRSK': // Portfolio At Risk
            case 'SAVBALRPT': // Savings Balnces Report
                $parameters['addtemptable'] = 1;
                break;

            case 'CLIENTRPTS': // Client Report
                $parameters['addtemptable'] = 0;
                break;
            case 'SAVSTAT': // Savers Statement
            case 'RECALINT': // 

            case 'OUTBAL': // Loan Outstanding Balances
            case 'DISBURSEMENTS': // Disbursements
            case 'MLLCARD': // MUltiple Loan ledgercard
                $parameters['addtemptable'] = 0;
                break;

            case 'GUARANTORS':
                $parameters['addtemptable'] = 0;
                break;

            default:
                break;
        }

        // get paerameter list of the sp
        $sp_meta_data = $this->SQLSelect("SELECT parameter_name,parameter_mode,data_type FROM information_schema.parameters WHERE SPECIFIC_NAME ='" . $var_sp . "' AND SPECIFIC_SCHEMA ='" . DB_DATABASE . "' ORDER BY ORDINAL_POSITION", true);
        $field_list_str = '';
        $nCount = 1;
        $nlen = count($sp_meta_data);

        foreach ($sp_meta_data as $key => $value) {
            if ($nCount != $nlen) {
                $field_list_str .= ":" . $value['parameter_name'] . ",";
            } else {
                $field_list_str .= ":" . $value['parameter_name'];
            }

            $hold_params[] = $value['parameter_name'];

            $nCount++;
        }

        $this->stmt = self::$instances[self::$key]->prepare('CALL `' . $var_sp . '`(' . $field_list_str . ')');


        // bind by parameters and pass reference not value 
        foreach ($parameters as $param => &$value) {
            // check see if parameter is part of the sp parameter list  and bind it          
            if (in_array($param, $hold_params)) {
                $this->stmt->bindParam(':' . $param, $value);
            }
        }

        $recordset_array = $this->resultset();

        $this->stmt->closeCursor();

        if ($format == 'REPORT') {
            $report_columns = unserialize($_SESSION['report_columns']);
            return Common::array_column_multi($recordset_array, $report_columns);
        } else {
            return $recordset_array;
        }
    }

    public function SQLSelect($query = '', $returnresults = true)
    {

        try {

            $this->stmt = self::$instances[self::$key]->prepare($query);

            if ($returnresults) {
                //$this->stmt = self::$instances[self::$key]->prepare($query);	


                $array_results = $this->resultset();

                if (
                    count($array_results) > 0
                ) {
                    return $array_results;
                } else {
                    return array(array('1' => '1'));
                }
            } else {
                $this->stmt->execute();
            }
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function RowsAffected()
    {
        return self::$instances[self::$key]->Rows;
    }

    // decode data frm Jason
    public function json_decodeData($cString)
    {

        $jason = preg_replace("{\\\}", "", $cString);

        return json_decode($jason, true);
    }

    public function prepareData($tables, $data)
    {

        $objects = json_decodeData($data);
        // $ob is null because the json cannot be decoded
        if ($objects === null) {
            $objects = $data;
        }
        // prepare data for each table
        foreach ($tables as $tkey => $tvalue) {

            $aInsert = array();

            $valuesToInsert = array();

            $ncount = 0;

            // prepare firld name and value as posted form page	
            foreach ($objects as $key => $value) {
                if ($value instanceof StdClass) {

                    $aInsert[$value->name] = $value->value;

                    if ($value->value != "") {

                        array_push($valuesToInsert, array($value->name => $value->value));
                    }
                }
            }

            // get table field  list
            $tableTemplate = array_map('array_flip', array_values($this->preparefieldList($tvalue)));


            // flaten array
            $flat1 = call_user_func_array('array_merge', $valuesToInsert);


            /// flaten array inot a singlle dimension array

            $flat2 = call_user_func_array('array_merge', $tableTemplate);
        }

        // merge field list and values from page
        $this->finaldata[$tvalue] = array_intersect_key($flat1, $flat2);

        return $this->finaldata;
    }
}
