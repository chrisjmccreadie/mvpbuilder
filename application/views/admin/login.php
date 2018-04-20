<?php
  	//load the header
    $this->load->view('admin/header.php');

?>
    <div id="wrapper">

        <!-- Sidebar -->
        <div id="sidebar-wrapper">
            <?php
  				//load the header
    			$this->load->view('admin/menu.php');
			?>     
        </div>

        <!-- Page Content -->
        <div id="page-content-wrapper">
            <div class="container-fluid">
                <h1>Login</h1>
                <div class='row'>
                    <div class='col-md-4'>
                        <form>
        				  Email:<br>
        				  <input class="form-control" type="text" name="email" id="email"><br>
        				  Password:<br>
        				  <input class="form-control" type="password" name="password" id="password"><br>
        				  <button class="form-control" type="button" name="login" id="login">login</button>
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
    $this->load->view('admin/footer.php');
?>
 