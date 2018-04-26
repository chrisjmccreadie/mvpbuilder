<?php
/*
	Author : Chris McCreadie
	date : 4/4/2018

	This is a generic model which hold all the reusuable code for the framework.

*/
class Generic_model extends CI_Model {

	function __construct()
    {
        parent::__construct();

	}

	function fetchProduct($product)
	{
		//echo $this->config->item('searchtable');
		$table = $this->config->item('searchtable');
		//get the fields and check if it has an active and / or archived
		$active = '';
		$archived = '';
		$fields = '*';
		foreach ($this->db->list_fields($table) as $item)
		{
			//note (chris) do we need this additional check as in theroy the can have the name of a archived product?
			if ($item == "active")
				$active = "and active = 1";
			if ($item == "archived")
				$archived = "and archived = 0";	

			//build the field list and remove and active and archived from it
			if (($item != 'active') && ($item != 'archived'))
			{
				if ($fields == '*')
					$fields = $item;
				else
					$fields = $fields.','.$item;
			}


		}
		//run the query
		$query = "select $fields from `$table` where `name` = '$product' $active $archived";
		$result = $this->runQuery($query);
		return($result->result());

	}

	function search($search)
	{
		//echo $this->config->item('searchtable');
		$table = $this->config->item('searchtable');
		//get the fields and check if it has an active and / or archived
		$active = '';
		$archived = '';
		foreach ($this->db->list_fields($tabl) as $item)
		{
			if ($item == "active")
				$active = "and active = 1";
			if ($item == "archived")
				$archived = "and archived = 0";			
		}
		
		//build the search paramaters
		$paramaters = '';
		foreach ($this->config->item('searchfields') as $field)
		{
			if ($paramaters == '')
				$paramaters = $paramaters." `$field` like '%$search%'";
			else
				$paramaters = $paramaters." and `$field` like '%$search%'";

			
		}

		$fields = '*';
		foreach ($this->config->item('searchdisplayfields') as $field)
		{
			if ($fields == '*')
				$fields = "`$field`";
			else
				$fields = $fields.",`$field`";

			
		}
		
		$query = "select $fields from `$table` where ".$paramaters.$active.$archived;
		//echo $query;
		$result = $this->runQuery($query);
		return($result);

	}

	function generateHash()
	{
		$this->load->helper('string');
		return(random_string('alnum', 16));
	}

	/*
		This is a simple logging function it stores a list of informaiton in a table called log.  Extend this as you see fit.

		Note tis can be turned fom the config file by setting logging to 0

		code = the code to set the description (description is optional)
		description = the description (derived from the switch)
		sessionid = the id of the user who preformed the action
		time = the time action was done

	*/
	function logIt($code,$command = '')
	{
		

		if ($this->config->item('logging') == 1)
		{
			$time = time();
			$description = '';
			switch ($code) {
		    case 1:
		        $description = "User logged in";
		        break;
			case 2:
		        $description = "User logged out";
		        break;		        
	
			}
			$sessionid= $this->session->id;
			if ($sessionid == '')
				$sessionid = 0;
			
			
			$query  = "INSERT INTO `log` ( `code`, `description`, `command`, `userid`,`timelogged`) VALUES ( $code, '$description', '$command', $sessionid,$time)";
			
			$query = $this->runQuery($query);

		}
	}


	
	/*
	START OF SQL PROCESSING
	*/


	/*
		this function takes a table and looks for other tables which match the field name in it.
		If it finds one it pulls in the data from that table and stores it in an key value array.

		This miminics the "natural join" pattern to link together primary foreign keys

		It has a number of constraints multipile joins etc but in most use cases a single join is enough.
		If you find it becoming myuch more complex than that then a NOSQL solution may be more apt for your
		requirements.


	*/

