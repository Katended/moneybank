<?php
require_once('common.php');
class DataTable
{
	public static $numberof_rows_on_page = 15;
	public static $page = 1;
	public static $lastpage = "";
	public static $totalsets = 0;
	public static $totalrows = 0;
	public static $sortfield = '';
	public static $sortorder = '';
	public static $rec_count = 0;
	public static $columns = array();
	public static $sSQL = ''; // array();
	public static $keyfield = '';
	public static $fieldlist = array(); // AN EXTRA COLUMN MY BE DEFINED FOR THE PRIMARY KEY WHEN SPECIFYING FIELDS
	public static $order = "";
	public static $where_condition = "";
	public static $searchable = array();
	private static $request;
	private static $actionlinks;
	public static $_instance;
	public static $tableTitle;

	public static function getInstance()
	{


		if (self::$_instance === null) {
			self::$_instance = new self;
		}
		return self::$_instance;
	}

	/**
	 * Create the data output array for the DataTables rows     
	 *  @param  array $columns Column information array
	 *  @param  array $data    Data from the SQL get
	 *  @return array          Formatted data in a row based format
	 */
	public static function data_output($data)
	{
		$out = array();
		for ($i = 0, $ien = count($data); $i < $ien; $i++) {

			$row = array();

			for ($j = 0, $jen = (count(self::$columns)); $j < $jen; $j++) { // add 1 because we want to add the checkbox column data

				$column = self::$columns[$j];

				// checkbox first
				// first position is taken
				if ($j == 0) {

					$formId = self::$request['frmid'];
					$dataValue = $data[$i][self::$columns[$j]['db'] ?? ''];
					$ajaxDataDiv = self::$request['ajaxdatadiv'];

					$function = sprintf("showValues('%s','%s','%s','','load.php','%s','',false)", $formId, $ajaxDataDiv, 'edit', $dataValue);

					// usually the first column is the identity column
					$row[0] = sprintf(
						'<input type="checkbox" class="row-checkbox" value="%s" onClick="if (this.checked) { %s; }">',
						htmlspecialchars($dataValue, ENT_QUOTES),
						htmlspecialchars($function, ENT_QUOTES)
					);

					$function  = sprintf("<a href='#' onClick=\"showValues('%s','%s','%s','','load.php','%s','',true)\"><img src='images/icons/trash.png' title='Delete' ></a>", $formId, $ajaxDataDiv, 'Delete', $dataValue);
				}

				if (isset($column['formatter'])) { // Is there a formatter?
					$row[$column['dt'] + 1] = $column['formatter']($data[$i][$column['db']], $data[$i]);
				} else {
					$row[$column['dt'] + 1] = $data[$i][(self::$columns[$j]['db'] ?? '')] ?? '';
				}

				// add link 
				if (count($row) > count(self::$columns)) {
					$row[] = $function ?? '';
				}
			}

			$out[] = $row;
		}

		return $out;
	}
	//	/**
	//	 * Database connection
	//	 *
	//	 * Obtain an PHP PDO connection from a connection details array
	//	 *
	//	 *  @param  array $conn SQL connection details. The array should have
	//	 *    the following properties
	//	 *     * host - host name
	//	 *     * db   - database name
	//	 *     * user - user name
	//	 *     * pass - user password
	//	 *  @return resource PDO connection
	//	 */
	static function db($conn)
	{
		if (is_array($conn)) {
			return self::sql_connect($conn);
		}
		return $conn;
	}

	public static function getRecordCount($query)
	{
		$results_query = Common::$connObj->SQLSelect($query);
	}

	//        $columns = array(    
	//               array( 'db' => 'checkbox', 'dt' => 0 ),
	//               array( 'db' => 'primary key', 'dt' => 1 ),
	//               array( 'db' => 'client_surname',  'dt' => 2 ),
	//               array( 'db' => 'client_firstname',   'dt' => 3 ),
	//               array( 'db' => 'client_middlename',     'dt' => 4 ))
	public static function prepareFieldList($fieldlist = array())
	{

		$nCount = 0;

		foreach ($fieldlist as $value):
			self::$columns[] = array('db' => $value, 'dt' => $nCount);
			$nCount++;
		endforeach;
	}

