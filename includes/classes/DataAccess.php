<?php
		
	class TheBursarDataAccess{		
		
		private $link = "db_link";
		private $tables = "";
		private $parameters = "";
		public $data = array();
		private $action = "";
		private $cinnerjoin = "";
		private $cleftjoin  = "";			
		private $orderby = "";
		private $groupby = "";
		private $commadelimitedfieldlist = "";
		public  $num_of_rows = 0;
		
		//$ctables; the tables from which to select
		//$commadelimitedfieldlist: filed list if empty all fields will be selected
		//$caction: Operation can de delete, update, select
		//$parameters: where clause
		//$cinnerjoin; inner join
		//$cleftjoin: left join
		//$orderby: order by
		//$groupby; group by
		function setvars($ctables,$commadelimitedfieldlist,$caction,$parameters='',$cinnerjoin='',$cleftjoin='', $orderby='',$groupby=''){		
			
			$this->tables = $ctables;
			$this->parameters = $parameters;		
			$this->action = $caction;
			$this->cinnerjoin = $cinnerjoin;
			$this->cleftjoin  = $cleftjoin;			
			$this->orderby = $orderby;
			$this->groupby = $groupby;
			$this->commadelimitedfieldlist = $commadelimitedfieldlist;
			
			//$this->tep_db_perform();
		}		
		
		 function tep_db_perform() {
	  	  
		  # clears data from this just in case is is initialised and reused
		  $this->ClearData();
		  
		  switch($this->action){		  	
		
		  	case 'SELECT':
			
			 $query = 'SELECT ';
			 
			 if($this->commadelimitedfieldlist!=''){
			 	$query .=$this->commadelimitedfieldlist;					
			 }else{
			 	$query .='*';	
			 }
			 
			 $query .=' FROM '.$this->tables;
			 
			
			 if($this->cinnerjoin!=''){
			 	$query .=$this->cinnerjoin;					
			 }
			 
			 if($this->cleftjoin!=''){
			 	$query .=$this->cleftjoin;					
			 }
			 			 
			 if($this->parameters!=''){
			 	$query .=$this->parameters;					
			 }
			 
			  if($this->orderby!=''){
			 	$query .=' ORDER BY '.$this->orderby;					
			  }
			  
			   if($this->groupby!=''){
			 	$query .=' GROUP BY '.$this->groupby;					
			  }	
		
			  break;
			
		    case 'INSERT':
			
		      $query = 'insert into ' .$this->tables . ' (';
		      while (list($columns, ) = each($data)) {
		        $query .= $columns . ', ';
		      }
		      $query = substr($query, 0, -2) . ') values (';
		      reset($data);
		      while (list(, $value) = each($data)) {
		        switch ((string)$value) {
		          case 'now()':
		            $query .= 'now(), ';
		            break;
		          case 'null':
		            $query .= 'null, ';
		            break;
		          default:
		            $query .= '\'' . tep_db_input($value) . '\', ';
		            break;
		        }
		      }
		      
			  $query = substr($query, 0, -2) . ')';
			  break;
			  
		    case 'UPDATE':
		      $query = 'update ' .$this->tables . ' set ';
		      while (list($columns, $value) = each($data)) {
		        switch ((string)$value) {
		          case 'now()':
		            $query .= $columns . ' = now(), ';
		            break;
		          case 'null':
		            $query .= $columns .= ' = null, ';
		            break;
		          default:
		            $query .= $columns . ' = \'' . tep_db_input($value) . '\', ';
		            break;
		        }
		      }
			  
		      $query = substr($query, 0, -2) . ' where ' . $parameters;
			  
			  break;
		  
			
			case 'DELETE':
		      $query = 'delete FROM ' .$this->tables . ' set ';
		      while (list($columns, $value) = each($data)) {
		        switch ((string)$value) {
		          case 'now()':
		            $query .= $columns . ' = now(), ';
		            break;
		          case 'null':
		            $query .= $columns .= ' = null, ';
		            break;
		          default:
		            $query .= $columns . ' = \'' . tep_db_input($value) . '\', ';
		            break;
		        }
		      }
			  
		      $query = substr($query, 0, -2) . ' where ' . $parameters;
			  
			  break;
		    }
			
			//echo $query;
		   $results = tep_db_query($query);
		   
		   $this->num_of_rows = mysqli_num_rows($results);		 
			
			$all = array();
		
			while ($all[] = mysqli_fetch_assoc($results)) {}
			 array_pop($all);	
			 
			 foreach($all as $key=>$val){
			
				$this->data[$key]= $val;
		
			 }	
			
			return ($this->data);
	  }
	  
	  private function ClearData() {
       	$this->data = array();
   	  }
}
?>