<?php
  	//load the header
    $this->load->view('www/header.php');
    $this->load->view('www/nav.php');
?>
    <div id="wrapper">
        <!-- Page Content -->
        <div id="page-content-wrapper">
            <div class="container-fluid">
                <h1>My Account</h1>
                <div class='row'>
                    <div class='col-md-4'>
                        <form id="myaccountform" name="myaccountform"> 

                          <?php 
                          //print_r($this->config->item('clientloginexcludefields'));
                          //loop through the fields
                          foreach ($fields as $field)
                          {
                            //create tthe html element
                            $html = $this->core_model->htmlInput($field);
                            echo $html;
                          }
                          ?>
                          <?php
                              //load the buttons
                              $options['signin'] = 0;
                              $options['forgotpassword'] = 0;
                              $options['signup'] = 0;
                              $options['update'] = 1;
                              $options['reset'] = 0;
                              $options['passwordupdate'] = 0;
                           
                              $this->load->view('www/signbuttons.php',$options);
                          ?>
                          <input type="hidden" class='form-control'  name="id" id='id' value="<?php echo $this->session->id;?>">

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
 