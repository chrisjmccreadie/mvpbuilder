<?php
  	//load the header
    $this->load->view('www/header.php');
    $this->load->view('www/nav.php');
?>
    <div id="wrapper">
        <!-- Page Content -->
        <div id="page-content-wrapper">
            <div class="container-fluid">
                <h1>Search Results</h1>
                
                    <?php
                    	foreach ($searchresults as $item)
                    	{
                    		//remove the fields we do not want to show

                    ?>
                    <div class='row'>
                    	<div class='col-md-4'>
                    	<?php
                    		//set a urlsgement to send to the product page
                    		//note (chris) we colud move all this to parser class.
                    		$urlsegment = '';
                    		foreach ($item as $key => $value)
                    		{
                    			//check for a url segement
                    			//note (chris) we are assuming the first field in the search fields array is the most important, act accoridngly
                    			if ($urlsegment == '')
                    			{
                    				$urlsegment = $value;
                    				$urlsegment = str_replace(" ", "-", $urlsegment);
                    			}
                    			//print_r($item);

                    			//show the key
                    			//echo $key;
                    			//show the value
                    			//note (chris) this is a very simple rendering of the search results make this as complex as you wish
                    			echo "<br>";
                    			echo $value;
                    			
                
                    		}
                    		echo "<br>";
                    		echo "<a href='".base_url()."product/$urlsegment'>View</a>";
                    		
                    	?>
                    	 </div>	
                    </div>	
                    <?php
                    	}

                    ?>
                    
                
            </div>
        </div>
        <!-- /#page-content-wrapper -->

    </div>
    <!-- /#wrapper -->
<?php
	//load the footer
    $this->load->view('www/footer.php');
?>
 