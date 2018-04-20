<?php
  	//load the header
    $this->load->view('template/header.php');

?>
    <div id="wrapper">

        <!-- Sidebar -->
        <div id="sidebar-wrapper">
            <ul class="sidebar-nav">
                <li class="sidebar-brand active" >
                    <!-- hold the title here -->
                 	  <a href="#" >Casinodaddy</a>
                </li>
                <li>
                    <a href="#">Current Hunt</a>
                </li>
                <li>
                    <a href="#">Past Hunts</a>
                </li>
            </ul>
        </div>

        <!-- Page Content -->
        <div id="page-content-wrapper">
            <div class="container-fluid">
                <h1>Current Hunt</h1>
                <p>
                Name : <?php echo $result->name;?> <br>
                Start Amount : <?php echo $result->StartAmount;?> <br>
                Start Date : <?php echo $result->dateStarted;?> <br>

                </p>
               
            </div>
        </div>
        <!-- /#page-content-wrapper -->

    </div>
    <!-- /#wrapper -->
<?php
	//load the footer
    $this->load->view('template/footer.php');
?>
 
