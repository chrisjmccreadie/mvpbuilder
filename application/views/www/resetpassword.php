<?php
    //load the header
    $this->load->view('www/header.php');
    $this->load->view('www/nav.php');
?>
    <div id="wrapper">
        <!-- Page Content -->
        <div id="page-content-wrapper">
            <div class="container-fluid">
                <h1>Reset Password</h1>
                <div class='row'>
                    <div class='col-md-4'>
                        <?php
                          if ($id == 0)
                          {
                            echo 'hash not found not able to reset password';
                          }
                          else
                          {
                        ?>
                        <form id="passwordresetform" name="spasswordresetform"> 

                          <?php 
                            $field = (object) array('name' => 'password');
                            $html = $this->core_model->htmlInput($field);
                            echo $html;
                          
                          ?>

                          <?php
                              //load the buttons
                              $options['signin'] = 0;
                              $options['forgotpassword'] = 0;
                              $options['signup'] = 0;
                              $options['update'] = 0;
                              $options['reset'] = 0;
                              $options['passwordupdate'] = 1;
                              $this->load->view('www/signbuttons.php',$options);
                          ?>
                          <input type="hidden" class='form-control'  name="id" id='id' value="<?php echo $id;?>">
                          <?php
                            }
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