<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// This can be removed if you use __autoload() in config.php OR use Modular Extensions
/** @noinspection PhpIncludeInspection */
require APPPATH . 'libraries/REST_Controller.php';

class Table extends REST_Controller {

	function __construct()
    {
        // Construct the parent class
        parent::__construct();

        // Configure limits on our controller methods
        // Ensure you have created the 'limits' table and enabled 'limits' within application/config/rest.php
        /*
        $this->methods['users_get']['limit'] = 500; // 500 requests per hour per user/key
        $this->methods['users_post']['limit'] = 100; // 100 requests per hour per user/key
        $this->methods['users_delete']['limit'] = 50; // 50 requests per hour per user/key
        */
    }



    public function index_get()
    {
    	/*
    	todo (chris) 
					set limits 
					set fields
		*/
    	//get the table
    	$table = $this->input->get('table');
    	//build the query
		$query = "select * from `$table`";
		//get the results
		$query = $this->generic_model->runQuery($query,$table,1);
		//build the result
    	$message = [
            'result' => $query->result()
        ];

        $this->set_response($message, REST_Controller::HTTP_CREATED); // CREATED (201) being the HTTP response code

    }

    

}