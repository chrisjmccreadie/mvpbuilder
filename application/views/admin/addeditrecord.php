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

                	foreach ($fields as $item)
                	{
                        
                        //echo "<div class='form-group' >";
                        if ($recordresult == '')
                        {
                            $item->value = '';
                        }
                        else
                        {
                            foreach ($recordresult as $key => $value)
                            {
                                if ($key == $item->name)
                                {
                                    $item->value = $value;
                                }
                            }  
                        }
                        //print_r($item);
                        $fieldoutput = $this->generic_model->buildFormElement($item,$foreigntabledata);
                        if ($fieldoutput != '')
                        {
                            echo "<div class='form-group' >";
                            echo $fieldoutput; 
                            echo "</div>";
                        }
                        
                        //echo "</div>";
                        /*
                        exit;
                		//check to see if we have data for the field
                		$editdata = $item->default;
                		if ($recordresult != '')
                		{
                			echo  "<input type='hidden' class='form-control'  name='id' id='id' value='$recordresult->id'>";

                			foreach ($recordresult as $key => $value)
                			{
                				if ($item->name == $key)
                					$editdata = $value;
                			}
                		}

                		//check to see if we found the foreigntable.
                		$foreigndata = '';
                		foreach ($foreigntabledata as $item2)
                		{
                			foreach ($item2 as $key => $value) 
                			{
                				if ($key == $item->name)
                				{
                					//print_r($value);
                					$foreigndata = $value;
                				}
                			}
                			
                		}
                		//check the field type and it's not primary as this would be an auto increment. 
                		echo "<div class='form-group' >";
                		
                		if (($item->type == "int") && ($item->primary_key != 1) && ($item->name != 'archived'))
                		{
                			echo "<div class='row'>";
                			echo "<div class='col-md-1'><label for='$item->name'>$item->name:</label></div>";
                			//check if foreign data is not blank
                			if ($foreigndata == '')
                				echo "<div class='col-md-1'><input class='form-control'  type='text' name='$item->name' id='$item->name'  value='$editdata'></div>";
                			else
                			{
                				echo "<div class='col-md-4'>";
                				echo "<select class='form-control picker' data-live-search='true' name='$item->name' id='$item->name' >";
                				echo "<option value=''>Please  select</option>";
                				foreach ($foreigndata as $foreignitem)
                				{
                					$selected = '';
                					if ($foreignitem->id == $editdata)
                							$selected = 'selected';
									echo "<option value='$foreignitem->id' $selected>$foreignitem->name</option>";
								}
								echo "</select>";
								echo "<span id='error$item->name' class='selecterrors'></span>";
								echo "</div>";

                			}
                			echo "</div>";
                		}

                		//check the for varchar
                		if ($item->type == "varchar")
                		{
                			echo "<div class='row'>";
                			echo "<div class='col-md-1'><label for='$item->name'>$item->name:</label></div>";
                			echo "<div class='col-md-4'><input class='form-control' type='text' name='$item->name' id='$item->name' value='$editdata'></div>";
                			echo "</div>";
                			//echo "<input type='text' name='$item->name' id='$item->name'>";
                		}

                		//check for date
                		if ($item->type == "date")
                		{
                			echo "<div class='row'>";
                			echo "<div class='col-md-1'><label for='$item->name'>$item->name:</label></div>";
                			echo "<div class='col-md-4'><input class='datepicker form-control' data-date-format='yyyy/m/dd' name='$item->name' id='$item->name' value='$editdata'></div>";
                			echo "</div>";
                			///echo "<input class='datepicker' data-date-format='mm/dd/yyyy' name='$item->name' id='$item->name'>";
                		}

                		//check for date
                		if ($item->type == "text")
                		{
                			echo "<div class='row'>";
                			echo "<div class='col-md-1'><label for='$item->name'>$item->name:</label></div>";
                			echo "<div class='col-md-12'><textarea class='form-control' rows='5' name='$item->name' id='$item->name'></textarea></div>";
                			echo "</div>";
                			
                			//todo (chris) get working
       						//echo "<div id='editor'></div>";
                			//echo "<textarea class='form-control' rows='5' name='$item->name' id='$item->name'></textarea>";

                		}
                        echo "</div>";
                		*/
                		

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