	function getDataSet($table)
	{
		//set the results array
		$dataset = array();
		//get the fields for the table
		$fields = $this->db->field_data($table);
		//loop around the fields
		foreach ($fields as $key => $value) 
		{
    		//check if it is an int and if it is look for a matching table
    		//note (chris) we could extend this to use more than ints but this is how must primary / foreign keys work.
    		if ($value->type == "int")
    		{
    			//get the table list.
    			foreach ($this->session->tables as $item)
				{
					//check if the field matched the table name
					if ($item == $value->name)
					{
						//get the data
						//note (chris) we could further filter here limits, wheres etc.

						if ($this->config->item('table_foreignkey_fields') == '')
							$thefields = '*';
						else
							$thefields = $this->config->item('table_foreignkey_fields');
						$query = "select $thefields from $item";
						//run the query
						$result = $this->runQuery($query);
						//store the result in a key value pair
						$tmpdata = array($value->name=>$result->result());
						//add to our dataset/
						$dataset[] = $tmpdata;
					}
				}
    		}
		}
		return($dataset);
	}

	//this function gets the data from the dataset function above
	//note (chris) may not be required will see when I implement it.
	function getForeignKeyData($data)
	{

	}

	//run a basic sql query
	function runQuery($sql,$table='',$archived = 0,$limit='')
	{
		if ($archived == 1)
		{
			
			//get and loop through the fields looking for an archive
			foreach ($this->db->list_fields($table) as $item)
			{
				//check if its called archived
				if ($item == 'archived')
				{
					//add the query
					if(stristr($sql, 'where') === FALSE) 
		    			$sql = $sql.' where archived = 0';
		    		else
						$sql = $sql.' and archived = 0' ;	
				}
			}
			
		}
		$sql = $sql.$limit;
		//this function runs the SQL and passed back an array
		$query = $this->db->query($sql);
		return($query);
	}

	function updateFromValueArray($table,$data,$id = '')
	{
		$query = '';
		//loop through each array element
		foreach ($data as $key => $value)
		{
			//check if it is the first pass as we do not want to add a , if it is not
			if ($query == '')
			{
				$query = "UPDATE `$table` set ";
				$query = $query." `$key` = '$value'";
			}
			else
				$query = $query." , `$key` = '$value'";
		}
		if ($id != '')
			$query  = $query." where id = '".$id."'";
		return($query);
	}


	/*
	END OF SQL PROCESSING
	*/
	function clearSession()
	{
		//note (chris) may not be required
		/*
		$session = $this->session->all_userdata();
		foreach ($session as $key => $value)
		{
			//echo $key.'<br>';
			if ($key != '__ci_last_regenerate')
			{

			}
		}
		*/
		$this->session->sess_destroy();

	}

	//set the session array based on a array of key value pairs
	function setSession($data)
	{
		foreach ($data as $key => $value)
		{

			$this->session->$key = $value;
		}
	}

	/*
	START OF TIME PROCESSING
	*/



	/*
	This function takes a unixttimestamp and converts in into human readable time. 
	Note this does not check for bad timestamps, we could make it do this but we should only hold someones hand so much, let them eat the dirt so to say.

	$timestamp = the unixtimestamp
	$formatOverride = If you wan to use a custom format set it here 
	$format = default formats(most people view time the same most of the time after all so lets cover these, defaults to 1 

	//usage

	$this->generic_model->timestampToTime("4444443242349");
	$this->generic_model->timestampToTime("4444443242349","Y-m-d H");
	$this->generic_model->timestampToTime("4444443242349","",1);

	*/
	function timestampToTime($timestamp,$formatOverride = '',$format = 1)
	{
		//check if the function has been overriden
		if ($formatOverride != '')
		{
			$displaytype = $formatOverride ;
		}
		else
		{
			//check the format types and set it.
			switch ($format) 
			{
		    	case 1:
		      	 	$displaytype = "Y-m-d H:i:s";
		       		break;
		    	default:
      				$displaytype = "Y-m-d H:i:s";
			}
		}
		//conver it
		return(date($displaytype,$timestamp));

	}


	/*
	END OF TIME PROCESSING
	*/

	function sendEmail($to,$from,$fromname,$subject,$content)
	{
		
		$this->load->library('email');
		$this->email->initialize($this->config->item('sendgridsettings'));

		$this->email->from($from, $fromname);
		$this->email->to($to);
		$this->email->subject($subject);
		$this->email->message($content);
		$this->email->send();
		//echo "Emailed :".$to."<br>";
	}

}