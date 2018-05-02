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
                <h1>table <?php echo $table;?></h1>
                
                <?php
                if ($this->session->issuperadmin == 1)
                {
                ?>
                	<button>
                	<a href="/admin/table/<?php echo $table;?>/admin"><i class="fas fa-cog"></i> Table Admin</a>
                	</button>
                <?php
                }
			    				
				//check the user can add records
				if ($this->session->caninsert == 1)
				{
				?>
                <row>
                <button>
                	<a href="/admin/table/<?php echo $table;?>/add"><i class="fas fa-plus-square"></i> Add Record</a>
                </button>
                </row>
                <?php
            	}
            	?>
               <input type="hidden" class='form-control'  name="table" id='table' value="<?php echo $table;?>">
				<table id="admintable" class="display" style="width:100%">
			        <thead>
			            <tr>
			            	<?php
			            		//loop throuh the fields and output
			            		//print_r($fields);
			            		//check this against the field list

			            		foreach ($fields as $field)
			            		{
			            			//if ($field->hideview == 0)
			            				echo " <th>".$field->name."</th>";
			            			
			            			
			            		}
			            	?>
			            	<th>Action</th>
			               
			                
			            </tr>
			        </thead>
			        <tbody>
			           
			            	<?php
			            		//loop through data
			            		//print_r($result);
			            		foreach ($result as $item)
			            		{

					            	echo "<tr id='row$item->id'>";
					            	//loop through each item.
					            	//print_r($item);
			            			foreach ($item as $key => $value)
			            			{


			            				//loop through the foreign data array
				                		foreach ($foreigntabledata as $item2)
				                		{
				                			//loop through the individual tables
				                			foreach ($item2 as $key2 => $value2) 
				                			{
				                				//check if the fields match
				                				if ($key2 == $key)
				                				{

				                					//loop through the foreign data values
				                					foreach ($value2 as $item3)
			            							{

			            								//check if it matches
			            								if ($item3->id == $value)
			            									$value = $item3->name;
				                					}
				                				}
				                			}
				                		}

				                		//check for an image
					            		$result = $this->generic_model->getImageById($table, $key,$item->id);
					            		//print_r($result);
					            		if (is_object($result))
					            		{
					            			$value = "<img src='https://process.filestackapi.com/resize=width:50,height:50,fit:scale/".$result->handle."'/>";
					            		}
			            					echo "<td>$value</td>";
			            			}
			            			echo "<td>";
			            			if ($this->session->canedit == 1)
			            				echo "<a href=\"/admin/table/$table/edit/$item->id\"><i class=\"fas fa-edit\"></i> </a>";
			            			if ($this->session->candelete == 1)
			            				echo "<a href=\"javascript:deletetablerecord($item->id)\"><i class=\"fas fa-trash\"></i> </a>";
			            			
			            			echo "</td>";
			            			echo "</tr>";
			            		}

			            	?>
			            	
			               
			           
			        </tbody>
			     </table>
               
            </div>
        </div>
        <!-- /#page-content-wrapper -->

    </div>
    <!-- /#wrapper -->
<?php
	//load the footer
    $this->load->view('admin/footer.php');
?>