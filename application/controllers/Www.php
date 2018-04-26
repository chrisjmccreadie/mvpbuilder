<?php

/*

	TO DO 

	fix it so a client and admin can log in at the same time
	active = 1 for all login atempts

*/


//todo fix he menu left ths is caused in the add wizard css 
defined('BASEPATH') OR exit('No direct script access allowed');

class Www extends CI_Controller {

	//over ride the default controller as we do not every function to have to have login in checking.
	public function __construct()
    {
	    parent::__construct();
	   	//get the second ulr paramter this should be table, view etc.  It comes directy after the admin.  if this is blank we are at the main admin page (one would assume)
	    $checkloginpage = $this->uri->segment(1);
	    //check that the session login is not set to 1
	    if ($this->session->clientloggedin != 1) 
		{ 
			//check one of the pages we allow 
			if (in_array($checkloginpage, $this->config->item('clientloginrequired')))
			{
    			//user is not logged in we can do stuff wiith that knowledge
				redirect('/');
				exit;
			}
		}
	}



	//main =
	public function index()
	{
		
		$this->load->view('www/home');
	}

	public function product()
	{
		$product = $this->uri->segment(2);
		$productmodified = str_replace('-', ' ', $product);
		$product = $this->generic_model->fetchProduct($productmodified);
		//print_r($product);
		$data = array(
		'product' =>  $product
		);
		$this->parser->parse('www/product', $data);

		
	}

	public function search()
	{
		$data = '';
		//check clinet management is activated
		if ($this->config->item('clientaccount') == true)
        {
        	$results = $this->generic_model->search($this->input->get('search'));
        	$data['searchresults'] = $results->result();
        	$this->load->view('www/search',$data);
        }	
        else
		{
			redirect('/');
		}
	}

	//log the user ut
	public function logout()
	{
		//log the user out
		//note (chris) this will have to be extened to use all the fiends in the login table to 100 cleanse the data
		$this->session->name = '';
		$this->session->email = '';
		$this->session->clientloggedin = 0;
		$this->session->id = '';
		redirect('/');
		exit;
	}

	//reset the user password
	public function resetpassword()
	{
		//check clinet management is activated
		if ($this->config->item('clientaccount') == true)
        {
        	//get the hash
			$hash = $this->uri->segment(2);
			//get the table
			$table = $table = $this->config->item('clientloginable');
			//build the query
			$query = "select id from `$table` where `resethash` = '$hash'";
			//run the query
			$result = $this->generic_model->runQuery($query);
			//check we found the client
	   		if ($result->num_rows() != 0)
	   		{
	   			//get the row (there should one be one email / hash is unique)
				$ret = $result->row();
				//set the id
				$data['id'] = $ret->id;
				//load the view
				$this->load->view('www/resetpassword',$data);
			}
			else
			{
				//set the id to 0 to show an error in the view
				$data['id'] = 0;
				//load the view
				$this->load->view('www/resetpassword',$data);
			}
			
		}
		else
		{
			redirect('/');
		}
		
		

		
	}

	//forgot password function
	public function forgotpassword()
	{
		if ($this->config->item('clientaccount') == true)
        {
			$this->load->view('www/forgotpassword');
		}
		else
		{
			redirect('/');
		}
	}

	//sign 
	public function signin()
	{
		if ($this->config->item('clientaccount') == true)
        {
			$this->load->view('www/signin');
		}
		else
		{
			redirect('/');
		}
	}

	//sign 
	public function signup()
	{
		if ($this->config->item('clientaccount') == true)
        {
        	//get the fields from the login table
			$data['fields'] = $fields= $this->db->field_data($table = $this->config->item('clientloginable'));
			$this->load->view('www/signup',$data);
		}
		else
		{
			redirect('/');
		}
		
	}


	//sign up 
	public function myaccount()
	{
		if ($this->config->item('clientaccount') == true)
        {
        	//debug
			//print_r($this->session);
			//get the fields from the login table
			$data['fields'] = $fields= $this->db->field_data($table = $this->config->item('clientloginable'));
			//load the view
			$this->load->view('www/myaccount',$data);
		}
		else
		{
			redirect('/');
		}
		
	}
}
