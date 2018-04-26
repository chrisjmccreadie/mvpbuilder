<?php
/*
	Author : Chris McCreadie
	date : 4/4/2018

	This is the core model which hold all the code specific to this deployment





*/
class Core_model extends CI_Model {

	function __construct()
    {
        parent::__construct();
		
	}

	/*
	START OF HTML PROCESSING
	*/

	function htmlInput($field)
	{
		//set html buffer
		$html = '';
		//check its not excluded
        if (!in_array($field->name, $this->config->item('clientloginexcludefields'))) 
        {
          	$fieldtype = 'text';
        	switch ($field->name) 
        	{
			    case "password":
			        $fieldtype = "password";
			        break;
			    case "email":
			        $fieldtype = "email";
			        break;
			}
          //debug
          //echo $field->name;
          //check if its a password field
          //note (chris) we can extend wit the admin code in table.php to handle all the variant types but just keeping it simples for now.
          if ($fieldtype == "password")
          {
             $html = $html.ucfirst($field->name).":<br>";
             $html = $html. "<input class='formelement form-control' type='$fieldtype' name='$field->name' id='$field->name' value=''>";
             $html = $html. "<span id='error$field->name'></span><br>";
             $html = $html."Confirm ". ucfirst($field->name).":<br>";
             $html = $html. "<input class='formelement form-control' type='$fieldtype' name='confirm$field->name' id='confirm$field->name' value=''>";
             $html = $html. "<span id='error$field->name'></span><br>";

          }
          else
          {
            //get the session data
            $data = $this->session->userdata($field->name);
            //output a normal text field 
            $html = $html. ucfirst($field->name).":<br>";
            $html = $html. "<input class='formelement form-control' type='$fieldtype' name='$field->name' id='$field->name' value='$data'>";
            $html = $html. "<span id='error$field->name'></span><br>";
          }
         
        }
        return($html);
        //print_r($field->name);
    }



	/*
	END OF HTML PROCESSING
	*/


}