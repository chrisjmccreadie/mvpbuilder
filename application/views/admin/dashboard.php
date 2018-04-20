<?php
  	//load the header
    $this->load->view('admin/header.php');

    //print_r($this->config->item('admin_table_list'));

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
                <h1>Dashboard</h1>
               
               
            </div>
        </div>
        <!-- /#page-content-wrapper -->

    </div>
    <!-- /#wrapper -->
<?php
	//load the footer
    $this->load->view('admin/footer.php');
?>
 