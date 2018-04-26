<?php
  	//load the header
    $this->load->view('www/header.php');
    $this->load->view('www/nav.php');
?>
    <div id="wrapper">
        <!-- Page Content -->
        <div id="page-content-wrapper">
            <div class="container-fluid">
                <h1>Signin</h1>
                <div class='row'>
                    <div class='col-md-4'>
                        <form>
                          Email:<br>
                          <input class="form-control formelement" type="text" name="email" id="email">
                          <span id='erroremail'></span><br>
                          Password:<br>
                          <input class="form-control formelement" type="password" name="password" id="password">
                          <span id='errorpassword'></span><br>
                          <?php
                              //load the buttons
                              $options['signin'] = 1;
                              $options['forgotpassword'] = 1;
                              $options['signup'] = 1;
                              $options['update'] = 0; 
                              $options['reset'] = 0;
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
 