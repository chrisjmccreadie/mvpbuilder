<?php

/*

Admin class

This class handles all the administration for the simple admin system.

todo 

	Add / Edit tables
	
		add a proper WYSIWYG editor text areas
		add a nice select (with find etc)
		add all the SQL variant types datetime etc
		add look up table for extra fields required. max / min chars etc
		add inline editing

add s3 class
add memcache class
add rest methods 
add setup script
ajax loading of datatables for larger datasets (config item)
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
			//redirect to the login page
			//print_r($this->session);
			//exit;
			redirect('admin');
			exit;
		}


 
	}

	//main index file this will take us to dashboard or login
	public function index()
	{
		//$this->generic_model->logit(1);
		//exit;
		//check we have a sesssion 
		//$this->generic_model->setSession();
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

		TODO

		add excluded fields
		add insert / delete and edit options
		add fontawesome fonts for this
		auto remove any filed that is autoincrement from the edit part. 

	*/
	public function table()
	{
		//get the table
		$table = $this->uri->segment(3);

		/*

			START OF THE POST PROCESSING.

			We have a number of patterns that we are checking for 

			table_show_fields = an array that holds a filed list otherwise we just show all
			archived = if you add this to any table it will use it a persistnent delete and not show them in the admin, you can override by setting showarchived to 1 this will also affect the
					   any table deletes we do.


		*/

		//set the field list to all fields
		$fieldlist = '*';
		//loop through the show fields array (found in conifg)
		foreach ($this->config->item('table_show_fields') as $key => $value) 
		{
			//check if the table matches the current tabl
			if ($key == $table)
			{
				//found table implode the array as these are the fields we want
				$fieldlist = implode($value,",");
			}
		}

		


		//build the query
		$query = "select $fieldlist from `$table` ";
		//get the results
		$query = $this->generic_model->runQuery($query,$table,1);
		

		//store the table name for the view to process
		$data['table'] = $table;
		//store the fields for processing.
		$data['fields'] =$query->field_data();
		//store the results for the view to process.
		$data['result'] = $query->result();
		//get the assoicated datasets
		$data['foreigntabledata'] = $this->generic_model->getDataSet($table);

		//load the view
		$this->load->view('admin/table',$data);

	}


	public function addrecord()
	{
		//get the table
		$table = $this->uri->segment(3);
		//store the fields
		$data['fields'] = $this->db->field_data($table);
		//store the table name for the view to process
		$data['table'] = $table;	
		//get the assoicated datasets
		$data['foreigntabledata'] = $this->generic_model->getDataSet($table);
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
		$data['fields'] = $this->db->field_data($table);
		//store the table name for the view to process
		$data['table'] = $table;	
		//get the assoicated datasets
		$data['foreigntabledata'] = $this->generic_model->getDataSet($table);
		//load the view
		$this->load->view('admin/addeditrecord',$data);

	}
}