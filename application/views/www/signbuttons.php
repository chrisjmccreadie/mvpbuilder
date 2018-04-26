
 <?php
/*
	this inclue is used for 

	signup.php
	signin.php
	myaccount.php
	forgotpassword.php
	resetpassword.php

	it shows the correct buttons and if recaptcha is to be shown.

	If you want more advaned use cases for any of these forms then just remove the include and do the code in the actual form above




*/

 		//check for recaptcha
    	if ($this->config->item('recaptcha') == 1)
    	{
    ?>

   	 <div class="g-recaptcha" data-sitekey="<?php echo $this->config->item('recaptchakey');?>"></div>

<?php
	}
?> 

<?php
	//check for reset
	if ($reset == 1)
	{
?>
   <button class="form-control" type="button" name="forgotpasswordbutton" id="forgotpasswordbutton">Reset</button>
<?php
	}
?>

<?php
	//check for update
	if ($update == 1)
	{
?>
 <button class="form-control" type="button" name="myaccount" id="myaccount">Update</button>
<?php
	}
?>

<?php
	//check for signin
	if ($signin == 1)
	{
?>
<button class="form-control" type="button" name="clientsignin" id="clientsignin">Signin</button>
<?php
	}
?>

<?php
	//check for forgot password
	if ($forgotpassword == 1)
	{
?>
<br>
<a href="<?php echo base_url();?>forgotpassword">Forgot Password?</a>
<br>Or<br>
<?php
	}
?>

<?php
	//check for sign up
	if ($signup == 1)
	{
?>

<button class="form-control" type="button" name="clientsingup" id="clientsingup"><a href="/signup">Signup<a></button>
<?php
	}
?>

<?php
	//check for sign up
	if ($passwordupdate == 1)
	{
?>

<button class="form-control" type="button" name="passwordreset" id="passwordreset">Update</button>
<?php
	}
?>




                         
