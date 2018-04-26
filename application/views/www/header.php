<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Template site</title>
   <!-- Bootstrap core CSS -->
    <link href="<?php echo base_url();?>assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
     <!-- Bootstrap core CSS -->
    <link href="<?php echo base_url();?>assets/css/www.css" rel="stylesheet">

    <?php
    	if ($this->config->item('recaptcha') == 1)
    	{
    ?>
   	<script src='https://www.google.com/recaptcha/api.js'></script>
   	<?php
   		}
   	?>
</head>
<body>