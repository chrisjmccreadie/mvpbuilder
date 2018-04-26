<?php
/*
	This function handles all of the ajax calls for the template system.


*/


//todo fix he menu left ths is caused in the add wizard css 
defined('BASEPATH') OR exit('No direct script access allowed');

class Clientajax extends CI_Controller {

	public function index()
	{
		echo "Go away, you";
	}

	//reset the users password
	public function resetPassword()
	{
		//get the post data
		$data = $this->input->post();
		//get the client user table
		$table = $this->config->item('clientloginable');
		//update the password
		$query = "update `$table` set `password` = '".$data['password']."', `resethash` = '' where `id` = '".$data['id']."'";
		//echo $query;
		$result = $this->generic_model->runQuery($query);
		$error = $this->db->error();
        if ($error['message'] != '') 
            echo 0;
        else
        {
			echo 1;
        }
	}

	//send out a link for a new password
	public function forgotPasssword()
	{
		//get the post data
		$data = $this->input->post();
		//decode the email
		$data['email'] = urldecode($data['email']);
		//get the table
		$table = $this->config->item('clientloginable');
		//build the query
		$query = "select * from `$table` where `email` = '".$data['email']."' and active = 1 and archived = 0";
		//run the quey
		$result = $this->generic_model->runQuery($query);
		//check we found the user
		if ($result->num_rows() != 0)
		{
			//get the email from config
        	$email = $this->config->item('emailforgotpassword');
        	//set the details
        	$subject = $email['subject'];
          	$content = $email['content'];
          	//get a hash
          	$hash = $this->generic_model->generateHash();
          	//set the reset link
			$content = str_replace("[HASH]", base_url().'resetpassword/'.$hash, $content);
			//get the id
			$ret = $result->row();
			//update the database with the hash link
			$updatequery = "update `$table` set `resethash` = '$hash' where `id` = '".$ret->id."'";
			$this->generic_model->runQuery($updatequery);
          	//send reset email
        	$this->generic_model->sendEmail($data['email'],$this->config->item('emailfrom'),$this->config->item('emailfromname'),$subject,$content);
			echo 1;
		}
		else
		{
			//user not found
			echo 0;
		}

	}

	//create user account
	public function createAccount()
	{
		//get the post data
		$data = $this->input->post();
		//get the client user table
		$table = $this->config->item('clientloginable');
		unset($data['confirmpassword']);
		//urledecode any vars you want here
		$data['email'] = urldecode($data['email']);

		//check email does not exist already.
		$query = "select * from `$table` where `email` = '".$data['email']."'";
		$result = $this->generic_model->runQuery($query);
		//if it is not 0 then it is not in the database
		if ($result->num_rows() != 0)
		{
			echo 2;
			exit;
		}
		
		//build the insert
		//todo (chris) turn this into a generic insert query
		$query = '';
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
		{
			

			//send error res
            echo 0;
		}
        else
        {
        	//get the email from config
        	$email = $this->config->item('emailsignup');
        	//set the details
        	$subject = $email['subject'];
          	$content = $email['content'];
          	//send the email
        	$this->generic_model->sendEmail($data['email'],$this->config->item('emailfrom'),$this->config->item('emailfromname'),$subject,$content);
        	//send success res
			echo 1;
        }
	}

	//update account function 
	public function updateAccount()
	{

		//get the post data
		$data = $this->input->post();
		//get the client user table
		$table = $this->config->item('clientloginable');
		//get the id
		$id = $data['id'];
		//get the password
		$password = $data['password'];
		//remove the id and password so the query can be built correctly
		unset($data['id']);
		unset($data['confirmpassword']);
		unset($data['password']);

		//urledecode any vars you want here
		$data['email'] = urldecode($data['email']);
		//create an update
		$query = $this->generic_model->updateFromValueArray($table,$data,'');
		if ($password != '')
			$query = $query." and `password` = '$password'";
		$query  = $query." where id = '".$id."'";
		$query = $this->generic_model->runQuery($query);
		//check for an error
		$error = $this->db->error();
        if ($error['message'] != '') 
            echo 0;
        else
        {
        	//update the session
        	$this->generic_model->setSession($data);
			echo 1;
        }
	}
	
	/*

	this function checks for a login

	*/
	public function checkSignin()
	{
		
		//get the details
		$password =$this->input->post('password');
		$email = $this->input->post('email');
		//get the table
		$table = $this->config->item('clientloginable');
		//get the fields
		$fields = $fields= $this->db->field_data($table = $this->config->item('clientloginable'));
		$select = "";
		$i = 0;
		//loop through them and look for the fields we care about
		foreach ($fields as $field)
        {
        	//check its not excluded
        	if (!in_array($field->name, $this->config->item('clientloginexcludefields'))) 
            {
            	//check its not a password field
            	if ($field->name != 'password')
            	{
            		//built the select
	            	if ($select == '')
	            		$select = $field->name;
	            	else
						$select = $select.",$field->name";
				}
				else
           	 		unset($fields[$i]);
            }
            else
           		unset($fields[$i]);
           	$i++;
        }
        //build the query
		$query = "select id,$select from `$table` where `email` = '$email' AND `password` = '$password' and archived = 0";
		//debug
		//echo $query;
		//get the results
		$query = $this->generic_model->runQuery($query);
		$result = $query->result();
		//check we found him
		if (count($result) > 0)
		{
			//set the user session infromation
			$this->session->clientloggedin = 1;
			//update the session
			$this->generic_model->setSession($result[0]);

			//$this->generic_model->logIt(1);
		}
		else
		{
			//the user is not valid so blank the session
			//note (chris) we could aslo unset the vars here
			$this->session->name = '';
			$this->session->email = '';
			$this->session->clientloggedin = 0;
			$this->session->id = '';

		}
		echo $this->session->clientloggedin ;
	}
}