<?php


//todo fix he menu left ths is caused in the add wizard css 
defined('BASEPATH') OR exit('No direct script access allowed');

class Main extends CI_Controller {

	public function index()
	{
		//build the query
		$query = "select * from `bonushunt` where activeHunt = '1'";
		//get the active bonus hunt
		$query = $this->generic_model->runQuery($query);
		//turn it into an object to make it a little easier to work with. 
		print_r($query->result());
		//convert the timestamp
		$result->dateStarted = $this->generic_model->timestampToTime($result->dateStarted);
		$data['result'] = $result;
		$this->load->view('main',$data);
	}
}
