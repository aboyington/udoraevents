<?php
if(!defined("INCLUDE_ROOT"))
   include_once("../../include/config.php");
include_once(INCLUDE_ROOT . "db.php");
include_once(INCLUDE_ROOT . "bll/utility.php");
class ga_users_bll {

    private $tableName = "ga_users";
	
	function add($fields, $queryanalysis = false)
	{
		$db = new DB;
		return $db->Insert($this->tableName, $fields, true, $queryanalysis);
	}
	
	function update($fields, $filters)
    {
	    $db = new DB;
		$mid = 0;
		$queryanalysis = false; // for query analysis & debuging
		$mid = $db->Update($this->tableName, $fields, $filters, $queryanalysis); 
		return true;
    }
		
	function remove($id){
		$db = new DB;
		return $db->Delete($this->tableName, array('id' => $id));
	}

	function fetch_values($fields, $isadmin, $filters, $ismultiple = false, $queryonly=false)
    {
	    $db = new DB;
		$bind = $db->prepareBinds(NULL, $filters);
		if($fields == "")
		   $fields = "*"; // default fields
		   
		$query = "SELECT " . $fields . " from ".$this->tableName;
		$query .= $db->prepareFilters($filters);
		if(!$queryonly)
		{			
			$rec = $db->smartQuery(array(
			'sql' => $query,
			'par' => $bind,
			'ret' => 'obj'
			 ));
			 if(!$ismultiple)
			     return $rec->fetch(PDO::FETCH_OBJ);
		     else
			 {
				 $records = array();
				 while($r = $rec->fetch(PDO::FETCH_OBJ))
				 {	 
					 $records[] = $r;
				 }
				 return $records;
			 }
		}
		else
		{
			return $bind;
		}
	}

    function check($filters)
    {
		$db = new DB();
        $output = $db->Check($this->tableName, $filters);
		if($output > 0)
		  return true;
		else
		  return false;
    }
    
    function fetch_records($entity, $queryonly = false)
	{
		$db = new DB();
		$startindex = ($entity->pagenumber - 1) * $entity->pagesize;
				
        $logic = $this->filter_logic($entity);
        if($entity->fields == "")
		   $fields = "*"; // default fields
	    $query = "select " . $fields . " from " . $this->tableName . " as u inner join ga_user_levels as l on l.userid=u.userid " . $logic;
		$query .= " order by " . $entity->order;
		if(!$entity->loadall)
			$query .= " LIMIT " . $startindex . "," . $entity->pagesize;

		if(!$queryonly)
		{
			$db = new DB;
			$rec = $db->smartQuery(array(
			'sql' => $query,
			'par' => $this->bindsearchparams($entity),
			'ret' => 'obj'
			 ));
			 
			 $records = array();
			 while($r = $rec->fetch(PDO::FETCH_OBJ))
			 {	 
				 $records[] = $r;
			 }
			 return $records;
		}
		else
		{
		   return $query;
		}
    }

    // non cache version of count script
    function count_records($entity, $queryonly = false)
	{      
        $logic = $this->filter_logic($entity);
       
	    $query = "SELECT count(*) as total from ".$this->tableName." ".$logic;
      	
        if(!$queryonly)
		{
			$db = new DB;
			$total = $db->smartQuery(array(
			'sql' => $query,
			'par' => $this->bindsearchparams($entity),
			'ret' => 'col'
			 ));
			return $total;
		}
		else
		{
			// for query analysis purpose
			return $query;
		}
		
    }
      
	// core filter logic
    function filter_logic($entity)
    {
        $filters = array();
		if($entity->userid > 0)
		    $filters[] = " u.userid=:userid";
		
		$script = "";
        if(count($filters) > 0)
			$script .= ' WHERE ';
		
		$util = new utility();
		$script .=  implode(' AND ', $filters);
	  
		if($util->endsWith(trim($script),"WHERE"))
            $script = substr($script, $util->lastIndexOf($script,"WHERE") + 5) . ' ';
        return $script;
    }

    function bindsearchparams($entity)
    {
        $arr = array();
		if(isset($entity->userid))
		{
		  if($entity->userid > 0)
		   $arr['userid'] = $entity->userid;
		}
		
		
        return $arr;
    }
}

?>