	//	/**
	//	 * Paging
	//	 *
	//	 * Construct the LIMIT clause for server-side processing SQL query
	//	 *
	//	 *  @param  array $request Data sent to server by DataTables
	//	 *  @param  array $columns Column information array
	//	 *  @return string SQL limit clause
	//	 */
	static function limit($request)
	{
		$limit = '';

		self::$request = $request;
		if (isset($request['start']) && $request['length'] != -1) {
			$limit = "LIMIT " . intval($request['start']) . ", " . intval($request['length']);
		}
		return $limit;
	}
	//        
	//	/**
	//	 * Ordering
	//	 *
	//	 * Construct the ORDER BY clause for server-side processing SQL query
	//	 *
	//	 *  @param  array $request Data sent to server by DataTables
	//	 *  @param  array $columns Column information array
	//	 *  @return string SQL order by clause
	//	 */
	static function order($request)
	{
		$order = '';
		if (isset($request['order']) && count($request['order'])) {
			$orderBy = array();
			$dtColumns = self::pluck(self::$columns, 'dt');
			for ($i = 0, $ien = count($request['order']); $i < $ien; $i++) {
				// Convert the column index into the column data property
				$columnIdx = intval($request['order'][$i]['column']);
				$requestColumn = $request['columns'][$columnIdx];
				$columnIdx = array_search($requestColumn['data'], $dtColumns);
				$column = self::$columns[$columnIdx];
				if ($requestColumn['orderable'] == 'true') {
					$dir = $request['order'][$i]['dir'] === 'asc' ?
						'ASC' :
						'DESC';
					$orderBy[] = '`' . $column['db'] . '` ' . $dir;
				}
			}
			if (count($orderBy)) {
				$order = 'ORDER BY ' . implode(', ', $orderBy);
			}
		}
		return $order;
	}
	//	/**
	//	 * Searching / Filtering
	//	 *
	//	 * Construct the WHERE clause for server-side processing SQL query.
	//	 *
	//	 * NOTE this does not match the built-in DataTables filtering which does it
	//	 * word by word on any field. It's possible to do here performance on large
	//	 * databases would be very poor
	//	 *
	//	 *  @param  array $request Data sent to server by DataTables
	//	 *  @param  array $columns Column information array
	//	 *  @param  array $bindings Array of values for PDO bindings, used in the
	//	 *    sql_exec() function
	//	 *  @return string SQL where clause
	//	 */
	static function filter($request, &$bindings)
	{
		$globalSearch = array();
		$columnSearch = array();
		$dtColumns = self::pluck(self::$columns, 'dt');
		if (isset($request['search']) && $request['search']['value'] != '') {
			$str = $request['search']['value'];
			for ($i = 0, $ien = count($request['columns']); $i < $ien; $i++) {
				$requestColumn = $request['columns'][$i];
				//	$columnIdx = array_search( $requestColumn['data']??'', $dtColumns );// commented 
				$columnIdx = 	$i;
				$column = self::$columns[$columnIdx];
				//	if ( $requestColumn['searchable'] == 'true' ) {
				$binding = self::bind($bindings, '%' . $str . '%', PDO::PARAM_STR);
				$globalSearch[] = "`" . $column['db'] . "` LIKE " . $binding;
				//	}
			}
		}


		// Individual column filtering
		if (isset($request['columns'])) {
			for ($i = 0, $ien = count($request['columns']); $i < $ien; $i++) {
				$requestColumn = $request['columns'][$i];
				//	$columnIdx = array_search( $requestColumn['data'], $dtColumns );
				$column = self::$columns[$columnIdx];
				$str = $request['search']['value'];
				if ($str != '') {
					$binding = self::bind($bindings, '%' . $str . '%', PDO::PARAM_STR);
					$columnSearch[] = "`" . $column['db'] . "` LIKE " . $binding;
				}
			}
		}
		// Combine the filters into a single string
		$where = '';
		if (count($globalSearch)) {
			$where = '(' . implode(' OR ', $globalSearch) . ')';
		}
		// if ( count( $columnSearch ) ) {
		// 	$where = $where === '' ?
		// 		implode(' AND ', $columnSearch) :
		// 		$where .' AND '. implode(' AND ', $columnSearch);
		// }
		//		if ( $where !== '' ) {
		//			$where = 'WHERE '.$where;
		//		}
		// if ( $where !== '' ) {

		//     if(count(self::$searchable)>0){
		//             if(self::$where_condition!=""):
		//                 self::$where_condition.=" AND ";
		//             endif;

		//            self::$where_condition .= " (".implode(" LIKE '%".$request['search']['value']."%' OR ",self::$searchable)." LIKE '%".$request['search']['value']."%')";


		//     }
		// }

		// CHECK IF THE WHERE CONDITION HAS BEEN PASSED
		if (self::$where_condition != ''):
			self::$where_condition = ' WHERE ' . self::$where_condition;
		endif;

		return $where;
	}
	//        
	//        
	//        
	//        //* Generate sql for the sortorder and fields to be sorted by
	//	static function getSortSQL() {
	//            if ($this->sortfield != ""):
	//                $sortsql = " ORDER BY " . $this->sortfield . " " . $this->sortorder . " ";
	//            else:
	//                $sortsql = "";
	//            endif;
	//
	//            return $sortsql;
	//        }
	//        
	//        
	////        # Generate the limit clause based on the page and the total number of rows
	//	 static function getLimitSQL() {
	//           
	//            
	//		# display links for the pagination
	//            
	//              
	//		$this->lastpage = ceil($this->totalrows/$this->numberof_rows_on_page);
	//
	//		# Number of link sets
	//		$this->totalsets = ceil($this->lastpage/10);
	//
	//		# This code checks that the value of $page is an integer between 1 and $lastpage
	//
	//		$this->page = (int)$this->page;
	//
	//
	//		if ($this->page < 1) {
	//                    $this->page = 1;                    
	//		} else if ($this->page > $this->lastpage) {
	//                    if($this->lastpage>=1):
	//                        $this->page = $this->lastpage-1;
	//                    endif;
	//                  //  
	//		}
	//
	//		#echo "page: ".$this->page." lastpage: ".$this->lastpage." totalrows: ".$this->totalrows;
	//		# This code will construct the LIMIT clause for the sql SELECT statement
	//		//if($this->page >1){
	//                    return 'LIMIT ' .(($this->page > 1 ? $this->page* $this->numberof_rows_on_page: 1) - 1).',' .$this->numberof_rows_on_page;
	//              //  }else{
	//              //      return 'LIMIT ' .$this->numberof_rows_on_page.',' .$this->numberof_rows_on_page;
	//             //   }
	//	}
	//        
	//        
	//	/**
	//	 * Perform the SQL queries needed for an server-side processing requested,
	//	 * utilising the helper functions of this class, limit(), order() and
	//	 * filter() among others. The returned array is ready to be encoded as JSON
	//	 * in response to an SSP request, or can be modified if needed before
	//	 * sending back to the client.
	//	 *
	//	 *  @param  array $request Data sent to server by DataTables
	//	 *  @param  array|PDO $conn PDO connection resource or connection parameters array
	//	 *  @param  string $table SQL table to query
	//	 *  @param  string $primaryKey Primary key of the table
	//	 *  @param  array $columns Column information array
	//	 *  @return array          Server-side processing response array
	//	 */
	public static function simple($request)
	{
		try {

			$bindings = array();

			self::$actionlinks = $request['actionlinks'];

			//		$db = self::db( $conn );

			// Build the SQL query string from the request
			$limit = self::limit($request, self::$columns);
			$order = self::order($request, self::$columns);
			$where = self::filter($request, $bindings);

			//		// Main query to actually get the data
			//		$data = self::sql_exec( $db, $bindings,
			//			"SELECT `".implode("`, `", self::pluck(self::$columns, 'db'))."`
			//			 FROM `$table`
			//			 $where
			//			 $order
			//			 $limit"
			//		);
			//		// Data set length after filtering
			//		$resFilterLength = self::sql_exec( $db, $bindings,
			//			"SELECT COUNT(`{$primaryKey}`)
			//			 FROM   `$table`
			//			 $where"
			//		);
			//		$recordsFiltered = $resFilterLength[0][0];
			//		// Total data set length
			//		$resTotalLength = self::sql_exec( $db,
			//			"SELECT COUNT(`{$primaryKey}`)
			//			 FROM   `$table`"
			//		);
			//		$recordsTotal = $resTotalLength[0][0];


			//   $query =  "SELECT COUNT(".self::$keyfield.") AS reccount".self::$sSQL;  

			// $results_query = $this->Conn->SQLSelect($query); 

			self::$sSQL = "SELECT " . implode(",", self::$fieldlist) . " " . self::$sSQL . " " . self::$where_condition . " " . self::$order . " " . $limit . " ; SELECT COUNT(*) AS reccount " . self::$sSQL . " " . self::$where_condition . ";";
			// echo self::$sSQL = "SELECT ".implode(",",self::$fieldlist)." ".self::$sSQL." ".self::$where_condition." ".self::$order." ".$limit." ; SELECT COUNT(".self::$keyfield.") AS reccount ".self::$sSQL." ".self::$where_condition.";";                    
			// exit();
			$results_query = Common::$connObj->SQLSelect(self::$sSQL);


			if (count($results_query) > 1):

				$recordsTotal = $results_query[1][0]['reccount'];

				$results_query1 = $results_query;

				unset($results_query);

				$data = $results_query1[0];

				unset($results_query1);


			endif;


			$recordsFiltered = $recordsTotal;


			//                $columns = array(
			//                    array("sTitle": "<input type='checkbox' id='selectAll'></input>")
			//          
			//                ); 
			// $js_array = array_map(function($item) {
			// 	return array(
			// 		'title' => $item['db']
			// 	);
			// }, self::$columns);

			$columarray = $request['columntitle'];

			foreach ($columarray as $value) {
				$js_array[] = ['title' => $value];
			}

			//Add checkbox column title
			array_unshift($js_array, array("title" => "<a href='#'>All</a>"));
			// array_unshift($js_array,array("title"=> "<a href='#'>All</a>")) ;
			$js_array[] = array("title" => "");

			//add checkbox columns
			//	array_unshift(self::$columns,array('db'=>'checkbox','dt'=>'-1')) ;	

			return array(
				"draw" => isset($request['draw']) ?
					intval($request['draw']) : 1,
				"recordsTotal"    => intval($recordsTotal),
				"recordsFiltered" => intval($recordsFiltered),
				"data"            => self::data_output($data),
				"columns" => ($js_array ?? ''),
				"caption" => self::$tableTitle ?? ""
			);
		} catch (Exception $e) {
			throw $e;
		}
	}

