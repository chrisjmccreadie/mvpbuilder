<?php
  	//load the header
    $this->load->view('www/header.php');
    $this->load->view('www/nav.php');
?>
    <div id="wrapper">
        <!-- Page Content -->
        <div id="page-content-wrapper">
            <div class="container-fluid">
                <h1>Forgot Password</h1>
                <div class='row'>
                    <div class='col-md-4'>
                        <form id='forgotpasswordform'  name='forgotpasswordform'>
                          Email:<br>
                          <input class=" form-control" type="text" name="email" id="email">
            			  <span id='erroremail'></span>
                          <br>
                         <?php
                              //load the buttons
                              $options['signin'] = 0;
                              $options['forgotpassword'] = 0;
                              $options['signup'] = 0;
                              $options['update'] = 0;
                              $options['reset'] = 1;
                              $options['passwordupdate'] = 0;
                            
                              $this->load->view('www/signbuttons.php',$options);
                          ?>

                        </form>
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
 