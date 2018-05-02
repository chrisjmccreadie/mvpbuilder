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
            <div class="container-fluid">
                <h1>table <?php echo $table;?></h1>
                <form id='addrecordform'>
                  <input type="hidden" class='form-control'  name="table" id='table' value="<?php echo $table;?>">

 				<?php 
                    //print_r($fields);
                    //print_r($foreigntabledata);	
                    //print_r($recordresult);
                    //loop through the fields
                    foreach ($fields as $item)
                    {
                        
                        if (($item->hideedit == 0) || ($item->hideedit == ''))
                        {
                            //print_r($item);
                            //check if we have a record result (this means its an edit form)
                            if ($recordresult == '')
                            {
                                //set the value to false
                                $item->value = '';
                            }
                            else
                            {
                                //we have data so lets se the value
                                foreach ($recordresult as $key => $value)
                                {
                                    //does it match
                                    if ($key == $item->name)
                                    {
                                        $item->value = $value;
                                    }
                                }  
                            }
                            //build the element
                            $fieldoutput = $this->generic_model->buildFormElement($item,$foreigntabledata);
                            //output the element
                            if ($fieldoutput != '')
                            {
                                echo "<div class='form-group' >";
                                echo $fieldoutput; 
                                echo "</div>";
                            }
                        }
                    }
                ?>

                <?php 
                	if ($recordresult == '')
                	{
                ?>
                 <button type="button" class="btn btn-primary btn-lg" id='addrecord'>Add</button>
                 <?php
                 	}
                 	else
                 	{
                 ?>
                    <button type="button" class="btn btn-primary btn-lg" id='updaterecord'>Update</button>
                 <?php
                 	}
                 ?>
                 </div>

 				</form>
               
            </div>
        </div>
        <!-- /#page-content-wrapper -->

    </div>
    <!-- /#wrapper -->
<?php
	//load the footer
    $this->load->view('admin/footer.php');
?>