	//	/**
	//	 * The difference between this method and the `simple` one, is that you can
	//	 * apply additional `where` conditions to the SQL queries. These can be in
	//	 * one of two forms:
	//	 *
	//	 * * 'Result condition' - This is applied to the result set, but not the
	//	 *   overall paging information query - i.e. it will not effect the number
	//	 *   of records that a user sees they can have access to. This should be
	//	 *   used when you want apply a filtering condition that the user has sent.
	//	 * * 'All condition' - This is applied to all queries that are made and
	//	 *   reduces the number of records that the user can access. This should be
	//	 *   used in conditions where you don't want the user to ever have access to
	//	 *   particular records (for example, restricting by a login id).
	//	 *
	//	 *  @param  array $request Data sent to server by DataTables
	//	 *  @param  array|PDO $conn PDO connection resource or connection parameters array
	//	 *  @param  string $table SQL table to query
	//	 *  @param  string $primaryKey Primary key of the table
	//	 *  @param  array $columns Column information array
	//	 *  @param  string $whereResult WHERE condition to apply to the result set
	//	 *  @param  string $whereAll WHERE condition to apply to all queries
	//	 *  @return array          Server-side processing response array
	//	 */
	static function complex($request, $conn, $table, $primaryKey, $whereResult = null, $whereAll = null)
	{
		$bindings = array();
		$db = self::db($conn);
		$localWhereResult = array();
		$localWhereAll = array();
		$whereAllSql = '';
		// Build the SQL query string from the request
		$limit = self::limit($request, self::$columns);
		$order = self::order($request, self::$columns);
		$where = self::filter($request, self::$columns, $bindings);
		$whereResult = self::_flatten($whereResult);
		$whereAll = self::_flatten($whereAll);
		if ($whereResult) {
			$where = $where ?
				$where . ' AND ' . $whereResult :
				'WHERE ' . $whereResult;
		}
		if ($whereAll) {
			$where = $where ?
				$where . ' AND ' . $whereAll :
				'WHERE ' . $whereAll;
			$whereAllSql = 'WHERE ' . $whereAll;
		}
		// Main query to actually get the data
		$data = self::sql_exec(
			$db,
			$bindings,
			"SELECT `" . implode("`, `", self::pluck(self::$columns, 'db')) . "`
			 FROM `$table`
			 $where
			 $order
			 $limit"
		);
		// Data set length after filtering
		$resFilterLength = self::sql_exec(
			$db,
			$bindings,
			"SELECT COUNT(`{$primaryKey}`)
			 FROM   `$table`
			 $where"
		);
		$recordsFiltered = $resFilterLength[0][0];
		// Total data set length
		$resTotalLength = self::sql_exec(
			$db,
			$bindings,
			"SELECT COUNT(`{$primaryKey}`)
			 FROM   `$table` " .
			$whereAllSql
		);
		$recordsTotal = $resTotalLength[0][0];
		/*
		 * Output
		 */
		return array(
			"draw"            => isset($request['draw']) ?
				intval($request['draw']) :
				0,
			"recordsTotal"    => intval($recordsTotal),
			"recordsFiltered" => intval($recordsFiltered),
			"data"            => self::data_output(self::$columns, $data)
		);
	}
	/**
	 * Connect to the database
	 *
	 * @param  array $sql_details SQL server connection details array, with the
	 *   properties:
	 *     * host - host name
	 *     * db   - database name
	 *     * user - user name
	 *     * pass - user password
	 * @return resource Database connection handle
	 */
	static function sql_connect($sql_details)
	{
		try {
			$db = @new PDO(
				"mysql:host={$sql_details['host']};dbname={$sql_details['db']}",
				$sql_details['user'],
				$sql_details['pass'],
				array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION)
			);
		} catch (PDOException $e) {
			self::fatal(
				"An error occurred while connecting to the database. " .
					"The error reported by the server was: " . $e->getMessage()
			);
		}
		return $db;
	}
	/**
	 * Execute an SQL query on the database
	 *
	 * @param  resource $db  Database handler
	 * @param  array    $bindings Array of PDO binding values from bind() to be
	 *   used for safely escaping strings. Note that this can be given as the
	 *   SQL query string if no bindings are required.
	 * @param  string   $sql SQL query to execute.
	 * @return array         Result from the query (all rows)
	 */
	static function sql_exec($db, $bindings, $sql = null)
	{
		// Argument shifting
		if ($sql === null) {
			$sql = $bindings;
		}
		$stmt = $db->prepare($sql);
		//echo $sql;
		// Bind parameters
		if (is_array($bindings)) {
			for ($i = 0, $ien = count($bindings); $i < $ien; $i++) {
				$binding = $bindings[$i];
				$stmt->bindValue($binding['key'], $binding['val'], $binding['type']);
			}
		}
		// Execute
		try {
			$stmt->execute();
		} catch (PDOException $e) {
			self::fatal("An SQL error occurred: " . $e->getMessage());
		}
		// Return all
		return $stmt->fetchAll(PDO::FETCH_BOTH);
	}
	/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
	 * Internal methods
	 */
	/**
	 * Throw a fatal error.
	 *
	 * This writes out an error message in a JSON string which DataTables will
	 * see and show to the user in the browser.
	 *
	 * @param  string $msg Message to send to the client
	 */
	static function fatal($msg)
	{
		echo json_encode(array(
			"error" => $msg
		));
		exit(0);
	}
	/**
	 * Create a PDO binding key which can be used for escaping variables safely
	 * when executing a query with sql_exec()
	 *
	 * @param  array &$a    Array of bindings
	 * @param  *      $val  Value to bind
	 * @param  int    $type PDO field type
	 * @return string       Bound key to be used in the SQL where this parameter
	 *   would be used.
	 */
	static function bind(&$a, $val, $type)
	{
		$key = ':binding_' . count($a);
		$a[] = array(
			'key' => $key,
			'val' => $val,
			'type' => $type
		);
		return $key;
	}
	/**
	 * Pull a particular property from each assoc. array in a numeric array, 
	 * returning and array of the property values from each item.
	 *
	 *  @param  array  $a    Array to get data from
	 *  @param  string $prop Property to read
	 *  @return array        Array of property values
	 */
	static function pluck($a, $prop)
	{
		$out = array();
		for ($i = 0, $len = count($a); $i < $len; $i++) {
			$out[] = $a[$i][$prop];
		}
		return $out;
	}
	/**
	 * Return a string from an array or a string
	 *
	 * @param  array|string $a Array to join
	 * @param  string $join Glue for the concatenation
	 * @return string Joined string
	 */
	static function _flatten($a, $join = ' AND ')
	{
		if (! $a) {
			return '';
		} else if ($a && is_array($a)) {
			return implode($join, $a);
		}
		return $a;
	}
}
