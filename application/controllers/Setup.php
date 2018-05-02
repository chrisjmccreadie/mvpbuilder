<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Setup extends CI_Controller {

	public function index()
	{
		//test database 
		$fields= $this->db->field_data('log');
		$error = $this->db->error();
        if ($error['message'] != '')
        {
            echo "Database credenetials incorrect please check application/config/database.php";
            print_r($error);
        }
        else
			echo "Database check passed";

		//test the config
		if ($this->config->item('title') == '')
		{
			echo "<br>Title not set please set in application/config/config.php";
		}
		else
		{
			echo "<br>Title :".$this->config->item('title');
		}

		//check for filestack
		if ($this->config->item('filestackapikey') == '')
		{
			echo "<br>Filestack API key not set please set in application/config/config.php";
		}
		else
		{
			echo "<br>Filestack API key set :".$this->config->item('filestackapikey');
		}



	}
}
?>
