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

		Tthis function fetches a product from the choosed product table.

		the table is set in config item under the item searchtable

	*/

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


	/*

		Tthis function preforms a search against the chosen table.

		the table is set in config item under the item searchtable


	*/

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


	/*
		this function takes a table and looks for other tables which match the field name in it.
		If it finds one it pulls in the data from that table and stores it in an key value array.

		This miminics the "natural join" pattern to link together primary foreign keys

		It has a number of constraints multipile joins etc but in most use cases a single join is enough.
		If you find it becoming myuch more complex than that then a NOSQL solution may be more apt for your
		requirements.


	*/

	function getDataSet($table,$fields)
	{
		//print_r($fields);
		//set the results array
		$dataset = array();
		//get the fields for the table
		//$fields = $this->db->field_data($table);
		//loop around the fields
		foreach ($fields as $key => $value) 
		{
    		//check if it is an int and if it is look for a matching table
    		//note (chris) we could extend this to use more than ints but this is how must primary / foreign keys work.
    		if (($value->type == "int") || ($value->type == "tinyint"))
    		{
	    		if (($value->htmltype == "lookup") && ($value->lookup != ''))
	    		{
	    			$searchterm = $value->lookup;
	    		}
	    		else
	    		{
	    			$searchterm = $value->name;
	    		}

    			//todo if there is a look up we have to build it here
    			foreach ($this->session->tables as $item)
				{
					//echo $item;
					if ($item == $searchterm )
					{
						if ($this->config->item('table_foreignkey_fields') == '')
							$thefields = '*';
						else
							$thefields = $this->config->item('table_foreignkey_fields');
						$query = "select $thefields from $item";
						//echo $query;
						//run the query
						$result = $this->runQuery($query);
						//store the result in a key value pair
						$tmpdata = array($searchterm =>$result->result());
						//add to our dataset/
						$dataset[] = $tmpdata;

					}
				}
			}
    		
		}
		//print_r($dataset);
		//exit;
		return($dataset);
	}

	/*


	this function builds the correct input type

	todo 

	required


	*/
	function buildFormElement($field,$foreigntabledata = '')
	{
		//check its not a reserved field
		if ($field->name == 'archived') 
			return('');	
		//print_r($field);
		$required = '';
		//set template var
		$template = '';
		//check the required field
		if ($field->required  != '')
			$required = 'required';

		//check if its an id field if is return a hidden field
		if ($field->name == 'id') 
		{
			//check that we have a value its an edit form so we create a hidden field
			if ($field->value != '')
			{
				$data = array("value"=>$field->value);
				$template = 'admin/formelements/hidden';
				$output = $this->parser->parse($template, $data, TRUE);
				return($output);
			}
		}
		else
		{
			//$type = $field->type;
			if ($field->htmltype != '')
			{
				$field->type = $field->htmltype ;
			}
			//echo "ll";
			//print_r($field->type);
			//echo "<br>";
			//check the type to get the correct template
			switch ($field->type) 
			{

		    	case "image":	
		    		$field->lookupdata = "";
		    		$field->htmltype ='image';
		    		$field->foreigndata = '';
		      	 	$template = 'admin/formelements/image';
		       		break;	
		       	case "lookup":	
		    		//get the table;
		    		//print_r($field);
		    		$sql = "select id,name from `$field->lookup`";
		    		$result = $this->runQuery($sql);
		    		//print_r($result->result());
		    		$field->lookupdata = $result->result();
		    		$field->htmltype ='select';
		    		$field->foreigndata = '';
		      	 	$template = 'admin/formelements/lookup';
		       		break;				
		    	case "textfield":	
		    		//echo "tf";	
		    		$field->htmltype = 'textfield';    		
		      	 	$template = 'admin/formelements/textfield';
		       		break;
		    	case "date":
		      	 	$template = 'admin/formelements/date';
		       		break;	
		    	case "int":
		    		$foreigndata = '';
            		foreach ($foreigntabledata as $item2)
            		{
            			foreach ($item2 as $key => $value) 
            			{
            				if ($key == $field->name)
            				{
            					//print_r($value);
            					$foreigndata = $value;
            				}
            			}
            			
            		}
            		if ($foreigndata == '')
            		{
		    			$field->htmltype = 'textfield';
		    			$template = 'admin/formelements/textfield';
            		}
            		else
            		{
            			$field->htmltype = 'lookup';
            			$field->lookupdata = $foreigndata;
            			$template = 'admin/formelements/lookup';
            		}

		      	 	
		       		break;	
		    	case "text":
		    		$field->htmltype = 'textfield';
		      	 	$template = 'admin/formelements/text';
		       		break;	
		    	case "wysiwyg":
		    		//print_r($field);
		    		$field->htmltype = 'textfield';
		      	 	$template = 'admin/formelements/wysiwyg';
		       		break;	
		    	case "select":
		    		$field->htmltype = 'select';
		      	 	$template = 'admin/formelements/select';
		       		break;			       				       				       				       			    
			}
			//check its not blank, if it is then it is type we do not deal with yet.
			if ($template != '')
			{

				//build the array to send to the parser
				$output = $this->parser->parse($template, $field, TRUE);
				return($output);	
			}
			else
				return('');	
		}
		return('');	
	}

	function getTableModifiers($table)
	{
		//get the modified fields
		$modifiedfields = $this->db->field_data("tablemodifier");
		//set a field holdet for the select
		$fieldselect = '';
		//set a counter
		$i = 0;
		//loop and remove the ones we no longer care about
		foreach ($modifiedfields as $key => $value)
		{
			//check if we want to get rid of it
			//note (chris) we could move this into config to set these tabled we control so not 100% necessary default	primary_key
			if ( ($value->name == 'field') || ($value->name == 'active') || ($value->name == 'table') )
			{
				unset($modifiedfields[$i]);
			}
			else
			{
				if ($fieldselect == '')
					$fieldselect = $value->name;
				else
					$fieldselect = $fieldselect.','.$value->name;
			}
			//increment it
			$i++;
		}
		

		//get the the fields
		$fields = $this->db->field_data($table);
		//print_r($fields);
		//set a counter
		$i = 0;
		//loop around the fileds
		foreach ($fields as $field)
		{
			//note (chris) should we be removing active etc.
			//check its a field we care about
			//if (($field->name != 'active') && ($field->name != 'archived'))
			//if (($field->name != 'id') && ($field->name != 'active') && ($field->name != 'archived') || ($value->name == 'table') )
			//if ($value->name == 'table') 
			//{	
				$sql = "select $fieldselect from `tablemodifier` where `table` = '$table' and `field` = '".$field->name."' and `active` = 1";	
				//denug
				//echo $sql;		
				//run the sql
				$result = $this->runQuery($sql);
				//check its in the modifier table
				$ret = '';
				if ($result->num_rows() != 0)
				{
					//get the row
					$ret = $result->row();
					//debug
					//print_r($ret);
					//loop through the remaining fields and add them
					//note (chris) this may seem a a little veborse but it means we can extend this table for ever without ever having to
					//worry about updating this code.
					foreach ($ret as $key => $value)
					{
						//set the field
						$fields[$i]->$key = $value;
					} 
					
				}
				else
				{
					//we did not find it in the modifier table so just add the fields
					foreach ($modifiedfields as $key => $value)
					{
						//set the name
						$name = $value->name;
						//it is not found so we have to chehck the type and set it accordingly they reason we do not have to do this above is because it has a vlaue and
						//we can infer from that.
						//note (chris) turned this off until I can test the GUI set to blank as theis will mean it is not being used.
						/*
						if ($value->type == 'int')
							$fields[$i]->$name = 0;
						else
							$fields[$i]->$name = '';
						*/
						$fields[$i]->$name = '';						
						
					} 
					//$fields[$i]->isrequired = 0;
				}
				

			//}
			//else
			//{
				//get rid of it is id,active or archived field
			//	unset($fields[$i]);
			//}
			$i++;


		}

		return($fields);

	}

	//this function gets the data from the dataset function above
	//note (chris) may not be required will see when I implement it.
	/*
	function getForeignKeyData($data)
	{
		
	}
	*/

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