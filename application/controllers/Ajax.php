<?php
/*
	This function handles all of the ajax calls for the template system.


*/


//todo fix he menu left ths is caused in the add wizard css 
defined('BASEPATH') OR exit('No direct script access allowed');

class Ajax extends CI_Controller {

	public function index()
	{
		echo "Go away, you";
	}

	/*

	Thid function deleted or marks a record as archived (if archived field exists)
	*/
	public function deleterecord()
	{
		//get the post data
		$id = $this->input->post('id');
		$table = $this->input->post('table');
		$fields= $this->db->field_data($table);
		//todo (chris) check if it will impact other tables

		$archiveit = 0;
		//check for archieved
		foreach ($fields as $item)
        {
        	if ($item->name == 'archived')
        		$archiveit = 1;

        }


        if ($archiveit == 1)
        {
        	//update it
        	$query = "UPDATE `$table` SET `archived` = '1' WHERE `id` = $id";
        }
        else
        {
        	//delete it
        	$query = "DELETE FROM `$table` WHERE `id` = $id";
        }
        $result = $this->generic_model->runQuery($query);
        //todo (chris) make the udpate in admin.js show error if it as a 0 returned
        $error = $this->db->error();
        if ($error['message'] != '') 
            echo 0;
        else
			echo $id;
		
	}

	/*

	This funciton will updated an edited record
	note (chris) editrecord is techincally incorrect as we are updating it but it is from the edit section so it flows correctly with ths name
	happy to change it though if it causes outrage.

	todo (chris) we could refactor the edit and add record into one function.
	*/
	public function editrecord()
	{
		//get the post data
		$data = $this->input->post();
		//get the table
		$table = $this->input->post('table');
		//get the id
		$id = $data['id'];
		//remove it from the vars
		unset($data['id']);
		//remove the table from the array
		unset($data['table']);
		//create an update
		$query = $this->generic_model->updateFromValueArray($table,$data,0,$id);
		//run the query
		$query = $this->generic_model->runQuery($query);
		//check for sql errors 
		$error = $this->db->error();
		if ($error['message'] != '') 
            echo 0;
        else
			echo 1;
	}

	/*

	this function adds a record to the database.

	*/
	public function addrecord()
	{
		//get the post data
		$data = $this->input->post();
		//set the table
		$table = $this->input->post('table');
		//remove the table from the array
		unset($data['table']);
		//set a blak query
		$query = '';
		//loop through all the fields to build the query
		//todo (chris) replace this with a generic query when it is used more than once
		foreach ($data as $key => $value)
		{
			//check if it is the first pass as we do not want to add a , if it is not
			if ($query == '')
			{
				$query = "INSERT INTO `$table` (";

				//`id`, `name`, `dateStarted`, `dateEnded`, `StartAmount`, `EndAmount`, `activeHunt`, `archived`, `description`) VALUES (NULL, '', '', '', '0', '0', '0', '0', '');"
				$query = $query." `$key`";
			}
			else
				$query = $query." , `$key`";
		}
		//add the next part of the query
		$query = $query.') values (';
		//do it again and get the values
		$values = '';
		foreach ($data as $key => $value)
		{
			$value = urldecode($value);
			if ($values == '')
				$values = $values."  '$value'";
			else
				$values = $values." , '$value'";
		}
		$query = $query.$values.')';
		//insert the record
		$query = $this->generic_model->runQuery($query);
		//make it check for sql errors 
		$error = $this->db->error();
		if ($error['message'] != '') 
            echo 0;
        else
			echo 1;

	}

	/*

	this function checks for a login

	*/
	public function checkLogin()
	{
		
		//get the details
		$password =$this->input->post('password');
		$email = $this->input->post('email');
		//build the jquery
		$query = "select * from `user` where `email` = '$email' AND `password` = '$password' and archived = 0";
		//get the results
		$query = $this->generic_model->runQuery($query);
		$result = $query->result();
		//check we found him
		//echo count($result);
		if (count($result) > 0)
		{
			//set the user session infromation
			$this->session->loggedin = 1;
			$this->session->caninsert = $result[0]->caninsert;
			$this->session->candelete = $result[0]->candelete;
			$this->session->canedit = $result[0]->canedit;
			$this->session->name = $result[0]->name;
			$this->session->canviewtables = $result[0]->canviewtables;
			$this->session->email = $result[0]->email;
			$this->session->id = $result[0]->id;
			//get all the tables
			$tables = $this->db->list_tables();
			$i = 0;
			foreach ($tables as $item)
			{
				//exlcude the ones we do not want to deal with
				if (in_array($item, $this->config->item('admin_exclude_table_list'))) 
				{
	    			unset($tables[$i]);
				}
				$i++;
				
			}
			//set the tables array
			$this->session->tables = $tables;
			$this->generic_model->logIt(1);

			

		}
		else
		{
			//the user is not valid so blank the session
			//note (chris) we could aslo unset the vars here
			$this->session->caninsert =0;
			$this->session->candelete = 0;
			$this->session->canedit =0;
			$this->session->name = '';
			$this->session->email = '';
			$this->session->canviewtables = 0;
			$this->session->loggedin = 0;
			$this->session->tables = '';
			$this->session->foreigntabledata = '';
			$this->session->id = '';

		}
		echo $this->session->loggedin ;
	}
}
