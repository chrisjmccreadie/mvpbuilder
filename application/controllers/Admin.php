<?php

/*

Admin class

This class handles all the administration for the MVP admin system.


*/



defined('BASEPATH') OR exit('No direct script access allowed');

class Admin extends CI_Controller {

	//over ride the default controller as we do not every function to have to have login in checking.
	public function __construct()
    {
	    parent::__construct();
	    //check if we are logged out.

	    //get the second ulr paramter this should be table, view etc.  It comes directy after the admin.  if this is blank we are at the main admin page (one would assume)
	    $checkloginpage = $this->uri->segment(2);

	    //check that the session login var is not one and we are not in the login in page
	    if (($this->session->loggedin != 1) && ($checkloginpage != ''))
		{
			//print_r($this->session);
			//exit;
			//redirect to the login page	
			redirect('admin');
			exit;
		}


 
	}

	//main index file this will take us to dashboard or login
	public function index()
	{
		//print_r($this->session);

		if ($this->session->loggedin == 1)
		{
			$this->load->view('admin/dashboard');
		}
		else
		{
			$this->load->view('admin/login');
		}
		
	}

	//the main dashboard for the 
	public function dashboard()
	{
		//print_r($this->session);
		//exit;
		$this->load->view('admin/dashboard');
	}


	//this function handles the logout
	public function logout()
	{
		$this->generic_model->clearSession();
		//log it
		$this->generic_model->logIt(2);
		redirect('admin');

	}

	/*
		This funciton gets a table and renders out the results for administation

	*/
	public function table()
	{
		//get the table
		$data['table'] = $this->uri->segment(3);

		/*

			START OF THE POST PROCESSING.

			We have a number of patterns that we are checking for 

			table_show_fields = an array that holds a filed list otherwise we just show all
			archived = if you add this to any table it will use it a persistnent delete and not show them in the admin, you can override by setting showarchived to 1 this will also affect the
					   any table deletes we do.


		*/
		//set fieldlist var
		$fieldlist = '';
		//store the fields for processing.
		$fields = $this->generic_model->getTableModifiers($data['table']);
		$i=0;
		foreach ($fields as $field)
		{
			//check if we are hiding it
			//print_r($field);
			if (($field->hideview == 0) || ($field->name == 'id'))
			{
				//build the list
				if ($fieldlist == '' )
					 $fieldlist = $field->name;
				else
					$fieldlist = $fieldlist.','.$field->name;

			}
			else
			{
				//remove it
				unset($fields[$i]);
			}
			$i++;
		}
		$data['fields'] = $fields;
		//print_r($data['fields']);
		//echo $fieldlist;
		//exit;
		//build the query
		$query = "select $fieldlist from `".$data['table']."` ";
		//echo $query;
		//get the results
		$query = $this->generic_model->runQuery($query,$data['table'],1);
		//remove any archived and active fields
		//print_r( $query->result());
		//store the results for the view to process.
		$data['result'] = $query->result();
		//get the assoicated datasets
		$data['foreigntabledata'] = $this->generic_model->getDataSet($data['table'],$data['fields']);
		//load the view
		$this->load->view('admin/table',$data);

	}

	public function tableadmin()
	{
		//get all the tables
		$data['alltables'] = $this->db->list_tables();
		//get the table
		$data['table'] = $this->uri->segment(3);
		//get the fields with modifiers
		$data['fields'] = $this->generic_model->getTableModifiers($data['table']);
		//load the view
		$this->load->view('admin/tableadmin',$data);
		//debug
		/*
		foreach ($fields as $field)
		{
			$fieldoutput = $this->generic_model->buildFormElement($field);
			echo $fieldoutput;
			//exit;
		}
		*/

	}


	public function addrecord()
	{
		//get the table
		$data['table'] = $this->uri->segment(3);
		//get the fields with modifiers

		$data['fields'] = $this->generic_model->getTableModifiers($data['table']);
		//print_r($data['fields'] );

			
		//get the assoicated datasets
		$data['foreigntabledata'] = $this->generic_model->getDataSet($data['table'],$data['fields']);
		//set the result to blank as its add add
		$data['recordresult'] = '';		
		//load the view
		$this->load->view('admin/addeditrecord',$data);

	}


	public function editrecord()
	{
		//get the table
		$table = $this->uri->segment(3);
		$id = $this->uri->segment(5);
		//build the query
		$query = "select * from `$table` where `id` ='$id' ";
		//get the results
		$result = $this->generic_model->runQuery($query);
		$result = $result->result();
		$result = (object) $result[0];
		$data['recordresult'] = $result;

		//store the fields
		$data['fields'] = $this->generic_model->getTableModifiers($table);
		//store the table name for the view to process
		$data['table'] = $table;	
		//get the assoicated datasets
		$data['foreigntabledata'] = $this->generic_model->getDataSet($table,$data['fields'] );
		//load the view
		$this->load->view('admin/addeditrecord',$data);

	}
}