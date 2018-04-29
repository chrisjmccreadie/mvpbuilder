<?php
	//note (chris) this whole view may be to clever for it's own good and may have to be recoded for the sake of symentery
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
                <h1>table <?php echo $table;?></h1>
                
                
                <row>
                
               <input type="hidden" class='form-control'  name="table" id='table' value="<?php echo $table;?>">
				<table id="admintable" class="display" style="width:100%">
			        <thead>
			            <tr>
			            	<?php

			            		//loop through the fields to create the table headers
			            		foreach ($fields as $item)
	            				{
	            					//loop through each item pair
	            					foreach ($item as $key => $value)
	            					{
	            						//ignore the default from the main table as we do not want to show thiese are headers
	            						if (($key != 'primary_key') && ($key != 'default') && ($key != 'max_length') && ($key != 'htmltype'))
	            							echo "<th>$key</th>";

	            					}
	            					echo "<th>Action</th>";
	            					//end the loop
	            					break;
	            				}

			            	?>
					    </tr>
			        </thead>
			        <tbody>
			           	
	            	<?php
	            		//build some dropdowns arrays to create the meta look up tables

	            		//int dropdown
	            		$intselect = array("Please Select"=>"","Textfield"=>"textfield","Dropdown"=>"dropdown");
	            		//date dropdown
	            		$dateselect = array("Date"=>"Date");
	            		//varchar dropdown
	            		$varcharselect = array("Please Select"=>"","Textfield"=>"textfield","Dropdown"=>"dropdown","Image"=>"image");
	            		//teext dropdpwn
	            		$textselect = array("Please Select"=>"","Textarea"=>"textarea","WYSIWYG"=>"wysiwyg");
	            		//set a var to hold the selected type array (above)
	            		$typeselect = '';
	            		//loop through data
	            		foreach ($fields as $item)
	            		{	
	            			//print_r($item);
	            			//build the types
	            			//todo (chris) move this to switch
        					if ($item->type == "int")
        					{
        						//add a normal textfield
        						$typeselect = $intselect;
        						
        					}
        					if ($item->type == "date")
        					{
        						//add a normal textfield
        						$typeselect = $dateselect;
        						
        					}
        					if ($item->type == "varchar")
        					{
        						//add a normal textfield
        						$typeselect = $varcharselect;
        						
        					}
        					if ($item->type == "text")
        					{
        						//add a normal textfield
        						$typeselect = $textselect;
        						
        					}
	            	?>
	            		<tr>
		            		<?php

		            		

		            		//set a counter
		            		$i = 0;
		            		//loop through each item to render out to the table
	            			foreach ($item as $key => $value)
	            			{
	            				//print_r($item);

	            				//ignore the default from the main table as we do not want to show thiese are headers
	            				if (($key != 'primary_key') && ($key != 'default') && ($key != 'max_length') && ($key != 'htmltype'))
	            				{
	            					echo "<td>";
            						if ($i == 1)
            						{
            							//set the required field to the item type
            							$requiredselect = $item->type;
            							//check for it being and default to the type form the table and not the meta
            							if ($item->htmltype != '')
            							{
            								//set it the data we have in the meta table
            								$requiredselect = $item->htmltype;
            								
            							}
            							//check if it's an in from the master table and if so set the type to textfield
            							//note (chris) not sure if I like this one one bit we may be better off setting textfield to vars and into respectivetly.
            							//			   but this is the element type it is going into so it is tecincally correct.
            							if ($requiredselect == 'int')
            							{
            								$requiredselect = 'textfield';
            							}

										//outut the field type
										//note (chris) we may not require this            							
            							echo "(".$value.")";
            							//build the type select
            							echo "<select name='htmltype$item->name' id='htmltype$item->name'>";	
            							//loop through the option array            							
            							foreach ($typeselect as $selectkey => $selectvalue)
            							{
            								//check if the type matches the paired value and make it the selected field if so.
            								if ($selectvalue == $requiredselect)
            									echo "<option value='$selectvalue' selected>$selectkey</option>";
            								else
            									echo "<option value='$selectvalue' >$selectkey</option>";
            							}
            							echo "</select>";
            						}
            						else
            						{
            							//check if its required
            							if ($key == 'required')
	            						{
	            							if ($value == 1)
	            								echo "<input class='requiredcheck' type='checkbox' id='required$item->name' name='required$item->name' value='$value' checked='checked'>";
	            							else
	            								echo "<input  class='requiredcheck' type='checkbox' id='required$item->name' name='required$item->name' value='0' >";
	            						}
	            						else
	            						{
	            							echo $value; 
	            						}
	            						
            						}


	            						
	            					
	            					echo "</td>";
	            					$i++;
            					}
	            					
		            			
							}
							echo "<td><span class='savetablemeta' data-id='$item->name'><i class='fas fa-save ' ></i></span>";
							?>
							
	            		</tr>
					<?php
	           			}
	            	?>

			            	
			               
			           
			        </tbody>
			     </table>
                              <input type="hidden" class='form-control'  name="table" id='table' value="<?php echo $table;?>">

            </div>
        </div>
        <!-- /#page-content-wrapper -->

    </div>
    <!-- /#wrapper -->
<?php
	//load the footer
    $this->load->view('admin/footer.php');
?>