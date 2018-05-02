<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Setup extends CI_Controller {



	public function index()
	{
		//when you are happy with setup unghost below or delete this file.
		//exit;
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

		//check receptcha
		if (($this->config->item('recaptchakey') == '') && ($this->config->item('recaptcha') == 1))
		{
			echo "<br>Recaptcha API key not set please set in application/config/config.php";
		}
		else
		{
			echo "<br>Recaptcha not being used";
			
		}

		$sendgrid =  $this->config->item('sendgridsettings');

		if ($sendgrid['smtp_user'] == '')
			echo "<br>Sendgrid user not set";
		if ($sendgrid['smtp_pass'] == '')
			echo "<br>Sendgrid password not set";

		if ( $this->config->item('emailfrom')== '')
			echo "<br>Email from user not set";
		if ( $this->config->item('emailfromname') == '')
			echo "<br>Email from name password not set";
		
		if ( $this->config->item('clientaccount')== '')
			echo "<br>Client Account disabled ";
		else
			echo "<br>Client Account enabled ";

		if ( $this->config->item('search') == true)
		{
			echo "<br>Search enabled ";
			if ($this->config->item('searchtable') == '')
			{
				echo '<br>No search table set';
			}
			else
			{
				echo '<br>search table set:'+$this->config->item('searchtable');
			}
		}



	}
}
?>
