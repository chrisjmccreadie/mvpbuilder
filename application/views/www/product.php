<?php
  	//load the header
    $this->load->view('www/header.php');
    $this->load->view('www/nav.php');
?>
    <div id="wrapper">
        <!-- Page Content -->
        <div id="page-content-wrapper">
            <div class="container-fluid">
                <h1>Home Page</h1>
                <div class='row'>
	                <div class='col-md-4'>
	                {product}
	                <h5>Name :{name}</h5>
	                <h4>Start Date :{dateStarted}</h4>
	                <h4>Start Amount :£{StartAmount}</h4>
	                <p>Desc : {description}</p>
	        		{/product}
                </div>
                </div>
            </div>
        </div>
        <!-- /#page-content-wrapper -->

    </div>
    <!-- /#wrapper -->
<?php
	//load the footer
    $this->load->view('www/footer.php');
?>
 